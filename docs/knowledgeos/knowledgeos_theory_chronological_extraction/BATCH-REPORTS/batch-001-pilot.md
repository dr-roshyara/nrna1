# Batch 001 — Pilot Execution Report (F0001–F0010)

| | |
|---|---|
| **Scope** | first 10 files of `docs/knowledgeos/list_of_files_to_read.log` |
| **Protocol** | `docs/knowledgeos/backlog/knowledge_os_protocoll.md` @ `f3e606cac` |
| **Gates executed** | §45 Gate 4 (Phase 0) + Gate 5 (full pipeline) — **Gates 1–3 were NOT executed first** (see PILOT-F13) |
| **Files read completely** | 10 / 10 (§2 honoured; no file skipped, no excerpt-only read) |
| **Outcome** | The protocol is **executable**, produces genuinely useful output, and **surfaced defects in itself** — which is what a pilot is for |

---

## 1. Counters (§40 process-completeness indicators)

```text
files_read / files_expected            10 / 10
files_validated / files_read           10 / 10   (record integrity only, not math)
evidence_records / files_read          14 / 10
verified_edges / candidate_edges       10 / 0    ← see PILOT-F07
gaps_open                              5
gaps_resolved                          0
contradictions_open                    3
contradictions_resolved                2
theory_objects_candidate               3
theory_objects_reconstructed           4
architecture_objects                   0         ← BLOCKED, see PILOT-F05
research_obligations_open              6
unresolved_files                       0
```

---

## 2. Findings

### PILOT-F01 — **CRITICAL: registry order is not chronological order**

All ten files carry the identical registry timestamp `Aug 5 15:44`. That is the **filesystem mtime** — the date the files were copied/committed — **not** the authorship date. The real chronology, recoverable only from document content, is:

```text
2026-08-02:  F0001, F0005, F0008, F0009, F0010
2026-08-03:  F0002, F0003, F0004, F0006, F0007
```

Registry order therefore places five **later** documents (F0002–F0004, seq 2–4) **before** five **earlier** ones (F0008–F0010, seq 8–10).

**Consequence:** §0A's founding construct — the chronological sequence `F₁ → F₂ → … → Fₙ` — is built on a field that does not carry chronology for this corpus. §9's stateful `State(Fᵢ) → State(Fᵢ₊₁)` chain, §0E.2's "compare against the immediately preceding file", and §8A's chronological Phase 0 all inherit the defect. I read F0002 (Aug 3) at sequence 2 and F0008 (Aug 2) at sequence 8 — encountering a **later** document's conclusions six files before the **earlier** document they were drawn from.

This is not a small ordering nuisance. It silently inverts causality in exactly the way §3 spends a whole section forbidding.

### PILOT-F02 — **Identifier collisions are pervasive, and the corpus does not notice them**

Within ten files:

| Label | Distinct meanings found |
|---|---|
| `C-1` | model amnesia (F0002, F0006) · Product Primacy (F0003) · a correction ID (F0010) — **3 meanings** |
| `I-1` | Knowledge ≠ Artifact (F0002) · "the strategic model already exists" (F0009) · a lint rule ref (F0003) |
| `R-n` | rejected claims R-1..R-12 (F0003) · platform rulings R-1..R-77 (F0003, same file!) · R-A/R-B/R-C responsibilities (F0010) |
| `E-1` | EKP consumption falsification (F0002, F0006) · "PKS Generation n=0" evidence gap (F0005) |
| `D-n` | decision packages (F0006) · source role "Discovery" (F0002) |
| `P1–P5` | principles (F0001) — the file **itself** flags these as document-local and un-minted |

§19A's identity test (six criteria, `same label ≠ same Theory Object`) is **empirically vindicated** — a naive label-matching reconstruction would have merged three unrelated `C-1`s on file one of one. Recorded as `C-0005`.

### PILOT-F03 — **§4A's ID namespace collides with the corpus's own registers**

The protocol mints `F####` (File), `AR-###` (Architecture Object), `T-###` (Theory Object), `C-###` (Contradiction), `P-###` (Proposition), `G-###` (Gap), `D-###` (Definition), `R-###` (Relationship). The corpus **already uses**: `F-1..F-3` (failures), `AR-1` (architectural risk), `T1–T5` (tiers), `C-1..C-12` (canonical concerns), `P1–P5` (principles), `G-1..G-10` (evidence gaps), `D-1..D-10` (decision packages), `R-1..R-77` (rulings).

**Every single one of eight protocol namespaces collides with a live corpus register.** §4 forbids "a second, competing File ID scheme" but says nothing about the protocol's *other* namespaces colliding with identifiers that already exist *inside* the material being reconstructed. Writing `G-0001` into a corpus that already means something else by `G-1` is a provenance hazard.

### PILOT-F04 — **§26's Track-A/B rule is non-executable**

The deterministic rule's first branch is *"IF file/path belongs to the registered Track-B corpus"*. **No such register exists or was supplied.** By the rule as written, every file falls through to `UNKNOWN` — which is what I recorded for all ten. The firewall §26 protects cannot be enforced because its input does not exist. This is a `[BLOCKING]` item that §49 does not currently list.

### PILOT-F05 — **No Reference Architecture ⇒ Layer 2 steps 12–14 cannot execute (confirms §49 item 1)**

I could not perform `MAP FILE TO REFERENCE ARCHITECTURE`, `IDENTIFY ARCHITECTURAL ROLE`, or `DETECT NEW/MISSING/CHANGED COMPONENTS`. `ARCHITECTURE-OBJECTS.jsonl` is empty and all `kernel_classification` values are `UNCLASSIFIED`.

**But the pilot found something §0C did not anticipate:** the *Emergent Historical Architecture* is executable **immediately** — F0001 §2 hands over five tiers and six subsystems with per-subsystem evidence status. The dependency is one-directional: `HA-###` needs no Reference Architecture, but I did not mint them either, because §0C frames both as a *pair* to be compared. **§0C should permit the historical side to run alone.**

### PILOT-F06 — **Volume extrapolation: the corpus is far larger than §19C assumes**

Ten files yielded ~60 named objects, ~25 definitional statements, 7 theory objects worth recording, 5 gaps, 5 contradictions, 7 research obligations. Linear extrapolation to 3,081 files: **~18,000 named objects**. §49 item 8's illustrative figure ("500 historical objects consolidate into roughly 120 candidates") is off by roughly **36×**. Not a defect in the schema — but the Candidate Theory Registry must be designed for ~10⁴, not ~10².

### PILOT-F07 — **The candidate/verified distinction collapsed in practice**

I recorded **10 verified edges and 0 candidate edges**. Not because discovery was perfect, but because in a corpus this explicitly cross-referenced, an edge is either stated outright (→ verified immediately) or entirely absent (→ never became a candidate). §8's `Candidate Search → Candidate Edge → Evidence Retrieval → Complete-file comparison → Verified Edge` pipeline degenerated to a single step. The candidate layer may only earn its keep where ML/embedding discovery is actually used (§27) — which this pass did not need.

### PILOT-F08 — **The corpus self-declares its epistemic status; the protocol has no field for it**

Every file opens with a header table carrying `Kind` / `Status` / `Authority`, e.g. *"CANDIDATE — NOT ADOPTED"*, *"Generated — never authoritative without human review"*, and closes with *"Nothing in this document executes."* Nine of ten also declare `Placement: DERIVED → docs/knowledgeos (exit 0)` — machine-verifiable provenance.

This is a **gift** to §29's `SOURCE_ASSERTED_CONCLUSION` handling, and the protocol currently ignores it. A `source_self_declared_status` field would capture it at near-zero cost and materially improve `historical_derivation_status` accuracy.

### PILOT-F09 — **Gap and Contradiction are two views of one finding**

The verdict-vocabulary collision is simultaneously `G-0005` (a missing scoping rule) and `C-0004` (two closed sets that conflict). I recorded it twice with a cross-reference. The protocol gives no guidance on whether that is correct or duplicative; at 3,000-file scale, systematic double-recording would inflate both ledgers.

### PILOT-F10 — **Intra-file revision is a third case the protocol does not model**

§5A versions *our* records; §16 handles *file→file* resolution. But this corpus corrects itself **inside a single file**: F0005 contains *"CORRECTED 2026-08-02 — '0 OF 11 ENFORCING' IS TOO STRONG"*; F0006's Package 1 amendment downgrades its own supporting evidence; F0008's F-3 row is struck through in place. The original claim, the correction, and their order all sit in one file with one `file_id`. Neither §5A nor §16 covers this.

### PILOT-F11 — **§0E.4's Level 2 worked well and was cheap**

Documentation-quality assessment was the *easiest* part of the pass and produced a genuinely useful finding on F0008 (`internal_consistency: PARTIAL` — title verdict vs scope correction), which F0010 §0 C-2 independently confirms. The 12-dimension schema was more than needed; ~6 dimensions carried all the signal.

### PILOT-F12 — **Checkpoint hashing was specified but not implementable here**

§36A requires `corpus_manifest_hash` / `protocol_hash` / `schema_version`. No tooling exists to compute or verify them, so `RECONSTRUCTION-STATE.json` records them as `NOT_COMPUTED`. §49 item 4 (`OPERATIONAL`) is confirmed as genuinely blocking for *resumable* execution, though not for a single-session pilot.

### PILOT-F13 — **The pilot ran Gates 4–5 without Gates 1–3, and that was the right call**

§45 orders: freeze Reference Architecture → freeze schemas → freeze state semantics → *then* pilot. Had I obeyed strictly, the pilot could not have started (Gate 1 is blocked on human authoring). Running the pilot **first** is what produced PILOT-F01 through F05 — findings that will change what gets frozen in Gates 1–3. **The gate order in §45 is inverted for its own purpose.**

---

## 3. Batch completion status (§37)

`BATCH_PROCESSING_COMPLETE`: **NO.**

| § | Criterion | Status |
|---|---|---|
| 1 | every file terminal | ✅ 10/10 `COMPLETED` |
| 2 | every §9A gate evaluated | ⚠️ `ARCHITECTURE_ALIGNMENT_CHECKED` **cannot** be evaluated (PILOT-F05) |
| 3 | all required records exist | ⚠️ 1 of 10 dossiers written; `THEORY-THREADS`/`CANDIDATE-THEORY-OBJECTS`/`DERIVATION-INSTANCES` not minted |
| 4 | all cross-references resolve | ⚠️ `DI-0001..0005` referenced from `THEORY-OBJECTS.jsonl` but no `DERIVATION-INSTANCES.jsonl` written — **a real dangling-reference defect this check caught** |
| 5 | §37 checks pass | ✅ IDs unique, paths/timestamps preserved, no candidate silently promoted, no Track-B leak (nothing classified at all) |
| 6 | unresolved findings recorded | ✅ |

Criterion 4 catching a genuine dangling reference in my own output is the single best evidence in this report that §37 is worth running.

---

## 4. Verdict

The protocol **works**. Ten files in, it produced a defensible reconstruction with real correction chains, real gaps, and real contradictions — and it refused to overclaim at every point where it should have.

It is **not ready for 3,000 files**, for reasons the pilot rather than the document had to find: registry chronology (F01), namespace collision (F03), and an unenforceable track firewall (F04) are all `BLOCKING`, and none of the three was on §49's list before today.
