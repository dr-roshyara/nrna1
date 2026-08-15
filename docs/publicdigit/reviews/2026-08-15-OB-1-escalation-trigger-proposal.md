# `OB-1` — Escalation trigger for the `A5` deferral · **PROPOSAL**

**Date:** 2026-08-15 · **Prepared by:** Session 2 (Governance) · **Work item:** `KOS-GOV-ATTRIBUTION-001`
**Status: PROPOSED — NOT ADOPTED.** Preparation only, as instructed. **No implementation · no `AST-015`/`AST-016` change · no new mechanism · no policy adopted · `A5` NOT operationalized.**

> **A prior trigger formulation was delivered and then withdrawn by the PO/ARB before registration. Governance verified it appears nowhere in the governance record — nothing was registered and nothing required unwinding.** This proposal supersedes it.

---

## 1 · What `OB-1` must supply

`P-1` made a trigger the **precondition** for `A5` becoming operational policy. `A-5.1` records it as blocking. Required: **threshold · owner · review point.**

**The defect it exists to cure:** `A5`'s original trigger (*"if and when evidence shows the convention insufficient"*) named no threshold, owner or review point, so **`A5` could never demonstrably fail.** Any replacement must be falsifiable — it must be capable of firing.

## 2 · Measured evidence — why a bare count is harder than it looks

**Counting today's disclosed overlaps gives different answers depending on what counts:**

| Artifact kind | Count | Example |
|---|---|---|
| **Governance review** disclosing an **actual** overlap | **2** | `…EXEC-TOPOLOGY-001-governance-review…` §4 · `…GOV-ATTRIBUTION-001-governance-review` §0 |
| Decision **registration** carrying a disclosure/pointer | 2 | both `…-decision-registration.md` |
| **Architecture** pre-emptive note (forward-looking, not an occurrence) | 1 | the ADP's header note |
| Intake / commission artifacts **discussing** the class | 2 | intake · architecture-commission |
| **Total artifacts touching the overlap** | **7** | |

> ### 🔴 **The decisive finding: on a single day's record, the honest count is either 2 or 7 — a 3.5× spread — purely from the reading.**
> A threshold of *"3 review artifacts"* would therefore be **not yet met** (strict reading) **or long since exceeded** (loose reading). **A count-based trigger is unusable until "what counts" is defined in the rule itself** — and defining it precisely is harder than it appears, because registrations and pre-emptive notes are genuinely disclosure-bearing without being occurrences of the hazard.

**Second measured fact:** with one process family doing all lanes, **overlap recurrence is driven by ordinary workload, not by risk.** A count therefore measures **throughput**, not **harm**.

## 3 · Alternatives

### `T-1` — Occurrence count

| | |
|---|---|
| **Threshold** | **N** (e.g. 3) **Governance review artifacts** carrying an `A-5.2` disclosure of an **actual** overlap |
| **Owner** | PO/ARB |
| **Review point** | On reaching N; and at the end of the `A5` evaluation period |

**Consequences.** ✅ Objective and auditable — `A-5.2` `D-b` makes every disclosure self-identifying by commit/artifact, so the count is a query over the record, not a maintained list. ❌ **Requires a counting rule the rule text must carry** (§2's 2-vs-7 problem), and any such rule will have edge cases. ❌ **Measures frequency, not harm** — the overlap could recur ten times harmlessly, while a single damaging instance would not fire. ❌ With one process family, N is reached by **doing normal work**, so it fires on volume rather than risk. ⚠️ Choosing N is arbitrary without a harm model, and today's baseline is already ambiguous relative to any small N.

### `T-2` — Harm / dispute condition

| | |
|---|---|
| **Threshold** | Any disclosed overlap where the `A-5.2` `D-d` **independent contribution is null, trivial, or contested**; **or** any party raises a material dispute about attribution or independence |
| **Owner** | PO/ARB — raisable by any participant |
| **Review point** | Immediately on the condition |

**Consequences.** ✅ **Targets the actual hazard** — a review that added no independent signal — rather than a proxy for it. ✅ **No new instrumentation:** `D-d` already exists under `A-5.2`, so the condition reads a field the duty already requires. ❌ *"Null, trivial, contested"* and *"material"* are **judgments**, and in the null/trivial case the judgment is made by **the same process whose independence is in question**. ❌ 🔴 **It may never fire.** Alone, `T-2` **reproduces the exact defect `OB-1` exists to cure** — an unfalsifiable trigger. **Governance does not recommend `T-2` as a standalone.**

### `T-3` — Time-boxed review with a harm arm *(hybrid)* — **RECOMMENDED**

| | |
|---|---|
| **Threshold** | **(a)** arrival of a **fixed review point** — the end of the `A5` evaluation period, or a stated not-to-exceed date, whichever is earlier; **and (b)** immediately on `T-2`'s harm condition, if it occurs first |
| **Owner** | PO/ARB *(Governance compiles the evidence; it does not decide)* |
| **Review point** | The fixed date — **guaranteed** — plus immediately on **(b)** |

**Consequences.**
- ✅ **Cannot be unfalsifiable.** A date always arrives, so the trigger **always fires at least once**. This structurally cures the defect that created `OB-1`, rather than restating it in new words.
- ✅ **Dissolves the counting problem instead of solving it.** At the review point, **all** accumulated disclosures are examined together and their number becomes **evidence for the decision**, not the trigger for it. §2's 2-vs-7 ambiguity stops mattering — no rule text has to adjudicate it.
- ✅ **Harm arm prevents waiting** when something actually goes wrong.
- ✅ **No maintained register:** `A-5.2` `D-b` makes the evidence a query over the record.
- ❌ Requires the PO/ARB to **set a date** — a decision, not a derivation.
- ❌ Between review points, overlaps accumulate without automatic escalation — **mitigated by arm (b)**.
- ⚠️ A date set too far out weakens it to near-`T-2`; too near, and it fires before `A5` has produced usable evidence.

## 4 · Recommendation

> ### **`T-3` — time-boxed review with a harm arm.**
> **Threshold:** the earlier of **(a)** the end of the `A5` evaluation period *(as defined by `P-6`)* or a stated not-to-exceed date; **(b)** any disclosed overlap whose `D-d` independent contribution is null, trivial or contested, or any material dispute raised about attribution/independence.
> **Owner:** **PO/ARB.** Governance compiles the evidence and decides nothing.
> **Review point:** immediately upon **(b)**; otherwise at **(a)**, and in any event at the end of the `A5` evaluation period.

**Why this and not the others, in one line each.** `T-1` alone fires on workload and needs a counting rule the record shows is contestable. `T-2` alone repeats the unfalsifiability defect `OB-1` exists to cure. **`T-3` is the only option that is guaranteed to fire, and it converts the count from an unreliable trigger into reliable evidence.**

**Two parameters the PO/ARB must set if `T-3` is chosen:** the **not-to-exceed date**, and whether Governance is obliged to **surface accumulated disclosures proactively** at the review point or only on request.

## 5 · Supporting definition — offered, not adopted

**If any option retaining a count is chosen** (`T-1`, or `T-3` if the count is ever given trigger weight), Governance proposes this definition so §2's ambiguity is settled *in the rule* rather than at the moment of counting:

> **A countable instance** is a **Governance review artifact** — an artifact whose stated purpose is to review engineering evidence — that carries an `A-5.2` disclosure of an **overlap that actually occurred** in producing that review.
> **Not countable:** decision registrations · commissions · intakes · pre-emptive or forward-looking notes · any artifact merely *discussing* the class.

**Under this definition today's baseline is 2.** *(Recorded so that whatever is adopted starts from a stated number rather than an assumed one.)*

## 6 · Scope

**Prepared, not adopted.** No implementation · no `AST-015`/`AST-016`/hook/`SESSION_START`/workflow-semantics change · no new mechanism · **no policy adopted beyond this proposal** · **`A5` NOT operationalized and NOT implemented** — it remains a deferral whose precondition is unmet.

**`OB-1` remains OPEN.** Until the PO/ARB adopts a trigger, `A5` is **not operational policy** and current practice continues as **unruled practice**. Per `P-6`, the `A5` evaluation period has **not started**.

**Untouched:** `V-3` · `D-2` · `D-6` · `E-1` · `O-CLOSURE-VOCAB` · the bootstrap gap · Election work. `A-5.2`'s disclosure duty remains **in force** and is unaffected by this proposal.

---

## Traceability

`P-1` and `A-5.1` (`OB-1` as blocking precondition) · `P-6` (evaluation period) · `A-5.2` `D-b`/`D-d` (self-identifying disclosures; independent-contribution field) · Governance review §8.1 (the original trigger's unfalsifiability finding, `e0a9d31c`) · disclosure-instance census this session (7 artifacts; 2 under §5's definition) · withdrawn PO/ARB formulation — verified absent from the governance record · `KOS-GOV-ATTRIBUTION-001` decision registration (`d4332e93`)
