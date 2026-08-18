# 04 · `EM-DOM-001` Act B — the `RecordedOperationalStatus` contract

**Work item:** `EM-DOM-001` (`PBDIGIT-68`) · **act:** B (contract only) · **date:** 2026-08-19
**Commits:** `e7317077` (RED, test alone) → `216552aa` (GREEN, interface alone)

> ## ⛔ Read this before you use it
> **The contract EXISTS. Nothing implements it, nothing calls it, and nothing may.**
> Act-B GREEN means only that the domain now *states what must be retrievable*. Operational status is **not** persisted, **not** retrievable at runtime; restoration is **not** fixed; `GREEN-5` stays **STOPPED**; `EM-GOV-063`'s terminal consequence stays unreachable (`PBDIGIT-72`). **Writing an adapter (act C) or a call site (act D) needs its own authorization.**

## Purpose

`ElectionOperationalStatus` was semantically complete and **unreachable**: nothing in `app/` could construct, store or retrieve one, so recorded operational truth could not enter any decision. Two adopted domain policies were starved by that gap — `ResumptionTarget` (P-7, `EM-GOV-059(c)`) and `ExpiryConsequence` (`EM-GOV-063`).

This step adds the one thing that was missing: a **Domain-owned contract that names the question** — *"what is this election's RECORDED operational status?"* — and nothing else.

## Where it fits

`app/Contexts/Election/Domain/OperatingCore/Port/` — the operating core's **driven ports**: contracts the domain declares for something it needs supplied from outside itself, where the supplying mechanism is deliberately unchosen. Pure PHP, no framework, no Laravel (repo Domain-layer rule).

⛔ **Not `Repository/`.** Repo **Rule 9** reserves repositories for aggregates, and all three interfaces there say so in their own docblocks (*"contract for AG-1/AG-2/AG-3 … repositories exist for aggregates only"*). A fourth one there would assert aggregate standing that **D1** withholds and **D4** forbids. Gate 1 (`6079fe9f`) confirmed `Port/` on that reasoning; the closest precedent is **`ProtocolAppend`** — a contract over the Election's *own recorded record* whose storage mechanism is deliberately not prescribed and which has no adapter.

## Key files

| File | Role |
|---|---|
| `app/Contexts/Election/Domain/OperatingCore/Port/RecordedOperationalStatus.php` | the contract — one operation, added in `216552aa` |
| `tests/Unit/Contexts/Election/OperatingCore/RecordedOperationalStatusRetrievalRedTest.php` | the `H-1` structural-absence RED, added in `e7317077` |
| `…/Condition/ElectionOperationalStatus.php` | the frozen return type — **unchanged** |
| `…/Condition/HaltedAtGate.php`, `…/Policy/ResumptionTarget.php` | reached only *through* the status; **unchanged** |

## How it works

```php
interface RecordedOperationalStatus
{
    public function ofElection(ElectionId $electionId): ElectionOperationalStatus;
}
```

- **Key:** the election's own `ElectionId` — the same key the three existing aggregate contracts use, and the only discriminator the overlay needs (the frozen status type carries no second one).
- **Answer:** the **frozen** `ElectionOperationalStatus`, unchanged — the two orthogonal recorded facts (`EM-GOV-059(b)`): the overlay condition, and whether a halt is recorded and at which gate.
- **Total by type.** *"No operational status is recorded"* is not an answer this contract may give: `Operative`-not-halted is a **positive recorded state**, not an absence (`EM-GOV-062`). Whether an election that has not been constituted has an overlay at all is a **lifecycle-phase** question — `BND-1`, deferred; the contract neither answers nor encodes it.
- **A read of recorded truth**, never a computation or re-classification (`DD-1`) — the core's own recorded-vs-derived distinction (`HaltedAtGate` = *"the recorded fact"*; `GateIntervalState` = *"DERIVED … never stored"*).
- **Absence of a halt is a legitimate answer**, discriminated by `isHalted()`. Restoration and Resumption are distinct: a restoration with no prior halt has a known causal origin and **no resumption target** (**D2**). The contract fabricates no halt (ADR-2 §6(b)) and supplies only the input to *"where does restoration resume?"* — never a verdict on *"why is restoration permitted?"* (ADR-2 §6(a)).

## Design decisions carried

| Ref | What it fixes here |
|---|---|
| **D1** (`BND-2` = Act B IN SCOPE) | authorizes **creation and definition of the contract only** — no adapter, no Application change, no boundary or mechanism selection |
| **D2** (`w8`) | absence of a halt is legitimate; **no representation is selected**, and none is encoded here |
| **D3** (`BND-1` deferred) | no phase concept, no `ElectionLifecycleState`, no Published-Language relationship with `App\Domain\Election\*` |
| **D4** (`BND-3` deferred) | the contract prescribes no mechanism, so every boundary reading `BND-3` leaves open can supply it unchanged — it selects none, in either direction |
| **Gate 1 `6079fe9f`** (`G-2a`) | naming/placement confirmed; §5.1's negative list governs the docblock |
| **Gate 2 `ca72dec8`** | `C-3`: a non-nullable return **is** a legitimate type-level encoding and breaches no decision · `C-4`: `H-1`'s *"domain invariant"* framing overclaims and its *"no target is produced by any means"* clause is not executable |

## Testing

`H-1` is a **structural-absence RED**, committed **alone** before the interface, so the ordering is git-provable:

```
e7317077  RED   4 failures — interface_exists(...\Port\RecordedOperationalStatus) === false
216552aa  GREEN 4 passing  — the interface exists with the confirmed signature
```

What it asserts, and only this: the contract is **declared**, is an **interface**, lives in the operating core's `Port/` namespace, declares **exactly one** operation `ofElection(ElectionId): ElectionOperationalStatus` with a **non-nullable** return, exposes **no** persistence/collection idiom, and is **satisfiable by a Domain-only double**.

What it deliberately does **not** assert (`C-4`, `H-3`): no domain-invariant claim; no *"no target is produced by any means"* (not executable — P-7 refuses `null` by signature, already frozen); nothing behavioural about halt retention, restoration or the `w8` case — those already hold on the frozen type, so a test of them would be a **regression lock** (`H-2`), and counting one as the RED would falsify the RED-before-GREEN evidence.

Run it: `vendor/bin/phpunit tests/Unit/Contexts/Election/OperatingCore/` (46/46 green). The pre-existing errors in `OperatingCoreApplication/` (GREEN-3…7 pending, `AbsentAggregateReferenceRedTest`) are **identical with and without this file**.

## Pitfalls

1. ⛔ **Do not write an adapter.** Act C is unauthorized. Like `OrganisationalAppointmentAuthority`, this interface is deliberately unimplemented — but note the difference: that port has *no operations at all* because its form may not be fixed; this one has a declared signature and simply **no supplier yet**.
2. ⛔ **Do not inject it into a handler or a `Query` class.** That is act D, it would take the Application's collaborator set from six to seven, and `C-2` requires its own authorization for that.
3. ⛔ **Do not read the placement as a boundary answer.** `Port/` withholds `BND-3` rather than answering it; `Rule 9` is one-way (`Repository/` ⇒ aggregate), never its contrapositive.
4. ⛔ **Do not add a second operation.** One semantic responsibility. No `save` (act C), no `find()` idiom, no collection query, no filtering.
5. ⚠️ **`W-4` will bind the eventual consumer:** no Application class may **hold** an `ElectionOperationalStatus`/`HaltedAtGate`/`OperationalCondition` as a property — *retrieve and use, never retrieve and hold*.
6. ⚠️ **The docblock is governed.** Gate 1 §5.1 bars boundary vocabulary in **either** direction: it may not say the overlay is an aggregate, nor that it is not one. Keep it that way when editing.
7. ⚠️ **A structural guard scans this directory's source as text** (framework-token freedom, cf. `PBDIGIT-71`) — a docblock mentioning a framework token would fail it.

**Traceability.** `EM-DOM-001` Act-B implementation release *(PO/ARB, 2026-08-19)* · authorization `117536f3` · **D1–D4** + Appendix Q *(live)* — `docs/publicdigit/reviews/2026-08-18-EM-DOM-001-decision-recording-surface.md` · Gate 1 `6079fe9f` · Gate 2 `ca72dec8` *(C-1…C-4)* · Phase-2A design map §§B/C/F/G/H-1/H-3 · `ADR_20260817_2145` §6/§6b/`C-2` · `ADR_20260817_2300` §6(a)/(b)/(h) · `EM-GOV-059(b)(c)` · `EM-GOV-062` · `EM-GOV-063` *(`PBDIGIT-72`)* · `DD-1` · `W-4` · P-7 · repo **Rule 9** · baseline `1f4b4c5f` *(frozen core: one file ADDED, none modified — 56 → 57)* · commits `e7317077` (RED) · `216552aa` (GREEN).
