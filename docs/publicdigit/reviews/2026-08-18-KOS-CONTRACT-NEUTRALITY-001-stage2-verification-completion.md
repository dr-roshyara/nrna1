# KOS-CONTRACT-NEUTRALITY-001 Stage 2 — **verification completion and reproduction**

**Date:** 2026-08-18 · **Assignment:** `S1-verification-python-stage2` (`ACTIVE`, mutation owner) · **Grant:** `G-KOS-CONTRACT-STAGE2-VERIFY`

> ## ⚠️ THIS IS NOT A SECOND INDEPENDENT VERIFICATION. It cannot be.
> **The Stage-2 independent verification was already delivered** — `2026-08-16-KOS-CONTRACT-NEUTRALITY-001-stage2-independent-verification.md`, committed `4d4738db`, verdict **FAIL — implementation defect**. This process **read that report before doing any work**, so it is permanently disqualified from independently re-verifying its conclusions.
>
> **What this document is:** the completion of **two commissioned grant items the delivered report does not cover** — **(b)** and **(f)** — plus an **independent reproduction** of the report's load-bearing measurement, which no process had attempted. **The existing verdict is not re-decided, softened, or overturned here.**

---

## 0 · Independence, stated first (`INV-ATTR-2`, self-declared)

| Bar | This process |
|---|---|
| Not `claude-code-session:fbc084f0` — the barred process, *"disqualified twice over"* | **Declared.** This session's id begins `b260fb38`; it drafted no part of the contract and wrote neither collector. **`Observed` for authorship; `Declared` for the id — the record cannot attest it.** |
| Not the Stage-2 implementer | **Declared** — no Python or PHP collector code was authored here. |
| **Independent of the delivered Stage-2 verification** | 🔴 **NO. Disqualified — this process read the report first.** Every conclusion below is either a fresh measurement or an assessment of an uncovered item, never an endorsement of the report's reasoning. |
| Prior estate exposure, disclosed | This session authored the **BC-7 Domain Model Refinement** (`KOS-ARCH-BASELINE-003`, seq 12) and declined Verification #3 there as the author under repair. **Different work item, different subject; no contact with LCOM4, the contract, or either collector before this assignment.** |

**Mutation:** none. All probes ran from a scratchpad directory. **No repository file was created, edited, or deleted** — no contract, fixture, `expected.json`, PHP reference, Python collector, test, or workflow record.

---

## 1 · Grant coverage audit — why this document exists

`G-KOS-CONTRACT-STAGE2-VERIFY` commissions six checks. Mapping the delivered report against them:

| Item | Commissioned check | Delivered report | Status |
|---|---|---|---|
| **(a)** | Python implemented from the contract, not ported | §3, structural evidence | ✅ covered |
| **(b)** | **PHP == Python == expected on all ten fixtures** | — | 🔴 **NOT COVERED** |
| **(c)** | The two exclusions remain distinguishable in Python | §4 | ✅ covered |
| **(d)** | Construct new divergence cases | §5, four-class probe | ✅ covered |
| **(e)** | Is the anonymous-class gap the only one? | §6 | ✅ covered |
| **(f)** | **Is the implementer's declared authorship weakness adequately reflected in claim strength?** | — | 🔴 **NOT COVERED** |

**(b) is the experiment's headline claim** — the grant's own *"REQUIRED RESULT SHAPE: PHP result == Python result == expected"* — and until now it rested entirely on the implementer's self-report. **(f) is the item that governs how much any of the evidence is worth.** Both are completed below.

---

## 2 · (b) The ten-fixture comparison — **re-derived independently: 10/10**

**Method, recorded so it is reproducible.** A read-only harness in scratchpad called `Lcom4Collector::collect()` and `lcom4_collector.collect()` directly on each fixture's source, and compared both against `expected.json`. **The observer runner was deliberately not used** — it appends to the append-only observation file, and evidence must not be polluted by its own verification.

| Fixture | Expected | PHP | Python | |
|---|---:|---:|---:|---|
| `cohesive-class.php` · `CohesiveExample` | 1 | 1 | 1 | ✅ |
| `split-class.php` · `SplitExample` | 2 | 2 | 2 | ✅ |
| `call-chain.php` · `ChainExample` | 1 | 1 | 1 | ✅ |
| `constructor-glue.php` · `ConstructorGlueExample` | 2 | 2 | 2 | ✅ |
| `isolated-methods.php` · `IsolatedExample` | 2 | 2 | 2 | ✅ |
| `static-methods.php` · `StaticExample` | 2 | 2 | 2 | ✅ |
| `trait-user.php` · `TraitUserExample` | 1 | 1 | 1 | ✅ |
| `self-call.php` · `SelfCallExample` | 2 | 2 | 2 | ✅ |
| `static-call-chain.php` · `StaticChainExample` | 1 | 1 | 1 | ✅ |
| `parent-call.php` · `ParentCallExample` | 2 | 2 | 2 | ✅ |

> **(b) PASSES — and the pass is far less informative than its shape suggests.** All ten agree, no fixture produced an extra or missing class, and the implementer's report is accurate on this point. **But §3 shows that three contract-defined divergences exist which these ten fixtures do not touch.** The ten fixtures probe *contract semantics* — exclusions, statics, constructors, `parent::`. **They probe no parsing construct where a hand-written scanner and an AST parser part company.** A 10/10 pass is therefore **necessary and nowhere near sufficient** for a neutrality claim, and it should never be quoted alone.

---

## 3 · Reproduction of the delivered report's measurement — **confirmed, and my first attempt was wrong**

Nobody had tested the verifier's own claims. I constructed my own probe rather than reuse `t1.php`.

**My first probe was defective and I record it rather than quietly discarding it.** In two of four classes I gave both methods the same instance variable, so the correct LCOM4 was 1 regardless and the probe **could not detect a vanished method**. It produced a false "no divergence" on the attribute case. **Corrected principle: to detect a lost node, the two methods must be genuinely disconnected, so the correct answer is 2 and any merge or loss shows as 1.**

**Corrected probe — four classes, each with two disconnected methods; contract-correct answer is 2 for all four:**

| Case | Contract requires | PHP | Python | |
|---|---:|---:|---:|---|
| `HeredocCase` — `$this->a()` inside a **heredoc** | 2 | 2 | 🔴 **1** | fabricated edge |
| `NowdocCase` — same inside a **nowdoc** | 2 | 2 | 🔴 **1** | fabricated edge |
| `AttrSameLine` — `#[SomeAttribute] public function a() {…}` on one line | 2 | 2 | 🔴 **1** | **method lost** |
| `AttrOwnLine` — attribute on its own line *(control)* | 2 | 2 | **2** | ✅ |

> **All three divergences reproduce, and the control behaves exactly as the delivered report claims.** The values match its table cell for cell, from a probe it did not supply. **The Stage-2 FAIL verdict rests on measurements that reproduce independently.** The PHP reference is correct in all four cases; **every defect is Python-side**, and the same-line-vs-own-line control isolates the trigger precisely.
>
> **This is reproduction, not endorsement.** I confirm the *measurements*. Whether the report's classification and reasoning are sound is a judgment I am disqualified from making, having read it first.

---

## 4 · (f) Is the declared authorship weakness adequately reflected? — **HONESTLY DISCLOSED, INSUFFICIENTLY DISCOUNTED**

**The facts, from the record.** Governance recommended Stage 2 go to a process other than `fbc084f0`, **not for `R-34` reasons but for experiment validity**: *"implementing from a contract you authored tests your memory of what you meant, not whether the words are sufficient for someone who wasn't there."* **The PO/ARB did not redirect. The experiment proceeded in the drafting process, and the implementer disclosed this at the top of its own evidence report rather than burying it.** `R-34` was **not** breached — the implementer did not verify itself. What was declined was a *recommendation*, by the authority entitled to decline it.

**Assessment — three parts.**

**① The disclosure itself is exemplary.** It is placed *before* the result (§1 of the evidence report, headed *"read this before the result"*), it names the governance recommendation it did not follow, and it separates what the weakness undermines from what it does not. **The direction of the implementer's own calibration is correct:** the *agreement* evidence is weakened by authorship (remembered intent can substitute for written sufficiency); the *divergence* finding is not, because there was no remembered intent to draw on.

**② The discount applied is too small, and the reproduction above measures by how much.** The implementer characterised the 34-case agreement as *"real but limited."* **The truer statement is that the agreement set is systematically blind in exactly the region where the defects live.** One process chose the contract's wording *and* the cases that would test it — a closed loop — and an outsider's **first** sustained probe of scanner-vs-AST territory found **three** contract-defined defects. **That is not a limitation of coverage; it is a measurement of the loop's blind spot.** The 34 cases and the 10 fixtures are evidence that the *semantics* were understood, and almost no evidence about *contract neutrality*, which is the commissioned question.

**③ One claim should be weakened before this evidence goes further.** The implementer's *"two strategies this different agreeing is evidence about the contract, not about a shared library"* is sound **as to the agreements** — but it is stated generally, and the agreements are precisely where the authorship weakness bites hardest. **Recommended reflection of the weakness, for the ARB rather than performed here:** the neutrality claim should rest on the **divergence** findings (unaffected by authorship) and **not** on the agreement counts (affected, and now shown blind).

> **(f) verdict: `VERIFIED WITH NOTES`.** The weakness is disclosed with unusual integrity and located correctly. **It is under-discounted in the strength of the agreement claim, and the correction is to how the evidence is quoted, not to anything in the artifacts.**

---

## 5 · Findings

| # | Finding | Class |
|---|---|---|
| **N-1** | **The delivered verification does not cover grant items (b) and (f).** Its six sections address (a), (c), (d), (e). **(b) — the experiment's own REQUIRED RESULT SHAPE — had never been independently re-derived by any process until now.** | **Finding** — against assignment completeness, not against the verdict |
| **N-2** | **(b) passes 10/10**, and the pass is nearly uninformative about neutrality: the ten fixtures probe contract semantics and no scanner-vs-AST construct. **It must never be quoted alone as neutrality evidence.** | Finding |
| **N-3** | **The three divergences reproduce exactly** from an independently constructed probe, control included. | Confirmation |
| **N-4** | **The authorship weakness is honestly disclosed but under-discounted** (§4). | Finding |
| **O-1** | **A probe that cannot fail is not evidence.** My first attempt gave both methods a shared variable, making the correct answer 1 and the lost-method defect undetectable. **Recorded because the same trap would silently weaken any future probe suite** — including a remediation suite written after this report. | Observation |

**Not established, and stated so:** that the three defects are exhaustive. **Enums, interfaces, traits as analysed units, readonly/promoted constructor properties, nested declarations and match/enum-case parsing remain unprobed by me.** Given that a first probe found three, the prior that more exist is high — **`Inferred`, not measured.**

---

## 6 · What was not done

**No repair · no modification of the contract, fixtures, `expected.json`, the PHP reference or the Python collector · no resolution of the anonymous-class gap · no architecture work · no Python adoption · no Stage 3 · no acceptance · no qualification or closure.** The Stage-2 verdict is **not** re-decided here; it stands as delivered.

**This assignment is not closed by this document (`G-1`).** Closure is Governance's act.

---

**Traceability:** grant `G-KOS-CONTRACT-STAGE2-VERIFY` items (a)–(f) · `G-KOS-CONTRACT-EXP` (REQUIRED RESULT SHAPE) · `G-KOS-CONTRACT-STAGE2` (METHOD BINDING: implement from the contract, not by porting) · assignment `S1-verification-python-stage2` `executionContext` (seq 11), ACTIVE since seq 13 · delivered verification `4d4738db` · Stage-2 evidence report §1 (declared weakness) · `S3-implementation-python-stage2` `executionContext` (the routing recommendation that was not followed) · `scripts/observations/Lcom4Collector.php` · `scripts/observations/lcom4_collector.py` · `scripts/observations/examples/lcom4/expected.json` and its ten fixtures · `R-34` · `G-1` · `INV-ATTR-2`.

---

> **Completion of two uncovered grant items, plus reproduction. Not a second independent verification — this process is disqualified from that. No acceptance. The PO/ARB decides.**
