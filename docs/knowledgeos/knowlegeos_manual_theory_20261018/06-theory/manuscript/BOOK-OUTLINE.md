# Book outline: the sections arranged by what the book must deliver

*Rulebook §4, METHOD-VERSIONS entry 44 (Principal, 2026-10-19). Arranges the existing sections by the five deliverables of `06-theory/RESEARCH-PURPOSE.md`, and shows what is missing. Section numbers (§) are stable; files are listed in each chapter's index (`chapter_NN/NN.00-index.md`; Ch. 0–2 split into section files on 2026-10-19). Updated at every consolidation review; new sections are placed here when they are written. First version 2026-10-19; updated after the C9 settlement, and at the consolidation review S0036–S0065.*

**Status marks:** ✓ holds (has VALIDATED concepts) · ~ provisional · ✗ mostly refuted (see the section's "What does not hold") · — missing.

## 1. The kernel's primitives

| Candidate primitive | Where the book treats it | State | Missing |
|---|---|---|---|
| **Knowledge object and identity** | §0.3 the knowledge model; §1.5 knowledge is not its carrier; §1.10 identity needs governed names; §3.1 dimensions; §3.3 kinds; §3.4 knowledge ≠ artifact | ~ | one attribute table for a knowledge item (F13); identity (I-0) has no concept ID |
| **Lifecycle** | §0.4 how knowledge moves; §2.11 exceptions and the rule lifecycle; §4.1–4.3 change, the improvement cycle, the observed recommendation lifecycle | ~ | the four kinds of change (Ch. 4 plan) not written; retirement unobserved |
| **Authority** | §1.2 provenance and standing; §1.6 nothing but an act confers authority (A1); §1.12–1.14 record ≠ act, grants, roles; §5.5 who executed, who authorized; §6.5 record ≠ act as a context boundary (T3) | ✓ (CON-0016, scoped) | "authority" in three senses not yet split into three IDs (consolidation review); a fourth use, "authoritative" as the canonical home of knowledge (S0066, §6.6 C3), C12 |
| **Evidence** | §1.3 grading claims; §4.1 from observation to pattern; §8.3 usage ≠ outcome | ~ | the evidence scales are not related to one another |
| **Relationships** | §3.6 relationships; §3.8 four orders | ✓ (CON-0064, scoped) | "realization" in two senses |
| **Policy (rules)** | Ch. 2 §2.0–2.11; §9.5 a recorded rule must reach the actor | ~ | no concept ID for policy as a primitive |
| **History** | §1.13.2 record integrity; §5.5 C5 | ~ | S0129's R-CONFLICT and "History ≠ Reconstruction Guess" not yet in the book |
| *Candidates added by the corpus* | visibility §1.7; standing §1.2 (CON-0018 ✗) | ~ | — |
| **Trust** (F23) | — (S0913: Trust(a, d, c, t); trust ≠ authority; trust is itself knowledge) | — | not in the book; primitive or derivable? |
| **Conflict over claims** (F24) | §9.4 C3 (one current claim + supersession) against S0913 / R-CONFLICT (preserve, "Unresolved" terminal) | — | the tension is recorded, not settled |
| **Requirement / contract** | §5.4 (EC_t, Sat, Δ); §9.1 (Req: reachable states). Two objects, one word | ~ | the relation is open (T9-01) |

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
| The object of an act: it may include its argument; an acceptance covers the delivered version; the interpretive act (F21) | §5.6 (CON-0123 ~; CON-0124 ✗ as a law) | ~ |
| A subject-blind authorization check cannot enforce a rule about subjects | §5.5 C6 (CON-0131) | ✓ (T3) |
| Differential testing reports only the symmetric difference of blind sets; corroboration counts independent information | §8.6 C2, C4 (CON-0126 ✓ for common-mode failures; CON-0128 ~) | ✓ / ~ (T3) |
| Renumbering is safe for identifiers no outside reference binds (a sufficient condition) | §8.7 C2 (CON-0129, as a sufficient condition) | ✓ (T3) |
| A claim must declare the layer it holds at (neutrality, conformance) | — (S0164 L1276–1323, L1485) | missing (consolidation S0036–S0065) |
| *Missing:* the derivation from primitives to laws as one chain. **Both consolidation reviewers (S0036–S0065):** the sections in scope add cases to Ch. 1 laws and derive nothing from primitives; minimality of the primitives was never tested | — | — |

## 3. The boundary: kernel and application

| Question | Where | State |
|---|---|---|
| The three boundary questions (necessary? must the kernel provide it? delegable?) | RESEARCH-PURPOSE | stated, not tested |
| Product independence (the litmus) is not placement | §9.2 C2 | ✓ (CON-0105) |
| Method, binding and evidence | §1.11; §6.1 | ✓ (CON-0072, CON-0075) |
| Capabilities are not kernel operators | §9.3 C4 (CON-0111) | ✓ (restricted) |
| "Kernel" as a homonym (F14) | §6.1 C4; §6.4 C6 | recorded |
| Contexts, types, splits, admission | §6.2–6.4 | ~ |
| No second authoritative home (CON-0139 ✗ as "never copies"); the direction law: applications → kernel only | §6.6 C1, C8 | ~ (C12; direction law a candidate for deliverable 3) |
| Owned vs preserved invariants: a candidate test for boundary question 2 | §6.5 C2 (CON-0132) | ~ (T3) |
| **Plane or context (F22):** the authority-record machinery as a kernel plane (§1.14.3) or an application context BC-7 (§6.5 C0) | §1.14.2–1.14.3; §6.5 C0 | open fork (T3 critical finding) |
| **First boundary test (theory turn 1): recording authority, F22** | §9.6 (revised after review TT1): CR-a necessary iff R_s ∈ Req; the evaluator CE also necessary, and the stronger kernel candidate; placement decided by K3 (several independent applications?) and K4 (uniqueness across them); BC-7 as built fails I-1 (lost writes), the roles law and one-write-path; a delegation contract stated | ~ (Principal's act 2026-10-19: K3 = several applications, R_s ∈ Req; open on K4; CE's rule a kernel candidate, to be tested in theory turn 2) |
| **Second boundary test (theory turn 2): the evaluator CE** | §9.7 (revised after review TT2): an evaluator is necessary; §1.14.1's CE not yet a function; order of acts a uniqueness where act times are absent, tied or untrusted (F26, three options); K5 with a time axis; a common, versioned value for definition, coverage, root and policy values (one home only with §1.10's premise); delegation needs a declared cut and a must-do | ~ (missing: a total, monotone definition of CE; F26; K5 and F27 for the Principal; recording time; versioning of θ) |
| **Architecture views (window C15)** | §6.7: the C4 views draw no kernel line; testing the direction law needs a partition and identifiable dependencies (CON-0147); an output-only check confirms its inputs exactly when the fibre is a singleton (CON-0146 restated); S0084's adapter/core line | ~ (missing: a kernel-level view with typed relations; where verification meets acceptance) |
| *Missing:* further capabilities tested by all three boundary questions | — | — |

## 4. The open forks

The fork table lives in `06-theory/THEORY-MAP.md` (F1 … F24, F13a). New at the consolidation S0036–S0065: F23 (trust), F24 (conflicting claims). Sections that state a fork: §3.9 (F11), §3.10 (F8), §5.4 C8 (F15), §9.1 (F16, F17), §5.5 (F13a).

## 5. The kernel's type and existence

| Part | Where | State |
|---|---|---|
| The scaffold, its degeneracy, the repair, existence by one check | §9.1 | ✓ (CON-0099, scoped) / ~ (CON-0100) |
| Parts and whole | §9.2 | ✓ (CON-0105) |
| Preventive and generative halves | §9.3 | ✗ as stated (CON-0110), analogy kept |
| Invariants that cannot fail | §9.4 | ✗ as first stated (CON-0114, 0116); valid cores kept |
| Distribution as a candidate required capability | §9.5 (failure case of §0.7, §1.16.6) | ~ (CON-0117) |
| *Missing:* the required capabilities of KnowledgeOS itself (T9-01), **the load-bearing undefined object**: six claims defer to it (§5.5 C3, §6.5 C1, §9.3, §9.4 C7, §9.5 C1, C5); candidates in scope: distribution (§9.5), bootstrap (§9.2), the record and fold (§6.5), Zero(EC) reachable (§5.4); how to establish a capability (S0164 D5 E1–E10); the state space (F13, T9-02) | — | — |

## Chapters that serve method, not a deliverable directly

- **Ch. 0** foundations (§0.1–0.9) and **Ch. 8** validation (§8.1–8.5): how the theory is tested. §8.5 (verification by a different method, CON-0121 ✓; calibration of grades); §8.6 independence of a check and §8.7 correction chains (T3, provisional).
- **Ch. 5 §5.1–5.3** decisions and deferral: how decisions about knowledge are prepared.
