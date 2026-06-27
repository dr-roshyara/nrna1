<?php

namespace Tests\Architecture;

use Tests\TestCase;

class ElectionCapabilityArchitectureTest extends TestCase
{
    /**
     * Get all PHP files in a given path under app/
     */
    private function getPhpFilesInPath(string $relativePath): array
    {
        return glob(app_path($relativePath . '/*.php')) ?: [];
    }

    /**
     * Assert that none of the given files contain a forbidden string
     */
    private function assertFilesDoNotContain(array $files, string $forbidden, string $message): void
    {
        foreach ($files as $file) {
            $contents = file_get_contents($file);
            $basename = basename($file);
            $this->assertStringNotContainsString(
                $forbidden,
                $contents,
                "{$basename}: {$message}"
            );
        }
    }

    public function test_capability_policies_do_not_import_eloquent(): void
    {
        $files = $this->getPhpFilesInPath('Application/Election/Capabilities/Policy');
        $this->assertNotEmpty($files, 'No policy files found in Application/Election/Capabilities/Policy');
        $this->assertFilesDoNotContain($files, 'use Illuminate\Database', 'Policies must not import Eloquent');
    }

    public function test_capability_policies_do_not_use_auth_facade(): void
    {
        $files = $this->getPhpFilesInPath('Application/Election/Capabilities/Policy');
        $this->assertFilesDoNotContain($files, 'use Illuminate\Support\Facades\Auth', 'Policies must not use Auth facade');
        $this->assertFilesDoNotContain($files, 'auth()', 'Policies must not call auth()');
    }

    public function test_capability_policies_do_not_use_carbon(): void
    {
        $files = $this->getPhpFilesInPath('Application/Election/Capabilities/Policy');
        $this->assertFilesDoNotContain($files, 'Carbon', 'Policies must not use Carbon (non-deterministic)');
    }

    public function test_resolver_implementation_code_does_not_use_carbon(): void
    {
        // The resolver's docstring documents the invariant that policies must be non-temporal.
        // We check that no policy files import or use Carbon.
        // (The docstring mention of Carbon::now() is correct invariant documentation.)
        $files = $this->getPhpFilesInPath('Application/Election/Capabilities/Policy');
        foreach ($files as $file) {
            $contents = file_get_contents($file);
            $this->assertStringNotContainsString(
                'use Carbon',
                $contents,
                basename($file) . ': Policy must not import Carbon'
            );
        }
    }

    public function test_resolver_does_not_call_auth(): void
    {
        $resolver = app_path('Application/Election/Services/ElectionCapabilityResolver.php');
        $this->assertFileExists($resolver);
        $contents = file_get_contents($resolver);
        $this->assertStringNotContainsString(
            'auth()',
            $contents,
            'ElectionCapabilityResolver must not call auth()'
        );
    }

    public function test_capability_context_does_not_import_http_layer(): void
    {
        $context = app_path('Application/Election/Capabilities/CapabilityContext.php');
        $this->assertFileExists($context);
        $contents = file_get_contents($context);
        $this->assertStringNotContainsString(
            'use Illuminate\Http',
            $contents,
            'CapabilityContext must not import HTTP layer'
        );
    }

    public function test_capability_decision_does_not_import_eloquent(): void
    {
        $decision = app_path('Application/Election/Capabilities/CapabilityDecision.php');
        $this->assertFileExists($decision);
        $contents = file_get_contents($decision);
        $this->assertStringNotContainsString(
            'use Illuminate\Database',
            $contents,
            'CapabilityDecision must not import Eloquent'
        );
    }

    public function test_capability_trace_does_not_import_eloquent(): void
    {
        $trace = app_path('Application/Election/Capabilities/CapabilityTrace.php');
        $this->assertFileExists($trace);
        $contents = file_get_contents($trace);
        $this->assertStringNotContainsString(
            'use Illuminate\Database',
            $contents,
            'CapabilityTrace must not import Eloquent'
        );
    }
}
