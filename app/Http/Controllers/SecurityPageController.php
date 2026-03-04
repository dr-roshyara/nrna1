<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class SecurityPageController extends Controller
{
    /**
     * Display the security page.
     */
    public function show(): Response
    {
        return Inertia::render('Public/Security', [
            'layers' => $this->getSecurityLayers(),
            'pillars' => $this->getSecurityPillars(),
            'badges' => $this->getTrustBadges(),
            'faqItems' => $this->getFaqItems(),
            'images' => $this->getImages(),
            'ctaButtons' => $this->getCtaButtons(),
        ]);
    }

    /**
     * Get five-layer security architecture data.
     */
    private function getSecurityLayers(): array
    {
        return [
            [
                'id' => 'layer-1',
                'number' => 1,
            ],
            [
                'id' => 'layer-2',
                'number' => 2,
            ],
            [
                'id' => 'layer-3',
                'number' => 3,
            ],
            [
                'id' => 'layer-4',
                'number' => 4,
            ],
            [
                'id' => 'layer-5',
                'number' => 5,
            ],
        ];
    }

    /**
     * Get three security pillars.
     */
    private function getSecurityPillars(): array
    {
        return [
            [
                'id' => 'pillar-anonymity',
                'icon' => 'shield-check',
            ],
            [
                'id' => 'pillar-verification',
                'icon' => 'check-circle',
            ],
            [
                'id' => 'pillar-isolation',
                'icon' => 'lock',
            ],
        ];
    }

    /**
     * Get trust badges data.
     */
    private function getTrustBadges(): array
    {
        return [
            'securityTests' => '36',
            'anonymity' => '100%',
            'protection' => '3-Layer',
            'coverage' => '100%',
        ];
    }

    /**
     * Get FAQ items.
     */
    private function getFaqItems(): array
    {
        return [
            [
                'id' => 'question1',
            ],
            [
                'id' => 'question2',
            ],
            [
                'id' => 'question3',
            ],
            [
                'id' => 'question4',
            ],
        ];
    }

    /**
     * Get image paths.
     */
    private function getImages(): array
    {
        return [
            'layerArchitecture' => '/images/security/five_layer_security (1).png',
            'votingJourney' => '/images/security/five_layer_security (2).png',
        ];
    }

    /**
     * Get CTA buttons.
     */
    private function getCtaButtons(): array
    {
        return [
            [
                'id' => 'cta-demo',
                'label' => 'Start Secure Election',
                'href' => '/election/demo/start',
                'variant' => 'primary',
            ],
            [
                'id' => 'cta-whitepaper',
                'label' => 'View Security Whitepaper',
                'href' => '#whitepaper',
                'variant' => 'secondary',
            ],
        ];
    }
}
