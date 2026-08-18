# KOS-CONTRACT-NEUTRALITY-001 — **parsing architecture evaluation**

**Date:** 2026-08-18 · **Assignment:** `S4-architecture-parsing-evaluation` (role `architecture`, registered seq 20; predecessor `S1-verification-stage2-breadth`)
**Grant:** `G-KOS-CONTRACT-PARSING-ARCH` (`AUTHORIZED` — PO/ARB Decision 3, 2026-08-18)
**Type:** ARCHITECTURE PROPOSAL. **Not** implementation · **not** verification · **not** acceptance · **not** a selection.

> ⚠️ **Naming discrepancy, disclosed rather than resolved.** The START prompt names the assignment `S4-architecture-contract-parsing-architecture`; the registered assignment (seq 20) is **`S4-architecture-parsing-evaluation`**. They are the same commission — same grant, same predecessor, same four options. **I acted under the registered assignment and created no new assignment and no new grant.** Reconciling the label is a Governance act, listed in §13.

---

## 1 · Executive summary

> ### **Recommended: D — stratify the contract and put the conformance boundary at the fact model; realize the language layer with B (grammar-exact extraction) and complete the contract with C-as-completion. A is rejected as an architecture.**

**Four findings carry the recommendation. Three of them change what the question is.**

**① Not one of the twelve divergences is a disagreement about cohesion.** Classified by root cause (§4): **nine have a lexical root · two a structural/grammar root · one is a contract question · zero are disagreements about the cohesion model** the contract actually specifies. The seven pinned decisions — constructors, statics, `parent::`, first-class callables, traits, inheritance, own-class resolution — were independently re-implementable and **agreed everywhere the scanner could reach them**. `Observed`, from the breadth report's own data.

> **Consequence, and it is the central one: the experiment did not fail at contract neutrality. It failed at a layer the contract never specified.** The neutrality claim was implicitly being made over *PHP source comprehension*, which is not language-neutral, is not written down anywhere in the contract, and was never anybody's decision.

**② The reference is already Option B, and it still produced `NEW-5`.** `Lcom4Collector.php` uses `nikic/php-parser` v5.4.0 — a full AST. It diverges anyway, on the one item that is a contract question. **Measured cause:** `Lcom4Collector.php:115` calls `Node\Name::toString()`, which returns `Fq` for **both** `Fq` and `\Fq`; `toCodeString()` returns `\Fq`, and `isFullyQualified()` returns `true`/`false`. *(`Observed` — §3.3.)* **The distinction the pinned decision turns on is preserved by the parser and discarded by the accessor.** So the reference's `NEW-5` behaviour is **not a considered semantic decision; it is information loss at the extraction API.** ⇒ **An AST is necessary and demonstrably not sufficient.** Contract fidelity is lost or kept in the *extraction* step, whichever parser sits underneath.

**③ The defect list is not a backlog; it is an incrementally-discovered specification of the PHP lexical grammar.** All eleven code-side defects are instances of **one** architectural decision — *approximate a language grammar with regular expressions*. That is why the discovery rate has not fallen (three, then nine): **you cannot enumerate the complement of a grammar.** Option A proposes to keep discovering it. That search has no termination proof, so **A cannot bound its own residual risk** — and an option that cannot bound residual risk cannot support a neutrality *claim*, whatever its defect count reaches.

**④ Decision 2 already specified the intermediate representation; it did not call it one.** *"node set + edge set + final LCOM4"* **is** a three-layer fact model. Making it explicit — a language-neutral **fact schema**, a language-specific **extraction binding**, and cohesion rules over the schema — turns Decision 2 from an evidence rule into an *architecture*, and turns `O-2` (compensating errors) from a hazard into a structural impossibility. **The already-recorded `_future_layout` intent in `expected.json`** — *"specification/ + fixtures/ + implementations/{php,python,…} — the specification becomes the source of truth"* — **is this architecture, recorded 2026-08-04 and not yet executed.** D consumes it; it does not invent a second one.

**Feasibility, measured, not asserted:** the PHP-grammar-exact token stream resolves **every lexical construct in the defect surface** — `#[…]` is `T_ATTRIBUTE` and not a comment; heredoc/nowdoc are delimited by `T_START_HEREDOC`/`T_END_HEREDOC`; interpolated code appears as real `T_VARIABLE`/`T_OBJECT_OPERATOR` tokens *inside* the string; post-`?>` and post-`__halt_compiler()` text is `T_INLINE_HTML`; `$class` is `T_VARIABLE`, never `T_CLASS`; non-ASCII identifiers are ordinary `T_STRING`; `new class implements X` shows `T_NEW` before `T_CLASS`; and `\Fq` is `T_NAME_FULLY_QUALIFIED`, a **distinct token kind** from `T_STRING`. In all nine probes the count of genuine class-*declaration* keywords was exactly **1** — the real class, no ghosts, no keyword-named fabrications. *(`Observed` — §2.3.)*

**What this proposal does not do:** it selects nothing (that is the PO/ARB's act) · it does not write the `NEW-5` rule (Decision 1 reserved it) · it authorizes no implementation · it modified no contract, fixture, `expected.json`, collector, or test.

---

## 2 · Evidence base

### 2.1 · Process disclosure (`INV-ATTR-2` / `G-2` — **`Declared`, not attestable**)

| Bar | Status |
|---|---|
| Not the Python collector implementer / contract drafter (`claude-code-session:fbc084f0`) | **Declared — satisfied.** Wrote neither collector and no part of the contract. |
| Not the breadth verifier (`claude-code-session:5e1dd9ee`) | **Declared — satisfied.** |
| Not the completion/reproduction auditor (`claude-code-session:b260fb38`) | **Declared — satisfied.** |
| **Producing process, self-declared** | **`claude-code-session:1c8b041b`** — a fresh terminal that had performed no prior work on this work item. |

**Prior exposure that IS present, and disclosed:** the grant *requires* reading the Stage-2 FAIL verification, the completion audit and the breadth report, and I read all three. That exposure would disqualify me from **re-deciding** any of their verdicts. **I have re-decided none.** The seq-20 `executionContext` recommends separation from the implementer and the breadth verifier as an *experiment-validity* recommendation; on the declared identity it is satisfied. **`R-34`/`P-2` binds forward: this process must not verify this proposal.**

### 2.2 · Binding inputs read (read-only)

`scripts/observations/examples/lcom4/expected.json` — the contract, seven pinned decisions, `_caution`, `_future_layout` · `scripts/observations/Lcom4Collector.php` (180 lines, `nikic/php-parser`) · `scripts/observations/lcom4_collector.py` (279 lines, hand-written scanner) · the commission `2026-08-16-…-commission.md` · Amendment 1 `…-amendment-two-stage.md` · the Stage-2 authorization `…-stage2-authorization.md` · the Stage-2 evidence report · **the Stage-2 independent verification (`4d4738db`, FAIL — implementation defect)** · **the completion/reproduction audit (`f1ec1821`, `N-1`…`N-4`, `O-1`)** · **the breadth verification report (`17e4f066`, 76 probes, nine mechanisms, `NEW-5`, `O-2`)** · **Decisions 1–3 registration** · the workflow record `.claude/runtime/workflow/KOS-CONTRACT-NEUTRALITY-001.json` seq 1–21 and grants `G-KOS-CONTRACT-EXP`, `-EXP-AMD1`, `-STAGE1-VERIFY`, `-STAGE2`, `-STAGE2-VERIFY`, `-STAGE2-BREADTH`, `-PARSING-ARCH`.

### 2.3 · Measurements taken for this evaluation

**All read-only, in the session scratchpad. No repository artifact was created, modified or deleted other than this document.** These are **feasibility measurements for architecture options**, not verification of the defect surface — that verification is delivered and I do not re-decide it.

| # | Measurement | Result | Class |
|---|---|---|---|
| M-1 | Toolchain present | PHP **8.5.8** CLI · `nikic/php-parser` **v5.4.0** in `vendor/` · `token_get_all()` available · Python **3.13.2** | `Observed` |
| M-2 | **AST-grade PHP parser available to Python** | **NONE.** `tree_sitter`, `tree_sitter_php`, `tree_sitter_languages`, `phply`, `ply`, `antlr4`, `lark`, `pygments` — **all absent**; `pip list` yields no candidate | `Observed` |
| M-3 | **Token stream vs the lexical defect surface** — nine probes reproducing the divergent constructs | **All nine resolved.** `T_ATTRIBUTE` ≠ comment · `T_START_HEREDOC`/`T_END_HEREDOC` delimit · `T_CURLY_OPEN`+`T_VARIABLE`+`T_OBJECT_OPERATOR` expose interpolated code · `T_INLINE_HTML` covers post-`?>` and post-`__halt_compiler` · `$class`→`T_VARIABLE` · non-ASCII ident→`T_STRING` · `new class implements`→`T_NEW` before `T_CLASS`. **Declaration-`class` count = 1 in every probe** | `Observed` |
| M-4 | **`NEW-5` at both layers** | Lexer: `\Fq` → **`T_NAME_FULLY_QUALIFIED`** (distinct kind). AST: `FullyQualified('Fq')->toString()` = `Fq`, `->toCodeString()` = `\Fq`, `->isFullyQualified()` = `true`; plain `Name('Fq')` → `Fq`, `Fq`, `false` | `Observed` |
| M-5 | PHP 8.5 named token kinds | **152** | `Observed` |
| M-6 | **Estate exposure**, `app/` (1 620 PHP files) | `{$this->…}` complex interpolation: **26 files, 60 sites** · heredoc/nowdoc: **2 files** · attributes: **0 files** · non-ASCII *identifiers*: **0** (596 files contain non-ASCII bytes, in strings/comments) | `Observed` |
| M-7 | Estate exposure, repo-wide (`app scripts tests database routes config`) | attributes: **61 files** · `{$this->…}`: **71 files** · heredoc: **7 files** · `?>`: **2 files** | `Observed` |

**M-6 is the load-bearing exposure number and it cuts both ways, so it is stated plainly:** `NEW-6` (interpolation) is **live in production code today** — 26 files, 60 sites in `app/` where a real `$this->property` read or `$this->m()` call is invisible to the scanner. `NEW-9` (non-ASCII identifiers) and `K3`/`NEW-2`/`NEW-3` (attributes) are **latent, not currently active in `app/`**, though attributes are present in 61 files elsewhere and a Laravel 11 / PHP 8.2+ estate accrues them.

### 2.4 · What I deliberately did **not** do

**No prototype, no repair, no collector of any kind was written** — building a working alternative would be implementation, which this grant forbids. The token probes above establish *feasibility* and stop there. **No fixture, `expected.json`, contract, PHP or Python file was touched** (`git status` on `scripts/observations/` clean). **No verification of the defect surface was attempted or repeated.** **No option was selected.** **No `NEW-5` rule was written.**

---

## 3 · `NEW-5` — the semantic ambiguity, stated and returned

> **Decision 1 reserved the semantic rule to the PO/ARB. This section identifies what the contract states, what is ambiguous, what clarification is required, and what each plausible reading costs. It decides nothing.**

### 3.1 · What the contract currently states

Pinned decision `own_class_name_resolution`, verbatim:

> *"`OwnClass::m()` is recognized only when the name matches the class's own declared name **AS WRITTEN**; namespaced or aliased spellings are not resolved — the class is analyzed in isolation (known limitation)."*

And `intra_class_calls`, verbatim, on the same relationship:

> *"The relationship is determined by the behavioural dependency, **NOT** by the syntax used to express it. INCLUDED: `$this->m()`, `self::m()`, `static::m()`, `OwnClass::m()` — where m is declared in this class body."*

### 3.2 · What is ambiguous — and it is a **collision between two pinned decisions**, not a gap

**`intra_class_calls` says the relationship is *semantic*: syntax must not decide it.** **`own_class_name_resolution` says recognition is *syntactic*: the name must match *as written*.** For `\Fq::b()` inside `class Fq`, the two decisions point in opposite directions:

* by **behavioural dependency**, `\Fq::b()` is unambiguously a call to this class's own `b()` — an edge;
* by **spelling as written**, `\Fq` is not `Fq` — no edge.

**The contract does not say which decision governs when they conflict**, and the phrase "AS WRITTEN" is never defined against the PHP grammar's three name kinds — **unqualified** (`Fq`), **qualified** (`Ns\Fq`), **fully-qualified** (`\Fq`) — which the language itself distinguishes at the token level (`T_STRING` / `T_NAME_QUALIFIED` / `T_NAME_FULLY_QUALIFIED`, M-4).

### 3.3 · 🔴 The finding that changes the character of this question

**Neither implementation is executing a stated rule. Both are executing an accident of their extraction API.**

| | Mechanism | Effective rule |
|---|---|---|
| **PHP reference** | `Lcom4Collector.php:115` → `Node\Name::toString()` | **Strip a leading `\`, then compare.** `\Fq` matches `Fq`; `\App\Fq` does not. |
| **Python collector** | `STATIC_CALL_RE` char class `[A-Za-z_\\]` swallows the backslash into the qualifier | **Compare the raw lexeme including `\`.** `\Fq` never matches `Fq`. |

**M-4 measures the consequence precisely:** `toString()` returns `Fq` for `Fq` **and** for `\Fq`; `toCodeString()` distinguishes them; `isFullyQualified()` is a first-class predicate. **The information the pinned decision turns on is preserved by the parser and thrown away one line later, by an accessor chosen for convenience.**

> **So the true statement is not "PHP is right and Python is wrong", nor its converse. It is: *nobody has ever decided this, and two library-level defaults have been standing in for a decision.*** Decision 1's prohibition — *the PHP reference is not automatically authoritative* — is therefore not a procedural formality here. It is **measurably correct**: the reference's behaviour on `NEW-5` was never chosen.

### 3.4 · The plausible readings and their consequences

| | Reading | `\Fq::b()` in `class Fq` | Who diverges today | Consequence |
|---|---|---|---|---|
| **R1** | **Lexeme identity.** "As written" = the exact spelling; `\Fq` ≠ `Fq` | **no edge** | **PHP** | The reference must change (`toString()` → `toCodeString()`/`isFullyQualified()`). Coherent with "namespaced or aliased spellings are not resolved" and with "analyzed in isolation". **Cost:** the metric now depends on a spelling distinction users do not think of as semantic — `\Fq::b()` and `Fq::b()` produce different cohesion. |
| **R2** | **Behavioural dependency governs.** `intra_class_calls` outranks spelling; any name whose *final segment* is the own name is own-class | **edge** | **Python** | Coherent with the contract's own headline rule. **Cost:** it deletes "as written", and it over-matches — `\Vendor\Other\Fq::b()` in `class Fq` becomes an edge that is provably a *different* class. **This reading is unsound without namespace resolution, which "analyzed in isolation" forbids.** |
| **R3** | **The reference's de facto rule.** Strip exactly one leading `\`, then compare | **edge** | Python | Ratifies current PHP behaviour. **Cost:** it is a rule nobody stated, whose only justification is that a library method happens to do it; adopting it makes the reference authoritative by default — **the precise outcome `G-KOS-CONTRACT-EXP-AMD1` forbids**, and Decision 1 restated. |
| **R4** | **Resolve names properly** — track `namespace` and `use`, resolve to a canonical FQN, compare | **edge**, and correctly | Both | Semantically the strongest and the only reading under which `\App\Fq::b()` inside `namespace App; class Fq` is handled correctly. **Cost:** it repeals the pinned `own_class_name_resolution` limitation and weakens "the class is analyzed in isolation" — a **larger contract change**, with consequences for `inherited_methods` and `trait_methods` framing, and it requires the extractor to carry namespace context. |

### 3.5 · What clarification is required (the minimum, not the maximum)

1. **Rule the precedence:** when `intra_class_calls` ("dependency, not syntax") and `own_class_name_resolution` ("as written") conflict, **which governs?** Every other question here is downstream of that one.
2. **Define "as written" against the PHP name kinds** — unqualified / qualified / fully-qualified / relative (`namespace\Fq`) / aliased (`use X as Fq`) — and state the answer **per kind**, not as a single sentence. The grammar distinguishes five; the contract addresses one.
3. **State whether the rule is a *limitation* or a *decision*.** Today it is written as a "known limitation", which invites future repeal; if it is a deliberate boundary, say so.
4. **Rule the adjacent silences the same act should not leave open** (§13): anonymous classes · `?->` nullsafe · whether an enum/interface/trait is an analysed unit · first-class callables in fully-qualified form.

> **Returned to PO/ARB undecided, as Decision 1 requires.** **No repair may target `NEW-5`, and this proposal proposes none.** §8 shows how the recommended architecture makes each of these decidable *once, explicitly, in one place* — which is a different thing from deciding them.

---

## 4 · Defect-surface root-cause analysis

### 4.1 · The layer model this analysis uses

The disagreement between the two collectors is not one boundary but six, and naming them is what makes the option comparison possible:

```
 L0  bytes / encoding            ── PHP-specific
 L1  LEXICAL       tokens        ── PHP-specific  ┐  language substrate
 L2  STRUCTURAL    declarations, bodies, scopes  ─┘  (no contract content)
 ─────────────────────────────────────────────────  ← candidate conformance boundary
 L3  FACT MODEL    per unit: methods; per method: {props touched} {call targets + qualifier kind}
 L4  COHESION SEMANTICS   exclusions · what is an edge · what is a node   ← THE CONTRACT
 L5  METRIC        connected components + interpretation                  ← THE CONTRACT
```

**The contract's seven pinned decisions live entirely in L4–L5.** The `_variant` statement is L5. **Nothing in the contract text describes L1 or L2 at all** — and L1/L2 is where every code-side defect sits.

### 4.2 · The twelve divergences, classified

| # | Mechanism | Consequence class | **Root layer** | Closable by contract text alone? |
|---|---|---|---|---|
| `K1` | heredoc body scanned as code | fabricated edge | **L1** | no |
| `K2` | nowdoc body scanned as code | fabricated edge | **L1** | no |
| `K3` | `#[Attr]` same line as method → `#` read as comment | **lost node** | **L1** | no |
| `NEW-1` | `new class implements X` → `CLASS_RE` captures the keyword | **fabricated class identity** | **L2** (grammar) | no |
| `NEW-2` | same-line attribute on a multi-line method → opening brace blanked | **whole class → `value 0`, "no analyzable methods"** | **L1** root → **L2** cascade | no |
| `NEW-3` | same-line attribute on a class declaration | **class vanishes entirely** | **L1** root → **L2** cascade | no |
| `NEW-4` | heredoc containing a stray `{` | **boundary corruption; properties migrate between methods** | **L1** root → **L2** cascade | no |
| `NEW-5` | `\Fq::b()` | divergent edge; **PHP arguably the divergent party** | **CONTRACT** (+ L3 schema gap: qualifier kind unrepresented) | **yes — and only so** |
| `NEW-6` | `"…{$this->b()}…"` / `"$this->p"` | **lost real edges** (26 files / 60 sites live in `app/`) | **L1** | no |
| `NEW-7` | text after `?>` / `__halt_compiler()` | **fabricated classes** | **L1** (no PHP-mode) | no |
| `NEW-8` | a variable named `$class` | **fabricated class identity** | **L1/L2** | no |
| `NEW-9` | non-ASCII identifiers (legal PHP) | **lost property · lost method · lost class** | **L1** (identifier charset) | no |

**Plus, and kept separate because they are a different claim:**

| | Item | Class |
|---|---|---|
| **G-1** | anonymous classes — contract silent; PHP emits `(anonymous)`, Python omits or fabricates | **contract silence** |
| **G-2** | `$this?->x` nullsafe — **both** implementations miss it; agreement without conformance | **contract silence + shared L3 gap** |
| **G-3** | whether an enum / interface / trait is an analysed unit — both treat as non-unit, nobody ruled it | **contract silence** |
| **G-4** | first-class callable in fully-qualified form `\Own::m(...)` — untested | **untested** |
| **E-1** | `N-2`: the ten fixtures probe L4–L5 only; **no fixture touches L1–L2** | **evidence-method weakness** |
| **E-2** | `O-1`: a probe that cannot fail is not evidence | **evidence-method weakness** |
| **E-3** | `O-2`: equal metrics ≠ equal analysis (C3 compensating errors; A6 shared blind spot; A2/A3 coincidental agreement) | **evidence-method weakness** |

### 4.3 · Do the failures share an architectural cause? — **Yes. One cause, eleven symptoms.**

**Counts:** **9 lexical (L1) · 2 structural (L2) · 1 contract · 0 semantic-model.**

> **Every code-side defect is an instance of a single decision: *approximate a language grammar with regular expressions and hand-written brace arithmetic*.** `blank_noise` is a lexer that does not know about heredocs, attributes, interpolation, PHP-mode or Unicode; `CLASS_RE`/`METHOD_RE` are a parser that does not know about keyword context; `count("{") − count("}")` is a block-structure model that any brace inside any unrecognized literal defeats.
>
> **This explains the observation the grant told me to weigh — the discovery rate has not fallen (three, then nine).** The defect set is not a bug list of bounded size. **It is the complement of a formal grammar, discovered one construct at a time by whoever happens to think of one.** A complement of a grammar is not enumerable by inspection, so *no amount of probing establishes closure*, and the breadth report was right to refuse to claim it.

**Three corollaries the option comparison must respect:**

1. **The defect surface is two-signed** (`NEW-6`/`NEW-9`/`K3` lose real facts; `K1`/`NEW-4`/`NEW-7`/`NEW-8` invent facts). **No "conservative approximation" defence is available** for any option that keeps approximating.
2. **A fix at L1 fixes L2 cascades for free** (`NEW-2`, `NEW-3`, `NEW-4` are all L1 roots with L2 consequences), but **a fix at L2 does nothing for L1**. Ordering matters; M-3 confirms the L1 fix is available off the shelf.
3. **`NEW-5` is untouched by every L1/L2 improvement**, and `G-1`…`G-3` likewise. **A parsing decision alone cannot close this surface** — the grant's third evidence point, now with a mechanism: those items live at L3/L4, and the reference already has a perfect L1/L2 and diverges anyway (§1②).

---

## 5 · Option A — incremental repair of the current hand-written scanner

### 5.1 · What it can address

**Each named defect individually, and each is genuinely repairable in isolation:** teach `blank_noise` heredoc/nowdoc delimiters; distinguish `#[` from `#`; blank only non-interpolating string spans and descend into interpolations; track PHP-mode across `<?php`/`?>`/`__halt_compiler`; widen every identifier class from `[A-Za-z_]` to PHP's Unicode identifier grammar; require `CLASS_RE` not to be preceded by `new` or `$`. **Nothing here is exotic.**

### 5.2 · Residual risk — **unbounded, and that is the finding, not a caution**

**Repairing all twelve is not "fixing twelve bugs"; it is beginning to write a PHP lexer.** M-5 measures the target: **152 named token kinds** in PHP 8.5. The repaired scanner would still owe correct treatment of, at minimum: BOM and non-UTF-8 encodings · `goto` labels · `declare(ticks=…)` · nested/indented heredoc closing-marker rules · `${…}` and `{$…}` interpolation variants and their deprecations · escape sequences inside encapsed strings · backtick shell-exec · property hooks and asymmetric visibility (PHP 8.4) · the PHP 8.5 pipe operator · attributes in the grammatical positions nobody has probed (class constants, closures, parameters, enum cases). **The breadth report lists ten families it explicitly did not test; each is a candidate defect and none is a candidate proof.**

> **The decisive property: Option A cannot state its own residual risk.** Every other option can — B/D delegate L1–L2 to already-validated software and inherit *its* defect rate; C bounds risk by narrowing the claim. **A's risk is "however many constructs nobody has thought of yet", which is not a number and cannot be put in front of the PO/ARB.** For an experiment whose entire product is a *claim about trustworthiness*, an unquantifiable residual is a first-order defect and not a second-order one.

### 5.3 · Grammar coverage · maintenance · future syntax

**Grammar coverage:** partial by construction and permanently so — the scanner is a hand-maintained approximation whose accuracy is only ever known where someone wrote a probe. **Maintenance burden:** every PHP release is a potential silent regression, and **silent is the operative word** — `NEW-3` shows the failure mode is a *missing row*, which no value-comparison suite detects. **Future syntax resilience: the weakest of the four.** The scanner must be edited for each grammar change, by someone who noticed the grammar changed; `token_get_all()` tracks the grammar because it *is* the engine's lexer (M-1, M-3).

### 5.4 · Against the Decision-2 evidence rule

**Satisfiable, and misleading if satisfied naively.** The scanner already computes a node set and an edge set internally; exposing them is mechanical. **But under A the node set is derived from a corrupted structure** — `NEW-4` puts `$this->n` in the wrong method, `NEW-2` empties the class. **Exposing the IR under A yields excellent *diagnosis* and no additional *correctness*.** That is a real benefit (it converts `O-2` compensating errors into visible discrepancies) and it must not be read as conformance.

### 5.5 · Interaction with the standing decisions

**A cannot address `NEW-5` and, per Decision 1, must not try.** A repair programme framed as "close the divergences" would sweep `NEW-5` in with the rest and thereby **encode an undecided semantic as settled — the exact failure Decision 1 exists to prevent.** If A is chosen, the `NEW-5` carve-out must be explicit in the authorization, not left to the implementer's memory of this document.

> **Assessment: A is a legitimate *tactical* response and an illegitimate *architecture*.** It reduces a measured, live defect (`NEW-6`, 26 files) quickly and cheaply. It cannot support the claim the work item exists to make.

---

## 6 · Option B — AST-grade parser replacement or augmentation

**"Option B" is three different architectures with three different independence properties, and the evaluation is unusable until they are separated.**

### 6.1 · B1 — **shared front-end**: one grammar-exact extractor (PHP-side) emits facts; all implementations consume them

**Closes:** L1 **and** L2 entirely — 11 of 11 code-side defects, by construction, for every implementation at once.
**Semantic fidelity:** highest available; the AST *is* the grammar.
**Cost that must be stated first:** **it collapses the very independence the experiment measures.** If both implementations read the same extractor's output, their agreement is a property of one program, not evidence about the contract — the circularity the Stage-2 method binding was written to avoid (*"implement FROM THE CONTRACT TEXT — not by porting"*).
**And the disqualifier is conditional, not absolute:** the circularity exists **only if the neutrality claim is asserted over L1–L5.** If the contract explicitly places the boundary at L3 and says so, a shared extractor is *below* the claim and no longer circular. **That relocation is Option D.** ⇒ **B1 is not viable standalone; it is viable as D's language layer.**

### 6.2 · B2 — **independent AST per language**: each implementation brings its own PHP-grammar parser

**The only option that preserves full-stack independence.** It is also **the one currently blocked, and by facts rather than by judgement:**

* **`Observed` (M-2): no AST-grade PHP parser is present on the Python side** — not `tree_sitter`, not `tree_sitter_php`, not `phply`, nothing.
* **Adding one requires Python dependency management, which every grant in this lane forbids** (`pyproject` / `setup` / `requirements` named explicitly in `G-KOS-CONTRACT-EXP` and `G-KOS-CONTRACT-STAGE2`). **B2 therefore cannot be chosen without a governance act amending the experiment's constraints.** That is a decision for the PO/ARB, listed in §13 — not a technical obstacle I can route around.
* **Candidate maturity, `Inferred` and flagged as such** — I could not measure it, having installed nothing: `tree-sitter-php` is an actively maintained PHP-8-capable grammar with Python bindings and is the strongest candidate; `phply` is PLY-based and PHP-5-era, and would reintroduce a grammar lag — i.e. a *new* version of the same defect class, in a dependency rather than in our code. **If B2 is selected, verifying the candidate's actual grammar coverage against PHP 8.5 is a precondition, not a detail.**

**Second-order property, worth weighing:** under B2 the *parsers* are independent but almost certainly **not equally correct**, so divergences would then be attributable to *two vendors' grammar coverage* rather than to the contract. **B2 buys independence at the cost of making disagreements harder to interpret, not easier.**

### 6.3 · B3 — **shared lexer, independent parser**: the token stream is the boundary

**The middle term, and it is stronger than its modesty suggests.** PHP ships `token_get_all()` (M-1): the engine's own lexer, exact by construction, 152 token kinds (M-5).

* **`Observed` (M-3): the token stream resolves all nine lexical constructs, and the two structural ones become trivial** — `new class …` is `T_NEW` before `T_CLASS`; `$class` is `T_VARIABLE`; a declaration-`class` count of exactly 1 was measured in every probe. **11 of 11 code-side defects close.**
* **What stays independent:** L2 structure (what is a class body, where does a method end), L3 fact extraction, L4 semantics, L5 metric — **which is where 100 % of the contract's content lives.**
* **What is shared:** only the token stream — a lower-level, easily-audited artifact than an AST, and one that embeds **no** contract semantics (a token stream cannot express "constructors are excluded").
* **Cost:** the consumer still needs PHP to produce tokens, so the Python side depends on a PHP process or a serialized token dump. That is an operational coupling, and it is the honest price.

> **B3 is the cheapest architecture that closes the entire code-side defect surface while leaving every contract-bearing layer independently implemented.** It is a strong candidate on its own and a natural realization of D's language layer.

### 6.4 · What no variant of B fixes

**`NEW-5`, `G-1`, `G-2`, `G-3`.** The reference is already B and produced `NEW-5` (§1②, M-4). **B moves the failure from "the scanner could not see it" to "the extractor normalized it away" — a better failure, and still a failure.** Any B must therefore be paired with an explicit L3 fact schema that names what the extractor must preserve. **That pairing is Option D.**

> **"More sophisticated" is not the argument for B, and I decline to make it.** The argument is narrow and measured: **B moves L1/L2 correctness from *hand-maintained* to *delegated to already-validated software*, which converts an unbounded residual risk into an inherited and bounded one.** Everything else B is credited with is really D's.

---

## 7 · Option C — explicit contract lexical limitations

**C has two forms that share a name and must not share a verdict.**

### 7.1 · C-as-substitute — the contract excludes the constructs the scanner gets wrong ❌ **Reject**

**What it would have to exclude,** derived from the surface rather than guessed: heredoc and nowdoc bodies · **all attribute usage** (or all same-line attribute usage) · **double-quoted string interpolation** · inline-HTML and post-`__halt_compiler` regions · **non-ASCII identifiers** · anonymous classes · code containing a variable named `$class` · fully-qualified own-class references.

**Are those exclusions acceptable to the experiment's purpose? — No, and the measurements say why.**

* **`{$this->…}` is live in `app/` today: 26 files, 60 sites (M-6); 71 files repo-wide (M-7).** Excluding interpolation does not remove a defect; it **relabels 60 real, contract-defined relationships as out of scope**, and the `_caution` line already warns that values on real classes are *"observations pending this suite's conformance"*. Those values would then be pending forever, by construction.
* **Attributes are ordinary PHP 8 and ordinary Laravel 11.** `app/` shows 0 today, 61 files elsewhere (M-6/M-7) — so the exclusion's cost is **rising**, not stable.
* **Non-ASCII identifiers are legal PHP** and this is a German-language estate. Excluding them is excluding the language, not scoping the metric.
* **"Code containing a variable named `$class`"** is not a construct anyone can reason about as a domain boundary. Writing it into a contract would be an admission in the form of a rule.

**Would it weaken the contract-neutrality claim? — It would invert it.** The claim would become *"the contract is neutral over the subset of PHP that our weakest implementation can lex"*, which is **circular**: the contract's scope would be defined by an implementation defect. **`G-KOS-CONTRACT-EXP-AMD1` forbids treating the current PHP implementation as the definition of correctness; C-as-substitute does something structurally identical with the *Python* implementation** — it makes an implementation's limits definitional. **Legitimate domain decision or escape from implementation defects? On this evidence: escape.**

### 7.2 · C-as-completion — the contract states its preconditions and rules its silences ✅ **Required under every option**

**A different act entirely, and the contract is measurably incomplete without it:**

1. **State the conformance precondition.** *"A conformant implementation must resolve PHP source to at least the fidelity of the language's own lexical grammar"* — i.e. the contract declares **where its neutrality boundary is** and what an implementation must be able to do to make the claim. **The contract currently asserts language-neutrality while saying nothing about the language layer at all** — the gap this whole episode has been probing.
2. **Rule the silences:** anonymous classes (`G-1`) · nullsafe `?->` (`G-2` — *both* implementations miss it, so agreement there is currently worth nothing) · enum/interface/trait as analysed units (`G-3`) · `NEW-5`'s five name kinds (§3.5) · first-class callables in FQ form (`G-4`).
3. **State the evidence model,** which Decision 2 already did — and which belongs *in the contract*, not only in a decision register.

> **Verdict: C is not a competing option. As a substitute it is rejected on measured exposure; as a completion it is mandatory work that A, B and D all require.** The four-way framing hides this, and the hiding matters: a matrix that scores C against A and B invites reading "C loses" as "the contract needs no change", which is the opposite of what the evidence shows.

---

## 8 · Option D — **contract stratification: put the conformance boundary at the fact model**

> **Proposed because the evidence requires it: A, B and C each answer a *sub*-question, and none of them answers the question the work item asked — *what is the neutrality claim over?* Until that is answered, no parser choice can be right, because the parser's job is defined by where the boundary sits.**

### 8.1 · The problem D solves, that A/B/C do not

**The contract asserts a neutrality claim without stating its own scope.** Every actor has silently assumed a different scope: the Stage-2 method binding assumed L1–L5 (implement everything from the text, including PHP comprehension); the pinned decisions describe only L4–L5; Decision 2's evidence model implies L3+L4+L5. **The experiment has been measuring a claim nobody wrote down.** A/B/C all take the scope as given and argue about tooling underneath it.

### 8.2 · The architecture

**Three artifacts replace one, and the split is along the boundary that already exists in the evidence:**

**① The fact schema (L3) — language-neutral, normative.** For each *analysed unit*: its identity and kind; its declared methods. For each method: the set of `$this` properties touched; the set of call sites, each carrying **target name + qualifier kind** (`self` · `static` · `parent` · unqualified · qualified · fully-qualified · relative · aliased · dynamic/not-determinable) + **callable-vs-invocation**. **This is the node set and edge-input set that Decision 2 already mandates comparing.**

**② The extraction binding (L0–L2 → L3) — per language, normative *for that language*.** For PHP: what a class/enum/interface/trait declaration is; that `#[…]` is an attribute and not a comment; that encapsed strings contain code; that post-`?>` text is not code; PHP's identifier grammar; the five name kinds. **This document is where every one of the twelve divergences becomes a *stated rule* rather than a discovered defect.**

**③ The cohesion rules (L3 → L4 → L5) — language-neutral, normative.** The seven pinned decisions, restated over the fact schema rather than over PHP syntax. `intra_class_calls` becomes *"a call site whose qualifier kind is in {self, static, own-name-as-written} and whose target is a declared method of this unit"* — and **`NEW-5` becomes a line in a table of qualifier kinds with a ruled answer, instead of an accident of `Name::toString()`.**

**The conformance boundary is declared at ②/③.** *Contract neutrality* = **an independent party, reading ① and ③ alone, produces the same node set, edge set and metric.** Extraction (②) is stated, testable, and explicitly **below** the claim — so it may be shared (B1/B3) without circularity, because the contract *says* it is shared.

### 8.3 · Why this is consumption, not invention — **Canonical Discovery**

**It already exists in the repository, twice, unexecuted:**

* **`expected.json` `_future_layout`, recorded 2026-08-04:** *"When a second implementation language arrives, this evolves to `specification/` + `fixtures/` + `implementations/{php,python,…}` — **the specification becomes the source of truth**, every implementation executes it."* **The second language has now arrived.** The trigger condition the estate wrote down for itself has fired.
* **Decision 2, adopted 2026-08-18:** node set + edge set + final metric. **That is ①/③ stated as an evidence rule.** D adds nothing to it except the recognition that an evidence model over an intermediate representation *is* an architecture, and cannot be honoured by implementations that have no such representation.

**No new bounded context, aggregate, port or domain event is proposed. No new capability is created.** This is the existing conformance-contract capability, **stratified**.

### 8.4 · What D closes

| Defect class | Closed by | How |
|---|---|---|
| **L1 lexical (9)** | ② + B1/B3 underneath | delegated to the language's own lexer; **`Observed` feasible, M-3** |
| **L2 structural (2)** | ② | grammar-exact declaration/body model |
| **`NEW-5` (1)** | ① + ③ | qualifier kind is a **schema field**; the rule is a ruled row, not a library default. **D makes it decidable; the PO/ARB decides it** |
| **`G-1`…`G-4` silences** | ① + ③ | "analysed unit kind" and "call-site kind" are schema fields; a silence becomes a missing table row, which is visible |
| **`O-2` compensating errors** | Decision 2, structurally | comparison at L3/L4 makes cancelling errors **impossible to hide**, not merely unlikely |
| **`N-2` blind fixtures** | fixtures × expected-IR | a fixture now pins node and edge sets, so an L1/L2 defect fails a fixture **even when the metric happens to match** |
| **Unbounded residual risk** | boundary placement | residual is confined to ② and inherited from validated software, so it is **stateable** |

### 8.5 · Honest costs

**D is more work than A and more thinking than B.** It requires writing two documents that do not exist (① and ②) and re-expressing the seven pinned decisions over a schema. **It requires the PO/ARB to make a scope decision that has been avoided so far** (§13.1) — which is a cost, because it cannot be delegated. **And it reduces what "contract neutrality" claims:** the honest claim becomes *"the cohesion model is independently implementable"*, not *"anyone can reimplement PHP comprehension from our text."* **That reduction is not a weakening of this proposal — it is the finding. The larger claim was never true, and the twelve divergences are what it looks like when a system tries to make it.**

---

## 9 · Conformance evidence architecture (Decision 2, per option)

**Decision 2 in force: node set + edge set + final LCOM4. Final-metric equality alone is insufficient.**

| | **A** repaired scanner | **B1/B3** grammar-exact | **B2** independent ASTs | **D** stratified |
|---|---|---|---|---|
| **Reproducibility** | possible; both collectors are deterministic and pure over source | same | same | same — **and additionally the IR is a durable artifact**, so a run's evidence outlives the run |
| **Differential testing** | PHP-vs-Python at three layers; the breadth report's 76-probe corpus is the natural seed *(promotion is a separate act, §13.6)* | same, with the disagreement space reduced to L3+ | **richest** — L1/L2 also differ, so disagreements are informative about parser coverage… | **strongest** — the *specification* is testable independently of any implementation; new languages join by executing ①/③ |
| | | | …**and are hardest to attribute** (contract? our code? a vendor's grammar gap?) | attribution is unambiguous by layer |
| **IR visibility** | **retrofit** — the scanner has node/edge sets internally; exposing them is mechanical, and they will be **derived from a corrupted structure** | retrofit, over a sound structure | retrofit ×2 | **native** — the IR *is* the interface; nothing to retrofit |
| **Compensating-error detection (`O-2`)** | detected once the sets are exposed (C3 would have shown `{a,c}` vs `{a,über,c}` immediately) | detected | detected | **structurally impossible to hide** — conformance is *defined* over the sets |
| **Can it satisfy Decision 2 at all?** | **yes** | **yes** | **yes** | **yes, and it is the only option where Decision 2 is the architecture rather than an addition to it** |

> **Two observations that belong to the PO/ARB rather than to the option comparison.**
> **①** Decision 2 is **orthogonal to the parser choice** — every option can satisfy it, so it must not be used as a tie-breaker between them. **What it does do is make an option's failures visible**, and that is worth more under A (where failures continue) than under D (where they are prevented).
> **②** `G-2` (nullsafe) shows the limit of differential evidence in general: **both** implementations miss `?->`, so no comparison of the two can ever surface it. **Differential testing detects disagreement, never shared blindness.** Only the contract — ruling `?->` explicitly — closes that class, under every option. **This is an independent argument for C-as-completion (§7.2), and it does not depend on which parser wins.**

---

## 10 · Decision matrix

**Scale: ✅ strong · 🟡 partial/conditional · 🔴 weak/blocked.** Every cell is a judgement on the evidence in §2–§9, not a preference.

| Criterion | **A** repair scanner | **B1** shared AST | **B2** independent ASTs | **B3** shared lexer | **C** as substitute | **C** as completion | **D** stratified (B3/B1 + C-completion) |
|---|---|---|---|---|---|---|---|
| **Contract fidelity** | 🔴 fidelity is whatever the regexes happen to do | 🟡 high at L1/L2; **`NEW-5` shows L3 fidelity is not implied** | 🟡 same | 🟡 same | 🔴 fidelity redefined to match the defect | ✅ makes fidelity *statable* | ✅ fidelity is specified at L3 and testable |
| **PHP grammar coverage** | 🔴 partial, hand-maintained, unknown where unprobed | ✅ exact (engine grammar) | 🟡 vendor-dependent; **must be verified** (`Inferred`) | ✅ exact (engine lexer, M-3) | 🔴 coverage reduced by fiat | n/a | ✅ exact, and **declared** in ② |
| **Defect-surface resilience** | 🔴 **cannot bound residual risk** (§5.2) | ✅ 11/11 code-side | ✅ 11/11 per implementation | ✅ 11/11 (M-3) | 🔴 defects renamed, not removed | 🟡 closes the *contract* class only | ✅ 11/11 + contract class + silences |
| **Evidence reproducibility (Decision 2)** | 🟡 satisfiable; sets derived from corrupted structure | ✅ | ✅ | ✅ | 🟡 | n/a | ✅ **native** |
| **Maintainability** | 🔴 grows with every construct discovered | ✅ library-maintained | 🟡 two grammars to track | ✅ engine-maintained | 🟡 low code cost, high semantic debt | ✅ | ✅ change lands in one of three named places |
| **Future syntax resilience** | 🔴 manual, and silent when missed | ✅ follows PHP releases | 🟡 follows two vendors' release cadences | ✅ follows PHP releases | 🔴 exclusion list must keep growing | n/a | ✅ inherited at ②; ① and ③ are unaffected by syntax |
| **Migration cost** | ✅ lowest | 🟡 rewrite the Python fact layer | 🔴 **blocked**: no parser available (M-2) + grant forbids Python packaging | 🟡 moderate; needs a PHP process/token dump | ✅ lowest (text only) | 🟡 contract-writing effort | 🔴 highest — two new documents + restated decisions |
| **Risk** | 🔴 continued silent wrong numbers on live code (26 files, M-6) | 🟡 **circular unless the boundary is declared** | 🟡 disagreements hard to attribute | 🟡 operational coupling to PHP | 🔴 contract scope defined by a defect | ✅ low | 🟡 scope decision cannot be delegated |
| **Ability to demonstrate neutrality** | 🔴 a claim on an unbounded residual is not a claim | 🔴 **destroys the claim** standalone | ✅ strongest *if* unblocked | ✅ strong at L2–L5, **explicitly not at L1** | 🔴 inverts the claim | 🟡 necessary, not sufficient | ✅ **the claim becomes precise, scoped and testable** |

**How to read the matrix, because a naive reading misleads in three places:**

1. **B1's 🔴 and D's ✅ differ by one sentence in the contract.** Same code, same shared extractor — the only difference is whether the contract *declares* the boundary. **That is the whole finding, in one row.**
2. **D's 🔴 on migration cost is real and is not a tie-breaker.** The alternative to paying it is making a neutrality claim nobody can state the scope of — which is what the last three verification cycles have been spending effort on.
3. **C-as-completion has no losing column.** It is a *component*, and the matrix scores it only to make explicit that no option ships without it.

---

## 11 · Recommended architecture

> ## **D — stratify the contract; conformance boundary at the fact model. Language layer realized by B3 (shared engine lexer), with B1 available for the PHP-side extractor. C-as-completion is mandatory. A is rejected as an architecture and is available only as an explicitly-labelled stopgap.**

**The recommendation in one line:** *stop claiming that PHP comprehension is language-neutral, state where the neutrality boundary is, and delegate everything below it to software that already knows the grammar.*

**Why D and not B alone** — B is the *implementation* of D's language layer, and the reference proves B alone is insufficient: it is already B and produced `NEW-5` (M-4). **Why D and not A** — A cannot state its residual risk (§5.2), and a work item whose product is a trustworthiness claim cannot rest on an unstatable residual. **Why D and not C-as-substitute** — the exclusions are live in production (26 files / 60 sites, M-6) and the scope would be defined by a defect (§7.1). **Why B3 rather than B2 as the default realization** — B2 is `Observed`-blocked today (M-2) and would require a governance act to unblock; B3 closes the same 11 defects with software already present (M-1, M-3) and keeps every contract-bearing layer independent.

**Staging, so the recommendation is actionable without being an authorization:**

| Stage | Content | Depends on |
|---|---|---|
| **S-0** | **PO/ARB rules the scope question** (§13.1) and the `NEW-5` semantics (§3.5) | nothing — **this is the gate** |
| **S-1** | Write **① the fact schema** and **③ the cohesion rules over it** (restating the seven pinned decisions; adding the ruled silences) | S-0 |
| **S-2** | Write **② the PHP extraction binding** — the twelve divergences become stated rules | S-1 |
| **S-3** | Extend the conformance suite: fixtures × **expected IR** + expected metric; seed from the 76-probe corpus (§13.6) | S-1 |
| **S-4** | Implementations conform to ①/③; extraction per ② | S-2, S-3 |

**Each stage is a separate authorization. This proposal authorizes none of them.**

**A dissent I record against my own recommendation, because the PO/ARB should have it:** if the estate's actual need is *"trustworthy LCOM4 numbers on our own code, soon"* rather than *"a defensible language-neutrality claim"*, then **A is the better answer to that need** and D is over-engineering. **The two needs have been travelling together under one work item since 2026-08-16 and have never been separated.** Which one is being served is a PO/ARB judgement, not mine — §13.2.

---

## 12 · Consequences for implementation architecture

**Stated as consequences of the recommendation, per the grant. None is authorized, proposed as a next action, or begun.**

**If D is selected:**

1. **`expected.json` splits** along the layout it already anticipates: `specification/` (① fact schema · ③ cohesion rules) · `bindings/php.md` (②) · `fixtures/` · `implementations/`. **The single JSON file stops being both specification and expectation.**
2. **Both collectors gain an IR boundary.** `Lcom4Collector.php` already has one implicitly — its `$methods` array *is* the fact model — so PHP-side change is largely *exposing* it plus replacing `toString()` with a qualifier-kind-preserving accessor (`isFullyQualified()` / `toCodeString()`, M-4). **The Python collector's `blank_noise`/`CLASS_RE`/`METHOD_RE` layer is superseded rather than repaired.**
3. **The Python collector's future is a consequence, not a decision here.** Either it becomes an L3→L5 implementation consuming the IR (preserving its value as neutrality evidence, at the reduced and honest scope), or it is retired as an experiment artifact whose purpose is served. **Both are PO/ARB calls.**
4. **The conformance suite gains a second assertion layer** (expected node/edge sets). Existing fixtures keep their metric expectations unchanged; **only additions are implied, no restatement of a pinned value.**
5. **The `_caution` line gains a referent.** *"Values on real classes are observations pending this suite's conformance"* becomes checkable, because conformance acquires a definition.
6. **`NEW-5`'s ruling, whatever it is, lands in exactly one place** (③'s qualifier-kind table) and both implementations inherit it. **Under any other option it lands in two implementations separately, which is how it diverged in the first place.**

**If A is selected instead:** the `NEW-5` carve-out must be explicit in the authorization (§5.5); the acceptance criterion must be node+edge+metric, never metric alone (Decision 2, `O-2`); and the authorization should state that A is a **bounded remediation, not a conformance architecture**, so that a later neutrality claim is not built on it by default.

**Under every option:** **C-as-completion is owed** — the contract must state its preconditions and rule `G-1`…`G-4`. **`G-2` (nullsafe `?->`) cannot be closed by any parser choice or any differential test**, because both implementations share the blindness (§9②).

---

## 13 · Open decisions for PO/ARB

> **All are returned undecided. This proposal decides none of them, and decides nothing else.**

**13.1 · 🔴 The scope question — *what is the contract-neutrality claim over?* (the gate; everything else is downstream).**
&nbsp;&nbsp;&nbsp;&nbsp;**(a)** L3→L5 — *the cohesion model is independently implementable* ⇒ D/B3/B1 viable, extraction may be shared. **(b)** L1→L5 — *PHP comprehension is independently implementable too* ⇒ **only B2**, which requires 13.4. **The parsing architecture cannot be selected before this is answered, because the parser's job is defined by the answer.**

**13.2 · Which need is this work item serving?** A defensible neutrality *claim*, or trustworthy LCOM4 *numbers on this estate*? **26 files / 60 sites in `app/` are currently mis-measured by the Python collector (M-6).** If the second need is urgent, A-as-stopgap is a legitimate parallel act — and should be labelled a stopgap in its authorization, not allowed to become the architecture by default.

**13.3 · The `NEW-5` semantic rule** — §3.5: rule the precedence between `intra_class_calls` and `own_class_name_resolution`; define "as written" per PHP name kind; state whether it is a limitation or a decision. **Reserved to the PO/ARB by Decision 1 and left reserved here.**

**13.4 · Does the experiment's constraint set still forbid Python dependency management?** Every grant in this lane forbids `pyproject`/`setup`/`requirements`. **B2 is blocked by that constraint, not by any technical fact** (M-2). If 13.1 answers **(b)**, this must be amended; if it answers **(a)**, the constraint can stand.

**13.5 · Rule the four contract silences** — `G-1` anonymous classes · `G-2` nullsafe `?->` (**no differential test can ever surface this**) · `G-3` enum/interface/trait as analysed units · `G-4` FQ first-class callables. **Every option needs these; none of them is a parsing question.**

**13.6 · Promote the 76-probe breadth corpus into the repository?** It is currently throwaway scratchpad, offered by the breadth report as a separate authorized act. **It is the natural seed for the differential/IR suite under every option**, and it is the only artifact in this lane that would be *lost* by inaction.

**13.7 · Should Decision 2's evidence model be written into the contract?** It is currently a decision-register entry. **It is a conformance rule, and conformance rules belong in the conformance contract.**

**13.8 · Governance bookkeeping** — reconcile the assignment label (`S4-architecture-contract-parsing-architecture` in the START vs `S4-architecture-parsing-evaluation` registered at seq 20); and note that `php scripts/doc-placement.php --scope=product-specific --domain=knowledgeos` derives `docs/knowledgeos`, while **this work item's ten prior artifacts and all five prior `KOS-*` architecture proposals sit in `docs/publicdigit/reviews/`**. I followed the lane's precedent rather than split the work item across roots; **whether the lane's root is correct is a Governance question I raise and do not resolve.**

---

## 14 · What this proposal did not do

**No option selected** (the selection is the PO/ARB's act) · **no `NEW-5` rule written** (Decision 1 reserved it) · **no implementation authorized or performed** · **no repair of either collector** · **no modification of the contract, `expected.json`, fixtures, the PHP reference, the Python collector, any test, or any prior report** · **no prototype built** · **no verdict re-decided** — the Stage-2 `FAIL` stands as delivered, the breadth findings stand as delivered · **no claim that the defect surface is closed** (the discovery rate has not fallen; `Inferred`: more remain) · **no acceptance and no self-verification** (`R-34`/`P-2`) · **no assignment or grant created** · **this assignment is not completed by this document** (`G-1`) — closure is a Governance act on a PO/ARB decision.

---

**ARCHITECTURE PROPOSAL COMPLETE · STOPPING.**
**Next actor: PO/ARB — decide the scope question (13.1), select the parsing architecture, and resolve the `NEW-5` semantics (13.3).**

**Traceability:** grant `G-KOS-CONTRACT-PARSING-ARCH` · assignment `S4-architecture-parsing-evaluation` (seq 20) · handoff seq 21 · Decisions 1–3 registration (2026-08-18) · breadth verification `17e4f066` (76 probes, nine mechanisms, `NEW-5`, `O-2`) · Stage-2 independent verification `4d4738db` (FAIL) · completion/reproduction audit `f1ec1821` (`N-1`…`N-4`, `O-1`) · Stage-2 authorization and Amendment 1 (`G-KOS-CONTRACT-EXP-AMD1`: *"MUST NOT silently treat the current PHP implementation as the definition of correctness"*) · commission 2026-08-16 · `scripts/observations/examples/lcom4/expected.json` (seven pinned decisions, `_caution`, `_future_layout`) · `scripts/observations/Lcom4Collector.php` (`namesThisClass` :113–125, `toString()` :115) · `scripts/observations/lcom4_collector.py` (`blank_noise` :59–99, `CLASS_RE`/`METHOD_RE` :121–122, `find_methods` :134–148, `STATIC_CALL_RE` :156) · measurements M-1…M-7, taken read-only in the session scratchpad (`lexprobe.php`, `lexprobe2.php`, `astprobe.php`), **not added to the repository** · `R-34` · `P-2` · `G-1` · `G-2` · `INV-ATTR-2` · `ES-005.4` (Canonical Discovery — §8.3).
