#!/usr/bin/env python3
"""IV-1a (G-LOG-0095): VERIFIED re-basing of stage-1 hit offsets to the raw decoded coordinate that the FROZEN
`p3b_s5_r5.binary_preclassify` expects. An execution-layer adapter: it neither changes the classifier nor Freeze 1.

Coordinates (from `p3b_s3_mechanical_prep`):
  txt  = decode(b, "utf-8", errors="replace")          ← the classifier's coordinate (via char_to_byte_offsets)
  base = latex_normalize(NFKC(txt))  = norm_base(txt)  ← single-character terms are offsets here
  cf   = base.casefold()                                ← multi-character terms are offsets here (terms stored casefolded)

Method (sound by construction; every uncertainty is reported, never guessed):
  1. cf → base per character (casefold is checked to be context-free on this text); a cf offset inside a multi-char
     casefold expansion has no base start → UNVERIFIED.
  2. base ← txt through a CHUNKING at split characters, accepted only if the chunk-wise normalization concatenates to
     exactly the stage-1 base text (finest level that passes; coarser levels on failure).
  3. inside the hit's chunk: raw start j0 and end j1 with norm(chunk[:j0]) = the normalized prefix at the hit start,
     norm(chunk[:j1]) = the prefix at the hit end, and norm(chunk[j0:j1]) = the hit text → VERIFIED.
  Anything else (crossing chunks, no consistent start, a chunk longer than MAX_CHUNK, decoder mismatch, a term not at
  its offset) → UNVERIFIED, and the file's candidate is HUMAN-REVIEW (never FALSE-HIT).
No corpus I/O here: pure functions over bytes supplied by the caller.
"""
import codecs
import importlib.util
import os

_HERE = os.path.dirname(os.path.abspath(__file__))


def _load(name):
    s = importlib.util.spec_from_file_location(name, os.path.join(_HERE, name + ".py"))
    m = importlib.util.module_from_spec(s)
    s.loader.exec_module(m)
    return m


r5 = _load("p3b_s5_r5")
s3 = _load("p3b_s3_mechanical_prep")
MAX_CHUNK = 4096
SPLIT_LEVELS = (frozenset(" \t\r\n\x00�"), frozenset("\n\x00�"), frozenset("\n"), frozenset())


def decoder_consistent(b):
    """The classifier's incremental decoder must produce exactly the one-shot decode (same characters, same count)."""
    dec = codecs.getincrementaldecoder("utf-8")(errors="replace")
    inc = "".join(dec.decode(b[i:i + 1]) for i in range(len(b))) + dec.decode(b"", final=True)
    return inc == b.decode("utf-8", errors="replace")


def _chunks(txt, split):
    bounds = [0] + [i for i in range(1, len(txt)) if txt[i] in split or txt[i - 1] in split] + [len(txt)]
    return [(bounds[k], bounds[k + 1]) for k in range(len(bounds) - 1) if bounds[k] < bounds[k + 1]]


def _chunking(txt, base):
    for level, split in enumerate(SPLIT_LEVELS):
        spans = _chunks(txt, split) if txt else []
        out, a = [], 0
        for r0, r1 in spans:
            n = s3.norm_base(txt[r0:r1])
            out.append((r0, r1, a, a + len(n), n))
            a += len(n)
        if "".join(x[4] for x in out) == base:
            return level, out
    return None, None


def rebase(b, hits):
    """hits: [(stage-1 offset, term)] → {"file_status", "chunk_level", "hits": [...]}, one entry per input hit."""
    txt = b.decode("utf-8", errors="replace")
    base = s3.norm_base(txt)
    cf = base.casefold()
    per = []
    file_reason = None
    if not decoder_consistent(b):
        file_reason = "decoder mismatch (incremental ≠ one-shot decode)"
    cf_start, pos = [], 0
    for ch in base:
        cf_start.append(pos)
        pos += len(ch.casefold())
    if file_reason is None and pos != len(cf) or "".join(ch.casefold() for ch in base) != cf:
        file_reason = file_reason or "casefold is not character-local on this text"
    base_of_cf = {p: k for k, p in enumerate(cf_start)}
    base_of_cf[len(cf)] = len(base)
    level, chunks = (None, None) if file_reason else _chunking(txt, base)
    if file_reason is None and chunks is None:
        file_reason = "no chunking reproduces the stage-1 normalization"
    for o, term in hits:
        e = {"offset": o, "term": term, "status": "UNVERIFIED", "raw_offsets": [], "raw_end": None, "reason": file_reason}
        per.append(e)
        if file_reason:
            continue
        L = len(term)
        if L > 1:
            if cf[o:o + L] != term:
                e["reason"] = "term not at its stage-1 offset (casefolded coordinate)"
                continue
            if o not in base_of_cf or o + L not in base_of_cf:
                e["reason"] = "offset inside a casefold expansion"
                continue
            k0, k1 = base_of_cf[o], base_of_cf[o + L]
        else:
            if base[o:o + 1] != term:
                e["reason"] = "term not at its stage-1 offset (base coordinate)"
                continue
            k0, k1 = o, o + 1
        ch = next((c for c in chunks if c[2] <= k0 and k1 <= c[3]), None)
        if ch is None:
            e["reason"] = "hit crosses a normalization chunk boundary"
            continue
        r0, r1, a0, a1, n = ch
        if r1 - r0 > MAX_CHUNK:
            e["reason"] = f"chunk longer than {MAX_CHUNK} characters"
            continue
        c = txt[r0:r1]
        pref = [s3.norm_base(c[:j]) for j in range(len(c) + 1)]
        want0, want1 = n[:k0 - a0], n[:k1 - a0]
        starts = [j for j, p in enumerate(pref) if p == want0]
        good = []
        for j0 in starts:
            j1 = next((j for j in range(j0 + 1, len(c) + 1) if pref[j] == want1
                       and s3.norm_base(c[j0:j]) == base[k0:k1]), None)
            if j1 is not None:
                good.append((j0, j1))
        if not good:
            e["reason"] = "no prefix-consistent raw start and end for the hit"
            continue
        e.update(status="VERIFIED", raw_offsets=sorted({r0 + j0 for j0, _ in good}), raw_end=r0 + good[0][1], reason=None)
    return {"file_status": "UNVERIFIED" if file_reason else "OK", "chunk_level": level, "hits": per}


def preclassify(b, hits):
    """IV-1a around the FROZEN classifier: verified raw offsets → r5.binary_preclassify (unchanged). An ambiguous hit whose
    candidate raw offsets fall in different regions becomes UNVERIFIED. FALSE-HIT only if the frozen classifier says so AND
    every hit is VERIFIED; otherwise HUMAN-REVIEW, with reasons."""
    res = rebase(b, hits)
    offs = sorted({o for h in res["hits"] if h["status"] == "VERIFIED" for o in h["raw_offsets"]})
    frozen = r5.binary_preclassify(b, offs)
    for h in res["hits"]:
        if h["status"] == "VERIFIED" and len({frozen["hits"].get(o) for o in h["raw_offsets"]}) > 1:
            h.update(status="UNVERIFIED", reason="ambiguous raw start with inconsistent regions")
    reasons = sorted({f"UNVERIFIED: {h['reason']}" for h in res["hits"] if h["status"] != "VERIFIED"})
    ok = not reasons
    cand = "FALSE-HIT" if ok and frozen["candidate"] == "FALSE-HIT" else "HUMAN-REVIEW"
    if frozen["candidate"] == "HUMAN-REVIEW" and offs:
        reasons.append("frozen classifier: a verified hit lies in text or text-like bytes")
    return {"candidate": cand, "classifier": frozen, "hits": res["hits"], "chunk_level": res["chunk_level"],
            "file_status": res["file_status"], "reasons": reasons}
