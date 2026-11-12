# Reference Geography: final independent validation report

| | |
|---|---|
| **Status** | Supporting evidence for the ratification of 2026-11-10 (D-93); not itself ratified |
| **Validated** | Strategic baseline §C (`2026-11-09-reference-geography-strategic-baseline.md`) · tactical baseline v3.11 (`2026-11-09-reference-geography-tactical-design-baseline-v3.11.md`) · coherence assessment · decision record §11.13–§11.27 (D-79 … D-92) |
| **Method** | Inspection of the current files, not of their own claims of completeness |
| **Labels** | [AD] · [BF] · [AC] · [TD] · [TU] · [OQ] |

## Disagreements (first)

**None.** One precision: the review's phrase that Geography's responsibility is to "assess the source and publish" is exact; §C now uses it consistently, and no passage says Geography owns truth [BF: no occurrence in v3.11 or §C].

## A. Strategic DDD verdict

**Coherent and sufficiently defined.** §C answers every question in the commission:
- purpose and what consumers may and may not rely on;
- what Geography owns and does not own;
- competent sources;
- official units versus organisation areas;
- **levels 0–2 global, 3–10 country-specific** (made explicit at this validation);
- context boundaries;
- valid time versus knowledge time;
- source authority versus publication authority versus organisational use.

**Prohibitions explicit:** Geography never determines belonging, membership, coverage, electoral entitlement or sovereignty (§C.1, §C.2, §C.5 invariant 7).

## B. Tactical DDD verdict

**v3.11 faithfully implements the accepted rules at the domain level**, after four corrections made at this validation:

| # | Finding | Kind | Correction |
|---|---|---|---|
| V-1 | §16 said "governance facts are not in the publication model", contradicting the D-90 notices that take stream positions (§8, §10) | **Defect** (internal contradiction) | Roster facts outside; **D-90 notices inside**, stated with the reason |
| V-2 | §15's list of writes omitted the D-90 notices | Defect (completeness) | Row added |
| V-3 | §17 still listed ADMISSION and GROUPING as "by analogy" | Stale | Closed (D-91, D-92); PO-1 … PO-8 and the Identity contract listed |
| V-4 | §C did not state the 0–2 / 3–10 distinction or D-90 in the business lifecycle | Gap (readability) | Added to §C.3 and §C.4 |

**Checked with no finding:**
- lifecycle (prepare, assess, submit, approve, publish);
- immutable evidence and the complete content version;
- approvals bound to the version;
- the classification model, with every kind classified once;
- `excluded(r)` over all versions;
- D-84a by role;
- Identity merges, cases A–E;
- D-90 confirmation semantics;
- `recordedAt`, positions and both boundaries;
- reconciliation one level, atomic and reversible;
- **corrections forward-only**: nothing published is rewritten.

## C. Cross-context integrity

**Preserved.**

| Context | Owns |
|---|---|
| Geography | Publishes official references |
| Organisation | Belonging, areas, coverage (D-3, D-22, D-80, D-82) |
| Membership | Membership facts |
| Election | Electorate at one τ |
| Identity | Persons and reference semantics |
| Governance | Organs |

- Geography holds only person references, and conflicts of interest are declared.
- **D-73:** one τ per operation, complete or refused, no timestamp moved, no cross-context causal claim.

## D. Proof obligations

| PO | Protects | Kind | To demonstrate | Falsified by |
|---|---|---|---|---|
| PO-1 lock to commit | Completeness at τ (D-73) | Database | Lock held from reading to commit or rollback | A close committing over an open earlier writer |
| PO-2 single write path | One publisher (D-31), no rewriting | Architectural constraint | All claims, notices, sequence, W and roster writes under the lock | Any write outside it |
| PO-3 dependency-only reads | G-4 … G-6, I-1 … I-4 | Architectural constraint | Validation reads only through recorded predicates | An unrecorded read |
| PO-4 gap-free ordered positions | Unambiguous history | Database | Committed in order, no gaps | p+1 visible while p is not |
| PO-5 one snapshot | Consistent answers | Database | Each answer from one snapshot after its boundary | A mixed answer |
| PO-6 refuse, never adjust | D-73 "not by moving timestamp generation" | Domain invariant | Stored `recordedAt` equals the clock reading and is above W | An adjusted or ≤ W value |
| PO-7 one clock | Completeness at τ | Database | One clock for the stream | Two clocks |
| PO-8 shared contract | Cross-context τ | Platform | Every D-73 participant conforms | **Membership §38.2 moves timestamps (known)** |
| Identity contract (4) | D-88 distinct persons | Dependency on Identity | Stable references; equality = same person; merge and reversal notices without organisation data | A merge without notice |

**Why they do not invalidate the baseline:** each is a property the implementation must *have*, not a domain rule still to be *chosen*. If PostgreSQL could not provide PO-1, PO-4, PO-5 or PO-7, the mechanism would change. **The domain promises would not.** PO-8 is Membership's alignment and is not redesigned here.

## E. Findings by label

- **[AD]** D-3, D-4, D-5, D-22, D-31, D-73 / CR-01, D-75 (XD-3), D-79 … D-92, D-84a: all represented.
- **[BF]** No "truth" ownership wording; every cited decision exists in the record; 21 claim kinds each classified once.
- **[AC]** V-1 … V-4 corrected; roster facts govern acting, D-90 notices describe publications.
- **[TD]** None new.
- **[TU]** PO-1 … PO-8; the Identity contract.
- **[OQ]** **None open.** Deferred: L-4c, Q-2, Japanese classification, naming below level 2, level-1 names, address ownership.

## F. Ratification recommendation

**READY FOR PRINCIPAL/ARB RATIFICATION.**

**In business terms:** the design now says, without contradiction:
- what PublicDigit's geography register is and what others may rely on;
- who may change it, and with what independent checks;
- how history is kept and how past questions are answered.

**It leaves no business rule for the developer to invent.**

**Material limitation:** ratification accepts the domain design, not a proven implementation. PO-1 … PO-8 and the Identity contract must be demonstrated in the implementation architecture, and **cross-context historical queries involving Membership cannot be fully guaranteed until PO-8 is resolved.**
