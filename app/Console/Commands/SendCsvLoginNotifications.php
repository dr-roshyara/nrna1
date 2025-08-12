<?php

namespace App\Console\Commands;

use App\Notifications\CsvLoginDetailsNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use SplFileObject;

class SendCsvLoginNotifications extends Command
{
    protected $signature = 'csv:notify-logins
                            {path : Path to CSV file}
                            {--delimiter=auto : CSV delimiter (auto, ; , , \\t, |)}
                            {--user-id-column=user_id : Header for user ID}
                            {--name-column=name : Header for name}
                            {--email-column=email : Header for email}
                            {--password-column=password : Header for password}
                            {--contact1-column=contactperson1 : Header for contact person 1}
                            {--contact2-column=contactperson2 : Header for contact person 2}
                            {--time-column=time : Header for time}
                            {--login-url= : Login URL (required)}
                            {--vote-url= : Optional dedicated voting URL (defaults to login URL in template)}
                            {--dry-run : Print what would be sent without emailing}';

    protected $description = 'Read user_info.csv and email each user their details + bilingual voting steps.';

    public function handle(): int
    {
        $path        = $this->argument('path');
        $delimiterOp = (string) $this->option('delimiter');

        $userIdKey = mb_strtolower((string) $this->option('user-id-column'));
        $nameKey   = mb_strtolower((string) $this->option('name-column'));
        $emailKey  = mb_strtolower((string) $this->option('email-column'));
        $passKey   = mb_strtolower((string) $this->option('password-column'));
        $c1Key     = mb_strtolower((string) $this->option('contact1-column'));
        $c2Key     = mb_strtolower((string) $this->option('contact2-column'));
        $timeKey   = mb_strtolower((string) $this->option('time-column'));

        $loginUrl = (string) ($this->option('login-url') ?? '');
        $voteUrl  = $this->option('vote-url');
        $voteUrl  = ($voteUrl !== null && $voteUrl !== '') ? (string) $voteUrl : null;

        $dryRun   = (bool) $this->option('dry-run');

        if (!file_exists($path)) {
            $this->error("File not found: {$path}");
            return self::FAILURE;
        }
        if ($loginUrl === '') {
            $this->error('Missing required --login-url option.');
            return self::FAILURE;
        }

        $file = new SplFileObject($path, 'r');

        // Auto-detect delimiter unless overridden
        $delimiter = $this->resolveDelimiter($file, $delimiterOp);
        $this->line("Using delimiter: " . ($delimiter === "\t" ? '\\t' : $delimiter));

        // Configure CSV reader
        $file->setFlags(SplFileObject::READ_CSV | SplFileObject::SKIP_EMPTY);
        $file->setCsvControl($delimiter);
        $file->rewind();

        // HEADER
        $headerRow = $file->fgetcsv();
        if ($headerRow === false || $headerRow === [null]) {
            $this->error('CSV seems empty or header row missing.');
            return self::FAILURE;
        }

        // Normalize headers: strip BOM, trim, lowercase
        $header = array_map(function ($h) {
            $h = (string) $h;
            $h = preg_replace('/^\xEF\xBB\xBF/', '', $h); // strip UTF-8 BOM
            return mb_strtolower(trim($h));
        }, $headerRow);

        $index = array_flip($header);

        foreach ([$userIdKey, $nameKey, $emailKey, $passKey, $timeKey] as $required) {
            if (!array_key_exists($required, $index)) {
                $this->error("Missing required header: '{$required}'. Found: " . implode(', ', $header));
                return self::FAILURE;
            }
        }

        $userIdIdx = $index[$userIdKey];
        $nameIdx   = $index[$nameKey];
        $emailIdx  = $index[$emailKey];
        $passIdx   = $index[$passKey];
        $timeIdx   = $index[$timeKey];
        $c1Idx     = array_key_exists($c1Key, $index) ? $index[$c1Key] : null;
        $c2Idx     = array_key_exists($c2Key, $index) ? $index[$c2Key] : null;

        $rowNum = 1; // header consumed
        $sent   = 0;
        $skipped = 0;

        while (!$file->eof()) {
            $row = $file->fgetcsv();
            if ($row === false || $row === [null]) {
                continue;
            }
            $rowNum++;

            // pad row to header length to avoid undefined offsets
            if (count($row) < count($header)) {
                $row = array_pad($row, count($header), '');
            }

            $userId = trim((string)($row[$userIdIdx] ?? ''));
            $name   = trim((string)($row[$nameIdx] ?? ''));
            $email  = trim((string)($row[$emailIdx] ?? ''));
            $pass   = (string)($row[$passIdx] ?? ''); // keep as-is
            $time   = trim((string)($row[$timeIdx] ?? ''));

            $contacts = [];
            foreach ([$c1Idx, $c2Idx] as $idx) {
                if ($idx === null) { continue; }
                $raw = (string)($row[$idx] ?? '');
                if ($raw === '') { continue; }
                // support multiple entries separated by , ; |
                $parts = preg_split('/[;,|]/', $raw);
                foreach ($parts as $p) {
                    $p = trim($p);
                    if ($p !== '') { $contacts[] = $p; }
                }
            }

            // Validate
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $userId === '' || $name === '' || $pass === '') {
                $this->warn("Row {$rowNum}: skipped (missing/invalid userId/name/email/password).");
                $skipped++;
                continue;
            }

            if ($dryRun) {
                $this->line("DRY-RUN --> Would send to: {$name} <{$email}> (ID: {$userId}) | time={$time} | contacts=[" . implode(' ; ', $contacts) . "]");
                $sent++;
                continue;
            }

            // Send (positional args to avoid named-parameter issues)
            Notification::route('mail', $email)->notify(
                new CsvLoginDetailsNotification(
                    $userId,     // userId
                    $name,       // name
                    $pass,       // password
                    $time,       // time
                    $loginUrl,   // loginUrl
                    $contacts,   // contacts
                    $voteUrl     // voteUrl (nullable)
                )
            );

            $sent++;
            $this->info("Sent to: {$name} <{$email}> (ID: {$userId})");
            // dd("test");
            sleep(3);
        }

        $this->line("Done. Processed rows: " . ($rowNum - 1) . ". Sent: {$sent}. Skipped: {$skipped}.");
        if ($dryRun) {
            $this->line("NOTE: --dry-run was enabled. No emails were actually sent.");
        }

        return self::SUCCESS;
    }

    /**
     * Resolve delimiter from option or auto-detect from the first non-empty line.
     */
    private function resolveDelimiter(SplFileObject $file, string $option): string
    {
        // Manual override
        if (strtolower($option) !== 'auto') {
            if ($option === '\\t') { return "\t"; } // allow \t from CLI
            return $option;                         // ';' ',' '|' etc.
        }

        // Auto-detect: read a raw non-empty line
        $file->rewind();
        $firstLine = '';
        for ($i = 0; $i < 5 && !$file->eof(); $i++) {
            $firstLine = $file->fgets();
            if ($firstLine !== false && trim($firstLine) !== '') { break; }
        }
        if ($firstLine === false) {
            return ';'; // safe fallback
        }

        // Strip UTF-8 BOM
        $firstLine = preg_replace('/^\xEF\xBB\xBF/', '', $firstLine);

        $candidates = [
            ';'  => substr_count($firstLine, ';'),
            ','  => substr_count($firstLine, ','),
            "\t" => substr_count($firstLine, "\t"),
            '|'  => substr_count($firstLine, '|'),
        ];
        arsort($candidates);
        $top = array_key_first($candidates);
        return ($candidates[$top] === 0) ? ';' : $top;
    }
}
