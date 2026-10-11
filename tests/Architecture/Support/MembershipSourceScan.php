<?php

declare(strict_types=1);

namespace Tests\Architecture\Support;

use PhpParser\Error;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;

/**
 * Static source scan for the Membership fitness tests (PBDIGIT-271: R5, R7, R8). No Laravel boot, no database.
 * A source-architecture guard, not a runtime security boundary.
 */
final class MembershipSourceScan
{
    /**
     * @param list<string> $dirs            repository-relative directories to scan
     * @param list<string> $excludePrefixes repository-relative path prefixes to skip
     * @return array<string, string> repository-relative path => source
     */
    public static function sources(string $root, array $dirs, array $excludePrefixes): array
    {
        $out = [];
        foreach ($dirs as $dir) {
            $base = $root . '/' . $dir;
            if (!is_dir($base)) {
                continue;
            }
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($base, \FilesystemIterator::SKIP_DOTS)) as $file) {
                $path = (string) $file;
                if (!str_ends_with($path, '.php')) {
                    continue;
                }
                $relative = substr($path, strlen($root) + 1);
                foreach ($excludePrefixes as $prefix) {
                    if (str_starts_with($relative, $prefix)) {
                        continue 2;
                    }
                }
                $out[$relative] = (string) file_get_contents($path);
            }
        }

        return $out;
    }

    /**
     * The first forbidden pattern found in each non-allowlisted source, as "path: pattern", sorted by path.
     * Doubled backslashes (namespaces written inside string literals) are normalised before matching.
     *
     * @param array<string, string> $sources
     * @param list<string> $forbidden literal patterns
     * @param list<string> $allowlist repository-relative paths that may contain them
     * @return list<string>
     */
    public static function violations(array $sources, array $forbidden, array $allowlist): array
    {
        ksort($sources);
        $violations = [];
        foreach ($sources as $path => $source) {
            if (in_array($path, $allowlist, true)) {
                continue;
            }
            $normalised = str_replace('\\\\', '\\', $source);
            foreach ($forbidden as $pattern) {
                if (str_contains($normalised, $pattern)) {
                    $violations[] = "$path: $pattern";
                    break;
                }
            }
        }

        return $violations;
    }

    /**
     * The first forbidden `new` or static call found in each non-allowlisted source, as "path: new Admit" or
     * "path: Decision::accepted", sorted by path (hardening H2, PBDIGIT-271). Parsed with nikic/php-parser and its
     * name resolver, so imports, aliases, grouped imports, qualified and namespace-relative names and self / static /
     * parent are resolved, names compare case-insensitively as in PHP, and comments and strings are not code.
     * Dynamic class names, callable arrays / strings, reflection, class_alias and eval are not seen.
     *
     * @param array<string, string> $sources
     * @param list<string> $constructed fully qualified classes whose `new` is forbidden
     * @param array<string, list<string>> $staticCalls fully qualified class => forbidden static methods
     * @param list<string> $allowlist repository-relative paths that may contain them
     * @return list<string>
     * @throws \RuntimeException when a source cannot be parsed, so a parse failure cannot hide a violation
     */
    public static function creationViolations(array $sources, array $constructed, array $staticCalls, array $allowlist): array
    {
        $forbidden = [];
        foreach ($constructed as $class) {
            $forbidden['new ' . strtolower($class)] = 'new ' . self::shortName($class);
        }
        foreach ($staticCalls as $class => $methods) {
            foreach ($methods as $method) {
                $forbidden[strtolower($class) . '::' . strtolower($method)] = self::shortName($class) . '::' . $method;
            }
        }

        $parser = (new ParserFactory())->createForNewestSupportedVersion();
        ksort($sources);
        $violations = [];
        foreach ($sources as $path => $source) {
            if (in_array($path, $allowlist, true)) {
                continue;
            }
            try {
                $ast = $parser->parse($source) ?? [];
            } catch (Error $e) {
                throw new \RuntimeException("Cannot parse $path: {$e->getMessage()}", 0, $e);
            }
            $finder = self::creationFinder();
            (new NodeTraverser(new NameResolver(), $finder))->traverse($ast);
            foreach ($finder->found as $key) {
                if (isset($forbidden[$key])) {
                    $violations[] = "$path: {$forbidden[$key]}";
                    break;
                }
            }
        }

        return $violations;
    }

    /** Collects "new <fqcn>" and "<fqcn>::<method>" keys (lower case) in source order. */
    private static function creationFinder(): NodeVisitorAbstract
    {
        return new class () extends NodeVisitorAbstract {
            /** @var list<string> */
            public array $found = [];

            /** @var list<array{string, ?string}> enclosing [class, parent] pairs */
            private array $classes = [];

            public function enterNode(Node $node)
            {
                if ($node instanceof Node\Stmt\Class_) {
                    $this->classes[] = [(string) $node->namespacedName, $node->extends === null ? null : $node->extends->toString()];
                } elseif ($node instanceof Node\Expr\New_ && $node->class instanceof Node\Name) {
                    $this->record('new ', $node->class, '');
                } elseif ($node instanceof Node\Expr\StaticCall && $node->class instanceof Node\Name && $node->name instanceof Node\Identifier) {
                    $this->record('', $node->class, '::' . $node->name->toLowerString());
                }

                return null;
            }

            public function leaveNode(Node $node)
            {
                if ($node instanceof Node\Stmt\Class_) {
                    array_pop($this->classes);
                }

                return null;
            }

            private function record(string $prefix, Node\Name $name, string $suffix): void
            {
                $class = $name->toString();
                if ($name->isSpecialClassName()) {
                    $enclosing = end($this->classes) ?: [null, null];
                    $class = $name->toLowerString() === 'parent' ? $enclosing[1] : $enclosing[0];
                }
                if ($class !== null) {
                    $this->found[] = $prefix . strtolower(ltrim($class, '\\')) . $suffix;
                }
            }
        };
    }

    private static function shortName(string $class): string
    {
        $position = strrpos($class, '\\');

        return $position === false ? $class : substr($class, $position + 1);
    }
}
