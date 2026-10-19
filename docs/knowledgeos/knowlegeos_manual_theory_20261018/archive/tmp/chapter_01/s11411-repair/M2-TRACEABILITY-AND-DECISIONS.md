# M2: resolution status of every finding, and the decision brief reconciled to reserved decisions 1–6

**What the statuses mean:**
- **resolved in candidate** = M2 has a definition plus a proof or regression test addressing it. It is **not independently validated** until the second review reports.
- **parameterized** = the mathematics is defined for every value, and the value is a reserved decision.
- **partial** = some of it is addressed.
- **unresolved** = not addressed.

Evidence references point to `M2-MODEL.md` (§), `M2-PROOFS-TESTS-COMPARISONS.md` (A–D) and the test names in `tests_m2.py`.

## 1. Original six v1 defects and the additional problems

| ID | Status | Evidence |
|---|---|---|
| V1-a fixed point under υ | resolved in candidate (existence for every option) + parameterized (υ, reserved decision 6; record-order rule) | T1, T2, T2′; tests `V1-a …` |
| V1-b issuance | resolved in candidate | MODEL §3 w0; test `V1-b` |
| V1-c delegation | resolved in candidate + parameterized (δ, reserved decision 1; C15) | MODEL §2–3; tests `V1-c …`, `F-1`, `F-10` |
| V1-d totality | resolved in candidate. **ω = intersection is outside M2 (partial)** | T4; tests `V1-d …` |
| V1-e expiry | resolved in candidate + parameterized (β, C14) | MODEL §3 w2; tests `V1-e …` |
| V1-f time domains | resolved in candidate: points, Ax1–Ax2, bitemporal *k*; parameterized ρ | MODEL §1; tests `V1-f`, `F-5`, `F-6`, `F-7` |
| NEW-1 Property 2 | resolved by restatement: P2a proved under premises; P2b declared false | A: P2a, P2b; test `F-14` |
| NEW-2 late early ending | parameterized (late, reserved decision 2) | tests `NEW-2 …` |
| NEW-3 case 4 vacuous | resolved by restatement: meaningful for quota grants | MODEL §3 quota |
| RV-C1 delegation fidelity | resolved: narrowing is [SRC AMS L285, L92–94]; "a new act is required" is [SRC AMS L189] (verified by the author; the review had L190) | MODEL §2–3 |

## 2. The 21 review findings

| F | Status | Evidence / note |
|---|---|---|
| F-1 | resolved in candidate | parents {∅}; test `F-1`; mutation check |
| F-2 | resolved in candidate | recursive V; test `F-2`; mutation check |
| F-3 | resolved in candidate (agreement with v1 **under stratified views only**) | T2′; test `F-3`; mutation check |
| F-4 | resolved in candidate (uses inside υ) + parameterized | test `F-4 …` |
| F-5 | resolved: inclusive **derived from v1 case 12**; exclusive is an option | A "Question instant"; test `F-5 …` |
| F-6 | parameterized: at-act removes the dependence on the act's own recording delay | tests `F-6 …`; comparison D |
| F-7 | resolved in candidate: the bitemporal question makes the treatment symmetric | tests `F-7 …` |
| F-8 | parameterized (consume_view, C13) | tests `F-8 …` |
| F-9 | resolved in definition (debit through a satisfied basis) + parameterized (C13) | test `F-9` |
| F-10 | resolved in candidate (activity containment; Kleene order) | tests `F-10 …` |
| F-11 | parameterized (κ_g per C1) + **open question Q1** (does the human-act reference count as the issuing act?) | tests `F-11 …`; comparison C #13 |
| F-12 | resolved ("by *t*") | tests `V1-d`, `F-12` |
| F-13 | resolved by v1's own rationale; union kept as C8 | test `F-13`; mutation check |
| F-14 | resolved by restatement (P2b false, P2a proved) | test `F-14` |
| F-15 | resolved: static premise plus the bitemporal question | P4; test `F-15` |
| F-16 | resolved (T3's statement corrected) | T3 consequence (iii) |
| F-17 | resolved: wording "least"; the view of the C layer is K_k | T1 scope |
| F-18 | resolved: [BOOK]/[SRC] split; λ / late renamed; one numbering (this table); the interval convention parameterized | MODEL tags; §3 below |
| F-19 | **unresolved (declared outside M2)**: ω = intersection | MODEL §6 |
| F-20 | resolved in candidate + parameterized (C6); P2a scoped against conflicting claims | test `F-20 …`; A P2a |
| F-21 | parameterized; **T3 does not hold under consume_view = now (counterexample class)** | tests `F-21 …`; T3 |

**Totals:** 15 resolved in candidate · 5 parameterized or partly · 1 unresolved (F-19, declared). **None is independently validated yet.**

## 3. Reconciled decision brief: one numbering only

Reserved decisions (RD) 1–6 and alternatives C1–C13 are the book's existing IDs. **New items continue the C-series** (C14–C16) instead of starting another scheme. The superseded D-1…D-7 brief stays NOT FOR DECISION and is replaced by this table. Reviewer questions Q1–Q14 are mapped in the column "Q".

| ID | Q | Policy question | Alternatives | Business consequences | Source evidence | AI recommendation | Needed before the M2 **text** is final? |
|---|---|---|---|---|---|---|---|
| **RD 1** (ending policy P) | Q4(a) | which grant endings carry over to claims, per kind; **including whether a delegate falls with the delegator (δ)** | per kind and ending: carry over / not. δ yes / no | δ yes: no orphan delegates, but work stops at a term end unless re-authorized. δ no: delegates outlive their delegator | AMS L285 (distinguish), L189 (a new act is required); book §1.13.3(d) leans yes, "sources do not rule" | δ yes for delegated grants. P otherwise per kind, still open | **yes** (δ) |
| **RD 2** (record view, L-RT) | Q2, Q3, Q10, Q6(e), Q11 | (a) ρ: at-recording / at-act / now; (b) late: does an ending unseen when the act was judged reach its product once known?; (c) λ: a grant recorded after the act that cites it; (d) consume_view: view / now | see comparison D | at-act: reproducible, no cycles, filing delay irrelevant. now: strict and retroactive. late = reach: good faith, but known endings take effect. consume_view = now: at most *n* uses, but cycles possible | safeguard 3 wording; v1 case 10; no source rule | ρ = at-act; late = reach; λ = U; consume_view: **your trade-off** (D) | **yes** |
| RD 3 (P-E) | — | unchanged | — | — | — | — | no |
| RD 4 (ι) | — | unchanged | — | — | — | — | no |
| RD 5 (OQ-24) | — | unchanged | — | — | — | — | no |
| **RD 6** (unverifiable cancellations, υ) | Q7, Q6(d) | count as undetermined / do not count (greedy, record order); do uses fall under υ? | undetermined: an unverifiable entry can freeze decisions. Does-not-count: no freeze; under ρ = now, which of two mutually annulling entries counts depends on record order | safeguard 6 | does-not-count, **including uses**; with ρ = at-act the order dependence disappears (T2′) | **yes** |
| C1 | Q1, Q13 | record constitutes / represents; **does the human-act reference on a grant count as its issuing act?** | — | C1-a: a missing record means no authority. C1-b: it means "cannot determine". If the reference counts, today's grants behave as in v1 | AMS L49–51 vs IA L99–101 | C1 open. Treat the reference as the issuing act (AM-3, AMS L286 requires one) | **yes** (affects every live grant) |
| C2 (κ) | — | unknown citation | constitutive / evidential | — | v1 L69 promise | **[DER]** tied to C1 (confirm only) | no |
| C4 (χ, ω) | Q9 | co-signature / pooled; ω whole-or-nothing / intersection | intersection requires structured scopes (none today) | — | live scopes are prose (AMS L104–112, reported) | χ co-signature; ω whole-or-nothing **for now** (intersection not modelled) | no |
| C5 (σ) | Q12 | standalone / conditional; conditional evaluated at *t* or at the time of supersession | — | — | — | standalone | no |
| C6 | Q14 | both stay current / later prevails | later prevails needs a recorded **conflict** relation; Chapter 2 defines conflict only for rules | Ch. 2 §2.3 | both-current until a resolving act (the book's explicit-resolution alternative) | no |
| C7 (π) | — | missing period | undetermined / open-ended | — | AMS L123 states "permanent" as a **defect** | undetermined | no |
| C8 | Q8 | headline aggregation and order | progress (M2 default, derived from v1's rationale) / union (v1 literal) / most recent intent | — | v1 L155 rationale | progress | no |
| C10 | (old D-7) | cycles under ρ = now | U / greedy order / no retroactive effect against earlier records | disappears under at-act | — | follows RD 2 | no, if RD 2 = at-act |
| C11 (λ) | Q10 | grant recorded after the act it covers | U / T (retroactive ratification) / F | — | — | U | inside RD 2 |
| C12 | — | can the root change over time? | — | — | — | not modelled | no |
| C13 | Q6(a)(b)(c) | consumption: only by an **authorized** use (M2); which basis is debited (any satisfied basis, M2); does issuance under a grant consume? (no, in M2) | — | an unauthorized "use" cannot exhaust a grant (safeguard 6) | the CONSUMED record: "recorded, not newly authorized" | as M2 | no |
| **C14** (new) | Q5 | expiry instant: half-open / closed | half-open: one holder at a handover instant. Closed: two | IM L131, L211–219; AMS L284; **no rule** | half-open, with date-only periods meaning "to the end of that day" | **yes** (the text states it) |
| **C15** (new) | Q4(d) | must a delegated grant's period lie within the parent's? | yes / no | — | **no source rule**; the book's §1.13.3(a) | yes | no (an option) |
| **C16** (new, technical) | Q11 | question instant inclusive / exclusive | — | — | **[DER] v1 case 12 → inclusive** | inclusive: **confirm only** | no |

**Merged without loss:**
- old D-1 → C1 (ε reading, Q13);
- D-2 → C2;
- D-3 → RD 1 (δ) + C15 (period) + [SRC/DER] (scope, activity, right to delegate);
- D-4 → C14;
- D-5 → C13;
- D-6 → RD 2(b);
- D-7 → C10.

**Settled technically, no decision needed:**
- F-1, F-2, F-12, F-16, F-17;
- the right to delegate ([DER]);
- scope and activity narrowing ([SRC]);
- question instant inclusive ([DER]; confirm only).

## 4. Questions for the Principal (minimal set before final §1.14.1 text)

1. **RD 2:** ρ, late, λ and consume_view.
2. **RD 1:** δ (and P, when ready).
3. **RD 6:** υ, including uses.
4. **C1:** constitutes vs represents; and whether a grant's human-act reference counts as its issuing act.
5. **C14:** the expiry instant.

Everything else either has a recommended default that reproduces v1 or is not needed for the text.
