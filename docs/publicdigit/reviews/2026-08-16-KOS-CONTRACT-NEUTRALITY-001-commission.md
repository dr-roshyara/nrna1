# KOS-CONTRACT-NEUTRALITY-001 — Work Item A commissioned (bounded evidence experiment)
# and why Work Item B was NOT created

**2026-08-16 · Session 2 (Governance)** · **Lane registered · ⛔ NOT STARTED — the START is the PO/ARB's act.**

```
─────────────────────────────────────────────────────────────
 ILLUSTRATIVE PROVENANCE BLOCK — TRIAL INSTANCE #7
 Responsibility : governance
 Operator       : Session 2 (Governance)              [declared]
 Approver       : PO/ARB — Work Item A instruction 2026-08-16 [declared]
 Evidential     : DECLARED, not attested (INV-ATTR-2). Evidence only.
─────────────────────────────────────────────────────────────
```

---

## 1 · The instruction, registered verbatim

> *"Proceed with Work Item A as a bounded evidence experiment. Do not treat it as Python migration or platform implementation.*
> *The experiment may implement exactly one existing capability against the existing language-neutral conformance contract and its existing fixtures.*
> *No new platform architecture, package structure, repository split, service boundary, database, API, or Python framework may be introduced.*
> *The experiment must stop after producing its evidence and hand that evidence to ARB.*
> *The experiment must remain completely separate from `KOS-ARCH-BASELINE-001` and must not provide target-architecture assumptions to the Architecture baseline lane."*

## 2 · What was registered

| Act | Value |
|---|---|
| **Work item** | `KOS-CONTRACT-NEUTRALITY-001` — workflow `evidence-experiment`, four canonical roles |
| **Assignment** | seq **1** — `S3-implementation-contract-neutrality`, role **`implementation`** |
| **Grant** | **`G-KOS-CONTRACT-EXP`** — `AUTHORIZED` |
| **Handoff** | seq **2** — bootstrap |
| **START** | ⛔ **NOT performed — reserved to the PO/ARB** |

**Named for the question, not the technology.** The work item is `CONTRACT-NEUTRALITY`, not `PYTHON-MIGRATION`, because the question is *"is the contract genuinely language-neutral?"* — Python is the instrument. **Naming it for Python would have prejudged what the experiment is for**, which is the error the ARB commentary itself warns against.

**Workflow type `evidence-experiment`** — deliberately not `platform-implementation`. **The record should say what this is.**

## 3 · Scope — the boundaries that make it an experiment

**Permitted:** exactly **one** existing capability (the cohesion/`lcom4` collector) · the **existing** contract and its **existing seven fixtures** · comparison of PHP result vs Python result vs `expected.json`.

**Required result shape:** `PHP result == Python result == expected`.

**🛑 HARD STOP:** once the comparison produces its evidence, **stop** and hand the evidence to the ARB.

**Forbidden:** any new platform architecture · package structure (`pyproject`/`setup`/`requirements`) · repository split · service boundary · database · API · Python framework · migration · replacement of PHP · new EKS design · new bounded contexts or aggregates · **modifying the existing contract, fixtures, expectations or the PHP implementation** · any change to `KOS-ARCH-BASELINE-001` · **supplying target-architecture assumptions to the baseline lane** · Election work (`A-8`) · self-certification.

**`R-34` binding:** the implementing process **must not verify its own result**. Independent verification is a separate assignment, not yet created.

**Outcome must be classified as one of three** *(taken from the ARB commentary, because the distinction is the experiment's main value)*:

| | Outcome | What it means |
|---|---|---|
| **A** | **Pass** | The contract is genuinely language-neutral |
| **B** | **Fail — contract ambiguity** | ⭐ **The contract is imprecise. Improve the CONTRACT, not the architecture** |
| **C** | **Fail — implementation defect** | Contract sound; the Python implementation is wrong |

> **The experiment produces EVIDENCE ONLY.** It decides no strategic question: it does not adopt Python, does not open any gate, and does not determine whether PHP is retained or retired. **What the evidence means strategically is the ARB's to decide, later.**

---

## 4 · 🟠 Work Item B was NOT created — and I need a decision

> ### **Recommended: do not create Work Item B yet. It conflicts with a phase separation you reaffirmed in the same message.**

**The instruction says:** *"I would now create two separate work items… **Work Item B — EKS Strategic Architecture Finalization**: establish the authoritative strategic architecture, domain boundaries, knowledge model, authority model, lifecycle model, and architectural invariants."*

**The conflict, stated plainly:**

| | |
|---|---|
| **Work Item B's content** | Rule Model · Authority Model · Knowledge Vocabulary · Bounded Contexts · Context Map · C4 · Architecture Constitution |
| **What that is** | ⭐ **Target-architecture design** |
| **What is already active** | `KOS-ARCH-BASELINE-001` **Phase A**, whose grant **forbids target-architecture design**, and whose agreed sequence defers it to **Phase C, separately authorized if justified** |
| **What the same message says** | *"The Python experiment must not provide target-architecture assumptions to the Architecture baseline lane"* · *"new speculative architecture → pause"* |

> **Creating Work Item B now would open a target-architecture lane while the baseline it should rest on is still being reconstructed — and would do so under the same rule that was just used to keep the Python experiment away from Phase A.** The isolation you applied to Track 1 applies at least as strongly to Track 2, because Track 2 *is* the target architecture.

**There is also a measured reason.** The gap analysis's decisive finding is that **architecture output is displacing evidence production**: the evidence store has been idle since 2026-08-05, while `docs/knowledge_tranfer/` grew from 24 documents to **31 in about two hours** (verified `1b2945ed`). **Work Item B is, by its own description, a large architecture-writing programme.** Opening it now would add to the numerator of the very ratio the analysis identifies as the problem.

**Options**

| | Option | Consequence |
|---|---|---|
| **A** | **Defer Work Item B until Phase A's baseline is verified and accepted** *(recommended)* | Target architecture rests on a verified baseline. Matches the agreed sequence exactly |
| **B** | **Create it now, dormant** — registered, uncommissioned, no START | Visible without competing. **But a registered lane invites starting**, and the register already exists for holding things that aren't being worked |
| **C** | **Create and start it now** | ❌ Contradicts the Phase A/C separation and the pause on speculative architecture |

**Governance recommends A**, and notes the Open Findings Register already exists to hold Work Item B's *intent* without creating a lane for it.

## 5 · The §10 programme rule — ready, not adopted

> *"Architecture activity cannot substitute for operational evidence when the decision under consideration is explicitly gated on operational evidence."*

**Governance judges this a genuinely strong invariant** — stronger than *"don't write too many documents"*, because it binds the *substitution*, not the volume. **It is not registered**, because the instruction was phrased as *"I would therefore add"*. **One word adopts it**, and it would extend the existing amendment series rather than create a new home.

## 6 · What has NOT been done

**No START** · **no Work Item B** · **no Python file written** · no package, framework or structure created · no contract, fixture or expectation modified · no PHP implementation touched · `KOS-ARCH-BASELINE-001` untouched and still awaiting its own performing session · no Election artifact touched · `AST-015`/`AST-016` unchanged · no verification assignment created (it is `R-34`-separate and comes after evidence exists).

---

*Technical references: PO/ARB instruction 2026-08-16 (§1 verbatim) · `KOS-CONTRACT-NEUTRALITY-001` seq 1–2 · `G-KOS-CONTRACT-EXP` · gap analysis and its independent verification (`1b2945ed`) · the conformance contract at `scripts/observations/examples/lcom4/` (7 fixtures + `expected.json`, verified present) · evidence store last written 2026-08-05 (verified) · `KOS-ARCH-BASELINE-001` Phase A grant `G-KOS-ARCHBASE-A` · `A-8` · `R-34` · `A-7`/`GOV-HUMAN-01` · `INV-ATTR-2`.*
