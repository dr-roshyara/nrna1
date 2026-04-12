<?php

namespace Tests\Unit\Traits\Voting;

use Tests\TestCase;
use App\Traits\Voting\CodeVerificationTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

/**
 * Concrete test implementation of CodeVerificationTrait
 * Allows testing protected methods from the trait
 */
class CodeVerificationTraitTestHelper
{
    use CodeVerificationTrait;

    public function testVerifyPlainCode(string $submitted, string $stored): bool
    {
        return $this->verifyPlainCode($submitted, $stored);
    }

    public function testVerifyHashedCode(string $submitted, string $hashed): bool
    {
        return $this->verifyHashedCode($submitted, $hashed);
    }

    public function testVerifyPasswordHashCode(string $submitted, string $passwordHash): bool
    {
        return $this->verifyPasswordHashCode($submitted, $passwordHash);
    }

    public function testHasVotingWindowExpired($code): bool
    {
        return $this->hasVotingWindowExpired($code);
    }
}

class CodeVerificationTraitTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create a test class using the trait
     */
    private function getTraitInstance()
    {
        return new CodeVerificationTraitTestHelper();
    }

    /**
     * Test: Plain code verification with exact match
     */
    public function test_verify_plain_code_with_exact_match()
    {
        $trait = $this->getTraitInstance();

        $stored = 'ABC123';
        $submitted = 'abc123'; // Case-insensitive

        $result = $trait->testVerifyPlainCode($submitted, $stored);

        $this->assertTrue($result, 'Plain code verification should be case-insensitive');
    }

    /**
     * Test: Plain code verification rejects mismatched codes
     */
    public function test_verify_plain_code_rejects_mismatch()
    {
        $trait = $this->getTraitInstance();

        $stored = 'ABC123';
        $submitted = 'XYZ789';

        $result = $trait->testVerifyPlainCode($submitted, $stored);

        $this->assertFalse($result, 'Plain code verification should reject mismatched codes');
    }

    /**
     * Test: Plain code verification handles whitespace
     */
    public function test_verify_plain_code_ignores_whitespace()
    {
        $trait = $this->getTraitInstance();

        $stored = 'ABC123';
        $submitted = '  abc123  '; // With whitespace

        $result = $trait->testVerifyPlainCode($submitted, $stored);

        $this->assertTrue($result, 'Plain code verification should ignore leading/trailing whitespace');
    }

    /**
     * Test: Plain code verification rejects empty codes
     */
    public function test_verify_plain_code_rejects_empty()
    {
        $trait = $this->getTraitInstance();

        $result1 = $trait->testVerifyPlainCode('', 'ABC123');
        $result2 = $trait->testVerifyPlainCode('ABC123', '');
        $result3 = $trait->testVerifyPlainCode('', '');

        $this->assertFalse($result1, 'Should reject empty submitted code');
        $this->assertFalse($result2, 'Should reject empty stored code');
        $this->assertFalse($result3, 'Should reject both empty');
    }

    /**
     * Test: Hashed code verification with bcrypt
     */
    public function test_verify_hashed_code_with_bcrypt()
    {
        $trait = $this->getTraitInstance();

        $plainCode = 'SecureCode123';
        $hashed = Hash::make($plainCode);

        $result = $trait->testVerifyHashedCode($plainCode, $hashed);

        $this->assertTrue($result, 'Hashed code verification should accept correct bcrypt hash');
    }

    /**
     * Test: Hashed code verification rejects wrong code
     */
    public function test_verify_hashed_code_rejects_wrong_code()
    {
        $trait = $this->getTraitInstance();

        $plainCode = 'SecureCode123';
        $wrongCode = 'WrongCode456';
        $hashed = Hash::make($plainCode);

        $result = $trait->testVerifyHashedCode($wrongCode, $hashed);

        $this->assertFalse($result, 'Hashed code verification should reject wrong code');
    }

    /**
     * Test: Hashed code verification rejects empty codes
     */
    public function test_verify_hashed_code_rejects_empty()
    {
        $trait = $this->getTraitInstance();

        $hashed = Hash::make('SomeCode');
        $result1 = $trait->testVerifyHashedCode('', $hashed);
        $result2 = $trait->testVerifyHashedCode('code', '');

        $this->assertFalse($result1, 'Should reject empty submitted code');
        $this->assertFalse($result2, 'Should reject empty hash');
    }

    /**
     * Test: Password hash code verification
     */
    public function test_verify_password_hash_code()
    {
        $trait = $this->getTraitInstance();

        $plainCode = 'PasswordHashCode123';
        $passwordHash = password_hash($plainCode, PASSWORD_BCRYPT);

        $result = $trait->testVerifyPasswordHashCode($plainCode, $passwordHash);

        $this->assertTrue($result, 'Password hash verification should accept correct password');
    }

    /**
     * Test: Password hash code rejects wrong code
     */
    public function test_verify_password_hash_code_rejects_wrong()
    {
        $trait = $this->getTraitInstance();

        $plainCode = 'PasswordHashCode123';
        $wrongCode = 'WrongPasswordCode456';
        $passwordHash = password_hash($plainCode, PASSWORD_BCRYPT);

        $result = $trait->testVerifyPasswordHashCode($wrongCode, $passwordHash);

        $this->assertFalse($result, 'Password hash verification should reject wrong code');
    }

    /**
     * Test: Voting window expiration check
     */
    public function test_voting_window_expired_check()
    {
        $trait = $this->getTraitInstance();

        // Create a mock code object
        $code = (object) [
            'code_to_open_voting_form_sent_at' => now()->subMinutes(35),
            'voting_time_in_minutes' => 30,
        ];

        $result = $trait->testHasVotingWindowExpired($code);

        $this->assertTrue($result, 'Voting window should be expired after 35 minutes with 30-minute window');
    }

    /**
     * Test: Voting window not expired
     */
    public function test_voting_window_not_expired()
    {
        $trait = $this->getTraitInstance();

        $code = (object) [
            'code_to_open_voting_form_sent_at' => now()->subMinutes(20),
            'voting_time_in_minutes' => 30,
        ];

        $result = $trait->testHasVotingWindowExpired($code);

        $this->assertFalse($result, 'Voting window should not be expired after 20 minutes with 30-minute window');
    }

    /**
     * Test: Voting window uses config fallback
     */
    public function test_voting_window_uses_config_fallback()
    {
        $trait = $this->getTraitInstance();

        // Code with null voting_time_in_minutes - should use config
        $code = (object) [
            'code_to_open_voting_form_sent_at' => now()->subMinutes(35),
            'voting_time_in_minutes' => null,
        ];

        // Default config is 30 minutes
        $result = $trait->testHasVotingWindowExpired($code);

        $this->assertTrue($result, 'Should use config default when voting_time_in_minutes is null');
    }
}
