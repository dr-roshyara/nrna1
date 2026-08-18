# KOS-CONTRACT-NEUTRALITY-001 — **breadth verification of the contract-neutrality defect surface**

**Date:** 2026-08-18 · **Assignment:** `S1-verification-stage2-breadth` (seq 17 REGISTER · seq 18 HANDOFF · seq 19 START)
**Grant:** `G-KOS-CONTRACT-STAGE2-BREADTH` (AUTHORIZED — PO/ARB Option B, 2026-08-18)
**Question asked:** *are the three known divergences the complete defect surface, or are more present?*
**The Stage-2 `FAIL — implementation defect` verdict is NOT re-decided here.**

---

## 1 · Independence statement

| Bar | Status |
|---|---|
| Not the Python Stage-2 implementer | **Satisfied** — `Declared` |
| Not the original Stage-2 verifier | **Satisfied** — `Declared` |
| Not the completion/reproduction auditor | **Satisfied** — `Declared` |

**Process identity, self-declared:** `claude-code-session:5e1dd9ee` — a process distinct from `claude-code-session:fbc084f0`, which drafted the corrected contract **and** implemented the Python collector and is therefore disqualified twice over. This process began from an empty context in a fresh terminal; it had performed no prior work on this work item.

**⚠️ The disclosure is `Declared`, NOT `Observed`, and not attestable (INV-ATTR-2 / G-2).** No mechanism in this repository can prove process separation. Compliance rests on where the PO/ARB started the session. Per the assurance baseline's C3 rule this assignment's attribution is **Declared-Recorded at the time of the act**.

**Prior exposure that IS present, and disclosed:** the grant *requires* reading the original Stage-2 verification report and the completion/reproduction audit, and I did. That exposure would disqualify me from **re-deciding** the FAIL verdict; this assignment does not ask me to, and I have not. It does not bias breadth search, which asks for constructs *nobody probed*.

## 2 · Evidence and method

**Read (read-only):** the contract and its seven pinned decisions (`scripts/observations/examples/lcom4/expected.json`) · the PHP reference (`scripts/observations/Lcom4Collector.php`, 180 lines) · the Python collector (`scripts/observations/lcom4_collector.py`, 279 lines) · the ten golden fixtures · the Stage-2 evidence report · the Stage-2 independent verification (`4d4738db`, FAIL) · the completion/reproduction audit (`f1ec1821`, findings `N-1`..`N-4`, `O-1`) · the breadth grant and assignment.

**Harness:** a scratch comparator running both collectors over the same throwaway probe and comparing the `(class, value)` observation lists. **Nothing in the repository was modified** — no contract, no fixture, no `expected.json`, no PHP, no Python, no test. Probes live only in the session scratchpad.

**Method — falsification, per `O-1` (*"a probe that cannot fail is not evidence"*).** Every probe below is stated with **what result would have falsified it**. Probes were built as *minimal discriminating* cases: each is designed so that a wrong analysis produces a *different LCOM4 number*, not merely a different internal state. The implementer's 34 self-check cases were **not reused as proof**; the ten fixtures were re-run only as a baseline control.

**Mechanism claims are `Observed`, not `Inferred`:** for every new divergence I dumped what the Python scanner actually discovered (class names, body offsets, per-method property and call sets) rather than reasoning from the source.

**Measured totals: 76 probes — 54 agree · 22 diverge.** Baseline control: the ten golden fixtures agree with each other **and** with `expected.json` (`PHP == Python == expected`, 10/10), so the harness is not manufacturing divergence.

## 3 · Coverage matrix

| # | Required family | Probes | Result |
|---|---|---|---|
| 1 | **enums** | E1–E5, M4, M5, C4, D6 | **Tested — no divergence** (E5 diverges, but only as the *known* anonymous-class gap reaching through an enum) |
| 2 | **interfaces** | I1–I3, E4, C4 | **Tested — no divergence** |
| 3 | **traits as analysed units** | T1–T5 | **Tested — no divergence** |
| 4 | **readonly / promoted properties** | R1–R6 | **Tested — no divergence** |
| 5 | **nested declarations** | N1–N5, I4–I6, C1, D2 | 🔴 **NEW divergences** (`NEW-1`) + known anon-class gap |
| 6 | **match / enum-case parsing** | M1–M6 | **Tested — no divergence** |

**None of the six was skipped.** Additional constructs probed under the grant's permission: attributes in further positions (X1–X2, B1–B2, D6), heredoc blast radius (B3, B6), string interpolation (A4, A5, D3), own-class name resolution (A1–A3, D1), non-code regions (B4, D4), identifier charset (C2, C3, C3b, C6), nullsafe (A6), first-class callables (A7), backticks (B5), `$class` as a variable name (A8), braced namespaces (C1), conditional declaration (D2), CRLF (D5), a realistic control class (C5).

## 4 · Known divergences — **all three reproduce**

| Probe | Known finding | PHP | Python | Contract requires |
|---|---|---:|---:|---|
| K1 | heredoc fabricates an edge | 2 | 🔴 1 | 2 |
| K2 | nowdoc fabricates an edge | 2 | 🔴 1 | 2 |
| K3 | same-line attribute loses a method | 2 | 🔴 1 | 2 |
| K4 | own-line attribute (**control**) | 2 | 2 ✅ | 2 |
| B6 | heredoc, second shape | 2 | 🔴 1 | 2 |

*Falsifier for K1–K3: PHP and Python agreeing. They did not. Falsifier for K4: divergence on the control. There was none.*

**Also reproduced: the known anonymous-class GAP** (contract-silent, so neither implementation can be called wrong on it): I6 bare `new class` → PHP 2 observations, Python 1 · E5 inside an enum · N5 nested anon-in-anon → PHP 3 observations, Python 1.

**One bounded-scope question answered:** B3/B6 tested whether a heredoc corrupts *later* classes in the same file. **It does not** — the class following the heredoc was analysed correctly in both. The blast radius is the containing class. *(Falsifier: the later class diverging. It did not.)*

## 5 · 🔴 New divergences — **nine, in nine distinct mechanisms**

> Every row is `Observed`: measured on both collectors, with the Python scanner's internal state dumped.

### `NEW-1` · Anonymous class with `extends` / `implements` → **a fabricated class named after a PHP keyword**

| Probe | PHP | Python |
|---|---|---|
| I4 `new class implements Marker` | `Factory=1`, `(anonymous)=2` | `Factory=1`, **`implements=2`** |
| I5 `new class extends Base` | `Base=0`, `Factory2=1`, `(anonymous)=2` | `Base=0`, `Factory2=1`, **`extends=2`** |
| I6 `new class` (bare, control) | `Factory3=1`, `(anonymous)=2` | `Factory3=1` (omitted) |

**Mechanism, observed:** `CLASS_RE = \bclass\s+([A-Za-z_]\w*)` captures the *next word* after `class`. For `new class implements Marker {` that word is the keyword `implements`. The dump shows `class 'implements' body[123:250]` with methods `p`, `q` — the anonymous class's real methods filed under a keyword.
**Why this is not merely the known gap:** the known gap is *omission* (I6). This is *misattribution* — an observation exists, carries a real value, and its `class` identity is a PHP keyword. Any consumer keyed on class name (a dashboard, a trend series, a per-class threshold) silently ingests garbage. *Falsifier: Python reporting `(anonymous)` or nothing. It reported neither.*

### `NEW-2` · Same-line attribute on a **multi-line** method → **the class loses every method and reports `value = 0`**

| Probe | PHP | Python |
|---|---:|---:|
| X1 (three real methods, `#[Route]` on the same line as a multi-line `index()`) | **3** | 🔴 **0** |
| X2 (same shape, attribute on its own line — **control**) | 3 | 3 ✅ |

**Mechanism, observed:** `blank_noise` treats `#` as a line comment, so `#[Route] public function index(): void {` is blanked **including its opening brace** — but the closing `}` two lines down survives. `find_methods`' depth arithmetic (`count("{") - count("}")`) then reads `0` instead of `1` for every subsequent method, so `show` and `edit` are skipped too. Dump: `class 'Controller' body[51:125] methods=` — **empty**.
**Severity, stated as consequence not judgement:** the emitted interpretation string is **`"no analyzable methods"`** for a class with three of them. This is worse in kind than the known K3 (which loses one method): here the collector makes a **positive false statement about the class**, and `0` is the value most likely to be read as "nothing to see here". *Falsifier: agreement, or Python merely dropping the annotated method. Neither occurred.*

### `NEW-3` · Same-line attribute on a **class declaration** → **the class disappears entirely**

| Probe | PHP | Python |
|---|---|---|
| B1 `#[Entity] class Product {` | `Entity=0`, **`Product=2`** | `Entity=0` — *`Product` absent* |

**Mechanism, observed:** the blanking removes `class Product {` itself, so `CLASS_RE` never matches. Dump lists only `class 'Entity'`. **A whole class silently vanishes from the observation set** — a *missing row*, not a wrong number, which no value-comparison suite detects. *Falsifier: agreement. There was none.*

### `NEW-4` · Heredoc containing a stray `{` → **method boundaries corrupt, properties migrate between methods**

| Probe | PHP | Python |
|---|---:|---:|
| B3 | 2 | 🔴 1 |

**Mechanism, observed — distinct from the known K1.** K1 is a *fabricated edge* from heredoc text. Here the heredoc's unbalanced `{` extends `match_block`, so the class body is computed as `body[25:323]` (overrunning into the next class) and the dump shows `tpl(props=['n'])` — **`$this->n` belongs to `other()`, not to `tpl()`** — while `other` is lost as a node entirely. The heredoc defect therefore also corrupts **which method owns which state**, not only whether an edge exists. *Falsifier: the divergence being explained by a fabricated edge alone. The dump refutes that.*

### `NEW-5` · `\Own::m()` — fully-qualified own-class call. ⚠️ **The PHP reference may be the divergent party**

| Probe | PHP | Python | Note |
|---|---:|---:|---|
| A1 `\Fq::b()`, global namespace | 2 | 🔴 3 | PHP records the edge; Python does not |
| A2 `\App\Ns::b()` in `namespace App` | 3 | 3 | agree — **coincidentally** |
| A3 `\Vendor\Other\Ns2::b()` | 3 | 3 | agree — **coincidentally** |

**Mechanism, observed on both sides.** PHP passes `Node\Name::toString()` into `namesThisClass()`; for a fully-qualified name that **drops the leading backslash** — verified directly: `(new FullyQualified("Foo"))->toString() === "Foo"`. So PHP reads `\Fq::b()` as own-class. Python's `STATIC_CALL_RE` character class `[A-Za-z_\\]` **swallows the backslash into the qualifier**, yielding `\fq`, which never equals `fq`.

**This one is not a plain Python defect.** The pinned decision `own_class_name_resolution` reads: *"`OwnClass::m()` is recognized only when the name matches the class's own declared name **AS WRITTEN**; namespaced or aliased spellings are not resolved."* `\Fq` is a *fully-qualified spelling*, not the name as written — on that reading **Python conforms and the PHP reference does not**. The contract does not settle it. **Classification: contract ambiguity in the defect surface, not solely an implementation defect.** Repairing the Python collector would not resolve A1; someone must rule.

**And note A2/A3:** they agree only because the Python regex's backslash-swallowing happens to align with PHP's refusal to resolve a namespaced name. **Agreement produced by two unrelated accidents is not evidence of a shared rule.**

### `NEW-6` · Double-quoted string interpolation → **real invocations and real property accesses are lost**

| Probe | PHP | Python |
|---|---:|---:|
| A4 `"value: {$this->b()}"` — a genuine invocation | 2 | 🔴 3 |
| A5 `"value: $this->shared"` — a genuine property read | 2 | 🔴 3 |
| D3 `"x {$this->shared} y"` | 2 | 🔴 3 |

**Mechanism, observed:** `blank_noise` blanks the whole double-quoted string, interpolation included; the dump shows `a(props=[],calls=[])`. PHP's AST descends into `Encapsed` and sees the real `MethodCall` / `PropertyFetch`.
**Direction is the OPPOSITE of the heredoc defect** — heredoc makes Python *invent* relationships; interpolation makes it *lose* them. **The defect surface is not one-signed**, so no "Python is merely conservative" summary is available. Contract-defined territory: `$this->m()` is the contract's own first example of an included relationship, and `{$this->b()}` *is* `$this->b()`. *Falsifier: agreement. There was none.*

### `NEW-7` · Non-code regions scanned as code → **classes fabricated out of text**

| Probe | PHP | Python |
|---|---|---|
| B4 inline HTML after `?>` containing class-like prose | `RealOne=1` | `RealOne=1`, **`Ghost=2`** |
| D4 `__halt_compiler();` trailing data | `BeforeHalt=1` | `BeforeHalt=1`, **`GhostAfterHalt=2`** |

**Mechanism, observed:** the scanner has no concept of PHP-mode. Everything after `?>` or `__halt_compiler()` is code to it. Dump: `class 'Ghost' body[153:200] methods=p, q`. **Observations are invented for classes that do not exist.** *Falsifier: agreement. There was none.*

### `NEW-8` · A variable named `$class` → **a fabricated class named after a keyword**

| Probe | PHP | Python |
|---|---|---|
| A8 `return $class instanceof self;` | `VarClass=2` | `VarClass=2`, **`instanceof=0`** |

**Mechanism, observed:** `CLASS_RE` matches inside `$class instanceof self`, captures `instanceof`, then binds it to the next `{` it can find — the dump shows `class 'instanceof' body[130:150]`, a slice of a *different method's* body, giving `0` methods. `$class` is ordinary PHP. *Falsifier: agreement. There was none.*

### `NEW-9` · Non-ASCII identifiers (legal PHP) → **lost property, lost method, lost class**

| Probe | PHP | Python | Lost |
|---|---:|---:|---|
| C2 `$this->über` shared by two methods | 2 | 🔴 3 | the **property** edge |
| C3b `function über()` called by `a()` | 1 | 🔴 2 | the **method node** and the edge |
| C6 `class Ünique` | `Ünique=2` | *nothing* | the **class** |

**Mechanism, observed:** every Python pattern anchors on `[A-Za-z_]`, so an identifier whose first character is outside ASCII never matches; PHP's identifier grammar admits it. One root cause, three consequence classes. Relevant to this estate specifically: German-language identifiers are ordinary here. *Falsifier: agreement. There was none.*

## 6 · ⭐ A methodological finding — `O-2`: **equal LCOM4 values are not evidence of equal analysis**

Probe **C3** (`function über()` invoked as `$this->über()`) **agrees at value 2** — and the agreement is an artefact. The dump shows Python's node set is `{a, c}` while PHP's is `{a, über, c}`: Python lost the *method* **and** lost the *edge*, and the two errors cancelled to the same component count.

**Consequence for how neutrality is evidenced:** a differential suite that compares only the metric can be defeated by compensating errors. `O-1` says a probe that cannot fail is not evidence; `O-2` adds that **a probe that can only observe the final number can fail to notice that it did fail**. Any future conformance suite should compare the intermediate analysis — node set and edge set — not only the LCOM4 value. **Registered as an observation, not a decision.**

**A second instance of the same trap:** probe **A6** (`$this?->shared`) **agrees at 3** — because *both* implementations miss nullsafe access. A real behavioural dependency is invisible to both. **Agreement here establishes agreement, not contract conformance.** The contract's included list is written with `->` and is silent on `?->`, so no one is provably wrong — and no one is provably right.

## 7 · Constructs tested with **no divergence** (54 agreeing probes)

Each was built to be able to fail; the stated falsifier is *divergence*, and none occurred.

* **Enums (E1–E5, M4, M5, D6, C4):** plain enum with cases + methods + a static · enum followed by a class · enum-case constant fetches and `Enum::from()` called from a class · enum implementing an interface with a `match` · enum with `self::CASE` arms and a `self::` static call · attribute on an enum case. **Both sides treat an enum as a non-unit and emit nothing for it** — consistently.
* **Interfaces (I1–I3, E4, C4):** interface with methods and a constant · interface plus its implementing class · abstract class with a body-less abstract method (**both count it as an isolated node** — a real risk of divergence that did not materialise) · attribute on an interface.
* **Traits as analysed units (T1–T5):** standalone trait · class `use`-ing a trait · **trait conflict-resolution block with braces inside the class body** (`insteadof` / `as`) · trait aliasing with a visibility change · trait with an abstract method and a static. **Both treat a trait as a non-unit and analyse only the class body**, matching the pinned `trait_methods` decision.
* **readonly / promoted properties (R1–R6):** promoted `readonly` constructor properties with two readers sharing one · `readonly class` · **property hooks in both arrow and braced form** (a brace block in the class body that is not a method) · attribute on a promoted constructor parameter, same line · `self::$staticProp` shared between methods (neither side makes it an edge).
* **Nested declarations (N1–N4, C1, D2):** named class inside a function · named class inside a method (both emit two observations, same order) · closure touching `$this` (both attribute it to the enclosing method) · named function inside a method (neither makes it a node) · braced `namespace {}` blocks · conditionally declared class.
* **match / enum-case (M1–M6):** `match` arms invoking `$this->m()` and `self::m()` · **arms containing strings that look like calls** (neither fabricates) · nested `match` followed by more methods (no brace-depth damage) · `match` over enum cases · `switch`/`case`.
* **Other:** first-class callables `$this->b(...)` and `self::b(...)` (both exclude, per the pinned decision) · nullsafe `$this?->x` (**agreement without established conformance — see §6**) · backtick shell-exec with interpolation (agree; the scanner's failure to blank backticks happens to coincide with PHP interpolating them) · multi-line attribute arguments · CRLF line endings · own-class call through a `use ... as` alias (both refuse) · a realistic 4-method service class · the ten golden fixtures.

## 8 · Are the three known divergences exhaustive? — **No**

> ## 🔴 **The defect surface is substantially wider than the three known divergences.**
>
> **Nine new divergences in nine distinct mechanisms**, found in one sitting of 76 probes. Three of the six families the PO/ARB named (enums, interfaces, traits, readonly/promoted, match) are **clean**; the divergences cluster elsewhere — and they cluster in the **scanner-vs-AST** seam, exactly where `N-2` predicted the fixture population was blind.

**What changed qualitatively, not just quantitatively:**

1. **New consequence classes.** The three known divergences are all *wrong values*. The new set adds **missing observations** (`NEW-3`, `NEW-9c`), **fabricated observations** (`NEW-7`, `NEW-8`), **fabricated class identities** (`NEW-1`), and a **positive false statement** — `value 0` / `"no analyzable methods"` for a populated class (`NEW-2`). A conformance suite that compares values on known classes cannot see four of these six.
2. **The surface is two-signed.** Python both invents relationships (heredoc, `NEW-4`, `NEW-7`, `NEW-8`) and loses real ones (`NEW-6`, `NEW-9`, K3, `NEW-2`). No monotone "conservative approximation" story is available.
3. **At least one item is not a Python defect.** `NEW-5` turns on a contract ambiguity, and on the stricter reading of the pinned decision it is **the PHP reference** that diverges. **A repair confined to the Python collector cannot close the surface.**
4. **Agreement is a weaker signal than it looks.** `O-2`: two probes agree while the underlying analyses differ (C3 by cancelling errors, A6 by a shared blind spot), and A2/A3 agree by unrelated accidents. **The 54 agreements in this report are evidence about 54 constructs — not about the constructs nobody has written down yet.**

**Stated at the strength of the evidence:** that the surface is *wider than three* is **`Observed`**. That it is now *fully characterized* is **not claimed** — see §9.

## 9 · Residual uncertainty

* **Exhaustiveness is still not established, and this report does not establish it.** A second sustained probing pass found nine new mechanisms after the first found three. **The rate of discovery has not fallen**, which is the signal that would justify believing the surface is closed. `Inferred`, not measured: more remain.
* **The search was hypothesis-driven, not systematic.** I derived probes by reading the Python scanner for structural weaknesses. That biases discovery toward defects a reader can *foresee*. A systematic method — differential fuzzing over generated PHP, or running both collectors across this repository's real `app/` tree and diffing — would cover cases no reader predicts. **Neither was performed.**
* **The contract's silences are unresolved and some agreements sit on top of them:** anonymous classes; whether an enum/interface/trait is a "class" for this metric; nullsafe `?->`; `\Own::m()` (`NEW-5`). Where the contract is silent, *no observed agreement can be called conformance*.
* **My independence is `Declared`, not attestable.** If the session routing was wrong, this entire report inherits the same weakness as its predecessors.
* **Explicitly UNTESTED — silence would not have counted as coverage:**
  * differential fuzzing / random PHP generation
  * both collectors run over the repository's real production code
  * BOM-prefixed or non-UTF-8 encodings
  * `goto` labels; `declare(ticks=…)`; PHP 8.5-only syntax (e.g. the pipe operator)
  * deeply chained interpolation (`{$this->a->b()}`) beyond A4/A5/D3
  * attributes in the remaining grammatical positions (parameters beyond R5, class constants, enum cases beyond D6, closures)
  * very large files, and any performance-driven behavioural difference
  * **traits / interfaces / enums as analysed units under a future contract ruling** — out of scope while the contract is silent
  * `first_class_callables` in `Own::m(...)` fully-qualified form
  * multi-file / cross-file resolution (the contract analyses classes in isolation by decision)

## 10 · Recommendation

> ## **A — proceed to implementation correction. With two conditions.**

The evidence is **not ambiguous** on the question this assignment was given: the three known divergences are **not** exhaustive, and the answer is `Observed`, not inferred. Further breadth verification of the same kind would keep finding defects in the same seam (`blank_noise` / regex-vs-grammar) without changing what the PO/ARB must decide. **Option B has produced its decision-relevant result; continuing it has diminishing returns.**

**Two conditions, because the naive repair does not close the surface:**

1. **`NEW-5` must be ruled on before it is "fixed."** It is a contract ambiguity in which the **PHP reference** is arguably the divergent party. Sending it to a Python-side repair would silently make the reference the definition of correctness — the exact failure mode `G-KOS-CONTRACT-EXP-AMD1` forbids: *"the experiment MUST NOT silently treat the current PHP implementation as the definition of correctness."*
2. **The repair's acceptance criterion should not be "the values agree."** Per `O-2`, two of this report's agreements are artefacts. **A corrected collector should be evidenced against the intermediate analysis — node set and edge set — not the LCOM4 number alone.** Otherwise compensating errors will pass.

**A further question the evidence raises but does not settle, and which I do not decide:** six of the nine new mechanisms are failures of *lexing PHP with regular expressions*, not failures of the cohesion contract. The original verification already put this to the ARB — *"whether contract neutrality can be claimed on a hand-written scanner at all, or whether the contract should require an AST-grade parser as a conformance precondition."* **This breadth pass supplies the evidence that question was waiting for: the defect surface is a property of the parsing strategy, not of the contract's cohesion rules.** Whether that means "repair the scanner", "require a real parser", or "the contract must state its lexical preconditions" is a PO/ARB decision.

**Not decided here, by constraint:** no repair · no contract, fixture, `expected.json`, PHP or Python modification · no Stage 3 · no acceptance · no verdict change · **no claim that the defect surface is closed.**

---

**BREADTH VERIFICATION COMPLETE · STOPPING.** This assignment is **not** completed by the process that performed it (G-1); closure is a Governance act on a PO/ARB decision.

**Traceability:** `.claude/runtime/workflow/KOS-CONTRACT-NEUTRALITY-001.json` seq 17–19 · grant `G-KOS-CONTRACT-STAGE2-BREADTH` · `scripts/observations/lcom4_collector.py` (`blank_noise` :59–99, `CLASS_RE`/`METHOD_RE` :121–123, `find_methods` depth arithmetic :134–148, `PROP_OR_CALL_RE`/`STATIC_CALL_RE` :155–157, `analyse_method` :163–193) · `scripts/observations/Lcom4Collector.php` (`observeClass` :53–104, `namesThisClass` :113–125) · `scripts/observations/examples/lcom4/expected.json` (seven pinned decisions) · Stage-2 independent verification `4d4738db` · completion/reproduction audit `f1ec1821` (`N-1`..`N-4`, `O-1`) · 76 scratch probes with both collectors' outputs and the Python scanner's dumped internal state, held in the session scratchpad at `/tmp/claude-1891886374/-home-d0f38614-c3a6-41d3-9952-7f59ad699b2d-roshyara-personal-nrna1/5e1dd9ee-6386-45e5-aa6c-06d4d7c54dbf/scratchpad/` (`probes/`, `compare.py`, `mech.py`, `php_run.php`, `full-run.txt`). **The corpus was kept as throwaway input per the grant and was NOT added to the repository.** If the PO/ARB wants it as durable evidence, promoting it is a separate authorized act.

**Integrity, verified after the run:** `git status` on `scripts/observations/` and `tests/` shows no modification; the pre-existing `__pycache__/lcom4_collector.cpython-313.pyc` retains its 2026-08-16 timestamp, so importing the collector for comparison rewrote nothing. The only repository addition is this report; the only workflow-record addition is the seq-19 START.
