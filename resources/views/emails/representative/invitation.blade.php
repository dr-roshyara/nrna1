<x-mail::message>
# Sie wurden als Vertreter/in eingetragen! 👋

Guten Tag {{ $representativeName }},

{{ $creatorName }} hat Sie als **Vertreter/in** der Organisation **{{ $organizationName }}** im Wahlsystem eingetragen.

## Jetzt Zugang einrichten:

<x-mail::button :url="$setupUrl">
Passwort festlegen
</x-mail::button>

### So geht's:
1. Klicken Sie auf "Passwort festlegen"
2. Geben Sie Ihre E-Mail-Adresse ein: `{{ \Illuminate\Support\Facades\Auth::user()->email ?? $representativeName }}`
3. Sie erhalten einen Link zum Passwort-Reset
4. Erstellen Sie ein sicheres Passwort
5. Melden Sie sich an und nehmen Sie an Wahlen teil

## Ihre Rechte:
- ✅ An Wahlen teilnehmen
- ✅ Ergebnisse einsehen
- ✅ Mit der Wahlkommission kommunizieren

## Fragen?
Kontaktieren Sie die Wahlkommission unter: **{{ $organizationEmail }}**

---

<x-mail::subcopy>
Diese Einladung wurde automatisch versendet. Wenn Sie nicht als Vertreter/in eingetragen sein möchten, kontaktieren Sie bitte {{ $organizationEmail }}.
</x-mail::subcopy>

Danke, dass Sie unser Wahlsystem nutzen!
Ihr Team von Public Digit
</x-mail::message>
