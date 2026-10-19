"""ML discovery aid for ALM-r1 (NOT proof, NOT source evidence).

Question: over the bounded space, WHEN does recognition from records disagree with validity?
Method: generate (history, recording order, prefix k, act) cases; label disagreement
(Rec under r1.1-like lam+=0, lam-=0 closed world  !=  V); fit a shallow decision tree on
interpretable boolean features; print the learned rules; compare them with Th-3's premise
(completeness Comp(k, tau(e))). A learned rule is a hypothesis to be checked against a proof.
Deterministic: random_state=0. Run: python3 -B ml_discovery_r1.py
"""
import json
from itertools import permutations
from alm_r1 import Entry, History, rec
from tests_r1_exhaustive import histories, theta, VALIDITY_GRID

th = theta(**VALIDITY_GRID[0])
X, y, names = [], [], ["comp_k_tau", "e_recorded", "unrecorded_earlier_revoke",
                       "unrecorded_earlier_grant", "late_recorded_any", "n_acts"]
for H in histories(max_n=3):
    V = History(H, th)
    for perm in permutations(H):
        E = [Entry(i + 1, a) for i, a in enumerate(perm)]
        pos = {a.id: i + 1 for i, a in enumerate(perm)}
        for e in H:
            for k in range(1, len(H) + 1):
                if pos[e.id] > k:
                    continue
                r = rec(E, k, ("valid", e.id), th, now=100, lam_pos=0, lam_neg=0)
                earlier = [f for f in H if f.tau <= e.tau and f.id != e.id]
                feats = [
                    all(pos[f.id] <= k for f in earlier),
                    True,
                    any(f.kind == "REVOKE" and pos[f.id] > k for f in earlier),
                    any(f.kind == "GRANT" and pos[f.id] > k for f in earlier),
                    any(pos[f.id] > pos[e.id] for f in earlier),
                    len(H),
                ]
                X.append([int(v) for v in feats])
                y.append(int((r == "T") != V.valid(e.id)))

out = {"cases": len(y), "disagreements": sum(y)}
try:
    from sklearn.tree import DecisionTreeClassifier, export_text
    clf = DecisionTreeClassifier(max_depth=3, random_state=0, class_weight="balanced").fit(X, y)
    # first run without class_weight predicted the majority class only (acc = base rate 0.9747);
    # recorded in ALM-R1-RESULTS.md. Balanced weights force the tree to explain the rare class.
    from sklearn.metrics import precision_score, recall_score
    pred = clf.predict(X)
    out["precision_disagree"] = round(precision_score(y, pred), 4)
    out["recall_disagree"] = round(recall_score(y, pred), 4)
    out["tree"] = export_text(clf, feature_names=names)
    out["train_accuracy"] = round(clf.score(X, y), 4)
    # the hypothesis to check against Th-3: disagreement => not comp_k_tau
    out["disagreements_with_completeness"] = sum(1 for xi, yi in zip(X, y) if yi and xi[0])
except Exception as ex:
    out["error"] = repr(ex)
json.dump(out, open("ml-discovery.json", "w"), indent=1)
print(json.dumps({k: v for k, v in out.items() if k != "tree"}, indent=1))
print(out.get("tree", ""))
