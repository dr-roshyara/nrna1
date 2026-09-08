# `P-36` — Identity Resolution Cardinality & `K1` Persistence Necessity Audit

**2026-09-08 · Lane T · INDEPENDENTLY EXECUTED.** After [`53` `P-35`](./53-P35-K1-IDENTITY-TARGET-MEANING-VS-RECORD-AUDIT.md).

# 0. Executive verdict

$$\boxed{\begin{array}{c}\mathbf{MODEL\ C} \textbf{ — a specific SUBSET. } K1 \textbf{ must retain the } \mathbf{POSITIVE\ ADJUDICATIONS}\textbf{; } \mathit{Unknown} \textbf{ requires NO retention of its own.}\\[6pt] \boxed{\mathit{Unknown} = \mathit{NotKnownSame} \wedge \mathit{NotKnownDifferent} = \textbf{THE ABSENCE OF BOTH ADJUDICATIONS — retaining it adds nothing over retaining nothing.}}\end{array}}$$

⭐⭐⭐ **The `[EMP]` core is `§25S.7`, boxed:** *"This is **not simply** `SameAs = False`. There is a
difference between **`KnownDifferent`** and **`NotKnownSame`** … `¬SameAs(x,y) \neq DistinctFrom(x,y)`
— **unless the domain explicitly uses a closed-world identity rule**."*

⇒ **`Different` is a POSITIVE adjudication, not a negation** ⇒ non-regenerable ⇒ **`K1`.**
⇒ **`Unknown` is what you get when nothing was adjudicated** ⇒ regenerable ⇒ ⛔ **not `K1`.**

$$\boxed{|K| = 11. \textbf{ ⭐ } P\text{-}36 \textbf{ specified } K1\textbf{'s required PAYLOAD. It changed neither cardinality nor } K1\textbf{'s subject.}}$$

⚠️ **My pre-registered prediction was `D`. It is WRONG — the corpus determines a great deal.** Recorded
in §22 as a new reusable failure mode.

# 1. Boundary and firewall

⛔ **No `three_model_convergence/`** — no inspection, listing, filename, path resolution, metadata,
comparison or prediction; the string appears only as a search suppression pattern. ⛔ **No
`external_research/`.** ⛔ **No Schema v3, architecture, implementation, new cell, silent `K1`
redefinition, or canonicalization of `MeaningIdentity`/`RecordIdentity`/the four-valued set.** ⭐
Carried: both lanes read one corpus ⇒ future agreement is **common-cause**, never confirmation.

⭐⭐ **Declared method note:** `P-19` proved *"every constructible `K1` pair either uses the circular
denotation argument or changes `K2`. No minimal-pair witness exists."* ⇒ **§8/§11's pair tests cannot
succeed for `K1` by construction, and their failure is EXPECTED and UNINFORMATIVE** — ⛔ **not evidence
against necessity.** §18's two-condition test carries the weight instead.

⭐⭐⭐ **Provenance repair, self-flagged:** `P-35` cited `25S §3` for the four-valued `Resolution`, but
that reached me via `STEP-VERIFY-025h-025z:194` — **the verification lane quoting the primary.** I have
now read the primary (1 779 lines). ✅ **The citation is CORRECT** — `§25S.3` is titled *"Four possible
outcomes"* and carries the set verbatim. ⭐ **But the summary omitted everything that decides this
audit**: `§25S.4`, `§25S.7`, `§25S.19`–`§25S.22`.

# 2. Exact corpus claims — representation, rule, or obligation?

| # | witness | specifies | is it a persistence requirement? |
|---|---|---|---|
| ⭐⭐ **X1** | `§25S.3` — *"Entity resolution **should** not be binary"*; `Resolution \in \{Same,Different,PossibleSame,Unknown\}`; *"This is much **safer**"* | ⭐ **a representation** | ⛔ **NO — `[STIPULATED]`, a safety recommendation** |
| ⭐⭐⭐⭐ **X2** | `§25S.7` — `\boxed{\neg SameAs \neq DistinctFrom}`; *"**not simply** `SameAs=False`"*; **`KnownDifferent`** vs **`NotKnownSame`**; *"we also **need** `DistinctFrom`"* | ⭐⭐ **a LOGICAL rule** | ⭐⭐ **YES, derivatively** — a positive adjudication cannot be recovered from silence |
| ⭐⭐⭐ **X3** | `§25S.4` — *"We cannot conclude `Same`. Nor `Different`. **Without enough context**: `Identity = Unknown`. This should **block automatic merging**"* | ⭐⭐ **an operational DEFAULT** | ⛔ **NO — `Unknown` is a function of insufficient context** |
| ⭐⭐⭐⭐ **X4** | `§25S.20` — *"we should **not destroy the historical identities** … `KAID_A → KAID_{Canonical}` … **the mapping is preserved**"* | ⭐⭐ **a RETENTION rule** | ⭐⭐⭐ **YES — the only direct one** |
| ⭐⭐ **X5** | `§25S.19` — `\boxed{LabelChange \neq IdentityChange}`; *"once `KAID_X` is established … it **should** be stable"* | ⭐ **an invariant** + a stability recommendation | ⭐ **partly** — boxed part `[EMP]`, *"should be stable"* `[STIPULATED]` |
| ⭐⭐ **X6** | `§25S.21` — **identity SPLIT**: a merge later found wrong | ⭐ **adjudications are REVISABLE** | ⭐ **supports historical retention** |
| ⭐⭐ **X7** | `§25S.22` — *"identity uncertainty **must** propagate"*; `SameAs(A,B)=Possible ⇒ PotentialPropagation`; *"only once identity is **established** should inheritance become authoritative"*; *"prevents catastrophic contamination"* | ⭐⭐ **an INFERENCE rule** | ⚠️ **computation-time; retention only via non-computability** |
| **X8** | `§25S.23` — *"identity **confidence is not enough**"* | a caution | ⛔ no |
| ⭐ **X9** | `P-08` `K1` — *"for the three adjudicated identities; **non-regenerable**"*; `W1` *"an **adjudication**, not a **computation**"* | ⭐⭐ **the criterion** | ⭐ **the test to apply** |

# 3–7. The four values, audited individually

$$\boxed{\textbf{The criterion is } P\text{-}08\textbf{'s own: } \mathbf{is\ this\ value\ REGENERABLE\ from\ what\ else\ is\ retained?}}$$

| value | what it is | regenerable? | `K1`? |
|---|---|---|---|
| ⭐⭐ **`Same`** | ⭐ a **positive adjudication** — `W1`'s *"one bit per pair"* | ⭐⭐⭐ **NO** — semantic equivalence not computable *(`P-35` `V1`)* | ⭐⭐ **YES** |
| ⭐⭐⭐ **`Different`** | ⭐⭐ **`KnownDifferent` — a POSITIVE adjudication, explicitly NOT `¬SameAs`** | ⭐⭐⭐ **NO** — `X2` boxed | ⭐⭐ **YES** |
| ⭐ **`PossibleSame`** | ⭐ a positive assessment with an operational consequence *(`PotentialPropagation`)* | ⚠️ **`[OPEN]`** — `X8` warns confidence is not enough; whether it is adjudicated or scored is unstated | ⚠️ **`[OPEN]`** |
| ⭐⭐⭐ **`Unknown`** | ⭐⭐ **`NotKnownSame ∧ NotKnownDifferent`** — `X3`'s *"without enough context"* | ⭐⭐⭐ **YES — it IS the absence of the other two** | ⛔ **NO** |

⭐⭐⭐ **The asymmetry, stated exactly:** a recorded **`Different`** must never be confused with silence
*(`X2`)*; but **`Unknown` IS the silence**, so storing it is storing the absence of information. ⭐⭐
**Retaining `Unknown` adds nothing over retaining nothing** — which is the whole answer to §14's
negative-information audit.

⚠️ **`PossibleSame` is left `[OPEN]`, not resolved to fit the pattern.** `X7`'s *"must propagate"* is an
**inference-time** obligation; whether the judgment must be **retained** depends on whether `Possible`
is adjudicated or recomputed, and `X8` explicitly unsettles that.

# 8. Open-world logic audit

| property of the four-valued set | verdict |
|---|---|
| **A** exhaustive | ⚠️ **`[UNWITNESSED]`** — never claimed |
| **B** mutually exclusive | ⚠️ **`[UNWITNESSED]`** — ⭐ and `X7` writes `SameAs(A,B)=Possible`, i.e. `Possible` as a **value of `SameAs`**, not a sibling of it |
| **C** ordered | ⭐ **`[UNWITNESSED]`** |
| **D** merely illustrative | ⭐⭐ **partly — `X1` is a *"should"* + *"safer"* recommendation** |
| ⭐⭐⭐ **E** context-dependent | ⭐⭐⭐ **`[EMP]` — `X2`'s caveat: *"unless the domain explicitly uses a CLOSED-WORLD identity rule"*** |
| **F** underdetermined | ⭐ **as a partition, yes** |

$$\boxed{\textbf{⭐⭐ The five states } \{Same,\ NotKnownSame,\ Different,\ PossibleSame,\ Unknown\} \textbf{ are NOT established as a partition, and } \mathbf{NotKnownSame} \textbf{ collapses into } \mathbf{Unknown} \textbf{ only under OPEN-world reading.}}$$

# 9–10. The two candidate models, both tested

**Positive-only `R⁺ = \{Same, NotRecorded\}`:** map `Different → NotRecorded`.
⭐⭐⭐ **FAILS.** `X2` boxes exactly this collapse as illegitimate: `KnownDifferent` would become
indistinguishable from `NotKnownSame`. ⭐ **And the loss is epistemically required to survive, not
merely useful** — `X3` makes `Unknown` *block automatic merging*, so a system that cannot tell
`Different` from `Unknown` would block merges it should permit and permit merges it should block.

**Full four-valued:** ⭐⭐ **OVER-READS.** `X1` is *"should … much safer"* — a **representation
recommendation**. ⛔ **Nowhere does the corpus say all four values persist**, and `X3` defines
`Unknown` in a way that makes its storage vacuous.

$$\boxed{\textbf{⇒ } \mathbf{MODEL\ C}\textbf{: } K1 \textbf{ retains } \{\mathit{Same},\ \mathit{Different}\} \textbf{ certainly, } \mathit{PossibleSame} \textbf{ } \mathbf{[OPEN]}\textbf{, } \mathit{Unknown} \textbf{ never.}}$$

# 11. Information-theoretic tests, correctly constructed

| test | result |
|---|---|
| pairs for `Same` vs `Different` | ⭐⭐ **NOT CONSTRUCTIBLE — and, as declared in §1, this is EXPECTED.** Any two histories differing in an adjudication differ in a *recorded judgment*, which is `K1`'s own payload; ⛔ **`P-34`'s error was to use a pair differing in EVIDENCE, which changes `K2`** |
| ⭐ the argument that carries instead | ⭐⭐⭐ **§18's two conditions.** (i) **Non-reconstructable:** `X2` — a positive `Different` cannot be recovered from silence, and `V1` — semantic equivalence is not computable. (ii) **Corpus-required:** `X4` — *"we should not destroy the historical identities … the mapping is preserved."* ⭐ **Both hold for `Same`/`Different`; only (i) is arguable for `PossibleSame`; NEITHER holds for `Unknown`** |
| **representation vs necessity** | ⭐⭐ **kept apart throughout** — `X1` is representation, `X4` is necessity, and **only `X4` is load-bearing** |

# 12. Historical vs current

⭐⭐ **`X6` (identity split) proves adjudications are REVISABLE**, and ⭐⭐⭐ **`X4` requires the prior
identities to survive the revision** — *"we should not destroy the historical identities."* ⇒ **prior
adjudications must remain recoverable through a merge.** ⚠️ **Whether a superseded `Same` must survive
a later `Different` outside the merge case is `[OPEN]`** — `X4` speaks only of merges.

# 13–15. DDD · mathematics · measurement

| | classification |
|---|---|
| `SameAs` / `DistinctFrom` | ⭐⭐ **domain RELATIONS, established by adjudication** |
| `Resolution` | ⭐ **a domain-service OUTPUT / value** — ⛔ **no aggregate created because the mathematics uses a set** |
| `KAID` | ⭐ **identifier**, `[STIPULATED]` stable |
| the merge mapping | ⭐⭐ **a persisted RELATION** *(`X4`)* |

$$\boxed{\begin{array}{ll}\mathit{Resolution} : \mathit{Pair} \rightarrow \{\ldots\} \textbf{ as a TOTAL function} & \textbf{⛔ } \mathbf{NOT\ ESTABLISHED}\\ \mathit{Resolution}_t(x,y) \textbf{ (temporal)} & \textbf{⚠️ } \mathbf{[UNWITNESSED]} \textbf{ — no witness indexes it by time}\\ \textbf{policy dependence} & \textbf{⭐ } \mathbf{[EMP]}\textbf{ via } X2\textbf{'s open/closed-world caveat}\end{array}}$$

**Measurement:** ⭐⭐ `Same`/`Different` are **assigned** and **latent** *(not observable, not
reproducible — `V1`)*; `PossibleSame` is ⚠️ **assigned or scored — `[OPEN]`**; ⭐⭐⭐ **`Unknown` is
DERIVED — the absence of measurement, not a measurement state.** ⭐ **That is exactly §17's distinction
between *uncertainty as a measurement state* and *absence of measurement*, and the corpus places
`Unknown` in the second.**

# 16. ⭐⭐ Attack on `P-35` — six verdicts

| # | `P-35` claim | verdict |
|---|---|---|
| 1 | non-regenerability selects **meaning-level** adjudication | ⭐⭐ **`[QUALIFIED]`** — it selects **adjudicated** judgments; the *"meaning-level"* label imports `§25I`'s `[STIPULATED]` typology unnecessarily |
| 2 | record identity is computable | ⭐ **VALID** `[EMP]` |
| 3 | semantic equivalence is undecidable | ⭐ **VALID** `[EMP]` |
| 4 | therefore the adjudication is what `K1` preserves | ⭐⭐ **VALID, and STRENGTHENED by `X4`** |
| ⭐ **5** | absence of `SameAs` becomes `Unknown` | ⭐⭐ **VALID and now `[EMP]`-confirmed by `X3`** — ⚠️ **but `P-35` used it to argue LOSS; it actually argues the OPPOSITE for `Unknown`** |
| ⭐⭐⭐ **6** | `V3 + V1` establish irrecoverable loss | ⭐⭐⭐ **`[QUALIFIED]` — this is the missing bridge the commission predicted.** Irrecoverable loss holds for the **positive adjudications only**. `P-35` did not distinguish the four values, so it over-generalised |

⭐⭐ **`P-35`'s verdict `D` on `K1`'s definition SURVIVES** *(non-regenerability, orthogonal axis)*;
⭐ **its treatment of the payload is refined, not overturned.**

# 17. `K1` reconstruction — **`K1-C`**

$$\boxed{\mathbf{K1\text{-}C}\textbf{: } \mathbf{non\text{-}regenerable\ sameness\ adjudications\ must\ be\ preserved.}}$$

⭐ **This refines `K1-A`'s *payload* without contradicting its *subject***: `P-08`'s *"for the three
adjudicated identities"* names **what** carries identity; `K1-C` names **what must be kept** about it.
⛔ **Not `K1-D`** *(the full `Resolution` state — `Unknown` is vacuous)* · ⛔ **not `K1-E`** *(the corpus
determines more than "nothing")* · ⛔ **not `K1-B`** *(too broad — it would sweep in regenerable
judgments)*.

# 18–19. New-cell test and kernel impact

1 corpus-grounded ✅ *(`X2`, `X4`)* · 2 typed ⚠️ **partly** — `PossibleSame` untyped · 3 epistemic ✅ ·
4 persistence ✅ · ⭐ **5 not already represented — 🔴 FAILS: this IS `K1`** · 6–7 not reached.

$$\boxed{\begin{array}{c}|K| = 11.\\ \textbf{⭐ } P\text{-}36 \textbf{ changed } \mathbf{K1\textbf{'s required PAYLOAD}} \textbf{ (positive adjudications; not } \mathit{Unknown}\textbf{).}\\ \textbf{⛔ It changed NOT cardinality, NOT } K1\textbf{'s subject, and NOT the status of } \mathit{MeaningIdentity}/\mathit{RecordIdentity}.\end{array}}$$

# 20. Status register
**`[EMP]`** ⭐⭐ `\boxed{\neg SameAs \neq DistinctFrom}` with *"not simply `SameAs=False`"* and
`KnownDifferent` vs `NotKnownSame` · the **closed-world caveat** · `Identity=Unknown` *"without enough
context"*, *"should block automatic merging"* · *"we should **not destroy the historical identities** …
the mapping is preserved"* · `\boxed{LabelChange \neq IdentityChange}` · identity **split** is possible ·
*"identity uncertainty must propagate"* · *"identity confidence is not enough"* · `K1` *"non-regenerable"*
· `W1` *"an adjudication, not a computation"*.
**`[DERIVED]`** ⭐⭐⭐ **`Unknown` is regenerable — it IS the absence of both adjudications** ·
`Different` is non-regenerable because it is positive · the positive-only model **collapses a boxed
distinction** · `R⁺` fails · `Unknown` is absence-of-measurement, not a measurement state.
**`[CORROBORATION]`** `X6` (split) corroborates `X4`'s non-destruction.
**`[STIPULATED]`** ⭐ the four-valued set itself *("should not be binary … much safer")* · `KAID`
stability *("should be stable")* · `§25I`'s two-level typology.
**`[REFUTED]`** ⭐ **Model A** *(positive `Same` only — collapses `KnownDifferent`)* · ⭐ **Model B**
*(all four persist — over-reads a "should")* · *`Resolution` as an established total function*.
**`[QUALIFIED]`** ⭐⭐⭐ **`P-35` claim 6** *(loss holds only for positive adjudications)* · **`P-35`
claim 1** *(meaning-level label unnecessary)*.
**`[UNWITNESSED]`** exhaustiveness · mutual exclusivity · ordering · `Resolution_t` · retention of a
superseded `Same` outside the merge case.
**`[OPEN]`** ⭐ **`PossibleSame`'s persistence status** · **`PossibleSame` as a value of `SameAs` vs a
sibling of it** · open- vs closed-world for KnowledgeOS · the `V6`/`V8` natural-key tension *(`P-35`)* ·
assertion vs record · record vs version · `M5` warrant-only change · the 28 abandoned `GT` invariants ·
Pair D *(`P-33`)* · the admitted-transition disjunction *(`P-31`)* · `ω`'s unglossed partiality ·
`Accepted ⇒ Warrant` · `Governance`-in-`K` · `W3`'s conditional · `Ind_ρ`'s five slots · `K4b-ii` ·
register deletion · ⛔ **well-foundedness / terminality / acyclicity — DEFERRED.**
**`[UNDECIDABLE]`** semantic equivalence *(carried, `P-35`)*.
**`[ARCH]`** none used.

# 21. Corrections to `P-35`
⭐ Claim 6 `[QUALIFIED]`; claim 1 `[QUALIFIED]`; **verdict `D` survives.** ⭐⭐ **Citation `25S §3`
VERIFIED against the primary** — correct, though it had reached `P-35` through a verification-lane
quotation rather than the source.

# 22. ⭐⭐ Reusable methodological lessons — a seventh

Established: 1 vocabulary absence ≠ obligation absence · 2 notation ≠ ontology · 3 witness status must
be checked · 4 a pre-registered prediction can be wrong · 5 identical semantic content ≠ identical
retained state · 6 a representation distinction ≠ a persistence obligation.

$$\boxed{\begin{array}{c}\mathbf{7.} \textbf{ A verification-lane SUMMARY is not a substitute for the PRIMARY — and predicting}\\ \textit{"the corpus does not determine this"} \textbf{ from a summary is a } \mathbf{systematic\ bias\ toward\ UNDERDETERMINED.}\end{array}}$$

⭐⭐ **Evidence: I pre-registered `D`. The one-line summary showed only the four-value set; the primary
devotes `§25S.3`, `§25S.4`, `§25S.7`, `§25S.19`–`§25S.22` to exactly this question and determines it.**
⭐ **Second consecutive prediction miss — both recorded, neither quietly dropped.**

# 23. Carried items
See §20's `[OPEN]` block. ⛔ **Nothing reopened; well-foundedness, terminality and acyclicity remain
deferred.**

### 24. ONE NEXT UNRESOLVED QUESTION

$$\boxed{\textbf{Is KnowledgeOS OPEN-world or CLOSED-world on identity — and does } K1\textbf{'s payload depend on that choice?}}$$

⭐ `X2` states the whole result **conditionally**: *"`¬SameAs \neq DistinctFrom` — **unless the domain
explicitly uses a closed-world identity rule**."* ⭐⭐ **Under a closed-world rule, `Different` becomes
derivable from `¬Same`, and `K1`'s payload shrinks to `\{Same\}` — so Model C holds only on an
unstated premise.**

```
MODEL C — A SPECIFIC SUBSET. K1 RETAINS THE POSITIVE ADJUDICATIONS; UNKNOWN REQUIRES NO RETENTION.
|K| = 11. P-36 SPECIFIED K1's PAYLOAD, NOT ITS CARDINALITY OR ITS SUBJECT.

THE [EMP] CORE IS §25S.7, BOXED: "This is NOT SIMPLY SameAs = False. There is a difference between
KnownDifferent and NotKnownSame. Therefore NOT SameAs(x,y) != DistinctFrom(x,y) — UNLESS THE DOMAIN
EXPLICITLY USES A CLOSED-WORLD IDENTITY RULE."

So Different is a POSITIVE ADJUDICATION, not a negation — non-regenerable, therefore K1. And §25S.4
defines Unknown operationally: "We cannot conclude Same. Nor Different. WITHOUT ENOUGH CONTEXT:
Identity = Unknown." That makes Unknown exactly NotKnownSame AND NotKnownDifferent — the ABSENCE of
both adjudications. Retaining it adds nothing over retaining nothing. That is the whole answer to the
negative-information question: a recorded Different must never be confused with silence, but Unknown IS
the silence.

BOTH CANDIDATE MODELS FAIL. Positive-only {Same, NotRecorded} collapses exactly the distinction §25S.7
boxes as illegitimate — and the loss is operationally real, since Unknown blocks automatic merging while
Different does not. Full four-valued OVER-READS: §25S.3 is "SHOULD not be binary ... much SAFER", a
representation recommendation, and nowhere does the corpus say all four persist.

THE PERSISTENCE OBLIGATION HAS EXACTLY ONE DIRECT WITNESS, §25S.20: "we should NOT DESTROY THE
HISTORICAL IDENTITIES ... the mapping is preserved." §25S.21's identity SPLIT shows adjudications are
revisable, which is why the historical ones must survive.

THE MINIMAL-PAIR TESTS WERE DECLARED UNINFORMATIVE IN ADVANCE, not after failing. P-19 proved every
constructible K1 pair either circles or changes K2, so their failure here is expected; §18's two
conditions — non-reconstructability AND corpus requirement — carry the result instead. Both hold for
Same and Different; only the first is arguable for PossibleSame; NEITHER holds for Unknown.

PossibleSame IS LEFT [OPEN] RATHER THAN FITTED TO THE PATTERN. §25S.22's "must propagate" is an
inference-time rule, and §25S.23 warns "identity confidence is not enough", so whether Possible is
adjudicated or scored is unstated.

ATTACK ON P-35: claim 6 is [QUALIFIED] — irrecoverable loss holds for the POSITIVE adjudications only,
and P-35 over-generalised across all four values. That is precisely the missing logical bridge the
commission predicted. Claim 1's "meaning-level" label is [QUALIFIED] as unnecessary. P-35's verdict D on
K1's DEFINITION survives; only its payload treatment is refined.

A PROVENANCE FLAG I RAISED AGAINST MYSELF WAS CHECKED AND CLEARED: P-35 cited 25S §3 for the four-valued
set, but that had reached me through the verification lane's quotation. The primary confirms the
citation — and reveals that the summary omitted every section that decides this audit.

MY PRE-REGISTERED PREDICTION OF D WAS WRONG — the second consecutive miss, both recorded. It yields
reusable lesson #7: a verification-lane SUMMARY is not a substitute for the PRIMARY, and predicting "the
corpus does not determine this" from a summary is a systematic bias toward UNDERDETERMINED.

ONE NEXT UNRESOLVED QUESTION: Is KnowledgeOS OPEN-world or CLOSED-world on identity — and does K1's
  payload depend on that choice? §25S.7 states the entire result conditionally, and under a closed-world
  rule Different becomes derivable and K1's payload shrinks to {Same}.

NO SCHEMA v3 — NO NEW CELL — NO CANONICALIZATION OF THE FOUR-VALUED SET — NO ARCHITECTURE — NO
three_model_convergence INSPECTION — NO CROSS-TRACK COMPARISON — WELL-FOUNDEDNESS STILL DEFERRED.
```
