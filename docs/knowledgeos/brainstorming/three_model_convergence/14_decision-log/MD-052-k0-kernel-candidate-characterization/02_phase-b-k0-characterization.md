# MD-052 Phase B — K0 Characterization (Cold-Verified Against Source, Not Assumed)

**Method**: every claim below is checked directly against the verbatim text of `K0`, `A4`, `A5`,
`A7`, `A8`, `A9`, `A10`, `AM`, `00-INDEX` as actually read this turn — not against this phase's own
earlier prose summary. Where a claim's ultimate support lies in a file **not** opened this phase
(most commonly the `findings/TV-F-001…019` series, or the earlier registers `A1/A2/A3/A3W/A3X/A6`),
that dependency is named explicitly and the claim is tagged accordingly — it is not treated as
verified.

## Evidence-level legend (per authorization)

`SS` SOURCE-STATED · `FD` FORMALLY-DERIVED (a real proof is present in an opened file) · `CD`
CONDITIONAL (stated as true only given named assumptions) · `RC` RECONSTRUCTED (this phase's own
inference from opened material) · `HP` HYPOTHETICAL · `UR` UNRESOLVED (rests on an unopened source).

## P1–P7 (the seven frames)

`SS`. Verbatim in `K0` §2.1: Observation frame (`W`,`O`,`Ω`), Property frame (`g`,
`Identifiable(g,Ω)`), Status-order frame (finite poset, `Candidate⋖Supported⋖Accepted`, boundary edge
`Accepted⋖Committed`), Gap frame (`𝒦`, `R`, `𝒮_gap`, `ev`, `Zero`), Evidence frame (`E`, `~`, `≺`,
`s`, `(S⁺,S⁻)`, `N`), Dynamics frame (`ℰ`, `δ`, `H`, `Replay`), Gate frame (`Auth`, `Adm_i`, 3-valued
conjunction). Each carries a source anchor (e.g. "031 §17–18") pointing into the raw research track —
**those anchors were not chased down this phase**; P1–P7's *existence as K0's own stated frames* is
`SS`, but whether each anchor document actually supports the frame as described is `UR`.

## KA1–KA7 (the seven assumptions)

`SS`, verbatim in `K0` §2.2. Two are self-flagged by K0 itself as weak points: KA3 ("currently
UNCONSTRUCTED, MV-F-22") and KA5 ("corpus violates it in use, C-014") — K0 is transparent about its
own assumption failures, not hiding them.

## Removal / irredundancy test

`SS`, `K0` §2.4: a table mapping each frame's removal to specific lost theorems, concluding "Nothing
in the candidate is removable; the candidate is *irredundant*. Sufficiency for the FULL claimed
theory is NOT asserted." **This is an argued claim, not a formally derived one** — no proof is given
that *no other combination* of the remaining six frames could reconstruct the lost theorem by an
unconsidered route; the argument is "this specific theorem's stated proof in A4 cites this frame,"
which establishes *sufficiency of the frame for that proof*, not *necessity in the strongest sense*
(that no alternative proof exists). Tagged `SS` for the claim, `RC` for this phase's own qualifying
observation about its actual strength. K0's own text is honest about the second half of this same
gap ("sufficiency... NOT asserted").

## T-K1–T-K10

`FD` — genuinely verified as **present as real mathematical arguments**, not mere assertions: `A4`
gives every theorem an explicit "Statement," "Proof," and closing "∎," each traced to specific K0
frames/assumptions, plus a mandatory "Non-Consequences" section stating what the theorem does *not*
establish. **What this phase did NOT do**: independently re-derive or line-by-line check the
correctness of any proof (e.g. re-verify the invariance-of-domain argument in T-K7, or re-run the
induction in T-K8 for a hidden gap). This phase confirms the proofs *exist in the stated form and
structure*; it does not certify their mathematical correctness. Six theorems are unconditional
(T-K1/T-K2/T-K4/T-K5/T-K9/T-K10 per `A4`'s own "Register outcome" table), four are explicitly
conditional on named assumption sets (T-K3, T-K6b, T-K8, and T-K7 carries a stated qualifier rather
than an assumption set) — this six/four split is `SS`, directly read from `A4`'s own summary table,
and matches the theorem-by-theorem "Scope" paragraphs that accompany each proof.

## Identity/equivalence calculus gap; η; ladder-transition calculus

All three `SS` as K0's own stated gaps (`K0` §3): the identity calculus is "assumed, not supplied"
(KA3); η's "input is unconstructed"; the ladder gives "the order, not the dynamics" (AF-F-3). **Two
of these three gap-claims are elaborated further in `A5`** — η is listed as **R-01, "REFUTED
(input-insufficiency)," sourced to TV-F-011** — a file this phase did not open. **Tag: `UR`** for the
refutation-strength claim specifically (K0 itself only claims η is "unconstructed"; `A5`'s stronger
"REFUTED" verdict rests entirely on an unopened source and is not independently checked here). The
ladder-transition-calculus gap has no corresponding `A5` entry found this phase — it remains at K0's
own weaker "absent" characterization, `SS`, not escalated.

## The `K_t`-representation-independence claim

**This is the most consequential single claim in the document, and K0 itself does not present it as
established.** Verbatim, `K0` §0: *"None of the corpus's derivable or witnessed results depends on
any specific `K_t` tuple... **⚑VERIFIER INFERENCE, to be adversarially checked at Level 1.**"*
**Tag: `HP`, by K0's own explicit self-labeling** — not downgraded by this phase; K0 already marks it
unverified. `00-INDEX`'s own checkpoint trail records the programme's own session as **"STOPPED
pending supervision"** with the downstream `V2`/`V3`/`G-theory-gap-register` artifacts still
"pending" — there is no evidence in the files read this phase that the promised Level-1 adversarial
check was ever actually run. **This claim is therefore exactly as strong as "an experienced verifier
asserts it, has not yet checked it, and the checking session halted before checking it" — a real,
interesting hypothesis, not a finding.**

## The five-way "kernel" disambiguation

`SS`, `K0` §1's own table — five senses named and distinguished cleanly (K0 itself; the 049
representational tuple; the 162 DDD shared kernel; the constitutional/eleven-laws kernel; the
Kernel-track K1–K8 capacity list). This is a genuinely useful piece of local terminological hygiene,
**internal to K0's own document** — it does not by itself resolve or prevent this reconstruction's
*own*, separately-discovered kernel-name collisions (F1/F2/F5/F7, the "K-1" collision MD-042 already
found) — see Phase C claim 3 for whether it actually helps here or merely adds a sixth taxonomy to
reconcile.

## A5/A7/A8/A9/AM status

All four are internally consistent with `K0`/`A4` wherever cross-checked (e.g. `A5`'s P-01…P-13 table
matches `A4`'s own theorem numbering and verdicts exactly; `A9`'s counterexamples CE-07/08/09
correctly cite T-K6a/T-K6c/T-K7 as their targets). **A meaningful fraction of A5's own content beyond
the T-K-derived rows (P-14 through P-17, R-01 through R-07, the "conditional/open pool") cites
`TV-F-0xx` findings not opened this phase** — tag those specific rows `UR`, not `FD`/`SS` as a blanket
matter. `A7` (computability) and `AM` (measurement) are consistent, disciplined, self-critical
registers (e.g. `AM` explicitly flags its own SNF composite metric as "REJECTED," its own `Readiness`
symbol as "UNDEFINED — BLOCKING") — `SS` throughout, no theorem-grade claims to independently verify.
`A8` (the math↔DDD↔architecture matrix) is the one file that makes claims reaching directly into this
reconstruction's own territory — see Phase D.
