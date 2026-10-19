#!/usr/bin/env python3
"""Filter v2, revision r4 (PROPOSED; see SPEC.md). Pure functions take an injected seal and injected governance pins,
so tests use fake fixtures only.

Production entry point is disabled: D2_CLEARED is False until the rule owner answers D2 in writing and the D1 review
passes. Changing it is a governed act, not a code edit. The library functions themselves are not guarded: importing
and calling them with a real seal is a production use and falls under the same rule.

Design note (r4): content matching against a denylist is a best-effort screen. Each review round has found further
encodings; the D1 reviewer must judge residual risk (SPEC §6), and the Principal's preferred D2 option (custodian-
produced allowed list) does not depend on this screen being complete.
"""
import hashlib
import html
import json
import os
import quopri
import re
import stat
import sys
import unicodedata
import urllib.parse

D2_CLEARED = False
MIN_LABEL_COMPACT_LEN = 3          # shorter labels are rejected (fail closed), not silently over-matched
MAX_BYTES = 50_000_000             # larger files fail closed
MAX_DECODE_ROUNDS = 6
MAX_FORMS = 64
PATH_KEYS = ("path", "current_path", "historical_path")
HEX64 = re.compile(r"^[0-9a-f]{64}$")
ID_FORM = re.compile(r"^([a-z]+)0*([0-9]+)$")
INVISIBLE = set("͏ᅟᅠ឴឵᠋᠌᠍᠎᠏⠀ㅤﾠ")
SEP_IN_PREFIX = r"[^0-9a-z]{0,3}"
SEP_BEFORE_NUMBER = r"[^0-9a-z]{0,8}"


class FailClosed(Exception):
    """Any integrity or boundary violation. Callers must stop and emit no allowed list. Messages never carry names."""


def sha256_bytes(data):
    return hashlib.sha256(data).hexdigest()


def norm(p):
    p = os.path.normpath(unicodedata.normalize("NFC", str(p or "")))
    return p[2:] if p.startswith("./") else p


def rel_in_root(path, root):
    rel = os.path.relpath(os.path.realpath(path), os.path.realpath(root))
    if rel == ".." or rel.startswith(".." + os.sep) or os.path.isabs(rel):
        raise FailClosed("path outside ROOT")
    return norm(rel)


def _drop(s):
    return "".join(ch for ch in s if unicodedata.category(ch) not in ("Cf", "Mn") and ch not in INVISIBLE)


def _fold1(s):
    s = _drop(unicodedata.normalize("NFKD", s))
    s = _drop(unicodedata.normalize("NFKC", s))
    s = "".join(str(unicodedata.decimal(ch)) if unicodedata.decimal(ch, None) is not None else ch for ch in s)
    return s.casefold()


def _decoders(s):
    yield urllib.parse.unquote(s)
    yield html.unescape(s)
    yield re.sub(r"\\u([0-9a-fA-F]{4})", lambda m: chr(int(m.group(1), 16)), s)
    yield quopri.decodestring(s.encode("utf-8", "surrogatepass")).decode("utf-8", "replace")


def decoded_forms(s):
    """The text plus every form reachable by repeated %xx, HTML-entity, JSON \\uXXXX and quoted-printable decoding."""
    forms, frontier = {s}, {s}
    for _ in range(MAX_DECODE_ROUNDS):
        new = {d for f in frontier for d in _decoders(f)} - forms
        if not new:
            break
        forms |= new
        frontier = new
        if len(forms) > MAX_FORMS:
            break
    return sorted(forms)


def fold(s):
    """Every decoded form, each folded (NFKD/NFKC, format chars, combining marks and invisible fillers dropped,
    decimal digits to ASCII, casefold), joined by newlines. Matching any form withholds."""
    return "\n".join(_fold1(f) for f in decoded_forms(str(s)))


def compact1(s):
    """Seal-side normalization of one string: folded, letters and digits only."""
    return "".join(ch for ch in _fold1(str(s)) if ch.isalnum())


def compact(s):
    """Text-side normalization: every decoded form, letters and digits only (forms concatenated)."""
    return "".join(ch for ch in fold(s) if ch.isalnum())


def label_list_hash(labels):
    return sha256_bytes(json.dumps(sorted(labels)).encode())


def file_list_hash(rows):
    key = sorted([str(r.get(k) or "") for k in ("source_id", "content_sha256") + PATH_KEYS] for r in rows)
    return sha256_bytes(json.dumps(key).encode())


def _canonical_seal_path(p):
    p = str(p)
    if (os.path.isabs(p) or "\\" in p or unicodedata.normalize("NFC", p) != p
            or ".." in p.split("/") or norm(p).startswith("/")):
        raise FailClosed("seal path not canonical (relative, '/'-separated, NFC, no '..')")
    return norm(p)


def _id_regex(prefix, number):
    pre = SEP_IN_PREFIX.join(re.escape(ch) for ch in prefix)
    return re.compile(pre + SEP_BEFORE_NUMBER + "0*" + number + r"(?![0-9])")


def verify_integrity(seal, pins):
    """pins: governance-pinned expected hashes {'label_list_sha256', 'file_list_sha256'}, injected by the caller."""
    labels = list(seal.get("holdout_labels") or [])
    rows = list(seal.get("holdout_files") or [])
    if not labels or not rows:
        raise FailClosed("seal empty or unsealed")                                      # I-3
    lh, fh = label_list_hash(labels), file_list_hash(rows)
    if lh != seal.get("holdout_label_list_sha256") or lh != (pins or {}).get("label_list_sha256"):
        raise FailClosed("label list hash mismatch (self or pin)")                      # I-1
    if fh != seal.get("holdout_file_list_sha256") or fh != (pins or {}).get("file_list_sha256"):
        raise FailClosed("file list hash mismatch (self or pin)")                       # I-2
    comp = []
    for l in labels:
        c = compact1(l)
        if len(c) < MIN_LABEL_COMPACT_LEN:
            raise FailClosed("label empty or too short after normalization")            # I-5
        comp.append(c)
    ids, shas, paths = [], set(), set()
    for r in rows:
        m = ID_FORM.match(compact1(r.get("source_id") or ""))
        sha = str(r.get("content_sha256") or "").lower()
        ps = [_canonical_seal_path(r[k]) for k in PATH_KEYS if r.get(k)]               # I-8
        if not m or not HEX64.match(sha) or not ps:
            raise FailClosed("malformed seal row")                                      # I-6
        ids.append((m.group(1), str(int(m.group(2)))))
        shas.add(sha)
        paths.update(ps)
    return {"labels": comp, "sha": shas, "paths": paths,
            "id_res": [_id_regex(p, n) for p, n in ids], "ids": {p + n for p, n in ids}}


def decode_or_none(data):
    """Strict UTF-8 without NUL bytes; anything else is treated as undecodable (and withheld)."""
    if b"\x00" in data:
        return None
    try:
        return data.decode("utf-8")
    except UnicodeDecodeError:
        return None


def read_regular(p):
    """Open without following a final symlink and without blocking; require a regular file within MAX_BYTES."""
    try:
        fd = os.open(p, os.O_RDONLY | os.O_NOFOLLOW | getattr(os, "O_NONBLOCK", 0))
    except OSError:
        raise FailClosed("cannot open file in scan (symlink, permission or missing)") from None
    try:
        st = os.fstat(fd)
        if not stat.S_ISREG(st.st_mode):
            raise FailClosed("non-regular file in scan")
        if st.st_size > MAX_BYTES:
            raise FailClosed("file too large")
        chunks = []
        while True:
            b = os.read(fd, 1 << 20)
            if not b:
                break
            chunks.append(b)
        return b"".join(chunks)
    except OSError:
        raise FailClosed("read error in scan") from None
    finally:
        os.close(fd)


def mentions_label(view, *texts):
    c = "".join(compact(t) for t in texts)
    return any(l in c for l in view["labels"])


def quotes_holdout_id(view, *texts):
    t = fold("\n".join(texts))
    return any(rx.search(t) for rx in view["id_res"])


def corpus_withheld(view, rel, sha, source_id=None, text=None):
    if str(sha).lower() in view["sha"] or norm(rel) in view["paths"]:
        return True
    if source_id is not None:
        m = ID_FORM.match(compact1(source_id))
        if m and m.group(1) + str(int(m.group(2))) in view["ids"]:
            return True
    if mentions_label(view, rel) or quotes_holdout_id(view, rel):
        return True                                       # never print a path that names a label or a sealed ID
    if text is not None and (mentions_label(view, text) or quotes_holdout_id(view, text)):
        return True
    return False


def family_withheld(view, relpath, text):
    if text is None:
        return True
    return mentions_label(view, relpath, text) or quotes_holdout_id(view, relpath, text)


def scan_families(view, families_dir, root):
    allowed, withheld, subdirs = [], 0, 0

    def _walk_error(err):
        raise FailClosed("unreadable directory in scan tree") from None

    try:
        for base, dirs, files in os.walk(families_dir, onerror=_walk_error):
            if any(os.path.islink(os.path.join(base, d)) for d in dirs):
                raise FailClosed("symlinked directory in scan tree")                    # I-7
            dirs.sort()
            if base != families_dir:
                subdirs += 1
            for fn in sorted(files):
                p = os.path.join(base, fn)
                rel_root = rel_in_root(p, root)                                          # I-4
                data = read_regular(p)
                h = sha256_bytes(data)
                relf = os.path.relpath(p, families_dir)
                if (h in view["sha"] or rel_root in view["paths"]
                        or family_withheld(view, relf, decode_or_none(data))):
                    withheld += 1
                else:
                    allowed.append((relf, h))
    except OSError:
        raise FailClosed("filesystem error in scan") from None
    return allowed, withheld, subdirs


def scan_corpus(view, root, items):
    """items: iterable of (path, source_id or None)."""
    allowed, withheld = [], 0
    try:
        for p, sid in items:
            rel = rel_in_root(p, root)                                                  # I-4
            data = read_regular(p)
            h = sha256_bytes(data)
            text = decode_or_none(data)
            if text is None or corpus_withheld(view, rel, h, source_id=sid, text=text):
                withheld += 1
            else:
                allowed.append((rel, h))
    except OSError:
        raise FailClosed("filesystem error in scan") from None
    return allowed, withheld


def write_allowed(out_path, header, rows):
    if not os.path.isabs(out_path):
        raise FailClosed("output path must be absolute")                                # F-8
    lines = [header] + list(rows)
    if any(any(c in str(x) for c in "\t\r\n") for r in lines for x in r):
        raise FailClosed("field contains tab or newline")
    try:
        fd = os.open(out_path, os.O_WRONLY | os.O_CREAT | os.O_EXCL | os.O_NOFOLLOW, 0o600)
    except OSError:
        raise FailClosed("output exists, is a symlink, or cannot be created") from None
    with os.fdopen(fd, "w", encoding="utf-8") as f:
        for r in lines:
            f.write("\t".join(str(x) for x in r) + "\n")


def source_id_check(view, allowed_family_texts, opened_family_names, opened_doc_texts):
    """Count-only check (1752 report §4.2). Returns (count, opened_families_hit, opened_docs_hit) and nothing else."""
    hits = {name for name, text in allowed_family_texts.items() if quotes_holdout_id(view, name, text)}
    return (len(hits), bool(hits & set(opened_family_names)),
            any(quotes_holdout_id(view, t) for t in opened_doc_texts))


def production_main(argv):
    if not D2_CLEARED:
        raise SystemExit("REFUSED: production use disabled until D2 is answered in writing and D1 review passes")
    raise SystemExit("REFUSED: production adapter to the real seal is intentionally not implemented in v2")


if __name__ == "__main__":
    production_main(sys.argv[1:])
