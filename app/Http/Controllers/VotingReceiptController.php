<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\ReceiptCode;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VotingReceiptController extends Controller
{
    /**
     * Display randomized list of receipt codes for an election
     *
     * @param Organisation $organisation
     * @param Election $election
     * @return \Inertia\Response
     */
    public function index(Organisation $organisation, Election $election)
    {
        // Committee-only: the full randomized receipt-code list is a governance/
        // audit view (ElectionPolicy::viewResults — active election officer for
        // this organisation), distinct from a voter's own self-service lookup
        // via /vote/verify_to_show.
        $this->authorize('viewResults', $election);

        // Security: only accessible while results are visible. results_published is the
        // single toggle-able flag (Hide/Unhide) — results_published_at is a permanent
        // "was ever published" timestamp and must NOT gate this, or hiding results would
        // never actually hide this page once first published.
        if (!$election->results_published) {
            abort(404);
        }

        $receiptCodes = ReceiptCode::where('election_id', $election->id)
            ->orderBy('created_at')
            ->get();

        // Randomize order and add serial numbers
        $randomizedCodes = $receiptCodes->shuffle();
        $displayCodes = $randomizedCodes->map(function ($code, $index) {
            return [
                'serial' => $index + 1,
                'code' => $code->receipt_code,
                'is_reverified' => $code->isReverified(),
                'reverified_at' => $code->reverified_at,
            ];
        });

        // Use results_published_at if available, otherwise use current time
        $publishedAt = $election->results_published_at
            ? $election->results_published_at->format('F j, Y \a\t g:i A')
            : now()->format('F j, Y \a\t g:i A');

        return Inertia::render('Election/ReceiptCodes', [
            'election' => [
                'id' => $election->id,
                'name' => $election->name,
                'slug' => $election->slug,
            ],
            'organisation' => [
                'id' => $organisation->id,
                'name' => $organisation->name,
                'slug' => $organisation->slug,
            ],
            'receipt_codes' => $displayCodes->values(),
            'total_votes' => $receiptCodes->count(),
            'reverified_count' => $receiptCodes->whereNotNull('reverified_at')->count(),
            'published_at' => $publishedAt,
            'last_updated' => now()->format('F j, Y \a\t g:i A'),
        ]);
    }

    /**
     * Download the committee-facing receipt-code list as CSV.
     *
     * Same authorization/visibility boundary as index() — a download must
     * never be reachable by anyone who couldn't already see the page. The
     * order is independently re-shuffled on every call (never the storage/
     * insertion order), exactly like the on-screen list already re-shuffles
     * on every page load — so the serial numbers in a downloaded file are not
     * a stable identity and will differ between downloads.
     *
     * @param Organisation $organisation
     * @param Election $election
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download(Organisation $organisation, Election $election)
    {
        $this->authorize('viewResults', $election);

        if (!$election->results_published) {
            abort(404);
        }

        $rows = ReceiptCode::where('election_id', $election->id)
            ->get()
            ->shuffle()
            ->values();

        $filename = 'receipt-codes-' . $election->slug . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
            // No status column: election receipt exports must not carry
            // negative-sounding wording ("Not Verified") that could read as
            // casting doubt on the election's credibility.
            fputcsv($handle, ['#', 'Receipt Code']);
            foreach ($rows as $index => $code) {
                fputcsv($handle, [
                    $index + 1,
                    $code->receipt_code,
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Mark a vote as verified by the voter
     *
     * @param Request $request
     * @param Organisation $organisation
     * @param Election $election
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmCorrect(Request $request, Organisation $organisation, Election $election)
    {
        $request->validate([
            'receipt_code' => 'required|string',
        ]);

        // Find receipt code by exact match
        $receiptCode = ReceiptCode::where('election_id', $election->id)
            ->where('receipt_code', $request->receipt_code)
            ->first();

        if (!$receiptCode) {
            return back()->withErrors(['error' => 'Receipt code not found.']);
        }

        if ($receiptCode->reverified_at) {
            return back()->withErrors(['error' => 'This vote has already been verified.']);
        }

        $receiptCode->markAsReverified();

        // Log audit trail
        \Log::info('Vote verified as correct', [
            'receipt_code' => $request->receipt_code,
            'election_id' => $election->id,
            'user_id' => auth()->id(),
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Thank you for confirming your vote is correct!')
                     ->with('vote_data', [
                         'is_reverified' => true,
                         'reverified_at' => $receiptCode->reverified_at->format('F j, Y \a\t g:i A')
                     ]);
    }
}
