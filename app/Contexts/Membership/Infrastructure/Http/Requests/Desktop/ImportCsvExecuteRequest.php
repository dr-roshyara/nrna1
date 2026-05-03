<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Http\Requests\Desktop;

use Illuminate\Foundation\Http\FormRequest;

/**
 * CSV Import Execute Request
 *
 * CASE 4: Tenant Desktop API - CSV Import Execution
 * Route: /{tenant}/api/v1/members/import (POST)
 *
 * Architecture:
 * - Format validation ONLY (no business logic)
 * - No field name validation (handled by Application layer)
 * - Global platform (NO hardcoded field names)
 *
 * Business Context:
 * - Executes member import after preview validation
 * - Creates TenantUsers and Members in single transaction
 * - Supports duplicate handling and column mapping
 * - Transaction rollback on any error
 *
 * Field Name Validation:
 * - HTTP layer validates FORMAT (is it string? array? length?)
 * - Application layer validates CONTENT (valid field for THIS tenant?)
 * - Allows ANY tenant to define their own fields
 *
 * @see CLAUDE.md Golden Rule #5: Global Platform Design
 * @see ImportCsvPreviewRequest.php for validation pattern reference
 */
class ImportCsvExecuteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * Authorization handled by middleware (auth:sanctum + can:manage-members)
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // REQUIRED: CSV/Excel file
            'file' => [
                'required',
                'file',
                'mimes:csv,txt,xlsx,xls',
                'max:10240', // 10MB max
            ],

            // OPTIONAL: File encoding
            'encoding' => [
                'nullable',
                'string',
                'in:UTF-8,ISO-8859-1,Windows-1252',
            ],

            // OPTIONAL: Skip duplicate members (by email)
            'skip_duplicates' => [
                'nullable',
                'boolean',
            ],

            // OPTIONAL: Custom column mapping
            // Format: {'csv_column_name': 'target_field_name'}
            // Example: {'Name': 'full_name', 'E-mail': 'email'}
            // Field name validation happens in Application layer (CsvImportService)
            'column_mapping' => [
                'nullable',
                'array',
            ],

            // Validate format only, NOT specific field names
            // Global Platform: Any tenant can map to their own fields
            'column_mapping.*' => [
                'string',
                'max:255', // Reasonable length limit
            ],
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            // File validation
            'file.required' => 'CSV or Excel file is required.',
            'file.file' => 'Uploaded content must be a valid file.',
            'file.mimes' => 'File must be CSV (.csv, .txt) or Excel (.xlsx, .xls) format.',
            'file.max' => 'File size must not exceed 10MB.',

            // Encoding validation
            'encoding.in' => 'Encoding must be UTF-8, ISO-8859-1, or Windows-1252.',

            // Skip duplicates validation
            'skip_duplicates.boolean' => 'Skip duplicates must be true or false.',

            // Column mapping validation (format only)
            'column_mapping.array' => 'Column mapping must be an object of column pairs.',
            'column_mapping.*.string' => 'Column mapping values must be strings.',
            'column_mapping.*.max' => 'Column mapping field name must not exceed 255 characters.',
        ];
    }

    /**
     * Get custom attribute names for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'file' => 'import file',
            'encoding' => 'file encoding',
            'skip_duplicates' => 'skip duplicates option',
            'column_mapping' => 'column mapping',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * Normalize encoding to uppercase for consistency.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('encoding') && $this->encoding !== null) {
            $this->merge([
                'encoding' => strtoupper((string) $this->encoding),
            ]);
        }

        // Normalize skip_duplicates to boolean
        if ($this->has('skip_duplicates') && $this->skip_duplicates !== null) {
            $this->merge([
                'skip_duplicates' => filter_var($this->skip_duplicates, FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }
}
