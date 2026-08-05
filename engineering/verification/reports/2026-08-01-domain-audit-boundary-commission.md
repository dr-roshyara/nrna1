# Domain Audit Boundary Commission — Strategic DDD Review

**Date:** 2026-08-01 · **Role:** Senior Principal Architect · **Commission:** do the artifacts called *"audit"* represent **one domain concept** or several with different ownership and lifecycle? Model verification only — no implementation, no WP-7 redesign, no roadmap change.
**Repository Integrity Gate:** ✅ PASSED. Evidence read from the working tree at HEAD.

**The reframing this commission rests on (ARB):** the first question is not *"which tree does the Retention Invariant cover?"* but *"are these the same domain concept at all?"* **Storage location does not define a bounded context; business meaning defines the model.**

---

## 1. Audit artifact inventory — classified by business purpose, not by path

Three artifacts carry the word *audit* (or an audit-like role). **They are not variants of one thing.**

| | **A — Election Event Journal** | **B — Voter Activity Trail** | **C — Constitutional Integrity Log** |
|---|---|---|---|
| **Producer** | `ElectionAuditService` | `ElectionAudit::voter_log()` helper | Laravel channel `constitutional_integrity` |
| **Consumer** | **an administrator, by download** — `AdminElectionController::downloadAuditFile()` | *(no reader found in the codebase)* | *(no reader found in the codebase)* |
| **Storage** | `logs/audit/{slug}_{Ymd}_{Hi}/{category}.jsonl` | `logs/organisation_{id}/{election_name}/{user_id}_{user_name}.log` | `logs/constitutional_integrity-{date}.log` |
| **Shape** | JSONL, categorised `election` · `voters` · `committee`, **rotated at 100 MB** | one plain-text file **per person per election** | daily-rotating text |
| **Business meaning** | *what happened in this election run*, exportable as an artifact | *the complete activity trail for one person in one election* — the project's stated dispute-resolution evidence | *integrity warnings raised by the platform about itself* |
| **Reached by `audit:cleanup`** | ✅ **yes** | ❌ **no** | ❌ **no** |

## 2. Ubiquitous-language assessment — three concepts, not one

| Artifact | Business event represented | Who relies on it | Why it exists |
|---|---|---|---|
| **A** | an **election's** lifecycle events | administrators, auditors, exports | to show how a *run* proceeded |
| **B** | a **person's** participation | a disputant, an adjudicating authority | to answer *"what did this individual do?"* — the anonymity-safe participation record |
| **C** | the **platform's** self-assessment | operators/engineers | to surface integrity violations |

**Verdict: A, B and C are three distinct domain concepts.** The strongest evidence is that **their subjects differ** — an *election*, a *person*, a *platform*. Three subjects means three concepts; a shared parent directory means nothing.

**A decisive piece of counter-evidence against unifying them,** found in `ElectionAuditService`'s own docblock:

> *"Prevents indefinite growth of audit logs within 30-day retention window"*

**Artifact A already declares a 30-day retention assumption in code** — and `config/logging.php` gives **C** `'days' => 365`. **Two artifacts already carry different, independently-chosen retention policies, neither derived from the Evidence Preservation Window.** Different retention is the clearest possible signal of different lifecycle, and therefore of different domain concepts.

## 3. Ownership matrix

| Artifact | Owner | Basis |
|---|---|---|
| **A — Election Event Journal** | **Administrative Export** | its only consumer is an admin download endpoint; it is categorised, rotated and packaged for export |
| **B — Voter Activity Trail** | ⚠️ **Constitutional Evidence — PROBABLE, needs ARB confirmation** | its content matches the project's dispute-resolution claim, but **no artifact names it as the Retention Invariant's subject.** Recorded as an architectural question, not asserted |
| **C — Constitutional Integrity Log** | **Diagnostics / Operational Logging** | a severity-filtered warning channel about the platform, not about an election |

**B's ownership is the commission's open question.** The word *"constitutional"* appears in **C**'s name, while the constitutional *evidence* is plausibly **B** — a naming inversion worth flagging, because it invites exactly the mistake of protecting the artifact whose name sounds most important.

## 4. Lifecycle matrix — three lifecycles, none of them EPW

| Artifact | Created | Retained | Archived | Deleted | Governed by |
|---|---|---|---|---|---|
| **A** | per election run, on first event | *"30-day retention window"* (docblock) + 100 MB rotation | ✖ none | `audit:cleanup --days=30` by **folder mtime** | an **assumption in code** |
| **B** | on each voter action | **indefinitely — nothing prunes it** | ✖ none | ⚠️ **never; no mechanism exists** | **nothing** |
| **C** | daily by the log channel | **365 days** | ✖ none | channel rotation | `config/logging.php` |

**No artifact's lifecycle is governed by the Evidence Preservation Window today.** All three lifecycles are **incidental** — a CLI default, a log-rotation setting, and an absence.

**B's row is the finding with the sharpest edge:** the artifact most likely to *be* constitutional evidence has **no retention mechanism at all**. Its evidence is never deleted, which satisfies the Retention Invariant **by accident** while providing no guarantee — and it also means unbounded growth nobody owns.

## 5. Retention boundary — which evidence is constitutionally protected?

| Artifact | Classification | Evidence |
|---|---|---|
| **B — Voter Activity Trail** | ⚠️ **PROBABLY constitutionally protected** | it is the participation record a dispute would rely on. **But no governed artifact names it**, so this must be ruled, not assumed |
| **A — Election Event Journal** | **Operational / exportable** | consumed by an admin download; already treats 30 days as sufficient. *Unless* it is the only durable record of a contested event — in which case it becomes evidence, which is a question for the ARB |
| **C — Constitutional Integrity Log** | **Disposable (diagnostic)** | severity-filtered platform warnings; 365-day rotation is an operations decision |

**Not assumed: that every artifact under `storage/logs` is covered by Constitutional Policy 2.** On the evidence, **at most one of the three** plausibly is — and it is **not** the one `audit:cleanup` touches.

## 6. Context ownership

| Lifecycle | Owning context | WP-7's relationship |
|---|---|---|
| A's export lifecycle | **Administrative Export** (admin/platform side) | ✅ within WP-7's reach — this is what `audit:cleanup` deletes |
| B's evidence lifecycle | ⚠️ **unassigned** — plausibly Constitutional Evidence, but **no context claims it** | ⛔ **outside WP-7 until an owner exists.** WP-7 must not become the de-facto owner of evidence simply because it is the slice that noticed |
| C's diagnostic lifecycle | **Operational Logging / Infrastructure** | ⛔ outside WP-7 entirely |

**The risk this phase exists to prevent:** WP-7 is the first slice to look at retention, so it is the natural place for all three lifecycles to be quietly assigned. **Noticing a problem does not confer ownership of it** — the same principle that separated DC-1 (governance gap) from DC-2 (implementation overreach).

## 7. Consequences for WP-7

| WP-7 | Artifact | Basis |
|---|---|---|
| **Must protect** | **A**, and only A — the tree it actually reaches, once an EPW is computable for it | its business owner permits deletion, so a *guard* is meaningful |
| **May delete** | **A**, once its window has closed | that is the whole capability |
| **Must ignore** | **B** and **C** | different concepts · different owners · different lifecycles · not reachable by the command |
| **Must not become** | the owner of B's lifecycle | B needs an owner *before* it needs a guard |

**This narrows WP-7 rather than widening it**, and it dissolves the earlier I-1 dilemma. The previous framing asked *"is WP-7 protecting the wrong tree?"* The model answer is: **A and B are different concepts, so WP-7 is not mis-aimed — it is correctly aimed at A.** What the earlier finding really exposed was not a WP-7 defect but **an unowned artifact (B) that no work package covers.**

## 8. Architectural recommendation

1. **Confirm the three-concept model** — A Administrative Export · B Voter Activity Trail (candidate Constitutional Evidence) · C Diagnostics. **Storage co-location is not evidence of a shared concept.**
2. **WP-7 remains scoped to A.** No expansion. The scope definition stands; **I-1 is answered by the model** rather than by a tree-choice.
3. **Open a separate architectural question for B**, owned by the ARB, in this order: *does the Retention Invariant cover the Voter Activity Trail? If so, which context owns its lifecycle?* **Not a WP-7 item, and not an implementation task** — B currently has no owner, and a guard without an owner would be WP-7 defining policy.
4. **Record C as settled** — diagnostics under operational logging, 365-day rotation, outside Policy 2.
5. **Flag the naming inversion for the ubiquitous language:** the artifact *named* `constitutional_integrity` is diagnostics, while the plausible constitutional evidence is the unnamed voter trail. **Names should follow meaning** — but renaming is its own commission, not a WP-7 side effect.
6. **Record one risk beyond retention:** B grows without bound and nothing prunes it. That is an **operational** concern, owned outside WP-7, recorded so it is not lost.

> ### **The three artifacts are three domain concepts. WP-7 owns a deletion guard over ONE of them (A). The Voter Activity Trail (B) is an unowned artifact requiring its own ARB decision before any retention mechanism may touch it — and the earlier "two trees" finding was not a WP-7 defect but the discovery of that gap.**

---

**Traceability:** Constitutional Policy 2 · EPIC-004K §142 · WP-7 scope definition `2026-08-01-wp7-scope-definition.md` (finding I-1) · WP-7 readiness `2026-08-01-wp7-readiness-commission.md`. **Evidence read from `ElectionAuditService.php` (folder shape, 100 MB rotation, the 30-day docblock), `ElectionAudit.php` (per-person trail), `AdminElectionController::downloadAuditFile()` (A's only consumer), `AuditCleanup.php` (mtime deletion), `config/logging.php` (C's 365-day rotation), `storage/logs/**`. No code written; no model changed; no ownership assigned that did not already exist.**
