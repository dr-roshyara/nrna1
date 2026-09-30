"""Print the key structure of a JSON file with value TYPES only (never values). Lists: element-type summary + length class.
Sibling dicts with identical key sets are collapsed to the first."""
import json, sys
def t(v): return {dict: "dict", list: "list", str: "str", bool: "bool", int: "int", float: "float", type(None): "null"}[type(v)]
def walk(v, ind, depth, maxd):
    if isinstance(v, dict):
        seen = {}
        for k, x in v.items():
            sig = (t(x), tuple(sorted(x)) if isinstance(x, dict) else None)
            if sig in seen and isinstance(x, dict): seen[sig].append(k); continue
            seen[sig] = [k]
            print(" " * ind + f"{k}: {t(x)}" + ("" if not isinstance(x, list) else f"[{'+'.join(sorted({t(y) for y in x})) or 'empty'}]"))
            if depth < maxd and isinstance(x, dict): walk(x, ind + 2, depth + 1, maxd)
            if depth < maxd and isinstance(x, list) and x and isinstance(x[0], dict): walk(x[0], ind + 4, depth + 1, maxd)
        for sig, ks in seen.items():
            if len(ks) > 1: print(" " * ind + f"(same shape as {ks[0]}: {', '.join(ks[1:])})")
walk(json.load(open(sys.argv[1])), 0, 0, int(sys.argv[2]) if len(sys.argv) > 2 else 8)
