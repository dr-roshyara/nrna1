# Phase 2 P1 Implementation Plan - Structured Data + Performance Monitoring

**Status:** Planning → Implementation
**Scope:** Breadcrumb Schema + Event Schema + Performance Monitoring
**Duration:** 2-3 days
**Depends On:** Phase 1 + Phase 2 P0 ✅ Complete
**Priority:** P1 - Enhances SEO value

---

## 📋 Phase 2 P1 Deliverables

### 1. Breadcrumb JSON-LD Schema
**Current State:** No breadcrumbs
**Target State:**
- Semantic breadcrumb navigation
- JSON-LD BreadcrumbList schema
- Dynamic based on route
- Multi-language support
- Search result enhancement

**Impact:** Rich snippets in Google search results

### 2. Event Schema (Elections)
**Current State:** Elections not marked as events
**Target State:**
- Elections as Event schema
- Start/end dates
- organisation as organizer
- Vote count as attendance
- Structured event data

**Impact:** Elections visible in Google Events

### 3. organisation Schema Enhancement
**Current State:** Basic organisation schema
**Target State:**
- Enhanced with member count
- Election count
- Description
- Logo/image
- Contact info

**Impact:** Better knowledge graph integration

### 4. Performance Monitoring
**Current State:** No performance tracking
**Target State:**
- Core Web Vitals monitoring
- Page load metrics
- SEO performance dashboard
- Analytics integration

**Impact:** Data-driven optimization

---

## 🗂️ Implementation Structure

```
app/
├── Helpers/
│   ├── SchemaGenerator.php (ENHANCE - add Breadcrumb, Event, organisation)
│   └── BreadcrumbHelper.php (CREATE - generate breadcrumb data)
│
├── Http/
│   ├── Middleware/
│   │   └── TrackPerformance.php (CREATE - performance monitoring)
│   └── Controllers/
│       └── AnalyticsController.php (CREATE - performance dashboard)

resources/
├── js/
│   ├── composables/
│   │   ├── useBreadcrumbs.js (CREATE - breadcrumb management)
│   │   └── usePerformance.js (CREATE - performance metrics)
│   ├── components/
│   │   ├── BreadcrumbSchema.vue (CREATE - JSON-LD breadcrumbs)
│   │   └── PerformanceMonitor.vue (CREATE - dev tool)

tests/
└── Unit/
    ├── SchemaGeneratorTest.php (CREATE - schema tests)
    ├── BreadcrumbHelperTest.php (CREATE - breadcrumb tests)
    └── PerformanceTrackingTest.php (CREATE - monitoring tests)
```

---

## 🧬 1. Breadcrumb Schema Implementation

### 1.1 Create BreadcrumbHelper
**File:** `app/Helpers/BreadcrumbHelper.php`

```php
<?php

namespace App\Helpers;

class BreadcrumbHelper
{
    /**
     * Generate breadcrumb array for current route
     *
     * Returns structured data for both HTML and JSON-LD
     */
    public static function generateBreadcrumbs(string $route, array $params = []): array
    {
        $breadcrumbs = [
            ['label' => 'Home', 'url' => url('/')],
        ];

        // Parse route and build breadcrumbs
        $segments = explode('.', $route);

        // Route-specific breadcrumbs
        match ($route) {
            'organizations.show' => $breadcrumbs = self::organizationBreadcrumbs($breadcrumbs, $params),
            'election.dashboard' => $breadcrumbs = self::electionBreadcrumbs($breadcrumbs, $params),
            'pricing' => $breadcrumbs = self::pricingBreadcrumbs($breadcrumbs),
            default => null
        };

        return $breadcrumbs;
    }

    private static function organizationBreadcrumbs(array $breadcrumbs, array $params): array
    {
        $breadcrumbs[] = [
            'label' => trans('sitemap.sections.organizations'),
            'url' => url('/organizations')
        ];

        if ($params['organisation'] ?? null) {
            $breadcrumbs[] = [
                'label' => $params['organisation']->name,
                'url' => route('organizations.show', ['slug' => $params['organisation']->slug])
            ];
        }

        return $breadcrumbs;
    }

    private static function electionBreadcrumbs(array $breadcrumbs, array $params): array
    {
        $breadcrumbs[] = [
            'label' => trans('sitemap.sections.elections'),
            'url' => url('/elections')
        ];

        if ($params['election'] ?? null) {
            $breadcrumbs[] = [
                'label' => $params['election']->name,
                'url' => url('/election/' . $params['election']->id)
            ];
        }

        return $breadcrumbs;
    }

    private static function pricingBreadcrumbs(array $breadcrumbs): array
    {
        $breadcrumbs[] = [
            'label' => trans('seo.pages.pricing.title'),
            'url' => route('pricing')
        ];

        return $breadcrumbs;
    }

    /**
     * Generate JSON-LD schema for breadcrumbs
     */
    public static function generateJsonLd(array $breadcrumbs): array
    {
        $items = [];

        foreach ($breadcrumbs as $index => $breadcrumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $breadcrumb['label'],
                'item' => $breadcrumb['url']
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items
        ];
    }
}
```

### 1.2 Create BreadcrumbSchema Component
**File:** `resources/js/components/BreadcrumbSchema.vue`

```vue
<template>
  <div class="breadcrumb-container">
    <!-- HTML Breadcrumbs for UX -->
    <nav class="breadcrumb-nav" aria-label="breadcrumb">
      <ol class="breadcrumb-list">
        <li v-for="(item, index) in breadcrumbs" :key="index" class="breadcrumb-item">
          <a v-if="index < breadcrumbs.length - 1" :href="item.url" class="breadcrumb-link">
            {{ item.label }}
          </a>
          <span v-else class="breadcrumb-current">
            {{ item.label }}
          </span>
        </li>
      </ol>
    </nav>

    <!-- JSON-LD Schema (hidden) -->
    <script type="application/ld+json" v-html="jsonLdSchema"></script>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3-vue3'

const page = usePage()

const props = defineProps({
  breadcrumbs: {
    type: Array,
    required: true
  }
})

const jsonLdSchema = computed(() => {
  const items = props.breadcrumbs.map((item, index) => ({
    '@type': 'ListItem',
    'position': index + 1,
    'name': item.label,
    'item': item.url
  }))

  const schema = {
    '@context': 'https://schema.org',
    '@type': 'BreadcrumbList',
    'itemListElement': items
  }

  return JSON.stringify(schema)
})
</script>

<style scoped>
.breadcrumb-nav {
  margin-bottom: 1rem;
}

.breadcrumb-list {
  display: flex;
  flex-wrap: wrap;
  list-style: none;
  padding: 0;
  margin: 0;
  font-size: 0.875rem;
}

.breadcrumb-item {
  display: flex;
  align-items: center;
}

.breadcrumb-item + .breadcrumb-item::before {
  content: '/';
  margin: 0 0.5rem;
  color: #999;
}

.breadcrumb-link {
  color: #0066cc;
  text-decoration: none;
}

.breadcrumb-link:hover {
  text-decoration: underline;
}

.breadcrumb-current {
  color: #666;
  font-weight: 500;
}
</style>
```

### 1.3 Create useBreadcrumbs Composable
**File:** `resources/js/composables/useBreadcrumbs.js`

```javascript
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3-vue3'

export function useBreadcrumbs() {
  const page = usePage()

  const breadcrumbs = computed(() => {
    return page.props.breadcrumbs || [
      { label: 'Home', url: '/' }
    ]
  })

  const jsonLd = computed(() => {
    const items = breadcrumbs.value.map((item, index) => ({
      '@type': 'ListItem',
      'position': index + 1,
      'name': item.label,
      'item': item.url
    }))

    return {
      '@context': 'https://schema.org',
      '@type': 'BreadcrumbList',
      'itemListElement': items
    }
  })

  return {
    breadcrumbs,
    jsonLd
  }
}
```

---

## 🎭 2. Event Schema Implementation

### 2.1 Enhance SchemaGenerator for Events
**File:** `app/Helpers/SchemaGenerator.php` (Enhancement)

```php
<?php

namespace App\Helpers;

use App\Models\Election;
use App\Models\organisation;

class SchemaGenerator
{
    // ... existing methods ...

    /**
     * Generate Event schema for Election
     */
    public static function generateElectionEventSchema(Election $election): array
    {
        $organisation = $election->organisation;

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $election->name,
            'description' => $election->description,
            'startDate' => $election->start_date?->toIso8601String(),
            'endDate' => $election->end_date?->toIso8601String(),
            'eventStatus' => self::getEventStatus($election),
            'eventAttendanceMode' => 'OnlineEventAttendanceMode',
            'url' => url('/election/' . $election->id),
            'image' => $organisation?->logo_url ?? url('/images/og-default.jpg'),
            'organizer' => [
                '@type' => 'organisation',
                'name' => $organisation?->name ?? 'Public Digit',
                'url' => $organisation ? route('organizations.show', ['slug' => $organisation->slug]) : url('/')
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => url('/election/' . $election->id),
                'price' => '0',
                'priceCurrency' => 'USD'
            ],
            'performers' => [
                [
                    '@type' => 'organisation',
                    'name' => $organisation?->name ?? 'Public Digit'
                ]
            ]
        ];
    }

    /**
     * Generate enhanced organisation schema
     */
    public static function generateOrganizationSchema(organisation $org): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'organisation',
            'name' => $org->name,
            'url' => route('organizations.show', ['slug' => $org->slug]),
            'description' => $org->description ?? 'organisation on Public Digit',
            'logo' => $org->logo_url ?? url('/images/logo-2.png'),
            'email' => $org->email,
            'address' => $org->address,
            'memberCount' => $org->members_count ?? 0,
            'eventCount' => $org->elections_count ?? 0,
            'sameAs' => self::getSocialLinks($org)
        ];
    }

    private static function getEventStatus(Election $election): string
    {
        if ($election->end_date && $election->end_date->isPast()) {
            return 'EventScheduled'; // Completed
        } elseif ($election->start_date && $election->start_date->isFuture()) {
            return 'EventScheduled'; // Upcoming
        } else {
            return 'EventScheduled'; // In progress
        }
    }

    private static function getSocialLinks(organisation $org): array
    {
        $links = [];
        $settings = $org->settings ?? [];

        foreach (['website', 'facebook', 'twitter', 'linkedin'] as $platform) {
            if ($url = $settings[$platform] ?? null) {
                $links[] = $url;
            }
        }

        return $links;
    }
}
```

### 2.2 Create EventSchema Component
**File:** `resources/js/components/EventSchema.vue`

```vue
<template>
  <script type="application/ld+json" v-html="eventJsonLd"></script>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  election: {
    type: Object,
    required: true
  },
  organisation: {
    type: Object,
    default: null
  }
})

const eventJsonLd = computed(() => {
  const schema = {
    '@context': 'https://schema.org',
    '@type': 'Event',
    'name': props.election.name,
    'description': props.election.description,
    'startDate': props.election.start_date,
    'endDate': props.election.end_date,
    'eventAttendanceMode': 'OnlineEventAttendanceMode',
    'url': window.location.href,
    'organizer': {
      '@type': 'organisation',
      'name': props.organisation?.name || 'Public Digit'
    },
    'offers': {
      '@type': 'Offer',
      'url': window.location.href,
      'price': '0',
      'priceCurrency': 'USD'
    }
  }

  return JSON.stringify(schema)
})
</script>
```

---

## 📊 3. Performance Monitoring Implementation

### 3.1 Create TrackPerformance Middleware
**File:** `app/Http/Middleware/TrackPerformance.php`

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class TrackPerformance
{
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);

        $response = $next($request);

        $duration = (microtime(true) - $startTime) * 1000; // Convert to ms

        // Store performance data
        $this->recordMetric(
            $request->path(),
            $request->method(),
            $response->status(),
            $duration
        );

        // Add performance headers
        $response->header('X-Response-Time', round($duration, 2) . 'ms');

        return $response;
    }

    private function recordMetric(string $path, string $method, int $status, float $duration): void
    {
        if ($this->shouldTrack($path)) {
            $key = 'performance:' . date('Y-m-d:H') . ':' . md5($method . $path);

            $data = Cache::get($key, [
                'count' => 0,
                'total_duration' => 0,
                'min_duration' => PHP_INT_MAX,
                'max_duration' => 0,
                'status_codes' => []
            ]);

            $data['count']++;
            $data['total_duration'] += $duration;
            $data['min_duration'] = min($data['min_duration'], $duration);
            $data['max_duration'] = max($data['max_duration'], $duration);
            $data['status_codes'][$status] = ($data['status_codes'][$status] ?? 0) + 1;

            Cache::put($key, $data, now()->addHours(24));
        }
    }

    private function shouldTrack(string $path): bool
    {
        // Don't track static assets or API calls
        $excludedPaths = ['/sitemap', '/robots.txt', '/api/', '/mapi/'];

        foreach ($excludedPaths as $excluded) {
            if (str_starts_with($path, $excluded)) {
                return false;
            }
        }

        return true;
    }
}
```

### 3.2 Create PerformanceMonitor Component
**File:** `resources/js/components/PerformanceMonitor.vue`

```vue
<template>
  <div v-if="isDevelopment" class="performance-monitor">
    <div class="monitor-header">
      <h3>⚡ Performance Metrics</h3>
      <button @click="toggleExpanded" class="toggle-btn">
        {{ isExpanded ? '▼' : '▶' }}
      </button>
    </div>

    <div v-if="isExpanded" class="monitor-content">
      <div class="metric">
        <span class="label">Page Load:</span>
        <span class="value">{{ pageLoadTime }}ms</span>
      </div>
      <div class="metric">
        <span class="label">DOM Content:</span>
        <span class="value">{{ domContentTime }}ms</span>
      </div>
      <div class="metric">
        <span class="label">LCP:</span>
        <span :class="['value', lcpStatus]">{{ lcpTime }}ms</span>
      </div>
      <div class="metric">
        <span class="label">FID:</span>
        <span :class="['value', fidStatus]">{{ fidTime }}ms</span>
      </div>
      <div class="metric">
        <span class="label">CLS:</span>
        <span :class="['value', clsStatus]">{{ clsValue }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'

const isExpanded = ref(false)
const pageLoadTime = ref(0)
const domContentTime = ref(0)
const lcpTime = ref(0)
const fidTime = ref(0)
const clsValue = ref(0)

const isDevelopment = computed(() => import.meta.env.DEV)

const lcpStatus = computed(() => lcpTime.value < 2500 ? 'good' : lcpTime.value < 4000 ? 'needs-improvement' : 'poor')
const fidStatus = computed(() => fidTime.value < 100 ? 'good' : fidTime.value < 300 ? 'needs-improvement' : 'poor')
const clsStatus = computed(() => clsValue.value < 0.1 ? 'good' : clsValue.value < 0.25 ? 'needs-improvement' : 'poor')

onMounted(() => {
  if (typeof window !== 'undefined' && 'PerformanceObserver' in window) {
    // Page Load Time
    window.addEventListener('load', () => {
      const perfData = window.performance.timing
      pageLoadTime.value = perfData.loadEventEnd - perfData.navigationStart
      domContentTime.value = perfData.domContentLoadedEventEnd - perfData.navigationStart
    })

    // Largest Contentful Paint (LCP)
    new PerformanceObserver((list) => {
      const entries = list.getEntries()
      const lastEntry = entries[entries.length - 1]
      lcpTime.value = Math.round(lastEntry.renderTime || lastEntry.loadTime)
    }).observe({ entryTypes: ['largest-contentful-paint'] })

    // First Input Delay (FID)
    new PerformanceObserver((list) => {
      const entries = list.getEntries()
      entries.forEach((entry) => {
        fidTime.value = Math.round(entry.processingDuration)
      })
    }).observe({ entryTypes: ['first-input'] })

    // Cumulative Layout Shift (CLS)
    new PerformanceObserver((list) => {
      list.getEntries().forEach((entry) => {
        if (!entry.hadRecentInput) {
          clsValue.value += entry.value
        }
      })
    }).observe({ entryTypes: ['layout-shift'] })
  }
})

const toggleExpanded = () => {
  isExpanded.value = !isExpanded.value
}
</script>

<style scoped>
.performance-monitor {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background: #222;
  color: #0f0;
  border: 2px solid #0f0;
  border-radius: 8px;
  padding: 12px;
  font-family: monospace;
  font-size: 12px;
  max-width: 300px;
  z-index: 9999;
}

.monitor-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 10px;
  cursor: pointer;
}

.monitor-header h3 {
  margin: 0;
  font-size: 14px;
}

.toggle-btn {
  background: none;
  border: none;
  color: #0f0;
  cursor: pointer;
  font-size: 12px;
  padding: 0;
}

.monitor-content {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.metric {
  display: flex;
  justify-content: space-between;
  gap: 10px;
}

.label {
  color: #0f0;
}

.value {
  color: #fff;
  font-weight: bold;
}

.value.good {
  color: #0f0;
}

.value.needs-improvement {
  color: #ff0;
}

.value.poor {
  color: #f00;
}
</style>
```

---

## 🧪 4. Testing

### 4.1 Create Tests
**File:** `tests/Unit/SchemaGeneratorTest.php`

```php
<?php

namespace Tests\Unit;

use App\Helpers\SchemaGenerator;
use App\Models\Election;
use App\Models\organisation;
use Tests\TestCase;

class SchemaGeneratorTest extends TestCase
{
    public function test_generates_valid_event_schema()
    {
        $election = Election::factory()->create();
        $schema = SchemaGenerator::generateElectionEventSchema($election);

        $this->assertEquals('https://schema.org', $schema['@context']);
        $this->assertEquals('Event', $schema['@type']);
        $this->assertNotEmpty($schema['name']);
        $this->assertNotEmpty($schema['startDate']);
    }

    public function test_generates_organization_schema()
    {
        $org = organisation::factory()->create();
        $schema = SchemaGenerator::generateOrganizationSchema($org);

        $this->assertEquals('https://schema.org', $schema['@context']);
        $this->assertEquals('organisation', $schema['@type']);
        $this->assertEquals($org->name, $schema['name']);
    }
}
```

---

## 📝 File Changes Summary

### New Files (7)
- `app/Helpers/BreadcrumbHelper.php`
- `resources/js/composables/useBreadcrumbs.js`
- `resources/js/components/BreadcrumbSchema.vue`
- `resources/js/components/EventSchema.vue`
- `app/Http/Middleware/TrackPerformance.php`
- `resources/js/components/PerformanceMonitor.vue`
- `tests/Unit/SchemaGeneratorTest.php`

### Modified Files (3)
- `app/Helpers/SchemaGenerator.php` - Add Event & organisation schemas
- `app/Http/Middleware/HandleInertiaRequests.php` - Add breadcrumb props
- `app/Http/Kernel.php` - Register TrackPerformance middleware

---

## ✅ Verification Checklist

- [ ] Breadcrumb components render correctly
- [ ] JSON-LD breadcrumbs valid (schema.org validator)
- [ ] Event schema shows in Google Rich Results
- [ ] organisation schema enhanced
- [ ] Performance metrics tracking
- [ ] No console errors
- [ ] All tests pass
- [ ] Mobile responsive

---

## 🎯 Success Criteria

1. **Breadcrumbs Working:**
   - ✅ HTML breadcrumb navigation visible
   - ✅ JSON-LD schema valid
   - ✅ Rich snippet in search results

2. **Event Schema Active:**
   - ✅ Elections marked as events
   - ✅ Schema.org validation passes
   - ✅ Events visible in Google Events

3. **Performance Tracked:**
   - ✅ Metrics collected
   - ✅ Dashboard accessible
   - ✅ Core Web Vitals monitored

---

**Ready to implement Phase 2 P1?**
