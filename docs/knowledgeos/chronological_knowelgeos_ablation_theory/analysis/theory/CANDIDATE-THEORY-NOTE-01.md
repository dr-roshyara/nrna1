# Candidate theory note 01 — transition semantics from released evidence (L0-REL-03/04/06/07)

⚠ authority: generated · research hypothesis · not canonical · not a model selection · formal basis of the MT comparison: SECONDARY-REPRODUCED.
Layers are kept apart: **FACT** (released wording) → **INTERPRETATION** → **CANDIDATE INVARIANT / RULE** → **ATTACK**.

## 1. What the sources actually state (FACT)

| # | Source (released lines) | Statement |
|---|---|---|
| F1 | Metamodel §6 L104 | four axes, marked ⊥: `authority` (generated → … → authoritative → historical), `status` (idea → … → frozen/sealed \| superseded → archived), `maturity` (Candidate → Observed → Validated → Standard, **Learning only**), `adoption` (planned → adopted → deprecated → removed, **Runtime only**) |
| F2 | Metamodel §6 L104 | "Illegal combinations are rejected (precedent: `frozen`+`generated`)" |
| F3 | Metamodel §6 L104 | evolution rules ("when may X become Y") are hosted in ES-006.1, the Plan Concept and R-39's multi-context bar; this metamodel "indexes, never restates" |
| F4 | ES-006.1 L17 | ladder Research → Pilot → Qualification → Engineering Standard → Stable Engineering Capability (forward only as written) |
| F5 | ES-006.1 L20 | "everything is promoted because operational evidence demonstrated necessity"; "no reference architecture needed" is a **SUCCESS** outcome of a qualification |
| F6 | CAP-001 §9 L172/L195 | the record is canonical; counters are **derived** from it ("recompute, never edit") |
| F7 | CAP-001 §9 L193 | "absence of evidence is not PASS" (the INCONCLUSIVE verdict) |
| F8 | CAP-001 §9 L228 (OE-2) | a WARN requires human **disposition**; the tool "reports the hazard … it does not prescribe" |

F6–F8 concern identifier integrity. They are used below **only as analogical hypotheses**, never as evidence about knowledge promotion.

## 2. Semantic mechanisms identified (INTERPRETATION)
1. **Standing is multi-dimensional, not one bit.** The MT family's single `ad` (adopted) bit compresses at least three knowledge axes: authority, status and maturity.
2. **Axes are typed by object kind.** `maturity` applies to Learning only, `adoption` to Runtime only. So an axis is a *partial* function of the object kind.
3. **Terminology collision.** The metamodel's "adopted" is a **Runtime** axis value. It must not be read as knowledge adoption or promotion.
4. **Promotion is a step on a ladder (F4)** with a necessary evidence condition (F5). It is not a binary transition.
5. **Qualification is an evaluation event with at least two successful outcomes**: promote, or "not needed". Non-promotion is not failure.
6. **The exits from standing that the sources name are *supersession* (status → superseded → archived) and *historicization* (authority → historical).** No released line names demotion, revocation or invalidation.
7. **Two orderings:** the ES-006.1 ladder (F4) and the maturity axis (F1) both end in a "Standard"-like rung. **Whether they are the same dimension is not stated** (open).

## 3. Candidate invariants (CANDIDATE; each labelled with its support)
- **I1 (F1+F2, stated):** the state is a product of typed axes, restricted by a set of illegal combinations. Only one is declared: ¬(status = frozen ∧ authority = generated).
- **I2 (F4, as written):** the ladder rung is monotone under the stated moves. *Only forward moves are stated; reverse moves are not asserted to be absent* (¬stated ⇏ impossible).
- **I3 (F5, stated as necessary):** Promote(x, t) → ∃ t′ ≤ t · DemonstratedNecessity(x, t′). **Necessary, not sufficient.** The authorizing actor and **the timing (t′ = t_a or t′ = t_p) are not stated.**
- **I4 (F1, stated):** Kind(x) = Learning → maturity is defined; Kind(x) = Runtime → adoption is defined.
- **I5 (derived by computation, §5):** entering `frozen` requires that authority has already left `generated` (holds in every variant).
- **H1 (hypothesis from F6, analogy only):** standing is a *function of an append-only record* (derived, never maintained). If true for knowledge, revocation would be a new record event, not an in-place state edit.
- **H2 (hypothesis from F8, analogy only):** machine or evidence verdicts do not change standing; a human disposition does. **If this transferred to knowledge promotion it would favour P1 (explicit revocation) over P0 (automatic invalidation). It is recorded as a hypothesis only, not as evidence; the engine is unchanged.**

## 4. Candidate transition rules (CANDIDATE)
- **Promote(x):** rung := next(rung). Pre: DemonstratedNecessity (I3). Authorizer: unstated. Effect on authority, status and maturity: unstated.
- **Qualify(x) → {promote, not-needed}:** both are success outcomes (F5).
- **Supersede(x, y):** status(x): → superseded → archived. It presupposes a successor y. **This is an identity-changing transition across two objects.**
- **Historicize(x):** authority(x): → historical. Trigger unstated.
- **Not stated anywhere released:** Demote, Revoke, Invalidate, Reassess, Revalidate, Expire (explicitly: "no research-expiry rule").

## 5. Logical attack by computation (`axes_check.py`)
- **Method:** a Learning object; the axes of F1 with forward single-axis moves; F2 as the only illegal combination; three readings of the unstated status fragments. A result counts only if it holds in **all** variants.
- **Robust results:**
  - **(a) I5:** authority must leave `generated` before `frozen` is entered. This is an ordering nobody stated; it follows from F1 + F2.
  - **(b) Undetermined but reachable:** the combinations *historical + idea*, *Standard + idea* and *authoritative + archived* are all reachable, and no released rule says whether they are legitimate.
  - **(c) Under-constraint:** every one of the 44–56 legal combinations is reachable, and only one illegal combination is declared.
- **Variant-dependent:** whether *generated + sealed* is reachable depends on whether "frozen/sealed" means alternatives or a chain (V1/V3: yes; V2: no). So the precedent "frozen+generated" may or may not extend to `sealed`.
- **Conclusion (formal, not historical):** either coupling rules exist in the not-yet-read hosts (ES-006.2–.4, the Plan Concept, R-39's bar), or "orthogonal" is an over-claim and the joint space needs more illegal combinations. Either way this is a **testable** question about the corpus.

## 6. Contradictions and ambiguities
- **A1:** F5 grounds promotion in evidence but does not fix **when** the grounding is checked. This is exactly the MT1 vs MT2 (TOCTOU) question, and it stays open.
- **A2:** it is not stated whether ES-006.1's ladder and the maturity axis are the same dimension. "Standard" appears in both.
- **A3:** it is not stated whether `frozen/sealed` means alternatives or a sequence. This changes reachable combinations (§5).
- **A4:** "R-39's multi-context bar" (F3) calls R-39 bar-*defining*. Its exception semantics remain unstated in released lines.
- **Recorded non-contradiction:** "orthogonal" (F1) and "illegal combinations" (F2) are compatible only as *orthogonal generation with a constraint set*. That is how I1 is phrased.

## 7. Are MT1/MT2/P0/P1 adequate?
- **As a discriminating experiment: yes.** They isolate two real questions: check timing (EQ-2) and post-promotion loss (EQ-3/EQ-4).
- **As the final ontology: no, on four counts.** They lack:
  - (i) typed, multi-axis standing (a single `ad` bit);
  - (ii) a multi-rung ladder;
  - (iii) **supersession**: an exit that involves a successor object, which single-object MT cannot express;
  - (iv) a qualification event with a successful non-promotion outcome.
- **In addition:** P0 (automatic invalidation) and P1 (explicit revocation) are both **unattested** exits. The attested exits are supersession and historicization.

## 8. Is a new model family required?
- **A candidate extension is warranted, but not built now.** Call it **MK: typed multi-axis standing**. The object has a kind; typed axes (authority, status, maturity or adoption), a ladder rung, and evidence; an illegal-combination set; and an event record. Its transitions are Promote, Qualify, Supersede(x, y) and Historicize.
- In MK, the MT questions become **projections**: EQ-2 = the timing of I3; EQ-3/EQ-4 = which exits exist.
- Building MK now would outrun the evidence. The minimal next step is the evidence below, then a pre-registered MK spec only if the hosts confirm the structure.

## 9. Smallest next evidence
1. **ES-006.2–.4** (L22–49; the same file, already hash-verified): the neighbouring hosted rules. These are the likeliest place for coupling rules (§5b/c), exit rules (EQ-3/EQ-4) and whether the ladder equals maturity (A2).
2. **R-37** (the burden-of-proof rule that ES-006.1 applies to promotion): the likeliest place for the **timing** of the evidence condition (A1).
3. Deferred: the Plan Concept L36 (self-declared "NOT ruled"); R-39's bar (off the critical path).
