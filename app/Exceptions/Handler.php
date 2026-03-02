<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use App\Exceptions\Voting\VotingException;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // Handle voting exceptions
        $this->renderable(function (VotingException $e, $request) {
            return $this->handleVotingException($e, $request);
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Handle voting exceptions with user-friendly responses
     *
     * @param VotingException $e
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    private function handleVotingException(VotingException $e, $request)
    {
        // Log the exception with full context
        Log::error('Voting exception occurred', [
            'exception' => get_class($e),
            'message' => $e->getMessage(),
            'user_id' => auth()->id(),
            'user_email' => auth()->user()?->email,
            'ip_address' => $request->ip(),
            'url' => $request->url(),
            'method' => $request->method(),
            'context' => $e->getContext(),
        ]);

        // Return JSON response for API requests
        if ($request->wantsJson() || $request->isJson()) {
            return response()->json([
                'error' => $e->getUserMessage(),
                'code' => get_class($e),
                'status' => $e->getHttpCode(),
            ], $e->getHttpCode());
        }

        // Return redirect with error for web requests
        return redirect()->route('dashboard')
            ->with('error', $e->getUserMessage());
    }
}
