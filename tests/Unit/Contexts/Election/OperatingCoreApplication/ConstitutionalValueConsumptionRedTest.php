<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Election\OperatingCoreApplication;

use App\Contexts\Election\Domain\OperatingCore\Committee\VacancyGround;
use App\Contexts\Election\Domain\OperatingCore\Event\CommitteeSeatVacated;
use App\Contexts\Election\Domain\OperatingCore\Event\ElectionBecameInoperative;
use App\Contexts\Election\Domain\OperatingCore\Event\ElectionRestored;
use App\Contexts\Election\Domain\OperatingCore\Event\RecoveryPeriodStarted;
use App\Contexts\Election\Domain\OperatingCore\Recovery\PeriodKind;

/**
 * EM-IMPL-002 — RED AMENDMENT (architecture hold before GREEN-5, item 3).
 *
 * THE APPLICATION BOUNDARY RULE, in the PO's promoted wording:
 *
 *   > **Application consumes established constitutional values.**
 *   > **Application never derives constitutional mathematics.**
 *
 * Promoted from a GREEN-2 design decision to a standing boundary rule, because the
 * same question recurred in UC-2 and UC-3 (both need the required Committee Votes)
 * and each recurrence is an opportunity for the application to acquire a second
 * rule surface (G-5's named smell).
 *
 * ⚠️ HONEST CHARACTERIZATION OF THIS FILE — it is a REGRESSION LOCK, not a
 * failing-first obligation. The behaviour it protects is ALREADY TRUE at GREEN-4:
 * no handler derives the threshold, and UC-2/UC-3 read `requiredVotes()` from the
 * established acceptance decision. These tests therefore PASS on arrival. That is
 * stated rather than disguised: adding an assertion that fails only because
 * production code has not yet been written would be RED; locking in compliance
 * that already exists is a GUARD. Both are legitimate, and they are not the same
 * thing — the RED→GREEN ordering claim belongs to the pins in the other files.
 *
 * The two halves are pinned separately, because they fail in different ways:
 *  · NON-DERIVATION is structural — a rule surface can appear in code that no
 *    behavioural test would notice (six months later, `ceil(2 * $n / 3)` in a
 *    handler still passes every flow test).
 *  · CONSUMPTION is behavioural — the handler must act on the value the domain
 *    ESTABLISHED, which is provable through the flows: the two-thirds denominator
 *    and a bare majority disagree at a Committee of four, so the onset and the
 *    restoration land at different vacancies under each. The tests below are
 *    constructed on exactly that disagreement.
 *
 * Scope note: the token list deliberately excludes the bare words `threshold`,
 * `majority` and `quorum`. Handler PROSE legitimately discusses them (UC-1's
 * docblock states that it never compares thresholds), and a scan that forbids
 * vocabulary in comments is the defect recorded as `PBDIGIT-71`. This pin
 * therefore forbids CODE-LEVEL derivation forms only.
 */
final class ConstitutionalValueConsumptionRedTest extends OperatingCoreApplicationTestCase
{
    private const APPLICATION_DIR = __DIR__ . '/../../../../../app/Contexts/Election/Application/OperatingCore';

    /**
     * The forbidden forms: every way the application could COMPUTE a constitutional
     * value instead of consuming the one the domain established. All are absent at
     * GREEN-4; this pin keeps them absent.
     */
    private const DERIVATION_FORMS = [
        'RequiredVotes::',            // constructing the required votes itself
        'forConstitutedSize(',        // …by the value object's own factory
        'requiredVotesFor(',          // …or through the rule's factory
        'ThresholdRule',              // holding the named rule at all (P-1's surface)
        'ThresholdEvaluation',        // P-1 raw policy call
        'twoThirdsOfCommitteeVotes',  // naming the adopted rule (EM-GOV-036) in application code
        'intdiv(',
        'ceil(',
        'floor(',
        'round(',
    ];

    /** @return list<string> */
    private function applicationSources(): array
    {
        $files = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(self::APPLICATION_DIR, \FilesystemIterator::SKIP_DOTS)
        );
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }
        sort($files);

        return $files;
    }

    /** Half 1 (structural): the application holds no constitutional mathematics of its own. */
    public function test_no_application_source_derives_constitutional_mathematics(): void
    {
        $sources = $this->applicationSources();
        $this->assertNotEmpty($sources, 'The granted application surface must exist for this guard to mean anything.');

        foreach ($sources as $file) {
            $source = (string) file_get_contents($file);

            foreach (self::DERIVATION_FORMS as $form) {
                $this->assertStringNotContainsString(
                    $form,
                    $source,
                    sprintf(
                        'Application derives constitutional mathematics in %s (forbidden form "%s"). '
                        . 'Constitutional values are ESTABLISHED by the domain and consumed by the application '
                        . '(EM-GOV-036/038/057; G-1/G-5).',
                        $file,
                        $form,
                    )
                );
            }

            // No arithmetic or comparison performed directly on a domain count — the
            // aggregate answers questions about its own numbers (P-3's door is
            // AG-1::unableToFunction, P-1's is AG-2::requiredVotes).
            $this->assertDoesNotMatchRegularExpression(
                '/(constitutedSize|nonVacantCount|acceptCount|objectCount)\(\)\s*([*\/%+-]|[<>]=?|[!=]==?)/',
                $source,
                sprintf('Application computes with a domain count in %s — the aggregate owns its own arithmetic (G-5).', $file)
            );
        }
    }

    /**
     * Half 2 (behavioural, UC-2): at a Committee of FOUR the established rule
     * requires THREE Committee Votes (⌈2·4/3⌉ = 3 — EM-GOV-036/038(a)), while a
     * bare majority would require two. The onset therefore lands on the SECOND
     * vacancy under the established rule, and would land nowhere under a majority.
     */
    public function test_uc2_acts_on_the_established_denominator_not_a_derived_one(): void
    {
        $this->seedCommittee('s1', 's2', 's3', 's4');
        $gate = $this->seedGate(4);
        $this->assertSame(3, $gate->requiredVotes()->count(), 'Fixture: the ESTABLISHED constitutional value is three (EM-GOV-036/057).');

        $handler = $this->recordVacancyHandler();

        $this->instants->setNowEpoch(1_000);
        $handler->handle($this->vacancyCommand('s1'));

        $this->assertSame(
            [CommitteeSeatVacated::class],
            $this->protocol->eventClassSequence(),
            'Three non-vacant seats still meet the established requirement of three: no consequence exists.'
        );

        $this->instants->setNowEpoch(2_000);
        $handler->handle($this->vacancyCommand('s2', VacancyGround::LossOfEligibilityOrIndependence));

        $this->assertSame(
            [CommitteeSeatVacated::class, CommitteeSeatVacated::class, ElectionBecameInoperative::class, RecoveryPeriodStarted::class],
            $this->protocol->eventClassSequence(),
            'Two non-vacant seats fall short of the established three: the onset follows. A derived majority (two) '
            . 'would have produced no onset here — which is precisely what this pin forbids.'
        );

        /** @var ElectionBecameInoperative $onset */
        $onset = $this->protocol->entriesOfEvent(ElectionBecameInoperative::class)[0]->event;
        $this->assertSame(2_000, $onset->onset->epochSeconds, 'The onset is the causing event’s own instant (P-4).');
    }

    /**
     * Half 2 (behavioural, UC-3): the mirror image. Restoration must land where the
     * ESTABLISHED requirement is met again — at the third non-vacant seat of four,
     * never at the second (which a derived majority would have called sufficient,
     * making the election never inoperative and the restoration unrecordable).
     */
    public function test_uc3_acts_on_the_established_denominator_not_a_derived_one(): void
    {
        $committee = $this->seedCommittee('s1', 's2', 's3', 's4');
        $this->seedGate(4);
        $committee->recordVacancy($this->seat('s1'), VacancyGround::DeathOrPermanentIncapacity, null, $this->at(10));
        $committee->recordVacancy($this->seat('s2'), VacancyGround::DeathOrPermanentIncapacity, null, $this->at(20));
        $this->seedRecoveryProcess(PeriodKind::CommitteeRestoration, 20);

        $this->instants->setNowEpoch(100);
        $this->fillSeatHandler()->handle($this->fillCommand('s1'));

        $this->assertSame(1, $this->protocol->countEventsOf(ElectionRestored::class),
            'Three non-vacant seats meet the established requirement of three: the restoration is recorded. '
            . 'Under a derived majority the condition would never have been active, and nothing would be recorded here.'
        );

        $restoration = $this->recoveries->find($this->electionId(), PeriodKind::CommitteeRestoration);
        $this->assertSame(
            80,
            $restoration->readingAt($this->at(500))->elapsedSeconds,
            'The allowance stopped accruing at the recorded restoration (100), not at the reading (500) — the domain’s clock, not the application’s (DD-1).'
        );
    }
}
