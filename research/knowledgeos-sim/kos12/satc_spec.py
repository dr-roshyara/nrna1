"""PHASE A — Formal specification of the eight Sat_c.  SPECIFICATION, NOT IMPLEMENTATION.

    define Sat_c   ≠   implement Sat_c

Common skeleton (the review's §"better mathematical abstraction"):

    Sat_c(K,r;Γ) = ⊤  if  K,Γ ⊨ P_c(r)
                 = ⊥  if  K,Γ ⊨ ¬P_c(r)
                 = U  otherwise

so the research object is the FAMILY  {P_C,P_E,P_P,P_S,P_Con,P_G,P_T,P_O}
and the question is whether it is coherent, independent and composable.

TWO LEVELS:
    Level 1   Sat_c(K,r;Γ) ∈ {⊤,⊥,U}          the value
    Level 2   Just_c(K,r;Γ)                    WHY that value

Hypothesis under test: Reason(U) ∈ {UNOBSERVED, UNINTERPRETED, UNDERDETERMINED,
UNOBSERVABLE, NO_EVALUATOR, NO_ORDERING, DELTA_UNDEFINED}.

Status: **Sat_c Formal Candidate Specification v0.1** — not canonical, not implemented.
"""

# reasons a value of U may carry (Level 2)
UNOBSERVED, UNINTERPRETED, UNDERDETERMINED, UNOBSERVABLE = \
    "UNOBSERVED", "UNINTERPRETED", "UNDERDETERMINED", "UNOBSERVABLE"
NO_EVALUATOR, NO_ORDERING, DELTA_UNDEFINED, INSUFFICIENT_PROV, NO_TEMPORAL_SEM = \
    "NO_EVALUATOR", "NO_ORDERING", "DELTA_UNDEFINED", "INSUFFICIENT_PROVENANCE", "NO_TEMPORAL_SEMANTICS"

# The twelve attributes the review requires of every class.
SPEC = {
"content": dict(
  input_type="K_t : semantic content of the knowledge state",
  requirement_type="r = (Content, p)",
  semantic_predicate="P_C(r) ≡ p ∈ Content(K_t)",
  top="p ∈ Content(K_t)",
  bot="¬p ∈ Content(K_t)   — an EXPLICIT negation, not an absence",
  u="neither p nor ¬p present.  **absence ≠ negation**",
  u_reasons=[UNOBSERVED, UNINTERPRETED],
  conflict_behaviour="if both p and ¬p ∈ Content(K_t) the class is INCOHERENT as stated — "
                     "see PB-2; a contradiction value is not in the codomain",
  provenance_dependency="none",
  temporal_dependency="none as stated — but Content is time-indexed in K_t, so this is UNDER-SPECIFIED",
  authority_dependency="none",
  factivity_requirement="NONE — content membership is not truth",
  executable_now=True),

"evidence": dict(
  input_type="Evidence(K_t,p) plus an admissibility condition E_min",
  requirement_type="r = (Evidence, p, E_min)",
  semantic_predicate="P_E(r) ≡ Evidence(K_t,p) ⊨ E_min",
  top="Evidence(K_t,p) ⊨ E_min",
  bot="Evidence(K_t,p) ⊨ ¬E_min",
  u="evidence exists but does not decide E_min — **existence ≠ sufficiency**",
  u_reasons=[UNOBSERVED, UNDERDETERMINED],
  conflict_behaviour="conflicting evidence yields U, never an average",
  provenance_dependency="YES — admissibility is source-relative",
  temporal_dependency="YES — evidence may expire (**CE-3: no retirement relation exists**)",
  authority_dependency="the admission POLICY is governance-supplied",
  factivity_requirement="NONE — and this is where CE-1 originates: E_min can be met by a false report",
  executable_now=True),

"provenance": dict(
  input_type="Π(p), the provenance record of p",
  requirement_type="r = (Provenance, p, π_min)",
  semantic_predicate="P_P(r) ≡ Π(p) ⊨ π_min",
  top="Π(p) ⊨ π_min",
  bot="Π(p) ⊨ ¬π_min",
  u="Π(p) insufficient to decide",
  u_reasons=[INSUFFICIENT_PROV, UNOBSERVED],
  conflict_behaviour="two sources of differing authority ⇒ U unless π_min ranks them",
  provenance_dependency="YES — definitionally",
  temporal_dependency="weak",
  authority_dependency="π_min is a governance artifact",
  factivity_requirement="NONE",
  executable_now=True,
  note="MUST NOT be confused with provenance-sensitive equivalence ≅_λ: "
       "Sat asks 'does this state have the required provenance', ≅_λ asks "
       "'are two states equivalent under provenance'"),

"status": dict(
  input_type="ES(p,K_t), the epistemic status of p",
  requirement_type="r = (Status, p, s_min)",
  semantic_predicate="P_S(r) ≡ ES(p,K_t) ⪰ s_min",
  top="ES(p,K_t) ⪰ s_min",
  bot="ES(p,K_t) ≺ s_min",
  u="ES undetermined, **or ⪰ itself undefined**",
  u_reasons=[UNDERDETERMINED, NO_ORDERING],
  conflict_behaviour="incomparable statuses ⇒ U",
  provenance_dependency="none",
  temporal_dependency="none",
  authority_dependency="none",
  factivity_requirement="NONE",
  executable_now=False,
  blocker="**⪰ IS NOT DEFINED BY THE THEORY.** Any implementation invents it."),

"consistency": dict(
  input_type="Contr(K_t,p), a contradiction predicate",
  requirement_type="r = (Consistency, p)",
  semantic_predicate="P_Con(r) ≡ ¬Contr(K_t,p)",
  top="¬Contr(K_t,p)",
  bot="Contr(K_t,p)",
  u="Contr cannot be evaluated — **unknown contradiction ≠ no contradiction**",
  u_reasons=[UNDERDETERMINED, UNOBSERVED],
  conflict_behaviour="this class IS the conflict class; the open question is whether a "
                     "fourth value C is needed (review: HYPOTHESIS, not a choice)",
  provenance_dependency="weak",
  temporal_dependency="YES — p true@t1 and false@t2 is not a contradiction",
  authority_dependency="none",
  factivity_requirement="NONE",
  executable_now=False,
  blocker="Contr is not defined; and whether contradiction needs a 4th value is OPEN"),

"governance": dict(
  input_type="an authoritative governance evaluator Eval_Gov",
  requirement_type="r = (Governance, g)",
  semantic_predicate="P_G(r) ≡ Eval_Gov(K_t,g) = ⊤",
  top="the authority grants g",
  bot="the authority denies g",
  u="**no authority supplied** — and governance satisfaction may NOT be inferred from content",
  u_reasons=[NO_EVALUATOR],
  conflict_behaviour="competing authorities ⇒ U; resolution is NORMATIVE",
  provenance_dependency="YES",
  temporal_dependency="YES — authority is time-bounded",
  authority_dependency="YES — definitionally",
  factivity_requirement="NONE — **authority is not truth**",
  executable_now=False,
  blocker="no evaluator exists; this is why every governance requirement read U in E1"),

"temporal": dict(
  input_type="a temporal interpretation of K_t over an interval I",
  requirement_type="r = (Temporal, p, I)",
  semantic_predicate="P_T(r) ≡ p established over I",
  top="p established over I",
  bot="p established false over I",
  u="temporal evidence insufficient, **or no temporal semantics defined**",
  u_reasons=[NO_TEMPORAL_SEM, UNOBSERVED],
  conflict_behaviour="true@t1 / false@t2 is NOT a contradiction — it is a temporal fact",
  provenance_dependency="weak",
  temporal_dependency="YES — definitionally; **time is not metadata**",
  authority_dependency="none",
  factivity_requirement="NONE",
  executable_now=False,
  blocker="no temporal semantics defined; this is the second source of U in E1"),

"operational": dict(
  input_type="δ(K_t,o), the state after operation o",
  requirement_type="r = (Operation, o, κ)",
  semantic_predicate="P_O(r) ≡ Sat_κ(δ(K_t,o))",
  top="δ defined ∧ applicable ∧ postcondition κ met",
  bot="δ defined ∧ applicable ∧ κ not met",
  u="**δ undefined or inapplicable — U, NEVER automatically ⊥**",
  u_reasons=[DELTA_UNDEFINED],
  conflict_behaviour="n/a",
  provenance_dependency="YES — replay",
  temporal_dependency="YES",
  authority_dependency="YES — precondition ≠ authorization ≠ postcondition",
  factivity_requirement="NONE",
  executable_now=False,
  blocker="δ is Step 290 and is open; this is the third source of U in E1",
  note="RECURSIVE: Sat_op is defined via Sat_κ, so the family is not flat"),
}

CLASSES = list(SPEC)
ALL_U_REASONS = sorted({x for s in SPEC.values() for x in s["u_reasons"]})
EXECUTABLE_NOW = [c for c, s in SPEC.items() if s["executable_now"]]
BLOCKED        = [c for c, s in SPEC.items() if not s["executable_now"]]
