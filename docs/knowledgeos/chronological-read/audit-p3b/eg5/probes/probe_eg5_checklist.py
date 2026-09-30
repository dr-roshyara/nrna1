#!/usr/bin/env python3
"""EG-5 in_checklist EMPTY probes on the SYNTHETIC r7_full_fixture (read-only import; no repo write).
Prints no S-id and no label name. Run: cd <CR>/scripts/tests && PYTHONPATH=.:.. python3 -B <this file>"""
import collections, re
import r7_full_fixture as FX

ST = FX.base()
EMPTY = FX.EMPTY_LAB
LABS = ST["ctx"]["labels"]


def mask(s):
    s = re.sub(r"S\d{4}", "S####", str(s))
    for l in LABS:
        s = s.replace(l, "<label>")
    return s


def show(name, b):
    kinds = collections.Counter(mask(f).split(" (")[0][:80] for f in b.get("failures") or [])
    print(f"{name:44s} {b['result']:18s} {dict(kinds)}")


def chk(st, examined):
    st["ctx"]["slices"][EMPTY]["in_checklist"] = True
    o = FX.objs_of(st, EMPTY)
    if examined is None:
        o.pop("checklist_examined", None)
    else:
        o["checklist_examined"] = examined


def drop_empty_object(st):
    st["ctx"]["slices"][EMPTY]["in_checklist"] = True
    st["ctx"]["objs"] = [o for o in st["ctx"]["objs"] if o["working_label"] != EMPTY]


def clean_empty_records(st):
    """EG-5c fixture realism: the EMPTY label carries no register / P1-gap records."""
    st["ctx"]["reg"] = [r for r in st["ctx"]["reg"] if r["working_label"] != EMPTY]
    st["ctx"]["gap"] = [g for g in st["ctx"]["gap"] if g["working_label"] != EMPTY]


def main():
    show("baseline", FX.verify(ST))
    show("(i) in_checklist + examined 1..23", FX.verify(ST, lambda s: chk(s, list(range(1, 24)))))
    show("(i) + no EMPTY register/gap records", FX.verify(ST, lambda s: (clean_empty_records(s), chk(s, list(range(1, 24))))))
    show("in_checklist, checklist_examined absent", FX.verify(ST, lambda s: chk(s, None)))
    show("in_checklist, examined 1..22", FX.verify(ST, lambda s: chk(s, list(range(1, 23)))))
    show("(ii) in_checklist, EMPTY object not assembled", FX.verify(ST, drop_empty_object))
    show("not in_checklist but examined 1..23", FX.verify(ST, lambda s: FX.objs_of(s, EMPTY).update(checklist_examined=list(range(1, 24)))))


if __name__ == "__main__":
    main()
