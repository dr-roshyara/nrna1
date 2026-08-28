# Corpus Extraction Ledger

**Programme:** Session 1 · full-corpus research extraction (objective revised 2026-08-25)
**Method:** one document at a time · provenance before content · extract, classify, record, continue
**Status:** RESEARCH · NON-AUTHORITATIVE · extraction phase · **no synthesis, no adjudication**
**Corpus in scope:** **256 documents** — P1 root 98 · P2 kernel 158
**OUT OF SCOPE (HPA, 2026-08-25):** `_misc/` — 7 documents, **not extracted**.
⟦I⟧ Consequence recorded: this excludes `_misc/20260819-224159-linux-analogy-kernel-os-model` (33 KB,
declared `brainstorm` / `theme: 02-kernel-platform`) — the **earliest kernel-platform document in the
corpus**, and one referenced by nothing in Phase 2. Also excluded: `20260819-221159-digitalization-robot-vision`,
`225329-ip-protection`, `225858`/`225332-kos-ipo` (the latter self-declared `duplicate`),
`20260821-2353-deepseek-investigation-eks-pks-aip`, and the corpus's only image
(`20260821-150830-diagram.png`). If the Kernel question later turns on the OS/kernel analogy, this
exclusion is where that evidence sits.

**Registers maintained:** document log · concept register · definition matrix · Knowledge Space
matrix · KnowledgeOS matrix · Kernel candidate matrix · temporal matrix · claim/hypothesis register ·
contradiction register · vocabulary collisions

**Provenance codes:** `P1` ORIGINAL_PROJECT · `P2` EXTERNAL_RESEARCH · `P3` BOOK_EXTRACTION ·
`P4` MODEL_INTERPRETATION · `P5` RESPONSE_TO_EXISTING_MODEL · `P6` CRITIQUE ·
`P7` ARCHITECTURE_PROPOSAL · `P8` FORMAL_ARCHITECTURE · `P9` UNKNOWN

---

## M-1 · METHODOLOGICAL FINDING — the corpus carries its own provenance metadata

⟦RESEARCH FACT⟧ **26 documents carry YAML front-matter** declaring provenance and classification:

```yaml
source:
  original_name: · original_path: · detected_timestamp:
  timestamp_source: filesystem-mtime · timestamp_confidence: medium
classification:
  theme: · type:
status:
  authoritative: false
```

**Coverage:** P1 root **21 of 98** · `_misc` **5 of 6** · P2 kernel **0 of 157**.
⟦I⟧ A convention that existed early and was **abandoned before Phase 2**. It is the only
machine-readable provenance in the corpus.

**Consequences for this extraction:**

1. ⟦RESEARCH FACT⟧ **All 26 declare `authoritative: false`.** The corpus explicitly declares its own
   non-authority. This is corpus-side confirmation of the standing discipline, not an external rule.
2. ⟦RESEARCH FACT⟧ `timestamp_source: filesystem-mtime`, `timestamp_confidence: medium` — **the
   corpus itself rates its own timestamps as medium confidence.** This independently corroborates the
   three chronology inversions recorded in `../kernel/classification/dependency-map.md` §3.
3. **Five documents are self-declared excludable** (type `noise` / `duplicate`, theme
   `off-topic-publicdigit`) — exclusion rests on the corpus's own metadata, not my judgment:
   `20260819-104802` · `20260819-104804` · `20260819-104830` · `20260819-205541` ·
   `_misc/20260819-225332`.
4. ⟦I⟧ Declared `type` values reveal a vocabulary the later corpus lost: `analysis` ·
   `architecture-proposal` · `brainstorm` · `noise` · `duplicate` · `Decision` · `enum` ·
   `PRIOR_ABSENCE` · `POSTERIOR_ABSENCE`.

### Declared classification, per document (P1 + `_misc`)

| type | theme | document |
|---|---|---|
| analysis | evidence-assurance-governance | `20260816-204714` · `20260819-092449` · `20260819-205757` |
| analysis | patterns-technology | `20260818-213516` · `20260820-123902` |
| analysis | operating-model-product | `20260821-120810` |
| architecture-proposal | domain-context | `20260819-204018` · `20260819-204431` |
| architecture-proposal | **kernel-platform** | `20260821-121929` |
| architecture-proposal | evidence-assurance-governance | `20260819-220806` · `20260821-120633` |
| architecture-proposal | event-integration | `20260820-231930` · `20260821-142748` |
| architecture-proposal | patterns-technology | `20260819-220924` |
| architecture-proposal | operating-model-product | `20260817-145153` |
| brainstorm | **kernel-platform** | `_misc/20260819-221159` · `_misc/20260819-224159` |
| brainstorm | operating-model-product | `20260819-104823` · `_misc/20260819-225329` · `_misc/20260819-225858` · `20260820-115444` |
| duplicate | — | `20260819-205541` · `_misc/20260819-225332` |
| noise | off-topic-publicdigit | `20260819-104802` · `20260819-104804` · `20260819-104830` |

---

## M-2 · METHOD CHANGE — stop criterion narrowed (HPA, 2026-08-25)

**Superseded:** stopping when a document introduces *"a potentially Kernel-relevant finding"*.
⟦I⟧ On a corpus containing many Kernel candidates that criterion produces excessive stops.

**In force now:** *Stop for human attention when the finding **changes the research model** or creates
a **significant unresolved contradiction** — not merely because another candidate appears.*

A new Kernel candidate is **recorded and extraction continues**. The Kernel candidate matrix absorbs
it; no report interrupt.

---

## Document log

| # | Document | Prov | Phase | Type | Extracted |
|---|---|---|---|---|---|
| 1 | `20260801-1231-eks-current-architecture-baseline` | **P1** | 1 | baseline reconstruction | ✅ |
| 2 | `20260816-204714-track2-eks-semantic-discovery` | **P1** | 1 | analysis (declared) | ✅ |
| 3 | `20260817-145153-ai-engineering-platform-6-role-model` | P7 | 1 | architecture-proposal (declared) | ✅ scanned — no Knowledge content |
| 4 | `20260818-213516-lcom4-multi-language-binding` | P1 | 1 | analysis (declared) | ✅ scanned — no Knowledge content |
| 5 | `20260819-092449-research-on-role-separation` | **P2**+P1 | 1 | analysis (declared) | ✅ |
| 6 | `20260819-205757-ddd-correction-verification-verdict` | **P6**+P2 | 1 | analysis (declared) | ✅ **major** |
| 7 | `20260819-104748-ai-engineering-lifecycle-five-responsibilities` | P7 | 1 | — | ✅ scanned — empty |
| 8 | `20260819-104823-four-session-role-model-refinement` | P1 | 1 | brainstorm (declared) | ✅ scanned — `HANDOFF ≠ ACTIVE` only |
| 9 | `20260819-204018-kos-product-architecture-v2` | P7 | 1 | architecture-proposal | ✅ scanned — empty |
| 10 | `20260819-204431-kos-ddd-architecture-review-v3` | P7 | 1 | architecture-proposal | ✅ scanned — empty |
| 11 | `20260819-220806-kos-3-0-state-durability-ddd-boundary` | P7 | 1 | architecture-proposal | ✅ *"the boundary protects invariants"*; R-CONFLICT as domain invariant |
| 12 | `20260819-220924-spring-di-principle-for-kos` | P7 | 1 | architecture-proposal | ✅ `DI ≠ Authority Resolution`; infrastructure ≠ domain invariant |
| 13 | `20260820-115444-poa-vs-ddd-decision-hierarchy` | P1 | 1 | brainstorm (declared) | ✅ *"History matters ≠ everything must be event sourced"* |
| 14 | `20260820-123902-kos-design-patterns` | P1 | 1 | analysis (declared) | ✅ patterns as local solutions, DDD-bounded |
| — | 5 self-declared `noise`/`duplicate` | — | 1 | excluded by corpus metadata | ⊘ |

**Coverage: 24 of 256 in-scope extracted · 5 excluded by declaration · 232 remaining.**

⟦I⟧ Session-1 finding artifacts now live at `docs/knowledgeos/reviews/kernel/session1/` (S1-F001…F003); this ledger remains the extraction record.

---

## DOC-5 · `20260819-092449-research-on-role-separation`

**PROVENANCE** `P2` EXTERNAL_RESEARCH (NIST AI RMF, nvlpubs.nist.gov) applied in support of a project
separation · declared `analysis` · `authoritative: false`

**DISTINCTIONS** ⟦C⟧ `Operating role ≠ capability ≠ bounded context ≠ agent ≠ service` — five-way,
externally supported.
**CLAIMS** ⟦C⟧ A platform can prove only *"the recorded producer identity differs from the recorded
verifier identity"* — plus permission, environment, ordering and immutability facts.
⟦I⟧ **Sharp epistemic limit: recorded-identity difference is not actual independence.** Bears on
`W:C-3` (who checks authority adequacy). ⟦C⟧ *"role declarations alone are insufficient."*
**QUESTIONS** ⟦C⟧ open register incl. *"Which knowledge is mandatory versus advisory?"* ·
*"What happens when required knowledge is unavailable?"* · *"Who decides applicability?"*
⟦I⟧ The last connects to DOC-2's applicability `UNKNOWN` (KCON-008).
**Change:** extends existing producer/verifier separation — no model change.

---

## DOC-6 · `20260819-205757-ddd-correction-verification-verdict` — **MAJOR**

**PROVENANCE** `P6` CRITIQUE of a project proposal, with `P2` external support · declared
`type: analysis`, `authoritative: false`, `proposed: false` · front-matter `original_name: "# Verification verdict"`

**F-1 · T-1 ORIGIN MOVES BACK FOUR DAYS.** ⟦C⟧ *"A `KnowledgeProduct` aggregate is valid only if it
owns invariants that must be maintained atomically. In DDD, an aggregate is not simply a large object
graph or a database document."* → ⟦C⟧ *"**This may be too large.**"* with the failure list:
transaction contention · excessive coupling · difficult versioning · large event payloads · awkward
concurrent review · unclear ownership of local invariants.
⟦I⟧ Same criterion and same verdict as `110248` (2026-08-23), **four days earlier**. **T-1 is four
independent arrivals, not three, and it originates in Phase 1 project reasoning — not as a Phase 2
rediscovery.** `../kernel/classification/contradiction-map.md` T-1 amended accordingly.

**F-2 · TWO NEW CONCEPTS.** ⟦C⟧ `KnowledgeProduct` / `KnowledgeElement`, with an ownership split:
*"A Knowledge Product owns product identity, scope, version, lifecycle, and membership. **Knowledge
Elements own their own content and local invariants.** Governance determines wh…"* Candidate
decomposition: `Decision · Method · Binding · Rule · EvidenceRecord · GovernanceCase`.
⟦I⟧ This is a **third genus for Knowledge** — not *understanding*, not *relationship*, but **a product
with members**. C-K1 becomes three-way.

**F-3 · EARLY KNOWLEDGEOS DEFINITION.** ⟦C⟧ *"KnowledgeOS is a governed knowledge-product platform
whose core domain is the creation, evolution, and authorized delivery of trusted organizational
knowledge."* ⟦L⟧ v1.1 §4: core domain = **Knowledge Identity**. ⟦I⟧ Both lifecycle framings; differ on
the object and on whether **delivery** is core. → **PARTIALLY CONSISTENT**, not adjudicated.

**REJECTIONS** ⟦C⟧ *"should not yet be frozen exactly as written"*; Evidence *"probably a separate
bounded context, but not necessarily a separate core domain"*; Knowledge Semantic Context =
supporting/projection context unless semantic interpretation is itself a primary business capability.
**DDD CANDIDATES** KnowledgeProduct → CANDIDATE AGGREGATE (explicitly challenged as too large) ·
KnowledgeElement → CANDIDATE AGGREGATE / ENTITY · Evidence → CANDIDATE BOUNDED CONTEXT.

---

## DOC-11…14 · brief

⟦C⟧ `20260819-220806`: *"The boundary protects invariants"*; R-CONFLICT proposed as a **domain
invariant**. ⟦C⟧ `20260819-220924`: `DI ≠ Authority Resolution`; *"AI providers, vector/graph
databases, policy engines and connectors are **replaceable infrastructure**, not domain invariants."*
⟦C⟧ `20260820-115444`: *"**History matters ≠ everything must be event sourced**"*; warns against
extensions that *"mutate the kernel"*. ⟦C⟧ `20260820-123902`: patterns are *"local solutions to
demonstrated domain or architectural problems"*, selection follows DDD boundaries.
⟦I⟧ All four **confirm or incrementally extend**; no model change.

---

## DOC-2 · `20260816-204714-track2-eks-semantic-discovery`

**PROVENANCE** `P1` ORIGINAL_PROJECT · declared `type: analysis`, `theme: 03-evidence-assurance-governance`, `authoritative: false` · records a **ruling session** ("Then you ruled SC-1") — so it is project decision-making, not import.

**DEFINITIONS** ⟦C⟧ *"A **Rule** is a standing, authoritative obligation governing behaviour within a defined scope and period. A deviation is non-conformance unless an authorized exception permits it."*

**DISTINCTIONS** ⟦C⟧ `Rule ≠ Decision ≠ Recommendation ≠ Permission` — a **four-way** non-collapse.
⟦C⟧ **`UNKNOWN ≠ DOES NOT APPLY`** and **`UNKNOWN ≠ EMPTY SCOPE`** — *"captured as a candidate invariant."*

**CLAIMS** ⟦C⟧ *"**Unknown applicability must never be treated as non-applicability.**"*
⟦C⟧ Rule comprises: obligation · scope · validity · binding strength · authority · evidence · exceptions · **supersession**.
⟦C⟧ Four applicability dimensions ruled (SC-1): System location · Environment · Artifact kind · Lifecycle phase; **Audience explicitly excluded**.

**KERNEL IMPLICATIONS** ⟦I⟧ This is the **earliest appearance of an UNKNOWN non-collapse in the corpus** — 2026-08-16, six days before v1.1. ⟦FORMAL ARCHITECTURE⟧ v1.1 §9 carries *unknown ≠ absent ≠ false*; this document carries *unknown ≠ does-not-apply ≠ empty-scope* — **a different pair of distinctions on the same term**, and neither is among v1.1's eleven non-collapse rows. Recorded as **VC-1** below, not as a gap.

**TEMPORAL** ⟦C⟧ `validity` and `supersession` named as Rule components — the earliest supersession reference found so far.

**DDD CANDIDATES** Rule → CANDIDATE VALUE OBJECT or POLICY · scope/validity → CANDIDATE VALUE OBJECT · applicability dimensions → CANDIDATE INVARIANT set. **No decision.**

**KNOWLEDGE / KNOWLEDGE SPACE / KERNEL** ⟦C⟧ none of the three terms appears. This document is about **Rule**, not Knowledge.

---

## DOC-3, DOC-4 — scanned, no Knowledge-relevant content

`20260817-145153` (P7, 6-role model, 940 lines) and `20260818-213516` (P1, LCOM4 multi-language
binding, 596 lines) contain **no definitional, Knowledge, Knowledge Space, Kernel or epistemic-state
material**. ⟦I⟧ Recorded as scanned-and-empty rather than omitted, so coverage stays honest.

---

## SCOPE — narrowed twice on 2026-08-25, second narrowing AMBIGUOUS

1. `_misc/` **excluded** (7 docs) — recorded above.
2. *"and make research of only kernel"* — **two readings, materially different work:**
   - **(a) folder scope** — extract only `brainstorming/kernel/` (158 docs), dropping the 98 Phase 1
     root documents.
   - **(b) concept scope** — keep both locations, extract only **Kernel**-relevant content.

⟦I⟧ **Reading (a) would discard the origin of the corpus's most durable finding.** KCON-012
(atomicity-not-relatedness) originates at `20260819-205757` — **Phase 1 root** — and the three
earliest Knowledge definitions (KCON-002/010/001) are all Phase 1 root. Dropping Phase 1 would
remove the provenance layer this programme exists to protect.

**Acted on the intersection of both readings** — Kernel-focused extraction, `kernel/` prioritised —
and **paused Phase 1 root extraction rather than discarding it** (14 of 98 done). Awaiting
confirmation of (a) or (b).

---

## Kernel candidate matrix (§10) — opened

| Candidate | Source | Why proposed | Counter-evidence | Alternative location | Status |
|---|---|---|---|---|---|
| **Authority mechanics** | `20260821-2032` | ⟦C⟧ *"The kernel should provide the **authority mechanics**"* — but must not decide *"which architecture is correct"* | — | domain governance keeps the decisions | **RESEARCH_CANDIDATE** |
| **Evidence mechanics** (generic) | `20260821-2032` | ⟦C⟧ evidence chain *"potentially the **core** of the KnowledgeOS kernel"* | ⟦C⟧ same doc: kernel must **not** own `ArchitectureAssessment`/`ProductAssessment`/`SecurityAssessment` | domain contexts own typed assessments | **RESEARCH_CANDIDATE** |
| **Lifecycle / provenance** | `20260821-2032` | part of the proposed deterministic runtime | — | — | **RESEARCH_CANDIDATE** |
| **KnowledgeProduct** | `20260819-205757` | proposed core aggregate | ⟦C⟧ challenged **in the same document** as *"too large"* | decompose to KnowledgeElements | **CHALLENGED** |
| **KnowledgeElement** | `20260819-205757` | owns own content + local invariants | — | — | **RESEARCH_CANDIDATE** |
| `UNKNOWN`-applicability distinction | `20260816-204714` | ⟦C⟧ *"candidate invariant"* | ⟦I⟧ VC-1 — different altitude from v1.1's epistemic `UNKNOWN` | applicability layer, not epistemic state | **OPEN** |
| R-CONFLICT as domain invariant | `20260819-220806` | proposed | — | — | **OBSERVED** |
| **ChangeSet** | `20260821-2108` | ⟦C⟧ separates event detection from observation execution; source-agnostic; *"genuinely looks like a reusable kernel/platform primitive"* | — | — | **RESEARCH_CANDIDATE** (⟦I⟧ *implemented* — higher evidence class) |
| **Rule Port** | `20260821-2108` | kernel holds the protocol; EKS/PKS supply rules | — | rules live outside | **RESEARCH_CANDIDATE** — ⟦L⟧ CONSISTENT with Verification Port |
| **Observation · Assessment · Result · Rule-evaluation protocol** | `20260821-2108` | B's member list | ⟦I⟧ absent from A's list 36 min earlier | — | **OPEN** (see S1-F003) |
| ⚠ **Authority mechanics — contested** | A `20260821-2032` vs B `20260821-2108` | A: *"exactly the kind of invariant a kernel can protect"* | **absent from B entirely** | — | **CHALLENGED** |

⟦I⟧ **Zero-lens note:** the intersection of the two earliest Phase 1 Kernel member-lists is
`{Evidence, Provenance}` only. See `../../reviews/kernel/session1/S1-F003-kernel-member-candidates-diverge.md`.

*No row may reach `FORMALLY_ACCEPTED` here — only the formal architecture can produce that status.*

---

## DOC-15 · `20260821-2032-how-to-change-eks-into-knowledgeos-kernel` — **MAJOR (Kernel)**

**PROVENANCE** `P1` ORIGINAL_PROJECT (no front-matter; reasons from the DOC-1 baseline) · Phase 1 ·
**2026-08-21 — the day before v1.1**

**DEFINITION — a Phase 1 Kernel definition.** ⟦C⟧ the kernel is *"closer to: **a deterministic runtime
for governed knowledge state, authority, evidence, lifecycle and provenance**."*

**REJECTIONS** ⟦C⟧ *"The kernel is therefore not primarily:"* `database · API · plugin system · AI
runtime · knowledge graph`. ⟦C⟧ And: *"The kernel should not simply be 'the current EKS code moved
into a kernel package.'"* ⟦C⟧ It *"should not own"* `ArchitectureAssessment · ProductAssessment ·
SecurityAssessment`.
⟦I⟧ **This anticipates v1.1 §17's rejected set** (*Database · Ontology repository · LLM wrapper ·
Truth machine*) by one day, from Phase 1, independently. Classification: **CONSISTENT** — and
evidence that the negative boundary is original project reasoning, not a later import.

**DISTINCTIONS** ⟦C⟧ `Artifact existence ≠ Authority` · `Record existence ≠ Authority establishment`
— *"exactly the kind of invariant a kernel can protect."*
⟦L⟧ v1.1 §15 carries *Evidence ≠ Authority*. ⟦I⟧ Related but **not identical**: this pair is about
*existence* vs *establishment*, not evidence vs authority. Recorded as **PS-2 · POSSIBLE SYNONYM**,
unmerged.

**CLAIMS** ⟦C⟧ evidence chain `Observation → Evidence → Assessment → Verdict → Decision`, *"potentially
the core of the KnowledgeOS kernel"*. ⟦C⟧ The kernel *"should probably **grow out of** the existing EKS
governance/evidence mechanisms, rather than be invented independently."*
⟦I⟧ Note the chain differs from DOC-1's: DOC-1 had `Observation → Recommendation → Decision → Outcome →
Assessment`; this has `Observation → Evidence → Assessment → Verdict → Decision`. **Same endpoints,
different middle, different order.** Recorded as **C-K2** below.

**KERNEL IMPLICATIONS** authority mechanics · generic evidence mechanics · lifecycle · provenance —
all entered in the matrix above as RESEARCH_CANDIDATE.

---

## DOC-16…19 · brief (Phase 1, 08-20 → 08-21)

⟦C⟧ `20260821-120633` **G-4**: *"⛔ **No authority manufacture** — the checker emits `CONFLICT
DETECTED`, never `INDEPENDENCE = TRUE`; never a decision."* ⟦I⟧ Converges with DOC-5's KCON-013
(recorded difference ≠ actual independence) from a different direction — **two independent arrivals**.
⟦C⟧ `20260821-121929` (declared `theme: 02-kernel-platform`): *"translation ≠ simplification."*
⟦C⟧ `20260820-231930`, `20260821-142748`: event-driven positioning; no Kernel or Knowledge definitions.

---

## Concept register (accumulating)

| ID | Concept | First appearance | Prov | Status | Supporting | Challenged by |
|---|---|---|---|---|---|---|
| KCON-001 | Knowledge as relationship | `20260822-0135` | P1 | strong research model | `0135`,`0225`,`0249`,`0302`,`1057` | KCON-002 |
| KCON-002 | Knowledge as durable understanding | **`20260801-1231`** (oldest) | P1 | definition | DOC-1 only | KCON-001 |
| KCON-003 | Knowledge derived from decisions & outcomes | `20260801-1231` | P1 | claim | DOC-1 | — |
| KCON-004 | Observation ≠ Knowledge | `20260801-1231` | P1 | definition | DOC-1 | — |
| KCON-005 | Evidence as first-class | `20260801-1231` | P1 | definition | DOC-1 | — |
| **KCON-006** | **Rule as standing authoritative obligation** | `20260816-204714` | P1 | definition (ruled) | DOC-2 | — |
| **KCON-007** | **Rule ≠ Decision ≠ Recommendation ≠ Permission** | `20260816-204714` | P1 | ruled distinction | DOC-2 | — |
| **KCON-008** | **UNKNOWN ≠ DOES-NOT-APPLY ≠ EMPTY-SCOPE** | `20260816-204714` | P1 | **candidate invariant (corpus's own words)** | DOC-2 | — |
| **KCON-009** | **Supersession as a Rule component** | `20260816-204714` | P1 | claim | DOC-2 | — |
| **KCON-010** | **KnowledgeProduct** (identity·scope·version·lifecycle·membership) | `20260819-205757` | P6 | **candidate aggregate — challenged in the same document** | DOC-6 | DOC-6 itself |
| **KCON-011** | **KnowledgeElement** (owns own content + local invariants) | `20260819-205757` | P6 | candidate aggregate/entity | DOC-6 | — |
| **KCON-012** | Aggregate validity = atomically-maintained invariants, **not relatedness** | **`20260819-205757`** | P6 | **strong — 4 independent arrivals** | DOC-6, `110248`, `111730`, `20260825-113243` | — |
| **KCON-013** | Recorded producer≠verifier identity **is not** actual independence | `20260819-092449` | P2 | claim (external) | DOC-5 | — |
| **KCON-014** | KnowledgeOS as governed knowledge-**product** platform | `20260819-205757` | P6 | definition | DOC-6 | ⟦L⟧ v1.1 §4 (different object) |
| **KCON-015** | **Kernel as deterministic runtime for governed knowledge state, authority, evidence, lifecycle, provenance** | `20260821-2032` | P1 | **definition (Phase 1)** | DOC-15 | — |
| **KCON-016** | Kernel is **not** database/API/plugin system/AI runtime/knowledge graph | `20260821-2032` | P1 | **rejection set** | DOC-15 | ⟦L⟧ §17 **agrees** |
| **KCON-017** | `Artifact existence ≠ Authority`; `Record existence ≠ Authority establishment` | `20260821-2032` | P1 | candidate invariant | DOC-15 | — |
| **KCON-018** | **No authority manufacture** — a checker may emit `CONFLICT DETECTED`, never `INDEPENDENCE = TRUE` | `20260821-120633` | P1 | claim | DOC-16, DOC-5 | — |

---

## Vocabulary collisions

**PS-1 · POSSIBLE SYNONYM — `KnowledgeElement` vs *minimum preservation unit*.**
`KnowledgeElement` (`20260819-205757`, P1 phase, P6 provenance) vs *"identity-bearing, contextualized
assertion record"* (`kernel/20260825-113243`). Similar role — the thing that owns its own content and
local invariants — six days and one phase apart, different vocabulary. **NOT merged**, per §5.

**PS-2 · POSSIBLE SYNONYM — `Record existence ≠ Authority establishment` (DOC-15) vs ⟦L⟧ `Evidence ≠
Authority` (v1.1 §15).** Related, not identical: existence-vs-establishment is not evidence-vs-authority.
**NOT merged.**

**VC-1 · `UNKNOWN`** — DOC-2 (P1, 2026-08-16) uses it for **applicability**: *unknown ≠ does-not-apply
≠ empty-scope*. ⟦FORMAL ARCHITECTURE⟧ v1.1 §9 uses it for **epistemic state**: *unknown ≠ absent ≠
false*. ⟦I⟧ Same token, two distinction sets, two altitudes. **Do not merge.** Status: recorded.

*(Carried forward from earlier classification work: `projection` — three senses; `agent` — epistemic
participant vs accountable actor; `C-1` — `W:C-1` vs ⟨C-1⟩.)*

---

## Contradiction register

**C-K1 · Knowledge as *understanding* vs Knowledge as *relationship*** — both `P1`
ORIGINAL_PROJECT, three weeks apart (`20260801-1231` vs `20260822-0135` et al.).
**Now three-way**, all `P1` phase:
| Position | Genus | Source | Date |
|---|---|---|---|
| A | **durable understanding** | `20260801-1231` | 08-01 |
| B | **a product with members** | `20260819-205757` | 08-19 |
| C | **the relationship** | `20260822-0135` + 4 | 08-22 |

Classification: **DIFFERENT LEVEL (candidate)** — *understanding* is participant-internal and
non-inspectable; *relationship* is structural and inspectable; *product* is an artefact with
membership and lifecycle. ⟦I⟧ The three may be different altitudes rather than rivals, but no
document proposes that. **STATUS: UNRESOLVED**, per §13 not resolved during extraction.

---

### C-K2 · Two different knowledge-production chains, both Phase 1

| | Chain | Source | Date |
|---|---|---|---|
| A | `Observation → Recommendation → Decision → Outcome → Assessment → Knowledge` | `20260801-1231` | 08-01 |
| B | `Observation → Evidence → Assessment → Verdict → Decision` | `20260821-2032` | 08-21 |

⟦I⟧ Same endpoints, **different middle terms and different order** — B puts Decision *last* and adds
`Verdict`; A puts Decision *third* and adds `Recommendation`/`Outcome`. Both `P1` ORIGINAL_PROJECT.
Classification: **DIFFERENT CONTEXT (candidate)** — A describes an engineering-feedback loop, B an
evidence-adjudication loop. **STATUS: UNRESOLVED.**

---

### C-K3 · Is a knowledge kernel a *deterministic* runtime?

| | Position A | Position B |
|---|---|---|
| Claim | kernel = *"a **deterministic runtime** for governed knowledge state, authority, evidence, lifecycle and provenance"* | *"Knowledge systems **lack deterministic execution**"* — the Unix analogy is weak there |
| Source | `20260821-2032` (KCON-015) | `20260821-2351` §20 |
| Provenance | **P1 ORIGINAL_PROJECT** | **P2 EXTERNAL_RESEARCH** |
| Time | 20:32 | 23:51 — **same evening** |

⟦I⟧ The project's own commissioned evidence base contradicts the project's own definition, 3h19m later.
Possible reconciliation: *deterministic governance mechanics* ≠ *deterministic execution* — **not
adopted**, neither document draws it. Bears on unruled `W:C-2`.
**STATUS: UNRESOLVED.** Detail: `../../reviews/kernel/session1/S1-F004-no-established-kernel-category-determinism-challenged.md`

### KCON additions

| ID | Concept | First appearance | Prov | Status |
|---|---|---|---|---|
| **KCON-019** | **No established `knowledge kernel` research category** — the term is *"metaphor, prototype vocabulary, or isolated conceptual work"* | `20260821-2351` | P2 | **research fact** |
| **KCON-020** | Trustworthy core = **preserving epistemic accountability over time**, not retrieval | `20260821-2351` | P2 | claim (external) — 3rd arrival at *over time* |
| **KCON-021** | Knowledge graph as Kernel: evidence **Weak**; `Knowledge System = Knowledge Graph` unsupported | `20260821-2351` | P2 | research fact |
| **KCON-023** | **Kernel protects the rules; mathematics describes the behavior** — kernel is *not* a mathematical engine | `20260822-0021` | P1 | **claim — anticipates the Phase 2 verdict by ~4 days** |
| **KCON-024** | Kernel as **formal semantic operating system**; primitives `Identity·Evidence·Authority·State·Transition·Provenance·Time·Invariant` | `20260822-0021` | P1 | **third member list** |
| **KCON-025** | Method: discover invariants **before** choosing a mathematical model | `20260822-0021` | P1 | method rule |
| **KCON-022** | Unix analogy licenses *minimal trusted core* + *mechanism/policy separation*; denies *deterministic execution*, *process isolation*, *universal semantics* | `20260821-2351` | P2 | research fact |

---

## Model state

**CURRENT RESEARCH MODEL CANDIDATE:** K-M1 — *provisional; rests essentially on DOC-1 alone, whereas KCON-001 (relationship) rests on five independent Phase-1 documents* (see `KNOWLEDGE-RECONSTRUCTION-LEDGER.md`).
**MODEL CHANGE CANDIDATES** (recorded, not applied — §17):
- *Observation ≠ Evidence* may require a SPLIT (from DOC-1)
- `UNKNOWN` may be **two concepts, not one** — applicability vs epistemic state (from DOC-2, VC-1)
- *understanding vs relationship* may be different levels rather than rivals (C-K1)

No new model version created. Per §17, versions follow accumulated evidence, not single documents.
