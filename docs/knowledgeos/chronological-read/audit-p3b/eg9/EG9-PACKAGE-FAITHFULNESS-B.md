# EG-9 package faithfulness check (§17 + §13 lines mentioning (g), EG5-V2.8-DECISION-PACKAGE.md)

**Method:** read-only. Compared every checkable statement in §17 ("Part (g): EG-9 + EG-10") and the §13 lines mentioning (g) against `audit-p3b/eg9/EG9-SPEC-A.md` v1-v3 and `EG9-REVIEW-B.md` v1-v3 (confirmed byte-identical to the scratchpad copies I authored). Also spot-checked the underlying source citations already verified across the three review rounds.

**Verdict: NEEDS-EDIT.** One MATERIAL provenance problem: a sentence from my (B2's) review commentary was folded into the "decision" table as if it were already-reviewed spec content, with no SPEC-A v4 or attribution marking it as an unincorporated recommendation. Everything else checked is FAITHFUL — no drops, no softening, no overstatement found.

## The problem: R-I table cell absorbs an unincorporated review recommendation

§17's R-I cell reads: "...A correction is applied only by a separate human act that authorizes a **fresh witnessed S5 run** for the excepted content, **never by a bypass write**."

I searched `EG9-SPEC-A-v3.md` for "witnessed" and "bypass" — **neither word appears anywhere in it.** The phrase originates verbatim from `EG9-REVIEW-B-v3.md` (my own review), where it is explicitly framed as an **unmet recommendation**, not settled spec text:

> "One clarity gap, not material: the recommendation combines R-I with '...a separate human act,' but doesn't say in one place what that act concretely does... Recommend one sentence saying this explicitly, so H-EG9-6 doesn't get read as authorizing an unwitnessed write path." (REVIEW-B-v3:19)
> "**SPEC-ACCEPTABLE.** Recommend one added sentence to H-EG9-6 (quarantine-lift = a fresh witnessed run, not a bypass write) before it goes to the human; nothing else blocks acceptance." (REVIEW-B-v3:34)

No `EG9-SPEC-A-v4.md` exists incorporating this. The decision package has silently merged the reviewer's suggested edit into the author's design as though A4 wrote it and B2 reviewed it — that review-implementation cycle never happened. This is not a content error (I stand by the sentence; it is good and uncontested), but it breaks the paper trail this project's own governance discipline insists on: a review records a recommendation; the author, not the package assembler, adopts it in a new revision. As written, a reader of §17 cannot tell that this specific clause was never seen by A4 or put through a fourth review round.

**Exact edit:** either (a) revert the R-I cell to v3's actual text ("Corrections are stored and reported. A correction is applied only by a separate human act.") and drop the added clause, or (b) keep the clause but mark it, e.g.: "...never by a bypass write *(clarified per B2's REVIEW-B-v3 recommendation; not yet folded into a SPEC-A revision)*." (b) is cheaper and loses nothing; recommended.

## Everything else checked: FAITHFUL

- **§21 status under R7, re-derivation blindness, verifier-as-predicate-composer argument:** all match SPEC-A v1/v2 and are independently confirmed accurate in REVIEW-B v1/v2 (I re-derived §21 from the full protocol text myself; the categorical argument replacing the weaker GL:906-921 precedent argument is correctly the *v2* text, not the retracted v1 framing — the package correctly uses the corrected version).
- **Engineering (E9-01…E9-12, T-01…T-17):** test-id ranges are accurate and complete across all three spec rounds (v1 defined E9-01…E9-12, unchanged through v3; v2 defined T-01…T-13, v3 added T-14…T-17 — the union is exactly T-01…T-17).
- **"80 tranches (79 of 5, 1 of 1)":** matches SPEC-A-v2 §4's corrected wording exactly (the ambiguous v1 phrasing I flagged was fixed in v2, and §17 uses the fixed version).
- **class-H rule** (batch-wide, non-retryable, worst-cased, disclosed beside EG-6's retry-survival limitation): matches SPEC-A-v2 §3 item (4a), which REVIEW-B-v2 confirmed correctly resolves the v1 finding. Faithful, not softened (still says "batch-wide," not narrowed to "label-wide").
- **The precedent (G-LOG-0024/0025):** §17 states "G-LOG-0024 is only the audit's recommendation" and cites G-LOG-0025's "pending and not applied" / quarantine language — this is the *corrected* reading (confirmed directly against GL:502-530 in the v3 round, after my v2 review had overstated it). The package correctly carries the corrected reading forward, not my earlier overstatement. Faithful.
- **check_tranche and its residual:** "point-in-time check," "no code-level append-only enforcement" on `P3B-GOVERNANCE-LOG.md,` "detectable on re-verification, but authorship stays unproven," "hash-chaining... a separate governance-integrity item," "attempt field... always 1 until EG-6 part (d) lands" — every clause matches SPEC-A-v3 §1 and is confirmed accurate in REVIEW-B-v3. Not overstated (still says "detectable," never "prevented") and not softened (the "no code-level append-only enforcement" gap is stated as plainly in §17 as in the source).
- **EG-10 runbook text:** step 7b and the restated step 9 CLI text match SPEC-A-v2 §5, which REVIEW-B-v2 independently verified against `p3b_s5_state.py`'s actual `argparse` syntax and `TRANSITIONS` dict. The "blocks the canary" claim matches both A4's and my own independent findings.
- **§13's coverage/non-coverage lines** for part (g) ("No addendum text, so no rebind," "the EG-10 runbook fix inside (g) blocks the canary," "executing any §21 audit or H-06 acceptance" excluded, "hash-chaining the governance log" excluded as a separate backlog item): all trace directly to SPEC-A v1 (no-rebind claim, independently confirmed against K3 §16.5's unconditional text) and SPEC-A v3 (the two exclusions). Nothing dropped, nothing added beyond the one R-I clause above.

**Nothing else overstated, softened, or unsupported was found.**
