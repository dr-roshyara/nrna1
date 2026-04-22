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
        // Security: Only accessible after results are published
        // Check both results_published_at (timestamp) and results_published (boolean) for backward compatibility
        if (!$election->results_published_at && !$election->results_published) {
            abort(403, 'Results have not been published yet.');
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
