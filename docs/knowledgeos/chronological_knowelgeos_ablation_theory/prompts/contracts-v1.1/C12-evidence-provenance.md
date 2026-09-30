# C12 — Evidence and provenance · v1.1

**Treatment:** INHERITED-UNCHANGED principles (v3.5 R6–R11; P3B §1C historical vs research time, §18 provenance,
§14.4 anti-projection) with an F-SPECIFIC chain.

## 1. The chain (every record resolves back through it)
`F-ID → manifest row (path, content sha256) → page (READ-LOG, page sha256) → unit (UNITS, text sha256) → inventory item
(verbatim quote) → contribution (inventory_refs) → analysis record (inventory_refs) → research record (supporting
evidence, analysis_refs) → checkpoint test`.

## 2. Rules
- Anchors are verbatim quotes, never line numbers; every quote is machine-checked against the bytes.
- Historical time (list position, date evidence of the file) and research time (UTC of the record, run id, model id)
  are separate fields; a research-time recognition is never backdated.
- Anti-projection: a record citing a later F-ID for an earlier one's meaning is refused by construction (no
  look-ahead); a later file that seems to explain an earlier one is recorded in the later file's records.
- S isolation (§F-11): S-ids are never evidence; `content_equals_s_sources` is an identity fact only (RL-04).
- Own artifacts are DERIVED (v3.5 R8): nothing in this lane folder is ever read as corpus.
