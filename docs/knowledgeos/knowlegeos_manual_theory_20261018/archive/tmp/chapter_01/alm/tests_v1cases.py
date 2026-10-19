"""Regression tests: the v1 §1.14.1 cases (CH-01-v1-reconstructed.md, cases 1-15) run against
the UNCHANGED ALM baseline (alm.py == alm_v1.py, sha256 ebc1386a80731c35...).

Each test states v1's expected answer and reports AGREES / DIVERGES / NOT-EXPRESSIBLE.
A divergence is a finding about ALM, not automatically a defect: v1 itself is a
conditional model with known defects. Run: python3 tests_v1cases.py
"""
from alm import Act, Model, INF

ALL = frozenset({("PERFORM", "*"), ("ADOPT", "*"), ("END_CLAIM", "*"),
                 ("GRANT", "*"), ("REVOKE", "*"), ("CONFIRM", "*")})
AD = frozenset({("ADOPT", "proc")})
rows = []


def row(case, v1_expected, alm_answer, status, note=""):
    rows.append((case, v1_expected, alm_answer, status, note))


def subject_current(M, H, subject, t):
    return any(M.current(H, a.id, t) for a in H if a.kind == "ADOPT" and a.subject == subject)


M = Model()

# Case 1: alternative grants; g1 revoked; current through g2.
H = [Act("g1", "R", "GRANT", 1, holder="chair", scope=AD),
     Act("g2", "R", "GRANT", 1, holder="chair", scope=AD),
     Act("r1", "R", "REVOKE", 2, target="g1"),
     Act("A", "chair", "ADOPT", 3, subject="proc")]
row(1, "current", M.current(H, "A", 4), "AGREES" if M.current(H, "A", 4) else "DIVERGES",
    "ALM is existential over grants; v1 uses CITED bases (see case 9)")

# Case 2: joint authority, suspension.
row(2, "suspended during suspension", "n/a", "NOT-EXPRESSIBLE",
    "ALM has no joint bases (C4) and no suspension ending kind")

# Case 3: grant ended before the act -> not validly made.
H = [Act("g", "R", "GRANT", 1, holder="chair", scope=AD, period=(1, 2)),
     Act("A", "chair", "ADOPT", 3, subject="proc")]
row(3, "not validly made", M.validity(H)["A"], "AGREES" if M.validity(H)["A"] is False else "DIVERGES")

# Case 4: consumed grant.
row(4, "current if the adoption consumed g; else per P", "n/a", "NOT-EXPRESSIBLE",
    "ALM baseline has no consumption (single-use) at all - M2 G-3/C13 not covered")

# Case 5: supersession by an invalid successor.
row(5, "sigma=standalone: superseded; conditional: current", "n/a", "NOT-EXPRESSIBLE",
    "ALM has END_CLAIM only; no supersession-with-successor (C5 sigma)")

# Case 6: annulment of a grant with a backdated effective time.
row(6, "rho=now: not validly made; rho=at-the-time: validly made", "n/a", "NOT-EXPRESSIBLE",
    "ALM has no annulment and no effective time distinct from act time")

# Case 7: re-adoption after revocation: current, revoked, current again (subject level).
H = [Act("A1", "R", "ADOPT", 1, subject="proc"),
     Act("x", "R", "END_CLAIM", 2, target="A1"),
     Act("A2", "R", "ADOPT", 3, subject="proc")]
seq = [subject_current(M, H, "proc", t) for t in (1, 2, 3)]
row(7, "current, revoked, current", seq, "AGREES" if seq == [True, False, True] else "DIVERGES")

# Case 8: validity ran out and revoked -> headline 'revoked', both reasons listed.
H = [Act("A", "R", "ADOPT", 1, subject="proc", claim_period=(1, 2)),
     Act("x", "R", "END_CLAIM", 3, target="A")]
row(8, "not current; headline revoked + reasons", M.current(H, "A", 4), "PARTIAL",
    "value agrees (not current); ALM has no headline/reasons (C8)")

# Case 9: no cited grant / unknown citation.
row(9, "explicit none: not validly made; unknown: U or F by kappa", "n/a", "NOT-EXPRESSIBLE",
    "ALM does not model citations: it searches for ANY covering grant. Semantic difference "
    "from v1 (validity relative to the cited basis)")

# Case 10: late-recorded cancellation; another act relying on the grant, between.
H = [Act("g", "R", "GRANT", 1, holder="chair", scope=AD),
     Act("r", "R", "REVOKE", 2, target="g"),          # 1 March
     Act("B", "chair", "ADOPT", 3, subject="proc")]   # 5 March
pos = {"g": 1, "B": 2, "r": 3}                          # revocation recorded 10 March
at_time = M.recognize_valid(H, pos, 2, "B", now=100, lam=0)
now_view = M.recognize_valid(H, pos, 3, "B", now=100, lam=0)
ok = at_time == "AUTHORIZED" and now_view == "NOT_AUTHORIZED" and M.validity(H)["B"] is False
row(10, "at-the-time: authorized; now: not", (at_time, now_view, "V=" + str(M.validity(H)["B"])),
    "AGREES" if ok else "DIVERGES",
    "ALM's V equals v1's rho=now; ALM's R at the act's own position equals rho=at-the-time")

# Case 11a: unauthorized ending of the chair's grant: no effect.
H = [Act("g", "R", "GRANT", 1, holder="chair", scope=AD),
     Act("r", "mallory", "REVOKE", 2, target="g"),
     Act("A", "chair", "ADOPT", 3, subject="proc")]
row("11a", "no effect", M.validity(H)["A"], "AGREES" if M.validity(H)["A"] else "DIVERGES")

# Case 11b: authorized ending: later approvals not validly made.
H = [Act("g", "R", "GRANT", 1, holder="chair", scope=AD),
     Act("A0", "chair", "ADOPT", 2, subject="proc"),
     Act("r", "R", "REVOKE", 3, target="g"),
     Act("A", "chair", "ADOPT", 4, subject="proc")]
v = M.validity(H)
row("11b", "later not validly made; earlier per P", (v["A0"], v["A"]),
    "AGREES" if v["A0"] and not v["A"] else "DIVERGES", "earlier: kappa (= RD 1 P)")

# Case 11c: delegated authority; the chair's own grant ends -> does the delegate's end? (RD 1, delta)
H = [Act("gc", "R", "GRANT", 1, holder="chair", scope=frozenset({("GRANT", "*"), ("ADOPT", "proc")})),
     Act("gs", "chair", "GRANT", 2, holder="sec", scope=AD),
     Act("r", "R", "REVOKE", 3, target="gc"),
     Act("S", "sec", "ADOPT", 4, subject="proc")]
row("11c", "per RD 1 (delta); v1 leans yes for delegated grants", M.validity(H)["S"],
    "HIDDEN-POLICY" if M.validity(H)["S"] else "AGREES-WITH-delta=yes",
    "ALM baseline silently fixes delta = NO: a delegate outlives the revoked delegator")

# Case 12: same moment: adoption and revocation of the claim share an act time.
H_after = [Act("A", "R", "ADOPT", 1, subject="proc"), Act("x", "R", "END_CLAIM", 1, target="A")]
H_before = [Act("x", "R", "END_CLAIM", 1, target="A"), Act("A", "R", "ADOPT", 1, subject="proc")]
a1, a2 = M.current(H_after, "A", 1), M.current(H_before, "A", 1)
row(12, "revocation after adoption: revoked; before: current", (a1, a2),
    "AGREES" if (a1, a2) == (False, True) else "DIVERGES",
    "ALM V3(c) uses tau(x) <= t, ignoring the A0 order between ADOPT and END_CLAIM")

# Case 13: mutual annulment.
row(13, "U under rho=now; defined order under at-the-time", "n/a", "NOT-EXPRESSIBLE",
    "no act annulment in ALM; the grant analogue (mutual revocation) is resolved by A0, not U")

# Case 14: no adoption on record.
H = [Act("A", "R", "ADOPT", 1, subject="proc")]
r14 = M.recognize_current(H, {}, 0, "A", 2, now=100, lam=INF)
row(14, "cannot determine (C1-b) / never adopted (C1-a)", r14,
    "AGREES(C1-b)" if r14 == "UNDETERMINED" else "DIVERGES")

# Case 15: revocation whose authority cannot be checked.
row(15, "U (upsilon=undetermined) or current with warning", "n/a", "NOT-EXPRESSIBLE",
    "ALM baseline has no evidence quality: a recorded revocation is either valid or not (RD 6 not covered)")

if __name__ == "__main__":
    w = max(len(str(r[0])) for r in rows)
    for case, exp, got, st, note in rows:
        print(f"case {str(case).ljust(w)}  {st:22s} v1: {exp}  |  ALM: {got}  {('- ' + note) if note else ''}")
    from collections import Counter
    print("\nsummary:", dict(Counter(r[3].split('(')[0] for r in rows)))
