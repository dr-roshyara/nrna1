# `KOS-NEXT-ACTOR-ORCHESTRATION-001` — FOLLOW-UP observations (recorded, NOT implemented)

**Recorded by:** `claude-code-session:5c0e13c1-bfec-407e-bf8e-f51cc52bb489` — the appointed **implementation** actor for this work item (lane seq 1–3, ACTIVE).
**Why this document exists:** the commission binds *"If a deeper requirement is discovered: record FOLLOW-UP. Do not implement it in this slice."* Two findings surfaced while implementing `AST-018`. Both are **outside this slice's scope** and neither was acted on.
**Placement derived:** `scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` → `docs/knowledgeos` (exit 0).

> ⛔ **This document records observations. It decides nothing, corrects nothing, and reopens nothing.** It does not touch the completed `AST-017` `V-1/V-3/V-5` correction or its independent re-verification. Classification of these findings — and whether either blocks adoption — belongs to Governance, not to the implementation actor (`R-34`: engineering supplies evidence and never accepts its own work).

---

## FU-1 · The authoritative workflow store is not durable

**Fact.** `.claude/runtime/` is git-ignored:

```
$ git check-ignore -v .claude/runtime/workflow/KOS-NEXT-ACTOR-ORCHESTRATION-001.json
.gitignore:32:.claude/runtime/    .claude/runtime/workflow/KOS-NEXT-ACTOR-ORCHESTRATION-001.json
```

**Why it matters.** `.claude/runtime/workflow/*.json` is the authoritative record — the trust anchor that
`AST-015` folds, that `AST-017` resolves against, and that every `REGISTER`/`HANDOFF`/`START` in this
programme has been written into. It is append-only *within the working tree* and preserved by nothing
outside it. A lost or reset working tree loses every governed lane, every recorded human `START` act, and
therefore every attribution the system relies on. Registration artifacts under `docs/knowledgeos/` are
durable; **what they register is not.**

**Sharpening of an existing concern.** This is stronger than "the evidence being registered may not be
durable": here the *registration itself* — the transition log — is the non-durable part.

**Not decided here.** Whether the store should be tracked, mirrored, exported at slice close, or
deliberately left ephemeral is a governance/architecture decision with real consequences (an append-only
record under version control invites merge semantics nobody has designed). **No change was made.**

## FU-2 · Identity-label extraction cannot distinguish an assignment from a mention

**Fact.** `AST-017`'s `processLabelsReferenced()` (`.claude/scripts/session-bootstrap.php` l.137–147) merges
two different kinds of match into one undifferentiated set:

| Branch | Pattern | What it actually means |
|---|---|---|
| 1 | `/claude-code-session[:=]([A-Za-z0-9._-]+)/` | the lane's **registered actor** — real attribution |
| 2 | `/\b([a-f0-9]{6,16})\b/i` | **any** hex-shaped token in free prose — a mention |

**Evidence from the live record** (`KOS-AIP-GOV-STATE-DURABILITY-ADR`, extracted labels per lane):

```
S5-architecture-dv-correction-review   [architecture] = HANDED_OFF  {a8ce5a39., f7e57e4a, a8ce5a39, dd639043, 2f0301c2}
S6-architecture-dv-correction-rv-repair [architecture] = COMPLETED  {8b92a100, a8ce5a39, ff50a2cf}
```

`a8ce5a39` is the **registered actor of S5** and, in S6, merely **named in prose** (S6's registered actor is
`8b92a100`). Both lanes therefore "reference" `a8ce5a39`, and the resolver reports `AMBIGUOUS`.

**Consequence.** The verdict is fail-closed and creates **no authority breach** — `authorized_to_act=false`
is the safe direction. But the *reason* is semantically wrong: there is **one** assignment and **one**
historical mention, not two assignments. Because governed lanes accumulate prose that names prior actors by
design (independence disclosures do exactly this), the false-ambiguity surface grows monotonically with the
record. Left alone, more actors resolve `AMBIGUOUS` over time for reasons unrelated to their assignments.

**A second, narrower artifact of branch 1.** The label character class includes `.`, so a label written
immediately before a sentence-ending period is captured *with* the period. Three labels in the live record
are affected — `5e1dd9ee.`, `a8ce5a39.`, `fbc084f0.` — each appearing alongside its correct bare form. A
lane whose *only* label carried the period would not be attributable to its own actor at all: the actor
would resolve `UNRESOLVED` despite holding an `ACTIVE` lane.

**What this slice did about it — and did not.** `AST-018` avoids *producing* the defect: it writes
`claude-code-session:<id>` followed by whitespace, and `N-16` pins the full loop (appoint → run `AST-017` as
that process → `RESOLVED` + `authorized_to_act: true`). That is a **producer-side avoidance in new code
only.** `AST-017` was **not modified**, no existing `executionContext` was rewritten, and the extractor was
**not touched** — the `AST-017` correction was bounded to `V-1/V-3/V-5` and has already passed independent
re-verification. Reopening it here would invalidate that verification.

**Not decided here.** Whether FU-2 is (a) an adoption blocker, (b) a bounded follow-up correction to
`AST-017`, or (c) accepted as-is with the fail-closed behaviour documented, is a **Governance
classification**, and the remedy (differentiate attribution from mention in the extractor's output, rather
than widening or narrowing the match) would need its own authorized slice.

---

## Relationship to the wider architecture

These findings sit in a third responsibility, distinct from the two already implemented:

```
AST-017   What is the current governed situation?
AST-018   What should happen next, and how is the next actor prepared?
   ↑ this slice
──────────────────────────────────────────────────────────────────
   ?      Is the evidence underlying the decision durable and semantically trustworthy?
          ↑ FU-1 (durability) and FU-2 (semantic trustworthiness) both land here
```

Naming that third responsibility is **not** proposed here. One slice is not evidence for a new capability
(`ES-006.1`), and the methodology is frozen.

**Traceability:** work item `KOS-NEXT-ACTOR-ORCHESTRATION-001` lane `5c0e13c1-…` seq 1–3 · commission
`…-implementation-prompt.md` (*"record FOLLOW-UP"*, *"NO EKS-07 REOPEN"*, *"NO MIGRATION IMPACT"*) ·
`AMENDMENT-001` · `AST-015` · `AST-017` (`processLabelsReferenced` l.137–147; **unmodified**) · `AST-018`
`N-16` · `.gitignore:32` · live record `KOS-AIP-GOV-STATE-DURABILITY-ADR` lanes S5/S6 · `INV-ATTR-1/2` ·
`R-34`/`EP-02` · `ES-005.4` · `ES-006.1` · EKS-07 **not** reopened · `AST-017` `V-1/V-3/V-5` correction and
its independent re-verification **untouched**.
