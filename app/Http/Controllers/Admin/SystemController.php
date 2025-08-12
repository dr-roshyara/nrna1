<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Vote;
use App\Models\User;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SystemController extends Controller
{
    /**
     * Show system status dashboard
     */
    public function status()
    {
        $systemStats = $this->getSystemStats();
        $databaseStats = $this->getDatabaseStats();
        $performanceMetrics = $this->getPerformanceMetrics();
        $securityStatus = $this->getSecurityStatus();
        $recentAlerts = $this->getRecentAlerts();

        return Inertia::render('Admin/System/Status', [
            'systemStats' => $systemStats,
            'databaseStats' => $databaseStats,
            'performanceMetrics' => $performanceMetrics,
            'securityStatus' => $securityStatus,
            'recentAlerts' => $recentAlerts,
        ]);
    }

    /**
     * Get system statistics
     */
    private function getSystemStats()
    {
        return [
            'server_info' => [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'server_time' => now()->toDateTimeString(),
                'timezone' => config('app.timezone'),
                'environment' => config('app.env'),
                'debug_mode' => config('app.debug'),
            ],
            'disk_usage' => [
                'total_space' => $this->formatBytes(disk_total_space('/')),
                'free_space' => $this->formatBytes(disk_free_space('/')),
                'used_percentage' => $this->getDiskUsagePercentage(),
            ],
            'memory_usage' => [
                'current' => $this->formatBytes(memory_get_usage(true)),
                'peak' => $this->formatBytes(memory_get_peak_usage(true)),
                'limit' => ini_get('memory_limit'),
            ],
        ];
    }

    /**
     * Get database statistics
     */
    private function getDatabaseStats()
    {
        return [
            'connection_status' => $this->checkDatabaseConnection(),
            'tables' => [
                'users' => User::count(),
                'votes' => Vote::count(),
                'elections' => Election::count(),
                'publishers' => Publisher::count(),
            ],
            'recent_activity' => [
                'votes_last_hour' => Vote::where('created_at', '>=', now()->subHour())->count(),
                'users_registered_today' => User::whereDate('created_at', today())->count(),
                'active_sessions' => $this->getActiveSessionCount(),
            ],
        ];
    }

    /**
     * Get performance metrics
     */
    private function getPerformanceMetrics()
    {
        return [
            'cache_status' => [
                'enabled' => Cache::getStore() instanceof \Illuminate\Cache\NullStore ? false : true,
                'driver' => config('cache.default'),
                'hit_ratio' => $this->getCacheHitRatio(),
            ],
            'queue_status' => [
                'connection' => config('queue.default'),
                'pending_jobs' => $this->getPendingJobsCount(),
                'failed_jobs' => $this->getFailedJobsCount(),
            ],
            'response_times' => [
                'average_response_time' => $this->getAverageResponseTime(),
                'database_query_time' => $this->getAverageDatabaseQueryTime(),
            ],
        ];
    }

    /**
     * Get security status
     */
    private function getSecurityStatus()
    {
        return [
            'ssl_status' => request()->isSecure(),
            'failed_login_attempts' => $this->getFailedLoginAttempts(),
            'suspicious_activities' => $this->getSuspiciousActivities(),
            'last_security_scan' => $this->getLastSecurityScanDate(),
            'firewall_status' => 'active', // This would be determined by your security setup
        ];
    }

    /**
     * Get recent system alerts
     */
    private function getRecentAlerts()
    {
        // This would typically come from a system alerts/logs table
        return [
            [
                'id' => 1,
                'type' => 'info',
                'message' => 'System backup completed successfully',
                'timestamp' => now()->subMinutes(30),
                'resolved' => true,
            ],
            [
                'id' => 2,
                'type' => 'warning',
                'message' => 'High memory usage detected',
                'timestamp' => now()->subHours(2),
                'resolved' => false,
            ],
            // Add more alerts as needed
        ];
    }

    /**
     * Helper methods
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    private function getDiskUsagePercentage()
    {
        $total = disk_total_space('/');
        $free = disk_free_space('/');
        $used = $total - $free;
        
        return round(($used / $total) * 100, 2);
    }

    private function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            return 'connected';
        } catch (\Exception $e) {
            return 'disconnected';
        }
    }

    private function getActiveSessionCount()
    {
        // This depends on your session storage
        // For database sessions, you could count active sessions
        return DB::table('sessions')->count();
    }

    private function getCacheHitRatio()
    {
        // This would require implementing cache hit/miss tracking
        // Return a sample value for now
        return 85.2;
    }

    private function getPendingJobsCount()
    {
        try {
            return DB::table('jobs')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getFailedJobsCount()
    {
        try {
            return DB::table('failed_jobs')->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getAverageResponseTime()
    {
        // This would require implementing response time tracking
        return '120ms';
    }

    private function getAverageDatabaseQueryTime()
    {
        // This would require implementing query time tracking
        return '25ms';
    }

    private function getFailedLoginAttempts()
    {
        // Count failed login attempts in the last 24 hours
        // This would depend on your authentication logging
        return 5;
    }

    private function getSuspiciousActivities()
    {
        // Count suspicious activities in the last 24 hours
        // This would depend on your security monitoring
        return 2;
    }

    private function getLastSecurityScanDate()
    {
        // Return the last security scan date
        // This would be stored in your security logs
        return now()->subDays(3)->toDateString();
    }
}