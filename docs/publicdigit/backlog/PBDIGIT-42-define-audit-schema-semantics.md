# PBDIGIT-42 — Define the semantics of `overlay_influence_chain` (audit schema)

**Type:** Architecture decision required (product/ARB) · **Epic:** `PBDIGIT-EPIC-06` Results and Audit · **Created:** 2026-08-06
**Extracted from:** `ADR_20260806_1340_Audit_Event_Transaction_Boundary` §7, on Product Owner review

| | |
|---|---|
| **Status** | **OPEN — decision required. Engineering must not decide it in code** |
| **Why it is separate** | Approving *transactional isolation* must not implicitly approve *audit semantics*. Two unrelated decisions, two review cycles |
| **Blocks** | **Invariant I-2** — *"a rejected vote must still leave a record of why"*. No audit row can be written at all until this is decided |
| **Does NOT block** | **Invariant I-1** — *"a ballot must never be lost to an audit failure"*. That is satisfied by the ADR's transactional isolation, independently of this decision |

---

## The problem, stated exactly

`election_security_events.overlay_influence_chain` is **`json NOT NULL` with no default** (`database/migrations/2026_05_26_000001_create_election_security_events_table.php:23` — `$table->json(...)` without `->nullable()`).

**Nothing in the codebase ever constructs an influence chain.** The name appears in exactly three places, none of which gives it meaning:

| Location | What it is |
|---|---|
| the migration `:23` | the column declaration |
| `app/Models/ElectionSecurityEvent.php:29` | a `$fillable` entry |
| `app/Models/ElectionSecurityEvent.php:44` | a cast to `array` |

**`SecurityEventRecorder:52` — the only writer — does not supply it.** So every audit `INSERT` fails with `SQLSTATE[23502]`.

**Note the shape of this:** the column is *required* by the schema, *declared* by the model, and *unknown* to the application. **Its sibling overlay columns (`overlay_applied`, `overlay_signal_type`) are nullable** — so the `NOT NULL` may itself be the accident.

## Why engineering stopped rather than filling it in

> **Writing a placeholder into an *audit* column whose semantics nobody can state is worse than leaving it unwritten.**

An audit trail's value is that its contents mean something. A column populated with `[]` "to make the insert work" produces a record that **looks** like evidence and is not — and it would be indistinguishable, later, from a genuine empty chain. **That is a worse outcome than a visible failure.**

## The three options

| | Option | What it asserts | Cost |
|---|---|---|---|
| **i** | **Write the observed overlay identifiers** — the ordered list of overlays that produced observations during the evaluation | that "influence chain" means *which overlays influenced this decision*. The data already exists: `SecurityEventRecorder:45-50` builds exactly this map for `overlay_observations` | small — one line in the recorder |
| **ii** | **Make the column nullable** | that the concept is not implemented, and the schema should stop pretending it is | one migration |
| **iii** | **Drop the column** | that no consumer is intended, and the field was speculative | one migration; irreversible without another |

**Engineering's factual input, not a recommendation:** option **i** is the only one that yields a *non-empty* value from data that exists today, and it would make `overlay_influence_chain` nearly a duplicate of `overlay_observations` — which is itself an argument for **ii** or **iii**. **Whether an "influence chain" is a distinct concept from an "observation set" is a domain question, and it is the question that decides this story.**

## What must be true before this is decided

* [ ] **Is there an intended consumer?** No reader of this column exists. `IpVelocityOverlay:38` reads the table but not this field.
* [ ] **Is "influence chain" a distinct domain concept from "observation set"?** If not, the column is redundant regardless of how it is filled.
* [ ] **Does any retention, compliance or dispute-resolution obligation reference it?** The table carries `retention_days` default 730, which implies an evidentiary purpose.

## Acceptance criteria

* [ ] The decision is recorded as an ADR (or an amendment to `ADR_20260806_1340`), **with the reasoning, not just the outcome**.
* [ ] `election_security_events` rows can be written — **I-2 becomes satisfiable**.
* [ ] A test asserts that a rejected vote leaves a deny audit record *(the I-2 test the ADR marks blocked)*.
* [ ] If option **i**: the meaning of the chain is documented where a future reader will find it, and its relationship to `overlay_observations` is stated.
* [ ] If **ii** or **iii**: the migration is accompanied by a note explaining that the concept was never implemented — **so the next engineer does not re-add it speculatively.**

## Related — do not conflate

**`trust_state_transition`** is the *other* required column the recorder omits, and it is **not** part of this story: its value is unambiguous (`'<trust_level_before>-><trust_level_after>'`, both already written) and it is fixed under the approved ADR. **Recorded here only so the two are not mistaken for one problem** — a fix supplying just one of them would pass review and fail again at runtime.

---

**Traceability:** `docs/publicdigit/adr/ADR_20260806_1340_Audit_Event_Transaction_Boundary.md` §7 (the extraction), §8 (I-2 blocked) · `PBDIGIT-38` §DISCOVERY D-5 · `database/migrations/2026_05_26_000001_create_election_security_events_table.php:23,26` · `app/Models/ElectionSecurityEvent.php:29,44` · `app/Application/Election/Security/SecurityEventRecorder.php:45-52` · `app/Application/Election/Security/Overlays/IpVelocityOverlay.php:38`
