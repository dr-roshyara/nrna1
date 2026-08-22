"""SNF mechanisms as TRUE black boxes.

CONTRACT (identical for every mechanism):
    interpret(expression: str, context: dict) -> CandidateSet

    CandidateSet = {
        "mechanism": str,
        "candidates": [{"representation": IR-dict, "confidence": float,
                        "uncertainty": str}],
        "abstained": bool,
        "abstention_reason": str | None,
        "evidence": [str],
    }

NO-GOLD RULE (the corrected apparatus):
    Mechanisms receive ONLY `expression` + `context` (domain, language).
    They NEVER receive gold, expected answers, distractors, transformation
    provenance, or evaluation data. Gold lives only in the evaluation layer
    (evaluator.py) and is compared AFTER the fact via d_SNF.

INDEPENDENCE:
    Each mechanism implements a genuinely different semantic strategy over
    shared lexical DATA (lexicon.py = a dictionary, not an algorithm):
        SNF-A  symbolic normalization (canonical ordering, morphology, slots)
        SNF-B  directed-graph canonicalization (edges encode argument direction)
        SNF-C  Pāṇinian kāraka semantic roles (explicit unknown/absent/ambiguous)
        SNF-D  SAIT/NSID closed-world registry (resolve known, abstain on unknown)
        SNF-E  constitutional veto ensemble (arbitration rule = experimental var.)
        SNF-N  null baseline (most-common-candidate prior)
    They share the surface scaffolder `_surface()` (tokenizer + clause split) —
    the same way real parsers share a tokenizer while differing semantically.
"""

from __future__ import annotations

import _bootstrap  # noqa: F401

from ir import (IR, Argument, ROLE_AGENT, ROLE_PATIENT, ROLE_INSTRUMENT,
                ROLE_RECIPIENT, ROLE_SOURCE, ROLE_LOCATION, ROLES,
                ARG_EXPRESSED, ARG_UNKNOWN, ARG_NOT_EXPRESSED, ARG_AMBIGUOUS,
                ARG_NOT_LICENSED, MODALITY_ASSERTED, MODALITY_NECESSARY,
                MODALITY_POSSIBLE, MODALITY_REQUESTED, UNC_RESOLVED,
                UNC_AMBIGUOUS, UNC_UNKNOWN)
from lexicon import (VERBS, NOUNS, NEGATION, MODALITY, TEMPORAL_PAST,
                     TEMPORAL_FUTURE, QUANTIFIERS_ALL, QUANTIFIERS_SOME,
                     BE_AUX, PREP_ROLES, REGISTERED_PREDICATES,
                     REGISTERED_ENTITIES, NULL_PRIOR_PREDICATE,
                     NULL_PRIOR_AGENT, NULL_PRIOR_PATIENT, tokenize,
                     content_tokens)


# ---------------------------------------------------------------------------
# Shared surface scaffold (tokenizer + clause structure) — NOT a semantics
# ---------------------------------------------------------------------------

class _Surface:
    """Low-level clause structure every mechanism starts from."""

    def __init__(self, tokens: list[str], verb_idx: int | None,
                 predicate: str | None, frame: list[str],
                 be_before_verb: bool, by_idx: int | None,
                 pp: list[tuple[str, str]], pre_verb_nouns: list[str],
                 post_verb_nouns: list[str], negated: bool, modality: str,
                 temporal: str, quant: str):
        self.tokens = tokens
        self.verb_idx = verb_idx
        self.predicate = predicate
        self.frame = frame or []
        self.be_before_verb = be_before_verb
        self.by_idx = by_idx
        self.pp = pp
        self.pre_verb_nouns = pre_verb_nouns
        self.post_verb_nouns = post_verb_nouns
        self.negated = negated
        self.modality = modality
        self.temporal = temporal
        self.quant = quant

    def is_passive(self) -> bool:
        return self.be_before_verb and self.by_idx is not None

    def active(self) -> bool:
        return not (self.be_before_verb and self.by_idx is not None)


def _noun_after(toks: list[str], start: int) -> str | None:
    """First canonical noun at or after `start`, skipping determiners."""
    for t in toks[start:]:
        if t in NOUNS:
            return NOUNS[t]
    return None


def _surface(expression: str) -> _Surface:
    """Clause-level scaffold. Deterministic, expression-only."""
    toks = tokenize(expression)
    content = content_tokens(expression)

    # Negation marker (skip "no <noun>" which is a quantifier).
    negated = False
    for i, t in enumerate(toks):
        if t in NEGATION:
            if t == "no" and i + 1 < len(toks) and toks[i + 1] in NOUNS:
                continue  # "no invoice" -> quantifier
            negated = True

    # Modality.
    modality = MODALITY_ASSERTED
    for t in toks:
        if t in MODALITY:
            modality = MODALITY[t]

    # Temporal.
    temporal = "NONE"
    for t in toks:
        if t in TEMPORAL_PAST:
            temporal = "PAST"
        elif t in TEMPORAL_FUTURE:
            temporal = "FUTURE"

    # Quantification.
    quant = "NONE"
    for i, t in enumerate(toks):
        if t in QUANTIFIERS_ALL:
            quant = "ALL"
        elif t in QUANTIFIERS_SOME:
            quant = "SOME"
        elif t == "no" and i + 1 < len(toks) and toks[i + 1] in NOUNS:
            quant = "NONE_Q"

    # Main verb.
    verb_idx = None
    predicate = None
    frame = []
    for i, t in enumerate(toks):
        if t in VERBS:
            verb_idx = i
            predicate, frame = VERBS[t]
            break

    # Passive signal: be-form before the verb + optional "by X" after it.
    be_before_verb = any(t in BE_AUX for t in toks[:verb_idx] if verb_idx is not None)
    by_idx = None
    if verb_idx is not None:
        for i in range(verb_idx + 1, len(toks)):
            if toks[i] == "by":
                by_idx = i
                break
    passive = be_before_verb and by_idx is not None

    # Prepositional phrases -> (prep, object-noun-canonical-or-None).
    # The passive "by X" object is the AGENT, not an instrument, so it is NOT a
    # PP here. Objects are scanned past determiners ("with a brush" -> brush).
    pp = []
    for i, t in enumerate(toks):
        if t not in PREP_ROLES:
            continue
        if t == "by" and passive:
            continue
        obj = _noun_after(toks, i + 1)
        if obj is not None:
            pp.append((t, obj))

    # Noun slots by position (by-object is never a bare post-verbal noun).
    if verb_idx is not None:
        pre_verb_nouns = [NOUNS[t] for t in toks[:verb_idx] if t in NOUNS]
        post_verb_nouns = [NOUNS[t] for t in toks[verb_idx + 1:] if t in NOUNS]
        # A noun in a PP ("with a brush") is not a bare patient candidate.
        pp_nouns = {obj for _, obj in pp}
        if passive and by_idx is not None:
            by_obj = _noun_after(toks, by_idx + 1)
            if by_obj is not None:
                pp_nouns.add(by_obj)
        post_verb_nouns = [n for n in post_verb_nouns if n not in pp_nouns]
    else:
        pre_verb_nouns = []
        post_verb_nouns = []

    return _Surface(toks, verb_idx, predicate, frame, be_before_verb, by_idx,
                    pp, pre_verb_nouns, post_verb_nouns, negated, modality,
                    temporal, quant)


def _coref_resolve(nouns_seen: list[str], token: str) -> str | None:
    """Minimal discourse coreference: "it"/"they" -> most recent patient noun.
    Mechanism-internal; receives only the surface, never gold."""
    if token in ("it", "they", "them"):
        return nouns_seen[-1] if nouns_seen else None
    return None


def _base_ir(predicate: str, negated: bool, modality: str, temporal: str,
             quant: str, uncertainty: str, provenance: str,
             confidence: float) -> IR:
    ir = IR(predicate=predicate, negation=negated, modality=modality,
            temporal=temporal, quantification=quant, uncertainty=uncertainty,
            confidence=confidence, provenance=provenance)
    return ir


def _candidate_set(mechanism: str, ir: IR, confidence: float,
                   uncertainty: str, abstained: bool = False,
                   reason: str | None = None, evidence: list[str] | None = None,
                   extra_candidates: list[IR] | None = None) -> dict:
    # Single source of truth for confidence: the IR's own confidence field.
    ir.confidence = confidence
    ir.uncertainty = uncertainty
    cands = [{"representation": ir.to_dict(), "confidence": round(confidence, 4),
              "uncertainty": uncertainty}]
    for extra in (extra_candidates or []):
        cands.append({"representation": extra.to_dict(),
                      "confidence": round(extra.confidence, 4),
                      "uncertainty": extra.uncertainty})
    return {
        "mechanism": mechanism,
        "candidates": cands,
        "abstained": abstained,
        "abstention_reason": reason,
        "evidence": evidence or [],
    }


# ---------------------------------------------------------------------------
# SNF-A — Symbolic normalization
# ---------------------------------------------------------------------------

class SNFA:
    """Canonical ordering, morphological normalization, slot filling.

    Claims only NORMALIZATION, never semantic equivalence from surface
    similarity: its evidence says what was normalized, not what it means.
    Passive is normalized by reversing the agent/patient after "by".
    """

    name = "SNF-A"

    def interpret(self, expression: str, context: dict) -> dict:
        s = _surface(expression)
        if s.predicate is None:
            return _candidate_set(self.name, _base_ir("", False,
                                 MODALITY_ASSERTED, "NONE", "NONE",
                                 UNC_UNKNOWN, self.name, 0.0),
                                 0.0, UNC_UNKNOWN, abstained=True,
                                 reason="no verb found",
                                 evidence=["no main verb detected"])

        ir = _base_ir(s.predicate, s.negated, s.modality, s.temporal, s.quant,
                      UNC_RESOLVED, self.name, 0.85)
        ev = [f"normalized: predicate={s.predicate}",
              f"negation={'true' if s.negated else 'false'}",
              f"modality={s.modality}"]

        # Slot filling.
        if s.is_passive():
            agent = _noun_after(s.tokens, s.by_idx + 1) if s.by_idx is not None else None
            patient = s.pre_verb_nouns[0] if s.pre_verb_nouns else None
            if agent:
                ir.arguments.append(Argument(ROLE_AGENT, agent, ARG_EXPRESSED))
                ev.append(f"passive: agent=from 'by' -> {agent}")
            if patient:
                ir.arguments.append(Argument(ROLE_PATIENT, patient, ARG_EXPRESSED))
                ev.append(f"passive: patient=pre-auxiliary -> {patient}")
        else:
            agent = s.pre_verb_nouns[0] if s.pre_verb_nouns else None
            patient = s.post_verb_nouns[0] if s.post_verb_nouns else None
            if agent:
                ir.arguments.append(Argument(ROLE_AGENT, agent, ARG_EXPRESSED))
            if patient:
                ir.arguments.append(Argument(ROLE_PATIENT, patient, ARG_EXPRESSED))

        # PP roles (canonical order after core slots).
        any_ambig = False
        for prep, obj in s.pp:
            role = PREP_ROLES[prep]
            if role == "INSTRUMENT" and prep == "with" and obj in ("manager",
                                                                  "team",
                                                                  "client"):
                ir.arguments.append(Argument(role, obj, ARG_AMBIGUOUS))
                any_ambig = True
                ev.append(f"prep '{prep} {obj}': role AMBIGUOUS (instrument/companion)")
            else:
                ir.arguments.append(Argument(role, obj, ARG_EXPRESSED))
                ev.append(f"prep '{prep} {obj}' -> {role}")
        if any_ambig:
            ir.uncertainty = UNC_AMBIGUOUS
            ev.append("uncertainty=AMBIGUOUS (underdetermined slot)")

        # Frame roles not filled -> NOT_EXPRESSED (absence, not ignorance).
        filled = {a.role for a in ir.arguments}
        for role in s.frame:
            if role not in filled:
                ir.arguments.append(Argument(role, None, ARG_NOT_EXPRESSED))
                ev.append(f"frame role {role}: NOT_EXPRESSED")

        # Missing agent or patient lowers confidence (normalization is partial).
        has_agent = any(a.role == ROLE_AGENT and a.status == ARG_EXPRESSED
                        for a in ir.arguments)
        has_patient = any(a.role == ROLE_PATIENT and a.status == ARG_EXPRESSED
                          for a in ir.arguments)
        conf = 0.85 if (has_agent and has_patient) else 0.55
        ir.confidence = conf
        ev.append(f"confidence={conf:.2f} "
                  f"(agent={'yes' if has_agent else 'no'}, "
                  f"patient={'yes' if has_patient else 'no'})")

        # Canonical argument ordering.
        order = {r: i for i, r in enumerate(ROLES)}
        ir.arguments.sort(key=lambda a: order.get(a.role, 99))
        return _candidate_set(self.name, ir, conf, ir.uncertainty, evidence=ev)


# ---------------------------------------------------------------------------
# SNF-B — Directed-graph canonicalization
# ---------------------------------------------------------------------------

class SNFB:
    """Build a DIRECTED graph; canonicalize; read the IR from edge direction.

    `cat chased dog` and `dog chased cat` produce different directed edges
    (cat ->chase-> dog vs dog ->chase-> cat), so they can never converge.
    Active/passive normalize to the SAME edge after passive reversal.
    """

    name = "SNF-B"

    def interpret(self, expression: str, context: dict) -> dict:
        s = _surface(expression)
        if s.predicate is None:
            return _candidate_set(self.name, _base_ir("", False,
                                 MODALITY_ASSERTED, "NONE", "NONE",
                                 UNC_UNKNOWN, self.name, 0.0),
                                 0.0, UNC_UNKNOWN, abstained=True,
                                 reason="no verb found",
                                 evidence=["no verb node in graph"])

        ir = _base_ir(s.predicate, s.negated, s.modality, s.temporal, s.quant,
                      UNC_RESOLVED, self.name, 0.8)
        ev = [f"graph: predicate node={s.predicate}",
              f"directed edges from surface"]

        # Directed edges: subject ->(predicate)-> object, or reversed in passive.
        if s.is_passive():
            patient_node = s.pre_verb_nouns[0] if s.pre_verb_nouns else None
            agent_node = _noun_after(s.tokens, s.by_idx + 1) if s.by_idx is not None else None
            ev.append("passive reversal applied (edge direction normalized)")
        else:
            agent_node = s.pre_verb_nouns[0] if s.pre_verb_nouns else None
            patient_node = s.post_verb_nouns[0] if s.post_verb_nouns else None

        if agent_node:
            ir.arguments.append(Argument(ROLE_AGENT, agent_node, ARG_EXPRESSED))
        if patient_node:
            ir.arguments.append(Argument(ROLE_PATIENT, patient_node, ARG_EXPRESSED))
        ev.append(f"edges: {'->'.join(x or '?' for x in [agent_node, s.predicate, patient_node])}")

        # PP edges (typed by role).
        for prep, obj in s.pp:
            role = PREP_ROLES[prep]
            ir.arguments.append(Argument(role, obj, ARG_EXPRESSED))
            ev.append(f"PP edge '{prep}' -> {role}:{obj}")

        # Frame roles missing -> NOT_EXPRESSED.
        filled = {a.role for a in ir.arguments}
        for role in s.frame:
            if role not in filled:
                ir.arguments.append(Argument(role, None, ARG_NOT_EXPRESSED))
        conf = 0.8 if agent_node and patient_node else 0.5
        ir.confidence = conf
        return _candidate_set(self.name, ir, conf, UNC_RESOLVED, evidence=ev)


# ---------------------------------------------------------------------------
# SNF-C — Pāṇinian kāraka semantic roles
# ---------------------------------------------------------------------------

class SNFC:
    """Kāraka role assignment with EXPLICIT epistemic statuses.

    kartṛ(AGENT) karman(PATIENT) karaṇa(INSTRUMENT) sampradāna(RECIPIENT)
    adhikaraṇa(LOCATION) apādāna(SOURCE).

    Rules:
      * pre-verbal NP -> kartṛ, unless passive then post-"by" NP -> kartṛ.
      * post-verbal bare NP -> karman.
      * 'to/for' -> sampradāna; 'from' -> apādāna; 'in/at' -> adhikaraṇa;
        'using/via' -> karaṇa; 'with' -> karaṇa, but AMBIGUOUS if the filler
        is animate (could be a companion/beneficiary, not an instrument).
      * frame roles unexpressed -> NOT_EXPRESSED (NEVER inferred as EXPRESSED).
      * a role the surface does not license -> NOT_LICENSED.
      * 'no verb' or genuinely undecidable -> ABSTAIN.
    Central hypothesis to TEST (not an architectural fact): explicit role
    structure + abstention may preserve distinctions better than normalization.
    """

    name = "SNF-C"

    def interpret(self, expression: str, context: dict) -> dict:
        s = _surface(expression)
        if s.predicate is None:
            return _candidate_set(self.name, _base_ir("", False,
                                 MODALITY_ASSERTED, "NONE", "NONE",
                                 UNC_UNKNOWN, self.name, 0.0),
                                 0.0, UNC_UNKNOWN, abstained=True,
                                 reason="no kāraka frame applicable",
                                 evidence=["no verb -> no kartṛ frame"])

        ir = _base_ir(s.predicate, s.negated, s.modality, s.temporal, s.quant,
                      UNC_RESOLVED, self.name, 0.8)
        ev = [f"frame for {s.predicate}: kartṛ,karman"
              + (",karaṇa" if ROLE_INSTRUMENT in s.frame else "")
              + (",sampradāna" if ROLE_RECIPIENT in s.frame else "")
              + (",adhikaraṇa" if ROLE_LOCATION in s.frame else "")]

        any_ambig = False

        # kartṛ / karman.
        if s.is_passive():
            agent = _noun_after(s.tokens, s.by_idx + 1) if s.by_idx is not None else None
            patient = s.pre_verb_nouns[0] if s.pre_verb_nouns else None
            if agent:
                ir.arguments.append(Argument(ROLE_AGENT, agent, ARG_EXPRESSED))
            if patient:
                ir.arguments.append(Argument(ROLE_PATIENT, patient, ARG_EXPRESSED))
        else:
            agent = s.pre_verb_nouns[0] if s.pre_verb_nouns else None
            patient = s.post_verb_nouns[0] if s.post_verb_nouns else None
            if agent:
                ir.arguments.append(Argument(ROLE_AGENT, agent, ARG_EXPRESSED))
            if patient:
                ir.arguments.append(Argument(ROLE_PATIENT, patient, ARG_EXPRESSED))

        # Optional kārakas from prepositions.
        for prep, obj in s.pp:
            role = PREP_ROLES[prep]
            if role == "INSTRUMENT" and prep == "with" and obj in ("manager",
                                                                  "team",
                                                                  "client"):
                ir.arguments.append(Argument(role, obj, ARG_AMBIGUOUS))
                any_ambig = True
                ev.append(f"karaṇa/companion ambiguous: 'with {obj}'")
            else:
                ir.arguments.append(Argument(role, obj, ARG_EXPRESSED))

        # Frame roles absent from surface -> NOT_EXPRESSED. Do NOT invent them.
        filled = {a.role for a in ir.arguments}
        for role in s.frame:
            if role not in filled:
                ir.arguments.append(Argument(role, None, ARG_NOT_EXPRESSED))
                ev.append(f"kāraka {role}: NOT_EXPRESSED (not guessed)")

        # Core roles absent entirely -> higher uncertainty (incomplete surface).
        has_agent = any(a.role == ROLE_AGENT and a.status == ARG_EXPRESSED
                        for a in ir.arguments)
        has_patient = any(a.role == ROLE_PATIENT and a.status == ARG_EXPRESSED
                          for a in ir.arguments)
        if not has_agent or not has_patient:
            ir.uncertainty = UNC_AMBIGUOUS
            ev.append("core kāraka missing -> uncertainty=AMBIGUOUS")

        conf = 0.8
        if any_ambig:
            conf = 0.6
            ir.uncertainty = UNC_AMBIGUOUS
            ev.append("uncertainty=AMBIGUOUS (underdetermined kāraka)")
        if not has_agent or not has_patient:
            conf = 0.5
        ir.confidence = conf
        return _candidate_set(self.name, ir, conf, ir.uncertainty, evidence=ev)


# ---------------------------------------------------------------------------
# SNF-D — SAIT/NSID closed-world registered-identifier registry
# ---------------------------------------------------------------------------

class SNFD:
    """Genuinely closed-world. Resolve registered, abstain on unknown.

    * predicate must be REGISTERED, or ABSTAIN.
    * every content noun must be a REGISTERED identifier, or ABSTAIN
      (never invent identifiers).
    * within registered terms, resolve by active/passive slot filling.
    * distinguishes UNKNOWN (abstain) from KNOWN (resolve) explicitly.
    """

    name = "SNF-D"

    def interpret(self, expression: str, context: dict) -> dict:
        s = _surface(expression)
        ev = ["closed-world registry lookup"]

        if s.predicate is None:
            return _candidate_set(self.name, _base_ir("", False,
                                 MODALITY_ASSERTED, "NONE", "NONE",
                                 UNC_UNKNOWN, self.name, 0.0),
                                 0.0, UNC_UNKNOWN, abstained=True,
                                 reason="no registered predicate resolvable",
                                 evidence=ev + ["no verb"])

        if s.predicate not in REGISTERED_PREDICATES:
            return _candidate_set(self.name, _base_ir("", False,
                                 MODALITY_ASSERTED, "NONE", "NONE",
                                 UNC_UNKNOWN, self.name, 0.0),
                                 0.0, UNC_UNKNOWN, abstained=True,
                                 reason=f"predicate {s.predicate} not registered",
                                 evidence=ev + [f"UNREGISTERED predicate: {s.predicate}"])

        # Identifier registry check — compare CANONICAL identifiers (NOUNS
        # mapping) against the registry, not surface tokens: a plural of a
        # registered identifier IS that identifier. ("reports" -> "report".)
        surface_nouns = [t for t in s.tokens if t in NOUNS]
        unregistered = [n for n in surface_nouns if NOUNS[n] not in REGISTERED_ENTITIES]
        if unregistered:
            return _candidate_set(self.name, _base_ir("", False,
                                 MODALITY_ASSERTED, "NONE", "NONE",
                                 UNC_UNKNOWN, self.name, 0.0),
                                 0.0, UNC_UNKNOWN, abstained=True,
                                 reason=f"unknown identifier(s): {unregistered}",
                                 evidence=ev +
                                 [f"UNKNOWN identifiers (abstain, never invent): {unregistered}"])

        # Resolve.
        ir = _base_ir(s.predicate, s.negated, s.modality, s.temporal, s.quant,
                      UNC_RESOLVED, self.name, 0.95)
        if s.is_passive():
            agent = _noun_after(s.tokens, s.by_idx + 1) if s.by_idx is not None else None
            patient = s.pre_verb_nouns[0] if s.pre_verb_nouns else None
        else:
            agent = s.pre_verb_nouns[0] if s.pre_verb_nouns else None
            patient = s.post_verb_nouns[0] if s.post_verb_nouns else None

        if agent:
            ir.arguments.append(Argument(ROLE_AGENT, agent, ARG_EXPRESSED))
        if patient:
            ir.arguments.append(Argument(ROLE_PATIENT, patient, ARG_EXPRESSED))
        for prep, obj in s.pp:
            ir.arguments.append(Argument(PREP_ROLES[prep], obj, ARG_EXPRESSED))

        filled = {a.role for a in ir.arguments}
        for role in s.frame:
            if role not in filled:
                ir.arguments.append(Argument(role, None, ARG_NOT_EXPRESSED))

        ev.append(f"resolved to registered meaning: {ir.predicate}"
                  + "".join(f" {a.role}={a.entity}" for a in ir.arguments))
        return _candidate_set(self.name, ir, 0.95, UNC_RESOLVED, evidence=ev)


# ---------------------------------------------------------------------------
# SNF-E — Constitutional veto ensemble (NOT a weighted average)
# ---------------------------------------------------------------------------

class SNFE:
    """Runs A/B/C/D, performs DIVERGENCE ANALYSIS, then arbitrates.

    NOT a weighted average: score = wA*A + wB*B + wC*C is FORBIDDEN here.
    Output is one member's candidate IR (the least-divergent one inside its
    agreement cluster), or an abstention. The arbitration rule is an
    EXPERIMENTAL VARIABLE — the pilot measures behavior under it, it does not
    endorse any rule.

    Rules (config.rule):
      "unanimity"        promote only if ALL members agree (d < tau_agree)
      "majority"         promote if >= 3 of 4 agree
      "pairwise"         promote if >= 2 of 4 agree
      "abstain_conflict" promote on majority, else ABSTAIN (default)
      "veto"             any member abstention blocks promotion
    """

    name = "SNF-E"

    def __init__(self, rule: str = "abstain_conflict", tau_agree: float = 0.30):
        if rule not in ("unanimity", "majority", "pairwise", "abstain_conflict",
                        "veto"):
            raise ValueError(f"unknown arbitration rule: {rule}")
        self.rule = rule
        self.tau_agree = tau_agree
        self.members = [SNFA(), SNFB(), SNFC(), SNFD()]
        self._last_divergence = None  # for reporting

    def _run_members(self, expression: str, context: dict):
        return {m.name: m.interpret(expression, context) for m in self.members}

    def _rep_ir(self, out: dict) -> IR | None:
        if out["abstained"] or not out["candidates"]:
            return None
        return IR.from_dict(out["candidates"][0]["representation"])

    def _pairwise(self, irs: dict[str, IR]):
        from distance import d_snf
        matrix = {}
        for a in irs:
            matrix[a] = {}
            for b in irs:
                matrix[a][b] = (d_snf(irs[a], irs[b]) if a != b else 0.0)
        return matrix

    def interpret(self, expression: str, context: dict) -> dict:
        from distance import d_snf

        outs = self._run_members(expression, context)
        member_names = {m.name for m in self.members}
        irs = {m: self._rep_ir(out) for m, out in outs.items()}
        answered = {m for m, ir in irs.items() if ir is not None}
        ev = [f"members run: {','.join(m.name for m in self.members)}"]
        ev.append(f"answered: {sorted(answered)}; "
                  f"abstained: {sorted(member_names - answered)}")

        if self.rule == "veto" and len(answered) < len(self.members):
            return _candidate_set(self.name, _base_ir("", False,
                                 MODALITY_ASSERTED, "NONE", "NONE",
                                 UNC_UNKNOWN, self.name, 0.0),
                                 0.0, UNC_UNKNOWN, abstained=True,
                                 reason="member abstention vetoed promotion",
                                 evidence=ev)

        matrix = self._pairwise({m: irs[m] for m in answered})
        self._last_divergence = matrix

        # Agreement clustering: greedily find the largest cluster within tau.
        members = sorted(answered)
        best_cluster: list[str] = []
        for pivot in members:
            cluster = [pivot]
            for other in members:
                if other != pivot and matrix[pivot][other] <= self.tau_agree:
                    cluster.append(other)
            if len(cluster) > len(best_cluster):
                best_cluster = cluster
        ev.append(f"agreement cluster: {sorted(best_cluster)} "
                  f"(rule={self.rule}, tau_agree={self.tau_agree})")
        ev.append(f"divergence matrix: "
                  + "; ".join(f"{a}<->{b}:{matrix[a][b]:.2f}"
                              for a in members for b in members
                              if a < b))

        n = len(best_cluster)
        if self.rule == "unanimity":
            promote = n == len(self.members)
        elif self.rule in ("majority", "abstain_conflict", "veto"):
            promote = n >= max(3, len(self.members) - 1)
        else:  # pairwise
            promote = n >= 2

        if not promote:
            if n >= 2:
                rep = best_cluster[0]
                return _candidate_set(self.name, irs[rep], 0.5,
                                      UNC_AMBIGUOUS, evidence=ev +
                                      [f"partial agreement (cluster={sorted(best_cluster)}); "
                                       "candidate + divergence note"])
            return _candidate_set(self.name, _base_ir("", False,
                                 MODALITY_ASSERTED, "NONE", "NONE",
                                 UNC_UNKNOWN, self.name, 0.0),
                                 0.0, UNC_UNKNOWN, abstained=True,
                                 reason="incompatible member outputs (no agreement)",
                                 evidence=ev + ["no agreement cluster -> ABSTAIN"])

        # Promote the least-divergent member of the cluster.
        rep = min(best_cluster, key=lambda m: sum(
            matrix[m][o] for o in best_cluster if o != m))
        ir = irs[rep]
        conf = 0.6 + 0.1 * (n - 2)
        ir.confidence = min(0.95, conf)
        ev.append(f"PROMOTE member {rep} (least-divergent in cluster); "
                  f"agreement count={n}")
        return _candidate_set(self.name, ir, ir.confidence, UNC_RESOLVED,
                              evidence=ev)


# ---------------------------------------------------------------------------
# SNF-N — Null baseline
# ---------------------------------------------------------------------------

class SFNN:
    """Deliberately weak baseline: most-common-candidate prior, never abstains.

    Purpose: answer the question "is abstention/convergence genuinely
    informative?" If SNF-C's abstentions correlate with real difficulty while
    this null mechanism (0 abstention, constant confidence) is worse on
    selective accuracy, then abstention carries information.
    """

    name = "SNF-N"

    def interpret(self, expression: str, context: dict) -> dict:
        ir = IR(predicate=NULL_PRIOR_PREDICATE,
                arguments=[Argument(ROLE_AGENT, NULL_PRIOR_AGENT, ARG_EXPRESSED),
                           Argument(ROLE_PATIENT, NULL_PRIOR_PATIENT, ARG_EXPRESSED)],
                negation=False, modality=MODALITY_ASSERTED, temporal="NONE",
                quantification="NONE", uncertainty=UNC_RESOLVED,
                confidence=0.5, provenance=self.name)
        return _candidate_set(self.name, ir, 0.5, UNC_RESOLVED,
                              evidence=["null baseline: prior only "
                                        f"({NULL_PRIOR_PREDICATE})",
                                        "never abstains by design"])


# ---------------------------------------------------------------------------
# Registry of all mechanisms
# ---------------------------------------------------------------------------

ALL_MECHANISMS = {
    "SNF-A": SNFA,
    "SNF-B": SNFB,
    "SNF-C": SNFC,
    "SNF-D": SNFD,
    "SNF-E": SNFE,
    "SNF-N": SFNN,
}


def instantiate(name: str, **kwargs):
    return ALL_MECHANISMS[name](**kwargs)


if __name__ == "__main__":
    # Smoke test: each mechanism runs and returns a serializable CandidateSet.
    cases = [
        "The committee approved the order.",
        "The order was approved by the committee.",
        "The cat chased the dog.",
        "The dog chased the cat.",
        "The committee did not approve the order.",
        "The committee rejected the order.",
        "The engineer cleaned the machine with a brush.",
        "The engineer cleaned the machine with the manager.",
    ]
    for c in cases:
        print("=" * 78)
        print("EXPR:", c)
        for name in ("SNF-A", "SNF-B", "SNF-C", "SNF-D", "SNF-E", "SNF-N"):
            out = instantiate(name).interpret(c, {"domain": "business",
                                                  "language": "en"})
            cand = out["candidates"][0]["representation"]
            args = " ".join(f"{a['role']}={a['entity']}({a['status']})"
                            for a in cand["arguments"])
            print(f"  {name:<5} {'ABSTAIN: ' + out['abstention_reason'] if out['abstained'] else ''}"
                  f"{cand['predicate']} neg={cand['negation']} u={cand['uncertainty']} "
                  f"conf={cand['confidence']:.2f}  [{args}]")
