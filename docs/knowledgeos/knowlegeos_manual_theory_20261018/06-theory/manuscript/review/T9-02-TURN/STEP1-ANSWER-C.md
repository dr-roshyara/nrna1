T9-02 STEP 1 ANSWER C

Independence here means the process was separate. I wrote this answer from the allowed book sections only. I had not seen any of the Professor's T9-02 material (09.20, the TODO, the index, the change log, or this review folder's other files). That is not a claim of personal independence. Labels used: Src means the book says it; Ded means it follows from stated premises; [I] marks my own judgment; Hyp is a hypothesis.

## 1. Current position (Step 1)

| Item | Where | What it says |
|---|---|---|
| T9-02 row | brief L30 | 𝒮 is "undefined". The test used a three-part toy state, and F13 is not fixed. The deliverable is "one table of attributes, value sets and kinds". Not needed for Req's core, nor for Q4 since U2. |
| The type | §9.1 C6 L92–100, L122 | The kernel candidate is the tuple (𝒮, X, M, G, I_S, I_T, I_R, Req). "Its content stays open: 𝒮, the acts, and Req" (L122). The toy state stands in for F13 and is unverified (L133). |
| Toy state | §9.1 C4 L44–54 | The state is (claims, acts, standing), with I1 standing ⊆ acts. Standing is a stored component here. |
| 𝒮 contains the record | §9.11 L73 (R1 step 1), L33, L40, L129 | "Let 𝒮 contain the record ρ". Laws 7, 8, 9b, 16 and 24 need it. Law 1 "presupposes acts and authority in 𝒮 (F13)". What is open is "the content of 𝒮 beyond 'contains the record'" (L129). |
| Not a capability | §9.10.5 L201; §9.19 L72 | The state space is the type's 𝒮. It is not absorbed into Req; it was escalated (U1). |
| Pool needs 𝒮 | §9.19.4 L166–170; T1 L238; U3 L398 | The pool needs 𝒮. Req's core is stated over the record without T9-02. Granularity is kept unfixed and results are scoped (U3 (b)). |
| Q4 | §9.19 L114, L396; §9.10 L75 | Since U2, g1's class is Q4's kernel reference, so Q4 no longer waits on F13. G7 (g1's classes against §3.1 and §1.3) stays open (§9.18 L82). |
| F13 | THEORY-MAP L134 | Ch. 1's labels and Ch. 3's six dimensions are "never mapped". Evidence status has 3 values in one place and 4 in the other. The question is which attributes are dimensions, components or derived. Each needs a bearer (F37). |
| F37 | §3.1 L60–91; MAP L156 | Bearer (A) is the instance, (B) is a bearer per dimension. It stays a fork (DB-5). |
| The record in practice | §9.13.2 L24–52; §9.18.4 L107–112; §9.17.2 | D is "a finite sequence of entries" with admitted kinds and required fields. It is now split into D_S and D_W. The record is distinct from its carrier, and the carrier is fixed by designation (K2-d). |
| What is read | §9.13 L100–110 | CE* reads entry fields and the inputs covers and actor–holder link (K3). θ is fixed to Θ_f "not read from the record". Positions are read only by D's citation rule. |
| Limits | brief L33 | 𝒮 is not to be treated as settled. Kernel existence does not give minimality (T9-03). F13, F37 and U1–U6 are not to be resolved beyond the existing decisions. |

**Dependency chain (Ded).** Req (T9-01, §9.10) fixes the observers and the inputs. The observers and guards fix what the state must determine. That fixes 𝒮 (T9-02). 𝒮 fixes the pool (T9-03 and U3), and the pool fixes the kernel's existence and minimality (§9.19 T1–T10). F13 and F37 bear on the **item view**, not on this chain's core (see §4 below).

**The trivial cases (Ded).**
- **Empty requirement set** (R = ∅, no Q): any 𝒮, including a one-point state, with M = ∅ is a kernel (§9.1 C2 L31). So 𝒮 is **underdetermined without Req**. Every state requirement comes from Req and the laws.
- **One-point state {∗}:** every observer is constant. A constant answerer fails Q1 on D, because CE* gives T on some records and F on others (§9.10.5 L174–175). It also fails Q2, and fails O1–O4 unless s₀ already meets them. So it is excluded by Req, not by P.

## 2. Candidate state spaces and their distinctions (Steps 2–3)

| Candidate | Definition |
|---|---|
| **S₁ attribute vector** | 𝒮 = ∏ over items of (attribute values). This is S0016's product form and the reading the row's deliverable assumes. |
| **S₂ record** | 𝒮 = {ρ : a finite sequence of typed entries, ρ ∈ D_S ∩ D_W}, with s₀ = ⟨⟩ (§9.11 L73 (i)). |
| **S₂′ entry set** | Finite sets of entries closed under citation, a DAG (the D_acyclic rival, §9.13 L70). |
| **S₃ record plus cache** | S₂ plus stored derived values (standing, grade). |

**Separating counterexample 1 (S₁ against S₂; Ded, not computed).**
- ρ₁ = [root r; grant g to a; adoption of c by a citing g].
- ρ₂ = ρ₁ followed by [revocation of c; grant g′ to a; adoption of c citing g′].
- Current authority at "now" is T in both, and with no evidence entries the grade is "not attested" in both. So the S₁ vectors are equal.
- Q2's reference is "the record itself, cut at s", returned in record order (§9.10 L45, L50). It differs between ρ₁ and ρ₂. Q1 at a valid time between the revocation and the re-adoption also differs (F in ρ₂).
- Uniqueness (law 9b) also needs H(ρ) (§9.11 R1).
- So S₁ fails Q2, Q1-at-t and 9b. Adding the history it lacks turns S₁ into S₂ plus a cache, which is S₃.

**Rejecting S₃ (Src and Ded).** A derived value is never stored as standing (CON-0134 as restated, §9.8 L69). A cache is also a second home that can disagree with CE*: stored T while CE* gives F. §1.10 L49–53 treats two homes as a defect.

**Separating counterexample 2 (S₂ against S₂′).**
- Take two sequences with the same entry set in different orders, so that in one of them an entry cites a later position.
- CE* values are equal, because positions are not read (§9.13 L105, check 18c). But only one sequence is in D (L56).
- So the sequence order matters for D membership and for cuts as prefixes (Q1 input s, L33), not for the values.
- The sequence form is required by the Principal's decision "keep D" (L70) and by K1's numbered-successor detectability (§9.15 L74). Req alone does not require it.

**Minimality of S₂ (Ded, from a standard result).** Treat Req's core as a machine whose input histories lead to replies and observer answers.
- The smallest state space is the set of histories modulo observational equivalence. This is the Myhill–Nerode, minimal-realization construction (standard, not new).
- Q2 returns every entry up to s in record order. So two distinct reachable records are told apart by Q2 at the full cut: no coarser state exists.
- Accepted inputs that leave the record unchanged (idempotence, O1 L109) and refusals (not a step) leave the state unchanged. So no finer state is needed.
- **Sufficiency premise (open, which I call P-par):** every observer, guard and reply is a function of (ρ, input, fixed parameters). The guards checked meet it: citation, single-use, owner, designation and removal all read ρ (§9.13 L37–48). Carrier-in-force, owner and H(ρ) are derivable from ρ.
- P-par is **not shown** for covers and the actor–holder link. K3 calls them "the relation the kernel's own steps accepted as input" (§9.15 L74). If they are accepted by steps but are not entries, they are a second state component.
- θ and the evaluator version are fixed in Θ_f now. H3 (law 11a) wants them recorded as entries.

**The five questions, per distinction**

| Distinction | Needed by | Rule depending on it | If removed | Simpler form? | Place |
|---|---|---|---|---|---|
| The record ρ, an entry sequence | Q2 (L50); laws 7, 8, 9b, 16, 24 (§9.11 R1) | CE*, g1, Lemma D, uniqueness | Q2 and 9b fail (ex. 1) | No, under Q2 as written (minimality above) | Kernel (Ded, B with one record) |
| Total order (sequence, not DAG) | D's "earlier position" (L54–56); cuts; K1 | D, the append guard | Only D membership changes (ex. 2) | Yes, D_acyclic. Rejected by decision, not by Req | Kernel by decision |
| Entry kinds and required fields | D L27–51; §9.18.4 | CE*, g1, guards | The removal models (§9.19 L209–216) | No for the kinds read; unread fields are optional | Kernel for the read fields |
| covers, actor–holder link | CE* inputs (L98) | Q1, Q3 | CE* gives U | Inputs, or entries | **Undecided** (P-par) |
| θ(u), evaluator version | H3; Q1's u | Replay | Fixed Θ_f works now | A fixed parameter | Undecided (H3 against Θ_f) |
| Carrier and designation | K2-d; §9.17 R1 | The designation guard | A byte-identical copy cannot be told apart | Root designation outside D (L11) | Boundary, not state (Ded, R1) |
| Stored standing or grade | — | — | Nothing is lost | Derived (S₃ rejected) | **Not state** |
| Two records Ρ | §9.19 L157; T9-07 (c) | T1, T4, T7, T9 scoped away from it | — | — | Undecided (an alternative B) |

**A consequence for the toy model [I].** Under S₂, C4's invariant I1 (standing ⊆ acts) cannot fail, because standing is computed and CE* gives F when there is no adoption (L129). C4's "bad" mechanism cannot even be expressed. The burden moves to the origin condition (a transition invariant on input labels) and to observer correctness. So law 1's class moves from S toward O. This is an implication, not a decision. A tombstone also changes an earlier entry in place (L62), so S₂'s mechanisms are not purely append. Law 10a, "positions only grow", still holds.

## 3. Outcome (Step 4): **Partially resolved**

**Established (Ded, under the premises stated: B with one designated record and K1, Req's core as written, decisions D and U2):**
- the kernel state must contain ρ;
- under Q2 as written, the smallest state that meets Req's core is the set of reachable records (S₂), given P-par;
- every value a core reference definition computes (current authority, standing, g1's class, carrier-in-force, owner, H(ρ)) is **derived, not a state component**;
- S₁, S₃ and the one-point state fail.

**Not established:**
- P-par for covers and the link, and θ against H3;
- the fields of O1's five kinds outside D (registration, link, retirement, establishment, declassification; U6 and T9-21). Most of F13's attributes would live there;
- several records (T9-07 (c));
- the pool and granularity (U3), so there is still no minimal kernel of KnowledgeOS;
- F37.

**What is interpretation [I]:**
- the row's deliverable, "one table of attributes", presupposes S₁;
- for the kernel, the bearer of fields is the **entry** (an act record). Items are referents of entry fields, and item attributes are views.

## 4. F13's attributes, classified

"Component" means a field of an entry in D. "Derived" means an observer's output. "Not carried" means no admitted kind holds it.

| Attribute | Classification | Deciding lines |
|---|---|---|
| provenance (§1.3) | **Component**: actor, cited bases, recorder, authentication status, recording application | §9.13 L28–51; F13a partly covered (executor = actor, authorizer through cited grant, recorder) |
| evidential grade (§1.3: observed / inferred / hyp / unknown) | **Homonym.** The "supported" sense is **derived** (g1 over D_W). §1.3's mode-of-knowing sense is the source kind of evidence content, **not carried** | §9.18 L82 ("source kind … not carried"), L192–201; §1.3 L6, L26. The 3-versus-4 mismatch is between two different properties, so G7 cannot be a plain value mapping |
| standing | **Derived** (CE*; Q1 reads it as current authority) | §9.10 L31; §9.13 L129; CON-0134 |
| visibility | **Undecided.** Not carried (declassification is outside D); Q6 is a candidate, and deny-all meets it | §9.13 L52; §9.10 L94; §9.19 L221 |
| owner | **Homonym.** Grant owner (an application) is a **component** (D L29, F22 (C + O)). The maintainer sense (§1.11) is **not carried** (registration is outside D), and judging it is application. Q1's (o1) is derived | §1.11 L33; §9.10 L99, L150 |
| identity I-0 | **Component**: entry identity is content-derived; item k is a field (`item`, `about`). Assigning governed ids is registration, outside D; the identity rule T9-18 is open | §1.10 L35; §9.10 L33; §9.19 L189 |
| nature | **Not carried**; read by no reference. Undecided until registration has a D clause | §3.1 L15; §9.19 L52 |
| representation | **Not kernel state** [I]: a property of carriers, and the kernel's own carrier is designated, not represented | §3.1 L77; §9.17 R1 |
| governance status | **Invalid as one dimension.** Its standing part is derived; its lifecycle part (retirement, supersession) is **undecided**, outside D | CON-0003; §9.19 L191, L212 |
| authority (canon) scope | **Component in form**: every kind has a `scope` field. Whether canon scope is that field's value set is undecided (a homonym risk with §2.8's scope senses) | §9.13 L28–34; §3.1 L80 |
| evidence status (§3.1) | As the evidential grade row: the class is derived; observed / inferred / hypothesized is not carried | §3.1 L19; §9.18 L82 |
| operational state | **Not kernel state.** It is a conflation of three properties: binding strength (rule content), enforcement (a realization; execution is outside the kernel), exercise history (open) | §3.1 L40–50, L78 |

**Further homonyms to report:**
- "state" has three senses: 𝒮; the "operational state" dimension; the work-state fold (§9.19 L57);
- "standing" versus "current authority" (§9.10 L31);
- "owner" and "grade", as in the table above.

## 5. Consequences (Step 5). These are implications only; nothing is closed.

- **Kernel type and existence:** 𝒮 = S₂ makes the pool a set of maps ρ ↦ ρ·e or ⊥ (G-kind is natural), but U3 keeps granularity open. T1–T10 still apply only to fixed pools.
- **F13:** it splits into three tables: (i) entry kinds × fields (largely present in §9.13.2 and §9.18.4); (ii) observers (derived); (iii) not-carried attributes, which wait on T9-21 and F37. Whether F13 should be reframed this way is the Principal's question (below).
- **Candidate primitives:**
  - history becomes the state itself, which is consistent with "derived" (§9.8);
  - lifecycle of items stays undecided (T9-21);
  - trust and visibility are not state.
- **Hyp:** every Req extension so far has been a new entry kind or field, not a new state component. A falsifier would be a timer step without input (§9.11 R3).
- **Laws:** law 1 may be reclassified S → O; the "bad" mechanism class disappears under S₂.

## 6. Disproof, and the remaining work (Step 6)

**This answer is refuted by any of:**
- (a) a core observer, guard or reply that reads something outside (ρ, input, fixed parameters);
- (b) two distinct reachable records that Q2 cannot tell apart, which would make S₂ non-minimal;
- (c) a Req item whose answer depends on unrecorded kernel state;
- (d) Q2 redefined to return sets, which coarsens the minimal state to S₂′.

**Open questions:**
- settle P-par: covers and the link as entries or as fixed inputs; θ against H3;
- D clauses for the five kinds (T9-21);
- a T9-07 (c) version for record sets Ρ;
- an exhaustive small-model check of the minimality argument. It has not been run: one finite model would only illustrate it.

**For the Principal:** should F13 be asked of the kernel's entries (fields) or of the item view (attributes and dimensions)? This decides the form of the T9-02 deliverable.

**Not attempted:** the derivation-chain commission.

## Files and commands

**Opened (read):**
- `03-methodology/REVIEWER-INSTRUCTIONS.md`; the brief;
- 09.01, 09.10, 09.19 in full; 09.13 L1–130; 09.17 L1–60; 09.18 L100–135 and L192–205; 09.11 L20–100; 09.16 L370–385; 09.15 L68–80;
- 01.03, 01.07, 01.10, 01.11, 03.01 in full;
- THEORY-MAP L125–160; RESEARCH-PURPOSE L35–80.

**Disclosure:**
- `wc -l` on the glob `chapter_09/09.0*` also counted **09.00-index.md** (a line count only; its content was not read);
- `ls` listed directory names, including 09.20, TODO.md and the review folders (names only, not opened);
- `ls tmp/tools | head -50` listed tool names; none was run.

**Copied:** nothing (the scratch directory was not used).

**Commands (all read-only, in W):**
- `ls` of chapter_09, chapter_01, chapter_03 and tmp/tools;
- `wc -l` on the allowed files;
- `grep -n` for "F13|T9-02|state space|𝒮" in THEORY-MAP, RESEARCH-PURPOSE and CONCEPT-REGISTER;
- `grep -n` for 𝒮, state space, F13 and T9-02 in 09.02–09.09 and 09.11–09.18;
- `grep` for D_W, g1, F13 and attribute in 09.18;
- `grep` for K1, K2 and R7 in 09.15 and 09.16;
- `grep` for "derived" and "Lemma D" in 09.08;
- `sed -n` ranges as listed above.

No git commands, no TODO.md, no 09.20, no other review folder.
