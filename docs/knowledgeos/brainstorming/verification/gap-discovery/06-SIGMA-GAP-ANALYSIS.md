# 06 — Σ / Epistemic Status Gap Analysis

**Mandate §11.** *"Do not ask the human to choose a vocabulary until you have demonstrated which
distinctions are mathematically/semantically necessary."*

Executed evidence: `exec/exp_sigma.py`, `exec/exp_measurement.py`, `exec/exp_ekp_bridge.py`.

---

## 1. Method — necessity before vocabulary

Instead of arguing about words, this session asked: **what must the system be able to decide?**
A status value is necessary iff dropping it collapses two situations that a required decision must
separate.

Ten situations were modelled from corpus material, and nine decisions the corpus itself requires
(usability in a decision, investigation, conflict process, gap display, out-of-scope, re-validation,
replacement pointer, authority refusal, evidential contradiction).

---

## 2. RESULT 1 — Ten situations, ten distinct decision signatures

```
10 situations -> 10 distinct decision signatures
(no two situations produce the same set of decisions)
```

**SG-1 (`EXECUTED`).** Every one of the ten situations is decision-relevant. There is no
redundancy to remove: **any Σ carrying fewer than ten distinguishable states cannot support the
decisions the corpus already requires.**

This immediately rules out the three-value ladders that appear in the corpus (`Candidate <
Supported < Accepted`, verified running in `reviews/synthesis/analysis/mathematical-tests/
ladder_dc_reference.py`) as a *complete* Σ. They are adequate as **one axis**, not as Σ.

---

## 3. RESULT 2 — Σ is not one axis. It is at least five.

```
underlying fact ->  decisions that read it
  asked        -> out-of-scope, gap
  evidence     -> contradicted, usable, investigate, gap, conflict-process
  authority    -> usable, refused
  supersession -> usable, replacement-pointer
  validity     -> usable, re-validation

worst-case cross-product:
  asked(2) x evidence(4) x authority(3) x supersession(2) x validity(2) = 96 states
```

**SG-2 (`EXECUTED`, CRITICAL).** A one-dimensional Σ would need **96 values**. The distinctions the
corpus requires are **five orthogonal facts**, not one enum.

The corpus's vocabulary debate then resolves itself without a human decision:

| Corpus status word | Axis it actually reads |
|---|---|
| `Unknown`, `Supported`, `Refuted`, `Conflicted` | **evidence** |
| `Accepted`, `Rejected` | **authority** |
| `Superseded` | **supersession** |
| `Invalidated`, `Stale` | **validity** |
| `Contested` | a **process** fact — see §5 |
| `Candidate` | ambiguous: evidence-insufficiency *or* pre-authority. See §6. |

**Putting these nine words in one enum conflates four independent axes.** That is the finding; the
vocabulary question is downstream and does **not** require a human decision yet.

This subsumes the corpus's `EpistemicStatus ≠ GovernanceStatus` result (ARC D). That result is
**correct but insufficient**: it separates two axes where five are needed.

---

## 4. RESULT 3 — Irreducibility of each axis

Removal test (`exec/exp_sigma.py` EXP-23). A "harmful collision" is two situations that become
indistinguishable but that a required decision separates.

| Axis dropped | Equivalence classes | Harmful collisions | Witness |
|---|---:|---:|---|
| *(keep all)* | 9 | 1 | `one supporting item` vs `meets the policy bar` |
| `asked` | 8 | 2 | + `never asked` vs `asked, nothing found` |
| `evidence` | 6 | 1 | five situations collapse into one |
| `authority` | 7 | 1 | `accepted` vs `rejected` vs neither |
| `supersession` | 8 | 2 | + `accepted` vs `replaced by a newer` |
| `validity` | 8 | 2 | + `accepted` vs `expired` |

**SG-3 (`EXECUTED`).** All five axes are irreducible: dropping any one produces a harmful collision.
This is the necessity demonstration the mandate demands, produced without choosing a vocabulary.

**SG-4 (`EXECUTED`, HIGH) — and there is a sixth requirement the five axes do not cover.**

Note the collision that survives **even with all five axes**: `one supporting item` and
`meets the policy bar` are indistinguishable under an evidence axis reduced to *polarity*
(supporting? refuting?). They differ only in **whether the evidence is sufficient relative to a
declared bar** — three items versus one, against a two-item requirement.

Therefore the evidence axis is not `{none, support, refute, both}`. It must carry **sufficiency
relative to a policy**, i.e. an `Insufficient` value distinct from both `Unknown` and `Supported`.

**Independent convergence worth recording.** The pre-existing script
`reviews/synthesis/analysis/mathematical-tests/zero_reference.py` — which this session ran, output in
§7 — reports exactly this from the opposite direction:

> *"'Insufficient' is expressible ONLY because Γ is carried; the ratified four-arm summary
> (unknown/conflicting/missing/invalid) has no arm for it, nor for Stale or Prohibited — the PF-1
> loss."*

Two independent methods (that script's semantics audit; this session's decision-signature necessity
test) reach the same conclusion: **the ratified status set drops `Insufficient`, and it is
necessary.** Per finding `EV-0`, that script belongs to the Claude review thread, so this is
convergence between two Claude sessions, not between the corpus and a reviewer — but the methods
genuinely differ, and this session's construction was built before its output was read.

---

## 5. `Contested` is a process, not a status

`Contested` in the corpus means *someone has opened a challenge*. That is a fact about an
**open process**, not about the assertion's evidence, authority, supersession or validity. It has a
lifecycle of its own (opened, adjudicated, withdrawn) and a duration.

**SG-5 (`DERIVED`, MEDIUM).** `Contested` should not be a Σ value. It is either a sixth axis
(process state) or — more cleanly — an `ℛ`-edge to a contestation object. The corpus's own
`ladder_dc_reference.py` output records the consequence of forcing it into the enum:

> *"source Ω_A 'Accepted AND Contest:Active' has NO single-state representation in the layered
> model — the PF-6 residue, demonstrated."*

That is the same defect: a conjunction of two axes has no single-enum representation. **PF-6 is not
a residue to be tolerated; it is evidence that the enum is the wrong shape.**

---

## 6. `Candidate` is ambiguous across two axes

`Candidate` is used for both *"not enough evidence yet"* (evidence axis) and *"not yet accepted by an
authority"* (authority axis). Under the five-axis decomposition these are different states with
different consequences: the first is fixed by gathering evidence, the second by an authority act, and
`EXP-23` shows they must not collapse (`drop 'authority'` produces a harmful collision merging
`one supporting item`, `meets the policy bar`, `support + accepted`, `support + rejected`).

**SG-6 (`EXECUTED`, MEDIUM).** `Candidate` is a one-word conflation of two axes. Any ladder built on
it inherits the conflation.

---

## 7. Σ against reality: the running system has no epistemic axis at all

`exec/exp_ekp_bridge.py` EXP-12 and EXP-14, against `docs/knowledge/`:

```
axis 1 'status'    (lifecycle) : frozen 1, baseline 1, approved 24, draft 12
axis 2 'authority' (source trust): authoritative 15, provisional 12, derived 10, generated 1
observed (status, authority) pairs: 7        -> the axes DO vary independently

knowledge-lint rule identifiers (16):
  boundary_consistency, circular_dependency, code_refs_exist, enum_values_valid,
  frontmatter_parses, frontmatter_present, knowledge_id_pattern, knowledge_id_unique,
  links_resolve, orphan_document, recommended_fields_present,
  relationship_targets_exist, required_fields_present, review_overdue,
  single_authoritative, traceability_complete

  epistemic support / evidence check:  NO
  status transition legality check:    NO
```

**SG-7 (`EMPIRICALLY OBSERVED`, CRITICAL).** The running system has **two** of the five axes:

- `status` ≈ a **lifecycle** axis (not one of my five; it is a governance-process axis)
- `authority` ≈ a **source-trust** axis (also not one of my five)

and **zero** of the evidence, supersession-as-state, validity, or asked axes as *enforced* concepts.
No field records whether a claim is supported by evidence; no lint rule mentions evidence.

Two consequences, in opposite directions:

1. **Against the theory:** Σ has never been tested by reality. It has no instance.
2. **Against the implementation:** the EKP governs *documents* by lifecycle and trust, and has no
   mechanism at all for *epistemic* status. A `status: approved, authority: authoritative` document
   that is factually refuted has no way to say so.

**SG-8 (`EXECUTED`, HIGH).** The EKP's own two axes are also not clean. `statuses.yaml` gives a
single integer `order` 1..8 that encodes **two different things**: progression (`draft`(1) →
`frozen`(6)) and retirement (`superseded`(7), `archived`(8)). Executed consequence:

```
order-rule allows approved(4)   -> superseded(7) ? True
order-rule allows draft(1)      -> frozen(6)     ? True     <-- skips four states
order-rule allows superseded(7) -> approved(4)   ? False    <-- blocks un-supersession
```

The schema declares a total order and the lifecycle needs a **covering relation**. The lint passes
because it validates *membership* in the vocabulary and never validates a *transition*.

---

## 8. Answering the mandate's classification question directly

| Status | Epistemic | Governance | Lifecycle | Procedural | Relational | Derived |
|---|:-:|:-:|:-:|:-:|:-:|:-:|
| `Unknown` | ✔ | | | | | |
| `Supported` | ✔ | | | | | ✔ (from evidence + Γ) |
| `Insufficient` **(missing)** | ✔ | | | | | ✔ |
| `Refuted` | ✔ | | | | | ✔ |
| `Conflicted` | ✔ | | | | ✔ (needs ≥2 assertions) | ✔ |
| `Accepted` | | ✔ | | | | |
| `Rejected` | | ✔ | | | | |
| `Contested` | | | | ✔ | ✔ | |
| `Superseded` | | | ✔ | | ✔ (needs the successor) | |
| `Invalidated` / `Stale` | | | ✔ | | | ✔ (from time) |
| `Candidate` | ✔? | ✔? | | | | — **conflated** |

Three of these are **relational**, not properties of an assertion at all: `Conflicted`, `Contested`
and `Superseded` are all facts about *pairs*. Under the terminal model that makes them `ℛ`-edges —
and `ℛ`-edges carry no status of their own (`05-ASSERTION-SEMANTICS.md` CS-3), so the theory cannot
say *when* a supersession was recorded or *who* authorized it.

---

## 9. Findings

| ID | Finding | Class | Severity |
|---|---|---|---|
| **SG-1** | Ten corpus-derived situations produce ten distinct decision signatures; a Σ with fewer than ten distinguishable states is inadequate. | `EXECUTED` | HIGH |
| **SG-2** | Σ is **at least five orthogonal axes** (asked / evidence / authority / supersession / validity); a single enum would need 96 values. `EpistemicStatus ≠ GovernanceStatus` is correct but separates 2 where 5 are needed. | `EXECUTED` | **CRITICAL** |
| **SG-3** | All five axes are irreducible: dropping any one produces a harmful collision. | `EXECUTED` | HIGH |
| **SG-4** | A sixth requirement survives all five axes: **evidential sufficiency relative to a policy bar** (`Insufficient`). Converges with `zero_reference.py`'s independent PF-1 finding. | `EXECUTED` | HIGH |
| **SG-5** | `Contested` is a process/relational fact, not a status. Forcing it into the enum produces the PF-6 inexpressibility already demonstrated by executed code. | `DERIVED` | MEDIUM |
| **SG-6** | `Candidate` conflates evidence-insufficiency with pre-authority. | `EXECUTED` | MEDIUM |
| **SG-7** | The running system has **no epistemic axis whatsoever** — no field, no lint rule. Σ has zero instances in reality. | `EMPIRICALLY OBSERVED` | **CRITICAL** |
| **SG-8** | The EKP's `order` integer conflates progression and retirement; the order-rule permits `draft → frozen` and forbids un-supersession; no transition legality is checked anywhere. | `EXECUTED` | HIGH |
| **SG-9** | Three status words (`Conflicted`, `Contested`, `Superseded`) are relational; the terminal model puts relations in `ℛ`, and `ℛ`-edges have no status, provenance or time. | `DERIVED` | HIGH |

**No human decision is requested in this document.** Per the mandate, the vocabulary question is
deferred until the axis decomposition is accepted or refuted; it is downstream of SG-2.

---

**Next:** `07-MEASUREMENT-THEORY-GAP.md`.
