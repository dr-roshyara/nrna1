# ALM-r1 traceability: TD-001 core · v1 · review 1 · corpus → spec → code → test → result (2026-10-18)

**Status:** pre-review-2. Rows marked "bounded" are evidence within the declared bounds (`ALM-R1-RESULTS.md` §1), **not proofs**.

**Result classes:**
- **SRC** source-stated;
- **VER** independently verified: oracle agreement or reproduction;
- **DER** mathematically derived;
- **ASM** assumed;
- **POL** policy-dependent (register ID);
- **CON** contradicted;
- **UNR** unresolved;
- **OOS** out of scope (declared).

## A. TD-001 statements (Addendum 2, #1–#11) and r1.1

| # | Requirement (exact source) | Spec | Code | Test | Result | Class / qualification |
|---|---|---|---|---|---|---|
| 1 | validity requires the required authority (AMS L49–51; V3 L321–323) | S-4.4–S-4.6 | `History._eval`, `auth` | `S0058_…`, `REG_CE4` | pass; Th-1 bounded 67,704/0 | **DER + VER**. Internal only; external obligations **UNR** (T1-15) |
| 2 | subject: decision / rule / exception (r1) | S-2 `ckind ∈ K` | `Act.ckind`, `RD1[ckind]` | `REG_CE1` (decision) | pass | **POL (RD 1 per kind)**. Rule/exception have no differing semantics beyond RD 1: **UNR (T1-04)** |
| 3 | "currently authoritative" (V3 L313) | S-4.8 `Current` | `History.current` | currency vs oracle 121,152/0 | pass | **DER + VER** |
| 4–5 | "an act" vs "a recorded act" (C1) | S-4.6 / S-4.7 | `Theta.C1`, `avail` | `S0103_C1b_vs_C1a`; Pr-10 | pass | **POL (C1)**. The options are **incomparable** (Pr-10, 80 / 100,499 examples) |
| 6 | authority **at the time of the act** (AB L104–107; S0058 L77) | S-4.3/4.4 at point `e` | `alive(g, ('act', e))` | `S0058_anachronistic_linkage`, `REG_CE10` | pass | **DER + VER + SRC** |
| 7 | own validity period (V3 L319) | S-4.8 `cperiod`, `ιc` | `current` | `own_period_ends_currency`, `REG_CE12` | pass | **DER**. Boundary convention **POL (C14 for grants; claim convention [Cand])** |
| 8 | **no later** properly authorized ending (r1.1 clause 3) | S-4.1 END after ADOPT; S-4.8 | `form`, `current` | `REG_CE2`, `REG_V1_CASE12`, `unauthorized_end_no_effect` | pass | **DER + VER**. Was CON in the baseline |
| 9 | ending of authority carries over **under the organisation's rules** | S-4.8 Carry (RD 1 per kind, δ, BASIS) | `current`, `alive` | `REG_CE1`, `REG_CE3` | pass | **POL (RD 1, δ; BASIS [Cand])**. Evidence downgraded (review E-1, E-2, E-3) |
| 10 | recognition **as the records stood** on a date | S-5, S-6, S-8 | `rec`, `rec_at_date` | Th-3 bounded 363,558/0; `S-8_no_clock…` | pass | **DER + VER**, **conditional on AX-AppendOnly (unverifiable on BC-7)** and dated witnesses (none exist in the store: **UNR / evidence**) |
| 11 | "cannot determine", **never "not authorized"** | S-6.1 with λ⁺ = 0, λ⁻ = ∞ | `rec(..., 0, INF)` | `REG_CE6`, `r11_never_F_even_with_recorded_revocation`, `T1-20_horizons…` | pass | **DER**. Was CON. **R1-F4:** r1.1 is stricter than soundness requires (Th-5) → input to r1.2 |

## B. v1 §1.14.1: the 16 case rows

| v1 case | In scope? | Test | Result |
|---|---|---|---|
| 1 alternative grants | yes | Th-1 bounded (multiple root grants); BASIS = any | **VER** (bounded) |
| 2 joint authority + suspension | **OOS** | — | — |
| 3 grant ended before the act | yes | `S0058_…`; bounded (gRaF finite period) | **VER** |
| 4 consumed grant | **OOS** (C13) | — | — |
| 5 supersession with a successor | **OOS** (C5) | — | — |
| 6 annulment with an effective time | **OOS** (RD 2) | — | — |
| 7 re-adoption after ending | yes | expressible with C6 = off | **DER**. No dedicated test → *add* (noted for review) |
| 8 ran out + ended: headline | partial | value only | headline **OOS** (C8) |
| 9 citations | **OOS** (C2) | — | — |
| 10 late-recorded cancellation | yes | Th-4 minimal example; `T1-20_…` | **VER** |
| 11a unauthorized ending | yes | `unauthorized_end_no_effect` | **VER** |
| 11b authorized ending | yes | bounded (rRa) | **VER** |
| 11c delegated grant after the parent ends | yes | `REG_CE3` | **POL (RD 1 δ)**. Was a hidden policy |
| 12 same moment | yes | `REG_V1_CASE12` | **VER**, with a **semantic difference**: v1 orders by record position; r1 by recorded act order (C10 = given). A recorded ord replaces position because records are never back-dated but can be late (AX-NoBackdate). **Divergence explained, not hidden** |
| 13 mutual annulment | partial: the grant analogue only | `C10_mutual_revocation` | **POL (C10)**. Act annulment **OOS** |
| 14 no adoption on record | yes | `S0092_given_unrecorded` (analogue) | **DER**: U (r1.1) |
| 15 unverifiable revocation | **OOS** (RD 6 υ). *Partly:* a revocation with ⊥ fields gives U via worlds | — | **UNR** |

**Tally:**
- in scope: 9 rows (1, 3, 7, 10, 11a, 11b, 11c, 12, 14);
- partial: 3 (8, 13, 15);
- OOS: 6 (2, 4, 5, 6, 9, plus the headline part of 8).

**Divergences:** case 12, explained above. **Hidden policies:** none known (11c is now a parameter).

## C. v1 known defects 1–7

| v1 defect | r1 | Class |
|---|---|---|
| 1 υ fixed point | υ OOS. V is unique by Th-1 | DER (υ OOS) |
| 2 issuance and delegation unchecked | issuance checked (GRANT needs `(GRANT, π)`); δ parameter | DER + POL (RD 1 δ) |
| 3 no adoption on record / annulled / expiry | U on no record; expiry via period; annulment OOS | DER / OOS |
| 4 act time vs total order; no recording time | ord is recorded; ρ optional (C1 = a); position ≠ order | DER + ASM |
| 5 covers / actor–holder | target-specific atoms; holder ≠ performer | DER |
| 6 revoked-actor cycle | C10 parameter (given / no_same_instant) | POL (C10) |
| 7 grant `authority` field = issuer? | the model distinguishes the issuer (GRANT holder) from the grantee | DER. The **data** question stays UNR |

## D. Review 1: see `ALM-R1-RESULTS.md` §3 (each finding: R / NA / A / P / U)

Open items carried:
- **E-8:** REVOKE has no governance-side instance (UNR, evidence).
- **C-9:** `Proc` is supplied externally (ASM).
- **C-10:** static root (ASM, C12).

## E. Corpus and independence rules

| Source | Test | Class |
|---|---|---|
| S0058 L77 | `S0058_anachronistic_linkage` | SRC + VER |
| S0092 L35 / S0095 L141 | `S0092_given_unrecorded` | SRC + DER |
| S0103 L191 | `S0103_C1b_vs_C1a` | SRC + POL (C1) |
| S0052 (n = 1) | `S0052_conditional_grant` | SRC + DER |
| S0104 / IND-5 | `REG_CE5` | SRC + POL (BAR [Cand]) |
| A-1.4 / IND-2 | `REG_IND2` | REPO + DER |
| I-8, EEP / IND-1 | `REG_IND1` | SRC + DER |
| A-4.3, A-5.2 / IND-3 | `IND3_permit_with_disclosure` | REPO + DER |
| INV-ATTR-2 / IND-7 | `IND7_attested_grade…` | REPO + DER |
| IND-4, IND-6 | — | OOS |
| OQ-6 (no governance revocation) | — | UNR (evidence) |

## F. Reverse direction: every model rule → its justification (bidirectional traceability)

**Class key:**
- **SRC** source-stated;
- **REPO** repository, outside the corpus;
- **V1** the book's v1;
- **REV** a review-1 repair;
- **DER** derived from other rules;
- **ASM** assumption;
- **POL** reserved policy;
- **CAND** an unregistered candidate parameter.

| Spec rule | Justification | Class |
|---|---|---|
| S-1 holder ≠ performer; authority on holders only | INV-ATTR-1 (PO/ARB 2026-08-15); review A-6 | REPO + REV |
| S-1 wildcard only at object level | none; a modelling convenience | **ASM** |
| S-2 one ADOPT = one claim (`claim = a.id`) | review C-4 (identity conflation) | REV + **ASM** (no source fixes claim identity) |
| S-2 END covers revoke / supersede / annul of a claim, prospective only | r1.1 clause 3; the ex-tunc / successor variants are OOS | SRC (r1.1) + **ASM** (collapse) |
| S-2 unstated grant period = [τ, ∞) | R1-F2 (fixture defect); S0058 (no anachronism) | **ASM** (no source fixes the default) |
| S-3 C10 options | review P-2/P-6; v1 case 12 | POL (C10) |
| S-4.1 GRANT `from ≥ τ` | S0058 L77 | SRC |
| S-4.1 END after ADOPT | r1.1 "later"; review C-4 | SRC + REV |
| S-4.1 REVOKE/CONFIRM target precedes | none directly; needed by Th-2 | **ASM** (DER-motivated) |
| S-4.2 GRANT needs `(GRANT, π)` per delegated atom | v1 defect 2 (issuance checked); M2 G-10 (narrowing unsourced: not imposed beyond coverage) | V1 + **ASM** |
| S-4.2 target-specific atoms | review C-3 | REV |
| S-4.3 revocation prospective, from its act | S0079 (no back-dating); Stress Test 8 (analogue) | SRC + REPO (analogue) |
| S-4.3 proviso + CONFIRM | S0052 (n = 1) | SRC (thin) |
| S-4.3 δ (ParentAlive) | RD 1 (δ) | POL |
| S-4.4 a single grant must cover all needs | none; pooled coverage C4 OOS | **ASM** |
| S-4.4 static root | C12 | POL (only one option implemented: flagged) |
| S-4.5 IND-1 restriction (holder-level) | S0067 I-8; S0043 L28; checkpoint §3 | SRC |
| S-4.6/4.7 C1 = b / a | r1.1 open alternative C1; review F-3 formulation | POL (C1) |
| S-4.8 C16 inclusive at instant t | register C16 ("confirm only") | REPO-register (DER) |
| S-4.8 Carry per claim kind; BASIS | RD 1 per kind; BASIS unregistered | POL + **CAND** |
| S-4.8 C6 | register C6 | POL |
| S-4.9 IND-2 relational, performer-level | A-1.4/D-5 (PO/ARB 2026-08-14) | REPO |
| S-4.9 IND-5 bars, BAR mode | S0104; BAR unregistered | SRC + **CAND** |
| S-4.9 IND-3 flag; IND-7 grade | A-4.3/A-5.2; INV-ATTR-2 | REPO |
| S-5 AX-AppendOnly | BC-7 T-3 *Proposed*; C-3 (unverifiable) | **ASM** |
| S-5 AX-NoBackdate | S0079, S0091, S0092, S0095, S0103 | SRC |
| S-5 AX-Truth | none; known to fail (S0104 I-1) | **ASM** |
| S-6 worlds over ⊥ fields and contradictions | review C-6; v1 safeguard 5 (in part) | REV + V1 |
| S-6.1 two horizons λ⁺, λ⁻ | review F-1; r1.1 text = (0, ∞) | REV + SRC (r1.1) + POL (T1-20) |
| S-7 Eff = Known ∧ Rec = T ∧ Proc; Eff_obs | S0071 I-2; review E-7 (observed START does not consult grants) | SRC |
| S-8 dated witnesses | Th-8; no source mechanism (none invented: only *required evidence* stated) | DER |

**Assumptions with no source (7):**
- the object-level wildcard;
- claim identity;
- the END collapse;
- the default period;
- target precedence for REVOKE/CONFIRM;
- single-grant coverage;
- AX-AppendOnly and AX-Truth.

They are listed here so that review 2 and the Principal see every one.
