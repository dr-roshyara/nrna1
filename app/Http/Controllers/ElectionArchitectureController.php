<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class ElectionArchitectureController extends Controller
{
    public function show(): Response
    {
        return Inertia::render('Public/ElectionArchitecture', [
            'phases' => $this->getPhases(),
            'images' => $this->getImages(),
        ]);
    }

    private function getPhases(): array
    {
        return [
            ['key' => 'administration', 'icon' => '⚙️', 'color' => 'blue'],
            ['key' => 'nomination', 'icon' => '📋', 'color' => 'green'],
            ['key' => 'voting', 'icon' => '🗳️', 'color' => 'purple'],
            ['key' => 'counting', 'icon' => '📊', 'color' => 'orange'],
            ['key' => 'results', 'icon' => '✅', 'color' => 'emerald'],
        ];
    }

    private function getImages(): array
    {
        return [
            'stateMachine' => '/storage/architecture/architecture-state-machine.png',
            'sequenceDiagram' => '/storage/architecture/sequence-daigram-state-machine.png',
        ];
    }
}
