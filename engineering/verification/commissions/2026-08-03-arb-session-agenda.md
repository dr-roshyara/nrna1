# ARB Session Agenda — 2026-08-03

**Chaired by:** ARB Chief / Principal DDD Architect.
**Constructed from:** the rulings register (`ADR-AIP-LOG-Platform-Rulings.md`, R-1…R-80), `.claude/CONTEXT.md` open items, and repository evidence — **not from the most recent conversation.**
**Status:** AGENDA. **This document issues no ruling.** Constructing an agenda is a planning act; it neither authorizes nor decides anything (R-80's vocabulary: permission · authorization · commissioning · execution).

---

## 0. Three findings from agenda construction

The submitted backlog was accurate but **not complete**. Building from the register surfaced three items it omitted, and one piece of evidence that bears on an item it did include.

| # | Finding | Consequence for the agenda |
|---|---|---|
| **F-1** | **R-52's classification is an explicitly open ARB question.** Its own annotation reads: *"this ruling also OPENED a work package and is also typed Delivery Governance · Approval. By the correction applied to R-60, opening is a PLANNING act. The issuing authority addressed R-60 only… whether to correct it is the ARB's."* | added as **B-11** |
| **F-2** | **R-52's work package may be complete but was never closed by a ruling.** R-52 opened WP-6 remediation to *"unblock the merge gate"*; R-71 (2026-08-02) records `composer merge-gate` **PASS (270 · 671)**. **Evidence insufficient to conclude the package is complete** — the gate passing is consistent with completion but does not establish it. | added as **B-12**, marked evidence-insufficient |
| **F-3** | **The register's silence on WP-3A/WP-4A authorizations is CONFIRMED, not merely alleged.** Register search returns `R-67 — WP-3A ACCEPTED` and `R-69 — WP-4A ACCEPTED`; **no authorization ruling exists for either.** | **B-7** is rulable now, on established evidence |

**F-4, bearing on B-4 (WP-3B).** The mint is still present at `CoordinatesAdjudication.php:66-67`, and its own comment states the removal precondition: *"Chain start (raise path not yet implemented)… When ChallengeRouted consumption lands, this becomes `EventProvenance::fromConsumed`."* **`ChallengeRouted` consumption landed and was accepted (R-69).** Whether that comment states *the governing* removal condition is the disputed point — **two accepted artifacts disagree, and this evidence does not resolve which governs.** Recorded for the Board's deliberation, **not** as a resolution.

---

## 1. Agenda

### Priority A — blocks current delivery

| # | Item | Type | Evidence | Rulable? |
|---|---|---|---|---|
| **A-1** | **Q1** — which crash models must WP-4B's redrive recover (A/C only, or A/B/C)? | Architecture Governance · Adoption | decision package §2 (three models traced to code) · §3 (EPIC-004K §12) | ✅ **yes** |
| **A-2** | **Q2** — does implementing §12's reconcile fall **inside WP-4B**, widening R-76's request-path-only scope, or a separately authorized slice? | Execution Governance · Scope | R-76 · §12 · the Contestation translator precedent | ✅ yes, **after A-1** |
| **A-3** | **Q3** — confirm `issuance_requested_at` means *"a request was made"*, or rule it must mean *confirmed* | Architecture Governance · Adoption | package §6: R-76 excluded PM-6's confirmation half, which **forces** the former | ✅ **yes — independent** |
| **A-4** | **Q4** — must `redriveIssuance()` isolate failures per process? | Execution Governance · Authorization **only** | package §5: one throw aborts the pass; `orderBy('concluded_at')` starves the newest | ✅ **yes — fully independent** |
| **A-5** | **Q5** — K2 amended to express its stated intent, or confirmed as written | Delivery Governance · Adjudication | package §4 (the two-test contradiction proof) | ✅ yes, **after A-1 + A-3** |
| **A-6** | **`ChallengeRaised` PROMOTION** — internal domain event → published integration event | Architecture Governance · **Permission** | R-75 adopted the *path*; the *promotion act* is unrecorded. Its internal status is stated in three accepted artifacts and enforced by a live test | ✅ yes |
| **A-7** | **`ChallengeRaised` ALLOCATION** — which work package owns the publication work | Execution Governance · **Allocation** | belongs to neither §WP-3 nor §WP-4B as recorded | ✅ yes, **after A-6** |

### Priority B — programme governance

| # | Item | Type | Evidence | Rulable? |
|---|---|---|---|---|
| **B-4** | **WP-3B** — the mint's removal from `CoordinatesAdjudication`; allocation unresolved | Execution Governance · Allocation + reconciliation | mint present (verified); precondition arguably met by R-69 (**F-4**); two accepted artifacts disagree on the condition | ⚠️ **reconciliation required first** — which artifact governs is itself the question |
| **B-5** | **PM-6's confirmation half** — allocation | Execution Governance · Allocation | unallocated; **couples to A-3** — a *confirmed* marker requires PM-6 | ✅ yes, **after A-3** |
| **B-6** | **R-77** — the readiness-review methodology question | Governance · Methodology | **new implementation evidence: the same failure mode now observed TWICE** — ADR-T14's tick (half a document checked) and §12 (a citation treated as coverage) | ✅ **yes — see §5** |
| **B-7** | **Register silence** on WP-3A's and WP-4A's authorizations (R-62 class) | Delivery Governance · Recording correction | **confirmed (F-3):** acceptances exist, authorizations do not | ✅ **yes — independent** |
| **B-8** | **Contract R-2 has no ADR home** | Architecture Governance · Adoption | Deptrac **enforces** it; no ADR states it. An enforced rule with no adopted decision | ✅ **yes — independent** |
| **B-9** | **ENG-012** — risky notices 101 → 105, cause unestablished | Operational observation | new supporting observation from WP-4B (the house `removed error handlers` pattern, 23+ pre-existing tests), **cause still not established** | ⛔ **Evidence insufficient for determination** |
| **B-10** | **The conversation boundary question** — due before WP-8 | Architecture Governance | `2026-08-02-conversation-question-observability.md` | ✅ deferrable safely (WP-8 deferred by R-79) |
| **B-11** | **R-52's classification** (F-1) — *opening* typed as Approval where R-60's correction makes it Planning | Delivery Governance · Recording correction | the register's own annotation names this as the ARB's | ✅ **yes — independent** |
| **B-12** | **R-52's work package** — complete but never closed? (F-2) | Delivery Governance · Acceptance | gate PASS at R-71 is *consistent with* completion | ⛔ **Evidence insufficient** — requires a completion-evidence report first |

---

## 2. Dependency ordering

```
A-4  ─────────────────────────────────────────►  (independent; blocks nothing, blocked by nothing)
A-3  ──┬────────────────────────►  B-5
       └──┐
A-1  ──┬──┴──►  A-5
       └──►  A-2
A-6  ─────►  A-7  ─────►  (production producer path)
B-7  ─────────────────────────────────────────►  (independent)
B-8  ─────────────────────────────────────────►  (independent)
B-11 ─────────────────────────────────────────►  (independent)
B-4  ──► requires reconciliation of two accepted artifacts before allocation
B-12 ──► requires completion evidence before an acceptance ruling
B-9, B-10 ──► carried
```

## 3. Classification — **rulable ≠ recommended**

These are four independent properties and collapsing any two of them mis-plans the session. *Rulable* is a statement about **evidence**; *recommended* is a statement about **this session's time**.

| Item | Rulable (evidence sufficient) | Recommended **this session** | Blocking (prevents engineering) | Safely deferred |
|---|---|---|---|---|
| **A-1** Q1 models | ✅ | ✅ | ✅ | ⛔ |
| **A-2** Q2 allocation | ✅ after A-1 | ✅ | ✅ *if* B in scope | ⛔ |
| **A-3** Q3 marker | ✅ | ✅ | ⛔ | ⛔ |
| **A-4** Q4 isolation | ✅ | ✅ **first** | ✅ | ⛔ |
| **A-5** Q5 K2 | ✅ after A-1+A-3 | ✅ | ✅ | ⛔ |
| **A-6** promotion | ✅ | ⚠️ **not required this session** | ⛔ | ✅ |
| **A-7** allocation | ✅ after A-6 | ⚠️ not required this session | ⛔ | ✅ |
| **B-4** WP-3B | ⛔ reconciliation first | ⛔ | ⛔ | ✅ |
| **B-5** PM-6 | ✅ after A-3 | ⛔ | ⛔ | ✅ |
| **B-6** R-77 | ✅ | ⛔ | ⛔ | ✅ |
| **B-7** register silence | ✅ | ⛔ | ⛔ | ✅ |
| **B-8** contract R-2 | ✅ | ⛔ | ⛔ | ✅ |
| **B-9** ENG-012 | ⛔ evidence insufficient | ⛔ | ⛔ | ✅ |
| **B-10** conversation question | ✅ | ⛔ | ⛔ | ✅ (R-79) |
| **B-11** R-52 classification | ✅ | ⛔ | ⛔ | ✅ |
| **B-12** R-52 closure | ⛔ evidence insufficient | ⛔ | ⛔ | ✅ |

**The distinction earns its place immediately: B-7, B-8 and B-11 are all rulable on established evidence and none is recommended for this session.** Spending Board time on recording hygiene before Q1 would delay the only rulings that restart delivery. **Rulable means "the Board *can*", not "the Board *should now*".**

**A-4 remains the highest-value ruling and should be taken first.** The starvation defect holds under crash models A, B and C alike, so authorizing its repair **cannot** pre-empt A-1. It converts a frozen engineering track into one executable slice at zero governance cost.

## 3a. Horizons

| Horizon | Items | What it buys |
|---|---|---|
| **Immediate — restarts delivery** | **A-4 · A-3 · A-1 · A-2 · A-5** | batches 7–9 become executable |
| **Current release — closes §WP-4** | **A-6 · A-7 · B-4 · B-5** + batches 7–11 + acceptance | §WP-4 closes *by acceptance*, then WP-8 is reachable (R-79) |
| **Governance maintenance** | **B-6 · B-7 · B-8 · B-9 · B-10 · B-11 · B-12** | debt retired; no delivery consequence either way |

**One deviation from the suggested grouping, with its reason.** The proposed horizons placed **promotion and allocation (A-6/A-7) in *Immediate*.** This agenda places them in **Current release**:

> **Under the adopted governing model (R-76: the request path only) and the delivery analysis recorded in this agenda, the absent producer does not prevent execution of Batch 7, but remains on the critical path to completing §WP-4.**

**Stated that way deliberately, and the reason is this claim's own history.** An earlier phrasing read *"…without making Batch 7 unbuildable"* — **withdrawn as stronger than the evidence.** Engineering's position on this question moved twice before settling: first that `ContestedOutcomeRef`'s absence blocked construction of the command; then a **withdrawn sequencing inference**; then the production-path-versus-request-seam reading. **A conclusion twice revised is an architectural conclusion drawn under an adopted model — not a verified fact — and must carry the model it depends on.** If R-76's scope changes, this grouping changes with it.

**Recorded as a deviation rather than applied silently.** Overruling it is the Chief's call.

## 3b. Held: the artifact split (not performed)

The recommendation to separate **ARB Session Agenda · Programme Governance Backlog · Decision Dependency Map** is accepted **and deliberately not performed now** — the recommendation itself scheduled it for after the current governance cycle closes.

**A second reason to hold it:** splitting one artifact into three *while items are mid-deliberation* is precisely when an item falls between the new documents. The correct moment is when the Immediate horizon is empty. **Held, with its trigger named — not deferred indefinitely.**

## 4. Blocking analysis

| Blocks **engineering** | Blocks **later governance** | Safely deferred |
|---|---|---|
| **A-1** (batches 7–9) · **A-5** (K2's disposition) · **A-2** *if* B is in scope · **A-4** (the isolation repair — frozen only because Batch 7 is) | **A-1 → A-2 → A-5 → WP-4B acceptance → §WP-4 closure → WP-8** (R-79) · **A-6 → A-7** for the production producer path · **A-3 → B-5** | **B-9** (non-blocking by R-71) · **B-10** (WP-8 deferred) · **B-12** (pending evidence) · **B-4** *if* the Board prefers reconciliation as its own act |

**Not blocking, contrary to a natural assumption:** **A-6/A-7 do not block Batch 7.** R-76 scoped WP-4B to the request path, and the seam *reads* `contestedOutcome` from the record. The absent producer makes the **production path** incomplete; it does not make the seam unbuildable.

## 5. B-6 (R-77) — the one item whose evidence status changed

**ES-006.1 forbids promoting methodology from a single occurrence.** R-77 was left open in exactly that posture. **The same failure mode has now been observed twice:**

1. **ADR-T14's readiness tick** — the ADR was certified ✅ having checked only its *"never writes"* half.
2. **EPIC-004K §12** — cited in `CoordinatorIssuanceRequest`'s traceability for the half that justified the adapter adding nothing, while the obligation it places on the requester went unread.

**Both are: a governing document referenced for the portion that supported the design, with the remainder unexamined.** Whether two occurrences meet the promotion threshold is the ARB's determination — **the threshold rule is ES-006.1's, and this agenda does not apply it.** Recorded because the evidence base moved, which is the only thing that reopens a frozen methodology question (`.claude/CLAUDE.md`: *"unless PublicDigit implementation exposes a genuine deficiency"*).

## 5a. Session 1 scope — approved for deliberation

**Session 1 rules the Immediate horizon and then stops: A-4 → A-3 → A-1 → A-2 → A-5.**

**Governance maintenance (B-6…B-12) is NOT in Session 1's scope** — it is entered only if time remains *and* the Board explicitly decides to. This is the `rulable ≠ recommended` rule applied to the session itself: those items are rulable, and ruling them ahead of A-1 would delay the only decisions that release engineering.

**Later sessions**, as advised: **S2** architecture (A-1 · A-2) · **S3** consequences (A-5 · A-6 · A-7) · **S4** programme governance (B-4 · B-5 · B-6 and the remaining recording matters). *(S1 opens with A-4/A-3 because both are independent and A-3 is already constrained by R-76; where the Board prefers to seat A-1 is its own sequencing call.)*

**No omnibus motion.** Each item is a distinct governance act with its own evidence dependency — architecture adoption · execution authorization · delivery adjudication · recording correction · allocation. **Ruling them in one motion would merge act types the programme has spent the whole cycle separating**, and would leave no record of which evidence supported which decision.

## 6. Recommended ruling sequence

| Step | Items | Rationale |
|---|---|---|
| **1** | **A-4** | independent, unblocks engineering immediately, prejudges nothing |
| **2** | **A-3** | independent; R-76 already forces the answer, so it is cheap and it gates B-5 |
| **3** | **A-1** | the substantive architecture ruling; everything in Priority A converges here |
| **4** | **A-2** | moot if A-1 puts Model B out of scope |
| **5** | **A-5** | needs A-1 + A-3; touches only a test |
| **6** | **B-7 · B-8 · B-11** | recording and adoption hygiene; independent, and these rot if deferred |
| **7** | **A-6 → A-7** | permission, then allocation — never merged |
| **8** | **B-5** | after A-3 |
| **9** | **B-4** | reconcile the two accepted artifacts, then allocate |
| **carried** | **B-9 · B-10 · B-12** | evidence insufficient or lawfully deferred |

## 7. Engineering release conditions

| After | Engineering may |
|---|---|
| **A-4** | repair `redriveIssuance()`'s per-process isolation. **Mechanism is engineering's level** — the ARB authorizes the slice, it does not choose the construct |
| **A-1** | resume batches 7–9 within the model adopted |
| **A-1 + A-2** | implement §12's reconcile **only if A-2 places it inside WP-4B** |
| **A-5** | amend or confirm K2 — **test only, no production code** |
| **batches 7–9 + acceptance evidence** | present WP-4B for acceptance. **§WP-4 closes by acceptance, never by authorization** |
| **A-6 + A-7** | build the production producer path in the allocated package |

**Unchanged by every item above:** Batch 6's production code stands. A-2 and A-4 would **add** to it; A-5 touches a test.

## 8. Outside ARB authority

- **Q-2's MAD duration** and every temporal business policy — business, not architecture.
- **Implementation mechanism** (level 3) — engineering's, per the F-B finding of the 2026-08-01 decision pack: *"MECHANISM IS ENGINEERING'S LEVEL."* The ARB authorizes slices and adopts models; it does not select constructs.
- **PKS / KnowledgeOS items** — `CBC-3`'s re-disposition, the MCA/CDR sequence, Ledger B: **PA/Authority**, a different authority. ARB-bucket OQs 2/3/4/7/9/10/11 remain ARB's but are not on this agenda.
- **EP-01 plan approval** where a plan lacks it — the Delegated Authority's act, separate from ARB execution authorization (F-A, 2026-08-01 pack).
- **Pushing the 52 local commits** — operational, and the largest reversible risk currently open.

## 9. Traceability

Register R-1…R-80 (esp. **R-52** · **R-60** · **R-62** · R-67 · R-69 · R-71 · R-72 · R-73 · R-74 · R-75 · **R-76** · **R-77** · R-78 · **R-79** · R-80) · EPIC-004K §9 · §11 · §12 · ES-006.1 · ADR-T1 · ADR-T14 · ADR-T16 · INV-B1 · AP-1 · AP-2 · decision package `2026-08-03-crash-window-semantics-decision-package.md` (🔒 READY FOR ARB) · `.claude/CONTEXT.md` open items · commit `f2ac054c8`.
