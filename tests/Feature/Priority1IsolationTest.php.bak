<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organisation;
use App\Models\Message;
use App\Models\Image;
use App\Models\Upload;
use App\Models\Calendar;
use App\Models\Event;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;

class Priority1IsolationTest extends TestCase
{
    use RefreshDatabase;

    protected Organisation $orgA;
    protected Organisation $orgB;
    protected User $userA;
    protected User $userB;

    protected function setUp(): void
    {
        parent::setUp();

        // Fake all jobs - Calendar creates SynchronizeGoogleEvents job that requires data not in tests
        Bus::fake();

        // Create two tenant organisations
        $this->orgA = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'org-a']);
        $this->orgB = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'org-b']);

        // Create users in different orgs using UserOrganisationRole model
        $this->userA = User::factory()->create(['name' => 'User A']);
        UserOrganisationRole::create([
            'user_id' => $this->userA->id,
            'organisation_id' => $this->orgA->id,
            'role' => 'admin',
        ]);

        $this->userB = User::factory()->create(['name' => 'User B']);
        UserOrganisationRole::create([
            'user_id' => $this->userB->id,
            'organisation_id' => $this->orgB->id,
            'role' => 'admin',
        ]);
    }

    // ============= MESSAGE TESTS =============

    /** @test */
    public function test_messages_are_scoped_to_organisation()
    {
        session(['current_organisation_id' => $this->orgA->id]);

        $messageA = Message::factory()->create(['organisation_id' => $this->orgA->id]);
        $messageB = Message::factory()->create(['organisation_id' => $this->orgB->id]);

        $messages = Message::all();

        $this->assertCount(1, $messages);
        $this->assertEquals($messageA->id, $messages->first()->id);
    }

    /** @test */
    public function test_message_find_returns_null_for_other_org()
    {
        session(['current_organisation_id' => $this->orgA->id]);
        $messageB = Message::factory()->create(['organisation_id' => $this->orgB->id]);

        $found = Message::find($messageB->id);

        $this->assertNull($found);
    }

    /** @test */
    public function test_message_creation_auto_fills_organisation_id()
    {
        session(['current_organisation_id' => $this->orgA->id]);

        $message = Message::create([
            'from' => 'user@example.com',
            'to' => 'recipient@example.com',
            'message' => 'Test message',
            'code' => 'TEST-CODE',
        ]);

        $this->assertEquals($this->orgA->id, $message->organisation_id);
    }

    // ============= IMAGE TESTS =============

    /** @test */
    public function test_images_are_scoped_to_organisation()
    {
        session(['current_organisation_id' => $this->orgA->id]);

        $imageA = Image::factory()->create(['organisation_id' => $this->orgA->id]);
        $imageB = Image::factory()->create(['organisation_id' => $this->orgB->id]);

        $images = Image::all();

        $this->assertCount(1, $images);
        $this->assertEquals($imageA->id, $images->first()->id);
    }

    /** @test */
    public function test_image_find_returns_null_for_other_org()
    {
        session(['current_organisation_id' => $this->orgA->id]);
        $imageB = Image::factory()->create(['organisation_id' => $this->orgB->id]);

        $found = Image::find($imageB->id);

        $this->assertNull($found);
    }

    /** @test */
    public function test_image_creation_auto_fills_organisation_id()
    {
        session(['current_organisation_id' => $this->orgA->id]);

        $image = Image::create();

        $this->assertEquals($this->orgA->id, $image->organisation_id);
    }

    // ============= UPLOAD TESTS =============

    /** @test */
    public function test_uploads_are_scoped_to_organisation()
    {
        session(['current_organisation_id' => $this->orgA->id]);

        $uploadA = Upload::factory()->create(['organisation_id' => $this->orgA->id]);
        $uploadB = Upload::factory()->create(['organisation_id' => $this->orgB->id]);

        $uploads = Upload::all();

        $this->assertCount(1, $uploads);
        $this->assertEquals($uploadA->id, $uploads->first()->id);
    }

    /** @test */
    public function test_upload_find_returns_null_for_other_org()
    {
        session(['current_organisation_id' => $this->orgA->id]);
        $uploadB = Upload::factory()->create(['organisation_id' => $this->orgB->id]);

        $found = Upload::find($uploadB->id);

        $this->assertNull($found);
    }

    /** @test */
    public function test_upload_creation_auto_fills_organisation_id()
    {
        session(['current_organisation_id' => $this->orgA->id]);

        $upload = Upload::create(['filename' => 'test.pdf']);

        $this->assertEquals($this->orgA->id, $upload->organisation_id);
    }

    // ============= CALENDAR TESTS =============

    /** @test */
    public function test_calendars_are_scoped_to_organisation()
    {
        session(['current_organisation_id' => $this->orgA->id]);

        $calendarA = Calendar::factory()->create(['organisation_id' => $this->orgA->id]);
        $calendarB = Calendar::factory()->create(['organisation_id' => $this->orgB->id]);

        $calendars = Calendar::all();

        $this->assertCount(1, $calendars);
        $this->assertEquals($calendarA->id, $calendars->first()->id);
    }

    /** @test */
    public function test_calendar_find_returns_null_for_other_org()
    {
        session(['current_organisation_id' => $this->orgA->id]);
        $calendarB = Calendar::factory()->create(['organisation_id' => $this->orgB->id]);

        $found = Calendar::find($calendarB->id);

        $this->assertNull($found);
    }

    /** @test */
    public function test_calendar_creation_auto_fills_organisation_id()
    {
        session(['current_organisation_id' => $this->orgA->id]);

        $calendar = Calendar::create([
            'google_id' => 'test-google-id',
            'name' => 'Test Calendar',
        ]);

        $this->assertEquals($this->orgA->id, $calendar->organisation_id);
    }

    // ============= EVENT TESTS =============

    /** @test */
    public function test_events_are_scoped_to_organisation()
    {
        session(['current_organisation_id' => $this->orgA->id]);

        $eventA = Event::factory()->create(['organisation_id' => $this->orgA->id]);
        $eventB = Event::factory()->create(['organisation_id' => $this->orgB->id]);

        $events = Event::all();

        $this->assertCount(1, $events);
        $this->assertEquals($eventA->id, $events->first()->id);
    }

    /** @test */
    public function test_event_find_returns_null_for_other_org()
    {
        session(['current_organisation_id' => $this->orgA->id]);
        $eventB = Event::factory()->create(['organisation_id' => $this->orgB->id]);

        $found = Event::find($eventB->id);

        $this->assertNull($found);
    }

    /** @test */
    public function test_event_creation_auto_fills_organisation_id()
    {
        session(['current_organisation_id' => $this->orgA->id]);

        $event = Event::create([
            'google_id' => 'test-google-id',
            'name' => 'Test Event',
        ]);

        $this->assertEquals($this->orgA->id, $event->organisation_id);
    }

    // ============= RELATIONSHIP TESTS =============

    /** @test */
    public function test_event_calendar_relationship_respects_isolation()
    {
        session(['current_organisation_id' => $this->orgA->id]);

        $calendarA = Calendar::factory()->create(['organisation_id' => $this->orgA->id]);
        $eventA = Event::factory()->create([
            'calendar_id' => $calendarA->id,
            'organisation_id' => $this->orgA->id,
        ]);

        // Load relationship - should not throw error
        $calendar = $eventA->calendar;
        $this->assertNotNull($calendar);
        $this->assertEquals($calendarA->id, $calendar->id);
    }

    /** @test */
    public function test_multiple_org_isolation_verified()
    {
        // Create data in org A
        session(['current_organisation_id' => $this->orgA->id]);
        $msgA = Message::factory()->create(['organisation_id' => $this->orgA->id]);
        $imgA = Image::factory()->create(['organisation_id' => $this->orgA->id]);
        $uploadA = Upload::factory()->create(['organisation_id' => $this->orgA->id]);
        $calendarA = Calendar::factory()->create(['organisation_id' => $this->orgA->id]);
        $eventA = Event::factory()->create(['organisation_id' => $this->orgA->id]);

        // Create data in org B
        session(['current_organisation_id' => $this->orgB->id]);
        $msgB = Message::factory()->create(['organisation_id' => $this->orgB->id]);
        $imgB = Image::factory()->create(['organisation_id' => $this->orgB->id]);
        $uploadB = Upload::factory()->create(['organisation_id' => $this->orgB->id]);
        $calendarB = Calendar::factory()->create(['organisation_id' => $this->orgB->id]);
        $eventB = Event::factory()->create(['organisation_id' => $this->orgB->id]);

        // Verify org B can only see their data
        $this->assertCount(1, Message::all());
        $this->assertCount(1, Image::all());
        $this->assertCount(1, Upload::all());
        $this->assertCount(1, Calendar::all());
        $this->assertCount(1, Event::all());

        // Switch back to org A - verify isolation
        session(['current_organisation_id' => $this->orgA->id]);
        $this->assertCount(1, Message::all());
        $this->assertCount(1, Image::all());
        $this->assertCount(1, Upload::all());
        $this->assertCount(1, Calendar::all());
        $this->assertCount(1, Event::all());
    }
}
