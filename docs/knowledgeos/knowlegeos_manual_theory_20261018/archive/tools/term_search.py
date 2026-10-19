#!/usr/bin/env python3
"""Zero/Gap pilot, step A: term search. Prints ONLY path, per-term hit counts and sha256 for files with >= 1 hit.
Input: an allowed-list file (one repository-relative path per line) produced by the seal custodian.
No text from any file is printed or written.   python3 -I term_search.py <allowed_list.txt> <repo_root> > hits.tsv"""
import hashlib, os, re, sys
TERMS = {"zero": r"\bzero\b", "gap": r"\bgap\b", "falsezero": r"false[\s_-]?zero",
         "unknown": r"\bunknown\b", "epistemic_contract": r"epistemic[\s_-]contract"}
PAT = {k: re.compile(v, re.I) for k, v in TERMS.items()}
allowed, root = sys.argv[1], sys.argv[2]
print("path\tsha256\t" + "\t".join(TERMS))
n = hit = 0
for line in open(allowed, encoding="utf-8"):
    rel = line.strip()
    if not rel: continue
    n += 1
    data = open(os.path.join(root, rel), "rb").read()
    text = data.decode("utf-8", "replace")
    c = [len(PAT[k].findall(text)) for k in TERMS]
    if any(c):
        hit += 1
        print(rel, hashlib.sha256(data).hexdigest(), *c, sep="\t")
print(f"# searched {n} files, {hit} with hits", file=sys.stderr)
