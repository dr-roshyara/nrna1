# Governance Weight Assessment — `docs/election_management/04-voter-list.md`

**Type:** Governance discovery · **Date:** 2026-08-12 · **Programme:** IERVP (Session 2)
**Question:** *does this document carry governance weight?*
**Recommendation:** **D — MIXED AUTHORITY; SECTION-LEVEL REVIEW REQUIRED** *(§10)*
**Mode:** discovery only. **The guide was NOT modified. No governance metadata added. Constitution, ADRs, `ElectionMembership`, `ElectionPolicy` and tests untouched. No entity/aggregate/repository introduced. No governance mechanism created. Nothing promoted.**

**Governing rule observed throughout:** *evidence may establish what a document says, and that it has been treated as normative; **evidence does not itself grant authority.***

---

## 1 · The answer, in one line

> **The document is a screen manual that happens to contain four genuine business rules — and it sits downstream of an implementation decision that deliberately deviated from its own architecture document. Adopting it wholesale would elevate badge colours and CSV filenames to business rules; rejecting it wholesale would discard the only written statement of the voter lifecycle.**

**By the repository's own executable test of governedness, it is not a governed document:** no knowledge card, not a recognised documentation root, cited as authoritative by nothing. **OBSERVED FACT.**

---

## 2 · What the document actually is

**Register analysis (OBSERVED FACT).** Of 209 lines: ASCII mock-ups of the screen, badge colours, button labels, click sequences, a success-message string, a CSV filename and column list, and a "Common Mistakes" section keyed to buttons. **The dominant register is UI-procedural.**

Embedded in it are a small number of **imperative, business-level statements**. The mixture is the finding.

| | |
|---|---|
| **Predominantly** | operational/procedural guidance for two named roles |
| **Contains** | 4 normative business statements · 3 advisory recommendations · 1 statement contradicted by the implementation · 1 statement scoped explicitly to the UI |
| **Never mentions** | audit · reason capture · actor recording · the two-actor suspension flow · what happens after a voter has voted |

> **Its silence matters as much as its content: a document that never mentions audit cannot answer `BR-1.6`.**

---

## 3 · Rule extraction

**Classification per statement. `NORMATIVE` = imperative business claim · `PROCEDURAL` = how to operate the screen · `ADVISORY` = recommendation.**

| # | Line | Statement | Kind | Actor → Action | Precondition | Resulting business meaning | Reversal | Stated elsewhere? | Conflict? |
|---|---|---|---|---|---|---|---|---|---|
| **R1** | 5, 63 | *"Only voters with Active status can submit a ballot. All others are blocked at the voting step."* | **NORMATIVE** | system → refuse ballot | status ≠ active | deny-by-default exercisability | — | ✅ matches `isVoterInElection()` | **AGREES** |
| **R2** | 3 | *"Available to Chief and Deputy Election Officers only"* | **NORMATIVE** | officer → all voter acts | active chief/deputy | authority boundary | — | ✅ `manageVoters`; tests | **AGREES** |
| **R3** | 90, 101 | suspension makes an active voter `inactive`; *"a suspended voter cannot vote, even if voting is still open"* | **NORMATIVE** | officer → suspend | status = active | exercisability withdrawn, election-independent | re-approval | ✅ implementation | **AGREES** |
| **R4** | 105 | *"Suspended voters show an Approve button. Simply click it to restore their eligibility."* | **NORMATIVE** | officer → restore | status = inactive | exercisability restored | — | ✅ `approve()` | **AGREES** (guide is **more specific** — it names restoration as a concept the code does not) |
| **R5** | 111, 119 | removal is *"permanent"*; *"cannot be restored **through the voter list UI** — contact your system administrator"* | **UI-SCOPED** | officer → remove | any | exercisability withdrawn; recourse is administrative | admin only | ❌ nowhere else | ⚠️ **CONTRADICTS implementation** — `approve()` has no guard against `removed` |
| **R6** | 59, 130 | assignment yields **`invited`**; approval is then required | **NORMATIVE** | officer → assign | — | an approval gate exists | — | ❌ | 🔴 **CONTRADICTS** — no code produces `invited`; assignment writes `active` |
| **R7** | 133 | *"Only organisation members can be assigned as voters."* | **NORMATIVE** | officer → assign | org linkage exists | admission prerequisite | — | ⚠️ the FK enforces a **role linkage**, not the `Member` aggregate (0 rows) | **AGREES with the FK; ambiguous on "member"** |
| **R8** | 84 | *"Approve voters before opening voting."* | ADVISORY | officer | — | — | — | — | — |
| **R9** | 177 | *"Suspend any ineligible members"* | ADVISORY | officer | — | ⚠️ uses suspension as the mechanism for **ineligibility** | — | — | ⚠️ **collides with `A-2`** — conflates a governance act with an eligibility fact |
| **R10** | 190, 194 | *"I approved someone by mistake → click Suspend. This reverses the approval"* | ADVISORY | officer | — | ⚠️ uses suspension as **undo** | — | — | ⚠️ same collision as R9 |
| **R11** | 160 | *"**Eligible** — Active voters whose access hasn't expired"* | PROCEDURAL | — | — | a statistic | — | ⚠️ derives from `isEligible()`, which has **0 callers** | **UNKNOWN** |

**Audit implication for every row above: none. The guide is silent on audit throughout.**

---

## 4 · Provenance — OBSERVED FACT

| Evidence sought | Result |
|---|---|
| Author / date | **Single commit `95aac103`, 2026-03-22**, by the repository owner |
| Commit character | A large feature commit — *"feat: multitenancy branch — member import, election flow, UI, i18n, **docs**"*. **Documentation bundled with code, not a governance act** |
| Revisions since | **None** — never modified in ~5 months |
| Review / adoption event | **None found** |
| Cited by the Constitution | **No** |
| Cited by any ADR | **No** |
| Cited by code comments as specification | **No** |
| Cited by any test as the source of expectations | **No** |
| Cited by developer guides as normative | **No** |
| Owner / steward | **None named** |
| Change-control process | **None** |
| Part of any Definition of Done | **No** |
| **Knowledge card** (the repository's own requirement for a governed doc) | ❌ **absent** |
| **Recognised root in the executable placement policy** (`documentation-placement.yaml`) | ❌ **not registered** |

> **The repository defines governedness executably — *"every governed doc needs a knowledge card"* — and this document satisfies none of the criteria.** The only repository references to the string `election_management` are (a) my own session artifacts from today, (b) a **different** folder `developer_guide/election/election_management/`, and (c) i18n translation keys.

### 4.1 🔑 The provenance chain that decides the character of this document

| Step | Artifact | Finding |
|---|---|---|
| 1 | `architecture_legacy/election/election_management/Voterlist.md` | An architecture document that **specified dedicated `approved_at`, `approved_by`, `suspended_at`, `suspended_by` columns** and **`Log::channel('voting_audit')->info('Voter suspended', …)`** |
| 2 | `developer_guide/election/election_management/08-voter-management.md:9-20` | *"**Critical Analysis of Architecture Doc (`Voterlist.md`)** — The architecture document contained several errors. These were corrected before implementation."* The recorded decision: *"Add `approved_at`, `approved_by`, `suspended_at`, `suspended_by`, `has_voted` columns → **None exist** … **Used existing `status` column**"* |
| 3 | Implementation | `status` reused; **the specified audit logging was dropped** |
| 4 | `docs/election_management/04-voter-list.md` | **Describes the resulting screen** |

**Three consequences, and they are the most useful output of this assessment:**

1. **The guide is DESCRIPTIVE of an implementation, and the implementation deliberately deviated from its architecture document.** A document downstream of a deviation cannot be the source of the rule. **INTERPRETATION**, strongly supported.
2. 🔑 **The `status` overload was a deliberate, recorded decision — not drift.** Its stated rationale was *"None exist"*: **availability, not modelling.** **So `Q3` candidate D is not a new idea; it is the original design, rejected on cost grounds.** *(Recorded for `Q3`. Nothing decided.)*
3. 🔑 **The audit gap I measured is a deviation from a documented design intent, not a mere omission.** `Voterlist.md` specified voter-suspension audit logging; the implementation dropped it.

**And `Voterlist.md` is itself not an authored specification:** it opens *"## ✅ **You're Absolutely Right!**"* — **it is an assistant-conversation transcript.** It also sits in `architecture_legacy/`, which ADR `20260801_1712` declares legacy (as it does `developer_guide/`). **OBSERVED FACT.**

---

## 5 · Governance hypotheses tested

| | Hypothesis | Verdict |
|---|---|---|
| **A** | Formally governed normative artifact | ❌ **Rejected** — no card, no root registration, no citation, no steward, no adoption, no change control |
| **B** | Informal but strong business evidence | ✅ **True of R1–R4, R7** |
| **C** | Operational/procedural guide | ✅ **True of the document's dominant register** |
| **D** | Implementation documentation | ⚠️ **Partly** — it documents the screen, and post-dates the implementation |
| **E** | **Mixed — some sections normative, others explanatory** | ✅ **Best fit** |
| **F** | Unknown | ❌ Evidence is sufficient |

---

## 6 · Business decision ownership

**For each extracted rule: the intended owner, versus where the decision is documented and where authority actually sits.**

| Rule | Intended owner | Documentary source | **Governance authority** |
|---|---|---|---|
| R1 exercisability requires Active | **Domain** (entitlement/exercisability) | Officer Guide + implementation | **Unknown** |
| R2 authority = chief/deputy | **Policy/Authorization** — constitutionally | Officer Guide + `ElectionPolicy` | **Unknown** (Constitution names no voter-level action) |
| R3 suspension withdraws exercisability | **Domain** | Officer Guide | **Unknown** |
| R4 restoration | **Domain** | Officer Guide **only** | **Unknown** |
| R5 removal permanence | **Domain** + Policy | Officer Guide **only** | **Unknown** |
| R6 approval gate (`invited`) | **Application** | Officer Guide **only** | **Unknown** — and unimplemented |
| R7 admission prerequisite | **Domain** (mode-dependent) | Officer Guide + `VoterSourceStrategy` | **Partially `VoterSourceStrategy`** |
| R8–R10 advisory | Interface/Projection | Officer Guide | n/a |
| R11 statistic | Interface/Projection | Officer Guide | n/a |

> **`Documentary source: Officer Guide · Governance authority: Unknown` is the result for six of seven business rules.** That is a legitimate finding, and it is the crux: **the rules are written down in a place that has no authority to write them.**

---

## 7 · Cross-check against the Constitution and other sources

| Rule | vs `ElectionConstitution` | vs ADRs | vs `ElectionPolicy` | vs implementation | vs tests |
|---|---|---|---|---|---|
| R1 | **Constitution silent** | — | — | **AGREES** | **UNKNOWN** — no test asserts it for a *voter* |
| R2 | **Constitution silent** (no voter-level action) | — | **AGREES** | **AGREES** | **AGREES** |
| R3 | **Constitution silent**; `suspend` there is **election-level** → **GUIDE IS MORE SPECIFIC** | — | **AGREES** | **AGREES** | **AGREES** (workflow only) |
| R4 | **Constitution silent** (`restore` absent) → **GUIDE IS MORE SPECIFIC** | — | **AGREES** | **AGREES** | **UNKNOWN** |
| R5 | **Constitution silent** → **GUIDE IS MORE SPECIFIC** | ⚠️ *Revocation* in the accepted vocabulary means identity-trust withdrawal — **different object** | — | 🔴 **CONTRADICTS** (`approve()` has no `removed` guard) | **UNKNOWN** |
| R6 | silent | — | — | 🔴 **CONTRADICTS** (no producer of `invited`) | **UNKNOWN** |
| R7 | silent | **COMPLEMENTS** `VoterSourceStrategy` | — | **AGREES** with the FK; ambiguous on *"member"* | **AGREES** at the assignment gate |
| R9/R10 | silent | ⚠️ **CONTRADICTS `A-2`** in spirit — treats suspension as an eligibility/undo device | — | — | — |

**Two contradictions are recorded as evidence requiring governance resolution. I have not resolved either.**

---

## 8 · `BR-1` impact

| `BR-1.x` | Relevant guide rule | Evidence | Other authority | Agreement | Governance status | **Result** |
|---|---|---|---|---|---|---|
| **1.1** does termination end existence? | R5 | *"permanent"*, UI-scoped | none | — | ungoverned | **STILL OPEN** — the guide speaks to *effect*, never to *existence* |
| **1.2** reversible? | R5 | *"contact your system administrator"* | none | — | ungoverned | **SPECIFIED BY THE GUIDE, not authoritative** |
| **1.3** naming | — | — | vocabulary reserves `revoke` | — | — | **STILL OPEN** |
| **1.4** actors | R2 | chief/deputy | `ElectionPolicy`, tests | agree | ungoverned as a *rule* | **PARTIALLY specified** — role yes, **count no** |
| **1.5** reason mandatory? | — | **silent** | org side requires it | — | — | **STILL OPEN** |
| **1.6** audit? | — | **silent** | `Voterlist.md` specified it; dropped | — | — | **STILL OPEN** |
| **1.7** temporary/indefinite? | R3 | *"until re-approved"* — a **condition**, not a duration | none | — | ungoverned | **STILL OPEN** |
| **1.8** restoration symmetry | R4 | one actor, same authority | implementation agrees | agree | ungoverned | **SPECIFIED BY THE GUIDE, not authoritative** |
| **1.9** Chief acting on org change | R9 | *"suspend any ineligible members"* — **advisory** | none | — | — | **STILL OPEN** |
| **1.10** post-vote governance | — | **silent** | `ADR-T11`, `ADR-003` | — | — | **STILL OPEN** |
| **1.11** consumption meaning | — | **silent** | — | — | — | **STILL OPEN** |
| **1.12** is `invited` real? | R6 | documented as real | 🔴 no implementation | **CONTRADICT** | ungoverned | **CONTRADICTION — needs resolution** |
| **1.13** which suspension path | — | documents **only** the single-actor path | code+tests have both | **CONTRADICT by omission** | ungoverned | **STILL OPEN**, and the guide is *evidence for the single-actor path* |

**Score: the guide speaks to 6 of 13; it authoritatively settles 0.**

---

## 9 · The critical question

**Q(a) — If the Product Owner formally recognised this guide as a governed normative artifact, what would become specified immediately?**

| Would become specified |
|---|
| **R1** only Active may exercise the entitlement — **deny-by-default** |
| **R2** authority = active chief/deputy (role, not count) |
| **R3** suspension withdraws exercisability, independent of the election's own state |
| **R4** restoration exists, as re-approval, by the same authority, one actor |
| **R5** termination is permanent **to officers**, with administrative recourse — i.e. **`BR-1.2` answered** |
| **R7** admission requires an organisation linkage |

**⚠️ And these would come with it, which is why wholesale adoption is the wrong instrument:** badge colours · button labels · the success-message wording · the CSV filename and columns · the statistics-card definitions · *"click Suspend to undo an approval"* (**which contradicts `A-2`**) · and **R6, a rule the code does not implement.** **Adopting the document would make the UI a business rule and freeze it.**

**Q(b) — Which of those would still require explicit constitutional/ADR ratification?**

| Rule | Still needs ratification? | Why |
|---|---|---|
| **R1** | ✅ **Yes** | It is an exercisability rule; exercisability is the `Q3` decision, and the Constitution names no voter-level action |
| **R2** | ✅ **Yes** | Authority belongs constitutionally; today it lives only in an application policy |
| **R3** | ✅ **Yes** | Requires a voter-level constitutional `suspend` **distinct** from election-level `suspend` |
| **R4** | ✅ **Yes** | `restore` has no constitutional existence |
| **R5** | ✅ **Yes** | Termination is `BR-1.1`; and a *"permanent"* rule that `approve()` can defeat must be enforced before it can be ratified |
| **R7** | ⚠️ **Partly** | `VoterSourceStrategy` already governs admission sources; only the *wording* of "member" needs disambiguation |

> **So recognition would settle almost nothing on its own: every business rule in the guide still needs constitutional or ADR ratification. What recognition WOULD do is make the guide a legitimate *source of the operational expression* of those rules — which is exactly the layered model the Product Owner sketched.**

---

## 10 · Recommendation

> ## **D — MIXED AUTHORITY; SECTION-LEVEL REVIEW REQUIRED**

**Why not A:** it fails every objective test of governedness the repository defines, it contains a rule the code does not implement (**R6**), a rule the code contradicts (**R5**), and advisory text that conflicts with an approved business rule (**R9/R10**). **Adoption would elevate UI details to business rules.**

**Why not C alone:** dismissing it as procedural would discard **R1–R5 and R7** — the only written statement of the voter lifecycle anywhere in the repository, and the only place `restoration` exists as a named concept.

**Why not B alone:** *"strong evidence, not governed"* is a true statement of **status** but gives no instrument. **The document's problem is that it mixes registers**, and only a section-level review can separate them. *(If a whole-document status label is wanted instead, it is **B**.)*

**Why not E:** provenance, register, citations, contradictions and `BR-1` impact are all established.

---

## 11 · PROPOSED GOVERNANCE DECISION

**Proposed, not performed. The Product Owner is the deciding authority.**

| | |
|---|---|
| **What would be recognised** | The **business statements** R1, R2, R3, R4, R5, R7 — as the **operational expression** of rules to be ratified elsewhere; **not** as their source of authority |
| **What would NOT be recognised** | All UI-procedural content — badge colours, button labels, click steps, message strings, CSV format, statistics-card definitions, mock-ups; the advisory text **R8–R10**; and **R6** (`invited`), pending resolution of the contradiction |
| **Which rules become authoritative** | **None by recognition alone.** Each still requires constitutional/ADR ratification (§9 Q(b)) |
| **Which remain subject to Constitution/ADR** | R1 (exercisability, via `Q3`) · R2 (authority) · R3 (voter-level suspension) · R4 (restoration) · R5 (termination, via `BR-1.1`) · R7 (wording only) |
| **Proposed owner/steward** | **Election governance**, as the operational-expression layer beneath the Constitution and ADRs — **consistent with the Product Owner's layered model.** Naming the steward is a governance act I do not perform |
| **Change-control implication** | Recognition means the guide can **no longer be edited as a side effect of a UI change**. Its business statements would need the same change discipline as the rules they express — and it would need a **knowledge card** and a **registered documentation root** to be governable at all |
| **Conflicts requiring resolution first** | 🔴 **R6** — documented `invited` state with no implementation · 🔴 **R5** — *"permanent"* defeated by `approve()`'s missing guard · ⚠️ **R9/R10** — suspension described as an eligibility/undo device, conflicting with `A-2` |
| **`BR-1.x` resolved by recognition** | **`BR-1.2`** (reversal is administrative) and **`BR-1.8`** (restoration symmetry) — *as specified statements awaiting ratification* |
| **`BR-1.x` remaining open** | `1.1` · `1.3` · `1.4` (count) · `1.5` · `1.6` · `1.7` · `1.9` · `1.10` · `1.11` · `1.12` · `1.13` |

> **The layered model the Product Owner sketched is supported by this evidence:** *Constitution governs principles and authority → ADRs record decisions → the Officer Guide operationalises ratified rules.* **The guide should derive authority, never hold it.** That also avoids the failure mode of a second competing constitution.

**STOP. No adoption performed.**

---

## 12 · Boundaries and self-audit

* **Nothing changed:** the guide was not edited, no governance metadata added, Constitution/ADRs/`ElectionMembership`/`ElectionPolicy`/tests untouched, no entity/aggregate/repository introduced, no governance mechanism created, nothing promoted.
* **Labels kept separate** — OBSERVED FACT (register, provenance, citations, contradictions) · INTERPRETATION (that descriptiveness follows from the deviation chain) · RECOMMENDATION (§10) · PROPOSED DECISION (§11). **No DECISION was taken.**
* **Not executed:** `approve()`-on-a-`removed`-row is read, not run — so **R5's contradiction is `MECHANISM NOT ESTABLISHED`**, not proven.
* **Provenance limits:** a single commit is all git offers. **Absence of a review event is not proof no review happened** — it may have occurred outside the repository. I report absence of evidence, not evidence of absence.
* **I did not assess the other six guides** in the folder. `03-management-dashboard.md` and `06-faq-troubleshooting.md` may carry business statements of their own; **`BR-1` did not require them, and I did not read them for rules.** A folder-level ruling would need that.
* **`PBDIGIT-69`** is cited nowhere.
* **Session 1** — `SD-1`, `SD-2`, `SD-4`, Master Matrix, 1,376-test baseline — **neither read as authority nor modified; no handoff artifact exists.**

**Traceability:** `docs/election_management/04-voter-list.md:1-209` · `docs/election_management/README.md:1-20` · commit `95aac103` (2026-03-22, sole commit) · `architecture_legacy/election/election_management/Voterlist.md:1-4,186-233` · `developer_guide/election/election_management/08-voter-management.md:9-20` · `docs/adr/20260801_1712_legacy_folder_and_files.md` · `docs/knowledge/schema/documentation-placement.yaml` (no `election_management` root) · `app/Models/ElectionMembership.php:28` (`@see` a path that now resolves only under `architecture_legacy/`) · `app/Policies/ElectionPolicy.php:91-98` · `app/Models/User.php:315-328` · `app/Http/Controllers/ElectionVoterController.php:196-216,222-241` · measured 2026-08-12: no knowledge card · no citation of the guide as authoritative anywhere in the repository.
