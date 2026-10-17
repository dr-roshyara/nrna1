# R7 · KC-1 readiness audit: how the guard rows R11, R14–R17 and R21 are counted

**Analysis only; decides nothing; draft r1** (2026-10-17). **r0 reviewed PWL; r1 fixes not re-reviewed.** The r0 review re-ran every computation (byte-identical) and verified the partition, the counts, the κ values and the E3-REL points; its findings M1–M9 are mapped in §11.

**What this document does not do.**
- It ratifies, selects, adopts and authorizes nothing. KC-1 is not made.
- It resolves none of: K1; the convention within the resulting family; K9; R80's role; C's parse; the crossing definition (D1 / O2); C-γ (stays returned); D12; the spanning rule (F-LOG-0271); C6; K15–K19; (g); (h); H13; A1; A5.
- E4 (R8) and E5 are not authorized.
- No option is favoured for preserving 13 / 8, or for changing it.

**Standing qualifier (verbatim):** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

**Λ7 disclosure.** Everything here is Claude-family: the sources (T2, T3, E2b, E3, KC, MKC, the matrix, the guard-purpose analysis), their reviews, this audit, its review, and all four blind encoders (§9). Shared interpretive errors would not be detected. This audit shares sources and a commissioning analyst with KC, MKC, T2C and the guard-purpose analysis; it is not independent evidence for any of them.

**Claim labels.** **[SF]** source fact (quoted or located) · **[FD]** formal derivation · **[CR]** computational result (script and output named) · **[E]** analyst assumption · **[Rec]** recommendation · **[HDR]** human decision required.

**Scripts** (`R7/KC-1-READINESS-scripts/`, run with `python3 -B … ..`; no `__pycache__` written; each `.out` sits next to its script):

| file | sha256 prefix | role |
|---|---|---|
| `repro.py` / `repro.out` | `ce2b95db8cfded3a` / `0f51db6dd59a90fb` | re-runs kc, mkc, adhoc, quotes into scratch; diffs; artifact hashes (§1) |
| `readiness.py` / `readiness.out` | `f114fbefa94c8e19` / `e2eb3dbe1dc52f15` | factorisation, enumerated sensitivity space, flip matrix, equivalences (§3, §4) |
| `lex.py` / `lex.out` | `50ac474e639d725e` / `5395dc4cff971169` | text checks for rule 1 vs N, conflict witnesses for N_gen, parse robustness, relations-table comparison (§2.3, §2.6); r1: comments only (held lists labelled [E]; parses labelled non-independent), output unchanged |
| `blind_eval.py` / `blind_eval.out` | `0c24f3a070e8a4cf` / `c2ea005966c84154` | evaluation of the blind determinacy test (§9); r1: intervals labelled descriptive only |
| `blind/` | see §9 | packets A and B, `key_sources.md`, the four encodings, `agree.py`, `agree.out`, the packet build scripts |

`readiness.py`, `lex.py` and `blind_eval.py` import `mkc.py` (and through it `kc.py` and `blind_enc.py`) unchanged.

**Enumerated space (applies to every "only", "invariant", "hinge" and "robust" claim below).** The sensitivity results hold **within the enumerated space** of §3.1: rest principle, K1, R80, C's class-level clause, K8, K9, K13, spanning rule, D12. **Not enumerated:** K18 (the source of ρ; the matrix records that T2's "rests on" column moves E3-REL to **6 / 3**); K2–K7 and K10–K12, which enter only as fixed parts of the four rest principles and are never varied independently; K15–K17 and K19.

---

## 0. Summary

| question | result |
|---|---|
| **T3's "internal tension" (§2.3), correcting the KC / MKC framing** | **The contradiction is not in T3's text.** T3's negation sentence N (T3:68) sits inside rule 2's bullet "What counts as "reading C1"", and its two examples are the contrast clauses of R26 ("with no Cap condition") and R30 ("no Cap or status condition") [SF]. AUDIT-Q6-TEST1 F11 already read it as covering contrast clauses only [SF]. **The A1 synthesis (KC), N_gen "negations and absent clauses are not read", conflicts with rule 1 at all six guard rows [FD on an [E] premise; witnesses CR]. T3's text read in its own scope and examples has no conflict** [FD]. KC A1 and MKC's "internal tension" are therefore properties of KC's generalisation, not of T3. Lex specialis (rule 1's closed list over N_gen) is one way to reconcile T3 with A1; T3's own crossing table is the output of either reading [CR] |
| Does T3's reading need more than T2's tier label? (§2.4) | **Not as a row selector, for the 83 rows**: rule 1 carries its own closed list, and the tier token "guard form" has exactly the same extension [CR]. It needs a determinable denied tie, which is verdict-robust across the parses tried [CR]. A priority meta-rule is needed **only if** N is generalised to N_gen [E]. For rows outside the 83, the intensional selector (T2's unadopted label) would be needed [E] |
| Is T3 single-principle? | **No.** It is consistent and deterministic but non-uniform: R16 and R80 have the same logical form and are treated differently (MKC CE-form-1). That is Rq3, an [E] stipulation, not a contradiction [FD] |
| Space enumerated (§3) | **928 encodings × 3 spanning rules = 2,784 evaluations; × 2 D12 readings = 5,568 E3-REL values.** Native subspace (each named convention with only its own open boxes): 47 encodings, 141 evaluations, 282 E3-REL values [CR] |
| Invariant within the enumerated space (§3.3) | E3-REL's non-guard part equals the ratified non-guard part, so **E3-REL (D12 a) is moved by K1 and by the spanning rule only, among the parameters enumerated**; rest principle, R80, K8, K9, K13 never move it [CR]. (K18, not enumerated, does: 6 / 3.) S′ = S″ on all 83 rows. Under S, P-c is never the strict minimum. Under S′ / S″ no change, status or conformance clause row crosses. K8, K9 and K13 never move a row verdict [CR] |
| Hinging on exactly one premise, within the enumerated space (§3.4) | "E3-REL = the ratified 13 / 8 list" (**F-LOG-0297's E3-REL box**), "T-A host cells = ratified" and "guard rows keep their ratified verdicts" each hinge on **K1 alone** and hold iff K1 = (a) [CR]. **F-LOG-0297 as a whole does not**: it also accepted the cut-status columns, and under rest K with K1 = (a) the R27 / R28 cut-status cells change under S (KC-3, KC:468) [SF + CR]. The R-i / R-i′ record asymmetry is never moved by K1 [CR] |
| Undefined or non-deterministic combinations (§3.5) | **Computationally none.** At specification level: "C without a parse" is a family (four guard-verdict vectors, three E3-REL values); K with R80 = guard-form denial contradicts K's role rule [FD]. T3's text is not among them (row 1) |
| Sensitive rows (§4) | 48 of 83 rows are **robust within the enumerated space**. Exactly the six guard rows are moved by K1 and by no other enumerated parameter. The other sensitive rows each move under one premise or one pair [CR] |
| Blind determinacy test (§9) | Packet A: **no disagreement observed between 2 encoders** at verdict level; both reproduce T3's six guard verdicts (13 / 8). Packet B: the two encoders disagree, at verdict level at **R15 and R21** (R11 is CB23 for both), landing at **10 / 5 and 9 / 4** [CR]. This is an existence result for **packet B's** text, which was less specified than packet A's (§9) |
| Disqualification (§5) | **No option is disqualified** on [U] grounds (all pass Rq6, Rq8), for irreducible non-determinism, or for internal contradiction. Disqualified as readings only: N_gen combined with rule 1 (no model), and "C" without a parse as a single convention (it is a family) [FD] |
| Outcome warranted (§5.5) | **(4) a genuine human normative choice on K1.** The determinacy evidence is recorded as an **observation**, not a preference (§5.5) |
| D1 / O2 (§2.6) | Under C's content sense of "reads", K1 (b) SH or (c) K1c makes E3-REL coincide with the T-A rows of T3's (unadopted) relations-table "held" column [CR, held lists [E]]. The coincidence is near-definitional (the held column excludes guard rows by construction). **O2 is unnecessary for that output only if C's content sense of "reads" is accepted**; under that sense R14 names f, d and r yet reads nothing [FD] |

---

## 1. Reproduction (task 1)

**Re-run** [CR, `repro.out`]: kc.py, mkc.py and adhoc.py, run with `python3 -B` into a scratch directory, are byte-identical to the committed `.out` files. quotes.py differs in exactly four lines:
- the matrix hash (`bc5d1c2664726172` → `46b55196f90da25c`) and the log hash (`02bd04f4ae0235ae` → `a485b8f7bf7ccbe1`);
- Q57 "MISSING" at matrix line 88, and the final line "1 quote(s) missing".

**Cause** [SF, git history]: `quotes.out` was produced against the matrix at commit `eacec7727` (hash `bc5d1c…`) and the log before F-LOG-0306. Commit `3a03ad4cd` then added the 2026-10-16 note to the matrix (two lines, so Q57's text moved to line 90, where it is verbatim) and appended F-LOG-0306. **No quote is wrong; one line reference is stale.** This is a recording discrepancy in the guard-purpose analysis §8, not a content error. Nothing is edited here.

**Artifact versions reviewed** (sha256 prefixes, `repro.out`):

| artifact | version | prefix | final fixes reviewed? |
|---|---|---|---|
| KC-1-DECISION-MATRIX.md | r1.1 + 2026-10-16 note | `46b55196f90da25c` | **r1.1 fixes not re-reviewed** |
| KC-GUARD-PURPOSE-ANALYSIS.md | r1.1 | `53685c6041ed1dcf` | **r1.1 fixes not re-reviewed** |
| KC-MKC-REASSESSMENT.md | r1.2 | `8cccf5a32aa7c668` | **r1.2 fixes not re-reviewed** |
| KC-CONVENTIONS-PROPOSAL.md | r1.1 | `2cfa32ccd823605c` | **r1.1 not re-reviewed** |
| AUDIT-Q6-TEST2-CLAUSE-COORDINATE-TABLE.md | r1.1 | `212dcdb1b19aa58b` | **r1.1 wording fixes not re-reviewed** |
| T3-CANDIDATE-MODEL.md | r1.9 | `24c4b5bf8cc343ac` | — |
| T2-RULE-CANDIDATES.md | r1.4 | `463527a7386c063f` | — |
| E3-ARCHITECTURE-PROPOSAL.md | r1.3 | `b975d9dadd7704aa` | — |
| F-GOVERNANCE-LOG.md | through F-LOG-0306 | `a485b8f7bf7ccbe1` | — |
| kc.py · mkc.py · adhoc.py · blind_enc.py | as committed | `a697c384…` · `10d40efb…` · `91935bc6…` · `03343c88…` | hashes equal those cited in KC and MKC |

---

## 2. Formal model (task 2)

### 2.1 Objects

[FD, restating KC §2.1 and MKC §1.2; no new content]
- Rows X = {R1, …, R83}, partitioned into tiers T-A (R1–R24), T-B (R25–R39, R79, R80, R83), T-C, T-D. The guard list L = {R11, R14, R15, R16, R17, R21}.
- Concepts C1–C28 with placement π: C → {CB1, CB23, ⊥}. CB1 = {C13–C18, C23, C24, C27}; CB23 = {C2–C12, C19–C21}; C1 spans; C22, C25, C26, C28 → ⊥.
- **Counting function.** A convention Π assigns ρ_Π(x) = (A_x, f_x): a concept set A_x ⊆ C ∖ {C1} and an actor flag f_x ∈ {none, N, R, B, U}.
- **Cross predicate** for spanning rule s and cut (P, Q): sides_s(x) = {π(c) : c ∈ A_x, π(c) ≠ ⊥} ∪ span_s(f_x), where span_S(f) = {CB1, CB23} for any flag, span_S′(f) = ∅, span_S″ = N ↦ {CB23}, R ↦ {CB1}, B ↦ both, U ↦ ∅. x crosses iff |sides_s(x)| = 2. Verdict v_s(x) ∈ {X, CB1, CB23, none}.
- **E3-REL** (E3 r1.3 §2.3) [SF]: E3-REL_{s,D12} = {x ∈ T-A : v_s(x) = X} ∖ H, with H = {R12, R13, R23, R24} under the accepted D12 reading and H = {R12, R13, R23} under the other (R24 becomes shared; E3:99, 113).
- **Host rule** (E2b §A1.3 / §A1.4) [SF]: for x ∈ T-A ∪ T-B, the host cell is v_s(x), with X = "hosted on both". The ratified lists RAT_S, RAT_S′ (= RAT_S″) are the reference.
- **Rank** [SF, KC §3.1]: over the 817,190 cuts of the 23 placed concepts into 9 │ 14, cr(cut) = number of T-A + T-B rows that cross; reported as (cr(P-c), #cuts strictly fewer, #cuts equal including P-c). Under S a flagged row crosses every cut (RESEARCH-AUDIT-1 Lemma 1's assumption).

### 2.2 Factorisation of every convention

**By construction** [FD]: `readiness.py`'s `compose` takes a rest principle's encoding and overrides the guard rows with a K1 value, so ρ_Π = ρ_rest ⊕ ρ_K1 is a property of how the space is built. What is computed [CR, `readiness.out` §0] is that every **named** convention equals such a composition:

| convention | rest | K1 value at L | check |
|---|---|---|---|
| T3 | T3 (FZ) | (a) denied items | FZ |
| M | M | (a), up to K7 at R15 (R15 without E; verdict-equal) | verdicts equal FZ on L under S, S′, S″ |
| K, K-T | K | (a), FZ's sets | K-T = K on all 83 rows |
| U | U | (a), FZ's sets | — |
| C (K1c) · C-SH · C-R15ref · C-R15ref-R14 | **K** | (c) K1c · (b) SH · (c) R15ref · (c) R15ref-R14 | each equals K-rest + parse on all 83 rows |
| C-Σ | **K** | (c) with premise A-ii | equals K-rest + Σ parse on all 83 rows |

**Limit of the factorisation** [FD]. For M, U and C, the K1 value is **entailed by the principle** (M and U read every denial; C reads only assertions). Treating rest and K1 as independent choices for those conventions is therefore **option P** (a per-convention selection), which conflicts with the provisional "single principle" criterion (F-LOG-0302). Only T3 and K (K-T) take K1 as a separate stipulation (rule 1's list; K's role label). The off-diagonal points of §3 are option-P points.

**Per convention: assumptions, sources, undefined cases.**

| convention | necessary assumptions | source-backed rules | analyst's rules [E] | undefined cases |
|---|---|---|---|---|
| T3 | N read in its own scope (rule 2's C1 bullet, contrast-clause examples), as F11 reads it; FZ where T3 is silent (K9 = A.0; K10 read-through from FZ F5) | rules 1–5, 2a (T3:66–76) [SF, unratified] | K9 = A.0; conforms read-through; Den(x) per row | none on the 83 rows. **If N is generalised to N_gen (KC A1), no model** (§2.3) |
| M | level of wording; abbreviation expansion of clause names | KC §2.1 definition (analyst) | everything; M is an analyst principle | none: no M choice point moves a row (`adhoc.out`) |
| K / K-T | a role assignment (which rows "prohibit"); R80's role | role list = T2 tier label / T3 list [SF, unadopted] | K's definition; R80 empty clause; R79 a delimiting contrast | R80 as guard-form denial contradicts K's role rule |
| C | a parse of the guard rows' asserted residue; C's class-level clause | KC §2.1 definition (analyst) | the parse (K1c / SH / R15ref / R15ref-R14); class-level reading | **without a parse, C is a family** (§3.5) |
| C-Σ | logical-form classification A-i; exceptive premise A-ii | MKC §1.4 (analyst) | all of A-i, A-ii | none once A-i, A-ii fixed; verdict-equal to C-R15ref |
| U | polarity-uniform reading; R80 "acts exist" part | MKC §6.2 (analyst) | all | R80: whether the "acts exist" part is read (adhoc) |

### 2.3 T3's "internal tension": in the text, or in the A1 synthesis? (key question)

**The texts** [SF, `lex.out` §1]:
- **G** (T3:66, rule 1): "A **guard row** (T-A guard form: R11, R14–R17, R21) is judged by the items whose tie or order it denies". A closed extensional list, with a worked example (R14).
- **N** (T3:68): "Negations ("with no Cap condition", "no Cap or status condition") are not read, as for R79 and R68". N sits **inside rule 2's bullet "[r1.7] What counts as "reading C1"."** Its two quoted examples are the contrast clauses of **R26** ("… with no Cap condition", T2:85) and **R30** ("(no Cap or status condition)", T2:89). Its cited rows R79 and R68 are likewise contrasts inside positive statements (R79 "not that a record references it"; R68's capture contrast).
- Rule 1: "A **T-B row** is judged by its clause text only" (T3:66); rule 5: "A row reading no placed item (an absent clause, …) is "none"" (T3:76).
- **AUDIT-Q6-TEST1 F11** (line 74) already reads the convention so: "a contrast clause ("with no Cap condition", "not an absence in capture") is not read; a guard row reads the items whose tie or order it denies; a T-B row that states no clause (R80) reads nothing" [SF].
- T3 has no other "not read" rule for denials; the only other "not read" sentence (T3:198) concerns row references [CR].

**Formalisation.** For a row x, let Den(x) be the items of the tie or order x denies, and Pos(x) the items of what x asserts (K1c is one realisation).
- **N_scope (T3's text) [SF scope; FD consequence].** A contrast clause *inside a positive statement* contributes nothing. A guard row is not a positive statement with a delimiting contrast: its whole statement is the denial. So dom(N_scope) ∩ L = ∅, and G and N_scope never apply to the same part of the same row. **No conflict; no priority rule needed.** Absent T-B clauses (R80) are handled by rule 1's T-B sentence and rule 5, which also do not meet L (L ∩ T-B = ∅, `lex.out` §4).
- **N_gen (KC's A1 synthesis) [E].** "Negations, denials and absent clauses are not read", as an exceptionless universal over all rows. With G also exceptionless: for x ∈ L, ρ(x) must contain Den(x) and exclude the items occurring only in the denied part. **If Den(x) ∖ Pos(x) ≠ ∅, no ρ(x) exists.** [FD, on the [E] premise N_gen]
  - Witnesses [CR, `lex.out` §3]: Den ∖ Pos (K1c) is non-empty for **all six** guard rows: R11 {C5, C8, C9, C12}; R14 {C11, C12, C14, C22}; R15 {C8, C9, C11, C12, C14, C18, C22}; R16 {C8, C9, C13, C17, C19, C23}; R17 {C5, C6, C8, C9, C19}; R21 {C6, C12}.
- **Lex specialis.** If N_gen is adopted anyway, giving G priority on its closed domain L restores consistency: a prioritised system G ≻ N_gen in which every row gets exactly one ρ [FD]. This is a repair for the synthesis, not for T3's text.

**Result.**
- **[FD]** **The A1 synthesis conflicts with rule 1 at six rows; T3's text read in its own scope and examples has no conflict.** KC A1 ("T3 mixes M and C at A1") and MKC's "internal tension" describe the synthesis. They remain true as a statement of **non-uniformity**: T3 treats denials in guard rows and denials elsewhere differently (MKC Rq3, [E]). That is not inconsistency.
- **[FD]** **Determinism.** L is enumerated, so domain membership needs no label. The residual freedom is the parse of Den(x). Across six realisations of Den(x) (four analyst parses: FZ; M's R15; MKC's K-T A-i variants combined; a minimal-tie parse [E]; plus the two blind A encodings), **every guard row takes one verdict** [CR, `lex.out` §5]. **These realisations are not independent**: all start from the same row texts, and the minimal-tie parse is built from the tie endpoints that are then used to explain the robustness. The structural reason [FD]: under (a) the verdict depends only on whether the denied tie has an endpoint on each side, and each guard row names those endpoints in its own label.
- **[CR]** T3's crossing table is the output of T3's text read either way (N_scope, or N_gen with lex specialis): FZ reproduces the ratified lists exactly (KC §3.4).

### 2.4 Selector and other inputs for T3's reading

- **[CR]** The T2 tier cells containing "guard form" are exactly R11, R14–R17, R21, equal to rule 1's list (`lex.out` §2). T2's R21 inconsistency (tier "guard form; modelling choice" vs the counts line "guard / non-constraint") concerns D2's *non-constraint* half, not the selector.
- **[FD]** For the 83 rows, T3 needs **no** external selector: rule 1 enumerates L. T2's label is provenance, not an input. A priority meta-rule is needed only under N_gen [E].
- **[FD]** For rows outside the 83, the intensional criterion "T-A guard form" (an unadopted T2 label [E]) would be needed. That is an extension risk, not a present indeterminacy.
- **Qualification of the guard-purpose analysis F3** ("D2 selects the K1 row domain under every option"): true **for the evaluation** of every option; **definitionally**, only T3, K and K-T use a selector. M, C, C-Σ and U apply one rule to every row [FD].

### 2.5 Equivalences under stated assumptions [CR, `readiness.out` §8]

| pair | relation | assumption |
|---|---|---|
| K ~ K-T | **encoding-equal** (all 83 rows) | none (MKC J1, confirmed) |
| C-R15ref ~ C-Σ | **verdict-equal** on all 83 rows, S and S′ (encoding differs at R11) | R80 and K9 boxes set alike |
| C-K1c ~ C-SH | E3-REL-equal (9 / 4); differ on R11, R15, R21 host cells | as above |
| T3 ~ K | host-equal on T-A + T-B under S′ / S″ (differ only on T-C / T-D R62, R71, R75); differ on R27, R28 (plus T-C rows) under S | R80 = empty clause in both |
| T3 ~ M ~ K ~ U | E3-REL-equal (13 / 8) under every native setting | K1 = (a) |
| T3, M, K, K-T, U at L | guard-row verdict-equal | none (§2.2) |
| K ~ U | verdict-equal except R79, R80 | — |

**[FD]** Under the K1 coordinate alone, the options fall into **five guard-verdict classes**: (a) = {T3, M, K, K-T, U}; SH; K1c; R15ref = C-Σ; R15ref-R14.

### 2.6 The crossing definition: D1 kept, or O2? (counting convention vs redefining a term)

- **[SF]** D1 ("a crossing records which items a row's statement reads") is uniform across the unratified texts (guard-purpose analysis F1).
- **[CR on [E] lists]** Under K1 (b) SH and under K1 (c) K1c, E3-REL equals the T-A rows of T3's relations-table "relation held" column (T3:310), under S (R2, R3, R5, R6, R8, R9, R10, R19, R22) and under S′ (R2, R3, R9, R22) (`lex.out` §6). **The held lists are hand-transcribed [E]; the S′ list is inferred**, because T3:310 marks R8 as internal under S′ / S″ and R5, R6, R10, R19 as S-only but states no S′ set. Under K1 (a), E3-REL adds exactly R14, R15, R16, R21; under R15ref and C-Σ it adds R15, which T3:310 lists under "not held" ("R15 guard").
- **[FD] The coincidence is near-definitional.** T3's held column excludes guard rows by construction (it cites them only under "not held"), and K1 (b) / K1c differ from (a) only at guard rows. It is not independent corroboration of anything.
- **[FD] Conditional result.** A coupling-like E3-REL is obtainable by a counting convention while D1's wording ("reads") stays, **provided C's content sense of "reads" is accepted**: under K1 (b), R14 *names* f, d and r yet reads nothing. If "reads" is taken in its naming sense, K1 (b) is itself a reinterpretation of D1, and the line between "counting convention" and "redefinition" (O2) narrows. Whether that reinterpretation is acceptable is **[HDR]**.
- **[SF]** The relations table is unadopted, and T3 says "A crossing is not a boundary relation" (T3:299).

---

## 3. Sensitivity analysis over the enumerated space (task 3)

### 3.1 The space [CR, `readiness.out` §1]

| parameter | values | applicability |
|---|---|---|
| rest principle | T3, M, K (= K-T), U | all |
| K1 | (a) the rest's own guard rows; (b) SH; (c) K1c; (c) R15ref; (c) R15ref-R14; (c) C-Σ | all (off-diagonal points are option P, §2.2) |
| R80 role | empty clause; VC8 "acts exist"; guard-form denial {C8, C9, C13, C17}; denial, record part only {C13, C17} (adhoc's U alternative) | all; native only where the convention leaves it open |
| C class-level clause (Ccls) | read; nothing (R23, R24, R66–R70, R82 empty) — adhoc's C choice point | only where rest = K and K1 ≠ (a) |
| K8 | (a) generic act → C8 + C9; (b) C8 only | all (approximate reconstruction, KC §6) |
| K9 | (a) A.0; (b) BL value mapping | all (approximate) |
| K13 | (a) included; (b) VC6 | all |
| spanning | S, S′, S″ | all |
| D12 | accepted; other (R24 shared) | E3-REL only |

**Count:** 4 × 6 × 4 × 2³ = 768 base points, plus 160 for Ccls = nothing → **928 encodings; 2,784 (encoding, spanning) evaluations; 5,568 E3-REL values.** Native subspace: **47 encodings** (T3 1; M 2; K 4; U 4; C-SH, C-K1c, C-R15ref, C-R15ref-R14 8 each; C-Σ 4). K8 and K13 are (a) at every native point because every principle agrees with T3 there (KC §2.2) [SF].

**Not enumerated:** K18 (moves E3-REL to 6 / 3 per the matrix and KC §3.5; weak, 7 rows lack the column); K2–K7 and K10–K12 (fixed inside the rest principles, never varied independently); K15–K17, K19.

**Implementation note** [CR]: kc.vm (K9 (b)) adds C8 to R23 unconditionally, so it would re-fill an emptied R23. `readiness.py` applies Ccls after K8 / K9 / K13. With that order, K9 moves no row verdict, as KC states.

### 3.2 (i) Assumption → consequence [CR, `readiness.out` §4]

Counts are single-parameter flips (pairs of points that differ in that parameter only) that change the output.

| parameter (flips) | E3-REL D12 a | E3-REL D12 b | host-diff cells | rank | S′-minimality | asymmetry | any row verdict |
|---|---|---|---|---|---|---|---|
| rest (3,456) | **0** | **0** | 2,688 | 3,350 | 229 | 2,688 | 3,456 |
| **K1 (6,720)** | **5,760** | **5,760** | 6,240 | 6,116 | 105 | **0** | 6,240 |
| R80 (4,176) | 0 | 0 | 4,176 | 2,956 | 204 | 2,088 | 4,176 |
| Ccls (480) | 0 | 480 | 480 | 480 | 0 | 0 | 480 |
| K8 (1,392) | 0 | 0 | 0 | 1,392 | 336 | 0 | **0** |
| K9 (1,392) | 0 | 0 | 0 | 1,392 | 516 | 0 | **0** |
| K13 (1,392) | 0 | 0 | 0 | 1,308 | 148 | 0 | **0** |
| spanning (2,784) | 1,856 | 1,856 | 1,088 | 2,521 | 556 | 1,856 | 1,856 |

The 960 K1 flips that leave E3-REL unchanged are the pairs SH ↔ K1c and R15ref ↔ C-Σ [CR].

**E3-REL by K1 value** [CR, §3]: (a) 13 / 8; SH and K1c 9 / 4; R15ref and C-Σ 10 / 5; R15ref-R14 11 / 6, attained by every enumerated point with that K1 value. Under D12 (b), +1 / +1, except where Ccls = nothing (R24 then reads nothing, so +0).

**Native results per named convention** (`readiness.out` §2) reproduce every matrix figure: T3 13 / 8, 0 host cells, ranks 24 / 10 / 78 and 17 / 0 / 1; M 2 / 2 cells, 19 / 0 / 2; K 2 / 0, 26 / 64 / 304; C-K1c 7 / 5, 22 / 46 / 234; C-Σ 6 / 4, 23 / 34 / 155; U 4 / 2, 28 / 469 / 1246 [CR]. New beyond the matrix: K9 (b) breaks S′-minimality for M, the C family, C-Σ and one U point (e.g. M 19 / 2 / 5; C-K1c 13 / 1 / 10); Ccls = nothing lowers C's P-c crossings by 2 [CR].

### 3.3 (ii) Invariant within the enumerated space (2,784 evaluations) [CR, `readiness.out` §5]

1. E3-REL (D12 a) ⊇ {R2, R3, R9, R22} under every rule; under S ⊇ {R2, R3, R5, R6, R8, R9, R10, R19, R22}.
2. E3-REL (D12 a) ⊆ the ratified 13-row S list, and **E3-REL ∖ L = ratified ∖ L**. Hence, within the enumerated space, **E3-REL membership varies only at R14, R15, R16, R21**, and **E3-REL = ratified ⇔ K1 = (a)** [FD from 1–2 and §3.2]. (K18, not enumerated, would break this.)
3. Verdicts under S′ equal those under S″ on all 83 rows.
4. Under S, P-c is never the strict minimum (928 / 928).
5. Under S′ / S″, no change, status or conformance clause row crosses (H-D or H-B).
6. Under S, R25 (D-chg) crosses; under no rule does an H-B change / conformance row (R30–R34, R37) cross.
7. K8, K9 and K13 never move a row verdict (they move only the rank).
8. **K1 never moves an asymmetry record** (0 of 6,720 flips).

**Not invariant:** P-c minimality under S′ / S″ (540 / 1,856; 426 / 464 with K8 = K9 = a); R24 crossing (fails exactly when Ccls = nothing); the R-i and R-i′ row-level asymmetries (1,632 and 2,088 of 2,784).

### 3.4 (iii) Conclusions that hinge on exactly one premise, within the enumerated space [CR, `readiness.out` §6]

| conclusion | true in | depends on (enumerated parameters) |
|---|---|---|
| E3-REL (D12 a) = ratified 13 / 8 list (**F-LOG-0297's E3-REL box**) | 384 / 2,784 | **K1 only** |
| E3-REL (D12 b) = 14 / 9 | 384 / 2,784 | **K1 only** |
| T-A host cells = ratified | 384 / 2,784 | **K1 only** (Ccls also moves T-A cells, but only where K1 ≠ (a) already broke them) |
| all six guard rows keep their ratified verdicts | 384 / 2,784 | **K1 only** |
| R-i / R-i′ record asymmetry at row level | 1,224 / 2,784 | rest, R80 (not K1) |
| S-only H-D asymmetry widened to D-st | 544 / 2,784 | rest, spanning |
| T-A + T-B host cells = ratified | 40 / 2,784 | rest, K1, R80, spanning |
| P-c minimal / unique minimum | 540 / 280 of 2,784 | rest, K1, R80, K8, K9, K13, spanning |

**F-LOG-0297 as a whole** [SF + CR]: the act also accepted "the cut-status columns (all other cut-status cells)". Under rest K with K1 = (a), R27 and R28 cross under S, so their cut-status cells change and **KC-3 is triggered** (KC:468), although the E3-REL box stands. Only the E3-REL box hinges on K1 alone.

**P-c's S′-minimality.** In the native subspace it holds at 27 of 47 points, and every native point with K9 = (a) is minimal [CR, `readiness.out` §9]. Outside the native subspace it also fails with K8 = (b): **the review's re-run of T3 with K8 = (b), K9 = (a) gives 17 / 1 / 4** (as KC §1 already records), and with R80 = denial or K13 = (b) [CR]. So S′-minimality depends on **K8, K9, K13 and R80** (and on rest and K1); no single box is necessary for it to fail.

### 3.5 (iv) Undefined or non-deterministic combinations

- **[CR]** Computationally none: every one of the 928 encodings yields one verdict per row per rule.
- **[FD]** At specification level:
  - **"C" without a parse** names a set of four guard-verdict vectors (SH, K1c, R15ref, R15ref-R14; C-Σ coincides with R15ref) with three E3-REL values. It is a family, not a convention.
  - **Rule 1 combined with N_gen** (KC's A1 synthesis, [E]) has no model (§2.3). T3's text in its own scope is not affected.
  - **K with R80 = guard-form denial**, and K / C with R80 = the record-only denial, contradict K's own role rule (KC §5 dependency note); they exist only as option-P points.
  - **U with R80 = empty or VC8** departs from U's definition; likewise option-P points.

---

## 4. Row-level anomaly analysis (task 4)

**Matrix** [CR, `readiness.out` §7]: for each of the 83 rows and each enumerated parameter, the number of single-parameter flips that change the row's verdict (spanning included).

**Clustering method.** Question: which rows move under which enumerated premises? Input: the row × parameter flip-count matrix. Method: **exact partition by the set of parameters with a non-zero flip count** (no distance, no threshold, no learned model). Assumptions: the space of §3.1. Limitations: it ignores flip magnitudes, interactions beyond single flips, and every parameter not enumerated (K18; K2–K7 and K10–K12 inside the rest principles). No score decides anything.

| cluster (parameters that move the row) | rows | verification |
|---|---|---|
| none: **robust within the enumerated space** | **48 rows**: R1–R4, R7, R9, R12, R13, R18, R20, R22, R26, R29–R37, R39, R83, R40–R44, R46–R51, R53–R55, R57–R59, R81, R64, R65, R72–R74, R77, R78 | [CR] zero flips under every enumerated parameter |
| K1 only | **R11, R14, R15, R16, R17, R21** (exactly L) | [FD] K1 replaces only rows of L (by construction), and every L row takes ≥ 2 verdicts across K1 values |
| R80 only | R80 | [FD] the R80 box sets only R80 |
| Ccls only | R23, R24, R66–R70, R82 | [FD] Ccls empties exactly these rows |
| rest only | R79, R45, R52, R62, R75 | [inspection] R79: M / U read its contrast (K5d); R45, R52: K7 relational clause (M off); R62: K11 (K, U read "independent" through); R75: K4b row references (K, U) |
| spanning only | R5, R6, R8, R10, R19, R25, R38, R63, R76 | [inspection] each carries an actor flag in every rest encoding; under S it crosses, under S′ / S″ it does not |
| rest × spanning | R27, R28, R56, R60, R61, R71 | [inspection] actor read only under K / U (Adopted, Cap read through: K3, K6) or via rest-dependent row references (R71); visible under S only, except R71 |

**Most flip-prone rows** (total flips): R80 (4,176), R15 (4,128), R21 (3,840), R14 (3,456), then R11, R79, R62, R71, R75 (2,304 each) [CR]. Among the guard rows, R15 and R21 take three verdicts across K1 values; R16 and R17 take two (X or CB23 under (a); none under every (b) / (c) parse) [CR, `lex.out` §5].

**Native subspace** [CR, §7b]: 25 rows (S) and 21 rows (S′) vary among the 47 native encodings; each is in a sensitive cluster above.

---

## 5. Disqualification test (task 5)

### 5.1 [U]-level grounds

- **Rq6 separability** [U]: every convention passes, by construction of ρ (§2.1) [FD].
- **Rq8 family neutrality** [U]: no convention's rule mentions H-D or H-B (matrix) [SF].
- **No option is disqualified on [U] grounds** [FD].

### 5.2 Irreducible non-determinism

- **"C without a parse"** is a family (§3.5). This is **reducible** by fixing a parse; it disqualifies only using "C" in an act without the parse box filled, which KC §5 already requires [FD].
- **Packet B's text** was non-deterministic between two blind encoders at R15 and R21 (§9) [CR, n = 2]. Also reducible, by enumerating the parse.
- **No option is irreducibly non-deterministic** [FD].

### 5.3 Internal contradiction

- **T3's text read in its own scope has no contradiction** (§2.3). The conflict arises only if N is generalised to N_gen (KC A1, [E]); that reading is disqualified as a reading of T3, and T3 is not disqualified [FD].
- No contradiction was found in M, K, K-T, U, C-with-parse or C-Σ [FD, within the 83 rows].

### 5.4 Not disqualifying [E] stipulations

Rq1–Rq5, Rq7h, Rq7c and Rq9 are [E] or [E-s] (MKC §2.2). None is used to disqualify or to rank.

### 5.5 Which outcome the evidence warrants

**Determinacy, stated symmetrically (observation, not preference).** "Interpretation (a)" covers two different things, and Rq1 (an [E] stipulation) grades them differently in MKC:
- **Text-determinate (a)**, stated intensionally ("a row that denies a tie reads the tie's items") with no list: the blind A arm agreed on all guard verdicts (2 encoders), **but the same rule moved the controls R79 and R80 off T3** (both crossed; §9). Applied to every row, it is M / U-like: MKC grades M ✓ and U ~ on Rq1.
- **T3's (a)** is determinate through its **enumerated list** L, plus N read in its own scope (or a priority clause if N_gen is wanted). MKC grades T3 ✗ and K ✗ on Rq1, because the enumeration (or label) is a choice point that moves E3-REL. That grade is consistent with this audit: T3's determinacy is by stipulation, not by the text of the rule alone.
- **(b) / (c)** is determinate only with an enumerated parse (C-K1c, C-SH, C-R15ref, C-R15ref-R14, or C-Σ via A-ii). Packet B's text, without a parse, was not (two encoders, two E3-REL points). MKC grades C ✗ and C-Σ ✗.
- So **every option that keeps T3's R79 / R80 verdicts needs a stipulated row list or parse**; the only text-determinate (a) changes R79 / R80. Determinacy does not separate (a) from (b) / (c) once each is stipulated as fully as T3 is. **It is recorded as an observation, not as a conditional preference.**

| outcome | warranted? |
|---|---|
| (1) some options disqualified | **No** (only readings: N_gen with rule 1; C without a parse as a single convention) |
| (2) a conditional preference | **No, on this evidence.** The determinacy comparison is symmetric once each option's stipulations are counted (above), and Rq1 is [E] |
| (3) a unique recommendation | **No**: the sources conflict on K1 (guard-purpose O1), and no [U] requirement separates the options |
| (4) a genuine human normative choice | **Yes.** K1 is a choice about what the counts should count. The evidence locates it exactly (§3.4, within the enumerated space) and states its consequences (§7), but does not make it |

---

## 6. DDD implications (task 6)

**Facts both choices share** [CR/SF]:
- The concept placement, and so the cut E3-M1 / E3-M23 itself, is untouched by K1. With the native boxes at K8 = K9 = (a), P-c remains the unique minimum under S′ / S″ for every K1 value on T3-rest (17 / 0 / 1 under (a); 13 / 0 / 1 under (b) SH and (c) K1c; `readiness.out` §10) [CR]. Under S it is never minimal [CR].
- T3 §1b [SF]: "the groupings are conceptual clusters only, not units within which anything could be enforced" (via R23 / Lem 1.12), and "The grouping boundaries are therefore not derivable from T1/T2; they are VP11".
- E3 [SF]: a module "carrying" a row means only that the row's statement reads items placed in it; no context-map pattern is supported; E3-REL's relation status is [O].
- **A low-crossing cut is not evidence of bounded contexts** (E3 Act 1 non-claims; F-LOG-0297 "not bounded contexts") [SF].

| aspect | under K1 (a) | under K1 (b) / (c) |
|---|---|---|
| E3-REL as "rows the modules share" | 13 / 8: the 9 / 4 constraint rows **plus four guard rows** (R14, R15, R16, R21) that T2 calls absences of constraint (R14–R16) or a modelling rule (R21) [SF] | 9 / 4 (SH, K1c), 10 / 5 (R15ref, C-Σ) or 11 / 6 (R15ref-R14). Under SH / K1c it matches the [E]-transcribed "relation held" rows of T3:310, near-definitionally (§2.6) |
| reading E3-REL as an interface of constraints | **premature / partly false**: 4 of 8 rows (S′) impose nothing | closer to a constraint interface, but still unenforceable from records (R23) [SF]; R15ref adds a row T3 lists as "not held" |
| CG1–CG6 (aggregate-like groupings) | guard rows cross groupings (T3 §1b); whether they are "cross-grouping constraints" is C-γ (returned) | under SH / K1c guard rows cross no grouping **only if** "reads" also governs §1b (KC §4); under R15ref(-R14), R15 (and R14) still cross. C-γ stays returned |
| invariants | none created: T-A rows stay candidate model constraints (F-LOG-0275 Act II [SF]); guard rows stay non-constraints by D2 whatever K1 is | same. **K1 does not change D2**; it only changes whether non-constraints are counted |
| module cut M1 / M23 | cut and names unchanged; crossings 17 (S′, T-A + T-B) | cut and names unchanged; crossings 13–15 (S′) |

**Premature while K1 is open** [FD/Rec]:
- any statement of the module interface's size or content (8 vs 4, 5 or 6 under S′);
- treating E3-REL as coupling, or as a set of constraints;
- any context-map pattern (already unsupported, E3 §2.3);
- any aggregate or enforcement boundary drawn from CG1–CG6 (not derivable; VP11; C-γ returned);
- P-c's S′-minimality as a settled fact: it depends on **K8, K9, K13 and R80** as well as on rest and K1 (§3.4);
- reading the low-crossing cut as bounded contexts (excluded by the act's own non-claims).

---

## 7. Consequences per choice (task 7)

[CR from `readiness.out` §2, §3 and §10 (T3 rest with each K1 value); acts from KC §5 and the matrix §5 [SF].] Host cells are T-A + T-B, S / S′, departing from E2b §A1.3 / §A1.4.

| K1 choice | E3-REL (D12 accepted / other) | guard host cells departing (S′ = S) | F-LOG-0297's E3-REL box | KC acts triggered by K1 itself |
|---|---|---|---|---|
| **(a)** (T3, M, K, K-T, U) | **13 / 8** (14 / 9) | none | **stands** | KC-4 (recording only, under T3); none of KC-2, KC-3, KC-5 **from K1** |
| **(b) SH** | 9 / 4 (10 / 5) | 6: R11, R17 → none; R14, R15, R16, R21 → none | **needs a new act** | KC-2 (§A1.3 gains a "none" category), KC-3 (E3-REL; §2.2, §4.2 E3-EV3, totals), KC-4 (rule 1 guard sentence replaced) |
| **(c) K1c** | 9 / 4 (10 / 5) | 5: R14, R16 → none; R17 → none; R15 → CB23; R21 → CB1 | needs a new act | as (b), plus the parse fixed in KC-4 |
| **(c) R15ref** = C-Σ verdicts | 10 / 5 (11 / 6) | 4: R14, R16, R17 → none; R21 → CB1 | needs a new act | as (c) |
| **(c) R15ref-R14** | 11 / 6 (12 / 7) | 3: R16, R17 → none; R21 → CB1 | needs a new act | as (c) |

**Triggered by other choices, not by K1** [CR]:
- KC-2 / KC-3 at R27, R28 under S (host and cut-status cells, so F-LOG-0297's cut-status box): rest K (and so C, C-Σ), and U.
- KC-2 / KC-5 at R79, R80: rest M and U; R80 = VC8 under K or C moves R80 → CB23.
- **KC-5 (asymmetry records): never triggered by K1.** The records move with the rest principle, the R80 box and the spanning rule (§3.2). Under C, the matrix's KC-5 entry comes from C's K-rest (D-st widening under S), not from its guard rows.

**Under every choice** [SF]: no spanning rule selected (F-LOG-0271); D12 open; C-γ returned unless expressly included; C6 open (KC-1 ratifies conventions, not a frozen encoding); E4 and E5 not authorized.

---

## 8. Recommendation (analyst, not a decision) [Rec]

1. **Frame KC-1 as the K1 value at L.** Within the enumerated space, F-LOG-0297's E3-REL box, the T-A host cells and the guard verdicts hinge on K1 alone (§3.4). K18 and the parameters fixed inside the rest principles were not enumerated.
2. **If (a) is chosen as T3**, the act may add a **clarifying clause**: that the negation sentence (T3:68) covers contrast clauses inside positive statements (as its examples R26 and R30, and F11, read it) and does not reach rule 1's guard rows. This is a clarification against KC's A1 generalisation, not a cure for an inconsistency in T3's text (§2.3).
3. **If (b) / (c) is chosen**, the act must name the parse; "C" without a parse is a family with three E3-REL values (§3.5).
4. **On D1 / O2**: if C's content sense of "reads" is accepted, keep D1's wording; a coupling-like E3-REL is then reachable by K1 (b) SH / K1c without O2 (§2.6). If "reads" is meant in its naming sense, K1 (b) is itself a reinterpretation of D1 and should be decided as such. Either way, do not fold O2 into K1 silently.
5. **Rest principle and K1.** For T3 and K, K1 is a separate stipulation. For M, U and C, the principle entails its K1 value; choosing rest and K1 separately for them is option P and conflicts with the provisional "single principle" criterion (§2.2). The asymmetry records (KC-5) move with the rest principle, the R80 box and the spanning rule, not with K1 (§3.2, §7).
6. **No unique recommendation on K1 itself** (outcome (4), §5.5).

Confidence: high for the computed figures within this encoding and space (Λ7); low to medium for every reading of the sources.

---

## 9. Blind determinacy test

**Status.** Run in parallel by the coordinator (not by this analyst); results integrated here. Materials copied into `KC-1-READINESS-scripts/blind/` (packets, `key_sources.md`, the four encodings, `agree.py`, `agree.out`, and the build scripts under `blind/build/`; `key_sources.md` still names the original `../kc1-build/` folder). `agree.py` re-run: byte-identical [CR].

**Design** [SF, `key_sources.md`; coordinator's description].
- Two fresh Claude encoders per interpretation; each read only its packet. Eleven rows: the six guard rows plus controls R1, R6, R9, R79, R80, in shuffled order, with no verdicts, placements or crossing vocabulary (leak check clean).
- **Disclosed packet design choices:**
  - Packet B omits C's definitional-unfolding clause **and C's bound-variable sort rule**; their effect on R11, R15 and R21 was **not assessed**.
  - Packet A carries an operational gloss ("never equated, ordered or linked") that tells the encoder what a denial looks like; packet B has no comparable operational guidance on what counts as "asserted". **The two packets are not equally specific.**
  - Both packets carry a symbol glossary from T3's rule-2 mapping plus K8 / K9 at (a); the shared rules (level of naming, no unfolding, no row references) are the same.
  - Omitted from both: the relational rule (K7), the negation sentence (T3:68), the class rule, the conforms read-through.
  - **Consequence [FD]:** packet A states the guard rule **intensionally**, with no closed list and no negation sentence. It tests a polarity-blind (a) (M / U-like at non-guard rows), **not T3's enumerated (a)**. This shows at the controls (below).

**Agreement** [CR, `agree.out`]:

| comparison | rows | exact | mean Jaccard | item κ |
|---|---|---|---|---|
| A1 ~ A2 | guard | 5 / 6 | 0.967 | 0.980 |
| A1 ~ A2 | control | 5 / 5 | 1.000 | 1.000 |
| B1 ~ B2 | guard | 3 / 6 | 0.583 | 0.446 |
| B1 ~ B2 | control | 5 / 5 | 1.000 | 1.000 |

**No inferential interval is given.** `blind_eval.out` §4 prints bootstrap intervals, **descriptive only**: they resample items or the six rows, not encoders (n = 2 per arm); the items within a row are not independent; and 303 undefined row-cluster draws were dropped for B. They are not evidence of reliability.

**P-c verdicts** [CR, `blind_eval.out` §1]. Each blind guard-row set was substituted into FZ, all other rows FZ:
- **Packet A: no disagreement observed between the 2 encoders at verdict level.** Both reproduce T3's verdicts on all six guard rows (R11 CB23, R14 X, R15 X, R16 X, R17 CB23, R21 X), giving E3-REL 13 / 8. A's only set disagreement (R17's C4, a lexical "norm value" ambiguity between C4 and C5) moves no verdict.
- **Packet B: B1 → 10 / 5; B2 → 9 / 4**, two different points of C's 9 / 4 to 11 / 6 range. At verdict level they disagree at **R15** (X vs none) and **R21** (CB1 vs none) only; **R11 is CB23 for both** (they disagree at R11 only in items). This is an **existence result**: packet B's text admits at least two verdict-level readings.
- Row by row (Jaccard, `blind_eval.out` §3): A's sets are closest to FZ (= T3 = K = U) or M. B1's mean Jaccard is highest against C-Σ (0.905) and C-R15ref (0.821); B2's against C-SH (0.833). B1 reads R15 as {C11, C14, C18} (the exceptive "except d = r under constitution"); B2 reads R15 and R21 as {}. These are the parse choice points MKC found analytically (R11 F1 restatement, R15 exceptive, R21 "is a value of c").

**Controls** [CR, `blind_eval.out` §2]:
- R1, R6, R9: all four encoders equal FZ.
- **R79, R80 under A:** both A encoders read R79 as {C8, C9, C13, C17} and R80 as {C8, C9, C13}, so both **cross** (T3: CB23 and none). This follows from packet A's intensional rule without the negation sentence: A behaved like M / U at the T-B controls. A packet-design effect, not an indeterminacy (the two A encoders agree).
- **R80 under B:** both read {C8, C9} → CB23, the VC8 "acts exist" reading, not T3's "none".
- The controls agree perfectly within each arm, so the observed disagreement is specific to packet B on guard rows.

**Interpretation (bounded).**
- [CR] With 2 encoders per arm, packet A produced no verdict disagreement; packet B produced verdict disagreement at R15 and R21, at the analytically predicted parse points.
- [E] Packet B was less specific than packet A (no unfolding clause, no sort rule, no operational gloss). Whether a fuller statement of C would remove the disagreement **was not tested**. The result is about packet B's text, not about C as a principle.
- [FD] This is the empirical counterpart of Rq1, which is [E]. It is **not a normative argument for (a)**, and the A arm did not test T3's enumerated (a) (§5.5).
- Λ7: all encoders are Claude-family. This measures within-family behaviour of two packet texts, not correctness.

---

## 10. Limitations

- Λ7 throughout; the blind test is within-family and n = 2 per arm (§9).
- Every "only", "invariant", "hinge" and "robust" claim is **within the enumerated space**; K18, K2–K7, K10–K12, K15–K17 and K19 were not varied.
- K8 and K9 (b) are approximate reconstructions (KC §6); their rank effects are approximate, their zero verdict effect is computed.
- The rank under S rests on RESEARCH-AUDIT-1 Lemma 1's assumption that C1 spans every cut.
- Den(x), the minimal-tie parse, the tie endpoints (`lex.out` §5) and the held lists (`lex.out` §6) are [E]; the parse realisations are not independent.
- The factorisation rest ⊕ K1 holds by construction of the space (§2.2).
- The clustering uses single-parameter flips only.
- r1 fixes are not re-reviewed.
- Standing qualifier (verbatim): "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable".

---

## 11. Changes: r1 (review of r0: PASS WITH LIMITATIONS; every computation reproduced byte for byte)

| finding | severity | change |
|---|---|---|
| **M1** contradiction not in T3's text | material | N (T3:68) placed in its scope (rule 2's "reading C1" bullet) with its examples identified as the R26 and R30 contrast clauses; F11 (AUDIT-Q6-TEST1:74) cited. N_gen labelled [E] (KC A1). Result restated: "the A1 synthesis conflicts with rule 1 at six rows; T3's text read in its own scope and examples has no conflict". §0 first row rewritten and placed first, as a correction of the KC / MKC "internal tension" framing; §2.3, §2.4, §3.5, §5.3 aligned; Rec 2 recast as a clarifying clause |
| **M2** scope of invariance claims | material | "within the enumerated space" added to §0, §3.3, §3.4, §4, Rec 1, §10; the non-enumerated parameters named in the header and §3.1: K18 (E3-REL 6 / 3 per the matrix), K2–K7 and K10–K12 inside the rest principles, K15–K17, K19 |
| **M3** F-LOG-0297 | material | "F-LOG-0297 stands iff K1 = (a)" → "F-LOG-0297's E3-REL box"; added that F-LOG-0297 also accepted the cut-status columns, which change at R27 / R28 under S with rest K and K1 = (a), triggering KC-3 (KC:468) (§0, §3.4, §7) |
| **M4** S′-minimality | material | "K9 (b) is necessary for P-c to lose S′-minimality" dropped; the review's T3 re-run with K8 = (b), K9 = (a) (17 / 1 / 4) cited; K8 added to §3.4 and to the §6 DDD bullet |
| **M5** conditional preference | material | §5.5 restated symmetrically: text-determinate (a) is M / U-like and changes R79 / R80; T3's (a) needs its list plus N's scope (or a priority clause); (b) / (c) needs a parse. Reconciled with MKC's Rq1 grades (T3 ✗, K ✗, M ✓, U ~, C ✗, C-Σ ✗). Preference (2) demoted to an observation; outcome stays (4) |
| **M6** blind test | material | intervals removed from the table and labelled descriptive only (resample items or rows, not encoders; 303 undefined B draws dropped); "determines completely" → "no disagreement observed between 2 encoders"; B's disagreement kept as an existence result, scoped to packet B; the specificity asymmetry stated (B lacks C's unfolding clause and sort rule, effect not assessed; A has an operational gloss); verdict-level B disagreement limited to R15 and R21 (R11 CB23 for both). `blind_eval.py` note updated |
| **M7** O2 | material | "O2 not needed" made conditional on C's content sense of "reads" (R14 names f, d, r yet reads nothing under K1 (b)); the held lists labelled [E], the S′ list inferred (also in `lex.py` comments); the coincidence noted as near-definitional (§2.6, Rec 4, §6) |
| **M8** Rec 5 and §2.2 | minor | factorisation stated as holding by construction; for M, U and C the K1 value is entailed, so separate choice is option P and conflicts with the provisional single-principle criterion; asymmetry records moved by R80 and spanning as well as rest (§2.2, §7, Rec 5) |
| **M9** minor | minor | "six independent parses" vs "eight realisations" replaced by "six realisations (four analyst parses + two blind), not independent; minimal tie built from the endpoints" (§2.3, `lex.py` comment); §0 cites §5 / §5.5; "48-row robust core" → "robust within the enumerated space" |
