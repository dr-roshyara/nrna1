"""Carrier (epistemic artifact) kinds and their DERIVATION RULES.

Second anti-circularity device.  A carrier kind is NOT stamped by the
operator that produced it.  It is derived from

    (multiset of input kinds, atoms introduced by the producing step)

so ANY operator set able to introduce the required atoms over the required
inputs produces the carrier.  Otherwise "only DetectGap makes a Gap" would
be true by fiat and the ablation would be rigged.
"""
from .atoms import *

# ambient carriers: available without any operator (the situation, not knowledge)
WORLD        = "World"
CONTEXT      = "Context"
INQUIRY      = "Inquiry"
IDEAL_STATE  = "IdealState"
POLICY       = "Policy"          # evidential admission policy (governance input)
RULE         = "Rule"            # declared inference rule
OBJECTIVE    = "Objective"       # cost/utility model (governance input)
ACTION_SET   = "ActionSet"
K_STATE      = "EpistemicState"  # K_t  (K_0 is ambient: possibly empty)

AMBIENT = [WORLD, CONTEXT, INQUIRY, IDEAL_STATE, POLICY, RULE, OBJECTIVE,
           ACTION_SET, K_STATE]

# derived carriers
OBSERVATION     = "Observation"
SEMANTIC        = "SemanticContent"
REPRESENTATION  = "Representation"
EVIDENCE        = "Evidence"
RELATION        = "Relation"
DISCRIMINATION  = "Discrimination"
HYPOTHESIS      = "Hypothesis"
CLAIM           = "Claim"
DEFEATER        = "Defeater"
VERDICT         = "Verdict"
NORM_DELTA      = "NormDelta"
GAP             = "Gap"
DETERMINATION   = "Determination"
DECISION        = "Decision"

# ---------------------------------------------------------------------------
# Derivation table.  Each rule: (required input kinds (set), required atoms
# introduced at this step (set)) -> produced kind.
# Rules are matched by: inputs available >= required inputs, and
# step_atoms >= required atoms.
# ---------------------------------------------------------------------------
DERIVATION_RULES = [
    # inputs                              atoms introduced             -> kind
    ({WORLD},                             {A_WORLD_CONTACT},              OBSERVATION),
    ({OBSERVATION, CONTEXT},              {A_MEANING},                    SEMANTIC),
    ({SEMANTIC},                          {A_ENCODING},                   REPRESENTATION),
    ({OBSERVATION, POLICY},               {A_QUALIFICATION},              EVIDENCE),
    ({REPRESENTATION},                    {A_LINKING},                    RELATION),
    ({SEMANTIC},                          {A_LINKING},                    RELATION),
    ({REPRESENTATION},                    {A_GENERATION},                 HYPOTHESIS),
    ({SEMANTIC},                          {A_GENERATION},                 HYPOTHESIS),
    ({REPRESENTATION, RULE},              {A_ENTAILMENT},                 CLAIM),
    ({CLAIM, RULE},                       {A_ENTAILMENT},                 CLAIM),
    ({HYPOTHESIS, RULE},                  {A_ENTAILMENT},                 CLAIM),
    ({CLAIM},                             {A_NEGATION},                   DEFEATER),
    ({HYPOTHESIS},                        {A_NEGATION},                   DEFEATER),
    ({CLAIM, EVIDENCE},                   {A_WARRANT},                    VERDICT),
    ({HYPOTHESIS, EVIDENCE},              {A_WARRANT},                    VERDICT),
    ({K_STATE, IDEAL_STATE},              {A_NORM_COMPARISON},            NORM_DELTA),
    ({NORM_DELTA},                        {A_DIFF_DECISION},              GAP),
    ({NORM_DELTA, INQUIRY},               {A_CLOSURE},                    DETERMINATION),
    ({ACTION_SET, OBJECTIVE},             {A_ACTION_PREFERENCE},          DECISION),
    ({K_STATE},                           {A_MUTATION},                   K_STATE),
]

# generic discrimination: any two same-kind artifacts + difference-decision
DISCRIMINABLE = [REPRESENTATION, SEMANTIC, HYPOTHESIS, CLAIM, EVIDENCE,
                 OBSERVATION, VERDICT, RELATION, NORM_DELTA]

def derive_kinds(input_kinds, step_atoms):
    """Return the set of carrier kinds derivable in ONE step."""
    out = set()
    ik, sa = set(input_kinds), set(step_atoms)
    for req_in, req_at, kind in DERIVATION_RULES:
        if req_in <= ik and req_at <= sa:
            out.add(kind)
    if A_DIFF_DECISION in sa and (ik & set(DISCRIMINABLE)):
        out.add(DISCRIMINATION)
    return out
