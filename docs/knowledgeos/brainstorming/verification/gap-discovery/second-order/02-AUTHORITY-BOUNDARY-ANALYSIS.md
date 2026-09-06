# 02 — Authority Boundary Analysis (D-2)

**Mandate Part B.** Second-order derivation attempt on D-2: *"Is authority exogenous?"*

**Relied upon from the first-order pass:** `03` ON-1, `10` GR-3/GR-4/GR-5, `16` G-04/G-10/G-38.
**Executed here:** direct inspection and execution of `.claude/runtime/workflow/` and
`.claude/scripts/session-bootstrap.php`.

---

## 0. HEADLINE — a correction to my own first-order finding

**The first-order pass claimed the implementation *contradicts* Step 187's stipulation. It does not.
It implements it, with 100 % coverage.**

The first-order pass conflated two different questions:

| Question | First-order treatment | Second-order finding |
|---|---|---|
| **Where does authority originate?** | claimed contradiction | **Theory and implementation AGREE.** Exogenous, by reference, 132/132. |
| **Which artifacts are subject to the governance regime?** | merged into the above | **A genuine and separate coverage gap** (the ten schema files). Real, but it says nothing about D-2. |

`GR-5` survives as an implementation gap. `G-38` ("the implementation contradicts the stipulation")
is **withdrawn**.

---

## B1 — The three questions, kept apart

The mandate requires splitting "authority". The **running implementation splits it into six**, and
says so in its own header:

> `.claude/scripts/session-bootstrap.php`:
> *"The six are NEVER collapsed into one 'agent identity':
> IDENTITY ≠ ROLE ≠ ELIGIBILITY ≠ AUTHORIZATION ≠ OWNERSHIP ≠ CONTINUATION."*

| Mandate's question | Corpus | Implementation | Status |
|---|---|---|---|
| **1. Competence** — who is entitled to act? | `Authority ⊆ Actor × Action × Context × Time` (§187.14) | `role`, `eligibility` (73 lane records) | **`CORPUS EVIDENCE` + `IMPLEMENTATION OBSERVATION`** |
| **2. Authorization** — which act authorizes this operation? | `AuthorityState = (AS, B)` where `B` is the recorded basis (§187.23) | `grantId` + `status` + `scope`; **132 grant objects** | **`IMPLEMENTATION OBSERVATION`** |
| **3. Constitutional authority** — who may amend the rules? | §187.28–29: `Authority → GovernanceRule → TransitionContract → enforcement`; *"Kernel enforces authority claims; Kernel does not originate authority"* | `humanActRef` on **132/132** grants; `registeredBy: governance` on **132/132**; EKP constitution: ADR + supersession + ARB review | **`IMPLEMENTATION OBSERVATION`** |

---

## B2 — The regress, and exactly where it terminates

```
Policy  →  Authorization  →  Authority  →  Constitutional authority  →  ???
```

**Executed trace** over `.claude/runtime/workflow/*.json` (22 work items, 73 lanes):

```
grant objects                       132
grants carrying humanActRef         132 / 132   (100 %)
registeredBy value                  "governance"  on 132 / 132  (single value)
authority values                    all PO/ARB variants
grant status                        AUTHORIZED 130 · REVOKED 1 · CONSUMED 1
humanAct entries (free-text)         79, attached to transitions
humanAct entries as TYPED OBJECTS     0
hex tokens inside humanActRef        29 distinct; 21 resolve elsewhere in the store
```

Example grant, verbatim:

```json
{ "grantId": "G-KOS-INC1",
  "status": "AUTHORIZED",
  "authority": "PO",
  "humanActRef": "platform-implementation-commission §15 (7cbe5984) + D-2 boundary approval WITH R8 (f5981933)",
  "scope": "Increment 1 — authoritative workflow state record (boundary §12)",
  "registeredBy": "governance" }
```

And the resolver's own contract:

> *"a Governance act is required before any authorization."*
> *"This bootstrap **states the requirement; it cannot perform it**."*
> *"On any non-RESOLVED verdict: `operable=false`, `authorized_to_act=false` … The bootstrap never
> invents identity/authorization and never creates a transition."*

Executed: `php .claude/scripts/session-bootstrap.php --process-label=verification`
→ `verdict: UNRESOLVED · operable: false · candidates: 73` — **fail-closed, as documented.**

### The termination point, answered

The mandate offers four options. The evidence selects **C, realized as A-by-reference**:

| | Option | Verdict |
|---|---|---|
| A | an exogenous human act | **the terminus** — `humanActRef` on 132/132; `humanAct` objects: 0 |
| B | an internally represented constitutional rule | **partially** — the EKP constitution *is* internally represented, `status: frozen`, `requires_adr_to_change`, with an ADR+ARB amendment rule |
| C | **a two-level model** | **✅ THIS.** A frozen constitutional layer inside the system, anchored to human acts outside it |
| D | another mechanism already present | — |

$$\boxed{\text{Authority is EXOGENOUS in origin and ENDOGENOUSLY RECORDED, by reference, at 100\% coverage.}}$$

**No constitutional layer had to be invented.** The mandate warned against inventing one; the finding
is that one already exists, in code, and has since before this research began.

---

## B3 — Implementation consistency: which of the four readings is right?

| Reading | Verdict |
|---|---|
| 1. theory/implementation **contradiction** | **REFUTED.** They agree exactly. §187.29 is implemented. |
| 2. **incomplete** implementation | **partially true** — see the two gaps below |
| 3. **intentionally two-level** authority | **✅ CONFIRMED** — and documented in the source, not inferred |
| 4. authority partly exogenous, partly represented | **✅ CONFIRMED** — this is what "by reference" means |

Readings 3 and 4 are the same finding stated twice. The implementation is a **deliberate two-level
model**: the act is outside, the record of the act is inside.

### The two genuine residual gaps

**AB-1 (`IMPLEMENTATION OBSERVATION`, HIGH) — `humanAct` is untyped.**
79 `humanAct` entries exist; **all 79 are free-text strings**:

> `"PO/ARB START act 2026-08-19 delivering the Principal-Architect commission for
> KOS-AIP-GOV-STATE-DURABILITY-ADR. The act states the routing and its ground …"`

and `humanActRef` is likewise a prose string with embedded hex tokens (21 of 29 resolve). So the
constitutional layer is **recorded but not machine-checkable**. This is precisely the same defect
shape as Step 265's provenance reference whose `ResolveProvenance` has no typed target — and it is
the same defect the third verifier package reports as *"`AuthorityAct` has no type"*.

**AB-2 (`IMPLEMENTATION OBSERVATION`, CRITICAL — carried from `10` GR-5).**
All ten `docs/knowledge/schema/*.yaml` vocabulary files carry **zero** knowledge cards. Editing
`statuses.yaml` changes the meaning of every governed document with no ADR, no owner, no review, no
lint. **This is a coverage gap, not an authority-origin gap** — which is exactly the distinction the
first-order pass failed to draw.

---

## B4 — Is the constitutional layer mathematically necessary?

Re-answering the first-order question with the new evidence:

| | Verdict | Ground |
|---|---|---|
| mathematically necessary | **No** | a fixed-point construction over self-referential authority is consistent; nothing forces exogeneity |
| architecturally necessary | **Yes** | without it the dependency graph has the 7-node cycle (`03` ON-1) |
| **empirically observed** | **✅ YES — and this is the change** | 132/132 grants, 0 typed human acts, fail-closed resolver, ADR+ARB constitutional amendment. The two-level model is *running* |
| normative | **No longer** | it was a normative choice **before** the implementation existed; it is now an observed architectural fact with 100 % coverage |

**The decisive move:** the first-order pass classified D-2 as normative because "the corpus decided by
stipulation." But the mandate's own test asks whether *implementation* can decide it. It can, and it
has. A stipulation that is implemented, at 100 % coverage, with a fail-closed enforcement mechanism
and an explicit refusal to self-authorize, is **`IMPLEMENTATION OBSERVATION`, not an open choice.**

---

## VERDICT ON D-2

$$\boxed{\textbf{D-2 IS DERIVED — IT IS NOT AN IRREDUCIBLE NORMATIVE CHOICE}}$$

**Authority is exogenous in origin and endogenously recorded by reference, in a deliberate two-level
model that is specified in the corpus (§187.28–29) and implemented at 100 % coverage
(132/132 `humanActRef`, `registeredBy: governance`, fail-closed authorization).**

**Withdrawn from the first-order register:** `G-38` (claimed theory/implementation contradiction).

**Retained and re-scoped:**

- `AB-1` (new) — `humanAct` and `humanActRef` are untyped prose; the constitutional layer is recorded
  but not machine-checkable. **Engineering, derivable.**
- `AB-2` = `G-10`/`GR-5` — the schema vocabulary is outside the governance regime. **Engineering,
  and now correctly classified as a coverage gap rather than an authority-origin gap.**

Neither requires a human normative decision. Both require the same one-line answer the corpus
already gives for provenance: **type the reference and give the resolver a target.**

---

**Next:** `03-DETERMINATION-SCOPE-ANALYSIS.md`.
