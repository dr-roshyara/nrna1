# Repository-wide definition search: TD-001, freeze, external authority, "authority" (2026-10-18)

**Authorization:** the Principal's rule §6 "Repository-wide definition search" (2026-10-18). Everything below is **supplementary repository evidence (outside the S-corpus)** unless it is marked [corpus]. **Nothing here changes TD-001's status or closes a TODO by itself.**

## 0. Search scope and exclusions

**Tool:** `rg`, case-insensitive, over the repository working tree. Gitignored files were not searched (rg's default), so `.env*` and the runtime workflow JSON were not searched.

**Excluded by access rule:**
- `docs/knowledgeos/chronological-read/**`, which includes `_batch_input*`;
- any path matching `*holdout*`, `*seal*` or `*quarantine*`.

**Excluded to keep evidence independent** (parallel or derivative theory work, not definitions):
- `docs/knowledgeos/knowlegeos_manual_theory_20261018/**` (this book's own working folder);
- `docs/knowledgeos/chronological_knowelgeos_ablation_theory/**` (the F-series). Its "Freeze v2" hits were **listed but not opened**: 7 files under `analysis/theory/` and in `F-GOVERNANCE-LOG.md`;
- `docs/knowledgeos/knowledgeos-s-series-theory/**`;
- `docs/knowledgeos/brainstorming/**` (excluded for the authority, current-validity and external-obligation patterns; included only in the first "Freeze v2" pass);
- `*.jsonl`, and for the last pattern also `*.json`, `*.php`, `*.vue` and `*.js`.

| Pattern searched | Hits | Opened and verified |
|---|---|---|
| `freeze v ?2 · freeze-v2 · freeze_v2 · discovery freeze · freeze v1` | 23 files | dev guide 01; the Deferred Architecture Register (= S0029) [corpus]; `.claude/sessions/2026-08-04.md`; ARB evidence package 01 v3; the plan 20260804-1200; dev guide `engineering_observations/04` |
| `currently (valid\|authoritative\|in force)\|current validity` | 22+ files (truncated at 25) | **none opened in depth.** The hits are product-side PublicDigit documents (election timing, membership), theory-extraction 47, and Round38. ⚠ This search is **incomplete**. See §5 |
| `R-34 · R5b · G-2 … authorit` | 25+ files | the rulings register, `.claude/MEMORY.md`, `workflow-state.php:326`, the 2026-08-14 verification and ARB review |
| `revok · revocation` | 25+ files | product-side, mostly (voter slugs, membership). **No governance-grant revocation rule found** |
| `(grant\|authority) … (expire\|lapse\|valid until\|valid from\|effective date)` | 25+ files | not opened beyond the file list; product-side architecture rounds (Authority Discovery Round 6F1, 11, 12). ⚠ **Not verified** |
| law / regulation as authority (two-way proximity pattern) | 25 lines | EPIC-002 (literature research), ADR-UL-01, ADR-T20 |
| `as (the) records stood · as of date · bitemporal · valid time · knowledge time` | Membership product evidence only | the product-side `effectiveAt` / valid-time tests (an analogue only) |
| ES-001 · STANDARDS_INDEX · rulings register R-27/34/37/38/41/77/80/86 · Phase-02 §7 | — | read at their own location |

## 1. "Freeze v2": FOUND (outside the corpus)

**F-1 · Primary record: `.claude/sessions/2026-08-04.md` L525–527.**
- File added in commit `93555043a` (2026-08-04 00:11), last changed 2026-08-04 22:54. Author: session-log writer (AI), recording on the human's instruction.
- Text:
  > "## Discovery Freeze v2 adopted; execution phase declared. 1. **ARB directive recorded:** every new architectural concept must originate from an engineering event (event → observation → candidate → evidence → promotion). Composes with Freeze v1.0: **v1.0 gates WHETHER discovery reopens (explanatory failure); v2 names the only legal SOURCE.**"
- Also L545: "Constitutional set declared stable: Freeze v1 + Freeze v2 + the standing question."
- **Freeze v1.0** (L216–218): "Architecture Discovery Freeze v1.0 adopted … nothing architectural gets discovered unless months of engineering produce evidence the existing model CANNOT EXPLAIN."

**F-2 · Developer restatement: `developer_guide/knowledgeos/01_architecture_and_design_patterns.md` L144–147** (commit `d21fde502`, 2026-08-04).
- "Freeze v1: discovery reopens only when the existing model cannot explain engineering evidence. Freeze v2: new architecture originates only from engineering events (event → observation → candidate → evidence → promotion)."
- The guide declares itself "**a developer guide, not a governance document**". It names its canonical source as "`.claude/CONTEXT.md` (freezes v1/v2 …)".

**F-3 · Corpus use:** S0029 L12 ("the Freeze v2 path") and S0040 L54. Both are **consistent** with F-1: a firing criterion is an engineering event.

**Status assessment:**
- **Wording:** found, consistent across three files.
- **Authority:**
  - recorded as an "**ARB directive**", but **only in a session log**;
  - **no entry in the rulings register** (`engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md`, R-1…R-100+, last changed 2026-08-04): no "Discovery Freeze" row;
  - **R-34** (see §2) says ARB decisions are "created only on explicit ARB adoption". It does not require a register row, so the session log's "directive recorded" can count as the record of an adoption.
  - Governance status: **"approved within a stated scope" per the session log; register status: not minted. Authority: unclear, not absent.**
  - The earlier corpus statement (S0017 L55, freezes "under no named authority") **does not hold for Freeze v1/v2**, which name the ARB. It may still hold for other freezes.

**⚠ Naming collision (new finding):** "**Architecture Freeze 2.0**" is **R-37** (2026-07-10, structural: no new top-level folders or domain objects; lifting conditions C3 + PB-004 + retrospective). It is a **different freeze** from "**Discovery Freeze v2**" (2026-08-04). The register also holds R-27 (governance freeze) and R-38 (conceptual freeze), and R-80 mentions an "ENGINEERING METHODOLOGY FREEZE (2026-08-01)". **"Freeze v2" is therefore ambiguous across the repository.** S0029's "Freeze v2 path" resolves to the Discovery Freeze by its content.

**Who may freeze or amend a frozen artifact:**
- **Source:** `engineering/architecture/baseline/Phase-02-Domain-Model.md`. Its status is "FROZEN — Baseline v1.0 (ADR-AIP-01, 2026-07-08)", ARB-accepted. Its own `authority` field reads "Generated", an example of status ⟂ authority.
- §7 L304: "Amend a Frozen artifact / re-version the process — AI drafts ADR — **ARB-only**".
- L239: domain facts "`ArtifactFrozen` / `FreezeAmended` — producer: **ARB via ADR**".
- L144: "humans emit the only authoritative facts in the domain: … `ArtifactFrozen` …".
- **Applicability:** a definition for **frozen artifacts** (the artifact sense). It **does not cover** freeze-of-activity decisions (Discovery Freeze v1/v2, "implementation remains frozen"). For those, S0045 L39 ("FREEZE … ungoverned decision type, unruled") stands **as of 08-03** in the corpus.

## 2. Authority origin and records (bears on C1, T1-18, D)

| # | Source (exact location) | Status | Text | Bearing |
|---|---|---|---|---|
| R-1 | **ES-001.2**, `engineering/governance/ES-001-Engineering-Constitution.md` L26 | rule attributed to "(ARB 2026-07-11)"; **the hosting document and the Standards Index both say "PROPOSED — awaiting ARB review"** | "**Documents Record Governance; They Do Not Create It.** Only explicit ARB decisions create governance … Authors propose; the authority adopts (AIP-10 corollary: no artifact may assert an unoccurred adoption)." | Constitutional-level support for **C1-b** (records record; acts create). **Status uncertainty:** the rule is ARB-dated, but its home is a proposed standard. The repository treats it as binding (CLAUDE.md cites ES-001..006 as canonical). Report as "**approved per its own attribution; home not ratified**" |
| R-2 | **R-34**, rulings register L20 (2026-07-08) | issued ruling (register) | "ARB decisions (rulings here / ADRs — **created only on explicit ARB adoption, never by inference from praise or suggestion**)" | An act of adoption is required, and inference never suffices. Supports r1.1 (1) and (3). It says nothing about recording |
| R-3 | `.claude/scripts/workflow-state.php:326` (code) | implemented, verified 2026-08-14 (`docs/publicdigit/reviews/2026-08-14-KOS-AI-ORCH-001-increment-1-session1-verification.md` L46) | refusal: "**a grant registers a recorded human act by reference — the record never manufactures authority (G-2/R5b)**" | **Source of the S0067 quote**, verified at its own location |
| R-4 | **R-77**, register (2026-08-03) | issued | "**supersession requires an EXPLICIT ACT, NEVER INFERENCE**"; also "ADR-T14 REMAINS NORMATIVE ON PAPER, which is not the same as proving it governs implementation" | Supports r1.1 (3). It also separates **normative standing** from **governing practice**: a new distinction r1.1 does not draw |
| R-5 | **R-86**, register (2026-08-04) | issued by the "DECISION AUTHORITY" | "R-81 through R-85 [prepared, already entered in the register] … **These become governing rulings effective immediately**" | **A register entry before adoption did not govern**; the adoption act did, with **effect from the act**. Strong support for C1-b, and for "effective at the time of the act" (T1-05) |
| R-6 | **R-41**, register (2026-07-30) | issued (Principal Architect instruction) | "**An artifact has one authoritative lifecycle state at any moment** … session logs preserve the SEQUENCE of past states … **history is never rewritten** to manufacture consistency" | Uniqueness of current state + history preserved. Supports r1.1's structure. Bears on T1-04 (subject = artifact here) |
| R-7 | `.claude/MEMORY.md` L448–452 | **no issuing authority**: MEMORY itself says "CLAUDE.md and MEMORY.md have NO issuing authority" | lifecycle states "Proposed → Approved/Binding → {Clarified · **Frozen** · Superseded · Deprecated → Retired}"; "Supersession is forward-linked only" | A **pointer only**. Not used as authority |
| R-8 | `docs/implementation/PKS_ES005_Adoption_Decision.md` L91 | an adoption-decision record (status not further verified) | "adoption changes **BINDING STATUS** rather than implementation. **A rule may be binding and unenforced by machine**" | Validity (binding) ≠ enforcement. Supports keeping r1.1 free of enforcement conditions |

## 3. "Authority" terminology (D)

**T-1 · R-80, register (2026-08-03, ARB directive; scope annotation the same day by the issuing authority).** Status: **issued**.
- **The canonical execution-governance vocabulary:** "**PERMISSION** — may something be done? · **AUTHORIZATION** — is execution sanctioned? · **COMMISSIONING** — has someone been instructed to perform it? · **EXECUTION** — is the work actually being performed?"
- **Admission rule for new terms:** "a new term requires **DEMONSTRATED** ambiguity, not ANTICIPATED ambiguity."
- **Scope:** "for THIS programme and THIS governance model … claims no universality."
- **Bearing on TD-001:**
  - r1.1 says "a permission for that kind of act within that scope". **R-80 separates permission from authorization.**
  - r1.1's sense (a) "authority" conflates the two. Under R-80 a grant registers an **authorization**, while "who may approve" is a **permission** question.
  - **A recommendation is supported in evidence:** if the book adopts terms for sense (a), R-80's four terms are the **existing canonical vocabulary** (ES-005.4 consume-before-create).
  - **Applicability is uncertain.** R-80 is scoped to PublicDigit programme execution governance, not to a general theory. **The naming decision stays with the Principal (T1-01).**

**T-2 · The hierarchy (sense c):** `.claude/MEMORY.md` L456, pointing to `.claude/IMPLEMENTATION_PROTOCOL.md` A-2: "Business Authority → Architecture Decisions → Governance (ES-00x) → Implementation Protocol → Implementation → Operational Evidence". A pointer only; A-2 itself was not opened. Compare S0066 L415 [corpus].

**T-3 · Document authority (sense b):** `Phase-02-Domain-Model.md` L67, ubiquitous language: "authority (authoritative/derived/generated/historical/provisional), status (idea→…→frozen→superseded→archived)". This is sense (b) as a UL term of BC-1 in a frozen, ARB-accepted baseline.

**Assessment:**
- The repository has **canonical vocabulary for sense (a)** (R-80) and **for sense (b)** (BC-1 UL, `authorities.yaml`).
- **No artifact found reconciles the senses** under one term or gives each a distinct name.
- The corpus's own D2 (S0067: "two authority models", never merged) is the nearest to a reconciliation: it **forbids merging** them.

## 4. External obligations (C)

| Source | Status | Finding |
|---|---|---|
| `docs/implementation/EPIC-002_Literature_Review.md` L132–133 | research, **product domain** (election contestation); literature-graded "FACT, 3-0" | German administrative law "formally **decouples** finality-against-challenge (*Bestandskraft*) from immutability … an unlawful act can become non-appealable while remaining substantively unlawful and **revisable by the issuing authority itself, retrospectively**" |
| `docs/implementation/EPIC-002_Domain_Decomposition_Evaluation.md` L35; `EPIC-002_Cross_Disciplinary_Evidence_Consolidation.md` L27 | research questions | "automatic **lapse of legal authority**" / "self-executing legislative sunset/expiry" as a possibly different kind of event from adjudication |
| ADR-T20 (`docs/adr/ADR-T-LOG-Tactical-Implementation.md` L24); PB-003 certification L103 | ARB domain decision | "**legal finality** (binding determination exists) ≠ **operational completion**". A product-side analogue of validity ≠ effect |

**Assessment:**
- **No KnowledgeOS or engineering-governance artifact** found makes an external obligation (law, regulation) a source or limit of authority.
- The product side has **researched** legal authority (finality, lapse, retrospective revision) as **literature for the election domain**. That is relevant **analogical** evidence for T1-15, and r1.1 omits all three phenomena.
- **Not applicable as a definition** to the engineering-governance model.
- Proposed extensions remain S0022 F-3 [corpus] and this analogy.

## 5. Negative results and unsearched areas

- **"As the records stood on that date" has no governance-side definition.** Only the product-side valid-time (`effectiveAt`) machinery was found (membership), which is an analogue. Note the contrast with S0067/S0071 [corpus]: the authority store has **no wall-clock time and no history**.
- **No revocation rule for governance grants found.** OQ-6 [corpus] stands. S0102 #11 assigns revocation for **assurance levels** only.
- **Not searched or not opened:**
  - files whose name matches the freeze-v2 pattern in the excluded F-series and S-series folders;
  - the product-side "Authority Discovery" rounds (`docs/architecture/contexts/Round6F1_AuthorityDiscovery.md`, `AuthorityDiscoveryFindings.md`, `EvidenceContextAuthorityDiscovery.md`), which hit the expiry/valid-from pattern and probably model **election-domain** authority with time limits. **This is a gap worth one bounded follow-up read** (see the final report);
  - `.claude/IMPLEMENTATION_PROTOCOL.md` A-2;
  - the 22 "currently valid/authoritative" hits;
  - gitignored files (runtime workflow JSON);
  - `docs/knowledgeos/theory-extraction/**` was not opened, though permitted, because it is not needed for a definition.
- **Not found ≠ proven absent.**

## 6. Bounded follow-up: product-side "Authority Discovery" (done the same day)

These files belong to the **PublicDigit election domain**, not to KnowledgeOS governance. Their status is "Intermediate findings; no conclusions yet" or "design exploration", dated 2026-06-03/04. **Use: analogue only, never a definition for TD-001.**

| Source | Text | Bearing on r1.1 |
|---|---|---|
| `docs/architecture/contexts/AuthorityDiscoveryFindings.md` L57–67, Candidate Pattern C | "Authority appears to have **defined temporal boundaries** (start, duration, end, potential revocation)". Examples: observers' authority "expires after certification"; governance "lasts until superseded" | Analogue support for r1.1 (2) and (3): authority has periods and endings. **The KnowledgeOS grant record has neither** (S0071, OQ-6) |
| same file, L157–166, **Conflict 4** | "Fraud Authority (temporal) revokes decision by Observer Authority (**now expired**) … **Decisions may outlast the Authority that created them**" | **The exact TD-001 question, posed independently on 2026-06-03.** It supports the "at the time of the act" reading: a decision survives the expiry of its author's authority |
| `Round6F2_AuthorityStressTest.md` L362–392, Stress Test 8 | "Revocation **stops future Authority** from that source. But **prior Authority** (the claim made before revocation) **remains as historical fact**. Prior decisions: often persist (if certified) · can be overturned (by higher Authority)". H-B: "Different authority types could have **different revocation effects**" | **Analogue support for r1.1 (1) together with (3)'s "no ending … carries over under the organisation's rules"**: whether an ending carries over is type-dependent. It is not uniform, which is why r1.1 delegates it to the organisation's rules |

**Assessment:**
- A **second, independent line** in the same repository (product domain, two months earlier) arrives at the same structure as r1.1's (1) and (3).
- Its status is exploratory and its domain is different. It **raises confidence in r1.1's structure as an analogue**. It does **not** validate r1.1 for the governance model.
