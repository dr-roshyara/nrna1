"""Witnesses against the v1 TEXT of §1.14.1 (text-literal; no assumption added beyond what is quoted).
Each check prints the quoted rule it encodes. This is evidence about the text, not a proof of anything else."""
T, F, U = 'T', 'F', 'U'
def NOT(a): return {T: F, F: T, U: U}[a]
def AND(*v): return F if F in v else (U if U in v else T)
def OR(*v): return T if T in v else (U if U in v else F)

print("W1  F-1: mutual grant annulment, rho=now, both acts non-root")
# Text: 'authorized(x) = OR_B AND (i) every grant in B has t_x inside its act window'; window closes at an
# effective ending 'only if its act is authorized (with upsilon deciding how U counts)'.
# a1 annuls g1 (backdated to 0) citing g2; a2 annuls g2 (backdated to 0) citing g1.
for ups in ('counts as undetermined', 'does not count until evidenced'):
    eff = (lambda v: v) if ups.startswith('counts') else (lambda v: T if v == T else F)
    v = {'a1': U, 'a2': U}; seen = []
    for step in range(8):
        seen.append((v['a1'], v['a2']))
        v = {'a1': NOT(eff(v['a2'])), 'a2': NOT(eff(v['a1']))}   # window(g2) open iff a2 has no effect, etc.
    fps = [(p, q) for p in (T, F, U) for q in (T, F, U) if (NOT(eff(q)), NOT(eff(p))) == (p, q)]
    print(f"    upsilon = {ups}: iteration {seen[:5]} ... fixed points {fps}")

print("W2  F-8: two acts at the same act time each consume one single-use grant (half-open windows in act time)")
# Text: window = [start, first effective terminal ending); 'an ending caused by x' ignored when judging x.
# x1, x2 at t=5. Consumption caused by x2 ends g at t=5, so for x1: 5 in [0,5)? no -> x1 depends on x2 having effect.
v = {'x1': U, 'x2': U}; seen = []
for _ in range(6):
    seen.append((v['x1'], v['x2'])); v = {'x1': NOT(v['x2']), 'x2': NOT(v['x1'])}
print("    upsilon=undetermined iteration:", seen[:4], "-> both stay U although the record order (s1 < s2) is known")

print("W3  F-7: grant with a recorded end of period, no ending ACT")
# Text: act window = [start, first effective terminal ending) ... 'Only endings made by authorized acts count'.
grant = dict(start=0, end_of_period=4); endings_by_acts = []
inside = lambda t: grant['start'] <= t and all(False for _ in endings_by_acts)
print("    act at t=9 inside the window as written:", inside(9), "(case 3 and 'expired permission' say it must be outside)")

print("W4  F-3: grant issued by an unauthorized act")
# Text: 'A grant has a holder, permitted activities, a scope, a start ... all as recorded.' No condition on its issuing act.
issue_act = dict(root=False, cited=[])   # explicitly cites nothing -> authorized = F
grant_usable_per_text = True             # (i) checks only the window of the grant; nothing refers to issue_act
print("    authorized(issue act) =", F, "; an act under the grant passes condition (i):", grant_usable_per_text)

print("W5  F-4: parent grant ended before the child's act")
# Child g issued under parent h at t=1; h ended by a root act at t=3; x under g at t=5.
# Text: window(g) closes only through endings OF g; P acts only in condition 4 (claims), not in (i).
print("    authorized(x) per text = T for every value of P (case 11 'ends with it if its kind's policy says so' is not derivable)")

print("W6  F-5/F-6: headline vs value")
print("    no adoption on record: formula = OR over an empty set =", OR(), "; headline under C1-b = 'cannot determine' (a U-headline for value F)")
adoption_annulled = AND(T, NOT(T))   # condition 1: x authorized AND not annulled; annulled with effect
print("    adoption annulled with effect: value =", adoption_annulled, "; causal list has no 'annulled' entry -> no headline")
