#!/usr/bin/env python3
"""P3b S2b IDENTITY RESOLUTION — Appendix A.3b of the frozen P3b operating protocol v1.6.5 (G-LOG-0012);
decisions G-LOG-0009 (D-a, D-b, D-d(i), D-e, D-g) and G-LOG-0011 (strict stasis rule).

One manifest row per CONTENT record of 02-FILES.jsonl, in source_id order, with the fields
  source_id · historical_path · current_path · recorded_commit · content_sha256 · blob_spec_type · blob_spec ·
  content_identity · historical_linkage · linkage_evidence · search_eligibility
Rules (A.3b, in order):
  1 P0 row alignment. If no location holds, at historical_path, bytes whose sha256 equals P0's hash, and P0's hash
    equals the sha256 of another census row's content, the row is misaligned. Only rows on the human-accepted
    exception list (D-b: S2344, S2345, S2346) get PATH-CONTENT-P0-ROW-MISALIGNED, with identity sha256 = the content at
    historical_path in the recorded commit. Any other misalignment → UNRESOLVED (never auto-accepted).
  2 P0-hash uniqueness among CONTENT rows; otherwise UNRESOLVED.
  3 Blob resolution: recorded commit → unique commit with the recorded string's 36-char prefix (only if the recorded
    string is not an object) → every git version of historical_path → HEAD → working tree. A git location gives
    GIT-OBJECT, blob_spec "<full commit>:<historical_path>". Content with no git copy is copied to
    audit-p3b/preserved-content/sha256/<sha256><extension>, re-read and verified byte-for-byte: PRESERVED-AUDIT-COPY.
    current_path is set for every row by a working-tree sha256 search.
  4 historical_linkage = STASIS-OBSERVED iff a working-tree file holds the identity bytes AND its mtime equals the
    file_mtime of the record's own P0 row (for an exception row: of the S2344–S2346 P0 row whose sha256 equals the
    identity sha256). Nothing else qualifies. Otherwise STASIS-UNOBSERVABLE.
  5 search_eligibility = BINARY iff a NUL byte occurs in the first 8,192 bytes, else TEXT. Recorded, never used to
    exclude.
  6 contrary evidence: a known P1 record describing content absent from the identity bytes → UNRESOLVED. Known cases:
    none (KNOWN_CONTRARY is empty; the calibration found only positive discriminating evidence, S2880).
Gate for PASS: zero UNRESOLVED rows and every verification target met.

Implementation decisions (stated for review):
  a. Working-tree scope for the sha256 search: every regular file under docs/ (not following symlinks, skipping .git).
     Every census path lies under docs/knowledgeos/ (asserted).
  b. current_path, when several working-tree files hold the identity bytes: first a file whose mtime equals the rule-4
     comparand (historical_path preferred, then lexicographic order); else historical_path if it matches; else the
     lexicographically first match. null if none.
  c. "Every git version of historical_path" = the commits listed by `git log --all --full-history --format=%H -- path`,
     in that order; the first matching blob wins.
  d. "Another census row's content" (rule 1) = the bytes at that row's historical_path in its recorded commit.
  e. A.3b names 01-BATCH-MANIFEST.jsonl as an input; after G-LOG-0011 it is not used by any rule. It is hashed and
     recorded, not read.
  f. An exception-list row whose content does match P0 at historical_path → FAIL (the list would be stale).
  g. Verification targets (from the calibration and G-LOG-0011; a mismatch → FAIL, i.e. drift is escalated, never
     absorbed): CONTENT 2,767; P0-HASH 2,764; PATH-CONTENT-P0-ROW-MISALIGNED 3; UNRESOLVED 0; STASIS-OBSERVED 2,766;
     STASIS-UNOBSERVABLE 1 (S2809); GIT-OBJECT 2,759; PRESERVED-AUDIT-COPY 8. search_eligibility counts are reported,
     not targeted (any binary CONTENT file counts, e.g. PDF, not only .pyc).
  h. Dry run: preserved copies are written to a temporary directory outside the repository and verified there; the
     blob_spec still names the audit-p3b/ path; nothing is written under the repository.

Gate on inputs: S2 PASS (P3B-TIERS.jsonl header result PASS and body hash = header); 02-FILES.jsonl and
00-ROADMAP-VALIDATED.jsonl unchanged since S0; frozen protocol v1.6.5 hash.

Output (only on PASS, not with --dry-run): P3B-IDENTITY-MANIFEST.jsonl — line 1 {"header": {...§19.5...}}; then one
canonical-JSON row per CONTENT record. output_sha256 = sha256 of the body bytes. Canonical JSON =
json.dumps(obj, sort_keys=True, separators=(",", ":"), ensure_ascii=False), UTF-8.

Usage:  python3 p3b_s2b_identity_resolution.py [--dry-run [--emit=<path outside the repository>]]
        --emit writes the would-be manifest (header + body) for review; allowed only with --dry-run
Exit:   0 PASS · 1 FAIL (stop; human escalation, §22) · 2 usage error
"""
import collections
import datetime
import hashlib
import json
import os
import platform
import shutil
import subprocess
import sys
import tempfile

PROTOCOL = "prompts/20260924_1505_p3b-phase1-continuation-protocol-v1.6.5.md"
PROTOCOL_SHA256 = "b70fc216ea6a1cde5a8efbfc1ea2ac1397f18b519bf1c04c292a44d7415ee3f7"
EXCEPTIONS = ("S2344", "S2345", "S2346")          # G-LOG-0009 D-b
KNOWN_CONTRARY = {}                               # A.3b rule 6: none known
EXPECTED = {"content_records": 2767, "P0-HASH": 2764, "PATH-CONTENT-P0-ROW-MISALIGNED": 3, "UNRESOLVED": 0,
            "STASIS-OBSERVED": 2766, "STASIS-UNOBSERVABLE": 1, "GIT-OBJECT": 2759, "PRESERVED-AUDIT-COPY": 8}
BINARY_PROBE = 8192

REPO_ROOT = subprocess.check_output(["git", "rev-parse", "--show-toplevel"], text=True).strip()
CR_REL = "docs/knowledgeos/chronological-read"
CR = os.path.join(REPO_ROOT, CR_REL)
IN_FILES = "02-FILES.jsonl"
IN_ROADMAP = "00-ROADMAP-VALIDATED.jsonl"
IN_BATCH = "01-BATCH-MANIFEST.jsonl"
IN_TIERS = "P3B-TIERS.jsonl"
IN_PREFLIGHT = "P3B-PREFLIGHT.json"
OUT = "P3B-IDENTITY-MANIFEST.jsonl"
PRESERVE_REL = CR_REL + "/audit-p3b/preserved-content/sha256"
SCAN_ROOT = "docs"


def canon(obj):
    return json.dumps(obj, sort_keys=True, separators=(",", ":"), ensure_ascii=False)


def sha256_bytes(b):
    return hashlib.sha256(b).hexdigest()


def sha256_file(rel):
    with open(os.path.join(CR, rel), "rb") as f:
        return sha256_bytes(f.read())


def git(*args):
    return subprocess.run(["git", "-C", REPO_ROOT, *args], capture_output=True, text=True)


def to_epoch(v):
    s = str(v)
    if s.isdigit():
        return int(s)
    t = datetime.datetime.fromisoformat(s.replace("Z", "+00:00"))
    return int((t if t.tzinfo else t.replace(tzinfo=datetime.timezone.utc)).timestamp())


class Blobs:
    """git cat-file --batch reader; blob(spec) → bytes or None."""
    def __init__(self):
        self.p = subprocess.Popen(["git", "-C", REPO_ROOT, "cat-file", "--batch"],
                                  stdin=subprocess.PIPE, stdout=subprocess.PIPE)
        self.cache = {}

    def blob(self, spec):
        if spec in self.cache:
            return self.cache[spec]
        self.p.stdin.write((spec + "\n").encode("utf-8"))
        self.p.stdin.flush()
        line = self.p.stdout.readline().decode("utf-8").rstrip("\n")
        data = None
        # "<spec> missing" / "<spec> ambiguous" (the spec may contain spaces) carry no content;
        # otherwise the reply is "<oid> <type> <size>" followed by the content
        if not (line.endswith(" missing") or line.endswith(" ambiguous")):
            _, otype, size = line.rsplit(" ", 2)
            content = self.p.stdout.read(int(size))
            self.p.stdout.read(1)
            if otype == "blob":
                data = content
        self.cache[spec] = data
        return data


def scan_working_tree():
    by_sha = collections.defaultdict(list)
    for root, dirs, files in os.walk(os.path.join(REPO_ROOT, SCAN_ROOT)):
        dirs[:] = sorted(d for d in dirs if d != ".git")
        for fn in files:
            p = os.path.join(root, fn)
            if os.path.islink(p) or not os.path.isfile(p):
                continue
            with open(p, "rb") as f:
                h = sha256_bytes(f.read())
            by_sha[h].append((os.path.relpath(p, REPO_ROOT), int(os.stat(p).st_mtime)))
    return by_sha


def main():
    dry_run = "--dry-run" in sys.argv[1:]
    emit = next((a.split("=", 1)[1] for a in sys.argv[1:] if a.startswith("--emit=")), None)
    unknown = [a for a in sys.argv[1:] if a != "--dry-run" and not a.startswith("--emit=")]
    if unknown:
        print(f"usage error: unknown arguments {unknown}", file=sys.stderr)
        return 2
    if emit is not None and (not dry_run or os.path.abspath(emit).startswith(REPO_ROOT + os.sep)):
        print("usage error: --emit requires --dry-run and a path outside the repository", file=sys.stderr)
        return 2
    failures = []

    # ---- gate ----
    try:
        with open(os.path.join(CR, IN_TIERS), "rb") as f:
            tiers_raw = f.read()
        with open(os.path.join(CR, IN_PREFLIGHT), encoding="utf-8") as f:
            pre = json.load(f)
    except OSError as e:
        print(f"FAIL: missing S0/S2 artifact ({type(e).__name__}) — S0 and S2 must PASS before S2b", file=sys.stderr)
        return 1
    t_lines = tiers_raw.split(b"\n")
    t_header = json.loads(t_lines[0])["header"]
    t_body_sha = sha256_bytes(b"\n".join(l for l in t_lines[1:] if l) + b"\n")
    if t_header.get("result") != "PASS":
        failures.append("S2 P3B-TIERS.jsonl result is not PASS")
    if t_body_sha != t_header.get("output_sha256"):
        failures.append("P3B-TIERS.jsonl body hash differs from its header")
    s0_hashes = pre.get("body", {}).get("reference_file_sha256", {})
    input_hashes = {}
    for rel in (IN_FILES, IN_ROADMAP):
        h = sha256_file(rel)
        input_hashes[rel] = h
        if s0_hashes.get(f"{CR_REL}/{rel}") != h:
            failures.append(f"{rel} is not identical to its S0-recorded hash")
    for rel in (IN_BATCH, IN_TIERS, IN_PREFLIGHT):
        input_hashes[rel] = sha256_file(rel)
    proto_sha = sha256_file(PROTOCOL)
    if proto_sha != PROTOCOL_SHA256:
        failures.append("frozen protocol hash mismatch")

    # ---- inputs ----
    with open(os.path.join(CR, IN_ROADMAP), encoding="utf-8") as f:
        road = {r["source_id"]: r for r in (json.loads(l) for l in f if l.strip())}
    with open(os.path.join(CR, IN_FILES), encoding="utf-8") as f:
        content = [r for r in (json.loads(l) for l in f if l.strip()) if r["status"] == "CONTENT"]
    content.sort(key=lambda r: r["source_id"])
    outside = [r["source_id"] for r in content if not r["path"].startswith("docs/knowledgeos/")]
    if outside:
        failures.append(f"{len(outside)} census paths outside docs/knowledgeos/ (first: {outside[:3]})")
    head = git("rev-parse", "HEAD").stdout.strip()
    blobs = Blobs()
    wt = scan_working_tree()
    p0_count = collections.Counter(road[r["source_id"]]["sha256"] for r in content)
    commit_ok = {}

    def is_commit(c):
        if c not in commit_ok:
            commit_ok[c] = git("cat-file", "-e", c + "^{commit}").returncode == 0
        return commit_ok[c]

    row_content_sha = {}

    def content_at_recorded(r):
        sid = r["source_id"]
        if sid not in row_content_sha:
            b = blobs.blob(f"{r['commit']}:{r['path']}") if is_commit(r["commit"]) else None
            row_content_sha[sid] = sha256_bytes(b) if b is not None else None
        return row_content_sha[sid]

    def git_locations(r):
        """Yield (full_commit, bytes) at historical_path in A.3b rule-3 order (git part)."""
        path, rc = r["path"], r["commit"]
        if is_commit(rc):
            yield git("rev-parse", rc + "^{commit}").stdout.strip(), blobs.blob(f"{rc}:{path}")
        else:
            upc = git("rev-parse", "--verify", "-q", rc[:36] + "^{commit}")
            if upc.returncode == 0:
                c = upc.stdout.strip()
                yield c, blobs.blob(f"{c}:{path}")
        for c in git("log", "--all", "--full-history", "--format=%H", "--", path).stdout.split():
            yield c, blobs.blob(f"{c}:{path}")
        yield head, blobs.blob(f"{head}:{path}")

    rows, preserved, tmpdir = [], [], None
    for r in content:
        sid, path = r["source_id"], r["path"]
        p0 = road[sid]["sha256"]
        identity, cls, spec_type, spec, data = None, None, None, None, None
        # rules 1 and 3: find P0's bytes at historical_path (git, then working tree)
        for c, b in git_locations(r):
            if b is not None and sha256_bytes(b) == p0:
                identity, spec_type, spec, data = p0, "GIT-OBJECT", f"{c}:{path}", b
                break
        wt_any = [p for p, _ in wt.get(p0, [])]
        if identity is None and path in wt_any:                  # working tree at historical_path
            identity, src = p0, path
        if identity is not None and sid in EXCEPTIONS:
            failures.append(f"{sid} is on the exception list but P0's hash matches at its historical_path")
        other = set()
        if identity is None:                                     # rule 1 before rule 3's "any file" fallback
            other = {s for s, h in ((x["source_id"], content_at_recorded(x)) for x in content) if h == p0 and s != sid}
            if not other and wt_any:                             # rule 3: any working-tree file with P0's hash
                identity, src = p0, sorted(wt_any)[0]
        if identity is not None and spec_type is None:           # no git copy: preserve the working-tree bytes
            with open(os.path.join(REPO_ROOT, src), "rb") as f:
                data = f.read()
            spec_type, spec = "PRESERVED-AUDIT-COPY", f"{PRESERVE_REL}/{p0}{os.path.splitext(path)[1]}"
        if identity is not None:
            cls = "P0-HASH"
        else:
            rec_sha = content_at_recorded(r)
            if other and sid in EXCEPTIONS and rec_sha is not None:
                identity, cls = rec_sha, "PATH-CONTENT-P0-ROW-MISALIGNED"
                c = git("rev-parse", r["commit"] + "^{commit}").stdout.strip()
                spec_type, spec, data = "GIT-OBJECT", f"{c}:{path}", blobs.blob(f"{r['commit']}:{path}")
            else:
                cls = "UNRESOLVED"
        # rule 2
        if cls == "P0-HASH" and p0_count[p0] > 1:
            cls = "UNRESOLVED"
        # rule 6
        if sid in KNOWN_CONTRARY:
            cls = "UNRESOLVED"
        # rule 4
        if cls == "PATH-CONTENT-P0-ROW-MISALIGNED":
            p0_row = next((s for s in EXCEPTIONS if road[s]["sha256"] == identity), None)
        else:
            p0_row = sid
        comparand = to_epoch(road[p0_row]["file_mtime"]) if p0_row else None
        matches = sorted(wt.get(identity, [])) if identity else []
        equal = [p for p, m in matches if m == comparand]
        if equal:
            current = path if path in equal else equal[0]
        elif any(p == path for p, _ in matches):
            current = path
        else:
            current = matches[0][0] if matches else None
        cur_mtime = next((m for p, m in matches if p == current), None)
        linkage = "STASIS-OBSERVED" if equal and cls != "UNRESOLVED" else "STASIS-UNOBSERVABLE"
        # rule 5
        eligibility = ("BINARY" if b"\x00" in data[:BINARY_PROBE] else "TEXT") if data is not None else None
        if data is not None and sha256_bytes(data) != identity:
            failures.append(f"{sid}: resolved bytes do not hash to the identity sha256")
        if spec_type == "PRESERVED-AUDIT-COPY":
            preserved.append((sid, spec, data, identity))
        rows.append({"source_id": sid, "historical_path": path, "current_path": current, "recorded_commit": r["commit"],
                     "content_sha256": identity, "blob_spec_type": spec_type, "blob_spec": spec,
                     "content_identity": cls, "historical_linkage": linkage,
                     "linkage_evidence": {"p0_row": p0_row, "p0_file_mtime": comparand, "working_tree_mtime": cur_mtime},
                     "search_eligibility": eligibility})

    # ---- preserved copies (rule 3), verified byte-for-byte ----
    if preserved:
        dest_root = tempfile.mkdtemp(prefix="p3b-s2b-dryrun-") if dry_run else REPO_ROOT
        for sid, spec, data, identity in preserved:
            dest = os.path.join(dest_root, spec)
            os.makedirs(os.path.dirname(dest), exist_ok=True)
            if os.path.exists(dest):
                with open(dest, "rb") as f:
                    if sha256_bytes(f.read()) != identity:
                        failures.append(f"{sid}: an existing audit copy at {spec} has different bytes")
                        continue
            else:
                with open(dest, "wb") as f:
                    f.write(data)
            with open(dest, "rb") as f:
                if f.read() != data:
                    failures.append(f"{sid}: audit copy failed byte-for-byte verification")
        tmpdir = dest_root if dry_run else None

    counts = collections.Counter()
    for x in rows:
        counts[x["content_identity"]] += 1
        counts[x["historical_linkage"]] += 1
        counts[x["blob_spec_type"] or "NO-BLOB"] += 1
        counts["eligibility:" + str(x["search_eligibility"])] += 1
    observed = {"content_records": len(rows), **{k: counts.get(k, 0) for k in EXPECTED if k != "content_records"}}
    for k, v in EXPECTED.items():
        if observed[k] != v:
            failures.append(f"{k}: expected {v}, observed {observed[k]}")
    unresolved = [x["source_id"] for x in rows if x["content_identity"] == "UNRESOLVED"]
    unobservable = [x["source_id"] for x in rows if x["historical_linkage"] == "STASIS-UNOBSERVABLE"]

    ok = not failures
    body_bytes = ("\n".join(canon(x) for x in rows) + "\n").encode("utf-8")
    script_path = os.path.abspath(__file__)
    header = {
        "artifact": OUT,
        "script_name": "p3b_s2b_identity_resolution.py",
        "script_version": git("hash-object", script_path).stdout.strip(),
        "script_committed_unmodified": git("status", "--porcelain", "--", script_path).stdout.strip() == "",
        "protocol": PROTOCOL, "protocol_sha256_observed": proto_sha,
        "parameters": {"exceptions": list(EXCEPTIONS), "known_contrary": sorted(KNOWN_CONTRARY),
                       "working_tree_scan_root": SCAN_ROOT, "binary_probe_bytes": BINARY_PROBE,
                       "preserve_root": PRESERVE_REL, "expected": EXPECTED},
        "seed": None,
        "head_commit": head,
        "input_hashes": input_hashes,
        "s2_link": {"tiers_output_sha256": t_body_sha},
        "counts": {**observed, "search_eligibility": {k.split(":", 1)[1]: v for k, v in counts.items()
                                                      if k.startswith("eligibility:")}},
        "unresolved": unresolved, "stasis_unobservable": unobservable,
        "result": "PASS" if ok else "FAIL",
        "failures": failures,
        "output_sha256": sha256_bytes(body_bytes),
        "python_version": platform.python_version(), "platform": platform.platform(),
        "run_timestamp_utc": datetime.datetime.now(datetime.timezone.utc).strftime("%Y-%m-%dT%H:%M:%SZ"),
    }

    print("S2b IDENTITY RESOLUTION: " + " · ".join(f"{k} {v}" for k, v in observed.items())
          + f" · eligibility {header['counts']['search_eligibility']}")
    print(f"  STASIS-UNOBSERVABLE: {unobservable} · UNRESOLVED: {unresolved} · preserved copies: {len(preserved)}"
          + (f" (dry run, verified in {tmpdir})" if tmpdir else ""))
    print(f"  body output_sha256 {header['output_sha256']}")
    for f_ in failures:
        print(f"  FAIL: {f_}")
    if ok and not dry_run:
        with open(os.path.join(CR, OUT), "wb") as f:
            f.write((canon({"header": header}) + "\n").encode("utf-8") + body_bytes)
        print(f"wrote {CR_REL}/{OUT}")
    if emit is not None:
        with open(emit, "wb") as f:
            f.write((canon({"header": header}) + "\n").encode("utf-8") + body_bytes)
        print(f"emitted the would-be manifest to {emit} (review only)")
    if tmpdir:
        shutil.rmtree(tmpdir)
    print("S2b RESULT:", "PASS" if ok else "FAIL — STOP; human escalation (§22)")
    return 0 if ok else 1


if __name__ == "__main__":
    sys.exit(main())
