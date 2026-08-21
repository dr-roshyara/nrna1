# EP-01 Plan — Track-2 Deterministic Assurance (Phase 0)

| | |
|---|---|
| **Kind** | **EP-01 IMPLEMENTATION PLAN.** Phase 0 of the sequence in `docs/knowledgeos/reviews/2026-08-21-cost-optimization-governance-assurance-review.md` §7 |
| **Status** | ▶️ **AUTHORIZED AND IN EXECUTION.** ⭐ **Authorizing act: the Decision Authority's EP-01 plan approval and Phase-0 execution commission of 2026-08-21** — *"Approve the Phase 0 plan"*, followed by the **TRACK 2 — DETERMINISTIC ASSURANCE · PHASE 0 — HISTORICAL BACK-TEST** commission, which supplies role, scope, the authority boundary *(read-only · no governed artifact · no workflow record · no migration state · no gate · no routing · no identifier)*, the reuse directive *(extend `knowledge-lint` / `link-check` / `identifier-check` / `verify.sh`; no new engine)*, the required back-test targets, the exit criteria and the stop conditions. **Recorded here as the authorizing act, per the `CAP-001` precedent `20260802-0015` of recording rather than inferring** *(and per `R-46`: EP-01 plan approval is the DA's act, not the ARB's)*. ⛔ **Nothing beyond Phase 0's scope is authorized by this approval.** |
| **What it is NOT** | ⛔ **not a gate · not a routing change · not an assurance-class model · not a methodology change · not a review-model change.** Those are Phases 2–4 and are **frozen** (`2026-08-01`) |
| **Capabilities** | ⭐ **REALIZE `CAP-003` Vocabulary Integrity** *(catalogued CANDIDATE, `DP-3`)* · ⭐ **EXTEND `CAP-004` Reference Integrity's realization to intra-document references** *(catalogued REALIZED, `DP-4`)* · ⭐ **EXTEND `CAP-001`'s register notion to document-local identifiers** *(`DP-1`)*. ⛔ **No new capability, no new `CAP` id, no new register, no new domain policy** |
| **Governing rules already in force** | **`DP-1`** *(unique within its register(ns))* · **`DP-3`** *(one meaning per context; homonyms qualified)* · **`DP-4`** *(a reference resolves, or is classified as evidence — never repaired on a guess)* · **§0.4.4** *(one canonical CURRENT definition per section; superseded wording only where labelled)* |
| **Why it needs no new authority** | ⭐ **`KnowledgeOS_Deferred_Architecture_Register`, correction ACCEPTED 2026-08-04: *"automation of deterministic work is not speculative architecture"*** — and the register's own gate, *"gated on their OBJECTS existing"*, is now met (§3) |
| **Inputs** | the review above *(§3 measurement · `X-1`…`X-8` · §11 canonical discovery)* · `PKS_Phase_III_Capability_Catalog.md` *(§4 table · §7 `DP` policies · §8 `H-CAT-1` and *"choose capability 2"*)* · the DA response of 2026-08-21 |
| **Placement** | **ES-004.2** — `docs/plans/YYYYMMDD-HHMM-<what>-plan.md`, derived: `php scripts/doc-placement.php --scope=cross-product` not used; this is a plan, whose root ES-004.2 fixes |
| **Label check (`PMR-10`)** | ⭐ **performed, and it is why nothing is minted:** `php scripts/identifier-check.php CAP-002` → **`INCONCLUSIVE`** *("series `CAP` is not a governed register; absence of evidence is not PASS")*. ⇒ ⛔ **this plan mints NO identifier.** It consumes `CAP-001`/`CAP-003`/`CAP-004`, which the catalogue already carries |

**Epistemic classes used throughout: Observed · Derived · Hypothesis · Recommendation · Open Question.** *Not mixed.*

---

# 1 · Objective

> **Make the mechanically-detectable defect classes that Track 2 paid for in amendment cycles detectable by a read-only check, and prove it by REDISCOVERING them in the historical record.**

⛔ **Not the objective:** shortening any review · skipping any gate · classifying or routing work · requiring frontmatter on governed architecture artifacts · changing any artifact under `docs/`.

# 2 · Business capability and DDD position *(EP-03, derived from the repository)*

| Question | Answer | Class |
|---|---|---|
| **Business capability** | **Engineering Knowledge Validation** — the catalogued capability family | Observed |
| **Objective it serves** | independent-architecture capacity spent on **judgement**, not on enumeration and reference errors | Derived |
| **Which bounded context OWNS it** | ⭐ **the Engineering Knowledge Platform capability layer** (`scripts/lib/EngineeringKnowledge/Capabilities/`), which already owns `CAP-001` | Observed |
| **Is another context affected?** | ⛔ **No.** PublicDigit product code is untouched | Observed |
| **Published-Language interaction?** | ⛔ **No** | Derived |
| **Does ownership change?** | ⛔ **No** | Derived |
| **Does the context map change?** | ⛔ **No** | Derived |
| **Impact classification** *(loop Phase 6)* | **Engineering** *(tooling)* + **Repository** *(new files under `scripts/`)*. ⛔ **NOT Strategic Architecture · NOT Tactical Architecture · NOT Governance** | Derived |
| **Stewardship — what must remain stable** | `knowledge-lint`'s existing behaviour over `docs/knowledge/` *(no regression)* · `link-check`'s **≥99 confidence** bar *(never weakened)* · ⭐ **`identifier-check`'s `INCONCLUSIVE` semantics — *absence of evidence is not PASS* — which every new rule INHERITS** | Observed |

# 3 · Why now — the objects the checks read now exist *(Observed)*

| Object | Where it came from |
|---|---|
| a **declared trace table** with resolvable IDs | AMD6 `§0.6.5` |
| **operator instruction IDs** (`P5·1`…`P7·3`) | AMD6 `§4.7` |
| **acceptance criteria 16–20** | AMD6 `§10` |
| a **normative enumeration block** a reference can dangle from | AMD6 `§4.3` |
| ⭐ a **historical defect corpus with known answers** | `bb1708b7` · `0a2fa71d` · `7d3abc59` |

⭐ **The Deferred Register's own criterion for un-gating deferred automation is *"gated on their OBJECTS existing"*. It is met.**

# 4 · Design decisions

| | Decision | Rationale |
|---|---|---|
| ⭐ **`D-1`** | **Rules live in the capability library; the four entry points the DA named are ADAPTERS over them** — `knowledge-lint --profile=structural --root=<dir>` · `link-check --anchors` · `identifier-check` *(document-local register)* · `verify.sh` orchestration | ⭐ **Follows `CAP-001`'s established Domain/Application/Infrastructure/Tests pattern (`ES-005.4` — consume the pattern that exists)**, keeps each rule unit-testable, and ⛔ **avoids coupling Track-2 checking to `CAP-006`'s frontmatter/knowledge-card schema — which is the trap the review named, because REQUIRING a card on governed architecture artifacts is a frozen documentation-architecture change** |
| **`D-2`** | **Every rule emits `Verdict` via `Shared\Domain\Assessment`; a rule that cannot evaluate emits `INCONCLUSIVE`, never `PASS`** | inherited from `CAP-001`; it is the difference between evidence and silence |
| **`D-3`** | **Warn-only. Exit 0 always in Phase 0.** A `--strict` flag may exist but ⛔ **is wired into no gate and no hook** | a blocking check is a governance object (review §6 `X-4`) |
| **`D-4`** | ⭐ **Every report states what it did NOT check, positively** — carrying the DA's formulation verbatim: *mechanical assurance proves DECLARED STRUCTURE; architecture review discovers UNDECLARED ARCHITECTURAL CONTENT* | ⛔ **the false-assurance risk is the top risk (§8), and this is its only real mitigation** |
| **`D-5`** | **Configuration is data, not code** — per-document current vocabulary (the `DI-2` deny-list) lives in a YAML under `docs/knowledge/schema/` alongside the existing schemas | matches `link-check`'s `repository-migrations.yaml` precedent |
| ⚠️ **`D-6`** | **`DI-3`/`DI-6`'s class is IMPLEMENTED LAST and BEHIND A NAMED OPEN QUESTION** *(`OQ-1`)* | ⛔ **it has no catalogued capability; building it first would be inventing a capability by implementation, which is the accretion the standing rule forbids** |

# 5 · Slices *(TDD — RED before GREEN, per slice)*

> **Harness already exists, measured:** `composer.json` PSR-4 `EngineeringKnowledge\` → `scripts/lib/EngineeringKnowledge/`; `phpunit.xml:44–45` `<testsuite name="EngineeringKnowledge">`. ⇒ **`--testsuite=EngineeringKnowledge` runs it. ⛔ No new harness.**

| # | Slice | Capability · rule | RED boundary | Status |
|---|---|---|---|---|
| **S1** | **document-local identifier uniqueness + monotonic ordering** | **`CAP-001` / `DP-1`**, register = the document | tests assert a collision verdict on a fixture with `## 4.1` twice; fail because the rule class does not exist | ✅ `895d38cb` |
| **S2** | **intra-document reference resolution** *(`§x.y` → exactly one heading; `step N` → a normative enumeration that defines `N`)* | **`CAP-004` / `DP-4`** | tests assert `MISSING` for a `step 5` reference against a block defining `1..4` | ✅ `d16a3a78` |
| **S3** | **vocabulary integrity** — stale-token scan against declared current vocabulary; **confusable-identifier detection** *(`CASE B` vs `CASE β`)* | ⭐ **`CAP-003` / `DP-3` — the capability's FIRST realization** | tests assert an unqualified-homonym verdict on a fixture carrying both tokens | ✅ `4a923440` |
| **S4** | **table column-count consistency** | `CAP-004`-adjacent structural rule | fixture with a ragged table | ✅ `73dbe091` |
| **S5** | **unlabelled-superseded heuristic → `WARN` only** | ✅ **`CAP-003` / `DP-3` mapping CONFIRMED by the back-test** *(`OQ-2` hypothesis: §0.4.4's canonical-document rule is DP-3 applied to a document — AMD5 `DI-4` §8:709 vs §0.5.1:120 and §4:382 → WARN; AMD6 quiet)* | fixture with two current dispositions, one unlabelled | ✅ `a2529dde` |
| **S6** | ⭐ **the back-test harness** *(§6)* | — | runs green only when §6's matrix is reproduced | ✅ `b753cac1` |
| **S7** | **adapters + `verify.sh` wiring** *(warn-only)* | — | integration tests at the four real entry points fire the known S1–S5 verdicts, exit 0, carry D-4's statement | ✅ `7f04e220` |
| ⚠️ **S8** | **enumeration-vs-content agreement** (`DI-3`/`DI-6`) | ⛔ **BLOCKED on `OQ-1`** | not started | ⛔ |

# 6 · ⭐ The exit criterion — a falsification test, and it is already verified as FEASIBLE

**Method:** run the checker over `git show <commit>:docs/knowledgeos/architecture/KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN.md` and require the stated findings. ⭐ **Every row below was CONFIRMED PRESENT in the historical state before this plan was written — the back-test cannot silently be unrunnable.**

| Commit | Lines | Must rediscover | ⭐ Verified present *(Observed, 2026-08-21)* |
|---|---|---|---|
| **`0a2fa71d`** *(AMD4)* | 706 | **`DI-1`** identifier collision | ✅ **`## 4.1` ×2 (lines 349, 436) · `## 4.2` ×2 (404, 442)** |
| **`0a2fa71d`** | 706 | **`DI-1`** non-monotonic ordering | ✅ **order is `4.1 · 4.3 · 4.4 · 4.2 · 4.0 · 4.1 · 4.2`** |
| **`0a2fa71d`** | 706 | **`DI-2`** stale vocabulary | ✅ **`Phase 2b` ×6** |
| **`0a2fa71d`** | 706 | **`DI-3`** count vs rows *(⚠️ `S8`, `OQ-1`)* | ✅ **heading says *"there are SIX"*; the table has 8 data rows** |
| **`7d3abc59`** *(AMD5)* | 833 | **`DI-5`** dangling reference | ✅ **criterion 14 and §4.5 cite *"Phase 5 step 5"*; the mandated block defines `1 · 2 · 3 · 4` only** |
| **`7d3abc59`** | 833 | **`DI-7`** confusable identifiers | ✅ **`CASE β` ×5 and `CASE B` ×8 coexist** |
| **`7d3abc59`** | 833 | **`DI-6`** gate set vs Gates column *(⚠️ `S8`, `OQ-1`)* | ✅ **`3 GATE EXECUTION` present; its named set contradicts the column** |
| **`7d3abc59`** | 833 | **`DI-4`** unlabelled superseded *(`WARN`)* | ✅ **§8's Phase-7 row** |
| ⭐ **`8307beca`** *(AMD6, current)* | 1206 | ⭐ **NOTHING in classes `S1`–`S4`** | **the regression direction: the checker must be QUIET on the repaired artifact** |

> ## ⛔ **If `S1`–`S4` do not reproduce their rows, Phase 0 FAILS and Phase 1 must not start.**
> ⭐ **And the AMD6 row is as important as the historical ones: a checker that fires on the repaired artifact is a false-positive generator, which would cost more than it saves.**

# 7 · Definition of Done

```
□ CAP-003 realized, CAP-004's realization extended, CAP-001's register notion extended
□ every rule unit-tested in Tests/{Domain,Application,Infrastructure};  --testsuite=EngineeringKnowledge green
□ §6's back-test matrix reproduced, including the AMD6 quiet row
□ every report carries D-4's "what was NOT checked" statement
□ warn-only:  exit 0;  no gate, no hook, no CI wiring
□ developer guide written  (developer_guide/<area>/ — Definition of Done, standing rule)
□ the capability catalogue's CAP-003 row updated from Candidate → Realized, with its realization named
□ H-CAT-1 evidence recorded  (do the two realized siblings share a contract?)
□ OQ-1 raised, not answered
□ ⛔ nothing under docs/ modified by the checker;  ⛔ no identifier minted
```

# 8 · Risks

| | Risk | Mitigation |
|---|---|---|
| 🔴 | **False assurance** — GREEN reads as *"the design is sound"* | **`D-4`**, and the DA's formulation printed in every report |
| 🔴 | **Capability-by-implementation** — `S8` quietly establishes an uncatalogued capability | **`D-6`**: `S8` is last and blocked on `OQ-1` |
| ⚠️ | **False positives on the current artifact** | the AMD6 quiet row in §6 is a first-class exit criterion, not an afterthought |
| ⚠️ | **Scope creep to a gate** | **`D-3`**; Phases 2–4 each name their own authority in the review |
| ⚠️ | **Displacement of PublicDigit priority** *(WP-7C → WP-8 → EPIC-005)* | one slice set, no product code touched; ⛔ **if it cannot be afforded, defer whole and lose nothing** |
| ⚠️ | **`knowledge-lint` regression** | its `docs/knowledge/` path is untouched; the profile is additive and covered by its own test |

# 9 · Open questions — ⛔ none answered here

| | Question | Owner |
|---|---|---|
| 🔴 **`OQ-1`** | **`DI-3`/`DI-6`'s class — *a stated enumeration contradicting the content it enumerates* — has NO catalogued capability.** Is it a new capability, an extension of an existing `DP`, or not a capability at all? ⛔ **Named and returned to governance; nothing invented; `S8` blocked until answered** | **Governance / ARB** *(capability existence is not Architecture's to decide)* |
| ✅ **`OQ-2`** *(evidence recorded; decision still ARB's)* | **Is `DI-4` (competing current definitions) genuinely `DP-3`?** ⭐ **The `S5` back-test CONFIRMED the hypothesis**: §0.4.4's canonical-document rule is `DP-3` applied to a document — AMD5's unlabelled single-remedy §8 Phase-7 row (709) competed with its own declared §4.4 split (120, 382); AMD6's repaired row (labelled + two-branch) went quiet. **Recorded as evidence, not adopted** | **ARB**, on `S5`'s recorded evidence |
| ⚠️ **`OQ-3`** | The `CAP` and `EKS` series are **not governed registers** (`identifier-check` → `INCONCLUSIVE`). Should their index files be added to the governed register map? | Governance — ⛔ **not a side effect of this plan** |
| ⚠️ **`OQ-4`** | Does realizing capability 2 confirm or refute **`H-CAT-1`** *(a parent "Validation Capability" abstraction)*? | recorded as evidence, decided by ARB |

# 10 · Next actions

```
1  ⭐ DA: approve or reject THIS PLAN            ← the only thing outstanding; nothing starts without it
2  Governance/ARB: OQ-1                          (unblocks S8 only; S1–S7 do not wait on it)
3  on approval: S1 → S7, RED before GREEN, one commit per slice
4  back-test report + developer guide + CAP-003 catalogue-row update
5  ⛔ STOP.  Phase 1 (author-side adoption) is a separate act on §6's evidence
```

⛔ **This plan does NOT:** write code · create a gate · modify any governed document · mint any identifier · answer `OQ-1`…`OQ-4` · touch the AMD6 chain · change the review model · lift or reinterpret the 2026-08-01 freeze.

**Traceability:** `docs/knowledgeos/reviews/2026-08-21-cost-optimization-governance-assurance-review.md` *(§3 measurement · §5 script discovery · §7 phasing · §11 capability discovery · the DA's `X-5` formulation)* · `docs/implementation/PKS_Phase_III_Capability_Catalog.md` *(§4 `CAP-001`…`CAP-006` · §7 `DP-1`…`DP-6` · §8 `H-CAT-1`)* · `docs/plans/20260802-0015-pks-identifier-validation-capability-plan.md` *(`R-46`; the `CAP-001` precedent and the DP-carries-policy pattern)* · `KnowledgeOS_Deferred_Architecture_Register` *(the ACCEPTED 2026-08-04 automation correction)* · `.claude/CLAUDE.md` *(EP-01/EP-03 · the 2026-08-01 freeze · the developer-guide DoD · the manual-editing policy)* · `ES-004.2` · `ES-005.4` · `ES-006.1` · `PMR-10` · **evidence read directly:** `composer.json` PSR-4 · `phpunit.xml:44–45` · `scripts/lib/EngineeringKnowledge/**` · `scripts/{knowledge-lint,link-check,identifier-check,doc-placement}.php` · `scripts/verify.sh` · **the §6 back-test rows, each verified present at its commit before this plan was written.**

**PLAN PROPOSED · STOPPING.** ⛔ **NO CODE WRITTEN. AWAITING EP-01 PLAN APPROVAL.**
