<?php

namespace Tests\Feature\Governance;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class ExportLifecycleVocabularyTest extends TestCase
{
    public function test_command_generates_typescript_file(): void
    {
        $output = base_path('resources/js/Constants/ElectionLifecycleStates.ts');

        Artisan::call('governance:export-lifecycle-vocabulary');

        $this->assertFileExists($output);
        $content = file_get_contents($output);

        $this->assertStringContainsString("DRAFT:", $content);
        $this->assertStringContainsString("SUBMITTED_FOR_APPROVAL:", $content);
        $this->assertStringContainsString("SETUP_ADMINISTRATION:", $content);
        $this->assertStringContainsString("VOTING_ACTIVE:", $content);
        $this->assertStringContainsString("SUSPENDED:", $content);
        $this->assertStringContainsString('ElectionLifecycleState', $content);
        $this->assertStringContainsString('@generated', $content);
    }

    public function test_generated_file_contains_all_12_states(): void
    {
        Artisan::call('governance:export-lifecycle-vocabulary');
        $content = file_get_contents(base_path('resources/js/Constants/ElectionLifecycleStates.ts'));

        $states = \App\Domain\Election\Enum\ElectionLifecycleState::cases();
        foreach ($states as $case) {
            $key = strtoupper(preg_replace('/(?<!^)[A-Z]/', '_$0', $case->name));
            $this->assertStringContainsString(
                "{$key}:",
                $content,
                "Generated file must contain state: {$case->value}"
            );
        }
    }
}
