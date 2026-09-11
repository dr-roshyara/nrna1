# KNOWLEDGEOS THEORY RECONSTRUCTION — MASTER PROTOCOL v3.5

**Part A is the controlling procedure. Part B is reference. Agents receive neither —
they receive `20260911_0221_agent-extraction-contract.md` only.**

Purpose: construct the KnowledgeOS theory from the complete historical corpus.
Primary object: `TheoryState(t)`. Frame: **the chronological pass is event sourcing;
the theory is the projection.**

# RECONSTRUCT FIRST → RECONCILE LATER → CANONICALIZE LAST → SYNTHESIZE WITH PROVENANCE

v3.5 changes (architecture review of v3.4): identity separated from type
compatibility · `relationship` as its own result with a basis · `source_id` is the
only ordering key · candidate vs established births · `NOT-EVIDENCED-IN-CAPTURE`
replaces `ABSENT` · P2 label normalization · validation and governance as status
vectors · `DERIVATIONAL` edges · primary layer + secondary roles · rationale block ·
assumption provenance · duplicate rule stated explicitly.

---

# PART A — CONTROLLING PROCEDURE

## A0. FIXED PATHS

```
CHRONOLOGY_INDEX = docs/knowledgeos/brainstorming/20260909-185001_files-to-read-one-by-one.log.md
OUTPUT_DIR       = docs/knowledgeos/chronological-read/
CONTRACT         = OUTPUT_DIR/prompts/20260911_0221_agent-extraction-contract.md
SCRIPTS          = OUTPUT_DIR/scripts/
BATCH_SIZE = 40      PARALLEL_BATCHES = 3–4      AUDIT_EVERY = 5 batches
```
Permanently firewalled paths are never read, quoted, or inferred.

## A1. RULES

```
R0   Phase 1 maximizes information preservation, not reduction. Nothing is dropped for
     being repetitive, partial, informal, or inconsistent. Reduction begins in Phase 2.
R1   source_id order (= roadmap order) is INGESTION order only — it controls what is
     read next and how batches are numbered. It is NOT assumed to be ARGUMENT order
     (the corpus's own logical/narrative sequence): a document can state that it
     logically precedes or follows another regardless of its source_id or mtime.
     Dates (explicit, internal, mtime) and any in-file precedence statement are
     attributes and evidence, never the sort key. Inside a BULK block the order is
     UNORDERED for lineage purposes. Argument order, where evidenced, is assembled in
     P2/P3 from explicit signals (R1a) — it never overwrites source_id, which remains
     the permanent citation and re-read key.
R1a  ORDER EVIDENCE — every file/contribution that bears on lineage or births records
     which signal, if any, places it in argument order, ranked strongest first:
     STEP-NUMBER (an explicit step/series position) · INTERNAL-TIMESTAMP (a stated
     authoring date) · FILENAME-DATESTAMP · SOURCE_ID (queue position; weakest, used
     only when nothing else is available). A file whose content explicitly claims a
     logical position relative to another ("this follows from X", "written after Y but
     precedes it") records that as a lineage_claim, never as a silent reordering.
R2   Each accessible unique file is read whole, once, by exactly one extraction agent.
R3   Every roadmap entry receives a source record. Every unique or repaired file
     receives a content record. An exact duplicate receives a duplicate record pointing
     to its first occurrence and is not semantically re-read. A firewalled entry
     receives a FIREWALL-LIMITED record. There is no "nothing here" shortcut of any name.
R4   One file may contribute to many objects; read once, record all.
R5   Phase 1 never resolves identity, never merges labels, never picks a canonical form.
     Labels are handles; UNKNOWN-OBJECT-CANDIDATE is always a valid answer.
R6   Source claim ≠ our assessment ≠ agent observation. Never silently correct the
     source; flag it. Keep the three apart in every record.
R7   A source's lineage statement is SOURCE-CLAIMED-*, never the fact. A fact needs
     corroboration (A11) and carries its basis.
R8   Own artifacts are DERIVED, never primary evidence, never auto-promoted.
R9   Duplicate ≠ independent evidence; repetition is not confirmation.
R10  Later evidence may clarify earlier evidence; it never rewrites the earlier record.
R11  Identity = source_id + commit + sha256. Path is navigation. Anchors are headings or
     verbatim quotes, never line numbers.
R12  Batches may run in parallel because batch order does not determine semantic
     interpretation; cross-batch identity resolution is forbidden in Phase 1.
R13  Scripts derive what can be derived (counts, ordering, candidate births, dormancy,
     coverage, near-dups, label clustering). Agents interpret; they do not count.
R14  The orchestrator never reads a corpus file or a full ledger.
R15  Status is a vector of independent fields (B4); coexisting states are separate
     fields, never one enum. Nothing is ever collapsed into one grade.
R16  Different type ≠ different object. Different context ≠ different object.
     Type compatibility and identity/relationship are decided separately.
R17  An absence in our capture is NOT-EVIDENCED-IN-CAPTURE until the census is complete
     AND a targeted lexical+conceptual search has failed; only then
     GENUINELY-UNDEFINED-AFTER-CENSUS.
R18  Completed batch / found definition / found contradiction / commit / discovery are
     progress events, not stop conditions. Only a terminal predicate stops a phase.
R19  Never execute Phase N+1 as a substitute for an unfinished Phase N.
R20  The pipeline output is a recommendation pending governance review (EP-02/R-34).
```

## A2. PIPELINE

```
P0 validate → P0.5 batch → P1 capture → P1c close
→ P2 normalize labels → families → P3 reconcile → P4 v1.2 → P5 validation
→ P6 governance → P7 synthesis → GATE
```
| Phase | The one question it answers |
|---|---|
| P1 | What did the corpus say? |
| P2 | What forms and candidate objects are present? |
| P3 | How are those forms related, and are they type-/math-coherent? |
| P4 | What belongs to v1.2? |
| P5 | What is validated, and by which means? |
| P6 | What is governed, and by which acts? |
| P7 | How is the complete theory written with provenance? |

## A3. RUNBOOK (session start)

```
ls OUTPUT_DIR
IF no 00-ROADMAP-VALIDATED.jsonl            → P0, P0.5
ELIF manifest has non-DONE batches          → P1 (dispatch)
ELIF no 02-FILES.jsonl                      → P1c
ELIF no 20-FAMILIES/_LABEL-NORMALIZATION.md → P2a
ELIF no 20-FAMILIES/<label>.md              → P2b
ELIF no 30-RECONCILIATION.md                → P3 … and so on
ALWAYS before session end: one line in .claude/CONTEXT.md (phase, last batch) + session log.
Never resume from conversation memory.
```

## A4. PHASE 0 — VALIDATE ROADMAP (script, no file reading)

```
commit = git rev-parse HEAD  → write 00-CORPUS-SNAPSHOT.txt
FOR line IN CHRONOLOGY_INDEX (in order):
    parse <date> <time> <path>; source_id = S%04d (line order)
    IF firewalled(path):        resolve = FIREWALL-LIMITED; continue
    IF NOT exists(path):
        candidates = ls/grep -l on the visible fragment in that directory
        IF |candidates| == 1:   resolve = REPAIRED; path = candidate; repair_evidence = …
        ELSE:                   resolve = UNRESOLVABLE; continue          # never guess
    ELSE:                       resolve = RESOLVED
    sha256 = hash(path); file_mtime = mtime(path)
    dup   = EXACT-DUPLICATE-OF:<first source_id with same sha256> | UNIQUE
    mtime_block = BULK-nn IF ≥5 files share this mtime ELSE null
    IF resolved path is under OUTPUT_DIR: resolve = SELF-CITATION-EXCLUDED; continue
       # a prior programme on this corpus found its own output cited as if it were
       # source evidence (caught via commit-date check) — this is the mechanical guard
    emit → 00-ROADMAP-VALIDATED.jsonl
TERMINAL: every line has resolve; every RESOLVED/REPAIRED has sha256, dup, mtime_block.
```
Known: ≥18 truncated entries (`…/mathematical_ideas_that_can_be_implemented/#`); ~46
files at `2026-08-05 15:44` (bulk block). source_id = citation + order identity ·
sha256 = content identity · path = navigation.

## A5. PHASE 0.5 — BATCH (script)

```
FILES = RESOLVED|REPAIRED AND dup == UNIQUE AND NOT firewalled, in source_id order
partition FILES into batches of BATCH_SIZE; keep a BULK block inside one batch when possible
IF a batch contains unusually large files: split smaller; record reason
emit → 01-BATCH-MANIFEST.jsonl  {batch_id, source_ids[], status: PENDING}
TERMINAL: every FILE in exactly one batch.
```

## A6. PHASE 1 — EVIDENCE CAPTURE (orchestrator loop)

```
WHILE EXISTS batch WITH status IN {PENDING, FAILED}:
    FOR up to PARALLEL_BATCHES such batches (lowest ids first):
        status = IN-PROGRESS
        dispatch fresh general-purpose Agent with:
            CONTRACT verbatim
          + batch_id, commit, [source_id | path | file_mtime | mtime_block] for the batch
          + snapshot of 11-OBJECT-INDEX.jsonl
          + OUTPUT_DIR/ledger/<batch_id>/ as the only writable location
    ON each return:
        run SCRIPTS/verify-batch <batch_id>                         # A7
        IF fail: status = FAILED; ledger/<batch_id>/ is overwritten on retry; continue
        merge index-proposals with relation NONE → 11-OBJECT-INDEX.jsonl (new labels only)
        append proposals with relation POSSIBLY:* and all UNKNOWN-OBJECT-CANDIDATE rows
              → 11-UNRESOLVED-CANDIDATES.jsonl                       # never folded
        append summary → 09-READ-STATUS.jsonl; status = DONE
    IF count(DONE) % AUDIT_EVERY == 0: run self-audit                 # A8
    IF session budget is near its end: update CONTEXT.md + session log; stop the session
TERMINAL: all batches DONE.
```
The orchestrator never holds corpus text; context grows by one summary per batch. A
batch that FAILS twice is a legitimate pause for judgment.

## A7. verify-batch (script; any failure fails the batch)

```
files.jsonl line count == |batch| AND set(source_id) == batch source_ids
every JSONL line parses; required keys present; status ∈ {CONTENT, FIREWALL-LIMITED}
every files.jsonl.contribution_assessment non-empty; summary non-empty
every contributions.jsonl.source_id ∈ batch
every label ∈ index ∪ proposals ∪ {UNKNOWN-OBJECT-CANDIDATE}
every contribution has ≥1 type ∈ TYPES and a non-empty anchor
every assumption has stated ∈ {EXPLICIT, USED-UNSTATED} and an anchor
git status shows no change outside ledger/<batch_id>/
```

## A8. Self-audit (every AUDIT_EVERY batches)

```
pick 2 source_ids from DONE batches, biased toward LOAD-BEARING contributions (≥1 type
   IN {DEFINITION, FORMALIZATION, AXIOM, EXPERIMENT, CORRECTION, RETRACTION}) rather
   than uniform random — these are the costliest to get wrong; fall back to random if
   fewer than 2 load-bearing files exist among DONE batches
dispatch a small agent: CONTRACT + those 2 files, no access to their records
script-diff {types, scope, labels, completeness, lineage_claims, assumptions} vs stored
log MATCH | DISCREPANCY:<fields> → 10-SELF-AUDIT-LOG.md                 # record, don't fix
IF the same discrepancy pattern recurs: re-audit backward from its first appearance
```

## A9. PHASE 1c — CLOSE (scripts)

```
concat ledger/B*/files.jsonl (source_id order)          → 02-FILES.jsonl
concat ledger/B*/contributions.jsonl (source_id order)  → 03-CONTRIBUTIONS.jsonl
ASSERT every UNIQUE accessible file appears exactly once in 02-FILES.jsonl
FOR each record: attach date evidence (attributes, never a sort key):
    explicit_dates[] · internal_date (front-matter/commission) · file_mtime
    best_historical_date = explicit ▸ internal ▸ mtime, with basis EXPLICIT|INTERNAL|MTIME
near-duplicate scan (normalized-text similarity) over all CONTENT files
   → 08-OVERLAP-REGISTER.jsonl {a, b, similarity, kind: NEAR-DUPLICATE | PARTIAL-OVERLAP}
build 12-SOURCE-REGISTER.md: one row per source_id incl. duplicates, firewalled,
   unresolvable — path, commit, sha256, mtime, best_historical_date(basis), status,
   provenance class, objects touched
TERMINAL: assertions pass. "Evidence capture complete" ≠ "theory complete".
```

## A10. PHASE 2 — LABEL NORMALIZATION, THEN OBJECT FAMILIES (ledger only)

```
P2a  LABEL NORMALIZATION (script, then one agent pass) → 20-FAMILIES/_LABEL-NORMALIZATION.md
     cluster working_labels + UNKNOWN-OBJECT-CANDIDATE rows + POSSIBLY:* proposals by:
        shared notation · shared alias · string similarity · co-occurrence in one file
     output CANDIDATE GROUPS: {group_id, members[], why_grouped, NOT an identity claim}
     a label may sit in more than one group; nothing is merged; nothing is renamed
     purpose: parallel agents wrote "K-state", "knowledge-state", "K*" — one group shows
     them side by side for P3; it never declares them the same object

P2b  FAMILIES (script SCRIPTS/derive-families → _derived.json; then agents write .md)
     per CANDIDATE GROUP and per member label:
        rows in source_id order (BULK blocks marked UNORDERED; date evidence shown beside)
        CANDIDATE births per kind — lexical | conceptual | formal | operational | governance
           = earliest row that matches the mechanical detector; inside a BULK block report
             the block. These are CANDIDATE-*-BIRTH until P3 inspection makes them
             ESTABLISHED-*-BIRTH or moves them.
        last_seen; lifecycle candidate =
           ACTIVE | DORMANT (absent after <S-id>, no RETRACTION row, no SOURCE-CLAIMED-
           REPLACEMENT against it) | SOURCE-CLAIMED-RETRACTED | SOURCE-CLAIMED-SUPERSEDED
           | CONTESTED (CONTRADICTION rows)
        completeness roll-up over: purpose · rationale · informal meaning · formal
           definition · type signature · invariants · dependencies · assumptions ·
           semantics · examples · warnings · experiments · open questions
           → PRESENT(rows) | PARTIAL(rows, missing) | NOT-EVIDENCED-IN-CAPTURE
        rationale block (from EXPLANATION/ARGUMENT/ANALYSIS/ALTERNATIVE rows):
           problem addressed · why introduced · gap it was meant to close ·
           alternative it replaced or complemented — each cited or NOT-EVIDENCED-IN-CAPTURE
        assumption register: per assumption {statement, stated EXPLICIT|USED-UNSTATED,
           source [S-id], later TESTED? [S-id], later RETRACTED? [S-id]}
        provisional primary_layer + secondary_roles[] (B3)
     AGENT writes 20-FAMILIES/<label>.md from _derived.json + the label's rows; never
        re-reads the corpus; every item cited [S-id §anchor]
     a definition split across files is assembled ONLY when fragments share a label or a
        SOURCE-CLAIMED lineage joins them; otherwise separate candidates
     _UNRESOLVED-OBJECTS.md = every candidate row not yet in a family, by group
     _theory-level.md / _methodological.md = those scopes, same discipline
TERMINAL (script): every label has a family; every contribution row is referenced by ≥1
     family, collection, or _UNRESOLVED-OBJECTS.md. No canonical form chosen.
```

## A11. PHASE 3 — RECONCILIATION (strongest model; adversarial)

Two questions per pair, answered independently, then a per-object roll-up.

```
FOR each CANDIDATE GROUP:
  FOR each pair of forms (A, B) in the group (and across groups when a SOURCE-CLAIMED row links them):

    Q1 RELATIONSHIP (what is the historical/semantic relation?)
        evidence = SOURCE-CLAIMED-* rows (IDENTITY, REFINEMENT, EXTENSION, REDEFINITION,
                   REPLACEMENT, SPECIALIZATION, DERIVATION, CONTINUATION, SEPARATION,
                   CONTRADICTION, RETRACTION — each with its target and quote) ·
                   derivations shown in corpus · semantic continuity of meaning/role.
                   P1 captured these claims; P3 tests them — a claim never becomes a
                   relationship without the basis being stated.
        relationship ∈ { SAME, REFINEMENT, EXTENSION, REDEFINITION, REPLACEMENT,
                         SPECIALIZATION, DERIVED-FROM, CONTINUATION, HOMONYM,
                         INDEPENDENT, UNWITNESSED }
        basis ∈ { CORROBORATED        claim + continuity (+ derivation where applicable)
                  SOURCE-CLAIMED-ONLY a claim exists, continuity not shown
                  INFERRED            continuity shown, no claim   (state the inference)
                  NONE }
        default: UNWITNESSED / NONE.  Insufficient alone: same symbol, same name, same
        author, same document, similar wording, temporal proximity, similar purpose.
        HOMONYM requires positive evidence of two different concepts (a
        SOURCE-CLAIMED-SEPARATION row, or different responsibility AND no continuity)
        — never type alone.
        NEGATIVE-VERDICT BAR (a prior programme on this corpus asserted DISJOINT from a
        narrow citation sweep and had to withdraw it): INDEPENDENT may be concluded only
        from a corpus-wide search (all families/groups, not just the current one) for a
        connecting claim or shared derivation. A search bounded to the current group or
        batch that finds nothing yields UNWITNESSED, labelled NEGATIVE-BOUNDED, never
        INDEPENDENT. Only a search stated as corpus-wide and empty yields INDEPENDENT,
        labelled NEGATIVE-CENSUS. Mirrors R17 for object absence; applies here to
        relationship absence.

    Q2 TYPE COMPATIBILITY (are the formal signatures compatible?)
        type_compatibility ∈ { COMPATIBLE, PARTIALLY-COMPATIBLE, INCOMPATIBLE, UNKNOWN }
        A REPLACEMENT may be INCOMPATIBLE. A REFINEMENT may add arguments. A representation
        change may change the codomain. Q2 informs Q1 as a question to investigate,
        never as an answer.

    record for the pair: relationship, basis, type_compatibility, "what says this?",
    "what would make this wrong?"

  PER OBJECT (roll-up of its pairs):
    semantic_status ∈ { RECONCILED, IDENTITY-UNWITNESSED, HOMONYM-SPLIT, CONTESTED }
    type_status ∈ { CLOSED, INCOMPLETE, UNTYPED }
    mathematical_status ∈ { CONSISTENT, INCONSISTENT, UNDER-SPECIFIED, UNDECIDABLE-FROM-CORPUS, NOT-APPLICABLE }
    statistics where relevant: population, sample, independence, probability vs score, test validity
    DDD: bounded context, responsibility, invariant ownership, entity/VO/function, mapping
    births: inspect each CANDIDATE-*-BIRTH against its source → ESTABLISHED-*-BIRTH,
            or MOVED (earlier unnamed occurrence found), or UNORDERED-BLOCK
    layer: primary_layer settled; secondary_roles[] settled (B3)
    absences: for each NOT-EVIDENCED-IN-CAPTURE item run one targeted search over the
            corpus (notation variants, Unicode/LaTeX/ASCII, aliases, prose descriptions);
            found → back to P1-style capture for that source; not found →
            GENUINELY-UNDEFINED-AFTER-CENSUS. Search locates evidence; it never decides identity.
    dependency edges ONLY when a source supports them, typed:
        DEFINITIONAL (A is defined using B) · DERIVATIONAL (A follows from B: theorem,
        consequence) · USAGE · EXPLANATORY · VALIDATION · GOVERNANCE
        co-occurrence ≠ edge; a cycle is a finding, not proof of circularity
    IF completeness PARTIAL: MAY emit DERIVED-PROPOSAL → 35-DERIVED-PROPOSALS.md
        {object, missing, proposed completion, reasoning, evidence [S-ids], assumptions,
         what would confirm, what could falsify, status: PROPOSED}      # quarantine, R8
TERMINAL: every group and every load-bearing object has pair records, the three
    per-object statuses, layer, births, edges → 30-RECONCILIATION.md
```

## A12. PHASES 4–6

```
P4  FOR each object/form: membership ∈ {PRE-V1.2, V1.2, POST-V1.2, CROSS-VERSION, UNKNOWN}
    AND separately, each YES[S-id] | NOT-EVIDENCED-IN-CAPTURE:
        existed-before · defined-in · referenced-by · used-by · required-by · proposed-for ·
        adopted-in · ratified-in · created-after
    never infer one from another; recency/elegance/length/repetition are not criteria
    → 40-V12-MEMBERSHIP.jsonl

P5  VALIDATION VECTOR — one field per means, each ∈ {YES[S-ids], PARTIAL[S-ids],
    NOT-EVIDENCED-IN-CAPTURE, FAILED[S-ids]}:
        proof · experiment · simulation · implementation · test · independent_validation
    experiment ≠ proof; implementation ≠ definition; test ≠ independent validation.
    Each experiment cited keeps hypothesis→result→interpretation→limitations.
    → 50-VALIDATION-STATUS.md

P6  GOVERNANCE VECTOR — one field per act, each ∈ {YES[S-id, date, body],
    NOT-EVIDENCED-IN-CAPTURE}:
        reviewed · recommended · selected · adopted · ratified · rejected · withdrawn
    governance ≠ truth; not ratified ≠ false. Only here may a DERIVED-PROPOSAL be
    promoted, by a recorded act. → 60-GOVERNANCE-STATUS.md
```

## A13. PHASE 7 — SYNTHESIS (strongest model; from Phases 2–6 only)

```
FOR section IN [01 Foundations, 02 Core concepts (by primary_layer, roles noted),
    03 Definitions (+completeness), 04 Mathematical model, 05 Semantics,
    06 Invariants & constraints, 07 Analysis & rationale (rationale blocks; assumption
    provenance), 08 Warnings & non-collapse rules, 09 Experimental evidence,
    10 Extensions, 11 Corrections, retractions & DORMANT formulations, 12 Limitations,
    13 Open questions, 14 Future research (+ DORMANT branches + open DERIVED-PROPOSALs),
    15 Version history, 16 Validation status, 17 Governance status]:
    write 70-THEORY/<nn>-<section>.md
    EVERY statement carries: [S-id §anchor] († if SECONDARY-SYNTHESIS) · origin
        (EVIDENCE | DERIVED-PROPOSAL) · status vector (B4) as one compact line
    a section may be short; it may never be padded; absence is stated as
        NOT-EVIDENCED-IN-CAPTURE or GENUINELY-UNDEFINED-AFTER-CENSUS, never silently
SCRIPTS/check-theory: 17 files exist · every family and unresolved object cited ≥1 ·
    no statement without citation+origin+vector · DERIVED-PROPOSAL only in 03/14
GATE: label "KNOWLEDGEOS THEORY v1.2 — RECONSTRUCTED, PENDING GOVERNANCE REVIEW";
    route through the repository's review/ARB mechanism. Never FINAL.
```

## A14. MASTER DECISION TREE

```
START → P0 validate → P0.5 batch
  → WHILE batches remain: dispatch ≤4 in parallel → each agent: FOR file IN batch:
        read whole → dates → provenance → ALL contributions → ALL objects → verbatim anchors
        → accuracy check → flag math/stat → in-file lineage claims only → write → NEXT FILE
     → verify → merge names / park candidates → DONE
  → P1c close (date evidence, overlaps, register)
  → P2a normalize labels (groups, not identities) → P2b families (candidates, not verdicts)
  → P3 per pair: relationship+basis ∥ type compatibility → per object: statuses, births,
       layer, edges; absences searched; proposals quarantined
  → P4 v1.2 → P5 validation vector → P6 governance vector (proposals promotable only here)
  → P7 write with citation + origin + status vector
  → GATE (pending review)
```

---

# PART B — REFERENCE

## B1. Output layout — `OUTPUT_DIR`
```
prompts/  scripts/{validate-roadmap, plan-batches, verify-batch, close-phase1, near-dup,
          build-source-register, normalize-labels, derive-families, check-theory}
00-CORPUS-SNAPSHOT.txt · 00-ROADMAP-VALIDATED.jsonl · 01-BATCH-MANIFEST.jsonl
ledger/B0001/{files,contributions,index-proposals}.jsonl + summary.md
02-FILES.jsonl · 03-CONTRIBUTIONS.jsonl · 08-OVERLAP-REGISTER.jsonl
09-READ-STATUS.jsonl · 10-SELF-AUDIT-LOG.md
11-OBJECT-INDEX.jsonl · 11-UNRESOLVED-CANDIDATES.jsonl · 12-SOURCE-REGISTER.md
20-FAMILIES/{_LABEL-NORMALIZATION.md, _derived.json, <label>.md, _theory-level.md,
             _methodological.md, _UNRESOLVED-OBJECTS.md}
30-RECONCILIATION.md · 35-DERIVED-PROPOSALS.md · 40-V12-MEMBERSHIP.jsonl
50-VALIDATION-STATUS.md · 60-GOVERNANCE-STATUS.md · 70-THEORY/01…17-*.md
```
Machine records JSONL; narrative Markdown; never TSV for quoted text.

## B2. Contribution TYPES and SCOPE (shared with the contract)
```
TYPES: CONCEPT · DEFINITION · FORMALIZATION · AXIOM · PRINCIPLE · INVARIANT · ASSUMPTION ·
EXPLANATION · ARGUMENT · ANALYSIS · WARNING · CONSTRAINT · DISTINCTION · EXAMPLE ·
COUNTEREXAMPLE · EXPERIMENT · EXPERIMENTAL-RESULT · EXTENSION · ALTERNATIVE · CORRECTION ·
RETRACTION · CONTRADICTION · IMPLEMENTATION · VALIDATION · GOVERNANCE · LIMITATION ·
OPEN-QUESTION · FUTURE-RESEARCH · RESTATEMENT (provisional; only after whole-file reading;
records what is restated + any shift in wording, scope, assumption, or example) ·
HYPOTHESIS (a proposed, not-yet-confirmed claim the source frames as tentative — distinct
from ASSUMPTION, which is relied upon, not proposed)
SCOPE: OBJECT · CROSS-OBJECT · THEORY-LEVEL · METHODOLOGICAL
CLOSED LIST — never invent a type. A scope value is never a type (B0001 wrote
types:["METHODOLOGICAL"], confusing scope with type — rejected by verify-batch). A new
finding is ANALYSIS or EXPERIMENTAL-RESULT, never "DISCOVERY" (also rejected in B0001).
```
Classify the contribution, never the file's importance. A one-sentence warning has the
same standing as a formula.

## B3. Theory layers (provisional in P2, settled in P3; organise theory §02)
```
primary_layer   ∈ FOUNDATIONAL · DERIVED · OPERATIONAL · META-THEORETICAL · LAYER-UNRESOLVED
secondary_roles ⊆ the same set (an object may be foundational mathematically AND have an
                  operational representation — record both, never force one)
```
Same-named objects in different layers is a finding, not an error.

## B4. Status vector — independent fields, never collapsed

| Group | Field | Set in | Values |
|---|---|---|---|
| Evidence | `source_status` | P1/P2 | WITNESSED · WITNESSED-PARTIAL · WITNESSED-INFORMAL · WITNESSED-NAME-ONLY · SECONDARY-ONLY · NOT-EVIDENCED-IN-CAPTURE · GENUINELY-UNDEFINED-AFTER-CENSUS |
| Identity | `semantic_status` | P3 | RECONCILED · IDENTITY-UNWITNESSED · HOMONYM-SPLIT · CONTESTED |
| Identity | `relationship` (per pair) | P3 | SAME · REFINEMENT · EXTENSION · REDEFINITION · REPLACEMENT · SPECIALIZATION · DERIVED-FROM · CONTINUATION · HOMONYM · INDEPENDENT · UNWITNESSED — with `basis` CORROBORATED · SOURCE-CLAIMED-ONLY · INFERRED · NONE |
| Form | `type_compatibility` (per pair) | P3 | COMPATIBLE · PARTIALLY-COMPATIBLE · INCOMPATIBLE · UNKNOWN |
| Form | `type_status` | P3 | CLOSED · INCOMPLETE · UNTYPED |
| Form | `mathematical_status` | P3 | CONSISTENT · INCONSISTENT · UNDER-SPECIFIED · UNDECIDABLE-FROM-CORPUS · NOT-APPLICABLE |
| Lifecycle | `lifecycle` | P2→P3 | ACTIVE · DORMANT · RETRACTED · SUPERSEDED · CONTESTED (SOURCE-CLAIMED-* until corroborated) |
| Validation | vector | P5 | proof · experiment · simulation · implementation · test · independent_validation — each YES[S] · PARTIAL[S] · NOT-EVIDENCED-IN-CAPTURE · FAILED[S] |
| Governance | vector | P6 | reviewed · recommended · selected · adopted · ratified · rejected · withdrawn — each YES[S,date,body] · NOT-EVIDENCED-IN-CAPTURE |
| Origin | `origin` | any | EVIDENCE · DERIVED-PROPOSAL |

Compact line for the theory, e.g.:
`[S1834 §"Two concessions"] · EVIDENCE · WITNESSED-PARTIAL · RECONCILED(REPLACEMENT/CORROBORATED, type INCOMPATIBLE) · INCOMPLETE · UNDER-SPECIFIED · V: exp=YES[S2210] impl=YES[S2301] indep=NOT-EVIDENCED · G: reviewed=YES[S2400] ratified=NOT-EVIDENCED`

"Uncontradicted" ≠ true · "type-closed" ≠ valid · "in corpus" ≠ validated ·
"incompatible type" ≠ different object.

## B5. Phase 1 record contracts (authoritative copy lives in the contract file)
- `files.jsonl`: `source_id, path, commit, file_mtime, mtime_block, explicit_dates[],
  status ∈ {CONTENT, FIREWALL-LIMITED}, summary, objects_touched[],
  contribution_assessment (never empty), provenance ∈ {PRIMARY, SECONDARY-SYNTHESIS,
  PROVENANCE-UNRESOLVED}, in_file_overlap_claim`.
- `contributions.jsonl`: `source_id, anchor, explicit_date, scope, labels[],
  label_confidence, unknown_candidate{candidate_of[], why}, types[], statement,
  type_signature{…}, completeness ∈ {COMPLETE, PARTIAL, INFORMAL-ONLY, NAME-ONLY, N/A},
  missing[], dependencies[], invariants[], assumptions[{statement, stated ∈ {EXPLICIT,
  USED-UNSTATED}, anchor}], lineage_claims[{kind ∈ SOURCE-CLAIMED-{IDENTITY, REFINEMENT,
  EXTENSION, REDEFINITION, REPLACEMENT, SPECIALIZATION, DERIVATION, CONTINUATION,
  SEPARATION, CONTRADICTION, RETRACTION}, target, quote}] — one claim kind per
  relationship P3 can conclude, so P1 can always preserve the evidence P3 needs; version_ref,
  experiment{hypothesis…conclusion}, review_flag ∈ {MATH-QUESTION, STAT-QUESTION,
  TYPE-QUESTION}`.
- `stated: USED-UNSTATED` is an **agent observation** (the source relies on it visibly
  without saying so) — kept apart from source claims (R6); the anchor shows where.

## B6. Worked cases
- **δ_A : K×E→K, later "δ_B replaces δ_A", δ_B : S_rep×O_core×CTX→S_rep×Trace** —
  P1: two forms + one SOURCE-CLAIMED-REPLACEMENT. P3: relationship REPLACEMENT with
  basis CORROBORATED if semantic continuity (same role: state transition) is shown, else
  SOURCE-CLAIMED-ONLY; type_compatibility INCOMPATIBLE. Same lineage, different type —
  never HOMONYM on type alone.
- **K_t / K*_t / K⁺_t / S_t / "knowledge state"** — P1 labels as used or
  UNKNOWN-OBJECT-CANDIDATE; P2a groups them; P2b shows the order; P3 decides each pair.
  Nobody needs to know beforehand that K*_t is "the answer".
- **"Zero is a meta-principle outside the kernel" vs `Zero(K,EC,Γ) ⇔ Δ=∅`** — two rows;
  P3 tests relationship (REDEFINITION? SPECIALIZATION? HOMONYM?) and layer
  (META-THEORETICAL vs FOUNDATIONAL) separately — possibly primary + secondary role.
- **"This distinction must never be collapsed."** — WARNING, scope THEORY-LEVEL or
  METHODOLOGICAL, full standing; theory §08.
- **P(A)=1.2 called a probability** — recorded verbatim; `review_flag: STAT-QUESTION`;
  assessed in P3. The source is never rewritten.
- **A concept never given a purpose anywhere** — rationale block reads
  NOT-EVIDENCED-IN-CAPTURE; after P3's targeted search fails,
  GENUINELY-UNDEFINED-AFTER-CENSUS; theory §07 says exactly that.

## B7. Model and cost
Extraction agents: default model (fidelity from contract + verify-batch). Self-audit,
P2a, P3, P7: strongest available. ~73 batches; 3–4 parallel → P1 in a handful of sessions.

## FINAL RULES
```
RECORD THE HISTORY BEFORE DECIDING WHAT THE HISTORY MEANS.
PRESERVE THE EVIDENCE BEFORE REDUCING IT.
RECONSTRUCT THE OBJECTS BEFORE DEFINING THE THEORY.
DECIDE RELATIONSHIP AND TYPE SEPARATELY.
VERIFY THE MATHEMATICS BEFORE CANONICALIZING.
NEVER LET OUR OWN SYNTHESIS BECOME THE SOURCE.
REPORT WHAT IS HALF-BUILT AS HALF-BUILT; REPORT WHAT IS NOT FOUND AS NOT FOUND.
```
