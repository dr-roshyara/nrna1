<?php

namespace App\Services;

use App\Models\Election;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class ElectionAuditService
{
    /**
     * Maximum file size before rotation (100 MB)
     * Prevents indefinite growth of audit logs within 30-day retention window
     */
    private const MAX_FILE_SIZE = 104857600; // 100 MB in bytes

    /**
     * Log an election event to JSONL audit files.
     *
     * @param Election $election
     * @param string $event Event name (e.g., 'voting_started', 'vote_cast')
     * @param User|null $user User performing the action
     * @param string $category Category for file routing: 'election', 'voters', 'committee'
     * @param string|null $ip IP address (stored in full)
     * @param array $metadata Additional event data
     */
    public function log(
        Election $election,
        string $event,
        ?User $user = null,
        string $category = 'voters',
        ?string $ip = null,
        array $metadata = []
    ): void {
        // Validate category
        $validCategories = ['election', 'voters', 'committee'];
        if (!in_array($category, $validCategories)) {
            $category = 'voters';
        }

        // Get or create audit folder
        $folderPath = $this->getOrCreateAuditFolder($election);

        // Build log entry
        $entry = [
            'event' => $event,
            'category' => $category,
            'election_id' => $election->id,
            'election_slug' => $election->slug,
            'timestamp' => now()->toIso8601String(),
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'user_email' => $user ? $this->maskEmail($user->email) : null,
            'ip' => $ip,
            'metadata' => $metadata,
        ];

        // Write to category-specific file
        $this->appendToJsonlFile($folderPath, "{$category}.jsonl", $entry);

        // Always write to election.jsonl
        if ($category !== 'election') {
            $this->appendToJsonlFile($folderPath, 'election.jsonl', $entry);
        }
    }

    /**
     * Log a per-voter action to the voter's individual audit file.
     * Creates the election folder and voters/ subfolder automatically.
     */
    public function logVoterAction(
        Election $election,
        User $voter,
        int $step,
        string $action,
        array $metadata = []
    ): void {
        $folderPath = $this->getOrCreateAuditFolder($election);

        $votersFolder = $folderPath . DIRECTORY_SEPARATOR . 'voters';
        if (!is_dir($votersFolder)) {
            File::makeDirectory($votersFolder, 0755, true, true);
        }

        $safeName = $this->sanitizeFileName($voter->name);

        $entry = [
            'step'         => $step,
            'action'       => $action,
            'election_id'  => $election->id,
            'election_slug' => $election->slug,
            'timestamp'    => now()->toIso8601String(),
            'voter_id'     => $voter->id,
            'voter_name'   => $voter->name,
            'voter_email'  => $this->maskEmail($voter->email),
            'ip'           => request()->ip(),
            'user_agent'   => request()->userAgent(),
            'metadata'     => $metadata,
        ];

        $this->appendToJsonlFile($votersFolder, $safeName . '.jsonl', $entry);
    }

    /**
     * Sanitize a voter name into a safe filename (without extension).
     */
    private function sanitizeFileName(string $name): string
    {
        $safe = iconv('UTF-8', 'ASCII//TRANSLIT', $name);
        $safe = preg_replace('/[^a-zA-Z0-9]/', '_', $safe);
        $safe = preg_replace('/_+/', '_', $safe);
        $safe = trim($safe, '_');

        if (empty($safe)) {
            $safe = 'voter_' . substr(hash('sha256', $name), 0, 8);
        }

        return $safe;
    }

    /**
     * Get or create the audit folder for an election.
     * Folder format: {slug}_{YYYYMMDD}_{HHmm} using election.start_date
     */
    private function getOrCreateAuditFolder(Election $election): string
    {
        // Format folder name from start_date
        $startDate = Carbon::parse($election->start_date);
        $folderName = sprintf(
            '%s_%s_%s',
            $election->slug,
            $startDate->format('Ymd'),
            $startDate->format('Hi')
        );

        // Build full path
        $basePath = storage_path('logs' . DIRECTORY_SEPARATOR . 'audit');
        $folderPath = $basePath . DIRECTORY_SEPARATOR . $folderName;

        // Use Laravel's File facade to ensure directory exists
        if (!is_dir($folderPath)) {
            File::makeDirectory($folderPath, 0755, true, true);
        }

        return $folderPath;
    }

    /**
     * Append a JSON object to a JSONL file (one JSON per line).
     * Implements log rotation when file exceeds MAX_FILE_SIZE.
     */
    private function appendToJsonlFile(string $folderPath, string $filename, array $entry): void
    {
        $filePath = $folderPath . DIRECTORY_SEPARATOR . $filename;

        // Check if file needs rotation (exceeds max size)
        if (File::exists($filePath) && filesize($filePath) >= self::MAX_FILE_SIZE) {
            $this->rotateFile($filePath);
        }

        // Encode entry as JSON
        $jsonLine = json_encode($entry, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";

        // Append to file (create if doesn't exist)
        File::append($filePath, $jsonLine);
    }

    /**
     * Rotate a log file by renaming it with a timestamp suffix.
     * Original: voters.jsonl → voters.jsonl.1713177600 (unix timestamp)
     * New: voters.jsonl (fresh, empty, ready for new entries)
     */
    private function rotateFile(string $filePath): void
    {
        $timestamp = time();
        $rotatedPath = $filePath . '.' . $timestamp;

        // Rename existing file with timestamp
        if (File::exists($filePath)) {
            File::move($filePath, $rotatedPath);
        }
    }

    /**
     * Mask email address for privacy.
     * Format: restaurant.namastenepal@gmail.com → r***@gmail.com
     */
    private function maskEmail(string $email): string
    {
        if (empty($email) || strpos($email, '@') === false) {
            return $email;
        }

        [$local, $domain] = explode('@', $email, 2);

        if (strlen($local) === 0) {
            return $email;
        }

        // First character + *** + domain
        return substr($local, 0, 1) . '***@' . $domain;
    }
}
