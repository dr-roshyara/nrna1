# `P-35` — `K1` Identity Target: Meaning Identity vs Record Identity Audit

**2026-09-08 · Lane T · INDEPENDENTLY EXECUTED.** After [`52` `P-34`](./52-P34-ASSERTION-IDENTITY-CORROBORATION-AND-K1-INDEPENDENCE-AUDIT.md).

# 0. Executive verdict

⭐⭐⭐⭐ **The question is answered by `P-08`'s own wording, which neither `P-34` nor the commission
quoted: `K1` is *"identity preservation — for the three adjudicated identities; **non-regenerable**."*
`K1` is therefore defined by **non-regenerability**, not by a choice between meaning and record —
**answer `D`**. The corpus then settles which level that selects, and it goes **against `P-34`**:
the verification lane finds **record identity COMPUTABLE** *(hashing, IDs)* and **semantic equivalence
explicitly NOT computable**. So the non-regenerable identity is the **semantic/meaning-level
adjudication**, exactly matching `P-08`'s own `K1` witness — *"one bit per pair: `SameId` or not — an
**adjudication**, not a **computation**."* ⭐⭐ **`P-34`'s "assigned identifier sufficient to
distinguish records" is a substitution of subject, and its §11 counterexample is invalid.**
$|K| = 11$; **semantic interpretation refined, cardinality untouched.**

# 1. Research-boundary confirmation

⛔ **No `three_model_convergence/` inspection** — no existence check, listing, filename, path
resolution, metadata or finding. ⚠️ **The string appears in my searches only as the suppression
pattern `-not -path '*three_model_convergence*'`, which is the mechanism *of* the firewall and never
reads or returns those paths.** ⛔ **No `external_research/`.** ⛔ **No architecture, schema, new cell,
merge, or canonicalization.** ⭐ **No convergence prediction, comparison or harmonisation.**

⚠️ **Vocabulary:** the commission's nine classes **omit `[ARCH]`**, which is load-bearing for
quarantining implementation claims; I retain it and use `[UNDECIDABLE]` only for *impossible in
principle*, distinct from `[OPEN]`. `[HYPOTHESIS]` not used; `P-33`'s single use reads as
`[STIPULATED]`.

⭐ **Statistical protocol carried:** both lanes read the same corpus ⇒ future agreement is
**common-cause**, ⛔ never replication or independent confirmation. **Divergence is the informative
outcome.**

# 2. ⭐⭐⭐ `P-07` / `P-08` reconstruction — without repair

$$\boxed{\mathbf{K1}\ \textbf{IDENTITY PRESERVATION — for the } \mathbf{three\ adjudicated\ identities}\textbf{; } \mathbf{non\text{-}regenerable} \qquad (P\text{-}08\ \S18)}$$

| `Q` | answer |
|---|---|
| **Q1** what entity? | ⭐⭐ **`candidate` · `definition` · `association`** — `P-07`'s IDENTITY KERNEL = 3. ⛔ **Not "assertions", not "records", not "meanings"** |
| **Q2** what evidence? | `F-CONT` and `F-REF` forcing conditions; `W1`'s collapse witness |
| ⭐⭐⭐ **Q3** identity, addressability, continuity or distinguishability? | ⭐⭐ **`P-07` notion 2: *"a **stable designator** that carries sameness across change"*** — and §1.1: `\boxed{\textbf{identity} \neq \textbf{surrogate identifier. A stable natural key IS an identity.}}` |
| ⭐⭐⭐ **Q4** does `P-07` specify the meaning/record target? | ⭐⭐⭐ **NO. The distinction did not exist in `P-07`'s frame, and `P-07` names ten notions — none of them is `MeaningIdentity` or `RecordIdentity`.** |

⭐⭐ **`P-07` answers *WHICH THINGS need identities*; `§25I` answers *WHAT KIND of identity*. These
are ORTHOGONAL AXES.** ⛔ **`P-07` is not rewritten to fit `P-34`.**

⭐⭐⭐ **And `P-08` already carried the non-regenerability property that `P-34` presented as new:**
`W1` — *"two different candidates collapse into one identity | **one bit per pair: `SameId` or not —
an adjudication, not a computation** | `K1`"*; and `K1/K3` — *"identity assertions are
**adjudications**, not transitions."*

# 3. Corpus identity evidence

| # | witness | status | establishes | ⚠️ does **NOT** establish |
|---|---|---|---|---|
| ⭐⭐⭐⭐ **V1** | `STEP-VERIFY-025h-025z:51` — *"exact/observation/**record identity COMPUTABLE** (hashing, IDs — standard); **semantic equivalence explicitly NOT** (correctly localized). **Not TESTABLE.**"* | ⭐ **`[EMP]`** | ⭐⭐ **the computability asymmetry** | ⛔ that either must persist |
| ⭐⭐⭐ **V2** | `STEP-VERIFY-025h-025z:44` — two-level identity listed under **"PROPOSED IDEA"** | ⭐ **`[EMP]` about status** | ⭐⭐ **`[STIPULATED]` confirmed by an independent auditor** | ⛔ that it is canonical |
| ⭐⭐⭐⭐ **V3** | `25S §7` boxed — `¬SameAs(x,y) \neq DistinctFrom(x,y)` *(**open-world identity**)* | ⭐ **`[EMP]`** | ⭐⭐⭐ **absence of a sameness judgment ≠ difference** | ⛔ a specific storage form |
| ⭐⭐⭐ **V4** | `25S §3` — `Resolution(r_1,r_2) \in \{Same, Different, PossibleSame, Unknown\}` | **`[EMP]`** | ⭐ **four-valued resolution** | ⛔ that all four persist |
| ⭐⭐⭐ **V5** | `25S §20` — *"**Merge of identities preserved-with-mapping, never destructive**"* | ⭐ **`[EMP]`** | ⭐⭐ **a retention obligation on identity merges** | ⛔ which identity level |
| ⭐⭐ **V6** | `25S §12` — `IdentityKey = (Organization, Environment, Hostname, SystemID)` | **`[EMP]`** | ⭐ **a NATURAL KEY serving as identity** | ⛔ that identity must be assigned |
| ⭐⭐ **V7** | `25S §42` / UL — *"Atma **finalized** as bounded-context-scoped **meaning identity**"*; identity errors *"high-propagation"* | ⚠️ **`[STIPULATED]`** | scoping | ⛔ persistence |
| ⭐⭐⭐ **V8** | ⟦L⟧ `INV-KOS-IDENTITY-001` *"identity is assigned, never derived"* | **`[EMP]`** *(`P-34`)* | ⭐ **non-derivability** | ⛔ ⭐⭐ **which identity `K1` targets** |
| ⭐⭐ **V9** | `§25I.16` — `\boxed{A\text{-identity alone is insufficient}}` | **`[EMP]`** | proposition ≠ identity | ⛔ a persistence obligation |

**Measured:** `MeaningIdentity` 12 files · `RecordIdentity` 9 · `evidence aggregation` 95 ·
`occurrence identity` **0**. ⚠️ **Descriptive only.**

⭐⭐⭐ **V6 vs V8 is a genuine TENSION, recorded not resolved away:** a **natural key** is drawn from
content, so it *is* derived — yet `INV-KOS-IDENTITY-001` says identity is *never* derived. ⭐⭐ **The
available reconciliation — and it is `P-08`'s own: the *adjudication* is assigned even when the
*designator* is natural.** ⇒ **assignment attaches to the JUDGMENT, not to the LABEL's form.**

# 4. Identity ontology — §9's mandatory separation

| notion | relation |
|---|---|
| **assertion identity** vs **knowledge-record identity** | ⭐⭐ **`[OPEN]` — the corpus does not rigorously distinguish them**, and I do not equate them |
| **record identity** vs **occurrence identity** | ⚠️ `occurrence identity` **0 files** ⇒ **`[UNWITNESSED]`** |
| **identifier / addressability** | ⭐ **distinct** — `P-07` notions 3–7 separate reference, address, locator, natural key, surrogate |
| **`RecordIdentity`** vs **`VersionIdentity`** | ⭐⭐ **`[OPEN]`** — `§25I.19` glosses record identity as *"which concrete epistemic record/**version**"*, ⚠️ **conflating them in a single phrase**; no witness separates them |

$$\boxed{\textbf{Verdict on §9: } \mathbf{F\ —\ UNDERDETERMINED.} \textbf{ ⛔ } RecordIdentity = AssertionIdentity \textbf{ is NOT written.}}$$

# 5–6. Meaning and record tests

| | `MeaningIdentity` | `RecordIdentity` |
|---|---|---|
| **computable?** | ⭐⭐⭐ **NO — *"semantic equivalence explicitly NOT"*** | ⭐⭐ **YES — *"hashing, IDs — standard"*** |
| **regenerable from retained state?** | ⭐⭐⭐ **NO** | ⭐ **YES, given the identifiers** |
| ⭐ **matches `K1`'s *"non-regenerable"*?** | ⭐⭐⭐ **YES** | ⛔ **NO** |
| **`M1`** same `p`+context, different evidence | ⭐ same meaning | ⭐ different records *(`§25I.20`)* |
| **`M2`** same `p`, different context | ⭐ **meaning differs** — context is inside `§25I.9`'s tuple | different |
| **`M3`** different `p`, same evidence | different | different |
| **`M4`** same `p`, changed provenance | ⭐ *"provenance may change without changing the underlying knowledge proposition"* ⇒ **same meaning** | ⚠️ `[OPEN]` |
| **`M5`** same `p`, changed warrant | ⚠️ **`[OPEN]`** — untreated *(carried from `P-34`)* | `[OPEN]` |
| **`M6`** same `p`, temporal supersession | ⭐ `TemporalChange \neq LogicalContradiction` | new |
| **`R1`–`R7`** | — | ⭐⭐ **`R6`/`R7` are covered by `V5`: merges are *preserved-with-mapping, never destructive*** |

⭐⭐ **The asymmetry I flagged before running the test is CONFIRMED, and in the direction opposite to
my own prediction.** I predicted meaning would be derived and record assigned; ⭐⭐⭐ **the corpus says
record identity is the COMPUTABLE one and semantic equivalence is the UNDECIDABLE one.** ⭐ **Recorded
as a corrected prediction, not quietly dropped.**

# 7. The identity-to-meaning mapping — `T1`–`T7`

| | question | verdict |
|---|---|---|
| **T1** does the corpus merely distinguish? | ⭐⭐ **YES — and that is all `V2` grants** |
| **T2** must both persist? | ⭐ **`[UNWITNESSED]`** |
| **T3** is a mapping `RecordIdentity → MeaningIdentity` required? | ⚠️ **`[OPEN]`** — ⭐ **`V5`'s *"preserved-with-mapping"* is the ONLY mapping obligation, and it is about MERGES** |
| **T4** must the mapping be stable? | **`[UNWITNESSED]`** |
| ⭐ **T5** does corroboration preserve meaning while creating a new record? | ⭐⭐ **YES — `§25I.20`, `[EMP]` as a worked treatment** |
| **T6** many records per meaning? | ⭐ **YES — *"two independently supported instances of the same proposition"*** |
| **T7** many meanings per record? | ⭐ **`[UNWITNESSED]` — cardinality NOT inferred** |

# 8. ⭐⭐⭐ Information-theoretic tests — and `P-34`'s pair FAILS

| test | result |
|---|---|
| ⭐ **A — `MeaningIdentity`** | **`S_K(H1) = S_K(H2)` with different meaning identity?** ⭐⭐ **Not constructible** — meaning is a function of proposition + context, both retained by `K2`/`K4a-i`; ⚠️ **but by `V1` the FUNCTION is not computable**, so the *judgment* is not recoverable even though its *inputs* are ⇒ ⭐⭐⭐ **the adjudication, not the inputs, is what cannot be regenerated** |
| ⭐⭐⭐ **B — `RecordIdentity`** | ⭐⭐ **`P-34`'s pair is INVALID.** `§25I.20`'s two records differ in **evidence** ⇒ `S_K(H_1) \neq S_K(H_2)`. ⛔ **It is not an identical-state pair, so it is not a necessity witness** |
| **C — mapping** | ⚠️ **`[OPEN]`** — no witness requires the mapping independently of `V5`'s merge case |

$$\boxed{\begin{array}{c}P\text{-}19 \textbf{ had ALREADY proved: } \textit{"every constructible } K1 \textbf{ pair either uses the circular denotation argument or CHANGES } K2\textbf{. No minimal-pair witness exists."}\\ \boxed{\textbf{⭐⭐ } P\text{-}34\textbf{'s pair is exactly such a failure — it changes the state. } K1\textbf{'s ground REMAINS } P\text{-}19\textbf{'s PRESUPPOSITION argument, alone.}}\end{array}}$$

⭐ **`V3` supplies the persistence argument that a pair cannot:** since `¬SameAs \neq DistinctFrom`,
**the absence of a retained sameness adjudication does not mean "different" — it means "unknown."**
⇒ ⭐⭐ **failing to retain the adjudication destroys information irrecoverably**, and no
recomputation restores it *(`V1`: not computable)*.

# 9. `Corroborate` — what can and cannot be inferred

| | |
|---|---|
| does `A'` denote a new **record**? | ⭐ **`[EMP]`-supported via `§25I.20`** *(same meaning, new record)* |
| a new **assertion**? | ⚠️ **`[OPEN]`** — assertion vs record not rigorously distinguished *(§4)* |
| the same **meaning**? | ⭐⭐ **YES** |
| does the corpus **require** a new identifier? | ⛔ **`[UNWITNESSED]`** |
| does it **permit** the same identifier? | ⭐ **YES — *"does not have to change"*** |
| governed by assignment external to the operation? | ⭐⭐⭐ **YES — `V8`.** ⭐ **This is why the notation is silent, and the silence is principled** |

# 10 · 21. ⭐⭐⭐ `P-34` reassessed — seven verdicts

| # | `P-34` claim | verdict |
|---|---|---|
| 1 | *"identity is assigned, never derived"* | ⭐ **`[EMP]` — STANDS**, ⚠️ **with the `V6` natural-key tension recorded** |
| ⭐⭐ **2** | *"`K1` requires an **assigned identifier**"* | ⭐⭐⭐ **`[QUALIFIED]` — OVERCLAIM.** `P-07` §1.1: *"a stable **natural key** IS an identity"*, and `V6` exhibits one. ⭐ **`K1` requires an assigned JUDGMENT, not an assigned LABEL** |
| ⭐⭐ **3** | *"`K1` requires distinction between **records**"* | ⭐⭐ **`[QUALIFIED]`** — record identity is **computable** *(`V1`)*; `K1` is defined by **non-regenerability** |
| **4** | *"`K1` requires `RecordIdentity`"* | ⭐ **`[REFUTED]`** — computable ⇒ not the non-regenerable target |
| **5** | *"`K1` requires `MeaningIdentity`"* | ⭐ **`[DERIVED]`** — a **consequence** of non-regenerability + `V1`, ⛔ **never directly stated by the corpus** |
| **6** | *"both are persistence obligations"* | ⭐ **`[OPEN]`** — `T2` unwitnessed |
| ⭐⭐ **7** | *"`K1-e` + `K1-c` is an adequate decomposition"* | ⭐⭐ **`[QUALIFIED]`** — it omits `K1`'s actual defining criterion, **non-regenerability**, and `K1-c` (record) is the computable one |
| ⭐⭐⭐ **8** | *"`INV-KOS-IDENTITY-001` **repairs** `P-19`'s withdrawn witness"* | ⭐⭐⭐ **`[QUALIFIED]` — it CORROBORATES what `P-08` already said** *("non-regenerable"; *"an adjudication, not a computation"*)*, and ⛔ **does NOT supply the minimal pair `P-19` showed cannot exist** |

⭐ **§23 honoured — irreducibility is not transferred:** `K1` **as a capability** is independent
*(`P-19`'s presupposition argument)*; **record identity as its target** is `[REFUTED]`; **meaning
identity as a separate obligation** is `[OPEN]`. **Three different claims, three different verdicts.**

# 11–14. DDD · mathematics · measurement · temporal

| object | classification |
|---|---|
| Assertion · Knowledge Record | ⭐⭐ **not rigorously distinguished ⇒ `[OPEN]`** |
| Meaning | ⭐ **Value Object** — determined by proposition + context |
| `MeaningIdentity` | ⭐⭐ **relation — `SameAs`, and *not computable*** |
| `RecordIdentity` | ⭐ **identifier — computable** |
| `AssertionId` / `KnowledgeId` | ⭐ **identifier** |
| Version | ⚠️ **`[OPEN]`** — conflated with record in `§25I.19` |
| Occurrence | ⭐ **`[UNWITNESSED]`** |

$$\boxed{\begin{array}{ll}id_R : R \rightarrow I_R & \textbf{⭐ supported — computable } (V1)\\ id_M : M \rightarrow I_M & \textbf{⛔ NOT a function the system can evaluate } (V1\textbf{: semantic equivalence not computable})\\ meaning : R \rightarrow M & \textbf{⚠️ } \mathbf{[OPEN]} \textbf{ — assumed by } \S25I\textbf{'s two levels, never established; ⛔ injectivity/totality/stability all } \mathbf{[UNWITNESSED]}\end{array}}$$

⭐⭐ **Measurement audit — the decisive framing:** `SameAs` is **latent**, not observable; the
`Resolution` verdict is **assigned**; the identifier is a **label**. ⭐⭐⭐ **Label uniqueness is NOT
epistemic identity** *(`P-07` §1.1)* **and semantic similarity is NOT identity** *(`§25I.16`)* — the
commission's two traps, both avoided.

**Temporal:** across `S_{before} \xrightarrow{\tau} S_{after}`, what must remain recoverable is ⭐ **the
sameness ADJUDICATION** *(option `E`/`F`)* — ⛔ not the identifier's form, ⛔ not meaning as computed.
⭐ **`V5` makes this concrete: merges are *preserved-with-mapping, never destructive*.**

# 15 · 17. New-cell test and kernel impact

| condition | status |
|---|---|
| 1 corpus-grounded | ✅ *(`V3`, `V5`)* |
| 2 typed | ⚠️ partly — `Resolution` is four-valued |
| 3 epistemic necessity | ✅ |
| 4 persistence necessity | ✅ *(`V3`: absence ≠ difference)* |
| ⭐ **5 not already represented** | ⭐⭐⭐ 🔴 **FAILS — this IS `K1`** |
| 6–7 | ⛔ not reached |

$$\boxed{|K| = 11. \textbf{ ⭐ This audit changed the SEMANTIC INTERPRETATION of } K1\textbf{, not kernel cardinality.}}$$

# 16. Final `K1` interpretation — **`D`**

$$\boxed{\begin{array}{c}\mathbf{D} \textbf{ — } K1 \textbf{ is an identity primitive defined by } \mathbf{NON\text{-}REGENERABILITY}\textbf{, broader than either label:}\\ \textbf{it preserves the } \mathbf{sameness\ ADJUDICATION} \textbf{ for the three adjudicated identities.}\\[4pt] \boxed{\textbf{⭐ Given } V1\textbf{'s computability asymmetry, that adjudication is } \mathbf{meaning\text{-}level} \textbf{ — but } \mathbf{B} \textbf{ is a CONSEQUENCE, not the definition.}}\end{array}}$$

⛔ **Not `A`** *(record identity is computable)* · ⛔ **not `B` as the definition** · ⛔ **not `C`** ·
⛔ **not `E`** *("non-regenerable" IS a specification, just on a different axis than the commission's
question assumed)*.

# 18. Status register
**`[EMP]`** ⭐⭐ `K1` = *"for the three adjudicated identities; **non-regenerable**"* · `W1` *"an
adjudication, not a computation"* · ⭐ **record identity computable, semantic equivalence not** ·
two-level identity listed as a **"PROPOSED IDEA"** · `¬SameAs \neq DistinctFrom` · `Resolution ∈
{Same, Different, PossibleSame, Unknown}` · *"merge of identities preserved-with-mapping, never
destructive"* · `IdentityKey=(Org,Env,Hostname,SystemID)` · `INV-KOS-IDENTITY-001` ·
`\boxed{A\text{-identity alone is insufficient}}`.
**`[DERIVED]`** ⭐⭐ **`K1` targets the non-regenerable adjudication** · meaning-level follows from
`V1` · assignment attaches to the **judgment**, not the label · ⭐ **absence of a retained `SameAs` is
irrecoverable** *(`V3` + `V1`)*.
**`[CORROBORATION]`** `V8` corroborates `P-08`'s *"non-regenerable"*.
**`[STIPULATED]`** the two-level scheme *("I now recommend"; "PROPOSED IDEA")* · `Atma` scoping ·
`§25I.9`'s canonical tuple.
**`[REFUTED]`** ⭐ *`K1` requires `RecordIdentity`* · *label uniqueness = epistemic identity* ·
*semantic similarity = identity*.
**`[QUALIFIED]`** ⭐⭐⭐ **`P-34` claims 2, 3, 7 and its repair claim; `P-34`'s §11 counterexample is
INVALID** *(it changes state)*.
**`[UNWITNESSED]`** `occurrence identity` · `T2` · `T4` · `T7` · `meaning : R → M` as an established
function · a required new identifier under `Corroborate`.
**`[OPEN]`** ⭐ **`V6`/`V8` natural-key vs assigned-identity tension** · assertion vs knowledge-record
· record vs version · `M5` warrant-only change *(carried from `P-34`)* · the mapping obligation ·
`Corroborate`'s record emission · the 28 abandoned `GT` invariants · Pair D *(`P-33`)* · the
admitted-transition **or** infrastructure disjunction *(`P-31`)* · `ω`'s unglossed partiality ·
`Accepted ⇒ Warrant` · `Governance`-in-`K` · `W3`'s conditional · `Ind_ρ`'s five slots · `K4b-ii`'s
corroboration arm · register deletion · ⛔ **well-foundedness / terminality / acyclicity — DEFERRED.**
**`[UNDECIDABLE]`** ⭐ **semantic equivalence** — *"explicitly NOT computable … not TESTABLE"*: not
merely undetermined, but **undecidable in principle**, which is why it must be adjudicated.
**`[ARCH]`** none used.

# 19–20. Corrections and carried items
⭐ **`P-34` §11's counterexample and its "repair" claim are corrected above.** ⛔ **`P-07` and `P-08`
are NOT modified — only read.** ⭐ **`P-19`'s presupposition argument is reinstated as `K1`'s sole
independence ground.**

### ONE NEXT UNRESOLVED QUESTION

$$\boxed{\textbf{Must } K1 \textbf{ retain the FOUR-VALUED } \mathit{Resolution} \in \{\mathit{Same}, \mathit{Different}, \mathit{PossibleSame}, \mathit{Unknown}\}\textbf{, or only the positive } \mathit{SameAs} \textbf{ adjudications?}}$$

```
D — K1 IS AN IDENTITY PRIMITIVE DEFINED BY NON-REGENERABILITY, BROADER THAN EITHER LABEL.
|K| = 11. SEMANTIC INTERPRETATION REFINED; CARDINALITY UNTOUCHED.

THE ANSWER WAS ALREADY IN P-08's OWN WORDING, WHICH NEITHER P-34 NOR THE COMMISSION QUOTED:
  K1  IDENTITY PRESERVATION — for the three adjudicated identities; NON-REGENERABLE
Those three are P-07's candidate, definition and association — not "records", not "meanings". So K1 is
defined by NON-REGENERABILITY, on a different axis from the meaning/record typology. P-07 answers WHICH
THINGS need identities; §25I answers WHAT KIND of identity. Orthogonal axes, which is why the question
as posed had no direct answer.

THE CORPUS THEN SETTLES WHICH LEVEL NON-REGENERABILITY SELECTS — AND IT GOES AGAINST P-34. The
verification lane finds "record identity COMPUTABLE (hashing, IDs — standard); semantic equivalence
explicitly NOT ... Not TESTABLE." So the non-regenerable identity is the SEMANTIC one. That matches
P-08's own K1 witness exactly: "two different candidates collapse into one identity — one bit per pair:
SameId or not — AN ADJUDICATION, NOT A COMPUTATION." P-08 had the property from the start; P-34's
INV-KOS-IDENTITY-001 CORROBORATES it rather than repairing anything.

MY OWN PRE-REGISTERED PREDICTION WAS WRONG, AND IS RECORDED AS SUCH. I predicted meaning would be the
derived one and record the assigned one. The corpus says the opposite: record identity is COMPUTABLE,
semantic equivalence is UNDECIDABLE.

P-34's §11 COUNTEREXAMPLE IS INVALID. §25I.20's two records differ in EVIDENCE, so S_K(H1) != S_K(H2) —
it is not an identical-state pair and therefore not a necessity witness. P-19 had already proved that
"every constructible K1 pair either uses the circular denotation argument or CHANGES K2. No minimal-pair
witness exists." P-34's pair is exactly such a failure. K1's independence ground REMAINS P-19's
PRESUPPOSITION ARGUMENT, ALONE.

WHAT SUPPLIES THE PERSISTENCE ARGUMENT INSTEAD IS OPEN-WORLD IDENTITY: 25S boxes "NOT SameAs(x,y) !=
DistinctFrom(x,y)". Absence of a retained sameness adjudication does not mean "different" — it means
"unknown". Combined with non-computability, failing to retain the adjudication destroys information
that no recomputation can restore. And 25S §20 makes it concrete: "merge of identities
preserved-with-mapping, never destructive."

P-34's "ASSIGNED IDENTIFIER" IS AN OVERCLAIM. P-07 §1.1 already says "identity != surrogate identifier.
A stable NATURAL KEY IS an identity", and the corpus exhibits one: IdentityKey = (Organization,
Environment, Hostname, SystemID). The reconciliation with "assigned, never derived" is P-08's own: the
ADJUDICATION is assigned even when the DESIGNATOR is natural. Assignment attaches to the JUDGMENT, not
to the LABEL. That tension is recorded as [OPEN], not resolved away.

IRREDUCIBILITY WAS NOT TRANSFERRED, as §23 demanded: K1 as a capability is independent (P-19); record
identity as its target is REFUTED; meaning identity as a separate obligation is OPEN. Three claims,
three verdicts.

THE TWO-LEVEL SCHEME IS [STIPULATED] ON INDEPENDENT AUTHORITY — the verification lane lists it under
"PROPOSED IDEA", which agrees with §25I.19's own "I now recommend". It was not promoted.

BOUNDARY: no three_model_convergence inspection of any kind — the string appears only as a suppression
pattern in searches. No external_research. No schema, architecture, cell, merge or canonicalization.
P-07 and P-08 were read, never modified.

ONE NEXT UNRESOLVED QUESTION: Must K1 retain the FOUR-VALUED Resolution in {Same, Different,
  PossibleSame, Unknown}, or only the positive SameAs adjudications?
```
