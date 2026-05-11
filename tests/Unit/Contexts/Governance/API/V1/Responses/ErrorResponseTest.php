<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\API\V1\Responses;

use App\Contexts\Governance\API\V1\Responses\ErrorResponse;
use PHPUnit\Framework\TestCase;

final class ErrorResponseTest extends TestCase
{
    public function test_constructs_with_all_fields(): void
    {
        $response = new ErrorResponse(
            code: 'TEST_ERROR',
            message: 'Test message',
            status: 400,
            traceId: 'req-001',
        );

        $this->assertSame('TEST_ERROR', $response->code);
        $this->assertSame('Test message', $response->message);
        $this->assertSame(400, $response->status);
        $this->assertSame('req-001', $response->traceId);
    }

    public function test_default_trace_id_is_empty(): void
    {
        $response = new ErrorResponse('ERROR', 'msg', 500);

        $this->assertSame('', $response->traceId);
    }

    public function test_not_found_factory(): void
    {
        $response = ErrorResponse::notFound('Committee not found', 'trace-1');

        $this->assertSame('COMMITTEE_NOT_FOUND', $response->code);
        $this->assertSame('Committee not found', $response->message);
        $this->assertSame(404, $response->status);
        $this->assertSame('trace-1', $response->traceId);
    }

    public function test_validation_error_factory(): void
    {
        $response = ErrorResponse::validationError('Invalid depth');

        $this->assertSame('VALIDATION_ERROR', $response->code);
        $this->assertSame('Invalid depth', $response->message);
        $this->assertSame(400, $response->status);
    }

    public function test_rate_limited_factory(): void
    {
        $response = ErrorResponse::rateLimited();

        $this->assertSame('RATE_LIMITED', $response->code);
        $this->assertSame(429, $response->status);
    }

    public function test_internal_error_factory(): void
    {
        $response = ErrorResponse::internalError();

        $this->assertSame('INTERNAL_ERROR', $response->code);
        $this->assertSame(500, $response->status);
    }

    public function test_projection_stale_factory(): void
    {
        $response = ErrorResponse::projectionStale('Rebuild in progress');

        $this->assertSame('PROJECTION_STALE', $response->code);
        $this->assertSame('Rebuild in progress', $response->message);
        $this->assertSame(409, $response->status);
    }

    public function test_json_serialize_returns_error_envelope(): void
    {
        $response = ErrorResponse::notFound('Not found');

        $serialized = $response->jsonSerialize();

        $this->assertArrayHasKey('error', $serialized);
        $this->assertSame('COMMITTEE_NOT_FOUND', $serialized['error']['code']);
        $this->assertSame(404, $serialized['error']['status']);
    }

    public function test_not_found_error_has_status_404(): void
    {
        $response = ErrorResponse::notFound('Not found');

        $this->assertSame(404, $response->status);
        $this->assertSame('COMMITTEE_NOT_FOUND', $response->code);
    }

    public function test_validation_error_has_status_400(): void
    {
        $response = ErrorResponse::validationError('Bad input');

        $this->assertSame(400, $response->status);
        $this->assertSame('VALIDATION_ERROR', $response->code);
    }

    public function test_rate_limited_has_status_429(): void
    {
        $response = ErrorResponse::rateLimited();

        $this->assertSame(429, $response->status);
        $this->assertSame('RATE_LIMITED', $response->code);
    }

    public function test_internal_error_has_status_500(): void
    {
        $response = ErrorResponse::internalError();

        $this->assertSame(500, $response->status);
        $this->assertSame('INTERNAL_ERROR', $response->code);
    }

    public function test_projection_stale_has_status_409(): void
    {
        $response = ErrorResponse::projectionStale('Rebuild in progress');

        $this->assertSame(409, $response->status);
        $this->assertSame('PROJECTION_STALE', $response->code);
    }
}
