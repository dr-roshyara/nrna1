# III.7 · Decision, Authorization, Action

> **Edition 1 abstract** *(frozen baseline, verbatim opening)*: The model's second boundary system
> separates knowing from doing, in three non-collapsing steps: SufficientKnowledge ≠ ValidDecision
> ≠ AuthorizedAction. — *Full Edition-1 text:
> `../../../book/part-3-architecture/03-07-decision-authorization-action/chapter.md`.*

## 1 · Three questions that must never merge — *status: fact [FA] + source evidence [E]*

[E — SOURCE MODEL] Step 042 opens by separating three questions: **epistemic** ("do we have
sufficient knowledge?"), **logical** ("are the decision conditions internally consistent?"), and
**governance/safety** ("is the resulting action permitted?") — and boxes the conclusion the
ratified model carries as invariant I-3: **SufficientKnowledge ≠ ValidDecision ≠
AuthorizedAction.** "This distinction is fundamental." [FA] Everything in this chapter is
machinery for keeping those three apart under pressure — and the pressure is real: every shortcut
a system can take (act because we know enough; act because the plan is consistent; treat
permission as proof) is one of these three merges. [M] As before, the chapter keeps SOURCE MODEL →
RATIFIED SYNTHESIS → EDITION-2 EXPLANATION visibly apart; this boundary system yielded two new
compression findings (PF-7, PF-8), both taught below, neither repaired.

## 2 · The selector — and the source's second selector — *status: definition [FA/E]; PF-8 core*

> **Definition — Proposal (the selector)** · *Notation (ratified, 025g):* from `(K_t, Z_t, G)`
> propose the next epistemic action. *Semantics:* the answer to "what should we try to LEARN or
> establish next," computed from the state, the gap vector, and the goal — G's only direct
> consumer in the model. *Scope:* proposal only — **no authority** (invariant I-2); its output
> enters evaluation like anything else. *Grade:* [FA]←READ (025g; the naming RESERVED note of
> GN-04 stands). *Relations:* consumes III.4's Zero; feeds the decision machinery below.
> *Example:* §10. *Limitation:* selection quality (utility, risk) is not part of the ratified
> object — see the finding below.

[E — SOURCE MODEL, PF-8] The source is richer: it defines **two selector roles** with distinct
signatures (their lens-era names are Part I.5's story; this chapter needs only the functions).
The **epistemic-action selector** — `(K, Z, G, C) → A`, "what to do next to improve the state" —
is what the ratified Proposal carries. Beside it stands a **decision selector** —
`(K, G, D, C, P) → d`, "which decision is justified given the current knowledge and decision
model" — operating over an explicit `DecisionModel = (Outcomes, Constraints, Preferences,
Utilities, Uncertainty, Policy)` and returning not a bare verdict but an auditable
**DecisionResult**: the source's own example returns POSTPONE with its reason ("rollback
capability not established"), the blocking requirement, the alternatives' feasibility, the
evidence basis (E17, E22, E31), the decision-model version, and an authorization note. And the
source boxes the underlying distinction the two roles rest on: **Decision ≠ Action** — deciding
"Migrate" is not executing anything; it unfolds into several actions. [M] The ratified model
compressed this second role: fragments live inside DC's components, but no ratified object selects
among decisions under a utility/risk model, and no ratified structure mandates the
DecisionResult's auditability. Recorded as PF-8; taught at source strength; promoted nowhere.

## 3 · The Decision Contract — both source forms, one ratified — *status: definition [FA/E]; PF-7 core*

> **Definition — DecisionContract DC** · *Notation (ratified, verbatim):*
> `DC(d) = (Pre, Inv, Auth, Post, Temporal, Evidence)` — preconditions; invariants preserved;
> authorization requirements; required postconditions; timing constraints; assurance requirements.
> *Semantics:* every consequential decision carries an explicit contract (the source's Principle 6)
> — classical Hoare form `{Pre(d)} d {Post(d)}` extended, because "ordinary Hoare logic is not
> enough": KnowledgeOS decisions also answer to evidence, uncertainty, authority, temporal
> validity, and provenance. *Scope:* the contract EVALUATES; it does not execute (§7).
> *Grade:* [FA]←READ (042). *Relations:* Auth is III.6's boundary surfacing here; Evidence is
> III.5's store consulted here; Temporal echoes criterion H. *Example:* §10. *Limitation:*
> the ratified form is the source's EARLY form — see the finding.

[E — SOURCE MODEL, PF-7] Later in the same step the source refines its own contract to seven
components: `DC(d) = ⟨P, I, A, E, Q, T, O⟩`, adding **Q — epistemic sufficiency** as a component
in its own right (sufficiency-of-knowing, distinct from E's evidence requirements), with
admissibility as the six-fold conjunction over the tuple. The ratified row carries the early
six-form. [M] Recorded as PF-7; whether the ratified DC should ever be enriched toward Q's
separation is that finding's pending disposition — not this page's decision. What both forms share,
and what matters most, is the next section's law.

## 4 · Admissibility is a conjunction — never an average — *status: source evidence [E] + fact [FA]; derivation RECONSTRUCTABLE*

[E — RECONSTRUCTABLE] The source's admissibility law:
`Admissible(d, K, t) = Pre ∧ Invariant ∧ Assurance ∧ Authorization` — and its §42.10 argument is a
two-line derivation worth reproducing whole: suppose Pre = True, Assurance = True, Authorization =
True, but Invariant = False. Then Admissible = False. **"There must be no averaging."** A decision
that is 95% admissible is inadmissible; excellence in three conditions buys nothing against
failure in the fourth. [FA] This is the algebraic face of the whole boundary system: the three
questions of §1 do not trade off, and neither do their formal representatives. [E] The safety gate
is then just the invariant clause with teeth: for critical invariants, False ⇒ Block — and the
gate runs BEFORE side effects (§42.45), never as a post-hoc audit.

## 5 · Unknowns at the boundary — *status: source evidence [E] + fact [FA]*

[E] What if an invariant's status is Unknown? The source's answer became one of its seven closing
principles: **"Unknown safety state must not silently become safe"** — for safety-critical
decisions the default is Block (`Unknown → Block`); for exploratory analytics
`Unknown → ContinueWithWarning` may be right; the DECISION POLICY chooses, explicitly — including
the classic open-world/closed-world choice, which the source refuses to make universally: "the
applicable domain policy must specify which semantics apply." [FA] The reader has met this shape
twice already — III.4's Unknown-vs-Missing, the constitutional Unknown article — and here it
reaches the place it matters most: the last gate before the world changes. [IN] Note the
composition with III.5: an evidence layer that refuses to launder absence into falsity, a gap
function that types its unknowns, and a decision gate that refuses to treat unknown as safe are
one discipline applied at three altitudes.

## 6 · Authorization — a constraint, not a step; independent, both ways — *status: definition [FA/E]*

> **Definition — Authorization** · *Semantics (ratified):* a precondition constraint filled via
> governance — **never a processing step** a pipeline performs on its own. *Scope:* the Auth
> component of DC; the A6 crossing's formal residence. *Grade:* [FA]←**COMPOSITION**
> (025h + 042 + Q18 + 008-A6 — the grade is shown because this object, uniquely among the
> chapter's cast, was assembled by the synthesis from four sources rather than read from one).
> *Relations:* granted by authority (III.6 §6); recorded at L5; resolved fail-closed by the one
> piece of running code in this story (§11). *Example:* §10. *Limitation:* revocation and
> delegation semantics are unmodeled.

[E — SOURCE MODEL] The source sharpens independence in BOTH directions, and the second is the one
systems forget: `Recommended(Action) ⇏ Authorized(Action)` — obviously — but also
`Authorized(Action) ⇏ Recommended(Action)`: permission is not advice; an authorized action can be
epistemically foolish, and the model keeps the dimensions orthogonal so each can say so. [FA]
A6 restated at ratified strength, once more, because this is its operational home: *authority
determines commitment, not evidential truth* — the Auth slot is filled by an ACT, and no
accumulation of evidence, utility, or model-confidence fills it.

## 7 · Evaluation is not execution — *status: source evidence [E]*

[E] The source's §42.42 boxes the split this whole chapter enforces mechanically:
**decision evaluation ≠ decision execution.** Evaluate(d) runs the full contract — without
executing d. Consequences the source draws immediately: **dry-run capability** (evaluate against
current K without side effects — enabling simulation, review, what-if analysis, governance checks,
AI planning) and **counterfactual evaluation** (WhatIf(Execute(d))). [IN] Notice what this gives
the architecture for free: a governed system can KNOW whether a decision would be admissible
without anyone being tempted to take it — review becomes cheap, and the gate's verdict becomes a
first-class piece of knowledge (a Proposition, in III.3's vocabulary) rather than a side effect
of acting.

## 8 · The AI boundary — *status: source evidence [E] + fact [FA]*

[E — quoted] The source's architecture rule, boxed in its own words: **"AI may propose; the
governed system decides whether the proposal is admissible."** The flow is fixed —
`AIProposal → DecisionEvaluation → PolicyGate` — and trust is redefined: avoid `Trust(AI) = True`;
instead an agent's CAPABILITY is bounded by policy — "an AI agent's authority is explicitly
bounded." [FA] The reader has now collected this law's three independent statements: the formal
side here and in the ladder chapter (`LLMOutput ⇏ OrganizationalCommitment`), and the
constitutional side (generation is not justification; the LLM proposes at the gate's mouth and
never possesses). Three arrivals, one boundary — the programme's signature convergence pattern,
one more time.

## 9 · Action, Outcome, and the closed loop — *status: fact [FA] + source evidence [E]; OQ-4 ceiling*

[FA] Action changes state (`A : S_t → S_{t+1}`, III.3's primitive); Outcome is its observed
consequence; and the loop closes: [E — SOURCE MODEL, boxed] **World → Observation → Evidence →
Knowledge → Decision → Action → World** — `K_t → D_t → A_t → W_{t+1} → K_{t+1}` — "the beginning
of a genuine epistemic control system," with the source's immediate caution that *prediction is
not intervention* (acting on the world is categorically different from forecasting it).
[FA — grades shown] The ratified action-loop row carries exactly this at grade READ (head) /
**SPECIFIED** (the reference-machine elaboration) — specified, not established. [U — OQ-4, this
chapter's ceiling] What an authorized action formally IS beyond the boundary — its execution
semantics, failure modes, effect attribution, compensation — the architecture does not yet say;
the boundary is firm, the far side is open, and the closing act is an explicit L2 extension
ruling. The source's own §25Z.11 sketches a rich Decision object (Options, Evidence, Constraints,
ExpectedUtility, Risk, SelectedAction, Rationale) and its risk/utility sections go further —
all of it source-level material awaiting that ruling, none of it ratified.

## 10 · The running example — crossing the boundary — *status: illustration [IN]/[EDITORIAL]; never evidence*

*(Stage 6, the arc's last conceptual stage. CONCEPTUAL MODEL.)* The gap is closed enough: R is
Accepted (III.6, Journey 1). The **selector** proposes nothing further to learn — its Zero input
is quiet — and the standing decision options are `D = {PublishCertification, Postpone,
OrderRecount}`. The officer's decision machinery evaluates **PublishCertification** under its
contract:

```
DC(publish) =
  Pre:      R Accepted · all anomaly dispositions exist (r₄ closed)
  Inv:      no active custody conflict (r₃ resolved and recorded)
  Auth:     returning officer's certification authority (an act, on the record)
  Post:     certification published, versioned, attributed
  Temporal: within the statutory certification window
  Evidence: the r₁–r₅ evidence set, current per criterion H (r₅ re-attested after the
            patch — explicitly closing the Stale status the stage-3 vector recorded; the earlier
            silent healing was an example-continuity defect, corrected under GN-45, AF-F-18)
```

Every clause True ⇒ Admissible; the officer's act fills Auth; `Committed(R,
PublishCertification)` (III.6, Journey 2); the action executes; the world changes; the published
certification generates new observations (press verification, party responses) — `K_{t+1}`. **The
counterfactual that shows the machinery's teeth:** suppose r₄ had stayed Missing. Then Pre = False
⇒ Admissible = False — *regardless* of evidence volume on R, official impatience, or a dashboard
full of green; and the auditable result would read like the source's own DecisionResult:
POSTPONE, reason "anomaly dispositions incomplete," blocking requirement r₄, alternatives
(Publish = inadmissible, Postpone = feasible, Recount = feasible-but-unwarranted), evidence basis
cited, policy version named, "authorization: not required for postponement." *(ARCHITECTURE: the
gate is the Decision Interlock's line — knowledge flows terminate at recommendation; the Auth act
is Authority-Service material; the published artifact is History-Service material.
IMPLEMENTATION-HONESTY: no DC evaluator, no decision engine, no action executor exists; the
worked contract above was filled by hand.)*

## 11 · The lived fragment — the conjunction in running code — *status: evidence [E]*

[E] One piece of this chapter runs today, and it is precisely the strictest piece: the session
bootstrap resolves a process's authority **fail-closed** — its six-way non-collapse (identity ≠
role ≠ eligibility ≠ authorization ≠ ownership ≠ continuation) is the no-averaging conjunction in
code, and its treatment of unresolved facts is §5's safety default verbatim: any Unknown in the
chain yields NOT AUTHORIZED ("the bootstrap never invents identity/authorization and never
creates a transition"). [IN] It is a small implementation with a precise pedigree: of everything
this chapter teaches, what got built first was not the selector, not the contract evaluator, not
the loop — but the refusal. That ordering is itself the architecture's priority statement,
made in code.

## 12 · Realization — L3 and L4 — *status: fact [FA/E]*

[FA←E] At L3: the **Decision Interlock** "terminates knowledge flows at recommendation" — the
only kernel service whose whole job is refusal; the Authority Service records the acts that fill
Auth slots; engines (including the LLM) live upstream of the gate per §8's flow. [E — L4] Nothing
else executes: no contract evaluator, no dry-run harness, no decision engine, no action layer.
The bootstrap (§11) is boundary machinery for sessions, not a DC implementation. Builders should
read §§3–5 as the specification: the conjunction, the safety default, and evaluation/execution
separation are acceptance tests before they are features.

## 13 · Limitations and open questions — *status: register [U]*

- **OQ-4 (owned here): action/execution semantics OPEN** — boundary firm, far side unspecified;
  closing act: an explicit L2 extension ruling. The source's utility/risk/Decision-object material
  (025h/025z) is the obvious raw material for that ruling and remains unratified.
- **PF-7 / PF-8 (this chapter's findings):** the seven-component DC refinement and the
  two-selector model with auditable DecisionResult — pending the compression-family disposition.
- **Unmodeled:** authorization revocation/delegation; decision-dependency propagation is source
  content (the assurance-invalidation Principle 7: "evidence invalidation must propagate through
  decision dependencies") awaiting formal placement.
- Grades restated: Authorization = COMPOSITION; action loop = READ/SPECIFIED; both taught as such.

## 14 · Conclusion — *status: fact [FA], summary*

[FA] The boundary system is three refusals stacked: knowing enough is not deciding; deciding
validly is not being allowed; being allowed is not being wise. Its machinery is a contract whose
clauses conjoin and never average, a gate that blocks on unknowns before side effects, and an
authority slot only an act can fill. The source built it richer than the ratified summary — a
second selector, a seventh component, an auditable result — and those differences now stand in
the production log beside their siblings. What remains beyond the boundary is the architecture's
honest far edge: the loop is drawn, the world-side semantics are open, and the next chapter turns
inward instead — to the policy that governs the governing.
