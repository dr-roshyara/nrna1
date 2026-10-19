# Chapter 1 — final review, alternatives and decision briefs (2026-10-18)

*Lead researcher's reconciliation of an independent AI review with the lead's own computational tests. The reviewer was a fresh read-only session of the same model family, **not** an external expert. It read Chapter 1, the corrections note, the related chapters, the glossary, the authorized background sources and, at the Principal's request, the platform's implementation artifacts. It checked **derivation**, **source fidelity**, **implementability here** and **mathematical correctness** separately. Nothing in the chapter or the methodology was changed by this review.*

## A. Verdict: **RETURN FOR CORRECTION** (bounded; no reserved decision blocks it)

**In business terms.** The chapter's ideas are right, and they are improvements:
- a record is not a decision;
- *who may do what, where and when* must all be checked;
- *authorized* is not *well-founded*;
- uncertainty must be reported, not hidden.

But the precise rulebook in §1.14.1, which says when a decision counts today, **still gives wrong or undefined answers in some realistic situations**:
- a revocation recorded days after it was made;
- an unauthorized person "revoking" someone's permission;
- two acts at the same moment;
- two people annulling each other's authority;
- a decision for which no adoption is on record at all.

These are drafting and logic repairs. None needs one of the five reserved decisions. Several of them, though, expose **choices between valid concepts**, listed in C, which the corrected text must keep open.

**In technical terms.** The §1.14.1 model has six demonstrated formal defects:
- F1, the empty citation set yields *false*, not *undetermined*;
- F2, the claim "only annulment differs between the record views" is false; the model needs two time axes;
- F3, grant endings escape the authorization requirement;
- F4, under ρ = now the definition has two fixed points;
- F5, acts at the same timestamp are unordered;
- F6 and F7, the headline function is not total and its three-valued logic is unnamed.

The **concepts** in §1.3, §1.12, §1.13.1, §1.16.8 and §1.17.2 hold, with medium-severity consistency issues.

**What the tests established, and what they cannot.**
- **My property test** used 20,000 random scenarios on a literal implementation. It found **no violation** of "an extra alternative never hurts" and "the record views differ only with annulment", and it did find the timestamp gap and the joint-coverage ambiguity. It **missed** F1 and F2, because my implementation silently treated an empty citation as undetermined and let every act take effect when recorded. *A test checks the model the tester wrote, not the model in the text.*
- **The reviewer's hand-built counterexamples**, including one small enumeration, show defects. They do not show that a corrected model will be sound.
- **"Adding an alternative basis or adoption never turns current into non-current"** is **proved** (a disjunction: T ∨ anything = T). The same addition **can** turn *not current* into *cannot determine*.

## B. Findings register

| # | Where | Finding | Sev. | Type | Action | Decides |
|---|---|---|---|---|---|---|
| F1 | §1.14.1 authorizedρ | no cited grant gives "∃ over ∅" = **false** (not validly made), but example 9 and the text say "cannot determine" | high | formal | make citation three-valued (∅ = cites none vs ⊥ = citation unknown), **or** state reading C2 | Principal chooses C2 |
| F2 | §1.14.1 record view | a revocation decided 1 March and recorded 10 March, with an act on 5 March, gives different answers under the two views. So ρ affects **every** late-recorded ending, not only annulment. The act-window definition cannot yield "void from the start" | high | formal | **bitemporal model**: each act has an effective time and a record time; ρ chooses which records are known; annulment = an ending whose effective time is backdated | lead (formal); backdating policy is reserved decision 2 |
| F3 | §1.14.1 act window | grant endings need no authorization, but claim endings do. An unauthorized "revocation" of the Chair's grant voids the Chair's later decisions, which contradicts safeguard 2 | high | formal + conceptual | require authorization for grant endings too, and state the **root**: grants issued by the human authority's own recorded act, as AMS describes [AMS L38–51, L214–226] | lead (formal); the root is a source fact to state |
| F4 | §1.14.1 under ρ = now | mutual annulment (A₁ annuls g₁ under g₂; A₂ annuls g₂ under g₁) has **two fixed points** | medium | formal | evaluate along a total, well-founded order of acts (stratification by sequence); state it | lead |
| F5 | §1.14.1 condition 3; act window | same-timestamp acts are unordered; interval ends are unspecified | medium | formal | a total order of acts (sequence number); half-open windows [start, end) | lead |
| F6 | §1.14.1 headline | not total (no adoption on record); "revoked" outranks "not validly made", which implies the claim once counted; AB L109 justifies distinguishing, not ranking; "authority ended" includes the non-terminal suspension | medium | formal | add "no adoption on record → cannot determine"; list both precedence orders (C8); name "suspended" separately | lead; order is a choice (C8) |
| F7 | §1.14.1 logic | strong Kleene used but unnamed. A revocation whose authorization is undetermined makes the claim **cannot determine**, not "current with a warning", and that is an **attack surface**: anyone can make a decision indeterminate by recording an unverifiable cancellation | medium | formal + conceptual | name the logic; correct business safeguard 2; record the attack surface as a design risk | lead; mitigation is a policy question (brief 6 in D) |
| F8 | §1.14.1 covers | joint basis: co-signature (each grant covers) vs pooled coverage (the union covers); an act has one actor, so two-officer joint authority cannot be expressed; partial scope overlap undefined. **Confirmed by the lead's test** (2% of scenarios differ) | medium | formal | define both readings as a parameter; allow several actors per act | Principal chooses C4 |
| F9 | §1.14.1 | supersession takes effect even if the successor is invalid (reading taken silently); a revocation cannot be revoked (only re-adopted, with a new time and validity); annulment exists for grants, not for acts | medium | conceptual | state the limits; list C5; add act annulment or say why not | Principal chooses C5 |
| F10 | §1.14.1 | multiple adoptions: standing only grows; a narrower re-adoption cannot narrow an earlier one | low | conceptual | state the property and that narrowing requires revocation | lead |
| F11 | §1.13.1 | departs from AMS L51 ("authority **exists only as a grant**") without saying so | medium | source fidelity | quote L51; list C1 with consequences | Principal chooses C1 |
| F12 | §1.14.1 | "no grant period → undetermined" is an SC-2 **analogy** (Int); AMS L119–122 reads absence as "on its face, permanent" | low | source fidelity | label Int; list C7 | Principal chooses C7 |
| F13 | §1.2 vs §1.3 / §1.13.1 | "standing" means both *recognized* (records) and *de jure* (acts) | medium | conceptual | name the two concepts (C3) | lead (terminology) |
| F14 | §1.3 | "inferences have no epistemic support" contradicts §1.16.8 and the book's own Ded; "normative effect" excludes standing changes in §1.3 but includes them in P-E (§1.14.5); new terms missing from the glossary | medium | conceptual | valid deductions inherit support from their premises; one definition of normative effect; glossary rows | lead |
| F15 | §1.16.8 | "established by the record" conflicts with §1.12 (records are evidence); the list of claim types misses stipulations and interpretive readings | low | conceptual | "evidenced by"; add two types | lead |
| F16 | §1.14.1 business case "two conflicting decisions" | Ch. 2 §2.3 defines conflict for **rules**; "never by the system picking one" excludes implicit "later prevails", which is a policy choice | medium | missing policy | list C6 | Principal chooses C6 |
| F17 | §1.14.5 example | the quality gate does **not** become blocking by itself. It exits 1 only if `exit_on_fail` is set **and** violations exceed the threshold, and it prints "Blocking: Yes" while not blocking (**verified** `scripts/design-check.sh` L5, L9, L52–53, L216–232). The real switches are a config value and a command-line flag, with no act | medium | factual | correct the example: a better instance of P-E's concern and of a false self-report (§1.12) | lead |
| F18 | editorial | ρ has no reserved-decision number; "IM leans static" is weaker than stated (IM L221 concerns the exception's own interval) | low | editorial | number it; weaken the wording | lead |
| L1 | §1.14.1 business text | **"in force"** is used for *currently recognized*; Ch. 2 uses "not in force" for *validity not yet started* (lead's terminology scan) | low | editorial | use "currently recognized" or define "in force" | lead |
| L2 | §1.14.1 / §1.16.2 | the most overlapping pair of sections (TF-IDF cosine 0.43): the two-time argument is told twice. Similarity flags overlap; it does not prove redundancy | low | editorial | consolidate at the next pass | lead |

**Corrections A–G of the last pass:** all **present**. A, B, E and F have the gaps above; C, D, G and §1.16.8 are coherent apart from F8, F11, F14 and F15.

## C. Alternative-concept register (all kept; none chosen here)

| # | Concept 1 | Concept 2 | Where it matters | Consequence of 1 vs 2 | Decision needed? |
|---|---|---|---|---|---|
| C1 | **the record constitutes authority** (AMS L51: authority "exists only as a grant") | **the record represents authority** (§1.13.1; IA L99–101: a grant may exist unrecorded) | what a missing record means | 1: a missing record = no authority, which is simple and auditable but makes "unrecorded but real" impossible. 2: a missing record = not evidenced ("cannot determine"), which is truer to how organisations work but leaves more cases open | yes; Principal (it shapes F1 and C2) |
| C2 | **citation constitutive**: an act that cites no grant is not validly made | **citation evidential**: no citation = cannot determine | every rule today (none cites a grant) | 1: today **no** rule is validly made. 2: today **no** rule can be determined | yes; follows C1 |
| C3 | **de jure standing** (from valid acts) | **recognized standing** (from records) | §1.2, §1.3, §1.13.1 | both are needed; they diverge for real but unrecorded acts | no; terminology (lead) |
| C4 | **joint basis = co-signature**: each grant covers the act | **joint basis = pooled coverage**: the grants together cover it. Partial overlap: invalid, or valid on the intersection | §1.14.1 covers | 1 is strict, for dual-control decisions; 2 allows complementary mandates. Results differ in about 2% of random cases | yes; Principal (could differ by activity) |
| C5 | **supersession stands alone**: it takes effect when authorized | **supersession conditional**: only if the successor is validly adopted | §1.14.1 condition 3 | 1 can leave **nothing** in force; 2 can keep an old decision alive when the new one is defective | yes; Principal |
| C6 | **explicit resolution only**: conflicting decisions both stay current until a recorded act resolves them | **implicit "later prevails"** (lex posterior) | conflicting decisions | 1 is safe and visible but leaves conflicts open; 2 resolves automatically but lets timing decide | yes; Principal (relates to Ch. 2 §2.5, where priority by number was rejected) |
| C7 | **no grant period = undetermined** (SC-2 analogy) | **no grant period = permanent until ended** (AMS L119–122) | all 144 live grants | 1: almost nothing is determinable today. 2: everything recorded counts until an ending is recorded | yes; Principal |
| C8 | **headline: most informative first** (acts before lapse of time) | **headline: causal order** (not validly made first) | reporting | 1 shows the latest intent; 2 shows why the claim never counted | low stakes; lead may choose, stating both |
| C9 | **ρ chooses the record state** (bitemporal) | — | — | a single concept after F2; its **policy** remains reserved decision 2 | via brief 2 |

## D. Reserved-decision briefs (recommendation ≠ decision)

### Brief 1 — When a permission ends, what happens to the decisions made under it?
- **Question.** If the authority behind a decision expires, is used up, revoked, replaced, annulled or suspended, does the decision fall?
- **Alternatives:**
  - (a) **survives**: no ending carries over;
  - (b) **falls with it**: every ending carries over;
  - (c) **per kind**: different for ordinary decisions and rules, temporary exceptions and delegated permissions.
- **Consequences:**
  - (a) is stable; an office-holder's decisions outlive the term. But authority obtained in error persists unless separately annulled.
  - (b) is traceable, but standing becomes fragile: a routine expiry would void years of decisions.
  - (c) is the closest to the sources. It needs one rule per kind.
- **Evidence:**
  - AM-1, "the end is not one event" [AMS L284];
  - V3 L319, whose more natural reading has a rule fall when "its authority lapsed";
  - AB, an exception "cannot exceed the approver's authority" [AB L100];
  - §1.13.3(d), which leans "a delegated permission falls with its parent".
- **Risks.** (a) clashes with the V3 reading; (b) creates mass invalidation; (c) adds a classification burden.
- **Recommendation (lead, Int).** **(c):**
  - ordinary decisions and rules **survive** expiry and consumption, and a revocation or replacement of their permission **triggers a review**, not automatic invalidity;
  - **temporary exceptions** and **delegated permissions** fall with any ending of the permission behind them (dynamic reading).

### Brief 2 — Later found invalid: from the beginning, or from now on?
- **Question.** When the organisation records that a permission was issued in error, are earlier acts under it void from the start?
- **Alternatives:**
  - (a) **void from the start**: the annulment is backdated to the permission's start;
  - (b) **from now on**: it counts as an ending at the time it is recorded;
  - (c) **chosen per case**: every annulment act must state its effective time.
- **Consequences:**
  - (a) is correct in principle, but past results change silently, everything done under the permission must be re-examined, and **revocations made under it are undone**.
  - (b) is stable and auditable, but erroneous decisions remain until handled.
  - (c) makes the choice explicit each time, at the cost of a decision per case.
- **Evidence:** none in the sources; the chapter's own analysis. **Both views need a recoverable history of the records**, and the grant records are not under version control today (`.gitignore:33`). *(This corrects an unfair statement in the chapter that only (a) needs it.)*
- **Risks:** (a) is open to reorganising the past; (b) leaves error effects in place.
- **Recommendation (lead, Int).** **(c), with (b) as the default.** An annulment takes effect from its recording unless the annulling act explicitly states an earlier effective time and names the affected acts.

### Brief 3 — Premise P-E: may a machine produce the effect of a decision without a decision behind it?
- **Question.** Should every block, and every machine-granted permission, require a recorded authorizing act?
- **Alternatives:**
  - (a) **adopt broadly**: covers permit, forbid, block and oblige;
  - (b) **adopt narrowly**: forbid and block only;
  - (c) **keep as a design guideline** without force.
- **Consequences:**
  - (a): every passing gate and every fail-open needs an act. That is the most rigorous option and the most administrative.
  - (b): blocks need an act, but fail-open is ungoverned.
  - (c): no change. Today's gates switch to blocking by a config value or a command-line flag **without any act** (F17).
- **Evidence:**
  - CF L36, "gates follow ratification", PROPOSED;
  - ARCH-INV-1;
  - RS L181–183, which assigns enforcement to the mechanism as it matures and is in tension with P-E.
- **Risks:** (a) means friction; (b) leaves a fail-open gap; (c) is the status quo, including false self-reports.
- **Recommendation (lead, Int).** **(b), plus one explicit rule for fail-open:** a mechanism that cannot check must fail closed and report.

### Brief 4 — Does a decision recorded without any reason still count?
- **Alternatives:**
  - (a) **no**: a reason is a validity condition;
  - (b) **yes**: it counts, with a recorded defect;
  - (c) **depends on kind**: a reason is required for rule changes and exceptions, and a defect only for the rest.
- **Consequences:**
  - (a): strict; reasons added later cannot rescue the decision.
  - (b): stable standing; the weakness is visible.
  - (c): follows RM-3 where the sources speak.
- **Evidence:**
  - I-2, an invariant;
  - RM-3, "requires … a recorded reason", which is compatible with (b) and (c);
  - elsewhere, breaches of invariants are treated as defects.
- **Risks:** (a) invalidates on technicalities; (b) tolerates unexplained rule changes.
- **Recommendation (lead, Int).** **(c):** decisions that change rules or grant exceptions are contestable only if a reason is recorded, so require it; for other decisions, record the defect.

### Brief 5 — Does making a rule mandatory authorize the system to block on it?
- **Alternatives:**
  - (i) **yes**: mandatory implies blocking may be enforced;
  - (ii) **no**: blocking (hardness) needs its own specification, which may sit in the same decision.
- **Consequences:**
  - (i): simpler, but "we mean it but don't block yet" disappears, and gates turn hard by configuration.
  - (ii): one more field, and the intent stays explicit.
- **Evidence:**
  - RS L185, "a Rule may be MANDATORY while its current enforcement is ADVISORY. That is legitimate", which shows the two can come apart, though not that (i) is wrong;
  - OQ-24, still undecided.
- **Recommendation (lead, Int).** **(ii).**

### Brief 6 (new; risk found in F7) — How should unverifiable cancellations be handled?
- **Question.** Today any recorded cancellation whose authority cannot be checked makes a decision "cannot determine". Should that remain so?
- **Alternatives:**
  - (a) **keep it**: honest, but anyone can freeze a decision by recording an unverifiable cancellation;
  - (b) **a cancellation counts only once its authority is evidenced**: the decision stays current with a warning in the meantime;
  - (c) **(b), plus a time limit** after which an unverified cancellation is treated as unauthorized.
- **Recommendation (lead, Int).** **(b).** It matches safeguard 2 and the principle that a mechanism records but does not decide. It needs a policy act.

## E. Research plan and TODOs (optimized toward the theory)

| # | Step | Depends on | Acceptance |
|---|---|---|---|
| 1 | **Bounded correction of §1.14.1** (F1–F10, F16 framing) and of §1.3, §1.13.1, §1.14.5 example, §1.16.8 (F11–F15, F17, F18, L1) | Principal go-ahead | each counterexample above (late recording, unauthorized grant revocation, mutual annulment, same timestamp, no adoption, empty citation, invalid successor) gets a **defined** answer under every alternative of C; properties stated **with proofs**; reserved decisions untouched; business text agrees with the model |
| 2 | **One targeted re-review** of the corrected §1.14.1 (claims and evidence only, including this counterexample suite) | 1 | no high-severity formal defect; otherwise one more bounded pass, then stop and report |
| 3 | **Principal choices** on C1, C2, C4–C7 and briefs 1–6 | none; can run in parallel | recorded decisions, or "keep open" with reason |
| 4 | **Five-file batch**: `…1034` policy catalogue, `…1040` decision register, `…1044` domain events, `…1057` artifact gap analysis, `…1144` script inventory | authorization | per the working practice. `…1044` (domain events) bears directly on event ordering and bitemporality (F2, F4, F5) |
| 5 | **Platform implications** (outside the theory workspace; for the Principal or engineering). These are the smallest changes that would make the model evaluable: add `activity`, `validFrom`/`validTo`, a global `seq`, unique ids, and an `endsGrant` link on grant records; put grant records under version control (append-only); add `adoptedBy {act, grants:[[…]]}` to governed knowledge; let the quality gate read and report an `authorizedBy` act. **Defects found in live data:** duplicate grant ids (2 of 144); the quality gate's false "Blocking: Yes"; `statuses.yaml` "approved = formally approved **and in force**" merges standing with current authority | Principal | recorded as backlog proposals, not done here |

## F. Recommendation

- **Chapter 1 is not yet ready for final disposition.** Its concepts are sound; its central formal model needs one bounded correction (step 1) and one targeted re-review (step 2). **No reserved decision blocks that work.** The alternatives in C are written in as parameters and are not chosen.
- **The five-file batch can be considered for authorization** once step 1 is done. Alternatively, authorize it in parallel, because `…1044` (domain events) likely informs the ordering and two-time questions of step 1. **This is a recommendation; the authorization is yours.**
- **Project-state sync (separate from the theory):** the repository-level session log and CONTEXT remain outside this lane's write scope (D-14); the workspace `CONTEXT.md` and `sessions/2026-10-18.md` are current.
