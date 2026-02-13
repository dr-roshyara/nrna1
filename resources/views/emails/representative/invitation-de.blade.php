<x-mail::message>
# Willkommen als Vertreter/in! 👋

Guten Tag {{ $representativeName }},

Sie wurden von **{{ $creatorName }}** als Vertreter/in der Organisation **{{ $organizationName }}** hinzugefügt.

## Nächste Schritte

Um Ihr Konto zu aktivieren und auf das Wahlsystem zuzugreifen, bitte setzen Sie Ihr Passwort:

<x-mail::button :url="$setupUrl">
Passwort setzen
</x-mail::button>

## Ihre Rolle

Als Vertreter/in der {{ $organizationName }} können Sie:
- **Wahlmitglieder** verwalten und einladen
- **Wahlen** erstellen und durchführen
- **Ergebnisse** einsehen und exportieren
- **Berichte** zur Wahl genieren

## Kontaktinformationen

Falls Sie Fragen haben, kontaktieren Sie bitte:
**{{ $creatorName }}**
E-Mail: {{ $organizationEmail }}

<x-mail::panel>
**Hinweis:** Dieser Link ist 24 Stunden gültig. Falls er abgelaufen ist, können Sie einen neuen über die Anmeldeseite anfordern.
</x-mail::panel>

<x-mail::subcopy>
Dies ist eine automatische Benachrichtigung. Wenn Sie diese E-Mail nicht erwartet haben, kontaktieren Sie bitte sofort unseren Support.
</x-mail::subcopy>

Willkommen bei Public Digit!
Ihr Wahlsystem Team
</x-mail::message>
