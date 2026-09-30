#!/usr/bin/env python3
"""OWD-1 -- Open-World Python Corpus Discovery census script.

Runs the REAL, UNMODIFIED KnowledgeOS Python adapter (extract_facts.py) against every
top-level .py file in the local CPython 3.13.2 standard library, and separately inventories
AST-level Python constructs for frequency/prioritization purposes. Makes NO production
changes. Reproducible: rerun this script to regenerate owd1_census.json.
"""
import ast
import importlib.util
import json
import os
import sys

STDLIB = "/home/nab-raj.roshyara@dg-nexolution.de/.pyenv/versions/3.13.2/lib/python3.13"
EXTRACT_FACTS_PATH = (
    "/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/scripts/lib/"
    "EngineeringKnowledge/Capabilities/Cohesion/Infrastructure/Python/extract_facts.py"
)
OUT_PATH = os.path.join(os.path.dirname(os.path.abspath(__file__)), "owd1_census.json")

spec = importlib.util.spec_from_file_location("extract_facts", EXTRACT_FACTS_PATH)
extract_facts = importlib.util.module_from_spec(spec)
spec.loader.exec_module(extract_facts)

files = sorted(
    f for f in os.listdir(STDLIB)
    if f.endswith(".py") and os.path.isfile(os.path.join(STDLIB, f))
)

construct_totals = {}


def bump(name, filename, n=1):
    d = construct_totals.setdefault(name, {"files": set(), "occurrences": 0})
    d["files"].add(filename)
    d["occurrences"] += n


def decorator_name(dec):
    node = dec.func if isinstance(dec, ast.Call) else dec
    try:
        return ast.unparse(node)
    except Exception:
        return "<unknown>"


results = []

for fname in files:
    path = os.path.join(STDLIB, fname)
    with open(path, "r", encoding="utf-8", errors="replace") as fh:
        source = fh.read()

    entry = {"file": fname, "parse_ok": None, "extract_ok": None, "error": None,
              "classes": 0, "methods": 0, "nested_scope_methods": 0}

    try:
        tree = ast.parse(source)
        entry["parse_ok"] = True
    except SyntaxError as e:
        entry["parse_ok"] = False
        entry["error"] = f"SyntaxError: {e}"
        results.append(entry)
        continue

    for node in ast.walk(tree):
        if isinstance(node, ast.ClassDef):
            bump("ClassDef", fname)
            if len(node.bases) > 1:
                bump("MultipleInheritance", fname)
            if node.keywords:
                bump("ClassKeywordArgs(metaclass_etc)", fname)
            for dec in node.decorator_list:
                bump(f"ClassDecorator:{decorator_name(dec)}", fname)
        elif isinstance(node, ast.AsyncFunctionDef):
            bump("AsyncFunctionDef", fname)
            for dec in node.decorator_list:
                bump(f"Decorator:{decorator_name(dec)}", fname)
        elif isinstance(node, ast.FunctionDef):
            bump("FunctionDef", fname)
            for dec in node.decorator_list:
                bump(f"Decorator:{decorator_name(dec)}", fname)
        elif isinstance(node, ast.Lambda):
            bump("Lambda", fname)
        elif isinstance(node, (ast.ListComp, ast.SetComp, ast.DictComp, ast.GeneratorExp)):
            bump(type(node).__name__, fname)
        elif isinstance(node, ast.Yield):
            bump("Yield", fname)
        elif isinstance(node, ast.YieldFrom):
            bump("YieldFrom", fname)
        elif isinstance(node, ast.Await):
            bump("Await", fname)
        elif hasattr(ast, "Match") and isinstance(node, ast.Match):
            bump("MatchStatement", fname)
        elif isinstance(node, ast.NamedExpr):
            bump("WalrusOperator", fname)
        elif isinstance(node, ast.Try):
            bump("TryExcept", fname)
        elif isinstance(node, (ast.With, ast.AsyncWith)):
            bump("With", fname)
        elif isinstance(node, ast.AnnAssign):
            bump("AnnotatedAssign", fname)
        elif isinstance(node, ast.AugAssign):
            bump("AugmentedAssign", fname)
        elif isinstance(node, ast.Assign) and len(node.targets) > 1:
            bump("MultiTargetAssign", fname)
        elif isinstance(node, ast.Assign) and any(isinstance(t, (ast.Tuple, ast.List)) for t in node.targets):
            bump("DestructuringAssign", fname)
        elif isinstance(node, ast.Global):
            bump("Global", fname)
        elif isinstance(node, ast.Nonlocal):
            bump("Nonlocal", fname)
        elif isinstance(node, (ast.Import, ast.ImportFrom)):
            bump("Import", fname)
        elif isinstance(node, ast.Call) and isinstance(node.func, ast.Name) and node.func.id in (
            "getattr", "setattr", "__import__", "super", "hasattr", "delattr"
        ):
            bump(f"Call:{node.func.id}", fname)

    if "__slots__" in source:
        bump("SlotsUsage(textual)", fname)

    for classnode in [n for n in tree.body if isinstance(n, ast.ClassDef)]:
        entry["classes"] += 1
        for member in classnode.body:
            if isinstance(member, ast.ClassDef):
                bump("NestedClassInClassBody", fname)
            if isinstance(member, (ast.FunctionDef, ast.AsyncFunctionDef)):
                entry["methods"] += 1
                for inner in ast.walk(member):
                    if inner is member:
                        continue
                    if isinstance(inner, (ast.FunctionDef, ast.AsyncFunctionDef, ast.Lambda)):
                        bump("NestedFunctionOrLambdaInMethod", fname)
                        entry["nested_scope_methods"] += 1
                        break

    try:
        units = extract_facts.extract(source)
        entry["extract_ok"] = True
        entry["adapter_units"] = len(units)
        entry["adapter_methods"] = sum(len(u["methods"]) for u in units)
    except Exception as e:
        entry["extract_ok"] = False
        entry["error"] = f"{type(e).__name__}: {e}"

    results.append(entry)

summary = {
    "python_version": sys.version,
    "stdlib_root": STDLIB,
    "file_count": len(files),
    "files": files,
    "parse_failures": [r["file"] for r in results if r["parse_ok"] is False],
    "extract_failures": [
        {"file": r["file"], "error": r["error"]} for r in results if r.get("extract_ok") is False
    ],
    "totals": {
        k: {"file_count": len(v["files"]), "occurrences": v["occurrences"]}
        for k, v in construct_totals.items()
    },
    "per_file": results,
}

with open(OUT_PATH, "w") as out:
    json.dump(summary, out, indent=2, default=str)

print(f"wrote {OUT_PATH}")
print(json.dumps(
    {k: v for k, v in summary.items() if k not in ("per_file", "files", "totals")},
    indent=2, default=str,
))
