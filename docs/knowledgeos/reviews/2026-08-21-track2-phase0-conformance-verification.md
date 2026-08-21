# Verification — Track-2 Phase 0, **as built**: conformance to plan `20260821-1138`

**What was verified:** the delivered Phase-0 implementation *(commits `3e95e28d` … `6ed0bad7`)* against `docs/plans/20260821-1138-track2-deterministic-assurance-phase0-plan.md` — its design decisions `D-1`…`D-6`, its §6 exit criterion, and its §7 Definition of Done.
**Method:** ⭐ **run the tools, re-derive their evidence from git independently, and MUTATE the inputs to prove the checkers are content-driven rather than canned.** ⛔ **Not a document read-through.**
⚠️ **Conflict disclosed, because it changes what this artifact may be cited for:** the verifying process **authored the plan and the review being conformed to** *(`claude-code-session:bc1b47ef`)*. ⭐ **It did NOT author the implementation.** ⇒ this is a **conformance check by the specification's author**, ⛔ **NOT an independent verification of the implementation, and it accepts nothing** (`R-34`/`P-2`).
**Date:** 2026-08-21 · **Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos`, **exit 0**.

---

# 1 · Verdict

> ## ✅ **CONFORMS.** **Phase 0 is implemented, it works, and it is content-driven — proved by mutation, not by reading.**
> **5 findings, ⛔ none blocking the conformance verdict — but TWO are material to how the tool may be USED.** ⭐ **`V-1`: two of the six target defect classes are UNCOVERED IN LIVE USE — the capability is realized, its configuration is not adopted.** ⭐ **`V-3`: `S2` cannot tell DEFINING an enumeration from QUOTING one, so it must not be pointed at the reviews directory yet.** ⭐ **And `V-2`: the tool found FIVE real defects in governed artifacts that human review had passed over.**

---

# 2 · Conformance matrix — measured

| # | Plan requirement | Verdict | Evidence produced by this verification |
|---|---|---|---|
| 1 | `CAP-003` realized · `CAP-004` extended · `CAP-001` extended to a document-local register | ✅ | **three capability trees**: `VocabularyIntegrity` *(new — `ConfusableIdentifier`, `HomoglyphMap`, `DeclaredVocabulary`, `AssessesCompetingCurrentDefinitions`)* · `ReferenceIntegrity` *(new — `SectionReference`, `StepDefinition`, `StepReference`, `AssessesTableColumnCount`)* · `IdentifierIntegrity` *(extended — `AssessesDocumentLocalIntegrity`, `DocumentSectionSequence`)* |
| 2 | tests green in the existing harness | ✅ | `vendor/bin/phpunit --testsuite=EngineeringKnowledge` → ⭐ **OK (220 tests, 501 assertions)**, 0.7 s. ⛔ **No new harness** |
| 3 | **S6 back-test exists and is real** | ✅ | `Phase0BackTest` → **OK (19 tests, 108 assertions)**. It materializes the three states through `git show`, **anchors on §6's line counts (706 · 833 · 1206)**, locks **15 matrix cells**, and **fails closed** on an unreachable commit |
| 4 | the back-test's evidence is true | ✅ | **re-derived independently from git, not read from the test:** `## 4.1` at **349, 436** and `## 4.2` at **404, 442** at `0a2fa71d` · `Phase 2b` at **46, 96, 469, 627, 631, 673** · `7d3abc59:709` **is** §8's *"Removal is refused until the difference is reconciled and re-verified"* row — i.e. `DI-4` |
| 5 | ⭐ **the checkers are CONTENT-DRIVEN, not canned** | ✅ ⭐ **decisive** | **3 mutations of the clean AMD6 artifact** *(§3)* — every verdict flipped, with correct line evidence, and **unmutated copies stayed `PASS`** |
| 6 | **`D-2`** — `INCONCLUSIVE`, never `PASS`, when a rule cannot evaluate | ✅ | every unevaluable case prints *"…nothing to evaluate. **Absence of evidence is not PASS**"* — `CAP-001`'s semantics genuinely inherited |
| 7 | **`D-3`** — warn-only, no gate | ✅ | findings present → **exit 0**; `--strict` → **exit 1**; `verify.sh` **Gate 7 severity `warn`**, and `run_gate`'s `warn` branch returns `PASS` on **both** paths |
| 8 | **`D-4`** — every report states what it did NOT check | ✅ | printed on **every** adapter's output, carrying the DA's formulation verbatim: *mechanical assurance proves **DECLARED STRUCTURE** … architecture review discovers **UNDECLARED ARCHITECTURAL CONTENT**. A PASS here is mechanical, not architectural, assurance* |
| 9 | **`D-6`** — `S8` not built; `OQ-1` raised, not answered | ✅ | ⛔ **no enumeration-vs-content checker exists** *(searched)*; `OQ-1` carried in the plan `D-6`/§9 and in the back-test report §6 as **NOT-CHECKED**, returned to Governance/ARB |
| 10 | **`D-1`** — the four DA-named entry points as adapters | ✅ | `knowledge-lint --profile=structural --root= [--vocabulary=] [--strict]` · `link-check --anchors=` · `identifier-check --document=` · `verify.sh` Gate 7 |
| 11 | read-only | ✅ | `git status` over `docs/` and `scripts/` **unchanged by any verification run**; the tooling itself is committed, not dirty |
| 12 | DoD — catalogue, guide, hypothesis evidence | ✅ | **`CAP-003` → ✅ REALIZED *(2026-08-21 — Track-2 Phase-0, back-tested)*** · `CAP-004`'s row records the intra-document extension · **`H-CAT-1` updated to *"TWO siblings now realized … decision stays ARB's"*** · `developer_guide/knowledgeos/02_track2_structural_profile.md` |

# 3 · ⭐ The mutation test — the check that distinguishes a checker from a stub

**Method: copy the clean (AMD6) artifact, introduce ONE defect per copy, run the adapter over the directory.**

| Mutation | Expected | ⭐ Actual |
|---|---|---|
| heading `## 4.7` → `## 4.3` *(collide with the real §4.3)* | S1 FAIL | ✅ **S1 FAIL** — *"duplicate section identifier '4.3' at lines **587, 874**; non-monotonic section order: '4.6' (line 865) followed by '4.3' (line 874)"* ⭐ **AND S2 FAIL independently** — *"ambiguous section reference '§4.3' (line 84, 84, 165, 174, …)"* across ~20 citation sites |
| add *"See Phase 5 **step 9** …"* | S2 FAIL | ✅ **S2 FAIL** — *"dangling step reference 'step 9' (line 715) — the mandated block defines **1 · 1b · 2 · 3 · 4 · 5**"* ⭐ **it read AMD6's canonical six-slot block correctly** |
| drop one cell from a marker-lifecycle row | S4 FAIL | ✅ **S4 FAIL** — *"ragged table: data row (line 837) has 2 column(s); header row (line 835) has 3"* |
| *(control)* the unmutated copies | PASS | ✅ **PASS in every slice — no cross-contamination** |

⭐ **The first mutation is the strongest result: a single heading change produced BOTH the direct collision AND its second-order reference-ambiguity consequence — the exact `DI-1`→`DI-1`-consequence relationship the back-test documents at `0a2fa71d`, reproduced on a synthetic input the checker had never seen.**
⭐ **`S3` proved live too, outside the back-test:** given a vocabulary config and pointed at `7d3abc59`, it returned **FAIL** — *"confusable identifiers 'CASE A' (line 48, 430, 502, …) and 'CASE α' (line 120, 382, 504, 513, 753) coexist"*.

# 4 · Findings — 5, ⛔ none blocking

| | Finding | Class |
|---|---|---|
| 🔴 **`V-1`** | ⭐ **`S3` IS DORMANT IN LIVE USE — and it is two of the six target classes.** `docs/knowledge/schema/vocabulary-integrity.yaml` **does not exist**, so over the real corpus `S3` returns **`INCONCLUSIVE` 13 of 13**. ⇒ ⛔ **`DI-2` (stale vocabulary) and `DI-7` (confusables) are NOT covered today.** ✅ **The capability is sound** *(§3 proves it)*; it is the **configuration** that is unadopted — a deliberate `D-5` deferral *("the governed copy is a later adoption act this run must not do")*. ⭐ **Recommendation: adopt the config as its own small act. Until then, do not describe the corpus as vocabulary-checked** | coverage · **material** |
| 🔴 **`V-2`** | ⭐ **FIVE LIVE TRUE POSITIVES, and they had survived every review that passed over them.** `S4` FAIL on **`KOS-AIP-GOV-STATE-DURABILITY-IMPLEMENTATION-DESIGN.md`** *(data row 131 has 2 columns; header 127 has 3 — the `tokenRef`s row is missing its third cell)* **plus FOUR more in `docs/knowledgeos/reviews/`** — `2026-08-17-KOS-ARCH-BASELINE-002-architecture-landscape-v2.md` *(rows **50–53** have 2 columns; header 44 has 3 — four merged `Verdict`/`Evidence` cells)* · `KOS-ARCH-BASELINE-003-correction-delivery-note.md` · `…-verification-2-report.md` · `…-verification-3-report.md`. ⭐ **Two confirmed independently by pipe count.** **The implementation design passed the Governance review and three independent Architecture reviews with a malformed table in it.** ⇒ ⭐ **the programme's first operational evidence that is not a back-test — 5 findings, 0 false positives in this class** | evidence ⭐ |
| 🔴 **`V-3`** | ⭐ **`S2` CANNOT DISTINGUISH *DEFINING* AN ENUMERATION FROM *QUOTING* ONE — a systematic false-positive class over review artifacts, not an edge case.** **Measured over `docs/knowledgeos/reviews/` (83 documents): `S2` PASS 58 · 🔴 FAIL 9 · INCONCLUSIVE 16.** The 9 include **all four AMD architecture reviews** *(the AMD5 review alone: "dangling step reference 'step 5' (line 49, 56, 75, 75, 87, …)" — 16 sites, every one a legitimate citation of the PLAN's step 5)*, `…AMD5-SUMMARY.md`, and ⭐ **this very verification report** *("dangling step reference 'step 9' (line 41, 41) — the mandated block defines 1 · 2 · 3 · 4 · 5" — it parsed an enumeration I QUOTED as one I DEFINED)*. ⇒ ⛔ **safe pointed at `docs/knowledgeos/architecture` (its wired target); NOT safe pointed at `docs/knowledgeos/reviews` as-is.** ⭐ **Root cause, stated because it is fixable: the rule needs to know whether a document is the DEFINING carrier of an enumeration or a citing one.** **A RULE-SCOPE question to be RAISED beside `OQ-1` and `EKS-06`, ⛔ never silently suppressed** | rule scope · **material** |
| 🔴 **`V-4`** | ⭐ **`EKS-06` IS ALREADY TAKEN — and the brainstorming document still assumes it is free.** `docs/knowledgeos/backlog/EKS-06-reference-register-identifier-families.md` was minted **today at 14:35** for a **different** problem *(reference resolution bound to a fixed token list)*, while `how_to_optimize_cost.md` **and** the cost review §7 both propose *"`EKS-06` — Governance Assurance & Role Execution Scaling"*. ⇒ ⭐ **a live `DP-1`/`PMR-10` collision waiting to happen.** ⚠️ **And it is UNCOMMITTED** *(untracked, with `00_index.md` modified)*, so the mint is invisible to git and to any collision check. ⭐ **Recommendation: commit the mint, and give the assurance-scaling exploration the NEXT FREE number** | governance · identifier |
| ⚠️ **`V-5`** | **A claim marginally stronger than its code, inside the new gate.** `verify.sh` Gate 7's comment says the step *"never sets `OVERALL_STATUS`"*, yet the block below it contains `OVERALL_STATUS=$FAIL`. ✅ **Behaviour is correct — the line is unreachable, because `run_gate`'s `warn` branch returns `PASS` on both paths** — but the comment is only true once a reader proves that contract. ⚠️ **Inherited from the pre-existing Gate-6 pattern, not introduced here.** *(Recorded with a straight face: this is the defect class the programme exists to catch.)* | cosmetic |

# 5 · What this verification does NOT establish

⛔ **It is not independent verification of the implementation** — the verifier authored the specification *(disclosed above)*.
⛔ **It does not accept Phase 0, adopt Phase 1, or authorize the vocabulary-config adoption.**
⛔ **It says nothing about soundness of the plan's DESIGN** — only that the build conforms to it.
⭐ **And it inherits the tools' own bound, which is the right bound: mechanical assurance proves DECLARED STRUCTURE; it does not discover UNDECLARED ARCHITECTURAL CONTENT.** ⛔ **A green suite is not an architectural verdict.**

# 6 · Recommended next acts *(recommendations only — ⛔ nothing decided here)*

```
1  ⭐ adopt docs/knowledge/schema/vocabulary-integrity.yaml        → closes V-1; S3 becomes live
2  ⭐ commit the EKS-06 mint + index; renumber the assurance-      → closes V-4 before it collides
     scaling exploration to the next free EKS number
3  raise V-3 as a rule-scope question beside OQ-1 and EKS-06      → DEFINING vs QUOTING an enumeration
     ⛔ until then: do NOT point the adapter at docs/knowledgeos/reviews
4  fix V-2's FIVE ragged tables as ordinary documentation edits    → the first live catches, repaired
5  V-5: one-line comment/code tidy in verify.sh Gate 7
⛔ Phase 1 (author-side adoption) remains a SEPARATE act on the back-test evidence. Not taken here.
```

**Traceability:** `docs/plans/20260821-1138-track2-deterministic-assurance-phase0-plan.md` *(`D-1`…`D-6` · §6 · §7)* · `docs/knowledgeos/reviews/2026-08-21-track2-phase0-historical-back-test-report.md` *(FINAL STATUS PASS, authorized scope)* · `docs/knowledgeos/reviews/2026-08-21-cost-optimization-governance-assurance-review.md` *(§7 phasing · §11 capability discovery)* · `docs/implementation/PKS_Phase_III_Capability_Catalog.md` *(`CAP-003` REALIZED · `CAP-004` row · `H-CAT-1`)* · **commands run for this verification:** `vendor/bin/phpunit --testsuite=EngineeringKnowledge` *(220/501)* · `--filter Phase0BackTest` *(19/108)* · `knowledge-lint --profile=structural --root=docs/knowledgeos/architecture` · `--vocabulary=` over `7d3abc59` · `identifier-check --document=` · `link-check` *(58 broken refs, report only — ⛔ no `--apply`)* · `knowledge-lint` *(37 governed docs, all pass)* · `doc-placement --verify` *(3 roots ok, 1 unruled)* · `git show 0a2fa71d|7d3abc59|8307beca:<plan>` · **3 mutations over a temp copy** · `sed -n '31,80p' scripts/verify.sh` *(the `run_gate` warn contract)*.

**VERIFICATION DELIVERED · STOPPING.** ⛔ **NOTHING ACCEPTED, ADOPTED OR AUTHORIZED BY THIS ACT.**
