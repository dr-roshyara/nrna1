<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * PBDIGIT-354 S2-4d (D-239): scheduled resignations stay fail closed in production until Organisation S4 supplies the
 * organisation calendar. Guards, on code only (comments are removed before scanning):
 *
 * - G1 no production class implements OrganisationCalendar (its production answer belongs to Organisation S4; when S4
 *      lands, this guard is changed in that authorised step, together with G3);
 * - G2 no production code refers to a test calendar or to anything under Tests\;
 * - G3 the production closer keeps NoDueScheduledResignations: ScheduledResignationExecutor is constructed nowhere in
 *      app/, and RoutedLedgerBoundary builds its closer without an executor;
 * - G4 Membership's organisational code and its organisation migrations never read a time zone (no legacy
 *      `organisations.timezone`, no Membership-owned time zone, no UTC substitute).
 */
final class MembershipScheduledResignationWiringTest extends TestCase
{
    private const ROOT = __DIR__ . '/../..';

    /** @return array<string, string> path => code without comments */
    private static function sources(string ...$dirs): array
    {
        $out = [];
        foreach ($dirs as $dir) {
            $it = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(self::ROOT . '/' . $dir, \FilesystemIterator::SKIP_DOTS));
            foreach ($it as $f) {
                if ($f->getExtension() === 'php') {
                    $out[substr($f->getPathname(), strlen(self::ROOT) + 1)] = self::code((string) file_get_contents($f->getPathname()));
                }
            }
        }

        return $out;
    }

    private static function code(string $php): string
    {
        $code = '';
        foreach (token_get_all($php) as $t) {
            if (is_array($t) && in_array($t[0], [T_COMMENT, T_DOC_COMMENT], true)) {
                continue;
            }
            $code .= is_array($t) ? $t[1] : $t;
        }

        return $code;
    }

    /**
     * @param array<string, string> $sources path => code
     * @return list<string> "path: rule"
     */
    private static function violations(array $sources): array
    {
        $found = [];
        foreach ($sources as $path => $code) {
            if (preg_match('/implements\s+[^{]*\bOrganisationCalendar\b/', $code)) {
                $found[] = "$path: G1 implements OrganisationCalendar";
            }
            if (preg_match('/\bFixedOffsetOrganisationCalendar\b|\\\\?Tests\\\\/', $code)) {
                $found[] = "$path: G2 refers to test code";
            }
            if (preg_match('/new\s+\\\\?(?:[A-Za-z_\\\\]*\\\\)?ScheduledResignationExecutor\b/', $code)) {
                $found[] = "$path: G3 constructs ScheduledResignationExecutor";
            }
            // a time-zone SETTING (a column, config key, property or variable), not PHP's DateTimeZone class, which
            // DateOrdinal uses only to turn a date label into a day number (no instant becomes a local date there)
            if (str_contains($path, 'Contexts/Membership/') && preg_match('/(?<![A-Za-z])time_?zone/i', $code)) {
                $found[] = "$path: G4 reads a time zone";
            }
        }

        return $found;
    }

    public function test_the_scanner_finds_each_planted_violation(): void
    {
        $planted = [
            'app/Contexts/Organisation/Calendar.php' => self::code("<?php\nfinal class Calendar implements \\X\\OrganisationCalendar {}\n"),
            'app/Providers/Wiring.php' => self::code("<?php\n\$c = new \\Tests\\Support\\FixedOffsetOrganisationCalendar(0);\n"),
            'app/Contexts/Membership/Infrastructure/Organisational/Boundary.php' => self::code("<?php\n\$e = new ScheduledResignationExecutor(\$cal);\n"),
            'app/Contexts/Membership/Infrastructure/Organisational/Tz.php' => self::code("<?php\n\$tz = DB::table('organisations')->value('timezone');\n"),
            'app/Contexts/Membership/Infrastructure/Organisational/Comment.php' => self::code("<?php\n// organisations.timezone is never read; new ScheduledResignationExecutor(...)\n"),
            'app/Contexts/Membership/Infrastructure/Organisational/Label.php' => self::code("<?php\n\$d = new \\DateTimeImmutable('1970-01-01', new \\DateTimeZone('UTC'));\n"),
            'app/Contexts/Membership/Infrastructure/Organisational/Config.php' => self::code("<?php\n\$tz = config('app.timezone');\n"),
        ];

        $this->assertSame([
            'app/Contexts/Organisation/Calendar.php: G1 implements OrganisationCalendar',
            'app/Providers/Wiring.php: G2 refers to test code',
            'app/Contexts/Membership/Infrastructure/Organisational/Boundary.php: G3 constructs ScheduledResignationExecutor',
            'app/Contexts/Membership/Infrastructure/Organisational/Tz.php: G4 reads a time zone',
            'app/Contexts/Membership/Infrastructure/Organisational/Config.php: G4 reads a time zone',
        ], self::violations($planted), 'a comment is never a violation; a date-label DateTimeZone is not a time-zone setting');
    }

    public function test_production_code_has_no_calendar_no_test_calendar_no_executor_wiring_and_no_time_zone(): void
    {
        $this->assertSame([], self::violations(self::sources('app', 'config', 'routes')));
    }

    public function test_the_production_ledger_boundary_builds_its_closer_without_an_executor(): void
    {
        $code = self::code((string) file_get_contents(self::ROOT . '/app/Contexts/Membership/Infrastructure/Organisational/RoutedLedgerBoundary.php'));

        $this->assertMatchesRegularExpression('/new LedgerClockCloser\(\$this->connections->connectionFor\(\$organisation\), \$this->clock\)/', $code,
            'G3: the production closer takes no DueScheduledResignations, so it keeps NoDueScheduledResignations (D-239)');
    }
}
