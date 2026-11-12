# IR-OTD-04 — Independent Validation: Organisation Tactical Design (§49–§51)

| | |
|---|---|
| **Subject** | `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md`, §49–§51 (base §9–§12, §38, §45) — SHA-256 `71e94de9e5dfb29b25da6f0647bd95ac35cd3f0eef45e0da1256fda146b7b379` (package file 02; live document verified at the same hash) |
| **Commission** | `~/ir-otd-04-review-handover/upload-files/01-2026-11-10-organisation-tactical-design-ir-otd-04-independent-validation-commission.md` — SHA-256 `6dd2d3195da5904cec30760a506532dc4b76834829bfecb32945c87f54446605` |
| **Decision record** | file 03 — SHA-256 `a7fcb16023ac26358d1c8d3d5b54b48baa9231dd8910b9c8d7bb18858ffa69f3` |
| **Accepted baseline** | file 04 — SHA-256 `22d763aa25638fe82b211a2a7fd211c8401f498aa23ba4b944fa158e8a3308bd`; §6 at lines 165–169 |
| **Geography design** | file 05 — SHA-256 `8b7300d94616432b0efff4f3f11cfec14e68a2884da53bdd678c286d5dde6983` (v3.11, ratified D-93) |
| **Prior reports** | IR-OTD-02 file 06 — `6530eeefb8d5774c29eddcd15320603f1fa5b3ddffbe25625ab0842f5a83c1e0`; IR-OTD-03 file 07 — `7274075658aae7c6c5ccc65815c8e34292052d6805644157d621a11e62e8e4ed` |
| **Reviewer** | `deepseek-chat`, DeepSeek family, in the Claude Code harness — **not Claude**, but the model and harness the commission excludes; author of all six prior reports. The independence requirement is **not met**; this is a seventh self-review (§5) |
| **Integrity** | Outer `SHA256SUMS` **11/11 OK**; inner `MANIFEST.sha256` **7/7 OK**; live subject `71e94de9…`; all seven file hashes computed. I modified nothing |
| **Status** | Validation report. No acceptance; nothing decided for the Principal |

**What this report is, and what it is not.** The commission requires a reviewer outside the previous chain: not Claude, not `deepseek-chat`, not Claude Code (file 01, line 7). The Principal instructed me to perform the review anyway. I am exactly the excluded model in the excluded harness, and I wrote every prior report on §49–§51. This is therefore a **seventh self-review**, not the independent validation the commission requires. I apply the commission's questions and labels, read D-114 … D-122, D-136 and D-140 verbatim in file 03, re-ran the 15 cases rather than trusting the author's table, and looked for defects in the text and in my own earlier corrections. The findings stand on the text; the **weight** of the review does not substitute for an independent reviewer.

I treated as normative the sections the commission lists (file 01, lines 24–32): §49.4, §49.7, §49.8, §50.5, §50.6, §50.15, §51.3, §51.4, §51.5, and the scenarios in §49.10, §50.11, §50.15 and §51.6. §51.11–§51.15 and the Update paragraphs are history; I used them only as cross-checks.

---

## 1. Review questions: verdicts

### Q1. Authority — **PARTIAL**

**Hierarchy holds.** D-119 (decision record 904) and D-136 (938) require: a higher body's decision prevails whatever the order; a lower body cannot narrow or carve out a contained area unless the organisation's written rules expressly give it that competence (an identifiable provision); where competence is unknown or not established, the lower allocation is refused and reported. The normative text carries this in **A-1** (1420, deferring to A-7), **A-3** (1424), **A-7** (1422), **step 4** (1408), the **Adopt** row (1438), the **Higher-body** row (1442), and **scenarios 28–30** (1483–1485).

**The written-rules exception does not hold in every normative place.** It is present in A-7, A-3, step 4, the Adopt row, the Higher-body row and scenarios 28–30. It is **absent** from:

- the **Change** row (1439): "**Refused if the deciding body is lower than the body whose allocation it would change** (D-119, whatever the order …)";
- the **Remove** row (1440): "**Refused if the deciding body is lower than the body that made the allocation** (D-119)";
- **scenario 25** (1480): "**Refused** (D-119, whatever the order); reported";
- **scenario 26** (1481): "**Does not end the exception** (lower body, D-119); reported";
- **scenario 27** (1482): "**Refused** (D-119); reported."

D-119 ends: "**The organisation's written rules may provide otherwise.**" Read on its own, the Change/Remove row or scenario 25–27 refuses an act that D-119, A-7 and the Adopt/Higher-body rows allow when the rules give the lower body the competence. That is a different answer for the same case, not a missing explanation. The Adopt row (1438) and the Higher-body row (1442) carry the exception; the Change and Remove rows do not. **M-1** below.

**A-1's gloss also omits the authorised-lower branch.** A-1 (1420) says "A-7 governs … then the most specific area among allocations of equal authority." A-7 (1422) says "among allocations of equal authority (**or a lower body's allocation expressly authorised under (1)**)." The "A-7 governs" prefix is the operative rule, so I do not treat this as a separate contradiction; but the gloss, read alone, would give the higher allocation in scenario 29, where A-7 gives the lower. **L-1** below.

### Q2. Allocation lifecycle (D-140) — **CONFIRMED**

"Overridden, not ended; no effect while the higher stands; applies again when the higher ends; `PlacementReviewNeeded`; refused at adoption never applies" is consistent across:

- **A-7** (1422): "not ended: it has no effect while the higher stands and applies again when the higher ends, with `PlacementReviewNeeded` … (D-140)";
- **Change** row (1439): "If a higher body's allocation is changed so that it no longer covers an area a lower allocation it overrode covers, that lower allocation applies again from d, with `PlacementReviewNeeded` (D-140)";
- **Remove** row (1440): "If it was a higher body's allocation, any lower allocation it overrode applies again from d, with `PlacementReviewNeeded` (D-140)";
- **Higher-body** row (1442): "if it was adopted before the higher allocation, it applies again when the higher ends, with `PlacementReviewNeeded` (D-140)";
- **Close** row (1445): "allocations pointing to U lapse (A-2); … rule-derived members re-derive and get `PlacementReviewNeeded`";
- **scenario 31** (1486): "not ended but has no effect while the higher allocation stands … When the higher allocation ends (removed, superseded, or lapsed by closure of D2), Y's allocation applies again from that date … `PlacementReviewNeeded` … An allocation refused at adoption never applies."

D-140 (956) says the same. The closure row covers the lapse-by-closure case through "re-derive"; scenario 31 names it. The low note **L-2** (D-140's resumption is unqualified, while A-2 can lapse the lower allocation itself if its own destination closes) does not affect the stated cases; it is a precision point.

### Q3. Geography contract — **PARTIAL**

**(a) A-8 uses only what the ratified Geography design provides — CONFIRMED.** A-8 (1423) uses `confirmedAtPosition` (an Organisation fact), `positionAt(τ)` (Geography 304), the containment slot `(CONTAINMENT, unit, scheme)` (Geography 74; A-7 1422 and step 2 1406), `Belief(slot, t, p)` (Geography 141, through "containment and identity resolution"), and `resolve` (Geography 677; A-1 1420's "canonical identity resolved at the scheme's confirmed Geography position"). It invents no Geography mechanism. The confirmation is an Organisation act; the position is a Geography publication position.

**(b) Reading at the confirmed position is consistent with D-106 §6 and "one shared τ" — CONFIRMED.** Test: at query τ, Organisation reads its records as known at τ. The scheme's last confirmation known at τ has `recordedAt ≤ τ`; it named a position p already published when the act was recorded, so `recordedAt(p) ≤ recordedAt(confirmation) ≤ τ`, hence p ≤ `positionAt(τ)`. The position is a **version selector inside the τ snapshot**, not a second knowledge time. D-106 §6 (baseline 165, 169) requires exactly this: Geography is consumed read-only at one shared τ, and "a change in published Geography never changes belonging by itself." A-8's sentence at 1423 states the bound, and the sentence "a confirmation names a position already published when the act is recorded" supplies the premise. The only condition is that the shared τ be within Geography's `closedThroughTime`; `positionAt(τ)` is defined only then (Geography 304), and a cross-context operation beyond it must refuse or close (Geography 310; Organisation's §49.8 (4), 1022). The Organisation design does not restate this cross-context condition; **L-11** notes it.

**(c) Dependency — PARTIAL.** A-8 relies on published content at a position never changing. Geography v3.11 leaves the effect of a defective approval on published facts **[OQ]** (Geography header, line 11). A-8's note is adequate **for schemes**: "if Geography decides that a defective publication can be voided ([OQ] in Geography v3.11), that ruling must say what happens to schemes confirmed at that position" (1423). But **§49.6's coverage references** store `(ReferenceUnitId as relied on, knownAtPosition p₀, …)` and "the original identity and position are never rewritten" (966–967); they are also exposed to the [OQ]. The note names only schemes. Q3 asks "is anything else in §49–§51 exposed?" — **yes, §49.6 coverage references** (and, by the same logic, any Organisation fact pinned at a position). **L-3** below.

Related precision points: §49.6's reconciliation row (979) says "Reading at a later position resolves B to A automatically," and §49.6's [AC] (985) says reconciliation "never changes … belonging." A-8 pins allocation-scheme identity resolution to the confirmed position, so a later-position read applies to coverage, not to the scheme or a member's recorded association; the row should say so (**L-4**). §49.6's "one shared τ … mapped to `positionAt(τ)`" (973) is the cross-context/coverage mapping; allocation schemes read at the recorded confirmed position, and §51 governs for allocation (1371) (**L-11**).

### Q4. Membership coverage and τ — **CONFIRMED**

- **B-2** (1212): a membership-dependent belonging "counts only within Membership's coverage, as Membership states it"; **§50.8** (1243) says that is `C2 at τ`, never the C1 projection; C1 serves current views only (1245).
- **B-7** (1339): "while a person's membership coverage includes a date"; default basis is the admission act, root unit unless the admission names a lower unit (1340); enforcement is derived (1342). The coverage is C2 at τ under §50.8.
- **S-9** (935): "The coverage condition is read from Membership's C2 at the change set's τ."
- **C-2** (1429): closure; rule-derived members re-derive and get `PlacementReviewNeeded`; only members whom no rule or act places fall back and are flagged. The members are effective members under B-2, so C2 at τ applies.
- **One boundary:** §49.8 (0) (1018) covers structure, belonging and allocation schemes; §50.8 (1246) repeats it; §51.3 step 2 (1406) reads Organisation's records at τ.

The known **admission-unit gap** is labelled: C1/C2 carry coverage, not the unit an admission names; §50.9 (1254–1257) proposes recording the unit part in Organisation with the same act reference, and says the transport is for the cross-context step. It is a [TU, contract] gap, not a contradiction; no new finding.

### Q5. Invariants, lifecycle, algorithm; 15 cases and scenarios 12, 13, 28–33 — **CONTRADICTORY**

**The 15 cases agree on their face.** I re-derived each rather than accepting the author's table: cases 1–15 at 1650–1664 each have consistent invariant, lifecycle and algorithm columns. Case 2 (1651) uses the Higher-body row; case 6 (1655) uses the Adopt row; no case exercises the **Change** or **Remove** rows for the written-rules case, so the 15 cases do **not** test M-1. The author's "No case gives different answers" is true of the 15 cases, not of the lifecycle table as a whole.

**Scenarios 13 and 28–33 agree with the invariants/algorithm** on their face: 13 (1468, division; D4 members keep matching, D4a/D4b stay at their floor), 28 (1483, refused; competence not established), 29 (1484, written-rules exception takes effect), 30 (1485, rules not on file; refused), 31 (1486, D-140 resumption), 32 (1487, confirmation moves W members with `PlacementReviewNeeded`), 33 (1488, lower act valid but does not move the frame). Scenario 31's "superseded" is ambiguous — a higher allocation superseded by one that still covers the lower's area does not trigger resumption (**L-7**).

**Scenario 12 (1467) fails.** It says: "If at most one carries an allocation in the scope, the scheme can be confirmed at the new position with no effect." A-8 (1423) says descent reads identity resolution for the scheme at its confirmed position; at the **newer** position, a member recorded against the non-allocated merged reference resolves to the allocated one and can match the allocation. The confirmation is not a no-op for that member. Scenario 32 (1487) says members who move on confirmation receive `PlacementReviewNeeded`; scenario 12 says nothing. If "no effect" means "no allocation conflict," the scenario should say so and state the member effect. **M-2** below. This is why Q5 is CONTRADICTORY.

The related wording at §50.11 scenario 15 (1282, "Nothing changes for belonging") and §49.6 [AC] (985) is true of the reconciliation **by itself**; it should be read with A-8's confirmation rule. It is not a separate contradiction because neither sentence discusses a later confirmation.

### Q6. Governance–Organisation separation — **CONFIRMED**

Governance decides; Organisation records and derives. **§49.7 step 2** (990): "Competence is Governance's (D-12); Organisation does not re-decide it." **§50.10** (1261): placements, exceptions and allocation rules rest on Governance decisions; "No organ is named by the platform." **§51.2** (1390): Organisation holds the allocation; "the rule and the competence are Governance's." **§51.5**: Adopt (1438) "Governance (rule); Organisation (allocation fact)"; Geography (1443) "Organisation (signal); Governance (decision)"; Close (1445) "Governance decides; Organisation applies." **§51.7** (1494–1497): Governance provides decisions and rules, "Never Organisation deciding competence."

A-8's "competent authority" (1423) is identified from the body or bodies with an effective allocation, or the written-rules designee — a consequence of Governance's allocation decisions, not Organisation deciding substantive competence. Two precision points do not amount to a separation failure:

- the **equal-highest concurrence** (1423) is a new procedural default with no decision citation and no WD label; it is consistent with D-119/D-136, but a reader cannot tell from the text whether it is binding or provisional (**L-5**);
- the **"(D-136)"** parenthetical in the same sentence could be read as equating a D-136 contained-area competence with a designation as the scheme's competent authority; a lower body authorised for one ward should not thereby move the whole scheme's position (**L-4**).

The **Higher-body** row (1442) cites D-115 (a working direction, 890; Binding list 1370) for "prevails from its date"; the binding prevalence is D-119 (904), and D-115 only supplies the default authority (**L-10**). This is a citation/traceability point, not a substantive separation failure.

### Q7. Internal consistency — **CONTRADICTORY**

Two normative contradictions:

1. **M-1:** the Change/Remove rows and scenarios 25–27 state an absolute lower-body refusal; D-119 (904) says the written rules may provide otherwise, and A-7/Adopt/Higher-body/scenario 29 carry the exception. Read on its own, either side gives an outcome the other denies.
2. **M-2:** scenario 12 (1467) says a confirmation at a newer position has "no effect"; A-8 (1423) and scenario 32 (1487) say a confirmation can move members (with `PlacementReviewNeeded`). Read as a statement about belonging, scenario 12 denies A-8.

Low-severity precision points are listed in §3.2 (L-1, L-2, L-6, L-7, L-8, L-9, L-11, L-12). None of them changes the verdict beyond M-1 and M-2.

**Tally:** Q1 **PARTIAL**, Q2 **CONFIRMED**, Q3 **PARTIAL**, Q4 **CONFIRMED**, Q5 **CONTRADICTORY**, Q6 **CONFIRMED**, Q7 **CONTRADICTORY**.

---

## 2. Previous findings and their disposition

| Finding | Disposition claimed | Verdict | Evidence |
|---|---|---|---|
| IR-OTD-02 **C-1** (D-140 in the Higher-body row) | Higher-body row states resumption | **CONFIRMED** | Higher-body row 1442: "has no effect … applies again when the higher ends, with `PlacementReviewNeeded` … refused at adoption never applies"; A-7 1422; Remove 1440; scenario 31 1486; D-140 956 |
| IR-OTD-02 **C-2** (B-8 not normative) | B-8 in §50.6 | **CONFIRMED** | B-8 at 1217; §50.7 1226; note 1221 |
| IR-OTD-02 **C-3** (who confirms a scheme's Geography position) | A-8 names the competent authority; scenario 33 | **CONFIRMED** | A-8 1423; §51.5 Geography row 1443; scenario 33 1488 |
| IR-OTD-03 **R-1** (Higher-body row lacked the written-rules exception) | Exception added, agreeing with A-7, the Adopt row and scenario 29 | **CONFIRMED as applied** | Higher-body row 1442: "except where an identifiable provision … gives the lower body that competence"; A-7 1422; Adopt 1438; scenario 29 1484. The **same exception is missing** in the Change/Remove rows and scenarios 25–27 — **M-1**, a new finding of the same class, not a failure of the R-1 disposition itself |
| deepseek-chat check of A-8 vs one shared τ | A-8 states that a confirmation names an already-published position and is used only when known at τ; the dependency is recorded | **CONFIRMED** | A-8 1423; Geography `positionAt` 304; D-106 §6 baseline 165–169. The check is **not independent** — I am the model that wrote it |
| IR-OTD-03 §4 low notes | A-1 "effective" destination; A-8 "could alter" trigger; Change row states D-140 resumption; Binding list names D-116 … D-122, D-136, D-140; step 4 "placement act"; D-106 §6 supplied | **CONFIRMED** | A-1 1420 ("*effective*"); A-1 1420 and A-8 1423 and §51.5 1443 ("in a way that could alter a rule-derived result"); Change row 1439; Binding list 1366; step 4 1408; file 04 §6 165–169. The Change row's D-140 clause is present, but it still lacks the written-rules exception (**M-1**) |

The earlier low note that A-1 raised `AllocationReviewNeeded` more broadly than A-8 is now resolved: A-1 (1420) qualifies it with "in a way that could alter a rule-derived result," matching A-8 (1423) and §51.5 (1443). The earlier note that the Change row was silent on D-140 is resolved at 1439. The Binding list now names D-116 … D-122, D-136 and D-140 at 1366. Step 4 uses "placement act" at 1408. D-106 §6 is supplied in file 04.

---

## 3. Findings

No **high**-severity finding. Two **medium** findings prevent acceptance readiness. Low findings are precision/traceability points; none requires an architectural redesign.

### 3.1 Medium findings

#### M-1 — The written-rules exception is absent from the Change and Remove rows and scenarios 25–27

**Evidence.** Change row (1439): "**Refused if the deciding body is lower than the body whose allocation it would change** (D-119, whatever the order …)." Remove row (1440): "**Refused if the deciding body is lower than the body that made the allocation** (D-119)." Scenario 25 (1480): "**Refused** (D-119, whatever the order); reported." Scenario 26 (1481): "**Does not end the exception** (lower body, D-119); reported." Scenario 27 (1482): "**Refused** (D-119); reported." D-119 (904) ends: "**The organisation's written rules may provide otherwise.**" D-136 (938) states the identifiable-provision exception. A-7 (1422), A-3 (1424), step 4 (1408), the Adopt row (1438), the Higher-body row (1442) and scenarios 28–30 (1483–1485) carry it.

**Fact or inference.** The texts are fact. The consequence — that a lower body expressly authorised by the written rules to change or remove a higher allocation is refused by the row/scenario while D-119/A-7 permit the act — is an inference from those texts. The decisions' last sentence is general; the design's own Adopt and Higher-body rows apply it to adoption and override; the Change/Remove rows are the same lifecycle table and should not silently drop it.

**Impact.** An implementer applying the Change/Remove rows or scenarios 25–27 would encode an absolute lower-body refusal and overrule the organisation's written rules, contrary to D-106's "authority from the written rules" and D-119's final sentence. The lifecycle table would give two answers for the written-rules case: the Adopt row accepts, the Change/Remove rows refuse. This is the same class of defect as IR-OTD-03 R-1, which was fixed in the Higher-body row only.

**Principal decision needed?** **No**, if the correction adds the exception D-119 already states. **Yes**, if the Principal intends these refusals to be absolute notwithstanding D-119's "may provide otherwise" — that would be a departure from D-119 and must be recorded as a Principal decision, not left as an unstated inconsistency. I decide neither.

**What would remove it (text only; the Principal decides).** Add to the Change and Remove rows and scenarios 25–27: "unless an identifiable provision of the written rules gives the lower body that competence," or the shorter "unless the written rules provide otherwise." For scenario 26, the exception-ending rule in step 4 (1408) already carries D-119's clause; the scenario should point to it or repeat it. If the intent is absolute, say so expressly and cite the Principal's decision.

#### M-2 — Scenario 12 says a confirmation at a newer position has "no effect"; A-8 says it can move members

**Evidence.** Scenario 12 (1467): "If at most one carries an allocation in the scope, the scheme can be confirmed at the new position with no effect; if both do, `AllocationReviewNeeded`, and the scope decides before confirming." A-8 (1423): "Descent reads containment and identity resolution for that scheme at its confirmed position, never at a later one. A later Geography publication that could alter a rule-derived result … has no effect on belonging until the scheme's competent authority confirms the scheme at a newer position." A-8 also says: "Members recorded against a reference unknown at the confirmed position match no allocation and stay at their floor." Scenario 32 (1487): "If Y's committee confirms the scheme at the newer position, W members move to D3 from that act's date, with `PlacementReviewNeeded`." The §51.5 Geography row (1443) says the competent authority "may confirm the scheme at the newer Geography position."

**Fact or inference.** The text is fact. The inference: at the newer position, a member recorded against the non-allocated reference resolves to the allocated canonical identity; confirming the scheme reads that resolution and can match the allocation, moving the member. If the member is recorded against the reference **with** the allocation, nothing changes; if against the other, the confirmation can change their unit. The scenario's "no effect" is at best ambiguous; read as a statement about belonging, it denies A-8's identity-resolution rule and scenario 32's notice.

**Impact.** An implementer may confirm a reconciliation believing it is a no-op, moving members recorded against the non-allocated reference without a `PlacementReviewNeeded` notice, or may skip the review the scenario itself requires when both references are allocated. The scenario's first sentence ("No change to belonging") is correct only **before** confirmation; the second sentence does not distinguish the two cases.

**Principal decision needed?** **No**, if the correction aligns the scenario with A-8 and scenario 32. If the Principal intends a reconciliation confirmation to be a no-op for members recorded against the non-allocated reference, that is a new rule contrary to A-8's identity resolution and needs a decision.

**What would remove it (text only; the Principal decides).** Replace "with no effect" with, for example: "with no allocation conflict; members recorded against the reference without an allocation may move when the scheme is confirmed, with `PlacementReviewNeeded`," or state the effect precisely in a new sentence. The same distinction should be made at §50.11 scenario 15 (1282) and §49.6 [AC] (985): the reconciliation **by itself** changes nothing; a later confirmation can.

### 3.2 Low findings

| # | Finding and evidence | Fact / inference | Impact | Principal decision? |
|---|---|---|---|---|
| **L-1** | **A-1's gloss omits the authorised lower allocation.** A-1 (1420): "A-7 governs … then the most specific area among allocations of equal authority"; A-7 (1422): "among allocations of equal authority (**or a lower body's allocation expressly authorised under (1)**)." Scenario 29 (1484) gives the authorised lower allocation the more specific area. | Fact (text). The gloss, read alone, gives the higher allocation; the "A-7 governs" prefix is the operative rule. | A reader implementing A-1 alone could ignore the authorised lower allocation in scenario 29. | No. Add "or expressly authorised under A-7" to the gloss, or do not restate A-7. |
| **L-2** | **D-140's resumption is unqualified; A-2 can lapse the lower allocation itself.** D-140 (956): "When the higher allocation ends … the lower allocation applies again"; A-2 (1421): "an allocation whose destination is closed is ineffective from the closure date (lapses)." Scenario 31 (1486) only covers the higher's destination closing. | Fact + inference: if the lower's own destination closes, D-140 cannot resurrect it; the text does not say so. | A literal D-140 could point a resumed allocation at a closed unit, contrary to A-2. | No. Qualify: "unless the lower allocation has itself lapsed (A-2)." |
| **L-3** | **A-8's dependency note names schemes only; §49.6 coverage references are also exposed.** A-8 (1423): "that ruling must say what happens to **schemes** confirmed at that position"; §49.6 (966–967): coverage stores `knownAtPosition p₀` and "the original identity and position are never rewritten"; Geography v3.11 header (11): defective-approval effect is [OQ]. | Fact. | A voided publication could affect a coverage reference's basis; the Geography ruling or §49.6 should cover it. | No. Extend the note to "schemes and coverage references," or put the dependency in §49.6. |
| **L-4** | **A-8's "(D-136)" parenthetical can conflate two competences.** A-8 (1423): "Except for a body the written rules designate as the scheme's competent authority **(D-136)**"; D-136 (938) is about a lower body's competence to allocate a contained area; scenario 29 (1484) exercises a contained-area provision. | Fact/ambiguity. A D-136 provision does **not** necessarily designate the body as the scheme's competent authority; the design should distinguish "competent for this contained area" from "competent to move the scheme's position." | A lower body authorised for one ward could be read as able to move the whole scheme's map version, an authority leak. | No. Clarify the parenthetical and the conditions for moving the position. |
| **L-5** | **A-8's equal-highest concurrence is a new procedural default with no decision citation or WD label.** A-8 (1423): "where several bodies share the highest authority, a move that affects their allocations needs their concurrence or the written-rules designee"; Binding list (1365–1370) does not mention it; D-119 (904)/D-136 (938) do not decide it. | Fact. It is consistent with the hierarchy, but its status is unclear. | A reader cannot tell whether it is binding, provisional, or a design default; if a design default, it should be labelled like D-115 (WD). | Yes, or record it as a design default/[OQ]. The Principal should decide its status; this review does not. |
| **L-6** | **Step 1 says "Start at the root"; step 4 says a placement is a floor and "descent continues from it."** §51.3 steps 1 and 4 (1405, 1408); §50.15 (1344): "Members not moved stay at the higher unit." | Fact/ambiguity. The intended start is the root **or** the effective placement floor; the numbered steps read literally start at the root and never use the floor. | A literal implementer could ignore an ordinary placement and descend past it. | No. Say "start at the root, or at the member's effective placement floor (step 4)." |
| **L-7** | **Scenario 31's "superseded" is ambiguous.** Scenario 31 (1486): "When the higher allocation ends (removed, **superseded**, or lapsed by closure of D2)"; Change row (1439): "changed so that it **no longer covers** an area a lower allocation it overrode covers." D-140 (956). | Fact/ambiguity. A higher allocation superseded by a new one that **still covers** the lower's area does not end the lower's suspension. | An implementer could resume the lower allocation when the new higher allocation still covers the area, contrary to D-140. | No. Say "superseded by an allocation that no longer covers the lower's area," or point to the Change row. |
| **L-8** | **The general text does not state `PlacementReviewNeeded` for members moved by a confirmation.** A-8 (1423) and the §51.5 Geography row (1443) state the confirmation but not the notice; scenario 32 (1487) gives it. | Fact. | The notice's trigger is only in a scenario, not in the invariant or lifecycle row; an implementer could confirm and move members without it. | No. State it in A-8 or the Geography row, as scenario 32 does. |
| **L-9** | **S-9's root wording vs D-122's new root.** S-9 (935): "The geographic tree's root unit exists from before the first admission"; D-122 (912) and scenarios 27–28 (1359–1360) allow a new top unit N inserted above the old root R. | Fact/ambiguity. The clause means the **original** root; a new root N did not exist before the first admission. | A literal reading could treat a later top unit as an S-9 violation. | No. Say "the original root," or "a root exists from before the first admission, and a later root may be inserted above it without moving members." |
| **L-10** | **The Higher-body row cites D-115 (WD) for prevalence.** Higher-body row (1442): "prevails from its date (D-115)"; D-115 (890) is a working direction; Binding list (1370) says not binding; D-119 (904) is the binding "whatever their order" rule. | Fact. | A normative row rests partly on a WD; D-115 supplies the default authority, not the prevalence. | No. Cite D-119 for prevalence; keep D-115 only for the default authority. |
| **L-11** | **§49.6's later-position/`positionAt(τ)` wording is broader than A-8.** §49.6 (973): "one shared τ … mapped by Geography to `positionAt(τ)`"; (979): "Reading at a later position resolves B to A automatically"; A-8 (1423) pins allocation-scheme identity resolution to the confirmed position. | Fact/ambiguity. The §49.6 rows are about coverage/current reads; a reader could apply them to a member's association or an allocation scheme. | A later-position read could be mistaken for the allocation rule, undermining A-8. | No. Scope the rows to coverage, and point to A-8/§51 for allocation. The shared τ must also be within Geography's `closedThroughTime` (Geography 304, 310). |
| **L-12** | **Whether an allocation may use an organisation area is unstated.** A-1/A-7 (1420, 1422) use Geography canonical identity and containment; §51.2 (1391) says the recorded association is "the area the organisation records"; D-82 (baseline 166) says organisation areas exist and are never official references. | Fact + inference: if an allocation may use an organisation area, Geography's `resolve`/containment do not provide its hierarchy; if it may not, the design should say so. | An implementer could try to allocate by an organisation area and find no containment/identity mechanism. | Possibly, if the scope is a design choice; otherwise a clarifying sentence. The commission's Q3 is about Geography; the design should state the limit. |

### 3.3 Notes, not findings

- **§51.11 R-8 (1554)** still says "Geography is read at the operation's shared τ (`positionAt(τ)`, D-93 §6)," and **R-7 (1553)** says "as known at τ." These are history, not normative; A-8 supersedes them for allocation. They will read stale beside A-8 and should be marked superseded for readability. No verdict impact.
- **§50.9 admission-unit gap (1254–1257)** is labelled [TU, contract]; it is a known cross-context gap, not a new finding.
- **§51.15** is the author's own test. I re-ran it: 15/15 agree on their face. It does not exercise the Change/Remove rows, and it does not cover the reconciliation scenario 12, so its "No case gives different answers" is narrower than the lifecycle table.
- **D-106 §6** is supplied in file 04 (165–169), so it is not UNAVAILABLE. The accepted baseline's examples (re-parented district, UN regrouping) and its "never changes belonging by itself" are the premise A-8 correctly implements.
- **Geography's [OQ]** on defective approvals is open; A-8's dependency note is the right treatment for schemes; the ruling is for Geography/the Principal, not this review.
- **A-8's "effective from its date"** is not tested against §49.7's retroactivity rule (1003–1006) or D-117 (897). A confirmation back-dated before its decision could change past belonging; the design should say whether the §49.7/D-6 ratification route applies. This is a note, not a finding, because the ordinary case uses the act's date.

---

## 4. Verdict

**NOT VALIDATED.**

The design is substantively sound in the places the commission asks about:

- **A-8 is the right implementation of D-106 §6.** The confirmed position is a recorded fact known at τ and ≤ `positionAt(τ)`; it selects a map version inside the τ snapshot and is not a second knowledge time. The dependency on Geography's immutable published content is stated for schemes. Q3(b) is confirmed.
- **D-140 is consistently applied** in A-7, the Change, Remove and Higher-body rows, the closure row and scenario 31: overridden is not ended; it has no effect while the higher stands; it resumes with `PlacementReviewNeeded` when the higher ends; refused at adoption never applies.
- **B-2, B-7, S-9 and C-2 use C2 at τ**, under the single knowledge boundary; the admission-unit gap is known and labelled.
- **The 15 cases in §51.15 agree on their face**, and scenarios 13, 28–33 agree with the invariants and algorithm.
- **The Governance–Organisation separation holds**: Governance decides; Organisation records and derives; no organ is named; no rule makes Organisation decide substantive competence.

**What remains:**

1. **M-1 (medium).** Add D-119's written-rules exception to the **Change** and **Remove** rows (1439–1440) and **scenarios 25–27** (1480–1482), or record an intentional absolute refusal as a Principal decision departing from D-119. The Adopt and Higher-body rows already carry the exception; the lifecycle table currently gives two answers.
2. **M-2 (medium).** Align **scenario 12** (1467) with A-8 (1423) and scenario 32 (1487): a confirmation at a newer position can move members recorded against the non-allocated reference; state the effect and the `PlacementReviewNeeded` notice, or say precisely that "no effect" means no allocation conflict.
3. **Low findings.** The Principal should decide which to fix in the same pass. **L-2** (D-140 vs A-2), **L-4** (D-136 competence vs scheme-competent-authority designation), **L-5** (equal-highest concurrence status) and **L-8** (the confirmation notice) touch lifecycle/authority correctness and are worth closing with M-1/M-2. The others are precision/traceability.

**No new Principal decision is needed** for M-1 or M-2 if the fixes follow D-119 and A-8; a decision is needed only if the Principal intends a different rule (an absolute Change/Remove refusal, or a reconciliation confirmation that is a no-op for members recorded against the non-allocated reference). I decide nothing for the Principal and claim no acceptance.

**Independent validation remains outstanding.** This is a seventh self-review by the excluded model in the excluded harness. The commission's purpose — a first independent validation — is not met by this report, however the text findings are weighed. A human architect, or a GPT, Gemini or Mistral reviewer in its own interface, is still required before acceptance. If none is available, acceptance would rest on self-review only, and the Principal should record that.

---

## 5. Reviewer identity, prior involvement, integrity method, and the independence limit

**Who I am.** I am a **DeepSeek-family** model, designation **`deepseek-chat`**, running in the **Claude Code** command-line harness (an Anthropic product). I am **not** a Claude model. The commission (file 01, line 7) requires a reviewer **not Claude, not `deepseek-chat`, and not run inside Claude Code**; I am the excluded model in the excluded harness.

**Prior involvement.** I wrote **IR-OTD-01**, **IR-OTD-01-C**, **IR-OTD-01-C2**, **IR-OTD-01-C3**, **IR-OTD-02** and **IR-OTD-03** — every prior report on §49–§51. I also reviewed the Organisation people register (IR-OPR-01) earlier the same day; that is a different design and is not evidence here. This is the **seventh** review of §49–§51 by the same model.

**The independence limit, stated plainly.** The commission exists because six reviews by one model found real defects and none was independent. I am that model. The Principal instructed this review, and I have performed it as a senior DDD architect; but this report is a **self-review**, not the independent validation the commission requires. I mitigated the risk by reading D-114 … D-122, D-136 and D-140 verbatim in file 03 rather than in summaries; by re-deriving all 15 cases instead of trusting the author's table; by reading the Geography design's `Belief`, `positionAt`, containment slot and `resolve`; and by looking for defects in my own earlier corrections. That is how M-1 was found: R-1 fixed the Higher-body row but the same exception is missing in the adjacent Change/Remove rows and scenarios 25–27. The mitigation is evidence of a genuine re-read; it does not substitute for an independent reviewer.

**Integrity method (hashes computed).**

1. In `~/ir-otd-04-review-handover`, `sha256sum -c SHA256SUMS`: **11/11 OK** (`0-START-HERE.md`, `1-reviewer-prompt.md`, `2-opening-message.md`, `upload-files/01` … `07`, `upload-files/MANIFEST.sha256`).
2. In `upload-files`, `sha256sum -c MANIFEST.sha256`: **7/7 OK** (files 01–07).
3. The live subject hashes to `71e94de9e5dfb29b25da6f0647bd95ac35cd3f0eef45e0da1256fda146b7b379`; package file 02 hashes to the same value.
4. Computed file hashes: 01 `6dd2d3195da5904cec30760a506532dc4b76834829bfecb32945c87f54446605`; 03 `a7fcb16023ac26358d1c8d3d5b54b48baa9231dd8910b9c8d7bb18858ffa69f3`; 04 `22d763aa25638fe82b211a2a7fd211c8401f498aa23ba4b944fa158e8a3308bd`; 05 `8b7300d94616432b0efff4f3f11cfec14e68a2884da53bdd678c286d5dde6983`; 06 `6530eeefb8d5774c29eddcd15320603f1fa5b3ddffbe25625ab0842f5a83c1e0`; 07 `7274075658aae7c6c5ccc65815c8e34292052d6805644157d621a11e62e8e4ed`.
5. I modified nothing. Files 02 and 03 were treated as frozen for the duration; I did not edit the subject, the decision record, the package, or any supplied document. I ran no code from the bundle and read no organisation's written rules.

**Labels.** *Confirmed* — the required content is in the normative text at the line given. *Partial* — part present, part missing or contradicted. *Missing* — absent from the normative text; none in this review. *Contradictory* — a normative sentence, read on its own, gives an outcome another normative sentence or decision denies. *Not assessable* — the source needed is not in the package; the written rules are the only such source, and the competence exception is confirmed as a rule, not tested against a real text.

**UNAVAILABLE.** Any organisation's written rules (the D-119/D-136 competence exception cannot be tested against a real text); the Geography [OQ] ruling on defective approvals (open, not in the package); implementation, code, schemas and runtime behaviour (out of scope). Everything else relied on is in files 01–07.

**What this review does not establish.** No implementation correctness, no test execution, no reading of any organisation's written rules, no acceptance, no ratification, no authorisation, and no decision for the Principal. A review of the written design proves neither implementation correctness nor test execution. The author's §51.11–§51.15 and Update paragraphs are not treated as independent evidence.

**End of report.**
