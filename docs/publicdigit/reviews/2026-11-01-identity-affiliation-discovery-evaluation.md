# Affiliation discovery at sign-in: bounded evaluation for INV-AFF-1

**Date:** 2026-11-01 · **Author:** Claude (research; authority: generated)
**Status:** RESEARCH, for review. It decides nothing.
- **It does not establish that the proposed approach (L2) is feasible.**
- No product code, configuration or database was touched.

**Commission:** Principal decision of 2026-11-01, "bounded discovery evaluation: YES — research model and documentation only", covering items 1–8 below.
**Model:** `docs/publicdigit/membership/architecture/models/g15_affiliation_discovery.py` (SHA-256 prefix `0646a654269727ae`). Run with `python3 -I …`: print-only, standard library, seeded, about 2 s.
**Placement:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0).
**Baseline:** branch `election-flow-updated`, HEAD `576934d29`.

**Labels:**
- **[F]** fact from current source;
- **[M]** output of the model under its stated assumptions;
- **[A]** assumption;
- **[I]** inference;
- **[R]** recommendation;
- **[O]** open question;
- **[U]** unknown.

**No number from the model is a production rate.**

---

## 0. Summary

1. **L2 (references computed by Identity, nothing stored, ask every organisation at sign-in) meets INV-AFF-1 in the identity model.** [M]
   - It stores no central organisation facts in any of the 325 enumerated import / register / claim sequences.
   - It discovers the claimed organisations correctly in all of them.
   - Merges cause no organisation-database writes.
2. **What decides whether L2 is viable is operational scale, and that is [U]:**
   - the number of organisations;
   - whether their databases fail independently or together;
   - peak sign-in volume, including election days (approved B10).
3. **With independent failures, "automatic entry only when discovery is complete" degrades quickly as the number of organisations grows** [M, A]:
   - at 99.9 % availability per organisation, 9.5 % of sign-ins cannot enter automatically with 100 organisations, and 63 % with 1,000;
   - if all organisation databases share one cluster, the degraded share falls to the cluster's outage rate.
4. **The cost of rotating L2's secret derivation key is the main new security cost** [M, A]:
   - either every stored reference in every organisation database is rewritten (130,000 rewrites for an assumed 100,000 persons);
   - or old keys are kept, and remain able to match old references.
5. **A finding independent of the mechanism** [M]: under XD-3b as written, **every claim made after registration triggers a person merge**. Merges would be routine, so the merge process must distinguish claim-binding from duplicate resolution.
6. **The current code already persists cross-organisation affiliation information** in caches, sessions and logs [F]. INV-AFF-1 needs explicit rules for these.

**Assessment:**
- L2 is **plausible for a small number of organisations, or for organisation databases that share one failure domain**.
- It is **not supported** for many independently failing organisation databases.
- **Feasibility therefore remains open** until the [U] items are known.

---

## 1. Designs compared

| Design | How a signed-in person's organisations are found | What is stored centrally |
|---|---|---|
| **L1** | one lookup in Identity's stored mapping `person_id → (organisation, random PersonRef)` | the mapping: a cross-organisation index, minimised under C-4 |
| **L1-SH** | as L1, but one **shared** reference in every organisation (rejected option A; baseline only) | the mapping plus a correlatable shared reference |
| **L2** | for every organisation X, compute `ref_X = HMAC(K_derive, person_id ‖ X)`, send a signed assertion, and ask X whether it holds `ref_X`; use the answers and keep nothing | the bare Person, an alias list after merges (names no organisation), the key |
| **L3** | no discovery; the person enters through an organisation's own address or invitation | nothing; **requires amending approved B1.3 / B1.4** |

**Why L2 must compute references:**
- If references were random and stored only in organisation databases, Identity could not produce `ref_X` at sign-in without a stored mapping, which would be L1.
- Computing references is the only way to discover without storing [I].

---

## 2. Results by commissioned item

### Item 1: query cost, concurrency, peak volume (D1)

**Assumptions [A]:** median lookup 10 ms; lognormal spread σ = 0.8; 0.1 % of organisations unavailable; 1 s timeout; 32 parallel lookups per sign-in; budget of 200 concurrently open organisation-database connections.

| Organisations N | sign-in latency p50 / p95 | connection-seconds per sign-in | max sign-ins/s at the budget |
|---|---|---|---|
| 10 | 0.03 s / 0.08 s | 0.15 | ≈ 1,300 |
| 100 | 0.09 s / **1.0 s** | 1.5 | ≈ 135 |
| 1,000 | 1.1 s / 1.4 s | 14.7 | ≈ 14 |
| 5,000 | 2.9 s / 3.3 s | 73.8 | ≈ 3 |

**Reading [M]:**
- **Cost grows linearly with N, and a single slow or unavailable organisation pushes the whole sign-in to the timeout.** That is why the p95 for N = 100 equals the 1 s timeout.
- For comparison, L1 needs one central lookup plus one check per organisation the person actually belongs to.

**Unknown [U]:** the number of organisations and peak sign-ins, including election-day surges, are not known (multi-database decision D12).

**Approved B10** (election-day direct entry: "determine all elections active today" at sign-in) **needs cross-organisation discovery as well.** Under L2 that is another fan-out [I].

**Precision:** D1 shares come from 400 trials per N, roughly ±2.5 percentage points. An independent check with 4,000 trials at N = 1,000 gave 0.62–0.64 against the closed form 0.632. D2 below is the reference.

### Item 2: partial responses, timeouts, outages (D2)

A person with **k = 2** real affiliations, among N organisations each answering with probability a.

| | a = 99.99 % | a = 99.9 % | a = 99 % |
|---|---|---|---|
| **Complete-only policy, share of sign-ins that cannot enter automatically:** | | | |
| N = 100 | 1.0 % | 9.5 % | 63 % |
| N = 1,000 | 9.5 % | 63 % | ≈ 100 % |
| N = 5,000 | 39 % | 99 % | ≈ 100 % |
| **"Enter on one yes" policy, share of wrong automatic entries** (breaks approved B1.4), any N | 0.02 % | 0.2 % | 2.0 % |

**Reading:**
- "Enter on one yes" violates "never guess" at a rate equal to the chance of losing one of the person's own organisations.
- "Complete only" never guesses, but under independent failures it degrades as N grows.
- **With a single shared database cluster, failures are all-or-nothing**: the degraded share is the cluster's outage rate, and wrong entries are zero.
- So the physical topology (many independent servers vs one cluster) decides whether L2's completeness rule is workable [I].
- The topology is **[U]** and belongs to the multi-database design.

**Behaviour when discovery is incomplete [R]:** show the organisations found plus a notice that some could not be checked. Never enter automatically.

### Item 3: narrowing without a central index (D3)

**Remembered Working Organisation (approved B1.5):** if a share r of sign-ins has a still-valid remembered choice, expected lookups are r·1 + (1−r)·N [M]. For N = 1,000: r = 0.8 → 201; r = 0.95 → 51.

- This is legitimate **only if B1.5 takes precedence over B1.3's counting.** That reading is **[O]**, a PO question.
- Where the remembered choice lives is B1.5, a separate decision. If it is kept centrally, it is one stored affiliation per person.

**Device hint** (organisations remembered on the person's device):
- It narrows the search, but it **cannot** prove "exactly one organisation", so it never authorises automatic entry under B1.4 [I].
- At most it orders the queries.

**Rejected [R]:** organisations publishing a compact summary of their references (e.g. a Bloom filter) for the platform to test. That rebuilds a central index, with errors.

### Item 4: logs, traces, caches, sessions (analysis)

Current sources that **already persist** cross-organisation affiliation information [F]:

| Source | Evidence | What it persists |
|---|---|---|
| per-user role cache | `app/Models/User.php:1080-1086` (`user_{id}_dashboard_roles`, 3,600 s), built from `user_organisation_roles` with **no organisation filter** (`:1089-1093`) | roles across all organisations, per person |
| database sessions | `config/session.php:21` (default `database`); `session(['current_organisation_id' => …])` in `app/Http/Controllers/OrganisationController.php:117,429` | current organisation per session |
| routing logs | `app/Services/DashboardResolver.php` (62 log calls, several with `user_id` and organisation routing, e.g. `:131-179`) | which organisations a user was routed to |
| access logs | `app/Http/Middleware/EnsureOrganisationMember.php:125-131`; `app/Http/Middleware/CheckUserRole.php` (logs `user_roles`) | user ↔ organisation attempts and roles |
| central pointer | `users.organisation_id` (`database/migrations/2026_03_05_000002_create_uuid_users_table.php:13`) | one organisation per person |

**Requirements for INV-AFF-1 [R]:**
- Discovery results are never written to logs, traces, metrics labels, error reports, queues or shared caches.
- At most a per-request, in-memory value.
- Per-organisation access logs stay in that organisation's context.
- A static check (a fitness test) to enforce this would be a later, separately authorised item.

### Item 5: signed, short-lived, organisation-specific assertions (D5)

Six cases against four verifier rules [M, exhaustive]:

| Verifier | Bad cases it accepts |
|---|---|
| "do we hold this reference?" only | all five: replay to another organisation, expired, bare reference, same-organisation replay, retired key |
| signature only | replay to another organisation, expired, same-organisation replay |
| signature + audience + expiry | same-organisation replay |
| **signature + audience + expiry + nonce + current key set** | **none** |

**Minimum assertion [R]:**
- issuer, audience (that organisation), subject (that organisation's `PersonRef`), expiry of seconds to minutes, nonce;
- signed with a key from a published key set.
- The **signing** key rotates freely.

This confirms INV-AFF-1's boundary: a `PersonRef` alone proves nothing.

### Item 6: imported persons, voter claims, registration (D6)

Enumeration of all 325 ordered subsets of {import by A, import by B, register, claim A, claim B} [M, exhaustive]:

| Measure | L1 | L1-SH | **L2** | L3 |
|---|---|---|---|---|
| sequences with organisation-database writes caused by merges | 0 | **100** | 0 | 0 |
| sequences with persisted central rows naming an organisation | 310 | 310 | **0** | 0 |
| sequences where sign-in discovery is wrong | 0 | 0 | **0** | **100** (no discovery) |
| maximum lookups per organisation at sign-in (1 + aliases) | 1 | 1 | **3** | — |
| fully claimed sequences left with more than one live Person | 0 | 0 | 0 | 0 |

**Readings:**
- **Every successful claim triggers a merge in every design** (100 of 100 sequences with a claim).
  - Cause: XD-3b, where registration without a claim creates the account's own Person.
  - **Merges are therefore routine, not exceptional** [M].
  - The merge process (BR-11) must separate:
    - **claim-binding**: the account presents the organisation's one-time code; strong evidence, and the normal path;
    - **duplicate merge**: two imported Persons; needs review, with special care for election records.
  - **Alternative [R]:** registration started *from* a claim code binds the account to the imported Person directly and creates no second Person. This is a later design question.
- **L2's cost of avoiding a stored index:** after merges, a sign-in derives one reference per earlier `person_id`, multiplying the fan-out by 1 + the number of aliases (up to 3 here).
- **L1-SH confirms why the shared reference was rejected.** Merges force organisation-database rewrites. With the model's merge direction (code → account), up to 2. With the best direction policy, at least 1 once two organisations are involved [I].

### Item 7: merges, reference stability, key rotation (D6, D7)

- **Merges:** L1 re-points central rows; L2 appends to an alias list. Neither writes to organisation databases [M].
- **Reference stability under L2:** a reference stays valid as long as the key **and** the original `person_id` stay resolvable through the alias list.
- **Derivation-key rotation under L2** [M, A: 100,000 persons × 1.3 records]:
  - **either** 130,000 rewrites across all organisation databases;
  - **or** a key version stored with each reference and old keys kept, which then **remain able to match old references across organisations for as long as those references exist**.
- **L1 has no derivation key.** Its equivalent risk is the stored mapping table itself.
- **Key compromise:** under L2, anyone holding `K_derive` and a list of `person_id`s can compute every organisation's references and match them across organisations [I]. The key needs hardware or secret-manager protection, with access separated from database operators.

### Item 8: security, data protection, operational failure modes (analysis)

| Failure mode | L1 | L2 | Mitigation [R] |
|---|---|---|---|
| central store leak | mapping = affiliation register | bare Persons + aliases; no organisations | — |
| key leak | — | full cross-organisation matching | secret manager, key separation, key version |
| one organisation's database slow or unavailable | affects only its own members | affects **every** sign-in (timeout) | timeouts, the complete-only rule, a notice |
| logs, caches, sessions | must not copy the mapping | must not record discovery | item 4 rules |
| operator access | technically possible to all organisation databases in a shared application | same | per-organisation credentials (multi-database D6), audit |
| wrong voter link | claim-binding errors | same | one-time codes; review for duplicate merges; Election checks eligibility separately (BR-7) |
| data-protection basis for any central Person of an unregistered person | [U] | [U] | data-protection review (OQ-3a-1) |

---

## 3. Comparison

| Criterion | L1 | L2 | L3 |
|---|---|---|---|
| INV-AFF-1 (no persisted register) | ✗ | ✓ [M] | ✓ |
| approved B1.3 / B1.4 | ✓ | ✓ only when discovery is complete | ✗ (amendment needed) |
| sign-in cost | O(1 + k) | O(N × (1 + aliases)) | O(1) |
| resilience to one organisation's outage | high | low with independent failures | high |
| key-rotation burden | none | high | none |
| K4 (Person at import, no organisation recorded) | not met | met | met |

## 4. Recommendations [R]

1. **Do not declare L2 feasible yet.** First obtain:
   - the number of organisations, expected over 1–3 years;
   - the planned physical topology (one cluster vs independent servers);
   - peak and election-day sign-in volume.
   These come from multi-database D12 and the infrastructure design.
2. **Keep INV-AFF-1 as the target principle.** If L2 turns out infeasible at the target scale, decide the fallback explicitly (L1 under C-4, or L3 with a B1 amendment). Do not drift into an implicit index.
3. **Decide separately:**
   - whether B1.5 (remembered choice) takes precedence over B1.3 (counting);
   - where the remembered choice is kept.
4. **Treat claim-binding and duplicate merges as different processes**, and consider registration from a claim code (item 6).
5. **Record the item 4 persistence sources as findings** for later, separately authorised work. They contradict INV-AFF-1 today, whichever mechanism is chosen.

## 5. Open questions

| # | Question | Owner |
|---|---|---|
| OQ-E-1 | Number of organisations, physical topology, peak and election-day sign-in volume | PO + Ops |
| OQ-E-2 | Does a remembered Working Organisation (B1.5) take precedence over counting (B1.3)? | PO |
| OQ-E-3 | Acceptable sign-in latency and degraded-sign-in share | PO |
| OQ-E-4 | Key custody and rotation policy for `K_derive` (if L2) | Security + ARB |
| OQ-E-5 | Registration from a claim code, and the claim-binding vs duplicate-merge process | ARB + PO |

## 6. Limits of the model

- Latency, availability and population figures are assumptions. Failures are modelled either fully independent or fully shared; reality lies in between.
- D6 covers one human, two organisations and each event at most once.
- No real protocol, cryptography or database was exercised.
- D4 and D8 are analysis, not simulation.
- The L1-SH write count depends on the modelled merge direction.

## 7. Traceability

- Principal decisions of 2026-11-01 (INV-AFF-1 "yes, with boundaries"; evaluation "yes").
- `2026-11-01-organisation-inv-aff-1-affiliation-ownership-decision-record.md`.
- XD-3a, XD-3b, Decision C records.
- `docs/publicdigit/backlog/PBDIGIT-30-active-organisation-business-lifecycle-discovery.md` (B1, B10).
- `docs/publicdigit/brainstorming/multi-database-system/15-open-questions-and-decisions.md` (D1, D6, D12).
- Model g15 (path above); earlier models g7, g9–g14 in the same folder.
