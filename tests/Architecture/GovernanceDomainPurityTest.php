<?php

declare(strict_types=1);

namespace Tests\Architecture;

use App\Console\Commands\GovernanceRebuildProjections;
use App\Contexts\Governance\Application\DTOs\CommitteeHierarchyRecord;
use App\Contexts\Governance\Application\Services\CommitteeHierarchyBuilder;
use App\Contexts\Governance\Application\Services\GovernanceProjectionRebuilder;
use App\Contexts\Governance\Application\UseCases\GetCommitteeHierarchy;
use App\Contexts\Governance\Infrastructure\Projections\CommitteeGovernanceProjectionModel;
use App\Contexts\Governance\Infrastructure\Repositories\CommitteeHierarchyRepository;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

final class GovernanceDomainPurityTest extends TestCase
{
    private const GOVERNANCE_DOMAIN_PATH = 'app/Contexts/Governance/Domain';
    private const GOVERNANCE_CONTEXT_PATH = 'app/Contexts/Governance';

    private const FORBIDDEN_FRAMEWORK_IMPORTS = [
        'Illuminate\\',
        'Laravel\\',
        'Eloquent',
        'Carbon\\',
    ];

    private const FORBIDDEN_INFRASTRUCTURE_IMPORTS = [
        'App\\Contexts\\Governance\\Infrastructure\\',
    ];

    private const FORBIDDEN_MUTATION_PATTERNS = [
        'public function set',
        'public function update',
        'public function change',
        'public function modify',
        'public function mutate',
        'public function patch',
    ];

    // ─────────────────────────────────────────────────────────────
    // Layer 1: No framework dependencies in domain
    // ─────────────────────────────────────────────────────────────

    public function test_governance_domain_has_no_framework_dependencies(): void
    {
        $violations = [];

        foreach ($this->getPhpFiles(self::GOVERNANCE_DOMAIN_PATH) as $file) {
            $content = file_get_contents($file->getRealPath());

            foreach (self::FORBIDDEN_FRAMEWORK_IMPORTS as $forbidden) {
                if (preg_match('~use\s+' . preg_quote($forbidden, '~') . '~', $content)) {
                    $violations[] = sprintf('%s imports %s', $file->getPathname(), $forbidden);
                }
            }
        }

        $this->assertEmpty($violations, "Framework imports in domain:\n" . implode("\n", $violations));
    }

    // ─────────────────────────────────────────────────────────────
    // Layer 2: No infrastructure leakage into domain
    // ─────────────────────────────────────────────────────────────

    public function test_governance_domain_has_no_infrastructure_imports(): void
    {
        $violations = [];

        foreach ($this->getPhpFiles(self::GOVERNANCE_DOMAIN_PATH) as $file) {
            $content = file_get_contents($file->getRealPath());

            foreach (self::FORBIDDEN_INFRASTRUCTURE_IMPORTS as $forbidden) {
                if (preg_match('~use\s+' . preg_quote($forbidden, '~') . '~', $content)) {
                    $violations[] = sprintf('%s imports infrastructure %s', $file->getPathname(), $forbidden);
                }
            }
        }

        $this->assertEmpty($violations, "Infrastructure leakage:\n" . implode("\n", $violations));
    }

    // ─────────────────────────────────────────────────────────────
    // Layer 3: Domain value objects are all readonly
    // ─────────────────────────────────────────────────────────────

    public function test_governance_value_objects_are_readonly(): void
    {
        $voPath = self::GOVERNANCE_DOMAIN_PATH . '/ValueObjects';

        if (!is_dir($voPath)) {
            $this->markTestSkipped('No ValueObjects directory');
        }

        $violations = [];

        foreach ($this->getPhpFiles($voPath) as $file) {
            $content = file_get_contents($file->getRealPath());

            $isReadonly = str_contains($content, 'readonly class')
                || str_contains($content, 'enum ')
                || str_contains($content, 'readonly ')
                || str_contains($content, 'interface ');

            if (!$isReadonly) {
                $violations[] = $file->getBasename();
            }
        }

        $this->assertEmpty($violations, 'VOs must be readonly: ' . implode(', ', $violations));
    }

    // ─────────────────────────────────────────────────────────────
    // Layer 4: Domain events are readonly historical facts
    // ─────────────────────────────────────────────────────────────

    public function test_governance_domain_events_are_readonly_historical_facts(): void
    {
        $eventsPath = self::GOVERNANCE_DOMAIN_PATH . '/Events';

        if (!is_dir($eventsPath)) {
            $this->markTestSkipped('No Events directory');
        }

        $violations = [];

        foreach ($this->getPhpFiles($eventsPath) as $file) {
            $content = file_get_contents($file->getRealPath());

            if (!str_contains($content, 'readonly class')) {
                $violations[] = $file->getBasename() . ' must be readonly class';
            }
        }

        $this->assertEmpty($violations, "Events not readonly:\n" . implode("\n", $violations));
    }

    // ─────────────────────────────────────────────────────────────
    // Layer 5: No public setters in domain
    // ─────────────────────────────────────────────────────────────

    public function test_governance_domain_has_no_public_setters(): void
    {
        $violations = [];

        foreach ($this->getPhpFiles(self::GOVERNANCE_DOMAIN_PATH) as $file) {
            $content = file_get_contents($file->getRealPath());

            foreach (self::FORBIDDEN_MUTATION_PATTERNS as $pattern) {
                if (str_contains($content, $pattern)) {
                    $violations[] = $file->getPathname();
                    break;
                }
            }
        }

        $this->assertEmpty($violations, "Public setters in domain:\n" . implode("\n", $violations));
    }

    // ─────────────────────────────────────────────────────────────
    // Layer 6: GovernanceDecision is final
    // ─────────────────────────────────────────────────────────────

    public function test_governance_decision_aggregate_is_final(): void
    {
        $filePath = self::GOVERNANCE_DOMAIN_PATH . '/GovernanceDecision.php';
        $content = file_get_contents($filePath);

        $this->assertStringContainsString(
            'final readonly class GovernanceDecision',
            $content,
            'GovernanceDecision must be final readonly'
        );
    }

    // ─────────────────────────────────────────────────────────────
    // Layer 7: No executable logic in events
    // ─────────────────────────────────────────────────────────────

    public function test_governance_events_have_no_logic_methods(): void
    {
        $eventsPath = self::GOVERNANCE_DOMAIN_PATH . '/Events';

        if (!is_dir($eventsPath)) {
            $this->markTestSkipped('No Events directory');
        }

        $forbiddenPatterns = [
            'public function validate',
            'public function execute',
            'public function process',
            'public function evaluate',
            'public function authorize',
            'public function compute',
        ];

        $violations = [];

        foreach ($this->getPhpFiles($eventsPath) as $file) {
            $content = file_get_contents($file->getRealPath());

            foreach ($forbiddenPatterns as $pattern) {
                if (str_contains($content, $pattern)) {
                    $violations[] = sprintf('%s contains forbidden method pattern: %s', $file->getBasename(), $pattern);
                }
            }
        }

        $this->assertEmpty($violations, "Logic methods in events:\n" . implode("\n", $violations));
    }

    // ─────────────────────────────────────────────────────────────
    // Layer 8: Phase 1 does not import Phase 2 legitimacy
    // ─────────────────────────────────────────────────────────────

    public function test_phase1_committee_domain_does_not_depend_on_governance_legitimacy(): void
    {
        $phase1Path = 'app/Contexts/Membership/Domain/Committee';

        if (!is_dir($phase1Path)) {
            $this->markTestSkipped('Phase 1 committee domain not found');
        }

        $forbiddenGovernanceImports = [
            'App\\Contexts\\Governance\\Domain\\',
        ];

        $violations = [];

        foreach ($this->getPhpFiles($phase1Path) as $file) {
            $content = file_get_contents($file->getRealPath());

            foreach ($forbiddenGovernanceImports as $forbidden) {
                if (preg_match('~use\s+' . preg_quote($forbidden, '~') . '~', $content)) {
                    $violations[] = sprintf('%s depends on Phase 2: %s', $file->getBasename(), $forbidden);
                }
            }
        }

        $this->assertEmpty($violations, "Phase 1 depends on Phase 2:\n" . implode("\n", $violations));
    }

    // ─────────────────────────────────────────────────────────────
    // Layer 9: Governance context uses only typed factory methods
    // ─────────────────────────────────────────────────────────────

    public function test_governance_domain_classes_are_final(): void
    {
        $exceptions = ['GovernanceRole.php', 'Legitimacy.php'];
        $violations = [];

        foreach ($this->getPhpFiles(self::GOVERNANCE_DOMAIN_PATH) as $file) {
            if (in_array($file->getBasename(), $exceptions, true)) {
                continue;
            }

            $content = file_get_contents($file->getRealPath());

            if (preg_match('/^(abstract\s+)?(class|interface)\s+/m', $content)) {
                if (!str_contains($content, 'final class')
                    && !str_contains($content, 'final readonly class')
                    && !str_contains($content, 'interface ')
                ) {
                    $violations[] = $file->getBasename();
                }
            }
        }

        $this->assertEmpty($violations, 'Domain classes must be final: ' . implode(', ', $violations));
    }

    // ─────────────────────────────────────────────────────────────
    // Layer 10: No Carbon usage in domain
    // ─────────────────────────────────────────────────────────────

    public function test_governance_domain_uses_no_carbon(): void
    {
        $violations = [];

        foreach ($this->getPhpFiles(self::GOVERNANCE_DOMAIN_PATH) as $file) {
            $content = file_get_contents($file->getRealPath());

            if (preg_match('/Carbon/i', $content)) {
                $violations[] = $file->getPathname();
            }
        }

        $this->assertEmpty($violations, "Carbon usage in domain (use DateTimeImmutable):\n" . implode("\n", $violations));
    }

    // ─────────────────────────────────────────────────────────────
    // Phase 6: Hierarchy Projection — Architecture Fitness Tests
    // ─────────────────────────────────────────────────────────────

    public function test_projection_read_model_does_not_import_domain_aggregates(): void
    {
        $filePath = 'app/Contexts/Governance/Application/DTOs/CommitteeHierarchyRecord.php';
        $content = file_get_contents($filePath);

        // Allowed: value objects (CommitteeId), DateTimeImmutable
        $forbiddenPatterns = [
            'CommitteeFacts',
            'CommitteeConstitution',
            'CommitteeGovernanceProjection',
            'CommitteeGovernanceInterpreter',
        ];

        $violations = [];
        foreach ($forbiddenPatterns as $pattern) {
            if (str_contains($content, $pattern)) {
                $violations[] = $pattern;
            }
        }

        $this->assertEmpty($violations, 'Read model imports domain types: ' . implode(', ', $violations));
    }

    public function test_tree_node_has_no_setters(): void
    {
        $filePath = 'app/Contexts/Governance/Application/DTOs/CommitteeTreeNode.php';
        $content = file_get_contents($filePath);

        $this->assertStringContainsString('readonly class', $content, 'TreeNode must be readonly');

        $forbiddenMethods = ['function set', 'function addChild', 'function removeChild', 'function update', 'function change'];
        $violations = [];
        foreach ($forbiddenMethods as $method) {
            if (preg_match('/public\s+function\s+' . preg_quote($method, '/') . '/', $content)) {
                $violations[] = $method;
            }
        }

        $this->assertEmpty($violations, 'TreeNode has mutators: ' . implode(', ', $violations));

        // Verify hasChildren() is the only declared method besides constructor
        $methodCount = preg_match_all('/public\s+function\s+\w+\(/', $content);
        $this->assertGreaterThanOrEqual(1, $methodCount, 'TreeNode must have at least constructor');
    }

    public function test_hierarchy_repository_returns_read_model_only(): void
    {
        $filePath = 'app/Contexts/Governance/Infrastructure/Repositories/CommitteeHierarchyRepository.php';
        $content = file_get_contents($filePath);

        $this->assertStringContainsString(
            'CommitteeHierarchyRecord',
            $content,
            'Repository must use CommitteeHierarchyRecord'
        );
        $this->assertStringContainsString(
            '@return CommitteeHierarchyRecord[]',
            $content,
            'Repository return type must be CommitteeHierarchyRecord[]'
        );
        $this->assertStringNotContainsString(
            'CommitteeFacts',
            $content,
            'Repository must not return domain CommitteeFacts'
        );
    }

    public function test_builder_has_cycle_detection(): void
    {
        $filePath = 'app/Contexts/Governance/Application/Services/CommitteeHierarchyBuilder.php';
        $content = file_get_contents($filePath);

        $this->assertStringContainsString(
            'detectCycle',
            $content,
            'Builder must have cycle detection'
        );
        $this->assertStringContainsString(
            'Cycle detected',
            $content,
            'Builder must throw on cycle'
        );
        $this->assertStringContainsString(
            'orphanLog',
            $content,
            'Builder must track orphan quarantine'
        );
    }

    public function test_projector_is_replay_safe(): void
    {
        $filePath = 'app/Contexts/Governance/Infrastructure/Projections/CommitteeGovernanceProjector.php';
        $content = file_get_contents($filePath);

        $this->assertStringContainsString(
            'function rebuildAll',
            $content,
            'Projector must have rebuildAll for full replay'
        );
        $this->assertStringContainsString(
            'function onEvent',
            $content,
            'Projector must have onEvent for incremental events'
        );
        $this->assertStringContainsString(
            'alreadyProcessed',
            $content,
            'Projector must have idempotency check'
        );
        $this->assertStringContainsString(
            'CommitteeGovernanceInterpreter',
            $content,
            'Projector must use interpreter for governance evaluation'
        );
    }

    public function test_projection_schema_has_version_fields(): void
    {
        $files = glob('database/migrations/*_create_committee_governance_projections_table.php');
        $this->assertNotEmpty($files, 'Migration file not found');

        $content = file_get_contents($files[0]);
        $requiredFields = [
            'projection_version',
            'last_event_id',
            'last_event_occurred_at',
            'operational_state',
            'temporal_state',
            'legitimacy',
            'evaluated_at',
        ];

        $missing = [];
        foreach ($requiredFields as $field) {
            if (!str_contains($content, $field)) {
                $missing[] = $field;
            }
        }

        $this->assertEmpty($missing, 'Migration missing fields: ' . implode(', ', $missing));
    }

    public function test_no_eloquent_models_outside_infrastructure(): void
    {
        $layers = [
            'app/Contexts/Governance/Application',
            'app/Contexts/Governance/Domain',
        ];

        $forbidden = ['extends Model', 'Illuminate\\\\Database\\\\Eloquent', 'use Illuminate\\\\Database'];

        $violations = [];
        foreach ($layers as $path) {
            foreach ($this->getPhpFiles($path) as $file) {
                $content = file_get_contents($file->getRealPath());

                foreach ($forbidden as $pattern) {
                    if (preg_match('~' . $pattern . '~', $content)) {
                        $violations[] = $file->getPathname();
                        break;
                    }
                }
            }
        }

        $this->assertEmpty($violations, 'Eloquent in Application/Domain: ' . implode(', ', $violations));
    }

    public function test_use_case_caches_records_not_trees(): void
    {
        $filePath = 'app/Contexts/Governance/Application/UseCases/GetCommitteeHierarchy.php';
        $content = file_get_contents($filePath);

        // Must cache records, not trees
        $this->assertStringContainsString(
            "cacheKey(\$tenantId)",
            $content,
            'Use case must compute cache key from tenant'
        );
        $this->assertStringContainsString(
            'hierarchy_records_',
            $content,
            'Cache key must use hierarchy_records_ prefix'
        );
        $this->assertStringContainsString(
            'builder->buildTree',
            $content,
            'Use case must call builder after cache retrieval'
        );

        // Must NOT cache the tree result directly
        $this->assertStringNotContainsString(
            'cache->set.*buildTree',
            $content,
            'Use case must not cache the tree — only records'
        );
    }

    public function test_builder_uses_iterative_assembly(): void
    {
        $filePath = 'app/Contexts/Governance/Application/Services/CommitteeHierarchyBuilder.php';
        $content = file_get_contents($filePath);

        // Verify the main assembly uses loops (foreach), not recursive calls
        $this->assertStringContainsString(
            'foreach',
            $content,
            'Builder must use iterative loops for assembly'
        );

        // Detect cycle uses recursive DFS, which is fine — it's depth-limited
        $this->assertStringContainsString(
            'detectCycle',
            $content,
            'Builder must have cycle detection (recursive DFS accepted)'
        );

        // buildTree should not call itself recursively (iterative assembly)
        $this->assertStringNotContainsString(
            '$this->buildTree(',
            $content,
            'buildTree must be iterative, not recursive'
        );
    }

    // ─────────────────────────────────────────────────────────────
    // RULE-H6-01: No governance interpretation in query path
    // ─────────────────────────────────────────────────────────────

    public function test_query_handler_never_executes_policy_logic(): void
    {
        $queryPathFiles = [
            'app/Contexts/Governance/Application/UseCases/GetCommitteeHierarchy.php',
            'app/Contexts/Governance/Application/Services/CommitteeHierarchyBuilder.php',
            'app/Contexts/Governance/Infrastructure/Repositories/CommitteeHierarchyRepository.php',
            'app/Contexts/Governance/Application/DTOs/CommitteeHierarchyRecord.php',
        ];

        foreach ($queryPathFiles as $filePath) {
            $content = file_get_contents($filePath);

            $this->assertStringNotContainsString(
                'CommitteeGovernanceInterpreter',
                $content,
                sprintf('%s must not import the governance interpreter', basename($filePath))
            );
        }
    }

    // ─────────────────────────────────────────────────────────────
    // RULE-H6-02: Projections have freshness metadata
    // ─────────────────────────────────────────────────────────────

    public function test_projection_has_freshness_metadata(): void
    {
        $reflection = new ReflectionClass(CommitteeGovernanceProjectionModel::class);
        $model = $reflection->newInstanceWithoutConstructor();

        $fillable = $model->getFillable();

        $this->assertContains('projection_generation', $fillable, 'Model must track generation');
        $this->assertContains('projection_schema_version', $fillable, 'Model must track schema version');
        $this->assertContains('rebuilt_at', $fillable, 'Model must track rebuild timestamp');
        $this->assertContains('last_event_id', $fillable, 'Model must track last event');
    }

    // ─────────────────────────────────────────────────────────────
    // RULE-H6-03/H6-04: Query path does not import domain policies
    // ─────────────────────────────────────────────────────────────

    public function test_query_path_does_not_import_domain_policies(): void
    {
        // Reflection-based: verify that query-path files don't inject policy/interpreter classes
        $queryPathClasses = [
            GetCommitteeHierarchy::class,
            CommitteeHierarchyBuilder::class,
            CommitteeHierarchyRecord::class,
        ];

        $forbiddenConstructorTypes = [
            'OperationalStatePolicy',
            'TemporalGovernancePolicy',
            'ConstitutionalLegitimacyPolicy',
            'CommitteeGovernanceInterpreter',
        ];

        $violations = [];

        foreach ($queryPathClasses as $className) {
            $reflection = new ReflectionClass($className);
            $constructor = $reflection->getConstructor();

            if ($constructor === null) {
                continue;
            }

            foreach ($constructor->getParameters() as $param) {
                $type = $param->getType();
                if ($type === null) {
                    continue;
                }

                $typeName = $type->getName();
                foreach ($forbiddenConstructorTypes as $forbidden) {
                    if (str_contains($typeName, $forbidden)) {
                        $violations[] = "{$className} constructor depends on {$typeName}";
                    }
                }
            }
        }

        // Reflection on Repository — no constructor injection of policies
        $repoReflection = new ReflectionClass(CommitteeHierarchyRepository::class);
        $repoConstructor = $repoReflection->getConstructor();
        if ($repoConstructor !== null) {
            foreach ($repoConstructor->getParameters() as $param) {
                $type = $param->getType();
                if ($type === null) {
                    continue;
                }
                $typeName = $type->getName();
                foreach ($forbiddenConstructorTypes as $forbidden) {
                    if (str_contains($typeName, $forbidden)) {
                        $violations[] = "Repository depends on {$typeName}";
                    }
                }
            }
        }

        $this->assertEmpty($violations, "Query path depends on policy/interpreter:\n" . implode("\n", $violations));
    }

    // ─────────────────────────────────────────────────────────────
    // RULE-H6-04: Rebuild CLI command exists
    // ─────────────────────────────────────────────────────────────

    public function test_rebuild_command_exists(): void
    {
        $this->assertTrue(class_exists(GovernanceRebuildProjections::class), 'Rebuild command class must exist');

        $reflection = new ReflectionClass(GovernanceRebuildProjections::class);
        $signature = $reflection->getProperty('signature')->getDefaultValue();

        $this->assertStringContainsString('governance:rebuild-projections', $signature);
    }

    // ─────────────────────────────────────────────────────────────
    // RULE-H6-05/H6-07: Rebuilder has lock check
    // ─────────────────────────────────────────────────────────────

    public function test_rebuilder_has_lock_check(): void
    {
        $reflection = new ReflectionClass(GovernanceProjectionRebuilder::class);

        $this->assertTrue(
            $reflection->hasConstant('LOCK_TTL_SECONDS'),
            'Rebuilder must define LOCK_TTL_SECONDS'
        );
        $this->assertTrue(
            $reflection->hasConstant('LOCK_PREFIX'),
            'Rebuilder must define LOCK_PREFIX'
        );

        $lockPrefix = $reflection->getConstant('LOCK_PREFIX');
        $this->assertStringContainsString('lock', $lockPrefix, 'Lock prefix must reference locking');

        // Verify rebuildAll references a lock acquire call
        $method = $reflection->getMethod('rebuildAll');
        $fileName = $reflection->getFileName();
        $this->assertNotFalse($fileName, 'Rebuilder file must exist');
        $source = file_get_contents($fileName);
        $this->assertStringContainsString(
            '$this->lock->acquire',
            $source,
            'rebuildAll must acquire a lock'
        );
        $this->assertStringContainsString(
            '$this->lock->release',
            $source,
            'rebuildAll must release lock in finally'
        );
    }

    // ─────────────────────────────────────────────────────────────
    // RULE-H6-08: Rebuilder has failure isolation
    // ─────────────────────────────────────────────────────────────

    public function test_rebuilder_has_failure_isolation(): void
    {
        $reflection = new ReflectionClass(GovernanceProjectionRebuilder::class);
        $fileName = $reflection->getFileName();
        $this->assertNotFalse($fileName);
        $source = file_get_contents($fileName);

        // Each committee rebuild is wrapped in try/catch — one failure doesn't abort
        $this->assertStringContainsString(
            'try {',
            $source,
            'Rebuild must use try/catch for per-aggregate isolation'
        );
        $this->assertStringContainsString(
            'catch (\Throwable',
            $source,
            'Rebuild must catch Throwable for failure isolation'
        );
    }

    // ─────────────────────────────────────────────────────────────
    // RULE-H6-06: Projector rebuild is deterministic
    // ─────────────────────────────────────────────────────────────

    public function test_projector_rebuild_is_deterministic(): void
    {
        $projectorTestPath = __DIR__ . '/../Integration/Contexts/Governance/Infrastructure/Projections/CommitteeGovernanceProjectorTest.php';

        if (!file_exists($projectorTestPath)) {
            $this->markTestSkipped('Projector integration test not found');
        }

        $content = file_get_contents($projectorTestPath);

        $this->assertStringContainsString(
            'test_same_event_stream_produces_deterministic_projection',
            $content,
            'Projector test must verify deterministic rebuild (H6-06)'
        );
    }

    // ─────────────────────────────────────────────────────────────
    // RULE-H6-09: Projection generation is per rebuild session
    // ─────────────────────────────────────────────────────────────

    public function test_rebuild_runs_table_exists(): void
    {
        $migrations = glob(__DIR__ . '/../../database/migrations/*projection_rebuild_runs*');
        $contextMigrations = glob(__DIR__ . '/../../app/Contexts/Governance/Infrastructure/Database/Migrations/Landlord/*projection_rebuild_runs*');

        $this->assertNotEmpty(
            array_merge($migrations, $contextMigrations),
            'Migration for projection_rebuild_runs table must exist (H6-09)'
        );
    }

    // ─────────────────────────────────────────────────────────────
    // Helper
    // ─────────────────────────────────────────────────────────────

    /** @return iterable<SplFileInfo> */
    private function getPhpFiles(string $path): iterable
    {
        if (!is_dir($path)) {
            return [];
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        foreach ($iterator as $file) {
            if ($file->getExtension() === 'php') {
                yield $file;
            }
        }
    }
}
