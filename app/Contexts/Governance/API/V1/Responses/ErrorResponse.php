<?php

declare(strict_types=1);

namespace App\Contexts\Governance\API\V1\Responses;

final readonly class ErrorResponse implements \JsonSerializable
{
    public function __construct(
        public string $code,
        public string $message,
        public int $status,
        public string $traceId = '',
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'error' => [
                'code' => $this->code,
                'message' => $this->message,
                'status' => $this->status,
                'traceId' => $this->traceId,
            ],
        ];
    }

    public static function notFound(string $message, string $traceId = ''): self
    {
        return new self('COMMITTEE_NOT_FOUND', $message, 404, $traceId);
    }

    public static function validationError(string $message, string $traceId = ''): self
    {
        return new self('VALIDATION_ERROR', $message, 400, $traceId);
    }

    public static function rateLimited(string $traceId = ''): self
    {
        return new self('RATE_LIMITED', 'Too many requests. Please try again later.', 429, $traceId);
    }

    public static function internalError(string $traceId = ''): self
    {
        return new self('INTERNAL_ERROR', 'An unexpected error occurred.', 500, $traceId);
    }

    public static function projectionStale(string $message, string $traceId = ''): self
    {
        return new self('PROJECTION_STALE', $message, 409, $traceId);
    }

    public function toResponse(): \Illuminate\Http\JsonResponse
    {
        return new \Illuminate\Http\JsonResponse($this->jsonSerialize(), $this->status);
    }
}
