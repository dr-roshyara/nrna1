# VERIFY — adoption of the seven GATE1-B2 clarifications as section G (r3)

Sources: only the five files named in TASK.md, read in full. No commands and no web were used.
Method limit: the files were compared as the Read tool rendered them, line by line. Trailing whitespace, invisible characters and the final newline cannot be seen that way, so they are **not verified**.

---

## 1. Fidelity

### 1a. Is each r3 manual = old manual + section G, with nothing else changed?

**Gate 1:** `R3-MANUAL-GATE1.md` lines 1–95 are identical to `OLD-MANUAL-GATE1.md` lines 1–95. I checked every line: title, §A text and all 28 table rows, §B all 22 rows plus the derived-consequences note, §C, §D (values line and V1–V7), and §E. The only thing appended is:

- lines 96–97: two blank lines
- line 98: the `## G. r3 rules (…)` header
- line 99: a blank line
- lines 100–106: G1–G7

**Reserve:** `R3-MANUAL-RESERVE.md` lines 1–116 are identical to `OLD-MANUAL-RESERVE.md` lines 1–116. That covers the r2 title, §A–§E, and §F items 1–6 including the sub-bullets. The only thing appended is:

- lines 117–118: two blank lines
- line 119: the G header
- line 120: a blank line
- lines 121–127: G1–G7

**Other differences:** none in substance. Two cosmetic points:
- There are **two** blank lines before `## G`. Every other section break uses one. This is whitespace only and does not change meaning.
- Neither title was updated. The Gate 1 title still reads "Coding manual (frozen)". The reserve title still reads "Coding manual r2 (frozen; …)" although the file now contains r3 rules. This is not a change, so fidelity holds, but it is a labelling inconsistency (see §4).

→ **fidelity_ok = true**

### 1b. Does G reproduce the seven recommendations verbatim, apart from the dropped "RECOMMENDATION ONLY." prefix?

I compared each rule character by character against `RECOMMENDATIONS.md` items 1–7:

| G | Text identical to recommendation after the prefix is removed? |
|---|---|
| G1 | yes (including the single quotes and final period inside the quotes) |
| G2 | yes |
| G3 | yes |
| G4 | yes |
| G5 | yes |
| G6 | yes |
| G7 | yes |

Only formatting and framing were added:
- The numbered list `N.` became bullets `- **GN.**`.
- A header was added: "authorized by the human act of 2026-09-30; they override anything above that is looser".

The header is new text, not a recommendation. Its override clause copies §F's wording word for word, and it matters for §2 below.

→ **verbatim_ok = true**

---

## 2. Consistency (G vs A–F)

**About the override clause.** G says it overrides "anything above that is **looser**". The clause runs in one direction only. It covers cases where the rule above is looser than G. It does not say that G wins where G is the *looser* rule. "Looser" is also undefined for coding rules: the codes are not ranked on a strict/lax scale.

The "prevails" column below gives (i) what the clause literally yields and (ii) the evident intent.

| # | Rule | Existing text | G text | Prevails |
|---|---|---|---|---|
| C1 | G1 vs D-V1 (vacuous case) | D V1: "every act the record performs is permitted by §C" (a record with only unguarded acts meets this trivially → SUPPORTED) | G1: "If no act of this record is subject to a §C guard and no refusal cites a rule, code V1 UNTESTABLE." | G1. SUPPORTED-by-vacuity is plausibly the "looser" reading, so the clause applies. The meaning of V1 changes: vacuous records move from SUPPORTED to UNTESTABLE. |
| C2 | G2 vs D-V1 (aggregation) | D V1: SUPPORTED when "every act the record performs is permitted by §C" | G2: "SUPPORTED (at least one guarded act satisfied, none UNTESTABLE)" | G2. It is stricter, so the clause applies. It silently changes the result for a record with ≥1 satisfied guarded act plus unguarded acts, **if** unguarded acts get a per-act UNTESTABLE (see U3). D would give SUPPORTED; G2 would give UNTESTABLE. |
| C3 | G2 vs D values (NOT-MODELLED) | D: "NOT-MODELLED (the relevant act is not in the table)" | G2: "NOT-MODELLED only if every act is not modelled" | G2 (narrower). "Only if" is a necessary condition, not a sufficient one. Together with G1, NOT-MODELLED is reachable only when every act is unmodelled **and** at least one is subject to a §C guard. Examples: START/execution, reopening, creating vocabulary, successor-slice authorization. §C names these, but §A has no verb row for them. |
| C4 | G2 vs F3 (reserve only) | F3: "If unclear, list both and set V1 AMBIGUOUS." (row-level, unconditional) | G2: "VIOLATED > AMBIGUOUS > …" | G2 per the clause's intent: F3's AMBIGUOUS becomes a per-act value that a VIOLATED act outranks. Literally, F3 sets the **row** value, and neither rule is clearly "looser". |
| C5 | G6 vs F3 (reserve only) | F3: "If unclear, list both and set V1 AMBIGUOUS." | G6: "code AMBIGUOUS only if the competing readings give different codes for that judgement." | G6. F3's unconditional AMBIGUOUS is looser. Real divergence: an ADOPT reading with guard satisfaction unstated gives UNTESTABLE (F1). An ADOPT-DECISION reading has no guard, which also gives UNTESTABLE. F3 still demands AMBIGUOUS; G6 forbids it. |
| C6 | G6 vs D values | D: "AMBIGUOUS (the text allows two readings)" | G6 (as above) | G6. It is stricter and explicitly covers "all judgements". An explicit narrowing, not a silent one. |
| C7 | G3 vs D-V1 (refusals) | D V1: "every refusal that cites a rule concerns an act §C forbids" (a refusal citing a non-§C rule fails the SUPPORTED condition) | G3: "A refusal citing a rule outside §C does not bear on V1." | Intent: G3. **Literally unresolved.** G3 is the *looser* rule because it removes a SUPPORTED condition, and the clause only overrides looser rules above. |
| C8 | G5 vs E (silence) | E: "Silence is not evidence: if the record does not say it, the judgement is UNTESTABLE, not VIOLATED." | G5: "AMBIGUOUS if it names no actor." | G5 as the specific rule. The clause does not settle it, because neither rule is clearly looser. Silence about the actor now yields AMBIGUOUS instead of UNTESTABLE. G5 is at least compatible with G6: the authority reading gives SUPPORTED and the evidence-alone reading gives UNTESTABLE/VIOLATED, so the codes differ. |
| C9 | G5 vs F4 (reserve only) | F4: V4 "applies only if the record cites evidence … that bears on an existing decision or standing. Otherwise V4 is UNTESTABLE." | G5: "when a status change and evidence appear together, SUPPORTED if … AMBIGUOUS if …" | F4 should gate G5, but the text doesn't say so. If evidence appears next to a status change without bearing on it, F4 gives UNTESTABLE and G5 gives SUPPORTED or AMBIGUOUS. The clause cannot rank these. |
| C10 | G7 vs D-V2 | D V2: "Separation of permission / authorization / commissioning / execution … VIOLATED if it treats one as the other without a separate act" | G7: "V2 applies only if the record mentions permission, authorization, commissioning or execution; separating approval or acceptance from execution is outside V2." | Mostly consistent: D's four terms do not include approval or acceptance. There is one silent narrowing. A record that goes "approved → executed" with no authorization act could, under D, be read as treating approval as authorization, which is V2 VIOLATED. Under G7 it is outside V2 (§C START still catches it under V1). G7 is looser here, so the clause does not literally cover it. Intent: G7. |

Items from TASK.md's "pay special attention" list:
- **G2's V1 precedence vs existing V1 aggregation:** C2, C3, C4.
- **G6 vs AMBIGUOUS guidance:** C5, C6. There is a real conflict with F3 in the reserve manual only.
- **G7 vs the V2 definition:** C10. Consistent with the definition's four terms; narrower only for the approval-as-authorization case.
- **Reserve F vs G:**
  - F1 is consistent with G2 and supplies the per-act UNTESTABLE that G2 needs.
  - F2 supplies the act definition that G1 and G2 need.
  - F3 conflicts with G6 and G2 (C4, C5).
  - F4 overlaps G5 without saying which gates which (C9).
  - F5 and F6 are untouched.

Tensions inside G (the override clause does not reach these):
- G1 and G2: no strict contradiction, because of "only if" (C3). But G2's NOT-MODELLED case is barely reachable, and G2 never says what happens when every act is unmodelled and one is guarded.
- G1 says "no refusal cites a rule" (any rule). G3 says non-§C refusals don't count. A record with only unguarded acts and a refusal citing a non-§C rule therefore escapes G1. It reaches UNTESTABLE only by falling through G2's order.

---

## 3. Codability

| Term / construct | Where used | Defined in A–F? |
|---|---|---|
| "§C guard" / "subject to a §C guard" | G1, G2 | Partly. §C lists the guards, but three of them attach to acts with no §A operation: "START / execution", "reopening", and "creating new vocabulary, categories or a methodology extension". There is no rule for deciding whether an act is subject to a guard. |
| "guarded act satisfied" | G2 | No. Nothing says what "satisfied" means per act, or whether satisfaction must be stated. The reserve manual's F1 gives the unstated case (→ UNTESTABLE). Gate 1 has nothing. |
| per-act code of an **unguarded** act | G2 ("none UNTESTABLE") | No. If unguarded acts count as UNTESTABLE, G2 almost never yields SUPPORTED. If they are excluded, G2 works. The text does not say which. |
| "act" | G1, G2 | Reserve: F2 (precise). Gate 1: only §A "every performative act … (the headline and the Effect column)", with no exclusion of states, derived consequences or others' annotations. |
| "refusal", "cites a rule" | G1, G3, D-V1 | No. REJECT exists in §A, but nothing says whether a refusal is an act in G2's per-act scheme, or how a §C-citing refusal gets coded. |
| "demonstrated need" | §C, G4 | Defined by G4. Inside G4, "the problem" is undefined: which problem? |
| "successor slice", "slice", "predecessor" | §C, G4 | G4 gives a trigger (the record names the predecessor), not a definition. "Slice" is never defined. A successor that doesn't name its predecessor escapes the guard, which narrows §C. |
| "authority" / "act of an authority" | G5 | No. §C has "Decision Authority"; V6 has "deciding authority". Neither is defined. There is no code for a named actor who is not an authority. |
| "actor" | G5 | No. |
| "evidence" | G5 | Reserve: F4 gives examples. Gate 1: undefined. |
| "status change" | G5 | "status" appears in §B frames. It is unclear whether "standing" (in V4) is included. |
| "competing readings" | G6 | Only as D's "two readings". |
| "mentions" (keyword vs concept) | G7 | No. |
| "commissioning" | D-V2, G7 | No. There is no §A operation for it. |
| "looser" (override clause) | G header (and F header) | No. There is no ordering over codes or rules. |

Per rule:
- **G1:** codable only once "subject to a §C guard" is resolved.
- **G2:** not reliably codable because of the unguarded-act gap.
- **G3:** codable.
- **G4:** mostly codable.
- **G5:** codable except for a non-authority actor, and for applicability versus F4.
- **G6:** codable.
- **G7:** codable apart from "commissioning".

---

## 4. Asymmetry (Gate 1 has no §F)

No G rule names §F. There is still an **implicit** dependency:

- **G2** needs a per-act UNTESTABLE for a guard whose satisfaction is not stated. That is F1. Gate 1 has only E's general silence rule.
- **G1 and G2** need an act definition that excludes state descriptions, derived consequences and annotations by others. That is F2. Without it, Gate 1 coders may count Effect-column consequences as acts, which changes both G1 (vacuity) and G2 (aggregation).
- **G5** needs a notion of evidence that "bears on" a decision. That is F4, absent in Gate 1.
- **G6's** conflict with F3 exists only in the reserve manual. The two manuals will therefore code ADOPT/ADOPT-DECISION ambiguity differently.
- **Labelling:** the Gate 1 manual now has "r3 rules" but no r2 rules and an unversioned title. The reserve manual keeps its "r2" title while containing r3.

G is applicable in the Gate 1 manual, but less determinate there, and the same record can get different codes under the two manuals.

→ **f_dependency:** implicit. No explicit reference to §F, but G1, G2 and G5 rely on concepts that only §F (F1, F2, F4) defines.

---

## 5. Verdict: **PASS_WITH_LIMITATIONS**

The adoption itself is correct:
- Each r3 manual is its old manual plus section G, with only whitespace and header framing added.
- G1–G7 match the recommendations verbatim, apart from the prefix.

It does not FAIL on correctness of adoption. The limitations below come from the adopted text and the manuals' structure, not from transcription error. Before coding, they should be resolved or recorded as known limits:

1. The override clause is one-directional and "looser" is undefined. G3, G7 and G5 are not clearly given precedence (C7, C8, C10).
2. G2 does not say whether unguarded acts count as UNTESTABLE for "none UNTESTABLE" (U3/C2).
3. G6 conflicts with F3's unconditional V1 AMBIGUOUS in the reserve manual (C5). G2's precedence also conflicts with F3's row-level AMBIGUOUS (C4).
4. G5's applicability versus F4 is unordered (C9). G5 has no code for a named non-authority actor.
5. Undefined terms: "subject to a §C guard", "guarded act satisfied", "refusal", "the problem" (G4), "slice", "authority", "commissioning", "mentions".
6. G silently depends on F1, F2 and F4, which the Gate 1 manual lacks. The two r3 manuals are not equivalent.
7. Neither title was updated to r3. There are two blank lines before G (cosmetic).
8. Not verified: trailing whitespace, invisible characters and the final newline.
