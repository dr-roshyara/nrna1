# R7 · KC-1 readiness audit: how the guard rows R11, R14–R17 and R21 are counted

**Analysis only; decides nothing; draft r2.1** (2026-10-17). **r0 reviewed PWL; r1 fixes not re-reviewed; r2 reviewed PWL; r2.1 fixes not re-reviewed** (changes N1–N10, §15). The r0 review re-ran every computation (byte-identical) and verified the partition, the counts, the κ values and the E3-REL points; its findings M1–M9 are mapped in §11.

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

**r2 pointer.** §12–§14 (r2 reviewed PWL; r2.1 fixes not re-reviewed) challenge this summary. **The KC-1 decision package is §14.** Corrections to rows of this table are in §14.B: N_gen with lex specialis(L) differs from T3 only at R23, on [E] steps; T-A host cells also depend on K2; "(c) without a parse" spans up to 13 / 8; R80's "none" rests on T3's row-specific entry, not on its general rules.

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

---

## 12. Evidence challenge (r2, reviewed PWL; r2.1 fixes not re-reviewed)

**r2 scripts** (`R7/KC-1-READINESS-scripts/`, run with `python3 -B … ..`; no `__pycache__` written, checked by `find` after every run):

| file | sha256 prefix | role |
|---|---|---|
| `ch_reverify.py` / `ch_reverify.out` | `9269368beeef0ab7` / `7ad34349ca52c9dd` (r2.1: re-run, unchanged) | re-runs every r1 script, `agree.py`, `blind2_eval.py` and the r2 scripts into scratch; byte comparison; artifact hashes (§12.1) |
| `ch_boundary.py` / `ch_boundary.out` | `da53274ee55c2d19` / `b15dac36a2e5081a` (r2.1: §4 two K18 compositions, §6 class-rule witnesses, §7 cross-row check; §0–§3, §5 output unchanged) | row-locality, footprints, extended per-row product over the non-enumerated conventions, K18 × K1, K19, N_gen at R23 (§12.2, §12.3) |
| `ch_rho.py` / `ch_rho.out` | `14c1115cf82c8d08` / `8f13736851b452dd` (r2.1: §4 label corrected; numbers unchanged) | ρ_name and ρ_assert on all 83 rows; monotonicity; E3-REL per relation; rule search for the guard selector (§13) |
| `blind2_eval.py` / `blind2_eval.out` (coordinator's script, reviewed and extended in r2; r2-d updated in r2.1) | `d283fd5d7c1934dd` / `aa0bd74ef8b5f76d` | evaluation of the normalized blind replication (§12.4) |

### 12.1 Re-verification

**Re-runs** [CR, `ch_reverify.out`; also by hand into a scratch directory]:
- `readiness.py`, `lex.py`, `blind_eval.py` and `blind/results/agree.py` (run with its directory argument `.`): **byte-identical** to the committed `.out` files.
- `repro.py`: differs in **two lines** (shown as two −/+ pairs), both carrying the log hash `a485b8f7bf7ccbe1` → `eb02dc3191e9d186`: one inside the nested quotes diff, one in the hash table. **Cause** [SF, git]: `repro.out` was written before F-LOG-0307 was appended (commit `55a8a1b9d`). This is the same kind of recording drift as r1 §1 found for `quotes.out`. Every artifact hash other than the log is unchanged.
- No computational result of r1 changes.
- **Drift in r2's own outputs (r2.1, N1)** [CR]. `blind2/results/verbatim/` was created (19:22) after r2 wrote `blind2_eval.out` and `ch_reverify.out`. Re-run, `blind2_eval.out` differed in its r2-d lines only (the results listing gained `verbatim`); the r2-d check was then rewritten to report the verbatim replies (§12.4) and both outputs regenerated. `ch_reverify.out` re-runs to the same bytes (it reports byte-identity, not content). Hashes in the table above are the r2.1 values. No guard verdict, E3-REL figure or count changed.

**r1 fixes M1–M9: consistency check** [inspection of the r1 text; grep for the r0 phrasings listed in §11].
- No r0 statement listed in §11 survives ("determines completely", "48-row robust core", "stands iff", "K9 (b) is necessary", "O2 not needed", "six independent parses", "conditional preference" as an outcome): none found outside §11 itself.
- Residuals (minor; recorded here, §0–§11 not edited):
  - **R-1** (M9): §0 row 2 says the denied tie is "verdict-robust across the parses tried" without M9's qualifier that the parses are not independent (§2.3 has it).
  - **R-2** (cosmetic): the first comment line of `repro.py`, `readiness.py`, `lex.py` and `blind_eval.py` still reads "r0".
  - **R-3** (M1; **minor, rests on two [E] steps**; softened in r2.1): §2.3's last [CR] bullet and §0 row 1 say T3's table is the output of T3's text "read either way (N_scope, or N_gen with lex specialis)". Ordinary lex specialis already gives rule 3 priority (rule 3 names "separation"), so the class-rule rows do not separate the readings. **Only R23 does**, and only if an independence claim is counted as a denial and its residue is {C13} (both [E]) (§12.3). The N_scope half stands.
  - **R-4** (M2): §8 Rec 1 says the T-A host cells hinge on K1 alone. Outside the enumerated space they also depend on K2, at R12 (§12.2). The K2 / R12 effect itself is already in KC's inventory (KC:89, "R12, all readings"); it is new only relative to r1's hinge claim.
  - **R-5** (new evidence, not a stale statement): §3.5 and §2.2 describe "C without a parse" as four guard-verdict vectors with three E3-REL values. The blind replication widens this (§12.4).

**Versions and review status, F-LOG-0303 to F-LOG-0307** [SF: file headers and log entries; hashes CR, `ch_reverify.out`]:

| log | artifact | version | review status | sha256 prefix |
|---|---|---|---|---|
| F-LOG-0303 | KC-MKC-REASSESSMENT.md | r1.2 | r0, r1, r1.1 each PWL; **r1.2 fixes not re-reviewed** | `8cccf5a32aa7c668` |
| F-LOG-0304 | AUDIT-Q6-TEST2-CLAUSE-COORDINATE-TABLE.md | r1.1 | r0 FAIL; r1 PWL; **r1.1 wording not re-reviewed** | `212dcdb1b19aa58b` |
| F-LOG-0305 | KC-1-DECISION-MATRIX.md | r1.1 + dated note of 2026-10-16 | r0 PWL; r1 PWL; **r1.1 not re-reviewed** | `46b55196f90da25c` |
| F-LOG-0306 | KC-GUARD-PURPOSE-ANALYSIS.md | r1.1 | r0 FAIL (narrowed claim survives); r1 PWL; **r1.1 not re-reviewed** | `53685c6041ed1dcf` |
| F-LOG-0307 | KC-1-READINESS-AUDIT.md | r1 (as committed) → r2 (this text) | r0 PWL; **r1 not re-reviewed; r2 not reviewed** | r1: `1c93f649d002db70` |
| (context) F-LOG-0301 | KC-CONVENTIONS-PROPOSAL.md | r1.1 | r0, r1 PWL; **r1.1 not re-reviewed** | `2cfa32ccd823605c` |
| (context) F-LOG-0299 | AUDIT-Q6-TEST1-FROZEN-ENCODING.md | r1 | r0 PWL; **r1 not re-reviewed** | `c705d06dc65a8171` |
| (log) | F-GOVERNANCE-LOG.md | through F-LOG-0307 | — | `eb02dc3191e9d186` |

**Consequence.** Every KC-1 input from F-LOG-0301 onward carries **unreviewed final fixes**. None of them is reviewed at its current version.

### 12.2 Enumeration boundary: can the non-enumerated conventions move the guard verdicts, the T-A host cells or the E3-REL box?

**Lemma 0, row-locality** [FD from `blind_enc.side` / `crosses`; CR, `ch_boundary.out` §0]. The verdict v_s(x) is a function of ρ(x) and s alone; perturbing every other row (emptied, or made to cross) leaves it unchanged for all 83 rows and 3 rules. Hence:
- the guard verdicts depend only on ρ at L;
- the T-A host cells depend only on ρ at T-A;
- E3-REL (D12 a) depends only on ρ at T-A ∖ H, and under D12 (b) also on R24.

So a convention can matter only through its **footprint**, the rows whose ρ it changes. **Scope (r2.1, N7):** Lemma 0 is about the verdict function, not about how encodings are built. Where a row's encoding is **built from other rows** (K4b row references read through; K17 / R15ref, R15's "(R3)" gloss), conventions acting on the referenced rows also reach it. `ch_boundary.py` takes deltas relative to FZ, so such cross-row compositions (e.g. K3 × K4b at R71) are **not** composed in general. **Inside L ∪ T-A this is harmless** [CR, `ch_boundary.out` §7]: K4b's footprint misses L ∪ T-A, and the only cross-row construction there is R15 → R3, whose side set is {CB1, CB23} in all 928 enumerated encodings, so R15 under R15ref crosses whatever convention acts on R3. Outside L ∪ T-A (R71, R73, R75–R77) the claim is not made.

**Footprints** [CR, `ch_boundary.out` §1] (the KC inventory alternatives (`kc.ALT`)):

| convention | footprint | ∩ L | ∩ T-A |
|---|---|---|---|
| K2 class rule off | R12, R24, R51, R66–R69, R82 | — | R12, R24 (both in H) |
| K3, K3b (K11), K4, K4b, K5c, K6, K6b (K12), K10 | T-B, T-C and T-D rows only (R27, R28, R61, R62, R68, R70, R38, R71, R73, R75–R77, R26, R30, R79, R56, R60, R63, R33, R34) | — | — |
| K7 relational clause off | R15, R45, R52 | **R15** | R15 |

**Extended per-row product** [CR, `ch_boundary.out` §3]. For every row in L ∪ T-A, every distinct base row in the 928 enumerated encodings, every signed combination of the non-enumerated deltas touching that row (off / applied / inverted), and K15 and K16 on top. Two guards stop vacuous re-additions, both found in a first run and recorded in the script:
- an emptied row (K1 (b) / (c), or Ccls = nothing) gains nothing;
- the relational clause adds acts only where Ref or con is read (T3 rule 2).

Results:
- **Guard verdicts, for each fixed K1 value: unchanged** (36 row × K1 cells, all rules). K7 at R15 is verdict-neutral, because R15 crosses through r, d, f without the relational acts.
- **Non-guard T-A rows:** only **R12** widens: X → CB23 under K2 (b), in every rule. R12 ∈ H, so E3-REL is unaffected but **R12's T-A host cell moves**. R24's verdict set is unchanged ({X, none}, the "none" from Ccls).
- **E3-REL, D12 (a) and (b):** no T-A ∖ H row and no R24 change X-status in the extended space.

**K15, K16** [FD; CR side check, `ch_boundary.out` §2]. Every attribute → bearer pair is co-placed (r, c, k, Ref, con → C13 on CB1; d, f → acts and t(w) → work events on CB23), and O's expansion {C13, C14, C15} lies on CB1. Adding an item on a side the row already reads, or a co-placed bearer of a read attribute, never changes |sides|. Hence **K15 and K16 move no P-c verdict** under any K1 value or spanning rule. (They can move ranks over other cuts; not assessed.)

**K17, glosses** [FD]:
- R15's "(R3)" read under C is exactly the R15ref parse, an **enumerated K1 value**. Under (a), adding R3's items to a row that already crosses keeps it X (monotone).
- One unenumerated combination exists: SH plus the R15 gloss read. It gives a sixth guard vector (R15 X, all others none) with E3-REL 10 / 5, a value already attained.
- R25's gloss (T-B) and R77's list (T-D) lie off L ∪ T-A.

**K19, actor-location typing** [FD + CR, `ch_boundary.out` §5]:
- No guard row carries an actor flag.
- Under S every flag spans both sides, and under S′ no flag counts. So K19 can act **only under S″**.
- Under S″, re-typing a flag would make the row cross: for R5, R6, R10 or R19, N → R or B; for R8, R → N or B. The S″ verdicts of R12, R13, R22, R23 and R24 do not depend on the flag.
- T3 rule 2a fixes these rows' locations explicitly (α(a), α(w) → CB23; k(ρ) → CB1). KC's K19 examples are R70, R23 and R72, none of which is in T-A ∖ H. **So K19 does not touch the E3-REL box (S, S′). It can break "S′ = S″" (§3.3 item 3) only if rule 2a's location list is itself re-typed** [FD].

**K18, source of ρ** [CR, `ch_boundary.out` §4]:

| composition | guard verdicts (R11, R14, R15, R16, R17, R21) | E3-REL S / S′ | E3-REL ∖ L (S′) | T-A host cells departing (S′) |
|---|---|---|---|---|
| statement + K1 (a) = T3 | CB23, X, X, X, CB23, X | 13 / 8 | R2, R3, R9, R22 | 0 |
| **K18 (column) + K1 (a)** | CB23, X, X, **CB1**, CB23, **CB1** | **6 / 3** | **R3 only** | 6 (R2, R9, R16, R21, R22, R24) |
| K18 + K1 (b) SH | all none | 4 / 1 | R3 | 10 |
| K18 + K1 (c), composition I | = the column at L, **identical to K18 + (a)**: 6 / 3 | 6 / 3 | R3 | 6 |
| composition II (K1 value overrides the column at L; column elsewhere): (a) / SH / K1c / R15ref = Σ / R15ref-R14 | (a): T3's vector; others as their K1 value | **8 / 5**; 4 / 1; 4 / 1; 5 / 2; 6 / 3 | R3 | — |

Notes:
- **Composition rule (r2.1, N6).** The rows above use **composition I**: under K18 the column **replaces the input** at L, and the K1 value is then applied to that input. (a) reads its input whole, (b) empties it, and a (c) parse of statement text has nothing to parse, so (c) collapses to the column and gives the same 6 / 3 as (a). r2's "(c) undefined" is withdrawn. Composition II (K1's own guard sets override the column at L) is shown for completeness [CR, `ch_boundary.out` §4].
- The 6 / 3 figure reproduces `restson.out`. The seven rows without the column are kept at FZ here (restson drops them); the effect on E3-REL is nil, since none of them is in T-A ∖ H.
- **K18 is not orthogonal to K1.** It replaces the input on which K1 operates, at L **and everywhere else**: it moves E3-REL ∖ L (R2, R9, R22 drop under S′) under either composition. Under composition I it **subsumes** K1 (a)'s and (c)'s input at the guard rows, giving a guard vector that is none of the six K1 values; (b) still overrides it. Under composition II it leaves the guard verdicts to K1 but still moves E3-REL ∖ L.

**Precise scope of the "K1-only hinge"** [FD + CR]:
- **Guard verdicts and F-LOG-0297's E3-REL box** (13 / 8 under S / S′; 14 / 9 under D12 b) are functions of K1 alone over the enumerated space extended by K2–K7, K10–K13, K15–K17 and K19 (S, S′). **The proviso is K18 = the statement cell**, i.e. T3 rule 1's first sentence (T3:66, "its statement (T2 r1.4 "statement (working label)" column …)"), which every option (T3, M, K, C, C-Σ, U) shares.
- **T-A host cells** hinge on **K1 and K2** (R12 under K2 (b)), given K18 = statement.
- Under S″ only, K19 could add a dependence, but only by re-typing rule 2a's explicit locations.
- The spanning rule and D12 remain separate parameters, as in §3.4.

### 12.3 T3 scope re-examination

**(i) What T3 explicitly states** [SF, verbatim]:
- **T3:66** (rule 1): "1. A T2 row **crosses** the P-c cut iff its statement (T2 r1.4 "statement (working label)" column, including the clauses it names) reads **at least one item placed on CB1 and at least one item placed on CB23**. **[r1.6 review fix K3]** A **T-B row** is judged by its clause text only (see rule 3). A **guard row** (T-A guard form: R11, R14–R17, R21) is judged by the items whose tie or order it denies. For example, R14 denies an equation between f, d (CB23) and r (CB1), so it crosses."
- **T3:67** (rule 2, head): "2. **Items are placed by the concept table above, at the level at which the statement names them.** A named concept counts at its own placement. … Ref and con relate records to acts or work events, so a statement reading them also reads the acts or work events it relates them to." (the coordinate list is elided; full line in the file)
- **T3:68** (rule 2, sub-bullet): "- **[r1.7] What counts as "reading C1".** A statement reads C1 where its text names an actor: 𝒜, α(a), α(b), α(w) / α_W, k(ρ) ∈ 𝒜, actor identity, a keeper, norm-maker or executor, or a role or office. Per rule 1, the text of a Def 3.1 clause that the statement names counts as read (so a row naming D-chg reads D-chg's "(α(a), iss) ∈ Cap"). A derived predicate named as a concept (Adopted, C10) and Cap named alone (C2) count at their own placement and are not read through their definitions. Negations ("with no Cap condition", "no Cap or status condition") are not read, as for R79 and R68. The borderlines this convention decides are disclosed in the VP12 re-check section."
- **R26's example**:
  - T2:85: "**D-chg without capability** (R-e): change points are made by issue acts entering into force with the new value, with no Cap condition"
  - T3:107: "| R26 | T-B | — | Chg, issue acts, f, value | internal CB23 |"
  - T3:241: "| R26 | T-B | none ("with no Cap condition", a negation) | internal | internal | internal | internal |"
- **R30's example**:
  - T2:89: "**B-chg.** For every (t, τ) ∈ Chg(h): an issue act enters into force there with the new value (no Cap or status condition), **or** some work event has t(w) < t (any target)"
  - T3:111: "| R30 | T-B | — | Chg, issue act, f, t(w) | internal CB23 |"
  - T3:243: "| R30 | T-B | none ("no Cap or status condition", a negation) | internal | internal | internal | internal |"
- **AUDIT-Q6-TEST1 F11** (line 74, convention column): "negations: a contrast clause ("with no Cap condition", "not an absence in capture") is not read; a guard row reads the items whose tie or order it denies; **a T-B row that states no clause (R80) reads nothing**". Its reason column also records: "**Disclosed asymmetry:** F11 treats denials unequally."

**Three propositions, kept apart:**
- **P(i) T3 states** [SF]:
  - G: guard rows (closed list L) are judged by the denied tie's items.
  - N: "Negations" are not read. N is given **only** inside the bullet on reading C1, with two examples that are **contrast clauses inside positive T-B statements** (R26, R30), and two cited rows (R79, R68) of the same kind.
  - T3 states **no general rule about denials**. T3 never says "denials are not read".
- **P(ii) KC's A1 generalisation** [SF for the attribution, KC §2.3; content E]: N_gen = "negations, denials and absent clauses are not read", as a universal over all rows. With G, it has no model at L (§2.3).
- **P(iii) What applying T3 to the guard rows requires** [FD]. Nothing beyond P(i): rule 1 enumerates L and states the rule for it. N's examples are not guard rows, and no other T3 sentence reaches L. The reader must only **not** generalise N beyond its examples. That is a reading of scope, not an added rule.

**Test: does the clarifying clause change T3's meaning?** [FD by construction]
- *T3 as written, scope-limited reading*: G on L; N on contrast clauses (R26, R30, R79, R68); rule 1's T-B sentence and rule 5 on R80. This is FZ (AUDIT-Q6-TEST1 §2, F11), reproducing the ratified lists row for row (`kc.out`, r1 §3.2).
- *T3 plus the clause* ("N covers contrast clauses inside positive statements"): the same assignment at every row. **Rows whose verdict differs: none of 83, under S, S′ and S″.** This is **by construction** (both sides are FZ), not an independent computation.
- **Verdict [FD]: the clause is explanatory, not amending**, for the scope-limited reading. It removes one misreading (N_gen) and changes no output.
- **Blind support** [CR, n = 3, Λ7; §12.4]: the three A′ encoders were given rules 1–5 in general form, **including N**. All applied G at the guard rows and N to the R79 / R80 negations ("rule 1 denial"; "negation, not read"), and none reported a conflict. This is weak evidence that N_scope is how T3's text reads, not proof. **It cannot separate N_scope from lex specialis**: A′ rule 1 states the denial rule next to N's examples, so either reading yields the same encodings (r2.1, N5).
- **The contradiction claim is not reinstated.** No new evidence supports it.

**Finding: N_gen with lex specialis restricted to L differs from T3's table at one row, on two [E] steps (correction R-3; softened in r2.1)** [FD; CR on an [E] residue, `ch_boundary.out` §6]. Denial-form statements are not confined to L:
- **R23** asserts an **independence**, a denied dependence: "O, and every function of O, is independent of σ, f, Ref, con, d, Cap, k, 𝒜, E, W …". T3 reads it (FZ: X, hosted). Under N_gen its residue is "O … unchanged by fresh additions" → {C13} [E] → **CB1, not X, in every rule**. R23 is in H, so E3-REL is unaffected, but **R23's T-A host cell moves**.
- **Rule 3** (class rule) reads the 𝐑₀ clauses of a statement that "asserts or uses membership … **or separation between them**". The class-rule rows that **deny** a separation are **R66, R69 and R82** (T2:141, T2:144, T2:146; R82 at T3:248). R68 is not one of them: T2:143 is a positive sufficient condition, and T3:207 already leaves its contrast clause ("not an absence in capture") unread. Witness [CR, `ch_boundary.out` §6]: **R69 crosses only via rule 3** (T3:197; with the class rule off it is CB23 under S, S′, S″); R66 and R82 cross without it.
- **But ordinary lex specialis already gives rule 3 priority** over N_gen, because rule 3 names "separation" explicitly, as rule 1 names L. So the class-rule rows **do not separate** N_scope from N_gen + lex specialis.
- **Consequence:** only **R23** separates the two readings, and only through two [E] steps: counting an independence claim as a denial, and taking its residue to be {C13}. If either step is rejected, N_gen + lex specialis reproduces T3's table on the 83 rows. r1's "read either way" is therefore **qualified at R23 only**, not refuted.
- **Wording consequence** [Rec]: a clarifying clause should be stated **positively** ("N applies only to contrast clauses inside positive statements"), not as an exception for L ("does not reach rule 1's guard rows"). An L-exception leaves R23 exposed to the N_gen misreading (on the [E] steps above).

### 12.4 Normalized blind replication (blind2)

**Status** [SF]. Designed, pre-registered and run by the coordinator; evaluated with the coordinator's `blind2_eval.py`, which this analyst reviewed and extended. Materials in `KC-1-READINESS-scripts/blind2/` (not edited here).

**Integrity** [CR, `blind2_eval.out` r2-d]:
- `prereg.md` hashes to `2055aa68…` with its own "- prereg.md:" line removed, as its §9 specifies. The full file hashes to `7e48e553…`, so a plain `sha256sum` does not match; the stated procedure does.
- packet A′ hashes to `7a62f676…` and packet B′ to `08225e82…`, both as recorded.
- File mtimes order packets (19:02) < prereg (19:03) < results (19:04–19:05). **The pre-registration's only anchor is these file times plus its self-hash**; mtimes are weak evidence, and the commit that would fix the order in git is to follow review (r2.1, N1).
- **Deviations from the pre-registration** (disclosed; none affects the primary unit):
  - prereg §4 names `<id>.txt`, the full reply with a reason for every row. Only `<id>.reasons.txt` exists, with 7–8 of 11 rows (mostly the HARD ones), so "saved unedited" cannot be verified from the files.
    - **Resolved after the fact (orchestrating analyst, 2026-10-17).** Each encoder's final reply was extracted verbatim, by script, from the agent transcripts. The replies are in `blind2/results/verbatim/<id>.reply.txt` and, for blind1, `blind/results/verbatim/<id>.reply.txt`. Every saved `<id>.json` equals the JSON in its verbatim reply, for all 10 encoders [CR, `blind2_eval.out` r2-d: **verbatim replies present 10 / 10; JSON equal 10 / 10**; each blind2 reply carries a reason line for **11 of 11 rows**, blind1 replies for 3–5 rows, as blind1's packets made reasons optional]. **Provenance (r2.1, N10):** extracted by the orchestrator with `blind2/build/extract_verbatim.py` from session-local transcripts that are **not archived**; the extraction is reproducible only from those transcripts. The `.reasons.txt` files are the analyst's abridged transcriptions, and the verbatim replies take precedence over them. The filename differs from the prereg's `<id>.txt`.
  - No re-request is recorded; none appears to have been needed.

**Review of `blind2_eval.py`** [CR]. The primary-unit code (conv, side, substitution into FZ, H1 / H2, secondary counts) is correct and re-runs byte-identically. **Defect fixed:** the last line was a no-op ("… if False else '(see mkc.out)'") and printed no comparison. It was replaced by four documented sections:
- r2-a: the KC / MKC C-family guard vectors, computed;
- r2-b: each encoder's distance to them;
- r2-c: the controls against FZ, which were not reported before;
- r2-d: the integrity checks.

The output above the r2 line is unchanged (diff checked).

**Results** [CR, `blind2_eval.out`] (guard-row verdicts S′; S = S′ for every encoder, since no guard row names an actor):

| | R11 | R14 | R15 | R16 | R17 | R21 | E3-REL S / S′ |
|---|---|---|---|---|---|---|---|
| T3 (FZ) | CB23 | X | X | X | CB23 | X | 13 / 8 |
| A′1–A′3 | CB23 | X | X | X | CB23 | X | 13 / 8 each |
| B′1, B′3 | CB23 | X | X | X | CB23 | X | 13 / 8 each |
| B′2 | CB23 | X | X | X | CB23 | **CB1** | **12 / 7** |
| C-K1c | CB23 | none | CB23 | none | none | CB1 | 9 / 4 |
| C-R15ref = C-Σ (verdicts) | CB23 | none | X | none | none | CB1 | 10 / 5 |
| C-R15ref-R14 | CB23 | X | X | none | none | CB1 | 11 / 6 |
| C-SH | none ×6 | | | | | | 9 / 4 |

- **H2 (A′ agree on all six): true.** All three reproduce T3's six verdicts.
- **H1 (B′ disagree on ≥ 1): true, at R21 only.**
- **Cross-arm:** B′ equals A′ / T3 at R11 and R14–R17 for all three encoders, and at R21 for two of three. **No B′ encoder reproduces any KC / MKC C-family vector.** The nearest is C-R15ref-R14, which still differs at R16 and R17 (and R21 for B′1 and B′3).
- **Secondary (exact item sets, pairwise):**
  - A′: 12 / 18 on guard rows. The differences are R17 C4 / C5 (lexical), R21 C4 ("Σ and V" named or not) and **R15 C13** (A′1 reads "under constitution" as a record), none verdict-relevant. Controls 15 / 15.
  - B′: 13 / 18 on guard rows (R16 C23 kept or not; R21 three different sets), controls 15 / 15.
- **Controls against T3** (r2-c, not reported by the original script): R1, R6, R9 and R79 equal FZ in all six encoders. **R80 departs in all six: CB23, not T3's "none".** Every encoder read "acts exist" as asserted (the VC8 reading) and the negated record clause as not read. In blind1, R80 also departed (A: crosses; B: CB23).
  - R79 shows N at work. Blind1 A, whose packet **omitted** the negation sentence, read R79's "not that a record references it" and crossed. Blind2 A′, whose packet **includes** it, did not (CB23 = FZ) [CR].

**Classification of the disagreements** (prereg §6; from the reasons files) [E]:
- **B′2 at R21: inherent, in the prereg's sense.** All three cite B′ rule 3, which carries two sentences that pull apart at a denial row: "A part that asserts no formula about histories contributes nothing: a report that the frame lacks an axiom …" and "A row that denies a tie or an order between items reads only the object-level parts of its statement".
  - B′2 applied the first ("missing axiom and denied tie add nothing … object-level part "self-assertion is a value of c" → C15").
  - B′1 and B′3 applied the second ("rule 3 denial of tie → object-level parts c, σ, f"). B′3 flags the conflict explicitly ("conflicts with "frame lacks axiom contributes nothing"").
  - B′2 is not even internally uniform: at R14 it applies the second sentence ("rule 3 denies a tie → object-level parts f, d, r"), and at R17 it records the doubt ("unclear whether denial-only row should be []").
  - The same split is an **instruction gap** relative to KC: KC §2.4 item 4's parse, which was deliberately withheld, is exactly the missing priority. The two prereg categories are not exclusive here; it is reported as inherent, with the gap named.
- **A′ item differences:** lexical (R17 "norm value" C4 / C5; R21 whether "Σ and V" adds C4) or scope (R15 C13). No verdict effect.
- **B′ at R16** (all three X): the crossing comes partly from B′'s **read-through rule** (rule 4: "absence" C23 read through its definition gives records, Ref, acts), not only from the denied tie. KC's parse puts R16 at ∅, which also needs a stipulation that the "absence … not non-occurrence" clause asserts nothing.

**Interpretation (bounded)** [CR for the counts; FD / E as marked]:
1. **[CR + FD] The C family's distinctive guard verdicts were not produced from C's principle text applied blind.**
   - B′ carried KC:158 in full, with KC §2.2's column-C wording of K1 ("object-level parts only", paraphrased as above), but not KC §2.4 item 4's parse.
   - With n = 3, it gave T3's vector twice and T3's vector with R21 → CB1 once (12 / 7).
   - Blind1's B packet was a **(b)-like stipulation** ("Hence a denial, a non-entailment, or a statement that the frame requires nothing … contributes no items"; `blind/packet_B.md`:23), with no "object-level parts" wording. It produced the C-R15ref / C-Σ vector exactly (B1, 10 / 5) and a vector outside the enumerated list (B2: R11 CB23, all others none, 9 / 4) [CR, `blind_eval.out` §1].
   - So the outputs 9 / 4 to 11 / 6 (C) and 10 / 5 (C-Σ) **were observed only under explicit stipulations**: a (b)-like "denials contribute no items", or (c) **plus** a named parse (KC §2.4, or C-Σ's premise A-ii). Conditions: n = 3 + 2 encoders, Λ7, and packets that differ in several ways at once (wording of the denial rule, operational guidance, unfolding and sort rules), so no single packet feature is isolated.
2. **[FD] Consequence for §3.5 (correction R-5).** "K1 (c) without a parse" is a wider family than the four vectors r1 lists. Only blind2 B′ carried the "object-level parts" wording (blind1 B was a (b)-like stipulation and is not counted here). B′ adds T3's own vector (B′1, B′3: 13 / 8) and (a) with R21 → CB1 (B′2: 12 / 7). With KC's own (c) parses (K1c 9 / 4, R15ref / Σ 10 / 5, R15ref-R14 11 / 6), **E3-REL under "(c) as worded" ranges at least over 9 / 4 … 13 / 8.** The label "(c)" without a parse does not even fix the group (hosting vs coupling).
3. **[FD] Consequence for the KC-1 framing.** r1 Rec 3 already required naming the parse. The blind result **strengthens** it: the choice is **(a) versus an explicitly stipulated (b) versus (c) + a named parse**, never "C" or "(c)" by label. This changes the form of the options, not their number or their E3-REL values.
4. **[CR] Determinacy evidence, stated symmetrically.**
   - (a) stated in general form (no list) was reproduced at all six guard rows by **5 of 5** blind encoders over two packets (blind1 A 2 / 2; blind2 A′ 3 / 3) [CR]. Both A arms, however, departed from T3 at R80 (and blind1 A at R79).
   - **Counterpart (r2.1, N3):** the only general-form (b)-like test, blind1 B, reproduced the SH vector in **0 of 2** encoders (B1 gave the C-R15ref / Σ vector; B2 kept R11 at CB23) [CR]. So in general form (a) was reproduced and (b) was not; with n = 2 + 3, Λ7 and unequal packets, this is descriptive only.
   - (b) and (c)+parse were not tested as stipulated closed rules. Stated as a closed rule over L, (b) is determinate by construction.
   - (c)+parse is determinate only through a per-row table.
5. **Limits.**
   - n = 3 per arm (n = 2 in blind1); all encoders Claude-family (Λ7); statistics descriptive only.
   - B′ carries a glossary-vs-unfolding tension: Adopted and Chg are glossed as items while rule 4 says read through.
   - A′ omits the enumerated list, so it tests T3's **general-form** (a), not T3's enumerated (a). The R80 departure shows that T3's R80 = "none" rests on **T3's row-specific entry** (T3:122 "| R80 | T-B | — | — (no B-side record clause) | none |"; T3:265 "**none** (R80 states no clause)"; also F11), not on its general rules (r2.1, N9).
   - The test measures how texts are read, not which reading is correct. **It does not show (b) or (c)+parse to be illegitimate; it shows they are stipulations beyond C's principle text.**
   - It does not test a fuller statement of C (for example KC §2.1's full table) with the parse.

---

## 13. Theoretical refinement: two relations per row (proposal only; r2 reviewed PWL; r2.1 fixes not re-reviewed)

### 13.1 Formalisation

For each row x, with txt(x) its statement cell (K18 = statement):
- **ρ_name(x)**: the items that txt(x) names or depends on **as its subject matter**. This includes the items of a tie or order it denies (rule 1's guard sentence). It excludes a contrast clause that only delimits a positive statement (N_scope), and an absent T-B clause (rule 1's T-B sentence, rule 5).
- **ρ_assert(x)**: the items in the signature of what x **asserts about histories**. A report that the frame lacks an axiom, an empty clause and a delimiting contrast contribute nothing (KC:158). At L this is the **asserted residue**, and it needs a parse p.

Defined on these:
- **Crossing (D1, "reads")**: x crosses iff ρ_name(x) meets CB1 and CB23 under s. This keeps T3:66 ("reads"), T3:259 (""Crosses" means that the clause's statement reads items on both CB1 and CB23. It says nothing about the truth of the clause."), T3:296 ("A crossing records which items a clause's text reads") and T3:299 ("A crossing is not a boundary relation") [SF].
- **Coupling (a new, explicitly named measure)**: x couples the sides iff ρ_assert(x) meets CB1 and CB23 under s. It is grounded in T2's tier label "non-constraint" (a non-constraint couples nothing) and VP14 criterion "4 | coupling [I]" (Q39) [SF].
- **E3-REL must name its relation**: E3-REL_name = {x ∈ T-A ∖ H : crosses} or E3-REL_assert = {x ∈ T-A ∖ H : couples}.

**Computation with the existing encodings** [CR, `ch_rho.py`]:
- ρ_name ≈ **N-T3** = FZ (K1 (a)), or **N-M** = M (the strict "names" reading, polarity-blind).
- ρ_assert ≈ **A-T3[p]** (FZ outside L, parse p at L) or **A-C[p]** (C's level, definitions read through), with p ∈ {SH, K1c, R15ref, R15ref-R14, Σ}.

### 13.2 Results over all 83 rows [CR, `ch_rho.out`]

| comparison | rows where verdicts differ (S′) | E3-REL S / S′ (D12 a) |
|---|---|---|
| N-T3 vs A-T3[SH] | exactly L (6) | 13 / 8 vs 9 / 4 |
| N-T3 vs A-T3[K1c] | R14–R17, R21 (5) | 9 / 4 |
| N-T3 vs A-T3[R15ref] or A-T3[Σ] | R14, R16, R17, R21 | 10 / 5 |
| N-T3 vs A-T3[R15ref-R14] | R16, R17, R21 | 11 / 6 |
| N-T3 vs A-C[p] | as A-T3[p], plus: S′ R62, R71, R75; S R27, R28, R56, R60, R61, R62, R75 (**R71 differs under S′ only**) | same as A-T3[p] |
| N-M vs any A | adds R79, R80, R45, R52 | — |

- **Monotonicity** (ρ_assert ⊆ ρ_name, so that every coupling row also crosses):
  - holds for A-T3[SH] and A-T3[K1c];
  - **fails** for A-T3[R15ref], A-T3[R15ref-R14] and A-T3[Σ] at R15 (+C13: the cited R3's records, which R15 does not name);
  - fails widely for A-C[p] (read-through at R27, R28, R62, R68, R70, R71, R73, R75–R77).
- **E3-REL depends only on p, not on the level** (A-T3[p] = A-C[p] on E3-REL), because the level differences lie outside T-A.
- **The split** (hosts on N-T3, E3-REL on A-T3[p]) keeps all ratified host cells (0 departures), with E3-REL 9 / 4, 10 / 5 or 11 / 6. The guard rows then **cross but do not couple**: R14, R15, R16, R21 under SH / K1c, fewer under the R15ref parses.

### 13.3 Assessment against the questions set

| question | answer |
|---|---|
| Preserves source semantics? | **For crossing, yes**: ρ_name with N-T3 is T3's text (T3:66, 259, 296, 299). **For coupling, partly**: T2's "non-constraint" and VP14's coupling criterion are honoured, but **E3:111 defines E3-REL as "the set of T-A rows that E2b §A1.3 states … as "hosted on both CB1 and CB23", per reading, and nothing else"** [SF]. E3-REL_assert breaks that definition, so the split amends an accepted output (F-LOG-0297's E3-REL box) **by redefinition**, not by recount [FD] |
| Deterministic? | ρ_name: yes on the 83 rows, through L and N_scope (FZ). ρ_assert: **only with a named parse p**; without p it is the family of §12.4 [CR] |
| Avoids special cases? | **No.** ρ_name keeps rule 1's guard sentence, N_scope and the T-B absent-clause rule (R80). Polarity-uniform naming is N-M, which moves R79 / R80 (and R45 / R52 through K7). ρ_assert keeps a per-row parse at L. The only move is a relocation: the guard special case shifts from "how a guard row is read" to "which relation a use reads" [FD] |
| Does ρ_assert still need C's parse? | **Yes** [FD]. The parse **is** ρ_assert at L. It also needs a level choice (A-T3 or A-C), which E3-REL does not feel but the T-B / T-C / T-D rows do |
| Works across all 83 rows? | Yes, computationally, for every (ρ_name, ρ_assert[p]) pair [CR] |
| Explains E3-REL's consequences? | Yes, and more transparently: E3-REL_name = 13 / 8, and E3-REL_assert[p] gives the K1 (b) / (c) figures **exactly**. Every difference lies at L [CR] |
| New assumptions | (1) Host lists and E3-REL may use different relations, which conflicts with E3:111's "and nothing else". (2) A new named measure ("coupling") and its status. (3) The parse p. (4) A level for ρ_assert. (5) Under R15ref / Σ, a coupling relation that reads a row reference ρ_name does not read (non-monotone at R15) |

### 13.4 Model-integrity test and verdict

- **New dimension, overloaded, or another value?** **An existing dimension, applied per use.** "Which relation a use reads" coincides, for E3-REL, column for column with K1's two groups: E3-REL_name = K1 (a), E3-REL_assert[p] = K1 (b) / (c) with parse p [CR]. It is new only in allowing **host lists and E3-REL to take different K1 values**. That is option P across uses, the same structure §2.2 found across conventions.
- **Orthogonal:** yes, computationally (host cells and E3-REL become independent outputs).
- **Necessary:** **only** if the human wants hosting relevance (Rq7h) for the host lists **and** coupling (Rq7c) for E3-REL. It is then the one formulation that passes both Rq7h and Rq7c, which no single option does (matrix note: they conflict at R14, R16, R21). Otherwise it is unnecessary.
- **Sufficient:** **no.** It still needs p, ρ_name's special cases, and an amendment of E3:111.
- **Plain verdict** [FD]: **it does not dissolve the K1 dispute. It relabels it as "which relation E3-REL uses", plus the parse, and the human must still choose.**
  - What it adds is one coherent extra branch (§14.E, branch S): hosts unchanged, E3-REL coupling. It also gives a precise name for what (b) / (c) measure, which removes the "content sense of reads" stretch of §2.6 for that branch: crossing keeps D1 in its naming sense and coupling is a different, named measure.
  - **The simplest existing option** for each purpose remains a single K1 value for all uses. The refinement is preferable only if the human wants the two purposes served separately.
  - No convention is invented here to avoid the decision.

### 13.5 Computational method used: a rule search for the guard selector

| field | content |
|---|---|
| Question | Is there a row-level rule, over simple features of T2's row text, that selects exactly L without a per-row table (so that "guard row" is not a stipulated list)? |
| Inputs | the 83 T2 r1.4 rows; 10 hand-chosen boolean features [E]: title starts with "No"; title or statement contains a negation token; a post-hoc phrase list ("does not require", "imposes no", "never equates", "no axiom", "no frame axiom"); tier T-A / T-B / meta; T2 tier contains "guard" or "non-constraint" (**LABEL** features); T2 N-column contains "guard" |
| Method | exhaustive search over conjunctions of ≤ 3 literals and disjunctions of two conjunctions of ≤ 2 literals; target = exact extension L |
| Result [CR] | 116 exact rules; 92 use a LABEL feature. The 24 label-free rules **all** use the post-hoc phrase list, which was written after reading L, so its fit is partly circular. **Without it, no label-free rule fits**: the best ("title has a negation ∧ statement has a negation ∧ T-A") misses R21. Instructive near misses: "title starts with No" selects R14–R17 **and R80** but not R11 or R21; R80 ("H-B carries no record requirement") and R23 (an independence claim) have the surface form of guard rows |
| Assumptions | features [E]; T2 text as the only input; 83 rows; no labelled data beyond L itself |
| Validation | exact match only; no hold-out is possible (83 rows, one target) |
| Limitations | overfitting by construction of the post-hoc feature; the boundary of an intensional rule (frame axiom vs hypothesis clause at R80; denial vs independence at R23) is a judgement the search cannot make |
| Could it change the recommendation? | **No.** Every option uses L as its domain (r1 §2.4), so the selector question does not separate (a) from (b) / (c). At most it bears on Rq1 [E], for rows outside the 83 |

**Not done, and why.** A learned parse for ρ_assert was not attempted: there is no ground truth (the parse is what is disputed), so any learned model would only reproduce one analyst's parse. A property-based search for a parse rule at the four parse points (R11 restatement, R15 exceptive, R14 "may coincide", R21 "is a value of c") would need a formal statement grammar the corpus does not have. Further experiments would not resolve a material uncertainty: the open items are normative (§14.C).

---

## 14. KC-1 decision package (r2 reviewed PWL; r2.1 fixes not re-reviewed; decides nothing)

### 14.A Established (with scope and reproducibility)

| # | statement | label | scope | reproduce |
|---|---|---|---|---|
| A1 | Within the space enumerated in r1 **extended by the KC inventory alternatives (`kc.ALT`) for K2–K7, K10–K12, plus K13, K15–K17 and K19 (S, S′)** (cross-row compositions outside L ∪ T-A not composed; §12.2 scope), the guard verdicts and F-LOG-0297's E3-REL box (13 / 8; 14 / 9 under D12 b) are functions of K1 alone, **given K18 = statement cell**. T-A host cells depend on K1 and K2 (R12) | CR + FD | 83 rows; FZ-based encodings (Λ7) | `readiness.py`, `ch_boundary.py` |
| A2 | E3-REL per K1 value: (a) 13 / 8; SH and K1c 9 / 4; R15ref and C-Σ 10 / 5; R15ref-R14 11 / 6 (D12 b: + 1 / + 1, except where Ccls = nothing) | CR | as A1 | `readiness.out` §3 |
| A3 | K18 (T2 "rests on" column) is not orthogonal to K1: it changes the input at L and elsewhere. Under composition I (column replaces the input at L) it gives 6 / 3 with K1 (a) **and with (c)**; under composition II (K1 overrides at L) 8 / 5 with (a) | CR + FD | 76 rows with the column | `ch_boundary.out` §4 |
| A4 | T3's text, read with N in its own scope, has no internal contradiction. The clarifying clause changes no verdict on any of the 83 rows (explanatory) | FD + CR | 83 rows | §12.3; `kc.out` |
| A5 | N_gen with lex specialis restricted to L differs from T3's table only at R23 (host cell X → CB1), and only on two [E] steps (independence as denial; residue {C13}). Rule 3 is not a separating case: ordinary lex specialis gives it priority (R69 crosses only via rule 3) | FD; CR on [E] | T-A rows | `ch_boundary.out` §6 |
| A6 | Blind: (a) in general form reproduced T3's six guard verdicts in 5 of 5 encoders (two packets). No blind2 B′ encoder (C's principle text without the parse) produced a C-family vector. A C-family vector (C-R15ref / C-Σ) appeared only under blind1 B's explicit "denials contribute no items" sentence, and that (b)-like packet gave the SH vector in 0 of 2 encoders. B′ disagreed at R21 only (12 / 7). Every A encoder departed from T3 at R80 (blind1 A: X; blind2: all six encoders → CB23) | CR (descriptive) | n = 2 + 3 per arm; Λ7 | `blind_eval.py`, `blind2_eval.py` |
| A7 | The two-relation refinement reproduces both groups exactly (E3-REL_name = (a); E3-REL_assert[p] = (b) / (c) by p) and adds one split branch; it does not remove the parse or the special cases | CR + FD | 83 rows | `ch_rho.out` |

### 14.B What changed

| # | change | source | effect on earlier text |
|---|---|---|---|
| B1 | **T3 correction (r1, M1), kept:** the contradiction is in KC's A1 generalisation, not in T3's text | §2.3, §12.3 | KC A1 / MKC "internal tension" describe the synthesis |
| B2 | **New, R-3 (minor; softened in r2.1):** "read either way" is qualified at R23 only, on two [E] steps; rule 3 is covered by ordinary lex specialis. The clarifying clause should be worded positively | §12.3 | qualifies §0 row 1, §2.3 last bullet, Rec 2 |
| B3 | **New, R-4:** T-A host cells hinge on K1 **and K2** (R12) outside the enumerated space; the E3-REL box hinges on K1 given K18 = statement | §12.2 | qualifies §0, §3.4, Rec 1 |
| B4 | **New, R-5:** "(c) without a parse" spans at least 9 / 4 … 13 / 8 (KC's parses plus blind2 B′) and does not fix the group. The coupling outputs **were observed only under explicit stipulations** (n = 3 + 2, Λ7, packets differing in several ways) | §12.4 | widens §3.5, §2.2 "undefined cases" |
| B5 | **New:** R80's "none" rests on T3's row-specific entry (T3:122, T3:265; also F11), not on its general rules. T3's general rules read blind give the VC8 value (CB23) | §12.4 | bears on the R80 box (E3-REL unaffected) |
| B6 | repro.out log hash drift (F-LOG-0307 appended later); r2 output drift after `verbatim/` was added (r2.1, N1); R-1, R-2 minor | §12.1 | none on results |
| B7 | **r2.1, N6:** K18 + (c) is not undefined: under composition I it equals K18 + (a) (6 / 3) | §12.2 | corrects r2 A3 |

### 14.C Uncertain

- **Mathematical:**
  - ranks outside P-c under K15 / K16 (not assessed);
  - rank effects of K8 / K9 (b) are approximate reconstructions (KC §6);
  - S″ results under a re-typed rule 2a (K19);
  - the rank under S rests on RESEARCH-AUDIT-1 Lemma 1.
- **Source ambiguity:**
  - T2's R21 tier vs counts line (Q54 / Q61);
  - P9 ("prevents it and asserts nothing about it") pointing both ways;
  - E3:111 (E3-REL = host-list subset) against E3:115 [O] ("the rows the two modules share");
  - R80's role (empty / VC8 / denial);
  - the meaning of "object-level parts" (KC §2.2 vs §2.4);
  - VP14's "coupling [I]".
- **Incomplete testing:**
  - Λ7 throughout; blind n = 2–3 per arm, all Claude-family;
  - no blind test of (b) or of (c)+parse as stipulated rules, nor of T3's enumerated (a) (only general form);
  - the r1 fixes of five KC-1 inputs are not re-reviewed (§12.1);
  - this r2 is not reviewed;
  - the residues for N_gen (R23) and every parse are [E].
- **Normative (only the human can settle):**
  - what the counts should count at L: hosting relevance, constraint coupling, or both separately (the split);
  - whether determinacy without a per-row table (Rq1, [E]) should bind;
  - whether "single principle" (provisional) should bind;
  - whether accepted outputs may be redefined (E3:111) rather than recounted.

### 14.D Recommendation

**No recommendation on the K1 value** [Rec, outcome (4) unchanged]:
- The sources conflict on purpose (guard-purpose analysis).
- No [U] requirement separates the options.
- The refinement relabels rather than dissolves the choice (§13.4).
- Determinacy (Rq1) is an [E] stipulation.
- 13 / 8 is not a reason, and neither is its change.

**Two conditional observations, stated evenly; neither is a preference** [FD on CR / SF]:
- *If* **Rq1** binds ("determinate without a per-row table beyond L", [E]), then (c)+parse is disfavoured relative to (a) and (b). Between (a) and (b), determinacy does not discriminate: each is L plus one uniform rule.
- *If* **Rq7c** binds ("a row crosses only if a constraint it carries couples the sides", [E-s], MKC:172), then **(a) fails** (all of T3, M, K, K-T, U fail Rq7c at the guard rows; matrix block A), and **(b), (c)+parse and branch S** remain.
- Each conditional rests on a stipulated requirement; the evidence does not say which, if either, should bind. The blind evidence (A6) concerns how texts are read, not which purpose is right.

**Procedural recommendations** [Rec], justified by §12:
1. **State KC-1's options as (a), (b) SH, or (c) with a named parse** (K1c, R15ref, R15ref-R14, or C-Σ via A-ii). Do not select "C", "K1 (c)" or "coupling" by label (§12.4, B4).
2. **If (a) is chosen as T3**, add the clarifying clause **worded positively**: "the negation sentence (T3:68) applies only to contrast clauses inside positive statements". It is explanatory (§12.3).
3. **Carry the scope conditions in the act text:**
   - K18 = statement cell (T3:66);
   - K2 (class rule) affects R12's host cell;
   - the hinge is computed on Claude-family encodings (Λ7).
4. **Decide R80 as its own box.** The blind evidence (B5) is relevant to it, and E3-REL does not depend on it.
5. **If the human wants both purposes served**, consider branch S (§14.E) **explicitly**, as an amendment of E3:111. Do not reach it through a K1 value.

### 14.E Minimal decision set

**What each convention fixes logically** (✓ fixed by the convention; ○ a separate box within KC-1; ✗ a separate act outside KC-1) [FD, from KC §2.2, the matrix §5 and §12]:

| item | T3 | M | K / K-T | U | C (+parse) | C-Σ | split S (§13) |
|---|---|---|---|---|---|---|---|
| 1. K1 at L | ✓ (a) | ✓ (a), entailed | ✓ (a), by label | ✓ (a), entailed | ✓ (b) / (c), entailed | ✓ (c) | hosts (a); E3-REL (b) / (c) |
| 2. convention within family (rest) | ✓ | ✓ | ✓ | ✓ | ✓ (K rest) | ✓ (K rest) | ○ (rest for ρ_name; level for ρ_assert) |
| 3. K9 | ✓ A.0 | ○ | ○ | ○ | ○ | ○ | ○ (verdict-neutral; rank only) |
| 4. R80 | ✓ empty | ✓ denial | ○ empty / VC8 | ○ denial / record-only | ○ empty / VC8 | ○ empty / VC8 | ○ |
| 5. C's parse | n/a | n/a | n/a | n/a | **○ required** | ✓ via A-ii | **○ required** |
| 6. crossing definition (D1 / O2) | ✓ D1, naming sense | ✓ D1 | ✓ D1 | ✓ D1 | **○: content sense of "reads" [HDR] or O2** | as C | ✓ D1 kept; **new named coupling measure (✗ act)** |
| 7. C-γ | ✗ returned | ✗ | ✗ | ✗ | ✗ (empties under K1c / SH only if "reads" also governs T3 §1b) | ✗ (R15 still crosses) | ✗ (groupings use crossing: unchanged) |

**Branch consequences** [CR from `readiness.out` §2, §3, §10, `ch_rho.out`; acts SF from KC §5 and the matrix §5]. Host cells are T-A + T-B, S / S′.

| branch | E3-REL S / S′ (D12 b) | host-list changes | F-LOG-0297 | required clarification / parse | KC-2 … KC-5 | remaining open |
|---|---|---|---|---|---|---|
| **a-T3** | 13 / 8 (14 / 9) | none | E3-REL box and cut status **stand** | positive N clause (optional, explanatory) | KC-4 recording only | K15–K19 (K18 as scope proviso), D12, spanning rule, C-γ, C6, (g), (h), H13, A1, A5 |
| **a-M** | 13 / 8 | R79, R80 → X (T-A + T-B; T-C rows R45, R52 → CB1 outside the host lists) | E3-REL box stands | K9 | KC-2 (R79, R80), KC-3 (totals 46 / 36), KC-4, KC-5 (R-i / R-i′ records symmetric at row level) | as a-T3 |
| **a-K (K-T)** | 13 / 8 | R27, R28 → X under S (T-A + T-B; T-C / T-D rows R56, R60, R61 (S), R62, R71 (S′), R75 move outside the host lists) | E3-REL box stands; **cut-status cells R27 / R28 under S change** | K9; VC8 sub-box | KC-2 (S row), KC-3 (cut status, totals 53 / 39), KC-4, KC-5 (widened to D-st under S) | as a-T3 |
| **a-U** | 13 / 8 | K's changes plus R79, R80 | as a-K | K9; R80 | as a-K, plus M's KC-5 | as a-T3 |
| **b (SH)** | 9 / 4 (10 / 5) | R11, R17 CB23 → none; R14, R15, R16, R21 X → none (6 cells; a "none" host category) | **new E3-REL acceptance**; shared cut-status cells change | D1 reinterpretation (content sense) or O2 [HDR]; no parse | KC-2, KC-3 (E3 §2.2, §4.2 E3-EV3, totals), KC-4 (guard sentence replaced); KC-5 not from K1 | as a-T3, plus C-γ wording |
| **c-K1c** | 9 / 4 (10 / 5) | 5 cells: R14, R16, R17 → none; R15 → CB23; R21 → CB1 | new acceptance | parse K1c named; D1 as for b | as b, plus parse in KC-4 | as b |
| **c-R15ref / C-Σ** | 10 / 5 (11 / 6) | 4 cells: R14, R16, R17 → none; R21 → CB1 | new acceptance | parse named (C-Σ: A-ii) | as c | as b; C-γ concerns R15 |
| **c-R15ref-R14** | 11 / 6 (12 / 7) | 3 cells: R16, R17 → none; R21 → CB1 | new acceptance | parse named | as c | as b; C-γ concerns R14, R15 |
| **S (split, §13)** | 9 / 4, 10 / 5 or 11 / 6 by p | **none** | E3-REL box **replaced by redefinition** (E3:111 amended); cut status of shared cells follows | parse p; definition of the coupling measure; level | KC-2 not needed at L; **KC-3 with an E3:111 amendment**; KC-4 (coupling defined beside rule 1); KC-5 not from K1 | as b; host lists and E3-REL then diverge: E3-REL_assert ⊊ hosted-on-both, which breaks the **equality** in E3:111 ("and nothing else") |
| **"(c)" without a parse** | **not supported: at least 9 / 4 … 13 / 8** (§12.4) | undetermined | — | — | — | not a viable branch |

Under C (any parse) the rest is K, so a-K's R27 / R28 (S) cut-status and KC-5 widening also apply (r1 §7).

**Separate acts in every branch:** C-γ; D12; the spanning rule (F-LOG-0271); C6 (frozen encoding); K15–K19; (g); (h); H13; A1; A5. **E4 (R8) and E5 are not authorized.**

### 14.F Deferral

- **Coherent?** [FD, SF] **Yes.** Deferral is the state since F-LOG-0302:
  - the ratified host lists stay "a ratified output of an unratified method" (E2b:584);
  - F-LOG-0297's E3-REL box stands under (α), relying on the reopen condition in E2b §A2.7;
  - T3 as written stays the minimal-amendment baseline, not ratified.
  - No artifact contradicts another as a result of deferral. §12.3 removes the one alleged internal contradiction.
- **What stays blocked:**
  - E4 / R8 canonicalization (F-LOG-0300: "E4 follows on a settled basis");
  - KC-2 to KC-5;
  - any statement of E3-REL as an interface, coupling or set of constraints (r1 §6);
  - freezing an encoding (C6);
  - any amendment citing the host lists (audit C9).
- **Evidence that would reopen or sharpen it:**
  - a human ruling on purpose (hosting, coupling, or both via branch S);
  - a non-Claude or human blind replication, which would address Λ7, including a test of (c)+named parse and of T3's enumerated (a);
  - a reviewed r2 and reviews of the five unreviewed KC-1 inputs (§12.1);
  - a decision on K18 (the source of ρ), the only **non-enumerated** parameter found that moves E3-REL ∖ L (among the enumerated ones, the spanning rule moves R5, R6, R8, R10, R19, and D12 (b) adds R24; r2.1, N6);
  - for R80, a ruling on its role.

**Standing qualifier (verbatim):** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable"

**Λ7 (r2).** The r2 analyst, the blind2 designer and coordinator, all six blind2 encoders and every source are Claude-family. The blind2 packets were built by the coordinator from the same sources. Shared interpretive errors would not be detected.

**Kept unresolved:** C-γ (returned); D12; the spanning rule (F-LOG-0271); C6; K15–K19; (g); (h); H13; A1; A5. E4 / R8 and E5 are not authorized. KC-1 is not made; nothing is ratified, selected or authorized.

---

## 15. Changes: r2.1 (review of r2 §12–§14: PASS WITH LIMITATIONS; no recomputed number wrong; r2.1 fixes not re-reviewed)

| finding | severity | change |
|---|---|---|
| **N1** output drift; repro wording; prereg anchor | medium | `blind2/results/verbatim/` (created after r2's outputs) made `blind2_eval.out` drift. `blind2_eval.py` r2-d now reports the verbatim replies (10 / 10 present, JSON equal 10 / 10, blind2 reasons 11 / 11 rows) instead of "reasons for 7–8 of 11 rows"; both outputs re-run (`ch_reverify.out` same bytes), drift recorded in §12.1, hashes updated. Repro wording: "two lines". Prereg anchor stated as file times plus self-hash; the commit follows review (§12.1, §12.4) |
| **N2** R-5 / B4 sourcing | medium | blind1 B2 removed from the "(c) as worded" list (blind1 B was a (b)-like stipulation with no "object-level parts" wording); range 9 / 4 … 13 / 8 now sourced from KC's parses plus blind2 B′; "exist only as" → "were observed only under" explicit stipulations, with n = 3 + 2, Λ7 and multi-way packet differences stated (§12.4 items 1–2, B4) |
| **N3** symmetry | medium | §12.4 item 4: general-form (b)-like test 0 / 2 SH vectors vs general-form (a) 5 / 5 (blind1 A 2 / 2 + blind2 A′ 3 / 3, checked). §14.D: the Rq7c conditional added beside the Rq1 conditional (if Rq7c binds, (a) fails; (b), (c)+parse and S remain) |
| **N4** R-3 / A5 | medium | R68 removed from the separation-denial rows (T2:143 positive; T3:207 applies N); the rows are R66, R69, R82. R69 witness computed (crosses only via rule 3, T3:197; CB23 without it). Ordinary lex specialis already gives rule 3 priority, so only R23 separates the readings, on two [E] steps; "material" softened to minor (§12.1 R-3, §12.3, A5, B2) |
| **N5** label | minor | §12.3 "no row differs" labelled FD by construction (both sides FZ); A′ blind evidence stated as unable to separate N_scope from lex specialis |
| **N6** K18 composition; §14.F | medium | composition rule stated (column replaces the input at L); under it (c) equals (a), 6 / 3, so "undefined" is withdrawn; composition II added (K1 overrides at L: (a) 8 / 5). §14.F: K18 is the only **non-enumerated** mover of E3-REL ∖ L; the spanning rule (R5, R6, R8, R10, R19) and D12 (b) (R24) also move it (§12.2, A3, B7, §14.F) |
| **N7** Lemma 0 scope; A1; R-4 | minor | Lemma 0 scoped: cross-row constructions (K4b; K17 / R15ref) are not composed by the FZ-relative deltas; shown harmless inside L ∪ T-A (`ch_boundary.out` §7). A1 names "the KC inventory alternatives (`kc.ALT`)". R-4: the K2 / R12 effect is in KC:89; new only relative to r1 |
| **N8** wording and labels | minor | branch S: "E3-REL_assert ⊊ hosted-on-both", breaking E3:111's equality. `ch_rho.py` §4 label corrected to "hosted on both but not coupled" and re-run (numbers unchanged). a-M and a-K host-list cells restricted to T-A + T-B (T-C / T-D rows noted as outside). §13.2: R71 differs under S′ only |
| **N9** R80 | minor | B5 and §12.4 limits: R80 = none rests on T3's row-specific entry (T3:122, T3:265; also F11), not on its general rules |
| **N10** provenance | low | verbatim replies: extracted by the orchestrator with `blind2/build/extract_verbatim.py` from session-local transcripts that are not archived (§12.4) |

**Numbers changed in r2.1:** none of r2's computed figures. New figures only: K18 composition II (E3-REL 8 / 5 with (a), 4 / 1 SH and K1c, 5 / 2 R15ref and Σ, 6 / 3 R15ref-R14); K18 + (c) under composition I = 6 / 3 (was "undefined"); R69 without rule 3 = CB23; verbatim replies 10 / 10.

**Standing qualifier (verbatim):** "one corpus; finite universes; within-family evidence; cross-family waived; H-D vs H-B non-identifiable". Λ7 applies to the review and to these fixes. Nothing is ratified, selected or authorized; E4 / R8 and E5 are not authorized.
