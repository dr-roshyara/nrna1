<?php

namespace App\Services;

use App\Models\Organisation;
use App\Models\User;
use App\Models\OrganisationUser;
use App\Models\Member;
use App\Models\Voter;
use App\Models\Election;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\OrganisationUserImport;

class OrganisationUserImportService
{
    protected Organisation $organisation;
    protected ?Organisation $platformOrg = null;
    protected array $results = [
        'total' => 0,
        'created' => 0,
        'updated' => 0,
        'skipped' => 0,
        'errors' => [],
        'preview' => [],
    ];
    protected array $electionCache = [];

    public function __construct(Organisation $organisation)
    {
        $this->organisation = $organisation;
        $this->loadElectionCache();
        $this->platformOrg = $this->getPlatformOrganisation();
    }

    protected function getPlatformOrganisation(): Organisation
    {
        return Organisation::where('type', 'platform')->first() ?? Organisation::first();
    }

    protected function loadElectionCache(): void
    {
        $this->electionCache = $this->organisation->elections()
            ->pluck('id', 'id')
            ->toArray();
    }

    /**
     * Download template Excel file
     */
    public function downloadTemplate()
    {
        $headers = ['email', 'name', 'is_org_user', 'is_member', 'is_voter', 'election_id'];

        $sampleData = [
            [
                'john.doe@example.com',
                'John Doe',
                'YES',
                'YES',
                'YES',
                $this->organisation->elections()->first()?->id ?? '',
            ],
            [
                'jane.smith@example.com',
                'Jane Smith',
                'YES',
                'YES',
                'NO',
                '',
            ],
            [
                'bob.wilson@example.com',
                'Bob Wilson',
                'YES',
                'NO',
                'NO',
                '',
            ],
        ];

        return Excel::download(
            new class($headers, $sampleData) implements \Maatwebsite\Excel\Concerns\FromArray,
                \Maatwebsite\Excel\Concerns\WithHeadings
            {
                protected $headings;
                protected $data;

                public function __construct($headings, $data)
                {
                    $this->headings = $headings;
                    $this->data = $data;
                }

                public function array(): array
                {
                    return $this->data;
                }

                public function headings(): array
                {
                    return $this->headings;
                }
            },
            'organisation_user_template.xlsx'
        );
    }

    /**
     * Preview import (validate without saving)
     */
    public function preview($file): array
    {
        $rows = Excel::toArray(new OrganisationUserImport, $file)[0] ?? [];

        $this->results['total'] = 0;
        $this->results['preview'] = [];
        $this->results['errors'] = [];

        foreach ($rows as $index => $row) {
            $this->results['total']++;
            $rowNumber = $index + 2; // +2 because 1-indexed + header row

            $validation = $this->validateRow($row, $rowNumber);

            $previewItem = [
                'row' => $rowNumber,
                'email' => $row['email'] ?? '',
                'name' => $row['name'] ?? '',
                'is_org_user' => $row['is_org_user'] ?? 'NO',
                'is_member' => $row['is_member'] ?? 'NO',
                'is_voter' => $row['is_voter'] ?? 'NO',
                'election_id' => $row['election_id'] ?? '',
                'status' => $validation['valid'] ? '✅ Valid' : '❌ Invalid',
                'errors' => $validation['errors'],
                'action' => $this->determineAction($row['email'] ?? ''),
            ];

            $this->results['preview'][] = $previewItem;

            if (!$validation['valid']) {
                $this->results['errors'][] = [
                    'row' => $rowNumber,
                    'errors' => $validation['errors'],
                ];
            }
        }

        return [
            'preview' => $this->results['preview'],
            'stats' => [
                'total' => $this->results['total'],
                'valid' => count(array_filter($this->results['preview'], fn($item) => $item['status'] === '✅ Valid')),
                'invalid' => count($this->results['errors']),
            ],
        ];
    }

    /**
     * Process import (save to database)
     */
    public function import($file): array
    {
        DB::beginTransaction();

        try {
            $rows = Excel::toArray(new OrganisationUserImport, $file)[0] ?? [];

            $this->results['total'] = 0;
            $this->results['created'] = 0;
            $this->results['updated'] = 0;
            $this->results['skipped'] = 0;

            foreach ($rows as $index => $row) {
                $this->results['total']++;
                $rowNumber = $index + 2;

                $validation = $this->validateRow($row, $rowNumber);
                if (!$validation['valid']) {
                    $this->results['skipped']++;
                    continue;
                }

                $result = $this->processRow($row);
                if ($result['action'] === 'created') {
                    $this->results['created']++;
                } elseif ($result['action'] === 'updated') {
                    $this->results['updated']++;
                } else {
                    $this->results['skipped']++;
                }
            }

            DB::commit();

            return [
                'total' => $this->results['total'],
                'created' => $this->results['created'],
                'updated' => $this->results['updated'],
                'skipped' => $this->results['skipped'],
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Export current organisation users
     */
    public function export()
    {
        $orgUsers = OrganisationUser::where('organisation_id', $this->organisation->id)
            ->with(['user', 'member.voters'])
            ->get();

        $data = $orgUsers->map(function ($orgUser) {
            $voter = $orgUser->member?->voters()->first();
            return [
                $orgUser->user->email,
                $orgUser->user->name,
                'YES',
                $orgUser->member ? 'YES' : 'NO',
                $voter ? 'YES' : 'NO',
                $voter?->election_id ?? '',
            ];
        })->toArray();

        $headers = ['email', 'name', 'is_org_user', 'is_member', 'is_voter', 'election_id'];
        array_unshift($data, $headers);

        return Excel::download(
            new class($data) implements \Maatwebsite\Excel\Concerns\FromArray
            {
                protected $data;

                public function __construct($data)
                {
                    $this->data = $data;
                }

                public function array(): array
                {
                    return $this->data;
                }
            },
            "organisation_{$this->organisation->slug}_users.xlsx"
        );
    }

    /**
     * Validate a single row
     */
    protected function validateRow(array $row, int $rowNumber): array
    {
        $errors = [];

        // Required fields
        if (empty($row['email'])) {
            $errors[] = 'Email is required';
        } elseif (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Invalid email format';
        }

        // Name required
        if (empty($row['name'])) {
            $errors[] = 'Name is required';
        }

        // Check org user flag
        $isOrgUser = strtoupper($row['is_org_user'] ?? 'NO') === 'YES';
        if (!$isOrgUser) {
            // If not org user, they can't be member or voter
            if (strtoupper($row['is_member'] ?? 'NO') === 'YES') {
                $errors[] = 'Cannot be member without being organisation user first';
            }
            if (strtoupper($row['is_voter'] ?? 'NO') === 'YES') {
                $errors[] = 'Cannot be voter without being organisation user first';
            }
            return ['valid' => empty($errors), 'errors' => $errors];
        }

        // Member validation
        $isMember = strtoupper($row['is_member'] ?? 'NO') === 'YES';

        // Voter validation
        $isVoter = strtoupper($row['is_voter'] ?? 'NO') === 'YES';
        if ($isVoter) {
            if (!$isMember) {
                $errors[] = 'Cannot be voter without being member first';
            }

            $electionId = $row['election_id'] ?? '';
            if (empty($electionId)) {
                $errors[] = 'Election ID required for voters';
            } elseif (!isset($this->electionCache[$electionId])) {
                $errors[] = "Election '{$electionId}' not found in this organisation";
            }
        }

        return ['valid' => empty($errors), 'errors' => $errors];
    }

    /**
     * Process a single row
     */
    protected function processRow(array $row): array
    {
        $email = $row['email'];
        $name = $row['name'];
        $isOrgUser = strtoupper($row['is_org_user'] ?? 'NO') === 'YES';
        $isMember = strtoupper($row['is_member'] ?? 'NO') === 'YES';
        $isVoter = strtoupper($row['is_voter'] ?? 'NO') === 'YES';
        $electionId = $row['election_id'] ?? null;

        // Find or create user
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => bcrypt(Str::random(40)), // Random password, will be reset
                'organisation_id' => $this->platformOrg?->id,
                'region' => '',
                'email_verified_at' => now(),
            ]
        );

        $action = $user->wasRecentlyCreated ? 'created' : 'updated';

        // Handle OrganisationUser
        if ($isOrgUser) {
            $orgUser = OrganisationUser::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'organisation_id' => $this->organisation->id,
                ],
                [
                    'status' => 'active',
                    'role' => 'member', // Default role
                    'joined_at' => now(),
                ]
            );

            // Handle Member
            if ($isMember) {
                $member = Member::updateOrCreate(
                    ['organisation_user_id' => $orgUser->id],
                    [
                        'organisation_id' => $this->organisation->id,
                        'membership_number' => 'M' . uniqid(),
                        'joined_at' => now(),
                        'status' => 'active',
                    ]
                );

                // Handle Voter
                if ($isVoter && $electionId) {
                    Voter::updateOrCreate(
                        [
                            'member_id' => $member->id,
                            'election_id' => $electionId,
                        ],
                        [
                            'organisation_id' => $this->organisation->id,
                            'status' => 'eligible',
                            'voter_number' => 'V' . uniqid(),
                            'has_voted' => false,
                        ]
                    );
                }
            } elseif ($orgUser->member) {
                // Remove member and voters if no longer member
                $orgUser->member->voters()->delete();
                $orgUser->member()->delete();
            }
        } else {
            // Remove user from organisation if they exist
            OrganisationUser::where('user_id', $user->id)
                ->where('organisation_id', $this->organisation->id)
                ->delete();
        }

        return ['action' => $action];
    }

    /**
     * Determine what action would be taken
     */
    protected function determineAction(string $email): string
    {
        $user = User::where('email', $email)->first();

        if (!$user) {
            return '🆕 New User + OrganisationUser';
        }

        $orgUser = OrganisationUser::where('user_id', $user->id)
            ->where('organisation_id', $this->organisation->id)
            ->first();

        if (!$orgUser) {
            return '🔄 Existing User + New OrganisationUser';
        }

        return '📝 Update Existing';
    }
}
