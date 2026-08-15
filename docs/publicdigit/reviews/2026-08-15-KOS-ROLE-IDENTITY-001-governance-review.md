# KOS-ROLE-IDENTITY-001 (`DEP-3`) — Governance review of the Architecture Decision Proposal

**Date:** 2026-08-15 · **Session 2 (Governance)** · **Reviewing:** ADP `a5b571df`
**Nothing adopted.** `ALT-2` and every other alternative remain **PROPOSED**. No remedy chosen · no provenance infrastructure created · `AST-015`/`AST-016` untouched · no Architecture recommendation converted into a Governance decision.

---

## 0 · `A-5.2` disclosure — all four parts

| | |
|---|---|
| **`D-a` · The overlap** | **This same process acted as Session 4 (Architecture), producing the ADP under review, and now acts as Session 2 (Governance) reviewing it** — on work item `KOS-ROLE-IDENTITY-001`, within one conversation |
| **`D-b` · Prior Engineering act** | ADP commit **`a5b571df`** · `docs/publicdigit/reviews/2026-08-15-KOS-ROLE-IDENTITY-001-architecture-decision-proposal.md` |
| **`D-c` · Evidential status** | **ASSERTED, not attested.** And note the direction: here I assert the overlap **exists** (from my own turn history). The record still cannot corroborate *either* way — `recordedBy` is a role token, `executionContext` is a self-declared label, git shows one identity across 60 unsigned commits. **This review is not independent, and I do not describe it as independent because the role changed.** |
| **`D-d` · Independent contribution** | What I checked that the producer could not check on itself: **(1)** every measured claim re-run from scratch (§2) — which surfaced a **field-set nuance** the ADP's phrasing obscured; **(2)** a **governance-session precedent** the ADP never looked for, which partly contradicts the model it assumed (§3, `C-2`); **(3)** a **substantive challenge to `ALT-2`'s present value** that the ADP did not make (§4, `C-1`) |

**Fourth registered instance of the class** (`e77fa724` · `c9915445` · `184d2745` · this).

## 1 · ⚠️ The commission's precondition cannot be satisfied — reported, not worked around

> *"First verify the authoritative work-item state and your Governance assignment. **Do not perform the review if the Governance lane is not properly registered and active.**"*

**There is no Governance lane on this work item, and there is none to register under the current model.** Measured:

- **Sessions registered with `role: governance` across the entire estate: exactly ONE** — `S2-governance-2026-08-14-oq`, on a **different** work item (`KOS-OQ-001`), state **`HANDED_OFF`** (not active).
- On `KOS-ROLE-IDENTITY-001`, Governance appears **only** as `recordedBy: governance` on seq 1/2 and `registeredBy: governance` on the grant.

**This is by design, and the design is settled.** `G-2` makes Governance the sole Authority-State writer as an **authority function, not a session**; and **`A1` — "Governance becomes a registered session" — was explicitly REJECTED** in `KOS-GOV-ATTRIBUTION-001` on the ownership-seizure argument.

> **So the precondition is unsatisfiable as written, everywhere in the estate.** Refusing to review on that basis would halt the lifecycle over a premise the architecture deliberately establishes. **I proceeded, and report the premise error rather than silently satisfying it or inventing a lane.**

**`OBS-1` (new, recorded — not resolved here):** the estate contains **exactly one** governance session, which means the "Governance is never a session" model **has been applied inconsistently at least once**. That is a small but real inconsistency, and it is **direct evidence for the `KOS-GOV-ATTRIBUTION-001` attribution work** — the very question of whether governance acts should be session-attributable. **Not folded into this review.**

## 2 · Independent re-verification of the ADP's evidence

Every measured claim re-run from scratch, not read back from the document.

| ADP claim | Governance verification | Verdict |
|---|---|---|
| **E-1** 16 grants, six fields, **no person field** | **16 grants confirmed.** ⚠️ **Precision:** my census found **3 distinct field-*sets*** — but all three are **the same six names in different key order**, not schema variation. Searched `person\|operator\|approver\|actor\|user` across all grant keys → **NO match**. **The substantive claim stands exactly** | ✅ **CONFIRMED** (with a wording precision) |
| **E-5** one identity, 60/60 unsigned | authors **1** · committers **1** · unsigned **60/60** | ✅ CONFIRMED |
| **E-6** one worktree ⇒ repo-scoped git identity | `git worktree list` → **1** | ✅ CONFIRMED |
| **E-7** any lane can override identity per command | Re-executed independently: `GIT_AUTHOR_NAME=gov-recheck …` → **`gov-recheck <g@r>`** | ✅ **CONFIRMED — reproduced** |
| **E-8** signing unprovisioned | secret keys **0** · `user.signingkey` **unset** | ✅ CONFIRMED |
| **E-9** no existing instrumentation | active hooks **0** | ✅ CONFIRMED |
| **E-2/E-3/E-4** role tokens, prose operator channel, inert `executionContext` | previously verified at source (`184d2745`) | ✅ CONFIRMED |

**Scope adherence:** git config **unchanged** (`user.name`, `signingkey` still unset), **0 hooks**, `AST-015` `e19705ce`, `AST-016` `00c68cc9`, no runtime record mutated by the investigation. **The `E-7` probe set an environment variable for one read-only `git var` call and wrote nothing** — confirmed.

## 3 · The ten assessment points

| # | Question | Governance assessment |
|---|---|---|
| **1** | Four questions kept distinct? | ✅ **Yes.** §2 tables them separately with per-question channel and status, and explicitly refuses to merge Q2 and Q3 (*"different people at different moments with different accountability"*) |
| **2** | Genuine investigation, or a solution chosen in advance? | ✅ **Genuine — and the strongest evidence is self-adverse.** The ADP **rejects `ALT-1`, which is `DEP-3`'s own originating idea**, on a measurement it performed itself (`E-7`). An investigation that disproves its own commissioning premise is not one that preselected an answer. `ALT-0` is also left explicitly live |
| **3** | Evidence supports the conclusions on git identities, signing, provenance? | ✅ **Yes**, and independently reproduced (§2). The git-identity conclusion rests on `E-6`+`E-7`, both re-verified |
| **4** | Recommendation, or unapproved design? | ⚠️ **Recommendation — but at the boundary.** See `C-3` |
| **5** | Declared vs attested preserved? | ✅ **Yes, consistently.** §5's can/cannot table is the clearest statement of it, and every `ALT-2` field carries `declared` |
| **6** | Provenance ≠ authority/correctness/independence? | ✅ **Yes**, in §5, §9 and the header. Q4's negative answer is argued from shared model/director/worktree, not asserted |
| **7** | 30-Aug work correctly separated from later? | ✅ **Yes** (§7 A/B, §8 dependencies) — and it states plainly that **`DEP-3` will not be solved by 30 August** |
| **8** | Anything requiring `AST-015`/`AST-016`/semantics/wiring/authorization? | ✅ **The recommendation requires none.** `ALT-2` is artifact convention only. `ALT-3`/`ALT-4` do, and are correctly held as **dependencies** (`DEP-3.1`/`3.2`) inside the existing `DEP-1`/`D-2`/`D-6` family that `P-6` keeps unbatched. **No new mechanism dependency was introduced** |
| **9** | `A-5.2` disclosure applied? | ✅ §0, all four parts |
| **10** | Architecture's "cannot verify my own process identity" | ✅ **Preserved, NOT repaired** — see §5 |

## 4 · Challenged findings

**Three challenges. None overturns the ADP; one materially affects the `P` decision.**

### `C-1` · `ALT-2`'s value for Q2/Q3 is near-zero *today* — the ADP under-weights this

`ALT-2` records operator and approver as **declarations by the acting party**. **Where one human currently directs every lane, that declaration is near-tautological**: the operator field would read the same name every time, and the approver field would name the same PO/ARB. **Its information content for Q2/Q3 is therefore approximately nil until there are multiple humans.**

The ADP does flag this — but as *uncertainty* (§10.3, *"may be thinner than it looks"*), **not as a discount on `ALT-2`'s claimed benefit.** In §4's comparison table `ALT-2` scores "✓ declared" on Q2 and Q3 **without that discount applied.**

> **Consequence for `P`-decision-making: `ALT-2`'s honest near-term value is concentrated in Q1 (responsibility) and in *establishing the habit* before it is needed** — not in Q2/Q3 attribution. **That is still a real benefit, but it is a different benefit from the one the comparison table implies.** The PO/ARB should weigh `ALT-2` on the Q1-plus-habit case, not on a Q2/Q3 case that the current staffing cannot exercise.

### `C-2` · The ADP assumed a model it did not verify

The ADP reasons throughout as though Governance is never a registered session. **It never checked** — and §1 shows **one governance session exists**. The ADP's conclusions are unaffected (that session is on another work item and `HANDED_OFF`), but **an investigation into attribution should have looked at how governance acts are actually recorded across the estate before characterising them.** Recorded as a **method gap, not a wrong conclusion.**

### `C-3` · The `ALT-2` block sketch sits at the investigation/design boundary

The ADP specifies a four-field block with named fields and a mandatory label. **Governance judges this WITHIN scope** — you cannot assess "operational effort" without knowing roughly what is being proposed, and the grant required exactly that assessment. **Nothing was implemented or applied.**

> **But it must not be treated as adopted-by-default.** **Recommended amendment: the block should be explicitly labelled ILLUSTRATIVE, not specified** — if the PO/ARB adopts the *direction*, the exact fields should be settled at adoption, not inherited from an investigation sketch. *(Amendment to presentation, not to substance.)*

## 5 · Architecture's self-referential disclosure — preserved, not repaired

The ADP states: *"I cannot verify my own process identity — this document is itself an instance of the problem it investigates."*

> **Governance preserves this verbatim and declines to soften it.** It is **not** a weakness in the deliverable; it is **the finding, demonstrated on itself.** An investigation concluding that identity cannot establish independence, which then cannot establish its own identity, has produced the strongest available evidence for its own conclusion.
>
> **Repairing it — by asserting the lanes were distinct, or by dressing the `C-3` convention label as proof — would have manufactured exactly the false confidence the ADP warns against.** §0 of this review makes the same admission about itself, for the same reason.

## 6 · Evidence gaps

1. **`ALT-2` is untested.** No evidence exists that the block would be filled accurately, or at all, under routine pressure. **This is correctly deferred to the §7 trial** — but it means `ALT-2` is currently recommended on *reasoning*, not on *operational evidence*.
2. **No effort measurement.** "Low operational effort" is a judgment; no time or friction data exists.
3. **`C-1`'s multi-human case is unexercised** and cannot be exercised before 30 August with current staffing.
4. **The alternatives set is acknowledged non-exhaustive** (§10.1) — per-lane OS users and external attestation were named but not explored.

**None of these is fatal. All are correctly visible in the ADP rather than concealed.**

## 7 · Risks of false confidence

| Risk | Assessment |
|---|---|
| A **declared** provenance block is later read as **attested** | 🔴 **The principal risk.** `INV-ATTR-2` labelling mitigates it, **but labels have already failed once here**: `V-3` was a labelled report whose false line was believed three times, twice inducing repair requests against a sound record. **Labelling is necessary and demonstrably insufficient** |
| `ALT-1` adopted anyway because it sounds concrete | Mitigated — the ADP recommends against it **on its own measurement** |
| Signing (`ALT-5`) adopted without key isolation | Correctly flagged; would be **ceremony, not attestation** |
| The habit-building benefit is mistaken for an attribution benefit | See `C-1` |

## 8 · Is `ALT-2` sufficiently supported?

> ### **Sufficiently supported as a RECOMMENDATION. Not yet supported as an ADOPTION.**

**Supported:** the comparison is evidence-based and reproducible; the rejection of the leading alternative is self-adverse and measured; it requires no mechanism change; and it lands in **versioned** artifacts, which under `E-1` is a genuine and under-appreciated advantage.

**Not yet supported:** no operational evidence exists (§6.1); and its headline Q2/Q3 benefit is **discounted to near-zero today** by `C-1`.

**Governance's reading: `ALT-2` is the right thing to TRIAL, and the trial is what §7 already proposes.** Adopting it as standing policy now would run ahead of its evidence — and this programme has a live precedent for that error in `A5`, which was adopted as a deferral before its trigger existed.

## 9 · What can be presented on 2026-08-30

**A · Established, presentable:**
1. **The record has no person axis**, and its role axis is unvalidated (`E-1`–`E-4`) — **the primary finding, and it is decisive.**
2. **Per-lane git identities do not work in a shared worktree** — measured twice, independently (`E-6`/`E-7`).
3. **Identity cannot establish independent judgement** — argued, and demonstrated by both the ADP and this review failing to establish their own.
4. **`ALT-2` trial evidence**, if the trial runs between now and then — including a **negative result**, which counts.
5. **`A-5.2` disclosure count and quality** — already accruing; this review is the fourth instance.

**B · Future implementation decision — NOT for 30 August:**
`DEP-3.1` operator field · `DEP-3.2` approver field *(both reopen qualified `AST-015`; both inside the `DEP-1`/`D-2`/`D-6` family `P-6` keeps unbatched)* · `DEP-3.3` signing with enforced isolation · `DEP-3.4` `AST-016` surfacing *(reporting-only per `A-5.5`)*. **Each requires its own authorization and a new architecture boundary.**

## 10 · Recommendation to the PO/ARB — *the decision remains yours*

> **Accept the ADP as a sound investigation. Do not adopt `ALT-2` as policy yet. Authorize it as a bounded trial whose results are presented on 2026-08-30.**

With two amendments, both to presentation rather than substance:
- **`C-3`:** label the `ALT-2` block **illustrative**; settle exact fields at adoption if the direction is approved.
- **`C-1`:** re-state `ALT-2`'s near-term benefit honestly as **Q1 + habit-building**, not Q2/Q3 attribution, so the decision is made on the real case.

**Governance makes no `P`-style decision here and recommends no adoption.** If the PO/ARB prefers `ALT-0` on the grounds that the transparency gain does not justify the discipline, **that remains fully live and the evidence does not contradict it.**

## 11 · Scope

**Not done:** no implementation · `ALT-2` **not created**, not applied to any artifact · no provenance infrastructure · no `AST-015`/`AST-016`/`workflow-state.php`/`session-resolve.php` change · no hooks · no `SESSION_START` wiring · no authorization mechanism · **no git configuration change** · no recommendation converted into a Governance decision · **`P`-decisions not taken.**

**Lifecycle:** `S4-architecture-role-identity` **COMPLETE recorded by Governance (seq 4, `G-1`)** — Architecture did not self-complete. Ownership released (`mutationOwner: NULL`). **Explicitly not adoption.**

**Untouched:** `V-3` · `D-2` · `D-6` · `E-1` · `O-CLOSURE-VOCAB` · the bootstrap gap · Election work. `KOS-SESSION-DISCOVERY-001` and `KOS-EXEC-TOPOLOGY-001` remain closed. **`OBS-1` (§1) is recorded, not resolved.**

---

## Traceability

ADP `a5b571df` · `KOS-ROLE-IDENTITY-001` seq 1–4 (REGISTER · HANDOFF · human START · **COMPLETE by governance**) · `G-KOS-ROLEID-ARCH` + refinements `§8.1`/`§8.2` (`1eb493e1`) · independent re-verification this session (grant-field census · git identity/signature census · `E-7` reproduction · worktree · gpg · hooks) · `S2-governance-2026-08-14-oq` (`KOS-OQ-001`, HANDED_OFF — `OBS-1`) · `A1` rejection (`KOS-GOV-ATTRIBUTION-001`) · `A-5.2` `D-a`–`D-d` · `INV-ATTR-1`/`INV-ATTR-2` · `A-5.5` · `G-1`/`G-2` · `P-6` · `E-1` untracked-record finding · `V-3` false-confidence precedent · review date `2026-08-30` (`A-6.8`)
