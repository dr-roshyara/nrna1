#!/usr/bin/env python3
"""Python semantic-fact extractor -- KOS-CONTRACT-NEUTRALITY-001 / KOS-PYTHON-RULE-VALIDATION.

BOUNDED, DELIBERATELY SMALL -- grown one authorized, evidenced slice at a time (each
addition traceable to a grant, named at the call site that implements it). Not a general
Python semantic engine: no dynamic dispatch, no general cross-unit name resolution beyond
an analysed class's own bare name and its direct module-level aliases, no
__getattr__/__getattribute__, no multi-inheritance MRO beyond next-in-line `super()`.

Uses the real `ast` module (semantic AST analysis), not regex -- the whole point
of this experiment is to test whether ATTRIBUTE SYNTAX (self.value) can be
correctly resolved to its LANGUAGE MEANING (a property getter call, i.e. a real
method invocation) rather than transcribed as attribute access by default. A
regex-based reader could not make this distinction reliably; that is exactly
the failure mode this experiment exists to test for.

Contract: reads Python source on stdin, writes one JSON object on stdout,
naming exactly the fields needed to construct the existing PHP-side Domain
types (DeclaredUnit/MethodFacts/BehaviourReference/StateAccess) unchanged.
"""
import ast
import json
import sys
from collections import deque

# Python's own spelling for a lifecycle method. This adapter is the one place permitted to
# know it -- Domain consumes only the resulting methodRole, never this name.
_LIFECYCLE_METHOD_NAMES = {"__init__", "__del__"}


def _walk_own_scope(node: ast.AST):
    """Like `ast.walk`, but does not descend into a nested `def`/`async def`/`lambda`'s
    own body -- it has its own local scope; its self/cls references belong to it, not to
    the enclosing method (OWD-5, KOS-PYTHON-RULE-VALIDATION, 2026-09-27; grounded in real
    corpus evidence -- every real nested-closure occurrence found in CPython's stdlib
    (`contextlib.py`) was a "define and return, never call it here" wrapper/decorator
    factory, where the closure's own references are provably NOT part of the enclosing
    method's own direct execution). Comprehensions/generator expressions are
    DELIBERATELY NOT scope boundaries here: unlike a nested def, their body executes
    immediately as part of the enclosing method, so self/cls references inside them
    correctly remain attributed to it, unchanged."""
    todo = deque(ast.iter_child_nodes(node))
    while todo:
        current = todo.popleft()
        yield current
        if isinstance(current, (ast.FunctionDef, ast.AsyncFunctionDef, ast.Lambda)):
            continue
        todo.extend(ast.iter_child_nodes(current))


def extract(source: str) -> list[dict]:
    tree = ast.parse(source)
    class_nodes = [n for n in tree.body if isinstance(n, ast.ClassDef)]
    alias_map = _build_own_name_alias_map(tree)
    return [_extract_one(c, alias_map) for c in class_nodes]


def _build_own_name_alias_map(tree: ast.Module) -> dict[str, str]:
    """Module-level `Alias = Name` bindings -- the bounded Python analogue of PHP's
    `use X as Y;` alias table (`PhpFactExtractor::$aliases`), built the same minimal way:
    single name-to-name assignments only, no multi-target, no import aliasing, no
    attribute-chain resolution. Grant G-KOS-PYTHON-RULE-VALIDATION-R3-OWN-CLASS-NAME
    (2026-09-27)."""
    aliases: dict[str, str] = {}
    for node in tree.body:
        if (
            isinstance(node, ast.Assign)
            and len(node.targets) == 1
            and isinstance(node.targets[0], ast.Name)
            and isinstance(node.value, ast.Name)
        ):
            aliases[node.targets[0].id] = node.value.id
    return aliases


def _extract_one(class_node: ast.ClassDef, alias_map: dict[str, str]) -> dict:
    """One unit's own facts -- deliberately NOT inheritance-aware: `class_node.body` is only
    this class's own declared members (Python's AST does not resolve base classes either),
    exactly matching how PhpFactExtractor treats each declared unit independently. No
    resolution across `class_node.bases` is added here -- that is deliberately out of this
    experiment's bound (grant G-KOS-CONTRACT-PYTHON-INHERITANCE-EXPERIMENT)."""
    own_name = class_node.name
    own_aliases = {alias for alias, target in alias_map.items() if target == own_name}
    # OWD-13, 2026-09-28: an `async def` method is an ordinary declared method for
    # cohesion purposes -- nothing downstream (GraphBuilder/EdgeRules/Lcom4) cares
    # whether a method is sync or async. Previously excluded here entirely (not a
    # single fact wrong -- the method never existed in the extracted facts at all),
    # discovered via a real, substantial file (asyncio/base_events.py: 29 of 100 real
    # methods silently missing).
    method_defs = [n for n in class_node.body if isinstance(n, (ast.FunctionDef, ast.AsyncFunctionDef))]
    method_names = {m.name for m in method_defs}
    property_targets = _build_property_targets(class_node, method_defs, method_names)

    methods = []
    for m in method_defs:
        state_accesses = []
        behaviour_references = []
        for node in _walk_own_scope(m):
            if isinstance(node, ast.Call) and _is_super_dispatch(node.func):
                # Explicit parent dispatch. Structurally distinct from self./cls. (the
                # receiver is a Call to the builtin `super`, not a Name) -- recognised
                # syntactically here for the single-inheritance bounded case only; this is
                # NOT semantic MRO resolution (grant G-KOS-CONTRACT-PYTHON-PARENT-DISPATCH-
                # EXPERIMENT). targetUnitRelation is always Undetermined, matching the
                # characterized PHP `parent::` behaviour exactly -- EdgeRules excludes
                # ParentKeyword unconditionally regardless of this field, so membership in
                # method_names is deliberately NOT consulted here (parent dispatch always
                # targets an ancestor, never "this unit", by construction).
                behaviour_references.append({
                    "targetMethodName": node.func.attr,
                    "qualifierKind": "ParentKeyword",
                    "targetUnitRelation": "Undetermined",
                    "referenceMode": "Invocation",
                    "accessMode": "Direct",
                    "determinability": "Determinable",
                })
            elif isinstance(node, ast.Call) and _receiver_qualifier(node.func) is not None:
                # Grant G-KOS-CONTRACT-PYTHON-EVIDENCE-PRECISION-CORRECTION (2026-09-27):
                # the target NAME is always syntactically explicit for this form (self.X()/
                # cls.X()) -- there is nothing indeterminate about WHAT was written, only
                # possibly about whether X belongs to THIS unit's own frame. Frame membership
                # is GraphBuilder's job (node-matching -> TargetNotDeclaredHere), not this
                # adapter's to pre-judge -- matching PhpFactExtractor's own behaviour, not by
                # imitation but because the recovered analytical contract keeps "can I name
                # the target" and "is it in my frame" as separate questions
                # (ExclusionReason's own never-merge principle). Genuine D-1 (a
                # RUNTIME-COMPUTED name, e.g. getattr(self, name)()) never reaches this
                # branch at all -- it is not a self./cls. attribute call syntactically.
                qualifier = _receiver_qualifier(node.func)
                target = node.func.attr
                behaviour_references.append({
                    "targetMethodName": target,
                    "qualifierKind": qualifier,
                    "targetUnitRelation": "DenotesAnalysedUnit",
                    "referenceMode": "Invocation",
                    "accessMode": "Direct",
                    "determinability": "Determinable",
                })
            elif isinstance(node, ast.Call) and _is_getattr_self_dispatch(node.func):
                # D-1 (2026-09-28): `getattr(self, name)()` -- the receiver is known
                # (self, denoting the analysed unit) but the method NAME is a runtime
                # expression, not a literal. No legal name exists to record (matches
                # PHP's `$this->$m()` exactly -- same gap, same fix, confirmed
                # language-general in 2026-09-27-KOS-D1-analytical-definition-
                # decision.md and Case E of the L3 semantic-neutrality matrix). Bounded
                # to the literal `getattr(self, ...)` call form only -- a name bound to
                # a variable first, or `getattr(cls, ...)`, is not recognised here.
                behaviour_references.append({"kind": "Indeterminate"})
            elif isinstance(node, ast.Call) and _own_class_name_qualifier(node.func, own_name, own_aliases) is not None:
                # Grant G-KOS-PYTHON-RULE-VALIDATION-R3-OWN-CLASS-NAME (2026-09-27):
                # the receiver is the analysed class's own bare name, or a module-level
                # alias for it -- e.g. `Fq.b(self)` written inside `Fq` itself, or
                # `Alias.b(self)` where `Alias = Fq`. Mirrors PhpFactExtractor's
                # `classifyQualifier()`/`relationTo()` for exactly these two kinds
                # (UnqualifiedName, AliasedName); EdgeRules already knows how to treat
                # each (the alias case is excluded by kind regardless of this
                # targetUnitRelation, same as PHP -- no Domain change was needed).
                # Deliberately NOT extended: a bare name denoting any OTHER class stays
                # unrecognised -- no general cross-unit name resolution is introduced.
                qualifier = _own_class_name_qualifier(node.func, own_name, own_aliases)
                target = node.func.attr
                behaviour_references.append({
                    "targetMethodName": target,
                    "qualifierKind": qualifier,
                    "targetUnitRelation": "DenotesAnalysedUnit",
                    "referenceMode": "Invocation",
                    "accessMode": "Direct",
                    "determinability": "Determinable",
                })
            elif isinstance(node, ast.Attribute) and not _is_call_target(node, m) and _state_access_qualifier(node, own_name) is not None:
                qualifier = _state_access_qualifier(node, own_name)
                name = node.attr
                if name in property_targets:
                    # Combined OWD-4/OWD-7 correction (2026-09-28): resolve to the
                    # SPECIFIC underlying method a read or write actually invokes,
                    # instead of the collision-name fallback. A read (Load, the
                    # default for anything that is not a Store) targets the getter; a
                    # write (Store) targets the setter. If the needed side does not
                    # exist (e.g. writing a read-only property), no fact is emitted --
                    # that is not valid Python at runtime, so nothing to represent.
                    is_write = isinstance(node.ctx, ast.Store)
                    target = property_targets[name]["set" if is_write else "get"]
                    if target is not None:
                        behaviour_references.append({
                            "targetMethodName": target,
                            "qualifierKind": qualifier,
                            "targetUnitRelation": "DenotesAnalysedUnit",
                            "referenceMode": "Invocation",
                            "accessMode": "Direct",
                            "determinability": "Determinable",
                        })
                elif name in method_names:
                    # Plain (non-property) method named but not called: a reference to a
                    # callable, not a dependency on its behaviour -- ReferenceMode is what
                    # carries this distinction; EdgeRules already excludes it as
                    # CallableNotInvocation, unchanged, for exactly this reason.
                    behaviour_references.append({
                        "targetMethodName": name,
                        "qualifierKind": qualifier,
                        "targetUnitRelation": "DenotesAnalysedUnit",
                        "referenceMode": "CallableReference",
                        "accessMode": "Direct",
                        "determinability": "Determinable",
                    })
                else:
                    state_accesses.append({"propertyName": name, "accessMode": "Direct"})

        methods.append({
            "name": m.name,
            "hasBody": True,
            "stateAccesses": state_accesses,
            "behaviourReferences": behaviour_references,
            "methodRole": "Lifecycle" if m.name in _LIFECYCLE_METHOD_NAMES else "Ordinary",
        })

    return {"unit": {"kind": "ClassUnit", "name": class_node.name}, "methods": methods}


def _decorator_name(node: ast.expr) -> str:
    return node.id if isinstance(node, ast.Name) else ""


def _is_property_or_setter(m: "ast.FunctionDef | ast.AsyncFunctionDef") -> bool:
    """True for a `@property`-decorated def, or its `@<name>.setter`-decorated
    counterpart (an Attribute decorator, e.g. `@value.setter` on a def also named
    `value`) -- both are needed to count how many declared occurrences of a name are
    property-related, for occurrence-based disambiguation (see `_build_property_targets`)."""
    for d in m.decorator_list:
        if _decorator_name(d) == "property":
            return True
        if isinstance(d, ast.Attribute) and d.attr == "setter" and isinstance(d.value, ast.Name) and d.value.id == m.name:
            return True
    return False


def _bare_method_ref(node: "ast.expr | None", method_names: set[str]) -> str | None:
    """Only a bare `Name` referring to an already-declared method in the same class is
    recognised (OWD-7 scope, 2026-09-28): a lambda, an external function, or any other
    expression is deliberately excluded, not silently guessed at."""
    if isinstance(node, ast.Name) and node.id in method_names:
        return node.id
    return None


def _build_property_targets(
    class_node: ast.ClassDef, method_defs: "list[ast.FunctionDef | ast.AsyncFunctionDef]", method_names: set[str]
) -> dict[str, dict[str, "str | None"]]:
    """Unifies BOTH Python property idioms into one `property_name -> {"get": target,
    "set": target}` map, where `target` is the EXACT string `GraphBuilder`'s node
    labels already use downstream -- no `GraphBuilder`/`Lcom4` change is needed
    (combined OWD-4/OWD-7 correction, 2026-09-28).

    Decorator idiom (`@property`/`@x.setter`): getter and setter share the SAME raw
    method name. Python's own syntax GUARANTEES the getter is declared first (writing
    `@x.setter` requires `x` to already be a property, i.e. the `@property`-decorated
    def with that name must textually precede it) -- so occurrence 0 is always the
    getter, occurrence 1 (if present) always the setter, matching EXACTLY the
    occurrence-based disambiguation `GraphBuilder::build()` already computes (OWD-3)
    from the same declaration order, independently, on the PHP side.

    Call-form idiom (`NAME = property(getter, setter)`, OWD-7): getter/setter are
    normally DIFFERENTLY named, already-declared, ordinary methods -- no collision, no
    disambiguation needed; the target is simply that method's own name.

    Deliberately excluded, named rather than silently dropped: lambda-valued
    `fget`/`fset`, module-level monkey-patching (`Cls.attr = property(...)` outside any
    class body), the empty `property()` call (no getter or setter at all), and more
    than two property-related occurrences of the same name (not evidenced anywhere in
    this investigation's corpus)."""
    targets: dict[str, dict[str, str | None]] = {}

    occurrences: dict[str, list[int]] = {}
    for i, m in enumerate(method_defs):
        if _is_property_or_setter(m):
            occurrences.setdefault(m.name, []).append(i)
    for name, indices in occurrences.items():
        if len(indices) == 1:
            targets[name] = {"get": name, "set": None}
        elif len(indices) == 2:
            targets[name] = {"get": f"{name}#0", "set": f"{name}#1"}

    for node in class_node.body:
        if not (
            isinstance(node, ast.Assign)
            and len(node.targets) == 1
            and isinstance(node.targets[0], ast.Name)
            and isinstance(node.value, ast.Call)
            and isinstance(node.value.func, ast.Name)
            and node.value.func.id == "property"
        ):
            continue
        call = node.value
        prop_name = node.targets[0].id
        get_name = _bare_method_ref(call.args[0] if len(call.args) > 0 else None, method_names)
        set_name = _bare_method_ref(call.args[1] if len(call.args) > 1 else None, method_names)
        for kw in call.keywords:
            if kw.arg == "fget":
                get_name = _bare_method_ref(kw.value, method_names) or get_name
            elif kw.arg == "fset":
                set_name = _bare_method_ref(kw.value, method_names) or set_name
        if get_name is not None or set_name is not None:
            targets[prop_name] = {"get": get_name, "set": set_name}

    return targets


def _is_super_dispatch(node: ast.expr) -> bool:
    """True for exactly `super().X` -- zero-argument `super()` (the only form this bounded
    experiment covers; `super(Cls, self)` explicit-argument form is deliberately not
    recognised here). Syntactic recognition only: for single inheritance (this experiment's
    entire bound) the next class in Python's MRO after the current one IS the declared
    parent, so this recognition already carries the correct semantic meaning; it would NOT
    by itself be sufficient for multiple inheritance/diamond MRO, where "next in MRO" and
    "the declared parent" can differ -- explicitly out of bound here."""
    return (
        isinstance(node, ast.Attribute)
        and isinstance(node.value, ast.Call)
        and isinstance(node.value.func, ast.Name)
        and node.value.func.id == "super"
        and node.value.args == []
    )


def _is_getattr_self_dispatch(node: ast.expr) -> bool:
    """True for exactly `getattr(self, <anything>)` used as a call's own callee --
    D-1 (2026-09-28): the receiver is known (self) but the method name is computed, not
    a literal. Deliberately narrow, matching this adapter's existing bounded style: only
    the literal `getattr(self, ...)` two-plus-argument call form is recognised; a name
    bound to a variable first (`f = getattr(self, x); f()`), `getattr(cls, ...)`, or a
    three-argument `getattr(self, x, default)` used as a value (not called) are not."""
    return (
        isinstance(node, ast.Call)
        and isinstance(node.func, ast.Name)
        and node.func.id == "getattr"
        and len(node.args) >= 2
        and isinstance(node.args[0], ast.Name)
        and node.args[0].id == "self"
    )


_RECEIVER_QUALIFIER = {"self": "InstanceReceiver", "cls": "StaticKeyword"}


def _receiver_qualifier(node: ast.expr) -> str | None:
    """Maps the RECEIVER a target was named through (self./cls.) to the closest existing
    QualifierKind -- classification is by how the call was WRITTEN, not by how the callee
    was declared (consistent with QualifierKind's own documented purpose: "HOW the target
    of a behaviour reference was named"). `cls` is Python's own-class-at-call-time receiver
    (naturally supports the same late-static-binding-like semantics PHP's `static::`
    exists for) -- the closest existing case, not a PHP-specific concept smuggled in."""
    if isinstance(node, ast.Attribute) and isinstance(node.value, ast.Name):
        return _RECEIVER_QUALIFIER.get(node.value.id)
    return None


def _own_class_name_qualifier(node: ast.expr, own_name: str, own_aliases: set[str]) -> str | None:
    """Recognises a call whose receiver is the analysed class's own bare name
    (`UnqualifiedName`) or a module-level alias for it (`AliasedName`) -- the Python
    analogue of two of PHP's five name-spelling kinds. Only SELF-reference is
    recognised: a bare name denoting any other class returns None, matching this
    adapter's existing bounded scope (no general cross-unit call resolution)."""
    if not (isinstance(node, ast.Attribute) and isinstance(node.value, ast.Name)):
        return None
    name = node.value.id
    if name == own_name:
        return "UnqualifiedName"
    if name in own_aliases:
        return "AliasedName"
    return None


def _state_access_qualifier(node: ast.expr, own_name: str) -> str | None:
    """Qualifiers recognised for a plain attribute READ or WRITE (state access, or a
    property-getter reference through the same syntax) -- as opposed to a CALL: self/cls
    (existing, unchanged), plus the analysed class's own bare name (R5,
    KOS-PYTHON-RULE-VALIDATION, 2026-09-27), e.g. `A.value` written inside `A` itself.
    Deliberately NOT extended to an alias for the class (`Alias.value`) -- matches PHP's
    own aliased-spelling exclusion policy for this construct, not merely left untested;
    `_own_class_name_qualifier` is not reused here for exactly that reason. `super().x`
    stays unrecognised the same way it always has: its receiver is a Call, not a Name."""
    existing = _receiver_qualifier(node)
    if existing is not None:
        return existing
    if isinstance(node, ast.Attribute) and isinstance(node.value, ast.Name) and node.value.id == own_name:
        return "UnqualifiedName"
    return None


def _is_call_target(attr_node: ast.Attribute, method_node: "ast.FunctionDef | ast.AsyncFunctionDef") -> bool:
    """True if this exact Attribute node is the callee of a Call (self.x() form) --
    those are handled by the ast.Call branch above and must not be double-counted
    as a plain attribute read."""
    for node in ast.walk(method_node):
        if isinstance(node, ast.Call) and node.func is attr_node:
            return True
    return False


if __name__ == "__main__":
    print(json.dumps({"units": extract(sys.stdin.read())}))
