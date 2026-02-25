<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Track Performance Middleware
 *
 * Monitors response times and performance metrics
 * Stores hourly aggregated data in cache
 */
class TrackPerformance
{
    /**
     * Handle request and track performance
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $startTime = microtime(true);

        $response = $next($request);

        $duration = (microtime(true) - $startTime) * 1000; // Convert to ms

        // Record metric if appropriate
        // Use getStatusCode() instead of status() for compatibility with all response types
        // (e.g., BinaryFileResponse, StreamedResponse, etc.)
        $statusCode = method_exists($response, 'getStatusCode') ? $response->getStatusCode() : 200;

        $this->recordMetric(
            $request->path(),
            $request->method(),
            $statusCode,
            $duration
        );

        // Add response time header (only if response supports it)
        if (method_exists($response, 'header')) {
            $response->header('X-Response-Time', round($duration, 2) . 'ms');
        }

        return $response;
    }

    /**
     * Record performance metric
     *
     * @param string $path
     * @param string $method
     * @param int $status
     * @param float $duration
     * @return void
     */
    private function recordMetric(string $path, string $method, int $status, float $duration): void
    {
        if (!$this->shouldTrack($path)) {
            return;
        }

        try {
            // Create hourly key for aggregation
            $key = 'performance:' . date('Y-m-d:H') . ':' . md5($method . $path);

            // Retrieve existing metrics
            $data = Cache::get($key, $this->getEmptyMetrics());

            // Update metrics
            $data['count']++;
            $data['total_duration'] += $duration;
            $data['min_duration'] = min($data['min_duration'], $duration);
            $data['max_duration'] = max($data['max_duration'], $duration);
            $data['status_codes'][$status] = ($data['status_codes'][$status] ?? 0) + 1;
            $data['last_updated'] = now()->toIso8601String();

            // Store updated metrics (24 hour TTL)
            Cache::put($key, $data, now()->addHours(24));
        } catch (\Exception $e) {
            \Log::warning('Failed to track performance metric: ' . $e->getMessage());
        }
    }

    /**
     * Get empty metrics structure
     *
     * @return array
     */
    private function getEmptyMetrics(): array
    {
        return [
            'count' => 0,
            'total_duration' => 0,
            'min_duration' => PHP_INT_MAX,
            'max_duration' => 0,
            'status_codes' => [],
            'last_updated' => now()->toIso8601String()
        ];
    }

    /**
     * Determine if path should be tracked
     *
     * @param string $path
     * @return bool
     */
    private function shouldTrack(string $path): bool
    {
        // Don't track static assets
        $excludedPaths = [
            '/sitemap',
            '/robots.txt',
            '/api/',
            '/mapi/',
            '/.well-known/',
            '/favicon.ico',
            '/css/',
            '/js/',
            '/images/',
            '/_ignition/',
        ];

        foreach ($excludedPaths as $excluded) {
            if (str_starts_with($path, $excluded)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Calculate average duration
     *
     * @param array $metrics
     * @return float
     */
    public static function calculateAverageDuration(array $metrics): float
    {
        if ($metrics['count'] === 0) {
            return 0;
        }

        return $metrics['total_duration'] / $metrics['count'];
    }

    /**
     * Get success rate
     *
     * @param array $metrics
     * @return float
     */
    public static function getSuccessRate(array $metrics): float
    {
        if ($metrics['count'] === 0) {
            return 0;
        }

        $successCount = ($metrics['status_codes'][200] ?? 0) +
                       ($metrics['status_codes'][201] ?? 0) +
                       ($metrics['status_codes'][204] ?? 0) +
                       ($metrics['status_codes'][301] ?? 0) +
                       ($metrics['status_codes'][302] ?? 0);

        return ($successCount / $metrics['count']) * 100;
    }
}
