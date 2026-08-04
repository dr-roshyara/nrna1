# WP-4C-2 — Discovery (evidence only)

**Produced by:** engineering, 2026-08-04. **Discovery only — no production code, no model proposed.**
**Owner of the work package:** **Contestation** (R-88). **Blocked on:** EPIC-004K **§15.3**.
**Authority to exist:** discovery is evidence-gathering; it neither authorizes nor decides. **Neither WP-4C-2 nor any part of it is authorized.**

---

## 0. The boundary this report keeps

**§15.3 asks a Contestation-side design question: *"challenge disposition on declared failure."*** My own readiness review (2026-08-04) recorded that **challenge disposition is Contestation's to model.**

**So this report gathers evidence about what exists and what any answer must reckon with. It does NOT propose the disposition, the aggregate transition, or the invariant** — producing those would be answering §15.3, which is the one thing this discovery must not do. **Where the brief asks for "Aggregate boundaries" and "invariants", what follows is what the CODE ALREADY ESTABLISHES, not what WP-4C-2 should add.**

## 1. What exists in Contestation today

| Element | State | Evidence |
|---|---|---|
| `ChallengeState` | 8 cases: `Raised · Admitted · Investigating · Routed · Adjudicated · Resolved · Dismissed · Lapsed` | `Domain/Challenge/ChallengeState.php:15-22` |
| `Challenge::adjudicate(DeterminationId, DateTimeImmutable)` | exists — guards `Routed`, sets `Adjudicated`, **stores the determination id**, records `ChallengeAdjudicated` | `Challenge.php:105-111` |
| `ChallengeAdjudicationReaction::on(ChallengeId, DeterminationId, DeterminationOutcome, …)` | exists — the business reaction to a **binding determination** | `Application/ChallengeAdjudicationReaction.php:35` |
| Inbox translation | exists — `ChallengeReactionOutcomeTranslator`: replay → ack · conflicting → dead-letter + escalate · precondition missing → park+redrive | `Application/Inbox/` |
| **A consumer for `AdjudicationFailureDeclared`** | ⛔ **does not exist** | this is WP-4C-2 |
| **A consumer for `AdjudicationExpired`** | ⛔ **does not exist either** | `grep -rln AdjudicationExpired app/Contexts/Contestation/` → no matches |

## 2. 🛑 FINDING — the existing reaction cannot be reused, and this is why §15.3 is a design question

**`Challenge::adjudicate()` is determination-shaped:**

```php
public function adjudicate(DeterminationId $determinationId, DateTimeImmutable $at): void
{
    $this->guard('adjudicate', ChallengeState::Routed);
    $this->state = ChallengeState::Adjudicated;
    $this->determinationId = $determinationId;      // ← requires an id
    $this->record(new ChallengeAdjudicated($this->id, $determinationId, $at));
}
```

**`AdjudicationFailureDeclared` carries no determination — none was issued, and none can be.** So the transition, the event it records, and the field it sets all presuppose exactly the thing a declared failure lacks.

> **This is the substance of §15.3.** It is not a wiring task with an unanswered detail: **the Challenge aggregate has no transition for *"routed, adjudication attempted, no ruling possible"*, and inventing one is a modelling act in Contestation's domain.** Whether that is a new state, a reuse of `Lapsed`, a variant of `Dismissed`, or something else is **precisely what must not be decided here.**

**Three of the eight existing states are adjacent and none is obviously right — recorded to show the question is real, not to answer it:** `Dismissed` (but nothing was ruled) · `Lapsed` (but it did not lapse — an authority acted) · `Resolved` (but nothing resolved it).

## 3. Integration points with Adjudication — settled by delivered work

| | |
|---|---|
| **Event produced by Adjudication** | `AdjudicationFailureDeclared` — `challengeRef · reason · declaredByAuthority · declaredAt`, payload **v1** |
| **Reconstruction** | `AdjudicationFailureDeclaredHydrator`, registered — **the consumer side needs no new hydrator** |
| **Transport** | outbox row, `event_type = 'AdjudicationFailureDeclared'`, `aggregate_type = 'AdjudicationProcess'`, `aggregate_id = challengeRef` |
| **⛔ Not yet published** | **D4 is HELD.** Nothing enqueues the event, so **a consumer built today would have nothing to consume** |
| **Failure translation** | Contestation's translator pattern exists and is the precedent — **shape transfers; suitability is Contestation's call** |

## 4. Architectural assumptions requiring explicit verification before implementation

| # | Assumption | Why it must be verified, not assumed |
|---|---|---|
| **A-0** | ⚠️ **that the question is “which state?” at all.** The prior blocking question is **WHO OWNS THE UNRESOLVED RESPONSIBILITY** once adjudication has been attempted and no ruling is possible — Contestation · Election · Collection-side · or a human authority | **In DDD, CROSS-CONTEXT WORKFLOW STATE FOLLOWS RESPONSIBILITY OWNERSHIP, not the reverse.** (Refined 2026-08-04: the earlier wording “state follows responsibility” was broader than the evidence — **lifecycle states INSIDE an aggregate, such as `Draft → Validated → Published`, transfer no ownership at all.** The claim holds for state that moves work between contexts.) A state chosen before the owner is named would encode a responsibility nobody accepted. **This supersedes A-1 in ordering: terminality is a CONSEQUENCE of ownership, not an independent question** — e.g. if Collection-side owns the next move under COL-5a, the disposition is not terminal *because someone else must act* |
| **A-1** | that a declared failure is a **terminal** disposition for the challenge | **downstream of A-0.** If the challenge may be **re-routed** after an insufficiency (as an expired one is returned to Contestation), the disposition is not terminal and the state choice changes entirely |
| **A-2** | that `AdjudicationFailureDeclared` reaches Contestation as an **integration event** over the inbox | asserted nowhere; §10 names Contestation as a consumer but the transport is not fixed |
| **A-3** | that **one** reaction can serve both `AdjudicationFailureDeclared` and `AdjudicationExpired` | both consumers are missing (§1). They may share a disposition or need two — **and answering that for expiry is Q-2-adjacent business policy, not WP-4C-2** |
| **A-4** | that the **second consumer** in §10 (Collection-side, COL-5a — the renewed-collection demand) is out of WP-4C-2's scope | **never assessed.** EP-03 gathered no evidence; this report gathers none either. **Recorded as unassessed, not as excluded** |
| **A-5** | that no **Election-side** reaction is required | on `Dismissed` the existing reaction short-circuits *"because Election is silent"*. Whether Election is likewise silent on a declared failure is unverified |

## 5. Verdict

> **WP-4C-2 IS NOT READY FOR AN AUTHORIZATION PROPOSAL, and engineering does not produce one here.**

The brief's Step 3 asks for scope, RED plan, verification strategy and acceptance criteria. **Each of those requires knowing what disposition the Challenge takes — which is §15.3, open, and Contestation's to decide.** A RED plan written now would encode a disposition engineering invented; **that is the failure mode the whole programme has been built to avoid, and it would be worse here than elsewhere because a test that encodes an unmade decision makes it look decided.**

**Two things additionally block it, independent of §15.3:**
1. **D4 is held**, so nothing publishes the event a consumer would consume (§3).
2. **A-0 changes the answer, not merely its wording** — and it is upstream of every other choice, including A-1. *(An earlier draft of this line named A-1 as the upstream question; corrected in place when A-0 was recorded — ownership precedes terminality, and leaving the old ordering would have contradicted §4.)*

## 6. What engineering recommends — one act, not a package

**Route §15.3 to Contestation for a disposition decision**, with this report as its evidence. **The blocking question is small and specific:**

> **1. When an adjudication concludes that no ruling can issue, WHO OWNS THE NEXT MOVE** — Contestation, Election, Collection-side, or a human authority?
> **2. Given that owner, what becomes of the challenge, and is the disposition terminal or re-routable?**
>
> **In that order. Cross-context workflow state follows responsibility ownership** — naming a state before naming the owner would encode a responsibility nobody accepted, and the state would then have to be re-decided when the owner was. **A WP-4C discovery, not a promoted principle.**

**Once BOTH are answered, WP-4C-2 becomes plannable in one pass**, because everything else is already in place: the event exists, the hydrator is registered, the translator pattern is established, and the reaction's shape is precedented.

**Not recommended:** authorizing WP-4C-2 now, or writing a RED plan against an assumed disposition.

## 7. Traceability

**R-88** (subdivision · Contestation ownership) · **R-89** · R-87 · R-80 (consideration ≠ authorization) · EPIC-004K **§10 · §15.3** · ADR-T20 (legal finality) · Q-2 (horizon policy, A-3's neighbour) · `ChallengeState.php` · `Challenge.php:105` · `ChallengeAdjudicationReaction.php` · `Application/Inbox/ChallengeReactionOutcomeTranslator.php` · EP-03 `2026-08-04-wp4c-engineering-readiness-review.md` · plan `20260804-1900-wp4c1-adjudicationfailuredeclared-plan.md` §6/E2.


---

## 8. ARB review record (2026-08-04, appended — the report above is unmodified)

| Disposition | Ruling |
|---|---|
| The discovery report | **APPROVED** — evidence-only discipline held; boundary kept (§15.3 not answered); adjacent finding (AdjudicationExpired, no consumer) surfaced without assuming a shared answer |
| Implementation (aggregate change · RED tests · consumer · handler) | ⛔ **NOT AUTHORIZED** |
| Next step | **a Contestation MODELING SESSION with one objective:** *what is the ubiquitous language for "no ruling could be issued"?* Only after that answer exists is implementation authorized |

**One question the ARB adds — to be answered BEFORE choosing the aggregate state:**

> **Who owns the unresolved responsibility now?** Challenge → adjudication attempted → no ruling possible → *whose move is it?* (candidates, non-binding: Contestation · Election · Collection · human authority)
>
> **In DDD, state follows responsibility — never the other way around.** The disposition state (`Lapsed`? something new?) is a CONSEQUENCE of the ownership answer, not a choice made first.

**The review's core recognition, kept verbatim:** *"A RED plan written now would encode a disposition engineering invented. TDD validates business decisions; it must not create them."*

---

**✅ A-0 ANSWERED — see `2026-08-04-wp4c2-responsibility-analysis.md` (Phase A, 2026-08-04).** **A-0 was MIS-FRAMED here as a single-owner choice.** The accepted Context Map records **two** responsibilities on the declare-failure path: **Collection** owns the corrective move (**COL-5a**, subsumed into COL-1's Customer–Supplier return channel) and **Contestation** owns the challenge's disposition (§10/§15.3). **Election and human authority carry no identified responsibility.** The blocking question is now narrower: *given that Collection owns the corrective move, does the challenge END or WAIT?*
