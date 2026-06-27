<?php

declare(strict_types=1);

namespace App\Shared\Architecture\Parser;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

final class PhpFileVisitor extends NodeVisitorAbstract
{
    private ?string $namespace = null;

    /** @var Import[] */
    private array $imports = [];

    /** @var ClassLike[] */
    private array $classes = [];

    /** @var Method[] */
    private array $methods = [];

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
            $this->recordClassLike($node);
        }

        if ($node instanceof Node\Stmt\Enum_) {
            $this->recordEnum($node);
        }
    }

    public function getPhpFile(string $path): PhpFile
    {
        return new PhpFile(
            path: $path,
            namespace: $this->namespace,
            imports: $this->imports,
            classes: $this->classes,
            methods: $this->methods,
        );
    }

    // ─── Private helpers ───────────────────────────────────────────────

    private function recordClassLike(Node\Stmt\Class_|Node\Stmt\Interface_|Node\Stmt\Trait_ $node): void
    {
        $name = $node->name?->toString() ?? '(anonymous)';
        $type = match (true) {
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
        $methods = [];
        $properties = [];
        foreach ($node->stmts as $stmt) {
            if ($stmt instanceof Node\Stmt\TraitUse) {
                foreach ($stmt->traits as $trait) {
                    $traits[] = $trait->toString();
                }
            }

            if ($stmt instanceof Node\Stmt\ClassMethod) {
                $methods[] = $this->recordMethod($stmt);
            }

            if ($stmt instanceof Node\Stmt\Property_) {
                foreach ($stmt->props as $prop) {
                    $properties[] = '$' . $prop->name->toString();
                }
            }
        }

        $attributes = [];
        foreach ($node->attrGroups as $attrGroup) {
            foreach ($attrGroup->attrs as $attr) {
                $attributes[] = $attr->name->toString();
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

    private function recordEnum(Node\Stmt\Enum_ $node): void
    {
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
                $methods[] = $this->recordMethod($stmt);
            }
        }

        $classLike = new ClassLike(
            name: $name,
            type: 'enum',
            isFinal: true,
            isReadonly: false,
            isAbstract: false,
            extends: null,
            implements: [],
            traits: [],
            attributes: $attributes,
            methods: $methods,
            properties: [],
        );

        $this->classes[] = $classLike;
        $this->methods = array_merge($this->methods, $methods);
    }

    private function recordMethod(Node\Stmt\ClassMethod $stmt): Method
    {
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

        return new Method(
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
}
