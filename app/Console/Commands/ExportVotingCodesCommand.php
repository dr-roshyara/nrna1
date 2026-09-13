<?php

namespace App\Console\Commands;

use App\Models\Code;
use App\Models\Election;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Operational fallback for when an election's code-delivery email is not
 * working: exports each voter's name/contact + their code_to_open_voting_form
 * to a CSV file so the codes can be sent manually (phone, WhatsApp, a second
 * email attempt, etc.).
 *
 * Does not use tinker's broken `user_name` column reference — codes has no
 * such column; the voter's name/email/phone come from Code::user() (already
 * defined as withoutGlobalScopes() on the model).
 *
 * Writes a file rather than printing to the console table, so full voting
 * codes aren't left sitting in terminal scrollback/history longer than
 * necessary — only the file path and summary counts are printed.
 */
class ExportVotingCodesCommand extends Command
{
    protected $signature = 'election:export-codes
        {slug : The election slug (e.g. onf-europe-3-f99187bc)}
        {--email= : Print one voter\'s code directly to the terminal instead of writing a CSV}
        {--only-not-voted : Only include voters who have not yet voted}
        {--path= : Output path, relative to storage/app (default: exports/voting-codes-<slug>-<timestamp>.csv)}';

    protected $description = 'Export voter names + voting codes for an election to CSV (manual code delivery when email is not working)';

    public function handle(): int
    {
        $slug = $this->argument('slug');

        $election = Election::withoutGlobalScopes()->where('slug', $slug)->first();

        if (!$election) {
            $this->error("No election found with slug \"{$slug}\".");
            return 1;
        }

        $email = $this->option('email');

        if ($email) {
            return $this->printSingleCode($election, $email);
        }

        $query = Code::withoutGlobalScopes()
            ->with('user:id,name,email,telephone')
            ->where('election_id', $election->id);

        if ($this->option('only-not-voted')) {
            $query->where('has_voted', false);
        }

        $codes = $query->get([
            'id',
            'user_id',
            'has_voted',
            'code_to_open_voting_form',
            'code_to_open_voting_form_sent_at',
        ]);

        if ($codes->isEmpty()) {
            $this->warn("No codes found for election \"{$election->name}\" ({$slug}).");
            return 0;
        }

        $relativePath = $this->option('path')
            ?? 'exports/voting-codes-' . $slug . '-' . now()->format('Y-m-d_His') . '.csv';

        $handle = fopen('php://memory', 'w');
        fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM, opens cleanly in Excel
        fputcsv($handle, ['Name', 'Email', 'Telephone', 'Has Voted', 'Code To Open Voting Form', 'Code Sent At']);

        $sentCount = 0;
        foreach ($codes as $code) {
            if ($code->code_to_open_voting_form_sent_at) {
                $sentCount++;
            }
            fputcsv($handle, [
                $code->user->name ?? '(user not found: ' . $code->user_id . ')',
                $code->user->email ?? '',
                $code->user->telephone ?? '',
                $code->has_voted ? 'Yes' : 'No',
                $code->code_to_open_voting_form,
                $code->code_to_open_voting_form_sent_at ?: 'never sent',
            ]);
        }

        rewind($handle);
        Storage::put($relativePath, stream_get_contents($handle));
        fclose($handle);

        $this->info("Election: {$election->name} ({$slug})");
        $this->info('Codes exported: ' . $codes->count());
        $this->info('Already voted: ' . $codes->where('has_voted', true)->count());
        $this->info('Not yet voted: ' . $codes->where('has_voted', false)->count());
        $this->info('Code email never sent: ' . ($codes->count() - $sentCount));
        $this->info('Written to: ' . Storage::path($relativePath));
        $this->comment('Download this file from the server (e.g. scp/sftp), or move it into public/ temporarily if you need a direct URL — do not commit it or leave it publicly reachable, since it contains live voting codes.');

        return 0;
    }

    /**
     * --email mode: print one voter's code directly to the terminal rather
     * than writing a CSV — for the "just one person needs their code right
     * now" case.
     */
    private function printSingleCode(Election $election, string $email): int
    {
        $code = Code::withoutGlobalScopes()
            ->with('user:id,name,email,telephone')
            ->where('election_id', $election->id)
            ->whereHas('user', fn ($q) => $q->where('email', $email))
            ->first(['id', 'user_id', 'has_voted', 'code_to_open_voting_form', 'code_to_open_voting_form_sent_at']);

        if (!$code) {
            $this->error("No voting code found for \"{$email}\" in election \"{$election->name}\" ({$election->slug}).");
            return 1;
        }

        $this->info('Election: ' . $election->name . ' (' . $election->slug . ')');
        $this->info('Name: ' . ($code->user->name ?? '(unknown)'));
        $this->info('Email: ' . $email);
        $this->info('Telephone: ' . ($code->user->telephone ?? '(none on file)'));
        $this->info('Has voted: ' . ($code->has_voted ? 'Yes' : 'No'));
        $this->info('Code to open voting form: ' . $code->code_to_open_voting_form);
        $this->info('Code sent at: ' . ($code->code_to_open_voting_form_sent_at ?: 'never sent'));

        return 0;
    }
}
