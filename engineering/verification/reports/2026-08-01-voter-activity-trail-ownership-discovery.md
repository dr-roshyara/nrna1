# Strategic DDD Ownership Discovery — the Voter Activity Trail

**Date:** 2026-08-01 · **Role:** Senior Principal Architect · **Commission:** discover whether the Voter Activity Trail already belongs to an existing bounded context, or whether its ownership was never modelled. Discovery only — no implementation, no new context invented, no ownership self-assigned.
**Repository Integrity Gate:** ✅ PASSED. Evidence read from the working tree at HEAD.

> ## ⛔ THE COMMISSION CANNOT BE COMPLETED AS FRAMED — AND THE REASON IS THE FINDING
>
> **The Voter Activity Trail links a voter to their vote.** It therefore cannot be *owned* by any bounded context, because no context is permitted to hold it. **Admissibility precedes ownership**, and this artifact fails admissibility against a constitutional invariant.

---

## 0. The evidence, quoted verbatim

`storage/logs/organisation_null/demo_election/10_nab_roshyara.log` — one real file, three lines:

```
[2026-02-19 21:39:40] VOTE_STARTED   {"user_id":10,"action":"vote_started","election":"demo","step":1}
[2026-02-19 21:42:31] VOTE_SUBMITTED {"user_id":10,"action":"vote_submitted","candidate_id":5,"step":4}
[2026-02-19 21:52:52] VOTE_CONFIRMED {"user_id":10,"action":"vote_confirmed","vote_id":1,"step":5}
```

**Read the three lines together:** `user_id 10` → `candidate_id 5` → `vote_id 1`. That is a **complete voter↔vote linkage**, in a file whose *name* is the voter's identity, and `vote_id` supplies the join key into the vote and result tables.

Against the architecture's own words:

| Authority | Statement |
|---|---|
| Project charter (`CLAUDE.md`) | *"votes table: **NO user_id column** (voters cannot be linked to votes)"* · *"✓ No vote coercion possible"* |
| Same, on this very audit design | *"Candidate selections **(anonymous)**"* |
| **ADR-T11** | anonymity invariant — **no voter↔vote linkage** |

**The stored record contradicts the annotation beside it.** The design was documented as anonymous; the artifact is not.

**Severity, bounded honestly:** one file · demo tenant (`organisation_null`) · dated 2026-02-19 · and the writers (`voter_log`, `log_vote_submission`) are **autoloaded but never called from any controller or service** (verified: zero call sites outside the helper itself). So the mechanism is **dormant with historical residue**, not an active leak. **The capability is live, however** — the helper is registered in `composer.json` autoload and would write this shape the moment it is called.

## 1. Domain concept definition

Setting aside filesystem layout, helper names and logging mechanics, the concept as **implemented** is:

> **A per-person, ordered record of one individual's voting actions, including which candidate they selected and which vote row resulted.**

That is not an audit concept. **It is a ballot-with-a-name.** The lawful concept nearby — *"this person participated in this election"* — is a **different** concept, because it excludes the selection.

## 2. Business responsibility analysis

| Question | Evidence-based answer |
|---|---|
| Why does it exist? | The charter's stated intent: *"invaluable for dispute resolution"* — per-voter step timings, IP, and completion for answering *"what happened to this voter?"* |
| Who depends on it? | **Nobody in code.** No reader exists; no controller, service, command or test consumes it |
| Which business process cannot function without it? | **None identified.** The 5-step voting workflow, contestation and adjudication all function without it; `voter_slugs` / `voter_slug_steps` already carry step state **in the database, without candidate selections** |

**The concept has no dependent process** — which is itself evidence that it was never modelled, only written.

## 3. Existing bounded context evaluation

| Context | Relationship | Reasoning |
|---|---|---|
| **Voting / Election** | **Consumer of the lawful part only** | it owns *participation* (`voter_slugs`, step tracking) — and already records it **without** linking selections. It **cannot** own the linkage: the anonymity invariant is the reason its own vote table has no `user_id` |
| **Contestation** | **Uses indirectly** | a challenge may reference *that* a voter's process failed, never *how they voted* |
| **Adjudication** | **No relationship** | evidence enters by opaque reference (`EvidenceSet`, hashes); ADR-T11 forbids voter↔vote linkage in its examples |
| **Governance · Membership · Committee · Geography · Finance** | **No relationship** | different subjects entirely |
| **Audit / Retention** | **No relationship to the concept** | it can only govern a lifecycle; it cannot legitimise content |
| **Operational Logging** | mechanism only | writing a file is not owning a concept |

> **No existing context can own the artifact as implemented — and the reason is not a gap in the model. It is that the model forbids it.** The Voting context is the closest, and it is precisely the context whose design deliberately excludes this linkage.

## 4. Aggregate ownership assessment

**No aggregate, entity, value object or domain service owns this lifecycle, and none should be designed for it.**

The nearest legitimate owner is the **`VoterSlug` / `voter_slug_steps`** family, which already records participation and step progression **in the database, without candidate selections**. That is the lawful shape of the concept, and it already exists.

**No design is proposed here.** The observation is only that the lawful concept is already modelled and owned — which means the artifact adds a **linkage** rather than a **capability**.

## 5. Lifecycle responsibility

| Decision | Business owner | Operational executor |
|---|---|---|
| **Whether this artifact may exist at all** | ⛔ **the constitution** (ADR-T11 / anonymity) — **this is the prior question** | — |
| Creation | **unassigned** — currently an autoloaded helper with no caller | `ElectionAudit` helper |
| Retention / archival / deletion | **unassigned** — no mechanism exists | none |

**Retention is the wrong question to ask second.** A guard protecting an inadmissible artifact would preserve a constitutional violation for longer, with governance approval. **Admissibility must be settled before lifecycle.**

## 6. Constitutional Policy 2 relationship

| Option | Assessment |
|---|---|
| Explicitly governed | ❌ No governed artifact names the Voter Activity Trail as the Retention Invariant's subject |
| Implicitly governed | ⚠️ Plausible for a *lawful* participation record; **inapplicable to a linkage** the constitution forbids |
| Unrelated | ⚠️ Partly — an artifact that may not exist has no retention window |
| **Impossible to determine from current evidence** | ✅ **This one.** Policy 2 protects *evidence*; whether an unlawful record counts as protected evidence is not answerable from any artifact in the repository |

**Constitutional intent is not inferred here.** Recorded as an ARB question (§9).

## 7. Ownership classification

The commission's three options do not fit, and forcing one would misreport the finding:

| Option | Verdict |
|---|---|
| **A** — already owned by an existing context | ❌ No |
| **B** — concept exists, ownership missing, needs ARB assignment | ⚠️ **Half true, and misleading alone.** Ownership is missing *because the artifact is inadmissible*, not because modelling was overlooked |
| **C** — evidence supports a new bounded context | ❌ **No — and this is the important refusal.** Creating a context to own a linkage the constitution forbids would **legitimise the violation by giving it a home** |

> ### **Classification: PRIOR QUESTION — ADMISSIBILITY, NOT OWNERSHIP.**
> The artifact as implemented cannot be assigned to any owner. A **lawful successor concept** (participation without selection) is **already owned** by the Voting context via `VoterSlug`.

## 8. Architectural recommendation

**Recommendations only. No ownership assigned, no code changed, no context created.**

1. **Do not assign ownership.** Assigning an owner would make some context responsible for maintaining a voter↔vote linkage. **The absence of an owner is the model working correctly**, not a gap to fill.
2. **Escalate the anonymity finding as its own item**, at whatever severity the ARB judges — it is a **constitutional** matter, not a retention one, and it outranks WP-7 entirely.
3. **WP-7 remains untouched and correctly scoped to artifact A.** This finding *removes* B from WP-7's horizon rather than adding to it: an inadmissible artifact needs adjudication, not a retention guard.
4. **Recognise that the lawful concept already has an owner** — participation lives in `VoterSlug`/`voter_slug_steps`, in the database, without selections. Whatever the ARB decides, no new modelling is required to record participation.
5. **Record the residue** — one demo-tenant file dated 2026-02-19. Its disposition (retain as historical evidence · purge · treat as a demo artifact outside the invariant) is an **ARB decision**, not an implementation task, and deleting evidence on my own initiative would be exactly the wrong reflex.
6. **Record the dormant capability** — the writers are autoloaded with **zero call sites**. Whether a dormant capability to breach an invariant is itself a defect is an ARB judgement.

## 9. Questions requiring ARB

| # | Question | Why it cannot be answered here |
|---|---|---|
| **B-1** | **Does the Voter Activity Trail's `user_id` + `candidate_id` + `vote_id` record violate the anonymity invariant (ADR-T11)?** | Constitutional interpretation. The evidence is unambiguous; the *ruling* is not mine |
| **B-2** | Does the demo tenant (`organisation_null`) fall inside or outside the invariant? | No artifact states whether demo data is constitutionally in scope |
| **B-3** | What is the disposition of the existing file? | Deleting or retaining evidence is an authority decision |
| **B-4** | Is a **dormant** capability to write such a record itself a defect? | Judgement about latent risk |
| **B-5** | Does Constitutional Policy 2 protect an unlawfully-formed record? | Policy interpretation (§6) |
| **B-6** | If a lawful participation trail is wanted as a *file*, does it belong to Voting or to Audit? | Only arises if B-1 is resolved and a file-based record is still desired |

**None of these is a WP-7 item. None is an implementation task.**

---

**Traceability:** ADR-T11 (anonymity) · `CLAUDE.md` (*votes table has NO user_id* · *no vote coercion possible* · *"Candidate selections (anonymous)"*) · Constitutional Policy 2 · Domain Audit Boundary Commission `2026-08-01-domain-audit-boundary-commission.md` (which identified B as unowned) · WP-7 scope definition. **Evidence read from `app/Helpers/ElectionAudit.php`, `composer.json` autoload, `storage/logs/organisation_null/demo_election/10_nab_roshyara.log`, and a zero-result call-site search. No file was modified, moved or deleted; no ownership assigned; no bounded context created.**
