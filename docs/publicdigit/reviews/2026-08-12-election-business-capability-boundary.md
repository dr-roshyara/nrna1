# Election business-capability boundary — proposed (SD-4 Option C)

**Commission:** Principal Architect · Election Verification Slice 1 · **SD-4 Option C, investigation only**
**Date:** 2026-08-12 · **Checkpoint:** `d2b61a90`
**Status:** **`SD-1` NOT amended · 1,376 baseline PRESERVED · Master Matrix BLOCKED · no production code, test, fixture or PHPUnit configuration changed**

---

## 1 · Executive answer

> **The Election verification boundary can be derived from two authoritative sources that already exist, and neither is a filesystem path.**
>
> **The product-level journey** (`docs/publicdigit/backlog/README.md`, Product-Owner-owned) states *what the business does*. **`ElectionConstitution::RULES`** states *which of those steps carry a constitutional rule*. **The intersection is the Election verification boundary.**
>
> **`SD-1`'s five paths are not that boundary. They are a filesystem subtree that happens to contain some of it.**

**And one consequence that must be stated before any expansion:** **not all 155 out-of-universe capability-named files belong in the boundary.** SD-4A established they *exist*; **their business relevance is still unestablished**, and several are plainly infrastructure.

## 2 · Business purpose

From the product backlog, verbatim: *"The product is an Organisation Governance Platform whose first major capability is running elections."*

**The verification programme's purpose follows:** establish that **an organisation can run an election whose outcome is legitimate** — legitimate meaning the right people voted, once, anonymously, under rules that were in force at the time, with an auditable record.

## 3 · The Election capability map — derived from two authoritative sources

**Source 1 — the Level 0 journey** (product, PO-owned). **Source 2 — `ElectionConstitution::RULES`** (15 actions) over **`ElectionLifecycleState`** (12 states: `draft · submitted_for_approval · approved · rejected · setup_administration · setup_nomination · ready_for_voting · voting_active · counting · results_published · archived · suspended`).

| # | Business capability | Journey step | Constitutional action(s) | Actor | Decision ownership |
|---|---|---|---|---|---|
| **C1** | Election creation & configuration | Create / Configure Election | *(none — pre-constitutional)* | chief · deputy | Application |
| **C2** | Platform approval of an election | — *(governance, not in Level 0)* | `submit_for_approval` · `approve` · `reject` · `auto_submit` · `revise_and_resubmit` | chief · deputy · **platform_admin** · **system** | Policy/Authorization + Domain |
| **C3** | Administrative setup | Create Committees · Import Members | `begin_setup` · `complete_administration` | chief · deputy | Domain (preconditions) + Application (guard) |
| **C4** | Candidate registration | Candidate Registration | 🔑 **`apply_candidacy`** | **voter · member** *(the only participant action)* | **NOT ESTABLISHED** — B1 |
| **C5** | Candidate approval | Candidate Approval | `complete_nomination` | chief · deputy | Domain (`has_approved_candidates`) |
| **C6** | Voter admission & verification | Import/Approve Members · Voter Verification | *(none constitutional)* | chief · deputy | 🔴 **CONTESTED** — `PBDIGIT-49`: two homes |
| **C7** | Opening voting | Open Voting | `open_voting` | **chief only** | Domain (rules) + Application (guard) |
| **C8** | Casting a vote | Vote | *(none constitutional)* | voter | Application + Domain |
| **C9** | Closing voting | Close Voting | `close_voting` | chief · deputy | Application |
| **C10** | Counting | Count Votes | *(none constitutional)* | — | **NOT ESTABLISHED** |
| **C11** | Publishing results | Publish Results | `publish_results` | **chief only** | Application |
| **C12** | Audit & evidence | Audit | *(none constitutional)* | — | 🔴 **BD-1 unresolved** |
| **C13** | Archival | Archive | `archive` | chief · deputy | Application |
| **C14** | Suspension & resumption | — *(not in Level 0)* | `suspend` · `resume` | chief · **platform_admin** | Application |

### 🔴 Three structural findings from the map itself

1. **Six of fourteen capabilities carry NO constitutional action** — C1, C6, C8, C10, C12, and parts of others. **`vote casting` and `counting` — arguably the two most outcome-critical capabilities — are not constitutionally governed at all.** *(OBSERVED FACT from the rule set. Whether that is correct is a **business question**: the constitution governs lifecycle *transitions*, and casting a vote is not one.)*
2. **Two capabilities appear in the constitution but NOT in the Level 0 journey** — C2 (platform approval) and C14 (suspension). **The product journey does not describe them**, so *"every epic is a segment of this one journey"* is not literally true of the constitution.
3. **C4's ownership is unestablished** (B1) and **C6's is contested** (`PBDIGIT-49`, two homes) — **the two capabilities that decide *who may participate*.**

## 4 · Business invariants — only where an authoritative source states one

| Invariant | Source | Capability |
|---|---|---|
| An action is permitted only from its `allowed_states` | `ElectionConstitution::RULES` | all constitutional |
| An action is permitted only to its `allowed_roles` | same | all constitutional |
| Declared preconditions must hold | same | C3 · C5 · C7 · C2 |
| Lifecycle state is **derived from business facts**, never read from a column | `ADR_20260807_1500` + engine contract | all |
| **No voter↔vote linkage** | `ADR-T11` | **C8** |
| Only `chief` may open voting or publish results | `RULES` | C7 · C11 |

**Everything else: `BUSINESS RULE NOT SPECIFIED`.** Notably **one vote per voter** — no authoritative source found declaring it, though `PBDIGIT-62` concerned already-voted exclusion. **Recorded as unspecified, not assumed.**

## 5 · Capability vs technical concern — SD-4A's 155 files triaged by role

**Triaged by what the area *is*, not by whether it sits outside the universe:**

| Class | Areas |
|---|---|
| **A · Business capability** | candidacy application · voter eligibility · voter verification · `ElectionMembership` · approval · vote anonymity |
| **B · Supporting application** | voter source strategy · auto-transitions · voter repository |
| **C · Infrastructure/technical** | migration tests · capability *architecture* tests · controller *architecture* tests |
| **D · Projection/UI** | accessibility tests · results page tests |
| **E · Cross-cutting governance** | audit tests |
| **F · Unknown** | the remainder — **not read** |

> **Only class A, and arguably B, are candidates for the verification boundary.** **Class C tests architecture, not business behaviour** — including, notably, `ElectionCapabilityArchitectureTest`. **Adding all 155 would repeat SD-1's error in the opposite direction: a boundary drawn by name-matching rather than by business meaning.**

## 6 · Proposed verification boundary

**Include** — capabilities whose incorrectness would make an election outcome illegitimate:

> **C2 · C3 · C4 · C5 · C6 · C7 · C8 · C9 · C10 · C11 · C13 · C14** — and **C12 (audit)** *conditionally on `BD-1`*, since whether audit evidence is constitutional is unresolved.

**Exclude, with reason:**

| Excluded | Reason |
|---|---|
| C1 configuration | pre-constitutional; a misconfigured election cannot yet affect an outcome |
| Class C architecture/migration tests | verify **structure**, not business behaviour — they belong to a fitness-function suite, not a business-verification universe |
| Class D projection/accessibility | verify **presentation** of decisions made elsewhere |

**Ambiguous, deliberately unresolved:** **C6** (contested home — `PBDIGIT-49`) · **C12** (`BD-1`) · **C4** (ownership unestablished — B1) · **class F** (unread).

## 7 · What is required to turn this into `SD-1`

**Not done here, and must not be done by engineering:**

1. Product Owner **approves or amends the capability map** (§3).
2. Product Owner **rules on the three exclusions** (§6).
3. **Only then** derive the test population **per capability**, using **`phpunit --list-tests`** — never a scan.
4. Establish the **new denominator** under a recorded scope contract.
5. **Execute** the new population; produce a new manifest.
6. **Preserve the 1,376 baseline** and document the relationship.
7. **Then** the Master Matrix.

**Steps 3–6 are the same sequence the 826/1,376 defect taught. Skipping any of them recreates it.**

## 8 · Cross-stream evidence — referenced, not imported

**CROSS-STREAM EVIDENCE EXISTS — NOT YET PART OF SESSION 1 SPECIFICATION.** Session 2 is investigating `ElectionMembership` vs organisation `Member` (`D-ENT-1` / Model B), which **bears directly on C6 and C4**. **Not used as specification here.** If it becomes an approved architectural decision it may enter through **an explicit governance handoff**, not by absorption.

## 9 · Open Product Owner decisions

| | Decision |
|---|---|
| **SD-5** | Approve or amend the 14-capability map |
| **SD-6** | Are `vote casting` (C8) and `counting` (C10) intended to be **outside** constitutional governance? *(OBSERVED: they carry no constitutional action)* |
| **SD-7** | Do C2 (approval) and C14 (suspension) belong in the Level 0 journey, which currently omits them? |
| **SD-8** | Confirm the three exclusions (§6) |
| **SD-9** | Is **one vote per voter** an invariant? **No authoritative source states it** |
| open | `BD-1` (audit evidence) · `PBDIGIT-49` (eligibility home) · `SD-3` (programme identity) |

## 10 · Self-audit

| Check | ✓ |
|---|---|
| Capability map **derived**, not invented | ✅ two named authoritative sources |
| Business purpose stated before capabilities | ✅ §2 quotes the backlog |
| Did **not** amend `SD-1` or add tests | ✅ |
| 1,376 baseline preserved | ✅ untouched |
| Master Matrix not started | ✅ |
| 155 files **triaged by role**, not adopted wholesale | ✅ §5 — classes C and D proposed for exclusion |
| Unspecified rules labelled | ✅ *one vote per voter* left `NOT SPECIFIED` |
| Code construct ≠ capability | ✅ `ElectionCapabilityArchitectureTest` classified as infrastructure |
| Session 2 referenced, not imported | ✅ §8 |
| No test counted by scan | ✅ no test counts asserted in this document |
| Contested/unestablished ownership preserved | ✅ C4, C6, C12 |

## 11 · Explicit non-goals

No production/test/fixture/config change · no Constitution amendment · no legacy migration · no Model B or suspension implementation · no failure repair · **no scope change without Product Owner approval** · **no Master Matrix**.

---

**SD-4 OPTION C — INVESTIGATION COMPLETE**
**BUSINESS CAPABILITY BOUNDARY — PROPOSED (14 capabilities)**
**`SD-1` — NOT YET AMENDED**
**1,376-TEST BASELINE — PRESERVED**
**MASTER MATRIX — BLOCKED PENDING SCOPE DECISION**

**Traceability:** `docs/publicdigit/backlog/README.md` (Level 0 journey; business purpose) · `app/Domain/Election/Constitution/ElectionConstitution.php` (15 actions) · `app/Domain/Election/Enum/ElectionLifecycleState.php` (12 states) · `ADR_20260807_1500` · `ADR-T11` · SD-4A audit (155 files) · B1 (C4) · `PBDIGIT-49` (C6) · `BD-1` (C12) · `PBDIGIT-62`
