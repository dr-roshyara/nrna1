# IMPLEMENTATION READINESS MATRIX

**Authority:** HPA mandate 2026-08-31, recorded GN-77. Read-only. Subsumes the earlier mandate's
"book-writable-now" matrix — one register, not two (`ES-005.4`: consume or extend, never a second).

## A · Chain readiness (engineering view)

| Link | Readiness | Blocked behind | Provenance |
|---|---|---|---|
| Canonical Objects | **PARTIAL** — named, not typed | — | v0.1 §1 → v0.2 §5 (8 primitives) |
| Canonical State | **PARTIAL** — declarable, not comparable | identity + equality | v0.2 `K_t`, TESTED within scope |
| Canonical Invariants | **PARTIAL** — 2 TESTED, 8 READ, 2 RC | executable predicate form; closed register | v0.2 §4 |
| **Canonical Operations** | 🔴 **NOT ESTABLISHED** | a governance act | zero defined/authorized/ratified (verified) |
| **Canonical Transformations** | 🔴 **NOT ESTABLISHED** | operations (primary); identity/equality; closed invariant register | no pre/post anywhere; legality constraints only |
| Evidence | **PARTIAL** | the Evidence object; the qualification predicate; OQ-3 | I-5/I-6 TESTED (GN-27 CSV caveat) |
| Authority / Governance | **PARTIAL, collision open** | authority→gate binding; admissibility conjunction; **GC-1** | A6 · I-4 · DC 6-tuple · I-11 |
| Persistence | 🔴 **NOT ESTABLISHED** | — | canon silent |
| Replay | 🔴 **NOT ESTABLISHED** | identity/equality; history placement | canon silent |
| Tests | **OPEN** | suite execution; conformance criteria | EG-05 SPECIFIED, unexercised (OQ-5) |

**Two links severed; two links empty; four partial; one open. Nothing is CLOSED/TESTED end to end.**

## B · Book readiness (production view) — GREEN / AMBER / RED

| Part / chapter | Verdict | Condition or blocker |
|---|---|---|
| **Part I · DISCOVERY** I.1, I.2, I.4, I.5, I.6 | 🟢 **GREEN** | historical voice; sources read; no canon manufactured |
| **Part I · I.3 The Lens Programme** | 🔴 **RED** | evidence-gated on the 38-document kernel commission (BA-ED2-09); never filled from inference |
| **Part II · RECONSTRUCTION** (4 ch) | 🟢 **GREEN — but ACCEPTED and frozen** | no edit permitted; GN-70 closed it |
| **Part III · ARCHITECTURE** (10 ch) | 🟢 **GREEN — produced, gate-passed, protected** | cite it; never rewrite it for implementation convenience |
| **Part IV · THE FORMAL PROGRAMME** (proposed, ~5 ch) | 🟠 **AMBER** | admissible only as *programme history*, every construct marked NON-CANONICAL; requires the structural ruling first |
| **Part V · V.1** how to read a requirement | 🟢 **GREEN** | method only, already ratified discipline |
| **Part V · V.2** objects and identity | 🟠 **AMBER** | objects at ratified strength; **identity NOT ESTABLISHED** — must be stated as absent |
| **Part V · V.3** state | 🟢 **GREEN** | ratified `K_t`; state schema stated as missing |
| **Part V · V.4** invariants | 🟢 **GREEN** | I-1…I-12 with grades intact; must disclose that no closed register exists |
| **Part V · V.5** operations | 🔴 **RED** | **IMPLEMENTATION BLOCKER — CANONICAL SEMANTICS NOT ESTABLISHED** |
| **Part V · V.6** transformations | 🔴 **RED** | same, and blocked behind V.5 |
| **Part V · V.7** evidence | 🟠 **AMBER** | I-5/I-6 yes; object, qualification predicate, operator (OQ-3) stated as open |
| **Part V · V.8** governance & authority | 🟠 **AMBER** | ratified boundaries yes; **GC-1 must be visible and unresolved** |
| **Part V · V.9** software & tests | 🟠 **AMBER** | report the real estate honestly; the two unobservability figures (15/24 tests · 16/25 constructs) kept on separate denominators |
| **Part VI · IMPLICATIONS & OPEN** (5 ch) | 🟢 **GREEN**, IV.4/IV.5 extended | IV.4 must now carry GC-1, `𝒪_core` non-ratification, Σ/`Q_t` status, the closure disagreement |

**Book verdict: 8 GREEN · 5 AMBER · 3 RED.** No RED may be written around; each requires a
decision outside the book lane.

## C · What an engineer can build today, stated exactly

> A state over the eight ratified primitives, carrying the twelve invariants as constraints at
> their stated grades, enforcing the policy stratification (I-11) and the authority boundary (A6,
> I-4, DC.Auth as a governance-filled precondition), with evidence composition obeying I-5 and I-6.

> **They cannot build anything that changes that state**, because no legal operation and no legal
> transformation is canonically defined. Any attempt requires inventing semantics.

## D · The single unlocking act

**Ratify a closed operation registry with a membership criterion — or rule explicitly that none
exists and mark the contract BLOCKED there.** Links 4 and 5 depend on it; V.5 and V.6 depend on
those; the per-operation pre/post-conditions, preservation obligations and replay effects all
depend on those. No other single act unblocks as much.

**Not indicated:** another broad gap-discovery pass. The gap is **undecided, not under-analysed** —
the candidate analyses exist in layer 2; what is missing is a governance act.

---
## E · State update — GN-79 (2026-08-31)
The HPA selected **Option 1 — COMMISSION THE DERIVATION**. Commission issued:
`COMMISSION-operation-registry-derivation.md`. **Nothing in sections A–D changes**: the operation
registry is still NOT ESTABLISHED, V.5 and V.6 are still 🔴 RED, and the chain is still severed at
links 4 and 5. A commission is not a registry. The matrix's readiness values change only on the
**separate ratification act** that GN-79 defers, after the independent review required by C-8.
**Executor not yet assigned — see the commission's §10.**

---
## F · State update — GN-84 (2026-08-31)
The derivation returned **verdict D as a recommendation pending falsification**. Readiness values in
sections A–D are **unchanged**: the registry is NOT ESTABLISHED and V.5/V.6 remain 🔴 RED.
**Refinement recorded per HPA direction:** V.5 and V.6 are now writable **as status and dependency**
— what the blocker is, its evidence, its owner, the prerequisite canonical questions, and the
four-statement distinction (exists YES · unique NO · selectable NO · ratified NO) — but **not** as a
canonical operation/transition contract. Binding terminology for those chapters:
*"minimal under the chosen computational criterion"* (shown non-unique) may never be written as
*"minimal canonical operation set of KnowledgeOS"* (not demonstrated).
