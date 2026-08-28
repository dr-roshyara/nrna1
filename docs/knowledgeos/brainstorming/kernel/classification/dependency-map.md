# Kernel Corpus — Dependency & Response-Chain Map (Phase B)

**Artifact type:** classification / dependency map
**Status:** RESEARCH · NON-AUTHORITATIVE · decides nothing
**Scope:** `docs/knowledgeos/brainstorming/kernel/` — 141 source documents
**Companion:** `../00_CENSUS.md` (Phase A census, clusters, contradiction register)
**Method:** two objective passes — (1) explicit intra-corpus ID citation, (2) backward-reference
phrasing (*"previous answer/model/book"*, *"earlier position"*, *"your correction"*).

---

## 1 · The headline finding: the corpus is almost entirely unlinked

| Signal | Result |
|---|---|
| Documents citing **another corpus document by ID** | **1 of 141** (0.7%) — `20260825-130514`, and it is the only one |
| Documents carrying **backward-reference phrasing** | **43 of 141** (30%) |
| Documents with **no** backward reference of any kind | **98 of 141** (70%) |

**Consequence.** Dependency in this corpus is **implicit and positional**: a document responds to
*"the previous book"* or *"my earlier model"* without naming it. The referent is recoverable only
from **chronological adjacency**, and that recovery fails wherever:

- a **duplicate re-save** sits between a document and its antecedent (12 such copies — census §2);
- a **session boundary** intervenes (the corpus spans several sessions, e.g. `142630` is itself a
  handover package);
- **timestamps invert the logical order** (see §3).

This is a property of the capture process, not of the reasoning. It is also the single largest
obstacle to Phase D: reading "within clusters" cannot rely on links that were never written.

---

## 2 · Identified response chains

Reconstructed from content, not from links. `→` means *responds to / revises*.

| Chain | Sequence | Note |
|---|---|---|
| **Fagin** | `125708` → `130514` → `130932` → `132329` | The only fully-linked chain in the corpus: extraction → critique → acceptance → source verification |
| **Aggregate size** (T-1) | `110248` → `111730` → `20260825-113243` | Three independent arrivals at one finding |
| **McGinn** | `115235` → `120122` → `120514` | assessment → primitives → Zero-lens |
| **Merricks** | `113718` → `113938` → `114640` | objects/persons → non-redundancy criterion → truthmaker |
| **Chalmers** | `014614` → `020936` | thematic read, then re-processed on *"your correction"* |
| **Åström** | `142848` → `151311` | initial assessment → topological extraction |
| **ESL / PRML** | `140348` → `141924` → `141231` → `153124` → `152955` | the longest chain; "first/second/third book" phrasing |
| **Statistics** | `030300` → `032208` → `032635` → `032927` | inference basis → techniques → causal → Zero/DDD/Wisdom |
| **Evidence** | `033419` → `033614` | evidence-as-domain → Zero/Chinese pre-evidence structure |
| **Knowledge definition** | `160023` → `160637` → `155510` | measurable construct → continuously changing → capacity |
| **Knowledge Space (formal)** | `120319` → `122752` → `124325` → `125708` → `133456` → `155035` | information theory → Floridi → Dretske → Fagin → Gärdenfors → Searle |

**Highest backward-reference density:** `130932` (16 hits), `032219` (14), `132329` (7), `005850` (7).
The first three are all in the Fagin chain — the corpus's only well-linked region.

---

## 3 · Chronology anomalies — where timestamps invert logical order

Recorded because the census uses mtime as the ID, and these are the cases where the ID misleads.

| Documents | Anomaly |
|---|---|
| `20260824-161931` / `162039` | `161931` says *"I would refine the previous model"* and refers to the chakra-mapping that `162039` presents — the **response is timestamped 68s before what it answers** |
| `20260823-225443` / `230117` / `233040` | `233040` is the **research commission** that should open the Chinese-lens thread, but is timestamped **last** of the three |
| `20260824-152415` | Zero Lens **defined** here, in continuous use since `20260823-230117` — definition lags use by ~17h (census T-6) |

**Reading rule for Phase D:** mtime is a **save** time, not an authoring order. Where a chain's logic
and its timestamps disagree, the content wins and the anomaly is recorded here.

---

## 4 · Structural observations

**S-1 · The corpus has two distinct regions.** Documents up to `20260823-1236` are *kernel-internal*
(admission, aggregate, consistency boundary — dense in ADMIT/AGGR/INVAR). Everything from
`20260823-2250` onward is *source-extraction* (one book per document). The join is abrupt and
undocumented; nothing explains the shift in method.

**S-2 · Extraction documents are near-independent.** Each book extraction responds to its
predecessor at most rhetorically (*"more important than the previous book"*) and rarely revises it.
They accumulate rather than converge — which is why the census finds only 38 K3/K4 documents in 141.

**S-3 · The convergence points are the synthesis documents**, not the extractions: `010419`,
`111317`, `112051`, `123630`, `20260825-103542`. These are also, per census T-7, the documents most
at risk of being read as architecture.

**S-4 · Every genuine falsification is late or external.** `114530`, `234750`, `094638`, `130514` —
the corpus attacks itself rarely, and mostly via an imported instrument (tarka attack families,
a Daoist challenge, an outside critique).

---

## 5 · What this map does not do

No merit ranking · no cluster reading (Phase D) · no synthesis (Phase E) · no Zero pass (Phase F).
Chains in §2 are **reconstructions offered for confirmation**, not established provenance — the
corpus did not record its own links, so every chain here is inference from content.
