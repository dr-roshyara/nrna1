C109 STEP 1: BLIND INVENTORY

Window C109, sources S0393 to S0396. Transition review, step 1. Reviewer W (Opus). I read each source whole, to its last line, as it is on disk. I opened nothing else except the brief and REVIEWER-INSTRUCTIONS.md. My independence is process independence: I have not seen the Professor's draft, the book, the map or the register. It is not personal independence.

Line counts match the brief (442 / 920 / 753 / 640). All four paths are on CORPUS-ALLOWED.tsv with status "match": S0393 at row 1716, S0394 at 1717, S0395 at 1676, S0396 at 1718.

Persons are named by role. Lens names that are eponymous (Gödel, Escher, Leonardo, Turing) and AI product names (Kimi, DeepSeek, Perplexity, Claude, GPT) stay unchanged.

---

## S0393: "Reading the Lens System Through DDD Separation of Concerns" (442 lines). Depth: FULL

1. **Four-layer separation of lenses (main claim)** (l.3–5, 92–99, 353–360):
   - Observation lenses help us see the domain.
   - Adjudication lenses decide the structure.
   - The Kernel protects invariants.
   - Mechanisms are engineering, outside the Kernel.
   - "Lenses are instruments; DDD is the adjudicator; the Kernel is boring; everything else is mechanism."
2. **Kernel content (proposed)** (l.56–73): a "KnowledgeCore Admission Boundary" containing:
   - KnowledgeAggregate: Identity (invariant), Semantic Normal Form (canonical meaning), first-class Relations, JustificationPath, Context.
   - ConflictRecord: disagreement preservation, supersession tracking, resolution history.
   - Lifecycle: Admission (threshold), Revision, Supersession, Removal without deletion of history.
3. **"The Kernel is boring. It only protects invariants"** (l.75, 359): the Kernel is "the smallest possible set of invariants".
4. **Adjudication lenses** (l.41–48): DDD, Zero (absence of prerequisites, i.e. boundary-failure handling), Gödel Boundary (the system cannot certify itself, so governance is external), Contextual Completeness (no import without translation).
5. **Observation lenses** (l.13–31): 17 rows, each mapped to a DDD discovery role. Examples: Ganesha → bounded-context boundaries; Negative Epistemology → anti-corruption boundary; Gödel → the boundary between system and reality.
6. **Mechanism candidates outside the Kernel** (l.81–88): Semantic Compiler, the SNF engine, Avidya Detection, Harmonic Knowledge, the Wisdom lifecycle, Contextual Completeness mechanisms.
7. **Gap claim** (l.103–112): the lens set has no East-Asian or Chinese lenses. It is Indian, Western/formal and Semitic.
8. **Ten proposed Chinese lenses, each with a distinction** (l.118–338; summary l.432–441): Yijing (a pattern of change vs. identity preservation), Li (form carries extra-semantic meaning), Yin-Yang (complementarity vs. contradiction; resolution vs. synthesis vs. complementarity, l.188–191), Ziran (domain invariant vs. imposed structure), Gongfu (embodied vs. propositional knowledge), Ming (naming is consequential), Jing (resonance vs. representation), Shi (situational vs. universal; "situational context as an invariant", l.296), He (harmony vs. conflict detection), Wu (absence is generative, not failure; the complement of Zero).
9. **Claim** (l.375, 442): the Chinese lenses are observation instruments only and "would not change the Kernel".
10. [I] **Internal contradiction on Ziran.** Line 215 places Ziran in the *Adjudication* layer ("with DDD/Zero"). Lines 362, 375 and the box at l.392 place all the Chinese lenses in Observation.
11. [I] **Zero is classed twice.** It is an adjudication lens (l.44, 347, 400) and also sits in the Observation box (l.391). Gödel is also in both places: Gödel in Observation (l.31, 389) and "Gödel Boundary" in Adjudication (l.45). It is unclear whether these are one lens or two.
12. [I] **Lens count does not reconcile.** The text says "all 26 lenses" (l.5, 387). The tables list 17 + 4 = 21 names. Turing is named at l.108 but appears in no table. The source does not show the 26.
13. [I] **Semantic Normal Form is placed in two layers.** It sits inside KnowledgeAggregate (l.61) while the "SNF engine" is a mechanism (l.84, 349). Contextual Completeness is both an adjudication lens and a mechanism (l.46, 88). The source does not separate the value from the engine that produces it.
14. [I] **Shi is not kept to Observation.** Calling situational context "an invariant" (l.296) is a Kernel-level claim, which contradicts the "would not change the Kernel" claim (l.442).
15. [I] **Open question:** is the "26-lens" categorization pre-existing (l.428, "already visible in the existing categorization") or produced by this reading? The source asserts that it pre-exists.

## S0394: Chinese lenses, value assessment for kernel finding (920 lines). Depth: FULL

The file has two parts. Part A (l.1–599) answers S0393 in a reviewer's voice ("while Claude works", l.577). Part B (l.601–920) is a "Parallel Analysis" in a second voice.

1. **Main claim** (l.492–516, 605): the Chinese lenses "reinforce our existing findings rather than expanding the Kernel". The pattern is "Independent observation → convergent question → DDD adjudication → law", not "many philosophies → bigger Kernel".
2. **Unequal weighting** (l.7–18, 581–587):
   - Primary: Ming, Yijing, Ziran, Wu, Yin-Yang.
   - Secondary: Li, Shi.
   - Background: Gongfu, He, Jing (Jing is judged "dangerous for Kernel").
3. **Ming gives a vocabulary rule** (l.131–133): "If two uses of the same word have different lifecycle, ownership, identity, or consistency semantics, they must not silently share one domain concept." Example (l.109–117): REJECTED has 5 senses (rejected before admission, an admitted epistemic state, insufficient justification, invalid expression, governance refusal). Linked to C-1.
4. **Yijing** (l.36, 68–70): "Change of state ≠ change of identity." It is used as a falsification test ("what survived?") against C-8, C-15, supersession, revision, lifecycle and temporal determinism. Prediction is excluded as a mechanism (l.28–32).
5. **Yin-Yang** (l.141–183): "Contradiction ≠ complementarity", alongside F-CM-1, "Ambiguity ≠ Contradiction", which the current law "doesn't explicitly express" (l.149). A four-way split (l.163–171): CONFLICT, separate Knowledge, UNKNOWN, relation. Verdict: "Kernel value VERY HIGH as an adversarial lens; Kernel capability: NO."
6. **Ziran as a domain-necessity test** (l.193–215): "What invariant breaks if it can change independently?" "Show me the domain necessity for every boundary member." The example is why ConflictRecord sits inside the admission boundary (l.199).
7. **Wu is re-read as "absence without invention"** (l.221–279). "Absence of a constitutive prerequisite is not an epistemic state" (l.237). The correct result can be NOT CREATED rather than CREATED + SPECIAL_STATE (l.258–266). Linked to ZERO-DEFECT-1/2 and the rejected REJECTED overload. A dual lens: Zero asks what happens when the prerequisite is 0; Wu asks what must not be invented (l.274–277).
8. **Guard rules for each lens:**
   - Li may prompt an investigation of metadata but "cannot decide that the metadata belongs inside the Kernel. DDD decides that" (l.313–315).
   - Shi must not become a truth engine. Its question is whether one proposition in different contexts is the same Knowledge identity (l.343–347).
   - Harmony "must never become a Kernel admission criterion unless explicitly defined by domain law" (l.403).
   - Jing yields no Kernel capability (l.445).
   - Gongfu would be a scope expansion (l.375).
9. **A new adversarial test, the "Rectification + Transformation + Absence" test** (l.524–544, 895–902). It applies Ming, Yijing, Wu/Zero and Ziran to every proposed Kernel member: if a member "cannot answer all four cleanly, it is probably not yet sufficiently understood to implement". It is applied to Confidence (l.546–573), which supports the claim that "the Confidence question is still not solved" (C-11).
10. **Scope** (l.579–599, 891–906): no new Chinese research track. It is a temporary cross-validation lens set, to be applied only to C-4, C-8, C-11, C-15, C-17, C-18 and F-CM-1.
11. **Part B's final claim** (l.912–919): the convergence of traditions "is the strongest evidence" that (1) the Kernel boundary is correctly identified, (2) DDD adjudication works across cultures, and (3) the observation/mechanism separation is robust. [I] This is an overclaim. The lenses were mapped onto the existing questions by the same sessions (see the lineage note), so the convergence is constructed, not independent. Part B copies S0393's Kernel tree verbatim (l.838–853), so the claim confirms itself.
12. [I] **The parts disagree with themselves and with S0393:**
    - Li is "Medium" in the table (l.14), "MEDIUM/HIGH" in the body (l.317) and "MEDIUM" in Part B (l.707).
    - Shi is "Medium" in the table (l.15) and "MEDIUM/HIGH" in the body (l.351, 724).
    - Part B's heading for Wu is still "Generative Absence" (l.679), while its content is non-invention (l.681–688). S0393's Wu (generative) and S0394's Wu (non-creation) are close to opposites.
    - For Yin-Yang, Part A routes complementary perspectives to a *relation* (l.170). Part B says "separate identity for different perspectives" (l.653).
13. [I] **The test's name covers three of its four members.** It also includes Ziran, and the Confidence example adds DDD and Zero (l.562–568).
14. [I] **The distinctions dropped are part of the content.** Part B's adjudication box leaves out Contextual Completeness, which S0393 has (l.816–819 vs. S0393 l.46). Part A says the lenses add nothing to the Kernel, yet the Ming rule (item 3) and the NOT-CREATED distinction (item 7) are new domain rules, not only "validation".
15. **Open questions named:** C-1, C-4, C-8, C-11, C-15, C-17, C-18, F-CM-1, ZERO-DEFECT-1/2. The source does not define them.

## S0395: ADR-KOS-KERNEL-001, Kernel Scope and Boundary (proposal) (753 lines). Depth: FULL

The file has two parts: the ADR draft with commentary (l.1–576), and an answer on the "future computer brain" (l.577–753).

1. **Disambiguating "Kernel"** (l.22–33). The term had four senses: (1) KnowledgeOS, the wider ecosystem; (2) KnowledgeCore, the bounded context for admitted epistemic knowledge; (3) "Kernel" as the constitutional/invariant altitude; (4) the smallest executable or authoritative boundary protecting KnowledgeCore's invariants. Decision: the Kernel is a *proper subset* of KnowledgeOS (l.60). KnowledgeCore is the core-domain bounded context (l.66–69).
2. **Definition** (l.84–85, 553–557): the Kernel is "the smallest authoritative boundary that must exist to preserve KnowledgeCore's constitutional invariants". The one-sentence form adds the clause "while remaining incapable of becoming the semantic interpreter, truth oracle, workflow engine, or external governance system".
3. **Derivation basis** (l.87–101): the Kernel SHALL be derived from 9 listed bases (invariants, consistency, ownership, admission, identity, epistemic-state, evidence/justification preservation, lifecycle/history, deterministic guarantees). "Conceptual relatedness SHALL NOT be sufficient justification."
4. **Boundary principle (DDD)** (l.275–303): an element belongs inside "only when an invariant requires that element to participate in the boundary's consistency guarantee". The decisive question: "What invariant becomes invalid if these elements change independently?" Four non-identities: conceptual relationship ≠ aggregate ownership; traceability ≠ transactional atomicity; historical importance ≠ aggregate membership; constitutional responsibility ≠ automatic Kernel membership.
5. **Six exclusions and a capacity ceiling** (l.134–237, 377–386):
   - The six exclusions: semantic interpretation, truth determination ("not a semantic truth oracle"), mechanism intelligence (GPT, Claude, DeepSeek, Kimi, embeddings, SNF), workflow orchestration (the Kernel asks "is this transition admissible?"; workflow asks "what process next?"), infrastructure, and governance administration.
   - The ceiling: the Kernel "must be incapable of" these. A realization that "acquires semantic authority is architecturally invalid even if the implementation is technically correct."
6. **Purpose** (l.112–128): "to preserve and enforce the constitutional integrity of admitted epistemic state and its lawful transitions". The pipeline is candidate → admission boundary → authoritative state → lawful transition. The Kernel decides whether a transition "satisfies constitutional/domain requirements", not whether a proposition is true (l.166–170).
7. **Current hypothesis, PROPOSED** (l.311–328): the KnowledgeCore Admission Boundary contains KnowledgeAggregate, ConflictRecord, and a Verification Port as the "sole inbound admission gate". ConflictRecord is "a separate consistency boundary/aggregate". Its coordination with KnowledgeAggregate "must not be confused with a single aggregate boundary".
8. **Change from S0393** [I]: S0393's Kernel had Lifecycle as a sibling and no port. S0395 drops Lifecycle as a component, adds the Verification Port, and splits ConflictRecord off as its own aggregate.
9. **Capability taxonomy** (l.350–371): four classes that "SHALL NOT be collapsed": domain capabilities, boundary/invariant properties, anti-capabilities/prohibitions, and supporting mechanisms. A prohibition is not a positive capability. "All admission passes through one gate" is a boundary property. A capability "must be justified by an existing domain invariant."
10. **Brainstorming and authority** (l.392–425): "A brainstorming lens does not create domain authority." Only ratified law, constitutional decisions, governed decisions and accepted evidence can. The lens list here includes Viveka, Rta and topology (absent from S0393) and omits most of S0393's lenses. Zero is kept as a falsification lens.
11. **Unresolved questions** (l.330–340): rejection/refusal semantics; ambiguity vs. contradiction; whether Confidence is derivable; existing-aggregate operations; identity continuity; whether evidence is resolvable; authority adequacy; capability taxonomy; DEF-1.
12. **Explicitly undecided** (l.431–448): 15 items, including the command model, the exact Confidence model, the exact rejection model, and Wisdom as a KnowledgeCore domain.
13. **Sequence and status** (l.456–492, 498–512): a 12-step sequence from brainstorming to Kernel implementation, with a ruling between boundary definition and capability mapping. The Kernel is "defined as an architectural object of investigation, not yet implemented". The boundary is "PROPOSED · NON-AUTHORITATIVE". The ADR establishes "scope and vocabulary".
14. **Part 2, the computer-brain analogy** (l.578–753):
    - The KnowledgeOS Core could be the "epistemic memory and integrity substrate" or "epistemic kernel of a future cognitive system" (l.632, 684).
    - Outer components reason, perceive and plan. A claim that something "is now part of what the system knows" must cross the KnowledgeOS authority boundary (l.636–640).
    - The core preserves Identity → Evidence → Justification → Epistemic state → History → Lawful transition (l.729–741).
    - Constraint: "The possibility of becoming part of a future computer brain must never be used to enlarge the present Kernel boundary" (l.749).
15. [I] **Trivial-solution risk.** The ADR names prohibitions and a ceiling, but no positive domain capability or command ("command model" is undecided, l.439). As written, a Kernel that refuses everything satisfies §§4 and 9. Purpose (l.112) and §8 class 1 are the only must-do hooks, and they are left empty.
16. [I] **Source defects:**
    - The fences are unbalanced. The ````markdown fence opened at l.7 is closed at l.128 inside §3, and stray ``` fences follow at l.559 and l.576.
    - "The approved development sequence" (l.456) sits inside a document whose status is PROPOSED.
    - Part 2's diagram (l.587–599) shows ConflictRecord as a sibling with no Verification Port, which differs from §7.

## S0396: Kimi adversarial stress test, compared with DeepSeek and Perplexity (640 lines). Depth: FULL

1. **Roles of the three challenger reports** (l.5–9): DeepSeek = challenge; Perplexity = disciplined challenge; Kimi = adversarial stress test, aiming to falsify hidden assumptions. The source reports these reports and their content; none of them is in this window [I].
2. **Philosophical vs. constitutional boundary (main distinction)** (l.59–89). Kimi argues the candidate → knowledge boundary may be conventional, not ontological. The source answers that the constitutional boundary claims only "the boundary within which this system is authorized to make deterministic knowledge records", not "what knowledge really is". Called "one of the most important clarifications".
3. **Identity continuity is split** (l.27–43): KnowledgeId stable ≠ meaning stable ≠ epistemic status stable ≠ relationships stable. Kimi's conclusion that identity is wrong is rejected. The point "strengthens C-8".
4. **Admission ≠ epistemic finality** (l.97–144): Kimi says sufficiency-at-gate is not robust, using Mohism's three tests. The source keeps admission as "meets constitutional requirements". It allows later new evidence, contradiction, withdrawal, supersession and retraction. Attached to C-11 and C-15.
5. **Knowledge/action coupling is resisted** (l.152–208): "Kimi exposes a philosophical coupling, but not necessarily a boundary coupling." Epistemic relatedness does not require ownership by the same computational boundary. Rejected: "epistemically fatal".
6. **The AdmissionContract is normative** (l.218–252): "A deterministic contract does not become non-normative merely because it is executable." It raises the question of who may establish, change and interpret the contract (C-3, authority). Called "one of the best findings".
7. **Hidden reasoner in "conformance"** (l.262–297): if deciding that a candidate *conforms* needs judgements of relevance, sufficiency or meaning, semantic reasoning has entered the Kernel. Promoted to "an important falsification test".
8. **Representation** (l.303–339): the question becomes what information must survive normalization to preserve identity, provenance, authority and lifecycle. The source says this "remains compatible with representation agnosticism".
9. **History** (l.345–393): philosophical time ≠ audit chronology. "Event A occurred before event B" is compatible with relational meaning. Huayan must not "destabilize deterministic audit history". Ranked weaker.
10. **Minimality reframed** (l.401–426, 440–463):
    - The Kernel boundary is "explicitly a pragmatic constitutional boundary, not a metaphysical boundary".
    - "Aggregate ≠ metaphysical claim about what knowledge is"; the aggregate is "the smallest unit of constitutional accountability".
    - Do not replace the aggregate with field-based knowledge. Instead ask which invariants require entity identity.
11. **Filtering Kimi's own claims** (l.469–479): Kimi lists claims that should not influence architecture: Huayan's dissolution of boundaries, radical Daoist-classic perspectivalism, Daoist passive-system readings, a Neo-Confucian philosopher's metaphysics, and Yin-Yang cyclical state transitions.
12. **A three-agent convergence matrix** (l.487–503) and eight convergent questions (l.515–545): identity, boundary, authority, justification, lifecycle, context, reasoning, representation. "Converging on questions, not answers."
13. **Kernel defined by refusal** (l.606–628). It does NOT: interpret meaning, reason about evidence, decide truth, generate knowledge, execute action, infer authority, silently reinterpret history. It DOES: preserve identity, determinations, lifecycle, provenance and conflict; enforce constitutional invariants; maintain deterministic history. Called "a much stronger definition".
14. **Recommendations** (l.559–594, 632–638):
    - An "Evidence Convergence & Falsification Matrix" for each open C-question.
    - "No architecture change yet."
    - "Stop commissioning additional philosophical lenses."
    - Map the three reports to the C-questions, sorting each into: confirmed challenge, genuine falsification target, philosophical analogy, or unsupported architectural conclusion.
15. [I] **The matrix contradicts its own text:**
    - "Knowledge/action coupling" has ✓✓✓, "philosophically strong" (l.498), although §4 rejects it as a boundary coupling.
    - "Hidden semantic reasoner" is "strong" for Kimi (l.495), but "Kernel must perform reasoning" is only "implied" (l.501).
    - "Strength" is graded by counting agreeing LLM reports. These are correlated outputs, likely from shared prompts and material, so they are not independent evidence (see lineage).
16. [I] **The DOES list has no admission command.** It lists preservation verbs and "enforce invariants" but does not name admit or reject as a command. Compare S0395 §8 class 1. The trivial-model risk from S0395 carries over.
17. [I] **"The Chinese research"** (l.136) shows that Kimi's report used Chinese lenses (Mohism, Daoism, Huayan, ming/shi, Yin-Yang). That set differs from S0393's ten. Whether Kimi was commissioned from S0393/S0394 is unknown, because the source does not say.

---

## Cross-source table

| Topic | S0393 | S0394 | S0395 | S0396 |
|---|---|---|---|---|
| Kernel components | Admission Boundary: KnowledgeAggregate (with SNF), ConflictRecord, Lifecycle | copies S0393 (Part B) | KnowledgeAggregate, ConflictRecord (separate aggregate), Verification Port (sole gate); Lifecycle dropped | aggregate as accountability unit; capability list by refusal / DOES |
| Lens role | instruments; DDD adjudicates | the same, but weighted; cross-validation only | "a lens does not create domain authority" | stop commissioning lenses |
| Wu / Zero | Wu = generative absence | Wu = non-invention (NOT CREATED) | Zero = a falsification lens | — |
| Contradiction | Yin-Yang complementarity | Ambiguity ≠ Contradiction (F-CM-1); four-way split | "ambiguity versus contradiction" open | — |
| Identity | Identity (invariant) | change of state ≠ change of identity | identity continuity open | KnowledgeId ≠ meaning ≠ status ≠ relations |
| Admission | "Admission (threshold)" | — | single gate = boundary property | admission ≠ finality; gate sufficiency challenged |
| Normativity / authority | Gödel Boundary → external governance | Ming: naming is consequential | governance outside the Kernel; authority adequacy open | AdmissionContract normative; who sets it (C-3) |
| Must-do vs. must-never | invariants only ("boring") | — | prohibitions and ceiling; no command | DOES list, without admit/reject |
| Boundary status | "already architecturally clean" | "correctly identified" (Part B) | PROPOSED, non-authoritative | pragmatic constitutional, not metaphysical |

**Changes over time** (filename times 22:54 → 23:01 → 23:21 → 23:28 on 2026-08-23):
- The confidence in the boundary falls: S0394 says it is "correctly identified"; S0395 calls it a PROPOSED object of investigation; S0396 says no architecture change and that the evidence converges only on questions.
- The Kernel tree is revised: Lifecycle is dropped as a component, the Verification Port is added, and ConflictRecord becomes its own aggregate.
- Wu is inverted, from generative to non-inventing.

## Lineage note [I]

- S0394 answers S0393 directly ("the attached document", l.1, 20, 127, 599). Part B copies S0393's Kernel tree and layer boxes. S0394 is therefore derived from S0393, not an independent confirmation of it.
- S0394 Part A, S0395 and S0396 share one voice and one vocabulary ("I would", C-n questions, F-CM-1, "semantic reasoner/arbiter", "smallest consistency boundary"). They are one line of support.
- S0394 Part B is a second voice, but it follows Part A's verdicts, its test and its recommended actions, so it is not independent of Part A.
- S0396 reports three outside LLM challenge reports (DeepSeek, Perplexity, Kimi), none of them in this window. Their agreement is counted as "very strong" convergence; in my judgement it is at best one correlated line until their prompts and inputs are shown to be independent.
- The "cross-cultural convergence" claim (S0394 l.877, 912–919) is built by mapping lenses onto existing questions, so it confirms itself.

---

## Files opened (4 sources + 2 allowed)
1. /home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/06-theory/manuscript/review/C109-S0393-S0396/BRIEF-W-STEP1.md (whole)
2. /home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/03-methodology/REVIEWER-INSTRUCTIONS.md (whole)
3. /home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/brainstorming/kernel/20260823-225443-lens-system-read-through-ddd-separation-of-concerns.md (whole, l.1–442)
4. /home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/brainstorming/kernel/20260823-230117-chinese-philosophical-lenses-value-assessment-for-kernel-finding.md (l.1–470, then l.470–920)
5. /home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/brainstorming/kernel/20260823-232146-adr-kos-kernel-001-kernel-scope-and-boundary-proposal.md (l.1–400, then l.400–753)
6. /home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/brainstorming/kernel/20260823-232815-kimi-adversarial-stress-test-compared-with-deepseek-and-perplexity.md (l.1–330, then l.330–640)

## Commands and searches run
- One Bash call that did three things:
  - `mkdir -p` on the scratch directory /tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/16a0ac97-2df1-4d21-8fca-c43f5de5b1e2/scratchpad/c109-w/
  - `grep -n -F` on /home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv, with the four source filenames as fixed strings, one `-e` each. This was the targeted allowed-list check.
  - `wc -l` on the four source paths, each named in full.

No git command, glob, recursive search or directory listing was used. I copied no files and wrote no scratch files, so the scratch directory is empty. Nothing was refused, I edited nothing, and nothing went to an outside service. No S0155, S0156, S0157, S0161, S0170 or S0174 item came up.
