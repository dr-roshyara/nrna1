<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class LogController extends Controller
{
    /**
     * Show logs dashboard
     */
    public function index(Request $request)
    {
        $logFiles = $this->getLogFiles();
        $selectedLog = $request->get('log', 'laravel.log');
        $logContent = $this->getLogContent($selectedLog);
        $logStats = $this->getLogStatistics($selectedLog);

        return Inertia::render('Admin/Logs/Index', [
            'logFiles' => $logFiles,
            'selectedLog' => $selectedLog,
            'logContent' => $logContent,
            'logStats' => $logStats,
            'filters' => [
                'level' => $request->get('level', 'all'),
                'date' => $request->get('date', 'today'),
                'search' => $request->get('search', ''),
            ],
        ]);
    }

    /**
     * Get available log files
     */
    private function getLogFiles()
    {
        $logPath = storage_path('logs');
        
        if (!File::exists($logPath)) {
            return [];
        }

        $files = File::files($logPath);
        $logFiles = [];

        foreach ($files as $file) {
            if (Str::endsWith($file->getFilename(), '.log')) {
                $logFiles[] = [
                    'name' => $file->getFilename(),
                    'size' => $this->formatFileSize($file->getSize()),
                    'modified' => date('Y-m-d H:i:s', $file->getMTime()),
                    'path' => $file->getPathname(),
                ];
            }
        }

        // Sort by modification time (newest first)
        usort($logFiles, function($a, $b) {
            return strcmp($b['modified'], $a['modified']);
        });

        return $logFiles;
    }

    /**
     * Get log content with parsing
     */
    private function getLogContent($logFileName, $lines = 1000)
    {
        $logPath = storage_path('logs/' . $logFileName);
        
        if (!File::exists($logPath)) {
            return [];
        }

        try {
            // Read the last N lines of the log file
            $content = $this->readLastLines($logPath, $lines);
            return $this->parseLogContent($content);
        } catch (\Exception $e) {
            return [
                [
                    'level' => 'error',
                    'timestamp' => now()->toDateTimeString(),
                    'message' => 'Failed to read log file: ' . $e->getMessage(),
                    'context' => [],
                    'raw' => '',
                ]
            ];
        }
    }

    /**
     * Parse log content into structured format
     */
    private function parseLogContent($content)
    {
        $lines = explode("\n", $content);
        $entries = [];
        $currentEntry = null;

        foreach ($lines as $line) {
            if (empty(trim($line))) {
                continue;
            }

            // Check if this line starts a new log entry
            if ($this->isLogEntryStart($line)) {
                // Save previous entry if exists
                if ($currentEntry) {
                    $entries[] = $currentEntry;
                }
                
                // Start new entry
                $currentEntry = $this->parseLogLine($line);
            } else {
                // This is a continuation of the previous entry
                if ($currentEntry) {
                    $currentEntry['message'] .= "\n" . $line;
                    $currentEntry['raw'] .= "\n" . $line;
                }
            }
        }

        // Don't forget the last entry
        if ($currentEntry) {
            $entries[] = $currentEntry;
        }

        return array_reverse($entries); // Show newest entries first
    }

    /**
     * Check if a line starts a new log entry
     */
    private function isLogEntryStart($line)
    {
        // Laravel log format: [2024-01-15 10:30:45] local.ERROR: Message
        return preg_match('/^\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\]/', $line);
    }

    /**
     * Parse a single log line
     */
    private function parseLogLine($line)
    {
        // Default structure
        $entry = [
            'level' => 'info',
            'timestamp' => now()->toDateTimeString(),
            'message' => $line,
            'context' => [],
            'raw' => $line,
        ];

        // Try to parse Laravel log format
        $pattern = '/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\]\s+(\w+)\.(\w+):\s*(.+)$/';
        
        if (preg_match($pattern, $line, $matches)) {
            $entry['timestamp'] = $matches[1];
            $entry['environment'] = $matches[2];
            $entry['level'] = strtolower($matches[3]);
            $entry['message'] = $matches[4];
            
            // Try to extract JSON context if present
            if (strpos($entry['message'], '{') !== false) {
                $parts = explode(' {', $entry['message'], 2);
                if (count($parts) === 2) {
                    $entry['message'] = trim($parts[0]);
                    $jsonPart = '{' . $parts[1];
                    
                    try {
                        $context = json_decode($jsonPart, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $entry['context'] = $context;
                        }
                    } catch (\Exception $e) {
                        // If JSON parsing fails, keep it as part of the message
                    }
                }
            }
        }

        return $entry;
    }

    /**
     * Get log statistics
     */
    private function getLogStatistics($logFileName)
    {
        $logPath = storage_path('logs/' . $logFileName);
        
        if (!File::exists($logPath)) {
            return [
                'total_entries' => 0,
                'levels' => [],
                'file_size' => '0 B',
                'last_modified' => null,
            ];
        }

        $content = File::get($logPath);
        $lines = explode("\n", $content);
        
        $stats = [
            'total_entries' => 0,
            'levels' => [
                'emergency' => 0,
                'alert' => 0,
                'critical' => 0,
                'error' => 0,
                'warning' => 0,
                'notice' => 0,
                'info' => 0,
                'debug' => 0,
            ],
            'file_size' => $this->formatFileSize(File::size($logPath)),
            'last_modified' => date('Y-m-d H:i:s', File::lastModified($logPath)),
        ];

        foreach ($lines as $line) {
            if ($this->isLogEntryStart($line)) {
                $stats['total_entries']++;
                
                // Extract log level
                if (preg_match('/\]\s+\w+\.(\w+):/', $line, $matches)) {
                    $level = strtolower($matches[1]);
                    if (isset($stats['levels'][$level])) {
                        $stats['levels'][$level]++;
                    }
                }
            }
        }

        return $stats;
    }

    /**
     * Read last N lines from a file efficiently
     */
    private function readLastLines($filePath, $lines = 1000)
    {
        $file = fopen($filePath, 'r');
        $linecounter = $lines;
        $pos = -2;
        $beginning = false;
        $text = [];

        while ($linecounter > 0) {
            $t = " ";
            while ($t != "\n") {
                if (fseek($file, $pos, SEEK_END) == -1) {
                    $beginning = true;
                    break;
                }
                $t = fgetc($file);
                $pos--;
            }
            $linecounter--;
            if ($beginning) {
                rewind($file);
            }
            $text[$lines - $linecounter - 1] = fgets($file);
            if ($beginning) break;
        }
        fclose($file);

        return implode("", array_reverse($text));
    }

    /**
     * Format file size
     */
    private function formatFileSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $unitIndex = 0;
        
        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }
        
        return round($size, 2) . ' ' . $units[$unitIndex];
    }

    /**
     * Download log file
     */
    public function download(Request $request)
    {
        $logFileName = $request->get('log');
        $logPath = storage_path('logs/' . $logFileName);
        
        if (!File::exists($logPath)) {
            abort(404, 'Log file not found');
        }

        return response()->download($logPath);
    }

    /**
     * Clear log file
     */
    public function clear(Request $request)
    {
        $logFileName = $request->get('log');
        $logPath = storage_path('logs/' . $logFileName);
        
        if (!File::exists($logPath)) {
            return back()->with('error', 'Log file not found');
        }

        File::put($logPath, '');
        
        return back()->with('success', 'Log file cleared successfully');
    }
}