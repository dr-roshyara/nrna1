# KOS adapter architecture — status reconstruction (research report)

**Work item context:** `KOS-CONTRACT-NEUTRALITY-001` · **Date:** 2026-09-27
**Performer, self-declared, not attestable (`INV-ATTR-2`/`G-2`):**
`claude-code-session:e8f324f1-25ea-4281-a95a-483401312e3e`

> ⛔ **Review only. Nothing changed.** No `expected.json` edit, no governed contract file
> touched, no production code touched, no implementation, no acceptance. This report
> reconstructs current state from the repository; it authorizes nothing and decides nothing.
> **STOP after this report** — no PO/ARB request, no continuation of the `D-1`/`D-4`/`D-5`
> contract-incorporation work.

---

## 1 · Executive business summary

**The "PHP adapter / Python adapter / canonical KOS model" architecture your diagram
describes does not exist in code today, and — more importantly — the experiment that has
been run so far is not the experiment that diagram assumes.**

What has actually been built and tested is: *the same PHP source code*, analysed by two
independently-written programs — one in PHP, one in Python — to check whether both agree on
the cohesion-metric contract. **Nobody has ever pointed this metric at Python source code.**
"Python" here means "a Python-language program that reads PHP," not "a Python-language
codebase being measured." This is a different question from the one the adapter diagram
poses, and the difference matters for every recommendation below.

Separately: a genuinely language-neutral **fact model** (`L3`: `BehaviourReference`,
`StateAccess`, closed-vocabulary qualifiers/determinability) exists and is real, but it
exists **only on the PHP side**. The Python side predates it, never adopted it, and a
governance decision (2026-08-18) has since put any second-language implementation
**out of current scope** entirely. The multi-language vision is real, on record, and
explicitly **not authoritative** — filed as a proposal, never adopted.

## 2 · Chronological reconstruction

| Date | Event | Status at the time |
|---|---|---|
| 2026-08-16 | `KOS-CONTRACT-NEUTRALITY-001` commissioned; Stage 2 authorized: *"can an independent Python implementation produce the same observations as the PHP reference?"* | An **evidence experiment**, not an architecture programme |
| 2026-08-16/18 | `lcom4_collector.py` written — a **from-scratch Python scanner** implementing the pinned contract independently, deliberately *not* a port | Experiment in progress |
| 2026-08-18 | Stage-2 independently verified **`FAIL`** (heredoc/nowdoc/attribute defects); breadth verification finds 9 more divergences | Empirical result: the two toolchains disagree in specific, named ways |
| 2026-08-18, 21:35 | `docs/knowledgeos/brainstorming/20260818-213516-lcom4-multi-language-binding.md` — a **retrospective reflection** on *why* Stage-2 failed, proposing a future architecture (canonical `L3` + one `L4`/`L5` engine + per-language bindings). Front-matter: `authoritative: false, proposed: false`. Ends: *"no grant, no assignment, no transition... A direction on the record is not authority to act."* | **Vision recorded, immediately fenced off** |
| 2026-08-18 | `G-KOS-CONTRACT-IMPL-TRACK1-AMD1` (governance grant, already in this commission's record): *"NO PYTHON ANALYTICAL IMPLEMENTATION IS CURRENT SCOPE... the future common-engine direction returns to FUTURE ONLY"* | **Same conclusion, reached independently by Governance, same day** |
| 2026-08-18/19 | Track 1 delivers the PHP-only `L3`/`L4`/`L5` stratified architecture (`BehaviourReference`, `EdgeRules`, `GraphBuilder`) — accepted within its authorized (PHP-only) scope | The canonical-*fact-model* half of the vision is **built, but PHP-only** |
| 2026-08-18 | `KOS-AIP04-DISCOVERY-001` proposed — **a different initiative** (governance/platform capability discovery for the whole Knowledge Engineering Platform), sharing only the "KOS" prefix. Its own guardrail: *"ADR-AIP-04 must NOT assign conformance authority... Nothing in this document modifies the L3 fact model... or KOS-CONTRACT-NEUTRALITY-001."* | Unrelated lineage, explicitly self-fenced from this work |
| 2026-09-04/05 | This commission's Pass 1 + `V-3` `D-1`/`D-4`/`D-5` work (already on record) | Continues the PHP-only `L3` model, unaware at the time that the multi-language vision had already been fenced off five weeks earlier |

**Correction to a natural assumption:** the *architecture vision* is younger than, and a
direct reaction to, the *empirical failure* — not the other way around. Nobody designed the
canonical-model idea first and then tested it; the test failed first, and the canonical-model
idea is one proposed explanation for why, immediately marked non-authoritative.

## 3 · Current PHP architecture (`FACT`, read directly)

```
PHP source (10 golden fixtures, and app/)
      ↓
PhpFactExtractor (Infrastructure/Php) — token-based, grammar-exact, no full AST library
      ↓
L3 facts: BehaviourReference (6 mandatory attrs) · StateAccess (2 attrs) — CLOSED VOCABULARY,
          no provenance (INV-4/INV-L3-7), all-mandatory (INV-L3-5)
      ↓
L4: EdgeRules::verdict() (Decision 13.3, stratified rule table) + GraphBuilder (nodes/edges/exclusions)
      ↓
L5: Lcom4::compute(graph) → integer
      ↓
AnalyseCohesion::observe() → serialized observation (metric, nodes, edges, excluded)
```
This is real, exercised code, independently verified (Track-1 independent verification
`50d55d26`), accepted within its authorized scope. **`L3` and above are genuinely
language-neutral in *semantic responsibility*** (the domain classes contain no PHP-specific
logic) — but nothing currently feeds them from any language but PHP.

## 4 · Current Python architecture (`FACT`, read directly — full 279-line file)

```
PHP source (same fixtures — the function parameter is literally named `php_source: str`)
      ↓
blank_noise() → find_classes() → find_methods()  [regex/text scanning, hand-written]
      ↓
analyse_method() → raw (properties, calls) tuples — NOT BehaviourReference/StateAccess objects
      ↓
connected_components() — union-find directly over method-name strings
      ↓
LCOM4 integer
```
**No `L3` fact object. No `L4` verdict. No shared type with the PHP side at all.** It imports
nothing from, and shares no representation with, `scripts/lib/EngineeringKnowledge/`. It is a
flat scanner-to-metric pipeline — architecturally, the same shape the **original pre-Track-1**
PHP `Lcom4Collector.php` had, before Track-1 introduced stratification. **The Python side is
one architectural generation behind the PHP side, not a peer adapter to it.**

## 5 · Existing adapter/port boundary

**None exists in code.** Confirmed by direct search: no PHP interface or abstract class named
anything like a port/adapter contract exists anywhere in `scripts/lib/EngineeringKnowledge/`.
The `Infrastructure/Php` folder name follows a DDD *convention* (hexagonal-shaped naming),
but there is no `LanguageBindingInterface` or equivalent that a hypothetical
`Infrastructure/Python` could implement. **A folder name is not a port.**

## 6 · Canonical model status

| Concept | KOS-core (language-neutral)? | Evidence |
|---|---|---|
| `BehaviourReference`, `StateAccess`, `QualifierKind`, `Determinability`, `ExclusionReason`, `EdgeVerdict` | **Yes, genuinely** | No PHP source/token/AST reference anywhere in these classes (verified by reading all of them); `EdgeRules`'s own docblock: *"knows nothing about PHP"* |
| `EdgeRules`, `GraphBuilder`, `Lcom4` (L4/L5) | **Yes** | Consume only closed-vocabulary L3 facts |
| `PhpFactExtractor` | **PHP-specific, by design** | The one authorized "bounded exception" (`AMD1`) — implemented in PHP, knows PHP tokens; this IS where a hypothetical language binding boundary would sit |
| `lcom4_collector.py` | **Fully Python- *and* PHP-specific** — Python implementation language, PHP source language, no shared type | Traced above |
| The `04-contract-neutrality-fact-model.puml` diagram | **Describes only the PHP path** — one adapter, not two | Confirmed by the fork that read it |

**A canonical model exists for `L3`-and-above. It has never been fed by anything but the one
PHP binding.**

## 7 · Metric inventory

| Metric | PHP implementation | Python implementation | Same input? | Same representation? | Same output? | Tested? | Status |
|---|---|---|---|---|---|---|---|
| LCOM4 | `Lcom4::compute()` via `L3`→`L4`→`L5` (Track-1) *and*, historically, the pre-Track-1 regex `Lcom4Collector.php` | `lcom4_collector.py`, flat scanner | ✅ same PHP fixture files | ❌ no — Python has no `L3`/`L4` objects at all | Compared only as **integers + node/edge lists**, never as intermediate facts | ✅ yes (Stage-2 evidence, breadth verification) | **B (unit-tested) and C (both run) achieved. D (equivalent inputs) achieved. E (equivalent semantic representation) NOT achieved — there is no shared representation to be equivalent. F (outputs compared): yes, and found to diverge (3, then 12, confirmed defects). G (independent parity verification): the *divergence* was independently verified; *agreement* was never independently certified as sufficient — the record itself says the matching cases are "far less informative than their shape suggests."** |

This is the only metric in scope. No second metric has ever been attempted in both languages.

## 8 · PHP/Python parity experiments — the one that exists, examined precisely

- **Fixture:** the ten golden PHP fixtures, plus later `app/`-scoped breadth probes.
- **Source input:** PHP source text, in both cases.
- **Extraction method:** PHP — grammar-exact token scanning feeding a closed L3 model.
  Python — a hand-written regex/text scanner feeding raw tuples directly into a graph
  computation, no intermediate fact stage.
- **Intermediate representation:** PHP has one (L3). Python has none.
- **Metric:** LCOM4, both sides.
- **PHP result / Python result:** 10/10 fixtures agree; on broader probing, **3 (later 9 more,
  12 total) confirmed divergences**, all traced to constructs the regex-based tooling (both
  the original PHP `Lcom4Collector.php` and the Python scanner) cannot parse correctly
  (heredoc, nowdoc, same-line attributes) — **not** language-semantic disagreements about
  what LCOM4 means, but **parser-technology** disagreements about what the source text says.
- **Comparison method:** integer equality plus node/edge/exclusion list comparison.
- **Conclusion on record:** `FAIL — implementation defect`, independently reproduced.
- **Unresolved:** whether the *current* Track-1 PHP architecture (as opposed to the
  original regex-based one) would still diverge from the Python scanner — **never
  re-tested**, because Python was taken out of scope before Track-1 was built. This is a
  real gap: the parity experiment that exists tests an **already-superseded** PHP
  implementation against Python, not the current one.

**No genuine cross-language *semantic* parity experiment exists.** What was falsified is
parser robustness, not contract portability across source languages.

## 9 · Current parity maturity (`P0`–`P8`, evidence-only, not ambition)

| Stage | Assessment | Evidence |
|---|---|---|
| P0 — no adapter concept | passed | — |
| P1 — adapter concept documented | **reached, but only as a non-authoritative brainstorm** | `20260818-213516-lcom4-multi-language-binding.md`, `authoritative: false` |
| P2 — first adapter implementation | **reached only for PHP** | `PhpFactExtractor` |
| P3 — canonical representation exists | **reached, PHP-fed only** | `L3` classes |
| P4 — two language adapters produce comparable representations | **NOT reached** | Python has no representation to compare |
| P5 — metrics run independently on both paths | **reached, but pre-canonical-model** | Stage-2, against the *old* PHP regex collector, not Track-1 |
| P6 — controlled cross-language parity demonstrated | **NOT reached** | the one comparison found `FAIL`, and tested an outdated PHP path |
| P7 — independent verification / regression suite | **reached for the divergence, not for agreement** | independent `FAIL` reproduction exists; no independent *parity* certification exists |
| P8 — language adapter replaceable without core changes | **NOT reached; no such boundary exists to test** | §5 |

**Overall: P2–P3 solidly (PHP only); P1 for the vision; P4 upward not reached for any
second language.** Different parts sit at genuinely different stages — collapsing this into
one number would misstate it.

## 10 · Architectural gaps, prioritized

1. **(Architectural importance: highest) No port/interface boundary exists at all** — even
   for PHP alone. `PhpFactExtractor` is the only implementation of an unstated contract.
   Formalizing this contract is the actual prerequisite for *any* second language, and it's
   currently missing even in a single-implementation system.
2. **(Uncertainty: highest) It is unknown whether the current, Track-1-era PHP fact model
   would agree with a from-scratch second-language implementation** — the only empirical
   test on record used the *superseded* PHP path. This is a real, re-testable unknown, not
   solved by re-reading old results.
3. **(Cost to resolve: low, falsifiability: high) A canonical *serialization* of `L3` facts
   (e.g., the JSON shape `AnalyseCohesion::observe()` already produces) could be tested
   against a from-scratch second-language *consumer* of that serialization** — this would
   test the canonical-model hypothesis directly, without requiring a second full extractor,
   and is the cheapest real falsification test available.
4. **(Governance, not architecture) The current scope decision (PHP-sole-language,
   2026-08-18) has not been revisited since Track-1's `L3` model was built** — it was made
   *before* the canonical model existed in its current form, so the tradeoffs it weighed
   have changed; whether to revisit it is explicitly outside this report's authority.

## 11 · `D-1`/`D-4`/`D-5` architectural placement

None of `D-1`, `D-4`, `D-5` belong in `expected.json` by name (already established in the
prior representation-boundary experiment, §7 of that report — preserved here, not
re-litigated). Given this reconstruction, their placement is now clearer still:

- **`D-1`/`D-4`/`D-5`'s *normative content* (the semantic decision, and — for `D-4` — the
  scope boundary) belongs in the contract** (`expected.json`), because that's already where
  the parallel decision (`intra_class_calls`) lives, and it's genuinely about *meaning*, not
  implementation.
- **Their *representation* (`IndeterminateBehaviourReference`, `QualifierKind::ExplicitCallableDispatch`,
  and the still-open exclusion-record model) belongs in the `L3` canonical model** — this is
  KOS-core, language-neutral domain design, not PHP-adapter work. It sits at exactly the
  layer `PhpFactExtractor` feeds *into*, not the layer that would need duplicating per
  language.
- **They are NOT solving a PHP-specific problem at the wrong layer.** `V-3`'s entire finding
  is about the `L3` fact model's expressiveness (can it represent "observed but
  undeterminable"), which is precisely the canonical, language-neutral layer — this is
  actually a well-placed piece of work, not a misplaced one. The risk your question named
  (solving a PHP problem at the wrong altitude) does not materialize here, on the evidence.

## 12 · Current architecture diagram

```
PHP source ──► PhpFactExtractor (PHP-specific) ──► L3 (canonical, but PHP-fed only)
                                                        │
                                                        ▼
                                          L4 EdgeRules / GraphBuilder (canonical)
                                                        │
                                                        ▼
                                              L5 Lcom4::compute (canonical)
                                                        │
                                                        ▼
                                          AnalyseCohesion::observe (canonical)

PHP source ──► lcom4_collector.py (Python, PHP-specific, flat) ──► LCOM4 integer
              (no connection to the canonical L3/L4/L5 pipeline at all)
```

## 13 · Target architecture diagram (as proposed on record, non-authoritative)

```
PHP source    ──► PHP Adapter    ──┐
                                    ├──► L3 canonical facts ──► L4/L5 canonical engine ──► metric
Python source ──► Python Adapter ──┘         (one engine, not duplicated per language)
```
**Note the corrected framing:** the target vision is about a **second adapter analysing
Python *source*** — genuinely different from what Stage-2 tested (a Python-language program
analysing PHP source). No work toward this specific target (Python source → facts) exists
yet, on either side.

## 14 · Current → Target transition plan (smallest steps, not a schedule)

1. Formalize the PHP binding's implicit contract as an actual interface (`gap #1`) — needed
   regardless of Python, and currently the single biggest concrete architectural debt.
2. Run the cheap falsification test (`gap #3`): hand-construct `L3`-shaped facts for a
   handful of Python-source-equivalent cases and confirm `L4`/`L5` handle them with zero
   change (this only tests the *consumer* side, not extraction — much cheaper than a real
   Python extractor).
3. **Only if (2) succeeds**, and only as a separately authorized act: prototype a minimal
   Python-*source* extractor for a tiny construct subset, targeting the *existing* `L3`
   shape directly — this is the actual first adapter-parity experiment; none has been run.
4. Revisit the 2026-08-18 language-scope decision explicitly, informed by (1)-(3) — a
   PO/ARB act, not an architecture act, and not performed here.

## 15 · Recommended next research experiment (not decided, offered)

**Step 2 above** — a bounded, non-authoritative test of whether hand-constructed L3 facts
representing *Python-idiomatic* constructs (e.g., a Python method referencing `self.other()`
where "other" is dynamically resolved) pass cleanly through the *existing, unmodified* `L4`
`EdgeRules`/`GraphBuilder`/`L5` pipeline. This directly tests the strongest form of the
canonical-model hypothesis — that `L3` is *actually* language-neutral, not just PHP-neutral
by absence of counter-example — at near-zero cost, without writing any real extractor.

## 16 · `FACT`

No port/interface exists in code · `lcom4_collector.py` has no shared representation with
PHP's L3/L4/L5 · the multi-language vision document is explicitly `authoritative: false` ·
the governance record independently reached "Python out of current scope" the same day ·
the one parity experiment tested the pre-Track-1 PHP path, not the current one · the
fact-model diagram for this commission shows one adapter, not two · `KOS-AIP04-DISCOVERY-001`
is an unrelated governance-capability initiative that explicitly excludes this work.

## 17 · `DERIVED`

`D-1`/`D-4`/`D-5` belong at the `L3` canonical layer, not in `expected.json` by name, and not
in a PHP-specific location (follows from §6 + §11, not independently re-tested here) · the
current parity maturity is P2-P3 for PHP alone and P1 for the vision, not a single number
(follows from tracing each stage's evidence separately, §9).

## 18 · `HYPOTHESIS`

That `L3` would in fact accommodate Python-idiomatic constructs cleanly — plausible, given
its design intent and its demonstrated PHP-neutrality, but **not tested by this report** —
this is exactly what §15's experiment would establish or falsify.

## 19 · `UNKNOWN`

Whether the current, Track-1-era PHP model would parity-match a fresh second-language
extractor (never tested against the current architecture) · whether any effort outside this
repository's visible history addressed this question · what a real Python-source extractor
would cost to build to the point of a genuine parity test · whether PO/ARB would choose to
revisit the 2026-08-18 scope decision at all — none of these are answered here, and none are
guessed at.

---

**Traceability:** `docs/knowledgeos/architecture/00-KnowledgeOS-Architecture-Review-Index.md`
through `07-KnowledgeOS-Target-Architecture-Review.md` · `04-contract-neutrality-fact-model.puml`
· `docs/adr/DR-ARCH-001-why multiple_project_in_one.md` ·
`docs/knowledgeos/brainstorming/20260818-213516-lcom4-multi-language-binding.md` ·
`docs/knowledgeos/architecture/2026-08-18-KOS-AIP04-DISCOVERY-001-adr-aip-04-capability-discovery-proposal.md`
+ its independent verification/amendment/correction · `scripts/observations/lcom4_collector.py`
(full file) · `scripts/lib/EngineeringKnowledge/Capabilities/Cohesion/**` (full directory,
read directly) · `G-KOS-CONTRACT-IMPL-TRACK1-AMD1` · `2026-08-18-KOS-CONTRACT-NEUTRALITY-001-stage2-authorization.md`
· `2026-09-27-...-V3-representation-boundary-experiment.md` (prior report, this commission).

**STOP after this report.** No `expected.json` change. No PO/ARB request. No continuation of
`D-1`/`D-4`/`D-5` contract-incorporation work. Next actor: whoever decides between options
A/B/C at the end of the preceding message — this report supplies the evidence for that
choice, it does not make it.
