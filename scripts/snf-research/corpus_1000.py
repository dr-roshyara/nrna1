"""KOS-SNF-1000 — the P5 competition corpus.

CONSTRUCTION RULE (HPA decision Q-4, 2026-08-22):

    "Templates generate surface cases; they must not automatically generate
     semantic gold. The gold relationship must be explicitly declared and
     independently reviewable. Otherwise we risk testing whether the generator
     agrees with itself."

So every template carries THREE hand-written things:
  * `relation`   — the declared semantic relationship between the expressions
  * `rationale`  — why that relation holds, in one reviewable sentence
  * a gold builder that states the IRs explicitly, slot by slot

Nothing in this module imports, calls, or consults a semantic candidate
producer, and nothing here consults the evaluation distance. The gold is a
human declaration; the surface is generated; the two are never derived from
each other.

SCOPE (HPA decisions Q-1 and Q-2): this is a TOY WORLD — one small verb
lexicon, one closed entity set, hand-written gold. Results obtained on it are
evidence about the apparatus and the candidate producers, NOT evidence about
natural language. Families that a toy world cannot represent honestly are
EXCLUDED and recorded, not faked.
"""

from __future__ import annotations

import _bootstrap  # noqa: F401

import hashlib
import json
import os
from dataclasses import dataclass, field
from typing import Callable

from ir import (IR, Argument, ROLE_AGENT, ROLE_PATIENT, ROLE_INSTRUMENT,
                ROLE_RECIPIENT, ROLE_LOCATION, ARG_EXPRESSED, ARG_UNKNOWN,
                ARG_NOT_EXPRESSED, ARG_AMBIGUOUS, MODALITY_ASSERTED,
                MODALITY_POSSIBLE, MODALITY_NECESSARY, TEMP_NONE, TEMP_PAST,
                TEMP_FUTURE, QUANT_NONE, QUANT_ALL, QUANT_SOME,
                UNC_RESOLVED, UNC_AMBIGUOUS, UNC_UNKNOWN)

CORPUS_VERSION = "KOS-SNF-1000 v0.1"
SCOPE_NOTE = (
    "Toy world. One verb lexicon, one closed entity set, hand-written gold. "
    "Results are evidence about the apparatus and the candidate producers, and "
    "are NOT evidence about natural language, translation, metaphor, "
    "pragmatics, or institutional meaning."
)

# HPA decision Q-2 — excluded, recorded, NOT manufactured in a toy world.
EXCLUDED_FAMILIES = {
    "translation": "requires a real bilingual corpus and native gold",
    "metaphor": "requires figurative-language competence absent from the world",
    "pragmatics": "requires discourse context and speaker intent",
    "legal_semantics": "requires an institutional interpretive frame",
    "narrative_semantics": "requires multi-sentence discourse structure",
    "institutional_meaning": "requires an institution and its rules",
    "procedural_semantics": "requires process state, not single propositions",
    "technical_ontology": "requires a real domain ontology",
}

RELATIONS = ("EQUIVALENT", "PRESERVING", "DISTINCT", "AMBIGUOUS",
             "FALSE_CONSENSUS_TRAP", "ABSTAIN_EXPECTED")

FAMILIES = (
    "paraphrase_equivalent",
    "active_passive",
    "nominalization_reorder",
    "argument_inversion",
    "negation_vs_predicate",
    "modality_temporal_quantifier",
    "optional_role_presence",
    "unknown_vs_not_expressed",
    "role_omission_ambiguity",
    "genuine_ambiguity",
    "false_consensus_trap",
    "abstain_expected",
)

# Category drives the verdict logic in the evaluation layer; family drives
# reporting. Pair categories take two expressions, "ambiguous" takes one.
FAMILY_CATEGORY = {
    "paraphrase_equivalent": "equivalent",
    "active_passive": "transformation",
    "nominalization_reorder": "transformation",
    "argument_inversion": "distinct",
    "negation_vs_predicate": "distinct",
    "modality_temporal_quantifier": "distinct",
    "optional_role_presence": "distinct",
    "unknown_vs_not_expressed": "distinct",
    "role_omission_ambiguity": "ambiguous",
    "genuine_ambiguity": "ambiguous",
    "false_consensus_trap": "adversarial",
    "abstain_expected": "adversarial",
}

NO_ADMISSIBLE = "__NO_ADMISSIBLE_INTERPRETATION__"


# ---------------------------------------------------------------------------
# Declared world bindings (deterministic, ordered — no randomness anywhere)
# ---------------------------------------------------------------------------

# (verb_base, verb_past, PREDICATE) for two-role frames
V2 = [("approve", "approved", "APPROVE"),
      ("reject", "rejected", "REJECT"),
      ("review", "reviewed", "REVIEW"),
      ("sign", "signed", "SIGN"),
      ("chase", "chased", "CHASE")]

# three-role frames, by the third role
V3_LOC = [("store", "stored", "STORE")]
V3_INSTR = [("clean", "cleaned", "CLEAN"),
            ("manufacture", "manufactured", "MANUFACTURE")]
V3_RECIP = [("submit", "submitted", "SUBMIT"),
            ("send", "sent", "SEND")]

AGENTS = ["committee", "engineer", "manager", "council", "accountant", "team",
          "cat", "dog"]
PATIENTS = ["order", "invoice", "report", "contract", "proposal", "file",
            "package", "email"]
LOCATIONS = ["server", "factory", "room", "table", "system"]
INSTRUMENTS = ["machine", "tool", "brush", "laptop"]
RECIPIENTS = ["client", "vendor", "council", "manager"]
DETS = ["the", "a", "this", "that"]

# Out-of-world surface material: nothing here is in the toy lexicon, so no
# candidate producer can honestly interpret it. Abstention is the only correct
# behaviour, and the corpus DECLARES that.
OUT_OF_WORLD = [
    ("The quartermaster requisitioned the ordnance.",
     "The ordnance was requisitioned by the quartermaster."),
    ("The registrar transcribed the cadastre.",
     "The cadastre was transcribed by the registrar."),
    ("The apiarist inspected the hive.",
     "The hive was inspected by the apiarist."),
    ("The luthier varnished the soundboard.",
     "The soundboard was varnished by the luthier."),
    ("The archivist collated the folios.",
     "The folios were collated by the archivist."),
]


def _pick(seq, i):
    return seq[i % len(seq)]


# ---------------------------------------------------------------------------
# Gold constructors — every slot stated explicitly by hand
# ---------------------------------------------------------------------------

def _arg(role, entity, status=ARG_EXPRESSED):
    return Argument(role=role, entity=entity, status=status)


def _ir(predicate, args, negation=False, modality=MODALITY_ASSERTED,
        temporal=TEMP_NONE, quantification=QUANT_NONE,
        uncertainty=UNC_RESOLVED):
    return IR(predicate=predicate, arguments=args, negation=negation,
              modality=modality, temporal=temporal,
              quantification=quantification, uncertainty=uncertainty)


# ---------------------------------------------------------------------------
# Template model
# ---------------------------------------------------------------------------

@dataclass
class Template:
    name: str
    family: str
    relation: str
    rationale: str                 # hand-written, reviewable
    n: int
    build: Callable[[int], dict]   # index -> {expressions, gold_irs, ...}
    category: str = ""

    def __post_init__(self):
        self.category = FAMILY_CATEGORY[self.family]


# --- 1. paraphrase: determiner / number variation, same declared meaning ----

def _t_paraphrase(i):
    vb, vp, pred = _pick(V2, i)
    a, p = _pick(AGENTS, i), _pick(PATIENTS, i + 1)
    d1, d2 = _pick(DETS, i), _pick(DETS, i + 2)
    e1 = f"{d1.capitalize()} {a} {vp} {d1} {p}."
    e2 = f"{d2.capitalize()} {a}s {vp} {d2} {p}s."
    gold = _ir(pred, [_arg(ROLE_AGENT, a), _arg(ROLE_PATIENT, p)])
    return {"expressions": [e1, e2], "gold_irs": [gold, gold]}


# --- 2. active / passive: transformation that must preserve meaning ---------

def _t_active_passive(i):
    vb, vp, pred = _pick(V2, i)
    a, p = _pick(AGENTS, i + 2), _pick(PATIENTS, i)
    e1 = f"The {a} {vp} the {p}."
    e2 = f"The {p} was {vp} by the {a}."
    gold = _ir(pred, [_arg(ROLE_AGENT, a), _arg(ROLE_PATIENT, p)])
    return {"expressions": [e1, e2], "gold_irs": [gold, gold]}


# --- 3. reordering with an adjunct kept constant ----------------------------

def _t_reorder(i):
    vb, vp, pred = _pick(V3_LOC, i)
    a, p, loc = _pick(AGENTS, i), _pick(PATIENTS, i + 3), _pick(LOCATIONS, i)
    e1 = f"The {a} {vp} the {p} in the {loc}."
    e2 = f"In the {loc} the {a} {vp} the {p}."
    gold = _ir(pred, [_arg(ROLE_AGENT, a), _arg(ROLE_PATIENT, p),
                      _arg(ROLE_LOCATION, loc)])
    return {"expressions": [e1, e2], "gold_irs": [gold, gold]}


# --- 4. argument inversion: same words, opposite direction -----------------

def _t_inversion(i):
    vb, vp, pred = _pick(V2, i)
    a, b = _pick(AGENTS, i), _pick(AGENTS, i + 3)
    if a == b:
        b = _pick(AGENTS, i + 4)
    e1 = f"The {a} {vp} the {b}."
    e2 = f"The {b} {vp} the {a}."
    g1 = _ir(pred, [_arg(ROLE_AGENT, a), _arg(ROLE_PATIENT, b)])
    g2 = _ir(pred, [_arg(ROLE_AGENT, b), _arg(ROLE_PATIENT, a)])
    return {"expressions": [e1, e2], "gold_irs": [g1, g2]}


# --- 5. negation vs predicate change ---------------------------------------

def _t_negation(i):
    vb, vp, pred = _pick(V2, i)
    a, p = _pick(AGENTS, i + 1), _pick(PATIENTS, i)
    e1 = f"The {a} {vp} the {p}."
    e2 = f"The {a} did not {vb} the {p}."
    g1 = _ir(pred, [_arg(ROLE_AGENT, a), _arg(ROLE_PATIENT, p)])
    g2 = _ir(pred, [_arg(ROLE_AGENT, a), _arg(ROLE_PATIENT, p)], negation=True)
    return {"expressions": [e1, e2], "gold_irs": [g1, g2]}


# --- 6. modality / temporal / quantifier variation -------------------------

def _t_mtq(i):
    vb, vp, pred = _pick(V2, i)
    a, p = _pick(AGENTS, i), _pick(PATIENTS, i + 2)
    variant = i % 3
    base = _ir(pred, [_arg(ROLE_AGENT, a), _arg(ROLE_PATIENT, p)])
    if variant == 0:
        e1 = f"The {a} {vp} the {p}."
        e2 = f"The {a} may {vb} the {p}."
        g2 = _ir(pred, [_arg(ROLE_AGENT, a), _arg(ROLE_PATIENT, p)],
                 modality=MODALITY_POSSIBLE)
    elif variant == 1:
        e1 = f"Yesterday the {a} {vp} the {p}."
        e2 = f"Tomorrow the {a} {vp} the {p}."
        base = _ir(pred, [_arg(ROLE_AGENT, a), _arg(ROLE_PATIENT, p)],
                   temporal=TEMP_PAST)
        g2 = _ir(pred, [_arg(ROLE_AGENT, a), _arg(ROLE_PATIENT, p)],
                 temporal=TEMP_FUTURE)
    else:
        e1 = f"All {a}s {vp} the {p}."
        e2 = f"Some {a}s {vp} the {p}."
        base = _ir(pred, [_arg(ROLE_AGENT, a), _arg(ROLE_PATIENT, p)],
                   quantification=QUANT_ALL)
        g2 = _ir(pred, [_arg(ROLE_AGENT, a), _arg(ROLE_PATIENT, p)],
                 quantification=QUANT_SOME)
    return {"expressions": [e1, e2], "gold_irs": [base, g2]}


# --- 7. optional-role PRESENCE (the presence trap) -------------------------

def _t_presence(i):
    kind = i % 3
    if kind == 0:
        vb, vp, pred = _pick(V3_LOC, i)
        a, p, x = _pick(AGENTS, i), _pick(PATIENTS, i), _pick(LOCATIONS, i)
        role, prep = ROLE_LOCATION, "in"
    elif kind == 1:
        vb, vp, pred = _pick(V3_INSTR, i)
        a, p, x = (_pick(AGENTS, i + 1), _pick(PATIENTS, i + 1),
                   _pick(INSTRUMENTS, i))
        role, prep = ROLE_INSTRUMENT, "using"
    else:
        vb, vp, pred = _pick(V3_RECIP, i)
        a, p, x = (_pick(AGENTS, i + 2), _pick(PATIENTS, i + 2),
                   _pick(RECIPIENTS, i))
        role, prep = ROLE_RECIPIENT, "to"
    e1 = f"The {a} {vp} the {p} {prep} the {x}."
    e2 = f"The {a} {vp} the {p}."
    g1 = _ir(pred, [_arg(ROLE_AGENT, a), _arg(ROLE_PATIENT, p), _arg(role, x)])
    g2 = _ir(pred, [_arg(ROLE_AGENT, a), _arg(ROLE_PATIENT, p),
                    _arg(role, None, ARG_NOT_EXPRESSED)])
    return {"expressions": [e1, e2], "gold_irs": [g1, g2]}


# --- 8. UNKNOWN vs NOT_EXPRESSED (epistemic vs semantic absence) ----------

def _t_unknown_absent(i):
    vb, vp, pred = _pick(V3_LOC, i)
    a, p = _pick(AGENTS, i + 3), _pick(PATIENTS, i + 1)
    e1 = f"The {a} {vp} the {p}."
    e2 = f"The {a} {vp} the {p} in it."
    g1 = _ir(pred, [_arg(ROLE_AGENT, a), _arg(ROLE_PATIENT, p),
                    _arg(ROLE_LOCATION, None, ARG_NOT_EXPRESSED)])
    g2 = _ir(pred, [_arg(ROLE_AGENT, a), _arg(ROLE_PATIENT, p),
                    _arg(ROLE_LOCATION, None, ARG_UNKNOWN)],
             uncertainty=UNC_AMBIGUOUS)
    return {"expressions": [e1, e2], "gold_irs": [g1, g2]}


# --- 9. role omission -> underdetermined (single expression) ---------------

def _t_role_omission(i):
    vb, vp, pred = _pick(V3_RECIP, i)
    p = _pick(PATIENTS, i)
    e = f"The {p} was {vp}."
    intended = _ir(pred, [_arg(ROLE_AGENT, None, ARG_UNKNOWN),
                          _arg(ROLE_PATIENT, p),
                          _arg(ROLE_RECIPIENT, None, ARG_UNKNOWN)],
                   uncertainty=UNC_AMBIGUOUS)
    alt = _ir(pred, [_arg(ROLE_AGENT, None, ARG_UNKNOWN),
                     _arg(ROLE_PATIENT, p),
                     _arg(ROLE_RECIPIENT, None, ARG_NOT_EXPRESSED)],
              uncertainty=UNC_AMBIGUOUS)
    return {"expressions": [e], "gold_irs": [intended, alt]}


# --- 10. genuine ambiguity: instrument vs companion "with" ----------------

def _t_genuine_ambiguity(i):
    vb, vp, pred = _pick(V3_INSTR, i)
    a, p = _pick(AGENTS, i), _pick(PATIENTS, i + 4)
    companion = _pick(["manager", "accountant", "engineer", "team"], i)
    e = f"The {a} {vp} the {p} with the {companion}."
    instr_reading = _ir(pred, [_arg(ROLE_AGENT, a), _arg(ROLE_PATIENT, p),
                               _arg(ROLE_INSTRUMENT, companion, ARG_AMBIGUOUS)],
                        uncertainty=UNC_AMBIGUOUS)
    companion_reading = _ir(pred, [_arg(ROLE_AGENT, a), _arg(ROLE_PATIENT, p),
                                   _arg(ROLE_INSTRUMENT, None,
                                        ARG_NOT_EXPRESSED)],
                            uncertainty=UNC_AMBIGUOUS)
    return {"expressions": [e], "gold_irs": [instr_reading,
                                             companion_reading]}


# --- 11. false-consensus trap: same surface shape, different declared role --

def _t_false_consensus(i):
    vb, vp, pred = _pick(V3_INSTR, i)
    a, p = _pick(AGENTS, i + 1), _pick(PATIENTS, i + 3)
    instrument = _pick(INSTRUMENTS, i)
    companion = _pick(["manager", "accountant", "team", "council"], i)
    e1 = f"The {a} {vp} the {p} with the {instrument}."
    e2 = f"The {a} {vp} the {p} with the {companion}."
    g1 = _ir(pred, [_arg(ROLE_AGENT, a), _arg(ROLE_PATIENT, p),
                    _arg(ROLE_INSTRUMENT, instrument)])
    g2 = _ir(pred, [_arg(ROLE_AGENT, a), _arg(ROLE_PATIENT, p),
                    _arg(ROLE_INSTRUMENT, None, ARG_NOT_EXPRESSED)])
    return {"expressions": [e1, e2], "gold_irs": [g1, g2]}


# --- 12. out-of-world: abstention is the only correct behaviour ------------

def _t_abstain_expected(i):
    e1, e2 = _pick(OUT_OF_WORLD, i)
    null = _ir(NO_ADMISSIBLE, [], uncertainty=UNC_UNKNOWN)
    return {"expressions": [e1, e2], "gold_irs": [null, null]}


TEMPLATES = [
    Template("paraphrase", "paraphrase_equivalent", "EQUIVALENT",
             "Determiner and number variation only; no role, polarity, "
             "modality, tense or quantifier changes, so the declared meaning "
             "is identical.", 120, _t_paraphrase),
    Template("active_passive", "active_passive", "PRESERVING",
             "Voice alternation moves the surface positions of AGENT and "
             "PATIENT but the declared role assignment is unchanged.",
             90, _t_active_passive),
    Template("reorder_adjunct", "nominalization_reorder", "PRESERVING",
             "Fronting the locative adjunct changes word order only; the "
             "declared roles and the LOCATION filler are unchanged.",
             90, _t_reorder),
    Template("inversion", "argument_inversion", "DISTINCT",
             "The same lexical items with AGENT and PATIENT exchanged; who "
             "acts on whom is reversed, so the meanings differ.",
             100, _t_inversion),
    Template("negation", "negation_vs_predicate", "DISTINCT",
             "Predicate polarity is reversed. An asserted event and its "
             "denial are different meanings, not degrees of one meaning.",
             100, _t_negation),
    Template("modality_temporal_quantifier", "modality_temporal_quantifier",
             "DISTINCT",
             "Exactly one of modality, tense or quantification differs. An "
             "asserted event is not a possible one; past is not future; all "
             "is not some.", 120, _t_mtq),
    Template("optional_role_presence", "optional_role_presence", "DISTINCT",
             "One expression fills an optional role (LOCATION / INSTRUMENT / "
             "RECIPIENT) and the other leaves it unexpressed. The presence of "
             "an optional participant is part of what was said, so these are "
             "declared DISTINCT even though the two gold IRs share every "
             "other slot — this is the presence trap.",
             110, _t_presence),
    Template("unknown_vs_not_expressed", "unknown_vs_not_expressed", "DISTINCT",
             "One expression does not mention the role at all (semantic "
             "absence: NOT_EXPRESSED); the other refers to it with an "
             "unresolvable pronoun (epistemic absence: UNKNOWN). Not knowing "
             "a filler is not the same as there being no filler.",
             110, _t_unknown_absent),
    Template("role_omission", "role_omission_ambiguity", "AMBIGUOUS",
             "A short passive leaves AGENT unrecoverable and the RECIPIENT "
             "either unmentioned or unknown; the surface is underdetermined, "
             "so abstention or an explicitly hedged reading is correct.",
             60, _t_role_omission),
    Template("with_ambiguity", "genuine_ambiguity", "AMBIGUOUS",
             "'with X' licenses both an instrument reading and a companion "
             "reading and the surface does not decide between them; a single "
             "confident reading asserts more than was said.",
             60, _t_genuine_ambiguity),
    Template("with_trap", "false_consensus_trap", "FALSE_CONSENSUS_TRAP",
             "Both expressions have the identical surface shape 'with the X', "
             "but in one X is declared the INSTRUMENT and in the other X is a "
             "companion and the instrument is unexpressed. A producer that "
             "maps 'with NP' to INSTRUMENT by rule will agree with every "
             "other such producer and all of them will be wrong together.",
             90, _t_false_consensus),
    Template("out_of_world", "abstain_expected", "ABSTAIN_EXPECTED",
             "Neither the predicate nor the participants exist in the toy "
             "world, so no admissible interpretation is available to any "
             "producer. Abstention is the only correct behaviour; answering "
             "here is a false acceptance, which separates prudence from "
             "inability.", 50, _t_abstain_expected),
]


# ---------------------------------------------------------------------------
# Assembly
# ---------------------------------------------------------------------------

def build_corpus() -> list[dict]:
    """Deterministically assemble the corpus. Same input -> same output."""
    cases: list[dict] = []
    for t in TEMPLATES:
        for i in range(t.n):
            built = t.build(i)
            ambiguous = t.category == "ambiguous"
            cid = f"{t.family}-{i:04d}"
            cases.append({
                "id": cid,
                "family": t.family,
                "category": t.category,
                "relation": t.relation,
                "rationale": t.rationale,
                "template": t.name,
                "expressions": built["expressions"],
                "gold": {
                    "irs": [g.to_dict() for g in built["gold_irs"]],
                    "ambiguous": ambiguous,
                    "abstain_expected": t.relation == "ABSTAIN_EXPECTED",
                    "declared_by": "hand-written template declaration",
                },
            })
    _verify(cases)
    return cases


def _verify(cases: list[dict]) -> None:
    """Structural verification. Never touches a candidate producer."""
    ids = [c["id"] for c in cases]
    assert len(ids) == len(set(ids)), "duplicate case ids"
    for c in cases:
        assert c["relation"] in RELATIONS, c["id"]
        assert c["family"] in FAMILIES, c["id"]
        assert c["family"] not in EXCLUDED_FAMILIES, c["id"]
        assert c["rationale"].strip(), c["id"]
        n_expected = 1 if c["gold"]["ambiguous"] else 2
        assert len(c["expressions"]) == n_expected, c["id"]
        assert len(c["gold"]["irs"]) == 2, c["id"]
        for e in c["expressions"]:
            assert e.strip() and e.endswith("."), c["id"]


def corpus_digest(cases: list[dict]) -> str:
    payload = json.dumps(cases, sort_keys=True, separators=(",", ":"))
    return hashlib.sha256(payload.encode()).hexdigest()


def main() -> None:
    cases = build_corpus()
    from collections import Counter
    fams = Counter(c["family"] for c in cases)
    out = {
        "version": CORPUS_VERSION,
        "scope_note": SCOPE_NOTE,
        "excluded_families": EXCLUDED_FAMILIES,
        "n_cases": len(cases),
        "families": dict(sorted(fams.items())),
        "cases": cases,
    }
    here = os.path.dirname(os.path.abspath(__file__))
    dest = os.path.join(here, "..", "..", "docs", "knowledgeos",
                        "brainstorming", "KOS-SNF-1000.json")
    dest = os.path.normpath(dest)
    with open(dest, "w", encoding="utf-8") as fh:
        json.dump(out, fh, indent=1, sort_keys=True)
        fh.write("\n")
    print(f"{CORPUS_VERSION}: {len(cases)} cases -> {dest}")
    print(f"digest: {corpus_digest(cases)[:12]}")
    for fam, n in sorted(fams.items()):
        print(f"  {fam:<32} {n:>5}  ({n / len(cases):.1%})")


if __name__ == "__main__":
    main()
