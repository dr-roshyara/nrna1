# Cross-context strategic baseline closure: Principal decision dossier

| | |
|---|---|
| **Status** | **Decision dossier.** Records no ruling; changes no decision record, baseline or code; creates no backlog item. Written by Claude, **not independent**. |
| **Commission** | "Cross-Context Strategic Baseline Closure and Principal Decision Preparation", Principal, 2026-11-10 (the Principal DDD Architect's note, accepted by the Principal). |
| **Sources read (current repository text)** | the Election brief, addendum 1 and its reconciliation note; `./2026-11-10-election-model-strategic-validation-report.md` (current version); `./2026-11-10-people-register-p2-p7-decision-brief.md`; `./2026-11-10-ir-opr-01-independent-review.md` (F-4, F-5, F-7, F-8); decision record D-13…D-122; Organisation baseline §3; `docs/publicdigit/reviews/2026-10-08-membership-architecture-ratified.md` §8; CR-01 (`2026-11-09-membership-change-request-cr-01-…`, C0–C4); `docs/publicdigit/architecture/context-map.md` (Finance rows); tactical model §15–§17 and §22 (as evidence of proposals only); Round 29 VO-1 |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0) |

**Labels:** **FACT** · **RAT** · **WD** · **APP** · **P** · **REC** · **UNKNOWN** · **PRINCIPAL**.

## 0. Disagreements and additions (before the dossier)
1. **P4: I agree with the challenge and withdraw my whole-electorate block.** A narrower rule is safe (§B, P4).
   - IR-OPR-01 F-7 already offered the basis: "a 'duplicate pending local merge' state that Membership and Election must treat as one".
   - My validation report (§9) overlooked it.
2. **E-3′: the electoral division should not attach to the person.** The note asks for a relationship "between a person and an electoral division". It should attach to **the organisation's own record of the person** (its register entry, keyed by its `PersonRef`).
   - Not to the canonical Person (Identity's; D-111 isolation).
   - Not to Membership.
   - Not to the voter relationship: membership-mode organisations have no voter relationship but may need divisions.
3. **P7 is an Election-integrity question, not only a People Register one** (the note calls it "not Election-specific"). Silent e-mail linking attaches an organisation's record to an account **without a claim**, which D-113 forbids. A voting right could then reach the wrong human. My earlier P2–P7 brief said the register "does not depend on it technically"; that understated the risk.
4. **E-5 has a layer the note does not list:** secrecy against **coercion or observation while voting remotely** (a phone in someone else's hands). No electorate size addresses it. It matters directly for the phone and installable-app direction (§F).
5. **A gap neither report covered (G-1).** The ratified Membership predicates for Election are `IsMember`, `Suspended`, `CurrentType` and tenure (ratified §8). Fees reference periods, and settlement is Finance's (RAT outline). **The FeePaid direction between Finance and Membership is unregistered** (context map; act N-5 is "a reading, not a decision"). A common party rule, "members in arrears may not vote", therefore has **no contract that answers it at T**.

Otherwise I agree with the note, including its sequence: closure of decisions, then consolidation, then tactical design.

## 1. Integrated ownership view (strategic, no new contexts)
| Fact | Owner (changes it) | Consumers | Crosses by | Effective time / knowledge time | If unavailable or disputed |
|---|---|---|---|---|---|
| Person, Account, claim, `PersonRef` issue and binding | Identity (RAT XD-3; D-111, D-113) | Organisation (by its refs only) | claim; merge notice (C-5) | event time / Identity's record time | no binding without valid claim; disputes stay in Identity |
| Organisation's register entry (the person as the organisation knows them) | Organisation (D-101) | Membership, Election (by `PersonRef`) | `PersonRef` | recorded / τ | not a voter, not a member by itself |
| Affiliation incl. standing voter relationship | Organisation (D-109) | Election | "voter relationships as known at τ" (T-8, P) | effective period / τ | absent = not a voter (V-1) |
| Unit, unit belonging | Organisation (D-79, D-114, D-118…D-122) | Election, Deliberation, Communication | R-5 `EffectiveMembersAsAt(scope, asOf, τ)` (WD D-32) | effective period / τ | restructuring pending: D-41/D-43 |
| **Electoral division assignment** (proposed, E-3′) | Organisation | **Election only** | as voter relationship at τ (P) | effective period / τ | absent = no regional race from divisions |
| Organ, position, rules, decisions | Governance (D-38, D-97) | Election (R-6a, R-6b) | `ElectionRulesAsAt` | rule version in force / τ | competence gap: D-65 |
| Membership facts and coverage | Membership (RAT) | Organisation (C1, C2), Governance (C3), Election (C4) | CR-01 | asOf / τ (D-73) | Election never reconstructs |
| Fee settlement | Finance (RAT outline) | **UNKNOWN for Election (G-1)** | not registered | — | — |
| Workspace access | Organisation (D-104) | Authorization (enforces) | grant | effective period | no access implied by anything else |
| Electorate, eligibility, entitlement, post districts, candidacy decisions | Election (RAT) | Voting (ballot style only) | snapshot; blind credential (§22, P) | cut-off T; τ = T | fixed facts change only via Contestation (D-71) |
| Ballot, consumed credential | Voting (VO-1) | Election (totals only) | after close | — | no identity ever |

**Distinctions confirmed** (each pair is two facts with two owners or meanings):
- Person / Account;
- `PersonRef` / Person;
- Unit / Organ;
- Unit belonging / electoral division;
- Membership / standing voter relationship;
- standing voter relationship / electorate;
- voter criteria / candidacy criteria (both R-6a, separate sets);
- authority (Governance) / workspace access (D-104);
- entitlement (Election) / ballot enforcement (Voting).

## Part A: Executive conclusion
- **Established:**
  - ownership is coherent across Identity, Organisation, Membership, Governance, Election and Voting;
  - E-1 and E-2 follow from existing boundaries;
  - temporal rules (D-41…D-43, CR-01 τ) apply unchanged;
  - no new bounded context is needed.
- **Changed by this review:**
  - P4 narrowed to the affected person (no whole-electorate block);
  - E-3′ attaches the division to the organisation's record;
  - E-4′ defines "overlap" as double representation in the same organ;
  - E-5′ separates the constitutional requirement from the honesty rule, with no committee waiver;
  - P7 reclassified as an election-integrity precondition;
  - gap G-1 (dues-based eligibility) added.
- **Uncertain:**
  - NRNA's constitution (whether divisions are needed; candidacy and arrears rules);
  - real district sizes;
  - NRNA's list practice.
- **Strategic discovery:** complete. **No further discovery round is needed.** What remains is decisions.
- **Before tactical DDD:** the rulings in Part B, then consolidation. An independent review before freezing is advisable (all Election material is Claude-written).

## Part B: Decision register

### E-1. Can a central account field decide regional voting rights?
- **Why:** one organisation's import (`ImportUsers.php:737`) can change another organisation's ballots; nothing records the basis.
- **Facts:** C-1…C-11 (brief §2, FACT); D-80, D-101, D-111 (WD).
- **Options:** keep `users.region` | **reject it as authority**.
- **REC:** reject. **Consequence:** regional scope comes only from organisation facts read by Election. **Depends on:** none. **Status:** PRINCIPAL.

### E-2. Is regional entitlement fixed once, at the cut-off, and used everywhere?
- **Why:** today display and cast compute separately and live (C-7).
- **Facts:** D-41…D-43 (WD); C4 τ (RAT); credential per ballot style (§22, P).
- **Clarification asked for by the note:**
  - **effective time** = the date the belonging or membership is in force;
  - **knowledge time τ** = what the owning context had recorded by T;
  - Election reads both "as at T, known at T".
  - Display, preview, credential issuance and final-cast validation all use **the one snapshot decision**; Voting validates only the issued style.
- **Options:** live recomputation | **one fixed decision**.
- **REC:** accept. **Depends on:** E-1. **Status:** PRINCIPAL.

### E-3′. May an organisation keep electoral divisions that are not units?
- **Why:** forcing districts into units gives unintended forums, audiences and allocation authority (§23, D-57…, D-115…D-119).
- **Facts:** unit = place where people belong (baseline §3); D-82 areas never by themselves determine belonging or entitlement; D-118 one geographic belonging.
- **Validation of the note's seven questions:**
  1. **Separate concept:** yes. It is orthogonal to belonging, with negative invariants: no organ, forum, audience or allocation authority.
  2. **Attached to:** the organisation's register entry (its `PersonRef`). That covers both modes and keeps D-111 isolation.
  3. **Organisation-specific:** yes. The organisation defines its divisions (by a decision of its competent body) and their periods.
  4. **Temporal:** yes. Effective period and τ, as for belonging.
  5. **Both modes:** yes.
  6. **Certified roll:** **evidence for Election, not an authority.** The authority is whoever the written rules name (Governance).
  7. **Justified without NRNA's constitution:** the generic platform needs it wherever districts do not match structure. Necessity for any **specific** organisation is evidence-dependent.
- **Assignment discipline:** by an individual act, an authorised import, or an adopted residence rule (D-79). Never by an address or a D-82 area alone.
- **Additional default needed (PRINCIPAL):** **one division per division scheme per date**, unless the rules say otherwise. This parallels D-118. Without it, E-4 overlaps multiply.
- **Options:**
  - force into units (rejected: false facts);
  - Election assigns per election (rejected: Election would own an organisational fact);
  - **E-3′ in principle, activated when an organisation's rules need it**;
  - defer until evidence.
- **REC:** accept in principle with the qualifications above.
- **Consequence:** one new Organisation concept; no ownership change.
- **Depends on:** E-2.
- **Status:** PRINCIPAL.

### E-4′. What does an unresolved overlap hold, and who resolves it?
- **Definition (P):** a **genuine** overlap exists only when a voter qualifies for two or more races that **fill seats of the same organ through alternative constituencies**, and the rules don't say whether that is allowed. Posts carry `positionRef` (R-6a), so the organ is known.
  - Nested districts are **not** overlaps (D-114 nesting).
  - Races for different organs (a wing committee and a district committee) are **not** overlaps.
- **Who resolves:** the authority the written rules name for election rules (Governance, DD-11); the election committee only if the rules give it that power; if competence is undeterminable, D-65.
- **Options:** hold the whole election | hold the affected voter | **hold the affected races**.
- **REC:** hold the affected races. Others proceed if the election committee decides. No default entitlement is invented. Consistent with D-118 and D-122.
- **Depends on:** E-2, E-3′ (the division default). **Status:** PRINCIPAL.

### E-5′. What happens when a race cannot honestly be secret?
**Five layers:**

| Layer | Covered by |
|---|---|
| stored-data unlinkability | VO-1 (RAT), Level A (D-45) |
| secrecy while voting (observation, coercion, remote device) | **not covered**; Level B is later (D-45); remote voting can't prevent observation |
| inference from published results | D-48 publishes every race; breakdown minima only |
| claims PublicDigit makes about secrecy | D-45 spirit (never present weaker as stronger) |
| the organisation's constitutional requirement | its written rules (D-97) |

**Answers to the note's questions:**
1. **Authority to permit limited secrecy:** only the written rules can. Where they allow non-secret or limited-secrecy procedures, the body they name decides.
2. **The election committee:** only if the rules give it that power. **It cannot waive a constitutional secret-ballot requirement** (D-38: powers are in the rules).
3. **The constitution requires secrecy and the race is too small:** that conflict is the organisation's to resolve under its rules (merge districts, change procedure). **PublicDigit does not judge constitutional validity.** It records the decision and its basis, and Contestation decides disputes (D-71).
4. **Is a minimum sufficient?** No. It is a **risk control, not a guarantee**: unanimity or outside knowledge reveals choices at any size.
5. **No minimum set:** PublicDigit makes **no claim of protection against inference from results** for that race. Stored-data anonymity is still stated.
6. **Can it be called secret?** Only as far as each layer holds. The description is per race and per layer.
7. **Strategic versus later:** the invariant below is strategic; the threshold and suppression mechanics are organisation policy or tactical.

**REC, the minimal invariant (E-5′):**
> "PublicDigit describes each race's secrecy only as far as it actually holds, layer by layer, and never as more. A race whose electorate is below the organisation's minimum is opened only on a recorded decision of the authority the written rules name, citing its basis; PublicDigit never treats an operational decision as overriding a written secret-ballot requirement."

**Rejects:** a committee waiver (the earlier wording); a platform number; silence.
**Depends on:** none. **Status:** PRINCIPAL.

### E-6′. Does regional candidacy depend on district belonging?
- **Facts:** Governance owns candidacy criteria; Election applies them (tactical model §15–§17, R-6a, P; the ratified Membership text says eligibility is Election's). Candidacy and voter criteria are separate sets.
- **REC:**
  - only where the rules say so;
  - tested at the moment the rule names, by default the nomination cut-off;
  - a later change is reported to the election committee, which decides under the rules;
  - after the ballot is fixed, a change goes to Contestation (D-71);
  - no automatic removal;
  - no platform limit on standing in several districts (the rules decide, and the committee is warned).
- **Depends on:** E-3′ (divisions as evidence). **Status:** PRINCIPAL.

### P2. May an organisation archive someone with active relationships or access?
- **Distinct acts:**
  - archiving a register entry (Organisation);
  - ending an affiliation (Organisation, with basis);
  - withdrawing workspace access (D-104);
  - ending Membership (**only Membership's act**).
- **Options:** (i) cascade; **(ii) only when nothing is active**; (iii) flag only.
- **REC:** (ii).
  - **Active Membership blocks archiving**, because Organisation cannot end it (CR-01: Organisation never interprets Membership).
  - New relationships on an archived entry are refused.
  - Unarchiving is allowed, forward-only.
  - Erasure waits for XD-PROV (UNKNOWN).
- **Rejects:** (i) records ends nobody decided (IR-OPR-01 F-2, F-4).
- **Status:** PRINCIPAL.

### P3. May relationships of the same capacity overlap?
- **REC:**
  - Worker and Guest may overlap, each with its own description (no employment model);
  - **at most one active standing voter relationship per register entry**;
  - no capacity implies another;
  - the rules may add limits.
- **Rejects:** a blanket "no overlap", which would need a consistency unit nobody needs (F-1).
- **Status:** PRINCIPAL.

### P4. What happens to an election while two of an organisation's records are one human?
- **Facts:**
  - D-113: at most one of an organisation's references bound per Person, so claims cannot create this.
  - Only an **Identity merge of two Persons** can (C-5; IR-OPR-01 F-7). Both references then resolve to one Person, known to the organisation through the merge notice.
- **Alternatives tested:**

| Containment | One person, one vote | Electorate complete | Double vote possible | Explainable | Others proceed |
|---|---|---|---|---|---|
| whole electorate held | yes | yes | no | yes | **no** |
| races containing the person held | yes | yes | no | yes | **no** in practice: national races contain everyone |
| **the pair treated as one person** (at most one right across both entries); **that person's right held only if the two entries give different races** | **yes:** rights are issued to the signed-in Person, and both entries resolve to that Person | yes: the human is in the electorate once | **no** | yes: the snapshot records the open case | **yes** |

- **REC: the third.** The organisation decides on its records under its rules: merge, or contest Identity's merge in Identity.
  - **Residual risk:** two *unbound* references for one human cannot be detected under any option (D-113), and the electorate count may include them.
  - **Rejects:** a whole-election block, which my previous report proposed without proving it necessary.
- **Depends on:** D-113.
- **Status:** PRINCIPAL. The organisation's authority to merge its own records is part of this ruling (F-8).

### P5. Do election-only organisations keep a standing voter relationship?
- **REC:** yes.
  - It is an affiliation (D-109), not Membership.
  - It **never implies entitlement** (V-1); each election decides its electorate.
  - It does **not** carry the division: that sits on the register entry (E-3′), so both modes are alike.
  - Election may use a certified roll instead, as evidence.
- **Evidence still useful:** NRNA's list practice.
- **Status:** PRINCIPAL.

### P6. What may an import guarantee?
- **Five states, never merged:**
  1. register entry recorded;
  2. `PersonRef` issued (Identity);
  3. claim completed (Identity, D-113);
  4. voter relationship confirmed;
  5. entitlement in an election (Election at T).
- **REC: R-b.**
  - Rows show "person recorded, voter relationship pending";
  - **pending is never a voter**, and the committee sees the count before fixing;
  - row key: the organisation's own stable identifier, otherwise file plus row as **an occurrence only**;
  - re-import applies corrections with the import as basis;
  - omission never archives (F-5).
- **Status:** PRINCIPAL.

### P7. May the new register go live while the legacy import links accounts by e-mail?
- **Why (stronger than before):**
  - silent linking binds a record to an account without a claim, contrary to D-113;
  - it discloses whether accounts exist (PBDIGIT-341);
  - **in elections, a right may then be issued to the wrong human.**
- **REC: no.** PBDIGIT-341 containment first (no silent linking, no existence disclosure). The new capability goes live only after it.
- **Status:** PRINCIPAL. Authorising the containment work is a separate act.

### G-1 (new). Which context answers "fees paid at T" for voter eligibility?
- **Why:** "no vote while in arrears" is a common party rule. Today no contract answers it.
- **Facts:**
  - C4 predicates do not include fees (ratified §8);
  - Finance owns settlement (RAT outline);
  - the FeePaid direction is unregistered (act N-5).
- **Options:**
  - **(a)** arrears matter only through Membership acts (suspension or lapse decided under the rules); Election asks C4 only;
  - **(b)** Election asks Finance for a settlement fact for a period, at τ, and applies the rule;
  - **(c)** defer until an organisation needs it.
- **REC:**
  - **(a) where the rules make arrears change membership status;**
  - **(b) where the rules restrict voting but leave membership intact**. Both keep each fact with its owner.
  - (b) is a **new Finance → Election contract** (contract change).
  - Deciding the FeePaid direction (Finance and Membership) is a separate ARB act and is not needed for this.
- **Status:** PRINCIPAL. "Defer" is acceptable if NRNA has no such rule (UNKNOWN).

## Part C: Decision order
1. **P7** (independent; unblocks the claim and installable-app track).
2. **E-1 → E-2** (foundation).
3. **E-3′** (with its one-division-per-scheme default) → **E-4′** (uses that definition).
4. **E-5′** (independent).
5. **E-6′** (after E-3′).
6. **P2 → P3 → P4** (P4 uses P2's archive semantics for merged entries) → **P5 → P6**.
7. **G-1** (independent; may be deferred).

## Part D: Strategic impact
| Decision | Impact |
|---|---|
| E-1, E-2 | confirms existing boundaries; adds the "one decision used everywhere" invariant |
| E-3′ | **adds a domain concept** (Organisation); no ownership change; needs per-organisation evidence |
| E-4′ | refines a rule; adds a definition |
| E-5′ | **adds an invariant** (honest secrecy per layer; no operational override of written rules) |
| E-6′ | confirms the R-6a boundary; adds timing rule |
| P2, P3 | Organisation invariants |
| P4 | adds an invariant (a known duplicate pair is one voter) |
| P5, P6 | confirm boundaries; name states |
| P7 | no model change; an operational precondition |
| G-1 | **contract change** (option b) or none (a) |
| Wait for tactical design | snapshot structure; how a held race is opened; numbers for E-5; C4 wiring (conformance, `PBDIGIT-318`); migration of `region`/`state_name` |

## Part E: Baseline closure verdict
**READY WITH EXPLICIT OPEN ORGANISATION-SPECIFIC QUESTIONS, once the Part B rulings are made.** Until then it is not ready, but only because decisions are pending; no further discovery is needed.

| Criterion | Met? |
|---|---|
| 1 context responsibilities | yes |
| 2 fact ownership | yes, except G-1 |
| 3 authority to change | yes (Governance rules; D-65 for gaps) |
| 4 ubiquitous language | yes, with "electoral division" added by E-3′ |
| 5 contracts | yes, except G-1 (b) and T-8 (voter relationships at τ, P) |
| 6 temporal semantics | yes (D-41…D-43, CR-01) |
| 7 integrity and anonymity invariants | after E-5′ and P4 |
| 8 behaviour under ambiguity | after E-4′, P4, P6 |
| 9 no contradictions | none found between accepted decisions and working directions in this scope |
| 10 no duplicated concepts | yes; E-3′ passes the integrity test |

**The generic platform can be complete while NRNA's configuration is unknown**, because every organisation-specific choice routes to "the written rules decide" with a defined behaviour when the rules are silent.

## Integrated scenarios
| # | Scenario | Owner / rule | Time | Outcome | Resolved? |
|---|---|---|---|---|---|
| 1 | one Account, several organisations | Identity; D-110, D-111 | — | one login; separate records, references and data per organisation | yes |
| 2 | organisation knows someone without an account | Organisation; D-113 | — | reference issued, no Person; no voting right until claim | yes |
| 3 | member in A, voter-only in B | Membership (A), Organisation D-109 (B) | each T | A via C4, B via the voter relationship; no crossing (BR-13) | yes |
| 4 | root-unit member votes for a regional post | E-2 / E-3′ | T | only via a division (E-3′) or a lower unit | E-3′ |
| 5 | division differs from unit | E-3′ | T | division decides the regional race; unit decides forums | E-3′ |
| 6 | unit change before T | D-42 | T | new unit counts | yes |
| 7 | after T | D-42 | — | no effect | yes |
| 8 | membership corrected after fixing | C4 at τ = T; D-71 | — | snapshot unchanged; Contestation | yes |
| 9 | Identity merge → duplicate records | P4 | before issuance | one right for the pair | P4 |
| 10 | partial import | P6 | T | pending ≠ voter; count shown | P6 |
| 11 | roll entry not matched | §8 of the report | T | no right; reported to the organisation | yes (P) |
| 12 | overlapping districts, rules silent | E-4′ | before opening | affected races held | E-4′ |
| 13 | race too small for secrecy | E-5′ | before opening | honest per-layer description; opens only under the written rules' authority | E-5′ |
| 14 | candidate's belonging changes | E-6′ | per rule | reported; committee decides | E-6′ |
| 15 | archive with active relationships | P2 | — | refused until ended by each owner | P2 |
| 16 | legacy e-mail linking | P7; D-113 | — | must be contained before the new register goes live | P7 |
| 17 | two organisations' isolation | D-111 | — | no data crossing; each election reads only its organisation | yes |

## F. Mobile and installable app (PWA) consequences
- **The installable membership app (stage 2) gates are now three:**
  - **P7** (no silent linking: a phone user must only ever see records they claimed);
  - the **claim capability** (I-TDD-02 on D-113);
  - responsive foundation (done, PBDIGIT-338).
- **What the membership area can safely show offline:**
  - the member's own unit and division, with "as of" dates;
  - their membership status from Membership;
  - from T onwards, their entitled races.
  - **Nothing from another organisation is mixed in** (D-111).
- **Never cached or offline:** ballots, credentials, voting pages, or anything that reveals whether someone voted (§22 Level A; no "has voted" audiences).
- **E-5′ applies to phones directly.** Remote voting can't prevent observation or coercion. The per-layer honesty rule means the app must not say "your vote is secret" without qualification. It states what PublicDigit guarantees (stored-data unlinkability) and what it cannot (who is watching the screen).
- **Push notifications:** election reminders go to the electorate as a whole, never to "people who haven't voted yet".

## Final briefing
- **Established:** who owns what, across all contexts; the timing rules; no new contexts.
- **Changed:**
  - duplicates block only the person, not the election;
  - divisions attach to the organisation's record;
  - "overlap" is precisely defined;
  - no committee can waive a written secret-ballot requirement;
  - the legacy import is an election risk;
  - the arrears question is new.
- **Uncertain:** NRNA's rules and data.
- **Your decisions:** P7, E-1, E-2, E-3′, E-4′, E-5′, E-6′, P2–P6, G-1, in the Part C order.
- **Can wait:** thresholds, snapshot design, race-reopening mechanics, the C4 wiring fix, data migration.
- **Coherent:** yes.
- **Ready for consolidation:** yes, after the rulings, with NRNA-specific questions explicitly open.
