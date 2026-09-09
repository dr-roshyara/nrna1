# `P-86` — Contradiction (`Contr`) Prerequisite Audit

**2026-09-09 · Lane T.** After [`106`](./106-READ-RECORD-2026-09-04-EQUIV-SEM-MINKER-AND-A-REVERSED-BLOCK.md).

⛔ **No 3MC inspection · Lane-T persistence findings not used as F4 evidence · `|K| = 11 · [REC] ·
UNFROZEN`.**

# 0. ⚠️ DISAGREEMENT — three, and the third changes the whole queue

## 0.1 ⭐⭐⭐ The audit exists. It is `CR-1`.

`gap-discovery/gap-update-2026-09-02/10-CONFLICT-RECORDS-DECISION-REQUIRED.md` **§`CR-1`** already
delivers this commission's **Step 2** (seven signatures with sources), **Step 3** (semantics, explosion
policy), **Step 4** (five non-collapse constraints) and **Step 5** (`Sat_consistency` dependency).
⭐⭐ **In a permitted folder. `EKS-30` for the sixth time** — ⭐ **and I found it by running the check
first, which is what `EKS-30` asked for.**

## 0.2 ⭐⭐⭐ `Contr` is **not** the "next experiment" — the experiment was **run, twice**

⛔ The commission inherits the register's *"B — Contr · Status: OPEN — **NEXT EXPERIMENT**"*.
⭐⭐ **Both experiments exist, with machine-readable results:**

| | |
|---|---|
| **`KR-CONTR-EVAL-2026-09`** | `docs/knowledgeos/research/theory-v1.2-simulation/W-…` · **`E1`–`E13`** in `research/knowledgeos-sim/results/contr2/` |
| **`KR-CONTR-FDE-2026-09`** | `T1`–`T5`, `G_E12`, `G_E13`, `P_composition_probe` in `…/results/fde/` |

⭐ **The register's own `B1` is discharged.** ⚠️ **What survives is `B2`/`B4` — and `CR-1` types those
as `DECISION REQUIRED`, not experiment.**

## 0.3 ⭐⭐⭐⭐⭐ And the deepest disagreement — **`Contr` is not at the root**

`W-KR-CONTR-EVAL` **§20.2**, *"the review's most consequential structural point, and it reorganises the
whole queue"*:

$$\boxed{\underbrace{\textbf{Required Distinctions}}_{\textbf{⭐ the DECISION that sets everything}} \to \textbf{Evaluation Representation} \to \textbf{Typed Boundary/Reason} \to \textbf{Composition} \to \textbf{Aggregation}}$$

⇒ ⭐⭐⭐ **The approved order `1 Contr → 2 Factivity → 3 ⪰ …` is superseded by the experiment the
register itself commissioned.** ⛔ **The root is `ℛ_req`, and the commission's premise inherits the
pre-experiment ordering.**

⛔ **No `Contr` signature selected · no explosion policy chosen · Factivity, `⪰`, `Sat`, `≡sem`
untouched.**

---

# 1. Step 0 — the mandatory methodological control, run first

| check | result |
|---|---|
| exact duplicates in the `Contr` block | ⭐ **one — `160601` ≡ `162000`, honestly self-labelled *"second copy"*** ✅ |
| reversed re-save | ⛔ **none in this block** — all hashes distinct, content ascends |
| ⚠️⚠️⚠️ **batch-save signature** | ⭐⭐⭐ **71 of 158 consecutive pairs on 2026-09-02 are ONE SECOND apart — 45 %** |

$$\boxed{\begin{array}{c}\textbf{⭐⭐⭐ } \mathbf{180001 \to 180023} \textbf{ is twenty-three files at 1-second increments, containing multi-thousand-line reviews.}\\ \textbf{Nobody wrote 1 348 lines in one second. Those timestamps are } \mathbf{BATCH\ SEQUENCE\ NUMBERS}\textbf{, not save times.}\end{array}}$$

⭐⭐ **This is a second and larger form of `EKS-35`'s defect**, and `106`'s reversal is a **special case**:
within a batch the writer picks an order, and on 2026-09-04 they picked **descending**. ⇒ ⛔ **For 45 %
of that day, timestamp order carries NO argument-order information at all.**

⭐ **Applied:** the `Contr` thread's order was reconstructed **from content**, not timestamps.

# 2. Steps 1–2 — the thread, and the operand table

**Thread:** `155900` prompt → `155903` transcript *(experiment performed)* → `160600` reading strategy
→ `160601` Priest/FDE extraction → `161500` review *(accept with minor corrections)* → `162001`
*"experiment changes how to read the extraction"* → `162002` *"we can implement the FDE mapping"* →
⭐⭐ **`163000` disposition: *"factorized evaluation model, NOT an FDE state model"*** → `175306`
external write-up. ⭐ **Topic changes after `163000`.**

| formulation | operands | type | meaning | status |
|---|---|---|---|---|
| `Contr(p)` | a proposition | unary | property of a proposition | ⚠️ **competing** |
| `Contr(p,K)` | proposition × state | binary | in-state contradiction | ⚠️ competing |
| `Contr(K_t,p)` | state × proposition | binary | argument order reversed | ⚠️ competing |
| ⭐ **`Contr(K_t,σ)`** | state × **scope** | binary | **scope-level** | ⭐⭐ **not recoverable from `Contr(p)`** |
| `Contr(p,C,t)` | proposition × context × time | ternary | context/time-indexed | ⚠️ competing |
| `Contr(p,K,t)` | proposition × state × time | ternary | time-indexed | ⚠️ competing |
| ⭐ **`Contr(P,¬P)`** | a **pair** | binary | pair-level | ⚠️ competing |

⭐⭐⭐ **`CR-1`'s judgement, and it is right: *"genuinely competing — a scope predicate cannot be
recovered from a proposition predicate without a quantifier the corpus has not fixed."*** ⛔ **Not
merged.**

# 3. Step 3 — the formal test

| # | question | result |
|---|---|---|
| 1–3 | domain · codomain · arity | ⛔ **UNRESOLVED** — seven signatures, none selected |
| ⭐ **4** | **semantics** | ⭐⭐⭐ **ESTABLISHED: `Contr(p) ⟺ S⁺(p)=1 ∧ S⁻(p)=1`, i.e. `Σ=(1,1)`** — *"agreed across sources and independently derived"* |
| 5 | total / partial | ⛔ **OPEN** |
| 6 | may `p` carry its own negation? | ⭐ **yes — that is the point:** the two support channels are **independent bits**, so `p` and `¬p` coexist by construction |
| ⭐⭐ **7** | **both `p` and `¬p`** | ⭐⭐⭐ **`Standing = (S⁺,S⁻) ∈ {0,1}²`, and `(1,1)` is a DERIVED CONFIGURATION, not an enum member** |
| ⭐ **8** | **does it collapse the state?** | ⭐⭐ **UNDECIDED, and the two options are CONTRADICTORY:** `GlobalInvalidity` **vs** `LocalConflict`. ⛔ **No classical explosion imposed** |
| 9 | relation to consistency | ⭐ `Sat_consistency` is **one of five classes with no evaluator** |
| 10 | relation to satisfaction | ⭐⭐ **the signature fixes `Sat_consistency`'s input type** |

## ⭐⭐⭐⭐⭐ And the result that dissolves the codomain question entirely

`KR-CONTR-EVAL` §9, **labelled a theorem in its own lane:**

$$\boxed{\begin{array}{c}\textbf{*"No flat domain — of ANY cardinality — indexed by evaluation outcome is adequate."*}\\[4pt] \textbf{⭐⭐⭐ The minimum flat domain size } = \textbf{ the } \mathbf{CHROMATIC\ NUMBER}\ \chi \textbf{ of the required-distinction graph,}\\ \textbf{and } \chi = 3 \textbf{ on the protocol set, } \mathbf{ranging\ 3\text{–}21}\textbf{ with the condition set.}\end{array}}$$

⭐⭐ **So `{0,1}` vs `{⊤,⊥,U}` vs `{⊤,⊥,U,C}` is not a modelling choice — it is a graph-colouring
computation whose answer is a FUNCTION OF `ℛ_req`.** ⛔ **That retires the codomain debate that
`historical_source`, `document1/2/3`, and my own `104`/`105` all treated as an open selection.**

⭐ Minimum structure is **a pair with an indispensable reason/boundary component**; ⛔ **`reason` alone
is inadequate** *(10 values / 21 conditions; fails `Satisfied` vs `Unsatisfied`)*.

## The disposition, at the strength it earns
> ⭐ *"The experiment does **not** support representing contradiction as a distinguished scalar
> evaluation value… **The precise evaluation semantics, including the role of contradiction, remains
> OPEN.**"* · ⛔ *"`EVal = (v,r)` … **is not a KnowledgeOS primitive and must not become one by
> citation.**"* · ⛔ *"`D` is the surviving **tested** candidate, not the final theory."*

# 4. Step 4 — the boundaries, **established**

$$\boxed{Contr(p,K) \not\equiv \{Absent,\ InsufficientEvidence,\ NotAssessed,\ Unknown,\ Unobservable\}(p,K)}$$

⭐⭐ **Five non-collapse constraints, measured.** ⭐⭐⭐ **And `KR-CONTR-FDE` corrects where the ambiguity
sits:** `NotAssessed`, `Underdetermined` and `TheoryIncomplete` land in **`(1,0)`, not `(0,0)`** —
***"a positive `Standing` is just as boundary-ambiguous as an empty one."*** ⇒ **a reason/boundary
channel is required alongside the support pair.**

| boundary | verdict |
|---|---|
| `Contr ≠ Unknown` · `≠ Absent` · `≠ InsufficientEvidence` · `≠ NotAssessed` · `≠ Unobservable` | ⭐⭐ **ESTABLISHED, measured** |
| `Contr ≠ StateCollapse` | ⚠️ **UNDECIDED — that is exactly the `GlobalInvalidity`/`LocalConflict` choice** |
| `Contr ≠ FailedSatisfaction` | ⭐ **ESTABLISHED by construction** — `(1,1)` ≠ `(0,1)` |
| `Contr ≠ Conflict` · `≠ Inconsistency` | ⚠️ **`[UNWITNESSED]` as a separate pair — not tested under those names** |

# 5. Step 5 — dependency on Factivity and `Sat`

$$\boxed{\textbf{⭐⭐ Resolving } Contr \textbf{ NARROWS the } Sat \textbf{ blocker; it does } \mathbf{NOT} \textbf{ remove it, and it leaves Factivity } \mathbf{UNCHANGED}.}$$

⭐ **Narrows:** the signature fixes `Sat_consistency`'s input type, and `Sat_content`'s non-totality is
explained — ⭐⭐ **it needed a pair, and no flat set of any size would have worked.**
⛔ **Does not remove:** the evaluator body is still absent, and `χ` still depends on `ℛ_req`.
⛔ **Leaves Factivity unchanged:** `A-2` — *"the satisfaction family has no place to attach truth"* — is
about **truth attachment**, and `Standing` supplies **support**, not truth. ⭐ **The register's own
first correction says the same: *"Factivity does NOT block Contr."* It is symmetric.**

# 6. Step 6 — F4 relevance

$$\boxed{\mathbf{GENERAL\text{-}KNOWLEDGEOS.}}$$
⭐ `Contr` sits in the **evaluation** layer, which `historical_source` classes **GENERAL**, not
F4-specific. ⛔ **`Sat`'s F4-specific *form* does not make its evaluation content F4** — that
document's own row says so. ⛔ **No F4 claim made.**

# 7. Step 7 — verdict

$$\boxed{\mathbf{B\ —\ Contr\ DEFINED\ BUT\ FORMALLY\ INCOMPLETE.}}$$

⭐ **Defined:** the semantics `(1,1)`; the five non-collapse boundaries; the two-factor shape; and the
**no-flat-domain theorem**.
⛔ **Incomplete:** the **signature** (7 candidates) and the **blast radius** (2 contradictory options).

⚠️⭐⭐ **And the commission's verdict list has no slot for what these are.** ⛔ **They are not research
obligations.** `CR-1`: ***"None selects"*** · **`DECISION REQUIRED`**. ⭐⭐⭐ **The experiment has done
what an experiment can; what remains needs an authority, and Lane T is not it.**

# 8. Status

**`[EMP]`** ⭐ the seven signatures with sources · ⭐⭐ `Contr(p) ⟺ S⁺∧S⁻` agreed across lanes ·
⭐⭐⭐ the `χ` theorem, 3–21 · the five non-collapse constraints · `(1,0)` boundary-ambiguity ·
⭐⭐ **71/158 one-second gaps** · one honest duplicate, no reversal in this block.
**`[DERIVED]`** ⭐⭐⭐ **the codomain question is not a selection but a computation over `ℛ_req`** ·
⭐⭐ **`Contr`'s experiment is discharged; the residue is governance** · ⭐ resolving `Contr` narrows
`Sat` and leaves Factivity unchanged.
**`[OPEN]`** signature · blast radius · totality · `Contr ≠ Conflict`/`≠ Inconsistency` as named pairs ·
`ℛ_req`.
**Governance:** `|K| = 11 · [REC] · UNFROZEN`. ⛔ No cell touched.

# 9. Backlog

⭐⭐ **`EKS-37` filed.** ⛔ **Not `EKS-36`** *(already filed today by the other lane: two same-day efforts
never cross-citing)*. ⭐⭐⭐ **`EKS-37` is a different problem: the estate's single highest-leverage item,
`ℛ_req`, is typed as a DERIVATION by one authoritative source and as *"the DECISION that sets
everything"* by another — so nobody knows whether it goes to research or to governance, and the next
act cannot be commissioned to anyone.** Checked against `EKS-18`, `EKS-32`, `EKS-33`, `EKS-36`.

### 10. THE SINGLE SMALLEST UNRESOLVED QUESTION BEFORE FACTIVITY CAN BE AUDITED

$$\boxed{\begin{array}{c}\textbf{⭐⭐⭐ Is } \mathcal R_{req} \textbf{ — the required-distinction set — a } \mathbf{DERIVATION} \textbf{ or a } \mathbf{DECISION}\textbf{, and who owns it?}\end{array}}$$

⭐⭐ **It is prior to `Contr`, not after it:** `W-KR-CONTR-EVAL` §20.2 puts *"Required Distinctions"* at
the **root**, and §9 makes the minimum evaluation domain **equal to `χ` of the required-distinction
graph**, ⭐ **so `ℛ_req` literally determines how many evaluation values there are.**
⚠️⚠️ **And the estate says two different things about it:** the forward plan's `D1` calls enumerating
`ℛ_req`/`ℐ` *"a **derivation**, not a decision"* and *"the most evidenced blocker — blocked in 66 of 66
worlds"*; `W-KR-CONTR-EVAL` §20.2 calls Required Distinctions ***"the DECISION that sets everything."***
⛔ **Until that is settled the next act has no owner** — and ⭐ **it is answerable from documents already
in permitted folders** (`20260902-181000`, `182001`, `182013`, `182016`).

```
B — CONTR DEFINED BUT FORMALLY INCOMPLETE. AND CONTR IS NOT AT THE ROOT.

THREE DISAGREEMENTS. (1) The audit already exists as CR-1 in gap-discovery, delivering Steps 2, 3, 4 and
5 — EKS-30 for the sixth time, though I found it by running the EKS-30 check first. (2) Contr is NOT the
"next experiment": both experiments were RUN, with machine-readable results — KR-CONTR-EVAL (E1-E13) and
KR-CONTR-FDE (T1-T5, G_E12, G_E13). The register's B1 is discharged; CR-1 types the residue as DECISION
REQUIRED. (3) THE DEEPEST ONE: W-KR-CONTR-EVAL §20.2 — "the review's most consequential structural
point, and it reorganises the whole queue" — puts REQUIRED DISTINCTIONS at the root, above Evaluation
Representation, above Typed Boundary, above Composition. So the approved order "1 Contr -> 2 Factivity
-> 3 ordering" is superseded by the experiment the register itself commissioned.

METHODOLOGICAL CONTROL, RUN FIRST: one honest duplicate in the Contr block (160601 = 162000,
self-labelled "second copy"); NO reversal here; but 71 OF 158 CONSECUTIVE PAIRS ON 2026-09-02 ARE ONE
SECOND APART — 45%. The block 180001->180023 is twenty-three files at 1-second increments containing
multi-thousand-line reviews. Nobody wrote 1,348 lines in one second: those timestamps are BATCH SEQUENCE
NUMBERS. That is a second and larger form of EKS-35's defect, and 106's reversal is a special case —
within a batch the writer picks an order, and on 09-04 they picked descending. The Contr thread's order
was therefore reconstructed FROM CONTENT.

WHAT IS ESTABLISHED: the semantics — Contr(p) iff S+(p)=1 and S-(p)=1, agreed across sources and
independently derived; the five non-collapse constraints, measured — Contr is not Absent, not
InsufficientEvidence, not NotAssessed, not Unknown, not Unobservable; and Standing = (S+,S-) in {0,1}^2
with (1,1) a DERIVED CONFIGURATION, not an enum member.

AND THE RESULT THAT DISSOLVES THE CODOMAIN QUESTION: KR-CONTR-EVAL §9, labelled a theorem in its own
lane — "no flat domain, of ANY cardinality, indexed by evaluation outcome is adequate", with the minimum
flat domain size EQUAL TO THE CHROMATIC NUMBER of the required-distinction graph, chi = 3 on the
protocol set and RANGING 3-21. So binary versus ternary versus quaternary is not a modelling choice; it
is a graph-colouring computation whose answer is a FUNCTION OF R_req. That retires the debate that
historical_source, document1/2/3 and my own 104/105 all treated as an open selection.

WHAT IS NOT ESTABLISHED: the signature — seven genuinely competing candidates, and a scope predicate
cannot be recovered from a proposition predicate without a quantifier the corpus has not fixed; and the
blast radius — GlobalInvalidity versus LocalConflict, which cannot both hold. CR-1: "None selects."

DEPENDENCY: resolving Contr NARROWS the Sat blocker (it fixes Sat_consistency's input type and explains
Sat_content's non-totality — it needed a pair, and no flat set of any size would have worked) but does
NOT remove it, and leaves FACTIVITY UNCHANGED, because A-2's "no place to attach truth" is about truth
attachment while Standing supplies support. The register says the same in the other direction:
"Factivity does NOT block Contr."

F4 relevance: GENERAL-KNOWLEDGEOS. Sat's F4-specific FORM does not make its evaluation content F4.

THE COMMISSION'S VERDICT LIST HAS NO SLOT FOR WHAT REMAINS. The two open items are not research
obligations — they are DECISIONS, and "none selects". The experiment has done what an experiment can.

Backlog: EKS-37 filed — the estate's highest-leverage item, R_req, is typed as a DERIVATION by the
forward plan ("a derivation, not a decision", "blocked in 66 of 66 worlds") and as "the DECISION that
sets everything" by W-KR-CONTR-EVAL §20.2, so nobody knows whether it goes to research or governance.
Not EKS-36, which the other lane filed today for a different cause.

|K| = 11, [REC], UNFROZEN. No 3MC inspection.

THE SINGLE SMALLEST UNRESOLVED QUESTION BEFORE FACTIVITY: Is R_req a DERIVATION or a DECISION, and who
  owns it? It is PRIOR to Contr, not after it — §20.2 puts Required Distinctions at the root and §9
  makes the minimum evaluation domain equal to chi of the required-distinction graph, so R_req literally
  determines how many evaluation values there are. It is answerable from documents already in permitted
  folders: 20260902-181000, 182001, 182013, 182016.
```
