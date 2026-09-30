#!/usr/bin/env python3
"""P1c (A9) — near-duplicate scan over all CONTENT files.

Reads files directly from the FILESYSTEM (same as validate_roadmap.py's sha256_of() and
the same as every extraction agent's Read tool) — NOT from the pinned commit via a git
blob. The pinned commit in 00-CORPUS-SNAPSHOT.txt was recorded as a reference marker at
P0 time (per the protocol's own A4 pseudocode: `sha256 = hash(path)` reads the working
tree directly); the working tree has since advanced past that commit for files that were
present on disk but not yet committed at P0 time. Reading via git-at-commit was tried
first and found to disagree with what P0/extraction agents actually saw (confirmed via a
missing-blob check on S2926, the roadmap file itself, which post-dates the pinned
commit) — using the filesystem is what keeps this scan consistent with the rest of
Phase 1. As a safety net, this script re-verifies every file's sha256 against the
roadmap's recorded sha256 and reports any drift rather than silently trusting it.

Method: k-word-shingle MinHash + LSH banding to find candidate pairs at scale, then exact
Jaccard similarity on the shingle sets for every candidate pair. This is mechanical
similarity detection only (R13) — it never interprets meaning; P2/P3 agents do that.

Thresholds (documented here since the protocol does not fix numbers):
  similarity >= 0.85            -> NEAR-DUPLICATE
  0.40 <= similarity < 0.85     -> PARTIAL-OVERLAP
  similarity < 0.40             -> not reported
"""
import hashlib
import json
import os
import re
import subprocess
import random

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR = os.path.join(REPO_ROOT, "docs/knowledgeos/chronological-read")

SHINGLE_K = 8          # words per shingle
NUM_PERM = 64
BANDS = 16
ROWS = NUM_PERM // BANDS
NEAR_DUP_THRESHOLD = 0.85
PARTIAL_OVERLAP_THRESHOLD = 0.40
MERSENNE_PRIME = (1 << 61) - 1
MAX_HASH = (1 << 64) - 1


def load_jsonl(path):
    return [json.loads(l) for l in open(path, encoding="utf-8") if l.strip()]


def shingle_hash(s):
    return int.from_bytes(hashlib.blake2b(s.encode("utf-8"), digest_size=8).digest(), "big")


def normalize_and_shingle(text):
    text = text.lower()
    text = re.sub(r"[^a-z0-9\s]", " ", text)
    words = text.split()
    if len(words) < SHINGLE_K:
        return set()
    shingles = set()
    for i in range(len(words) - SHINGLE_K + 1):
        shingles.add(shingle_hash(" ".join(words[i:i + SHINGLE_K])))
    return shingles


def main():
    files = load_jsonl(os.path.join(CR, "02-FILES.jsonl"))
    roadmap = {r["source_id"]: r for r in load_jsonl(os.path.join(CR, "00-ROADMAP-VALIDATED.jsonl"))}
    content_files = [f for f in files if f["status"] == "CONTENT"]
    print(f"Candidate CONTENT files: {len(content_files)}")

    shingle_sets = {}
    skipped_binary = []
    skipped_missing = []
    sha_drift = []
    for f in content_files:
        abs_path = os.path.join(REPO_ROOT, f["path"])
        if not os.path.isfile(abs_path):
            skipped_missing.append(f["source_id"])
            continue
        with open(abs_path, "rb") as fh:
            raw = fh.read()
        actual_sha = hashlib.sha256(raw).hexdigest()
        recorded_sha = roadmap.get(f["source_id"], {}).get("sha256")
        if recorded_sha and actual_sha != recorded_sha:
            sha_drift.append(f["source_id"])
        try:
            text = raw.decode("utf-8")
        except UnicodeDecodeError:
            skipped_binary.append(f["source_id"])
            continue
        s = normalize_and_shingle(text)
        if s:
            shingle_sets[f["source_id"]] = s
    print(f"Shingle sets built: {len(shingle_sets)}  "
          f"(skipped binary/undecodable: {len(skipped_binary)}, "
          f"skipped missing-on-disk: {len(skipped_missing)}, "
          f"sha256 drift vs roadmap: {len(sha_drift)})")
    if sha_drift:
        print(f"  WARNING sha256 drift for: {sha_drift[:20]}")
    if skipped_missing:
        print(f"  WARNING missing on disk: {skipped_missing[:20]}")

    rng = random.Random(42)
    perms = [(rng.randrange(1, MERSENNE_PRIME), rng.randrange(0, MERSENNE_PRIME)) for _ in range(NUM_PERM)]

    signatures = {}
    for sid, shingles in shingle_sets.items():
        sig = [MAX_HASH] * NUM_PERM
        for h in shingles:
            for i, (a, b) in enumerate(perms):
                v = ((a * h + b) % MERSENNE_PRIME) & MAX_HASH
                if v < sig[i]:
                    sig[i] = v
        signatures[sid] = sig
    print(f"MinHash signatures computed for {len(signatures)} files")

    buckets = {}
    for sid, sig in signatures.items():
        for b in range(BANDS):
            band_tuple = tuple(sig[b * ROWS:(b + 1) * ROWS])
            key = (b, band_tuple)
            buckets.setdefault(key, []).append(sid)

    candidate_pairs = set()
    for key, sids in buckets.items():
        if len(sids) < 2:
            continue
        sids_sorted = sorted(sids)
        for i in range(len(sids_sorted)):
            for j in range(i + 1, len(sids_sorted)):
                candidate_pairs.add((sids_sorted[i], sids_sorted[j]))
    print(f"Candidate pairs from LSH banding: {len(candidate_pairs)}")

    results = []
    for a, b in candidate_pairs:
        sa, sb = shingle_sets[a], shingle_sets[b]
        inter = len(sa & sb)
        union = len(sa | sb)
        sim = inter / union if union else 0.0
        if sim >= PARTIAL_OVERLAP_THRESHOLD:
            kind = "NEAR-DUPLICATE" if sim >= NEAR_DUP_THRESHOLD else "PARTIAL-OVERLAP"
            results.append({"a": a, "b": b, "similarity": round(sim, 4), "kind": kind})

    results.sort(key=lambda r: -r["similarity"])
    out_path = os.path.join(CR, "08-OVERLAP-REGISTER.jsonl")
    with open(out_path, "w", encoding="utf-8") as fh:
        for r in results:
            fh.write(json.dumps(r, ensure_ascii=False) + "\n")

    near = sum(1 for r in results if r["kind"] == "NEAR-DUPLICATE")
    partial = sum(1 for r in results if r["kind"] == "PARTIAL-OVERLAP")
    print(f"OK: {out_path} written — {len(results)} pairs ({near} NEAR-DUPLICATE, {partial} PARTIAL-OVERLAP)")
    print(f"Thresholds used: NEAR-DUPLICATE >= {NEAR_DUP_THRESHOLD}, "
          f"PARTIAL-OVERLAP >= {PARTIAL_OVERLAP_THRESHOLD}")


if __name__ == "__main__":
    main()
