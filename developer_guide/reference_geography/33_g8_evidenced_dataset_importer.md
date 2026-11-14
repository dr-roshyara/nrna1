# 33: G-8, the evidenced-dataset importer (PBDIGIT-346)

**Decisions:** D-226 (Q-G-1: initial data as evidenced revisions; the legacy seeder is input, not evidence), D-232; Principal instruction 2026-11-14 (options A + C). **Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-14-pbdigit346-g8/`.

## Business purpose

**Official geography enters PublicDigit only as claims that rest on traceable evidence.**
- **The importer turns a dataset** (a manifest plus its source documents) **into one draft revision.** Every source is recorded: authority, URL, publication, retrieval time, SHA-256, reuse terms.
- **It never accepts the data.** Assessing, validating, submitting, approving and publishing stay the stewards' acts (D-84); acceptance of the initial dataset is the Human Arb Chief's.

## A dataset

A directory holding `manifest.json` and the evidence files it lists:

```json
{ "dataset": "…", "version": "1",
  "evidence": [ {"id": "cldr:47", "authority": "Unicode CLDR", "issued_on": "2025-03-13", "url": "…", "publication": "…",
                 "retrieved_at": "2026-11-14T…Z", "file": "cldr47-territory-names.csv", "sha256": "<64 hex>", "reuse_terms": "…"} ],
  "claims": [ {"slot": {"kind": "CLDR_NAME", "entry": "@iso:NP", "locale": "de", "form": "standard"},
               "value": "Nepal", "from": "2000-01-01", "basis": "other", "evidence": "cldr:47"} ] }
```

- **`@key`** names an entry. `EntryKeys::unitId(key)` is a deterministic version-5 UUID in a fixed PublicDigit namespace, so `@iso:NP` is the same entry in every dataset. **Never change the namespace constant.**
- **Every slot is built by the domain's own `SlotKey` rules,** so a manifest can express nothing the domain would refuse. **Every claim cites declared evidence.**
- **Claim and revision ids are deterministic:** one dataset version is one revision, and importing it again is refused.

## Flow (`ImportEvidencedDataset::import($directory)`)

1. **Parse** (`EvidencedDataset::fromJson`): strict; `DatasetRefused` with the reason.
2. **Verify every evidence file** against its recorded SHA-256 (the `EvidenceFiles` port; `LocalEvidenceFiles`, which accepts plain file names only). A mismatch or missing file is refused.
3. **`RevisionService::prepareWithContent`:** the actor must be appointed. **One save, all or nothing.**
4. **A validation preview** (`ImportResult::$violations`).

**Then the ordinary path:** `recordAssessment` → `validate` → `submit` → `approve` (a second person, for structural claims) → `PublishRevision::publish`.

| Layer | Component |
|---|---|
| Application | `ImportEvidencedDataset`; `Import\EvidencedDataset`, `DatasetEvidence`, `EntryKeys`, `ImportResult`, `DatasetRefused`; `RevisionService::prepareWithContent` |
| Port and adapter | `Ports\EvidenceFiles` → `Infrastructure\LocalEvidenceFiles` |

**Not wired to any route or console command.** Production publication waits for Platform Owner authority, and the initial dataset waits for the Human Arb Chief's acceptance. Tests call the use case directly on the isolated cluster.

## Testing

```
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php tests/Unit/Contexts/ReferenceGeography/Application/EvidencedDatasetTest.php
MEMBERSHIP_PG_IMAGE=<pinned> MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite ReferenceGeographyPostgres --filter ImportEvidencedDatasetTest
```

- **The sample** `tests/Fixtures/ReferenceGeographyDatasets/sample/` is **synthetic** (`XA`, `XB`: ISO user-assigned codes). Never load it outside tests.
- **Mutation:** 9 mutants:
  - 7 killed by assertions;
  - 2 refused by guards (a `TypeError`; the domain's unit id);
  - I4 found a test gap (refusal after a write), now closed.

## The proposed real sources

`…/2026-11-14-pbdigit346-g8/proposed-sources/SOURCES.md`:
- **CLDR 47:** stored, under the Unicode License V3;
- **UN M49:** **not stored** (its terms restrict commercial use; a decision is pending);
- **ISO 3166-1:** **unreachable** (stopped);
- **Nepal:** **unreachable** (a gap).

**Administrator: no administrator action required.** No migration, configuration or schedule. **Do not run the importer against production.**

**Traceability:** PBDIGIT-346 G-8 · D-84, D-86, D-226, D-227, D-232.
