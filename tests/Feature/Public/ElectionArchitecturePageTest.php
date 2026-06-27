<?php

namespace Tests\Feature\Public;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia as Assert;

class ElectionArchitecturePageTest extends TestCase
{
    #[Test]
    public function public_architecture_page_is_accessible(): void
    {
        $response = $this->get(route('public.election-architecture'));

        $response->assertStatus(200);
        $response->assertInertia(fn(Assert $page) => $page->component('Public/ElectionArchitecture'));
    }

    #[Test]
    public function page_has_phases_data(): void
    {
        $response = $this->get(route('public.election-architecture'));

        $response->assertInertia(fn(Assert $page) =>
            $page->has('phases', 5)
                ->has('images')
        );
    }

    #[Test]
    public function page_requires_no_authentication(): void
    {
        $response = $this->get(route('public.election-architecture'));
        $response->assertStatus(200);
    }

    #[Test]
    public function phases_contain_required_keys(): void
    {
        $response = $this->get(route('public.election-architecture'));

        $response->assertInertia(fn(Assert $page) => $page
            ->where('phases.0.key', 'administration')
            ->where('phases.0.icon', '⚙️')
            ->where('phases.1.key', 'nomination')
            ->where('phases.2.key', 'voting')
        );
    }

    #[Test]
    public function images_are_served(): void
    {
        $response = $this->get(route('public.election-architecture'));

        $response->assertInertia(fn(Assert $page) => $page
            ->has('images.stateMachine')
            ->has('images.sequenceDiagram')
        );
    }
}
