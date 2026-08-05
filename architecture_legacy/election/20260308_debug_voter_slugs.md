## ✅ **Das ist ein bekanntes Problem - Hier ist die Lösung!**

Das Problem liegt im `VoterSlugService`. Aktuell wird ein existierender Slug zurückgegeben, auch wenn er abgelaufen ist. Wir müssen die Logik ändern, dass **abgelaufene Slugs ignoriert werden**.

### **Die Lösung in 3 Dateien:**

---

## 📋 **FIX 1: VoterSlugService.php anpassen**

**Datei:** `app/Services/VoterSlugService.php`

**Methode:** `getOrCreateSlug()` (ca. Zeile 100-130)

```php
/**
 * Get or create a voter slug with proper expiration handling
 * 
 * @param User $user
 * @param Election $election
 * @param bool $forceNew For demo elections or restart
 * @return VoterSlug|DemoVoterSlug
 */
public function getOrCreateSlug(User $user, Election $election, bool $forceNew = false)
{
    $model = $election->type === 'demo' ? DemoVoterSlug::class : VoterSlug::class;
    
    // Für Demo-Election oder wenn forced: IMMER neuen Slug erstellen
    if ($forceNew || $election->type === 'demo') {
        // Lösche alte Slugs für diesen User in dieser Election
        $this->cleanupExpiredSlugs($user, $election, $model);
        return $this->createNewSlug($user, $election, $model);
    }
    
    // Für echte Elections: Prüfe auf aktiven, NICHT abgelaufenen Slug
    $slug = $model::withoutGlobalScopes()
        ->where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->where('expires_at', '>', now())        // ✅ NUR nicht-abgelaufene
        ->where('is_active', true)
        ->where('status', 'active')
        ->first();
    
    if ($slug) {
        return $slug;
    }
    
    // Kein aktiver Slug gefunden - erstelle neuen
    return $this->createNewSlug($user, $election, $model);
}
```

**Zusätzliche Methode fürs Aufräumen:**

```php
/**
 * Lösche alte/abgelaufene Slugs für diesen User in dieser Election
 */
protected function cleanupExpiredSlugs(User $user, Election $election, string $model): void
{
    // Markiere abgelaufene Slugs als inaktiv
    $model::withoutGlobalScopes()
        ->where('user_id', $user->id)
        ->where('election_id', $election->id)
        ->where(function($query) {
            $query->where('expires_at', '<=', now())
                  ->orWhere('is_active', false);
        })
        ->update([
            'is_active' => false,
            'can_vote_now' => false,
            'status' => 'expired',
            'updated_at' => now(),
        ]);
    
    // Optional: Für Demo-Elections kannst du sie sogar löschen
    if ($election->type === 'demo') {
        $model::withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->delete();
    }
}
```

---

## 📋 **FIX 2: ElectionStartController anpassen**

**Datei:** `app/Http/Controllers/Election/ElectionStartController.php`

```php
public function startDemo(Request $request)
{
    $user = auth()->user();
    
    if (!$user) {
        return redirect()->route('login');
    }
    
    // Demo-Election finden
    $election = Election::withoutGlobalScopes()
        ->where('type', 'demo')
        ->where('status', 'active')
        ->latest()
        ->first();
    
    if (!$election) {
        return redirect()->route('dashboard')
            ->with('error', 'Keine aktive Demo-Election gefunden.');
    }
    
    // NEU: IMMER neuen Slug für Demo erstellen (forceNew = true)
    $slugService = app(\App\Services\VoterSlugService::class);
    $voterSlug = $slugService->getOrCreateSlug($user, $election, true); // ✅ forceNew = true
    
    return redirect()->route('slug.demo-code.create', [
        'vslug' => $voterSlug->slug
    ])->with('success', 'Demo-Voting gestartet!');
}
```

---

## 📋 **FIX 3: Optional - Cleanup Command für abgelaufene Slugs**

**Datei:** `app/Console/Commands/CleanupExpiredVoterSlugs.php`

```php
<?php

namespace App\Console\Commands;

use App\Models\VoterSlug;
use App\Models\DemoVoterSlug;
use Illuminate\Console\Command;

class CleanupExpiredVoterSlugs extends Command
{
    protected $signature = 'voting:cleanup-slugs';
    protected $description = 'Lösche abgelaufene Voter-Slugs';

    public function handle()
    {
        $this->info('Räume abgelaufene Voter-Slugs auf...');
        
        // Echte VoterSlugs: als expired markieren
        $realUpdated = VoterSlug::where('expires_at', '<', now())
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'can_vote_now' => false,
                'status' => 'expired'
            ]);
        
        $this->info("✅ {$realUpdated} echte VoterSlugs abgelaufen.");
        
        // Demo VoterSlugs: löschen
        $demoDeleted = DemoVoterSlug::where('expires_at', '<', now())->delete();
        $this->info("✅ {$demoDeleted} Demo VoterSlugs gelöscht.");
        
        return Command::SUCCESS;
    }
}
```

In `Kernel.php` eintragen:
```php
protected $schedule = [
    // Alle 5 Minuten ausführen
    $schedule->command('voting:cleanup-slugs')->everyFiveMinutes(),
];
```

---

## 📋 **ZUSAMMENFASSUNG**

| Datei | Änderung | Effekt |
|-------|----------|--------|
| `VoterSlugService.php` | Prüft auf `expires_at > now()` | ✅ Nur aktive Slugs werden wiederverwendet |
| `VoterSlugService.php` | `forceNew = true` für Demo | ✅ IMMER neuer Slug für Demo |
| `ElectionStartController.php` | Übergibt `forceNew = true` | ✅ Demo-Start erzeugt neuen Slug |
| `CleanupExpiredVoterSlugs.php` | Cron-Job zum Aufräumen | ✅ Alte Slugs werden entfernt |

**Nach diesen Änderungen kannst du beliebig oft in der Demo voten!** 🚀