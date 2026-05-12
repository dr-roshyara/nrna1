<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Parser;

use PhpParser\Error;
use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use SplFileInfo;
use RuntimeException;

final class PhpFileParser
{
    private readonly \PhpParser\Parser $parser;

    public function __construct(
        private readonly ParseFailurePolicy $failurePolicy = ParseFailurePolicy::WARN_AND_SKIP,
    ) {
        $this->parser = (new ParserFactory())->createForNewestSupportedVersion();
    }

    public function parse(SplFileInfo $file): ?PhpFile
    {
        $content = file_get_contents($file->getRealPath());

        if ($content === false || $content === '') {
            return null;
        }

        try {
            $stmts = $this->parser->parse($content);
        } catch (Error $e) {
            return match ($this->failurePolicy) {
                ParseFailurePolicy::FAIL_FAST => throw new RuntimeException(
                    sprintf('Parse error in %s: %s', $file->getRealPath(), $e->getMessage()),
                    0,
                    $e,
                ),
                ParseFailurePolicy::WARN_AND_SKIP => null,
                ParseFailurePolicy::LEGACY_FALLBACK => null,
            };
        }

        if ($stmts === null) {
            return null;
        }

        $visitor = new class extends NodeVisitorAbstract
        {
            public ?string $namespace = null;
            /** @var Import[] */
            public array $imports = [];
            /** @var ClassLike[] */
            public array $classes = [];
            /** @var Method[] */
            public array $methods = [];

            public function enterNode(Node $node): void
            {
                if ($node instanceof Node\Stmt\Namespace_) {
                    $this->namespace = $node->name?->toString();
                }

                if ($node instanceof Node\Stmt\Use_) {
                    foreach ($node->uses as $use) {
                        $type = match ($node->type) {
                            Node\Stmt\Use_::TYPE_FUNCTION => 'use_function',
                            Node\Stmt\Use_::TYPE_CONSTANT => 'use_const',
                            default => 'use',
                        };
                        $this->imports[] = new Import(
                            namespace: $use->name->toString(),
                            alias: $use->alias?->toString() ?? $use->name->getLast(),
                            type: $type,
                            line: $use->getLine(),
                        );
                    }
                }

                if ($node instanceof Node\Stmt\Class_ || $node instanceof Node\Stmt\Interface_ || $node instanceof Node\Stmt\Trait_) {
                    $name = $node->name?->toString() ?? '(anonymous)';
                    $type = match (true) {
                        $node instanceof Node\Stmt\Class_ => 'class',
                        $node instanceof Node\Stmt\Interface_ => 'interface',
                        $node instanceof Node\Stmt\Trait_ => 'trait',
                        default => 'class',
                    };

                    $extends = null;
                    if ($node instanceof Node\Stmt\Class_ && $node->extends !== null) {
                        $extends = $node->extends->toString();
                    }

                    $implements = [];
                    if ($node instanceof Node\Stmt\Class_) {
                        foreach ($node->implements as $iface) {
                            $implements[] = $iface->toString();
                        }
                    }

                    $traits = [];
                    foreach ($node->stmts as $stmt) {
                        if ($stmt instanceof Node\Stmt\TraitUse) {
                            foreach ($stmt->traits as $trait) {
                                $traits[] = $trait->toString();
                            }
                        }
                    }

                    $attributes = [];
                    foreach ($node->attrGroups as $attrGroup) {
                        foreach ($attrGroup->attrs as $attr) {
                            $attributes[] = $attr->name->toString();
                        }
                    }

                    $methods = [];
                    $properties = [];
                    foreach ($node->stmts as $stmt) {
                        if ($stmt instanceof Node\Stmt\ClassMethod) {
                            $methodName = $stmt->name->toString();
                            $returnType = $stmt->returnType !== null
                                ? $this->resolveTypeName($stmt->returnType)
                                : null;

                            $parameterTypes = [];
                            foreach ($stmt->params as $param) {
                                if ($param->type !== null) {
                                    $parameterTypes[] = $this->resolveTypeName($param->type);
                                }
                            }

                            $statementCount = 0;
                            if ($stmt->stmts !== null) {
                                $statementCount = count($stmt->stmts);
                            }

                            $methods[] = new Method(
                                name: $methodName,
                                visibility: $stmt->isPublic() ? 'public' : ($stmt->isProtected() ? 'protected' : 'private'),
                                isStatic: $stmt->isStatic(),
                                isAbstract: $stmt->isAbstract(),
                                isFinal: $stmt->isFinal(),
                                returnType: $returnType,
                                returnsVoid: $returnType === 'void',
                                parameterCount: count($stmt->params),
                                parameterTypes: $parameterTypes,
                                hasBody: $stmt->stmts !== null,
                                statementCount: $statementCount,
                                line: $stmt->getLine(),
                            );
                        }

                        if ($stmt instanceof Node\Stmt\Property_) {
                            foreach ($stmt->props as $prop) {
                                $properties[] = '$' . $prop->name->toString();
                            }
                        }
                    }

                    $classLike = new ClassLike(
                        name: $name,
                        type: $type,
                        isFinal: $node instanceof Node\Stmt\Class_ && $node->isFinal(),
                        isReadonly: $node instanceof Node\Stmt\Class_ && $node->isReadonly(),
                        isAbstract: $node instanceof Node\Stmt\Class_ && $node->isAbstract(),
                        extends: $extends,
                        implements: $implements,
                        traits: $traits,
                        attributes: $attributes,
                        methods: $methods,
                        properties: $properties,
                    );

                    $this->classes[] = $classLike;
                    $this->methods = array_merge($this->methods, $methods);
                }

                if ($node instanceof Node\Stmt\Enum_) {
                    $name = $node->name?->toString() ?? '(anonymous)';

                    $attributes = [];
                    foreach ($node->attrGroups as $attrGroup) {
                        foreach ($attrGroup->attrs as $attr) {
                            $attributes[] = $attr->name->toString();
                        }
                    }

                    $methods = [];
                    foreach ($node->stmts as $stmt) {
                        if ($stmt instanceof Node\Stmt\ClassMethod) {
                            $methodName = $stmt->name->toString();
                            $returnType = $stmt->returnType !== null
                                ? $this->resolveTypeName($stmt->returnType)
                                : null;

                            $parameterTypes = [];
                            foreach ($stmt->params as $param) {
                                if ($param->type !== null) {
                                    $parameterTypes[] = $this->resolveTypeName($param->type);
                                }
                            }

                            $statementCount = 0;
                            if ($stmt->stmts !== null) {
                                $statementCount = count($stmt->stmts);
                            }

                            $methods[] = new Method(
                                name: $methodName,
                                visibility: $stmt->isPublic() ? 'public' : ($stmt->isProtected() ? 'protected' : 'private'),
                                isStatic: $stmt->isStatic(),
                                isAbstract: $stmt->isAbstract(),
                                isFinal: $stmt->isFinal(),
                                returnType: $returnType,
                                returnsVoid: $returnType === 'void',
                                parameterCount: count($stmt->params),
                                parameterTypes: $parameterTypes,
                                hasBody: $stmt->stmts !== null,
                                statementCount: $statementCount,
                                line: $stmt->getLine(),
                            );
                        }
                    }

                    $classLike = new ClassLike(
                        name: $name,
                        type: 'enum',
                        isFinal: true,
                        isReadonly: false,
                        isAbstract: false,
                        extends: null,
                        attributes: $attributes,
                        methods: $methods,
                    );

                    $this->classes[] = $classLike;
                    $this->methods = array_merge($this->methods, $methods);
                }
            }

            private function resolveTypeName(Node|null $type): string
            {
                if ($type === null) {
                    return 'mixed';
                }

                if ($type instanceof Node\NullableType) {
                    return '?' . $this->resolveTypeName($type->type);
                }

                if ($type instanceof Node\UnionType) {
                    return implode('|', array_map(fn ($t) => $this->resolveTypeName($t), $type->types));
                }

                if ($type instanceof Node\IntersectionType) {
                    return implode('&', array_map(fn ($t) => $this->resolveTypeName($t), $type->types));
                }

                return $type->toString();
            }
        };

        $traverser = new NodeTraverser();
        $traverser->addVisitor($visitor);
        $traverser->traverse($stmts);

        return new PhpFile(
            path: $file->getRealPath(),
            namespace: $visitor->namespace,
            imports: $visitor->imports,
            classes: $visitor->classes,
            methods: $visitor->methods,
        );
    }
}
