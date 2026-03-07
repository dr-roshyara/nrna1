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
            'dataStore' => $this->getDataStore(),
            'verificationMethods' => $this->getVerificationMethods(),
            'fingerprintSteps' => $this->getFingerprintSteps(),
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
                'labelKey' => 'pages.security.cta.demo',
                'href' => '/election/demo/start',
                'variant' => 'primary',
            ],
            [
                'id' => 'cta-whitepaper',
                'labelKey' => 'pages.security.cta.whitepaper',
                'href' => '#whitepaper',
                'variant' => 'secondary',
            ],
        ];
    }

    /**
     * Get data store section with stored and not stored information.
     */
    private function getDataStore(): array
    {
        return [
            'stored' => [
                'hashed_fingerprint',
                'country_code',
                'browser_family',
                'device_type',
                'receipt_hash',
            ],
            'notStored' => [
                'name',
                'user_id',
                'raw_ip',
                'raw_browser',
                'exact_location',
                'mac_address',
            ],
        ];
    }

    /**
     * Get verification methods (receipt and auditor proof).
     */
    private function getVerificationMethods(): array
    {
        return [
            [
                'id' => 'receipt',
                'badgeType' => 'receipt',
            ],
            [
                'id' => 'auditor',
                'badgeType' => 'auditor',
            ],
        ];
    }

    /**
     * Get fingerprint steps (4-step process).
     */
    private function getFingerprintSteps(): array
    {
        return [
            [
                'id' => 'step1',
                'number' => 1,
            ],
            [
                'id' => 'step2',
                'number' => 2,
            ],
            [
                'id' => 'step3',
                'number' => 3,
            ],
            [
                'id' => 'step4',
                'number' => 4,
            ],
        ];
    }
}
