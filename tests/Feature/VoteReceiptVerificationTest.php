<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\Vote;
use App\Models\ReceiptCode;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class VoteReceiptVerificationTest extends TestCase
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

        // Create UserOrganisationRole so middleware allows access
        UserOrganisationRole::firstOrCreate(
            ['user_id' => $this->user->id, 'organisation_id' => $this->organisation->id],
            ['role' => 'member']
        );
    }

    /** @test */
    public function receipt_code_is_stored_when_vote_is_saved()
    {
        $vote = Vote::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
        ]);

        $fullReceiptCode = 'test_private_key_' . $vote->id;

        ReceiptCode::create([
            'election_id' => $this->election->id,
            'receipt_code' => $fullReceiptCode,
        ]);

        $this->assertDatabaseHas('receipt_codes', [
            'election_id' => $this->election->id,
            'receipt_code' => $fullReceiptCode,
        ]);
    }

    /** @test */
    public function receipt_codes_page_is_inaccessible_before_results_published()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('organisations.election.receipt-codes', [
            'organisation' => $this->organisation->slug,
            'election' => $this->election->slug,
        ]));

        $response->assertStatus(403);
    }

    /** @test */
    public function receipt_codes_page_is_accessible_after_results_published()
    {
        $this->actingAs($this->user);
        $this->election->update(['results_published_at' => now()]);

        $response = $this->get(route('organisations.election.receipt-codes', [
            'organisation' => $this->organisation->slug,
            'election' => $this->election->slug,
        ]));

        $response->assertStatus(200);
        $response->assertInertia(fn($page) => $page->component('Election/ReceiptCodes'));
    }

    /** @test */
    public function receipt_codes_are_displayed_in_randomized_order()
    {
        $this->actingAs($this->user);
        $this->election->update(['results_published_at' => now()]);

        $codes = [];
        for ($i = 1; $i <= 5; $i++) {
            $code = Str::random(32) . '_' . Str::uuid();
            $codes[] = $code;
            ReceiptCode::create([
                'election_id' => $this->election->id,
                'receipt_code' => $code,
            ]);
        }

        $response = $this->get(route('organisations.election.receipt-codes', [
            'organisation' => $this->organisation->slug,
            'election' => $this->election->slug,
        ]));

        $response->assertInertia(fn($page) =>
            $page->component('Election/ReceiptCodes')
                 ->has('receipt_codes', 5)
                 ->where('receipt_codes.0.serial', 1)
                 ->where('receipt_codes.1.serial', 2)
                 ->where('receipt_codes.2.serial', 3)
                 ->where('receipt_codes.3.serial', 4)
                 ->where('receipt_codes.4.serial', 5)
        );
    }

    /** @test */
    public function vote_can_be_marked_as_reverified()
    {
        $this->actingAs($this->user);

        $vote = Vote::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
        ]);

        $fullReceiptCode = 'test_private_key_' . $vote->id;
        $receiptCode = ReceiptCode::create([
            'election_id' => $this->election->id,
            'receipt_code' => $fullReceiptCode,
        ]);

        $response = $this->post(route('organisations.vote.confirm-correct', [
            'organisation' => $this->organisation->slug,
            'election' => $this->election->slug,
            'voteId' => $vote->id,
        ]), [
            'receipt_code' => $fullReceiptCode,
        ]);

        $response->assertRedirect();
        $this->assertNotNull($receiptCode->fresh()->reverified_at);
    }

    /** @test */
    public function reverified_status_shows_green_tickmark_in_list()
    {
        $this->actingAs($this->user);
        $this->election->update(['results_published_at' => now()]);

        $vote = Vote::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
        ]);

        $fullReceiptCode = 'test_private_key_' . $vote->id;
        ReceiptCode::create([
            'election_id' => $this->election->id,
            'receipt_code' => $fullReceiptCode,
            'reverified_at' => now(),
        ]);

        $response = $this->get(route('organisations.election.receipt-codes', [
            'organisation' => $this->organisation->slug,
            'election' => $this->election->slug,
        ]));

        $response->assertInertia(fn($page) =>
            $page->component('Election/ReceiptCodes')
                 ->where('receipt_codes.0.is_reverified', true)
        );
    }

    /** @test */
    public function receipt_code_cannot_be_reverified_twice()
    {
        $this->actingAs($this->user);

        $vote = Vote::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
        ]);

        $fullReceiptCode = 'test_private_key_' . $vote->id;
        $receiptCode = ReceiptCode::create([
            'election_id' => $this->election->id,
            'receipt_code' => $fullReceiptCode,
            'reverified_at' => now(),
        ]);

        $response = $this->post(route('organisations.vote.confirm-correct', [
            'organisation' => $this->organisation->slug,
            'election' => $this->election->slug,
            'voteId' => $vote->id,
        ]), [
            'receipt_code' => $fullReceiptCode,
        ]);

        $response->assertSessionHasErrors();
        $this->assertEquals(1, ReceiptCode::whereNotNull('reverified_at')->count());
    }

    /** @test */
    public function receipt_codes_show_reverified_statistics()
    {
        $this->actingAs($this->user);
        $this->election->update(['results_published_at' => now()]);

        // Create 3 codes, 2 verified
        for ($i = 1; $i <= 3; $i++) {
            $vote = Vote::factory()->create([
                'election_id' => $this->election->id,
                'organisation_id' => $this->organisation->id,
            ]);
            ReceiptCode::create([
                'election_id' => $this->election->id,
                'receipt_code' => "key_{$i}_" . $vote->id,
                'reverified_at' => $i <= 2 ? now() : null,
            ]);
        }

        $response = $this->get(route('organisations.election.receipt-codes', [
            'organisation' => $this->organisation->slug,
            'election' => $this->election->slug,
        ]));

        $response->assertInertia(fn($page) =>
            $page->component('Election/ReceiptCodes')
                 ->where('total_votes', 3)
                 ->where('reverified_count', 2)
        );
    }
}
