<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\ReceiptCode;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * CSV download for the committee-only receipt-codes page
 * (VotingReceiptController::index() / resources/js/Pages/Election/ReceiptCodes.vue).
 *
 * Same authorization boundary as the page itself (ElectionPolicy::viewResults,
 * results_published gate) — a download must never be reachable by anyone who
 * couldn't already see the page.
 */
class VotingReceiptExportTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private Organisation $organisation;
    private Election $election;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create(['type' => 'tenant']);
        $this->election = Election::factory()->create([
            'organisation_id' => $this->organisation->id,
            'type' => 'real',
        ]);

        $this->user = User::factory()->forOrganisation($this->organisation)->create();

        UserOrganisationRole::firstOrCreate(
            ['user_id' => $this->user->id, 'organisation_id' => $this->organisation->id],
            ['role' => 'member']
        );

        ElectionOfficer::create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'role' => 'chief',
            'status' => 'active',
            'appointed_by' => $this->user->id,
            'appointed_at' => now(),
            'accepted_at' => now(),
        ]);
    }

    private function publishResults(): void
    {
        $this->election->update(['results_published_at' => now(), 'results_published' => true]);
    }

    private function downloadUrl(): string
    {
        return route('organisations.election.receipt-codes.download', [
            'organisation' => $this->organisation->slug,
            'election' => $this->election->slug,
        ]);
    }

    private function createCodes(int $count): array
    {
        $codes = [];
        for ($i = 1; $i <= $count; $i++) {
            $code = Str::random(32) . '_' . Str::uuid();
            $codes[] = $code;
            ReceiptCode::create([
                'election_id' => $this->election->id,
                'receipt_code' => $code,
            ]);
        }

        return $codes;
    }

    private function parseCsv(string $body): array
    {
        // Strip UTF-8 BOM before parsing, mirroring the export itself.
        $body = preg_replace('/^\xEF\xBB\xBF/', '', $body);
        $lines = array_filter(explode("\n", str_replace("\r\n", "\n", trim($body))));

        return array_map('str_getcsv', $lines);
    }

    public function test_download_is_forbidden_before_results_published(): void
    {
        $this->actingAs($this->user);

        $response = $this->get($this->downloadUrl());

        $response->assertStatus(403);
    }

    public function test_download_is_forbidden_for_non_committee_member(): void
    {
        $this->publishResults();

        $nonOfficer = User::factory()->forOrganisation($this->organisation)->create();
        UserOrganisationRole::firstOrCreate(
            ['user_id' => $nonOfficer->id, 'organisation_id' => $this->organisation->id],
            ['role' => 'member']
        );

        $response = $this->actingAs($nonOfficer)->get($this->downloadUrl());

        $response->assertStatus(403);
    }

    public function test_download_returns_csv_with_correct_headers(): void
    {
        $this->publishResults();
        $this->actingAs($this->user);

        $response = $this->get($this->downloadUrl());

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('attachment', $response->headers->get('Content-Disposition'));
        $this->assertStringContainsString('.csv', $response->headers->get('Content-Disposition'));
    }

    public function test_download_csv_has_only_serial_and_receipt_code_columns(): void
    {
        // No Status column: election receipt exports must not carry
        // negative-sounding wording ("Not Verified") that could read as
        // casting doubt on the election's credibility.
        $this->publishResults();
        $this->actingAs($this->user);
        $this->createCodes(3);

        $rows = $this->parseCsv($this->get($this->downloadUrl())->streamedContent());

        $this->assertSame(['#', 'Receipt Code'], $rows[0]);
        $this->assertCount(4, $rows); // header + 3 data rows
        $this->assertCount(2, $rows[1], 'Each data row must have exactly two columns.');
    }

    public function test_download_csv_serials_are_contiguous_and_all_codes_present(): void
    {
        $this->publishResults();
        $this->actingAs($this->user);
        $codes = $this->createCodes(6);

        $rows = $this->parseCsv($this->get($this->downloadUrl())->streamedContent());
        $dataRows = array_slice($rows, 1);

        $this->assertEqualsCanonicalizing($codes, array_column($dataRows, 1));
        $this->assertSame(['1', '2', '3', '4', '5', '6'], array_column($dataRows, 0));
    }

    /**
     * The user's explicit requirement: the download's row order (and therefore
     * its serial numbers) must not match the codes' storage order — each
     * download re-shuffles independently, exactly like the on-screen list
     * already does on every page load. With 20 codes, a same-order coincidence
     * has probability 1/20! — not exactly zero, but negligible for a test.
     */
    public function test_download_csv_order_does_not_match_storage_order(): void
    {
        $this->publishResults();
        $this->actingAs($this->user);
        $codes = $this->createCodes(20);

        $rows = $this->parseCsv($this->get($this->downloadUrl())->streamedContent());
        $downloadedOrder = array_column(array_slice($rows, 1), 1);

        $this->assertNotSame($codes, $downloadedOrder, 'Download order must not match storage/insertion order.');
    }

    public function test_download_shuffles_independently_on_each_request(): void
    {
        $this->publishResults();
        $this->actingAs($this->user);
        $this->createCodes(20);

        $first = array_column(array_slice($this->parseCsv($this->get($this->downloadUrl())->streamedContent()), 1), 1);
        $second = array_column(array_slice($this->parseCsv($this->get($this->downloadUrl())->streamedContent()), 1), 1);

        $this->assertNotSame($first, $second, 'Two separate downloads must not produce the same order.');
    }
}
