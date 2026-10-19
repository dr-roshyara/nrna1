# Book outline: the sections arranged by what the book must deliver

*Rulebook §4, METHOD-VERSIONS entry 44 (Principal, 2026-10-19). Arranges the existing sections by the five deliverables of `06-theory/RESEARCH-PURPOSE.md`, and shows what is missing. Section numbers (§) are stable; files are listed in each chapter's index (`chapter_NN/NN.00-index.md`; Ch. 0–2 split into section files on 2026-10-19). Updated at every consolidation review; new sections are placed here when they are written. First version 2026-10-19; updated after the C9 settlement.*

**Status marks:** ✓ holds (has VALIDATED concepts) · ~ provisional · ✗ mostly refuted (see the section's "What does not hold") · — missing.

## 1. The kernel's primitives

| Candidate primitive | Where the book treats it | State | Missing |
|---|---|---|---|
| **Knowledge object and identity** | §0.3 the knowledge model; §1.5 knowledge is not its carrier; §1.10 identity needs governed names; §3.1 dimensions; §3.3 kinds; §3.4 knowledge ≠ artifact | ~ | one attribute table for a knowledge item (F13); identity (I-0) has no concept ID |
| **Lifecycle** | §0.4 how knowledge moves; §2.11 exceptions and the rule lifecycle; §4.1–4.3 change, the improvement cycle, the observed recommendation lifecycle | ~ | the four kinds of change (Ch. 4 plan) not written; retirement unobserved |
| **Authority** | §1.2 provenance and standing; §1.6 nothing but an act confers authority (A1); §1.12–1.14 record ≠ act, grants, roles; §5.5 who executed, who authorized | ✓ (CON-0016, scoped) | "authority" in three senses not yet split into three IDs (consolidation review) |
| **Evidence** | §1.3 grading claims; §4.1 from observation to pattern; §8.3 usage ≠ outcome | ~ | the evidence scales are not related to one another |
| **Relationships** | §3.6 relationships; §3.8 four orders | ✓ (CON-0064, scoped) | "realization" in two senses |
| **Policy (rules)** | Ch. 2 §2.0–2.11; §9.5 a recorded rule must reach the actor | ~ | no concept ID for policy as a primitive |
| **History** | §1.13.2 record integrity; §5.5 C5 | ~ | S0129's R-CONFLICT and "History ≠ Reconstruction Guess" not yet in the book |
| *Candidates added by the corpus* | visibility §1.7; standing §1.2 (CON-0018 ✗) | ~ | — |

## 2. The laws between them

| Law (kind) | Where | State |
|---|---|---|
| Only an act confers standing (A1) | §1.6, §1.14.2 | axiom |
| Record ≠ act; the residual trust assumption | §1.12 | ~ |
| Role laws: recorder ≠ decider, producer ≠ verifier | §1.14.3; §5.5 C1–C2; §9.4 C3 | ~ |
| Derivation may narrow visibility, never widen it | §1.7 | ~ |
| Uniqueness needs a serialization point | §1.16.7; §9.4 C2 | ~ |
| A policy receives its inputs; gate inputs are closed | §1.17.4; §9.4 C4 | ~ |
| Running ≠ enforcing; executable ≠ executed | §3.9 (CON-0092, CON-0093) | ✓ |
| Stated ≠ distributed ≠ held ≠ enforced | §6.4 C3 (CON-0102); §9.5 C1 | ✓ / ~ |
| A deferred rule cannot be cited as authority | §2.8.3 (CON-0063) | ✓ |
| Rule applicability is three-valued | §2.8 | ~ |
| A gap is a set of unmet requirements, relative to a contract | §5.4 | ~ |
| *Missing:* the derivation from primitives to laws as one chain (consolidation review: the architecture view) | — | — |

## 3. The boundary: kernel and application

| Question | Where | State |
|---|---|---|
| The three boundary questions (necessary? must the kernel provide it? delegable?) | RESEARCH-PURPOSE | stated, not tested |
| Product independence (the litmus) is not placement | §9.2 C2 | ✓ (CON-0105) |
| Method, binding and evidence | §1.11; §6.1 | ✓ (CON-0072, CON-0075) |
| Capabilities are not kernel operators | §9.3 C4 (CON-0111) | ✓ (restricted) |
| "Kernel" as a homonym (F14) | §6.1 C4; §6.4 C6 | recorded |
| Contexts, types, splits, admission | §6.2–6.4 | ~ |
| *Missing:* any capability tested by all three boundary questions | — | — |

## 4. The open forks

The fork table lives in `06-theory/THEORY-MAP.md` (F1 … F17, F13a). Sections that state a fork: §3.9 (F11), §3.10 (F8), §5.4 C8 (F15), §9.1 (F16, F17), §5.5 (F13a).

## 5. The kernel's type and existence

| Part | Where | State |
|---|---|---|
| The scaffold, its degeneracy, the repair, existence by one check | §9.1 | ✓ (CON-0099, scoped) / ~ (CON-0100) |
| Parts and whole | §9.2 | ✓ (CON-0105) |
| Preventive and generative halves | §9.3 | ✗ as stated (CON-0110), analogy kept |
| Invariants that cannot fail | §9.4 | ✗ as first stated (CON-0114, 0116); valid cores kept |
| Distribution as a candidate required capability | §9.5 (failure case of §0.7, §1.16.6) | ~ (CON-0117) |
| *Missing:* the required capabilities of KnowledgeOS itself (T9-01); the state space (F13, T9-02) | — | — |

## Chapters that serve method, not a deliverable directly

- **Ch. 0** foundations (§0.1–0.9) and **Ch. 8** validation (§8.1–8.4): how the theory is tested.
- **Ch. 5 §5.1–5.3** decisions and deferral: how decisions about knowledge are prepared.
