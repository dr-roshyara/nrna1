---
artifact: 08-HUMAN-DECISION-DOSSIER
date: 2026-08-30
status: **3 DECISIONS — down from 6. D-4 closed by derivation · D-3 substantially closed by recovery · 1 escalated**
---

# 08 · Human Decision Dossier

**Only decisions that genuinely require HPA/governance authority.** Three were removed this pass and
the removals are stated first, because a shrinking dossier is the result that matters.

| Previously | Now |
|---|---|
| **D-4** is `Σ` stored or derived? | ✅ **CLOSED BY DERIVATION** — OR-merge and retract are jointly satisfiable only if `Σ` is derived (`03` §A4). No choice remains |
| **D-3** conditional determination in scope? | 🟡 **SUBSTANTIALLY CLOSED BY RECOVERY** — §157.22 already retains `Method`+`EvidenceReferences`+`ContextSnapshot`, and its invariant *requires* it. Residue folded into **D-3′** below |
| **D-5** restore `ℛ`? | 🟡 **FOLDED into D-1** — `ℛ`'s shape is determined by which relation-operations are mandatory |

---

## D-0 · ⚠️ **Ratify `𝒪_core`, or withdraw the claim that it is ratified**

**Decision.** Steps 273–280 are built on *"the verified semantic operation universe `𝒪_core`."*
**No authority record for it exists.** Ratify it, or direct that the downstream steps be re-marked.

**Why mathematics cannot decide it.** *"Mandatory"* is a normative predicate; no set of descriptions
entails an obligation.
**Why corpus archaeology cannot decide it.** The archaeology was done and returned: **zero hits
across six authority-record locations**, and **272A's own §272A.27 says the set is not proven** (7
open items).
**Why implementation cannot decide it.** The workflow store has no operation-universe record.

**Options.** **(a)** ratify `𝒪_sem^candidate` as-is — closes congruence and minimality immediately;
adopts a set its own source calls unproven. **(b)** ratify only the **14-operation forced lower
bound** — every member entailed by a corpus non-collapse law; leaves 4 optional. **(c)** decline to
ratify; direct that steps 273–280 re-mark `𝒪_core` as `HYPOTHESIS`. **(d)** ratify with the three
**forced-but-unenumerated** operations added: **`Qualify`, `Determine`, `Derive`**.

**Consequences.** (a) fastest, weakest ground. (b)+(d) provable now; `Transform`/`Merge`/`Split`/
`Reintroduce` stay optional. (c) honest, and stops further work compounding on an unratified premise.

**Invariants preserved by all options.** `K` is unaffected — **band-invariant across all 16
resolutions** (`EXECUTED`). Nothing downstream of `K` changes.

**Recommendation** *(a recommendation, not a discovered truth)*: **(b) + (d)** — ratify the forced
14 plus the three forced-but-omitted operations, and mark the remaining four **explicitly optional**.
It is the only option where every member is entailed rather than asserted.

**Separate and urgent, whichever you choose:** **13 research files carry `Authority: HPA`, 9 carry
`HPA Ruling`, 5 carry `HPA Supervisory Ruling`, 7 carry `Status: ACCEPTED` — against zero governance
entries for steps 272–280.** If those attestations are not yours, the research track is
self-attesting authority and that needs stopping on its own account.

---

## D-1 · The four unforced operations, and with them `ℛ`'s shape

**Decision.** Are **`Transform`, `Merge`, `Split`, `Reintroduce`** mandatory or optional? And
consequently: does `ℛ` stay a bare triple, or recover fields from the corpus's
`r=(E₁,E₂,T,R,Q,E,Σ,τ)`?

**Why mathematics cannot decide it.** They are forced by **no** non-collapse law. `Transform` is the
corpus's most-used operation (8 of 9 kernel phases) — **evidence of centrality, not of obligation.**
**Why corpus archaeology cannot decide it.** §256.2 calls its own table *"a reconstruction target,
not a claim that all nine are already formally defined."*
**Why implementation cannot decide it.** No implementation exercises them.

**Options.** **(a)** all four mandatory. **(b)** none. **(c)** `Transform` only. **(d)** open-ended
with a mandatory core.

**Consequences.** This determines what `∀T ∈ 𝒯` ranges over, hence **whether congruence and global
minimality are provable at all.** For `ℛ`: `Merge`/`Split` mandatory ⇒ relations need provenance and
evidence carriers ⇒ the bare triple is insufficient.

**Invariants preserved by all options.** `K`'s component set (16/16 tested). `Σ₀`. Provenance's
four-object split.

**Recommendation:** **(c) or (d).** Both keep the forced core provable now.
⚠️ **Caveat that must not be lost:** restoring `Σ` into `r` would import a defect — a bare `Σ` field
with no policy parameter is **ill-typed**, since `Σ` is policy-relative (`03` §A4).

---

## D-2 · Type the referent of `humanActRef`?

**Decision.** `Grant` is typed and has identity (132/132). The **human act it references** is a
free-text string. Give it an identity, or not?

**Why mathematics cannot decide it.** The regress is **already terminated**; this adds no
termination, only auditability.
**Why corpus archaeology cannot decide it.** `Grant = recorded reference to human act` is as far as
the corpus goes.
**Why implementation cannot decide it.** It shows a *practice*, and it shows the practice **already
failing**: two distinct authority acts share one `grantId` — *"a silent loss of an authority act."*

**Options.** **(a)** status quo. **(b)** add `actId`, keep the free text. **(c)** a fully structured
act record. **(d)** make `grantId` globally unique only — fixes the observed collision, nothing else.

**Consequences.** (a) leaves a known, realised failure. (b) minimal, fixes collision + revocation +
version-drift detection. (c) heaviest, **and the only option that risks the boundary** — a structured
act record edges toward a constructor. (d) cheapest, narrowest.

**Invariants preserved by all options.** *"Software records authority; software does not manufacture
it."* — (a),(b),(d) preserve it structurally; **(c) requires re-testing it.**

**Recommendation:** **(b)** — smallest change that closes a failure already on the record, and it
**extends** the existing `Grant` rather than creating a second mechanism (`ES-005.4`).

---

## D-3′ · Residue only — rule instance or rule version?

**Decision.** §157.22's `Method` field: does it reference a **rule instance** or a **rule version**?

**Why undecidable from evidence.** §157.22 supplies the field and not its referent granularity;
policy identity is `(id, version)`, which makes both coherent.
**This is the entire remaining content of D-3.** The *"because"* is already retained by the corpus's
own aggregate — no decision is needed on scope, only on granularity.

**Recommendation:** **rule version** — it composes with `Policy=(id,version)` and makes replay
deterministic. **Low stakes; defer if you prefer.**

---

## Standing item — not mine to resolve

**GN-73** records a **MATERIAL COLLISION WITH THE RATIFIED LANE**, raised by another session and
awaiting you: the policy-change loop was closed **twice, differently** — ratified `I-11 + R-1`
(v0.2, GN-19) and again by the 2026-08-30 audit — *"two unreconciled resolutions of one problem
(`ES-005.4`)"*.
