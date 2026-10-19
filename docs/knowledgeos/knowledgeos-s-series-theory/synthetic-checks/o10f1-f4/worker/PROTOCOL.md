# PROTOCOL — worker A, O-10f1..f4 finite checks (v1)

Written before any code. Synthetic, finite, exhaustive or seeded-random. Checks confirm algebra that is
proved analytically in O-10f1-f4-draft.md; they are NOT independent evidence of the theorems, only a
guard against algebra slips. Primitive: `determines(worlds, view, phi)` = for all w,w' in worlds,
view(w)=view(w') implies phi(w)=phi(w').

## K1 — single-step characterization (O-10f2, Theorem F2-1)
Claim: for W ⊆ X×O, F: X→O, evidence e: X→Ev, view (e(x),o):
Ver(e,W) ⇔ no pair (x,x') with e(x)=e(x'), F(x)≠F(x'), and some o ∈ {F(x),F(x')} with (x,o),(x',o) ∈ W.
Instance: X={0,1,2}, O={0,1,2}; ALL F (27), ALL W ⊆ X×O (512), ALL partitions e of X (5). Exhaustive.
Expected: 0 mismatches. Failure: any mismatch.

## K2 — exact condition for the equivalence (O-10f2, Theorem F2-2)
Claim: [for all e: Ver(e,W) ⇔ e determines F on X_W] ⇔ every F-distinct pair of X_W is confusable
(X_W = x-projection of W). Same exhaustive population as K1. Expected 0 mismatches. Failure: any mismatch.
Also report: number of (F,W) where (⇐) "e determines F ⇒ Ver" fails (expected 0, it is claimed universal).

## K3 — named constrained-fault instances (O-10f2)
(a) X={0,1}, F=id, O={0,1,2,3}, W={(x,F(x)),(x,F(x)+2)}: empty evidence verifiable, does not determine F.
Expected: Ver=True, determines=False.
(b) X={0,1}, F(x)=2x (range {0,2}), W={(x,F(x)),(x,F(x)+2)}: empty evidence NOT verifiable.
Expected: Ver=False.

## K4 — multi-step characterization (O-10f2, Theorem F2-3) and named instance
Claim: n=2, x_t, o_t ∈ {0,1}, W ⊆ ({0,1}×{0,1})^2, d_1=F_1(x_1), d_2=F_2(x_1,x_2); cumulative evidence view at
step 2 = G(x_1,x_2) (any partition of x-histories); view = (G, o_1, o_2). Ver_2 ⇔ no pair of x-histories
identified by G with d_2 different and a common admissible o-history (o_1,o_2) with o_2 ∈ {d_2(x̄),d_2(x̄')}.
Also: under S-multi at step 2, Ver_2 ⇔ G determines d_2 on X_{W,2}.
Population: 20000 seeded-random (seed 1017) instances (random W, F_1, F_2, G partition of 4 histories).
Expected 0 mismatches both claims. Failure: any mismatch.
Named instance: all-honest-or-all-inverted fault model, F_t = x_t, G = x_1 only:
Expected: Ver_2 True; G does not determine d_2; S-multi fails at step 2; marginal (x_2,o_2) admissible set is all 4.

## K5 — rules reading past outputs (O-10f3), named instances
(a) F_1=x_1, F_2=o_1, x ∈{0,1}, unconstrained o, no evidence: local correctness at step 2 verifiable; global
correctness [o_2 = x_1] not verifiable. Expected (True, False).
(b) x_1=0 fixed, x_2=(a,b) ∈ {0,1}^2, F_1 = x_1, F_2 = a if o_1=0 else b, evidence e_2 = a, e_1 = x_1:
global verifiable, local not. Expected (True, False).
(c) Evidence may read o: view (o, c) with c=[o=F(x)] is verifiable; with V={0,1,2}, F=id, unconstrained W,
(o,c) does not determine d (strictly coarser than (o,d)). Expected (True, False).

## K6 — contestability instances (O-10f1)
(a) distrusted attestation: x∈{0,1}, F=id, o free, attestation a. Auditor worlds a=x; party worlds a free.
Expected auditor Ver True, party False.
(b) redacted field: x=(xp,xq)∈{0,1,2}^2, F=[xp>xq], o∈{0,1}; auditor sees (xp,xq,o), party sees (xp,o).
Expected True, False.
(c) private knowledge: no evidence in record; party knows own x privately; auditor sees o only.
Expected party True, auditor False (incomparability).

## K7 — superseded decisions (O-10f4), named instances
(a) current-evidence-only store cannot verify historical decision: worlds = (x recorded at t, x current, D(h_t,t));
two worlds (0,1,0) [correct at t, later corrected] and (1,1,0) [wrong at t, no correction]; current-only view
(x_current, D) identical, c_hist differs. Expected: not verifiable; with bitemporal view (x_t, x_current, D): verifiable.
(b) current-version-only mapping: same structure with versions. Expected: not verifiable / verifiable.

Code: python3 -I -B workerA_checks.py > RESULTS.txt ; hashes recorded in RESULTS.txt header.
