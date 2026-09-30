"""Build the blind T-A reader packet (HD-4) from the FROZEN pre-registration r3. Deterministic; standard library.

Redactions (and nothing else):
  1. pre-registration section 3.2 (pre-declared expected findings), whole section;
  2. the one other sentence that states a prediction (section 5, PARTIALLY UNTESTED row);
  3. the PREDICTED table in aggregate.py (replaced by empty sets, so the tool still validates records).
Refuses to run unless the inputs match their recorded hashes. Writes reader_packet/ next to this file.
"""
import hashlib
import os
import sys

HERE = os.path.dirname(os.path.abspath(__file__))
LANE = os.path.dirname(os.path.dirname(HERE))
PREREG = os.path.join(LANE, "prompts", "KNOWLEDGEOS-H-F2-1-R-T-A-PREREGISTRATION.md")
AGG = os.path.join(HERE, "aggregate.py")
FROZEN = "be16deb7af88d1c87072566c0e1c1feb43258cf2e84e6d707107bedc6a617133"
AGG_SHA = "13532a5bd4123514ab1cf92d2a057a64c8bab20bdca5b2cf59e43febc2d8c7b9"
OUT = os.path.join(HERE, "reader_packet")


def sha(b):
    return hashlib.sha256(b).hexdigest()


def cut(text, start, end):
    i, j = text.index(start), text.index(end)
    assert i < j and text.count(start) == 1 and text.count(end) == 1
    return text[:i] + "### 3.2 [REDACTED for the blind reader packet]\n\n" + text[j:]


def main():
    pre, agg = open(PREREG, "rb").read(), open(AGG, "rb").read()
    if sha(pre) != FROZEN or sha(agg) != AGG_SHA:
        print("STOP: input hash mismatch", file=sys.stderr)
        return 2
    t = pre.decode("utf-8")
    t = cut(t, "### 3.2 Pre-declared expected findings", "## 4. Evidence extraction rule")
    leak = ", although §3.2 predicts NOT_EVIDENCED for A5g"
    assert t.count(leak) == 1
    t = t.replace(leak, " [REDACTED: prediction reference]")
    note = ("> **BLIND READER PACKET.** Derived mechanically by `analysis/t_a/build_reader_packet.py` from the frozen "
            f"pre-registration r3 (sha256 `{FROZEN}`). Redacted: §3.2 and one prediction reference in §5. "
            "Every methodological rule is unchanged. This packet is not the frozen document.\n\n")
    t = note + t
    a = agg.decode("utf-8")
    i = a.index("PREDICTED = {")
    j = a.index("}\n", a.index('"F-A3m": {"SUPPORTED", "NOT_EVIDENCED"}}')) + 2
    a = a[:i] + "PREDICTED = {t: set() for t in AXIOM_OF}  # REDACTED for the blind reader packet\n" + a[j:]
    os.makedirs(OUT, exist_ok=True)
    files = {"PREREGISTRATION-r3-BLIND.md": t.encode("utf-8"), "aggregate_blind.py": a.encode("utf-8")}
    for n, b in files.items():
        open(os.path.join(OUT, n), "wb").write(b)
    with open(os.path.join(OUT, "PACKET.sha256"), "w") as f:
        f.write(f"{FROZEN}  source:KNOWLEDGEOS-H-F2-1-R-T-A-PREREGISTRATION.md (frozen r3)\n")
        f.write(f"{AGG_SHA}  source:aggregate.py\n")
        for n, b in sorted(files.items()):
            f.write(f"{sha(b)}  {n}\n")
    print(open(os.path.join(OUT, "PACKET.sha256")).read(), end="")
    return 0


if __name__ == "__main__":
    sys.exit(main())
