#!/usr/bin/env python3
"""LCOM4 observation collector — independent Python implementation.

WHY THIS EXISTS
    Stage 2 of KOS-CONTRACT-NEUTRALITY-001. It answers one question:
    can the corrected language-neutral LCOM4 contract be implemented
    independently and produce the same observations as the PHP reference?

    It is an EVIDENCE EXPERIMENT. It adopts no language, opens no gate, and
    decides nothing about whether PHP is retained or retired.

WHAT IT IS IMPLEMENTED FROM
    scripts/observations/examples/lcom4/expected.json — the pinned decisions
    are the specification. This is deliberately NOT a port of
    Lcom4Collector.php: porting would test transliteration rather than whether
    the written contract is sufficient, which is the entire question.

    The parsing strategy is intentionally different from the reference's. The
    reference uses a full PHP AST parser (nikic/php-parser). No such parser is
    available here and installing one is forbidden by the grant, so this reads
    the source with its own scanner. If two such different strategies agree,
    the agreement is about the CONTRACT, not about a shared library.

CONTRACT AS IMPLEMENTED (each line traceable to a pinned decision)
    LCOM4            = number of connected components over the class's methods
    nodes            = methods declared in the class body
                       - __construct/__destruct excluded          [constructors]
                       - statics included                       [static_methods]
                       - trait methods not resolved              [trait_methods]
                       - inherited not included             [inherited_methods]
                       - no other magic-method handling          [magic_methods]
    edge, state      = two methods touch the same $this->property
    edge, behaviour  = one method uses another as a behaviour of the same
                       class:  $this->m()  self::m()  static::m()  Own::m()
                                                            [intra_class_calls]
    NOT an edge      = parent::m()            (out of frame — nothing to link)
                     = $this->$n(), call_user_func([...]), $var::m()
                                              (not determinable — cannot see it)
                     = m(...)                          [first_class_callables]

    The two exclusions above are different claims and are kept apart in the
    code as they are in the contract: OUT_OF_FRAME vs NOT_DETERMINABLE.
"""

from __future__ import annotations

import json
import re
import sys
from pathlib import Path

# --------------------------------------------------------------------------
# 1 · Scanning: blank out comments and string literals so that later pattern
#     matching cannot be fooled by text inside them. Lengths are preserved so
#     every offset stays valid.
# --------------------------------------------------------------------------


def blank_noise(src: str) -> str:
    out = list(src)
    i, n = 0, len(src)
    while i < n:
        c = src[i]
        if c == "/" and i + 1 < n and src[i + 1] == "/":
            j = src.find("\n", i)
            j = n if j == -1 else j
            for k in range(i, j):
                out[k] = " "
            i = j
        elif c == "#":
            j = src.find("\n", i)
            j = n if j == -1 else j
            for k in range(i, j):
                out[k] = " "
            i = j
        elif c == "/" and i + 1 < n and src[i + 1] == "*":
            j = src.find("*/", i + 2)
            j = n if j == -1 else j + 2
            for k in range(i, j):
                if src[k] != "\n":
                    out[k] = " "
            i = j
        elif c in "'\"":
            quote, j = c, i + 1
            while j < n:
                if src[j] == "\\":
                    j += 2
                    continue
                if src[j] == quote:
                    j += 1
                    break
                j += 1
            for k in range(i + 1, min(j - 1, n) + 1):
                if k < n and src[k] != "\n":
                    out[k] = " "
            i = j
        else:
            i += 1
    return "".join(out)


def match_block(src: str, open_pos: int) -> int:
    """Index just past the '}' closing the '{' at open_pos."""
    depth, i, n = 0, open_pos, len(src)
    while i < n:
        if src[i] == "{":
            depth += 1
        elif src[i] == "}":
            depth -= 1
            if depth == 0:
                return i + 1
        i += 1
    return n


# --------------------------------------------------------------------------
# 2 · Structure: classes, then the methods declared in each class body.
#     Only `class` — a trait/interface/enum is not a class    [trait_methods]
# --------------------------------------------------------------------------

CLASS_RE = re.compile(r"\bclass\s+([A-Za-z_]\w*)", re.I)
METHOD_RE = re.compile(r"\bfunction\s+([A-Za-z_]\w*)\s*\(", re.I)
EXCLUDED_METHODS = {"__construct", "__destruct"}  # [constructors]


def find_classes(clean: str):
    for m in CLASS_RE.finditer(clean):
        brace = clean.find("{", m.end())
        if brace == -1:
            continue
        yield m.group(1), brace, match_block(clean, brace)


def find_methods(clean: str, body_start: int, body_end: int):
    """Methods declared directly in the class body (depth 1 — not in closures)."""
    for m in METHOD_RE.finditer(clean, body_start, body_end):
        # depth relative to the class body: 1 means "directly in the class"
        depth = clean.count("{", body_start, m.start()) - clean.count(
            "}", body_start, m.start()
        )
        if depth != 1:
            continue
        brace = clean.find("{", m.end())
        semi = clean.find(";", m.end())
        if brace == -1 or (semi != -1 and semi < brace):
            yield m.group(1), None, None  # abstract/interface: declared, no body
            continue
        yield m.group(1), brace, match_block(clean, brace)


# --------------------------------------------------------------------------
# 3 · Relationships inside one method body.
# --------------------------------------------------------------------------

PROP_OR_CALL_RE = re.compile(r"\$this\s*->\s*([A-Za-z_]\w*)")
STATIC_CALL_RE = re.compile(r"(\$?[A-Za-z_\\]\w*)\s*::\s*([A-Za-z_]\w*)\s*\(")
FIRST_CLASS_CALLABLE_RE = re.compile(r"\A\s*\.\.\.\s*\)")  # m(...)  [first_class_callables]

OUT_OF_FRAME = {"parent"}          # nothing to connect to
SELF_REFERENTIAL = {"self", "static"}


def analyse_method(body: str, own_class: str):
    """-> (properties touched, methods used as behaviours of this class)"""
    props: set[str] = set()
    calls: set[str] = set()

    for m in PROP_OR_CALL_RE.finditer(body):
        rest = body[m.end():]
        stripped = rest.lstrip()
        if not stripped.startswith("("):
            props.add(m.group(1))          # $this->state — shared state edge
            continue
        after_paren = stripped[1:]
        if FIRST_CLASS_CALLABLE_RE.match(after_paren):
            continue                        # $this->m(...) — reference, excluded
        calls.add(m.group(1))               # $this->m()  — behavioural edge

    for m in STATIC_CALL_RE.finditer(body):
        qualifier, method = m.group(1), m.group(2)
        if qualifier.startswith("$"):
            continue                        # $var::m() — NOT DETERMINABLE
        q = qualifier.lower()
        if q in OUT_OF_FRAME:
            continue                        # parent::m() — OUT OF FRAME
        own_as_written = q == own_class.lower()
        if not (q in SELF_REFERENTIAL or own_as_written):
            continue                        # another class entirely
        if FIRST_CLASS_CALLABLE_RE.match(body[m.end():]):
            continue                        # self::m(...) — reference, excluded
        calls.add(method)

    return props, calls


# --------------------------------------------------------------------------
# 4 · Connected components (union-find over method names).
# --------------------------------------------------------------------------


def connected_components(methods: dict[str, tuple[set, set]]) -> int:
    names = list(methods)
    if not names:
        return 0
    parent = {n: n for n in names}

    def find(x):
        while parent[x] != x:
            parent[x] = parent[parent[x]]
            x = parent[x]
        return x

    def union(a, b):
        ra, rb = find(a), find(b)
        if ra != rb:
            parent[ra] = rb

    owner: dict[str, str] = {}
    for name, (props, _) in methods.items():
        for p in props:
            if p in owner:
                union(name, owner[p])
            else:
                owner[p] = name

    for name, (_, calls) in methods.items():
        for callee in calls:
            if callee in methods:          # only methods declared in this class
                union(name, callee)

    return len({find(n) for n in names})


# --------------------------------------------------------------------------
# 5 · Observation
# --------------------------------------------------------------------------


def interpretation(value: int) -> str:
    if value == 0:
        return "no analyzable methods"
    if value == 1:
        return "single connected component (cohesive)"
    return f"{value} connected components (disjoint responsibility clusters)"


def collect(php_source: str) -> list[dict]:
    clean = blank_noise(php_source)
    observations = []
    for class_name, body_start, body_end in find_classes(clean):
        methods: dict[str, tuple[set, set]] = {}
        for name, m_start, m_end in find_methods(clean, body_start, body_end):
            if name in EXCLUDED_METHODS:
                continue
            body = "" if m_start is None else clean[m_start:m_end]
            methods[name] = analyse_method(body, class_name)
        value = connected_components(methods)
        observations.append(
            {
                "metric": "LCOM4",
                "class": class_name,
                "value": value,
                "interpretation": interpretation(value),
            }
        )
    return observations


def main() -> int:
    if len(sys.argv) > 1:
        for path in sys.argv[1:]:
            print(json.dumps(collect(Path(path).read_text()), indent=2))
        return 0
    print(json.dumps(collect(sys.stdin.read()), indent=2))
    return 0


if __name__ == "__main__":
    raise SystemExit(main())
