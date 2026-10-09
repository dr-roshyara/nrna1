# Membership / Participation research models (bounded, executable)

**Status:** research artefacts, retained on request (Principal Reviewer, `20261009_2122_implementation.md` §2.B) as **counterexample generators and test-oracle scaffolding**. They are **not** production code, not tests in any suite, and not evidence of real-world frequencies.

**Run:** `python3 -I <file>`. Standard library only; deterministic, with no randomness.

| Model | Question | Domain (finite) | Result (as committed) |
|---|---|---|---|
| `g2_obligations.py` | stored charges vs a derived target after one correction | T = 6 months, 1 member, Admit / End, ≤ 1 annulment, fee 1 per month, pay none / all | stored wrong in 140 / 222; credit in 70 / 222. The "derived" result equals truth **by construction** (no evidence) |
| `g2_ledger.py` | append-only ledger with adjusting postings (design H) vs legacy STORED vs NAIVE recompute, against an **independent** interval-set oracle | T = 6, fee 2, ≤ 2 corrections from {AnnulEnd, AnnulAdmit, ReAdmit, CorrectEnd}, ≤ 1 waiver, close ∈ {none, 2, 4}, pay none / full / half | 42,075 histories. The two resolvers agree in all of them; H matches the oracle **per service month** in all; no closed posting mutated; STORED wrong in 38,106; NAIVE would mutate closed months in 14,433; credit in 14,883 |
| `g3_g4_access_vacancy.py` | an event-maintained access list vs derived access; legal vs functional vacancy | T = 6, one tenure, End optionally annulled | ACL diverges in 180 / 432 (476 wrong months); legal ≠ functional vacancy in 100 / 432 |
| `g4_reentry.py` | lapse vs suspend (BQ-P3) with re-entry | T = 6, End then re-admit at g ≥ e | they differ in 110 / 540; with no re-entry, 0 / 432 |

## Assumptions and limits

- The generators include some histories the kernel would **refuse**, for example AnnulEnd plus ReAdmit producing overlapping periods. Results cover all *generated* histories; they are not filtered by domain admissibility.
- No pro-rata or partial-month fees. One fee rule. No refunds (credit is counted, not settled). No interest or dunning.
- **The `g2_ledger.py` oracle initially disagreed with the replay resolver in 5,040 histories.** The cause was a **correction of an annulled End**. The oracle applied it; the replay treated it as inert. The ratified rule **BD-REV** ("a correction of an annulled fact is inert") decides it, and the oracle was corrected to follow it. That disagreement was the oracle doing its job: finding a semantic point that needs a ratified rule.
- Counts are numbers of enumerated cases in a deliberately small, uniform domain. **They are not probabilities.**

**Traceability:** `docs/publicdigit/reviews/2026-10-09-participation-semantic-contract-draft.md` (G-2 … G-4) · `…-membership-pbdigit264-plan-ratification-pack-and-cutover-gate.md` §9.
