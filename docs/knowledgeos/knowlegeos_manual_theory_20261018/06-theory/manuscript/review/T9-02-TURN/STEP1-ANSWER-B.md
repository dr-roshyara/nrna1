T9-02 STEP 1 ANSWER B

Reviewer B (kos-lane-reviewer, Sonnet). Process independence only. Topic review. Written from the allowed book sections only. I did not open 09.20, 09.00, 09.99, TODO.md or BOOK-OUTLINE. Marks: [Src] book states it; [Ded] follows from stated premises; [I] my judgment; [Hyp] hypothetical. Paths are under W/06-theory/manuscript/ unless noted.

## 1. Step 1: current position

| Item | What the book says | Where |
|---|---|---|
| T9-02 | Row text as in the brief. The test used a three-part toy state; the kernel's real attributes are not fixed (F13). Taken up later, after reading resumes: U1 decided (b), 2026-11-01. | brief; 09.19 L397 |
| F13 | Ch. 1 labels (provenance, evidential grade, standing, visibility, owner, identity I-0) and Ch. 3's six dimensions are two systems never mapped onto each other. Evidence status has 3 values in §3.1 and 4 in §1.3. Which are dimensions, components or derived? Each needs a bearer (F37). | THEORY-MAP L134 |
| Kernel type | (𝒮, X, M, G, I_S, I_T, I_R, Req). Premise: the invariants and observers' references are given independently of (M, G). Content of 𝒮, the acts and Req for KnowledgeOS stay open. | 09.01 L92–100, L99, L122 |
| Existence argument | Kernel(M) = P ∧ Req. C3: a kernel exists iff the union U of individually preserving pool mechanisms meets Req. Holds for state and transition invariants under the closed reading. Fails for trace invariants unless a history variable is empty at s₀, never shrinks, and is read and written by every act-consuming mechanism (result 5). Fails with guards (result 6). | 09.01 L33–40, L111–114 |
| 𝒮 so far | "𝒮 contains the record ρ" is the one stated content. P-loss is needed so H never shrinks. | 09.11 L73–L129 (R1 step 1; §9.11.7) |
| Laws needing 𝒮 | Law 1 (I1) "presupposes acts and authority in 𝒮 (F13)". Laws 7, 8, 9b, 16, 24 need the history. | 09.11 L33, R1 |
| Req and 𝒮 | Req's core (Q1–Q3, Q5, O1–O4) is stated over the record and reference definitions without T9-02. "The pool needs 𝒮." | 09.19 L166–167, L34 |
| Q4 and F13 | Q4's reference was absent (09.10 L75). U2 (2026-11-01): Q4's grade is g1's class, in the kernel. So Q4 no longer waits on F13. What remains of G7 is relating g1's classes to §1.3's and §3.1's statuses. | 09.19 L396; 09.18 L82 |
| T9-02 vs Req | The state space is "not a capability; it is the type's 𝒮". | 09.10 L201 |
| Candidate primitives | RESEARCH-PURPOSE lists object and identity, lifecycle, authority, evidence, relationships, policy, history. | RESEARCH-PURPOSE L28–35 |
| Constraints on this work | Kernel existence does not give primitive minimality (T9-03). Granularity is unfixed (U3 decided (b)). Mechanism-minimal and primitive-minimal differ. F37 stays a fork (DB-5). F36 and F22 are decided per law as quoted. | 09.01 L83–86; 09.19 L398; 03.01 L91 |

## 2. Steps 2–3: my candidate state spaces

**The D (domain) is the book's de facto state content** [Src]. D is well-formed records: entries of admitted kinds (root, grant, adoption, revocation, annulment, ending, consumption, transfer, removal, designation). Each entry has fields (actor, bases, scope, act time, recorder, authentication status). Citations point to earlier entries. Parameters θ are inputs. 09.13 L24–62.

| Candidate | Definition |
|---|---|
| **A: attribute table** | 𝒮_A = items → (provenance, grade, standing, visibility, owner, id, ...), stored values. This is the toy's form. |
| **B: record only** | 𝒮_B = D (records). Standing, grade and current owner are folds: CE* and g1 are functions of the record, nothing stored. |
| **C: record plus stored derived** | Like B, plus caches of the folds. CON-0141 says a stored derived value has no standing under a fixed fold. |

**Five-question answers per distinction**

| Distinction | (1) Requirement | (2) Rule or result that depends on it | (3) Fails if removed | (4) Simpler? | (5) Placement |
|---|---|---|---|---|---|
| **The record (ordered entries with kind and fields)** | Q1 at cut s, Q2 lineage, O1–O4 | H1′, I-1(GKC), O1 idempotence, U, K5; C3 result 5 and R1 | Law 9b becomes unexpressible as a T condition. Q1 at a past cut is not an observer of the current state. | No: a history variable is needed anyway. Candidate A plus a history variable is B. | Kernel [Ded, relative to the adopted laws] |
| **Standing stored beside the record** | none found | I1 (standing ⊆ acts) | I1 becomes true by construction, not a separate law | Yes: derive it (B). CE* already computes it. | Not necessary. Undecided whether the kernel caches [I]. |
| **Evidence entries and a grade** | Q4, O2 | g1 | Q4 has no reference | Grade as a fold (B) | Kernel by U2. The fold's vocabulary vs §1.3 and §3.1 is open (G7). |
| **Content-derived identity** | I-0 for immutable entries | 09.16 R2 (cited in 01.10) | Entry identity falls back to a namespace owner. | — | Kernel [Src] |
| **Policy values θ and evaluator version** | Q1 input u | H3 | Answers are not replayable | Input, not state, if H3 records it as an entry | Undecided. D does not admit the entries that set θ (09.13 L50 "Not admitted"). |
| **Order of acts** | CE* | F26 at ties: U | — | A constant of the record | Kernel, F26 settled in D |
| **Carrier or store** | designation | K2-w | — | — | Kernel by v19. Not an attribute. |
| **Read rule and visibility** | Q6, §1.7 law 13 | vis(d) ⊆ ∩ vis(sᵢ) | Deny-all meets Q6 (09.10 L94, L179) | — | Undecided. The read rule OQ-25 is open. |

**Counterexamples separating the candidates (small; none computed)**
1. **A vs B on history [Ded].** Take two histories with the same attribute table: one where act (a,1) was consumed and later lost, one where nothing happened. Under A the states are equal, so a mechanism consuming (a,1) is enabled in both. Uniqueness (law 9b) is violated. This is clear_used of 09.01 C6 result 5 (ii), read as a state-space property. A passes only with an added history component, which makes it B or C.
2. **A vs B on forgery [Ded].** A admits a mechanism that writes standing without an act. I1 catches it, so A's invariant has work to do. B has no standing field, so the I1 failure cannot be built. The risk moves to the fold. The forge of C6 result 1 stays (an act recorded by the kernel itself, with I_T's origin rule). So I1 is a consequence of the representation in B, not an independent law [Ded]. The "attribute-removal test" shows I1 depends on 𝒮's form [I].
3. **B vs C [I].** C's cache differs from B only by a consistency invariant (cache = fold). C adds one invariant and no capability. It is excluded unless performance is a requirement (not a kernel requirement in the book) [Hyp].
4. **Past cut [Ded].** Q1 at s needs prefixes of ρ. A holds none.

**Trivial cases**
- *One-point 𝒮:* every mechanism is the identity or ⊥. Req with any non-initial state fails, so there is no kernel. With Req = ∅, ∅ is the kernel and "minimal" is trivial (09.01 L31). A one-point space therefore cannot carry any Req of the book's.
- *Empty requirement set:* ∅ is the kernel (09.01 L31). So every 𝒮 is "adequate" and the question vanishes. This shows 𝒮 is determined only through Req and the laws, never alone.
- *Unchanged state (noop) and refuse:* each fails Req on all candidates (09.01 L55–66).

## 3. Step 4: outcome — **Partially resolved**

Reasons:
- **Established [Ded], relative to the adopted laws and C1 = constitute:** (i) 𝒮 must contain the ordered record (09.01 C6 result 5; 09.11 R1). The bare table A fails (counterexamples 1, 4). (ii) Standing, current authority and the grade are folds of the record, not independent state (Q1 reference CE*, Q4 reference g1, both in the kernel). A stored attribute table is therefore not necessary.
- **Not established:** (a) whether the kernel caches derived values (C vs B). (b) The vocabulary of F13: §1.3's labels against g1's classes (G7), and whether Ch. 3's six exist in 𝒮 at all. (c) Bearer, F37. (d) Policy state θ and the read rule. (e) The five kinds outside D (registration, link, retirement, establishment, declassification; 09.13 L50) are not admitted, so 𝒮 is fixed only for the admitted kinds [Src].
- **Dependence:** the answer holds only if the adopted laws hold. "Resolved" would need all kernel laws fixed (F36 is decided per law, but the laws outside D are not).
- Kernel existence and primitive minimality are not touched (T9-03).

## 4. My F13 table

| Attribute (label) | Classification [I] | Deciding lines |
|---|---|---|
| **identity (I-0)** | Record-level, content-derived; kernel. No namespace owner for immutable entries. | 01.10 I-0 scoping note; 09.13 L26 (unique ids) |
| **provenance** | Recorded fields of an entry: actor, recorder, authentication status, cited bases. Kernel, component of an entry. (Not tested whether the senses of §1.2 differ; I did not read 01.02.) | 09.13 L50 (D's fields); 01.03 |
| **evidential grade** | Derived (g1 over evidence entries); the entries are kernel. The value set is open: 3 in §3.1, 4 in §1.3, 4 classes plus U in g1. [I] g1's four classes (refuted, contested, supported, not attested) are not the same dimension as observed, inferred, hypothesized (strength of evidence vs support by recorded evidence). Not shown. | 01.03; 03.01 L19; 09.18 L82 |
| **standing** | Derived (CE*: T, F or U). Values differ from §1.3's (provisional, adopted, historical). §1.14.1-12 separates current authority from standing. Mapping open. | 01.03; 09.10 L28 (Q1 naming) |
| **visibility** | Constraint relation on derived items, vis(d) ⊆ ∩ vis(sᵢ); k = 0 undefined; aggregation not covered. Undecided: kernel or application; no read rule. | 01.07; 09.10 L94 |
| **owner** | Homonym, three senses [I]: (i) maintainer (§1.11); (ii) the grant's application owner (F22, a D field); (iii) "owned" = who may change it (Q3). The central question "owned" is read as Q3's. Only (ii) is state. | 01.11; 09.13 L38 (owner at issuance); 09.10 L36 |
| **nature** | No kernel clause in 09.01–09.19 uses it (search for the word and its siblings in those sections: a miss, not absence). Undecided, probably application [I]. | 03.01 L15; grep |
| **representation** | Property of the carrier (F37 (B)); carrier is a kernel notion only through designation. Undecided as an attribute. | 03.01 L77; 09.17 L64 |
| **governance status** | Open: mixes standing and lifecycle (CON-0003 invalid). Not in 𝒮 until F6 splits it. | 03.01 L52–58 |
| **canon scope** | Open: its sense differs from the act scope in D (homonym [I]). | 03.01 L80; 09.13 L28 |
| **evidence status** | As evidential grade (same as above, 3 vs 4 values). | 03.01 L19 |
| **operational state** | Invalid as one dimension (three senses). Binding strength has no kernel reader found. | 03.01 L20, L40–50 |

Grade, standing and operational state are not each independent state. If any of them is a fold, the "one table of attributes, value sets and kinds" in the TODO row would be a table of folds and record fields, not of stored attributes. [I]

## 5. Disproof

My answer would be refuted by:
- a kernel law that is a state invariant on 𝒮 and cannot be a fold or record clause (Candidate A would then be needed for it);
- a Req item answerable without any history (Candidate A would suffice and the record not be necessary);
- a proof that the fold CE* or g1 is not computable from the record in D;
- an adopted law that makes the cache (C) necessary, for example a performance requirement in Req;
- a counterexample to counterexample 1 under an A with a history variable that is not the record (then "record" would not be needed, only "history").

## 6. [I] judgments in this answer

- A stored attribute table is not necessary; the record is.
- Cache C is excluded unless Req adds a performance requirement.
- Ch. 3's nature, representation, governance status, canon scope and operational state are not in the kernel's 𝒮; this is a judgment from my own search, not from a definition.
- g1's classes and §1.3's grade are different dimensions.
- "Owner" is a homonym.

**Limits.** I did not compute a model. The counterexamples are small arguments, not finite checks. I read 09.01, 09.10 (parts), 09.11 (parts), 09.13 (L20–62), 09.18 (parts), 09.19 (parts) and Ch. 1 and Ch. 3 sections whole. Sections 09.02–09.09, 09.12, 09.14–09.17 I only searched. I did not read 01.02 (cited in the brief only through 01.03). I did not run any W tool.

## Files opened

Read in full: `03-methodology/REVIEWER-INSTRUCTIONS.md`; `06-theory/manuscript/review/T9-02-TURN/BRIEF-STEP1.md`; `chapter_09/09.01-kernel-scaffold.md`; `chapter_03/03.01-dimensions.md`; `chapter_01/01.03-grading-claims-evidence-strength-is.md`; `chapter_01/01.07-visibility-derivation-may-narrow-never.md`; `chapter_01/01.10-identity-of-knowledge-items-needs.md`; `chapter_01/01.11-ownership-method-binding-and-evidence.md`; `06-theory/RESEARCH-PURPOSE.md`.
Read in part (sed ranges): `chapter_09/09.10-required-capabilities.md` (L25–80, L185–215); `09.11-kernel-type-from-req.md` (L20–140); `09.13-the-evaluator.md` (L20–62); `09.18-evidence-in-the-record.md` (L60–100, L265–280); `09.19-req-and-a-when-a-minimal-kernel-is-well-posed.md` (L1–40, L105–175, L390–400); `06-theory/THEORY-MAP.md` (L134, L156 and grep lines).
Searched only: `06-theory/CONCEPT-REGISTER.tsv` (grep for F13 and attribute).

## Files copied

None.

## Commands and searches

- `ls chapter_09`, `ls chapter_01 chapter_03 | grep`, `wc -c` on chapter_09 sections 09.01–09.19.
- `grep -n -E "F13|T9-02|state space|𝒮"` over `chapter_09/09.02`–`09.19` and `chapter_01/01.03`, `01.07`, `01.10`, `01.11`.
- `grep -n -E "F13|attribute"` over CONCEPT-REGISTER.tsv, THEORY-MAP.md, RESEARCH-PURPOSE.md.
- `sed -n 134p`, `156p` and `grep -n -E "F36|F6 |F6\b|F22"` on THEORY-MAP.md.
- `sed` ranges on 09.10, 09.11, 09.13, 09.18, 09.19 as listed.
- `grep -n "9.10.5"` on 09.10.
- `grep -n -E "^\*\*D\b|domain D|..."` over 09.12, 09.13, 09.17.
- Loop of `grep -n -i -o` for "canon scope", "representation", "operational state", "governance status", "nature" over 09.01–09.19; `grep -n -E "Q6|read rule"` on 09.10.
- No git commands, no tools run, no files refused.
