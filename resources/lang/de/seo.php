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
            'title' => 'Digitale Online Wahlen für Verein | Public Digit',
            'description' => 'Digitale Online Wahlen für Verein: ✓ Geheime Vorstandswahlen ✓ Online-Abstimmungen ✓ Hybride Mitgliederversammlungen ✓ DSGVO-konform ✓ Ende-zu-Ende-verschlüsselt. Jetzt testen!',
            'keywords' => 'Digitale Online Wahlen für Verein, Vereinswahlen online, digitale Vorstandswahl Verein, Online-Abstimmung Verein, hybride Wahlen Verein, Mitgliederbefragung online, Satzungsänderung digital',
        ],

        'demo.result' => [
            'title'       => 'Demo-Wahlergebnisse | Public Digit',
            'description' => 'Sehen Sie umfassende Ergebnisse der Public Digit Demo-Wahl — Stimmzahlen, Kandidaten-Rankings und vollständige Wahlstatistiken.',
            'keywords'    => 'Demo-Wahlergebnisse, Online-Wahlresultate, Kandidatenranking, Wahlstatistiken',
            'robots'      => 'noindex, nofollow',
        ],

        'vereinswahlen' => [
            'title' => 'Digitale Online Wahlen für Verein | Public Digit',
            'description' => 'Die Plattform für digitale Online Wahlen für Verein: Einfach, sicher und rechtssicher. Ideal für Vorstandswahlen, Satzungsänderungen und Mitgliederbefragungen.',
            'keywords' => 'Digitale Online Wahlen für Verein, Vorstandswahl digital, Vereinssatzung online abstimmen, Mitgliederversammlung online',
            'robots' => 'index, follow',
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

        'login' => [
            'title'       => 'Anmelden | Public Digit',
            'description' => 'Melden Sie sich bei Ihrem Public Digit-Konto an, um auf Ihre Wahlen zuzugreifen, zu wählen oder Ihre Organisation zu verwalten.',
            'keywords'    => 'Anmelden, Einloggen, Public Digit Konto, Wahlplattform Login',
            'robots'      => 'index, follow',
        ],

        'register' => [
            'title'       => 'Konto erstellen | Public Digit',
            'description' => 'Registrieren Sie sich für ein Public Digit-Konto, um sichere Online-Wahlen für Ihre Organisation zu nutzen.',
            'keywords'    => 'Registrieren, Konto erstellen, Anmelden, Public Digit Registrierung',
            'robots'      => 'index, follow',
        ],

        'about' => [
            'title'       => 'Über Public Digit | Sichere Digitale Wahlplattform',
            'description' => 'Erfahren Sie mehr über die Mission von Public Digit, digitale Demokratie sicher, transparent und zugänglich für Organisationen, NGOs und Diaspora-Gemeinschaften zu machen.',
            'keywords'    => 'über public digit, digitale Wahlmission, sichere Wahlplattform, Diaspora-Wahlen',
            'robots'      => 'index, follow',
        ],

        'faq' => [
            'title'       => 'FAQ | Häufig gestellte Fragen | Public Digit',
            'description' => 'Finden Sie Antworten auf häufige Fragen zu Online-Wahlen, Sicherheit, Datenschutz und wie Public Digit für Ihre Organisation funktioniert.',
            'keywords'    => 'FAQ, häufige Fragen, Online-Wahlen Hilfe, Wahlplattform Fragen',
            'robots'      => 'index, follow',
        ],

        'security' => [
            'title'       => 'Sichere und anonyme Online-Wahlen | Public Digit',
            'description' => 'Fünfschichtige Sicherheitsarchitektur zum Schutz Ihrer Wahlen. Vollständige Wähleranonymität, kryptografische Verifizierung und Multi-Tenant-Isolation für Vereine, NGOs und Organisationen.',
            'keywords'    => 'sichere Online-Wahlen, anonyme Wahlen, Wahlsicherheit, digitale Wahlplattform, Wähleranonymität, DSGVO-Wahlen',
            'robots'      => 'index, follow',
        ],

        'demo' => [
            'title'       => 'Demo-Wahl ausprobieren | Public Digit',
            'description' => 'Erleben Sie sichere Online-Wahlen hautnah mit unserer interaktiven Demo-Wahl. Keine Registrierung erforderlich.',
            'keywords'    => 'Demo-Wahl, Abstimmung testen, Online-Wahl Demo, Wahlplattform testen',
            'robots'      => 'index, follow',
        ],

        'dashboard' => [
            'title'       => 'Dashboard | Public Digit',
            'description' => 'Greifen Sie auf Ihre Wahlen, Abstimmungsaktivitäten und Kontoverwaltung über Ihr persönliches Dashboard zu.',
            'keywords'    => 'Dashboard, meine Wahlen, Abstimmungs-Dashboard',
            'robots'      => 'noindex, nofollow',
        ],

        'profile' => [
            'title'       => 'Ihr Profil | Public Digit',
            'description' => 'Verwalten Sie Ihre Public Digit-Kontoeinstellungen, Benachrichtigungen und Präferenzen.',
            'keywords'    => 'Profil, Kontoeinstellungen, Benutzerprofil',
            'robots'      => 'noindex, nofollow',
        ],

        'hybrid' => [
            'title'       => 'Hybride Wahlen für Mitgliederversammlungen • Public Digit',
            'description' => 'Kombinieren Sie Präsenz- und Online-Wahl: Ideal für gemischte Mitgliederversammlungen mit Teilnehmern vor Ort und remote. Inklusive Authentifizierung und Auszählung.',
            'keywords'    => 'hybride Wahlen, gemischte Mitgliederversammlung, remote voting, Präsenzwahl, Online-Versammlung',
            'robots'      => 'index, follow',
        ],

        'sicherheit' => [
            'title'       => 'Sichere Online-Wahlen mit Ende-zu-Ende-Verschlüsselung • Public Digit',
            'description' => 'Banksicherheit für Ihre Wahlen: Ende-zu-Ende-Verschlüsselung, anonyme Stimmabgabe, manipulationssichere Protokolle und DSGVO-Konformität.',
            'keywords'    => 'Wahlsicherheit, Ende-zu-Ende-Verschlüsselung, anonyme Wahl, manipulationssicher, DSGVO',
            'robots'      => 'index, follow',
        ],
    ],
];
