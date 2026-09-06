# `X₃` SYNTHETIC GENERATOR — SPECIFICATION
## The election carrier for `KR-ALGEBRA-2026-09` Phase 3

**SPECIFICATION ONLY. No code. No data generated. No production table read or written.**
**This is the named blocker from `KR-ALGEBRA-DESIGN-2026-09.md`.**

---

# 1. The risk this specification exists to manage

> **If I choose the constraints, `X₃` becomes a third carrier I designed — and the Phase 2→3 gate
> collapses.** The gate is *"does the algebra work on a carrier we did not design."*

**Therefore: every generator constraint below cites the schema artifact that forces it.** A constraint
with no citation is not admissible into the generator. **Where the schema is genuinely silent, that is
recorded as an open decision (§9) — not filled in by me.**

# 2. Provenance — what was inspected

```
database/migrations/2026_03_05_000004_create_uuid_elections_table.php
                    …000005_create_uuid_posts_table.php
                    …000006_create_uuid_candidacies_table.php
                    …000007_create_uuid_codes_table.php
                    …000009_create_uuid_voter_slugs_table.php
                    …000011_create_uuid_votes_table.php
                    …000012_create_uuid_results_table.php
                    2026_03_08_001300_add_anonymity_and_fraud_detection_to_demo_votes.php
CLAUDE.md — domain model, anonymity rule, two-code system, SELECT_ALL_REQUIRED
```

# 3. Extracted invariants — every one traceable

| id | invariant | source |
|---|---|---|
| **I-ANON** | `votes` has **no `user_id`** | `votes` migration, and its comment `// CRITICAL: NO user_id - votes are completely anonymous` |
| **I-HASH** | `vote_hash` is **unique**, `string(64)` | `votes`: `->unique()`; anonymity migration: `string('vote_hash', 64)` |
| **I-SLOT** | ballot slots `candidate_01 … candidate_60`, all nullable strings | `votes` migration — 60 slot columns |
| **I-NOVOTE** | `no_vote_posts` is JSON, nullable | `votes` migration |
| **I-CAST** | `cast_at` timestamp, **NOT NULL** | `votes` migration |
| **I-REQ** | every post carries `required_number`, **NOT NULL** integer | `posts` migration |
| **I-SCOPE** | `is_national_wide` boolean (default true); `state_name` nullable | `posts` migration |
| **I-CAND** | a candidacy belongs to **exactly one** post (`post_id` FK, not null) | `candidacies` migration |
| **I-RESULT** | a result row links `(vote_id, election_id, candidacy_id, post_id)` | `results` migration |
| **I-CODE** | two-use code lifecycle: `code1`/`code2`, `is_code1_usable`, `code1_used_at`, `code2_used_at`, `has_voted` | `codes` migration |
| **I-SLUG** | `voter_slugs` carries `user_id`, `status ∈ {active,voted,abstained}`, `current_step` | `voter_slugs` migration |

## 3.1 ⚠️ A subtlety the generator must not get wrong

**`candidacies.user_id` exists — and it is the CANDIDATE, not the voter.**

```
voter identity     ── present in ─→  voter_slugs , codes
                   ── ABSENT from ─→ votes , results          ← I-ANON

candidate identity ── present in ─→  candidacies.user_id      ← legitimately
```

> **The anonymity invariant is specifically "no path from a vote to the VOTER".** A naive reading —
> *"no `user_id` anywhere near votes"* — is **wrong**, and a generator built on it would produce a
> carrier that misrepresents the domain. `results → candidacies → user_id` is a **legitimate** path
> and must be preserved.

# 4. ⭐ The redundancy the schema already contains — and it is the reduction axis

**The schema stores each ballot TWICE:**

```
DENORMALISED   votes.candidate_01 … candidate_60      slot-indexed, on the vote row
NORMALISED     results( vote_id , candidacy_id , post_id , position_order )   one row per selection
```

> ## This is a real, independently-motivated redundancy that I did NOT design.
>
> **It is exactly what representation reduction is about**, and it hands `X₃` a *natural* reduction
> axis — the schema's own. **`X₁` and `X₂` had reduction axes I invented; `X₃` does not.** That is
> what makes it a genuine gate carrier rather than a third construction of mine.

**Consistency requirement `I-CONSIST`:** the tally derived from the slot columns must equal the tally
derived from the `results` rows, for every vote. **A generator that violates this produces states the
production system could never reach.**

# 5. `Q` and `C` for `X₃` — both externally motivated

```
Q  (utility)     Q(D) = the TALLY:  for each post, the multiset of candidacies selected
                 — this is what an election is FOR; it is not a research construct

C  (anonymity)   C_k(R) = 1  iff  R admits no path from any vote to a voter identity
                 AND every equivalence class on the quasi-identifiers present in R
                 has ≥ k members
```

**Quasi-identifiers available in `votes`:** `cast_at` (granularity is the reduction knob),
`no_vote_posts` pattern, the slot-occupancy pattern, `election_id`.
**k-anonymity is an established external criterion — not invented for this experiment.**

# 6. What the generator must produce

**One synthetic election instance:**

```
election      1
posts         P, mixed national / regional         (I-SCOPE)
                each with required_number          (I-REQ)
candidacies   per post, ≥ required_number          (I-CAND)
voters        N, each with a region                (regional filtering, CLAUDE.md)
votes         one per voting voter                 (I-ANON, I-HASH, I-CAST, I-SLOT, I-NOVOTE)
results       rows consistent with the slots       (I-RESULT, I-CONSIST)
```

**Parameters, all declared and swept — none chosen to produce a result:**

| parameter | range | why |
|---|---|---|
| `N` voters | {50, 200, 1000} | **k-anonymity failure is a function of electorate size** — a small electorate is where fine `cast_at` re-identifies |
| `P` posts | {3, 8} | interacts with the `no_vote_posts` quasi-identifier |
| `required_number` | {1, 3} | `I-REQ` |
| **selection mode** | `exact` \| `up-to` | **CLAUDE.md documents BOTH via `SELECT_ALL_REQUIRED` — the schema does not decide, so the generator must not either.** Swept |
| `cast_at` granularity | second / minute / hour / day | **the reduction knob** |
| `k` | {2, 3, 5, 10} | swept as sensitivity |

# 7. Generation procedure — specified, not implemented

```
1  fix seed; record it
2  build election, posts (national/regional mix), candidacies         [I-SCOPE, I-REQ, I-CAND]
3  build voters with regions; build codes in a valid lifecycle state  [I-CODE, I-SLUG]
4  for each voting voter:
     a  determine ELIGIBLE posts   = national ∪ (regional matching the voter's region)
     b  for each eligible post, select candidacies per the selection mode   [I-REQ]
     c  posts left unselected → no_vote_posts                          [I-NOVOTE]
     d  write slot columns; write matching results rows                [I-CONSIST]
     e  vote_hash = H(unique nonce)  — NOT of voter identity           [I-ANON, I-HASH]
     f  cast_at drawn from the election window                          [I-CAST]
5  discard the voter↔vote mapping and never persist it                 [I-ANON]
```

> **Step 5 is the whole point.** The generator *knows* the mapping while constructing; **it must not
> emit it.** A generator that keeps a `voter_id → vote_id` table for convenience would make `C`
> trivially violable and destroy the carrier.

# 8. Validation — `VG-1 … VG-8`, run before the generator is used

**These decide whether `X₃` is faithful. All must pass; a failure blocks Phase 3.**

| id | assertion |
|---|---|
| **VG-1** | no emitted vote or result row contains a voter identifier, in any column or JSON blob `[I-ANON]` |
| **VG-2** | `vote_hash` unique across the population; 64 chars `[I-HASH]` |
| **VG-3** | **for every vote, the slot-derived tally EQUALS the results-derived tally** `[I-CONSIST]` |
| **VG-4** | every selection count satisfies `required_number` under the declared mode `[I-REQ]` |
| **VG-5** | every selected candidacy belongs to a post the voter was eligible for `[I-SCOPE]` |
| **VG-6** | `no_vote_posts` ∪ selected posts = eligible posts, exactly `[I-NOVOTE]` |
| **VG-7** | `results.candidacy_id` → `candidacies.user_id` **resolves** — the candidate path is intact `[§3.1]` |
| **VG-8** | **positive control:** at some parameter setting, `C_k` **fails** — otherwise `X₃` repeats Phase 2's `H-B` defect and cannot exercise the contract boundary |

## 8.1 `VG-8` STRENGTHENED — both sides of the boundary

**As first written, `VG-8` asked only for a failure. That is not enough.**

> ```
> VG-8   ∃ θ ∈ Θ : C_k(θ) = 0        contract FAILS somewhere
>    ∧   ∃ θ′ ∈ Θ : C_k(θ′) = 1      contract HOLDS somewhere
> ```
>
> **If every parameter setting gives `C_k = 0`, there is contract failure but no TRANSITION** — and a
> boundary experiment with no boundary is not a boundary experiment. **Both regions are required.**

## 8.2 `VG-9` — pre-existing-axis integrity

> **The `votes ↔ results` redundancy and the semantics of both representations must be demonstrably
> established by migration/schema history, and NOT introduced, modified or optimized for this
> experiment.**

**This blocks a circularity that would otherwise be invisible:**

```
   ✗  experiment wants reduction → experiment defines the redundancy → experiment "discovers" reduction
   ✓  existing production structure → independently motivated redundancy → experimental observation
```

### `VG-9` evidence, obtained at specification time — **it already passes**

```
votes  + results migrations   commit 52a42e47   2026-03-05
                              "feat: Implement UUID multi-tenancy system - Phases 1-3, 7"

zero-algebra research         untracked         2026-09-02 / 09-03
```

> **Six months earlier, in a multi-tenancy feature commit that has nothing to do with representation
> research.** The redundancy is **independently motivated and provably prior.** `VG-9` is therefore
> **satisfied by evidence, not by promise** — and the implementation must re-assert it (schema
> unchanged since) rather than re-establish it.

## 8.3 `VG-10` — the mathematical carrier must be defined, not gestured at

> ⚠️ **The production schema is NOT the carrier.** It supplies **external domain constraint and
> representation provenance**. The experiment must still say what mathematical object `X₃` *is*.

```
production schema        the source of constraints and provenance
        ≠
experimental carrier     the mathematical object the algebra operates on
```

**`VG-10` requires an explicit mapping**, of the form

```
X₃ = ( ballots , candidate relations , result representation , provenance , temporal state )
```

with each component's type, its schema origin, and the operations `T`, `E_S`, `Π` defined **on that
object** — not on the tables.

> **Without `VG-10`, "a real production carrier" is a RHETORICAL claim rather than a formal one**, and
> the gate it is supposed to satisfy would not actually be satisfied.

**`VG-8` is the one that earns the carrier.** Without a demonstrated `C` failure — **and a
contract-preserving region to contrast it with (§8.1)** — `X₃` adds a third carrier but not the
missing failure mode.

# 9. Open decisions — the schema is silent, so I am not filling these in

| # | question | why it is not mine to decide |
|---|---|---|
| 1 | `exact` or `up-to` selection | **CLAUDE.md documents both as configurable**; the schema does not decide. **Swept, not chosen** |
| 2 | turnout — what fraction of voters vote | affects `k` directly; no schema basis |
| 3 | `cast_at` distribution — uniform, or peaked near deadlines | **realistic peaking makes re-identification easier**; assuming uniform would understate the anonymity risk. **No schema basis; must be declared** |
| 4 | whether `metadata` / `device_fingerprint_hash` are populated | present in schema, semantics not fixed there; **if populated they are additional quasi-identifiers** |
| 5 | candidate-count distribution per post | no schema basis |

> **Items 2 and 3 are the ones that matter**: both directly set how easily `C_k` fails, so choosing
> them silently would be choosing the experiment's answer.

# 10. What the generator must NOT do

- **must not** read or write any production table
- **must not** emit a voter↔vote mapping
- **must not** derive `vote_hash` from voter identity
- **must not** collapse the slot/`results` redundancy — **it is the reduction axis (§4)**
- **must not** invent columns, or populate fields the schema leaves absent

# VERDICT

> ## SPECIFICATION ACCEPTED AS A CANDIDATE EXPERIMENTAL DESIGN
> ## **NOT unconditionally READY FOR IMPLEMENTATION**
>
> **Revised on review.** The earlier "ready to implement" was premature: three gates were missing.
> **`VG-8` strengthened · `VG-9` added (and already evidenced) · `VG-10` added.**
> **Implementation may proceed only if `VG-8`–`VG-10` pass at specification validation.**
>
> **Every constraint traces to a migration or to CLAUDE.md.** The five open decisions in §9 are
> **declared as parameters to sweep, not resolved by me** — which is what keeps `X₃` a carrier the
> research did not design.
>
> **Recommended order:** implement generator → run `VG-1..VG-8` → **only if `VG-8` shows a real `C_k`
> failure**, admit `X₃` into Phase 3. **If `VG-8` fails, `X₃` is a valid carrier but not the gate
> carrier, and Phase 3 must say so rather than proceed.**

# 11. Governance — `X₃` does not retroactively validate anything

> ## Existing evidence constrains the next experiment; the next experiment does not retroactively validate the existing evidence.

**The corrected KR-ZERO results are already strong enough to stand as frozen empirical evidence.**
**`X₃` is a NEW falsification/extension experiment — not confirmation of the existing theory**, and
**no `X₃` outcome may be used to modify a KR-ZERO conclusion.** If `X₃` and KR-ZERO disagree, that is
a finding to report, not a licence to revise the earlier result.

**Nothing implemented. `app/` and `database/` untouched.**
