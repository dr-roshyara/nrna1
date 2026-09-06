# 01 — Dependency Graph (mandate §3)

**Witness** `exec/t289_bootstrap.py` (+`_v2`) · **transcripts** `exec/OUT-t289_bootstrap.txt`, `..._v2.txt`
**27 nodes · 42 edges.** Every edge is **definitional or operational**. Co-mention is **not** an edge
(mandate §3): edges were admitted only where a cited passage states that B *requires something from* A.

## Edge list

| A → B | what B needs from A | class |
|---|---|---|
| `𝒪 → 𝒯` | which operations are mandatory fixes the transformation family | **CORPUS** 259 |
| `𝒯 → δ` | semantics can only be given for a fixed family | **DERIVED** |
| `𝒪 → 𝒪_K` | permitted observations are drawn from the operation space | **CORPUS** 259.8 |
| `𝒪_K → ≈` | `≈` is `∀O ∈ 𝒪_K` | **CORPUS** 261.5·261.21 |
| `≡ → ≈` | `261.1` lists them distinct; `258.8` defines `≡` observationally | **CORPUS** — ⚠️ the `N-1` conflict |
| `≈ → ≡` | same formula ⇒ definitional entanglement | **CORPUS** 261.5·261.21 |
| `≡ → δ` | `δ`'s postconditions are unstatable without an equality | **CORPUS**+**DERIVED** |
| `δ → congruence` | congruence is a property *of* `δ` | **CORPUS** 258.9 |
| `≡ → congruence` | congruence is *stated in terms of* `≡` | **CORPUS** 258.9 |
| `congruence → ≡` | *"if it fails, `≡_K` is too coarse"* — `≡`'s adequacy is judged by congruence | **CORPUS** 258.9 |
| `𝒯 → congruence` | congruence quantifies over all `T ∈ 𝒯` | **CORPUS** 258.9·259 |
| `congruence → minimality` | *"no minimality proof before congruence analysis"* | **CORPUS** 259 |
| `minimality → K` | `K* =` min info preserving mandatory distinctions | **CORPUS** 258.22 |
| `𝒯 → ≡_𝒯` | `≡_𝒯` is indexed by `𝒯` | **CORPUS** 260 |
| `≡_𝒯 → K` | the quotient *is* the state | **CORPUS** 258.12 |
| `identity → ≡` | object equality presupposes identity semantics | **CORPUS** 258.2·261.26 |
| `id_context → identity` | `I_48` transitivity holds only within a context | **CORPUS** 195.16 |
| `Π → ≡` | Decision 3 | **CORPUS** 254·261.8 |
| `𝒪 → Π` | `258.31`: decided by whether a mandatory op observes provenance | **CORPUS** 258.31 |
| `≡ → bindings` · `𝒪 → bindings` · `bindings → ≈` | per-operation relations | **CORPUS** 261.19 |
| `≡ → canonicalization` | a quotient needs an equivalence relation | **DERIVED** |
| `canonicalization → =` | `=` decidable only relative to a canonicalization | **CORPUS** 38.85 + **EMPIRICALLY VERIFIED** |
| `evidence → canonicalization` | canonicalization must **follow** evidence | **CORPUS** 38.85 |
| `≡ → merge` | `Merge` idempotence stated *modulo* `≡` | **CORPUS** 25J.45 |
| `δ → merge` | merge is a transformation | **DERIVED** |
| `K → state_rep` · `state_rep → =` | the representation must carry `K` | **CORPUS** 285 |
| `≡ → invariants` | equality-sensitive invariants are conditional | **CORPUS** 287 |
| `invariants → sufficiency` · `congruence → sufficiency` | sufficiency needs both | **CORPUS** 273·277·258.30 |
| `sufficiency → kernel` · `≡ → kernel` · `identity → kernel` · `𝒪_K → kernel` · `𝒪 → kernel` · `Π → kernel` · `assertion → kernel` · `temporal → kernel` | the six `261.23` conditions + sufficiency | **CORPUS** 261.23 |
| `Qualify → projection` | `π_K` computability blocked by `Qualify` | **CORPUS** 285 |
| `policy → δ` | admissibility gates transitions | **CORPUS** 278 |

## ⚠️ One edge REMOVED by audit

| Edge | Why removed |
|---|---|
| **`K → ≡_𝒯`** | `258.12` states an **IDENTIFICATION** (`K ≝ ℋ/≡_𝒯`), not a two-way dependency. **An equation rendered as two opposed edges is a modelling artifact, and the mandate (§4) forbids calling that a cycle.** `K` does not *determine* `≡_𝒯`; **`𝒯` does** (edge `𝒯 → ≡_𝒯`, retained). |

**Both graphs are retained in the transcripts** — v1 (43 edges, 5 "cycles") and v2 (42 edges, 4 cycles) —
so the audit is auditable.

## §16 Dependency ≠ blocker

| Class | Members |
|---|---|
| **hard blocker** | `𝒪` · `𝒯` · `𝒪_K` · `≡` · `δ` · `Qualify` |
| **soft dependency** | `policy → δ` · `evidence → canonicalization` |
| **derivable prerequisite** | `≈` · `congruence` · `minimality` · `bindings` · `canonicalization` · `merge` · `invariants` · `sufficiency` |
| **governance prerequisite** | `Π` · `id_context` · `assertion` · `temporal` |
| **implementation prerequisite** | `state_rep` · `=` · `projection` |
| **documentation dependency** | the `≅`/`≡` glyph overloading (`06`) |

**Not every dependency is inflated into a blocker: 8 of 27 nodes are derivable prerequisites.**

## STATUS
**ESTABLISHED** 42 audited edges, each cited · **EMPIRICALLY VERIFIED** cycle enumeration executed ·
**DERIVED** the 6-way dependency classification · **TECHNICALLY OPEN** edges into `𝒪` — none found ·
**NORMATIVE** none decided here · **DEFERRED** edge-list ratification
