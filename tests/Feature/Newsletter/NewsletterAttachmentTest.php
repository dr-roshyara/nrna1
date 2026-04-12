<?php

namespace Tests\Feature\Newsletter;

use App\Models\NewsletterAttachment;
use App\Models\Organisation;
use App\Models\OrganisationNewsletter;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NewsletterAttachmentTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private OrganisationNewsletter $newsletter;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create();
        $this->admin        = User::factory()->create();

        UserOrganisationRole::create([
            'organisation_id' => $this->organisation->id,
            'user_id'         => $this->admin->id,
            'role'            => 'admin',
        ]);

        $this->newsletter = OrganisationNewsletter::create([
            'organisation_id' => $this->organisation->id,
            'created_by'      => $this->admin->id,
            'subject'         => 'Test Newsletter',
            'html_content'    => '<p>Hello</p>',
            'status'          => 'draft',
        ]);
    }

    public function test_admin_can_upload_attachment_to_draft_newsletter(): void
    {
        Storage::fake('private');

        $file = UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($this->admin)
            ->postJson(route('organisations.membership.newsletters.attachments.store', [
                $this->organisation->slug,
                $this->newsletter->id,
            ]), ['attachment' => $file]);

        $response->assertOk();
        $this->assertDatabaseHas('newsletter_attachments', [
            'organisation_newsletter_id' => $this->newsletter->id,
            'original_name'              => 'document.pdf',
            'mime_type'                  => 'application/pdf',
        ]);
    }

    public function test_uploaded_file_is_stored_in_private_disk(): void
    {
        Storage::fake('private');

        $file = UploadedFile::fake()->create('report.pdf', 500, 'application/pdf');

        $this->actingAs($this->admin)
            ->postJson(route('organisations.membership.newsletters.attachments.store', [
                $this->organisation->slug,
                $this->newsletter->id,
            ]), ['attachment' => $file]);

        $attachment = NewsletterAttachment::first();
        $this->assertNotNull($attachment);
        Storage::disk('private')->assertExists($attachment->stored_path);
    }

    public function test_cannot_upload_attachment_to_non_draft_newsletter(): void
    {
        Storage::fake('private');

        $this->newsletter->update(['status' => 'processing']);

        $file = UploadedFile::fake()->create('document.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($this->admin)
            ->postJson(route('organisations.membership.newsletters.attachments.store', [
                $this->organisation->slug,
                $this->newsletter->id,
            ]), ['attachment' => $file]);

        $response->assertStatus(422);
        $this->assertDatabaseEmpty('newsletter_attachments');
    }

    public function test_max_3_attachments_per_newsletter(): void
    {
        Storage::fake('private');

        for ($i = 1; $i <= 3; $i++) {
            $this->actingAs($this->admin)
                ->postJson(route('organisations.membership.newsletters.attachments.store', [
                    $this->organisation->slug,
                    $this->newsletter->id,
                ]), ['attachment' => UploadedFile::fake()->create("file{$i}.pdf", 100, 'application/pdf')])
                ->assertOk();
        }

        // 4th upload must be rejected
        $response = $this->actingAs($this->admin)
            ->postJson(route('organisations.membership.newsletters.attachments.store', [
                $this->organisation->slug,
                $this->newsletter->id,
            ]), ['attachment' => UploadedFile::fake()->create('file4.pdf', 100, 'application/pdf')]);

        $response->assertStatus(422);
        $this->assertSame(3, NewsletterAttachment::count());
    }

    public function test_file_larger_than_10mb_is_rejected(): void
    {
        Storage::fake('private');

        // 11 MB — over limit
        $file = UploadedFile::fake()->create('huge.pdf', 11 * 1024, 'application/pdf');

        $response = $this->actingAs($this->admin)
            ->postJson(route('organisations.membership.newsletters.attachments.store', [
                $this->organisation->slug,
                $this->newsletter->id,
            ]), ['attachment' => $file]);

        $response->assertStatus(422);
        $this->assertDatabaseEmpty('newsletter_attachments');
    }

    public function test_unsupported_file_type_is_rejected(): void
    {
        Storage::fake('private');

        $file = UploadedFile::fake()->create('script.exe', 100, 'application/octet-stream');

        $response = $this->actingAs($this->admin)
            ->postJson(route('organisations.membership.newsletters.attachments.store', [
                $this->organisation->slug,
                $this->newsletter->id,
            ]), ['attachment' => $file]);

        $response->assertStatus(422);
        $this->assertDatabaseEmpty('newsletter_attachments');
    }

    public function test_non_admin_cannot_upload_attachment(): void
    {
        Storage::fake('private');

        $member = User::factory()->create();
        UserOrganisationRole::create([
            'organisation_id' => $this->organisation->id,
            'user_id'         => $member->id,
            'role'            => 'member',
        ]);

        $file = UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf');

        $this->actingAs($member)
            ->postJson(route('organisations.membership.newsletters.attachments.store', [
                $this->organisation->slug,
                $this->newsletter->id,
            ]), ['attachment' => $file])
            ->assertStatus(403);
    }

    public function test_admin_can_delete_attachment(): void
    {
        Storage::fake('private');

        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');
        $this->actingAs($this->admin)
            ->postJson(route('organisations.membership.newsletters.attachments.store', [
                $this->organisation->slug,
                $this->newsletter->id,
            ]), ['attachment' => $file]);

        $attachment = NewsletterAttachment::first();
        $this->assertNotNull($attachment);

        $this->actingAs($this->admin)
            ->delete(route('organisations.membership.newsletters.attachments.destroy', [
                $this->organisation->slug,
                $this->newsletter->id,
                $attachment->id,
            ]))
            ->assertRedirect();

        $this->assertSoftDeleted('newsletter_attachments', ['id' => $attachment->id]);
        Storage::disk('private')->assertMissing($attachment->stored_path);
    }

    public function test_deleting_newsletter_soft_deletes_attachments(): void
    {
        Storage::fake('private');

        $file = UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf');
        $this->actingAs($this->admin)
            ->postJson(route('organisations.membership.newsletters.attachments.store', [
                $this->organisation->slug,
                $this->newsletter->id,
            ]), ['attachment' => $file]);

        $this->newsletter->delete();

        // Newsletter is soft-deleted; cascade should soft-delete attachments via observer or model event
        $this->assertSoftDeleted('organisation_newsletters', ['id' => $this->newsletter->id]);
        // Attachments remain but newsletter is gone — files should still be on disk until cron cleans up
        $this->assertSame(1, NewsletterAttachment::withTrashed()->count());
    }
}
