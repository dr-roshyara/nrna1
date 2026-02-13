<x-mail::message>
# Organisation erfolgreich erstellt! 🎉

Guten Tag,

die Organisation **{{ $organizationName }}** wurde soeben von **{{ $creatorName }}** im Wahlsystem erfolgreich erstellt.

## Was können Sie jetzt tun?

<x-mail::button :url="$dashboardUrl">
Zur Organisationsübersicht
</x-mail::button>

1. **Mitglieder einladen** – Fügen Sie Ihre Wahlberechtigten hinzu
2. **Erste Wahl erstellen** – Legen Sie Termin und Stimmzettel fest
3. **Wahlkommission benennen** – Bestimmen Sie Wahlvorstände

Alle Daten werden DSGVO-konform auf Servern in Deutschland gespeichert.

<x-mail::panel>
**Ihre Ansprechpartner:**
{{ $creatorName }} (Administrator)
E-Mail: {{ $organizationEmail }}
</x-mail::panel>

<x-mail::subcopy>
Dies ist eine automatische Bestätigung. Wenn Sie diese E-Mail nicht erwartet haben, kontaktieren Sie bitte unseren Support.
</x-mail::subcopy>

Danke, dass Sie unser Wahlsystem nutzen!
Ihr Team von Public Digit
</x-mail::message>
