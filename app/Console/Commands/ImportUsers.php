<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use League\Csv\Reader;
use League\Csv\Exception;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ImportUsers extends Command
{
    protected $signature = 'users:import {file=user_info.csv} {--force : Skip confirmation prompts} {--inspect : Just show CSV headers without importing} {--delimiter=; : CSV delimiter (semicolon, comma, tab, etc)} {--debug : Show debug information during import}';
    protected $description = 'Import users from a CSV file into the database';

    private array $importedUsers = [];
    private array $failedUsers = [];
    private array $updatedUsers = [];
    private array $headerMapping = [];

    public function __construct()
    {
        parent::__construct();
        
        // Common header variations mapping
        $this->headerMapping = [
            'user_id' => ['user_id', 'userid', 'id', 'user_identifier', 'member_id', 'sn', 'serial_number', 'serialnumber', 's_no'],
            'name' => ['name', 'full_name', 'fullname', 'member_name', 'username', 'user_name'],
            'email' => ['email', 'email_address', 'e_mail', 'mail', 'email_id'],
            'region' => ['region', 'area', 'zone', 'district'],
            'country' => ['country', 'nation', 'country_name'],
            'state' => ['state', 'province', 'territory', 'state_name'],
            'city' => ['city', 'town', 'municipality', 'city_name'],
            'telephone' => ['telephone', 'phone', 'mobile', 'cell', 'contact', 'phone_number', 'mobile_number', 'contact_number'],
            'nrna_id' => ['nrna_id', 'nrnaid', 'membership_id', 'member_id', 'membership_number'],
            'is_voter' => ['is_voter', 'voter', 'can_vote', 'eligible', 'voting_eligible', 'voter_status'],
            'password' => ['password', 'pass', 'pwd', 'user_password']
        ];
    }

    public function handle(): int
    {
        $fileName = $this->argument('file');
        $csvPath = storage_path("app/csv_files/{$fileName}");

        if (!file_exists($csvPath)) {
            $this->error("CSV file not found: {$csvPath}");
            return self::FAILURE;
        }

        $this->info("Starting user import from: {$fileName}");
        $this->bootstrapSuperadmin();

        // Determine CSV delimiter
        $delimiter = $this->option('delimiter');
        if ($delimiter === 'semicolon') {
            $delimiter = ';';
        } elseif ($delimiter === 'comma') {
            $delimiter = ',';
        } elseif ($delimiter === 'tab' || $delimiter === '\t') {
            $delimiter = "\t";
        }
        // Keep the delimiter as-is if it's already a single character
        
        $this->info("Using delimiter: '" . ($delimiter === "\t" ? 'TAB' : $delimiter) . "'");

        // Read and validate CSV structure
        try {
            $csv = Reader::createFromPath($csvPath, 'r')
                ->setDelimiter($delimiter)
                ->setHeaderOffset(0);
            
            $headers = $csv->getHeader();
            
            // If inspect option is used, just show headers and exit
            if ($this->option('inspect')) {
                $this->info("CSV File: {$fileName}");
                $this->info("Delimiter: '" . ($delimiter === "\t" ? 'TAB' : $delimiter) . "'");
                $this->info("Headers found: " . implode(', ', $headers));
                $this->newLine();
                
                // Show what mappings would be applied
                $mappings = $this->mapHeaders($headers);
                $this->info("Header Mappings:");
                foreach ($mappings as $standard => $actual) {
                    $status = $standard === $actual ? "✅ Direct match" : "🔄 Mapped from '{$actual}'";
                    $this->line("  {$standard}: {$status}");
                }
                
                $this->newLine();
                $this->info("Missing required headers:");
                $requiredHeaders = [
                    'user_id', 'name', 'email', 'region', 'country', 
                    'telephone', 'nrna_id'
                ];
                $missingCount = 0;
                foreach ($requiredHeaders as $header) {
                    if (!isset($mappings[$header])) {
                        $this->line("  ❌ {$header}");
                        $missingCount++;
                    }
                }
                
                $this->newLine();
                $this->info("Optional headers (auto-generated if missing):");
                $optionalHeaders = ['is_voter', 'password', 'state', 'city'];
                foreach ($optionalHeaders as $header) {
                    $status = isset($mappings[$header]) ? "✅ Found" : "🔧 Will be auto-generated";
                    $this->line("  {$header}: {$status}");
                }
                
                if ($missingCount === 0) {
                    $this->info("🎉 All required headers can be mapped! Ready for import.");
                } else {
                    $this->error("❌ {$missingCount} required headers are missing.");
                    $this->newLine();
                    $this->info("Common variations accepted:");
                    foreach ($this->headerMapping as $standard => $variations) {
                        if (!isset($mappings[$standard])) {
                            $this->line("  {$standard}: " . implode(', ', $variations));
                        }
                    }
                }
                
                // Show sample data if available
                $records = iterator_to_array($csv->getRecords());
                if (!empty($records)) {
                    $this->newLine();
                    $this->info("Sample data from first row:");
                    $firstRow = reset($records);
                    foreach ($firstRow as $header => $value) {
                        $displayValue = strlen($value) > 50 ? substr($value, 0, 47) . '...' : $value;
                        $this->line("  {$header}: {$displayValue}");
                    }
                }
                
                return self::SUCCESS;
            }
            
            $headerMapping = $this->validateHeaders($headers);
            
            $records = iterator_to_array($csv->getRecords());
        } catch (Exception $e) {
            $this->error("Failed to read CSV: " . $e->getMessage());
            $this->newLine();
            $this->info("💡 Try different delimiter options:");
            $this->line("  --delimiter=, (comma - default)");
            $this->line("  --delimiter=; (semicolon)");
            $this->line("  --delimiter=tab (tab separated)");
            return self::FAILURE;
        }

        $totalRecords = count($records);
        $this->info("Found {$totalRecords} records to process.");

        if (!$this->option('force') && !$this->confirm("Do you want to proceed with the import?")) {
            $this->info("Import cancelled.");
            return self::SUCCESS;
        }

        // Pre-check for email and user_id duplicates in CSV
        $this->validateCsvDuplicates($records, $headerMapping);

        // Process each record
        $this->processRecords($records, $headerMapping);

        // Display detailed results
        $this->displayResults();

        return empty($this->failedUsers) ? self::SUCCESS : self::FAILURE;
    }

    /**
     * Validate that required headers exist in CSV
     */
    private function validateHeaders(array $headers): array
    {
        $requiredHeaders = [
            'user_id', 'name', 'email', 'region', 'country', 
            'telephone', 'nrna_id'
        ];
        
        // Optional headers (will be generated or defaulted if missing)
        $optionalHeaders = ['is_voter', 'password', 'state', 'city'];

        $this->info("Headers found in CSV: " . implode(', ', $headers));
        
        // Try to map headers automatically
        $mappedHeaders = $this->mapHeaders($headers);
        
        $missingHeaders = array_diff($requiredHeaders, array_keys($mappedHeaders));
        
        if (!empty($missingHeaders)) {
            $this->error("Missing required headers: " . implode(', ', $missingHeaders));
            $this->newLine();
            $this->info("Required headers:");
            foreach ($requiredHeaders as $header) {
                $this->line("  - {$header}");
            }
            $this->newLine();
            $this->info("Optional headers (will be generated/defaulted if missing):");
            foreach ($optionalHeaders as $header) {
                $this->line("  - {$header}");
            }
            $this->newLine();
            $this->info("Possible variations:");
            foreach ($missingHeaders as $missing) {
                $variations = $this->headerMapping[$missing] ?? [];
                if (!empty($variations)) {
                    $this->line("  {$missing}: " . implode(', ', $variations));
                }
            }
            $this->newLine();
            $this->info("Please update your CSV file headers or use --inspect to see current headers.");
            throw new Exception("CSV validation failed - missing required headers");
        }
        
        $this->info("✅ All required headers found in CSV file.");
        
        // Show mapping if any was applied
        $anyMapped = false;
        foreach ($mappedHeaders as $standard => $actual) {
            if ($standard !== $actual) {
                if (!$anyMapped) {
                    $this->info("Header mappings applied:");
                    $anyMapped = true;
                }
                $this->line("  '{$actual}' → '{$standard}'");
            }
        }
        
        // Show what will be auto-generated
        $autoGenerated = [];
        if (!isset($mappedHeaders['password'])) {
            $autoGenerated[] = 'password (random temporary passwords)';
        }
        if (!isset($mappedHeaders['is_voter'])) {
            $autoGenerated[] = 'is_voter (defaulted to true)';
        }
        if (!empty($autoGenerated)) {
            $this->info("Will auto-generate: " . implode(', ', $autoGenerated));
        }
        
        return $mappedHeaders;
    }

    /**
     * Map CSV headers to standard headers
     */
    private function mapHeaders(array $csvHeaders): array
    {
        $mapped = [];
        $csvHeadersLower = array_map('strtolower', $csvHeaders);
        
        foreach ($this->headerMapping as $standard => $variations) {
            $found = false;
            foreach ($variations as $variation) {
                $index = array_search(strtolower($variation), $csvHeadersLower);
                if ($index !== false) {
                    $mapped[$standard] = $csvHeaders[$index];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                // Check for exact match (case sensitive)
                if (in_array($standard, $csvHeaders)) {
                    $mapped[$standard] = $standard;
                }
            }
        }
        
        return $mapped;
    }

    /**
     * Check for duplicate emails and user_ids within the CSV file
     */
    private function validateCsvDuplicates(array $records, array $headerMapping): void
    {
        $emailHeader = $headerMapping['email'];
        $nameHeader = $headerMapping['name'];
        $userIdHeader = $headerMapping['user_id'];
        
        $emailCounts = [];
        $userIdCounts = [];
        $duplicates = [];

        foreach ($records as $index => $row) {
            $email = strtolower(trim($row[$emailHeader] ?? ''));
            $userId = trim($row[$userIdHeader] ?? '');
            $name = trim($row[$nameHeader] ?? '');
            $rowNumber = $index + 2; // Account for header row
            
            // Check for duplicate emails
            if (!empty($email)) {
                $emailCounts[$email] = ($emailCounts[$email] ?? 0) + 1;
                if ($emailCounts[$email] > 1) {
                    $duplicates[] = "Row {$rowNumber}: Duplicate EMAIL '{$email}' for {$name}";
                }
            }
            
            // Check for duplicate user_ids (after normalization)
            if (!empty($userId)) {
                // Apply same normalization as in mapRowData
                $normalizedUserId = is_numeric($userId) 
                    ? 'USER_' . str_pad($userId, 6, '0', STR_PAD_LEFT)
                    : preg_replace('/\s+/', '_', trim($userId));
                    
                $userIdCounts[$normalizedUserId] = ($userIdCounts[$normalizedUserId] ?? 0) + 1;
                if ($userIdCounts[$normalizedUserId] > 1) {
                    $duplicates[] = "Row {$rowNumber}: Duplicate USER_ID '{$normalizedUserId}' (from '{$userId}') for {$name}";
                }
            }
        }

        if (!empty($duplicates)) {
            $this->error("Duplicate values found in CSV file:");
            foreach ($duplicates as $duplicate) {
                $this->line("  - {$duplicate}");
            }
            $this->newLine();
            $this->error("Each user must have a unique email AND unique user_id.");
            throw new Exception("CSV contains duplicate emails or user_ids. Please fix and try again.");
        }
    }

    /**
     * Process all records from CSV
     */
    private function processRecords(array $records, array $headerMapping): void
    {
        $this->output->progressStart(count($records));

        foreach ($records as $index => $row) {
            $rowNumber = $index + 2; // Account for header row
            $this->processRecord($row, $rowNumber, $headerMapping);
            $this->output->progressAdvance();
        }

        $this->output->progressFinish();
    }

    /**
     * Process a single record
     */
    private function processRecord(array $row, int $rowNumber, array $headerMapping): void
    {
        // Map the row data to standard format
        $mappedRow = $this->mapRowData($row, $headerMapping);
        
        // Debug output if requested
        if ($this->option('debug')) {
            $this->line("=== DEBUG Row {$rowNumber} ===");
            $this->line("Original row: " . json_encode($row));
            $this->line("Header mapping: " . json_encode($headerMapping));
            $this->line("Mapped row: " . json_encode($mappedRow));
            $this->newLine();
        }
        
        $name = trim($mappedRow['name'] ?? '');
        $email = strtolower(trim($mappedRow['email'] ?? ''));
        $userId = trim($mappedRow['user_id'] ?? '');

        // Debug output for troubleshooting
        if (empty($name) || empty($email) || empty($userId)) {
            $this->recordFailure($name ?: 'Unknown', $email ?: 'Unknown', $rowNumber, 
                "Missing critical data - Name: '{$name}', Email: '{$email}', User ID: '{$userId}'");
            return;
        }

        try {
            // Validate the record
            $validator = $this->validateRecord($mappedRow);
            
            if ($validator->fails()) {
                $this->recordFailure($name, $email, $rowNumber, 
                    "Validation failed: " . implode('; ', $validator->errors()->all()));
                return;
            }

            // FIRST: Check if user_id already exists in database
            $existingUserById = User::where('user_id', $userId)->first();
            
            if ($existingUserById) {
                // Check if it's the same user (same user_id AND same email)
                if (strtolower($existingUserById->email) === $email) {
                    $this->updateExistingUser($existingUserById, $mappedRow, $name, $rowNumber);
                    return;
                } else {
                    // Same user_id but different email - this is not allowed
                    $this->recordFailure($name, $email, $rowNumber, 
                        "User ID '{$userId}' already exists with different email: {$existingUserById->email}. Each user_id must be unique.");
                    return;
                }
            }

            // SECOND: Check if email already exists in database
            $existingUserByEmail = User::where('email', $email)->first();
            
            if ($existingUserByEmail) {
                // Same email but different user_id - this is not allowed
                $this->recordFailure($name, $email, $rowNumber, 
                    "Email '{$email}' already exists for different user: {$existingUserByEmail->name} (ID: {$existingUserByEmail->user_id}). Each email must be unique.");
                return;
            }

            // All checks passed - create new user
            $this->createNewUser($mappedRow, $name, $rowNumber);

        } catch (\Exception $e) {
            $this->recordFailure($name, $email, $rowNumber, 
                "Unexpected error: " . $e->getMessage());
        }
    }

    /**
     * Map row data from CSV headers to standard format
     */
    private function mapRowData(array $row, array $headerMapping): array
    {
        $mappedRow = [];
        foreach ($headerMapping as $standard => $csvHeader) {
            // Trim all values to remove spaces around elements
            $value = trim($row[$csvHeader] ?? '');
            $mappedRow[$standard] = $value;
        }
        
        // Ensure we have all required fields with defaults if needed
        $mappedRow['state'] = $mappedRow['state'] ?? '';
        $mappedRow['city'] = $mappedRow['city'] ?? '';
        
        // Special handling for user_id - if we only have serial number, format it properly
        if (!empty($mappedRow['user_id']) && is_numeric($mappedRow['user_id'])) {
            $mappedRow['user_id'] = 'USER_' . str_pad($mappedRow['user_id'], 6, '0', STR_PAD_LEFT);
        } else if (!empty($mappedRow['user_id'])) {
            // Remove extra spaces from user_id and normalize
            $mappedRow['user_id'] = preg_replace('/\s+/', '_', trim($mappedRow['user_id']));
        }
        
        // Generate password if not provided
        if (empty($mappedRow['password'])) {
            $mappedRow['password'] = 'temp_' . Str::random(8);
        }
        
        // Handle boolean values for is_voter
        if (isset($mappedRow['is_voter'])) {
            $mappedRow['is_voter'] = $this->parseBooleanValue($mappedRow['is_voter']);
        } else {
            // Default to true if not specified
            $mappedRow['is_voter'] = true;
        }
        
        // Trim and clean other important fields
        if (!empty($mappedRow['name'])) {
            $mappedRow['name'] = preg_replace('/\s+/', ' ', trim($mappedRow['name'])); // normalize multiple spaces
        }
        
        if (!empty($mappedRow['email'])) {
            $mappedRow['email'] = strtolower(trim($mappedRow['email'])); // ensure lowercase and trimmed
        }
        
        if (!empty($mappedRow['telephone'])) {
            $mappedRow['telephone'] = preg_replace('/[^\d+]/', '', trim($mappedRow['telephone'])); // remove non-numeric except +
        }
        
        return $mappedRow;
    }

    /**
     * Validate a single record
     */
    private function validateRecord(array $row): \Illuminate\Validation\Validator
    {
        return Validator::make($row, [
            'user_id'   => 'required|string|max:255',
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'region'    => 'required|string|max:255',
            'country'   => 'required|string|max:255',
            'state'     => 'nullable|string|max:255',
            'city'      => 'nullable|string|max:255',
            'telephone' => 'required|string|max:18',
            'nrna_id'   => 'required|string|max:255',
            'is_voter'  => 'nullable|in:0,1,true,false',
            'password'  => 'nullable|string|min:6',
        ]);
    }

    /**
     * Update an existing user
     */
    private function updateExistingUser(User $user, array $row, string $name, int $rowNumber): void
    {
        try {
            $updateData = [
                'name'      => $row['name'],
                'region'    => $row['region'],
                'country'   => $row['country'],
                'state'     => $row['state'] ?? '',
                'city'      => $row['city'] ?? '',
                'telephone' => $row['telephone'],
                'nrna_id'   => $row['nrna_id'],
                'is_voter'  => $this->parseBooleanValue($row['is_voter'] ?? true),
                'password'  => Hash::make($row['password']),
            ];

            // Validate that all required fields are present
            $requiredFields = ['name', 'region', 'country', 'telephone', 'nrna_id'];
            foreach ($requiredFields as $field) {
                if (empty($updateData[$field])) {
                    throw new \Exception("Required field '{$field}' is empty for update. Value: '" . ($updateData[$field] ?? 'NULL') . "'");
                }
            }

            $user->update($updateData);

            $this->updatedUsers[] = [
                'name' => $name,
                'email' => $user->email,
                'user_id' => $user->user_id,
                'row' => $rowNumber
            ];
        } catch (\Exception $e) {
            throw new \Exception("Failed to update user: " . $e->getMessage() . " | Data: " . json_encode($row));
        }
    }

    /**
     * Create a new user
     */
    private function createNewUser(array $row, string $name, int $rowNumber): void
    {
        try {
            $userData = [
                'user_id'   => $row['user_id'],
                'name'      => $row['name'],
                'email'     => strtolower(trim($row['email'])),
                'region'    => $row['region'],
                'country'   => $row['country'],
                'state'     => $row['state'] ?? '',
                'city'      => $row['city'] ?? '',
                'telephone' => $row['telephone'],
                'nrna_id'   => $row['nrna_id'],
                'is_voter'  => $this->parseBooleanValue($row['is_voter'] ?? true),
                'password'  => Hash::make($row['password']),
                'email_verified_at' => now(), // Add this to avoid verification issues
            ];

            // Validate that all required fields are present
            $requiredFields = ['user_id', 'name', 'email', 'region', 'country', 'telephone', 'nrna_id'];
            foreach ($requiredFields as $field) {
                if (empty($userData[$field])) {
                    throw new \Exception("Required field '{$field}' is empty. Value: '" . ($userData[$field] ?? 'NULL') . "'");
                }
            }

            // Debug output
            if ($this->option('debug')) {
                $this->line("Creating user with data:");
                $debugData = $userData;
                $debugData['password'] = '[HASHED]'; // Don't show actual password hash
                $this->line(json_encode($debugData, JSON_PRETTY_PRINT));
            }

            // Try creating the user with explicit field assignment
            $user = new User();
            $user->user_id = $userData['user_id'];
            $user->name = $userData['name'];
            $user->email = $userData['email'];
            $user->region = $userData['region'];
            $user->country = $userData['country'];
            $user->state = $userData['state'];
            $user->city = $userData['city'];
            $user->telephone = $userData['telephone'];
            $user->nrna_id = $userData['nrna_id'];
            $user->is_voter = $userData['is_voter'];
            $user->password = $userData['password'];
            $user->email_verified_at = $userData['email_verified_at'];
            
            $user->save();

            $this->importedUsers[] = [
                'name' => $name,
                'email' => $user->email,
                'user_id' => $user->user_id,
                'row' => $rowNumber
            ];
            
            if ($this->option('debug')) {
                $this->info("✅ Successfully created user: {$user->name} ({$user->user_id})");
            }
            
        } catch (\Exception $e) {
            throw new \Exception("Failed to create user: " . $e->getMessage() . " | Attempted data: " . json_encode($userData ?? []));
        }
    }

    /**
     * Record a failed import
     */
    private function recordFailure(string $name, string $email, int $rowNumber, string $reason): void
    {
        $this->failedUsers[] = [
            'name' => $name ?: 'Unknown',
            'email' => $email ?: 'Unknown',
            'row' => $rowNumber,
            'reason' => $reason
        ];
    }

    /**
     * Parse boolean values from CSV
     */
    private function parseBooleanValue($value): bool
    {
        return in_array(strtolower($value), ['1', 'true', 'yes']);
    }

    /**
     * Display detailed import results
     */
    private function displayResults(): void
    {
        $this->newLine(2);
        $this->info("=== IMPORT SUMMARY ===");
        
        $totalProcessed = count($this->importedUsers) + count($this->updatedUsers) + count($this->failedUsers);
        $this->info("Total records processed: {$totalProcessed}");
        $this->info("Successfully imported: " . count($this->importedUsers));
        $this->info("Successfully updated: " . count($this->updatedUsers));
        $this->error("Failed imports: " . count($this->failedUsers));

        // Show newly imported users
        if (!empty($this->importedUsers)) {
            $this->newLine();
            $this->info("=== NEWLY IMPORTED USERS ===");
            foreach ($this->importedUsers as $user) {
                $userIdDisplay = $user['user_id'];
                if (strpos($user['user_id'], 'USER_') === 0) {
                    $originalSn = ltrim(str_replace('USER_', '', $user['user_id']), '0');
                    $userIdDisplay .= " (from SN: {$originalSn})";
                }
                $this->line("✅ Row {$user['row']}: {$user['name']} <{$user['email']}> (ID: {$userIdDisplay})");
            }
        }

        // Show updated users
        if (!empty($this->updatedUsers)) {
            $this->newLine();
            $this->info("=== UPDATED USERS ===");
            foreach ($this->updatedUsers as $user) {
                $userIdDisplay = $user['user_id'];
                if (strpos($user['user_id'], 'USER_') === 0) {
                    $originalSn = ltrim(str_replace('USER_', '', $user['user_id']), '0');
                    $userIdDisplay .= " (from SN: {$originalSn})";
                }
                $this->line("🔄 Row {$user['row']}: {$user['name']} <{$user['email']}> (ID: {$userIdDisplay})");
            }
        }

        // Show failed imports with clear names
        if (!empty($this->failedUsers)) {
            $this->newLine();
            $this->error("=== FAILED IMPORTS ===");
            $this->error("The following users could NOT be imported:");
            $this->newLine();
            
            // Group failures by type for better readability
            $failuresByType = [];
            foreach ($this->failedUsers as $failed) {
                $type = $this->categorizeFailure($failed['reason']);
                $failuresByType[$type][] = $failed;
            }
            
            foreach ($failuresByType as $type => $failures) {
                $this->error("🔸 {$type}:");
                foreach ($failures as $failed) {
                    $this->line("   ❌ Row {$failed['row']}: {$failed['name']} <{$failed['email']}>");
                    $this->line("      {$failed['reason']}");
                }
                $this->newLine();
            }
            
            $this->error("Import completed with errors. Please review the failed imports above.");
            $this->info("💡 Tip: Fix the issues in your CSV file and re-run the command to import the failed records.");
        } else {
            $this->newLine();
            $this->info("🎉 All records imported successfully!");
        }
        
        // Show important notes
        $this->newLine();
        $this->info("=== IMPORTANT NOTES ===");
        if (!empty($this->importedUsers) || !empty($this->updatedUsers)) {
            $this->line("• Both user_id and email must be unique across all users");
            $this->line("• Users are checked by user_id first (following original logic)");
            $this->line("• Serial numbers (sn) are converted to user_id format: USER_XXXXXX");
            $this->line("• User IDs with spaces are normalized (e.g., 'DE_MIS_ 1' → 'DE_MIS_1')");
            $this->line("• Passwords are securely hashed using Laravel's Hash::make()");
            $this->line("• Users should change their passwords on first login");
            $this->line("• All voters are marked as eligible by default");
            $this->line("• Superadmin user has been ensured with proper permissions");
        }
    }
    
    /**
     * Categorize failure reason for better grouping
     */
    private function categorizeFailure(string $reason): string
    {
        if (strpos($reason, 'Email') !== false && strpos($reason, 'already exists') !== false) {
            return 'DUPLICATE EMAIL CONFLICTS';
        }
        if (strpos($reason, 'User ID') !== false && strpos($reason, 'already exists') !== false) {
            return 'DUPLICATE USER_ID CONFLICTS';
        }
        if (strpos($reason, 'Duplicate EMAIL') !== false || strpos($reason, 'Duplicate USER_ID') !== false) {
            return 'CSV DUPLICATE VALUES';
        }
        if (strpos($reason, 'Validation failed') !== false) {
            return 'DATA VALIDATION ERRORS';
        }
        return 'OTHER ERRORS';
    }

    /**
     * Ensures a Superadmin user and permission exist.
     */
    private function bootstrapSuperadmin(): void
    {
        $superEmail = config('import.superadmin_email', 'roshyara@gmail.com');

        $superadmin = User::firstOrCreate(
            ['email' => $superEmail],
            [
                'password' => Hash::make(Str::random(16)),
                'name'     => 'Super Admin',
                'user_id'  => 'superadmin',
                'nrna_id'  => 'superadmin',
                'region'   => 'Global',
                'country'  => 'Global',
                'telephone' => '',
                'is_voter' => false,
            ]
        );

        // Create role and permission if they don't exist
        $role = Role::firstOrCreate(['name' => 'Superadmin']);
        $permission = Permission::firstOrCreate(['name' => 'send code']);
        
        // Ensure role has permission
        if (!$role->hasPermissionTo($permission)) {
            $role->givePermissionTo($permission);
        }

        // Ensure user has role
        if (!$superadmin->hasRole($role)) {
            $superadmin->assignRole($role);
        }

        $this->line("Superadmin user ensured: {$superadmin->email}");
    }
}