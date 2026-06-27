<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Http\Requests\Desktop;

use Illuminate\Foundation\Http\FormRequest;

/**
 * CSV Import Preview Request
 *
 * CASE 4: Tenant Desktop API - CSV Import Preview
 * Route: /{tenant}/api/v1/members/import/preview (POST)
 *
 * Architecture:
 * - Format validation ONLY (no business logic)
 * - No cross-context DB validation (moved to Application Service)
 * - Global platform (works for ANY political party worldwide)
 *
 * Business Context:
 * - Political parties upload messy Excel sheets
 * - Supports: CSV, Excel (.xlsx, .xls)
 * - Encoding: UTF-8, ISO-8859-1, Windows-1252 (international names)
 * - File size: Max 10MB (approx 100,000 members)
 *
 * @see CLAUDE.md Golden Rule #5: Global Platform Design
 * @see RegisterMemberRequest.php for validation pattern reference
 */
class ImportCsvPreviewRequest extends FormRequest
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
                'mimes:csv,txt,xlsx,xls', // CSV and Excel formats
                'max:10240', // 10MB max (approx 100,000 members)
            ],

            // OPTIONAL: File encoding (for international names)
            'encoding' => [
                'nullable',
                'string',
                'in:UTF-8,ISO-8859-1,Windows-1252',
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
            'file.max' => 'File size must not exceed 10MB (approximately 100,000 members).',

            // Encoding validation
            'encoding.in' => 'Encoding must be UTF-8, ISO-8859-1, or Windows-1252.',
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
    }
}
