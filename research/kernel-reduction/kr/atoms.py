"""Semantic atom vocabulary.

ANTI-CIRCULARITY DEVICE (Part XXIV of the protocol).

Atoms are derived from the CAPABILITY analysis and the corpus, NOT from
operator names.  An operator's power is exactly the set of atoms its
declared contract permits it to INTRODUCE.  Composition can never introduce
an atom that no operator in the set holds.  Therefore:

    "reconstruct X from the remaining operators"

is decidable, and

    "redefine Y so that Y also does X"

is *mechanically detectable* as a modification of Y's atom set
(-> SMUGGLING = TRUE), because it is a change to the model, not a
composition within it.

Atoms are deliberately allowed to be SHARED between operators.  A 1:1
operator->atom assignment would make every operator irreducible by
construction and the experiment vacuous.
"""

# --- productive atoms (introduce new semantic content / commitments) -------
A_WORLD_CONTACT      = "world-contact"            # only channel for new empirical content
A_MEANING            = "meaning-assignment"       # signal -> context-relative semantic content
A_ENCODING           = "symbolic-encoding"        # semantic content -> manipulable structure
A_LINKING            = "relational-linking"       # typed relation between two structures
A_DIFF_DECISION      = "difference-decision"      # verdict over presented alternatives (incl. UNDECIDED)
A_GENERATION         = "content-generation"       # content NOT entailed by current content
A_ENTAILMENT         = "entailment"               # content entailed under a declared rule
A_NORM_COMPARISON    = "norm-comparison"          # actual state vs normative target
A_NEGATION           = "adversarial-negation"     # produce a defeater for a claim
A_WARRANT            = "warrant-assessment"       # assign warrant/verdict given evidence+assumptions
A_MUTATION           = "state-mutation"           # commit to K_t (history preserving)
A_CLOSURE            = "closure-judgment"         # inquiry-relative adequacy declaration
A_ACTION_PREFERENCE  = "preference-over-actions"  # order/choose among actions under cost
A_QUALIFICATION      = "evidential-qualification" # admit an observation AS evidence under a policy

PRODUCTIVE_ATOMS = [
    A_WORLD_CONTACT, A_MEANING, A_ENCODING, A_LINKING, A_DIFF_DECISION,
    A_GENERATION, A_ENTAILMENT, A_NORM_COMPARISON, A_NEGATION, A_WARRANT,
    A_MUTATION, A_CLOSURE, A_ACTION_PREFERENCE, A_QUALIFICATION,
]

# --- structural atoms: modelled as INVARIANTS, not as ownable powers ------
# (context, temporal, uncertainty, assumption, provenance, alternative-set)
# See kr/capabilities.py :: INVARIANT_CAPABILITIES
