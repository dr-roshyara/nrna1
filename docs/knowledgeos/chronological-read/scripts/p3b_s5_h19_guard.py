#!/usr/bin/env python3
"""P3b S5 H-19 protection guard (plan v2.3.2 §M items 2, 3, 6; deliverable O-10). Infrastructure only.

  seal                        §M item 2: `assert_sealed()` (state SEALED, seal id, approved hold-out file-list hash).
  grep [--paths P ...]        §M item 6: static scan of the S5 tooling source. Default scope: scripts/p3b_s5*.py
                              (this includes scripts/p3b_s5a*.py), scripts/tests/*p3b_s5*.py, plus the two reviewed
                              reading modules scripts/p3b_discovery_io.py and scripts/p3b_read_source.py. Rules:
                                FORBIDDEN-PATH       any name on the common FORBIDDEN list (incl. the sealed S3 file)
                                BASE-RESOLVER        direct construction of the base content resolver (a call, or its
                                                     manifest factory) outside p3b_discovery_io.py
                                QUARANTINE-FILE      any reference to the hold-out quarantine file outside the scanner
                                HOLDOUT-LISTS        any reference to the seal's hold-out list accessors / keys outside
                                                     the scanner, the filter modules and the common module
                              Reports file:line and the rule; exit 1 on any violation. Committed allowed uses are
                              whitelisted explicitly below, by exact line text (a changed line fails closed).
  refusals RUN_OR_LOG ...     §M item 3: reader-log (READ-LOG.jsonl) refusals, with counts; a refusal naming a hold-out
                              file is a potential seal breach -> P3B-ESC. Exit 1 if any refusal exists (each one must
                              be investigated). Hold-out ids are never printed (counts only).
  inputs ARTIFACT ...         §M item 6: every §19.5 header `input_hashes` key of the given artifacts is allowlisted.

The seal is asserted SEALED at the start and end of every mode.
"""
import argparse
import glob
import hashlib
import json
import os
import re
import sys

_HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, _HERE)
import p3b_s5_ops_lib as ops  # noqa: E402

c = ops.load_common()

SCANNER = "scripts/p3b_s5_quarantine_scan.py"
COMMON = "scripts/p3b_s5_common.py"
DIO = "scripts/p3b_discovery_io.py"
PREPARE = "scripts/p3b_s5_prepare.py"                   # O-4 slice generator: discovery filtering of slices (§M 5)
EXTRA_SCOPE = ("scripts/p3b_stage2a_scan.py", "scripts/p3b_s5a_pass_plan.py", "scripts/p3b_s5a_pass_contract.py",
               "scripts/tests/s5a_test_fixtures.py", "scripts/p3b_discovery_io.py", "scripts/p3b_read_source.py")
# pre-seal committed modules the S5 tooling still loads: scanned and REPORTED separately, never whitelisted; they do not
# set the exit code (G-LOG-0042 fix list item 4)
PRE_SEAL_MODULES = ("scripts/p3b_s3_mechanical_prep.py", "scripts/p3b_s4_prepare_pilot.py")
TESTLIB = "scripts/tests/p3b_s5_ops_testlib.py"          # synthetic fake-seal fixtures for the tests

RULES = {
    "FORBIDDEN-PATH": [re.compile(r"(?<![A-Za-z0-9_.-])" + re.escape(f)) for f in c.FORBIDDEN],
    "BASE-RESOLVER": [re.compile(r"\bContentResolver\s*\("), re.compile(r"\bContentResolver\s*\.\s*from_manifest")],
    "QUARANTINE-FILE": [re.compile(r"P3B-HOLDOUT-QUARANTINE\.jsonl")],
    "HOLDOUT-LISTS": [re.compile(r"\bholdout_(?:sets|labels|files)\b"),
                      re.compile(r"\bhf_sid_mentions_in_discovery_bundle[s]\b")],       # the seal's §4 mention lists
}

# Whole-file permissions: (file, rule) -> reason.
FILE_ALLOW = {
    (SCANNER, "QUARANTINE-FILE"): "the quarantine scanner itself (§M item 6)",
    (SCANNER, "HOLDOUT-LISTS"): "the reviewed scanner/filter (addendum §5, §6)",
    (TESTLIB, "HOLDOUT-LISTS"): "synthetic fake-seal fixtures (never real hold-out data)",
}

# Exact-line permissions for the committed modules: (file, rule) -> {sha256 of the stripped line text}. Digests, not
# literals, so this file does not itself contain the patterns it forbids; a changed line no longer matches (fails closed).
LINE_ALLOW = {
    (COMMON, "HOLDOUT-LISTS"): {
        "e01a6bf3bc414372877dffe59e8067f8e76ad6459e027567f03d1183f021495b",   # common L8   module docstring
        "fa5bfd70f7807205319aa63333f7d543c6cdd2f42d4c8c51a9a97eccda3e9c9f",   # common L140 accessor definition
        "442f21ea7b79af95323670047186696aaa30a277d130d00f8fd1f95238d92519",   # common L143 accessor body (lists)
        "a5ee2696c21c55c811fd758937010d61491db28cc7723400f980a92c6c8c2ab4",   # common L144 accessor body (§4 mentions)
        "a897f44b2449c6da130d27477f5b8e8653c86894eb43694824f7f63190cf88d5",   # common L156 quarantine_hits
        "9029ea010b8ba3985d19b9af834e6a2f9cc4a314b37441c2aece79b56e3589c3",   # common L199 files_meta filter
    },
    (COMMON, "QUARANTINE-FILE"): {
        "cbaa0d754d5bc4925a27da7a5e217e87893805f6a47b64c1bc71a5589574c402",   # common L49  ALLOWLIST entry (G-LOG-0042)
    },
    (COMMON, "FORBIDDEN-PATH"): {
        "478a4895dfc190a28bfe06d2e3897a06dfa8d22ffd0ebd6c833354b7540c4a9d",   # common L66  FORBIDDEN tuple
        "d12eb959a91cf828df1a9a1479768dd1c4bb773543e3ab08ca46780f502706d1",   # common L67  FORBIDDEN tuple
        "4e48ff01898fded9e37659cc905aa835db6b945f51f57de54baf713136ffc1a1",   # common L68  FORBIDDEN tuple
    },
    (DIO, "BASE-RESOLVER"): {
        "377a1bbbc8b0fa50fbb25979c00b5145c28bf35b2a941cf4c20ab6b670b0b158",   # dio L4  docstring
        "576202341daf52b1a27d316cdabbaad0af1cd1530b39bfc573fb03eee327bcb9",   # dio L7  docstring (residual risk)
        "1d2d7af4beff66b80afa011bae36e40e7ed453983e288bed54fb3aea985e7e54",   # dio L48 the one allowed construction
    },
    (DIO, "HOLDOUT-LISTS"): {
        "c27a16d3b0b53e31db62990059488e1741cd530866b6c66920b9f98666d011b8",   # dio L52 approved file-list hash check
        "5d549f9753f5b6c5b0921736e6a606e86a73eaa30f1b217ff152c2f9009b15f1",   # dio L54 sealed list for the resolver
    },
    (PREPARE, "HOLDOUT-LISTS"): {
        "16c893ec5a7790dee74e29cfc97e30bb4026241fb189412fd132b500ed44a1fe",   # prepare L398, L475 filter sets for slices
    },
}


def default_scope():
    root = os.path.dirname(_HERE)
    files = set(glob.glob(os.path.join(_HERE, "p3b_s5*.py")))
    files |= set(glob.glob(os.path.join(_HERE, "tests", "*p3b_s5*.py")))
    files |= {os.path.join(root, f) for f in EXTRA_SCOPE}
    return sorted(os.path.relpath(f, root) for f in files if os.path.isfile(f))


def pre_seal_scope():
    root = os.path.dirname(_HERE)
    return [f for f in PRE_SEAL_MODULES if os.path.isfile(os.path.join(root, f))]


def grep(paths=None, root=None):
    """Returns (files_scanned, violations, whitelisted) ; violation = (file, line_no, rule, token)."""
    root = root or os.path.dirname(_HERE)
    files = paths if paths is not None else default_scope()
    violations, allowed = [], []
    for rf in files:
        fp = rf if os.path.isabs(rf) else os.path.join(root, rf)
        key = os.path.relpath(fp, root).replace(os.sep, "/")
        with open(fp, encoding="utf-8", errors="replace") as f:
            lines = f.read().split("\n")
        for n, line in enumerate(lines, 1):
            for rule, pats in RULES.items():
                for pat in pats:
                    m = pat.search(line)
                    if not m:
                        continue
                    digest = hashlib.sha256(line.strip().encode("utf-8")).hexdigest()
                    if (key, rule) in FILE_ALLOW or digest in LINE_ALLOW.get((key, rule), ()):
                        allowed.append((key, n, rule))
                    else:
                        violations.append((key, n, rule, m.group(0)))
                    break
    return files, violations, allowed


def _logs(targets):
    out = []
    for t in targets:
        ap = ops.abspath(t)
        if os.path.isdir(ap):
            ap = os.path.join(ap, "READ-LOG.jsonl")
        out.append(ap)
    return out


def refusals(targets):
    scanner = ops.load_script("p3b_s5_quarantine_scan")
    logs = _logs(targets)
    ops.check_paths(logs)
    report = []
    for k, lp in enumerate(logs, 1):
        calls = ref = ref_hf = 0
        lines = []
        with open(lp, encoding="utf-8") as f:
            for i, line in enumerate(f, 1):
                if not line.strip():
                    continue
                rec = json.loads(line)
                calls += 1
                if rec.get("refused"):
                    ref += 1
                    lines.append(i)
                    if scanner.count_holdout_sids(rec.get("source_ids") or []):
                        ref_hf += 1
        report.append({"log": ops.display(lp, k), "calls": calls, "refused_calls": ref,
                       "refused_calls_naming_holdout_files": ref_hf, "refused_line_numbers": lines,
                       "action": ("P3B-ESC: potential seal breach (addendum §6)" if ref_hf else
                                  "investigate each refusal (§M item 3)" if ref else "none")})
    return report


def _header_of(path):
    with open(path, encoding="utf-8") as f:
        raw = f.read()
    try:
        doc = json.loads(raw)
    except json.JSONDecodeError:
        first = next((ln for ln in raw.split("\n") if ln.strip()), "{}")
        doc = json.loads(first)
    if isinstance(doc, dict) and isinstance(doc.get("header"), dict):
        return doc["header"]
    return doc if isinstance(doc, dict) else {}


def inputs(artifacts):
    ops.check_paths(artifacts)
    report = []
    for k, a in enumerate(artifacts, 1):
        ap = ops.abspath(a)
        h = _header_of(ap)
        keys = sorted((h.get("input_hashes") or {}).keys())
        bad = []
        for n, k in enumerate(keys, 1):
            try:
                c.check_inputs([k])
            except c.S5Error:
                bad.append(f"<refused input key #{n}>")          # a key is never echoed (it could name a label)
        report.append({"artifact": ops.display(ap, k), "has_header": bool(h.get("input_hashes") is not None),
                       "input_keys": len(keys), "not_allowlisted": bad})
    return report


def main(argv=None):
    ap = argparse.ArgumentParser(description=__doc__.split("\n")[0])
    sub = ap.add_subparsers(dest="mode", required=True)
    sub.add_parser("seal")
    g = sub.add_parser("grep")
    g.add_argument("--paths", nargs="*")
    g.add_argument("--root")
    r = sub.add_parser("refusals")
    r.add_argument("targets", nargs="+")
    i = sub.add_parser("inputs")
    i.add_argument("artifacts", nargs="+")
    a = ap.parse_args(argv)
    try:
        c.assert_sealed()
        if a.mode == "seal":
            rc = 0
            print(json.dumps({"seal": "SEALED", "seal_id": c.SEAL_ID}))
        elif a.mode == "grep":
            files, viol, allowed = grep(a.paths, a.root)
            for v in viol:
                print(f"VIOLATION {v[0]}:{v[1]}: {v[2]} ({v[3]})")
            pre = []
            if a.paths is None:
                _, pre, _ = grep(pre_seal_scope())
                for v in pre:
                    print(f"PRE-SEAL {v[0]}:{v[1]}: {v[2]} ({v[3]})")
            by_mod = {}
            for v in pre:
                by_mod.setdefault(v[0], {}).setdefault(v[2], 0)
                by_mod[v[0]][v[2]] += 1
            print(json.dumps({"files_scanned": len(files), "violations": len(viol), "whitelisted_hits": len(allowed),
                              "pre_seal_modules": {"scanned": pre_seal_scope() if a.paths is None else [],
                                                   "violations": len(pre), "by_module_rule": by_mod,
                                                   "note": "reported, not whitelisted; not counted in the exit code"}}))
            rc = 1 if viol else 0
        elif a.mode == "refusals":
            rep = refusals(a.targets)
            print(json.dumps(rep, indent=1))
            rc = 1 if any(x["refused_calls"] for x in rep) else 0
        else:
            rep = inputs(a.artifacts)
            print(json.dumps(rep, indent=1))
            rc = 1 if any(x["not_allowlisted"] or not x["has_header"] for x in rep) else 0
        c.assert_sealed()
        return rc
    except c.S5Error as ex:
        print(f"REFUSED: {ex}", file=sys.stderr)
        return 1


if __name__ == "__main__":
    sys.exit(main())
