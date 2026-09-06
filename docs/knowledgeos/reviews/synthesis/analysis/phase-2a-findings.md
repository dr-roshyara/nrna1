# PHASE 2A · Findings — the seven gate priorities

**Authorized by:** `phase-gate-ruling.md` · scope-limited; **no book chapters, no Phase 2B**.
All findings from **direct reads** of the named sources unless marked ⟦TITLE⟧.

---

## P1 · EG-05 — did the conformance suite execute? **ANSWER: PARTIALLY**

The scan and reads split Steps 109–124 into three evidentially different bands:

**Band 1 — executed, at directory-archaeology depth (109–119).**
Step 109 contains numbered *Experiments* with real repository facts: ⟦C⟧ *"Documentation describes
`knowledge/ governance/ agents/`. Repository actually contains `app/ packages/ scripts/`"* →
`PASS`; ⟦C⟧ *"`.claude/` contains settings, hooks, commands, memory"* with the caution ⟦C⟧ *"a
directory named `memory` does not automatically mean authoritative KnowledgeOS memory"*; `AGENTS.md`
and `.codex/` inspected. PASS-verdict experiment counts run 11–26 per step through 119. ⟦INT⟧ These
are genuine observations of the actual repo — but at **structure level**, not code/behaviour level
(0–7 concrete path references per ~1,500–2,000-line document).

**Band 2 — real non-conformances found (118–121).**
Violation/non-conformance findings cluster here (13, 9, 10, 9 hits respectively), culminating in
Step 121's boxed ⟦C⟧ **`CRITICAL GOVERNANCE GAP`**: ⟦C⟧ *"No approval process exists… Constitution
can be silently weakened"* → constitutional **versioning** demanded. ⟦INT⟧ The suite found something
true and important about the real system — this is the strongest repository-validation evidence in
the corpus.

**Band 3 — methods DEFINED, not executed (121-method, 122, 123).**
Step 121's own verdict: ⟦C⟧ *"We have now **established the Constitution-to-Implementation
Conformance Method**"* with the classification `Conformant | Partial | Declared | Absent | Unknown`
— the method is delivered; classifying *every rule* is not reported done. Step 123's verdict: ⟦C⟧
*"We have now **defined** the first KnowledgeOS Self-Verification **Suite** (KOS-SV-01…SV-07)"*
covering Provenance/Authority/EpistemicStatus/TemporalValidity/DeterministicAssurance — **defined**,
with one executed probe (⟦C⟧ boxed *"Self-reporting failure"*: `APIClaim ≠ Reality`).

**EG-05 verdict:**
> **Directory-level archaeology: EXECUTED. Governance-gap detection: EXECUTED (with findings).
> Rule-by-rule conformance classification and the self-verification suite: DEFINED, NOT EXECUTED.**
The book may say *"conformance testing began and found a critical governance gap"*; it may **not**
say *"the architecture was validated against the repository."*

---

## P2/P3 · CON-02 and CON-01 — were the semantic shifts ever noticed in-corpus? **NO — and worse**

⟦OBS⟧ Verified: the word **"lens" appears 0 times** in both `025d` (Zero Algebra) and `025g` (Lord
Algebra). The formalizations never reference their origins.

⟦OBS⟧ **The smoking gun for CON-02** is the corpus's own R2 document. `question-17` (08-26, pre-
algebra) states: ⟦C⟧ *"Zero is **analytical, not transformational**. Lord is **analytical, not
transformational**."* Ten hours later `025g` defines Lord as *what should happen next* — an
**operational selector**. ⟦INT⟧ So the displacement is not merely lens-vs-algebra: **R5 contradicts
R2's explicit role assignment, and neither document cites the other.** Answer to the gate's DDD
question: on current evidence these are **two distinct concepts sharing a name** (Ω-observer vs
action-selector), not one concept with evolving semantics — the boundary between them is the
R4→R5 regime shift, and it was never made explicit. Per instruction: **nothing renamed**; recorded
for the ubiquitous-language decision the book must make.

⟦INT⟧ CON-01 (Zero) formulation fixed per the gate: the book must say **"the formalization
introduced semantics not present in the original lens"** (a goal `G` and context `EC`), never
"derived from the lens."

---

## P4 · Option 3 recovered (AD-01/OQ-06 closed)

⟦READ `125403`⟧ Option 3 = the **two-level observation architecture**:
> ⟦C⟧ **"What was observed ≠ what was understood"** — levels renamed by the document to **Source
> Observation** and **Semantic Observation**.
Rationale: collapsing them ⟦C⟧ *"loses provenance and makes later verification extremely
difficult"* — worked example: *"Nexus 3.69 is running"* can originate from DB / document / human /
(fourth path), each with different evidential character. ⟦INT⟧ Option 3 is this corpus's
independent re-arrival at the kernel-corpus's Expression≠Meaning / representation-agnostic-intake
family. Options 1/2 remain unrecovered (they precede `125403`; low priority).

---

## P5 · Step 100 Result blocks (OQ-08 closed) — **36/36 PASS, zero failures**

⟦OBS⟧ All 36 `### Result` blocks are `PASS`; no FAIL/GAP anywhere in the document. The document ends
by launching the conformance question ⟦C⟧ `A_intended ≟ A_implemented ≟ A_runtime`.
⟦INT⟧ **A 100%-pass self-administered closure test carries limited evidential weight** — contrast
Step 50, which produced local failures and absorbed them. Step 100's value is as the *pivot document*
(it hands over to conformance mode), not as validation. The book should cite Step 50, not Step 100,
as the audit.

---

## P6 · R2 → Step-49 survival map (EG-06) — **the R2 objects were silently dropped**

⟦OBS⟧ In `step-049` (the reduction that yields the 8 primitives): `atom: 0` occurrences ·
`dimension: 0` · `knowledge space: 0` · `lens: 0`.
⟦INT⟧ The Knowledge Atom, the dimension theory and the Knowledge Space — R2's three central objects
— are **absent from the reduction**, neither incorporated nor rejected. The 8-primitive kernel
`{Entity, State, Event, Observation, Proposition, Relation, Policy, Action}` descends from the R5
chain alone.
⟦OBS⟧ **And one more lineage fact of the first importance: `Knowledge` is not one of the 8
primitives.** ⟦INT⟧ R1's opening hypothesis — *knowledge is probably not the Kernel object*
(`20260825-214833`) — was **vindicated by construction** in Step 49, and the corpus never connects
the two documents. That is the single best example of the evolution the master prompt wants
preserved: a hypothesis at hour 1, silently confirmed by the architecture at hour 60, with no
recorded acknowledgement.

---

## P7 · PQ-02 — weighting of the 76-docs/hour material. **PROPOSED RULING**

⟦OBS⟧ Sample of the `20260828-12` hour: steps 091/095/104 are **2,200–2,400 lines each**, each
opening formulaically (⟦C⟧ *"We continue from Step N"*). 76 documents × ~2,000 lines ≈ 150k
lines/hour — a single generative run.
**Proposed ruling (for HPA confirmation):** for evidential counting, treat each contiguous
generative run (e.g. the 12:xx block) as **one authored artifact-chain**; its steps are *sections*,
not independent arrivals. Consequences: (a) "N documents agree" claims must not count within-run
agreement; (b) within-run self-tests (Step 100) are weaker than cross-run tests (Step 50 vs the
R4 experiment); (c) the chain still counts fully as *specification* output. **Not applied
retroactively to Phase-1 artifacts without confirmation.**
