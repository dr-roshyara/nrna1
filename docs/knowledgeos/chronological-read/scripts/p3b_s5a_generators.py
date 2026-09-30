#!/usr/bin/env python3
"""P3b S5a candidate generators (frozen protocol v1.7 Appendix A.6; S5 plan v2.3.2 §G, §I; G-LOG-0041).

INFRASTRUCTURE ONLY. Building and testing this script is authorized (G-LOG-0041); executing S5a research is not.

What it does
  * Seven generators: G-SHARED-GROUP, G-NOTATION, G-TYPE-SIM (pre-S5 inputs), G-DEPENDENCY, G-COCHANGE (AI-input: they
    read a pass snapshot of object records), and G-TOPIC / G-HYP (SELF-DERIVED: test-only, never budgeted, never counted).
  * Every generator produces candidate sets of arity k in {2, 3} (H-17). Members outside the S5 population (Tier X,
    hold-out, anything unknown) are dropped before any set is formed, so no set carries a Tier-X member (§G.3).
  * The full list is written first, sorted by member tuple (A.6). Budget rule (§G.3): full lists for G-SHARED-GROUP and
    G-NOTATION; <= 300 for G-TYPE-SIM, G-DEPENDENCY and G-COCHANGE, otherwise
    random.Random(int_seed(20261001, 5, i)).sample(full_list, 300), i by generator order.
  * The label-disjoint test family (§9E.2 item 6) is marked `in_test_family` AFTER the control draw (it needs the
    NO-CONTROL-AVAILABLE status): budget sub-sample -> drop NO-CONTROL-AVAILABLE -> greedy label-disjoint by sorted
    member tuple.
  * Output: P3B-CROSS-CANDIDATES.jsonl-format file ({header} line, then CANDIDATE records, then CONTROL records). The
    path is a parameter; in this build it is written to /tmp only.

Data access: only through scripts/p3b_s5_common.py (discovery-only loaders, allowlist, seal assert). Never reads a
sealed artifact, the census bundle index, corpus files, or the hold-out lists (beyond the filtering done inside the
common module).

  python3 p3b_s5a_generators.py --out /tmp/s5a-build/P3B-CROSS-CANDIDATES.jsonl \
      [--generators G-SHARED-GROUP,G-NOTATION,G-TYPE-SIM] [--snapshot-objects F.jsonl ...] [--snapshot-register F.jsonl ...]
"""
import argparse
import itertools
import json
import os
import random
import re
import sys
import unicodedata

_HERE = os.path.dirname(os.path.abspath(__file__))
if _HERE not in sys.path:
    sys.path.insert(0, _HERE)
import p3b_s5_common as C  # noqa: E402

# ------------------------------------------------------------------ frozen parameters (plan v2.3.2; do not change)
GENERATORS = ("G-SHARED-GROUP", "G-DEPENDENCY", "G-NOTATION", "G-COCHANGE", "G-TYPE-SIM")   # plan §G.3: §9E.1 table order (G-TIMELINE-SIM omitted), i = 0..4
SELF_DERIVED = ("G-TOPIC", "G-HYP")
ALL_GENERATORS = GENERATORS + SELF_DERIVED
GEN_INDEX = {g: i for i, g in enumerate(GENERATORS)}
ARITIES = (2, 3)                                   # H-17
BUDGET = 300                                       # §G.3
BUDGET_ROOT = 20261001                             # §G.3 seed root (generator budget sub-samples)
FULL_LIST_GENERATORS = ("G-SHARED-GROUP", "G-NOTATION")
BUDGETED_GENERATORS = ("G-TYPE-SIM", "G-DEPENDENCY", "G-COCHANGE")
DEGREE_CAP = 10                                    # H-18
H18_FILES_ABOVE_CAP = (                            # plan §I: the 50 listed files (frozen into the pass plan)
    "S0007", "S0008", "S0041", "S0091", "S0164", "S0206", "S0234", "S0237", "S0239", "S0240",
    "S0241", "S0446", "S0806", "S0807", "S1390", "S1395", "S1541", "S1631", "S1720", "S2055",
    "S2058", "S2263", "S2265", "S2305", "S2520", "S2521", "S2522", "S2523", "S2526", "S2528",
    "S2531", "S2577", "S2781", "S2782", "S2783", "S2784", "S2785", "S2786", "S2787", "S2797",
    "S2807", "S2808", "S2809", "S2810", "S2813", "S2816", "S2817", "S2818", "S2819", "S2820")
P2A_KINDS = ("EXACT-STRING-REUSE", "POSSIBLY-RELATION", "UNKNOWN-CANDIDATE-GROUP", "SHARED-NOTATION", "SHARED-ALIAS",
             "CO-OCCURRENCE", "STRING-SIMILARITY")          # §G.3 (MINOR-9): all seven admitted
FILTERED_KINDS = ("CO-OCCURRENCE", "STRING-SIMILARITY")       # §9E.1 correction / §I
HYP_KINDS = ("HYPOTHESIS", "STRUCTURE-CANDIDATE")             # A.6 G-HYP ("HYPOTHESIS/STRUCTURE records")

VERSIONS = {g: "s5a-1.0" for g in ALL_GENERATORS}
DEFINING = {
    "G-SHARED-GROUP": "shared P2a group membership / similarity (admitted kinds; CO-OCCURRENCE/STRING-SIMILARITY filtered per H-18)",
    "G-NOTATION": "shared normalized notation or alias (A.4 normalization)",
    "G-TYPE-SIM": "equality of the normalized type-signature shape (A.6 table " + "S5A-TYPE-SIM-TABLE-1" + ")",
    "G-DEPENDENCY": "dependency-edge existence / connectivity (connected k-subset, undirected)",
    "G-COCHANGE": "co-occurrence of change (change_vs_previous != RESTATES) at one PRIMARY source with degree <= 10",
    "G-TOPIC": "shared register topic (SELF-DERIVED)",
    "G-HYP": "joint listing in related_labels of one HYPOTHESIS/STRUCTURE-CANDIDATE record (SELF-DERIVED)",
}
AI_INPUT = {"G-SHARED-GROUP": "no", "G-NOTATION": "no", "G-TYPE-SIM": "no", "G-DEPENDENCY": "yes",
            "G-COCHANGE": "yes", "G-TOPIC": "yes", "G-HYP": "yes"}
POPULATION_RULE = {   # §9E.1 "Population" column, read literally (the control draw's eligible population)
    "G-SHARED-GROUP": "S5-population labels that are members of >= 1 admitted P2a group",
    "G-NOTATION": "S5-population labels sharing >= 1 normalized notation/alias with another S5-population label",
    "G-TYPE-SIM": "S5-population labels sharing >= 1 normalized signature shape with another S5-population label",
    "G-DEPENDENCY": "S5-population labels incident to >= 1 dependency edge between two S5-population labels",
    "G-COCHANGE": "S5-population labels sharing >= 1 eligible change source with another S5-population label",
}

# ------------------------------------------------------------------ G-TYPE-SIM signature-shape table (A.6; versioned)
TYPE_TABLE_VERSION = "S5A-TYPE-SIM-TABLE-1"
TYPE_CLASSES = ("SET", "FUNCTION", "RELATION", "SCALAR", "STATE", "OTHER")
TYPE_TABLE = (   # first matching class wins; patterns are regexes over the NFKC + LaTeX-normalized, casefolded token
    ("FUNCTION", (r"→", r"->", r"↦", r"\|->", r"⟶", r"\bfunctions?\b", r"\bmap(ping)?s?\b", r"\boperators?\b")),
    ("RELATION", (r"×", r"\brelations?\b", r"\bpairs?\b", r"\btuples?\b", r"⊑", r"⪯", r"≺")),
    ("SCALAR", (r"ℝ", r"ℕ", r"ℤ", r"\[0, ?1\]", r"\{0, ?1\}", r"\{true, ?false\}", r"\breals?\b", r"\bscalars?\b",
                r"\bnumbers?\b", r"\bintegers?\b", r"\bint\b", r"\bfloat\b", r"\bprobabilit", r"\bscores?\b",
                r"\bentropy\b", r"\bbool(ean)?\b", r"\bcounts?\b", r"\bdegree\b")),
    ("SET", (r"⊆", r"2\^", r"℘", r"\bpower ?sets?\b", r"\bsubsets?\b", r"\bsets?\b", r"(?<![_^])\{", r"∅", r"\bfamil(y|ies)\b",
             r"\bcollections?\b", r"\bpartitions?\b")),
    ("STATE", (r"\bstates?\b", r"_\{?t\b", r"_\{?t\+1", r"_\{?i\+1", r"\bstatus\b", r"\bphases?\b", r"\bstages?\b",
               r"\bmodes?\b", r"\bconfigurations?\b")),
)
_TYPE_RX = tuple((cls, tuple(re.compile(p) for p in pats)) for cls, pats in TYPE_TABLE)
_OPEN, _CLOSE = "([{⟨", ")]}⟩"
_ARROWS = ("→", "->", "⟶")


def type_table_sha256():
    return C.sha256_bytes(C.canon({"version": TYPE_TABLE_VERSION, "table": TYPE_TABLE}).encode("utf-8"))


def _norm_token(s):
    return C.s3.norm_base(str(s)).strip().casefold()


def token_class(token):
    t = _norm_token(token)
    for cls, rxs in _TYPE_RX:
        if any(rx.search(t) for rx in rxs):
            return cls
    return "OTHER"


def _strip_outer(s):
    s = s.strip()
    while len(s) >= 2 and s[0] in _OPEN and _CLOSE[_OPEN.index(s[0])] == s[-1]:
        depth, ok = 0, True
        for i, ch in enumerate(s):
            if ch in _OPEN:
                depth += 1
            elif ch in _CLOSE:
                depth -= 1
                if depth == 0 and i != len(s) - 1:
                    ok = False
                    break
        if not ok:
            break
        s = s[1:-1].strip()
    return s


def split_domain(domain):
    """Top-level split of a domain string on ',', ';', '×', ' x ', ' * ' (brackets respected)."""
    s = _strip_outer(C.s3.norm_base(str(domain or "")))
    if not s:
        return []
    out, cur, depth, i = [], [], 0, 0
    while i < len(s):
        ch = s[i]
        if ch in _OPEN:
            depth += 1
        elif ch in _CLOSE:
            depth = max(0, depth - 1)
        if depth == 0:
            if ch in ",;×":
                out.append("".join(cur)); cur = []; i += 1
                continue
            m = re.match(r"\s[xX*]\s", s[i:])
            if m:
                out.append("".join(cur)); cur = []; i += len(m.group(0))
                continue
        cur.append(ch)
        i += 1
    out.append("".join(cur))
    return [t.strip() for t in out if t.strip()]


def _split_arrow(s):
    depth = 0
    for i, ch in enumerate(s):
        if ch in _OPEN:
            depth += 1
        elif ch in _CLOSE:
            depth = max(0, depth - 1)
        elif depth == 0:
            for a in _ARROWS:
                if s.startswith(a, i):
                    return s[:i], s[i + len(a):]
    return None


def signature_shape(ts):
    """Normalized signature shape (A.6): arity, the class of each domain token (in order), the codomain class."""
    if isinstance(ts, dict):
        dom = split_domain(ts.get("domain"))
        try:
            arity = int(ts.get("arity"))
        except (TypeError, ValueError):
            arity = len(dom)
        cod = ts.get("codomain")
        cod_cls = token_class(cod) if cod not in (None, "") else "OTHER"
    elif isinstance(ts, str) and ts.strip():
        s = C.s3.norm_base(ts).strip()
        sp = _split_arrow(s)
        if sp is None:
            dom, cod_cls = [], token_class(s)
        else:
            d, cod = sp
            if ":" in d:
                d = d.rsplit(":", 1)[1]
            dom, cod_cls = split_domain(d), token_class(cod)
        arity = len(dom)
    else:
        return None
    return f"{arity}|{','.join(token_class(t) for t in dom)}|{cod_cls}"


def is_formal(ts):
    """A.5: a formal row has a non-empty type_signature (a dict with >= 1 non-empty value, or a non-empty string)."""
    if isinstance(ts, dict):
        return any(v not in (None, "", [], {}) for v in ts.values())
    return isinstance(ts, str) and bool(ts.strip())


# ------------------------------------------------------------------ G-NOTATION key normalization (A.4)
def notation_keys(x):
    """A.4 normalization of one notation/alias: NFKC + LaTeX table; casefold except single characters; plus the ASCII
    transliteration variant (A.4 fixed table)."""
    b = C.s3.norm_base(str(x)).strip()
    if not b:
        return set()
    out = set()
    for v in {b, C.s3.ascii_variant(b).strip()}:
        if v:
            out.add(v if len(v) == 1 else v.casefold())
    return out


# ------------------------------------------------------------------ data context
class Ctx:
    """Everything the generators and the control draw need, loaded through the common module only.
    Tests build a Ctx directly with synthetic data."""

    def __init__(self, pop, bands, groups=(), nodes=None, rows_by_label=None, files_meta=None, degree=None,
                 hubs=frozenset(), judged=frozenset(), inputs=()):
        self.pop = frozenset(pop)
        self.bands = bands
        self.groups = list(groups)
        self.nodes = nodes or {}
        self.rows_by_label = rows_by_label or {}
        self.files_meta = files_meta or {}
        self.degree = degree or {}
        self.hubs = frozenset(hubs)
        self.judged = frozenset(judged)
        self.inputs = tuple(inputs)

    @classmethod
    def load(cls):
        pop = C.s5_population()
        groups, nodes = C.derived_groups()
        bi, fm, deg = C.bundle_index(), C.files_meta(), C.file_degree()
        C.check_inputs(["03-CONTRIBUTIONS.jsonl"])
        with open(os.path.join(C.CR, "03-CONTRIBUTIONS.jsonl"), encoding="utf-8") as f:
            lines = f.read().split("\n")
        rows = {}
        for lab in sorted(pop):
            out = []
            for x in bi.get(lab, {}).get("rows", []):
                if x["source_id"] not in fm:                              # §M item 5: discovery files only
                    continue
                r = json.loads(lines[x["line"]])
                if r.get("source_id") != x["source_id"] or lab not in (r.get("labels") or []):
                    raise C.S5Error(f"bundle row pointer mismatch for {lab} line {x['line']}")
                out.append({"line": x["line"], "source_id": r["source_id"], "anchor": r.get("anchor"),
                            "statement": r.get("statement"), "type_signature": r.get("type_signature")})
            rows[lab] = out
        judged = frozenset(frozenset((p["a"], p["b"])) for p in C.discovery_pairs())
        ctx = cls(pop, C.label_bands(pop), groups, nodes, rows, fm, deg, frozenset(C.hubs()), judged,
                  inputs=("P3B-SAMPLE-PLAN.jsonl", "20-FAMILIES/_derived.json", "_batch_input_r2/bundle_index_discovery.jsonl",
                          "02-FILES.jsonl", "03-CONTRIBUTIONS.jsonl", "31-RECONCILIATION-PAIRS.jsonl",
                          "P3B-DISCOVERY-SEARCH.jsonl", C.HUBS, "P3B-HOLDOUT-SEAL.json"))
        ctx.assert_discovery_only()
        above = sorted(s for s, d in deg.items() if d > DEGREE_CAP)
        if tuple(above) != H18_FILES_ABOVE_CAP:
            raise C.S5Error("H-18: files above the degree cap differ from the 50 frozen files")
        return ctx

    def assert_discovery_only(self):
        """§M item 5: nothing outside the S5 population / discovery files passes the filters."""
        for g in self.groups:
            assert all(m in self.pop for m in g["members"]), "non-S5 member in a P2a group"
        assert all(lab in self.pop for lab in self.nodes), "non-S5 node"
        for lab, rows in self.rows_by_label.items():
            assert lab in self.pop
            assert all(r["source_id"] in self.files_meta for r in rows), "non-discovery row source"

    def primary_ok_row(self, row):
        s = row["source_id"]
        return (self.files_meta.get(s, {}).get("provenance") == "PRIMARY" and s in self.degree
                and self.degree[s] <= DEGREE_CAP)


# ------------------------------------------------------------------ generator result
class GenResult:
    """Candidates of one generator plus its defining-link predicate and eligible population (for the control draw)."""

    def __init__(self, generator, candidates, population, link_index, parameters, blinding_tokens=None,
                 excluded_inputs=None):
        self.generator = generator
        self.candidates = candidates             # list of {members, k, link_tokens, source_exclusions}
        self.population = tuple(sorted(population))
        self.link_index = link_index             # {label: set of link keys}; a pair is linked iff key sets intersect
        self.parameters = parameters
        self.excluded_inputs = excluded_inputs or {}

    def linked(self, a, b):
        la, lb = self.link_index.get(a), self.link_index.get(b)
        return bool(la and lb and not la.isdisjoint(lb))

    def any_linked(self, members):
        return any(self.linked(a, b) for a, b in itertools.combinations(members, 2))


def _ksubsets_from_buckets(buckets, pop):
    """buckets: {key: iterable of labels} -> {member tuple: set of keys}; k-subsets of each bucket, k in ARITIES."""
    out = {}
    for key in sorted(buckets):
        labs = sorted(set(l for l in buckets[key] if l in pop))
        for k in ARITIES:
            for s in itertools.combinations(labs, k):
                out.setdefault(s, set()).add(key)
    return out


def _cands(sets, extra=None):
    extra = extra or {}
    return [{"members": list(m), "k": len(m), "link_tokens": sorted(keys),
             "source_exclusions": extra.get(m, [])} for m, keys in sorted(sets.items())]


# ------------------------------------------------------------------ the generators
def g_shared_group(ctx):
    admitted, dropped = [], []
    for g in ctx.groups:
        mem = [m for m in g["members"] if m in ctx.pop]
        if g["kind"] not in P2A_KINDS:
            dropped.append({"group_id": g["group_id"], "reason": "KIND-NOT-ADMITTED"})
            continue
        if g["kind"] in FILTERED_KINDS:
            bad = [m for m in mem if not any(ctx.primary_ok_row(r) for r in ctx.rows_by_label.get(m, []))]
            if bad:
                dropped.append({"group_id": g["group_id"], "kind": g["kind"],
                                "reason": "H-18-FILTER: member without a PRIMARY row from a file of degree <= 10",
                                "failing_members": bad})
                continue
        admitted.append((g["group_id"], mem))
    buckets = {gid: mem for gid, mem in admitted}
    sets = _ksubsets_from_buckets(buckets, ctx.pop)
    link = {}
    for gid, mem in admitted:
        for m in mem:
            link.setdefault(m, set()).add(gid)
    pop = {m for _, mem in admitted for m in mem}
    return GenResult("G-SHARED-GROUP", _cands(sets), pop, link,
                     {"k": list(ARITIES), "group_kinds_admitted": list(P2A_KINDS), "filtered_kinds": list(FILTERED_KINDS),
                      "degree_cap": DEGREE_CAP, "groups_admitted": len(admitted), "groups_dropped_by_filter": len(dropped)},
                     excluded_inputs={"groups_dropped": dropped})


def g_notation(ctx):
    buckets = {}
    for lab in sorted(ctx.nodes):
        if lab not in ctx.pop:
            continue
        n = ctx.nodes[lab]
        for x in list(n.get("notations") or []) + list(n.get("aliases") or []):
            for key in notation_keys(x):
                buckets.setdefault(key, set()).add(lab)
    buckets = {k: v for k, v in buckets.items() if len(v) >= 2}
    sets = _ksubsets_from_buckets(buckets, ctx.pop)
    link = {}
    for key, labs in buckets.items():
        for m in labs:
            link.setdefault(m, set()).add(key)
    return GenResult("G-NOTATION", _cands(sets), set(link), link,
                     {"k": list(ARITIES), "normalization": "A.4 (s3.norm_base: NFKC + LaTeX table; casefold except single "
                      "characters; ASCII transliteration variant)", "latex_table_sha256":
                      C.sha256_bytes(C.canon(C.s3.LATEX_TABLE).encode()), "ascii_table_sha256":
                      C.sha256_bytes(C.canon(C.s3.ASCII_TABLE).encode())})


def label_shapes(ctx):
    shapes = {}
    for lab in sorted(ctx.rows_by_label):
        if lab not in ctx.pop:
            continue
        for r in ctx.rows_by_label[lab]:
            ts = r.get("type_signature")
            if is_formal(ts):
                sh = signature_shape(ts)
                if sh:
                    shapes.setdefault(lab, set()).add(sh)
    return shapes


def g_type_sim(ctx):
    shapes = label_shapes(ctx)
    buckets = {}
    for lab, shs in shapes.items():
        for sh in shs:
            buckets.setdefault(sh, set()).add(lab)
    buckets = {k: v for k, v in buckets.items() if len(v) >= 2}
    sets = _ksubsets_from_buckets(buckets, ctx.pop)
    link = {}
    for sh, labs in buckets.items():
        for m in labs:
            link.setdefault(m, set()).add(sh)
    return GenResult("G-TYPE-SIM", _cands(sets), set(link), link,
                     {"k": list(ARITIES), "table_version": TYPE_TABLE_VERSION, "table_sha256": type_table_sha256(),
                      "input": "03-CONTRIBUTIONS rows of S5-population labels via bundle_index_discovery (discovery files)",
                      "labels_with_shape": len(shapes), "shapes_shared": len(buckets)})


def _edges_from_objects(objects, pop):
    edges, excluded = set(), []
    for o in objects:
        a = o.get("working_label")
        for e in o.get("dependency_edges") or []:
            b = e.get("target_label")
            if a in pop and b in pop and a != b:
                edges.add(tuple(sorted((a, b))))
            else:
                excluded.append({"from": a if a in pop else "NON-S5", "to": b if b in pop else "NON-S5",
                                 "reason": "endpoint outside the S5 population or self-edge"})
    return sorted(edges), excluded


def g_dependency(ctx, objects):
    edges, excluded = _edges_from_objects(objects, ctx.pop)
    adj = {}
    for a, b in edges:
        adj.setdefault(a, set()).add(b)
        adj.setdefault(b, set()).add(a)
    sets = {}
    for a, b in edges:
        sets.setdefault((a, b), set()).add(f"{a}~{b}")
    for v in sorted(adj):                     # connected triples: a path through v (covers triangles)
        for x, y in itertools.combinations(sorted(adj[v]), 2):
            s = tuple(sorted((v, x, y)))
            keys = sets.setdefault(s, set())
            for p, q in itertools.combinations(s, 2):
                if q in adj.get(p, ()):
                    keys.add(f"{p}~{q}")
    link = {}
    for a, b in edges:
        link.setdefault(a, set()).add(f"{a}~{b}")
        link.setdefault(b, set()).add(f"{a}~{b}")
    return GenResult("G-DEPENDENCY", _cands(sets), set(adj), link,
                     {"k": list(ARITIES), "input_policy": "OMQ-17: PROPOSED records of H-06-accepted batches (pass snapshot)",
                      "graph": "undirected for selection", "edges": len(edges)},
                     excluded_inputs={"edges_excluded": len(excluded)})


def cochange_source_status(ctx, s):
    if s not in ctx.files_meta:
        return "NOT-A-DISCOVERY-FILE"
    if ctx.files_meta[s].get("provenance") != "PRIMARY":
        return "NON-PRIMARY"
    if s not in ctx.degree:
        return "UNCITED-BY-ANY-ROW"
    if ctx.degree[s] > DEGREE_CAP:
        return "DEGREE-ABOVE-CAP"
    return "ELIGIBLE"


def g_cochange(ctx, objects):
    by_src = {}
    for o in objects:
        lab = o.get("working_label")
        if lab not in ctx.pop:
            continue
        for p in o.get("timeline") or []:
            if isinstance(p, dict) and p.get("source_id") and p.get("change_vs_previous") != "RESTATES":
                by_src.setdefault(p["source_id"], set()).add(lab)
    status = {s: cochange_source_status(ctx, s) for s in by_src}
    eligible = {s: labs for s, labs in by_src.items() if status[s] == "ELIGIBLE" and len(labs) >= 2}
    sets = _ksubsets_from_buckets(eligible, ctx.pop)
    excl_sets = _ksubsets_from_buckets({s: labs for s, labs in by_src.items() if status[s] != "ELIGIBLE"}, ctx.pop)
    extra = {m: [{"source_id": s, "reason": status[s]} for s in sorted(srcs)] for m, srcs in excl_sets.items() if m in sets}
    link = {}
    for s, labs in eligible.items():
        for m in labs:
            link.setdefault(m, set()).add(s)
    return GenResult("G-COCHANGE", _cands(sets, extra), set(link), link,
                     {"k": list(ARITIES), "degree_cap": DEGREE_CAP, "sources_primary_cited_degree_le_cap": True,
                      "sources_seen": len(by_src), "sources_eligible": len(eligible)},
                     excluded_inputs={"sources_excluded": {s: st for s, st in sorted(status.items()) if st != "ELIGIBLE"}})


def g_topic(ctx, register):
    buckets = {}
    for r in register:
        labs = {r.get("working_label")} | set(r.get("related_labels") or [])
        for t in r.get("topics") or []:
            buckets.setdefault(t, set()).update(l for l in labs if l in ctx.pop)
    sets = _ksubsets_from_buckets(buckets, ctx.pop)
    return GenResult("G-TOPIC", _cands(sets), {m for v in buckets.values() for m in v}, {}, {"k": list(ARITIES)})


def g_hyp(ctx, register):
    buckets = {}
    for r in register:
        if r.get("kind") in HYP_KINDS:
            buckets[r.get("rs_id")] = {l for l in (r.get("related_labels") or []) if l in ctx.pop}
    sets = _ksubsets_from_buckets(buckets, ctx.pop)
    return GenResult("G-HYP", _cands(sets), {m for v in buckets.values() for m in v}, {}, {"k": list(ARITIES)})


def run_generator(g, ctx, objects=(), register=()):
    if g == "G-SHARED-GROUP":
        return g_shared_group(ctx)
    if g == "G-NOTATION":
        return g_notation(ctx)
    if g == "G-TYPE-SIM":
        return g_type_sim(ctx)
    if g == "G-DEPENDENCY":
        return g_dependency(ctx, objects)
    if g == "G-COCHANGE":
        return g_cochange(ctx, objects)
    if g == "G-TOPIC":
        return g_topic(ctx, register)
    if g == "G-HYP":
        return g_hyp(ctx, register)
    raise C.S5Error(f"unknown generator {g}")


# ------------------------------------------------------------------ budget and family
def budget_seed(g):
    return C.int_seed(BUDGET_ROOT, len(GENERATORS), GEN_INDEX[g])


def apply_budget(gres):
    """Marks in_budget on the full (sorted) list; returns the analysed candidates in sorted order."""
    g, full = gres.generator, gres.candidates
    if g in SELF_DERIVED:
        for c in full:
            c["in_budget"] = None                      # not budgeted, never counted
        return []
    if g in FULL_LIST_GENERATORS or len(full) <= BUDGET:
        chosen = {id(c) for c in full}
    else:                                              # frozen A.6 call, on the full list sorted by member tuple
        chosen = {id(x) for x in random.Random(budget_seed(g)).sample(full, BUDGET)}
    for c in full:
        c["in_budget"] = id(c) in chosen
    return [c for c in full if c["in_budget"]]


def mark_test_family(gres, nca_ids):
    """§9E.2 item 6: (1) budget sub-sample, (2) drop NO-CONTROL-AVAILABLE, (3) greedy label-disjoint by sorted tuple."""
    used = set()
    for c in gres.candidates:                              # already sorted by member tuple
        ok = (gres.generator in GENERATORS and c.get("in_budget") and c["candidate_id"] not in nca_ids
              and used.isdisjoint(c["members"]))
        c["in_test_family"] = bool(ok)
        if ok:
            used.update(c["members"])
    return [c for c in gres.candidates if c["in_test_family"]]


def assign_ids(gres):
    for i, c in enumerate(gres.candidates):
        c["candidate_id"] = f"{gres.generator}#{i:06d}"


def candidate_record(gres, c):
    params = dict(gres.parameters)
    if gres.generator in BUDGETED_GENERATORS:
        params.update({"budget": BUDGET, "budget_seed_root": BUDGET_ROOT, "budget_seed_int": budget_seed(gres.generator),
                       "budget_seed_index": GEN_INDEX[gres.generator]})
    return {"record_type": "CANDIDATE", "candidate_id": c["candidate_id"], "generator": gres.generator,
            "generator_version": VERSIONS[gres.generator], "parameters": params,
            "defining_property": {"property": DEFINING[gres.generator], "link_tokens": c["link_tokens"]},
            "inputs_ai_produced": AI_INPUT[gres.generator], "members": c["members"], "k": c["k"],
            "source_exclusions": c["source_exclusions"], "in_budget": c.get("in_budget"),
            "in_test_family": c.get("in_test_family", False),
            "self_derived": gres.generator in SELF_DERIVED}


# ------------------------------------------------------------------ orchestration
def build(ctx, generators, objects=(), register=(), with_controls=True):
    """Runs generators, budget, the A.7 control draw (p3b_s5a_controls) and the test family. Returns
    (results {g: GenResult}, candidate records, control records, draw summaries)."""
    import p3b_s5a_controls as K
    results, cand_recs, ctrl_recs, draws = {}, [], [], {}
    for g in [x for x in ALL_GENERATORS if x in generators]:
        gres = run_generator(g, ctx, objects, register)
        assign_ids(gres)
        analysed = apply_budget(gres)
        nca = set()
        if with_controls and g in GENERATORS:
            d = K.draw_for_generator(gres, ctx.bands, analysed)
            draws[g] = d
            nca = {x["candidate_id"] for x in d if x["status"] == "NO-CONTROL-AVAILABLE"}
            ctrl_recs.extend(K.control_records(gres, d))
        mark_test_family(gres, nca)
        results[g] = gres
        cand_recs.extend(candidate_record(gres, c) for c in gres.candidates)
    for r in cand_recs:
        for m in r["members"]:
            if m not in ctx.pop:
                raise C.S5Error("§M item 1: candidate member outside the S5 population")
    for r in ctrl_recs:
        for m in r.get("members", ()):
            if m not in ctx.pop:
                raise C.S5Error("§M item 1: control member outside the S5 population")
    return results, cand_recs, ctrl_recs, draws


def body_text(records):
    return "".join(C.canon(r) + "\n" for r in records)


def write_jsonl(path, header, records):
    with open(path, "w", encoding="utf-8") as f:
        f.write(C.canon({"header": header}) + "\n")
        f.write(body_text(records))


def read_jsonl_artifact(path, verify=True):
    with open(path, encoding="utf-8") as f:
        lines = [l for l in f.read().split("\n") if l]
    header = json.loads(lines[0])["header"]
    body = "".join(l + "\n" for l in lines[1:])
    if verify and C.sha256_bytes(body.encode("utf-8")) != header["output_sha256"]:
        raise C.S5Error(f"{path}: body hash differs from header output_sha256")
    return header, [json.loads(l) for l in lines[1:]]


def load_snapshot_records(paths):
    C.check_inputs(paths)
    out = []
    for p in paths:
        with open(os.path.join(C.CR, p), encoding="utf-8") as f:
            out.extend(json.loads(l) for l in f if l.strip())
    return out


def safe_out(path, allow_real=False):
    """Build mode: outputs under /tmp only. --real-pass: outputs must be allowlisted repository artifacts (never /tmp)."""
    ap = os.path.abspath(path)
    if not allow_real:
        if not ap.startswith("/tmp/"):
            raise C.S5Error("build mode: outputs may be written under /tmp only (pass --real-pass for an authorized pass)")
        return ap
    if ap.startswith("/tmp/"):
        raise C.S5Error("--real-pass: pass artifacts must be written in the repository, not /tmp")
    C.check_inputs([ap])
    return ap


def bind_to_snapshot(snapshot_path, record_paths, real_pass):
    """Plan §H.7 link 1: every AI-input record file must be a file the pass snapshot hashed, with the same sha256."""
    if not snapshot_path:
        raise C.S5Error("AI-input record files need --pass-snapshot (link 1)")
    if real_pass:
        C.check_inputs([snapshot_path] + list(record_paths))
    with open(snapshot_path, encoding="utf-8") as f:
        snap = json.load(f)
    body = snap.get("body") or {}
    if snap.get("header", {}).get("output_sha256") != C.sha256_bytes(C.canon(body).encode("utf-8")):
        raise C.S5Error("pass snapshot header does not match its body")
    hashed = {}
    for b in body.get("accepted_batches", []):
        for rel_path, h in b.get("files", {}).items():
            hashed[os.path.basename(os.path.dirname(rel_path)) + "/" + os.path.basename(rel_path)] = h
    for p in record_paths:
        key = os.path.basename(os.path.dirname(os.path.abspath(p))) + "/" + os.path.basename(p)
        if hashed.get(key) != C.sha256_file(os.path.abspath(p)):
            raise C.S5Error("an AI-input record file is not in the pass snapshot, or its hash differs (link 1)")
    return True


def main(argv=None):
    ap = argparse.ArgumentParser(description=__doc__.split("\n")[0])
    ap.add_argument("--out", default="/tmp/s5a-build/P3B-CROSS-CANDIDATES.jsonl")
    ap.add_argument("--generators", default="G-SHARED-GROUP,G-NOTATION,G-TYPE-SIM")   # the pre-S5 generators
    ap.add_argument("--snapshot-objects", nargs="*", default=[])
    ap.add_argument("--snapshot-register", nargs="*", default=[])
    ap.add_argument("--pass-snapshot", default=None, help="P3B-PASS-SNAPSHOT.json (link 1); required for AI-input generators")
    ap.add_argument("--real-pass", action="store_true", help="an authorized S5a pass (not authorized in this build)")
    a = ap.parse_args(argv)
    C.assert_sealed()
    C.verify_frozen()
    gens = [g for g in a.generators.split(",") if g]
    for g in gens:
        if g not in ALL_GENERATORS:
            raise C.S5Error(f"unknown generator {g}")
    if any(g in ("G-DEPENDENCY", "G-COCHANGE") for g in gens) and not a.snapshot_objects:
        raise C.S5Error("AI-input generators need the pass snapshot's object records (--snapshot-objects)")
    if a.snapshot_objects or a.snapshot_register:
        bind_to_snapshot(a.pass_snapshot, a.snapshot_objects + a.snapshot_register, a.real_pass)
    out = safe_out(a.out, a.real_pass)
    ctx = Ctx.load()
    objects = load_snapshot_records(a.snapshot_objects)
    register = load_snapshot_records(a.snapshot_register)
    results, cand, ctrl, draws = build(ctx, gens, objects, register)
    records = cand + ctrl
    params = {"generators": gens, "arities": list(ARITIES), "budget": BUDGET, "budget_seed_root": BUDGET_ROOT,
              "budget_seed_ints": {g: budget_seed(g) for g in gens if g in BUDGETED_GENERATORS},
              "degree_cap": DEGREE_CAP, "h18_files_above_cap": list(H18_FILES_ABOVE_CAP),
              "type_table_version": TYPE_TABLE_VERSION, "type_table_sha256": type_table_sha256(),
              "versions": {g: VERSIONS[g] for g in gens}, "population_rules": {g: POPULATION_RULE.get(g) for g in gens},
              "control_parameters": __import__("p3b_s5a_controls").parameters()}
    counts = {g: {"full": len(r.candidates), "k2": sum(1 for c in r.candidates if c["k"] == 2),
                  "k3": sum(1 for c in r.candidates if c["k"] == 3),
                  "in_budget": sum(1 for c in r.candidates if c.get("in_budget")),
                  "in_test_family": sum(1 for c in r.candidates if c.get("in_test_family")),
                  "population": len(r.population)} for g, r in results.items()}
    inputs = list(ctx.inputs) + list(a.snapshot_objects) + list(a.snapshot_register)
    h = C.header(__file__, inputs, params, body_text(records),
                 extra={"artifact": "P3B-CROSS-CANDIDATES.jsonl", "seed": {"budget_root": BUDGET_ROOT,
                        "control_root": 20261009}, "counts": counts, "build_mode": not a.real_pass,
                        "excluded_inputs": {g: r.excluded_inputs for g, r in results.items()}})
    write_jsonl(out, h, records)
    C.assert_sealed()
    print(json.dumps({"out": out, "output_sha256": h["output_sha256"], "counts": counts}, indent=1))
    return 0


if __name__ == "__main__":
    sys.exit(main())
