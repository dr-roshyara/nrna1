<?php

/**
 * SEO Translations - Deutsch (German)
 *
 * Used for server-side fallback meta tags in app.blade.php
 * These are also mirrored in resources/js/locales/de.json for client-side useMeta()
 *
 * Keep in sync with:
 * - resources/lang/en/seo.php (English)
 * - resources/lang/np/seo.php (Nepali)
 * - resources/js/locales/en.json (Vue i18n)
 * - resources/js/locales/de.json (Vue i18n)
 * - resources/js/locales/np.json (Vue i18n)
 */

return [
    'site' => [
        'title' => 'Public Digit',
        'description' => 'Sichere digitale Wahlplattform für Diaspora-Gemeinschaften, Organisationen und NGOs weltweit. DSGVO-konform, Ende-zu-Ende verschlüsselte Online-Wahlen.',
        'keywords' => 'Online-Wahlen, digitale Abstimmungen, Diaspora-Wahlen, NRNA-Wahlen, sichere Wahlplattform, elektronisches Abstimmungssystem',
    ],

    'pages' => [
        'home' => [
            'title' => 'Sichere Online-Wahlen | Public Digit Elections',
            'description' => 'Ermöglichen Sie Ihrer Organisation sichere, transparente Online-Wahlen. Public Digit bietet DSGVO-konforme Wahlen für Diaspora-Gemeinschaften, NGOs und Mitgliedsorganisationen weltweit.',
            'keywords' => 'Online-Wahlen, digitale Abstimmungen, sichere Wahlen, Diaspora-Wahlen, NRNA',
        ],

        'pricing' => [
            'title' => 'Preispläne | Public Digit Elections',
            'description' => 'Transparente Preisgestaltung für Organisationen aller Größen. Wählen Sie einen Plan, der zu Ihren Wahlbedürfnissen passt. Keine versteckten Gebühren, skalierbare Lösungen für NGOs und Diaspora-Gruppen.',
            'keywords' => 'Wahlpreise, Abstimmungssoftware-Kosten, Online-Wahlplattform, Lösungspreise für Wahlen',
        ],

        'organisations.show' => [
            'title' => '{organizationName} | Wahlen & Mitglieder | Public Digit',
            'description' => '{organizationName}: {memberCount} Mitglieder, {electionCount} Wahlen. Sichere digitale Wahlplattform für Organisationen und Diaspora-Gemeinschaften.',
            'keywords' => '{organizationName}, Wahlen, Abstimmungen, digitale Demokratie',
        ],

        'elections.index' => [
            'title' => 'Aktuelle Wahlen | Public Digit',
            'description' => 'Durchsuchen Sie aktuelle Wahlen auf der Public Digit-Plattform. Nehmen Sie an sicheren, transparenten Abstimmungen für Organisationen weltweit teil.',
            'keywords' => 'aktuelle Wahlen, bevorstehende Abstimmungen, Wahlverzeichnis, Abstimmungsmöglichkeiten',
        ],

        'elections.show' => [
            'title' => '{electionName} | {organizationName} | Public Digit',
            'description' => 'Informationen zu {electionName} von {organizationName}. Sichere, transparente Wahlplattform mit vollständiger Audit-Trail.',
            'keywords' => '{electionName}, {organizationName}, Wahlen, Abstimmungsergebnisse',
        ],

        'election.result' => [
            'title' => '{electionName} Ergebnisse | Public Digit Elections',
            'description' => 'Endergebnisse für {electionName}. Sehen Sie Wahlergebnisse, Kandidaten-Rankings und vollständige Abstimmungsstatistiken.',
            'keywords' => '{electionName}, Wahlergebnisse, Abstimmungsergebnisse, Wahlresultate',
        ],
    ],
];
