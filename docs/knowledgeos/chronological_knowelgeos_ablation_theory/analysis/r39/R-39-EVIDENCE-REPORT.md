# R-39 Evidence Report (targeted evidence, L0-DEC-32) — class: CORPUS EVIDENCE + FORMAL COMPARISON; not a theory revision

⚠ **Reader:** SELF (Claude, author of H-F2-1-R), a single reader, not blind. This is the same reader that missed R-39 in T-A. The historical facts below are quoted verbatim so that an independent reader can re-check them.

## A. Source identity

| # | Object | Blob | sha256 | Role |
|---|---|---|---|---|
| 1 | `668cc7b22:engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` | `70042f21…` | `5bb384af65cc5d8b19760ef168e7d5d3a9db9a67e75a148e16ad4445f080b005` | **primary**: the Rulings Register (*"Living (append-only) · Owner: Architecture Review Board"*, L3), R-39 at L25 |
| 2 | `668cc7b22:engineering/knowledge/methodology/DDD_Tactical_Governance_Principles.md` | `69c6679d…` | `45780fd5dfe920a98f7260e94ad5b06e22029b45b0ed3758002c8462c41948b9` | the adopted object; it points to R-39 (L4) |

- Commit `668cc7b22`: 2026-07-26. This predates F0018's date (2026-08-02) and is the same commit as ES-006 T-A object 7.
- Both objects are outside the canonical corpus; they were released by the OQ-6 scope IN of L0-DEC-32.

## B. Complete-read verification

- Door `CLEAR` (exit 0); control files identical to RRC-02; both object hashes verified before reading.
- Both read **completely** by immutable object (`git show`): object 1, 25 of 25 lines; object 2, 63 of 63 lines.
- `READ-LOG.jsonl` sha256 `21cab657…`. Nothing else was read.

## C. Historical evidence (source facts; no H-F2-1-R interpretation)

| # | Item | Source statement (verbatim) | Anchor |
|---|---|---|---|
| 1 | operation | *"Governance exception — early promotion of the DDD Tactical Governance Principles to the Engineering Platform."* | obj 1 L25 |
| 2 | affected object / state | the methodology module *"DDD Tactical Governance Principles"*; resulting status *"ADOPTED (explicit Decision Authority ruling, 2026-07-26)"*; canonical home moved to `engineering/knowledge/methodology/…` | obj 1 L25; obj 2 L3–L4 |
| 3 | actor / authority | *"The Decision Authority approved promotion on 2026-07-26"*; the module is *"ratified as permanent governance by the ARB 2026-07-26; promoted … by explicit Decision Authority ruling the same day"* | obj 1 L25; obj 2 L63 |
| 4 | source-described effect | promotion/adoption of the module into `engineering/` *"with a corresponding runtime hook"* (AST-014) | obj 1 L25; obj 2 L7 |
| 5 | temporal context | all on 2026-07-26; R-39 is appended after R-38 (2026-07-11) in an append-only register | obj 1 L3, L24–25 |
| 6 | explicit exception language | *"Governance exception"* · *"Early promotion recorded as explicit exception"* · *"the exception is traceable governance, never part of the methodology itself"* | obj 1 L25 (text + Effect column); obj 2 L4 |
| 7 | explicit promotion / bar language | *"The normal promotion rule (ES-006.1 + the EPIC-004 track's own self-governance: 'principles are promoted only on repeated evidence and explicitly NOT promoted when evidence is domain-specific') requires evidence from more than one bounded context."* | obj 1 L25 |
| 8 | explicit bypass language | *"approved promotion … with evidence from ONE context (PublicDigit Adjudication/Determination, EPIC-004D..K)"*; reason: *"the seven principles are generalized methodology by construction (no project-specific term appears in any statement)"* | obj 1 L25 |
| 9 | grandfathering / retroactivity | none stated. Prospective only: *"the multi-context bar continues to apply to FUTURE methodology promotions — this exception does not weaken the rule"* · Effect column: *"promotion rule intact"* | obj 1 L25 |
| 10 | evidence / reassessment | evidence **stated**: one context (EPIC-004D..K; *"first adoption evidence"*, obj 2 L63). Reassessment **deferred**: *"Expected validation: the next bounded context that adopts the module either confirms the principles or produces the amendment evidence"* | obj 1 L25; obj 2 L63 |
| 11 | uncertainty | (i) the bar's own wording is attributed jointly to *"ES-006.1 + the EPIC-004 track's own self-governance"*; the EPIC-004 source is **not released**. (ii) The bar dimension is **the number of distinct bounded contexts**, not a count of occurrences. (iii) What "confirms … or produces the amendment evidence" would do to the ADOPTED status is **not stated** | obj 1 L25 |
| 12 | exact anchors | obj 1 L25 (R-39 row, both columns) · obj 1 L3 (register status) · obj 2 L3–4, L7, L63 | — |

**Recorded dependencies, not followed** (each would need its own L0 release):
- the EPIC-004 track's self-governance text (the quoted bar);
- `docs/architecture/governance/DDD_PRINCIPLES.md` (the binding, which hosts the EPIC-004 provenance);
- AST-014.

## D. Explicit rules and mechanisms (as the source states them)

- **Normal rule (bar):** methodology principles are promoted only on repeated evidence **from more than one bounded context**, and not when the evidence is domain-specific.
- **Exception mechanism:** an **item-specific governance act** by the Decision Authority promotes **one** item whose evidence (one context) is **below** the bar. The act is justified by a stated reason, recorded as an explicit exception, and carries a deferred validation expectation.
- **Rule status:** the bar is **explicitly unchanged**, for this and for future items.

**Pre-registered mechanism sub-classification (F-LOG-0050): → BAR BYPASSED FOR ONE ITEM.**
- Not "bar changed": the source denies it twice.
- Not "evidence unstated": the one-context evidence is stated.
- Not composite: one act, one item.
- It is qualified by a deferred-validation clause.

## E. Ambiguities

1. Whether the deferred validation makes ADOPTED **provisional** (revocable if the next context fails) is not stated.
2. The bar wording depends on an unreleased EPIC-004 source.
3. The relation between this multi-context bar and F0018's *"n≥2 / never promote from a single occurrence"* (OBS-SF-1) is **suggestive but unresolved**:
   - R-39 attributes the repeated-evidence rule to *"ES-006.1 + the EPIC-004 track's own self-governance"*;
   - the released ES-006.1 text itself states no threshold (T-A);
   - F0018 also points to CAP-001 §9 (not released).

## F. Formal A6 comparison (interpretation; kept separate from C–E)

- **A6 (formal):** `u′ = u`. The bar in force does not change along a trajectory.
- **H-F2-1-R's promotion predicate:** Promote ⟺ e ∈ u ∧ g = 1.

| Question | Answer from the source |
|---|---|
| Did the bar (rule) change? | **No.** The source explicitly states the rule is intact, for future items too |
| Was a promotion granted while the item's evidence was below the bar? | **Yes.** One context, where more than one is required |
| Was there an evidential step and a governance step? | **Yes, both.** Stated evidence (EPIC-004D..K) plus the DA act. So D3's requirement (≥ 1 evidential and ≥ 1 GOV step) is **met**, not violated |
| Can the model represent the event? | **Only in two ways, both outside H-F2-1-R as stated:** (a) as an **item-specific** bar lowering, i.e. the −A6 countermodel pattern *"GOV moves the bar onto the item"*, contrary to the source's own statement that the bar did not change; or (b) as a **promotion event that occurs without the state condition e ∈ u**, which H-F2-1-R's Promote predicate cannot express (the open H-6 question: promotion as state vs event) |

> **Pre-registered outcome for A6: A6 NOT APPLICABLE — the mechanism is different (bar bypass by an item-specific governance exception, not a bar change).**

This is recorded with its consequence, below, not as a reassurance. The category was fixed before reading (F-LOG-0050) and chosen from the source's explicit denial that the bar changed. The word "exception" alone did not decide it; the stated effect did.

## G. Impact on H-F2-1-R (no change made)

1. **A6 is not contradicted by R-39.** The source-described bar did not change.
2. **R-39 is a source-described promotion that H-F2-1-R cannot represent.** Governance granted promotion to an item **below the evidential bar**. H-F2-1-R's claims are:
   - D1: governance alone cannot bring an item across the bar;
   - Promote requires e ∈ u.

   Both presuppose that promotion is gated by the bar; R-39 shows the historical system also contains a **governed exception path** around the bar.
   - Pre-registered class: *"a bypass may attack D1/D3 through a different path"* (F-LOG-0045).
   - Formally, this is a **gap in the model's promotion semantics**, not a failure of A6, D3 or the bar-constancy axiom.
3. **Bearing on the open H-6 question:** R-39 is evidence for the **EVENT** reading of promotion. Promotion is an act that the rule normally conditions on evidence, and that the authority can also perform by explicit exception.
4. **Status of H-F2-1-R:** unchanged (F2-FORMAL-CANDIDATE). What R-39 does settle:
   - A6's A-reading (a bar change) is not what happened;
   - the live question moves from *"is the bar constant?"* to *"does the model need an explicit exception/bypass path, or a promotion-as-event predicate?"*

   Whether to revise the model is a later, separate decision, which must go through the formal pipeline (≥ 2 alternatives, finite-state test, NV).

## H. Required next evidence target (not requested here; L0 decides)

1. **Independent re-read of R-39** by a blind non-Claude reader, since this report is SELF only and SELF previously missed R-39. **Cheapest and highest-value.**
2. **CAP-001 §9** (already queued): now also informative for whether the "n≥2 / multi-context" bar has a stated home, which bears on OBS-SF-1.
3. **EPIC-004 self-governance source** (optional): the origin of the quoted bar wording.
4. A2e and A5e remain as queued. R-39 does not bear on them.

---

## Addendum — conformance to the human's L0 decision text (2026-09-26; from the completed read, no re-read)

The human's L0 decision text ("Release Option 2: Rulings log + module", both at `668cc7b22`, read completely) matches L0-DEC-32 exactly. The read was already executed under it (single use).

**E. Temporal semantics** (each case checked against the verbatim record in §C; every "NOT-EVIDENCED" means the source says nothing, and nothing is inferred)

| Case | HISTORICAL FACT | Anchor |
|---|---|---|
| existing items (other than the promoted module) | **NOT-EVIDENCED** | — |
| already-promoted items | **NOT-EVIDENCED** | — |
| pending items | **NOT-EVIDENCED** | — |
| grandfathering | **NOT-EVIDENCED** | — |
| retroactivity | **NOT-EVIDENCED** | — |
| reassessment / re-evaluation | stated **for this item only**, and prospective: *"Expected validation: the next bounded context that adopts the module either confirms the principles or produces the amendment evidence"*. The consequence for the ADOPTED status is NOT-EVIDENCED | obj 1 L25 |
| future-only application | **stated, for the rule:** *"the multi-context bar continues to apply to FUTURE methodology promotions — this exception does not weaken the rule"* | obj 1 L25 |

**F. Classification (unchanged from §F):**
- HISTORICAL FACT: the bar is stated unchanged; one item was promoted below it by an explicit DA exception.
- FORMAL INTERPRETATION: **NOT APPLICABLE — different mechanism.**
- Anchor: obj 1 L25, the ruling text plus the Effect column *"Early promotion recorded as explicit exception; promotion rule intact"*.

**Dependencies:**

| Object | Needed to resolve R-39's classification? | Label |
|---|---|---|
| the EPIC-004 track's self-governance text (the source of the quoted bar wording) | **no**: the classification rests on the explicit "rule intact / exception" statements. It is needed only to verify the **provenance of the bar wording** (bearing on OBS-SF-1) | DEPENDENCY-REQUIRES-NEW-L0-RELEASE (optional; for provenance) |
| `docs/architecture/governance/DDD_PRINCIPLES.md` (the binding holding the EPIC-004 provenance) | no | DEPENDENCY-REQUIRES-NEW-L0-RELEASE (optional) |
| AST-014 (runtime hook) | no | not needed |

No dependency was followed. No theory, axiom, pre-registration, T-A result or formal-model change.
