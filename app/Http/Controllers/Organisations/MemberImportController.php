<?php

namespace App\Http\Controllers\Organisations;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessMemberImportJob;
use App\Models\MemberImportJob;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MemberImportController extends Controller
{
    /**
     * Show the member import page.
     *
     * GET /organisations/{slug}/members/import
     */
    public function create(string $slug)
    {
        $organisation = Organisation::where('slug', $slug)->firstOrFail();

        $this->authorizeMembership($organisation);

        return Inertia::render('Organisations/Members/Import', [
            'organisation' => [
                'id'   => $organisation->id,
                'name' => $organisation->name,
                'slug' => $organisation->slug,
            ],
        ]);
    }

    /**
     * Download the member import CSV template.
     *
     * Columns match exactly what ProcessMemberImportJob reads:
     *   email, firstname, lastname
     *
     * GET /organisations/{slug}/members/import/template
     */
    public function template(string $slug): StreamedResponse
    {
        $organisation = Organisation::where('slug', $slug)->firstOrFail();

        $this->authorizeMembership($organisation);

        $rows = [
            ['email', 'firstname', 'lastname', 'membership_number', 'joined_at',   'status', 'fees_status', 'expires_at'],
            ['john.doe@example.com',   'John', 'Doe',    'MEM-001', '2024-01-15', 'active',  'paid',        '2025-12-31'],
            ['jane.smith@example.com', 'Jane', 'Smith',  '',        '2024-02-01', 'active',  'unpaid',      ''],
            ['bob.wilson@example.com', 'Bob',  'Wilson', '',        '2024-03-10', 'active',  'exempt',      '2026-03-10'],
        ];

        return response()->streamDownload(function () use ($rows) {
            $handle = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($handle, $row, ';');
            }
            fclose($handle);
        }, 'members_import_template.csv', [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Accept the uploaded file, create a job record, and dispatch the queue job.
     *
     * POST /organisations/{slug}/members/import
     * Returns 202 JSON: { job_id, status_url }
     */
    public function store(Request $request, string $slug)
    {
        $organisation = Organisation::where('slug', $slug)->firstOrFail();

        $this->authorizeMembership($organisation);

        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:51200', // 50 MB
        ]);

        $file     = $request->file('file');
        $stored   = $file->storeAs(
            "imports/{$organisation->id}",
            Str::uuid() . '_' . $file->getClientOriginalName(),
            'local'
        );

        $importJob = MemberImportJob::create([
            'organisation_id'   => $organisation->id,
            'initiated_by'      => auth()->id(),
            'file_path'         => $stored,
            'original_filename' => $file->getClientOriginalName(),
            'status'            => 'pending',
        ]);

        ProcessMemberImportJob::dispatch($importJob->id);

        return response()->json([
            'job_id'     => $importJob->id,
            'status_url' => route('organisations.members.import.status', [
                'slug'  => $organisation->slug,
                'jobId' => $importJob->id,
            ]),
        ], 202);
    }

    /**
     * Return current job status for polling.
     *
     * GET /organisations/{slug}/members/import/{jobId}/status
     */
    public function status(string $slug, string $jobId)
    {
        $organisation = Organisation::where('slug', $slug)->firstOrFail();

        $this->authorizeMembership($organisation);

        // Single query: findOrFail scoped to this organisation (ownership check)
        $importJob = MemberImportJob::where('id', $jobId)
            ->where('organisation_id', $organisation->id)
            ->firstOrFail();

        return response()->json([
            'status'         => $importJob->status,
            'progress'       => $importJob->progress,
            'total_rows'     => $importJob->total_rows,
            'processed_rows' => $importJob->processed_rows,
            'imported_count' => $importJob->imported_count,
            'skipped_count'  => $importJob->skipped_count,
            'error_log'      => $importJob->error_log ?? [],
            'started_at'     => $importJob->started_at,
            'completed_at'   => $importJob->completed_at,
        ]);
    }

    // ── Private helpers ──────────────────────────────────────────────────────

    private function authorizeMembership(Organisation $organisation): void
    {
        $isMember = $organisation->users()
            ->where('users.id', auth()->id())
            ->exists();

        if (!$isMember) {
            abort(403, 'Sie haben keinen Zugriff auf diese Organisation.');
        }
    }
}
