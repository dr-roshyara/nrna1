<?php

return [
    'hero' => [
        'title' => 'Enterprise-Grade Sicherheitsarchitektur',
        'subtitle' => 'Fünf unabhängige Sicherheitsebenen schützen jeden Stimmzettel vom Klick bis zur Auszählung',
        'promise' => 'Ihre Stimme ist anonym. Ihre Wahl ist sicher. Ihre Ergebnisse sind überprüfbar.',
    ],

    'layers' => [
        'layer1' => [
            'title' => 'Anforderungsvalidierung',
            'description' => 'Überprüft, ob der Stimmzettel existiert, zum Benutzer gehört und aktiv ist',
        ],
        'layer2' => [
            'title' => 'Zeitliche Validierung',
            'description' => 'Überprüft Sitzungsablauf und Wahlzeitpunkt',
        ],
        'layer3' => [
            'title' => 'Goldene-Regel-Durchsetzung',
            'description' => 'Stellt sicher, dass der Wähler der richtigen Organisation angehört',
        ],
        'layer4' => [
            'title' => 'Geschäftslogik',
            'description' => 'Validiert Wahlregeln (eine Person, eine Stimme)',
        ],
        'layer5' => [
            'title' => 'Datenpersistenz',
            'description' => 'Speichert Stimmen anonym mit kryptographischem Beweis',
        ],
    ],

    'pillars' => [
        'anonymity' => [
            'title' => 'Vollständige Anonymität',
            'description' => 'Keine Wähler-IDs mit Stimmen gespeichert. Stimmen sind völlig anonym und dennoch überprüfbar.',
        ],
        'verification' => [
            'title' => 'Kryptographische Verifizierung',
            'description' => 'SHA256-Hashes beweisen, dass Stimmen gezählt wurden, ohne die Wähleridentität preiszugeben.',
        ],
        'isolation' => [
            'title' => 'Multi-Mandanten-Isolation',
            'description' => 'Die Daten jeder Organisation sind vollständig getrennt und unabhängig.',
        ],
    ],

    'faq' => [
        'question1' => [
            'question' => 'Wie halten Sie meine Stimme anonym?',
            'answer' => 'Wir speichern keine Wähleridentifikation mit Ihrer Stimme. Unsere Stimmtabelle hat keine user_id-Spalte. Stimmen enthalten nur Kandidatenauswahlen und werden nur durch kryptographische Hashes identifiziert, was Wähler-Stimmen-Verknüpfung mathematisch unmöglich macht.',
        ],
        'question2' => [
            'question' => 'Was verhindert doppelte Abstimmung?',
            'answer' => 'Unser System erzwingt „eine Person, eine Stimme" durch mehrere Ebenen: Stimmcodes können nur einmal verwendet werden, Stimmzettelsitzungen sind an bestimmte Benutzer gebunden, und die Stimmhistorie wird verfolgt. Kein Code kann zweimal verwendet werden.',
        ],
        'question3' => [
            'question' => 'Können Organisationen die Daten anderer sehen?',
            'answer' => 'Nein. Die Daten jeder Organisation sind auf Datenbankebene vollständig isoliert. Organisationen können nicht auf Daten anderer Organisationen, Wahlen, Wähler oder Ergebnisse zugreifen.',
        ],
        'question4' => [
            'question' => 'Wie kann ich überprüfen, dass meine Stimme gezählt wurde?',
            'answer' => 'Sie erhalten unmittelbar nach der Stimmabgabe einen eindeutigen kryptographischen Hash Ihrer Stimme. Sie können überprüfen, dass dieser Hash in den endgültigen Ergebnissen enthalten ist, ohne Ihren Stimminhalt offenzulegen.',
        ],
    ],

    'cta' => [
        'demo' => 'Sichere Wahl starten',
        'whitepaper' => 'Sicherheits-Whitepaper anzeigen',
    ],

    'badges' => [
        'security_tests' => '36 Sicherheitstests',
        'anonymity' => '100% Anonym',
        'protection' => '3-stufiger Schutz',
        'coverage' => '94% Testabdeckung',
    ],
];
