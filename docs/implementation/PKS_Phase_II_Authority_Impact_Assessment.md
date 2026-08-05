# PKS Phase II — Authority Impact Assessment (AIA-1)

| | |
|---|---|
| **Kind** | **Governance transition assessment** — determines what becomes authorized, what remains prohibited, and how far the pending AFV-F4 disposition propagates. **Evaluates consequences; makes no Authority decision; performs no editorial change; introduces no architectural knowledge.** |
| **Authority** | Generated — never authoritative without human review. **The disposition itself is not taken here.** §11 is advisory only. |
| **Status — DISCHARGED 2026-07-30** | ⚠️ *Per change-control item AIA-R1: the three assertions below (**AFV-F4 not disposed · no artifact edited · no correction applied**) were **true when written** and are **all now discharged** — AFV-F4 was **DISPOSED ACCEPT** on 2026-07-30, AD-1 and C4-1 were amended, and every correction was applied. **§8's two *NOT YET AUTHORIZED* items became CCP-1's C-01 and C-14 and executed.** The row is **date-marked, not rewritten**. **All four findings below were honoured in that execution.*** · **As issued:** **EXECUTED. Findings: 1 Major · 2 Moderate · 1 Minor. The Major finding must be addressed BEFORE the disposition, not merely before editorial work. STOP.** AFV-F4 not disposed · no artifact edited · no correction applied · nothing promoted · methodology untouched. |
| **Commission** | Authority Impact Assessment Commission AIA-1 (PA, 2026-07-28), issued to prevent **governance contamination** — editors unintentionally resolving an Authority-owned decision. |
| **Inputs (only these)** | **AFV-1 · AD-1 · Strategic Model (M0–M8) · DAR-1.** |
| **Deliberately NOT used** | C4 corrections · the editorial-corrections list as an instrument · promotion artifacts — all of which occur *after* disposition. *(C4-1 is examined only as an **object of impact**, never as a source of authorization — see §6.)* |
| **Placement** | `docs/implementation/`, ahead of the AFV-F4 disposition. |
| **Disposition History** | **2026-07-30: AIA-R1 disposed ACCEPT and applied** (authority-impact-integrity review) — the Status row **date-marked** to record that its three assertions are discharged; **content-neutral, nothing rewritten, no finding or impact conclusion touched.** · **2026-07-30: AIA-R2 RECLASSIFIED to an OBSERVATION (OBS-AIA-1) and CARRIED WITHOUT REMEDIATION** — the Status field's *"must"* against §11's *"should"* is a **modal** inconsistency whose **operational semantics remain advisory throughout**, so it carries no governance impact. *Per the Observations-vs-Findings rule: observations are carried, not remediated — so the modal is deliberately left as written.* · **2026-07-30: retrospective validation recorded — all four AIA findings (F1 asymmetry · F2 → C-14 · F3 → C-05's sequence binding · F4's bound) were honoured in the execution that followed the disposition.** |

**Governing separation maintained throughout:** *Authority changes **governance** state · editorial work changes **representation** state · never both in one commission.* **Authority resolves uncertainty; editors implement resolved decisions.**

---

## 1. Executive Summary

**The pending disposition's most important property is counter-intuitive: ACCEPT is the smaller governance act and REJECT is the larger one.**

**Derived — AIA-F1 (Major), the finding that must be settled before the disposition is taken:** rejecting AFV-F4 leaves AD-1's unqualified placement of AR-1 standing, which is **architecturally equivalent to adopting OQ-PKS-7's one-corpus reading.** REJECT is therefore not a "no change" outcome — it is a **substantive resolution of an ARB-owned open question**, requiring its own record and impact statement. **ACCEPT, by contrast, decides nothing about OQ-PKS-7: it restores the question to open by qualifying a placement that currently presupposes an answer.** If the Authority treats REJECT as the conservative default, it will resolve an open question by declining to act.

| Question | Answer |
|---|---|
| **What does ACCEPT decide?** | Only that AD-1's placement must carry the contingency the strategic model already records. **It does not decide OQ-PKS-7** |
| **What does REJECT decide?** | That the unqualified placement stands — **which adopts the one-corpus reading architecturally** (AIA-F1) |
| **What does DEFER decide?** | Nothing — and it leaves AD-1 at *Governance Review Required*, blocking baseline promotion indefinitely |
| **Does the decision propagate beyond AD-1?** | **Yes — to C4-1**, which will require a fifth correction not among its current four (AIA-F2) |
| **Does it change certification state?** | **No.** Neither certification dimension moves; a fidelity defect in an architecture artifact is not a methodology defect |
| **Is the strategic layer implicated?** | **No** — and this bounds the impact radius. M8 carries OQ-PKS-7 with its impact statement intact; **the presupposition entered at the architecture layer, not before it** (AIA-F4) |

**Derived — the editorial authorization answer, which is the commission's operational purpose:** of the eleven open corrections, **three are fully unaffected and may proceed immediately** (M7's DAR-1 folds), **seven are authorized with low contamination risk**, and **one must be sequenced into the post-disposition act** because it edits the very element under Authority review (AFV-F5, AR-1's label). **No correction is permanently blocked.**

---

## 2. Commission

**Authorized:** determine the decision's scope · map the impact radius · verify boundary preservation · produce an editorial authorization matrix · assess promotion impact · record findings · offer an advisory statement.

**Not authorized, and not done:** **AFV-F4 not disposed** · AD-1 not edited · C4-1 not edited · no correction applied · nothing promoted · methodology untouched.

## 3. Scope

**In scope:** the consequences of the pending AFV-F4 disposition across the governed baseline, under each of its three possible outcomes.

**Out of scope, stated so the boundary is unmistakable:** **OQ-PKS-7 itself** — whether the corpus is one domain or three is ARB-owned and is not touched here, in any direction · CBC-3's disposition (frozen) · the seam's promotion (MCR-3 territory) · the other four AFV findings' merits (settled at AFV-1) · the content of any correction.

## 4. The Pending Authority Decision

**AFV-F4, as recorded (AFV-1 §5.1, §11):** AD-1 renders AR-1 **wholly inside the PKS architecture boundary with no qualification.** The strategic model records OQ-PKS-7 as **surfaced at M6 and unresolved**, with the impact statement that under the three-corpora reading *"part of the demoted seam's content is outside PKS."* OQ-PKS-7 appears nowhere in AD-1's inputs, principles, or six open questions.

**Recommended remedy on record:** qualify AR-1's placement with the contingency wording the model already supplies (M6 §9). **Classification: Governance** — the wording requires no new knowledge, but adopting it concerns an unresolved Surfacing Register item, so it is the Authority's act and not an editorial one.

---

## 5. Decision Scope

### 5.1 What AFV-F4's disposition decides

| Under ACCEPT | Under REJECT | Under DEFER |
|---|---|---|
| That AD-1's placement must carry the model's recorded contingency | That the unqualified placement stands as the governed architecture | Nothing; the question of AD-1's placement stays open |
| That one editorial correction (the qualification) becomes authorized, with its wording constrained to M6 §9's | **That the one-corpus reading is adopted architecturally** — a resolution of OQ-PKS-7 in effect (AIA-F1) | That AD-1 remains *Governance Review Required* |

### 5.2 What the disposition does NOT decide — under any outcome

- **OQ-PKS-7 itself is not decided by ACCEPT.** Qualifying a placement *restores* the question's openness; it does not answer it. **This is the single most important scope boundary in this assessment.**
- **Where AR-1's content actually belongs** — untouched.
- **CBC-3's status** — the candidate-seam disposition is frozen and unaffected.
- **The seam's promotion or the preserved partition's re-entry** — MCR-3 territory, untouched.
- **AD-1's four other findings** — their merits were settled at AFV-1; only their *sequencing* is affected (§8).
- **The certification state** — neither dimension is in scope (§9).
- **Any strategic disposition, UL term, or relationship classification** — all untouched.

**Derived:** ACCEPT is a *narrow* act with a *precisely bounded* consequence. That narrowness is what makes it distinguishable from deciding OQ-PKS-7, and the distinction should be explicit in whatever record the Authority issues.

---

## 6. Impact Radius

| Artifact | Impact | Basis |
|---|---|---|
| **AD-1** | **DIRECT** | The placement appears in §6's diagram, §6.2's rationale, and §11's traceability row for AR-1 |
| **C4-1** | **INDIRECT — and the propagation is real (AIA-F2)** | Its Level 2 renders AR-1 inside the system boundary. If the placement is qualified, the rendering must carry the qualification as a marked contingency — **a fifth correction that is not among C4-1's current four** |
| **KBI-1** | **INDIRECT** | Its promotion-readiness classification of AD-1 (*Editorial Restoration Required*) is superseded by AFV-1's reclassification. **KBI-1's caution was correct**: it recorded the Model→Architecture link as *"consistent, not independently audited"* and raised KBI-F1 precisely to close it |
| **C4-2** | **NONE** | Its PASS verdict stands. **C4-1 faithfully rendered AD-1 as written**, so the defect entered C4-1 by **inheritance**, not by representation error — C4-2 verified the right thing and reached the right verdict |
| **M8 · M0–M7 · DAR-1** | **NONE (AIA-F4)** | M8 §8 carries OQ-PKS-7 with its impact statement intact; the strategic layer contains no architectural placement at all. **The presupposition originated in the transformation, not in the source** |
| **MCA · CDR · Checkpoint · RET-1** | **NONE** | No certification, methodology, or retrospective statement depends on AR-1's placement |
| **PMR register** | **NONE** | Contents are candidates by design |
| **Certification state (both dimensions)** | **NONE** | §9 |

**Derived:** the radius is **two artifacts direct-or-indirect, one classification superseded, and nothing upstream.** AIA-F4 is what bounds it: because the strategic layer never contained the presupposition, no re-verification of M0–M8 is implied by any outcome.

---

## 7. Boundary Preservation

| Check | Result under ACCEPT |
|---|---|
| Preserves strategic boundaries | ✅ **It restores one.** The qualification reinstates a contingency the model already records; it shifts no boundary |
| Introduces no Tactical DDD | ✅ The qualification is a placement caveat; no aggregate, entity, repository, API, deployment, or technology is implied |
| Does not reopen Strategic Discovery | ✅ **Verified specifically.** Adding a caveat drawn verbatim-in-substance from M6 §9 is not rediscovery — no evidence is collected, no candidate re-examined, no probe re-run |
| Does not disturb CBC-3's disposition | ✅ The seam remains a candidate seam; the qualification concerns *where its content may lie*, not *what it is* |
| Does not resolve OQ-PKS-7 | ✅ Under ACCEPT. ⚠️ **Not true under REJECT** — see AIA-F1 |

---

## 8. Editorial Authorization Matrix

*Of the eleven corrections currently open across the baseline. **Authorization here is conditional on the disposition being taken; nothing is authorized to proceed by this assessment alone.***

| Correction | Target | Classification | Rationale |
|---|---|---|---|
| **DAR-1 fold 1** (R-1's pattern name) | M7 | **UNAFFECTED — may proceed independently** | Concerns a withdrawn pattern name; no contact with AR-1, the PKS boundary, or OQ-PKS-7 |
| **DAR-1 fold 2** (R-3b's constraint restatement) | M7 | **UNAFFECTED** | As above |
| **DAR-1 fold 3** (R-4's pattern name) | M7 | **UNAFFECTED** | As above |
| **AFV-F1** (issuance-placement restatement) | AD-1 | **AUTHORIZED — low contamination risk** | Concerns the issuing act's placement relative to **SB-1**, not the PKS boundary. Independent of OQ-PKS-7 |
| **AFV-F2** (DR-1's scope) | AD-1 | **AUTHORIZED** | Dependency-rule scope; no boundary contact |
| **AFV-F3** (AP-3's qualification) | AD-1 | **AUTHORIZED** | Principle status; no boundary contact |
| **KBI-F3** (RET-1 input reconciliation) | AD-1 | **AUTHORIZED** | Input-list hygiene |
| **KBI-F2** (namespace declaration) | AD-1 | **AUTHORIZED with one constraint** | It declares the AR- namespace. **The declaration must not characterize AR-1's placement**, only its identifier space |
| **VR-1 · VR-2 · VR-3 · VR-4** | C4-1 | **AUTHORIZED — low contamination risk** | Labels, a governed-term restoration, a provenance row, an omission row. None touches AR-1's placement. **Efficiency note (not a governance constraint): C4-1 will need a fifth correction post-disposition (AIA-F2), so batching all five avoids editing C4-1 twice** |
| **AFV-F5** (AR-1's label) | AD-1 | **SEQUENCE-DEFERRED (AIA-F3)** | It edits **the very element under Authority review.** Renaming the label does not itself decide placement, but an editor working inside AR-1's entry while its placement is contested is the contamination scenario this commission exists to prevent. **Defer into the post-disposition act so AR-1 is edited exactly once** |
| *(created by the disposition)* **AR-1 placement qualification** | AD-1 | **NOT YET AUTHORIZED** | Comes into existence only if ACCEPT is issued; wording constrained to M6 §9's contingency |
| *(created by the disposition)* **AR-1 contingency annotation** | C4-1 | **NOT YET AUTHORIZED** | Consequent of the above (AIA-F2) |

**Derived:** **nothing is permanently blocked.** Three corrections are fully independent of the disposition, seven are authorized with the single KBI-F2 constraint, one is sequence-deferred, and two do not yet exist. **The contamination surface is one correction wide** — which is a good position to be in, and it is only knowable by mapping the corrections against the contested element rather than against their severity.

---

## 9. Promotion Impact

| Dimension | Impact |
|---|---|
| **AD-1's promotion readiness** | Resolves **only** on disposition. Under ACCEPT: *Editorial Restoration Required*, then ready once the batch is applied. Under REJECT: ready **only if** the OQ-PKS-7 adoption is separately recorded (AIA-F1). Under DEFER: remains *Governance Review Required* — **not promotable** |
| **C4-1's promotion readiness** | *Editorial Restoration Required* under every outcome — four corrections now, five under ACCEPT |
| **M7's promotion readiness** | Unaffected; its three folds may proceed independently |
| **Baseline-level readiness** | **Blocked until the disposition is taken**, because AD-1 is a load-bearing member and the baseline is promoted as a body |
| **Certification state** | **UNCHANGED** on both dimensions. **Derived:** AFV-F4 is a defect in an artifact produced *under* the frozen method, not a defect *in* the method; Method Design and Operational Evidence both stay exactly as the Checkpoint recorded them |
| **Governance classification** | AD-1's *Governance Review Required* status exists **because of** this pending decision and resolves with it |

**One observation recorded without routing** (this commission records impacts only, and consistent with AFV-1's tightening it neither proposes nor routes methodology work): the SDM contains no rule requiring an architectural derivation to carry unresolved open-question contingencies forward. Whether that has methodology significance is for a body with that authority to consider, unprompted by this record.

---

## 10. Findings

### AIA-F1 — **Major** *(must be addressed before the disposition, not merely before editorial work)*

- **Evidence:** AFV-F4's remedy is a qualification. Declining it leaves AD-1's unqualified placement of AR-1 inside the PKS boundary as the governed architecture — and that placement is what AFV-1 identified as presupposing OQ-PKS-7's one-corpus reading.
- **Rationale:** a rejection that leaves the presupposition in force **adopts** it. The program's constitutional constraint forbids resolving an open question implicitly; a REJECT recorded as "no change required" would do exactly that, by inaction.
- **Impact:** **the null-looking option has the larger governance footprint.** REJECT would require its own record: an explicit statement that the one-corpus reading is adopted architecturally, with an impact statement, and notice to the ARB that OQ-PKS-7 has been answered in this respect.
- **Recommended disposition of the finding:** the Authority should be presented with the three outcomes **and their asymmetry** before deciding — specifically, that ACCEPT decides nothing about OQ-PKS-7 while REJECT decides something about it.

### AIA-F2 — **Moderate**

- **Evidence:** C4-1's Level 2 renders AR-1 inside the system boundary, faithfully reproducing AD-1 as written. C4-1's open corrections are VR-1..VR-4; none concerns AR-1's placement.
- **Rationale:** if the placement is qualified, the rendering must carry the qualification, or the views will assert what AD-1 no longer does. **C4-2's PASS verdict remains correct** — the defect reached C4-1 by inheritance, not by representation error, and C4-2 was commissioned to verify C4-1 against AD-1 as it stood.
- **Impact:** **the disposition propagates beyond AD-1.** C4-1 acquires a fifth correction that does not exist yet.
- **Recommended disposition:** record the anticipated C4-1 annotation as a consequent correction at the moment ACCEPT is issued, so it is not discovered later as a fresh finding.

### AIA-F3 — **Minor (procedural)**

- **Evidence:** AFV-F5 edits AR-1's label; AFV-F4 concerns AR-1's placement.
- **Rationale:** the correction is harmless in content but co-located with the contested element.
- **Impact:** low, and fully avoidable by ordering.
- **Recommended disposition:** sequence AFV-F5 into the post-disposition act so AR-1 is edited exactly once.

### AIA-F4 — **Minor (bounding, and positive)**

- **Evidence:** M8 §8 carries OQ-PKS-7 with its impact statement intact; no strategic artifact contains an architectural placement of the seam's content.
- **Rationale:** the presupposition entered at the transformation, exactly as AFV-1 concluded.
- **Impact:** **bounds the radius** — no outcome implies re-verification of M0–M8, and no strategic disposition is at risk under any of the three options.

---

## 11. Authority Advisory Statement

**Advisory only. The disposition is the Authority's and is not taken here.**

1. **The three outcomes are not symmetric, and the asymmetry runs opposite to intuition.** **ACCEPT** is the narrow act: it qualifies a placement using wording the model already supplies, restores OQ-PKS-7 to open in the architecture, and **decides nothing about the open question itself.** **REJECT** is the broad act: it adopts the one-corpus reading architecturally and needs its own governance record (AIA-F1). **DEFER** decides nothing and blocks baseline promotion indefinitely.
2. **On the merits, this assessment's advisory is ACCEPT** — it removes an undisclosed presupposition, requires no new knowledge, preserves every strategic boundary, disturbs no frozen disposition, and has an impact radius of two artifacts. *(Advisory, per the commission; the Authority alone disposes.)*
3. **If REJECT is preferred, the record should state explicitly** that the one-corpus reading is adopted architecturally, carry an impact statement, and notify the ARB — so that the resolution of an ARB-owned question is a visible act rather than a consequence of declining one.
4. **Whatever the outcome, the disposition should state what it does *not* decide** — OQ-PKS-7 itself, CBC-3's status, the seam's promotion, and the preserved partition's re-entry — because AFV-F4's proximity to OQ-PKS-7 makes over-reading the disposition the likeliest downstream error.
5. **Sequence recommended after the disposition:** apply the editorial batch in one act (with AFV-F5 and any consequent C4-1 annotation included), then verify promotion readiness, then promote. **M7's three DAR-1 folds may proceed at any time**, being wholly independent.

---

*Traceability: executes the Authority Impact Assessment Commission AIA-1 (PA, 2026-07-28) ahead of the AFV-F4 disposition, to prevent governance contamination · inputs limited to AFV-1 · AD-1 · M0–M8 · DAR-1; C4 corrections, the editorial list as an instrument, and promotion artifacts excluded (C4-1 examined only as an object of impact) · decision scope stated under all three outcomes with an explicit not-decided list · impact radius mapped Direct/Indirect/None per artifact · editorial authorization matrix produced for all eleven open corrections · findings AIA-F1 (**Major**) · AIA-F2/F3 (Moderate/Minor) · AIA-F4 (Minor, bounding) · advisory statement recorded · **AFV-F4 not disposed, no artifact edited, no correction applied, nothing promoted, methodology untouched, no methodology work proposed or routed** · same-lineage act, T-2 carried. **STOP.***

> **Authority Impact Assessment complete. ACCEPT is the narrow act and decides nothing about OQ-PKS-7; REJECT is the broad act and would adopt the one-corpus reading architecturally, requiring its own governance record. The disposition propagates to C4-1, which acquires a fifth correction. Of eleven open corrections, three are fully independent, seven are authorized with one constraint, and one is sequence-deferred because it edits the contested element. Certification state is unaffected and no strategic artifact is implicated. The next act is the AFV-F4 disposition, separately commissioned.**
