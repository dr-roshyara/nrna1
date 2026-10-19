# KnowledgeOS Theory — Chapter 2: Rules, applicability and conflict

> **Sources.**
> - **RM**: `docs/knowledge_tranfer/20260816_0914_rule_model_and_conflict_analysis.md` (AI-generated, PROPOSED; adjudicates a conversation input)
> - **GA**: the gap analysis (Chapter 1)
> - **RS**: `…/20260816_1211_rule_model_business_semantics.md`
> - **SC**: `…/20260816_1351_scope_applicability_business_semantics.md`
> - **SD**: `…/20260816_1513_sc1_dimension_preparation.md`
> - **EV**: `…/20260816_2043_environment_vocabulary_preparation.md`
> - **V3, CF, AB, IM, IA, PC, DR, DE, AG, SI**: see Chapter 1
> - All are in `docs/knowledge_tranfer/`. **The whole track lies in the exploratory Track-B lane** [RS L22].
> - Code and configuration checked: `scripts/design-rules.json`, `scripts/design-check.sh`, `.claude/scripts/db-safety-check.sh`, `config/app.php`, `phpunit.xml`, `docs/knowledge/schema/*.yaml`. *(Audit H-08.)*
>
> **Status marks** as in Chapter 0.

---

## 2.0 What counts as a rule

**Source.** **RS**: `docs/knowledge_tranfer/20260816_1211_rule_model_business_semantics.md`. Its rulings RM-1…RM-4 were made by the ARB on 2026-08-16, **inside "Track B", an exploratory lane that governs nothing** until the architecture baseline is reconstructed, verified and accepted [RS L13–22, L391–395]. In RS's own words they are "true statements about a model nobody is obliged to use" [RS L395]. This chapter therefore marks them **Src (ruled, Track B)**: a standing weaker than adopted architecture.

**Eight things are called "rule" (Src, measured, RS L30–43; RS's text says "seven" at L30 but its table and L43/L87 count eight).** In the organisation, "rule" covers:
- engineering standards;
- rulings (recorded decisions);
- architecture principles;
- design policies;
- recommendation heuristics;
- quality-gate checks;
- document-validation checks;
- tool permissions.

They differ in who may issue them, what happens when they are broken, and whether a machine or a person decides. By Decision B (different question → different concept, Ch. 1 §1.9) they are not one concept.

**The definition (Src, ruled RM-1).**

> **A rule is a standing, authoritative obligation governing behaviour within a defined scope and period. A deviation is non-conformance unless an authorized exception permits it.** [RS L74]

RM-1 keeps rules separate from:
- **decisions/rulings**: what we chose, once;
- **recommendations**: what you might do here; they bind nobody;
- **permissions/access controls**: what a tool may do [RS L65–70, L87].

⚠ **Word clash (Lookback B).** In RM-1, "standing obligation" means *continuing, not one-off*: a rule binds always, a decision once. In this manuscript, **standing** (Ch. 1 §1.2) means *what the organisation treats a claim as* (provisional, authoritative …). RM-1's "authoritative" corresponds to our standing; its "standing" corresponds to **persistence**. I keep our usage and read RM-1 that way.

**What rules constrain (Src, RS L108–117).** Four subjects: **code** (what may be written), **documents** (what must be recorded), **process** (how work must proceed), **access** (what a tool may do; by RM-1, access controls are not rules). Today a rule's subject is "inferred from where the rule lives, never from the rule itself". That is why one principle can be enforced twice by mechanisms unaware of each other. This is evidence for making *subject* an explicit part of a rule (§2.2). *Added in Lookback A.*

By this definition **only three of the eight** are rules. Two refinements in the ruled wording matter [RS L78–81]:
- *"non-conformance"* rather than *"defect"*: deviation is an **observation**, and its consequence is a separate question (§2.9);
- the exception clause makes **exception part of the concept of rule**. A deviation cannot even be classified until one knows whether an authorized exception covers it. Rule and exception cannot be modelled separately [RS L83–85].

**Classification against the theory.**
- **Refinement of the foundations.** A rule is a claim of *Constraint* nature [GKC L36]. RS sharpens this: it constrains **what may be done, not what is true** [RS L60], and it must have **standing** (Ch. 1 §1.2).
- **New:** the three-way separation rule / decision / recommendation, with its consequence table:

| | binds | ends by | broken means |
|---|---|---|---|
| rule | everyone in scope | supersession or retirement | non-conformance *(RS's table says "a violation"; changed here to RM-1's ruled term)* |
| decision | the thing decided | being implemented | — |
| recommendation | nobody | acceptance or being ignored | nothing |

**Consequence for this chapter (Ded).** Everything below concerns rules in the RM-1 sense. Recommendation heuristics, such as the running engine's five threshold rules, are **not rules**. RS notes this means RM-3's lifecycle does not automatically govern them [RS L278]. GA's observation that "the platform's rule model is a threshold matcher" (§2.1) is therefore an observation about *recommendations*: **the recommendation engine is not a rule store.** Other running mechanisms *do* hold machine-read rules in the RM-1 sense:
- the design checks, with a fixed obligation (target 0), severities and allow-lists (§2.9);
- the per-kind traceability obligations enforced by the document linter [SD L103–108].

*(Corrected after audit H-02: my earlier "no machine-read rules at all" over-generalized from the recommendation engine.)*

---

## 2.1 Why rules need structure

**The idea.** A rule written only as prose ("all APIs must use OAuth2") is useful to people and **inert for analysis** [RM L122–124, L360–362]. Every question that matters about a rule requires structure:
- does it apply here?
- is it still valid?
- who authorised it?
- does it contradict another rule?

RM calls answering these questions "a genuine Core Domain" [RM L59–63]. **Src** as a hypothesis.

**Connection to the foundations (Ded).** This is Chapter 0's core problem, *knowledge must be valid at the moment of use and visibly so* (§0.1), stated for one nature of knowledge: **constraints**. The four questions are exactly *validity at use* decomposed:
- applies here → scope of use;
- still valid → time;
- authorised → standing;
- conflicts → consistency.

So the rule model is not a side topic. It is the foundations' central property worked out for normative knowledge.

**Empirical anchor (Src, GA).** The running rule engine stores rules as threshold matchers ("if a metric exceeds a value, recommend X"). It has no subject, applicability, scope, normative effect, validity period or authority [GA L240–251]. The gap is observed in shipped code, not just argued. V3 restates it as an "observed architectural gap, not speculation" [V3 L477–490]. **Verified independently** in `scripts/observations/recommendation-rules.yaml`: its five rules carry only `id`, `description`, `source`, `op`, `threshold`, `field` and `recommend`. None of subject, applicability, scope, effect, validity or authority can be expressed.

**Two routes to one gap, and how much that proves (Src: CF L85–106; Int: the caveat).** CF tests twelve proposed architecture invariants against its folder: eight already exist, one duplicates another, one is blocked on an open question, and **two are new**: every rule has scope and applicability; every rule has an explicit normative effect. These two are exactly the open rule-model question raised from the knowledge-model side. CF calls this convergence "the strongest evidence in the corpus" for closing the gap. *Caveat (Int):* the two routes are **not shown to be independent**. Both are AI-produced on the same day from overlapping material, and the conformance input arrived as a conversation input whose sources are unrecorded. Convergence of correlated derivations is weaker than replication. The gap itself does not depend on it: it is verified directly in the YAML above.

---

## 2.2 The rule as a formal object

**The idea.** A rule has seven parts [RM L96–104]:

> Rule = Subject + Applicability predicate + Normative effect + Scope + Temporal validity + Authority + Exceptions

Applicability is "a predicate, not a label". Normative effect is one of MUST · MUST_NOT · SHOULD · SHOULD_NOT · MAY [RM L131]. A numeric **priority is deliberately excluded** (§2.5). **Src.** *(After RM-1, §2.0, MAY is not an effect of a rule: permission belongs to exceptions. MAY is kept here only as RM's original list; audit C-05.)*

> **Revision (RS, two hours later the same day).** RS lists **eight** semantic dimensions [RS L349–359]: obligation · scope · validity · **binding strength** · authority · **evidence** · exceptions · **supersession**. Compared with RM:
> - RS **adds** evidence and supersession;
> - RS **separates** binding strength out of "normative effect";
> - RS **merges** subject and applicability into "obligation" and "scope".
>
> Neither list is final; RS adopts no structure. **A union of the two lists would double-count** (audit L-24), because RS's obligation and scope absorb RM's subject and applicability. **Mapping instead:**
>
> | RM (09:14) | RS (12:11) |
> |---|---|
> | subject + normative effect | **obligation** (what it obliges, of what) |
> | applicability + scope | **scope** (where it applies) |
> | temporal validity | validity |
> | — (inside normative effect) | **binding strength** |
> | authority | authority |
> | exceptions | exceptions |
> | — | **evidence** |
> | — | **supersession** |
>
> The book keeps RM's finer split (subject, applicability, scope) inside "obligation" and "scope" where formal analysis needs it. *Classification: UPDATE.*

**Formalization (Ded).** Let *w* range over states of the world (systems, contexts) and *t* over time. A rule *r* is:

- **subject** σᵣ: what kind of thing it governs;
- **applicability** Aᵣ(w): a predicate over world states, e.g. production(API) ∧ internal(API);
- **scope** Sᵣ: the region of the organisation where it is in force;
- **validity** Tᵣ = [fromᵣ, untilᵣ): a time interval;
- **effect**: a deontic operator applied to a proposition φᵣ(w), e.g. MUST φ with φ = "authentication = OAuth2";
- **authority**: the recorded act that issued it (its *standing*, Chapter 1 §1.2);
- **exceptions**: links to recorded exception acts.

*r* **applies** at (w, t) iff Aᵣ(w) ∧ w ∈ Sᵣ ∧ t ∈ Tᵣ.

This is RM's "three-way intersection: applicability ∩ scope ∩ temporal" [RM L240] stated as a single applicability condition.

> **Revised 2026-10-18 (pilot file 5, §2.8).** This two-valued "applies iff …" holds only when every relevant scope dimension is *specified*. Under the ARB ruling SC-2, applicability is **three-valued** (applies / does not apply / cannot determine). §2.8 gives the corrected definition; §2.3's conflict test inherits it.

**A candidate contract form tested against the theory (Src, PROPOSED; CF L210–230).** CF adopts as "candidate form" a rule record with blocks for id, subject, applicability, effect, authority, lifecycle, validity and evidence. It says four blocks exist and "the delta is three fields"; a note added the same day enlarges the delta to four elements plus a validity interval, citing RM. Two blocks fail against Chapter 1 (**Ded**):
- `authority: { owner: … }` names an **owner**. Ownership (who must maintain) is not authority (which act adopted it, under which grant; glossary). This is a level error of the kind in Ch. 0 §0.2.
- `lifecycle: { state: approved }` stores a state. By STATE ≠ AUTHORITY (Ch. 1 §1.14.1), "approved" does not establish current authority; that must be derived from act, grant validity and supersession.

The form predates V3 REV 1, which explains the gap. The element list of this section (ten parts, with authority = the issuing act) already avoids both errors. *Classification: EVIDENCE that the authority theory is needed to read rule contracts correctly.*

---

## 2.3 What a conflict is

**The idea.** Two rules conflict iff there is a possible state of the world where **both apply and their effects cannot both be satisfied** [RM L149]. Worked example [RM L154–162]:
- R1: production APIs must use OAuth2;
- R2: internal production APIs must not.

The world "production and internal" makes both apply, and no assignment satisfies both. **Src**, adopted by RM as *the* definition of conflict.

RM credits the definition with two properties: it is *decidable* and it is *explanatory*. Its output is the **region** where the contradiction holds, not a yes/no [RM L165–168].

**Assessment (Ded): correct, with three refinements.**

**(a) "Decidable" holds only for a restricted language.** Whether ∃(w, t) with both rules applying and their effects jointly unsatisfiable is a satisfiability question. It is decidable when applicability and effects are written in a decidable logic, e.g.:
- propositional formulas over finitely many attributes;
- linear arithmetic over thresholds.

It is **not decidable in general**. Precisely (refined after audit L-10): first-order logic over a *fixed finite* attribute domain is decidable; the boundary is unbounded domains, and quantified or non-linear arithmetic. The theory must therefore *restrict* the applicability language, or accept that some comparisons end in UNKNOWN. RM's UNKNOWN value [RM L191] is not an optional nicety. It is **logically necessary** whenever the language is expressive or the solver is bounded (even decidable fragments can time out).

*Note on RM's word:* RM's "decidable" [RM L167] means **determinate**, "the answer does not depend on who asks", not algorithmically decidable. My point (a) addresses a different notion. Both hold: a conflict, when found, is determinate; finding it may not be computable.

**(b) "Cannot both be satisfied" covers only part of deontic conflict.** In standard deontic logic:
- MUST φ = O(φ);
- MUST_NOT φ = O(¬φ);
- MAY φ = P(φ) = ¬O(¬φ).

Then:

| Pair | Conflict? | Why |
|---|---|---|
| MUST φ vs MUST_NOT φ | **yes** | O(φ) ∧ O(¬φ) has no compliant state. This is RM's case |
| **MAY φ vs MUST_NOT φ** | **yes, but not by "satisfaction"** | P(φ) ∧ O(¬φ) is *logically inconsistent*: one rule permits what the other forbids. Yet no *action* violates both, because a permission is not "satisfied" by acting. RM's definition **misses this class** |
| SHOULD φ vs MUST_NOT φ | **not a logical conflict** | SHOULD is defeasible. How strong effects dominate weak ones is a **governance policy, not logic**, as RM itself says [RM L137, OQ-13] |
| MUST φ vs MUST φ′ | conflict only if φ ∧ φ′ is unsatisfiable on the intersection | e.g. "use TLS 1.3" vs "use TLS 1.2 only" |

**Refined definition (Ded):**

> r₁ and r₂ **conflict** iff there exist (w, t) where both apply and **either** their hard effects (MUST, MUST_NOT) cannot be jointly satisfied, **or** one permits (MAY) what the other forbids (MUST_NOT) or demands the negation of (MUST ¬φ).

> **Revised after RS (§2.0, §2.11).** Under RM-1 a *rule* is an obligation. The deontic role of **permission against a rule** belongs to the **exception**, a separately authorized deviation (RM-4). So the "MAY vs MUST_NOT" case is one of two things:
> - an **exception**, if the permitting statement is a recorded, authorized exception whose scope lies within the rule's scope and whose validity is current;
> - otherwise an **unauthorised permission that contradicts a rule**, which *is* a conflict to report.
>
> This turns my deontic refinement into a precise test: **a permission overlapping a prohibition is legitimate iff it is an authorized exception.** *Classification of the change: REVISION of my own earlier text.*
>
> **Built into the test (audit L-07).** In standard deontic logic, P(φ) ∧ O(¬φ) is inconsistent whether or not P comes from an authorized exception. The "legitimate" reading needs the **defeasible** model, in which exceptions **narrow the rule**. The conflict test is run on the rule's **effective applicability**:
>
> A′ᵣ(w, t) = Aᵣ(w) ∧ w ∈ Sᵣ ∧ t ∈ Tᵣ ∧ ¬(E₁(w, t) ∨ … ∨ Eₖ(w, t))
>
> Here E₁…Eₖ are the regions of the rule's **current, authorized, scope-contained** exceptions. Two consequences:
> - a valid exception removes its region from the rule, so no conflict arises there;
> - a permission that is *not* such an exception does not narrow anything and is reported as its own finding class, **"unauthorised permission against a rule"**, separate from rule-vs-rule conflicts.
>
> **Final definition (supersedes the refined definition above):** r₁ and r₂ **conflict** iff there exist (w, t) in A′₁ ∩ A′₂ where their **mandatory** effects cannot be jointly satisfied.

> Pairs involving SHOULD / SHOULD_NOT are **tensions**, resolved by a stated dominance policy, never reported as proven conflicts.

**(c) The region is the result.** I agree with RM: the useful output is the set {(w, t) : both apply ∧ the effects clash}, the "applicable intersection" [RM L225]. A conflict therefore has a **place and a time span**. Two rules can conflict only during the overlap of their validity intervals [RM L245–248].

---

## 2.4 Most apparent contradictions are not conflicts

**The idea.** RM's taxonomy distinguishes [RM L178–191]:
- consistent;
- specializes (narrower domain, same direction);
- refines (tightens without contradicting);
- duplicates;
- overlaps (not yet compared);
- conflicts (proven);
- supersedes;
- exception to;
- and **UNKNOWN**: not analysed is not consistent.

Two worked cases show that specialization and refinement are *not* conflicts [RM L199–205]. RM's practical point: an engine that flags them is "switched off within a month" [RM L207]. Hence **CONFLICTS is the narrowest verdict; the burden of proof lies on the analyser** [RM L209]. **Src.**

**Correction implied by the theory (Ded), and made by the sources.** Two of RM's nine values, SUPERSEDES and EXCEPTION_TO, describe **recorded acts** ("by a recorded act", "under recorded authority" [RM L185–186]). By the authority theorem of Chapter 1 §1.6, a mechanism can never establish a standing relation. So an analyser **cannot output** these two values; it can only *observe* that one rule's domain lies inside another's with an opposite effect, and report OVERLAPS or CONFLICTS.

The later aggregate-boundaries document made exactly this correction. It moved SUPERSEDES and EXCEPTION_TO from the analyser's vocabulary into a separate vocabulary of human **dispositions** (background note KT-8, from `..._0922` §7, read earlier). **The theory of Chapter 1 implies the correction.** *Review B correction:* this is **consistency, not a successful prediction**. I knew of the later correction (KT-8) before writing Chapter 1, so it cannot count as a test the theory passed.

**A third vocabulary (Src, ARB REV 1; V3 L519–525).** The ARB set out its own rule-to-rule relationship vocabulary: DISJOINT · OVERLAPPING · CONDITIONAL · CONFLICTING · REDUNDANT · SUPERSEDING. V3 itself records this as a **collision** with the nine-value set above and the disposition set, and leaves it unresolved. **Classification: CONTRADICTION (vocabulary).**

*Analysis (Ded):*
- **DISJOINT is finer than CONSISTENT.** Disjoint rules cannot both apply; consistent rules may both apply without conflict. So DISJOINT ⊂ CONSISTENT.
- **REDUNDANT ≈ DUPLICATES.**
- **SUPERSEDING describes an act**, so it is a disposition, not a finding. It repeats the error corrected above.
- **CONDITIONAL is undefined in the source.** Possibly "conflict only under further conditions", which the region-valued result of §2.3(c) already expresses.

A reconciled analyser vocabulary would be: disjoint · consistent (overlapping, no clash) · specializes · refines · duplicates · conflicts (with region) · unknown. **Proposal (Ded), not ruled.**

**A fourth, earlier vocabulary (Src, PROPOSED; CF L236–242).** CF's outcome set is NO CONFLICT · EXCEPTION RELATIONSHIP REQUIRED · REQUIRES REVIEW, "never a bare CONFLICT", because a general rule plus a scoped exception is a legitimate shape. Its own note says RM's nine-value set replaced it the same day. *Analysis (Ded):* EXCEPTION RELATIONSHIP REQUIRED joins a **finding** (mandatory effects clash on region R) to a **remedy** (an exception act should be recorded). It is the same mixing of finding and disposition as SUPERSEDING. In the reconciled form it is "conflicts (region R)" plus a recommendation; whether an exception is granted is a disposition. *Classification: REPEATED pattern; adds no new value.*

**Rule parts (V3 L495–507).** The ARB's semantic model adds **identity** and **relationships** to the parts in §2.2. Identity is required by I-0 (Ch. 1 §1.10). *Classification: REFINEMENT.*

**Definition is not evaluation (Src, RM L83–86).** The analyser may *evaluate* rules but may never *define* what a valid rule is. "A conflict engine that also decides rule well-formedness has authored its own criteria." Rule definition (what a rule is) and rule evaluation (how rules relate) need separate owners. This is the analysis-level form of "criteria are used here, owned elsewhere". *Added in Review A; omitted at first writing.*

**Development (Ded): two vocabularies, two kinds of label.** Combined with Chapter 1 §1.3:

| Vocabulary | Kind of label | Who may assign |
|---|---|---|
| **Analyser findings**: consistent · specializes · refines · duplicates · overlaps · conflicts · unknown | evidential grade about a pair of rules | a mechanism |
| **Dispositions**: confirmed conflict · not a conflict · A supersedes B · exception granted · refined · accepted risk · … | standing | only a recorded human act |

**Two consequences, from the source of the split (Src: AB L86, L125–136, L189–193; REPEATED pattern).**
- A conflict **report** is a **projection** (a derived view), so it "may never be cited as authority for anything". This is P-8 (Ch. 1 §1.14.4) applied to analysis output.
- "A rule should not decide whether another rule conflicts with it." Conflict is a relation **between** rules, so it cannot be owned by either rule. A rule that computes its own conflicts would both hold an invariant and judge it, which is the "definition is not evaluation" separation above, and the legislator ≠ evaluator law (Ch. 1 §1.14.5).
- *Supporting sign (Src, AB L136):* "accepted risk" has **no analyser counterpart**. A label that only an act can produce is evidence that the two vocabularies really are of different kinds.

**A conflict that is real: two closed vocabularies (S0025; revision RC-66).** The bootstrap experiment met two verdict vocabularies, each declared **closed**: ES-003.1 has `PASS · PASS AFTER CORRECTION · WARN · FAIL`; CAP-001 §5 has `PASS · FAIL · WARN · INCONCLUSIVE` and forbids emitting PASS AFTER CORRECTION. "Nothing scopes them"; the session "picked one" [S0025 L72]. The experiment's diagnosis: "two sets both declared closed, differing in membership, nothing scoping them, is a platform defect" [L143; E-S0025 Q19] `[CON-0062 · invalid]` (partly valid; review C4 F-06).
- **Int (corrected after review C4 F-06; RC-66a): a candidate conflict, limited to one region.**
  - **Standing (§2.0):** ES-003 is among the proposed standards (Ch. 1, RC-34, T1-47) and CAP-001's authorization is unrecorded (Ch. 1 §1.13), so whether both "bind" is itself open.
  - **Region:** the sets share PASS, WARN and FAIL, so both can be obeyed at once except where a case calls for INCONCLUSIVE or PASS AFTER CORRECTION.
  - **A possible scope by actor:** Ch. 1 §1.9 records PASS AFTER CORRECTION as reserved to **human** review acts. CAP-001 forbidding a **mechanism** to emit it may be a scope by actor, not a collision. "Nothing scopes them" is the session's claim.
  - If none of these dissolves it, the remainder is a genuine conflict in the sense of §2.3 and must be reported unresolved (§2.5); the session's "picked one" would then be the silent resolution §2.5 rejects.

---

## 2.5 Why priority numbers are rejected

**The idea.** Resolving conflicts by numeric priority ("priority 10 beats priority 5") is rejected because it **hides the contradiction** [RM L215–219]. Resolution instead weighs authority, scope, specificity, time, exception status and explicit supersession. It reports the conflict *unresolved* until a person decides. An unresolved conflict is not permission to proceed [RM L221–231]. **Src.**

**Verification (Ded; re-derived after audit L-22, H-05).** My earlier argument ("priority violates I-4, so this is a theorem") was too quick:
- I-4 says resolve *at consumption*, and a priority applied *visibly at use* does not break that;
- §2.10 itself reads I-4/PK-P7 as requiring "explicit rank".

The valid grounds are two:
1. **A number assigned by a rule's author is not an act of the authority that would have to rule** on the conflict (Ch. 1 §1.6).
2. **Independent obligations are not alternatives** (§2.8.4a): ranking them picks a winner where both were meant to bind.

**Rank as annotation vs rank as resolution.** A visible rank recorded *as information* ("standard X normally prevails over local rule Y") is allowed. An automatic resolution of independent obligations by number, at rest, is not.

**Ded** under premises 1 and 2, not a theorem of the foundations alone.

**A constitutional override (S0021; revision RC-47).** The Relationship Ontology lists among its invariants "A constitutional principle is void-overriding" (I-10), traced to two documents independently (`Round39-MC`, `Round47-00`) [S0021 L114; E-S0021 Q19]. The file does not say what "void-overriding" means; its meaning is given by a later source (below) `[CON-0040 · invalid]` (partly valid: the meaning holds for one constitution and its own artifacts; the region reading is an interpretation; review C6).
- **Int:** if it means that a constitutional principle **voids** a conflicting lower rule, it is a resolution **by the authority of the issuing act**, not by a number. It would then fit ground 1 above and does not reintroduce priority numbers.
- **Not yet stated:** whether a voided rule is void from the start or only from the conflict, and who declares the conflict (Ch. 1 §1.6).
- **Usage, not definition (S0022; revision RC-52).** The cross-product validation applies I-10: it "held, and strengthened" because "a regulation voids a conflicting internal policy in both new domains" [S0022 L66; E-S0022 Q9]. That supports the Int above (voiding by the authority of the issuing source). Two limits: it is a usage, not a definition (T2-08 stays open), and the overriding source in the example is an **external** regulation, which the same document says the model cannot express (Ch. 1 §1.11, RC-50).
- **The meaning, from the methodology constitution (S0034, quoting `Round39-MC`).** A lower artifact (an ADR, a specification clause, a validator rule or a baseline) "that conflicts with any constitutional principle is void to the extent of the conflict" [S0034 L70].
  - **Int: voidness is partial, and on the narrow reading bounded by the conflict region.** By §2.3, a conflict has a **region**: the states where both rules apply and their effects cannot both be met. Reading "the extent of the conflict" as that region voids the lower rule **on the region only**, and elsewhere it stands. This is an interpretation, not a deduction: "extent" is mapped onto this book's region.
    - **A wider reading exists.** In constitutional law, "to the extent of the inconsistency" is also read to cover **indirect** inconsistency, where the higher norm occupies a whole field and voidness reaches beyond the region of direct clash. The source does not say which reading it means.
    - **Where it is computable.** The region is defined for rules (§2.3). Some of the listed lower artifacts are not rules: a **baseline** is a frozen set of artifacts, and many constitutional principles have no applicability-and-effect form. For those, the region, and so the extent, is not yet defined.
    - **Scope of the source.** S0034 quotes one constitution, the methodology constitution (`Round39-MC`), about the artifacts beneath **it** (its specification, ADRs, validator and baseline). "Constitution" has five or more senses in the corpus (§1.10 of Ch. 1), and I-10 is traced to two of them. So the meaning is established for that one constitution; a general constitutional override is not.
  - **This confirms the Int above:** the override works by the authority of the constitutional source (ground 1 of §2.5), not by a number. It also fits precedence among independent obligations: only the part that cannot be jointly met is lost.
  - **Still open (TODO T2-08, narrowed):** whether "is void" applies from the start (ab initio) or only from when the conflict is declared; and **who declares** the conflict (Ch. 1 §1.6). The quotation comes through S0034; `Round39-MC` itself has not been read.

---

## 2.6 Scale: partitioning must be sound

**The idea.** Comparing every pair of n rules costs O(n²), about 50 million comparisons at 10,000 rules. RM proposes to **partition first** (by subject, scope, domain, technology) and compare only within partitions that can overlap [RM L259–267]. **Src.**

**Development (Ded).** Partitioning is safe only if it is **sound**: whenever two rules *could* both apply to some (w, t), they must land in a common partition. If partition keys are coarser or differently organised than applicability, conflicts across partitions are silently missed. That would turn "no conflict found" into a false "consistent", the error RM itself forbids [RM L191].

Requirement (corrected after audit L-11). Being "a function of applicability" is **neither sufficient nor necessary**: a hash of the predicate text is such a function but separates overlapping rules. The sound condition is **over-approximation**:

> choose a projection π shared by all rules (e.g. onto subjects, or onto validity time). Each rule's key must **contain** the projection of its whole applicability region: key(r) ⊇ π(Region(r)). Every pair whose keys intersect is compared.

Then *non-empty overlap ⇒ overlapping projections ⇒ intersecting keys*, so no conflicting pair is missed. Under SC-2, a rule that is unspecified (⊥) on the projected attribute must be placed in **every** partition, or its pairs reported UNKNOWN.

---

## 2.7 Where AI fits

**The idea.** AI may **propose** possible conflicts ("candidate detection") but may never **declare** one. Only formal analysis proves, and only a human resolves [RM L296–308]. A **proven** result carries **no confidence score**: it is proved or not. Confidence belongs only to the candidate stage, because scores "hide the reasoning" [RM L310]. **Src.**

**Assessment (Ded).** This is the authority rule (Chapter 1 §1.6) applied twice:
- AI output is a candidate (provenance: generated; grade: hypothesized);
- formal analysis upgrades the *evidential grade* to proven or unknown;
- neither step touches *standing*.

The "no confidence on proofs" rule is also logically right. A sound proof's conclusion has probability 1 *given its premises*. The uncertainty that remains lies in the premises (were the rules encoded correctly?), which a confidence number on the *conclusion* would misplace.

**Refinement (Src: AB L140–148; Ch. 1 §1.16.3).** AB makes the premises explicit: a finding is a result only together with the rule versions, context snapshot, analyser version, ontology version and time interval it was computed on.

**Caveat for the candidate stage (Ded; from machine-learning practice).** The input-only account holds for a **deterministic** analyser such as a solver. An AI candidate detector is **stochastic**: the same inputs can yield different candidates on different runs. For that stage:
- the record must also include the model identifier and sampling settings; otherwise the run cannot be repeated even in principle;
- a confidence statement is legitimate there, but it should be **measured** (agreement across repeated runs, or precision against adjudicated cases), not self-reported by the model;
- a self-reported model confidence is a **claim by the producer**, and by the guard principle (§2.8.7) it is not evidence of anything.

This keeps RM's rule intact (no confidence on proofs) and says what confidence may mean where RM allows it. IM's requirement "identical inputs yield an identical result" [IM L164] must accordingly be **scoped to the formal analyser**. For an AI candidate stage it is false as stated. *(Int, general machine-learning knowledge, not verified in this project; marked in round 1.)* Deterministic decoding can make repeated runs agree, but it hides rather than removes the model's sensitivity to small input changes, and hardware-level non-determinism can remain.

---

## 2.8 Scope, silence and three-valued applicability

**Source added.** **SC**: `docs/knowledge_tranfer/20260816_1351_scope_applicability_business_semantics.md` ("Track B — exploratory, governs nothing"; it records one ARB ruling, SC-2, as APPROVED 2026-08-16).

### 2.8.1 Scope is three concepts

**The idea.** "Scope is the set of things a statement is about" [SC L79]. The word covers at least three different concepts [SC L97–103]:

| | Concept | Answers |
|---|---|---|
| **S-1** | **subject scope** | what is this statement (rule) about? |
| **S-2** | **grant scope** (SC: "authority scope"; renamed in this book, see §2.8.4a) | what may this actor act upon? |
| **S-3** | **evaluation scope** | what did a mechanism actually look at? |

The dangerous conflation is S-1 with S-3: it makes "the checker did not look there" indistinguishable from "the rule does not apply there" [SC L116]. **Src** (the split is graded *inferred* by its author [SC L328]).

**Development (Ded).** S-3 is the same idea as Chapter 1 §1.5's third kind of empty answer, "exists but the search did not reach it". The two documents reach one distinction from opposite sides:
- §1.5 from *knowledge versus carrier*;
- SC from *rule scope versus checker coverage*.

**Every report of absence must state its evaluation scope**; otherwise absence is uninterpretable.

### 2.8.2 Containment: who may authorise what

**The idea.** An authority can authorise a rule only if the rule's subject scope lies **within** the authority's grant scope. Exceptions must be narrower than their rule [SC L135–147, L208–210]:

> exception scope ⊆ rule subject scope ⊆ grant scope

SC writes the chain with strict ⊂ ("narrower") [SC L209]. This book uses ⊆, because an exception covering its rule's whole scope is unusual but not incoherent. Today none of these comparisons is possible: rules state scope in prose, and so do grants (20 of 20 grants record a scope, all as prose [AMS L102–113]) [SC L149]. **Src (inferred)**: SC itself grades the containment relation INFERRED [SC L328]. The ARB later called it a "candidate relationship" [SD L317].

**Development (Ded).** This adds conditions to Chapter 1's authority axiom. A standing change requires a recorded act (§1.2), **and** the actor's grant scope must contain the subject scope of what is changed, **and** the act must fall within the grant's validity period (endings exist, Ch. 1 §1.13.2). Without the scope condition, "a narrowly scoped grant could authorise an organisation-wide rule and nothing would notice" [SC L147].

So authority is **act × scope × time**: *who decided*, *whether they could decide this*, and *whether they still could then*. *(Time condition added after audit L-06.)*

### 2.8.3 What silence means: the SC-2 ruling

**The idea.** Two running mechanisms treat an unstated scope dimension in **opposite** ways [SC L165–174]:
- quality-gate rules: unstated means **everywhere** (silently broad);
- document placement: unstated means **no match**, falling through to an explicit PENDING (safely narrow).

The ARB ruled [SC L217–219], recorded in SC as APPROVED:

> **SC-2.** An unspecified scope dimension is **UNSPECIFIED, not "all"**. Missing scope must never silently broaden the applicability of a rule.

Explicit, governed defaults per rule type remain allowed [SC L227–228]. SC immediately names the hazard the ruling creates [SC L249–262]:

> **unknown ≠ ∅.** An implementation that reads "unspecified" as an *empty* set computes no intersection, concludes "disjoint", and reports **no conflict** where one may exist. That is a *silent* false negative, worse than a noisy false positive.

The resulting candidate invariant [SC L266–271]:

> **Unknown applicability must never be represented as non-applicability.** UNKNOWN ≠ DOES NOT APPLY; UNKNOWN ≠ EMPTY SCOPE.

**Status.** SC-2 is **Src, ruled**. Its original ruling record is not located beyond SC itself. The invariant is a **candidate**. SC-2 is "decided and not yet executable", because the set of dimensions that matter (SC-1) is unruled [SC L304–306].

**A deferred rule is silence (S0025; revision RC-67).** The bootstrap experiment needed the rule on how a second project obtains the platform. That rule (ES-005.4) is DEFERRED, "therefore SILENT", and "a deferred rule cannot be cited as authority." [S0025 L74; E-S0025 Q11] `[CON-0063 · validated]` (validated after review C4 by derivation; see below).
- **Ded (corrected after review C4 F-23; RC-67a):** by §2.11, effective = standing *adopted* ∧ t ∈ validity. A deferred rule is either not adopted, or adopted with its effect postponed; in both senses it is not currently effective, so it cannot bind or be cited as authority. *(The earlier step "under SC-2" is withdrawn: SC-2 concerns an unspecified scope dimension of a rule, not an absent or deferred rule; SEQ-0006.)*

### 2.8.4 Formalization (Ded)

> **Revised (pilot 2, file 4; §2.8.6).** The formalization below assumed every dimension is meaningful for every subject. That is false: documents have no environment. A third state, **not applicable** (governed per *subject kind*), must be added. The corrected version is in §2.8.6. The Kleene logic below is unchanged; it is now applied only over the dimensions applicable to the subject's kind.

Let the relevant scope dimensions be D₁…Dₖ, with known value sets V₁…Vₖ. A scope specification gives each dimension **either** a set Xᵢ ⊆ Vᵢ (specified, possibly ∅ or all of Vᵢ) **or** the marker ⊥ (unspecified). The three values **⊥, ∅ and Vᵢ are distinct**; this is SC-2 and the hazard together.

**Applicability of a rule r to a fully described context c** = (c₁…cₖ), per dimension:
- Xᵢ specified and cᵢ ∈ Xᵢ → **true**;
- Xᵢ specified and cᵢ ∉ Xᵢ → **false**;
- Xᵢ = ⊥ → **unknown**.

Combine across dimensions with **strong Kleene conjunction**: false dominates, then unknown, then true. Hence:

> **APPLIES** iff every relevant dimension is true;
> **DOES NOT APPLY** iff **some** specified dimension is false, *even if others are unspecified*;
> **CANNOT DETERMINE** otherwise.

**The important consequence (Ded):** one specified mismatch settles non-applicability despite missing information elsewhere. So SC-2 does **not** make every partly specified rule *indeterminate* (this book uses "indeterminate" for the three-valued case and "undecidable" only for computability; audit H-10). Only rules that match on every specified dimension and are silent on a relevant one yield CANNOT DETERMINE. This bounds the cost of the ruling.

**Semantics of ⊥ (stated after audit L-12, L-13).** "Unspecified" means: *some* value set X ∈ 𝒫(Vᵢ) is intended, but it is not known which. A comparison is **true** (or false) if it is true (or false) for **every** possible completion of the unknowns, and **unknown** otherwise (supervaluation). The rules below follow from this.

**Overlap of two rules**, per dimension:
- both specified → is Xᵢ ∩ Yᵢ ≠ ∅? (true or false);
- one specified as ∅, the other ⊥ → **false** (an empty side overlaps nothing);
- otherwise, with an unspecified side → unknown;
- combined by strong Kleene conjunction (exact for independent dimensions).

A conflict verdict (§2.3) over an *unknown* overlap is therefore **UNKNOWN, never CONSISTENT**. This is the formal reason RM's UNKNOWN value [RM L191] is required, now from scope rather than from decidability. Unknown overlap arises from (i) undecidable or unsolved effects (§2.3(a)) or (ii) unspecified dimensions.

**Containment** (§2.8.2), A ⊆ B, for regions that are products of their dimensions:
1. **Global empty case first:** if **any** Xᵢ = ∅, region A is empty, and A ⊆ B is **true**, whatever B says.
2. Otherwise, per dimension:
   - both specified → Xᵢ ⊆ Yᵢ;
   - Xᵢ specified, Yᵢ = ⊥ → unknown;
   - Xᵢ = ⊥, Yᵢ = Vᵢ → true;
   - Xᵢ = ⊥, Yᵢ ≠ Vᵢ (including ∅) → unknown;
   - both ⊥ → unknown.

   These are combined by strong Kleene conjunction.

*My earlier version stated the empty case per dimension, which gives a false result.* Counterexample: A = (∅, {a}), B = (V₁, {b}). Per dimension: true ∧ false = false. Yet A is empty, so A ⊆ B. *Corrected (audit L-12).*

**Still open (audit L-13):**
- contexts whose own values are unknown (cᵢ unknown);
- the **tree** dimension (system location, §2.8.6), where "cᵢ ∈ Xᵢ" must read "cᵢ lies in the subtree of some x ∈ Xᵢ" and containment is ancestry;
- folding the predicate Aᵣ and the validity interval Tᵣ into the same three-valued combination.

An authority check over unknown containment **must not pass** (absence is never permission).

**Corroboration.** The research verifier built for this programme composes its verdicts with the same logic: strong Kleene, "F dominates U, and U is never PASS" (background note B-12). This is **consistent with** the choice here. It is not independent confirmation: the verifier was plausibly built by the same assistant lineage *(corrected after audit E-05)*.

### 2.8.4a Two coherence findings (Review B)

1. **"Authority scope" names two different things.** In the foundations, *authority scope* is a dimension of a **claim**: where its canonicity holds (context-local, project, platform, constitutional) [GKC L39]. In SC, *authority scope* (S-2) is a property of an **actor's grant**: what they may act upon. Same words, different concepts. **The two are linked by containment** (§2.8.2): a claim's canonicity scope must lie within the authority scope of the actor whose act gave it standing. To keep them apart, this manuscript calls the first **canon scope** and the second **grant scope**.

2. **Precedence is not priority.** Document placement resolves competing matches by "first match wins" [SC L52], an ordering among rules. §2.5 rejects priority numbers. **These are compatible (Ded):**
   - placement rules are *alternatives within one decision* (where does this file go?), so exactly one must be chosen and order is a legitimate tie-break;
   - normative rules are *independent obligations*, so ordering them hides a contradiction instead of resolving it.

   The criterion is whether the rules **compete for one output** (precedence allowed) or **each bind independently** (precedence forbidden; report the conflict).

### 2.8.6 Which dimensions, and the third state "not applicable"

**Source added.** **SD**: `docs/knowledge_tranfer/20260816_1513_sc1_dimension_preparation.md` (Track B). Its ruling **SC-1** is recorded as performed by human act on 2026-08-16 20:40, "inside Track B" [SD L263–269].

**How a dimension earns its place (Src, SD L18–23).** A candidate dimension is admitted only if **(1)** some rule's behaviour already differs by it in a running mechanism **and (2)** a developer would get a wrong answer without it. "Candidates that merely sound useful are rejected."

**Classification:** NEW IDEA. It is an **empirical admission test for dimensions**, a sibling of the n ≥ 2 rule for structure (Ch. 1 §1.8) and of the foundations' parsimony principle. It is stricter than the usual modelling criterion (orthogonal, necessary, sufficient), because it demands *observed behavioural variation*.

**The ruled set (Src, ruled SC-1, Track B):** four dimensions [SD L267]:

| Dimension | Evidence it already varies behaviour |
|---|---|
| **system location**, one *hierarchical* axis (product → component) | placement rules keyed on product-specific vs cross-product; a reminder that fires only in domain-layer paths [SD L43–48] |
| **environment** | the destructive-command gate blocks unless "testing" is detected [SD L77–81] |
| **artifact kind** | traceability obligations differ per kind and are lint-enforced [SD L103–108] |
| **lifecycle phase** | a warning fires only on *frozen* documents changed without a decision record [SD L128–130] |

**Which dimensions apply to which kind of subject**, as SD gives it [SD L156–160]. *Copied with its caveats after audit R-16; my earlier "applies to" column was wrong in two cells.*

| Subject | System location | Environment | Artifact kind | Lifecycle phase |
|---|---|---|---|---|
| a governed document | ✅ | ⛔ not applicable | ✅ | ✅ |
| a running service | ✅ | ✅ | ⛔ | ⚠ in a *different sense* |
| a code component | ✅ | ✅ | ⛔ | ⚠ |

**System location also carries ownership (Src, SD L37–39).** Without it, Decision A (Ch. 1 §1.11) is unenforceable: an ownership split between platform and product cannot be applied to statements that cannot say which side they are about. Scope and ownership are linked: **the method/binding split is itself a value of the system-location dimension** (cross-product vs product-specific). *Added in the pilot-closing Lookback A.*

**Audience is excluded.** It answers "who should read this", not "where does this apply". A rule does not stop applying because a different reader looks at it [SD L140–148]. *Classification:* NEW EVIDENCE for Ch. 1 §1.6 ("relevance ≠ authority"), now as **relevance ≠ applicability**.

**Product and component are one axis (Src → Ded).** A component belongs to exactly one product, so they are granularities of one hierarchy [SD L50–59]. *Formal consequence (Ded):* the values of system location form a **tree**. A specified value v denotes the whole **subtree** below v, so "product P" covers all components of P. Containment between two location values is therefore **ancestry in the tree**, and the overlap of two values is non-empty iff one is an ancestor of the other.

**Independent check (Ind).** The governed schema directory `docs/knowledge/schema/` holds:
- 31 artifact kinds;
- 8 lifecycle statuses (draft, discovery, reviewed, approved, baseline, frozen, superseded, archived);
- 12 bounded contexts;
- 3 placement domains;
- 9 audiences;
- **no environment vocabulary at all**.

SD's measurement [SD L297–303] is **accurate**. The one dimension whose rule actually *blocks* is the one with no declared values: "three ad-hoc detections standing in for a vocabulary that was never declared" [SD L307].

**The finding that changes the formalization: dimensions are not universal (Src, SD L154–181).** A rule about documents leaves *environment* blank because documents have no environment. Under SC-2 that blank would be *unspecified*, so **every document rule would be permanently undeterminable**. SC-2 would defeat itself. The resolution, folded into the SC-1 ruling, gives three states per dimension:

| State | Meaning | Determinate? |
|---|---|---|
| **specified** | a value is given | yes |
| **unspecified** | the dimension applies to this kind of subject; no value given | **no**: CANNOT DETERMINE (SC-2) |
| **not applicable** | the dimension cannot apply to this kind of subject | **yes** |

**The loophole and how it was closed (Src, SD L324–365).** If a rule's *author* could declare "not applicable", anyone could escape CANNOT DETERMINE by assertion, and SC-2 would be reversed while looking compliant. The ruled text closes this: **"not applicable" is a governed property of the subject kind, not a declaration available to an individual author; "unspecified" must not be treated as "not applicable."** [SD L269]

**Corrected formalization (Ded; replaces §2.8.4's assumption).** Let K be the governed set of subject kinds, and let **app : K → 𝒫(D)** be the *governed* map giving, for each kind, the dimensions applicable to it. A scope statement about a subject of kind k assigns to each dimension d ∈ app(k) either a value set Xᵈ or ⊥ (unspecified). Dimensions outside app(k) are **absent from evaluation**, not unknown. Applicability is then strong-Kleene over app(k) only, exactly as in §2.8.4. Two consequences:
1. **Who controls app controls determinacy.** If app is fixed by governance, an author cannot change which dimensions count. This is the formal content of the loophole closure.
2. **Residual risk, one level up (Src, SD L369–373).** If an author may *mint a new subject kind*, they can choose its app-profile and reopen the escape. SD records this as low risk because kinds are already a governed list of 31, but the ruling does not state it. **The theory needs K itself to be governed.** **Ded: required, not optional.**
3. **Two further gaps (audit L-14; Ded):**
   - **Classification κ : subject → K must also be governed or checkable.** An author who classifies a service as a "document" drops *environment* as effectively as one who declares it not applicable. Governing K is necessary but not sufficient.
   - **K is not the list of 31 artifact kinds.** K must include running services and code components, which are not artifacts; "artifact kind" is itself a scope dimension that applies only to knowledge objects. SD's reassurance [SD L373] conflates the two lists.
4. **Open:** comparing scopes **across subject kinds** whose app-profiles differ (an exception about a service vs a rule about documents) is undefined. A rule is needed, e.g. compare on the intersection of the two profiles and mark the rest unknown.

**Challenge to one source claim (Ded): adding a dimension is not cheap under SC-2.** SD argues that adding a dimension later is cheap and removing one expensive, so one should start small [SD L210–215]. Under SC-2 the first half fails: adding dimension d to app(k) turns **every positive result**, every APPLIES and every proven overlap or conflict about kind k, into **CANNOT DETERMINE**, because no existing statement specified d. **Negative results survive**, since one false dimension still decides by strong Kleene. *My earlier "turns every existing statement … into CANNOT DETERMINE" contradicted my own §2.8.4 rule (corrected after audit L-15).* Precisely the useful results degrade, so the cost stands. Adding is cheap only if:
- (a) d is not applicable to existing kinds; or
- (b) a governed default for d is declared at the same time, which SC-2 permits as an "explicit, governed default" [SC L228].

The conclusion "start small" still holds, but for a different reason: *both* adding and removing are costly, so the set should be chosen by evidence. **Status: CHALLENGED** (source claim) / Ded (correction).

**Three lifecycle notions must not be confused (Lookback B).** "Lifecycle phase" here is a property of the **subject** (is this document frozen?). It is different from a rule's **standing** (Ch. 1 §1.2) and a rule's **validity period** (§2.11). A rule can be adopted (standing), in force (validity), and apply only to frozen documents (subject phase). Three different questions.

**Status of the containment chain (update).** The ARB refined the chain *exception ⊆ rule ⊆ authority* (§2.8.2) to "**a CANDIDATE RELATIONSHIP, semantic reasoning only** … not an adopted universal law" [SD L317]. My §2.8.2 treats it as a deduction. That stands as **Ded**; its standing as an adopted rule is **none**.

### 2.8.7 A dimension in practice: environment

**Source added.** **EV**: `docs/knowledge_tranfer/20260816_2043_environment_vocabulary_preparation.md` (Track B; a decision *preparation*: EV-1 is recommended, **no ruling is recorded in it**).

**What the evidence shows (Src, verified).** The repository declares two environments, *local* and *testing*. A third, *production*, is **never declared**: it appears only as the framework's fallback when no environment is set. No production system exists: no deployment pipeline, no infrastructure definitions, no deploy steps in CI [EV L28–56].

**Independent check (Ind):**
- `config/app.php` L53 reads `'env' => env('APP_ENV', 'production')`;
- the test-runner configuration sets `testing`;
- the one CI workflow that sets an environment sets `testing`;
- none of the six workflows contains a deploy step;
- there are no container or infrastructure files.

I did not open the local environment template (blocked as a potential credential file), so "local" rests on EV alone.

**Idea 1: silence that manufactures the most severe value (Src → Ded).** When the environment is unset, the system does not become *unspecified*. It reports itself as **production**: "a specific, consequential value that nobody chose" [EV L66–76]. In the terms of §2.8.8 this is a **distinct failure**, not one of E1–E7: an *unspecified* value (E2) **silently replaced by a specified one**. It is exactly what SC-2 forbids ("missing scope must never silently broaden applicability"), occurring live in configuration.

**Rule of thumb (Ded):** a default is legitimate only if it is **explicit and governed** (SC-2's qualification [SC L228]). A framework fallback is neither.

**Idea 2: why the blocking gate fails safe on missing information, and where it does not (Src: EV L84; Ded: the principle; corrected after audit R-17, L-17).**

**What the gate actually does.** The destructive-command gate (`.claude/scripts/db-safety-check.sh` L27) runs a case-insensitive pattern match over the **text of the command**. It looks for `APP_ENV=testing`, `--env=testing` or `DB_DATABASE…test`. It does not inspect the process environment, and one of its three patterns is a database name, not an environment. It permits on a match and blocks otherwise. EV already notes that an unset environment therefore fails safe [EV L84].

**The principle, with its correct scope:**

> **A guard should require positive evidence of the permitting condition, never the absence of the forbidding one.** This makes it robust to **missing or unknown** information, and to mislabelling as a *non-safe* value. **It is not robust to false claims of the safe value.**

This gate shows the second half. Its permitting evidence is a substring the **requester writes**:
- `php artisan migrate:fresh # --env=testing` would be permitted;
- so would a production command that sets a database name containing "test".

The permitting evidence is itself a **self-report** (Idea 3 below). A second condition is therefore needed:

> **the permitting evidence must have provenance other than the requester** (e.g. the actual process environment or connection, not the command text).

**Status: Ded**, one running case (n = 1). My earlier claim that an allow-list is "robust to mislabelled states" is **withdrawn** as stated.

**Idea 3: a system's report about itself is a claim, not a fact (Src → Ded).** "The environment a system reports is not the environment it is in" [EV L87]. This generalizes Ch. 1 §1.12 ("a record of an act is not the act"): a **self-description is a claim with provenance *self*** and must be checked like any other claim. The same script supplies a second instance. Its comments record that it once printed "BLOCKED" while exiting with a code that does **not** block, so the block was "cosmetic only" [script L31–36]. **A report of enforcement was not enforcement.** This is also RM-2's distinction (§2.9) between stated strength and actual consequence, seen in a single message.

**Idea 4: we reasoned with an environment we do not have (Src, EV L105–111).** Every worked example in the rule discussions used "production", yet no such system exists here. Illustrative examples are not evidence, and **examples can import concepts the domain does not contain**. *Methodological lesson (Int):* the theory's examples (including §2.3's OAuth2 case) are illustrations, and must never be read as evidence that the concepts they use exist in the domain.

**Idea 5: the smallest evidenced vocabulary (Src, recommendation).** EV recommends adopting *local* and *testing*, and recognising *production* "while recording that no system currently realises it" (Option C). No other environment should be added until one exists [EV L134–150, L182]. EV also states that environment is **not applicable to governed documents**, as a subject-kind property [EV L154–162]. That is the SC-1 three-state model working as intended.

**Development (Ded): adding a *value* is cheaper than adding a *dimension*, under one condition.** EV says a new environment "can be added later at the cost of one vocabulary entry" [EV L150]. Unlike adding a dimension (my challenge in §2.8.6), adding a value leaves existing statements specified and determinate, **provided statements name their values positively** ("applies in testing"). A statement written as a complement ("everywhere except testing") would **silently widen** to the new value, which is the SC-2 failure again. So EV's claim holds **if scope is written by positive enumeration over a flat dimension** (sufficient, not necessary; a governed default may *intend* widening). Two limits *(corrected after audit L-16; my earlier "iff" was too strong)*:
- **hierarchical dimensions** (system location, §2.8.6) widen by design: adding a component under product P widens every statement that names P;
- an "all" written as a symbol that resolves to the current value set widens like a complement.

These are concrete syntax requirements that the sources leave open [EV L168].

**Open (Src):**
- whether *code components* have an environment: "the code is environment-independent; its deployment is not" [EV L160]. By §2.8.6, this suggests *code* and *deployment* are **different subject kinds** (Int);
- whether environments form a hierarchy;
- whether EV-1 was ever ruled (no record found).

### 2.8.8 Revision of the Zero/Gap hypothesis (Chapter 0 §0.7)

*(Numbered §2.8.5 until pilot 2; renumbered so the sections read in order.)*

Chapter 0 hypothesized that the corpus's "Zero" and "Gap" formalize *kinds of empty answer*. Pilot files 2 and 5 now give a **concrete, distinct list** of empties that a sound theory must keep apart:

| # | "Nothing" means … | Formal | Source |
|---|---|---|---|
| E1 | the set is **known to be empty**: proven no applicability, proven disjoint | ∅ | SC |
| E2 | the set is **not known** (unspecified) | ⊥ | SC (SC-2) |
| E3 | **not looked at**: outside the evaluation scope | outside S-3 | SC §3, Ch. 1 §1.5 |
| E4 | knowledge exists but has **no carrier** | — | Ch. 1 §1.5 |
| E5 | the knowledge **does not exist** (a gap) | — | Ch. 0 §0.7 |
| E6 | **not analysable**: (a) **undecidable or unresolved** in the logic used; (b) **resource-exhausted** (time-out): the analysis gave up, so the domain may be perfectly decidable; or (c) **not readable** (see V3 addition below). Kept apart because the remedies differ: (a) needs a better model or more information, (b) needs more resources [PC L77–87] | UNKNOWN · TIMEOUT | RM, Ch. 2 §2.3(a); V3 L292; PC L77–87 |
| E7 | **not applicable**: the dimension cannot apply to this kind of subject (governed) | absent from app(k) | SD (SC-1), §2.8.6 |
| E8 | **withheld (Hyp)**: it exists, but the reader may not see it (added batch 3, file 5; marked Hyp in round 2) | outside may-read | **my deduction** from the read-access gap IA raises (IA L155–165 does not mention empties); depends on the may-read hypothesis, Ch. 1 §1.17.5 |
| E9 | *(candidate, provisional)* **invalid evidence**: evidence is present and readable, but unreliable or malformed (unlike E6(c), it can be read; it fails on validity) | Evidence ≠ ∅, Validity = false | S0721 L453 (branch BRANCH-01; Ch. 5 §5.4 C4: whether it is a kind of nothing or E2 with a recorded reason is open) `[CON-0082 · provisional]` |
| E10 | *(candidate, provisional)* **unknown dimension**: the aspect is not even in the dimension set; every other kind presupposes a fixed set of dimensions, this one does not, so it cannot be reported as a value, only found by search | d ∉ D | S0721 L294–330 (branch BRANCH-01; Ch. 5 §5.4 C4: content vs scope dimensions to be settled) `[CON-0083 · provisional]` |

*(Corrected after audit L-21.)* An **unsatisfiable need** is **not** the same as E5. Until diagnosed it is E3 ∪ E4 ∪ E5 (∪ E6, and ∪ E8 if the hypothesis holds): the knowledge may be unreached, uncarried, absent, unanalysable or withheld from this reader. Gap evidence must record which; otherwise it overstates real gaps.

*(Lookback B, batch 3 file 1: the paragraph above previously sat inside the table and split row E7 from it; moved.)*

*Added (pilot 2, file 5):* a further **failure mode**, not a kind of nothing: **E2 silently replaced by a specified value**. The framework reports "production" when no environment is set (§2.8.7). It is the opposite error to reading E2 as E1, and SC-2 forbids both.

*Added (pilot 2, file 4):* E7 is **determinate**, unlike E2. Confusing E7 with E2 either makes everything undeterminable or silently reverses SC-2. This is the sharpest case yet of two "nothings" whose confusion has opposite consequences.

*Added from V3 (batch 3, file 1):* the authority invariant INV-A3 lists **"unreadable ≠ permission"** next to "UNKNOWN ≠ permission" [V3 L292]. *Unreadable* means a record exists but cannot be interpreted. It is the record-level case of E6, so E6 is widened to "not analysable **or not readable**". INV-A3 also prescribes the **response** whenever a permission would rest on anything other than E1: **STOP · REPORT · ESCALATE**.

*Unreadable policy must fail closed (Src, observed; SI L160–167; verified `scripts/lib/config-guard.sh` L6).* The platform's own rule: "a governance gate must REFUSE to run when it cannot read its policy". It was written after a gate passed **without measuring anything** because its configuration parsed as empty [SI L164]. In this section's terms, a policy that cannot be read is E6(c), and a gate in that state may only report "cannot determine". **It must never pass.** *Limit of the cure (Ind, batch-4 review F15; read in `scripts/design-check.sh` L42–46, not executed):* the guard rejects a value that is present but not an integer. A **missing** key is filled with a hard-coded default (`// 613`, `// 150`), and `exit_on_fail` defaults to `false`. So the cure does not catch this section's named failure mode, "E2 silently replaced by a specified value" (below). "Verified L6" confirms the rule's text, not the gate's behaviour.

*A second domain, a shared premise (Src: DE L390–396).* From message delivery: "never assume that a timeout means the operation did not happen". Absence of a success response is not evidence of non-occurrence. The authority rule INV-A3 (absence is never permission) and this delivery rule **share one premise reached from two unrelated domains**: **the absence of a record is not a fact about the world.** They are not one rule: INV-A3 is a fail-closed **decision** rule (what to do), while the delivery rule is **epistemic** (what may be concluded) and prescribes no action. The premise is why E2, E3 and E6 license only "cannot determine" (licensing paragraph below). *(Corrected after batch-4 review F4, F16: the earlier text said "one epistemic rule" and "E3–E6", contradicting the licensing paragraph, which treats E4 and E5 as statements about knowledge.)*

*Also from V3 (closing, quoted by the author as the corpus's self-description; V3 L641):* "Undefined remains undefined. Absent remains absent. Candidate remains candidate." *Analysis (Ded):* these are three **no-silent-promotion** rules: E2 may not become a value; E4/E5 may not become E1; a candidate standing may not become authoritative without an act (§1.6). The first is the failure mode above; the third is ARCH-INV-1 in miniature.

Only E1 licenses "does not apply / no conflict". *(Candidates from BRANCH-01: E9 would license only "cannot determine", with the invalidating reason recorded; E10 only "no known gap", never "complete".)* E7 is also determinate, but only for the dimension concerned: it licenses "this dimension does not constrain this subject", not "no conflict" overall. E2, E3 and E6 license only "cannot determine". E4 and E5 are statements about knowledge, not about the world. E8 (Hyp) would license only "cannot determine *for this reader*"; whether even that may be said is a policy question (Ch. 1 §1.17.5). *Round 2:* E8 may not be a separate kind at all. It may be E3, with the evaluation scope narrowed by an access policy. It counts as distinct only if the reader cannot know that the scope was narrowed, which is the case where reporting it would disclose that something exists. **Open.** *(Lookback B: E7 was missing from this sentence; added.)*

*A ruled absence (Src, observed; SI L139–148, L202; batch-4 review F21).* One mechanism is deliberately **not registered**: "R-42 ruled the registry governs platform assets only … Registering it would require fabricating traceability" [SI L148]. Of nine capabilities without an identifier, two carry such a ruled exemption and seven do not [SI L202]. *Analysis (Ded):* a **ruled and recorded** absence is determinate, unlike an undeclared one. It is a variant of **E1**: the relevant set is known, and the ruling says this item is not in it. An unexplained absence stays E2 or E3. The difference is practical: a gap report must not count a ruled absence as a gap, and must not let an undeclared absence pass as ruled. Whether this is a ninth kind or only a recorded justification of E1 is **open**; I recommend the second (AI recommendation), since what the ruling adds is a reason for the empty set, not a new way of being empty.

*Applied to a pipeline (Src: IM L292–307; Ded).* IM removes "ActivationAllowed" from a rule-activation pipeline (a mechanism that emits *allowed* has granted authority) but keeps the outcome **"NoConflictDetected"**. By the licensing rule just stated, that label is ambiguous: it covers E1 (proven consistent) and also E3 and E6 (not looked at, not decidable). Only the first may count as "no conflict". IA, written six minutes later (10:29), splits it this way: the mechanism "may report PROVEN_CONSISTENT, PROVEN_CONFLICT or UNKNOWN", and UNKNOWN "never satisfies a HARD gate" [IA L121, L136]. *(Checked: the earlier note pointed to background note KT-8 only.)* **Hyp (sharpened):** the corpus's "Zero/Gap" distinctions map onto some subset of E1–E7 (and E8, if it survives as a separate kind). The corpus chapters must test which, and whether the corpus adds kinds not listed here.

---

## 2.9 How strongly a rule binds is not what enforces it

**The idea (Src, ruled RM-2 in part; RS L156–191).** The organisation uses several strength scales that cannot be compared:
- error / warning / info;
- error / warning;
- deny / ask / allow;
- none, for advisory heuristics;
- "must / should / may" in prose.

A further switch decides whether any failure actually stops work. It is currently set to *not stop*, so an "error" does not block [RS L158–166].

The ARB reframed this "defect" as **two concepts** [RS L176–185]:
- **binding strength**: how strongly the rule obliges; owned by the authority that issued it;
- **enforcement consequence**: what the mechanism does when the rule is broken *today*; owned by the mechanism; changes as enforcement matures.

> **A rule may be MANDATORY while its enforcement is ADVISORY. That is legitimate.** What is defective is having **no way to say both**. Then "we mean it but do not yet block" reads as "we do not mean it" [RS L185–187].

RM-2 approves the *need* for one common binding-strength concept and defers its vocabulary [RS L191].

**Worked example from the source (Src, RS L145–148, L189).** The quality gates schedule a **declining series of thresholds** over successive phases. Read with RM-2: the obligation is fixed (zero violations); the *enforcement threshold* moves toward it. "Two concepts, correctly separated in one mechanism, by accident."

**Independent check (Ind).** `scripts/design-rules.json` defines:
- phase targets 592 → 580 → 545 → 529 → 511 → 485 → 50 → **final target 0**;
- enforcement `exit_on_fail: false` in `mode: migration`;
- rule severities error / warning / info;
- an `allowlist_patterns` field on every rule: the unnamed exceptions of §2.11.

`scripts/design-check.sh` states: "Non-blocking during migration phase; becomes hard-blocking when violations drop below threshold." The source's description is **accurate**. The configuration also shows the obligation (target 0) and the enforcement trajectory **stored side by side but not named as different concepts**, exactly as RM-2 says.

**Connections (Ded; both corrected after audit L-09, L-08).**

1. **Realization bounds enforcement; it does not determine it.**
   - A check cannot *block* unless it exists in executable form, so **enforcement consequence ≤ realization level** (Ch. 1 §1.9).
   - But an executable check need not block. `design-check.sh` is executable, yet `exit_on_fail: false` makes it advisory. Its consequence changes by a **configuration flag, with no change of representation** ("becomes hard-blocking when violations drop below threshold").

   So enforcement consequence is a **separate, act-or-configuration-controlled property**, bounded above by representation. My earlier "enforcement consequence *is* the realization level, so no new dimension is needed" was refuted by this section's own example and is **withdrawn**. RM-2's split does need its own place in the model.

2. **Effects: obligation side only, with a partial strength scale.** After RM-1 a rule's effect is one of **oblige** (MUST, SHOULD) or **forbid** (MUST_NOT, SHOULD_NOT). Permission belongs to exceptions (§2.3 revision). Strength (mandatory vs recommended) is defined for these two polarities only, so effect is not a full product polarity × strength.
   - **Proven conflicts arise only between mandatory effects**, from the joint unsatisfiability of their propositions on the effective overlap (§2.3). This includes **same-polarity** conflicts such as "use TLS 1.3" vs "use TLS 1.2 only".
   - Recommended effects produce tensions.
   - Enforcement consequence plays no role in whether two rules conflict. A conflict between two advisory-enforced mandatory rules is still a conflict.

**Classification:** NEW IDEA (strength ≠ enforcement), integrated as a **separate property bounded by representation**.

**A further orthogonal property (Src: IM L45; REPEATED in structure).** IM separates **hardness** (HARD · SOFT · ADVISORY: "whether the business wants it to block") from **atomicity** ("when a condition can be evaluated"), warning that "conflating them is how an advisory check silently becomes a gate". *Mapping (Ded):* **hardness** is the **intended** enforcement, set by a decision; **enforcement consequence** (§2.9) is what the mechanism **actually does today**. **Atomicity** is a **required consistency guarantee**: the values used for it (ATOMICALLY_REQUIRED · SAME_CONTEXT_TRANSACTION · EVENTUALLY_CONSISTENT · ANALYTICAL; DE L68–72, L269) say how tightly the check must be bound to the change, not when the condition can be checked. IM's own gloss, "when a condition can be evaluated", suggests the other reading; the values used decide it. A condition can be checkable at any moment and still require atomic enforcement. A condition therefore has **four** properties that must not be inferred from one another:
- **binding strength**: how strongly it obliges;
- **hardness**: whether it is meant to block;
- **enforcement consequence**: whether it does block today;
- **required consistency guarantee** (atomicity): how tightly its check must be bound to the change it governs.

*(Corrected after batch-4 review F7: the fourth property was called "evaluability", which conflated it with when a check can run.)*

A gap between hardness and enforcement consequence is normal during migration ("advisory today; blocking once the gate is trusted", RS L181–183).

**Override, a fifth property defined only for blocking gates (Src, PROPOSED; PC L147–163).** Whether a block may be **lifted case by case** is not a degree of hardness. A HARD gate may allow an override and still default to refusal. But override is **not independent** of the other four: it means something only where the gate actually blocks (a blocking enforcement consequence). For an advisory gate there is nothing to lift. *(Corrected after batch-4 review F13: the earlier text called it independent of hardness while still counting four properties.)* A later source therefore adds **override: none | recorded act**, and insists that a user's on-screen confirmation is not enough: "a UI confirmation is a report", so the override must be a **recorded act** [PC L161]. *Analysis (Ded):* this follows from "a report of an act is not the act" (INV-A1, Ch. 1 §1.12) and from recognition requiring a record (I-2). Which gates allow override, and which acts qualify, is open (PC's OQ-29).

**Results that may open a gate (Src, PROPOSED; PC L77–87).** Of the analysis results proven conflict · proven consistent · unknown · not applicable · time-out, **only "proven consistent" satisfies a HARD gate**. This is the guard principle of §2.8.7 (positive evidence of the permitting condition) applied to analysis results. **Time-out is the case most tempting to treat as a pass**, which is why it is named separately.

*Two readings of "not applicable" (Ded; batch-4 review F9).* PC lists NOT_APPLICABLE as an analysis result without defining it. The name collides with the book's E7 ("the dimension cannot apply to this kind of subject"). Two readings, with opposite consequences:
- (i) **the analysis cannot be applied** to this pair (for example, the rules cannot be compared in the logic used). This is E6-like, and refusing the HARD gate is correct;
- (ii) **the two rules provably cannot meet** (their applicability sets are disjoint). This is determinate (E1), and by the licensing rule of §2.8.8 it licenses "no conflict". Under this reading, PC's "never satisfies a HARD gate" contradicts the licensing rule.

**Open.** I recommend (AI recommendation) reporting case (ii) as PROVEN_CONSISTENT, with disjointness as its reason, and keeping NOT_APPLICABLE for case (i) only. Then PC's gate rule and the licensing rule agree.

**Where a check may live (Src, PROPOSED; DE L62–74, L259–269; Ded).** A later source rules that **an event may not enforce an invariant**: "events represent facts after valid state transitions. They are not deferred validation hooks for conditions that must hold atomically" [DE L66]. Its rule EVT-1: no handler may evaluate an invariant whose required guarantee is ATOMICALLY_REQUIRED **or SAME_CONTEXT_TRANSACTION** [DE L70]. The check belongs to the aggregate, before the event exists [DE L265]. *Consequence (Ded):* **the required consistency guarantee fixes where a check may legally live.** A condition declared "always true" but checked after the change has committed is in fact only "eventually true". Its declared guarantee has been silently weakened, and nothing in a list of rules would show it. This is a gap between **declared and actual guarantee**, the same pattern as hardness vs enforcement consequence. It is **not** a change of realization level: the check stays executable; only its place moved. *Limit (Ded):* the guarantee holds only inside one transaction boundary (one aggregate, or one commit for rules kept as files, DE L235–247). For conditions spanning several aggregates, DE offers only pinning, detection or prohibition. A check repeated at the effect closes the gap only if the repetition is itself atomic with the effect. *(Corrected after batch-4 review F7: the earlier text reasoned from "evaluability", omitted SAME_CONTEXT_TRANSACTION, and read the placement as "the realization ladder read downward", which brought back the identification withdrawn above.)*

**OQ-24 made more concrete, not settled (Src: DE L133–140; Ded).** DE recasts the question as: **may a "conflict detected" finding arrive after the rule is already active?** If the check is hard, the analysis runs before activation. If it is advisory, the finding may arrive later, against an active rule. *Analysis (Ded; batch-4 review F8):* DE's recasting is **narrower** than the question, and its equivalence is unproven. Even with a hard gate, a later re-analysis (a new analyser version, a newly added rule) can raise "conflict detected" against a rule that is already active. The question the hard/advisory choice actually decides is **whether activation may come before its own analysis has completed**. What happens to an active rule when a later analysis finds a conflict is a further, separate question. Both stay open (reserved decision 5). Ch. 1 §1.14.5 adds, **conditionally on premise P-E** (not adopted), that a block needs a currently authoritative act authorizing that block for its scope, period and rule version. Whether a mandatory rule's adoption is such an act is open (OQ-24). *(Revised in correction rounds 3–4: the earlier text counted three properties and merged hardness with enforcement consequence.)*

**Deciding is not enforcing: the corpus's own placement (batch C-1; revision RC-12).** In the standard access-control architecture (OASIS XACML) policy is **administered** at one point (PAP), **decided** at another (PDP) and **enforced** at a third (PEP) [Ext: XACML 3.0; the corpus cites fetched pages, S0005 L100]. The corpus maps this as governance (PAP) → DP-n / capability (PDP) → runtime or retrieval boundary (PEP) [Src: S0005 L19; S0003 L42]. It concludes that KnowledgeOS **governs retrieval and competes with none of it**: KnowledgeOS sets policy; retrieval engines are enforcement surfaces [Src: S0003 L39; E-S0003 Q10].
- **Status:** the separation is externally standardized (**Src+Ext**).
- The **DP-n layer** between principles and capabilities is the corpus's own hypothesis with "no direct external counterpart found" [Src: S0007 L35]. TODO T2-06: DP-n is undefined until its source is read.
- **Int:** this matches this section: how strongly a rule binds (PAP/PDP side) is separate from what enforces it (PEP side).

**What a DP-n is (S0008; revision RC-13; resolves T2-06).** The corpus's meaning ledger defines a **Design Policy (`DP-n`)** as "the named policy a capability executes; the anchor between principle and capability", owned by the Decision Authority through the Capability Catalog, with a **1:1 binding** between capability and design policy [Src: S0008 L38; E-S0008 Q12]. A capability is "a protected engineering responsibility executing exactly ONE design policy; the script is its realization, never the capability" [Src: S0008 L25; E-S0008 Q8]. In this section's terms, a DP-n is the **rule with meaning** that a capability **executes**. It does not enforce it (corrected RC-13a after independent review): the corpus records that **every capability is advisory, while 41 enforced controls live runtime-side** [Src: S0008 L67; E-S0008 Q16]. The rules with meaning and the mechanisms that enforce are different sets, observed in the platform itself (see §2.10 and TODO T2-05). **Status:** **Src** (a candidate document); the layer stays a "UNIQUE HYPOTHESIS — needs operational proof" [S0007 L35].

**Where the design-policy layer was found (S0021; revision RC-46).** The Relationship Ontology is the document that **found** this layer. A reviewer had proposed that capabilities be owned by engineering domains. The author tested it against the repository:
- the Capability Catalog binds every capability to one design policy (`CAP-001→DP-1`, `CAP-002→DP-2` …) [S0021 L32]: "Capabilities are owned by design policies, not by domains — and that is observable in one table." [S0021 L44; E-S0021 Q9];
- domain ownership is rejected because "No artifact links any `CAP-n` to a `PD-n`." [S0021 L33; Q8].

The source places the layer in a chain: principle → design policy → capability (Ch. 3 §3.6). Its invariant I-2 states the binding as "A Capability protects EXACTLY ONE invariant (one `DP-n`)" [S0021 L106], which treats a design policy **as** an invariant.

**Status:** whether to adopt `DP-n` as a named layer is open ("observable in the Catalog but never declared as a concept", RO-1 [S0021 L166]). The binding does not mean the capability operates: CAP-002 is bound to DP-2 and was DEFERRED [S0020 L100]. `[CON-0034 · invalid]` (partly valid: the catalog is quoted only by example; RO-1 undecided; TODO T2-07). S0008's definition (RC-13) and S0021's finding are one line of evidence, not two.

---

## 2.10 The rules with meaning and the rules with enforcement are different sets

**Finding (Src, measured; RS L195–205, L209–220, L365–371).**
- **Governance rules** (standards, rulings, principles) carry evidence for their necessity and a recorded authority. **Standards** also declare supersession; rulings do not (see below). They are prose that no machine reads. *(Corrected after audit H-03.)*
- **Machine-enforced checks** (quality gates, recommendation thresholds) carry none of these. Anyone who can edit a configuration file can change them, without a record of who or why.

The only authority boundary enforced by machine is the reserved-verdict rule of Ch. 1 §1.9. RS's summary: "**The rules with semantics and the rules with enforcement are two different sets, and nothing connects them**" [RS L371].

**Significance (Int).** This is the single clearest *empirical* statement of the problem KnowledgeOS addresses (Ch. 0 §0.1), inside its own organisation:
- what is enforced cannot explain itself;
- what explains itself is not enforced.

In the terms of the realization ladder (Ch. 1 §1.9), the organisation's rules sit **mostly at two ends**: fully stated but unenforced, or enforced but never stated as rules. **The exceptions** *(corrected after audit H-04: "nothing in between" was too strong)*:
- the reserved-verdict rule completes all five stages (Ch. 1 §1.9);
- the quality gates are mechanized but advisory (§2.9);
- the document-validation rules are owned and lint-enforced [RS L40; SD L105].

**Prediction (Hyp, testable).** Under the foundations' trust principle (enforced knowledge is trusted most, PK-P4), the *unexplained* enforced checks will be trusted and obeyed, while the *explained* governance rules will be cited and bypassed. Behavioural evidence (violations per rule class) would test this.

**Two further violations of the foundations, measured (Ded from Src, RS L228–233):**
- **Rulings are append-only with no supersession links.** A later ruling may contradict an earlier one "and both remain in force on their face". This breaks I-4/PK-P7 (contradictions must coexist *with explicit, visible status*, rank as annotation, not automatic resolution; see §2.5) and I-7 (supersession is a link).
- **Configuration rules are edited in place, so their history is lost.** This breaks I-1 (no silent disappearance) and I-7.

**Protocol alone did not hold (S0015, S0014; revision RC-35).** A blind review turned a failed manual adoption (PMR-10) into "direct operational evidence AGAINST the Reference Architecture's stance that protocol + human authority suffice without resident mechanisms" [Src: S0015 L33; E-S0015 Q8]. The reconciliation keeps it "as evidence, not as a mandate to automate" [Src: S0014 L36]. **In this section's terms:** a rule held only by protocol (meaning without enforcement) failed in practice at least once. One case shows that protocol-only governance **can** fail; it does not show how often it fails, nor that automation is the remedy (N-10 forbids assuming so).

---

**Where enforcement actually lives (S0024; revision RC-60).** The Architecture Fitness Assessment adds a fifth dimension, **enforcement**, to its scoring of eleven platform responsibilities, and states: "MATURITY ≠ ENFORCEMENT." [S0024 L84] `[CON-0056 · validated]` (the maturity levels L0–L4 are not defined in the source; SEQ-0002). Its first finding, that no responsibility enforces and the platform is "adoptable by habit, never by mechanism" [L88], was **corrected by its author**: the runtime's configuration genuinely enforces 41 controls, so "no CAPABILITY enforces", not nothing [L72].

The corrected finding uses three kinds of control [S0024 L74–78] `[CON-0055 · invalid]` (partly valid / not verifiable: its governing vocabulary was not seen, and the counts are by mechanism while the kinds are by effect; review C4 F-07):

| Kind | Meaning | Mechanism | Count |
|---|---|---|---|
| **boundary** | "must never happen" | `permissions.deny` | 19 |
| **trigger** | ask the engineer | `permissions.ask` | 22 |
| **advisory** | mentions it and proceeds | 10 hooks + 6 scripts | 16 |

"The sharper finding: enforcement lives in the RUNTIME ADAPTER; capabilities live in the PLATFORM. Enforcement exists exactly where governance does not, and is absent exactly where it does." [L80; E-S0024 Q15]

**Int (qualified after review C4 F-07; RC-60a):** the 41 enforced items are **tool permissions**, which §2.0 (RM-1) excludes from "rules". So the comparison is between rules (unenforced) and permissions (enforced), not between two sets of rules. The "16 advisory" count is by mechanism; the book itself records a blocking destructive-command gate (§2.8.6) and guards whose message and effect differ (Ch. 1 §1.12), so "every automation is advisory" is not established by effect (SEQ-0003). With those limits, the pattern still sharpens this section: what is enforced and what is governed sit in **different components**. It agrees with RC-13 (S0008: capabilities execute their design policy but do not enforce it; enforcement is runtime-side). The body of S0024 still says "nowhere enforcing" [L202]; the correction governs as the author's later statement.

---

## 2.11 Exceptions and the rule lifecycle

**Exceptions exist today without the name (Src, RS L237–243).** Allow-lists beside quality-gate checks are exceptions "in every respect but name": scoped, recorded beside the rule, outcome-changing. They lack an approver, a reason, a validity period, and a way to distinguish *withdrawn* from *expired*. The organisation "grants exceptions today, silently, by editing a list."

**The definition (Src, ruled RM-4, RS L247).**

> **An exception is a separately authorized deviation from a rule within a defined scope and validity period.**

Each word closes a measured gap [RS L249–256]:
- **separately**: its own act, not an edit to the rule;
- **authorized**: has an approver;
- **scope**: explicit, not implicit in a pattern;
- **validity period**: makes expired distinguishable from withdrawn.

**The rule lifecycle (Src, ruled RM-3, RS L262–276).** Changing an authoritative rule requires explicit authorization and a recorded reason. Target path: **propose → review → authorize → effective**. Today the path is *edit a file → new organisational rule*.

**Integration (Ded).**
- RM-3 is Ch. 1 §1.2's authority axiom **for rules**: "effective" is a standing reached only by an act ("authorize"). There is no creation→effective edge.
- RM-4 with Ch. 2 §2.8.2 gives the full containment chain: **exception scope ⊆ rule subject scope ⊆ issuing authority's grant scope**, plus **exception validity ⊆ rule validity** (a time containment the sources imply but do not state).
- An exception has its own **standing** (granted, withdrawn, expired), separate from the rule's. Expired and withdrawn are different terminal states: expiry is caused by time, withdrawal by an act.
- By Ch. 1 §1.12, an exception is valid only if the authorizing act occurred, and verifiable only if it is recorded with approver, reason, scope and period.

**Lifecycle position ≠ validity period (Src → Ded; RS L144).** Engineering standards carry a lifecycle status (proposed / adopted / superseded): "**where it is in its life — not when it binds**". These are orthogonal:
- a rule can be adopted (standing) but not yet in force (validity starts later);
- or in force by date but not yet adopted, which by RM-3 makes it **not effective**.

The theory therefore needs both standing (Ch. 1 §1.2) and validity (§2.2). Effective = standing *adopted* ∧ t ∈ validity. **Ded.**

**Open (Src, RS L278).** Whether advisory heuristics, which are not rules, also need recorded change authority. Not ruled. By my criterion (Ch. 1 §1.9), changing a heuristic changes no standing, so it needs a *record* (provenance, I-3) but not an *authorizing act*. **Ded; unresolved as policy.**

**A deferral that falls due (S0025; revision RC-68).** The Reference Architecture deferred a "Platform ≙ Adoption split" with a recorded trigger: "a second adopting project". The bootstrap experiment claims to be that trigger: "The platform recorded the trigger; the trigger has now fired." It calls its proposed acquisition rule "not a suggestion — it is a deferral coming due" [S0025 L144–147; E-S0025 Q20] `[CON-0061 · invalid]`.
- **Int:** a trigger-bound deferral changes a lifecycle question from "whether" to "when". Two things remain open (TODO T2-09):
  - **who decides** that the trigger has fired (by §1.6, an authority, not the reporting author);
  - **whether** an isolated experiment on a hypothetical product is "a second adopting project" at all.

---

## Change log

| Date | Change | Reason |
|---|---|---|
| 2026-10-18 | Chapter created from RM (with GA) | pilot file 4 |
| 2026-10-18 | §2.8 from SC; §2.2 marked as revised (three-valued applicability) | pilot file 5 |
| 2026-10-18 | §2.8.7 from EV with configuration checks; failure mode "silent substitution" added to §2.8.8 (then numbered §2.8.5) | pilot 2, file 5 |
| 2026-10-18 | §2.8.6 from SD (SC-1, three states, loophole); §2.8.4 marked revised; E7 added to §2.8.8 (then §2.8.5); vocabularies verified | pilot 2, file 4 |
| 2026-10-18 | **Audit corrections:**<br>· L-12: containment, global empty case<br>· L-13: semantics of ⊥, tight overlap, open items<br>· L-15: adding a dimension degrades positive results only<br>· L-17, R-17: gate principle scoped, provenance condition<br>· L-09: enforcement ≤ realization<br>· L-08: effects and same-polarity conflicts<br>· L-07: effective applicability, final conflict definition<br>· L-11: partition over-approximation<br>· L-22, H-05: priority re-derived; rank as annotation<br>· L-16: adding a value<br>· L-14: κ, K ≠ artifact kinds, cross-kind<br>· L-21: unsatisfiable need ≠ E5<br>· L-24: RM↔RS mapping<br>· H-02, H-03, H-04: over-generalizations<br>· C-02: grant scope; C-05: MAY; C-06: references<br>· R-14 to R-17, R-21, E-05, H-08, H-10, L-10<br>**Note:** there is no §2.8.5 (renumbered to §2.8.8) | `AUDIT-ch00-02.md` |
| 2026-10-18 | §2.0, §2.9–2.11 from RS; §2.2 element list revised to 10 parts; §2.3(b) revised (permission vs prohibition = unauthorised exception) | pilot 2, file 2 |
| 2026-10-18 | §2.4 third vocabulary (DISJOINT…SUPERSEDING) analysed and reconciliation proposed; §2.2 identity and relationship parts noted; §2.8.8 "unreadable" added to E6 and response STOP · REPORT · ESCALATE recorded | batch 3, file 1 |
| 2026-10-18 | Lookback A/B (V3): §2.1 rule-engine gap verified in YAML; §2.8.8 table repaired (paragraph moved out of table), E6 row widened, E7 added to licensing sentence, no-silent-promotion maxims | batch 3, file 1 |
| 2026-10-18 | From CF: §2.1 convergence claim with independence caveat; §2.2 candidate contract tested (owner ≠ authority; stored "approved" ≠ current authority); §2.4 fourth vocabulary | batch 3, file 2 |
| 2026-10-18 | From AB: §2.4 conflict report as projection, conflict not owned by a rule, ACCEPTED_RISK sign; §2.7 reproducibility refinement and caveat for stochastic AI candidates | batch 3, file 3 |
| 2026-10-18 | From IM: §2.9 hardness as third orthogonal property; §2.8.8 "NoConflictDetected" ambiguity; §2.7 determinism requirement scoped to the formal analyser | batch 3, file 4 |
| 2026-10-18 | From IA: §2.8.8 E8 *withheld* added (table, licensing sentence, unsatisfiable-need union, hypothesis range); NoConflictDetected note verified against IA L121, L136 | batch 3, file 5 |
| 2026-10-18 | **Correction round 1:** §2.7 deterministic-decoding remark marked Int (unverified general knowledge) | correction round 1 |
| 2026-10-18 | **Correction round 2:** E8 marked Hyp (deduction, not in IA; may reduce to E3 narrowed by policy); union, licensing sentence and hypothesis range made conditional | correction round 2 |
| 2026-10-18 | **Correction round 3:** §2.9 "third orthogonal property": the §1.14.5 dependency restated conditionally on P-E, with OQ-24 open | correction round 3 |
| 2026-10-18 | **Correction round 4:** §2.9 four properties (binding strength, hardness, enforcement consequence, evaluability); the earlier merge of hardness with enforcement consequence corrected | correction round 4 |
| 2026-10-18 | **Batch 4:** §2.8.8 E6 split into undecidable / resource-exhausted / unreadable; same epistemic rule from the delivery domain; unreadable policy must fail closed · §2.9 override as a property; results that may open a gate; where a check may live (EVT-1); OQ-24 made concrete | batch 4 |
| 2026-10-18 | **Batch-4 independent review corrections:** §2.8.8 "E3–E6" → E2, E3, E6 and "one rule" → shared premise (F4, F16); fail-closed cure limited by design-check defaults (F15); ruled absence as a recorded E1 (F21) · §2.9 fourth property renamed **required consistency guarantee** (F7); override defined only for blocking gates (F13); two readings of NOT_APPLICABLE (F9); EVT-1 restored with SAME_CONTEXT_TRANSACTION, "realization ladder read downward" withdrawn (F7); OQ-24 recasting marked narrower than the question (F8) | batch-4 review |
| 2026-10-18 | **Corpus batch C-1 (S0001–S0007):** addition RC-12 at the end of §2.9 (deciding ≠ enforcing; PAP/PDP/PEP; DP-n hypothesis). Record: `BASELINE-REVISIONS.md` § "Corpus batch C-1". TODO T2-06 |
| 2026-10-18 | **Corpus S0008:** RC-13 (DP-n defined; capabilities advisory, enforcement runtime-side) in §2.9. TODO T2-06 resolved as to definition |
| 2026-10-19 | **Independent review C-1 correction:** RC-13a (DP-n executed, not enforced; verbatim S0007 quote) |
| 2026-10-19 | **Corpus S0015:** RC-35 (§2.10, protocol-only governance failed once; not a mandate to automate) |
| 2026-10-18 | **Corpus S0021:** RC-46 (§2.9, where the design-policy layer was found; CON-0034 invalid; T2-07) · RC-47 (§2.5, I-10 "void-overriding", meaning unresolved; T2-08) |
| 2026-10-19 | **Corpus S0022:** RC-52 (§2.5, I-10 used, not defined; the overriding source is external) |
| 2026-10-19 | **Corpus S0024:** RC-60 (§2.10, where enforcement actually lives; boundary/trigger/advisory; maturity ≠ enforcement) |
| 2026-10-19 | **Corpus S0025:** RC-66 (§2.4, two closed vocabularies: a real conflict) · RC-67 (§2.8.3, a deferred rule is silence) · RC-68 (§2.11, a deferral that falls due) |
| 2026-10-19 | **From S0026 (rule RK-1): changes are recorded only in `BASELINE-REVISIONS.md`.** This log is closed |
