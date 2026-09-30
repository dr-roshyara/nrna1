# `KOS-CONTRACT-NEUTRALITY-001` — V-3 exclusion-evidence representation: sufficiency pass (research report)

**Work item:** `KOS-CONTRACT-NEUTRALITY-001` · **Date:** 2026-09-28
**Assignment:** lane `S5-architecture-pass1-evidence-reconciliation`, continued as a **research
pass** (no new grant — nothing tracked is modified)
**Performer, self-declared, not attestable (`INV-ATTR-2`/`G-2`):**
`claude-code-session:e8f324f1-25ea-4281-a95a-483401312e3e`
**Commissioned by:** the human, on the recommendation of an external review of the
2026-09-27 experiment — explicitly as a *research gate, not an implementation task*, with
seven questions to answer before any incorporation brief goes back to PO/ARB.

> ⛔ **Non-authoritative research only.** `expected.json` untouched · no governed contract
> file modified · no implementation authorized or accepted · no `.claude/runtime/workflow`
> transition written · nothing here is conformance evidence · PO/ARB is **not** asked to act
> on this report — it is asked to act on nothing until the human reviewing this decides to
> send something forward. Verified before and after: `git status --short scripts/ tests/
> .claude/runtime` shows no change from this session (the large pre-existing `docs/`
> churn under `knowledgeos_theory_research`/`brainstorming` predates this pass, is unrelated
> to it, and is left untouched).

---

## 0 · What changed since the 2026-09-27 report, and why this pass had to re-read source

The 2026-09-27 experiment reasoned from source read that day. This pass **re-read the
current files directly, today**, rather than trusting that report's excerpts — the human's
own instruction flagged that OWD-3/5/8/10/13 had already rewritten `GraphBuilder::build()`
since the original V-3 architecture determination (2026-08-19), so V-3 must be evaluated
against the **current** pipeline, not the August one. Confirmed: the version read below
**is** current-as-of-today (hashes in Traceability); `GraphBuilder.php`'s own docblock
self-dates the OWD-3 node-identity change to `2026-09-27`, one day before this pass.

---

## 1 · What exactly does an excluded fact mean in the canonical L3 model? *(Q1)*

**FACT**, read directly: `BehaviourReference` (`Domain/BehaviourReference.php`) is a
**closed, six-attribute-total** L3 fact — `INV-L3-5`, stated in its own docblock: *"ALL SIX
attributes are mandatory... there is no default and no 'unknown'."* "Exclusion" is not an
L3 concept at all. It is an **L4 verdict** — `EdgeRules::verdict(BehaviourReference $ref):
EdgeVerdict`, a **total function over well-formed `BehaviourReference` values**. Every one
of its nine branches (`EdgeRules.php:26-71`) presupposes a `BehaviourReference` that already
exists and already carries a legal `targetMethodName` string.

This is the exact categorical distinction the whole `V-3` thread keeps re-deriving from
different angles: there are two different things that can go wrong, and they are not the
same kind of wrong —

```
(a) "the fact exists, L4 said don't connect it"      — ordinary exclusion (6 reasons today)
(b) "the fact cannot be legally constructed at all"   — D-1's actual problem
```

`$this->$m()` is case (b): `PhpFactExtractor.php:519-544` only builds a `BehaviourReference`
for `$this->member(...)` when the member token is `T_STRING` (line 523: `$this->sig[$i + 2][0]
=== T_STRING`); a `T_VARIABLE` there (i.e. `$this->$m()`) fails that condition and the
branch is skipped entirely — **confirmed by direct reading of the current extractor, not
inferred**. So D-1 is not an L4 exclusion-reason gap (adding a 7th `ExclusionReason` case
would not fix it) — it is an **L3 construction-domain gap**: there is no legal value to put
in `targetMethodName` for a fact that must nonetheless be recorded as "seen." That is exactly
why the 2026-09-04 adoption chose a **new fact kind**, not a new exclusion reason, and the
evidence here confirms that choice was structurally necessary, not merely convenient.

## 2 · What provenance/evidence must survive exclusion? *(Q2)*

**FACT**, read directly from `CohesionGraph.php`'s own docblock (Decision 13.7): *"Excluded
references are retained WITH THEIR REASON — seen-and-excluded must stay distinguishable from
never-seen."* Unpacking what that requires, precisely — no more and no less:

1. **which declaring method** held the reference (`method`) — required, for report grouping.
2. **why** it was excluded (`reason`, closed `ExclusionReason` vocabulary) — required, this
   IS the 13.7 claim.
3. Enough to **distinguish this exclusion from every other exclusion recorded for the same
   method** — required, or two different excluded references collapse into one entry and
   "seen-and-excluded" silently underreports how much was seen.

**Nothing in Decision 13.7's own text, or in `ExclusionReason`'s docblock, requires that item
3 be a *method-name string specifically*.** It requires *distinguishability*. The current
`target: string` field satisfies (3) only **incidentally**, by reusing a string that happens
to already exist for ordinary exclusions — not because 13.7 asks for a name. This matters
directly for Q4/Q7 below: the representation choice is free to satisfy (3) by any means that
is actually unique per fact, and `factId` is designed for exactly that role.

## 3 · Does an existing L3 fact already provide a stable `factId` suitable for this purpose? *(Q3)*

**Structurally: yes. Operationally, as of today: no — confirmed, not assumed.**

- **FACT** (read): `BehaviourReference::$factId` exists today, `public ?string $factId =
  null` (`BehaviourReference.php:27`), documented as the one-way join key to *"a separate
  provenance record"* (same file, lines 13-16).
- **FACT** (executed, today, this pass): the currently-accepted, currently-passing test
  `tests/Unit/Cohesion/UnconsumedFieldCharacterizationTest.php::test_factId_does_not_affect_graph_or_metric`
  constructs the **same** `BehaviourReference` twice, once with `factId = 'some-fact-id-0001'`
  and once with `factId = null`, and asserts `GraphBuilder::build()` produces **identical**
  nodes, edges, *and* `excludedReferences()` either way. Re-ran it in this pass:
  `vendor/bin/phpunit --filter UnconsumedFieldCharacterizationTest` → **OK, 2/2, 8
  assertions.** This is direct, current, executed proof that `factId` has **zero consumers**
  anywhere in `L4`/`L5` today — not a grep-absence inference, an assertion the suite already
  enforces.
- **FACT** (grepped, whole capability): the only two places `factId` is even mentioned are
  its own declaration and that one characterization test. `GraphBuilder::build()` never reads
  `$ref->factId` anywhere in its current body (`GraphBuilder.php`, read in full this pass).

**Qualification that matters for the acceptance criteria in §7:** the *field* is stable (same
shape in the constructor, presumably settable by both adapters), but there is **no existing
contract requiring it to be non-null or unique**. "Suitable for this purpose" is therefore
true of the field's *shape*, false of its current *contract status* — adopting Model C would
be newly imposing mandatoriness + uniqueness on `factId`, not merely switching a consumer on.

## 4 · Does Model C preserve more information than A/B without a new semantic obligation? *(Q4)*

**More information: yes, confirmed by the comparison table already in the 2026-09-27 report,
re-verified here against current source** — only C survives `INV-L3-5`-style scrutiny without
either nulling a documented string field (A) or fragmenting the record's shape by fact kind
(B), and only C generalizes to a future fact kind with zero new schema (§7 of the 09-27
report, unchanged by today's re-read).

**"Without a new semantic obligation" is not quite accurate, and this pass corrects it
explicitly:** Model C trades a *representation* obligation (what string names the target) for
a *provenance* obligation (every `BehaviourReference`, from both adapters, must carry a
factId that is non-null and unique). That obligation does not exist today (§3). It is
strictly smaller and more general than the one it replaces — but it is real, and it is new.
Stating this precisely, rather than presenting Model C as strictly free, is itself a finding:
the 2026-09-27 report's §6 table already shows this cost under "L3/L4 boundary implications"
but does not foreground it as a *new obligation*; this pass makes that framing explicit
because it directly determines the acceptance-criteria list in §7.

## 5 · Would adopting C affect only the V-3 evidence representation, or the wider contract? *(Q5)*

**Both — provably, from the current (post-OWD) `GraphBuilder::build()`, read in full this
pass.** The exclusion-emission code applies **identically to every exclusion path**, not
only a future D-1 branch:

```php
// GraphBuilder.php — TWO emission sites, both used by ALL SIX existing ExclusionReason cases
$excluded[] = ['method' => $label, 'target' => $ref->targetMethodName, 'reason' => $verdict->reason()];
// ...
$excluded[] = ['method' => $label, 'target' => $ref->targetMethodName, 'reason' => ExclusionReason::TargetNotDeclaredHere];
```

Adopting Model C **uniformly** — the only form of C that earns the generalization claim
in §4 and in the 09-27 report's §13 — means touching the exclusion record for
`OutOfFrame`, `NotTheAnalysedUnit`, `NotDeterminable`, `CallableNotInvocation`,
`AliasedSpelling`, and `TargetNotDeclaredHere` alike, not just the not-yet-built `D-1` case.
It also means promoting `factId` from optional/decorative (§3) to **mandatory and unique on
every `BehaviourReference` constructed by both the PHP and the Python adapter** — a new `L3`
invariant on an **existing** type, not a scoped addition for the new fact kind.

**Conclusion: this is an L3-and-L4 contract change, not an evidence-shape change scoped to
V-3.** Framing it as "only the V-3 evidence representation" (as the human's paste, following
the prior report, provisionally did) understates its reach. This is worth flagging precisely
because it raises the bar for what PO/ARB needs before deciding — see §7.

## 6 · Can `D-1`/`D-4`/`D-5` be incorporated independently, or must they move together? *(Q6)*

**They can be separated, along a seam the evidence draws precisely — re-verified against
current source, not carried over unchecked from 2026-09-27:**

- `D-4`/`D-5`'s scoped dispatch (`call_user_func`/`call_user_func_array`, literal
  `[$this,'name']` form only) **always has a real, literal method name** — by `D-4`'s own
  scope definition, the second array element is a string literal, not a variable. Its
  `targetMethodName` is therefore never illegal under `INV-L3-5`, and it needs **zero**
  `EdgeRules` change (confirmed by execution, 09-27 report §5, unaffected by today's re-read
  since `EdgeRules.php` is unchanged — same hash logic as before). `D-4`/`D-5` **never reach
  the exclusion-evidence question at all.**
- Only `D-1` intrinsically lacks a legal `targetMethodName` (§1). Only `D-1`, therefore,
  requires Model A/B/C to be resolved before it can be incorporated.

**This is Option 3 from the 2026-09-27 incorporation brief** (*"Incorporate `D-4`/`D-5` only,
defer `D-1`"*), which that same-day brief argued against for lacking *"evidence this narrower
step is actually needed rather than merely cautious."* **That evidentiary gap is exactly what
the later, same-day representation-boundary experiment supplied**, and neither document has
been reconciled with the other on the record — the brief's Option-3 rejection is now stale
relative to the experiment it precedes in the file but follows in time. **This pass flags the
inconsistency; it does not resolve it** — reconciling the two documents (or superseding the
brief) is itself a small governance action, listed in §8.

## 7 · What exact acceptance criteria would PO/ARB need? *(Q7)*

Derived from Decision 13.7's own standard (node set + edge set + metric, evidence never
collapsed to a scalar) and from what §§1-6 above actually establish, not from generic
process boilerplate:

1. **A resolved scope-of-invariant question**, currently genuinely undecided (09-27 report
   §8, unaffected by today's re-read): does `INV-L3-5`'s totality text bind the
   exclusion-*evidence* record, or only `L3` *facts* (`BehaviourReference` itself)? The
   invariant's own docblock scopes it to the fact type. Model A's cost (§6 of 09-27 report)
   is entirely contingent on this reading. **This must be answered before A/B/C can be
   compared on equal footing**, not folded silently into the model choice.
2. **If Model C is chosen:** an explicit `factId`-mandatory-and-unique rule, binding on
   *both* adapters (not just future code), plus the `GraphBuilder` change threaded through
   **all six** existing `ExclusionReason` branches (§5) — with `UnconsumedFieldCharacterizationTest`
   and `CohesionSemanticsTest` updated *deliberately*, as a disclosed consequence, not
   silently broken by a later implementation slice.
3. **An explicit ruling on the `D-1`/`D-4`/`D-5` seam (§6):** may `D-4`/`D-5` incorporate now
   under the existing `target: string` shape while `D-1` waits on 1-2 above, or does PO/ARB
   want one bundled amendment regardless of the asymmetry the evidence shows? Either answer
   is legitimate; what is not yet legitimate is proceeding as if the seam doesn't exist.
4. **The standing RED→GREEN→VERIFY→ACCEPT sequence**, performed by a process separated per
   the seq-38 architecture bar already on record for this work item, exactly as every prior
   slice on this commission.

---

## 8 · Recommended next actions (recommendations only — nothing here is decided)

Ranked by what each buys relative to its cost, given everything re-confirmed in this pass:

1. **Reconcile the two 2026-09-27 documents' disagreement on Option 3** (§6) — cheap (it is a
   documentation act, not new research), and removes a live inconsistency in the record
   before anything is sent to PO/ARB.
2. **Ask PO/ARB the narrow §7.1 scope question first**, separately from A/B/C itself — it is
   binary, it is load-bearing for the whole comparison, and answering it does not require
   deciding A/B/C yet.
3. **Only then** bring a (possibly narrowed, per §6) incorporation brief forward — for
   `D-4`/`D-5` alone if PO/ARB doesn't want to wait on the representation question, or for all
   three once §7.1-§7.3 are answered.
4. **Do not implement anything** ahead of 1-3 — nothing in this pass changes that boundary.

## 9 · A note on the machine-learning question, grounded in what was actually measured

The 2026-09-27 report's corpus re-measurement (unchanged today, one day later, negligible
drift at this scale) found **one** `D-4`-shaped call site and **one** genuine `D-1` call site
in the entire `app/` corpus. At this scale, enumeration-by-reading is not merely adequate, it
is more reliable than any statistical or learned method could be — a classifier trained or
tuned against one positive example has no meaningful generalization to measure. **ML/statistical
techniques are not a fit for closing `V-3` itself.** They remain a legitimate, bounded tool for
a *different, later* purpose this programme has already named (09-27 report §14): scanning a
much larger, more heterogeneous corpus (the CPython-scale corpora OWD already used) for
*candidate* dynamic-dispatch idioms not yet in the `D-4` enumerated list — clustering or
anomaly-detection over `QualifierKind` distributions to **surface candidates for human/
architecture review**, never to decide semantic inclusion itself. That boundary (ML assists
discovery, never adjudicates ontology) was already stated as a standing principle in the prior
report and this pass finds no evidence to revise it, only a concrete, currently-too-small
corpus to apply it to yet.

## 10 · What this pass falsifies or corrects from the prior record

- **Corrects** the human's/prior paste's framing that Model C affects *"only the V-3 evidence
  representation"* — §5 shows, from current source, that a uniform Model C touches all six
  existing exclusion paths and both adapters' `factId` contract, not a scoped addition.
- **Surfaces**, rather than resolves, a live inconsistency between the two 2026-09-27
  documents on whether Option 3 (`D-4`/`D-5` now, `D-1` later) is adequately evidenced — the
  brief rejects it for lack of evidence; the experiment produced by the same session, hours
  later, supplies exactly that evidence and does not update the brief.
- **Confirms, does not merely repeat**, on freshly re-read current source: the D-1 gap is a
  construction-domain problem (§1), `factId` is unconsumed today by an executed test (§3),
  and `D-4`/`D-5` never touch the exclusion-evidence question (§6).

**STOP after this report.** No `expected.json` change. No PO/ARB request initiated by this
pass. Next actor: the human, on the ranked recommendations in §8 — most narrowly, whether to
send the §7.1 scope question to PO/ARB on its own before anything else moves.

**Traceability:** `2026-09-27-...-V3-representation-boundary-experiment.md` ·
`2026-09-27-...-V3-incorporation-decision-brief.md` · `2026-09-04-...-V3-D1-D4-D5-ADOPTED.md` ·
`2026-08-19-...-v3-decisions-registration.md` (+Amendment 1) ·
`2026-08-19-...-V3-architecture-determination.md` · source re-read in full this pass, current
hashes: `BehaviourReference.php` `71f46585…`, `GraphBuilder.php` `4dff93e2…`,
`CohesionGraph.php` `cb797ecb…`, `expected.json` `11ce59d0…` (unchanged from 09-27),
`UnconsumedFieldCharacterizationTest.php` `67691c67…` — also read in full: `EdgeRules.php`,
`ExclusionReason.php`, `Determinability.php`, `QualifierKind.php`, `PhpFactExtractor.php`
(lines 495-630), `AnalyseCohesion.php`, `CohesionSemanticsTest.php` (consumer grep) ·
executed this pass: `vendor/bin/phpunit --filter UnconsumedFieldCharacterizationTest` (OK,
2/2) · `G-KOS-CONTRACT-ARTIFACT-UPDATE` (+amendments, confirmed `UNEXERCISED`, unaffected) ·
`INV-L3-5` · `S5-architecture-pass1-evidence-reconciliation` lane record.
