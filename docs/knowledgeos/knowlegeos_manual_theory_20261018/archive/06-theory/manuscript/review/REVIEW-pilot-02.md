# Pilot 02 — five files with idea classification, verification and look-backs

| Field | Value |
|---|---|
| Date | 2026-10-18 |
| Access state | corpus **not authorized**: governance log unchanged. The pilot used authorized background files, in the knowledge-transfer set's chronological order after pilot 01 |
| Files (all read in full) | ① ARB decision record A/B `…1156` (199 l.) · ② rule-model semantics `…1211` (403 l.) · ③ authority-model semantics `…1332` (342 l.) · ④ SC-1 dimension preparation `…1513` (381 l.) · ⑤ environment vocabulary `…2043` (193 l.) |
| Independent checks | code and config: `design-rules.json`, `design-check.sh`, `db-safety-check.sh`, `config/app.php`, `phpunit.xml`, CI workflows · **live authority record**: 144 grants recounted · governed schema: 10 YAML files |
| Not checked | `.env*` templates (blocked as credential files; respected) · the 2026-08-16 state of the grant record (untracked by git, so not reconstructable) |
| Register | `06-theory/IDEA-REGISTER.tsv`: **70 ideas** (67 from the files + 3 added by the closing look-back) |

## Classification of the 70 ideas

| Classification | n |
|---|---|
| New idea (from source) | 18 |
| New idea (researcher, from verification or deduction) | 5 |
| New evidence or example | 19 |
| Update or refinement | 14 |
| Contradiction or revision | 3 |
| Repeated idea | 5 |
| Open or unresolved | 6 |

**Status:** accepted 53 · provisional 10 · unresolved 3 · challenged 1 · accepted with condition 1 · revised (true then, not now) 1 · out of theory scope 1 (total 70).

## Verified independently

| Claim | Result |
|---|---|
| Strength label ≠ enforcement; declining thresholds; allow-lists as unnamed exceptions | **confirmed** in `design-rules.json` |
| Only governance registers grants; every grant references a human act | **confirmed at n = 144** (source n = 20) |
| No validity, delegation or owner fields on grants | **confirmed** (0/144 each) |
| Grants can never end | **no longer true**: REVOKED and CONSUMED records now exist (source true at the time) |
| Holder field mixes holder and delivery method | **confirmed, worse**: 107 spellings / 144 |
| Vocabularies 31/8/12/3/9; no environment list | **confirmed** |
| Schema "authority" field mixes provenance and standing | **confirmed** (`authorities.yaml`), resolving a cited-only claim from pilot 01 |
| Production only as framework fallback; no deployment | **confirmed** (local template not opened) |
| Gate permits only on positive "testing" | **confirmed**; also found a past cosmetic "BLOCKED" |

## What the theory gained (new understanding)

1. **Ownership** is a separate dimension. It is split inside every mechanism into method (platform), binding (product) and evidence (product). This gives the generative tier model a concrete meaning: a PKS = platform methods + product bindings + evidence.
2. **Authority is a recorded reference to an external act;** the record never contains authority. Verified at 7× the source's sample. Delegation is a derivation relation, not mere containment. Endings exist, including **consumption**, a single-use ending that no source names.
3. **Record integrity is the weak point.** Grant records are not version-controlled, so they are less protected than the acts they point to.
4. **What a rule is (RM-1):**
   - a rule is distinct from a decision, a recommendation and a permission;
   - exception is constitutive of rule;
   - binding strength is separate from enforcement consequence, where enforcement consequence = the realization level;
   - deontic effect = polarity × strength;
   - permission vs prohibition is legitimate iff it is an authorized exception.
5. **Rules with meaning and rules with enforcement are disjoint sets**: the clearest internal evidence for the problem KnowledgeOS addresses.
6. **Scope dimensions (SC-1).** Four dimensions with a governed **not applicable** state, keyed by subject kind. The loophole and its closure are formalized (app : K → 𝒫(D) governed), with a required extension: the set of subject kinds must itself be governed.
7. **Guard design:** require positive evidence of the permitting condition. **Self-reports are claims.** **Silent substitution** is a failure mode of "unspecified".

## Look-back findings

- **Omissions found and fixed:**
  - scope ↔ ownership link (④);
  - grant scope is prose (③);
  - "single canonical home" is machine-enforced (②): **registered, not integrated** into a chapter *(corrected after audit C-01)*;
  - the subject list of rules (②, at the per-file look-back).
- **My own errors corrected:**
  - §2.8.4 assumed universal dimensions;
  - §2.3(b) treated MAY as a rule effect;
  - an ordering problem in §2.8 (renumbered 2.8.5 → 2.8.8);
  - a word clash ("standing obligation").
- **Source claims challenged:**
  - "adding a dimension is cheap" (false under SC-2 unless not-applicable or a governed default);
  - "adding a value costs one entry" (true only with positive enumeration).
- **Weaknesses that remain:**
  - all five sources are AI-generated proposals. Their rulings stand inside an exploratory lane, so the theory's standing from them is **Track-B, not adopted**;
  - prior exposure: I had read these files before;
  - one independent check (the 08-16 grant counts) is impossible because the record is untracked.

## Method assessment

- **What worked:**
  - verification against running code and data. It confirmed some claims, overturned one and found three new ideas;
  - per-file look-backs caught omissions while the file was fresh.
- **What was inefficient:**
  - the register rows are long. A shorter "idea" column would do;
  - section placement inside an existing long chapter caused one numbering fix.

**Neither problem needs a method change.**
