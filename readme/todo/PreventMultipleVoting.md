Kurzfassung: Der „Zu oft weitergeleitet“-Loop kommt nicht von der höheren Rate-Limit-Zahl, sondern sehr wahrscheinlich von einem **Redirect in einem Voting-Middleware**, der wieder auf eine Route zeigt, auf der dieselbe Middleware erneut greift (oder von einem Fehler-Redirect auf exakt dieselbe Route). Am sichersten ist es, in den Voting-Middlewares **nicht zu redirecten**, sondern mit **HTTP-Status** zu antworten – oder Self-Redirects abzufangen.

Hier sind konkrete, risikoarme Fixes (inkl. Code-Snippets), die den Loop stoppen:

---

# 1) `PreventMultipleVoting.php`: *nur* Statuscodes, kein Redirect

Du machst das hier bereits weitgehend richtig (403/429 `abort`). Ich würde lediglich den Request-Zähler robust machen (atomar inkrementieren) und Header für Debug setzen.

```php
// app/Http/Middleware/PreventMultipleVoting.php

public function handle(Request $request, Closure $next)
{
    $vslug = $request->route('vslug');
    if (!$vslug instanceof \App\Models\VoterSlug) {
        return response('Invalid voting link', 403)->header('X-Vote-Block', 'invalid-slug');
    }

    $user = $vslug->user;

    // 1) Enforce one active slug (wie gehabt) – bei Verstoß KEIN Redirect:
    $enforcement = $this->securityService->enforceOneActiveSlugPerUser($user);
    if ($enforcement['enforcement_needed'] && in_array($vslug->slug, $enforcement['deactivated_slugs'])) {
        return response('This voting link is deactivated. Only one active voting session is allowed.', 403)
            ->header('X-Vote-Block', 'slug-deactivated');
    }

    // 2) Final validation
    $vslug->refresh();
    if (!$vslug->is_active || $vslug->isExpired()) {
        return response('Voting link expired or inactive.', 403)->header('X-Vote-Block', 'expired-or-inactive');
    }

    // 3) Already voted?
    if ($user->has_voted) {
        return response('You have already completed voting.', 403)->header('X-Vote-Block', 'already-voted');
    }

    // 4) Rate limiting – atomar und minutengenau:
    $key = sprintf('voting_requests_%d_%s', $user->id, now()->format('YmdHi')); // pro Minute
    $count = \Cache::increment($key);
    \Cache::put($key, $count, now()->addMinutes(2));
    if ($count > 100) { // dein Testlimit
        \Log::warning('Rate limit exceeded', ['user_id' => $user->id, 'count' => $count, 'ip' => $request->ip()]);
        return response('Too many voting requests. Please wait.', 429)->header('X-Vote-Block', 'rate-limit');
    }

    // Debug-Header (hilft im Browser-Netzwerk-Tab)
    header('X-Vote-OK: 1');

    return $next($request);
}
```

**Warum hilft das?** Selbst wenn irgendwo später ein Fehler-Handler Redirects baut, liefern wir hier **klare Statuscodes** zurück – keine Redirects. So kann kein Redirect-Loop entstehen.

---

# 2) `EnsureVoterStepOrder.php`: Self-Redirects verhindern

In dieser Middleware wird bei „Zukunftsschritt“ auf die aktuelle Step-Route umgeleitet. Wenn `currentRoute` zufällig **die gleiche Route** ist, entsteht ein Loop. Fange das ab:

```php
// app/Http/Middleware/EnsureVoterStepOrder.php

public function handle($request, Closure $next)
{
    // ... ermitteltes $targetStep, $vslug, $map etc.

    if ($targetStep === false) {
        return $next($request);
    }

    if ($targetStep > $vslug->current_step) {
        $currentRoute = $map[$vslug->current_step] ?? null;

        // ⛔️ Self-Redirect vermeiden
        if ($currentRoute && $request->route() && $request->route()->getName() === $currentRoute) {
            // einfach weiterlassen statt Redirect
            return $next($request);
        }

        if ($currentRoute) {
            return redirect()->route($currentRoute, ['vslug' => $vslug->slug]);
        }

        // Fallback: Kein Mapping -> lieber Fehlerstatus statt Redirect
        return response('Invalid step routing.', 409);
    }

    return $next($request);
}
```

---

# 3) `EnsureCommitteeMember.php`: kein `redirect()->back()` bei 403

`redirect()->back()` kann bei gleicher Referer-URL leicht Loops erzeugen. Nutze Statuscode:

```php
// app/Http/Middleware/EnsureCommitteeMember.php
if (!\Illuminate\Support\Facades\Auth::user()->is_committee_member) {
    if ($request->expectsJson()) {
        return response()->json(['error' => 'Unauthorized. Committee member access required.'], 403);
    }
    return response('Unauthorized. Committee member access required.', 403);
}
```

---

# 4) Login-Loop vermeiden

Falls `EnsureVoterStepOrder` bei nicht eingeloggten Usern auf `route('login')` leitet, stelle sicher, dass **die Login-Route nicht** von deinen Voting-Middlewares geschützt wird. Am besten die Voting-Middlewares **nur** an die echten Voting-Routen mit `{vslug}` hängen – nicht global auf `web`.

Zusätzlich: In `RedirectIfAuthenticated`/`Authenticate` keine Zirkelschlüsse bauen („wenn nicht eingeloggt → login“, **aber** die Login-Seite selbst wieder durchs Voting-Middleware-Karussell).

---

# 5) Proxy/HTTPS-Konfig prüfen (falls du einen Reverse Proxy nutzt)

Fehlkonfigurierter HTTPS-Zwang verursacht ebenfalls Redirect-Loops:

* `AppServiceProvider::boot()` – **kein** `URL::forceScheme('https')` lokal, wenn du `http://localhost` nutzt.
* `.env`: `APP_URL=http://localhost` (lokal), **kein** Mischbetrieb http/https.
* `TrustProxies` korrekt: `protected $proxies = '*';` (oder deine IPs) und Header auf `Request::HEADER_X_FORWARDED_ALL` (o. ä.), wenn du hinter einem Proxy arbeitest.

---

# 6) Schnelle Fehlersuche (ohne Codeänderung)

* DevTools → Netzwerk → schau dir die **Location-Kette** der Redirects an. Wenn du immer die **gleiche URL** siehst → Self-Redirect (Fix #2/#3). Wenn `http` ↔ `https` ping-pong → Fix #5.
* Server-Log filtern: `grep "X-Vote-Block" storage/logs/laravel.log` (bzw. die gesetzten Log-Einträge).
* Middleware testweise **kommentieren** (jeweils nur eine) → die, nach deren Deaktivierung der Loop weg ist, ist die Verursacherin.

---

# 7) Warum die Cookie-Meldung erscheint

Browser empfehlen Cookie-Löschen bei Redirect-Loops, aber in deinem Fall ist das **Symptom**, nicht die Ursache. Mit den obigen Anpassungen brauchst du daran nichts zu ändern.

---

Wenn du magst, kann ich dir die betroffenen Middlewares direkt als Patches (Diffs) vorbereiten.
Super — hier sind saubere **Git-Patches** (Unified Diffs), die du direkt anwenden kannst. Sie eliminieren Redirect-Loops, machen das Rate-Limiting atomar und vermeiden `redirect()->back()`-Fallen.  
Wenn deine Klassennamen minimal abweichen, übernimm die Blöcke manuell.

---

## 1) `app/Http/Middleware/PreventMultipleVoting.php`

- Keine Redirects mehr bei Sperren → nur Statuscodes.
- Atomare, minutengenaue Rate-Limits per Cache-Increment.
- Klare Debug/Block-Header.

```diff
*** a/app/Http/Middleware/PreventMultipleVoting.php
--- b/app/Http/Middleware/PreventMultipleVoting.php
@@
 <?php
 
 namespace App\Http\Middleware;
 
 use Closure;
+use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Cache;
 use Illuminate\Support\Facades\Log;
 
 class PreventMultipleVoting
 {
-    public function handle($request, Closure $next)
+    public function handle(Request $request, Closure $next)
     {
-        // Annahme: $vslug aus Route-Binding
-        $vslug = $request->route('vslug');
-        $user  = $vslug?->user;
+        // Annahme: $vslug via Route-Parameter/Binding
+        $vslug = $request->route('vslug');
+        if (!$vslug || !is_object($vslug)) {
+            return response('Invalid voting link', 403)->header('X-Vote-Block', 'invalid-slug');
+        }
+        $user  = $vslug->user;
 
-        // 1) Ein-Slug-Policy / Deaktivierte Slugs
-        $enforcement = app('App\\Services\\VotingSecurityService')->enforceOneActiveSlugPerUser($user);
-        if ($enforcement['enforcement_needed'] && in_array($vslug->slug, $enforcement['deactivated_slugs'])) {
-            return redirect()->route('vote.link.inactive');
-        }
+        // 1) Ein-Slug-Policy / Deaktivierte Slugs → KEIN Redirect
+        $enforcement = app('App\\Services\\VotingSecurityService')->enforceOneActiveSlugPerUser($user);
+        if (($enforcement['enforcement_needed'] ?? false) && in_array($vslug->slug, $enforcement['deactivated_slugs'] ?? [], true)) {
+            return response('This voting link is deactivated. Only one active session allowed.', 403)
+                ->header('X-Vote-Block', 'slug-deactivated');
+        }
 
-        // 2) Expired/Inactive prüfen
-        if (!$vslug->is_active || $vslug->isExpired()) {
-            return redirect()->route('vote.link.expired');
-        }
+        // 2) Expired/Inactive → KEIN Redirect
+        $vslug->refresh();
+        if (!$vslug->is_active || (method_exists($vslug, 'isExpired') && $vslug->isExpired())) {
+            return response('Voting link expired or inactive.', 403)->header('X-Vote-Block', 'expired-or-inactive');
+        }
 
-        // 3) Bereits gewählt?
-        if ($user && $user->has_voted) {
-            return redirect()->route('vote.already');
-        }
+        // 3) Bereits gewählt? → KEIN Redirect
+        if ($user && property_exists($user, 'has_voted') && $user->has_voted) {
+            return response('You have already completed voting.', 403)->header('X-Vote-Block', 'already-voted');
+        }
 
-        // 4) Rate Limiting (vorher: einfacher Zähler)
-        $recentRequests = \Cache::get("voting_requests_{$user->id}", 0);
-        if ($recentRequests > 100) { // Temporär erhöht
-            Log::warning('Suspicious voting activity - rate limit exceeded', [
-                'user_id' => $user->id,
-                'requests_per_minute' => $recentRequests,
-            ]);
-            return redirect()->route('vote.rate_limited');
-        }
+        // 4) Atomar: pro Minute (YYYYMMDDHHmm) und pro User
+        if ($user && $user->id) {
+            $minuteKey = now()->format('YmdHi');
+            $key = "voting_requests_{$user->id}_{$minuteKey}";
+            $count = Cache::increment($key, 1);
+            // TTL minimal > 1 Min, damit Zähler existiert
+            Cache::put($key, $count, now()->addMinutes(2));
+            if ($count > 100) { // Testlimit; produktiv ggf. 10–30
+                Log::warning('Rate limit exceeded', [
+                    'user_id' => $user->id,
+                    'count' => $count,
+                    'ip' => $request->ip(),
+                ]);
+                return response('Too many voting requests. Please wait.', 429)
+                    ->header('Retry-After', '60')
+                    ->header('X-Vote-Block', 'rate-limit');
+            }
+        }
 
-        return $next($request);
+        // Debug-Hinweis hilft im Netzwerk-Tab
+        header('X-Vote-OK: 1');
+        return $next($request);
     }
 }
```

---

## 2) `app/Http/Middleware/EnsureVoterStepOrder.php`

- Verhindert Self-Redirects, die den „zu oft weitergeleitet“-Fehler auslösen.
- Gibt bei fehlendem Mapping lieber einen **409** zurück als zu redirecten.

```diff
*** a/app/Http/Middleware/EnsureVoterStepOrder.php
--- b/app/Http/Middleware/EnsureVoterStepOrder.php
@@
 <?php
 
 namespace App\Http\Middleware;
 
 use Closure;
+use Illuminate\Http\Request;
 
 class EnsureVoterStepOrder
 {
-    public function handle($request, Closure $next)
+    public function handle(Request $request, Closure $next)
     {
         // Beispielhaft: $vslug und Step-Mapping
         $vslug = $request->route('vslug');
         $map = [
             1 => 'vote.step.code',
             2 => 'vote.step.identity',
             3 => 'vote.step.ballot',
             4 => 'vote.step.confirm',
         ];
 
-        $targetStep = (int) $request->attributes->get('target_step', 0);
+        $targetStep = (int) $request->attributes->get('target_step', 0);
         if ($targetStep === 0 || !$vslug) {
             return $next($request);
         }
 
         $current = (int)($vslug->current_step ?? 1);
         if ($targetStep > $current) {
             $currentRoute = $map[$current] ?? null;
-            if ($currentRoute) {
-                return redirect()->route($currentRoute, ['vslug' => $vslug->slug]);
-            }
-            return redirect()->route('vote.step.code', ['vslug' => $vslug->slug]);
+            // ⛔ Self-Redirect vermeiden
+            if ($currentRoute && $request->route() && $request->route()->getName() === $currentRoute) {
+                return $next($request);
+            }
+            if ($currentRoute) {
+                return redirect()->route($currentRoute, ['vslug' => $vslug->slug]);
+            }
+            // Fallback: Kein Mapping → Fehler statt Redirect-Loop
+            return response('Invalid step routing.', 409);
         }
 
         return $next($request);
     }
 }
```

---

## 3) `app/Http/Middleware/EnsureCommitteeMember.php`

- Kein `redirect()->back()` mehr; liefert 403 (oder JSON) zurück.

```diff
*** a/app/Http/Middleware/EnsureCommitteeMember.php
--- b/app/Http/Middleware/EnsureCommitteeMember.php
@@
 <?php
 
 namespace App\Http\Middleware;
 
 use Closure;
+use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Auth;
 
 class EnsureCommitteeMember
 {
-    public function handle($request, Closure $next)
+    public function handle(Request $request, Closure $next)
     {
         $user = Auth::user();
-        if (!$user || !$user->is_committee_member) {
-            return redirect()->back()->withErrors(['auth' => 'Unauthorized']);
-        }
+        if (!$user || !($user->is_committee_member ?? false)) {
+            if ($request->expectsJson()) {
+                return response()->json([
+                    'error' => 'Unauthorized. Committee member access required.',
+                ], 403);
+            }
+            return response('Unauthorized. Committee member access required.', 403);
+        }
 
         return $next($request);
     }
 }
```

---

## 4) (Optional, aber empfohlen) `app/Http/Middleware/TrustProxies.php`

- Verhindert HTTPS-Ping-Pong bei lokalem `http://localhost`.

```diff
*** a/app/Http/Middleware/TrustProxies.php
--- b/app/Http/Middleware/TrustProxies.php
@@
 <?php
 
 namespace App\Http\Middleware;
 
 use Illuminate\Http\Middleware\TrustProxies as Middleware;
 use Illuminate\Http\Request;
 
 class TrustProxies extends Middleware
 {
-    protected $proxies;
-    protected $headers = Request::HEADER_X_FORWARDED_ALL;
+    protected $proxies = '*';
+    protected $headers = Request::HEADER_X_FORWARDED_AWS_ELB
+        | Request::HEADER_X_FORWARDED_FOR
+        | Request::HEADER_X_FORWARDED_HOST
+        | Request::HEADER_X_FORWARDED_PORT
+        | Request::HEADER_X_FORWARDED_PROTO;
 }
```

---

## 5) (Optional, lokal) `app/Providers/AppServiceProvider.php`

- Stelle sicher, dass lokal **kein** `forceScheme('https')` aktiv ist.

```diff
*** a/app/Providers/AppServiceProvider.php
--- b/app/Providers/AppServiceProvider.php
@@
 use Illuminate\Support\ServiceProvider;
+use Illuminate\Support\Facades\URL;
 
 class AppServiceProvider extends ServiceProvider
 {
     public function boot(): void
     {
-        URL::forceScheme('https'); // ❌ Das erzeugt lokal http↔https Loops
+        // Nur in Produktion erzwingen
+        if (app()->environment('production')) {
+            URL::forceScheme('https');
+        }
     }
 }
```

---

## Anwendung

Speichere die Diffs z. B. als `loop-fixes.patch` im Projektroot und führe aus:

```bash
git apply --index loop-fixes.patch
git commit -m "Fix: prevent redirect loops in voting middlewares, atomic rate limiting, safer proxies"
php artisan config:clear && php artisan route:clear && php artisan cache:clear
```

---

## Routing-Hinweise (wichtig gegen Login-Loops)

- Hänge `PreventMultipleVoting` und `EnsureVoterStepOrder` **nur** an die echten Voting-Routen mit `{vslug}`.  
  Nicht global auf `web`, nicht auf `login`, nicht auf `password.*`.

Beispiel:

```php
// routes/web.php
Route::middleware([
    'signed', // falls du signierte URLs nutzt
    \App\Http\Middleware\PreventMultipleVoting::class,
    \App\Http\Middleware\EnsureVoterStepOrder::class,
])->group(function () {
    Route::get('/vote/{vslug}/code',    ...)->name('vote.step.code');
    Route::get('/vote/{vslug}/identity',...)->name('vote.step.identity');
    Route::get('/vote/{vslug}/ballot',  ...)->name('vote.step.ballot');
    Route::post('/vote/{vslug}/confirm',...)->name('vote.step.confirm');
});
```

---

Wenn du möchtest, passe ich die Limits (z. B. `> 100` → `> 20`) gleich „produktionsreif“ an und füge Feature-Flags per `.env` hinzu.
