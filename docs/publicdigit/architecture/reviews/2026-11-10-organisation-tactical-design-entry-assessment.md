# Organisation tactical DDD design: entry assessment (step 1)

| | |
|---|---|
| **Status** | **PROPOSED.** Tactical design work under the accepted Organisation strategic baseline (D-106). Decides nothing; no code, schemas or tickets. Not committed |
| **Placement** | `php scripts/doc-placement.php --scope=product-specific --maturity=research --domain=publicdigit` → `docs/publicdigit` (exit 0); `architecture/reviews/` |
| **Controlling boundary** | Organisation strategic baseline v1.0 revision 2c, **ACCEPTED (D-106)** · Reference Geography **RATIFIED (D-93)** · Membership **RATIFIED (D-96; CR-01 = D-73, D-77)** · Identity XD-3 (D-75) |
| **Canonical discovery** | The Organisation tactical design already exists: **§45 of `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md`** (PROPOSED; "held, not withdrawn", §46). **This assessment extends §45; it does not create a second design** (ES-005.4) |
| **Labels** | [AD] decision (RAT / ARB / D / WD) · [BF] fact · [AC] architectural conclusion · [TD] tactical recommendation · [TU] technical uncertainty · [OQ] Principal question |

## 1. Business explanation

Organisation keeps **the party's own map of itself:**
- **its structure:** the levels it uses and the units and wings it has set up, over time;
- **who belongs where**, on the basis of its own acts and rules;
- **the people it knows:** their affiliation with it, and its own record of each of them;
- **the areas its units serve:** official places taken from Geography, and areas it defines itself.

**It holds all of this; it decides none of it by itself.** Decisions come from the organisation's competent bodies under its written rules (D-106 conclusion 5).

**What tactical design must protect most:**
- a structure that is **always consistent on any date**, past or present;
- **a belonging that never arises from an address or from the map**;
- **history that is never rewritten.**

## 2. §45 against the accepted and ratified boundaries

**Most of §45 still holds:**
- its ubiquitous language: Tree, OrganisationalUnit, PersonBelonging, Belonging, BelongingBasis, Transfer, PlacementSuggestion, RuleCopy;
- its aggregates (Organisation registry, Tree, OrganisationalUnit, PersonBelonging);
- its invariants S-1 … S-4 and V-24;
- its change sets (D-30);
- its rule that no event ever comes from an address change;
- its 17 scenarios.

**It predates D-81 … D-106, and these gaps follow:**

| # | §45 passage | Conflicts with | Consequence | Kind |
|---|---|---|---|---|
| **T-1** | §45.6 Geography: "R-10 copy behind an anti-corruption layer; `OfficialUnitRevised` → `CoverageReviewNeeded`"; §45.3 coverage entry `(officialUnitRef, knownRevision, period, status)` | **D-93**: Geography publishes `RevisionPublished` at a **publication position**; identities are `ReferenceUnitId`; reconciliation is `resolve(B, p)` → canonical A with `knownAtPosition` | Coverage must hold `(ReferenceUnitId, knownAtPosition, valid period)`; **a reconciliation (B → A) must be followed**; "revision" means a position, not a row version | **Defect, to correct** |
| **T-2** | §45.3 OrganisationalLevel geography link `(countryCode, adminLevel)` | **D-81**: positions 3–10 belong to a country's **dated profile**; level numbers mean different things in different countries | The link must be to **(country, profile position or class) as at a date**, never to a bare number | **Defect** |
| **T-3** | §45.3 coverage: official units only | **D-82**, D-106: coverage = official references **and/or organisation areas** | **Organisation areas are missing from §45** (an area's identity, definition, lifecycle and relation to official references) | **Gap** |
| **T-4** | §45 has no affiliation or person profile | **D-101, D-103, D-104** | Missing: affiliation with its capacity; "Member" only reflecting Membership (D-103); workspace access as Organisation's fact (D-104); the person profile with address as supplied | **Gap** |
| **T-5** | §45.7 temporal model: "barrier §38.2" | **D-73** "proved by transaction ordering, not by moving timestamp generation"; Geography's knowledge-time contract (D-93 §6): refuse, don't bump | §38.2 moves timestamps (`max(clock, closedThrough + 1 µs, last + 1 µs)`). **Organisation must adopt the platform knowledge-time contract** instead (same issue as Membership's PO-8) | **Defect** |
| **T-6** | §45.3 Capacity: member · staff · guest · observer | D-101 affiliation capacities: User · Member · Worker · Guest · voter | **Two different lists**: one for belonging to a unit, one for affiliation with the organisation. Not wrong in itself (different levels), **but the vocabulary is unreconciled** | **Vocabulary, O-14** |
| **T-7** | §45.6 Governance R-1 / R-2 | D-29 is a **working decision**; D-106 excludes the exchange | Keep as the **working collaboration model**, not a contract | Status label |
| **T-8** | §45.6 Election R-5 `EffectiveMembersAsAt` | Election owns the electorate (RAT boundary) | **Organisation answers "belongings as known at τ"; Election decides who counts** | Wording |

## 3. The domain model (step 1 view)

**[TD] Five consistency areas.** Each exists because of a business invariant, not because of a table.

| Area | Business fact | Invariant that needs a consistency boundary | §45 aggregate |
|---|---|---|---|
| **Registry** | The organisation exists, with its identity | Its identity persists through renaming and restructuring (D-100) | Organisation |
| **Structure** | Levels and units over time | **On any date, every active unit has exactly one active parent in its tree, there are no cycles, and every unit's level fits** (S-1 … S-3); **a structural change set takes effect whole or not at all** (D-30) | Tree + OrganisationalUnit, coordinated by the tree version |
| **Belonging** | Where each person belongs, in which capacity | **At most one active geographic member belonging per person per date** (V-24, WD); every belonging has a basis (D-79, D-3) | PersonBelonging |
| **Affiliation and profile** (new, T-4) | The person's relationship with the organisation, and the organisation's record of them | "Member" never asserted by Organisation (D-103); never disclosed to another organisation (D-101) | **to design** |
| **Areas and coverage** (new, T-3) | Organisation areas; what each unit serves | An area never becomes official; coverage never creates belonging (D-80, D-82) | **to design** |

**Lifecycle responsibilities:**
- **Units:** established, moved, divided, merged, ended, brought back (D-13 … D-19), **only by decided change sets.**
- **Belongings:** established, transferred, ended, corrected (D-79 default: individual acts).
- **Affiliations:** begun and ended under the organisation's rules.
- **Areas:** defined and redefined by the organisation.

**Every change** is append-only, with an effective date and a knowledge time (D-73).

## 4. Most important invariants (to carry into every later step)

1. **Structure is consistent "as at" any (date, τ).**
2. **No belonging, transfer or coverage change ever follows from an address, a residence move or a Geography publication** (D-79, D-80, D-92). A Geography change produces at most a *review needed* signal.
3. **Membership truth only from Membership** (CR-01 C1/C2; D-103).
4. **Every structural change rests on a recorded decision** (D-106 conclusions 4 and 5).
5. **History is corrected forward:**
   - **a record correction changes what was known;**
   - **a new act changes what is true** (§45.7).

## 5. Recommended first tactical boundary

**Structure first: Tree, OrganisationalUnit and their Geography link** (T-1, T-2, T-5).
- **Why first:**
  - **belonging, coverage and areas all depend on it;**
  - **it is where the ratified Geography changes §45 the most** (positions, `ReferenceUnitId`, profiles, reconciliation);
  - its invariants (S-1 … S-4, D-30) are the hardest consistency rules in the context.
- **Then belonging** (the central one, as the review advises), **then affiliation and profile, then areas and coverage, then the cross-context contracts.**

## 6. Principal questions

**None now.**
- T-6 (vocabulary) is **O-14**, which is already open; it does not block step 2.
- Whether a wing is a separate tree or a unit type is tactical **within D-99**: it depends on the organisation's rules, and §45.9 already records it.

## 7. Next step

**Step 2:** the structure design. It revises §45.3 and §45.4 for D-81 and D-93:
- the level ↔ profile link;
- coverage entries by `ReferenceUnitId` and position;
- reconciliation handling;
- the knowledge-time contract replacing §38.2.

It will be written as **a dated addition to §45 (or a successor section), not a parallel document.**

**Update (2026-11-10):** step 2 is written as **§49** of `docs/publicdigit/reviews/2026-11-08-strategic-context-map-and-governance-tactical-model.md` (PROPOSED). It also corrects this assessment's "exactly one active parent" (§3): roots have none (S-0, S-1).
