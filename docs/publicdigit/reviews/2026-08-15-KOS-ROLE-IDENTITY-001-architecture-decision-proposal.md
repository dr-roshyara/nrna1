# KOS-ROLE-IDENTITY-001 (`DEP-3`) — Identification of Working Responsibilities
# Architecture Decision Proposal

**Session 4 — `S4-architecture-role-identity` · ACTIVE, mutation owner · grant `G-KOS-ROLEID-ARCH` (investigation only) · 2026-08-15**
**Startup gate passed:** `identity` ACTIVE + linkage · `authorized: true` vs the granted scope · seq 2 handoff + seq 3 human START present · `AST-016` `RESOLVED / operable: true`.

> ## PROPOSED — NOT GOVERNANCE-REVIEWED · NOT PO/ARB-APPROVED
> Investigation only. **Nothing implemented.** No `AST-015`/`AST-016`/`workflow-state.php` change · no hooks · no `SESSION_START` wiring · no authorization mechanism · no lane identities created · **no git configuration changed** · no component created · no follow-up started.
> **No remedy chosen or authorized. No mechanism preselected — one, several, some, or none all remained live throughout.**

---

## 1 · Current-state evidence (measured this session)

| # | Evidence | Method |
|---|---|---|
| **E-1** | **Every attribution channel in the record names a ROLE, never a person.** All **16 grants** across all 7 records carry exactly six fields — `grantId · status · authority · humanActRef · scope · registeredBy`. `authority` = *"PO/ARB (delivered decision…)"*; `registeredBy` = `governance`. **There is no person field anywhere in the schema.** | field census across all records |
| **E-2** | `recordedBy` is an **unvalidated free string** on `REGISTER`/`HANDOFF`/`START` — `"banana"` is accepted; only `COMPLETE`/`CONTINUATION` constrain it to `['governance','human']`. It is a **claimed** role, not a validated one | executed probe |
| **E-3** | The operator channel is **free prose**: `humanAct` on `START` reads *"PO/ARB act: '…'"* — again a **role**, not a person | record inspection |
| **E-4** | `executionContext` is stored on `REGISTER`, checked non-empty, re-emitted by `identity` — **never validated, never compared, never read by any precondition** | source (`:126, 199, 372`) |
| **E-5** | **Git provides no discrimination:** one author and one committer identity across 60 commits; **60/60 unsigned** | git census |
| **E-6** | 🔑 **Per-lane git identity is NOT achievable by configuration here.** There is **one worktree and therefore one `.git/config`**, so `git config user.name` is **repository-scoped**, shared by every lane | `git worktree list` (1) |
| **E-7** | 🔑 **Identity is overridable per command by any lane.** `GIT_AUTHOR_NAME=lane-test … git var GIT_AUTHOR_IDENT` → **`lane-test <lane@test>`**. **Demonstrated, not inferred** | executed |
| **E-8** | Signing is **possible but wholly unprovisioned**: gpg 2.4.9 and ssh-keygen present; **0 secret keys**, `user.signingkey` unset, `commit.gpgsign` unset | environment inspection |
| **E-9** | **Zero active git hooks** — no existing provenance instrumentation of any kind | `.git/hooks` census |
| **E-10** | The only de-facto lane discriminator is the **commit-subject convention** (`docs(governance)`, `MAINT(verification)`, …) — a **declaration** | commit-subject census |

> **Consolidated:** the record has **no person axis at all**, and its role axis is **claimed rather than validated**. `E-6`+`E-7` together mean that in *this* setup, "per-lane git identities" — `DEP-3`'s originating idea — resolves to **a per-command environment-variable convention that any lane can set to any value.** That is not a weakness of the idea; it is a property of a single shared worktree.

## 2 · The four questions, kept distinct

| # | Business question | Channel today | Status |
|---|---|---|---|
| **Q1** | Which **working responsibility** performed the activity? | `role` on `REGISTER` (validated against the workflow's role set) · `recordedBy` (**unvalidated**) · commit-subject convention | **Partially answered** — the strongest of the four |
| **Q2** | Which **person** initiated or operated it? | **none** — `humanAct` prose names a role; git shows one identity for everyone | **Unanswered; no channel exists** |
| **Q3** | Which **person** approved or verified it? | **none** — `authority` names a role (*"PO/ARB"*); `humanActRef` is prose | **Unanswered; no channel exists** |
| **Q4** | Was the judgement **genuinely independent**? | — | **Not answerable by identity at any strength** (§5) |

**Q2 and Q3 are distinct and must not be merged.** The operator (who ran the act) and the approver (who authorized it) are different people at different moments with different accountability. **Today the record collapses both into the single role token `human`.**

## 3 · Alternatives considered

**Not assumed exhaustive** (§10). Each is stated at the smallest form that could answer something.

| # | Alternative | Touches |
|---|---|---|
| **ALT-0** | **No additional instrumentation** — keep `C-3` + existing conventions | nothing |
| **ALT-1** | **Working-lane git identities** via per-command env vars | operating convention |
| **ALT-2** | **Structured provenance block in artifacts** — a small required header naming responsibility · operator · approver · evidential status | artifact convention |
| **ALT-3** | **Person-level operator field** in the record | 🔴 `AST-015` |
| **ALT-4** | **Person-level approver field** on grants | 🔴 `AST-015` (+ `D-2` family) |
| **ALT-5** | **Signed commits/artifacts** with per-lane keys | operating setup + key management |
| **ALT-6** | **Combinations** — e.g. `ALT-2` now, `ALT-3/4` later, `ALT-5` for high-assurance acts | staged |

## 4 · Comparison and trade-offs

| | Q1 | Q2 | Q3 | Q4 | Transparency | Reliability | Operational effort | Risk of false confidence |
|---|---|---|---|---|---|---|---|---|
| **ALT-0** | partial | ✗ | ✗ | ✗ | low | n/a | **none** | **low** — claims nothing |
| **ALT-1** | ✓ declared | ✗ | ✗ | ✗ | medium | **low** — forgeable by any lane (`E-7`) | low–medium (per-command discipline) | 🔴 **HIGH** — looks like attribution, is a convention |
| **ALT-2** | ✓ declared | ✓ declared | ✓ declared | ✗ | **high** | medium — declared but **versioned** and diffable | **low** — prose convention | **low** *if* labelled `declared` |
| **ALT-3** | ✓ | ✓ declared | ✗ | ✗ | high | medium | 🔴 high — reopens qualified `AST-015` | medium |
| **ALT-4** | ✓ | ✗ | ✓ declared | ✗ | high | medium | 🔴 high — `AST-015` + `D-2` | medium |
| **ALT-5** | ✓ | ✓ **attested** | ✓ **attested** | ✗ | high | **high — only non-forgeable option** | 🔴 high — key provisioning **and isolation** | low |
| **ALT-6** | ✓ | ✓ | ✓ | ✗ | high | graduated | graduated | low |

**Three trade-offs decide this comparison:**

1. **`ALT-1` is the worst risk-adjusted option** — it produces the *appearance* of attribution at a reliability I disproved by execution (`E-7`). **A forgeable identity that looks authoritative is worse than no identity**, and this programme already has the precedent: `V-3` cost three false-defect episodes precisely because an instrument asserted more than it could support.
2. **`ALT-5` is the only alternative that can *attest* rather than *declare*** — but only if keys are per-lane **and not cross-accessible**. In one OS user + one worktree, **any lane can read any key**, so `ALT-5` here delivers *declaration with extra steps* unless OS-level isolation is added.
3. **`ALT-2` is the only option that improves Q1–Q3 together at zero mechanism cost** — and, uniquely, it lands in **versioned** artifacts. Under `E-1` (the runtime record is untracked/gitignored) an artifact-borne fact is **more durable and more auditable** than a record-borne one.

## 5 · What each alternative can and cannot establish

> **Q4 cannot be established by any alternative on this list, or by any identity scheme at all.** All lanes are the same model, the same human director, the same worktree, the same repository. **Two distinct identities given the same evidence are one judgment computed twice.** Identity can establish *who acted*; independence is a property of *how judgment was formed*, which no identifier observes. **Any alternative claiming otherwise should be rejected on that ground alone.**

| Alternative | Can establish | **Cannot** establish |
|---|---|---|
| **ALT-1** | that a lane *claimed* an identity | that the claim is true (`E-7`) · anything about persons · independence |
| **ALT-2** | a **declared, versioned, diffable** statement of responsibility · operator · approver, with its own evidential status | truth of the declaration · independence |
| **ALT-3/4** | the same, inside the authoritative record and queryable | truth of the declaration (still self-declared) · independence |
| **ALT-5** | **that a specific key signed a specific object** | that the key-holder is the claimed person · that keys were isolated · independence |
| **ALT-0** | nothing new — **and claims nothing** | everything else |

## 6 · Recommendation

> ### **ALT-2 now — a structured provenance block in artifacts — with `ALT-5` recorded as the only path to attestation, and `ALT-1` recommended AGAINST.**

**Reasoning, in the order the evidence forced it:**

1. **Reject `ALT-1`.** `E-7` proves it forgeable by any lane; it would create false confidence, which this programme has already paid for once. *(This is the `DEP-3` originating idea, and the investigation recommends against it in its bare form — the evidence, not a prior preference, produced that.)*
2. **Do not reopen `AST-015` for `ALT-3`/`ALT-4` yet.** They are the *right shape* — attribution belongs in the authoritative record eventually — but they buy **declared** facts at the cost of reopening a qualified mechanism. `P-6` already sequenced this: establish operational value first.
3. **Adopt `ALT-2`.** It answers Q1–Q3 as **honest declarations**, costs no mechanism change, and is **versioned** — which under `E-1` makes it more durable than the record itself. It **extends the existing `A-5.2` `D-a`–`D-d` pattern** rather than inventing a second convention (**ES-005.4**).
4. **Hold `ALT-5`** for genuinely high-assurance acts, and only with key isolation — otherwise it is ceremony.
5. **`ALT-0` remains defensible** if the PO/ARB judges the transparency gain not worth the discipline. It is the honest floor, not a failure.

**Proposed `ALT-2` block — smallest useful form:**

```
Responsibility : architecture | implementation | verification | governance
Operator       : <who ran this>            [declared]
Approver       : <whose act authorized it> [declared, by commit/artifact ref]
Evidential     : declared — not attested   (INV-ATTR-2)
```

**Every field labelled `declared`.** Per `INV-ATTR-2` this must never be presented as attestation, and per `A-5.5` it must never gate anything.

## 7 · Evidence realistically available by 2026-08-30

**A. Achievable in the remaining window — all zero-mechanism:**

1. **The current-state census in §1** — already established, and it is itself the primary finding: **the record has no person axis, and its role axis is unvalidated.**
2. **A trial of the `ALT-2` block** on governance/architecture artifacts produced between now and 30 August, yielding **observed operational effort** (does it get filled in? is it accurate? does it survive handoffs?).
3. **The `A-5.2` disclosure count and quality** — how often the overlap occurs, and whether `D-d` is substantive. *(Already feeding the `A5` evaluation.)*
4. **A negative result if it occurs** — e.g. the block is skipped or filled mechanically. **That is evidence, not failure**, and is worth more than an untested proposal.

**B. Not achievable by 2026-08-30 — later phase (§8).** Anything touching `AST-015`/`AST-016`; key provisioning with isolation; any machine verification.

> **The honest expected state on 30 August:** a measured current-state, a short operational trial of one convention, and no attested attribution. **`DEP-3` will not be "solved" by then, and was never scoped to be.**

## 8 · Later dependencies requiring separate authorization

**Recorded only — none designed, none authorized.**

| # | Dependency | Requires |
|---|---|---|
| **DEP-3.1** | Operator field in the record (`ALT-3`) | 🔴 reopening qualified `AST-015`; new architecture boundary (`P-6`) |
| **DEP-3.2** | Approver field on grants (`ALT-4`) | 🔴 `AST-015` + the pre-existing **`D-2`** linkage gap |
| **DEP-3.3** | Signing keys with **enforced per-lane isolation** (`ALT-5`) | operating setup + OS-level separation; **not a mechanism change** |
| **DEP-3.4** | Surfacing any new attribution field in reports | 🔴 `AST-016`; **reporting-only** per `A-5.5` |

**All of `DEP-3.1`/`3.2` belong to the existing `DEP-1`/`D-2`/`D-6` family** that `P-6` requires to stay separately commissioned and **unbatched without a new architecture boundary**. **This investigation adds no new mechanism dependency beyond that family.**

## 9 · Explicit non-goals

Nothing implemented · `AST-015`, `AST-016`, `workflow-state.php`, `session-resolve.php` **untouched** · no hooks · no `SESSION_START` wiring · no authorization mechanism · no change to the execution model · **no lane identities created** · **no git configuration changed** *(the `E-7` probe set an environment variable for one read-only `git var` call and wrote nothing)* · no new component · no follow-up implementation started · no remedy chosen or authorized · no PO/ARB decision taken · **no claim that identity establishes independence, authority, correctness or approval** · `V-3`, `D-2`, `D-6`, `E-1`, `O-CLOSURE-VOCAB`, the bootstrap gap and Election work untouched · **Architecture does not complete its own assignment** (`G-1`) and does not self-certify.

## 10 · Remaining uncertainty

1. **Alternatives are not assumed exhaustive.** An external attestation service, per-lane OS users, or a signed-artifact registry were not explored — they exceed the "smallest useful form" filter, not necessarily the solution space.
2. **`ALT-2`'s durability is untested.** Conventions in this programme have historically been *invented during the incident they mitigate*. Whether a provenance block survives routine pressure is exactly what §7's trial would show — **and it may fail.**
3. **The operator/approver distinction may be thinner than it looks here**, because one human currently holds both. It matters structurally, but the near-term evidence may not exercise it.
4. **I cannot verify my own process identity.** This proposal's own `executionContext` is a self-declared label (`INV-ATTR-2`), so **this document is itself an instance of the problem it investigates** — which is evidence for §5's conclusion rather than an objection to it.
5. **Whether the transparency gain justifies any discipline at all is a PO/ARB judgment**, not an architectural finding. `ALT-0` remains live.

---

**Traceability:** grant `G-KOS-ATTR-ARCH`→`G-KOS-ROLEID-ARCH` · PO/ARB commission + refinements `§8.1`/`§8.2` (`1eb493e1`) · startup gate (identity · authorized · seq 1–3 · resolver) · measured `E-1`…`E-10` this session · `INV-ATTR-1`/`INV-ATTR-2` (`A-5.3`) · `A-5.2` `D-a`–`D-d` (the pattern `ALT-2` extends) · `A-5.5` reporting-only · `A-4` `C-1` · `C-3` · `E-1` untracked-record finding · `V-3` false-confidence precedent · `P-6` sequencing · `D-2` · `DEP-1` · `D-6` · review date `2026-08-30` (`A-6.8`)

> # PROPOSED — awaiting Governance review, then PO/ARB decision. No remedy chosen.
