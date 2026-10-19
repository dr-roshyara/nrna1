# PROPOSED / NOT RATIFIED; analysis only

# R7 · KC-1 independent reference implementation (refimpl r0, 2026-10-17)

**What this is.** A second calculation of the KC-1 guard-row verdicts and E3-REL figures. I wrote it from the definitions in the source documents, without importing, copying or reading `kc.py`, `blind_enc.py`, `mkc.py`, `readiness.py` or `ch_*.py`. §0–§3 of `ref.py` were finished and run before any existing output was opened. §4 was added afterwards. It reads only the existing **output** files (`kc.out`, `readiness.out`), not their code. Run it with `python3 -B ref.py > ref.out`.

**Decides nothing.** KC-1, C-γ, D12, the spanning rule (S / S′ / S″), E4 and E5 all stay open. I changed no other file and committed nothing.

## 1. Definitions used (sources)

| item | source | as used |
|---|---|---|
| Placement under P-c | T3 r1.9 §1a, "Placement of C1–C28" | CB1 = {C13–C18, C23, C24, C27}; CB23 = {C2–C12, C19–C21}. These are on no side: C1 (spanning), C22 (unplaced), and C25, C26, C28 (CB4 slot) |
| C1 under the spanning readings | T3 rule 2a | **S:** C1 counts on both sides. **S′:** it counts on neither side. **S″:** it counts where the statement locates the actor: act, work-event or Cap actor → CB23; keeper → CB1; unlocated → no side |
| Cross | T3 rule 1; E3 §2.3 | A row crosses (X) iff it reads ≥ 1 item on CB1 and ≥ 1 item on CB23 |
| E3-REL | E3 §2.3 | The T-A rows with verdict X, minus the hosted set H. H = MW1 {R12, R13, R23}, plus CV4 {R24} under D12 (a). Under D12 (b), R24 is shared and eligible |
| Class rule (K2) | T3 rule 3 | R12 reads D-rec (C13, C17) only through this rule |
| Guard rows L | T3 rule 1 | R11, R14, R15, R16, R17, R21 |

**Guard-row item sets per K1 value** (T1 C-ids):

| K1 | R11 | R14 | R15 | R16 | R17 | R21 | source |
|---|---|---|---|---|---|---|---|
| (a) | 6, 8 | 11, 12, 14 | 11, 12, 14, 20 | 8, 13, 17, 19 | 6, 8, 19 | 6, 12, 15 | T3 crossing table |
| SH (b) | ∅ | ∅ | ∅ | ∅ | ∅ | ∅ | KC §1 K1 (b) |
| K1c | 3, 4, 6 | ∅ | 20 | ∅ | ∅ | 15 | KC §2.4 item 4 |
| R15ref | as K1c | ∅ | 20 + R3's set {11, 14, 18} | ∅ | ∅ | 15 | KC §2.4 item 4, alternative parse |
| R15ref-R14 | as K1c | 11, 12, 14 | as R15ref | ∅ | ∅ | 15 | same |
| C-Σ | 3, 4, 5, 6, 8, 12 | ∅ | as R15ref | ∅ | ∅ | 15 | KC-MKC §6.2, the part table plus the R11 fix (F3d) |

**Transcription choices for the other T-A rows.** I transcribed these from the T3 crossing table, using the rule 2 mapping, and disclose each choice:
- **Sources outside T3.** v(a) → C5 and τ(a) → C3 follow A.0, because rule 2 is silent on them (K9). "Acts", E, the issue act and the adoption act map to C8, and obj maps to C9. K8 and K9 cannot move a P-c verdict, because these concepts all lie on CB23.
- **R16 and R17 under (a).** R16's "records referencing" maps to C13 + C17. "Work events" maps to C19. R17's "norm value" maps to C6.
- **R13.** It is encoded with D-chg's actor located on CB23. It crosses under every reading anyway.
- **Unlocated actors.** R22 and R23 count their actor as unlocated under S″. Both cross regardless.
- **R12.** Its D-rec items {C13, C17} are kept separate, so that K2 can be switched off.
- **K18.** I took the T2 "rests on" C-ids verbatim for R1–R24. When C1 appears there, it is treated as unlocated. Composition I follows KC-1-READINESS §12.2: the column replaces the input at L; (a) and the (c) parses read it whole; SH empties it. Composition II: the K1 sets override the column at L.

## 2. Results (`ref.out`)

- **Self-check.** The T-A categories under S are 17 X / 6 CB23 / 1 CB1. Under S′ and S″ they are 12 / 10 / 2. Both equal the T3 r1.7 counts table.
- **Guard verdicts** (identical under S, S′ and S″, because no guard row reads C1):

| K1 | R11 | R14 | R15 | R16 | R17 | R21 |
|---|---|---|---|---|---|---|
| (a) | CB23 | X | X | X | CB23 | X |
| SH | none | none | none | none | none | none |
| K1c | CB23 | none | CB23 | none | none | CB1 |
| R15ref | CB23 | none | X | none | none | CB1 |
| R15ref-R14 | CB23 | X | X | none | none | CB1 |
| C-Σ | CB23 | none | X | none | none | CB1 |

- **E3-REL** = 9 / 4 non-guard rows, plus the crossing guard rows:
  - (a) 13 / 8
  - SH 9 / 4
  - K1c 9 / 4
  - R15ref 10 / 5
  - C-Σ 10 / 5
  - R15ref-R14 11 / 6

  Each figure is +1 / +1 under D12 (b), because R24 is added. **All six published figures are reproduced.**
- **Invariant.** Outside L, E3-REL takes one value across all six K1 values:
  - S: R2, R3, R5, R6, R8, R9, R10, R19, R22 (9), plus R24 under D12 (b);
  - S′: R2, R3, R9, R22 (4), plus R24 under D12 (b).
- **Rows that move between S and S′.** R5, R6, R10 and R19 go from X to CB23. R8 goes from X to CB1. S′ = S″ on every T-A row.
- **K2 off.** R12 goes from X to CB23, and this is the only T-A move. E3-REL does not change, because R12 ∈ H. One host cell changes.
- **K18.**
  - Composition I gives **6 / 3** with (a) and with K1c (S: R3, R5, R6, R14, R15, R22; S′: R3, R14, R15), and 4 / 1 with SH.
  - Composition II gives (a) 8 / 5; SH 4 / 1; K1c 4 / 1; R15ref 5 / 2; C-Σ 5 / 2; R15ref-R14 6 / 3.
  - D12 (b) changes nothing here, because R24's column {C27, C18} lies on CB1 only.

## 3. Comparison with the existing outputs (§4; done after the calculation)

- **`kc.out`.** I compared the five named sets (T3 / FZ, C, C-SH, C-R15ref and C-R15ref-R14) at all 24 T-A rows under S and S′. There are **0 disagreements**.
- **`readiness.out` §3.** I compared the E3-REL lists for all six K1 values under S and S′ (D12 a). There are **0 disagreements**.
- **C-Σ.** `kc.out` does not list C-Σ, so its guard verdicts were checked against KC-MKC §5.1 instead. They agree.

**Differences that are not disagreements:**
- **Scope of the C sets.** `kc.out`'s C-family sets also differ from T3 outside T-A: R27 and R28 under S, and R56, R60, R61, R62, R71 and R75. I composed K1 on T3's base, so these rows are outside my scope. They do not affect E3-REL, which is T-A only.
- **R24 under D12 (b).** `readiness.out` §2 gives D12 (b) = +0 for the C-family encodings that set Ccls = "nothing", the class-level clause that empties R24. I do not model Ccls: R24 keeps its T3 reading. This is a definition choice, not a bug. It leaves the published D12 (a) figures unchanged.

## 4. Traceability

| figure | rule / parse | row set (S; S′ = S″) | denominator |
|---|---|---|---|
| 13 / 8 | K1 (a), T3 rule 1 | 9 / 4 non-guard rows + R14, R15, R16, R21 | eligible T-A rows: 20 (D12 a) |
| 9 / 4 (SH) | K1 (b): guard rows read ∅ | the 9 / 4 non-guard rows only | 20 |
| 9 / 4 (K1c) | KC §2.4 item 4 | the same 9 / 4 rows (R15 → CB23, R21 → CB1) | 20 |
| 10 / 5 (R15ref, C-Σ) | R15 + R3's {con, r, d} (C-Σ: A-ii exceptive) | 9 / 4 + R15 | 20 |
| 11 / 6 | R15ref + R14 "may coincide" | 9 / 4 + R14, R15 | 20 |
| 6 / 3 (K18, composition I) | T2 "rests on" column on every T-A row | R3, R5, R6, R14, R15, R22 / R3, R14, R15 | 20 |
| +1 / +1 (D12 b) | R24 shared, not at CV4 | + R24 | 21 |

**How to read "x / y".** It is two counts, not a ratio:
- x = |E3-REL| under S;
- y = |E3-REL| under S′ (= S″).

The denominator is the eligible set: the 24 T-A rows minus H.

## 5. Limitations

- I am Claude-family (Λ7) and read the same sources. This work is independent of the **code**, not of the model family or of how the sources are interpreted.
- The transcription choices in §1 resemble FZ's by construction, because both follow T3's table.
- Agreement shows that the figures are **reproducible**. It does not show that the semantics are justified.
- Only the T-A rows are encoded, so no claim is made about T-B, T-C or T-D rows.
- C-Σ's sets are transcribed from KC-MKC's statement of them, not re-derived from the Σ₁ rule.
