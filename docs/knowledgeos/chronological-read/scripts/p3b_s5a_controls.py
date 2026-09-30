#!/usr/bin/env python3
"""P3b S5a controls, blinding and reveal (frozen protocol v1.7 Appendix A.7, §9E.2 items 1-4, 6, 11; S5 plan v2.3.2 §H.1,
§H.7 links 3-4; G-LOG-0041).

INFRASTRUCTURE ONLY. Building and testing this script is authorized (G-LOG-0041); executing S5a research is not.

Control draw (library; called by p3b_s5a_generators.build and p3b_s5a_control_availability):
  * r = 2 control sets per analysed candidate, drawn from the generator's eligible population, such that NO PAIR of
    members has the generator's defining link; members never include a label of the candidate.
  * Exact arity (never relaxed). Matched on the set band = sorted multiset of member bands, per variable, over
    row, pair, prov, span, degree (p3b_s5_common.set_band). G-TYPE-SIM additionally matches has_formal_rows, never relaxed.
  * Within a matched set the controls share no label with the candidate or with each other; reuse across matched sets
    is allowed (G-LOG-0039).
  * Relaxation only when the pool is empty, in reverse variable order (degree, span, prov, pair, row), one variable
    at a time, cumulatively, each recorded. One control available -> kept (r_s = 1). None after all relaxations ->
    NO-CONTROL-AVAILABLE (the candidate is dropped from every cell's pool).
  * Per-candidate stream: numpy SeedSequence(20261009, spawn_key=(generator index, analysed-candidate index)), which is
    child [j] of child [i] of SeedSequence(20261009).spawn(...); recorded with every control record.
  * The draw is uniform over the valid pool: exact enumeration when the (link-unfiltered) pool has <= ENUM_LIMIT
    sets, otherwise rejection sampling from the uniform superset (still exactly uniform over the valid pool), falling
    back to exact enumeration if MAX_TRIES rejections occur. Deterministic given the stream.

Blind / reveal (CLI):
  * Blind file: every distinct set (analysed candidates, their controls, SELF-DERIVED candidates) once (§9E.2 item 11),
    sorted by member tuple, shuffled with numpy SeedSequence(20261008), re-keyed `unit_key` U000001..; ONLY members and
    evidence pointers (source_id, row line, anchor) -- analysts receive the blind file and nothing else (no object
    records, no candidate/control labels, no generator); defining-link fields (group ids, notations, shapes, edges,
    sources) are never written; evidence pointers that are defining-link tokens (G-COCHANGE shared source ids, of the
    proposing generator or of the generator a control serves) are stripped.
  * Reveal file: unit_key -> every role {CANDIDATE/CONTROL/SELF-DERIVED, generator, defining property, ids} and the
    number of pointers stripped.
  * blinding_level per generator by the §9E.2 item 6 token rule (script, not judgment), evaluated on the blind file as
    written: PARTIAL iff a defining-link token occurs in the blinded evidence text or the remaining pointers of any of
    the generator's sets (candidates: own tokens; controls: their candidate's tokens). See set_reveals_link.
  * The pass record (audit-p3b/S5A-PASS-RECORD.json; append-only, hash-chained entries, §19.5 header) receives the
    candidates, blind and reveal hashes and the blinding levels. In this build every output goes to /tmp only.

  python3 p3b_s5a_controls.py --candidates /tmp/s5a-build/P3B-CROSS-CANDIDATES.jsonl --blind-out ... --reveal-out ...
      --pass-record /tmp/s5a-build/S5A-PASS-RECORD.json
"""
import argparse
import itertools
import json
import math
import os
import re
import sys
import unicodedata

import numpy as np

_HERE = os.path.dirname(os.path.abspath(__file__))
if _HERE not in sys.path:
    sys.path.insert(0, _HERE)
import p3b_s5_common as C  # noqa: E402

R = 2                                   # H-17
CONTROL_ROOT = 20261009                 # §G.3
BLIND_ROOT = 20261008                   # §G.3
BAND_VARS = C.BAND_VARS                 # ("row", "pair", "prov", "span", "degree")
RELAX_ORDER = tuple(reversed(BAND_VARS))  # degree first, row last
FORMAL_VAR = "has_formal_rows"
ENUM_LIMIT = 5000                       # implementation constants of the uniform sampler (do not affect the law)
MAX_TRIES = 4000
GEN_ORDER = ("G-SHARED-GROUP", "G-DEPENDENCY", "G-NOTATION", "G-COCHANGE", "G-TYPE-SIM")


def parameters():
    return {"r": R, "control_seed_root": CONTROL_ROOT, "blind_seed_root": BLIND_ROOT, "band_variables": list(BAND_VARS),
            "relaxation_order": list(RELAX_ORDER), "never_relaxed": ["arity", FORMAL_VAR + " (G-TYPE-SIM)"],
            "set_band": "sorted multiset of member bands per variable (p3b_s5_common.set_band)",
            "relax_when": "pool empty (A.7)", "r_s_1_kept": True, "sampler": {"enum_limit": ENUM_LIMIT,
            "max_tries": MAX_TRIES, "law": "uniform over the valid pool"},
            "stream": "SeedSequence(20261009, spawn_key=(generator index, analysed-candidate index))"}


def level_vars(generator, level):
    relaxed = list(RELAX_ORDER[:level])
    active = [v for v in BAND_VARS if v not in relaxed]
    if generator == "G-TYPE-SIM":
        active = active + [FORMAL_VAR]
    return active, relaxed


def stream(gen_index, j):
    return np.random.Generator(np.random.PCG64(np.random.SeedSequence(CONTROL_ROOT, spawn_key=(gen_index, j))))


class Pool:
    """Class structure of a generator's eligible population at one relaxation level."""

    def __init__(self, gres, bands, level):
        self.active, self.relaxed = level_vars(gres.generator, level)
        self.classes = {}
        for lab in gres.population:
            self.classes.setdefault(tuple(bands[lab][v] for v in self.active), []).append(lab)
        self.keys = sorted(self.classes)
        self._cache = {}

    def multisets(self, target, k):
        """Class multisets of size k whose per-variable sorted band multisets equal the target profile."""
        tkey = tuple(target[v] for v in self.active)
        if (tkey, k) in self._cache:                   # k is part of the key: at full relaxation tkey is empty
            return self._cache[(tkey, k)]
        allowed = [key for key in self.keys if all(key[i] in tkey[i] for i in range(len(self.active)))]
        out = []
        for combo in itertools.combinations_with_replacement(allowed, k):
            if all(tuple(sorted(c[i] for c in combo)) == tkey[i] for i in range(len(self.active))):
                mult = {}
                for c in combo:
                    mult[c] = mult.get(c, 0) + 1
                out.append(tuple(sorted(mult.items())))
        self._cache[(tkey, k)] = out
        return out


def _valid(gres, members):
    return not gres.any_linked(members)


def _sample(rng, gres, ms_list, pool, excluded):
    """Uniform draw of one valid k-set from the multisets, excluding labels in `excluded`. None if the pool is empty."""
    avail = []
    for ms in ms_list:
        lists = [(sorted(l for l in pool.classes[key] if l not in excluded), m) for key, m in ms]
        cnt = 1
        for labs, m in lists:
            cnt *= math.comb(len(labs), m)
        if cnt:
            avail.append((cnt, lists))
    total = sum(c for c, _ in avail)
    if total == 0:
        return None

    def enumerate_all():
        out = []
        for _, lists in avail:
            for parts in itertools.product(*[itertools.combinations(labs, m) for labs, m in lists]):
                s = tuple(sorted(x for p in parts for x in p))
                if _valid(gres, s):
                    out.append(s)
        return sorted(set(out))

    if total <= ENUM_LIMIT:
        valid = enumerate_all()
        return valid[int(rng.integers(len(valid)))] if valid else None
    cum = np.cumsum([c for c, _ in avail])
    for _ in range(MAX_TRIES):
        u = int(rng.integers(total))
        idx = int(np.searchsorted(cum, u, side="right"))
        s = []
        for labs, m in avail[idx][1]:
            s.extend(labs[i] for i in rng.choice(len(labs), m, replace=False))
        s = tuple(sorted(s))
        if _valid(gres, s):
            return s
    valid = enumerate_all()
    return valid[int(rng.integers(len(valid)))] if valid else None


def draw_for_generator(gres, bands, analysed, r=R):
    """A.7 draw for every analysed candidate (sorted order). Returns one draw summary per candidate."""
    gi = GEN_ORDER.index(gres.generator)
    pools = {}
    out = []
    for j, c in enumerate(analysed):
        members, k = tuple(c["members"]), c["k"]
        rng = stream(gi, j)
        res = {"candidate_id": c["candidate_id"], "k": k, "stream": {"root": CONTROL_ROOT, "spawn_key": [gi, j]},
               "controls": [], "levels_tried": []}
        for level in range(len(BAND_VARS) + 1):
            if level not in pools:
                pools[level] = Pool(gres, bands, level)
            pool = pools[level]
            target = C.set_band(members, bands, pool.active)
            ms = pool.multisets(target, k)
            res["levels_tried"].append(level)
            first = _sample(rng, gres, ms, pool, set(members))
            if first is None:
                continue
            chosen = [first]
            while len(chosen) < r:
                nxt = _sample(rng, gres, ms, pool, set(members).union(*chosen))
                if nxt is None:
                    break
                chosen.append(nxt)
            for n, s in enumerate(chosen):
                res["controls"].append({"members": list(s), "relaxation_level": level,
                                        "relaxed_variables": list(pool.relaxed), "matched_variables": list(pool.active),
                                        "band_profile": {v: list(t) for v, t in C.set_band(s, bands, pool.active).items()}})
            break
        res["r_s"] = len(res["controls"])
        res["status"] = ("NO-CONTROL-AVAILABLE" if res["r_s"] == 0 else "OK" if res["r_s"] == r else "PARTIAL-R_S-1")
        res["candidate_band_profile"] = {v: list(t) for v, t in
                                         C.set_band(members, bands, level_vars(gres.generator, 0)[0]).items()}
        out.append(res)
    return out


def control_records(gres, draws):
    recs = []
    for d in draws:
        recs.append({"record_type": "CONTROL-DRAW", "generator": gres.generator, "candidate_id": d["candidate_id"],
                     "status": d["status"], "r_s": d["r_s"], "levels_tried": d["levels_tried"],
                     "seed_stream": d["stream"], "candidate_band_profile": d["candidate_band_profile"]})
        for n, ctl in enumerate(d["controls"]):
            recs.append({"record_type": "CONTROL", "control_id": f"{d['candidate_id']}/C{n + 1}",
                         "generator": gres.generator, "for_candidate": d["candidate_id"], "members": ctl["members"],
                         "k": len(ctl["members"]), "matching": {k: ctl[k] for k in ("relaxation_level", "relaxed_variables",
                                                                                     "matched_variables", "band_profile")},
                         "seed_stream": d["stream"], "draw_index": n + 1, "r_s": d["r_s"]})
    return recs


# ------------------------------------------------------------------ A.4 token matching (for blinding_level)
def _norm_text(s):
    b = C.s3.norm_base(s if isinstance(s, str) else C.canon(s))
    return b, b.casefold()


def term_occurs(term, text_cs, text_cf):
    t = C.s3.norm_base(str(term)).strip()
    if not t:
        return False
    if len(t) == 1:
        return re.search(r"(?<![\w])" + re.escape(t) + r"(?![\w])", text_cs) is not None
    t = t.casefold()
    if re.match(r"\w", t[0]) and re.match(r"\w", t[-1]):
        return re.search(r"\b" + re.escape(t) + r"\b", text_cf) is not None
    return re.search(r"(?<![\w])" + re.escape(t) + r"(?![\w])", text_cf) is not None


def own_name_terms(label):
    return {C.s3.norm_base(x).strip().casefold() for x in (label, label.replace("-", " "))}


POINTER_TOKEN_GENERATORS = ("G-COCHANGE",)     # generators whose defining-link tokens are evidence pointers (S-ids)
# M4-A (human ruling G-LOG-0045): for every blind unit with a G-SHARED-GROUP role (candidate or control), evidence
# pointers whose source is shared by two or more of the unit's members are stripped. The rule depends only on the
# unit's members, never on its role (role-symmetric), and is applied before analysis. It implements §9E.2 item 4 /
# A.7 stripping; the §9E.2 item 6 PARTIAL token rule is unchanged.
SHARED_SOURCE_STRIP_GENERATORS = ("G-SHARED-GROUP",)
# M4-R1, scope A'1 (§26 annex audit-p3b/20260925_0905_s5a-m4r1-blinding-annex.md; human rulings "M4 residual = A" and
# "M4-R1 scope = A'1"): after M4-A, every blind unit with a candidate or control role of ANY generator in which any
# member has no evidence pointer is WITHHELD (not written to the blind file; symmetric removal in every cell containing
# it, cause WITHHELD-M4R1), and every remaining such unit shows exactly M4R1_CAP pointer(s) per member: the lowest
# (source_id, row_line). Symmetric between candidate and control: whether it applies never depends on which of the
# two roles a unit holds; applied before analysis. (The G-SHARED-GROUP-only scope was rejected: the cap made family
# membership visible and produced a G-NOTATION role cue, AUC 0.896.) It removes the MEASURED pointer-derived role
# cues; it does not prove that no role cue exists (label-name semantics and anchors are visible and unmeasured).
M4R1_WITHHOLD = True
M4R1_CAP = 1
# Scope the engine implements (recorded, so a scope change cannot pass the approval check unnoticed).
M4R1_SCOPE = "all candidate/control units across all generators"
# Approved identity (human ruling A'1, §26 annex). check_m4r1_binding refuses any other recorded rule, cap or scope.
M4R1_APPROVED = {"rule": "R1 + cap 1", "cap": 1, "scope": "all candidate/control units across all generators"}
M4R1_CANONICALIZATION = ("sha256 over UTF-8 of p3b_s5_common.canon (json.dumps sort_keys=True, separators=(',', ':'), "
                         "ensure_ascii=False) of the sorted list of withheld member lists")
M4R1_KEYS = ("rule", "cap", "scope", "withheld_units", "withheld_sets_sha256", "canonicalization")


def shared_sources(rows_by_member):
    """Sources cited by the pointers of two or more distinct members of one unit."""
    seen = {}
    for lab, rows in rows_by_member.items():
        for s in {r["source_id"] for r in rows}:
            seen[s] = seen.get(s, 0) + 1
    return {s for s, n in seen.items() if n >= 2}


def _row_index(ctx):
    return {(lab, r["line"]): r for lab, rows in ctx.rows_by_label.items() for r in rows}


def resolve_pointers(ctx, blind_rec, index=None):
    """{label: [resolved row, ...]} for the evidence pointers REMAINING in a blind record."""
    index = index if index is not None else _row_index(ctx)
    out = {}
    for lab, ptrs in blind_rec["evidence"].items():
        out[lab] = [index.get((lab, p["row_line"]), {"source_id": p["source_id"], "anchor": p.get("anchor"),
                                                      "line": p["row_line"]}) for p in ptrs]
    return out


def _texts(row):
    """Blinded evidence text of one pointer (§9E.2 item 6): the resolved statement, type_signature and anchor quote."""
    return [_norm_text(row[f]) for f in ("statement", "type_signature", "anchor") if row.get(f) not in (None, "", {}, [])]


def set_reveals_link(ctx, generator, tokens, pointers):
    """§9E.2 item 6 token rule for one blinded set. `tokens` are the defining-link tokens of the generator for this set
    (a control is checked against its candidate's tokens); `pointers` = {label: [resolved rows]} REMAINING in the blind
    file. True iff a token occurs in the blinded evidence text or in the remaining pointers:
      G-SHARED-GROUP  a group id in the text;           G-NOTATION  the shared normalized notation/alias in the text;
      G-DEPENDENCY    both endpoint labels' A.4 terms in ONE evidence item (v1.6.3; a label's own name in its own
                      evidence does not count);
      G-COCHANGE      a shared source id as a remaining pointer's source_id, or in the text;
      G-TYPE-SIM      a remaining evidence row whose type_signature normalizes (A.6 table) to the defining shape."""
    if not tokens:
        return False
    tokens = list(tokens)
    if generator == "G-TYPE-SIM":
        import p3b_s5a_generators as G
        want = set(tokens)
        return any(G.is_formal(r.get("type_signature")) and G.signature_shape(r.get("type_signature")) in want
                   for rows in pointers.values() for r in rows)
    if generator == "G-COCHANGE":
        if any(r.get("source_id") in tokens for rows in pointers.values() for r in rows):
            return True
    if generator == "G-DEPENDENCY":
        members = list(pointers)
        terms = {}
        for tok in tokens:
            for end in tok.split("~", 1):
                terms.setdefault(end, set(C.s3.label_terms(end, ctx.nodes.get(end, {}))))
        for owner in members:
            for r in pointers[owner]:
                for text_cs, text_cf in _texts(r):
                    for tok in tokens:
                        ok = True
                        for end in tok.split("~", 1):
                            ts = terms[end] - own_name_terms(end) if end == owner else terms[end]
                            if not any(term_occurs(t, text_cs, text_cf) for t in ts):
                                ok = False
                                break
                        if ok:
                            return True
        return False
    for rows in pointers.values():
        for r in rows:
            for text_cs, text_cf in _texts(r):
                if any(term_occurs(t, text_cs, text_cf) for t in tokens):
                    return True
    return False


def _role_tokens(role, cand_by_id):
    """Defining-link tokens relevant to one role of a set: its own (candidate) or its candidate's (control)."""
    if role["role"] == "CANDIDATE":
        return cand_by_id[role["candidate_id"]]["defining_property"]["link_tokens"]
    if role["role"] == "CONTROL":
        return cand_by_id[role["for_candidate"]]["defining_property"]["link_tokens"]
    return []


def blinding_levels(ctx, blind, reveal, cand_records):
    """blinding_level per counted generator, by script, over every set of that generator (candidates and controls) in
    the blind file as written (after pointer stripping)."""
    cand_by_id = {r["candidate_id"]: r for r in cand_records}
    roles = {r["unit_key"]: r["roles"] for r in reveal}
    index = _row_index(ctx)
    out = {g: {"sets_checked": 0, "sets_revealing": 0, "first_revealing_unit_keys": []} for g in GEN_ORDER}
    for b in blind:
        ptrs = None
        for role in roles[b["unit_key"]]:
            g = role["generator"]
            if g not in out or role["role"] not in ("CANDIDATE", "CONTROL"):
                continue
            ptrs = ptrs if ptrs is not None else resolve_pointers(ctx, b, index)
            out[g]["sets_checked"] += 1
            if set_reveals_link(ctx, g, _role_tokens(role, cand_by_id), ptrs):
                out[g]["sets_revealing"] += 1
                if len(out[g]["first_revealing_unit_keys"]) < 10:
                    out[g]["first_revealing_unit_keys"].append(b["unit_key"])
    res = {}
    for g, v in out.items():
        if v["sets_checked"]:
            res[g] = {"blinding_level": "PARTIAL" if v["sets_revealing"] else "FULL", **v}
    return res


# ------------------------------------------------------------------ blind / reveal
def _collect_units(cand_records, ctrl_records):
    """{member tuple: [roles]} over analysed candidates, SELF-DERIVED candidates and controls (§9E.2 item 11)."""
    units = {}
    for r in cand_records:
        if r.get("self_derived"):
            role = {"role": "SELF-DERIVED", "generator": r["generator"], "candidate_id": r["candidate_id"],
                    "defining_property": r["defining_property"]["property"]}
        elif r.get("in_budget"):
            role = {"role": "CANDIDATE", "generator": r["generator"], "candidate_id": r["candidate_id"],
                    "defining_property": r["defining_property"]["property"], "in_test_family": r["in_test_family"]}
        else:
            continue
        units.setdefault(tuple(r["members"]), []).append(role)
    for r in ctrl_records:
        if r["record_type"] == "CONTROL":
            units.setdefault(tuple(r["members"]), []).append(
                {"role": "CONTROL", "generator": r["generator"], "control_id": r["control_id"],
                 "for_candidate": r["for_candidate"]})
    return units


def _sg_family(roles):
    return any(r["generator"] in SHARED_SOURCE_STRIP_GENERATORS and r["role"] in ("CANDIDATE", "CONTROL") for r in roles)


def _m4r1_in_scope(roles):
    """M4-R1 scope A'1: a unit carrying a candidate or control role of any generator."""
    return any(r["role"] in ("CANDIDATE", "CONTROL") for r in roles)


def _unit_evidence(ctx, m, roles, cand_by_id):
    """-> (evidence, stripped, stripped_shared, withheld). Token stripping (G-COCHANGE), M4-A shared-source stripping,
    then the M4 residual rule (withhold if a member is left without a pointer; otherwise cap per member)."""
    strip = set()
    for role in roles:
        if role["generator"] in POINTER_TOKEN_GENERATORS:
            strip.update(_role_tokens(role, cand_by_id))
    sg = _sg_family(roles)
    shared = shared_sources({lab: ctx.rows_by_label.get(lab, []) for lab in m}) if sg else set()   # M4-A
    evidence, stripped, stripped_shared = {}, 0, 0
    for lab in m:
        kept = []
        for x in ctx.rows_by_label.get(lab, []):
            if x["source_id"] in strip:
                stripped += 1
                continue
            if x["source_id"] in shared:
                stripped_shared += 1
                continue
            kept.append({"source_id": x["source_id"], "row_line": x["line"], "anchor": x.get("anchor")})
        evidence[lab] = kept
    withheld = False
    in_scope = _m4r1_in_scope(roles)                                             # A'1: every candidate/control unit
    if in_scope and M4R1_WITHHOLD and any(not v for v in evidence.values()):
        withheld = True
    elif in_scope and M4R1_CAP:
        evidence = {lab: sorted(v, key=lambda p: (p["source_id"], p["row_line"]))[:M4R1_CAP] for lab, v in evidence.items()}
    return evidence, stripped, stripped_shared, withheld


def withheld_sets(ctx, cand_records, ctrl_records):
    """Member tuples withheld under the M4 residual rule (sorted). Used by the blind build and the pre-reveal pools,
    so both apply exactly the same set."""
    cand_by_id = {r["candidate_id"]: r for r in cand_records}
    units = _collect_units(cand_records, ctrl_records)
    return sorted(m for m in units if _unit_evidence(ctx, m, units[m], cand_by_id)[3])


def m4r1_record(withheld):
    """The ONE recorded identity of the withheld set (counts and a hash; never the member lists). Written to the blind
    and reveal headers and to the PRE-ANALYSIS and LINK6 pass-record entries; `rule` states what the engine did."""
    return {"rule": ("R1" if M4R1_WITHHOLD else "no-R1") + f" + cap {M4R1_CAP}", "cap": M4R1_CAP,
            "scope": M4R1_SCOPE, "withheld_units": len(withheld),
            "withheld_sets_sha256": C.sha256_bytes(C.canon([list(m) for m in sorted(withheld)]).encode("utf-8")),
            "canonicalization": M4R1_CANONICALIZATION}


def check_m4r1_binding(*records):
    """Hard failure unless every record is a complete M4-R1 identity with the approved rule and cap, and all records
    carry the same withheld_units, withheld_sets_sha256 and canonicalization."""
    if len(records) < 2:
        raise C.S5Error("M4-R1 binding: at least two records are required")
    for r in records:
        if not isinstance(r, dict) or tuple(sorted(r)) != tuple(sorted(M4R1_KEYS)):
            raise C.S5Error("M4-R1 binding: a record is missing or malformed")
        if r["rule"] != M4R1_APPROVED["rule"] or type(r["cap"]) is not int or r["cap"] != M4R1_APPROVED["cap"] \
                or r["scope"] != M4R1_APPROVED["scope"]:
            raise C.S5Error("M4-R1 binding: recorded rule, cap or scope differs from the approved identity")
        if r["canonicalization"] != M4R1_CANONICALIZATION:
            raise C.S5Error("M4-R1 binding: canonicalization differs")
    first = records[0]
    for r in records[1:]:
        if (r["withheld_units"], r["withheld_sets_sha256"]) != (first["withheld_units"], first["withheld_sets_sha256"]):
            raise C.S5Error("M4-R1 binding: withheld-set count or sha256 differs between records")
    return True


def verify_m4r1_binding(blind_path, pass_record_path, real_pass=False):
    """The blind file and the pass record are bound to one withheld set: the PRE-ANALYSIS entry's blind_file_sha256
    is the blind file's hash, and the blind header's m4_residual equals the entry's m4_residual."""
    pre = pass_record_frozen(pass_record_path, "PRE-ANALYSIS-BLIND-REVEAL", real_pass)
    if C.sha256_file(blind_path) != pre.get("blind_file_sha256"):
        raise C.S5Error("M4-R1 binding: the blind file does not match the pass record")
    hb, _ = _read_jsonl_artifact(blind_path)
    return check_m4r1_binding(hb["parameters"].get("m4_residual"), pre.get("m4_residual"))


def _read_jsonl_artifact(path):
    import p3b_s5a_generators as G
    return G.read_jsonl_artifact(path)


def build_blind(ctx, cand_records, ctrl_records):
    """Blind file (analyst input: ONLY members and evidence pointers) and reveal file (sealed). Pointers that are
    defining-link tokens (G-COCHANGE shared source ids) of the generator that proposed the set, or of the generator it
    is a control for, are stripped from the set's evidence; M4-A applies to units with a G-SHARED-GROUP role, and M4-R1
    (R1 + cap 1, scope A'1) to every unit with a candidate or control role of any generator. Withheld units get no unit_key and are not written (the pools remove them
    symmetrically). Stripping counts are recorded in the REVEAL file only (in the blind file they would be a cue)."""
    cand_by_id = {r["candidate_id"]: r for r in cand_records}
    units = _collect_units(cand_records, ctrl_records)
    built = {m: _unit_evidence(ctx, m, units[m], cand_by_id) for m in units}
    keys = sorted(m for m in units if not built[m][3])
    perm = np.random.Generator(np.random.PCG64(np.random.SeedSequence(BLIND_ROOT))).permutation(len(keys))
    blind, reveal = [], []
    for n, idx in enumerate(perm):
        m = keys[int(idx)]
        key = f"U{n + 1:06d}"
        evidence, stripped, stripped_shared, _ = built[m]
        blind.append({"unit_key": key, "members": list(m), "evidence": evidence})
        reveal.append({"unit_key": key, "members": list(m), "pointers_stripped": stripped,
                       "shared_source_pointers_stripped": stripped_shared,
                       "roles": sorted(units[m], key=lambda x: (x["role"], x["generator"], C.canon(x)))})
    check_m4a(blind, reveal)
    return blind, reveal


def check_m4a(blind, reveal):
    """Build-time check. M4-A: a unit with a G-SHARED-GROUP candidate/control role keeps no pointer to a source shared
    by two or more of its members. M4-R1 (scope A'1): a unit with a candidate/control role of any generator has no
    member without a pointer and shows at most M4R1_CAP pointers per member. Raises otherwise; an implementation
    check, not a blinding_level rule."""
    roles = {r["unit_key"]: r["roles"] for r in reveal}
    for b in blind:
        rs = roles[b["unit_key"]]
        if _sg_family(rs) and shared_sources({lab: ptrs for lab, ptrs in b["evidence"].items()}):
            raise C.S5Error("M4-A: a G-SHARED-GROUP unit retains a shared-source pointer")
        if _m4r1_in_scope(rs):
            if M4R1_WITHHOLD and any(not v for v in b["evidence"].values()):
                raise C.S5Error("M4-R1: a candidate/control unit with a member without a pointer was not withheld")
            if M4R1_CAP and any(len(v) > M4R1_CAP for v in b["evidence"].values()):
                raise C.S5Error("M4-R1: a candidate/control unit shows more pointers per member than the cap")
    return True


# ------------------------------------------------------------------ artifact paths and the pass record
def check_artifact_path(p, real_pass=False):
    """S5A artifacts: repository paths must pass the allowlist; /tmp paths are build-mode scratch artifacts and are
    refused in a real pass (--real-pass), where every pass artifact must be a repository file."""
    ap = os.path.abspath(p if os.path.isabs(p) else os.path.join(C.CR, p))
    if ap.startswith("/tmp/"):
        if real_pass:
            raise C.S5Error("--real-pass: pass artifacts must be repository files, not /tmp paths")
        return ap
    C.check_inputs([p])
    return ap


def _json_artifact(body):
    return json.dumps(body, indent=1, sort_keys=True, ensure_ascii=False)


def write_json_artifact(path, header, body):
    with open(path, "w", encoding="utf-8") as f:
        f.write(json.dumps({"header": header, "body": body}, indent=1, sort_keys=True, ensure_ascii=False) + "\n")


def read_json_artifact(path, verify=True):
    with open(check_artifact_path(path), encoding="utf-8") as f:
        d = json.load(f)
    if verify and C.sha256_bytes(_json_artifact(d["body"]).encode("utf-8")) != d["header"]["output_sha256"]:
        raise C.S5Error(f"{path}: body hash differs from header output_sha256")
    return d["header"], d["body"]


FROZEN_ONCE = ("PRE-ANALYSIS-BLIND-REVEAL", "LINK6-POOLS-PREREVEAL", "PRE-RELEASE-BLINDING-GATE")   # one frozen entry per pass record (= per pass)


def _verified_entries(ap):
    _, body = read_json_artifact(ap)
    entries = body["entries"]
    prev = None
    for e in entries:
        core = {k: v for k, v in e.items() if k != "entry_sha256"}
        if e.get("prev_entry_sha256") != prev or C.sha256_bytes(C.canon(core).encode()) != e["entry_sha256"]:
            raise C.S5Error("pass record: hash chain broken (append-only violated)")
        prev = e["entry_sha256"]
    return entries


def pass_record_append(path, kind, payload, script_file, inputs=()):
    """Append-only, hash-chained entries. Existing entries are verified and never rewritten. The pre-analysis entries
    (FROZEN_ONCE) can be written once per pass record; a second one is refused."""
    ap = os.path.abspath(path)
    entries = _verified_entries(ap) if os.path.exists(ap) else []
    if kind in FROZEN_ONCE and any(e["kind"] == kind for e in entries):
        raise C.S5Error(f"pass record: {kind} is already frozen for this pass; a second entry is refused")
    entry = {"seq": len(entries) + 1, "kind": kind, "payload": payload,
             "prev_entry_sha256": entries[-1]["entry_sha256"] if entries else None}
    entry["entry_sha256"] = C.sha256_bytes(C.canon(entry).encode())
    body = {"artifact": "audit-p3b/S5A-PASS-RECORD.json", "entries": entries + [entry]}
    h = C.header(script_file, [i for i in inputs if not os.path.isabs(i)], {"append": kind}, _json_artifact(body))
    write_json_artifact(ap, h, body)
    return entry


def pass_record_frozen(path, kind, real_pass=False):
    """The FIRST (and only) frozen entry of `kind` for this pass. Refuses if none, or if more than one exists."""
    got = [e for e in _verified_entries(check_artifact_path(path, real_pass)) if e["kind"] == kind]
    if not got:
        raise C.S5Error(f"REFUSED: pass record has no {kind} entry")
    if len(got) > 1:
        raise C.S5Error(f"REFUSED: pass record has {len(got)} {kind} entries for one pass (exactly one is frozen)")
    return got[0]["payload"]


def main(argv=None):
    import p3b_s5a_generators as G
    ap = argparse.ArgumentParser(description=__doc__.split("\n")[0])
    ap.add_argument("--candidates", default="/tmp/s5a-build/P3B-CROSS-CANDIDATES.jsonl")
    ap.add_argument("--blind-out", default="/tmp/s5a-build/P3B-CROSS-BLIND.jsonl")
    ap.add_argument("--reveal-out", default="/tmp/s5a-build/P3B-CROSS-REVEAL.jsonl")
    ap.add_argument("--pass-record", default="/tmp/s5a-build/S5A-PASS-RECORD.json")
    ap.add_argument("--pass-snapshot", default=None, help="P3B-PASS-SNAPSHOT.json (link 1); required with --real-pass")
    ap.add_argument("--real-pass", action="store_true")
    a = ap.parse_args(argv)
    C.assert_sealed()
    C.verify_frozen()
    for p in (a.blind_out, a.reveal_out, a.pass_record):
        G.safe_out(p, a.real_pass)
    if a.real_pass and not a.pass_snapshot:
        raise C.S5Error("--real-pass requires --pass-snapshot (link 1)")
    spath = check_artifact_path(a.pass_snapshot, a.real_pass) if a.pass_snapshot else None
    cpath = check_artifact_path(a.candidates, a.real_pass)
    ch, recs = G.read_jsonl_artifact(cpath)
    cand = [r for r in recs if r["record_type"] == "CANDIDATE"]
    ctrl = [r for r in recs if r["record_type"] in ("CONTROL", "CONTROL-DRAW")]
    ctx = G.Ctx.load()
    blind, reveal = build_blind(ctx, cand, ctrl)
    withheld = withheld_sets(ctx, cand, ctrl)
    levels = blinding_levels(ctx, blind, reveal, cand)
    m4r1 = m4r1_record(withheld)
    params = {"blind_seed_root": BLIND_ROOT, "candidates_output_sha256": ch["output_sha256"], "m4_residual": m4r1}
    inputs = list(ctx.inputs) + [cpath] + ([spath] if spath else [])
    hb = C.header(__file__, inputs, params, G.body_text(blind), extra={"artifact": "P3B-CROSS-BLIND.jsonl",
                  "seed": {"blind_root": BLIND_ROOT}})
    G.write_jsonl(a.blind_out, hb, blind)
    hr = C.header(__file__, inputs, params, G.body_text(reveal), extra={"artifact": "P3B-CROSS-REVEAL.jsonl",
                  "sealed_until": "every blinded set is dispositioned", "blinding": levels})
    G.write_jsonl(a.reveal_out, hr, reveal)
    payload = {"link1_pass_snapshot_sha256": C.sha256_file(spath) if spath else None,
               "link3_candidates_file_sha256": C.sha256_file(cpath), "link3_candidates_output_sha256": ch["output_sha256"],
               "link4_controls_in": "P3B-CROSS-CANDIDATES.jsonl (CONTROL / CONTROL-DRAW records)",
               "blind_file_sha256": C.sha256_file(a.blind_out), "reveal_file_sha256": C.sha256_file(a.reveal_out),
               "blind_output_sha256": hb["output_sha256"], "reveal_output_sha256": hr["output_sha256"],
               "blinding_level": {g: v["blinding_level"] for g, v in levels.items()}, "m4_residual": m4r1}
    pass_record_append(a.pass_record, "PRE-ANALYSIS-BLIND-REVEAL", payload, __file__, inputs)
    verify_m4r1_binding(a.blind_out, a.pass_record, a.real_pass)              # blind header <-> pass record (hard)
    C.assert_sealed()
    print(json.dumps({"blind_units": len(blind), "blinding": levels, "pass_record": a.pass_record}, indent=1))
    return 0


if __name__ == "__main__":
    sys.exit(main())
