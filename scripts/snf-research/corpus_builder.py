"""KOS-SNF-pilot-100 corpus builder (100 cases, deterministic).

Split (per the HPA commission):
    20 equivalent     paraphrase pairs — SAME gold IR (C convergence target)
    20 distinct       pairs — DIFFERENT gold IR (NC / FC target)
    20 ambiguous      single expressions genuinely underdetermined
                      (abstention / uncertainty target)
    20 transformation meaning-preserving STRUCTURAL transforms — SAME gold IR
                      (T stability target)
    20 adversarial    DISTINCT pairs engineered for FALSE CONSENSUS
                      (high surface overlap, distinct meaning)

Schema:
    {
      "id": "Cnnn",
      "category": "equivalent|distinct|ambiguous|transformation|adversarial",
      "relation": "EQUIVALENT|DISTINCT|AMBIGUOUS|PRESERVING|FALSE_CONSENSUS_TRAP",
      "expressions": [e1, (e2)],
      "context": {"domain", "language"},
      "gold": {
        "irs": [gold_ir_for_each_expression],
        "ambiguous": bool,          # True only for ambiguous cases
        "alt_irs": [...],           # alternative reading(s) for ambiguous cases
        "notes": str,
      }
    }

DISCIPLINE: gold lives ONLY in this corpus, and the runner hands mechanisms
ONLY `expression` + `context`. The gold is read by the evaluator after the fact.
No mechanism ever sees gold.
"""

from __future__ import annotations

import json
import os

import _bootstrap  # noqa: F401

from ir import (IR, Argument, ROLE_AGENT, ROLE_PATIENT, ROLE_INSTRUMENT,
                ROLE_RECIPIENT, ROLE_SOURCE, ROLE_LOCATION,
                ARG_EXPRESSED, ARG_NOT_EXPRESSED, ARG_AMBIGUOUS,
                MODALITY_ASSERTED, UNC_RESOLVED, UNC_AMBIGUOUS)
from lexicon import FRAMES


def gold(predicate: str, *,
         agent=None, patient=None, instrument=None, recipient=None,
         location=None, source=None, negation: bool = False,
         modality: str = MODALITY_ASSERTED, uncertainty: str = UNC_RESOLVED,
         _ambig: tuple[str, ...] = ()) -> IR:
    """Hand-write the GOLD IR for one expression. Only the evaluation layer
    reads this. Frame roles absent from the surface are NOT_EXPRESSED;
    _ambig marks roles the gold itself considers underdetermined."""
    values = {"AGENT": agent, "PATIENT": patient, "INSTRUMENT": instrument,
              "RECIPIENT": recipient, "LOCATION": location, "SOURCE": source}
    args = []
    for role in FRAMES[predicate]:
        v = values[role]
        if v is None:
            args.append(Argument(role, None, ARG_NOT_EXPRESSED))
        else:
            args.append(Argument(role, v, ARG_AMBIGUOUS if role in _ambig
                                 else ARG_EXPRESSED))
    # Optional roles beyond the core frame, if expressed on the surface.
    for role in (ROLE_SOURCE, ROLE_RECIPIENT, ROLE_INSTRUMENT, ROLE_LOCATION):
        if role not in FRAMES[predicate] and values[role] is not None:
            args.append(Argument(role, values[role],
                                 ARG_AMBIGUOUS if role in _ambig else ARG_EXPRESSED))
    return IR(predicate=predicate, arguments=args, negation=negation,
              modality=modality, uncertainty=uncertainty, provenance="GOLD")


# ---------------------------------------------------------------------------
# Case definitions
# ---------------------------------------------------------------------------

def _pair(cid: str, category: str, relation: str, e1: str, e2: str,
          g1: IR, g2: IR, notes: str) -> dict:
    return {
        "id": cid,
        "category": category,
        "relation": relation,
        "expressions": [e1, e2],
        "context": {"domain": "business", "language": "en"},
        "gold": {"irs": [g1.to_dict(), g2.to_dict()], "ambiguous": False,
                 "alt_irs": [], "notes": notes},
    }


def _amb(cid: str, expr: str, intended: IR, alt: IR | None, notes: str) -> dict:
    return {
        "id": cid,
        "category": "ambiguous",
        "relation": "AMBIGUOUS",
        "expressions": [expr],
        "context": {"domain": "business", "language": "en"},
        "gold": {"irs": [intended.to_dict()], "ambiguous": True,
                 "alt_irs": [alt.to_dict()] if alt else [], "notes": notes},
    }


def build_corpus() -> list[dict]:
    cases: list[dict] = []
    add = cases.append

    # =====================================================================
    # 1. EQUIVALENT (20) — paraphrase, same gold IR
    # =====================================================================
    eq = []
    a = gold("APPROVE", agent="committee", patient="order")
    eq.append(("C001", "The committee approved the order.",
               "The order was approved by the committee.", a, a,
               "active/passive"))
    a = gold("CHASE", agent="cat", patient="dog")
    eq.append(("C002", "The cat chased the dog.",
               "The dog was chased by the cat.", a, a,
               "active/passive"))
    a = gold("CLEAN", agent="engineer", patient="machine", instrument="brush")
    eq.append(("C003", "The engineer cleaned the machine with a brush.",
               "The engineer cleaned the machine using a brush.", a, a,
               "with/using preposition synonym"))
    a = gold("REVIEW", agent="committee", patient="report")
    eq.append(("C004", "The committee reviewed the report.",
               "The committee reviewed the reports.", a, a,
               "patient number variation"))
    a = gold("SIGN", agent="manager", patient="contract")
    eq.append(("C005", "The manager signed the contract.",
               "The contract was signed by the manager.", a, a,
               "active/passive"))
    a = gold("SUBMIT", agent="team", patient="proposal", recipient="council")
    eq.append(("C006", "The team submitted the proposal to the council.",
               "The team submitted the proposal to the councils.", a, a,
               "recipient number variation"))
    a = gold("STORE", agent="accountant", patient="file", location="warehouse")
    eq.append(("C007", "The accountant stored the files in the warehouse.",
               "The accountant stored the file in the warehouse.", a, a,
               "patient number variation"))
    a = gold("REJECT", agent="council", patient="invoice")
    eq.append(("C008", "The council rejected the invoice.",
               "The invoice was rejected by the council.", a, a,
               "active/passive"))
    a = gold("WRITE", agent="engineer", patient="report")
    eq.append(("C009", "The engineer wrote the report.",
               "The engineer wrote a report.", a, a,
               "determiner variation"))
    a = gold("MANUFACTURE", agent="factory", patient="machine")
    eq.append(("C010", "The factory manufactured the machine.",
               "The machine was manufactured by the factory.", a, a,
               "active/passive"))
    a = gold("APPROVE", agent="committee", patient="order")
    eq.append(("C011", "The committee approved the order.",
               "The committees approved the order.", a, a,
               "agent number variation"))
    a = gold("SEND", agent="engineer", patient="email", recipient="client")
    eq.append(("C012", "The engineer sent the email to the client.",
               "The email was sent to the client by the engineer.", a, a,
               "active/passive + recipient"))
    a = gold("REVIEW", agent="accountant", patient="invoice")
    eq.append(("C013", "The accountant reviewed the invoice.",
               "The invoice was reviewed by the accountant.", a, a,
               "active/passive"))
    a = gold("CLEAN", agent="engineer", patient="machine")
    eq.append(("C014", "The engineer cleaned the machine.",
               "The engineers cleaned the machines.", a, a,
               "agent+patient number variation"))
    a = gold("CHASE", agent="dog", patient="cat")
    eq.append(("C015", "The dog chased the cat.",
               "The cats were chased by the dog.", a, a,
               "passive + patient number"))
    a = gold("REVIEW", agent="team", patient="proposal")
    eq.append(("C016", "The team reviewed the proposal.",
               "The teams reviewed the proposal.", a, a,
               "agent number variation"))
    a = gold("STORE", agent="manager", patient="file", location="room")
    eq.append(("C017", "The manager stored the files in the room.",
               "The manager stored the files in a room.", a, a,
               "determiner variation"))
    a = gold("WRITE", agent="committee", patient="report")
    eq.append(("C018", "The committee wrote the report.",
               "The committee wrote the reports.", a, a,
               "patient number variation"))
    a = gold("SIGN", agent="accountant", patient="contract")
    eq.append(("C019", "The accountant signed the contract.",
               "The accountant signed a contract.", a, a,
               "determiner variation"))
    a = gold("SUBMIT", agent="council", patient="proposal", recipient="committee")
    eq.append(("C020", "The council submitted the proposal to the committee.",
               "The councils submitted the proposals to the committee.", a, a,
               "agent+patient number variation"))
    for cid, e1, e2, g1, g2, notes in eq:
        add(_pair(cid, "equivalent", "EQUIVALENT", e1, e2, g1, g2, notes))

    # =====================================================================
    # 2. DISTINCT (20) — different gold IR
    # =====================================================================
    d = []
    d.append(("C021", "The cat chased the dog.", "The dog chased the cat.",
              gold("CHASE", agent="cat", patient="dog"),
              gold("CHASE", agent="dog", patient="cat"),
              "argument inversion"))
    d.append(("C022", "The committee approved the order.",
              "The committee rejected the order.",
              gold("APPROVE", agent="committee", patient="order"),
              gold("REJECT", agent="committee", patient="order"),
              "predicate swap"))
    d.append(("C023", "The committee approved the order.",
              "The committee approved the invoice.",
              gold("APPROVE", agent="committee", patient="order"),
              gold("APPROVE", agent="committee", patient="invoice"),
              "patient swap"))
    d.append(("C024", "The engineer cleaned the machine.",
              "The engineer cleaned the room.",
              gold("CLEAN", agent="engineer", patient="machine"),
              gold("CLEAN", agent="engineer", patient="room"),
              "patient swap"))
    d.append(("C025", "The manager signed the contract.",
              "The accountant signed the contract.",
              gold("SIGN", agent="manager", patient="contract"),
              gold("SIGN", agent="accountant", patient="contract"),
              "agent swap"))
    d.append(("C026", "The team submitted the proposal to the council.",
              "The team submitted the report to the council.",
              gold("SUBMIT", agent="team", patient="proposal", recipient="council"),
              gold("SUBMIT", agent="team", patient="report", recipient="council"),
              "patient swap"))
    d.append(("C027", "The accountant stored the files in the warehouse.",
              "The accountant stored the files in the room.",
              gold("STORE", agent="accountant", patient="file", location="warehouse"),
              gold("STORE", agent="accountant", patient="file", location="room"),
              "location swap"))
    d.append(("C028", "The council rejected the invoice.",
              "The council approved the invoice.",
              gold("REJECT", agent="council", patient="invoice"),
              gold("APPROVE", agent="council", patient="invoice"),
              "predicate swap"))
    d.append(("C029", "The committee approved the order.",
              "The committee did not approve the order.",
              gold("APPROVE", agent="committee", patient="order"),
              gold("APPROVE", agent="committee", patient="order", negation=True),
              "negation"))
    d.append(("C030", "The dog chased the cat.",
              "The dog did not chase the cat.",
              gold("CHASE", agent="dog", patient="cat"),
              gold("CHASE", agent="dog", patient="cat", negation=True),
              "negation"))
    d.append(("C031", "The engineer reviewed the report.",
              "The manager reviewed the report.",
              gold("REVIEW", agent="engineer", patient="report"),
              gold("REVIEW", agent="manager", patient="report"),
              "agent swap"))
    d.append(("C032", "The factory manufactured the machine.",
              "The factory cleaned the machine.",
              gold("MANUFACTURE", agent="factory", patient="machine"),
              gold("CLEAN", agent="factory", patient="machine"),
              "predicate swap"))
    d.append(("C033", "The engineer sent the email to the client.",
              "The engineer sent the package to the client.",
              gold("SEND", agent="engineer", patient="email", recipient="client"),
              gold("SEND", agent="engineer", patient="package", recipient="client"),
              "patient swap"))
    d.append(("C034", "The manager signed the contract.",
              "The manager signed the letter.",
              gold("SIGN", agent="manager", patient="contract"),
              gold("SIGN", agent="manager", patient="letter"),
              "patient swap (letter unregistered)"))
    d.append(("C035", "The team submitted the proposal.",
              "The team submitted the proposal to the council.",
              gold("SUBMIT", agent="team", patient="proposal"),
              gold("SUBMIT", agent="team", patient="proposal", recipient="council"),
              "recipient present vs absent"))
    d.append(("C036", "The accountant stored the files.",
              "The accountant stored the files in the warehouse.",
              gold("STORE", agent="accountant", patient="file"),
              gold("STORE", agent="accountant", patient="file", location="warehouse"),
              "location present vs absent"))
    d.append(("C037", "The committee wrote the report.",
              "The committee wrote the proposal.",
              gold("WRITE", agent="committee", patient="report"),
              gold("WRITE", agent="committee", patient="proposal"),
              "patient swap"))
    d.append(("C038", "The engineer cleaned the machine with a brush.",
              "The engineer cleaned the machine with a tool.",
              gold("CLEAN", agent="engineer", patient="machine", instrument="brush"),
              gold("CLEAN", agent="engineer", patient="machine", instrument="tool"),
              "instrument swap (tool unregistered)"))
    d.append(("C039", "The council approved the proposal.",
              "The council rejected the proposal.",
              gold("APPROVE", agent="council", patient="proposal"),
              gold("REJECT", agent="council", patient="proposal"),
              "predicate swap"))
    d.append(("C040", "The engineer signed the contract.",
              "The engineer wrote the contract.",
              gold("SIGN", agent="engineer", patient="contract"),
              gold("WRITE", agent="engineer", patient="contract"),
              "predicate swap (WRITE unregistered)"))
    for cid, e1, e2, g1, g2, notes in d:
        add(_pair(cid, "distinct", "DISTINCT", e1, e2, g1, g2, notes))

    # =====================================================================
    # 3. AMBIGUOUS (20) — single expressions, genuinely underdetermined
    # =====================================================================
    am = []
    # (a) "with X" instrument-vs-companion ambiguity
    am.append(("C041", "The engineer cleaned the machine with the manager.",
               gold("CLEAN", agent="engineer", patient="machine",
                    instrument="manager", uncertainty=UNC_AMBIGUOUS,
                    _ambig=("INSTRUMENT",)),
               gold("CLEAN", agent="engineer", patient="machine",
                    instrument="manager"),
               "with-manager: instrument or companion?"))
    am.append(("C042", "The committee approved the order with the accountant.",
               gold("APPROVE", agent="committee", patient="order",
                    instrument="accountant", uncertainty=UNC_AMBIGUOUS,
                    _ambig=("INSTRUMENT",)),
               gold("APPROVE", agent="committee", patient="order",
                    instrument="accountant"),
               "with-accountant: instrument or companion?"))
    am.append(("C043", "The manager reviewed the report with the team.",
               gold("REVIEW", agent="manager", patient="report",
                    instrument="team", uncertainty=UNC_AMBIGUOUS,
                    _ambig=("INSTRUMENT",)),
               gold("REVIEW", agent="manager", patient="report", instrument="team"),
               "with-team: instrument or companion?"))
    am.append(("C044", "The team cleaned the machine with the manager.",
               gold("CLEAN", agent="team", patient="machine",
                    instrument="manager", uncertainty=UNC_AMBIGUOUS,
                    _ambig=("INSTRUMENT",)),
               gold("CLEAN", agent="team", patient="machine", instrument="manager"),
               "with-manager: instrument or companion?"))
    am.append(("C045", "The accountant reviewed the invoice with the manager.",
               gold("REVIEW", agent="accountant", patient="invoice",
                    instrument="manager", uncertainty=UNC_AMBIGUOUS,
                    _ambig=("INSTRUMENT",)),
               gold("REVIEW", agent="accountant", patient="invoice",
                    instrument="manager"),
               "with-manager: instrument or companion?"))
    am.append(("C046", "The council approved the proposal with the accountant.",
               gold("APPROVE", agent="council", patient="proposal",
                    instrument="accountant", uncertainty=UNC_AMBIGUOUS,
                    _ambig=("INSTRUMENT",)),
               gold("APPROVE", agent="council", patient="proposal",
                    instrument="accountant"),
               "with-accountant: instrument or companion?"))
    am.append(("C047", "The engineer stored the files with the manager.",
               gold("STORE", agent="engineer", patient="file",
                    instrument="manager", uncertainty=UNC_AMBIGUOUS,
                    _ambig=("INSTRUMENT",)),
               gold("STORE", agent="engineer", patient="file", instrument="manager"),
               "with-manager: instrument or companion?"))
    am.append(("C048", "The committee signed the contract with the team.",
               gold("SIGN", agent="committee", patient="contract",
                    instrument="team", uncertainty=UNC_AMBIGUOUS,
                    _ambig=("INSTRUMENT",)),
               gold("SIGN", agent="committee", patient="contract", instrument="team"),
               "with-team: instrument or companion?"))
    # (b) "for X" beneficiary-vs-purpose ambiguity
    am.append(("C049", "The accountant stored the files for the manager.",
               gold("STORE", agent="accountant", patient="file",
                    recipient="manager", uncertainty=UNC_AMBIGUOUS,
                    _ambig=("RECIPIENT",)),
               gold("STORE", agent="accountant", patient="file", recipient="manager"),
               "for-manager: beneficiary or purpose?"))
    am.append(("C050", "The manager signed the contract for the client.",
               gold("SIGN", agent="manager", patient="contract",
                    recipient="client", uncertainty=UNC_AMBIGUOUS,
                    _ambig=("RECIPIENT",)),
               gold("SIGN", agent="manager", patient="contract", recipient="client"),
               "for-client: beneficiary or purpose?"))
    am.append(("C051", "The team submitted the proposal for the council.",
               gold("SUBMIT", agent="team", patient="proposal",
                    recipient="council", uncertainty=UNC_AMBIGUOUS,
                    _ambig=("RECIPIENT",)),
               gold("SUBMIT", agent="team", patient="proposal", recipient="council"),
               "for-council: beneficiary or purpose?"))
    am.append(("C052", "The engineer wrote the report for the manager.",
               gold("WRITE", agent="engineer", patient="report",
                    recipient="manager", uncertainty=UNC_AMBIGUOUS,
                    _ambig=("RECIPIENT",)),
               gold("WRITE", agent="engineer", patient="report", recipient="manager"),
               "for-manager: beneficiary or purpose?"))
    am.append(("C053", "The council reviewed the proposal for the committee.",
               gold("REVIEW", agent="council", patient="proposal",
                    recipient="committee", uncertainty=UNC_AMBIGUOUS,
                    _ambig=("RECIPIENT",)),
               gold("REVIEW", agent="council", patient="proposal",
                    recipient="committee"),
               "for-committee: beneficiary or purpose?"))
    # (c) "in X" locative-attachment ambiguity
    am.append(("C054", "The cat chased the dog in the room.",
               gold("CHASE", agent="cat", patient="dog", location="room",
                    uncertainty=UNC_AMBIGUOUS, _ambig=("LOCATION",)),
               gold("CHASE", agent="cat", patient="dog", location="room"),
               "in-room: chase location or dog's location?"))
    am.append(("C055", "The engineer cleaned the machine in the factory.",
               gold("CLEAN", agent="engineer", patient="machine",
                    location="factory", uncertainty=UNC_AMBIGUOUS,
                    _ambig=("LOCATION",)),
               gold("CLEAN", agent="engineer", patient="machine",
                    location="factory"),
               "in-factory: cleaning location or machine's location?"))
    am.append(("C056", "The manager stored the files in the room.",
               gold("STORE", agent="manager", patient="file", location="room",
                    uncertainty=UNC_AMBIGUOUS, _ambig=("LOCATION",)),
               gold("STORE", agent="manager", patient="file", location="room"),
               "in-room: storage location or files' location?"))
    am.append(("C057", "The team reviewed the proposal in the meeting.",
               gold("REVIEW", agent="team", patient="proposal", location="meeting",
                    uncertainty=UNC_AMBIGUOUS, _ambig=("LOCATION",)),
               gold("REVIEW", agent="team", patient="proposal", location="meeting"),
               "in-meeting: review location or proposal location?"))
    # (d) "from X" source-attachment ambiguity
    am.append(("C058", "The committee approved the order from the manager.",
               gold("APPROVE", agent="committee", patient="order",
                    source="manager", uncertainty=UNC_AMBIGUOUS,
                    _ambig=("SOURCE",)),
               gold("APPROVE", agent="committee", patient="order",
                    source="manager"),
               "from-manager: order's source or manager as participant?"))
    am.append(("C059", "The council rejected the invoice from the vendor.",
               gold("REJECT", agent="council", patient="invoice",
                    source="vendor", uncertainty=UNC_AMBIGUOUS,
                    _ambig=("SOURCE",)),
               gold("REJECT", agent="council", patient="invoice", source="vendor"),
               "from-vendor: invoice's source or vendor as participant?"))
    am.append(("C060", "The accountant reviewed the report from the manager.",
               gold("REVIEW", agent="accountant", patient="report",
                    source="manager", uncertainty=UNC_AMBIGUOUS,
                    _ambig=("SOURCE",)),
               gold("REVIEW", agent="accountant", patient="report",
                    source="manager"),
               "from-manager: report's source or manager as participant?"))
    for cid, expr, intended, alt, notes in am:
        add(_amb(cid, expr, intended, alt, notes))

    # =====================================================================
    # 4. TRANSFORMATION (20) — meaning-preserving structural transforms
    # =====================================================================
    t = []
    a = gold("SIGN", agent="accountant", patient="contract")
    t.append(("C061", "The accountant signed the contract.",
              "The contract was signed by the accountant.", a, a,
              "active/passive"))
    a = gold("REVIEW", agent="team", patient="proposal")
    t.append(("C062", "The team reviewed the proposal.",
              "The proposal was reviewed by the team.", a, a,
              "active/passive"))
    a = gold("SUBMIT", agent="council", patient="proposal", recipient="committee")
    t.append(("C063", "The council submitted the proposal to the committee.",
              "The proposal was submitted to the committee by the council.", a, a,
              "passive + recipient order"))
    a = gold("CLEAN", agent="engineer", patient="machine")
    t.append(("C064", "The machine was cleaned by the engineer.",
              "The engineer cleaned the machine.", a, a,
              "passive->active"))
    a = gold("MANUFACTURE", agent="factory", patient="machine")
    t.append(("C065", "The factory manufactured the machine.",
              "The machine was manufactured by the factory.", a, a,
              "active/passive"))
    a = gold("WRITE", agent="engineer", patient="report")
    t.append(("C066", "The engineer wrote a report.",
              "The engineer wrote the report.", a, a,
              "determiner variation"))
    a = gold("STORE", agent="accountant", patient="file", location="room")
    t.append(("C067", "The accountant stored the file in the room.",
              "The accountant stored files in the room.", a, a,
              "number variation"))
    a = gold("CLEAN", agent="engineer", patient="machine", instrument="brush")
    t.append(("C068", "The engineer cleaned the machine with a brush.",
              "The engineer cleaned the machine using a brush.", a, a,
              "with/using synonym"))
    a = gold("APPROVE", agent="committee", patient="order")
    t.append(("C069", "The committee approved the order.",
              "The committees approved the order.", a, a,
              "agent number variation"))
    a = gold("CHASE", agent="dog", patient="cat")
    t.append(("C070", "The dog chased the cat.",
              "The dog chased the cats.", a, a,
              "patient number variation"))
    a = gold("REVIEW", agent="committee", patient="report")
    t.append(("C071", "The reports were reviewed by the committee.",
              "The committee reviewed the report.", a, a,
              "passive + number"))
    a = gold("SUBMIT", agent="team", patient="proposal", recipient="council")
    t.append(("C072", "The proposal was submitted to the council by the team.",
              "The team submitted the proposal to the council.", a, a,
              "passive->active + recipient"))
    a = gold("SEND", agent="engineer", patient="email", recipient="client")
    t.append(("C073", "The email was sent to the client by the engineer.",
              "The engineer sent the email to the client.", a, a,
              "passive->active + recipient"))
    a = gold("STORE", agent="accountant", patient="file", location="warehouse")
    t.append(("C074", "The files were stored in the warehouse by the accountant.",
              "The accountant stored the files in the warehouse.", a, a,
              "passive->active + location"))
    a = gold("REVIEW", agent="accountant", patient="invoice")
    t.append(("C075", "The accountant reviewed an invoice.",
              "The accountant reviewed the invoice.", a, a,
              "determiner variation"))
    a = gold("REJECT", agent="council", patient="invoice")
    t.append(("C076", "The invoice was rejected by the council.",
              "The council rejected the invoice.", a, a,
              "passive->active"))
    a = gold("CLEAN", agent="engineer", patient="machine", instrument="brush")
    t.append(("C077", "The machine was cleaned with a brush by the engineer.",
              "The engineer cleaned the machine with a brush.", a, a,
              "passive + instrument order"))
    a = gold("CHASE", agent="dog", patient="cat")
    t.append(("C078", "The cats were chased by the dog.",
              "The dog chased the cat.", a, a,
              "passive + number"))
    a = gold("SIGN", agent="manager", patient="contract")
    t.append(("C079", "The contract was signed by the manager.",
              "The manager signed the contract.", a, a,
              "passive->active"))
    a = gold("SUBMIT", agent="team", patient="proposal", recipient="council")
    t.append(("C080", "The team submitted the proposals to the councils.",
              "The team submitted the proposal to the council.", a, a,
              "number variation"))
    for cid, e1, e2, g1, g2, notes in t:
        add(_pair(cid, "transformation", "PRESERVING", e1, e2, g1, g2, notes))

    # =====================================================================
    # 5. ADVERSARIAL (20) — distinct meaning, engineered false-consensus traps
    # =====================================================================
    adv = []
    adv.append(("C081", "The cat chased the dog.", "The dog chased the cat.",
                gold("CHASE", agent="cat", patient="dog"),
                gold("CHASE", agent="dog", patient="cat"),
                "inversion trap: identical words, swapped roles"))
    adv.append(("C082", "The committee approved the order.",
                "The committee did not approve the order.",
                gold("APPROVE", agent="committee", patient="order"),
                gold("APPROVE", agent="committee", patient="order", negation=True),
                "negation trap: one-word surface difference"))
    adv.append(("C083", "The committee approved the order.",
                "The committee approved the invoice.",
                gold("APPROVE", agent="committee", patient="order"),
                gold("APPROVE", agent="committee", patient="invoice"),
                "patient trap: one noun differs"))
    adv.append(("C084", "The engineer cleaned the machine.",
                "The engineer cleaned the room.",
                gold("CLEAN", agent="engineer", patient="machine"),
                gold("CLEAN", agent="engineer", patient="room"),
                "patient trap"))
    adv.append(("C085", "The manager signed the contract.",
                "The manager signed the letter.",
                gold("SIGN", agent="manager", patient="contract"),
                gold("SIGN", agent="manager", patient="letter"),
                "patient trap (letter unregistered)"))
    adv.append(("C086", "The committee approved the order.",
                "The committee rejected the order.",
                gold("APPROVE", agent="committee", patient="order"),
                gold("REJECT", agent="committee", patient="order"),
                "predicate trap: verb swap"))
    adv.append(("C087", "The team submitted the proposal to the council.",
                "The team submitted the report to the council.",
                gold("SUBMIT", agent="team", patient="proposal", recipient="council"),
                gold("SUBMIT", agent="team", patient="report", recipient="council"),
                "patient trap"))
    adv.append(("C088", "The accountant stored the files in the warehouse.",
                "The accountant stored the files in the room.",
                gold("STORE", agent="accountant", patient="file", location="warehouse"),
                gold("STORE", agent="accountant", patient="file", location="room"),
                "location trap (warehouse unregistered)"))
    adv.append(("C089", "The engineer sent the email to the client.",
                "The engineer sent the package to the client.",
                gold("SEND", agent="engineer", patient="email", recipient="client"),
                gold("SEND", agent="engineer", patient="package", recipient="client"),
                "patient trap"))
    adv.append(("C090", "The council rejected the invoice.",
                "The council approved the invoice.",
                gold("REJECT", agent="council", patient="invoice"),
                gold("APPROVE", agent="council", patient="invoice"),
                "predicate trap"))
    adv.append(("C091", "The accountant stored the files.",
                "The accountant stored the files in the warehouse.",
                gold("STORE", agent="accountant", patient="file"),
                gold("STORE", agent="accountant", patient="file", location="warehouse"),
                "presence trap: adding a PP flips the meaning"))
    adv.append(("C092", "The team submitted the proposal.",
                "The team submitted the proposal to the council.",
                gold("SUBMIT", agent="team", patient="proposal"),
                gold("SUBMIT", agent="team", patient="proposal", recipient="council"),
                "presence trap: recipient appears"))
    adv.append(("C093", "The dog chased the cat.",
                "The cat chased the dog.",
                gold("CHASE", agent="dog", patient="cat"),
                gold("CHASE", agent="cat", patient="dog"),
                "inversion trap 2"))
    adv.append(("C094", "The committee reviewed the report.",
                "The manager reviewed the report.",
                gold("REVIEW", agent="committee", patient="report"),
                gold("REVIEW", agent="manager", patient="report"),
                "agent trap"))
    adv.append(("C095", "The engineer cleaned the machine with a brush.",
                "The engineer cleaned the machine with a tool.",
                gold("CLEAN", agent="engineer", patient="machine", instrument="brush"),
                gold("CLEAN", agent="engineer", patient="machine", instrument="tool"),
                "instrument trap (tool unregistered)"))
    adv.append(("C096", "The manager signed the contract.",
                "The accountant signed the contract.",
                gold("SIGN", agent="manager", patient="contract"),
                gold("SIGN", agent="accountant", patient="contract"),
                "agent trap"))
    adv.append(("C097", "The factory manufactured the machine.",
                "The factory cleaned the machine.",
                gold("MANUFACTURE", agent="factory", patient="machine"),
                gold("CLEAN", agent="factory", patient="machine"),
                "predicate trap"))
    adv.append(("C098", "The engineer wrote the report.",
                "The engineer wrote the proposal.",
                gold("WRITE", agent="engineer", patient="report"),
                gold("WRITE", agent="engineer", patient="proposal"),
                "patient trap (WRITE unregistered)"))
    adv.append(("C099", "The committee approved the order.",
                "The council approved the order.",
                gold("APPROVE", agent="committee", patient="order"),
                gold("APPROVE", agent="council", patient="order"),
                "agent trap"))
    adv.append(("C100", "The cat chased the dog.",
                "The cat did not chase the dog.",
                gold("CHASE", agent="cat", patient="dog"),
                gold("CHASE", agent="cat", patient="dog", negation=True),
                "negation trap 2"))
    for cid, e1, e2, g1, g2, notes in adv:
        add(_pair(cid, "adversarial", "FALSE_CONSENSUS_TRAP", e1, e2, g1, g2, notes))

    return cases


def _verify(cases: list[dict]) -> None:
    counts: dict[str, int] = {}
    for c in cases:
        counts[c["category"]] = counts.get(c["category"], 0) + 1
    for cat, n in counts.items():
        assert n == 20, f"{cat}: expected 20, got {n}"
    assert len(cases) == 100, f"expected 100 cases, got {len(cases)}"
    ids = [c["id"] for c in cases]
    assert len(set(ids)) == 100, "duplicate case ids"
    print("corpus verified:", dict(sorted(counts.items())), "total", len(cases))


def main() -> None:
    here = os.path.dirname(os.path.abspath(__file__))
    cases = build_corpus()
    _verify(cases)
    out_dir = os.path.normpath(os.path.join(
        here, "..", "..", "docs", "knowledgeos", "brainstorming"))
    os.makedirs(out_dir, exist_ok=True)
    path = os.path.join(out_dir, "KOS-SNF-pilot-100.json")
    with open(path, "w") as f:
        json.dump({"version": "KOS-SNF-pilot-100", "case_count": 100,
                   "cases": cases}, f, indent=2)
    print("wrote", path)


if __name__ == "__main__":
    main()
