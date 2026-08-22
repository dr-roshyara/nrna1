"""Shared lexical resources for the toy SNF mechanisms.

This is SHARED DATA (a dictionary), not shared semantic strategy. Real
mechanisms also share training data / lexicons; what must differ between them is
the semantic ALGORITHM (normalization vs graph canonicalization vs kāraka roles
vs closed-world registry vs ensemble vs null prior). The mechanisms in
mechanisms.py implement genuinely different strategies over this data.

CRITICAL BOUNDARY: none of this data is gold. It contains no expected answers,
no corpus entries, no "correct" IRs. It is general knowledge about a toy world
(verbs, entities, function words) that any semantic system might possess.

Coreference: pronouns resolve via a simple discourse rule INSIDE each mechanism
(most recent PATIENT entity), not here.
"""

from __future__ import annotations

import re

# ---------------------------------------------------------------------------
# Tokens / morphology (shared lexical utility)
# ---------------------------------------------------------------------------

DETERMINERS = {"the", "a", "an", "this", "that", "these", "those", "any",
               "each", "every", "all", "some", "several", "no"}

_PUNCT = re.compile(r"[^a-z0-9'\-\s]")


def tokenize(expression: str) -> list[str]:
    """Lowercase, strip punctuation, split. Shared lexical utility only."""
    s = expression.lower()
    s = _PUNCT.sub(" ", s)
    return [t for t in s.split() if t]


def content_tokens(expression: str) -> list[str]:
    """Tokens minus determiners and pure function words."""
    return [t for t in tokenize(expression) if t not in DETERMINERS]


# ---------------------------------------------------------------------------
# Predicates: surface verb -> {predicate, frame (role order)}
# ---------------------------------------------------------------------------

VERBS = {
    "approve": ("APPROVE", ["AGENT", "PATIENT"]),
    "approves": ("APPROVE", ["AGENT", "PATIENT"]),
    "approved": ("APPROVE", ["AGENT", "PATIENT"]),
    "reject": ("REJECT", ["AGENT", "PATIENT"]),
    "rejects": ("REJECT", ["AGENT", "PATIENT"]),
    "rejected": ("REJECT", ["AGENT", "PATIENT"]),
    "chase": ("CHASE", ["AGENT", "PATIENT"]),
    "chases": ("CHASE", ["AGENT", "PATIENT"]),
    "chased": ("CHASE", ["AGENT", "PATIENT"]),
    "review": ("REVIEW", ["AGENT", "PATIENT"]),
    "reviews": ("REVIEW", ["AGENT", "PATIENT"]),
    "reviewed": ("REVIEW", ["AGENT", "PATIENT"]),
    "sign": ("SIGN", ["AGENT", "PATIENT"]),
    "signs": ("SIGN", ["AGENT", "PATIENT"]),
    "signed": ("SIGN", ["AGENT", "PATIENT"]),
    "write": ("WRITE", ["AGENT", "PATIENT"]),
    "writes": ("WRITE", ["AGENT", "PATIENT"]),
    "wrote": ("WRITE", ["AGENT", "PATIENT"]),
    "submit": ("SUBMIT", ["AGENT", "PATIENT", "RECIPIENT"]),
    "submits": ("SUBMIT", ["AGENT", "PATIENT", "RECIPIENT"]),
    "submitted": ("SUBMIT", ["AGENT", "PATIENT", "RECIPIENT"]),
    "send": ("SEND", ["AGENT", "PATIENT", "RECIPIENT"]),
    "sends": ("SEND", ["AGENT", "PATIENT", "RECIPIENT"]),
    "sent": ("SEND", ["AGENT", "PATIENT", "RECIPIENT"]),
    "store": ("STORE", ["AGENT", "PATIENT", "LOCATION"]),
    "stores": ("STORE", ["AGENT", "PATIENT", "LOCATION"]),
    "stored": ("STORE", ["AGENT", "PATIENT", "LOCATION"]),
    "clean": ("CLEAN", ["AGENT", "PATIENT", "INSTRUMENT"]),
    "cleans": ("CLEAN", ["AGENT", "PATIENT", "INSTRUMENT"]),
    "cleaned": ("CLEAN", ["AGENT", "PATIENT", "INSTRUMENT"]),
    "cut": ("CUT", ["AGENT", "PATIENT", "INSTRUMENT"]),
    "cuts": ("CUT", ["AGENT", "PATIENT", "INSTRUMENT"]),
    "manufacture": ("MANUFACTURE", ["AGENT", "PATIENT", "INSTRUMENT"]),
    "manufactures": ("MANUFACTURE", ["AGENT", "PATIENT", "INSTRUMENT"]),
    "manufactured": ("MANUFACTURE", ["AGENT", "PATIENT", "INSTRUMENT"]),
    "located": ("BE_AT", ["PATIENT", "LOCATION"]),
    "stood": ("BE_AT", ["PATIENT", "LOCATION"]),
}

# Canonical kāraka frames per predicate (role order). Derived from VERBS so
# the corpus gold and the mechanisms share the same world model.
FRAMES: dict[str, list[str]] = {}
for _pred, _frame in VERBS.values():
    FRAMES[_pred] = list(_frame)

# Registered predicates for SNF-D (closed-world). Predicates NOT here are
# "unregistered" — SNF-D must ABSTAIN on them, never invent a meaning.
REGISTERED_PREDICATES = {
    "APPROVE", "REJECT", "CHASE", "REVIEW", "SIGN", "SUBMIT", "STORE",
    "CLEAN", "MANUFACTURE", "SEND",
}
# Intentionally NOT registered (so SNF-D abstains on them):
#   WRITE, CUT, BE_AT

# ---------------------------------------------------------------------------
# Entities: surface noun -> canonical identifier
# ---------------------------------------------------------------------------

NOUNS = {
    "committee": "committee", "committees": "committee",
    "order": "order", "orders": "order",
    "cat": "cat", "cats": "cat",
    "dog": "dog", "dogs": "dog",
    "engineer": "engineer", "engineers": "engineer",
    "invoice": "invoice", "invoices": "invoice",
    "report": "report", "reports": "report",
    "manager": "manager", "managers": "manager",
    "council": "council", "councils": "council",
    "letter": "letter", "letters": "letter",
    "machine": "machine", "machines": "machine",
    "factory": "factory", "factories": "factory",
    "warehouse": "warehouse", "warehouses": "warehouse",
    "system": "system", "systems": "system",
    "server": "server", "servers": "server",
    "accountant": "accountant", "accountants": "accountant",
    "contract": "contract", "contracts": "contract",
    "package": "package", "packages": "package",
    "email": "email", "emails": "email",
    "team": "team", "teams": "team",
    "brush": "brush", "brushes": "brush",
    "proposal": "proposal", "proposals": "proposal",
    "file": "file", "files": "file",
    "table": "table", "tables": "table",
    "room": "room", "rooms": "room",
    "tool": "tool", "tools": "tool",
    "laptop": "laptop", "laptops": "laptop",
    "drone": "drone", "drones": "drone",
    "client": "client", "clients": "client",
    "vendor": "vendor", "vendors": "vendor",
    "warehouse": "warehouse",
}

# Registered identifiers for SNF-D (closed-world). Entities NOT here are
# "unknown identifiers" — SNF-D abstains on expressions naming them.
REGISTERED_ENTITIES = {
    "committee", "order", "cat", "dog", "engineer", "invoice", "report",
    "manager", "council", "machine", "factory", "system", "server",
    "accountant", "contract", "package", "email", "team", "proposal",
    "file", "table", "room",
}
# Intentionally NOT registered: brush, tool, laptop, drone, client, vendor,
# letter, warehouse — SNF-D abstains when they appear.

# ---------------------------------------------------------------------------
# Function-word classes
# ---------------------------------------------------------------------------

NEGATION = {"not", "never", "n't", "didn't", "doesn't", "no"}
# Note: "no" doubles as a quantifier ("no invoice was approved"). Mechanisms
# disambiguate: "no" + noun -> NONE_Q quantifier; "no" + verb -> negation.

MODALITY = {
    "must": "NECESSARY", "should": "NECESSARY", "shall": "NECESSARY",
    "may": "POSSIBLE", "might": "POSSIBLE", "could": "POSSIBLE",
    "can": "POSSIBLE", "please": "REQUESTED", "kindly": "REQUESTED",
}

TEMPORAL_PAST = {"yesterday", "last"}
TEMPORAL_FUTURE = {"tomorrow", "next"}

QUANTIFIERS_ALL = {"all", "every", "each"}
QUANTIFIERS_SOME = {"some", "several", "a few"}

# Passive auxiliaries: be-form + past participle (optional "by X").
# Individual tokens only (the tokenizer splits "has been" into two tokens).
BE_AUX = {"was", "were", "been", "being", "is", "are", "am", "has", "have",
          "had"}

# Preposition -> preferred kāraka role.
PREP_ROLES = {
    "to": "RECIPIENT",
    "for": "RECIPIENT",
    "from": "SOURCE",
    "in": "LOCATION",
    "at": "LOCATION",
    "inside": "LOCATION",
    "using": "INSTRUMENT",
    "via": "INSTRUMENT",
    "with": "INSTRUMENT",       # can also be companion -> mechanisms may AMBIGUATE
    "by": "INSTRUMENT",         # passive "by" handled separately as AGENT
}

# Most-frequent-predicate prior for SNF-N (the null baseline). Deliberately
# coarse and wrong for most inputs — that is its purpose.
NULL_PRIOR_PREDICATE = "APPROVE"
NULL_PRIOR_AGENT = "committee"
NULL_PRIOR_PATIENT = "order"
