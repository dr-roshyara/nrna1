# Registration — Implementation architecture ACCEPTED subject to OQ-1…OQ-4 · and the artifact consequences (gate 1)

**Registered by:** Governance, on the delivered PO/ARB act · 2026-08-18
**Subject:** `2026-08-18-KOS-CONTRACT-NEUTRALITY-001-implementation-architecture.md` (`d2f2e5a4`)

---

## 1 · Disposition · **ACCEPTED, CONDITIONALLY**

> **"ACCEPT the implementation architecture subject to OQ-1…OQ-4. This acceptance does NOT authorize implementation."**

The proposal is accepted **as the proposed realization of the already-selected Architecture D** — not as a licence to build. The four open questions it raised itself are now dispositioned, **unevenly**, and Governance records which is which rather than treating them as four decisions.

## 2 · The four OQ dispositions — decided, constrained, or still open

| | Status | What the act actually did |
|---|---|---|
| **OQ-1** anonymous-class identity | 🟨 **CONSTRAINED + ROUTED** | Two binding constraints decided: identity must be **deterministic within the declared analysis scope**, and **must not depend on an unstable runtime label such as `"(anonymous)"`**. The **selection** is routed to Architecture, which must document the chosen rule and its determinism requirements **before implementation**. *The proposal offered two candidates — a declaration path (deterministic, coupled to source order) and a declaration-site coordinate (stable under reordering, unstable under formatting); the constraints eliminate `(anonymous)` but not either candidate.* |
| **OQ-2** analysis scope | ⬜ **NOT DECIDED** | Three options are listed — single PHP file · repository/module scope · another explicitly bounded scope — and **none is selected**. The act *does* decide the **criteria**: the scope must determine **namespace/import visibility · identity · reproducibility · evidence boundaries**. ⚠️ *The proposal notes the pinned "analyzed in isolation" limitation implies the file — but records that **implication is not a ruling**.* **Governance asks: is this scope the PO/ARB's to rule, or Architecture's to propose under the criteria?** |
| **OQ-3** trait expectation consequence | ✅ **ACKNOWLEDGED + AUTHORIZED** | The consequence is acknowledged: **`trait = analysed unit` changes existing expected evidence.** A **separate governed artifact-update step is AUTHORIZED** for expected node sets · expected edge sets · expected LCOM4 values where affected · affected fixtures. ⛔ **"Do not let Implementation silently change expectations."** *No assignment is created by this act — see §4.* |
| **OQ-4** Python→PHP runtime dependency | ⬜ **NOT DECIDED** | The act frames two branches and takes neither: **if accepted**, Architecture/Implementation must define runtime contract · version compatibility · failure behaviour · reproducibility · CI/runtime requirements · local developer requirements; **if not accepted**, the consequence returns to Architecture for a different realization. *The proposal states it plainly: a PHP process would become a runtime dependency of the Python implementation — legitimate under 13.1 since it sits below the neutrality boundary, and a real CI/packaging cost.* |

**Two of four remain the PO/ARB's to rule.** Architecture cannot complete the final design without OQ-2 (identity uniqueness has no defined extent) and OQ-4 (the extraction transport is unchosen).

---

## 3 · Gate 1 performed — the semantic/expectation artifact consequences, recorded

The act's first next-gate is *"Governance records the semantic/expectation artifact consequences."* **Recorded here; none applied.** Each entry is a decided semantic meeting a pinned expectation:

| Decided rule | Artifact consequence | Evidence |
|---|---|---|
| **`trait = analysed unit`** (13.5) | **`trait-user.php` gains a second observation** — a pinned expectation changes | proposal `OQ-3`; *"the first place a decided semantic forces a change to a pinned expectation"* |
| **`enum = analysed unit`** (13.5) | Enums are **currently missed entirely** by the PHP side (`Stmt\Class_` misses `Enum_` and `Trait_`; found only via `ClassLike`) ⇒ **new units appear** | proposal §measured |
| **nullsafe `?->` = edge** (13.5) | **Currently invisible** — `$this?->b()` yields `MethodCall` count **0** ⇒ **new edges appear**; and per the ruling this must be covered by **declared expected evidence**, since differential comparison cannot detect a shared blindness | proposal §measured; 13.5 |
| **anonymous class = analysed unit with stable identity** (13.5) | `(anonymous)` **collides within one file** (probe `N5`: two rows, one designation) ⇒ **identity scheme changes**, and expectations keyed on the old label break | proposal §4.5 |
| **qualifier rules** (13.3): fully-qualified INCLUDE · relative INCLUDE · qualified EXCLUDE-*not the own class* · aliased EXCLUDE-*limitation* | **Edge sets change on BOTH sides** — the PHP reference resolves `\Fq` but refuses `\App\Fq`, so it is not uniformly compliant either | 13.3; Decision 1 (PHP not authoritative) |
| **node + edge + metric** (13.7) | **`expected.json`'s current shape — ten fixture→integer rows — becomes ONE OF THREE assertion layers, not the whole expectation** | proposal §316; 13.7 |

**⚠️ The scale, stated plainly:** this is not a fixture refresh. **Every layer of the expectation set is affected** — units, edges, identities and the file's very shape — and **both implementations are in scope**, not only Python.

## 4 · Why no artifact-update assignment is created yet — a sequencing observation

OQ-3 authorizes the artifact-update step, but **the expected node and edge sets cannot be written until OQ-1, OQ-2 and OQ-4 are settled**: identity depends on OQ-1's rule and OQ-2's scope, and the extraction transport (OQ-4) determines what is observable at all. **Creating the assignment now would authorize writing expectations against an undecided identity scheme.** Governance therefore records the authorization as **standing but unexercised**, and recommends the assignment be created after the final design.

## 5 · The gate sequence, registered

```
1. Governance records the artifact consequences        ← DONE (§3)
2. Architecture produces the final design per OQ-1…OQ-4  (needs OQ-2, OQ-4 ruled)
3. PO/ARB separately authorizes implementation
4. Implementation executes
5. Independent Verification verifies
6. PO/ARB accepts
```

⛔ **No implementation may begin from this act alone.** ⛔ Not done: OQ-2 · OQ-4 · the artifact-update assignment · implementation authorization · lane closures.

**Traceability:** the PO/ARB act 2026-08-18 · implementation architecture `d2f2e5a4` (§4.5 identity candidates · §OQ-1…OQ-4 table · §316 expected.json layers · measured PHP gaps) · Decisions 13.1/13.3/13.5/13.7 · Decision 1 · architecture selection registration
