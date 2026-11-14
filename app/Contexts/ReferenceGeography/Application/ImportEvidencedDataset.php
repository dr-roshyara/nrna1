<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Application\Import\DatasetRefused;
use App\Contexts\ReferenceGeography\Application\Import\EvidencedDataset;
use App\Contexts\ReferenceGeography\Application\Import\ImportResult;
use App\Contexts\ReferenceGeography\Application\Ports\EvidenceFiles;
use App\Contexts\ReferenceGeography\Domain\Violation;

/**
 * G-8 (D-226 Q-G-1, D-232; options A + C, 2026-11-14): turns an evidenced dataset into ONE DRAFT revision.
 * 1. parse the manifest strictly (EvidencedDataset): every slot through the domain's rules, every claim citing evidence;
 * 2. verify every evidence file against its recorded SHA-256 (a mismatch or a missing file refuses the import);
 * 3. prepare the draft with all claims and evidence in one save (RevisionService::prepareWithContent: the actor must
 *    be appointed; all or nothing); one dataset version is one revision, so a second import is refused;
 * 4. return a validation preview.
 * It assesses, submits, approves and publishes NOTHING: those stay the stewards' acts (D-84), so a working importer
 * never means accepted data. Not wired to any route or command: production publication waits for the Platform Owner.
 */
final class ImportEvidencedDataset
{
    public function __construct(
        private readonly RevisionService $revisions,
        private readonly EvidenceFiles $files,
    ) {
    }

    public function import(string $directory): ImportResult
    {
        $manifest = $this->files->manifest($directory) ?? throw new DatasetRefused("No manifest.json in {$directory}.");
        $dataset = EvidencedDataset::fromJson($manifest);
        foreach ($dataset->evidence as $e) {
            $actual = $this->files->sha256($directory, $e->file);
            if ($actual === null) {
                throw new DatasetRefused("Evidence {$e->id}: file {$e->file} is missing.");
            }
            if (!hash_equals($e->sha256, $actual)) {
                throw new DatasetRefused("Evidence {$e->id}: file {$e->file} does not match its recorded SHA-256 (recorded {$e->sha256}, found {$actual}).");
            }
        }
        try {
            $this->revisions->prepareWithContent($dataset->revisionId, array_map(static fn ($e) => $e->toEvidenceVersion(), $dataset->evidence), $dataset->claims);
        } catch (RevisionChangedConcurrently) {
            throw new DatasetRefused("Dataset {$dataset->dataset} version {$dataset->version} is already imported (revision {$dataset->revisionId->value()}).");
        }
        $preview = $this->revisions->validate($dataset->revisionId);

        return new ImportResult($dataset->revisionId->value(), count($dataset->claims), count($dataset->evidence),
            array_map(static fn (Violation $v): string => "{$v->rule}: {$v->detail}", $preview->violations));
    }
}
