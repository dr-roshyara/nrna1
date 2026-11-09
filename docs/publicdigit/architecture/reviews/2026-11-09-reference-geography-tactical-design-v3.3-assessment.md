# Reference Geography tactical design v3.3: companion assessment

| | |
|---|---|
| **Status** | PROPOSED. Companion to `2026-11-09-reference-geography-tactical-design-baseline-v3.3.md`. Not committed |
| **Binding** | D-81 to D-88 |

## 1. v3.2 findings

| Finding | Status | How |
|---|---|---|
| A. Watermark could admit a later publication at exactly W | **Resolved** | Later publications get `max(clock, previous + δ, W + δ)`, which is strictly above W; W is inclusive; only a committed close confirms (§6) |
| B. Predicate staleness missed removals | **Resolved** | **Before-images**: a family dependency is invalidated if a later claim's **new value or before-image** satisfies the condition, which catches gains and losses (§5.1) |
| C. Lock-phase proof conditional | **Resolved conditionally** | Invariant-to-dependency matrix (§5.2). **The proof holds only if the validator reads published state exclusively through dependency-recording queries [TU]**, to be enforced structurally and by tests |
| D. Slot completeness within kinds | **Resolved** | Per-kind table of what holds one value; **set-valued source names added** (several alternatives coexist) |
| E. Governance after D-88 | **Resolved** | Two roles only; T/A/B and the first approver removed; D-88 in the binding list |
| Consistency | **Resolved** | D-88 listed; no obsolete [OQ]; the formula and the explanation agree |

## 2. Guarantees by source

| Proven by the design | Database-dependent | Platform-contract-dependent | Human governance |
|---|---|---|---|
| Slot independence; dispute never changes values; identity never reused; **gain and loss detection, given the matrix** | `FOR UPDATE` held to commit and re-read under `READ COMMITTED`; atomic commit; timestamp resolution δ | **Cross-context τ:** every context meets the knowledge-time contract (§6). **Membership's barrier still to be checked against it** | Real-world identity; authenticity, competence, applicability; conflicts; supportability |

## 3. Scenarios

**Key:** D = domain-level · DB = database-dependent · P = platform-contract-dependent · H = human governance.

### 3.1 The 52 earlier scenarios

They hold under v3.3, with these changes:
- **Staleness cases** (v3.2 new #2–#5, v3.1 N9, v3 #10) now rest on before-images, and **removals are covered as well.**
- **Cross-context cases** (v3.2 new #6, #7) rest on the platform contract.
- **Governance case** (v3.2 new #10) per D-88: the assessing steward can never be the second person.

### 3.2 The 16 new scenarios

| # | Initial state | Operation | Invariant at risk | Expected | Mechanism | Kind |
|---|---|---|---|---|---|---|
| 1 | W = 12:00:00.000000 confirmed; clock reads 12:00:00.000000 | Publish | Completeness at W | `recordedAt` = 12:00:00.000001 > W | W + δ | D, DB (δ) |
| 2 | W = 12:00; clock reads 11:59 (rolled back) | Publish | Same | 12:00:00.000001 | W + δ | D |
| 3 | Membership and Geography | Each asked to close through τ | One τ (D-73) | Each confirms its own inclusive close ≥ τ, or refuses | Contract item 4 | P |
| 4 | Clock goes backwards between a Membership and a Geography publication | Later query at τ | Completeness | Each stream complete through τ; cross-context order of the two facts not promised | Per-stream clamp | P |
| 5 | C child of A at p₀; R depends on "children of A" | C moved to B at p₀+1; R publishes | G-4, G-5 | Before-image A → **stale** | Family dependency | D |
| 6 | R depends on "children of A" | A new child of A at p₀+1 | G-4, G-5 | New value A → stale | Family | D |
| 7 | R relies on "X held only by U" | U relinquishes X | I-1 basis | Before-image X → stale | Family | D |
| 8 | Same | Another subject takes X | I-1 | New value X → stale | Family | D |
| 9 | R cites mandate M | M withdrawn | Evidence basis | Slot dependency → stale | Slot | D |
| 10 | R relies on claim Y being undisputed | Y disputed | Evidence | (DISPUTE, Y) slot → stale | Slot | D |
| 11 | Unit Q in class c over V matched R's family | Q's interval shortened, so it no longer matches over part of V | G-6 | The shortening claim's before-image c over the overlap → stale | Family plus interval | D |
| 12 | R in Nepal | A German publication | False conflict | Not stale | No dependency overlap | D |
| 13 | R1 and R2 both depend on "children of A" | R1 publishes first | Overlap | R2 stale if R1's claims touch that family over V | Family | D |
| 14 | R1 (Nepal), R2 (Germany) | Both publish | Unrelated | Both succeed, serialised | Lock | D, DB |
| 15 | Multi-country R violates G-4 in one country | Pre-validation or the delta check | Atomicity | Rejected as a whole; nothing visible | One entry or none | D, DB |
| 16 | A rollback during publication or during a close | — | Gaps, false confirmation | **Publication:** row, entry and W unchanged; the position is reused. **Close:** W unchanged; no confirmation given | Transaction | DB |

## 4. Unresolved

- **[TU]** The matrix's completeness depends on **structural enforcement**: the validator may read published state only through dependency-recording queries.
- **[TU]** `FOR UPDATE` semantics under the chosen isolation; the resolution δ.
- **[TU / platform]** Membership's conformance to the knowledge-time contract.
- **[OQ], later:** names of regions and of territorial units; L-4c; Japanese blocks; Q-2.

**No new contradictions found. No Principal decision is needed.**

## 5. Readiness

**Ready for independent ratification review**, with the technical verifications above recorded as conditions for the implementation architecture, not for the domain model.
