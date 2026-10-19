"""Terminology discovery over the authorized corpus S0001-S0104 (status CONTENT).

DISCOVERY AID ONLY: counts and clusters are not evidence of meaning.
Run: python3 ml_terms.py  (reads index + source files read-only; deterministic: random_state=0)
"""
import json, re, collections, sys
from pathlib import Path

ROOT = Path("/home/nab-raj.roshyara@dg-nexolution.de/roshyara/personal/nrna1")
INDEX = ROOT / "docs/knowledgeos/knowlegeos_manual_theory_20261018/02-FILES.jsonl"

srcs = []
for line in INDEX.open():
    r = json.loads(line)
    if "S0001" <= r["source_id"] <= "S0104" and r["status"] == "CONTENT":
        p = ROOT / r["path"]
        if any(x in r["path"].lower() for x in ("chronological-read", "holdout", "seal", "quarantine")):
            continue
        srcs.append((r["source_id"], p.read_text(errors="replace")))

# ---- 1. variant census: currency / ending vocabulary ----
VARIANTS = {
    "currency": r"\b(in force|currently (?:valid|authoritative|in force)|current(?:ly)? authorit\w*|"
                r"governing|binding|effective(?: immediately)?|standing)\b",
    "ending": r"\b(supersed\w*|revok\w*|revocation|lapse[sd]?|expir\w*|withdraw\w*|annul\w*|rescind\w*|retire[sd]?)\b",
    "authority_noun": r"\bauthorit(?:y|ies)\b",
    "authoritative": r"\bauthoritative\b",
}
census = {k: collections.Counter() for k in VARIANTS}
per_src = collections.defaultdict(dict)
for sid, txt in srcs:
    for k, pat in VARIANTS.items():
        found = [m.group(0).lower() for m in re.finditer(pat, txt, re.I)]
        census[k].update(found)
        per_src[sid][k] = len(found)

# ---- 2. sentence clustering of "authority" contexts ----
sents = []
for sid, txt in srcs:
    for s in re.split(r"(?<=[.!?])\s+|\n+", txt):
        s2 = re.sub(r"[*`_>|#⭐⛔⚠️✅🔴🟠🟡]+", " ", s)
        if re.search(r"\bauthorit(?:y|ies)\b", s2, re.I) and 40 <= len(s2) <= 400:
            sents.append((sid, " ".join(s2.split())))

# keyword-defined reference senses (the manual analysis), used only to label clusters
SENSES = {
    "a_permission": r"\b(grant|permission|authori[sz]ed|may (?:approve|decide)|who may|human act|PO/ARB)\b",
    "b_document_trust": r"\b(generated|derived|provisional|historical|authoritative|standing|status)\b",
    "c_justification_order": r"\b(hierarch\w*|justif\w*|precedence|outrank\w*|chain)\b",
    "d_governing_body": r"\b(decision authority|ARB|board|DA\b)",
    "e_flow_or_record": r"\b(authority state|record\w*|writer|register\w*)\b",
}

def label(s):
    hits = [k for k, p in SENSES.items() if re.search(p, s, re.I)]
    return hits[0] if len(hits) == 1 else ("mixed" if hits else "none")

out = {"n_sources": len(srcs), "n_authority_sentences": len(sents),
       "census": {k: c.most_common(15) for k, c in census.items()}}
try:
    from sklearn.feature_extraction.text import TfidfVectorizer
    from sklearn.cluster import KMeans
    X = TfidfVectorizer(stop_words="english", min_df=3, ngram_range=(1, 2)).fit(
        [s for _, s in sents])
    V = X.transform([s for _, s in sents])
    km = KMeans(n_clusters=6, random_state=0, n_init=10).fit(V)
    terms = X.get_feature_names_out()
    clusters = []
    for c in range(6):
        idx = [i for i, l in enumerate(km.labels_) if l == c]
        top = [terms[j] for j in km.cluster_centers_[c].argsort()[::-1][:10]]
        lab = collections.Counter(label(sents[i][1]) for i in idx).most_common(3)
        clusters.append({"cluster": c, "size": len(idx), "top_terms": top, "manual_sense_labels": lab,
                         "example": sents[idx[0]][0] + ": " + sents[idx[0]][1][:160] if idx else ""})
    out["clusters"] = clusters
except Exception as ex:   # sklearn missing etc.
    out["clusters_error"] = repr(ex)

# ---- 3. inconsistent-wording probe: 'writes authority' vs 'never manufactures authority' ----
probe = {"writes_authority": r"writes? authority|authority writer",
         "records_not_creates": r"never manufactures authority|records authority; it never grants|do(?:es)? not create it"}
out["probe"] = {k: sorted({sid for sid, t in srcs if re.search(p, t, re.I)}) for k, p in probe.items()}

json.dump(out, sys.stdout, indent=1, ensure_ascii=False)
