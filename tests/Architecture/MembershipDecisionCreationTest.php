<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Tests\Architecture\Support\MembershipSourceScan;

/**
 * PBDIGIT-271, Slice 2a steps 8 / 8b, hardened (H2). Repository source-architecture guards, NOT a runtime security
 * boundary.
 *
 * - R7 (S2-F): accepted decisions and new Admit / End facts are created only by the Member aggregate
 *   (and Admit / End rehydrated only by the persistence decoder), so no code bypasses the decide* guards.
 * - R8 (S2-G): rule (statutory) and attested (migration) provenance are constructed in app/ only by the
 *   rehydration decoder. A future migration slice or statutory-admission capability must add its own path
 *   to this allowlist through a separately reviewed and authorised change.
 *
 * H2 (Human PO / ARB, 2026-10-11): names are resolved (imports, aliases, grouped imports, qualified and
 * namespace-relative names, PHP's case-insensitivity); comments and strings are not code. Not covered, by design:
 * dynamic class names, callable arrays / strings, reflection, class_alias, eval, unserialize, code outside app/.
 */
final class MembershipDecisionCreationTest extends TestCase
{
    private const MEMBER = 'app/Contexts/Membership/Domain/Organisational/Member.php';
    private const DECODER = 'app/Contexts/Membership/Infrastructure/Organisational/PostgresMemberRepository.php';
    private const PROVENANCE_DECODER = 'app/Contexts/Membership/Infrastructure/Organisational/ProvenanceJson.php';
    private const NS = 'App\\Contexts\\Membership\\Domain\\Organisational';
    private const PLANTED = 'app/Services/Planted.php';
    /** Known unparsable, unrelated file (PBDIGIT-272; Human PO / ARB, 2026-10-11). PHP cannot load it either. */
    private const UNPARSABLE_KNOWN = ['app/Http/Middleware/VoterSlugStep.php'];

    /** @return list<string> */
    private static function scan(array $sources): array
    {
        return MembershipSourceScan::creationViolations(
            $sources,
            [self::NS . '\\Admit', self::NS . '\\End'],
            [self::NS . '\\Decision' => ['accepted'], self::NS . '\\Provenance' => ['rule', 'attested']],
            [self::MEMBER, self::DECODER, self::PROVENANCE_DECODER],
        );
    }

    private static function planted(string $body): string
    {
        return "<?php\n" . str_replace('%NS%', self::NS, $body) . "\n";
    }

    /** @return array<string, array{string, string}> source => the one expected violation label */
    public static function forbiddenForms(): array
    {
        return [
            'P1 import, literal'            => ["use %NS%\\Decision;\nDecision::accepted(\$f, 1);", 'Decision::accepted'],
            'P2 fully qualified static'     => ["\\%NS%\\Decision::accepted(\$f, 1);", 'Decision::accepted'],
            'P3 partially qualified static' => ["use App\\Contexts\\Membership\\Domain\\Organisational;\nOrganisational\\Decision::accepted(\$f, 1);", 'Decision::accepted'],
            'P4 aliased import'             => ["use %NS%\\Decision as D;\nD::accepted(\$f, 1);", 'Decision::accepted'],
            'P5 grouped aliased import'     => ["use %NS%\\{Decision as D2, Admit};\nD2::accepted(\$f, 1);", 'Decision::accepted'],
            'P6 whitespace and line breaks' => ["use %NS%\\Decision;\nDecision\n    ::\n    accepted (\$f, 1);", 'Decision::accepted'],
            'P7 case-insensitive names'     => ["use %NS%\\Decision;\ndecision::ACCEPTED(\$f, 1);", 'Decision::accepted'],
            'P8 first-class callable'       => ["use %NS%\\Decision;\n\$c = Decision::accepted(...);", 'Decision::accepted'],
            'P9 same namespace, no import'  => ["namespace %NS%;\nDecision::accepted(\$f, 1);", 'Decision::accepted'],
            'P10 namespace-relative static' => ["namespace %NS%;\nnamespace\\Decision::accepted(\$f, 1);", 'Decision::accepted'],
            'P11 self:: in the class'       => ["namespace %NS%;\nfinal class Decision { public function f() { return self::accepted(\$x, 1); } }", 'Decision::accepted'],
            'P12 new, fully qualified'      => ["\$a = new \\%NS%\\Admit(\$id);", 'new Admit'],
            'P13 new, partially qualified'  => ["use App\\Contexts\\Membership\\Domain\\Organisational as Org;\n\$a = new Org\\Admit(\$id);", 'new Admit'],
            'P14 new, namespace-relative'   => ["namespace %NS%;\n\$e = new namespace\\End(\$id);", 'new End'],
            'P15 new, aliased import'       => ["use %NS%\\End as Finish;\n\$e = new Finish(\$id);", 'new End'],
            'P16 new, whitespace and break' => ["use %NS%\\Admit;\n\$a = new\n    Admit (\$id);", 'new Admit'],
            'P17 new, case-insensitive'     => ["use %NS%\\Admit;\n\$a = new admit(\$id);", 'new Admit'],
            'P18 new, without parentheses'  => ["use %NS%\\End;\n\$e = new End;", 'new End'],
            'P19 new, same namespace'       => ["namespace %NS%;\n\$a = new Admit(\$id);", 'new Admit'],
            'P20 provenance, aliased'       => ["use %NS%\\Provenance as P;\n\$p = P::attested('legacy', 'importer');", 'Provenance::attested'],
            'P21 provenance, whitespace'    => ["use %NS%\\Provenance;\n\$p = Provenance :: rule ('statute-7', 'v1');", 'Provenance::rule'],
            'P22 provenance, case'          => ["use %NS%\\Provenance;\n\$p = provenance::Attested('legacy', 'importer');", 'Provenance::attested'],
            'P23 provenance, qualified'     => ["\$p = \\%NS%\\Provenance::rule('statute-7', 'v1');", 'Provenance::rule'],
        ];
    }

    /** @return array<string, array{string}> sources that must not be reported */
    public static function permittedForms(): array
    {
        return [
            'N1 line comments'              => ["use %NS%\\Decision;\n// Decision::accepted(\$f, 1); new Admit(\$a);\n# Provenance::rule('r', 'v');"],
            'N2 block comment and docblock' => ["use %NS%\\Decision;\n/* Decision::accepted(\$f, 1) */\n/** new \\%NS%\\End(\$e); Provenance::attested('s', 'i') */\nfunction f() {}"],
            'N3 strings'                    => ["use %NS%\\Decision;\n\$a = 'Decision::accepted(\$f, 1)';\n\$b = \"new Admit(\\\$a)\";\n\$c = 'Provenance::rule(';"],
            'N4 heredoc and nowdoc'         => ["use %NS%\\Decision;\n\$a = <<<TXT\nDecision::accepted(\$f, 1); new Admit(\$x);\nTXT;\n\$b = <<<'TXT'\nProvenance::attested('s', 'i');\nTXT;"],
            'N5 unrelated same-named class' => ["use App\\Other\\End;\nuse App\\Other\\Decision;\n\$e = new End(\$x);\nDecision::accepted(\$x);"],
            'N6 permitted calls'            => ["use %NS%\\Decision;\nuse %NS%\\Provenance;\nuse %NS%\\EndKind;\nDecision::refused(\$r);\nProvenance::decision('actor', null, 'decl');\nEndKind::fromString('death');\n\$m = new \\App\\Contexts\\Membership\\Application\\Organisational\\EndMembership(...\$args);"],
            'N7 global classes'             => ["\$a = new Admit(\$x);\nDecision::accepted(\$f, 1);"],
        ];
    }

    #[DataProvider('forbiddenForms')]
    public function test_every_written_form_of_a_forbidden_creation_is_detected(string $body, string $label): void
    {
        $this->assertSame([self::PLANTED . ': ' . $label], self::scan([self::PLANTED => self::planted($body)]));
    }

    #[DataProvider('permittedForms')]
    public function test_comments_strings_and_unrelated_classes_are_not_reported(string $body): void
    {
        $this->assertSame([], self::scan([self::PLANTED => self::planted($body)]));
    }

    public function test_allowlisted_creation_paths_are_not_reported(): void
    {
        $this->assertSame([], self::scan([
            self::MEMBER => self::planted("namespace %NS%;\nreturn Decision::accepted(\$fact, 1);\nnew Admit(\$a); new End(\$e);"),
            self::DECODER => self::planted("use %NS%\\Admit;\nuse %NS%\\End;\nnew Admit(\$a); new End(\$e);"),
            self::PROVENANCE_DECODER => self::planted("use %NS%\\Provenance;\nProvenance::rule(\$r, \$v); Provenance::attested(\$s, \$i);"),
        ]));
    }

    public function test_an_unparsable_source_fails_the_scan_instead_of_hiding_a_violation(): void
    {
        $this->expectException(\RuntimeException::class);
        self::scan([self::PLANTED => self::planted("use %NS%\\Decision;\nDecision::accepted(\$f, 1")]);
    }

    public function test_accepted_decisions_new_facts_and_restricted_provenance_are_created_only_on_their_paths(): void
    {
        $sources = MembershipSourceScan::sources(dirname(__DIR__, 2), ['app'], self::UNPARSABLE_KNOWN);

        $this->assertNotEmpty($sources);
        $this->assertSame([], self::scan($sources));
    }

    public function test_the_named_exclusion_still_holds_only_an_unparsable_file(): void
    {
        // Expires with PBDIGIT-272: once the file parses, this fails and the exclusion must be removed.
        $sources = MembershipSourceScan::sources(dirname(__DIR__, 2), ['app/Http/Middleware'], []);
        foreach (self::UNPARSABLE_KNOWN as $path) {
            $this->assertArrayHasKey($path, $sources);
            try {
                self::scan([$path => $sources[$path]]);
                $parsed = true;
            } catch (\RuntimeException) {
                $parsed = false;
            }
            $this->assertFalse($parsed, "$path parses now: remove it from UNPARSABLE_KNOWN (PBDIGIT-272)");
        }
    }
}
