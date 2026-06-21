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

        'organisation-create-tutorial' => [
            'title'       => 'Anleitung: Organisation mit geografischem Geltungsbereich erstellen | Public Digit',
            'description' => 'Vollständige Schritt-für-Schritt-Anleitung zur Einrichtung Ihrer Organisation in Public Digit. Lernen Sie mehr über Ausschussstrukturen, geografische Geltungsbereiche und Mitgliedschaftssysteme. Perfekt für neue Benutzer.',
            'keywords'    => 'Organisation erstellen, Organisations-Setup, geografischer Geltungsbereich, Ausschussstruktur, Online-Wahl-Setup, Anleitung, Tutorial',
            'robots'      => 'index, follow',
        ],

        'governance-levels-tutorial' => [
            'title'       => 'Governance-Ebenen erklärt | Ausschusspyramide | Public Digit',
            'description' => 'Erfahren Sie, was Governance-Ebenen sind, warum sie für Ihr Organisation wichtig sind und wie Sie Ausschusshierarchien mit geografischen Geltungsbereichen konfigurieren. Mit Praxisbeispielen von NRNA, SPD und mehr.',
            'keywords'    => 'Governance-Ebenen, Ausschusspyramide, Organisationsstruktur, geografischer Geltungsbereich, Ausschussverwaltung, Governance-Tutorial, NRNA Governance',
            'robots'      => 'index, follow',
        ],

        'election-architecture' => [
            'title'       => 'Wahlarchitektur & Zustandsmaschine | Public Digit',
            'description' => 'Entdecken Sie Public Digits 5-Phasen-Wahllebenszyklus: eine manipulationssichere Zustandsmaschine für transparente, verifizierbare Wahlen mit unveränderlichen Prüfpfaden und kryptografischer Verifizierung.',
            'keywords'    => 'Wahlarchitektur, Zustandsmaschine, Wahllebenszyklus, manipulationssichere Wahlen, Abstimmungssystemarchitektur, digitale Wahlplattform',
            'robots'      => 'index, follow',
        ],

        'election-security' => [
            'title'       => 'Wahlsicherheit & Zustandsmaschinentechnologie | Public Digit',
            'description' => 'Erfahren Sie, wie unsere manipulationssichere Zustandsmaschine die Wahlintegrität durch unveränderliche Prüfpfade, kryptografische Verifizierung und überprüfbare Ergebnisse garantiert.',
            'keywords'    => 'Wahlsicherheit, Zustandsmaschine, manipulationssicher, Prüfpfad, kryptografisch, Wahlintegrität',
            'robots'      => 'index, follow',
        ],

        'votingSecurity' => [
            'title'       => 'Sichere Wahl durchführen - Fünf-Schicht-Sicherheit für Online-Abstimmungen | Public Digit',
            'description' => 'Sichere Wahl durchführen mit Public Digit. Fünf-Schicht-Sicherheitsarchitektur garantiert vollständige Anonymität, kryptographische Verifizierung und Multi-Tenant-Isolation. DSGVO-konform mit Geräte-Fingerprinting und End-to-End Verschlüsselung.',
            'keywords'    => 'sichere Wahl durchführen, Online-Abstimmung sicher, sichere Wahlen für Vereine, Wahlsicherheit, anonyme Abstimmungen, kryptografische Verifizierung, Geräte-Fingerprinting',
            'robots'      => 'index, follow',
        ],

        'committee_tutorial' => [
            'title'       => 'Ausschussverwaltung Anleitung | Public Digit',
            'description' => 'Erfahren Sie, wie Sie Ausschüsse erstellen, bearbeiten, Mitglieder verwalten und aus Ausschüssen entfernen. Schritt-für-Schritt-Anleitung für demokratische Organisationen.',
            'keywords'    => 'Ausschussverwaltung, Anleitung, Leitfaden, demokratische Verwaltung, Wahlen, Ausschussleitung',
            'robots'      => 'index, follow',
        ],

        'demo-guide' => [
            'title'       => 'Online-Wahl Demo - Sichere anonyme Wahlen testen | Public Digit',
            'description' => 'Erleben Sie eine vollständige Online-Wahl in 5 Schritten. Keine Registrierung erforderlich. Testen Sie sichere, anonyme, verifizierbare Wahlen mit Belegcode-Prüfung.',
            'keywords'    => 'Online-Wahl Demo, Demo-Wahl, sichere Online-Wahl, anonymes Wahlsystem, wie online wählen, Wahlprüfung, anonym wählen, Online-Wahl kostenlos',
            'robots'      => 'index, follow',
        ],

        'tutorials.election-settings' => [
            'title'       => 'Wahlkonfiguration - Einstellungen erklaert | Public Digit',
            'description' => 'Schritt-fuer-Schritt-Anleitung fuer alle Wahleinstellungen in Public Digit: IP-Beschraenkungen, Stimmzetteloptionen, Auswahlregeln und Waehlerverifizierung.',
            'keywords'    => 'Wahleinstellungen, Online-Wahl konfigurieren, IP-Beschrankung Wahl, Wahlerverifizierung, Stimmzettel Einstellungen, Wahlverwaltung, Public Digit Anleitung',
            'robots'      => 'index, follow',
        ],

        'tutorials.membership-modes' => [
            'title'       => 'Mitgliedschaftsmodi - Vollständig vs. Wahl-Nur | Public Digit',
            'description' => 'Verstehen Sie die Unterschiede zwischen Vollständiger Mitgliedschaft und Wahl-Nur-Modi. Lernen Sie, welcher Modus für Ihre Organisation geeignet ist und wie Sie wechseln können.',
            'keywords'    => 'Mitgliedschaftsmodi, vollständige Mitgliedschaft, Wahlberechtigung, Wählerverifikation, Organisationseinstellungen, Mitgliedschaftsverwaltung, Online-Wahl, Public Digit Leitfaden',
            'robots'      => 'index, follow',
        ],

        'elections.voters.import.tutorial' => [
            'title'       => 'Wähler-Import Anleitung & Vollständiger Leitfaden | Public Digit',
            'description' => 'Beherrschen Sie den Wählerimport für Wahlen auf Public Digit. Lernen Sie Vollständigem vs. Wahl-Nur-Modus, CSV-Formate, Massenregistrierung, Schritt-für-Schritt-Prozess und automatische Erstellung von Wählerkonten.',
            'keywords'    => 'Wähler-Import, Wahlwähler, CSV-Import, Massenverwaltung, Wählerregistrierung, Wahlvorbereitung, Import-Anleitung, Mitgliedschaftsmodi',
            'robots'      => 'index, follow',
        ],

        'organisations.settings' => [
            'title'       => 'Organisationseinstellungen - {organisationName} | Public Digit',
            'description' => 'Konfigurieren Sie den Mitgliedschaftsmodus und Organisationseinstellungen für {organisationName}. Verwalten Sie die Wählerberechtigung, wählen Sie zwischen Vollständiger Mitgliedschaft oder Wahl-Nur-Modus, mit {memberCount} Mitgliedern.',
            'keywords'    => 'Organisationseinstellungen, Mitgliedschaftsmodus, vollständige Mitgliedschaft, Wahlberechtigung, Wählerverifizierung, Organisationsverwaltung, Mitgliederverwaltung, Public Digit Einstellungen',
            'robots'      => 'index, follow',
        ],

        'newsletter-guide' => [
            'title'       => 'Kompletter Newsletter-Leitfaden für Organisationen | Public Digit',
            'description' => 'Beherrschen Sie Newsletter-Kampagnen für Ihre Organisation. Erlernen Sie Zielgruppensegmentierung, E-Mail-Komposition, Zustellungsverfolgung und Best Practices mit Public Digit.',
            'keywords'    => 'Newsletter, Organisation E-Mail, Zielgruppensegmentierung, E-Mail-Kampagnen, Mitgliederkommunikation, Wahlmitteilungen, Massen-E-Mail, E-Mail-Marketing',
            'robots'      => 'index, follow',
        ],

        'tutorials.voters-management' => [
            'title'       => 'Wählerverwaltung - Vollständige Anleitung | Public Digit',
            'description' => 'Erfahren Sie, wie Sie Wähler in Public Digit verwalten. Schritt-für-Schritt-Anleitung zum Hinzufügen, Importieren und Verwalten von Wählerlisten für Ihre Wahlen.',
            'keywords'    => 'Wählerverwaltung, Wählerlisten, Wählerimport, Wahlwähler, Mitgliederverwaltung, Anleitung, Tutorial',
            'robots'      => 'index, follow',
        ],

        // Thought-Leadership-Artikel — informativer SEO für Diaspora-Wahlgovernance
        'who-watch-the-watchmen' => [
            'title'       => 'Wahlprüfung für Diaspora-Organisationen | Public Digit',
            'description' => 'Wer überwacht die Wächter? Eine verständliche Betrachtung unabhängiger Wahlaufsicht und -prüfung für Diaspora-Organisationen mit sicheren Online-Wahlen.',
            'keywords'    => 'Wahlprüfung, Online-Wahlen für Diaspora-Organisationen, Diaspora-Wahlplattform, unabhängige Wahlaufsicht, sichere Online-Wahlen, überprüfbare Wahlen, NRNA-Wahlen, Online-Wahlsoftware Diaspora',
            'robots'      => 'index, follow',
        ],

        'ddd-article-part-one' => [
            'title'       => 'Vertrauenswürdige Online-Wahlen aufbauen | Public Digit',
            'description' => 'Die Frage, die alles veränderte: Wie eine Diaspora-Organisation sichere, überprüfbare Online-Wahlen und die Architektur digitalen Vertrauens neu durchdachte.',
            'keywords'    => 'Online-Wahlen für Diaspora-Organisationen, Diaspora-Wahlplattform, sichere Online-Wahlen, überprüfbare Wahlen, Online-Wahlsoftware, NRNA-Wahlen, digitale Demokratie',
            'robots'      => 'index, follow',
        ],
    ],
];
