#!/usr/bin/env python3
"""KC-1 independent reference implementation (PROPOSED / NOT RATIFIED; analysis only).

Written from the DEFINITIONS in T1 r1.6, T2 r1.4, T3 r1.9 (§1a placement, rule 2/2a/3,
crossing table), E3 §2.3 (E3-REL, H, D12), KC-CONVENTIONS r1.1 §2.4 item 4 (K1 parses),
KC-MKC §6.2 (C-Σ parts) and KC-1-READINESS §12.2 (K18 composition I/II).
No existing script (kc.py, blind_enc.py, mkc.py, readiness.py, ch_*.py) was imported,
copied or read before this file was complete.  Run: python3 -B ref.py > ref.out
Decides nothing: KC-1, C-γ, D12, the spanning rule, E4, E5 stay open.
"""

# ---------------------------------------------------------------- placement (T3 §1a, P-c)
CB1 = {13, 14, 15, 16, 17, 18, 23, 24, 27}
CB23 = {2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 19, 20, 21}
# stated exceptions: C1 (spanning, rule 2a), C22 (unplaced), C25, C26, C28 (CB4 slot, no side)
assert len(CB1) == 9 and len(CB23) == 14 and not CB1 & CB23


def side(c):
    return "CB1" if c in CB1 else "CB23" if c in CB23 else None


# ---------------------------------------------------------------- spanning readings (T3 rule 2a)
# actor flag per row: None = row reads no actor; else the S″ location of the actor:
# "CB23" (actor of an act / work event / Cap holder), "CB1" (keeper), "U" (unlocated).
def actor_sides(loc, reading):
    if loc is None:
        return set()
    if reading == "S":
        return {"CB1", "CB23"}
    if reading == "S'":
        return set()
    if reading == "S''":
        return {loc} if loc in ("CB1", "CB23") else set()
    raise ValueError(reading)


# ---------------------------------------------------------------- T-A rows (transcribed from T3 crossing table)
# Each row: (concept set read by the statement under T3 rules 1-2, class-rule-only concepts, actor loc)
# Concept ids are T1 C-ids.  Mapping used (rule 2): records/O/record ids -> C13; r -> C14; c -> C15;
# k -> C16; Ref -> C17; con/mode -> C18; Cap -> C2; τ(a) -> C3; P/V -> C4; v(a) -> C5 (A.0 mapping;
# rule 2 silent, choice T-a); σ -> C6; Chg -> C7; acts/E/issue/adoption act -> C8; obj -> C9;
# prep/Adopted -> C10; d -> C11; f -> C12; work events/W/τ(w) -> C19; t(w) -> C20; conformance -> C21.
TA = {
    "R1":  ({6, 12, 3, 5}, set(), None),
    "R2":  ({18, 17, 8}, set(), None),
    "R3":  ({18, 14, 11}, set(), None),
    "R4":  ({8, 9}, set(), None),
    "R5":  ({8, 11, 3, 5, 10, 12}, set(), "CB23"),
    "R6":  ({19, 20}, set(), "CB23"),
    "R7":  ({13, 14, 15}, set(), None),
    "R8":  ({16}, set(), "CB1"),
    "R9":  ({17, 18, 13, 8, 19}, set(), None),
    "R10": ({2}, set(), "CB23"),
    "R11": ({6, 8}, set(), None),                       # guard (a)
    "R12": ({8}, {13, 17}, None),                       # D-rec only via class rule (rule 3)
    "R13": ({17, 13, 6, 7, 21, 10, 8}, set(), "CB23"),  # named clauses; D-chg's α on D side
    "R14": ({14, 12, 11}, set(), None),                 # guard (a)
    "R15": ({14, 11, 12, 20}, set(), None),             # guard (a)
    "R16": ({13, 17, 8, 19}, set(), None),              # guard (a); Ref relational clause
    "R17": ({6, 8, 19}, set(), None),                   # guard (a)
    "R18": ({6, 20, 19, 4}, set(), None),
    "R19": ({10, 2, 11, 9}, set(), "CB23"),
    "R20": ({6, 7}, set(), None),
    "R21": ({15, 6, 12}, set(), None),                  # guard (a)
    "R22": ({13, 8, 19}, set(), "U"),                   # bare actor ids: unlocated
    "R23": ({13, 17, 18, 16, 6, 12, 11, 2, 8, 19}, set(), "U"),
    "R24": ({17, 13, 18, 8}, set(), None),
}
assert len(TA) == 24
GUARD = ["R11", "R14", "R15", "R16", "R17", "R21"]
R3_FORMULA = TA["R3"][0]  # {con, r, d}: read by R15's "(R3)" under C's row-reference rule

# ---------------------------------------------------------------- K1 values (guard-row item sets)
K1 = {
    "(a)":        {g: TA[g][0] for g in GUARD},                       # T3 rule 1
    "SH":         {g: set() for g in GUARD},                          # (b) guard rows read nothing
    "K1c":        {"R11": {3, 4, 6}, "R14": set(), "R15": {20},        # KC §2.4 item 4
                   "R16": set(), "R17": set(), "R21": {15}},
}
K1["R15ref"] = dict(K1["K1c"], R15={20} | R3_FORMULA)
K1["R15ref-R14"] = dict(K1["R15ref"], R14={11, 12, 14})
K1["C-Σ"] = dict(K1["R15ref"], R11={3, 4, 5, 6, 8, 12})              # MKC §6.2 part table

# ---------------------------------------------------------------- T2 "rests on" C-id column (K18)
RESTS_ON = {
    "R1": {6, 8, 12, 3, 4}, "R2": {17, 18}, "R3": {11, 14, 18, 22}, "R4": {8, 9},
    "R5": {8, 9, 11, 12, 1}, "R6": {19, 20, 3, 1}, "R7": {13, 14, 15}, "R8": {16},
    "R9": {17, 18}, "R10": {2}, "R11": {6}, "R12": {8, 9, 17}, "R13": {6, 7, 17, 21},
    "R14": {12, 11, 14, 22}, "R15": {22, 20, 24, 11, 12, 14}, "R16": {23},
    "R17": {6, 19, 21}, "R18": {21, 6, 19, 20, 4}, "R19": {10, 2, 9, 11}, "R20": {7, 6},
    "R21": {15}, "R22": {13, 1}, "R23": {1, 2, 6, 7, 8, 9, 10, 11, 12, 16, 17, 18, 19, 20, 21, 25},
    "R24": {27, 18},
}


def verdict(concepts, loc, reading):
    s = {side(c) for c in concepts if c != 1} - {None}
    if 1 in concepts:  # K18 column lists C1 explicitly; unlocated per column -> use row's loc
        s |= actor_sides(loc or "U", reading)
    s |= actor_sides(loc, reading)
    if s == {"CB1", "CB23"}:
        return "X"
    if s == {"CB1"}:
        return "CB1"
    if s == {"CB23"}:
        return "CB23"
    return "none"


def encode(k1="(a)", class_rule=True, k18=None):
    """Return {row: (concepts, loc)}.  k18: None | 'I' | 'II'."""
    enc = {}
    for r, (c, cls, loc) in TA.items():
        cc = set(c) | (cls if class_rule else set())
        enc[r] = (cc, loc)
    if k18 in ("I", "II"):
        for r in TA:
            enc[r] = (set(RESTS_ON[r]), None)  # column carries its own C1 marks
    if k18 == "I":
        # column replaces the input at L too; K1 then acts on that input:
        # (a) and (c)-parses read it whole (nothing to parse); SH empties it.
        if k1 == "SH":
            for g in GUARD:
                enc[g] = (set(), None)
    else:
        for g in GUARD:
            enc[g] = (set(K1[k1][g]), None)
    return enc


def verdicts(enc, reading):
    return {r: verdict(c, loc, reading) for r, (c, loc) in enc.items()}


H_MW1 = {"R12", "R13", "R23"}


def e3rel(v, d12):
    """E3-REL: T-A rows crossing (X), minus hosted set H.  d12='a': R24 hosted at CV4 (excluded);
    d12='b': R24 shared (eligible)."""
    H = H_MW1 | ({"R24"} if d12 == "a" else set())
    return sorted((r for r in v if v[r] == "X" and r not in H), key=lambda x: int(x[1:]))


def fmt(rows):
    return ", ".join(rows) + f" ({len(rows)})"


READ = ["S", "S'", "S''"]
out = []
p = out.append

p("KC-1 reference implementation output (PROPOSED / NOT RATIFIED; analysis only)")
p("=" * 78)

# §0 self-check against T3's own T-A counts
p("\n§0 Self-check: T-A category counts vs T3 r1.7 counts table (S 17/6/1/0/0; S′,S″ 12/10/2/0/0)")
base = encode()
for rd in READ:
    v = verdicts(base, rd)
    cnt = {k: sum(1 for x in v.values() if x == k) for k in ("X", "CB23", "CB1", "none")}
    p(f"  {rd:4}: X={cnt['X']} CB23={cnt['CB23']} CB1={cnt['CB1']} none={cnt['none']}")

# §1 guard-row verdicts per K1 value
p("\n§1 Guard-row verdicts (R11, R14, R15, R16, R17, R21) per K1 value (identical under S, S′, S″:"
  " no guard row reads C1)")
for k in K1:
    vs = {rd: verdicts(encode(k), rd) for rd in READ}
    assert vs["S"]["R11"] == vs["S'"]["R11"]
    p(f"  {k:11}: " + "  ".join(f"{g}={vs['S'][g]}" for g in GUARD)
      + "   sets: " + "; ".join(f"{g}{sorted(K1[k][g])}" for g in GUARD))

# §2 E3-REL per K1 value, reading, D12
p("\n§2 E3-REL (T-A rows with verdict X, not hosted at MW1 {R12,R13,R23} or, under D12 (a), CV4 {R24})")
p("    x/y = |E3-REL under S| / |E3-REL under S′ (= S″)|; two counts, not a ratio.")
p("    Eligible universe: 24 T-A rows − 4 hosted = 20 (D12 a) or − 3 = 21 (D12 b).")
summary = {}
for k in K1:
    for d12 in ("a", "b"):
        rel = {rd: e3rel(verdicts(encode(k), rd), d12) for rd in READ}
        assert rel["S'"] == rel["S''"]
        summary[(k, d12)] = (len(rel["S"]), len(rel["S'"]))
        p(f"  {k:11} D12({d12}): {len(rel['S'])}/{len(rel["S'"])}")
        p(f"      S : {fmt(rel['S'])}")
        p(f"      S′: {fmt(rel["S'"])}")

p("\n§2b Published figures vs this implementation (D12 a)")
PUB = {"(a)": (13, 8), "SH": (9, 4), "K1c": (9, 4), "R15ref": (10, 5), "C-Σ": (10, 5),
       "R15ref-R14": (11, 6)}
for k, pub in PUB.items():
    got = summary[(k, "a")]
    p(f"  {k:11} published {pub[0]}/{pub[1]}  computed {got[0]}/{got[1]}  {'AGREE' if got == pub else 'DISAGREE'}")

# §3 invariants and boundaries
p("\n§3a Invariant: E3-REL outside the guard rows L under each K1 value")
for rd in ("S", "S'"):
    seen = set()
    for k in K1:
        for d12 in ("a", "b"):
            seen.add((d12, tuple(r for r in e3rel(verdicts(encode(k), rd), d12) if r not in GUARD)))
    for d12 in ("a", "b"):
        vals = {s for (d, s) in seen if d == d12}
        p(f"  {rd:3} D12({d12}): {len(vals)} distinct value(s): {[fmt(list(x)) for x in vals]}")

p("\n§3b Rows that move between S and S′ (= S″), T-A, base encoding (a)")
vS, vP, vPP = (verdicts(base, rd) for rd in READ)
for r in TA:
    if vS[r] != vP[r]:
        p(f"  {r}: S={vS[r]}  S′={vP[r]}  S″={vPP[r]}")
p("  rows where S′ ≠ S″: " + str([r for r in TA if vP[r] != vPP[r]]))

p("\n§3c K2 (class rule) off: effect on R12 and on E3-REL")
for rd in ("S", "S'"):
    v_on, v_off = verdicts(encode(), rd), verdicts(encode(class_rule=False), rd)
    moved = [f"{r}: {v_on[r]}→{v_off[r]}" for r in TA if v_on[r] != v_off[r]]
    p(f"  {rd:3}: moved {moved};  E3-REL D12(a) on {len(e3rel(v_on, 'a'))}, off {len(e3rel(v_off, 'a'))}"
      f"; D12(b) on {len(e3rel(v_on, 'b'))}, off {len(e3rel(v_off, 'b'))}")

p("\n§3d K18: T2 'rests on' C-ids as item source (every T-A row)")
for comp in ("I", "II"):
    for k in K1:
        if comp == "I" and k not in ("(a)", "SH", "K1c"):
            continue
        res = []
        for d12 in ("a", "b"):
            rel = {rd: e3rel(verdicts(encode(k, k18=comp), rd), d12) for rd in ("S", "S'")}
            res.append(f"D12({d12}) {len(rel['S'])}/{len(rel["S'"])}")
        rel = {rd: e3rel(verdicts(encode(k, k18=comp), rd), "a") for rd in ("S", "S'")}
        gv = verdicts(encode(k, k18=comp), "S")
        p(f"  composition {comp:2} K1={k:11} {'; '.join(res)}  S: {fmt(rel['S'])}; S′: {fmt(rel["S'"])}")
        p(f"      guard verdicts: " + " ".join(f"{g}={gv[g]}" for g in GUARD))

print("\n".join(out))


# ================================================================ §4 comparison (added AFTER §0–§3 were complete)
# Reads only the published OUTPUT files (kc.out, readiness.out); imports no code.
import ast, os, re

HERE = os.path.dirname(os.path.abspath(__file__))
KC_OUT = os.path.join(HERE, "..", "..", "KC-CONVENTIONS-scripts", "kc.out")
RD_OUT = os.path.join(HERE, "..", "..", "KC-1-READINESS-scripts", "readiness.out")
out2 = []
q = out2.append
q("\n§4 Comparison with existing outputs (T-A rows only; per-row verdicts and E3-REL lists)")


def kc_lists(path):
    sets, cur = {}, None
    for line in open(path, encoding="utf-8"):
        m = re.match(r"## (.+)", line)
        if m:
            cur = m.group(1).strip()
        m = re.match(r"\s+lists (S'?):\s+(\{.*\})\s*$", line)
        if m and cur:
            d = ast.literal_eval(m.group(2))
            sets.setdefault(cur, {})[m.group(1)] = {r: k for k, rows in d.items() for r in rows}
    return sets


KCMAP = {"(a)": "T3/FZ (status quo)", "K1c": "C (content)", "SH": "C-SH (content; guards all empty)",
         "R15ref": "C-R15ref (R15 reads R3 via its row reference)",
         "R15ref-R14": "C-R15ref-R14 (also R14 \"may coincide\")"}
kcl = kc_lists(KC_OUT)
ndis = 0
for k, name in KCMAP.items():
    for rd in ("S", "S'"):
        mine = verdicts(encode(k), rd)
        theirs = kcl[name][rd]
        dif = [f"{r}: ref={mine[r]} kc={theirs.get(r)}" for r in TA if mine[r] != theirs.get(r)]
        ndis += len(dif)
        q(f"  kc.out [{name}] {rd:2}: T-A verdict disagreements: {dif if dif else 'none (24/24 agree)'}")

rd_rel = {}
for line in open(RD_OUT, encoding="utf-8"):
    m = re.match(r"\s+K1=(\S+)\s+(S'?)\s+\{'(\d+):([^']*)'", line)
    if m:
        rd_rel[(m.group(1), m.group(2))] = m.group(4).split(",")
RDMAP = {"(a)": "a", "SH": "b-SH", "K1c": "c-K1c", "R15ref": "c-R15ref",
         "R15ref-R14": "c-R15ref-R14", "C-Σ": "c-Sigma"}
for k, key in RDMAP.items():
    for rd in ("S", "S'"):
        mine = e3rel(verdicts(encode(k), rd), "a")
        theirs = rd_rel.get((key, rd))
        ok = theirs == mine
        ndis += 0 if ok else 1
        q(f"  readiness.out §3 K1={key:13} {rd:2} E3-REL(D12a): {'AGREE' if ok else 'DISAGREE'} ({len(mine)})")
q(f"  total disagreements: {ndis}")
print("\n".join(out2))
