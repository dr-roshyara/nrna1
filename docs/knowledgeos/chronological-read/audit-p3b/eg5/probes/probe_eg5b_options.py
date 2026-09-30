#!/usr/bin/env python3
"""EG-5b option probes on the SYNTHETIC r7_full_fixture (read-only import; no repo write; prints no S-id).
Run: cd <CR>/scripts/tests && PYTHONPATH=.:.. python3 -B <this file>"""
import collections, json, re, sys
import r7_full_fixture as FX

ST = FX.base()
PLAN = ST["plan"]
OB, R7 = FX.OB, FX.R7
IDX = {lab: L["index"] for lab, L in PLAN["labels"].items()}


def final_run(lab):
    return next((r for r, d in PLAN["labels"][lab]["runs"].items() if d["role"] in ("SINGLE", "SYNTHESIS")), None)


def fails(b):
    return [re.sub(r"S\d{4}", "S####", str(x)) for x in b.get("failures") or []]


def show(name, b, keep=("G-07", "G-02", "R7")):
    fl = [f for f in fails(b) if f.startswith(keep)]
    kinds = collections.Counter(re.sub(r"G-07 \S+: ", "G-07 <id>: ", f.split(" (")[0])[:70] for f in fl)
    print(f"{name:34s} {b['result']:18s} {dict(kinds)}")


def renumber_a(st):
    """Option (a): record run_id = <B>-R7 (already so in FX); n = 1000*L + k per label, k = file order 1..;
    derived_from_records remapped consistently."""
    ctx = st["ctx"]
    for key, fld, pre in (("reg", "rs_id", ""), ("gap", "gap_id", "G")):
        k = collections.Counter()
        mp = {}
        for r in ctx[key]:
            lab = r["working_label"]
            k[lab] += 1
            new = f"{R7}:{OB}:{pre}{1000 * IDX[lab] + k[lab]}"
            mp[r[fld]] = new
            r[fld] = new
            r["run_id"] = R7
        if key == "reg":
            for r in ctx[key]:
                r["derived_from_records"] = [mp.get(x, x) for x in r.get("derived_from_records") or []]


def collide(st):
    """Per-run independent numbering from 1 (what isolated agents do without a label-scoped rule)."""
    ctx = st["ctx"]
    for key, fld, pre in (("reg", "rs_id", ""), ("gap", "gap_id", "G")):
        k = collections.Counter()
        for r in ctx[key]:
            k[r["working_label"]] += 1
            r[fld] = f"{R7}:{OB}:{pre}{k[r['working_label']]}"
            r["derived_from_records"] = [] if key == "reg" else r.get("derived_from_records")
            if key == "gap":
                r.pop("derived_from_records", None)


def dispatched(st, what):
    """Dispatched final-run id in one register-bearing label's records (object / register ids)."""
    lab = next(r["working_label"] for r in st["ctx"]["reg"] if final_run(r["working_label"]))
    fr = final_run(lab)
    if what == "object":
        FX.objs_of(st, lab)["run_id"] = fr
    for r in st["ctx"]["reg"]:
        if r["working_label"] == lab and what in ("register", "both"):
            r["run_id"] = fr
            r["rs_id"] = r["rs_id"].replace(f"{R7}:", f"{fr}:", 1)


def overflow(st):
    """Option (a) with k beyond 999 in one label: n = 1000*L + 1000 + 1 == first id of label L+1 (if it has records)."""
    renumber_a(st)
    ctx = st["ctx"]
    labs = sorted({r["working_label"] for r in ctx["reg"]}, key=lambda l: IDX[l])
    a, b = labs[0], next(l for l in labs[1:] if IDX[l] == IDX[labs[0]] + 1) if any(IDX[l] == IDX[labs[0]] + 1 for l in labs[1:]) else (labs[0], None)
    if b is None:
        print("overflow probe: no adjacent register-bearing labels in the fixture; skipped")
        return
    r = next(x for x in ctx["reg"] if x["working_label"] == a)
    r["rs_id"] = f"{R7}:{OB}:{1000 * IDX[b] + 1}"


def claim_run_assembly(st):
    """The claim-evidence ref `run` must stay the dispatched READING run (not the assembly id)."""
    for run, cm in st["claims"].items():
        for cid, refs in cm.items():
            for ref in refs:
                ref["run"] = R7
            return


def main():
    regs = ST["ctx"]["reg"]
    per = collections.Counter(r["working_label"] for r in regs)
    print("fixture: register-bearing labels", len(per), "max records per label", max(per.values()),
          "gap records", len(ST["ctx"]["gap"]), "paths", sorted({L['path'] for L in PLAN['labels'].values()}))
    show("baseline (FX as is)", FX.verify(ST))
    show("(a) label-scoped n=1000L+k", FX.verify(ST, renumber_a))
    show("independent per-run numbering", FX.verify(ST, collide))
    show("dispatched id in object only", FX.verify(ST, lambda s: dispatched(s, "object")))
    show("dispatched id in register only", FX.verify(ST, lambda s: dispatched(s, "register")))
    show("(a) + claim ref run = <B>-R7", FX.verify(ST, lambda s: (renumber_a(s), claim_run_assembly(s))), keep=("R7-E", "G-07"))
    stc = FX.base()
    renumber_a(stc)
    overflow_st = stc
    show("(a) + k overflow into next label", FX.verify(ST, overflow))


if __name__ == "__main__":
    main()
