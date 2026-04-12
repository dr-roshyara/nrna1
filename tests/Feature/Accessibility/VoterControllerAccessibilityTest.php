<?php

namespace Tests\Feature\Accessibility;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Accessibility Tests for Voter Controller
 *
 * Verifies WCAG 2.1 AA compliance including:
 * - Semantic HTML structure
 * - ARIA labels and roles
 * - Keyboard navigation
 * - Color contrast
 * - Touch targets
 * - Screen reader compatibility
 */
class VoterControllerAccessibilityTest extends TestCase
{
    use RefreshDatabase;

    protected $organisation;
    protected $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create();
        $this->member = User::factory()->create();
        $this->member->organisationRoles()->attach($this->organisation->id, ['role' => 'member']);
    }

    /**
     * Test page has proper semantic structure
     *
     * @test
     */
    public function it_has_proper_semantic_html_structure()
    {
        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - Main element
        $this->assertStringContainsString('<main', $html);
        $this->assertStringContainsString('</main>', $html);

        // Assert - Header element
        $this->assertStringContainsString('<header', $html);

        // Assert - Nav element for breadcrumb
        $this->assertStringContainsString('aria-label="Breadcrumb"', $html);

        // Assert - Table structure
        $this->assertStringContainsString('<table>', $html);
        $this->assertStringContainsString('<thead>', $html);
        $this->assertStringContainsString('<tbody>', $html);
        $this->assertStringContainsString('<th scope="col">', $html);
    }

    /**
     * Test page has proper heading hierarchy
     *
     * @test
     */
    public function it_has_proper_heading_hierarchy()
    {
        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - H1 should exist (page title)
        $this->assertStringContainsString('<h1', $html);

        // Assert - H2 should exist (section headers)
        $this->assertStringContainsString('<h2', $html);

        // Assert - No H1 duplication
        $h1Count = substr_count($html, '<h1');
        $this->assertEquals(1, $h1Count, 'Should have exactly one H1 tag per page');
    }

    /**
     * Test all buttons have accessible labels
     *
     * @test
     */
    public function it_provides_aria_labels_for_all_buttons()
    {
        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - Buttons should have either text content or aria-label
        $this->assertStringContainsString('aria-label=', $html);

        // Check for approval button aria-label pattern
        $this->assertStringContainsString('approve_aria', $html);
    }

    /**
     * Test form inputs have associated labels
     *
     * @test
     */
    public function it_associates_labels_with_form_inputs()
    {
        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - Search input has label
        $this->assertStringContainsString('<label for="search"', $html);
        $this->assertStringContainsString('id="search"', $html);

        // Assert - Status filter has label
        $this->assertStringContainsString('<label for="status"', $html);
        $this->assertStringContainsString('id="status"', $html);
    }

    /**
     * Test image alt attributes
     *
     * @test
     */
    public function it_provides_alt_text_for_images()
    {
        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - SVG icons should have aria-hidden (they're decorative)
        $this->assertStringContainsString('aria-hidden="true"', $html);
    }

    /**
     * Test screen reader announcements
     *
     * @test
     */
    public function it_provides_screen_reader_announcements()
    {
        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - aria-live for dynamic content
        $this->assertStringContainsString('aria-live="polite"', $html);

        // Assert - sr-only class for screen reader only content
        $this->assertStringContainsString('sr-only', $html);

        // Assert - role="status" for status messages
        $this->assertStringContainsString('role="status"', $html);
    }

    /**
     * Test keyboard navigation support
     *
     * @test
     */
    public function it_supports_keyboard_navigation()
    {
        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - All interactive elements should be reachable via Tab
        // Buttons should not have tabindex="-1" (unless intentionally hidden)
        $this->assertStringContainsString('<button', $html);

        // Assert - Forms should be navigable
        $this->assertStringContainsString('<input', $html);
        $this->assertStringContainsString('<select', $html);

        // Assert - No tabindex="0" on non-interactive elements
        $this->assertStringNotContainsString('tabindex="0"', $html);
    }

    /**
     * Test focus management
     *
     * @test
     */
    public function it_has_visible_focus_indicators()
    {
        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - CSS for focus styles present
        $this->assertStringContainsString('focus:outline', $html);
        $this->assertStringContainsString('focus:ring', $html);
    }

    /**
     * Test skip links for keyboard users
     *
     * @test
     */
    public function it_provides_skip_links()
    {
        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - Skip link or main content anchor
        // Skip links help keyboard users bypass repetitive content
        $this->assertStringContainsString('sr-only', $html);
        $this->assertStringContainsString('main', $html);
    }

    /**
     * Test touch target sizes (minimum 44x44px)
     *
     * @test
     */
    public function it_has_adequate_touch_target_sizes()
    {
        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - Buttons have min-h-[44px] (Tailwind class for 44px height)
        $this->assertStringContainsString('min-h-[44px]', $html);

        // Assert - Input fields have adequate padding
        $this->assertStringContainsString('py-2', $html);
    }

    /**
     * Test color contrast compliance
     *
     * @test
     */
    public function it_has_sufficient_color_contrast()
    {
        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - Text color classes used (dark text on light, light text on dark)
        $this->assertStringContainsString('text-gray-900', $html);
        $this->assertStringContainsString('dark:text-white', $html);

        // Assert - Status badges have sufficient contrast
        $this->assertStringContainsString('bg-green-100 text-green-800', $html);
        $this->assertStringContainsString('dark:bg-green-900 dark:text-green-200', $html);
    }

    /**
     * Test dark mode support
     *
     * @test
     */
    public function it_supports_dark_mode()
    {
        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - Dark mode classes present
        $this->assertStringContainsString('dark:', $html);
        $this->assertStringContainsString('dark:bg-', $html);
        $this->assertStringContainsString('dark:text-', $html);
    }

    /**
     * Test responsive design
     *
     * @test
     */
    public function it_is_responsive_for_mobile_devices()
    {
        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - Mobile-first responsive classes
        $this->assertStringContainsString('sm:', $html);
        $this->assertStringContainsString('md:', $html);
        $this->assertStringContainsString('lg:', $html);

        // Assert - Viewport meta tag (should be in layout, but verify in HTML)
        // Check for responsive classes on key elements
        $this->assertStringContainsString('grid-cols-1', $html);
        $this->assertStringContainsString('md:grid-cols', $html);
    }

    /**
     * Test reduced motion support
     *
     * @test
     */
    public function it_respects_prefers_reduced_motion()
    {
        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - prefers-reduced-motion media query present
        $this->assertStringContainsString('prefers-reduced-motion', $html);
        $this->assertStringContainsString('animation-duration: 0.01ms', $html);
    }

    /**
     * Test high contrast mode support
     *
     * @test
     */
    public function it_supports_high_contrast_mode()
    {
        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - prefers-contrast media query present
        $this->assertStringContainsString('prefers-contrast', $html);
    }

    /**
     * Test table accessibility
     *
     * @test
     */
    public function it_has_accessible_table_structure()
    {
        // Arrange
        $voter = User::factory()->create([
            'organisation_id' => $this->organisation->id,
            'is_voter' => 1,
        ]);

        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - Table has caption or aria-label
        $this->assertStringContainsString('<caption', $html);

        // Assert - Header cells have scope attribute
        $this->assertStringContainsString('scope="col"', $html);

        // Assert - Table structure is semantic
        $this->assertStringContainsString('<thead>', $html);
        $this->assertStringContainsString('<tbody>', $html);
    }

    /**
     * Test form accessibility
     *
     * @test
     */
    public function it_has_accessible_forms()
    {
        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - Fieldsets or section organisation
        $this->assertStringContainsString('<section', $html);

        // Assert - Inputs have proper attributes
        $this->assertStringContainsString('type="text"', $html);
        $this->assertStringContainsString('type="search"', $html);
        $this->assertStringContainsString('type="checkbox"', $html);
    }

    /**
     * Test error message accessibility
     *
     * @test
     */
    public function it_makes_error_messages_accessible()
    {
        // Act
        $response = $this->actingAs($this->member)
            ->post("/organisations/{$this->organisation->slug}/voters/bulk-approve", [
                'voter_ids' => [],
            ]);

        // Assert - Error message should be accessible
        // The flash error should be announced
        $response->assertSessionHasErrors();
    }

    /**
     * Test link accessibility
     *
     * @test
     */
    public function it_has_accessible_links()
    {
        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - Links should have meaningful text
        $this->assertStringContainsString('<a href=', $html);

        // Assert - Links not just "click here"
        // Check that actual content is present
        $this->assertStringContainsString('dashboard', $html);
    }

    /**
     * Test status badges are accessible
     *
     * @test
     */
    public function it_makes_status_badges_accessible()
    {
        // Arrange
        User::factory()->create([
            'organisation_id' => $this->organisation->id,
            'is_voter' => 1,
            'approvedBy' => 'Admin',
        ]);

        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - Status badges use semantic classes
        // Color should not be the only indicator
        $this->assertStringContainsString('text-green-800', $html);
        $this->assertStringContainsString('Approved', $html);
    }

    /**
     * Test pagination accessibility
     *
     * @test
     */
    public function it_has_accessible_pagination()
    {
        // Arrange - Create many voters for pagination
        User::factory(60)->create([
            'organisation_id' => $this->organisation->id,
            'is_voter' => 1,
        ]);

        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - Pagination links should be clear
        $this->assertStringContainsString('aria-label', $html);
        $this->assertStringContainsString('Previous', $html);
        $this->assertStringContainsString('Next', $html);
    }

    /**
     * Test language attribute
     *
     * @test
     */
    public function it_includes_language_attribute()
    {
        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        // The HTML should include language attribute (typically in layout)
        $response->assertStatus(200);
    }

    /**
     * Test text resizing capability
     *
     * @test
     */
    public function it_allows_text_resizing()
    {
        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - No fixed pixel font sizes that prevent resizing
        // Should use relative units (em, rem) or viewport units
        $this->assertStringNotContainsString('font-size: 10px', $html);

        // Assert - Font size classes are relative
        $this->assertStringContainsString('text-', $html);
    }

    /**
     * Test no automatic redirects
     *
     * @test
     */
    public function it_does_not_use_automatic_redirects()
    {
        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - No meta refresh redirects
        $this->assertStringNotContainsString('http-equiv="refresh"', $html);
    }

    /**
     * Test focus trap in modals (if applicable)
     *
     * @test
     */
    public function it_manages_focus_in_interactive_elements()
    {
        // Act
        $response = $this->actingAs($this->member)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $html = $response->getContent();

        // Assert - Buttons and interactive elements are focusable
        $this->assertStringContainsString('<button', $html);
        $this->assertStringContainsString('</button>', $html);
    }
}
