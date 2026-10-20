# Book outline: the sections arranged by what the book must deliver

*Rulebook §4, METHOD-VERSIONS entry 44 (Principal, 2026-10-19). Arranges the existing sections by the five deliverables of `06-theory/RESEARCH-PURPOSE.md`, and shows what is missing. Section numbers (§) are stable; files are listed in each chapter's index (`chapter_NN/NN.00-index.md`; Ch. 0–2 split into section files on 2026-10-19). Updated at every consolidation review; new sections are placed here when they are written. First version 2026-10-19; updated after the C9 settlement, and at the consolidation review S0036–S0065.*

**Status marks:** ✓ holds (has VALIDATED concepts) · ~ provisional · ✗ mostly refuted (see the section's "What does not hold") · — missing.

## 1. The kernel's primitives

| Candidate primitive | Where the book treats it | State | Missing |
|---|---|---|---|
| **Knowledge object and identity** | §0.3 the knowledge model; §1.5 knowledge is not its carrier; §1.10 identity needs governed names; §3.1 dimensions; §3.3 kinds; §3.4 knowledge ≠ artifact | ~ | one attribute table for a knowledge item (F13); identity (I-0) has no concept ID |
| **Lifecycle** | §0.4 how knowledge moves; §2.11 exceptions and the rule lifecycle; §4.1–4.3 change, the improvement cycle, the observed recommendation lifecycle | ~ | the four kinds of change (Ch. 4 plan) not written; retirement unobserved |
| **Authority** | §1.2 provenance and standing; §1.6 A1, a stipulated definition of recognized standing, with named readings; §1.12–1.14 record ≠ act, grants, roles; §5.5 who executed, who authorized; §6.5 record ≠ act as a context boundary (T3) | ✓ (CON-0016, scoped) | "authority" in three senses not yet split into three IDs (consolidation review); a fourth use, "authoritative" as the canonical home of knowledge (S0066, §6.6 C3), C12 |
| **Evidence** | §1.3 grading claims; §4.1 from observation to pattern; §8.3 usage ≠ outcome | ~ | the evidence scales are not related to one another |
| **Relationships** | §3.6 relationships; §3.8 four orders | ✓ (CON-0064, scoped; impact clause H) | "realization" in two senses; acyclicity only on S0026's edges |
| **Policy (rules)** | Ch. 2 §2.0–2.11; §9.5 a recorded rule must reach the actor | ~ | no concept ID for policy as a primitive |
| **History** | §1.13.2 record integrity; §5.5 C5; **§9.8 (theory turn 3)**: state history decomposes into record properties H1′ (every change an entry), H2 (two orders), H3a/H3b (policy as entries; pinned evaluator) and the evaluator, conditional (D); truthful history needs H4 (soundness) and H5 (completeness or marked absence), not checkable inside the record; "History ≠ Reconstruction Guess" (S0129 L102); R-CONFLICT not adopted | ~ | whether history is a separate primitive or part of authority (§1.2 checkability, §1.16.2) and policy (RM-3): **for the Principal**; "the record" as a primitive has no independence model (Hyp); forks F29–F31 |
| *Candidates added by the corpus* | visibility §1.7; standing §1.2 (CON-0018 ✗) | ~ | — |
| **Trust** (F23) | **§9.9 (theory turn 4)**: three senses; trust in a source a conditional, definitional reduction (premises: faithful outcome records, adjudication with a root, F34); trust in a knowledge item Hyp; trust in the record: integrity and authenticity reduce to anchors; occurrence A1's bridge premise relative to A1-as-fact; relative to A1-as-form one of several record-to-world assumptions, a primitive not shown | ~ | the root of source assessments (F34); whether Req needs source or item trust; occurrence as an explicit axiom (for the Principal) |
| **Conflict over claims** (F24) | §9.4 C3 (one current claim + supersession) against S0913 / R-CONFLICT (preserve, "Unresolved" terminal) | — | the tension is recorded, not settled |
| **Requirement / contract** | §5.4 (EC_t, Sat, Δ); §9.1 (Req's inputs: reachable states; questions: observers, C6 result 7); **§9.10 (Req as questions and inputs to accept)**. Two objects, one word | ~ | §9.10 keeps the contract gap (Q7) as a candidate only |

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
| Differential testing reports at least the symmetric difference of blind sets, never the shared blind set; corroboration counts independent information | §8.6 C2, C4 (CON-0126 ✓ for common-mode failures; CON-0128 ~) | ✓ / ~ (T3) |
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
| **First boundary test (theory turn 1): recording authority, F22** | §9.6 (revised after review TT1): CR-a necessary iff R_s ∈ Req; the evaluator CE also necessary (its rule common and versioned, its home open, §9.7.9); placement decided by K3 (several independent applications?) and K4 (uniqueness across them); BC-7 as built fails I-1 (lost writes), the roles law and one-write-path; a delegation contract stated | ~ (Principal's act 2026-10-19: K3 = several applications, R_s ∈ Req; open on K4; CE tested in theory turn 2 (§9.7): kernel home not shown) |
| **Second boundary test (theory turn 2): the evaluator CE** | §9.7 (revised after review TT2): an evaluator is necessary; §1.14.1's CE not yet a function; order of acts a uniqueness where act times are absent, tied or untrusted (F26, three options); K5 with a time axis; a common, versioned value for definition, coverage, root and policy values (one home only with §1.10's premise); delegation needs a declared cut and a must-do | ~ (missing: a total, monotone definition of CE; F26; K5 and F27 for the Principal; recording time; versioning of θ) |
| **Architecture views (window C15)** | §6.7: the C4 views draw no kernel line; testing the direction law needs a partition and identifiable dependencies (CON-0147); an output-only check confirms its inputs exactly when the fibre is a singleton (CON-0146 restated); S0084's adapter/core line | ~ (missing: a kernel-level view with typed relations; where verification meets acceptance) |
| *Missing:* further capabilities tested by all three boundary questions | — | — |

## 3a. Kernel, application and observation marks (Int; proposals, untested)

*The only home of these marks (the Principal, 2026-10-20: no register column). A mark says where a concept or law would sit if it holds; it is not a boundary-test result. Chapters not listed have no marks yet (Ch. 2–5, Ch. 9).*

### Ch. 0–1

Each law or concept of Ch. 0 and Ch. 1 is classed by **form** (prohibition: what must never happen; must-do: what must be possible or done; definition or stipulation; observation) and given a **proposed mark**: **K?** a kernel candidate (not tested by the boundary test of RESEARCH-PURPOSE), **A** application, **O** an observation about the estate, **D** a definition or convention, **P** a purpose. Every mark is a proposal of this book. The must-do items in the last column are the inputs Ch. 0–1 give to the kernel's required capabilities (Req, Ch. 9 TODO T9-01). A prohibition with no must-do partner is satisfied by a system that does nothing (Ch. 9 §9.1 C1).

| Law or concept | Home | Form | Mark | Must-do partner (input to Req) |
|---|---|---|---|---|
| I-1(GKC) knowledge never silently disappears | §0.5 | prohibition | K? | retire only by a recorded act (staged retirement) |
| I-2 status changes explicit, reasoned, visible | §0.5 | must-do + prohibition | K? | record the act with its reason; show status where knowledge is found |
| I-3 every claim carries provenance | §0.5 | must-do | K? | attach provenance at creation |
| I-4 contradictions stay visible | §0.5 | must-do | K? (rest) / A (resolution at use) | report a proven conflict with its region (Ch. 2 §2.3) |
| I-5 authority is scoped | §0.5 | prohibition | K? | evaluate scope with each act (§1.6 condition 2) |
| I-6 freshness visible | §0.5 | must-do | A (half-life is domain policy) | show freshness at use |
| I-7 supersession is a link, never an edit | §0.5 | prohibition + must-do | K? | record supersession as a link |
| I-8 no second maintaining population | §0.5 | prohibition | A (an organisational rule) | — |
| I-9 quality evidenced, never asserted | §0.5 | prohibition | K? | grade claims by recorded evidence (§1.3) |
| I-10 representation changes deliberate | §0.5 | prohibition | K? | record a representation change as an act |
| I-11(GKC) the telos | §0.5 | purpose | P | — |
| context assembly, working context, pin | §0.7 | must-do | A (the sources' "candidate kernel" lead, §0.9 Q2, is open) | assemble, pin, evaluate (§0.7) |
| usable(k, t) = current ∧ well-founded ∧ visible | §0.1 | definition | K? | compute current (§1.14.1); show both values |
| knowledge does not execute, I-11(RO) | §1.1 | definition | D (fork F11) | — |
| provenance immutable; standing by acts | §1.2 | prohibition | K? | — (A1's must-do is the evaluator) |
| N-2 no created → authoritative edge | §1.2 | prohibition | K? | — |
| three labels (provenance, grade, standing) | §1.3 | definition | K? (attribute set, F13) | — |
| closing criteria for open questions | §1.4 | must-do | A | state a closing criterion per question |
| knowledge ≠ carrier | §1.5 | definition | K? (bearer, F37) | — |
| A1 (readings named) | §1.6 | stipulation | K? (reading: DB-1) | evaluate standing from recorded acts (§1.14.1) |
| two keys (no decree of evidential progression) | §1.6 | prohibition | K? | — |
| vis(d) ⊆ ∩ vis(sᵢ), widened only by an act | §1.7 | prohibition | K? | a declassification act; a rule for k = 0 |
| the generative reading (target) | §1.8 | observation / target | O | — |
| LA-4 responsibility vs component | §1.8 | definition | D | — |
| the realization ladder | §1.9 | observation | O | — |
| identity needs governed names (I-0) | §1.10 | must-do | K? | assign a governed identity per claim |
| ownership, method/binding split | §1.11 | definition | A | — |
| a record of an act is not the act | §1.12 | definition | K? | — |
| the grant holds a reference | §1.13 | must-do | K? | register grants by reference to an act |
| current authority derived (CE) | §1.14.1 | must-do | K? (Ch. 9 §9.7) | a total evaluator of standing |
| ARCH-INV-1 records, never grants | §1.14.2 | prohibition | K? | — |
| INV-A1 … A4 | §1.14.2 | prohibition; INV-A3 must-do | K? | STOP · REPORT · ESCALATE on absence (INV-A3) |
| five roles; role laws | §1.14.3 | definition | A? (plane or context, F22) | — |
| P-8 representations gain no authority | §1.14.4 | prohibition | K? | — |
| P-E, gates follow ratification | §1.14.5 | prohibition (not adopted) | K? | name the act behind each block |
| PKS concept vocabulary | §1.15 | definition | A | — |
| atomic change; pinning; reproducibility | §1.16 | must-do | K? | record act and transitions atomically; pin references |
| who may reject; who may read (OQ-25) | §1.17 | open | K? | a read-access rule (open) |
| CON-0012 decision-readiness · CON-0013 engineer decision · CON-0014 governance decision | §1.17.1 | definition | A (0013) / K? (0014) / — (0012 INVALID) | — |
| CON-0015 freeze · CON-0023 qualification | §1.14.5, §1.14.4 | definition (INVALID) | — | — |
| CON-0016 authority pair · CON-0017 provenance · CON-0018 standing | §1.2 | definition | K? | — |
| CON-0019 governance-act progression · CON-0020 / CON-0163 evidential progression | §1.6 | definition | K? | — |
| CON-0024 mission vs constraint · CON-0025 enacted mission · CON-0027, CON-0028 identities · CON-0051 platform-cost metric | §0.2 | observation (INVALID) | O | — |
| CON-0026 authority system · CON-0044 external authority | §1.11 | definition | A | — |
| CON-0054 genesis | §1.14.1 root acts | definition (INVALID) | K? (root acts) | a recorded root act |
| CON-0076 responsibility vs component | §1.8 | definition | D | — |
| CON-0084 runtime adapter | §1.5 | definition (INVALID) | A | — |

### Ch. 6

*A mark says where a concept would sit if it holds; it is not a boundary-test result (RESEARCH-PURPOSE's relocation test has been run only for §9.6 and §9.7). **Platform engineering** is the reusable engineering kit, which the corpus also calls a "kernel" (F14 homonym); **parked** means no mark can yet be given, with the reason.*

| Concept | § | Status | Mark | Reason |
|---|---|---|---|---|
| CON-0057 kernel admission (Tier-1) | §6.1 | invalid | platform engineering | the engineering kit's admission rule (F14), not the knowledge kernel |
| CON-0072 product binding | §6.1 | validated | platform engineering | the input contract of a reusable method |
| CON-0073 method · binding · evidence | §6.1 | invalid | platform engineering | a decomposition of reusable artifacts |
| CON-0074, CON-0075 portability ladder, lattice | §6.1 | invalid, validated | platform engineering | portability of artifacts |
| CON-0085 context admission criteria | §6.2 | invalid | platform engineering | a modelling method for products' contexts |
| CON-0086 context type | §6.3 | invalid | platform engineering | the type is platform knowledge, each instance product-side |
| CON-0087 boundary through a concept | §6.3 | validated | parked | a modelling form; §6.5 C1 uses it for the record/act split, whose placement is F22 |
| CON-0088 two reuse tests | §6.3 | invalid | platform engineering | reuse of responsibilities and artifacts |
| CON-0089 core/supporting relative to a product | §6.3 | validated | platform engineering | a strategic class; it does not decide placement (§9.6, D1) |
| CON-0102, CON-0103, CON-0104, CON-0108 | §6.4 | as tagged | parked | ownership and enforceability concepts used by both sides; no boundary test run |
| CON-0132, CON-0133, CON-0134 | §6.5 | as tagged | parked | the record of authority: plane or context is F22, open on K4 (§9.6.6a) |
| CON-0139, CON-0172 linked, not copied | §6.6 | invalid as stated, provisional | application | a rule for applications that use the knowledge |
| CON-0146, CON-0176 output-only checks | §6.7 | invalid as stated, provisional | parked | a verification result; its home is Ch. 8's theory of checks |
| CON-0147 testing the direction law | §6.7 | provisional | parked | it needs the kernel/application partition it would test |

### Ch. 8

| Claim | Mark | Reason |
|---|---|---|
| §8.1–§8.5 (methods and their limits) | observation (method) | ways the corpus tests claims; not parts of KnowledgeOS |
| §8.6 C2, C3 (differential testing; independence refutable, not establishable, on the record) | kernel candidate | a property of any record the kernel keeps (§9.8 H4) |
| §8.6 C6 (automation produces evidence, not standing) | kernel candidate | an instance of A1 |
| §8.8 C1 (stored is not true; non-consultation, CON-0141) | kernel candidate | a premise of §9.8 |
| §8.9 C2 (recusal; E_ρ(o)) | application | an assurance policy of the estate; F32, F35 |
| §8.9 C7 (a shared contract changes only by its own act) | kernel candidate (Hyp) | the kernel/application boundary |
| §8.10 C2 (a true finding stays true of its time) | kernel candidate | history (§9.8) |
| §8.10 C7 (the candidate law) | open | kernel law or estate policy (§8.10 C7) |
| §8.11 C4 (a barred process should not record on the barred lane) | kernel candidate | record soundness (§9.8 H4) |
| other claims of §8.6–§8.11 | observation | instances in one estate |

## 4. The open forks

The fork table lives in `06-theory/THEORY-MAP.md` (F1 … F37, F13a; F35–F37 added at S0100 stage 2). New at the consolidation S0036–S0065: F23 (trust), F24 (conflicting claims). Sections that state a fork: §3.9 (F11), §3.10 (F8), §5.4 C8 (F15), §9.1 (F16, F17, F36), §5.5 (F13a).

## 5. The kernel's type and existence

| Part | Where | State |
|---|---|---|
| The scaffold, its degeneracy, the repair, existence by one check | §9.1 | ✓ (CON-0099, scoped) / ✗ (CON-0100 as the kernel type; its state-invariant results kept) |
| The kernel type: transition and trace invariants, input origin, guards; existence by one check only for state and transition invariants | §9.1 C6 | ✗ (CON-0100 as the type) / ~ (CON-0183) |
| Parts and whole | §9.2 | ✓ (CON-0105) |
| Preventive and generative halves | §9.3 | ✗ as stated (CON-0110), analogy kept |
| Invariants that cannot fail | §9.4 | ✗ as first stated (CON-0114, 0116); valid cores kept |
| Distribution as a candidate required capability | §9.5 (failure case of §0.7, §1.16.6) | ~ (CON-0117) |
| **Req, the kernel's required capabilities** (theory turn 1) | §9.10 | ~ (CON-0188; turn 1 settled): core questions Q1–Q4 and inputs O1–O4, Q5 by I-4, seven candidates; reference definitions of Q1, Q3, Q4 partial or absent (the documented obstacle) |
| **The kernel type from Req** (theory turn 2): laws classified; whole histories carried by the record in the state; C6 not enough for liveness, recoverability, hyperproperties | §9.11 | ~ (CON-0189; under review) |
| *Missing:* the required capabilities of KnowledgeOS itself (T9-01), **the load-bearing undefined object**: six claims defer to it (§5.5 C3, §6.5 C1, §9.3, §9.4 C7, §9.5 C1, C5); candidates in scope: distribution (§9.5), bootstrap (§9.2), the record and fold (§6.5), Zero-all(EC) reachable or Zero-blocking(EC) reachable (§5.4; which one is open); how to establish a capability (S0164 D5 E1–E10); the state space (F13, T9-02) | — | — |

## Chapters that serve method, not a deliverable directly

- **Ch. 0** foundations (§0.1–0.9) and **Ch. 8** validation (§8.1–8.11; its scope, validation or assurance governance, is decision brief DB-4): how the theory is tested. §8.5 (verification by a different method, CON-0121 ✓; calibration of grades); §8.6 independence of a check and §8.7 correction chains (T3, provisional).
- **Ch. 5 §5.1–5.3** decisions and deferral: how decisions about knowledge are prepared.
