# CAP-001 — Architecture Stabilization Report

| | |
|---|---|
| **Kind** | **ARCHITECTURE FITNESS REVIEW.** ***No features added. No speculative abstraction. No new layer.*** |
| **Authority** | ⚠️ **Generated — never authoritative without human review** |
| **Status** | **DELIVERED.** §7 returns a readiness assessment |
| **Commission** | PA, 2026-08-02 — *"validate that CAP-001 establishes the canonical implementation pattern… architectural fitness rather than feature completion"* |
| **Placement** | ⭐ **DERIVED** — resolver → `docs/pks` (exit 0) |
| **Suite** | **28 tests · 41 assertions · OK** after every change below |

---

## 1. Architecture tree

```
scripts/lib/EngineeringKnowledge/
├── Shared/
│   └── Domain/
│       ├── Verdict.php          AP-8's closed vocabulary
│       └── Assessment.php       verdict + evidence + opaque subject
└── Capabilities/
    └── IdentifierIntegrity/
        ├── README.md            ← the capability contract; TEMPLATE for CAP-002+
        ├── Domain/
        │   ├── Identifier.php · IdentifierSeries.php · SeriesContents.php
        │   ├── IdentifierPolicy.php              (DP-1, stated once)
        │   └── AssessesIdentifierIntegrity.php   (applies the policy)
        ├── Application/
        │   ├── SeriesContentsReader.php          (PORT)
        │   └── ValidateIdentifier.php            (use case)
        ├── Infrastructure/                        (empty — adapters next)
        └── Tests/{Domain,Application}/
```

⛔ **`Shared/Application` and `Shared/Infrastructure` were NOT created.** *ES-005.2: a directory exists when its first artifact arrives. Nothing is shared at those layers.*

## 2. Dependency graph — verified, not asserted

```
CLI  ──▶  Infrastructure  ──▶  Application  ──▶  Domain  ──▶  Shared
                                                              (knows no capability)
```

| # | Fitness rule | Command | Result |
|---|---|---|---|
| **1** | Shared references no capability | `grep -rn "Capabilities\\\\" Shared` | ✅ **empty** |
| **2** | Domain references no Infrastructure/Application | `grep -rn "Infrastructure\|Application" Capabilities/*/Domain` | ✅ **empty** |
| **3** | Application does not reach into Infrastructure | `grep -rn "Infrastructure" Capabilities/*/Application` | ⚠️ **one hit — a DOCBLOCK sentence** *("Infrastructure is REPLACEABLE behind this interface")*. **Not a dependency.** Disclosed rather than reported as clean |
| **4** | One namespace hierarchy only | `grep -rh "^namespace"` | ✅ **four namespaces, all under `Capabilities/IdentifierIntegrity` or `Shared`** |
| **5** | One PSR-4 root | `composer.json` | ✅ **`EngineeringKnowledge\ → scripts/lib/EngineeringKnowledge/`** |

## 3. Shared inventory — justification for every class

| Class | Justification | Verdict |
|---|---|---|
| **`Verdict`** | **AP-8 governs the closed vocabulary for EVERY capability.** *Not "expected to be reused" — **already governed as capability-independent***. References no capability type | ✅ **KEEP IN SHARED** |
| **`Assessment`** | The shape *verdict + evidence* is what **AP-1** (output, not decision) and **DP-5** (state the evidence) require of every capability. Subject is an **opaque string** | ✅ **KEEP IN SHARED** |

**Rejected extractions — recorded so they are not re-proposed:**

| Candidate | Why NOT Shared |
|---|---|
| `Identifier` · `IdentifierSeries` · `SeriesContents` | **capability-specific.** Reference Integrity deals in references, not identifiers |
| `RegisterName` *(as proposed)* | same concept as `IdentifierSeries`; capability-specific |
| `FileSystem` · `YamlLoader` *(as proposed)* | ⛔ **no second consumer.** CAP-001 reads markdown. *"Do not extract because it might be reused"* |
| `Policy` interface | ⚠️ **one instance exists.** CAP-002 is the second consumer that would decide it — see §6 O-5 |

## 4. Capability README

**Delivered:** `Capabilities/IdentifierIntegrity/README.md` — purpose · responsibility · governing rules · inputs/outputs · **ubiquitous language (11 terms, each traced to M4/M6/PMR-10, none coined)** · six invariants · verdict mapping · dependencies · extension points · **what belongs / what explicitly does not**.

## 5. Fitness report

| Dimension | Result |
|---|---|
| **Dependency fitness** | ✅ **PASS** — §2, mechanically verified |
| **Vocabulary fitness** | ⚠️ **ONE FAILURE FOUND AND FIXED** — see below |
| **Capability fitness** | ✅ **PASS** — all seven capability classes contribute directly to identifier integrity. **No accidental utilities** |
| **Reuse fitness** | ✅ **PASS** — both Shared classes have *governed* reuse (AP-8/AP-1), not anticipated reuse |

**⚠️ Vocabulary failure, found by applying the commission's own rule to my own code:**

> **`IdentifierValidationService`** was structurally identical to `ParsingService` on the prohibited list — *technical action + Service*.
>
> **Renamed → `AssessesIdentifierIntegrity`**, matching **house precedent**: `app/Contexts/Election/Application/Service/ResolvesEvidencePreservationWindow.php`. **Verb-phrase service names are already this repository's convention.**

**Names retained, with reasons:** `ValidateIdentifier` — a use case named as a **command**, which is conventional DDD and describes an *intention*, not a mechanism. `SeriesContentsReader` — a **port** named for the role it provides; the prohibited form was `ReaderManager`.

## 6. Architectural observations *(recorded — NOT elevated to methodology)*

| # | Observation | Evidence | Architectural consequence |
|---|---|---|---|
| **O-1** | ⭐ **Shared cannot depend on a capability, and the type system will say so** | Extracting `Assessment` while still typed `?Identifier` produced a **compile-time TypeError**, not a review comment | Shared carries an **opaque string subject**. *The docblock records why, so it is not "improved" back* |
| **O-2** | **Renaming a class to drop a technical-action word is a vocabulary act, not cosmetics** | `ValidationResult → Assessment` aligned the type with **CBC-1's governed UL** | Names are checked against the UL, not against convenience |
| **O-3** | **The directory move alone would have hidden O-1** | The layer-first tree let `Assessment` sit beside `Identifier` with no error | ***Extraction is an architectural test; relocation is not.*** Move code to *discover* coupling, not merely to file it |
| **O-4** | **Capability-owns-layers survived its first contact** | Rename, extraction and restructure touched **zero** test assertions | The seam between capability and Shared is real |
| **O-5** | ⚠️ **`IdentifierPolicy`'s four methods map 1:1 onto the four verdicts** | `appliesTo · isEvaluable · isViolatedBy · isAtRiskOfViolation` | **May be a reusable Policy shape — or may be identifier-specific.** ⛔ **n=1. Not extracted.** CAP-002 decides |
| **O-6** | **The verdict VOCABULARY is shared; the verdict MAPPING is not** | AP-8 is capability-independent; *"cited but unminted → WARN"* is identifier-specific | Clean line: `Verdict` in Shared, mapping in the capability service |

⛔ **None of these is a methodology candidate.** *One corpus, one capability, n=1 throughout.*

## 7. Readiness assessment

### 7.1 Would CAP-002 require structural refactoring?

| Prospective capability | Reuses from Shared | Stays local | Refactoring needed? |
|---|---|---|---|
| **Projection Integrity** | `Verdict` · `Assessment` | Projection · Source · Regeneration · `ProjectionSourceReader` port | ⛔ **NONE** |
| **Vocabulary Integrity** | `Verdict` · `Assessment` | Term · Definition · Homonym · a term-source port | ⛔ **NONE** |
| **Relationship Integrity** | `Verdict` · `Assessment` | Relationship · Edge · traversal | ⚠️ **POSSIBLY** — if it needs graph traversal, a Shared traversal concept may be wanted. **That is H-CAT-4 and is unevidenced.** *Not a defect in the present architecture; a question for whenever it is attempted* |

**The template test, stated as the commission states it:**

> *"A future capability can be created by copying this structure, replacing only the domain concepts, and leaving the architecture unchanged."*

✅ **Satisfied for Projection Integrity and Vocabulary Integrity.** ⚠️ **Unproven for Relationship Integrity** — and it will remain unproven until one is attempted. *Recorded honestly rather than claimed.*

### 7.2 Verdicts

| Question | Answer |
|---|---|
| **Is CAP-001 the canonical capability template?** | ✅ **YES for structure, vocabulary and dependency direction** — with the honest limit that **a template is only proven by its second use** |
| **Can CAP-002 begin without architectural restructuring?** | ✅ **YES** — for Projection or Vocabulary Integrity |
| **Are there architectural deficiencies blocking further work?** | ⛔ **NONE FOUND.** Two items are *open questions*, not deficiencies: **O-5** (is the Policy shape reusable?) and **Relationship Integrity's** traversal need |

### 7.3 ⚠️ What is NOT stabilized

**CAP-001 is architecturally stable and functionally incomplete.** Outstanding: `MarkdownSeriesContentsReader` · `CliOutputFormatter` · the CLI entry point · the developer guide · **the operational evidence run.**

> ***The architecture has been verified. Its VALUE has not.*** **No evidence yet exists that this capability changes an engineering outcome — which is the only claim Phase III actually wants.**

---

*Traceability: PA stabilization commission 2026-08-02 · four fitness dimensions verified **by command, not assertion** · one vocabulary failure found in my own code and fixed against house precedent · Shared inventory justified per class, **four proposed extractions rejected for want of a second consumer** · six observations recorded, **none elevated** · CAP-002 readiness assessed for three prospective capabilities · **no features added · no speculative abstraction · no new layer · suite green throughout (28/41).***

> **⛔ STOP. Architecture stabilized. The next act adds infrastructure and produces the first operational evidence.**
