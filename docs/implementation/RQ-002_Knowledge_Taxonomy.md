# RQ-002 Deliverable 9 — Knowledge Taxonomy and Classification

**Kind:** synthesis artifact (the semantic foundation for a future Reference Architecture) — NOT a folder structure, NOT templates, NOT an ontology of documents, NOT design.
**Status:** COMPLETE — presented for ARB review. **STOP: the Reference Architecture may not be written until the ARB rules.**
**Evidence base (closed):** RQ-002 charter · Raw Findings (executors A/B/C/D) · Strategic Discovery Report · Architectural Synthesis (PK-P1..P10, invariants I-1..9). No new research; every classification is traceable; candidates are validated **or falsified**, not assumed.

---

## 0. The headline result

**"Project knowledge" is a FAMILY of knowledge types sharing one constitutional core, not a homogeneous domain.** Seven types survive the evidence; five candidates are falsified or merged. The types differ on *every* governance-relevant axis — producer, validation, lifecycle, retirement, freshness, discovery — while all obey the nine invariants of the Architectural Synthesis (status-at-discovery, provenance, supersession-by-link, …). Uniform treatment of knowledge is therefore an architectural error the evidence forbids; per-type governance is mandatory.

---

## 1. Validated knowledge types (seven)

### Knowledge Type 1: Executable Knowledge
- **Definition:** Knowledge encoded so that a machine can check it — code, tests, gates, fitness functions, and business rules/invariants *when encoded* in them.
- **Producer:** Developers, in the act of building (producer = consumer, by construction).
- **Consumer:** Developers, CI, agents; the highest-trust stratum for every consumer type.
- **Authority model:** The passing run. Authority is earned per execution, not assigned.
- **Validation method:** EXECUTION — the only mechanism empirically shown to keep an artifact true (B: trust r=0.67 at test proximity; D: curated-KB grounding 6% vs 35%).
- **Lifecycle:** Changes with its referent in the same gesture — the survival mode (B: the only documentation class updated promptly).
- **Retirement model:** Deleted with the code it guards; history preserved by version control.
- **Freshness model:** Self-enforcing — a stale executable fails.
- **Discoverability model:** Live-source search (agentic search; repository legibility = scent for this type — D).
- **Engineering Platform relationship:** already implemented — the merge gate, fitness suites, greenfield PHPStan are this type in production.
- **Evidence:** B (trust stratification, code-proximate survival) · D (execution-grounding) · PK-P4.

### Knowledge Type 2: Decision Knowledge
- **Definition:** A recorded choice with context, alternatives, rationale, consequences (ADR-class; rulings; the "why" code cannot express).
- **Producer:** Deciders at decision time — the capture window is decision time or never (C: vaporization).
- **Consumer:** Future maintainers, newcomers, auditors, agents (rationale is exactly what agents cannot derive from code — D).
- **Authority model:** Canonical = accepted ∧ unsuperseded ∧ owned ∧ **bound to code** (the one longitudinal ADR success required enforcement binding — B).
- **Validation method:** Enforcement-binding + human review; neither executes nor arises in conversation — the hardest type to validate (B).
- **Lifecycle:** IMMUTABLE — regime 1 of PK-P5; a dated decision stays true as a historical fact.
- **Retirement model:** Supersession by link with reason; the only mainstream form with built-in retirement semantics (B).
- **Freshness model:** Does not stale as history; its *canonicity* stales — status upkeep is the (never-measured) weak point.
- **Discoverability model:** Must surface when the governed code is touched — discoverability failure is the documented ADR consumption gap (B: readership never measured, "nobody read them before opening a PR").
- **Engineering Platform relationship:** already implemented (ADR corpus, rulings register, append-only, supersession) — the platform is an existence proof of this type done per the evidence.
- **Evidence:** B (NKM-06/ADR corpus of findings) · C (F1.2) · A (NKM-10) · highest-demand/lowest-capture finding (D F-CS-6, B).

### Knowledge Type 3: Definitional Knowledge (Domain)
- **Definition:** Ubiquitous-language terms with meaning fixed inside one bounded context; context boundaries themselves.
- **Producer:** The whole team, jointly and continuously (knowledge crunching — analysts-as-intermediaries break it).
- **Consumer:** The same team (speech + code identifiers); newcomers via code and conversation — rarely via glossary (B).
- **Authority model:** The living conversation + the code's identifiers; a glossary is derivative, never authoritative (Living Glossary exists as a tacit admission hand-maintained ones fail — B).
- **Validation method:** CONVERSATIONAL — drift is heard as awkwardness; term drift is a *boundary signal*, not mere decay (B).
- **Lifecycle:** Continuous evolution; splinters when contexts blur; **rots when the owning conversation stops** (~2 years after owner departure).
- **Retirement model:** Terms retire when their context retires; historical meanings preserved in decision records that used them.
- **Freshness model:** Freshness = conversation cadence; unmeasurable from the artifact alone.
- **Discoverability model:** In the code (identifiers) and in people; glossaries have weak scent unless regenerated from code.
- **Engineering Platform relationship:** partially implemented (Phase-02.6 vocabulary, OI-1 renames-by-supersession); the conversational-validation assumption is challenged when engineers are AI (open question §3).
- **Evidence:** B (NKM-01/02; Özkan SLR validation vacuum) · smallest-unit answer (term-bound-to-context).

### Knowledge Type 4: Descriptive Knowledge
- **Definition:** Prose describing current state: guides, READMEs, views, runbooks/procedures (operational subtype).
- **Producer/Consumer:** Survives ONLY where these are the same population (docs-as-code); second-population variants (wikis) decay to graveyard — the best-evidenced failure in the corpus.
- **Authority model:** Ownership + freshness date + review-with-code (the Google model); ownerless descriptive content is definitionally non-authoritative.
- **Validation method:** Review coupled to code change; regeneration from live sources where possible — noting automation *relocates* staleness if the source model is unmaintained (B/C5-auto).
- **Lifecycle:** EVOLVES/REGENERATES — regime 2 of PK-P5; materially stale within 30–90 days without coupling (D).
- **Retirement model:** Staged ROT disposal: merge+redirect / update-or-retire / archive — never silent abandonment.
- **Freshness model:** Owner + last-verified stamp, priced to the domain half-life; "last edited" is a proxy, not validity (A).
- **Discoverability model:** Scent-dependent (labels, summaries, addressability) — the type where foraging theory bites hardest.
- **Operational subtype note:** runbooks validate by USE (a followed runbook that works is validated; one that fails is challenged immediately) — the strongest consumption-feedback loop of any prose form.
- **Engineering Platform relationship:** implemented as developer guides (DoD-coupled = same gesture ✅) and READMEs; the wiki anti-pattern is structurally avoided.
- **Evidence:** B (NKM-07/09/10/13; Google GooWiki 90%) · C (ROT, scent) · A (drift 28.9%/4.7yr).

### Knowledge Type 5: Experiential / Research Knowledge
- **Definition:** Findings, lessons, retrospective observations, research charters/NKMs — claims produced by learning, **input-only until promoted**.
- **Producer:** Learners/researchers post-experience or post-review; historically captured under mandate at the worst moment (project end — A/LLIS).
- **Consumer:** Future deciders — via the promotion chain, never directly as authority.
- **Authority model:** NONE intrinsically — an experiential claim gains authority only by surviving evaluation and being promoted (McElroy; the platform's own pattern→evidence→retrospective→adoption chain).
- **Validation method:** CLAIM EVALUATION at intake — the gate almost no system implements, whose absence is the lessons-learned failure signature (A).
- **Lifecycle:** Append-only record; falsified claims retained as still-informative (negative knowledge prevents error repetition).
- **Retirement model:** Never edited; superseded by newer findings; archives remain retrievable.
- **Freshness model:** Two-sided — born-stale risk at intake (58.4%) dominates aging; intake validation ≥ review cadence.
- **Discoverability model:** The historical failure point (NASA: full repository, undiscoverable lessons); requires indexing by *future task*, not by past project.
- **Engineering Platform relationship:** implemented and working — pattern cards, evidence register, retrospective inbox, this very RQ-002 chain are this type with the claim-evaluation gate the literature says is missing.
- **Evidence:** A (NKM-14 knowledge claim; LLIS audits) · C (F3.3) · platform's own EPC discipline.

### Knowledge Type 6: Qualification / Evidence Knowledge
- **Definition:** Records of what instruments measured — gate outputs, qualification verdicts, baselines, provenance chains.
- **Producer:** Instruments (measuring runs), never authors; recorded as produced, never asserted (I-9).
- **Consumer:** Deciders, auditors, retrospectives, dashboards (derived-only).
- **Authority model:** The instrument + its recorded configuration (thread counts are part of measurement semantics — F-7D-2 as first-party evidence); authority is never editorial.
- **Validation method:** The run itself IS the validation; re-validation = re-run.
- **Lifecycle:** IMMUTABLE record with verdict-history semantics (PASS AFTER CORRECTION — history is part of the verdict).
- **Retirement model:** Never retired as fact; superseded as *current baseline* by newer runs; the old measurement stays true of its moment.
- **Freshness model:** Timestamped by construction; staleness = referent drift, detected by re-running.
- **Discoverability model:** Attached to the thing qualified (report ↔ ticket ↔ commit); provenance chains make lineage traversable.
- **Engineering Platform relationship:** fully implemented (OQ records, merge-gate outputs, Infection baselines, append-only session evidence) — the platform's most mature type.
- **Evidence:** C (PROV, provenance-as-substrate) · B (verification-by-execution) · platform practice as corroborating first-party evidence.

### Knowledge Type 7: Tacit Knowledge
- **Definition:** Mental models, skills, judgment — the actual operational knowledge store of every team (strongest-evidenced concept in the corpus).
- **Producer:** Every practitioner, involuntarily, by doing.
- **Consumer:** Colleagues via interruption (the empirically preferred channel); successors via apprenticeship.
- **Authority model:** Expertise recognition — social, unrecorded, real.
- **Validation method:** USE and peer conversation; not artifact-checkable.
- **Lifecycle:** Built by practice → strengthened by retrieval → decays with disuse → **lost abruptly at departure** (fat-tailed: 3–5x expected loss).
- **Retirement model:** None possible — it leaves with the person; offboarding "write-it-down" residue fails at the moment of need (Robillard).
- **Freshness model:** Self-refreshing while practiced; unmeasurable otherwise; the proxy is concentration risk (truck factor).
- **Discoverability model:** Social ("ask the person") — which does not scale and dies with turnover; succession mechanics are the only measured mitigation (~15–25%).
- **Engineering Platform relationship:** deliberately UNGOVERNED as artifact (PK-P9); the platform's role is measurement (concentration risk) and succession support — never capture mandates. Note: the platform's session logs + reconstruction discipline are a partial *rationale* capture channel at near-zero gesture cost — the direction the literature says capture must go.
- **Evidence:** B (NKM-12; LaToza; Ko; Rigby; Avelino) · A (NKM-1; retention bins).

## 2. Falsified / merged candidates (the register the instruction demanded)

| Candidate | Verdict | Reason (evidence) |
|---|---|---|
| **Architecture Knowledge** | ❌ FALSIFIED as a distinct type | Decomposes cleanly into Decision knowledge (ADRs/principles — immutable, superseded) + Descriptive knowledge (views — regenerate) + Executable (fitness rules). "Architecture" names a *content domain*, not a behavioral type; its artifacts obey the types above (B: r=0.41 for architecture docs sits exactly between the strata of its constituents). |
| **Business Rule Knowledge** | ⚠ MERGED | An encoded business rule is Executable knowledge (test/gate/policy-check); an un-encoded one is Definitional (a term/constraint in the language) or Descriptive prose — with correspondingly lower default authority (PK-P4). The rule's *decision* (why it exists) is Decision knowledge. No separate behavior found. |
| **Organizational Knowledge** | ❌ FALSIFIED as artifact-governable type | Walsh & Ungson's bins (culture, routines, structures) are predominantly enacted/tacit; the artifact-governable residue lands in the seven types. It is environment, not artifact class (A: repository metaphor contested; memory is enacted). |
| **Historical Knowledge** | ❌ FALSIFIED as a type — **it is a STATUS** | Any type's instance becomes historical via supersession/archival; "historical" is a position in the lifecycle (canonical/historical/deprecated), never a kind. Treating it as a type would re-break status-at-discovery (PK-P2). |
| **Runtime Knowledge** (observations, telemetry, logs) | ⏸ INSUFFICIENT EVIDENCE | The literature corpus barely covered operational telemetry as *knowledge*. Its provenance-bearing, instrument-produced character suggests kinship with Qualification/Evidence knowledge, but classifying it now would violate the no-unsupported-concepts constraint. → open question. |
| **Requirements** | ⚠ MERGED (per evidence available) | Behaves as Decision knowledge (what was agreed, immutable once accepted) + Definitional (terms) + Executable (when expressed as acceptance tests — with the executable-specification failure history noted: 12% versioned, business indifference). No distinct behavior evidenced. |

## 3. The classification matrix (one page)

| Type | Validation | Lifecycle regime | Retirement | Authority | Freshness | Discovery | Platform status |
|---|---|---|---|---|---|---|---|
| Executable | execution | change-with-code | dies with referent | the passing run | self-enforcing | live-source search | ✅ implemented |
| Decision | enforcement-binding + review | immutable | supersession-by-link | accepted∧unsuperseded∧owned∧bound | canonicity stales, not content | surface-on-touch | ✅ implemented |
| Definitional | conversational | continuous evolution | with its context | the living conversation + code | conversation cadence | code + people | ◐ partial |
| Descriptive | review-with-code / regeneration | evolve/regenerate | staged ROT | owner + freshness + review | stamped, half-life-priced | scent | ◐ implemented (guides) |
| Experiential | claim evaluation at intake | append-only | superseded, retained | none until promoted | born-stale-gated | indexed by future task | ✅ implemented |
| Qualification | the instrument run | immutable record | superseded as baseline | instrument + config | timestamped by construction | attached to referent | ✅ implemented |
| Tacit | use + peers | practice-bound | leaves with the person | expertise recognition | practice cadence | social / succession | ✅ deliberately ungoverned |

**Reading of the matrix (INTERPRETATION):** no two rows share a full profile — the family thesis holds. The constitutional core (the nine invariants) applies to all rows; the *governance machinery* must differ per row. The Engineering Platform already implements or partially implements six of seven rows **for engineering knowledge** — strengthening, without deciding, the flagged question of whether it is one specialization of a general knowledge domain.

## 4. Open questions raised by the taxonomy

1. Runtime/telemetry knowledge: kinship with Qualification knowledge needs first-party evidence (EPIC-002's Evidence discovery will produce exactly that — convergence of the two research threads noted). — OPEN
2. Definitional validation when engineers are AI: does conversational validation have an agent-compatible analogue? — OPEN (inherited from the Synthesis)
3. Boundary artifacts (IDDs: part decision, part description) — which regime governs which section? — OPEN (inherited)

---

**Success criteria check:** every distinct evidenced type identified (7) · per-type lifecycle/authority/validation/retirement defined (§1, §3) · the one-domain-or-family question answered: **a family with a constitutional core** (§0, §3) · the semantic foundation for the Reference Architecture exists (this document + the Synthesis). Candidates were genuinely falsified (4 falsified/merged, 1 insufficient-evidence — the taxonomy earned its shape rather than inheriting the candidate list). Constraints honored: no folders, no templates, no design, no new research. **STOP — awaiting ARB review before any Reference Architecture is written.**
