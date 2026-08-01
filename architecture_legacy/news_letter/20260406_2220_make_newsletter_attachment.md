# Claude CLI Prompt: Add Attachments to Newsletter System

## 📋 Copy This Complete Prompt into Claude CLI

```markdown
## Context
I have a working newsletter system with:
- Create, Edit, Show, Index pages
- Rich text editor (TipTap)
- Queue-based email sending with Redis locks
- Kill switch for high failure rates
- Audit logging
- Unsubscribe with tokens

Now I need to add **file attachments** to newsletters. Users should be able to upload PDFs, images, or documents that get attached to the email sent to members.

## Requirements

### Functional Requirements
1. Attachments can be added to draft newsletters
2. Maximum 3 attachments per newsletter
3. Maximum file size: 10 MB per file
4. Supported formats: PDF, JPG, PNG, DOC, DOCX, XLS, XLSX
5. Attachments are stored privately (not publicly accessible)
6. Attachments are sent as email attachments using Laravel Mail's `attach()` method
7. Users can add/remove attachments on the Edit page
8. Attachments are listed on the Show page

### Technical Requirements
- Storage: `storage/app/private/newsletters/{newsletter_id}/`
- Serve attachments via signed URLs (temporary, 5-minute expiry)
- Clean up attachments when newsletter is deleted
- Clean up orphaned attachments (cron job)

## TDD Implementation

### Phase 1: Create Migration and Model (Write Test First)

**File:** `tests/Feature/Newsletter/NewsletterAttachmentTest.php`

```php
<?php

namespace Tests\Feature\Newsletter;

use App\Models\NewsletterAttachment;
use App\Models\OrganisationNewsletter;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NewsletterAttachmentTest extends TestCase
{
    use RefreshDatabase;

    private OrganisationNewsletter $newsletter;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create();
        $this->newsletter = OrganisationNewsletter::factory()->create([
            'status' => 'draft',
            'created_by' => $this->admin->id,
        ]);
    }

    /** @test */
    public function admin_can_upload_attachment_to_draft_newsletter()
    {
        Storage::fake('private');
        
        $file = UploadedFile::fake()->create('document.pdf', 1024);
        
        $response = $this->actingAs($this->admin)
            ->post(route('organisations.membership.newsletters.attachments.store', [
                $this->newsletter->organisation->slug,
                $this->newsletter->id,
            ]), ['attachment' => $file]);
        
        $response->assertOk();
        $this->assertDatabaseHas('newsletter_attachments', [
            'organisation_newsletter_id' => $this->newsletter->id,
            'original_name' => 'document.pdf',
            'mime_type' => 'application/pdf',
        ]);
    }

    /** @test */
    public function cannot_upload_attachment_to_non_draft_newsletter()
    {
        $this->newsletter->update(['status' => 'processing']);
        
        $file = UploadedFile::fake()->create('document.pdf', 1024);
        
        $response = $this->actingAs($this->admin)
            ->post(route('organisations.membership.newsletters.attachments.store', [
                $this->newsletter->organisation->slug,
                $this->newsletter->id,
            ]), ['attachment' => $file]);
        
        $response->assertStatus(422);
    }

    /** @test */
    public function max_3_attachments_per_newsletter()
    {
        Storage::fake('private');
        
        // Upload 3 attachments
        for ($i = 0; $i < 3; $i++) {
            $file = UploadedFile::fake()->create("file{$i}.pdf", 1024);
            $this->actingAs($this->admin)
                ->post(route('organisations.membership.newsletters.attachments.store', [
                    $this->newsletter->organisation->slug,
                    $this->newsletter->id,
                ]), ['attachment' => $file]);
        }
        
        // Try 4th
        $file4 = UploadedFile::fake()->create('file4.pdf', 1024);
        $response = $this->actingAs($this->admin)
            ->post(route('organisations.membership.newsletters.attachments.store', [
                $this->newsletter->organisation->slug,
                $this->newsletter->id,
            ]), ['attachment' => $file4]);
        
        $response->assertStatus(422);
        $this->assertEquals(3, NewsletterAttachment::count());
    }

    /** @test */
    public function file_size_limited_to_10mb()
    {
        Storage::fake('private');
        
        $file = UploadedFile::fake()->create('large.pdf', 11 * 1024); // 11MB
        
        $response = $this->actingAs($this->admin)
            ->post(route('organisations.membership.newsletters.attachments.store', [
                $this->newsletter->organisation->slug,
                $this->newsletter->id,
            ]), ['attachment' => $file]);
        
        $response->assertSessionHasErrors(['attachment']);
    }

    /** @test */
    public function admin_can_delete_attachment()
    {
        Storage::fake('private');
        
        $file = UploadedFile::fake()->create('document.pdf', 1024);
        $response = $this->actingAs($this->admin)
            ->post(route('organisations.membership.newsletters.attachments.store', [
                $this->newsletter->organisation->slug,
                $this->newsletter->id,
            ]), ['attachment' => $file]);
        
        $attachment = NewsletterAttachment::first();
        
        $this->actingAs($this->admin)
            ->delete(route('organisations.membership.newsletters.attachments.destroy', [
                $this->newsletter->organisation->slug,
                $this->newsletter->id,
                $attachment->id,
            ]))->assertRedirect();
        
        $this->assertSoftDeleted('newsletter_attachments', ['id' => $attachment->id]);
    }
}
```

### Phase 2: Create Migration

**File:** `database/migrations/2026_04_06_100000_create_newsletter_attachments_table.php`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('newsletter_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organisation_newsletter_id')->constrained('organisation_newsletters')->cascadeOnDelete();
            $table->string('original_name');
            $table->string('stored_path');
            $table->string('mime_type');
            $table->unsignedInteger('size');
            $table->uuid('uploaded_by');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('organisation_newsletter_id');
            $table->foreign('uploaded_by')->references('id')->on('users');
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('newsletter_attachments');
    }
};
```

### Phase 3: Create Model

**File:** `app/Models/NewsletterAttachment.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewsletterAttachment extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'organisation_newsletter_id',
        'original_name',
        'stored_path',
        'mime_type',
        'size',
        'uploaded_by',
    ];
    
    public function newsletter()
    {
        return $this->belongsTo(OrganisationNewsletter::class, 'organisation_newsletter_id');
    }
    
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
```

### Phase 4: Update OrganisationNewsletter Model

Add relationship:

```php
public function attachments()
{
    return $this->hasMany(NewsletterAttachment::class, 'organisation_newsletter_id');
}
```

### Phase 5: Add Controller Methods

**File:** `app/Http/Controllers/Membership/OrganisationNewsletterController.php`

Add:

```php
use App\Models\NewsletterAttachment;
use Illuminate\Support\Facades\Storage;

public function storeAttachment(Request $request, string $slug, int $id)
{
    $org = Organisation::where('slug', $slug)->firstOrFail();
    $this->authorizeAdmin($org, $request->user());
    
    $newsletter = OrganisationNewsletter::where('organisation_id', $org->id)
        ->where('status', 'draft')
        ->findOrFail($id);
    
    // Max 3 attachments
    if ($newsletter->attachments()->count() >= 3) {
        return response()->json(['error' => 'Maximum 3 attachments per newsletter'], 422);
    }
    
    $request->validate([
        'attachment' => 'required|file|max:10240|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx',
    ]);
    
    $file = $request->file('attachment');
    $path = $file->store("newsletters/{$newsletter->id}", 'private');
    
    $attachment = NewsletterAttachment::create([
        'organisation_newsletter_id' => $newsletter->id,
        'original_name' => $file->getClientOriginalName(),
        'stored_path' => $path,
        'mime_type' => $file->getMimeType(),
        'size' => $file->getSize(),
        'uploaded_by' => $request->user()->id,
    ]);
    
    return response()->json($attachment);
}

public function destroyAttachment(Request $request, string $slug, int $id, int $attachmentId)
{
    $org = Organisation::where('slug', $slug)->firstOrFail();
    $this->authorizeAdmin($org, $request->user());
    
    $attachment = NewsletterAttachment::where('organisation_newsletter_id', $id)->findOrFail($attachmentId);
    
    Storage::disk('private')->delete($attachment->stored_path);
    $attachment->delete();
    
    return back()->with('success', 'Attachment removed.');
}
```

### Phase 6: Update Mailable

**File:** `app/Mail/OrganisationNewsletterMail.php`

```php
public function __construct(
    public readonly OrganisationNewsletter $newsletter,
    public readonly NewsletterRecipient $recipient
) {}

public function build()
{
    $mail = $this->from(...)->subject($this->newsletter->subject)
        ->html($this->newsletter->html_content);
    
    // Attach files
    foreach ($this->newsletter->attachments as $attachment) {
        $mail->attach(Storage::disk('private')->path($attachment->stored_path), [
            'as' => $attachment->original_name,
            'mime' => $attachment->mime_type,
        ]);
    }
    
    return $mail;
}
```

### Phase 7: Add Routes

In `routes/organisations.php` inside the newsletters group:

```php
Route::post('/{newsletter}/attachments', [OrganisationNewsletterController::class, 'storeAttachment'])
    ->name('attachments.store');
Route::delete('/{newsletter}/attachments/{attachment}', [OrganisationNewsletterController::class, 'destroyAttachment'])
    ->name('attachments.destroy');
```

### Phase 8: Update Vue Components

Add attachment upload UI to `Edit.vue` (and optionally to `Show.vue` for viewing):

```vue
<!-- Attachment upload section -->
<div class="mt-6">
    <label class="block text-sm font-semibold text-slate-700 mb-2">Attachments (max 3, up to 10MB each)</label>
    
    <!-- Existing attachments list -->
    <div v-if="attachments.length" class="mb-3 space-y-2">
        <div v-for="att in attachments" :key="att.id" 
             class="flex items-center justify-between bg-slate-50 rounded-lg px-3 py-2 text-sm">
            <div class="flex items-center gap-2">
                <PaperClipIcon class="w-4 h-4 text-slate-400" />
                <span>{{ att.original_name }}</span>
                <span class="text-xs text-slate-400">({{ formatFileSize(att.size) }})</span>
            </div>
            <button @click="removeAttachment(att.id)" 
                    class="text-red-500 hover:text-red-700">
                <TrashIcon class="w-4 h-4" />
            </button>
        </div>
    </div>
    
    <!-- Upload new attachment -->
    <div v-if="attachments.length < 3" class="border-2 border-dashed border-slate-300 rounded-lg p-4 text-center">
        <input type="file" ref="fileInput" @change="uploadAttachment" class="hidden" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx" />
        <button type="button" @click="$refs.fileInput.click()"
                class="text-sm text-purple-600 hover:text-purple-700">
            <PlusIcon class="w-4 h-4 inline mr-1" />
            Add attachment
        </button>
        <p class="text-xs text-slate-400 mt-1">PDF, images, Word, Excel — up to 10MB</p>
    </div>
</div>
```

## Execution Order

```bash
# 1. Run tests (RED)
php artisan test tests/Feature/Newsletter/NewsletterAttachmentTest.php --no-coverage

# 2. Create migration and run
php artisan migrate

# 3. Create model and relationships

# 4. Add controller methods

# 5. Update mailable

# 6. Add routes

# 7. Update Vue components

# 8. Run tests (GREEN)
php artisan test tests/Feature/Newsletter/NewsletterAttachmentTest.php --no-coverage
```

## Success Criteria

- [ ] Users can upload attachments to draft newsletters
- [ ] Max 3 attachments, 10MB each
- [ ] Attachments appear in sent emails
- [ ] Attachments can be deleted
- [ ] Attachments are cleaned up when newsletter is deleted
- [ ] Storage is private (not publicly accessible)
- [ ] All tests pass

Proceed with TDD implementation.
```

---

## Summary

This prompt will make Claude:

1. **Write 5 tests** for attachment functionality
2. **Create migration** for `newsletter_attachments` table
3. **Create model** with relationships
4. **Add controller methods** for upload/delete
5. **Update mailable** to attach files to emails
6. **Add routes** for attachment endpoints
7. **Update Vue components** with upload UI

**Estimated time:** 2-3 hours of Claude-assisted development

Copy the prompt into Claude CLI to implement newsletter attachments! 🚀