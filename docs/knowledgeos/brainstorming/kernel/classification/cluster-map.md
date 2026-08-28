# Kernel Corpus — Conceptual Cluster Map (Phase B)

**Artifact type:** classification / conceptual cluster map
**Status:** RESEARCH · NON-AUTHORITATIVE · **decides nothing, resolves nothing**
**Scope:** `docs/knowledgeos/brainstorming/kernel/` — 141 source documents
**Companions:** `../00_CENSUS.md` (Phase A) · `dependency-map.md` (Phase B, chains)
**Standing instruction honoured:** *"Do not resolve those temptations. Map them."*
Phase D deep reading has **not** started, and no cluster has been chosen for it.

---

## 0 · Namespace warning — read before using the last column

**Three different `C-n` numbering spaces exist in this programme.** Conflating them would corrupt
every row of the matrix below.

| Notation used here | Namespace | Example |
|---|---|---|
| `⟨C-1⟩ … ⟨C-5⟩` | **v1.1 r3 annotations** (architecture) | ⟨C-1⟩ canonical-form equality is not an identity determination · ⟨C-5⟩ inability to determine → `UNKNOWN` |
| `W:C-1 … W:C-19`, `W:F-CM-1a/1b`, `W:Wisdom`, `W:CC-1` | **adjudication workbook items** (22 open questions) | `W:C-1` capability-list re-typing · `W:C-8` identity-continuity test |
| `⟨Z-1⟩`, `⟨R-1⟩`, `⟨A-1..3⟩`, `⟨r4⟩` | other v1.1 annotations | ⟨Z-1⟩ states are states *of an identified object* |

**`W:C-1` (capability-list re-typing) and `⟨C-1⟩` (similarity ≠ identity) are unrelated.** Where the
matrix means the identity prohibition it writes `⟨C-1⟩`; the identity *test* is `W:C-8`.

Workbook item meanings used below: `W:C-2` determinism · `W:C-3` who checks authority adequacy ·
`W:C-4` targeting an existing `KnowledgeId` · `W:C-5` supersession coordination locus ·
`W:C-6` stale outward reference · `W:C-7` `CONFLICTED`↔`ConflictRecord` cardinality ·
`W:C-8` identity-continuity test · `W:C-10` evidence-reference resolvability ·
`W:C-11` Confidence derivability · `W:C-14` `NOT_ASSESSED` representation ·
`W:C-15` retraction/withdrawal representation · `W:C-16` admission idempotency ·
`W:C-17` internally impossible claim · `W:C-18` constitutional-version binding.

---

## 1 · The corpus is not one process — provenance by generation mode

**S-1 confirmed and quantified.** The corpus has two regions with an abrupt, undocumented join at
`20260823-1240`:

```
13 documents          128 documents
kernel-internal   ──►  book-by-book extraction
(original work)        (research inputs)
```

| Generation mode | Docs | Epistemic weight |
|---|---|---|
| **BOOK EXTRACTION** | 50 | research **input**; an author's claim, not a project position |
| **UNCLASSIFIED** | 37 | ⚠ mode not determinable by signal — **Phase D must judge each** |
| **ADVERSARIAL CRITIQUE** | 27 | attacks; the most Kernel-useful mode |
| **DUPLICATE** | 12 | no independent weight |
| **ORIGINAL BRAINSTORMING** | 6 | ⚠ **deliberate project thinking — the scarcest and heaviest class** |
| **RESPONSE TO PRIOR HYPOTHESIS** | 4 | revises a project position |
| **ARCHITECTURE PROPOSAL** | 3 | ⚠ highest leakage risk (census T-7) |
| **EXTERNAL TRANSCRIPTION** | 2 | not analysis at all (`163922`, `130339`) |

> **The single most important number here: 6 + 4 = 10 documents carry deliberate project
> architectural thinking. 50 carry an author's claim about a book.** A book-derived observation must
> not inherit the weight of a project hypothesis. Every synthesis step should keep these apart.

*(Update 2026-08-25 18:17: +8 documents since this table; `181038` adds a 4th ARCHITECTURE proposal and `181719` a 28th ADVERSARIAL critique.)*

Provenance is signal-derived and therefore **provisional** — 37 of 141 could not be classified
mechanically, and BOOK/ADVERSARIAL overlap wherever an extraction is written as an attack.

---

## 2 · Conceptual cluster matrix

Counts are documents with material presence (≥3 keyword hits), so clusters overlap by design —
one document may appear in several. Competing positions are **recorded, not adjudicated**.

| Cluster | Core question | Docs | Competing positions | K | Formal question affected |
|---|---|---:|---|---|---|
| **Identity** | What makes knowledge the same object? | ~14 | **primitive & indefinable** (`115235`, `120122`) vs **transformational/contextual** (`094638`) vs **assigned, never derived** (v1.1) | K3/**K4** | `W:C-8` · ⟨C-1⟩ · `W:C-4` |
| **Knowledge** | What is knowledge? | ~30 | **object** vs **state** vs **projection** vs **capacity** (`155510`) vs *not the Kernel primitive at all* (`120514`, `111545`, `130932`) | K3/**K4** | boundary · `W:Wisdom` |
| **Knowledge Space** | What is the larger epistemic space? | **28** | **unbounded/infinite** (`135642`, `180730`) vs **bounded-by-purpose** (`103542`) vs **formal state space** (`125708`) vs **measurable space (Ω,F)** (`181038`) | K3 | scope (outside the boundary?) |
| **Projection** | What does a projection represent? | **38** | **view only** vs **epistemically meaningful** (`140251`) vs **CQRS read-model** (`024119`) vs **measure over a measurable space** (`181038`, attacked by `181719`) | K3/**K4** | `W:C-15` · `W:C-14` |
| **Time / temporal** | Is knowledge intrinsically knowledge-*at-time*? | **42** | **timestamp** vs **temporal identity** vs **hidden temporal state** (`124529`, `131125`) | K3 | `W:C-15` · `W:C-18` |
| **Epistemic state** | What is an epistemic state? | ~20 | **seven closed states** (v1.1 §9) vs **four-valued T/F/B/N** (`164653`, `165635`) vs **8-way `UNKNOWN`** (`125708`) vs **state-as-primary-object** (`133456`) | **K4** | `W:C-14` · `W:C-17` · `W:C-7` |
| **Admission** | How does something become knowledge? | **35** | **gate** vs **selection** vs **plural admission** (`110525`) | **K4** | `W:C-16` · `W:C-4` · `W:F-CM-1a/1b` |
| **Evidence** | What makes knowledge justified? | ~40 | **evidence-as-domain** (`033419`) vs evidence-as-input vs *evidence ≠ authority* (v1.1) | K3/K4 | `W:C-10` · `W:C-11` |
| **Justification** | What must be preserved? | ~25 | **structural path** vs **semantic content** vs **pramāṇa route** (`164328`) | **K4** | `W:C-11` · ⟨C-5⟩ |
| **Boundary / aggregate** | What must the Kernel own? | ~24 | **minimal** (`113243`, `113410`) vs **broad/god-object** (`110248` attacks it) vs **preservation ≠ consistency ≠ projection** | **K4** | boundary · `W:C-7` |
| **Change / supersession** | How does knowledge evolve? | **40** | **transition** vs **new identity** vs **projection change** vs **non-monotonic** (`125708` §21) | **K4** | `W:C-15` · `W:C-5` · `W:C-6` |
| **Retraction** | Is retraction state, event, or disappearance? | **19** | ⚠ all three positions present, none reconciled | **K4** | `W:C-15` |
| **Decision / authority** | Who may establish or change knowledge? | **59** | **authority as role** vs **as property** vs **institutional/social** (`155035`) | K3/K4 | `W:C-3` · `W:C-18` |
| **History / lifecycle** | Is history part of the object or a record? | **49** | **member** vs **separate record** vs **append-only projection** | K3 | `W:C-15` · ⟨Z-1⟩ |

---

## 3 · The C-15 question set the HPA named — where the corpus actually speaks

**A structural finding: the C-15-relevant material splits across the two regions.**

| Sub-topic | Concentrated in | Top documents |
|---|---|---|
| **Retraction / withdrawal** | ⚠ **early / ORIGINAL region** | `114358` (32) · `233040` (29) · `111647` (24) · `113410` (14) · `123619` (11) |
| **Projection** | ⚠ **late / extraction era** | `110525` (34) · `140251` (28) · `135642` (20) · `155035` (13) |
| **Temporal** | spans both | `115235` (28) · `110525` (26) · `131125` (21) · `103542` (20) · `124529` (20) |

**Consequence for weighting:** the retraction evidence is the *project's own* thinking; the projection
evidence is almost entirely *extraction-era*. They do not carry equal weight, and a synthesis that
merges them flattens exactly the distinction S-1 exposes.

**Vocabulary collision — flagged, unresolved.** `024119` leads the projection count (49) but uses
"projection" in the **CQRS read-model** sense, not the epistemic sense. Two meanings, one word,
inside one cluster. A Vāṇī-class hazard that must be separated before any projection synthesis.

**Questions the corpus does NOT visibly answer** (asked by the HPA, no document found addressing
them directly): *can two projections differ without knowledge differing?* · *does absence from a
projection mean anything?* · *is history part of the object or a record of transitions?*
These are **gaps to be confirmed in Phase D**, not settled absences.

---

## 4 · Temptation map — mapped, not resolved

Per instruction. The value of these is **prospective**: after formal adjudication, which of these
pressures can still falsify the Kernel?

| # | Recurring pressure | Arrivals | Formal question it would push on |
|---|---|---|---|
| **T-1** | Enlarge the aggregate | 3 (`110248` → `111730` → `113243`) | boundary · `W:C-7` |
| **T-2** | Add an eighth epistemic state | 5, three names (`AMBIGUOUS` → catuṣkoṭi `B`/`N` → 8-way `UNKNOWN`) | `W:C-14` · `W:C-17` · `W:F-CM-1a` |
| **T-3** | Make Knowledge the Kernel primitive | 5, both directions | boundary |
| **T-4** | Make identity derived/relational | 2 opposed | `W:C-8` · ⟨C-1⟩ |
| **T-5** | Merge plurality into contradiction | 2 (`103542` §19 does it; `110525` avoids it) | `W:F-CM-1a/1b` · `W:C-7` |
| **T-6** | Use a lens before defining it | Zero Lens, ~17h | method |
| **T-7** | Let research become architecture | 3 (`232146`, `111317`, `131125`) | **all** |

> The corpus is not only a source of ideas. It is a **record of where human and AI reasoning
> repeatedly want to expand the Kernel** — which is what makes it valuable as the future adversarial
> corpus, and dangerous as a synthesis input.

---

## 5 · What Phase B did not do

No cluster read · no merit ranking · no temptation resolved · no first cluster chosen for Phase D ·
nothing promoted · nothing written to KnowledgeOS law. Counts are keyword-presence, so cluster
membership is **material presence, not aboutness** — Phase D corrects that. Provenance for 37 of 141
documents remains undetermined.
