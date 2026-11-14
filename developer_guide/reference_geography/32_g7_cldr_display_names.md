# 32: G-7, CLDR display names (PBDIGIT-346)

**Decisions:** D-87, and **D-231** (Option A, 2026-11-14: D-87 extended to the five level-1 regions). **Design:** v3.11 §3 (the CLDR slots), §9. **Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-14-pbdigit346-g7/`.

## Business purpose

**Every context can ask: "what do I show this user for this country, area or world region?"**
- **The answer is the Unicode CLDR name in the user's language,** as officially known at a chosen point in history.
- **PublicDigit never authors a name.**
  - If CLDR has nothing in the user's language or its parents, the published platform default locale is used, **and the answer says so** (`PLATFORM_FALLBACK`), so it's never mistaken for a translation.
  - If there is nothing at all, the answer is `null`.
- **Official names** (ISO 3166-1, UN M49) remain visible as attributed alternative names in `Place::$names`.

## Using it

```php
$name = $geography->displayName($unitId, 'de-AT', '2026-01-01', $geography->knowledgeAt($k));   // or form: 'short'
// $name->value          'Europa'
// $name->origin         'CLDR' | 'PLATFORM_FALLBACK'
// $name->requestedLocale 'de_AT'   $name->resolvedLocale 'de'   $name->cldrRelease '46'
// $name->form 'standard' | 'short' $name->shortFellBack   $name->fallbackLocale (fallback only)
// $name->validAt, $name->knownAt
```

## How a name is resolved (`CldrDisplayNames`, pure)

1. **The chain** (`CldrParentLocales`): the requested locale (a hyphen is read as CLDR's underscore), then its parents, ending at `root`.
   - **A locale's parent** is the release's explicit parent if the release map has one (`en_IN → en_001`); otherwise the locale minus its last subtag (`de_AT → de`); a bare language's parent is `root`.
   - **`root` is part of the chain and is not English** (v3.11 §9).
   - **A cyclic map ends the chain;** it never loops.
2. **The form:** for `short`, the whole chain is searched for a short form first; if none exists, for the standard form, and `shortFellBack` is true.
3. **No name in the chain:** the same search over the chain of the published `PLATFORM_DEFAULT_LOCALE`, giving `origin = PLATFORM_FALLBACK`. Here `resolvedLocale` and `cldrRelease` are null and `fallbackLocale` is set.
4. **Otherwise `null`.** A withdrawn name (value none) is no name.

**Which parent map:** the map of the **newest CLDR release** (compared numerically, `46.10 > 46.9`) published at k and in force at the valid date. `cldrRelease` reports the release of the **name actually found**.

## What a CLDR claim must carry (`RevisionValidator::cldr`, at submission)

| Rule | Requirement |
|---|---|
| `D-87_RELEASE` | a `CLDR_NAME` names its release: basis `Basis(BasisKind::Other, 'cldr:<release>')`. A `CLDR_INHERITANCE` map's basis release equals its slot's release |
| `D-87_LOCALE` | CLDR locales are CLDR identifiers (`de`, `de_AT`, `en_001`, `zh_Hant_TW`, `root`); the `PLATFORM_DEFAULT_LOCALE` value is a language locale, never `root` |
| `D-87_INHERITANCE` | the map is a JSON object `{child: parent}` of CLDR locales |

**As for every claim, the basis must name evidence attached to the revision.** For CLDR, the release **is** the evidence: `EvidenceVersion('cldr:46', <fingerprint>, 'Unicode CLDR', <release date>)`.
- **All three CLDR claim kinds are non-structural:** one steward publishes them (a new CLDR release is a name revision, D-87).

## Components

| Layer | Component |
|---|---|
| Domain | `CldrRelease`, `CldrLocale`, `CldrParentLocales`, `CldrDisplayNames`, `ResolvedDisplayName`, `DisplayNameOrigin`; `RevisionValidator::cldr` |
| Published | `DisplayName`; `GeographyReadModel::displayName` (in `published-language.json`) |
| Application | `PublishedGeography::displayName` reads the existing `GeographyReadSource::slotHistory` and `slotsOfKind`: **no new port, adapter or table** |

**Region names (D-231):** a level-1 region is an entry like a country. Its CLDR names are `CLDR_NAME` claims on it; its UN M49 names are `SOURCE_NAME` claims (`source: 'UN M49'`). **No region-specific code exists, or should.**

## Testing

```
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/ReferenceGeography/Domain/CldrDisplayNamesTest.php tests/Unit/Contexts/ReferenceGeography/Domain/CldrClaimValidationTest.php
MEMBERSHIP_PG_IMAGE=<pinned> MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite ReferenceGeographyPostgres --filter CldrDisplayNameReadTest
```

- **Unit: resolution (7):**
  - the requested locale with its release;
  - truncation, the release map, root as a locale;
  - short to standard;
  - the marked fallback, and null with no default;
  - withdrawn;
  - a cyclic map;
  - strict parsing.
- **Unit: validation (4).**
- **PostgreSQL (4):**
  - a **region** named by CLDR, with its UN name attributed;
  - **a later release never changes an earlier answer;**
  - the release map, and the marked fallback;
  - refusals: a malformed locale; beyond the knowledge.
- `PublishedGeographyTest`: `displayName` refuses a forged knowledge.

**Mutation** (`mutation-check-g7.php`, 12 mutants):

| Classification | Mutants |
|---|---|
| Killed by assertions | 11 |
| Refused by the intended domain guard (not counted as an assertion kill) | 1: **N6** ("a withdrawn name counts"); `ClaimValue::value()` refuses to read a withdrawal |
| Test gaps the run found | 2, closed: **N11** (the release map was indistinguishable from truncation in the database test) and **N12** (read a property of null) |

## Limitations and next

- **The validator doesn't restrict `CLDR_NAME` to level-1 and level-2 entries.** Who receives CLDR names is decided by what G-8 publishes.
- **The starting value of `PLATFORM_DEFAULT_LOCALE` is a G-8 decision.** The tests publish `en` as **fixture data only**. Until one is published, a user whose chain has no CLDR name gets `null`.
- **Which CLDR release and locales to load** is G-8 data.
- **Pitfall (third time in this work):** a PHPUnit test helper named `name()` or `status()` overrides a **final** `TestCase` method and kills the whole file. Name helpers `nameSlot()`, `statusOf()`.

**Administrator: no administrator action required.** No migration (the CLDR slot kinds exist since G-1), configuration or schedule.

**Traceability:** PBDIGIT-346 G-7 · D-87, D-231 · v3.11 §3, §9 · completion (D-226) §8.
