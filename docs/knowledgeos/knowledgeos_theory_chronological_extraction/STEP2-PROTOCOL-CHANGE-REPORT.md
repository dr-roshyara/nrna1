# Step-2 Protocol — Change Report (v1 → v2)

| | |
|---|---|
| **Subject** | `prompts/knowledge_os_step2_theory_construction_protocol.md` |
| **Commission** | Senior-architect review under 15 instructions (research architect · mathematician · statistician · DDD architect · computer-logic expert) |
| **Date** | 2026-09-22 |
| **Result** | **19 changes: 6 BLOCKING · 9 REQUIRED · 3 RECOMMENDED · 1 DEFERRED** |
| ⛔ **Not done** | Step 2 was **not executed** · the Step-1 Master Protocol was **not modified** |
| **Size** | 514 → 773 lines |

---

## 1. The six BLOCKING changes

These would have caused the protocol to **fail on first use** or to produce silently wrong output.

### C-01 · `BLOCKING` · Circular gate `G2-D` removed

| | |
|---|---|
| **What changed** | `G2-D` ("every competing formulation has a discriminator recorded") was an **entry gate**. It is now a **Phase-E output** (§3.2, §6). |
| **Why** | ⛔ **Naming discriminators *is* Step 2.** v1 line 169 reads `d := name_discriminating_observation(c)` — inside the algorithm the gate was guarding. Step 2 could not start until it had already run. |
| **F0001–F0025 observation** | `C-0007` has no discriminator and cannot acquire one without `RO-0008`, which needs evidence outside the registry. The gate was unsatisfiable in principle. |
| **Prevents** | A protocol that can never legally begin. |

### C-02 · `BLOCKING` · Circular gate `G2-F` removed

| | |
|---|---|
| **What changed** | Canonical Discovery was an entry gate. Split into a **one-time Phase-0 sweep** + a **per-formulation obligation** (`Q10`). |
| **Why** | ⛔ You cannot pre-search formulations that do not yet exist. The gate demanded discovery *of Step 2's own output*. |
| **Observation** | §0.1 — v1 was itself drafted without searching `docs/knowledgeos/backlog/`, missing `EKS-35`, which already registered findings F-1/F-2 with stronger evidence. |
| **Prevents** | Circularity, while keeping the real obligation enforceable at the point it can actually be checked. |

### C-03 · `BLOCKING` · Three contradictory gate counts reconciled

| | |
|---|---|
| **What changed** | §2.1 said "four of six"; §14 said "three of five"; the closing line named three gates. Now a **single Class-A list, stated once**. |
| **Why** | A reader could not determine whether Step 2 was permitted to start. |
| **Prevents** | The document disagreeing with itself about its own precondition. |

### C-04 · `BLOCKING` · Algorithm asserted the wrong gate set

| | |
|---|---|
| **What changed** | v1 line 134: `assert gates G2-A..G2-E` — **silently omitting `G2-F`**, which §2.1 declared mandatory. Now asserts Class A only, matching §2.2 exactly. |
| **Why** | The executable specification contradicted the prose specification. |
| **Prevents** | A gate declared and never enforced. |

### C-05 · `BLOCKING` · §9 violated its own `Q14` — formalization ladder added

| | |
|---|---|
| **What changed** | v1 stated five structures `S-1..S-5` as formalisms while `Q14` forbade exactly that. Added the **F0–F3 ladder** (§9): observed pattern → candidate structure → formal proposition → validated proposition. **All five are now capped at F1; none reaches F2.** |
| **Why** | `S-1` states `promote(k) ⟺ evidence(k) ≥ bar ∧ ∃a: grants(a,k)` — and **`bar` is undefined**, with three incompatible candidates in the corpus (`ES-006.1` n≥2 · `P-4`'s five-point scale · `CAP-001`). A conjunction with an undefined threshold is not a proposition. |
| **Observation** | `T-0013` (F0018) is rigorously derived; the *threshold it depends on* is never stated anywhere in 25 files. |
| **Prevents** | **Elegance mistaken for rigor** — the exact failure instruction 6 names. The honest post-25-file state is now stated: *nothing reaches F2*. |

### C-06 · `BLOCKING` · Output location bound to `phase2_extraction/`

| | |
|---|---|
| **What changed** | v1 named artifacts but **never said where they land**. §15.0 now binds every Step-2 write to `knowledgeos_theory_chronological_extraction/phase2_extraction/`, enforced by `Q18`. |
| **Why** | Step-2 synthesis would have mixed into the Step-1 record, destroying the boundary between *what the corpus said* and *what we inferred*. |
| **Prevents** | Contamination of the evidence base. **The separation is now the audit boundary:** deleting `phase2_extraction/` restores a clean Step-1 state with no residue. |

---

## 2. The nine REQUIRED changes

| # | Change | Why | F0001–F0025 observation | Prevents |
|---|---|---|---|---|
| **C-07** | ⭐ **`HISTORICAL-STORY.md` added as deliverable 1** (§4), with `Q16` forbidding any L2 statement in it | v1 jumped registries → seed, with no narrative at all | `IFR-0010`: F0019's narrative was revised after the fact and its first form is now **unrecoverable** | History silently absorbing later interpretation — the thing that makes it evidence |
| **C-08** | **Failed-test outcome added to the ladder** (§1.1): SURVIVED / REFUTED / INCONCLUSIVE | v1's L3→L4 assumed tests succeed; a refuted item had nowhere to go | `DI-0008`: F0019's premise was refuted while its conclusion was **retained** | A refuted claim quietly keeping its level |
| **C-09** | **`G2-B` → per-thread precondition** (§2.3) | v1 blocked *all* synthesis over 3 underspecified objects | `T-0006`/`T-0010`/`T-0011` are underspecified but **none is a thread's primary object** — so all 8 threads are synthesizable today | Over-strict gating that stops legitimate work |
| **C-10** | **`STEP2-THREADS.jsonl` overlay** + `Q17` | v1 listed `THEORY-THREADS.jsonl` (a Step-1 artifact) as a Step-2 output | — | Step 1 ceasing to be independently re-runnable |
| **C-11** | ⭐ **Provenance chain mandatory** (§14) + `Q15` + reverse index | v1 had only `supported_by` | `C-0007`: one falsified claim is load-bearing for a verdict **four documents away** | Inability to trace what a refuted record contaminates |
| **C-12** | ⭐ **Three verification modes** (§10.2): SELF / INDEPENDENT-BLIND / EXTERNAL-REQUIRED | v1 gestured at independence without defining it | `F0015`, an independent blind review, produced the window's most consequential correction — **catching what five self-reviewing documents had repeated** | Self-verification being mistaken for verification. **SELF alone is declared insufficient** |
| **C-13** | ⭐ **Theory maturity model** (§11): M0–M5, **per item** | Instruction 9; v1 had metrics but no maturity ladder | — | Assuming 25 files can yield a final theory |
| **C-14** | ⭐ **Nine-dimensional distance assessment** (§12) replacing a single figure | Instruction 10 | `HA-0007` is unclosable by reconstruction, so **100 % is not the target** | *"KnowledgeOS is 30 % complete"* — a number that hides which dimension is weak |
| **C-15** | ⭐ **Eight test types** (§13), each stating what it **cannot** settle | v1 ranked tests but never typed them | `OQ-11`: a synthetic benchmark built from the theory's own concepts cannot refute it | Tests that cannot decide what they are scheduled to decide |

---

## 3. RECOMMENDED and DEFERRED

| # | Change | Class | Why |
|---|---|---|---|
| **C-16** | `answers_question` field linking each seed item to one of the nine questions | RECOMMENDED | v1's schema had no link to §5's structure |
| **C-17** | Batch semantics defined for saturation (§12.3) | RECOMMENDED | v1 referenced "a full batch" without defining Step-2 batches |
| **C-18** | ⭐ **§0.2 conceptual rule stated explicitly** | RECOMMENDED | v1 read as constraining reasoning. Now: *the protocol constrains what must be evidenced, recorded, separated, tested and preserved — **not** legitimate reasoning or theory construction.* `T-0023` was assembled from five files none of which states the counter orders anything, and it is the batch's most useful finding — a protocol forbidding such synthesis would have suppressed it |
| **C-19** | §10.4 rank 6 vs `OQ-6` tension | **DEFERRED** | Escalation is not research, so the texts are compatible. Recorded rather than reworded |

---

## 4. Dependency audit

**No remaining circular dependency.** Strict order:

```
Class A prerequisites → Phase 0 (sweep) → A admit → B route → C story
→ D synthesize → E compete → F formalize → G seed → H verify
→ I agenda → J assess → K checkpoint
```

**No phase requires the output of a later phase.** Verified against §1 ladder, §2 gates, §3 algorithm, §5 seed, §6 competitions, §7 provisionality, §8 evidence, §9 formalization, §10 verification, §11 maturity, §12 distance, §13 agenda, §14 provenance, §15 artifacts, §16 quality gates, §17 experiments.

---

## 5. What changed about *starting*

| | v1 | v2 |
|---|---|---|
| Gates | 6, of which 4 open — **2 unsatisfiable in principle** | **3 prerequisites**, of which **1 open** |
| Open item | `RO-0011`, `RO-0014`, `G2-D`, `G2-F` | **`RO-0014` alone** — 10 registry rows, mechanical |
| `RO-0011` | a blocker | a **Class-C declared obligation** (no thread's primary object is affected) |
| Discriminators | a precondition | **Step-2 output** |
| Canonical discovery | a precondition | **Phase-0 sweep + `Q10`** |

> ### v1 could not legally start. v2 can, once one bounded migration is done.

---

## 6. Honest statement of what is still wrong

| Issue | Status |
|---|---|
| `Q10` records **one violation already committed** (§0.1, EKS-35) | Retained in the document, not erased |
| `Q14` was violated by v1 §9; v2 fixes it — but **all five structures now sit at F1**, and none may advance until `OQ-9` closes | This is the honest state, not a defect of the protocol |
| `C-0007` remains unresolved; its discriminator needs evidence **outside the registry** | Class-C obligation; may block M3 for several seed items |
| Four of six evidence targets (§8) are **unreachable under the current registry** | `OQ-6` — a governance decision, escalated, not decided here |
| Whether separating story from seed prevents contamination or merely adds cost is **unmeasured** | `OQ-12`, added |

---

*Traceability: change report for Step-2 protocol v1 → v2, 2026-09-22 · 19 changes classified BLOCKING/REQUIRED/RECOMMENDED/DEFERRED, each citing the F0001–F0025 observation that required it · consistency review performed across §1–§17 · ⛔ **Step 2 was NOT executed · the Step-1 Master Protocol was NOT modified** · ⛔ **nothing in this document executes.***
