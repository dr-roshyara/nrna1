# PBDIGIT-30 — Active Organisation Business Lifecycle Discovery

**Type:** Discovery (business) · **Epic:** `PBDIGIT-EPIC-01` Organisation Management
**Created:** 2026-08-06 · **Predecessor:** `PBDIGIT-29` (implementation discovery — complete)

| | |
|---|---|
| **Status** | **BLOCKED** |
| **Reason** | **Business decision required.** B1–B9 are questions about how the product should behave; none is answerable from the repository |
| **Owner** | **Product Owner** — not engineering |
| **Engineering** | **cannot continue.** No organisation-context design, modelling or implementation may begin until B1–B9 are answered |
| **Unblocks** | a tactical-modelling story *(only if the answers show a concept is warranted)*, then implementation, then verification |

---

## Why this story exists

`PBDIGIT-29` asked **"how does Organisation Context work?"** — an *implementation* question, and it is answered.

This story asks **"what is the business lifecycle of an Active Organisation?"** — a *business* question, and it is **not** answered anywhere in the repository.

The distinction matters because `PBDIGIT-29` produced open questions (its Q1–Q3) that **cannot be answered by reading code**: whether "active organisation" is a concept or a field, and whether a selection page should exist, are decisions about how the product should behave. Answering them from code would be inventing business rules from implementation accidents — the exact failure mode that produced the current instability.

**Nothing in this story is answerable by search. Every question below needs a human decision.**

---

## The nine business questions

| # | Question | Why it matters (grounded in `PBDIGIT-29`) |
|---|---|---|
| **B1** | **When does an Active Organisation begin?** At registration? At first login? At first organisation membership? | today it begins at registration by database constraint (`users.organisation_id` is `NOT NULL`), which is a schema decision, not a business one |
| **B2** | **When does it change?** | today it changes in ≥15 places, including while merely *viewing* a page (`PBDIGIT-29` I-1, I-5) |
| **B3** | **Who may change it?** The user? An admin? The system? | no authority exists today; the last writer wins (`PBDIGIT-29` I-1) |
| **B4** | **Is it remembered between logins?** | today: partly — the column persists, the session does not, and a cached *routing decision* may outlive both (`PBDIGIT-29` I-4) |
| **B5** | **What happens after "Organisation Created"?** | the event is dispatched with **no listeners**; the consequence is hard-coded in the controller (`PBDIGIT-29` §2, I-8). **This is the question that produced the story** |
| **B6** | **What happens when the user has exactly one organisation?** | R4 says enter it automatically; today voting sessions and elections take precedence over organisation context (`PBDIGIT-29` I-7) |
| **B7** | **What happens when the user has multiple organisations?** | R5 says show a selection page; **no such page or route exists** (`PBDIGIT-29` I-3). Is organisation selection wanted, or is role selection the intended experience? |
| **B8** | **What happens if the current organisation is deleted?** | **Evidence not found** in `PBDIGIT-29`. `users.organisation_id` is `NOT NULL` with a foreign key, so deletion has a schema consequence that must match a business intent |
| **B9** | **What happens if the user's membership is revoked?** | today `TenantContext` aborts **403** when membership is missing (`TenantContext:73,79`). Is a hard 403 the intended experience, or should the user fall back to another organisation? |

---

## Deliverable

**Answers, recorded here, in business language** — not code, not design. Each answer becomes the reference the next story is measured against.

## What this story must NOT do

* no code
* no architecture
* no naming of concepts (that is a modelling decision *after* these answers)
* no choosing a source of truth
* **no inferring answers from current behaviour** — the current behaviour is the thing under question

## Sequence after this story

```
PBDIGIT-29  implementation discovery   ✅ complete
PBDIGIT-30  business lifecycle          ← this story (needs human answers)
   ↓
tactical modelling story (only if B1–B9 show a concept is warranted)
   ↓
implementation story
   ↓
verification
```

**Note the conditional:** if the answers turn out simple, the outcome may be *one well-owned field*, not a new domain concept. `PBDIGIT-29` rev 2 was corrected specifically to stop presupposing otherwise.

---

**Traceability:** `PBDIGIT-29` (§0 key insight, §2 event trace, §5 inconsistencies I-1…I-9, §6 Q1–Q6) · business rules R1–R6 as stated in the `PBDIGIT-29` commission
