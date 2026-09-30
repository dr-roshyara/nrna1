# REVIEW — 1ak-3c `evector.py` (frozen) · independent hand recomputation

Scope: `evector.py`, `EVENTS.json`, `RESULT.json`, `SOURCES-1AM2.md` only. No code was run; everything below was recomputed by hand.

## 1. Summary

- **Rule fidelity: OK.** The code implements the docstring rule. I found no divergence that changes any result. Four minor notes: a silent case in `bound` handling, an input path I cannot verify, non-standard JSON output, and a narrow self-test. No unit string in EVENTS.json is mishandled.
- **Recomputation: matches.** All 26 non-excluded vectors match RESULT.json. So do all four variants' P/Q pools and all 12 (variant × model) pair lists.
- **Bounds: not established by the sources.** No source says an instance or slice lies in exactly one context. The only bound that affects any result is the one on **E01's breadth upper limit**. If it fails, M_br and M_vec lose every E01 pair and M_rep is unaffected. In the strict variant, M_rep stays FALSIFIED and M_br and M_vec become NOT FALSIFIED. A failure for slices, including R-36 slices spanning several contexts, changes no pair.
- **Critical dependence: every falsifying pair in VX+VE25 contains E01.** E02 and E20 each carry only one of the two pairs. The sources establish E01's *provenance* count as 1. They do **not** fix the count behind the decision at 1: the ruling row itself cites a second instance, so the defensible coding is 1–2. **POST HOC:** with E01 = UNK, or with the bound 1–2, all three models in VX+VE25 are **NOT FALSIFIED**.
- **Interpretation:** the strict result rests on one event, E01. E01 is a Principal-Architect-instructed *documentation standard*. It sits next to a *methodology* non-promotion (E02) and a "not a pattern" ruling (E20). Differences in actor, target type, or an unrecorded exception explain the pairs at least as well as "evidence does not determine promotion" does.

## 2. Rule fidelity

| Rule element | Docstring | Code | Verdict |
|---|---|---|---|
| Repetition units | instances, occurrences, slices, independent slices, tickets\*, demonstrations | `REP` tuple, plus `startswith("tickets") → "slices"` after `.lower()` | Faithful |
| Breadth units | contexts | `u == "contexts"` | Faithful |
| Repetition known v | rep=[v,v], br=[1,v] | `(v,v),(1,v)` | Faithful |
| Breadth known b | br=[b,b], rep=[b,∞] | `(v,INF),(v,v)` | Faithful |
| Stated bound lo-hi | rep=[lo,hi], br=[1,hi] | Applied only when `value == "UNK"` and the bound string contains `-` | Faithful for the data. A bound with an integer value is silently ignored; the docstring does not cover that case and no event has it |
| UNK value or unit | [0,∞] on both | `not isinstance(v,int) or u=="unk"` → `(0,INF),(0,INF)`; an unrecognised unit also gives `(0,INF)` | Faithful (the fallback for unknown units is conservative) |
| P eligibility | PROMOTED ∧ ground ≠ CHOICE | Same; VX also drops `exception == "YES"` | Faithful |
| Q eligibility | NOT-PROMOTED ∧ ground = RULE | Same | Faithful |
| EXCLUDED | Dropped | Filtered before the pools are built, and before `vectors` is output | Faithful |
| Falsification | M_rep: rep(Q).lo ≥ rep(P).hi · M_br: br(Q).lo ≥ br(P).hi · M_vec: both for the same pair | `rq[0] >= rp[1]`, `bq[0] >= bp[1]`, `r and b` | Faithful |
| Variants | V0 · VX · VE25 (drop E25) · VX+VE25 | Match the tuple in `__main__`. `drop` removes E25 from both pools, and E25 is only ever a Q | Faithful |

**Unit strings in EVENTS.json:**
- `instances`, `occurrences`, `slices`, `demonstrations`, `contexts` and `UNK` are all handled.
- `independent slices` matches exactly.
- `tickets (= the column's slices)` (E11) maps to slices through `startswith`.
- E13's bound `"2-3"` parses to (2,3),(1,3).
- E21's `"2-ish"` is in `note`, not in `bound`, so it is correctly treated as UNK.

**No unit string is mishandled.**

**Non-divergence notes:**
- (a) The docstring and `SRC` read `LOOP-1AM2/R2/B/REVIEW.json`. I was given `EVENTS.json` and cannot verify that the two are identical.
- (b) `json.dumps` writes `Infinity`, which is not strict JSON.
- (c) The self-test checks "UNK never falsifies" only for M_br.
- (d) The rule never reads `note` fields. This matters for E01, whose note says "<=2" (see §5).

## 3. Recomputation

**Vectors** (rep, breadth), all matching RESULT.json:

| Events | rep | breadth |
|---|---|---|
| E01, E02, E07, E08, E20 | [1,1] | [1,1] |
| E09, E15, E18, E24, E25, E26 | [2,2] | [1,2] |
| E04, E05, E06, E10, E11, E12, E14 | [3,3] | [1,3] |
| E13 | [2,3] | [1,3] |
| E17, E27 | [1,∞] | [1,1] |
| E03, E16, E19, E21, E23 | [0,∞] | [0,∞] |

**Pools:**
- **P (all variants):** E01 (UNK ground), E10 (UNK), E23 (UNK), E24 (UNK), E27 (UNK ground, exception YES). E27 is dropped in VX.
  - E04, E05, E12 and E13 are excluded (ground CHOICE).
- **Q:** E02, E20, E25. E25 is dropped in VE25.
  - E08, E17 and E21 have ground UNK, so they are not Q.
  - E26 has outcome UNK.

**Limits used for the pairs:**
- P rep.hi: E01 = 1, E10 = 3, E23 = ∞, E24 = 2, E27 = ∞.
- P br.hi: E01 = 1, E10 = 3, E23 = ∞, E24 = 2, E27 = 1.
- Q rep.lo: E02 = 1, E20 = 1, E25 = 2.
- Q br.lo: 1 for all three.

| Variant | P | Q | M_rep | M_br | M_vec | RESULT.json |
|---|---|---|---|---|---|---|
| V0 | E01 E10 E23 E24 E27 | E02 E20 E25 | (E01,E02)(E01,E20)(E01,E25)(E24,E25) | (E01,E02)(E01,E20)(E01,E25)(E27,E02)(E27,E20)(E27,E25) | (E01,E02)(E01,E20)(E01,E25) | match |
| VX | E01 E10 E23 E24 | E02 E20 E25 | (E01,E02)(E01,E20)(E01,E25)(E24,E25) | (E01,E02)(E01,E20)(E01,E25) | (E01,E02)(E01,E20)(E01,E25) | match |
| VE25 | E01 E10 E23 E24 E27 | E02 E20 | (E01,E02)(E01,E20) | (E01,E02)(E01,E20)(E27,E02)(E27,E20) | (E01,E02)(E01,E20) | match |
| VX+VE25 | E01 E10 E23 E24 | E02 E20 | (E01,E02)(E01,E20) | (E01,E02)(E01,E20) | (E01,E02)(E01,E20) | match |

Every verdict is FALSIFIED, as reported. There are **no mismatches**, including the order of the pairs.

## 4. Bounds validity (breadth ≤ repetition)

**What the sources say about "context":**
- S7 (R-39): "evidence from ONE context (PublicDigit Adjudication/Determination, EPIC-004D..K)". That is one bounded context spanning many epics, which fits breadth ≤ repetition.
- S4 row 14: "1 context only (Election). PB-005 deliberately did NOT reuse it".
- No source maps an *instance*, *occurrence* or *slice* to exactly one context. The premise "each instance … lies in exactly one context" is **asserted in the docstring and not supported by SOURCES-1AM2.md**. This is an evidence-scope issue.

**Could a single slice span several contexts?** Yes, plausibly:
- PB-005 is Contestation work ("Contestation *owns* the Challenge").
- The same slice also produces an integration-event carrier (`ChallengeResolvedIntegration`). Integration events cross bounded contexts by definition.
- It also reuses Inbox atomicity, and Inbox is owned by Messaging (S4 rows 3, 15, 17).
- PB-006 is Messaging work with "Inbox is one consumer".

**Could one R-36 slice count as more than one context?** Yes. Nothing in S5 or S4 prevents counting PB-005 as Contestation + Messaging.

**Which bounds affect the result?**
- Q's lower breadth bound (1) does not depend on breadth ≤ repetition.
- For a P, the bound matters only through **br(P).hi**. For P events coded in contexts, it matters through **rep(P).lo**, which never enters the test.
- So a failure matters only where a P's br.hi = v is used in a pair. The only such P is **E01** (instances, v = 1). E24 and E10 are slice-coded but have no M_br pairs anyway.

**If the bound fails:**
- *For slices only (the R-36 case):* no pair changes.
- *For instances (E01, br → [1,∞]):*
  - M_rep is unchanged in every variant.
  - M_br keeps only the E27 pairs: V0 has (E27,E02)(E27,E20)(E27,E25), VE25 has (E27,E02)(E27,E20), and VX and VX+VE25 have none.
  - M_vec is **NOT FALSIFIED in every variant**.
  - In VX+VE25: M_rep is FALSIFIED by (E01,E02)(E01,E20), and M_br and M_vec are NOT FALSIFIED.

For E01, the "context" of a documentation inconsistency in the WP-1 plan header (S1, S2) is not a bounded context the sources name. Its breadth is arguably undefined (UNK) rather than bounded at 1. That would have the same effect as the bound failing.

## 5. Critical dependence and sensitivity analysis — **POST HOC**

*Everything in this section is POST HOC. The analysis was designed after seeing RESULT.json and is not part of the frozen test.*

In VX+VE25 every pair in every model is (E01,E02) or (E01,E20).
- **E01 is in every falsifying pair**, so it is the single critical event.
- E02 and E20 are each in only one pair, so neither is individually critical.

### E01 — coded `value 1, unit instances, ground UNK, exception UNK, outcome PROMOTED`

Sources:
- S1 (R-41): "Provenance: the WP-1 closure inconsistency (a CLOSED plan whose header still read "AUTHORIZED — execution begins…"), corrected 2026-07-30; first checklist execution the same day caught a second instance (ADR-T22 row's issuance-time "Implementation NOT yet authorized" clause, annotated)."
- S2: "First checklist execution against WP-1's closure caught a second real instance." The log lists the execution *after* "ES-004.3 hosted" and "R-41 appended".
- EVENTS note: "<=2 counting the post-adoption 'second instance'".

**Does the source establish the coded value?**
- It establishes the *provenance* count as 1.
- It does not establish that the evidence behind the decision was exactly 1. The decision record (R-41) itself cites the second instance. The log's order of items conflicts with the row's content, so the source does not settle whether that instance came before or after adoption.
- What the sources support is **bound 1–2, value UNK**.
- The ground is also only UNK. The source says the standard was adopted by "Principal Architect instruction", which points to authority rather than evidence as the ground.

**Recomputed VX+VE25:**

| E01 coding | M_rep | M_br | M_vec |
|---|---|---|---|
| as coded (1) | FALSIFIED | FALSIFIED | FALSIFIED |
| **UNK** ([0,∞] both) | **NOT FALSIFIED** | **NOT FALSIFIED** | **NOT FALSIFIED** |
| bound 1–2 (rep [1,2], br [1,2]) | NOT FALSIFIED (1 ≥ 2 false) | NOT FALSIFIED | NOT FALSIFIED |
| ground CHOICE (E01 not in P) | NOT FALSIFIED | NOT FALSIFIED | NOT FALSIFIED |

The remaining P events cannot be falsified by Q lower limits of 1: E10 has hi 3, E23 has ∞, E24 has 2.

**Other variants with E01 = UNK (also POST HOC):**
- V0: M_rep (E24,E25) · M_br (E27,E02)(E27,E20)(E27,E25) · M_vec none.
- VX: M_rep (E24,E25) · M_br none · M_vec none.
- VE25: M_rep none · M_br (E27,E02)(E27,E20) · M_vec none.

### E02 — coded `1 occurrence, RULE`

Source S3: "**NOT promoted to methodology** — single occurrence, and ES-006.1 forbids promoting from one."
- Both the value and the RULE ground are **established**.
- With E02 = UNK, (E01,E20) remains and all three models stay FALSIFIED.

### E20 — coded `1 independent slice, RULE`

Source S4 row 17: "1 — explicitly ruled "temporary carrier, not a pattern" (PB-005 Q1) … replaced only on repeated evidence (ER-02)". S5: "remain Candidates".
- The value 1 is **established**.
- RULE is defensible but contestable: the primary stated reason is a ruling about its *kind* ("not a pattern").
- With E20 = UNK, or not Q, (E01,E02) remains and all three models stay FALSIFIED.
- Only if both E02 and E20 are neutralised does the strict variant become NOT FALSIFIED.

## 6. Interpretation

**What it establishes.** Under this coding, with its unit pooling and the one-context bound:
- E01 has the point vector (1,1), and so do E02 and E20. E01 was promoted; E02 and E20 were withheld on RULE grounds.
- The vectors are *equal*, not just ordered. So the pairs refute not only monotone models but **any** deterministic function of (repetition, breadth).
- Within that coding, "evidence alone, as a (repetition, breadth) vector, determines promotion" is false.

**What it does not establish:**
- That evidence plays no role. Most promotions in C3 track counts, and every falsifying pair in the strict variant rests on one event.
- That the result is robust. It disappears if E01 is coded 1–2 or UNK, if its ground is authority, or (for M_br and M_vec) if its breadth is unbounded.
- That "instance of an inconsistency", "occurrence of an observation" and "independent slice" are commensurate units of repetition. The rule assumes this; the sources do not.
- That other evidence dimensions are irrelevant: independence, domain-specificity, "generalized by construction" (R-39), stability.
- Anything beyond these 26 events.

**Alternative explanations that survive:**
- **Actor/authority.** E01 is a "Principal Architect instruction" (S1). S6 states the governing principle that "a decision is an explicit act by an authority". E02 is a PO/ARB observation and E20 an ARB candidate.
- **Target type.** E01 is a *documentation standard* hosted inside the existing ES-004 ("not a new standard document"). The one-occurrence bar in ES-006.1 governs promotion *to methodology* (S3, S7), which is E02's target.
  - The V0-only pair (E24,E25) shows the same pattern: the same protocol with the same 2 slices was adopted as an operating standard but not written into a standards document.
- **Exception.** E01 may be an unrecorded early-promotion exception by authority. The exception is recorded as such in R-39, but R-41 records none. Under H-X, E01 would lie outside the monotone guard, and removing it eliminates every strict-variant pair.
- **Kind of item.** E20 was withheld because it was ruled "not a pattern", not because of its count.

## 7. Disagreement register

| # | Item | Class | Effect |
|---|---|---|---|
| D1 | E01 value coded 1; the sources support 1–2 (the second instance is cited in R-41 itself) | source interpretation | **Critical:** strict variant → NOT FALSIFIED |
| D2 | E01 ground UNK; the source says "Principal Architect instruction", which points to authority as the ground | coding | Critical if coded CHOICE (E01 leaves P) |
| D3 | "Each instance/slice lies in exactly one context" is asserted, not sourced | evidence scope | Through E01's breadth only: M_br and M_vec lose every E01 pair |
| D4 | R-36 slices (PB-005, PB-006) plausibly span several contexts | source interpretation | None on the pairs (only P br.hi matters; slice-coded Ps have no M_br pairs) |
| D5 | Instances, occurrences and slices are pooled on one repetition axis | substantive | Supports every pair; not tested by the rule |
| D6 | "Falsified" undersells the (E01,E02) and (E01,E20) pairs: equal point vectors refute any function of the vector, not only monotone ones | formal reasoning | Strengthens the conditional claim |
| D7 | E20 ground RULE vs a "not a pattern" ruling about its kind | source interpretation | Non-critical ((E01,E02) remains) |
| D8 | E24 and E25 share one protocol and one body of evidence, so they are not independent events | independence | Affects the V0/VX M_rep pair (E24,E25) only |
| D9 | E01 exception coded UNK; it may be an unrecorded authority exception | source interpretation | Under H-X, would remove all strict pairs |
| D10 | Input is declared as `LOOP-1AM2/R2/B/REVIEW.json`; I reviewed `EVENTS.json` and cannot verify they are identical | evidence scope | None known |
| D11 | A `bound` with an integer `value` is ignored; the docstring does not cover this case | wording | No event affected |
| D12 | RESULT.json contains `Infinity` (not strict JSON) | wording | None |
