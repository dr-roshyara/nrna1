#!/usr/bin/env python3
"""F-Series evidence checks shared by f_transition.py (gates) and f_audit.py (re-verification). Pure checks: they
return (ok, findings[, stats]) and write nothing. v1.2: remediation of the independent audit (F-LOG-0007); finding
ids (F-nn) are cited where a rule answers one. Section refs: protocol §F-n, contracts Cnn."""
import collections
import hashlib
import json
import os
import re
import subprocess

import f_common as C

# ---- inherited vocabularies (INHERITED-UNCHANGED; sources named in protocol §F-3 matrix) --------------------------
TYPES = {"CONCEPT", "DEFINITION", "FORMALIZATION", "AXIOM", "PRINCIPLE", "INVARIANT", "ASSUMPTION", "EXPLANATION",
         "ARGUMENT", "ANALYSIS", "WARNING", "CONSTRAINT", "DISTINCTION", "EXAMPLE", "COUNTEREXAMPLE", "EXPERIMENT",
         "EXPERIMENTAL-RESULT", "EXTENSION", "ALTERNATIVE", "CORRECTION", "RETRACTION", "CONTRADICTION",
         "IMPLEMENTATION", "VALIDATION", "GOVERNANCE", "LIMITATION", "OPEN-QUESTION", "FUTURE-RESEARCH",
         "RESTATEMENT", "HYPOTHESIS"}                                           # v3.5 B2 / extraction contract
SCOPES = {"OBJECT", "CROSS-OBJECT", "THEORY-LEVEL", "METHODOLOGICAL"}           # v3.5 B2
COMPLETENESS = {"COMPLETE", "PARTIAL", "INFORMAL-ONLY", "NAME-ONLY", "N/A"}     # v3.5 B5
PROVENANCE = {"PRIMARY", "SECONDARY-SYNTHESIS", "PROVENANCE-UNRESOLVED"}        # extraction contract STEP 3
ORDER_EVIDENCE = {"STEP-NUMBER", "INTERNAL-TIMESTAMP", "FILENAME-DATESTAMP", "LIST-POSITION"}  # R1a; §F-3 row 7
LINEAGE = {f"SOURCE-CLAIMED-{k}" for k in ("IDENTITY", "REFINEMENT", "EXTENSION", "REDEFINITION", "REPLACEMENT",
                                           "SPECIALIZATION", "DERIVATION", "CONTINUATION", "SEPARATION",
                                           "CONTRADICTION", "RETRACTION")}     # extraction contract STEP 10
REVIEW_FLAGS = {None, "MATH-QUESTION", "STAT-QUESTION", "TYPE-QUESTION"}
VERSION_REF = {"pre-v1.2", "v1.2", "post-v1.2", "cross-version", "unknown"}
KINDS_B = {"OBSERVATION", "GAP", "SCHEMA-LIMITATION", "METHODOLOGICAL-DEFICIENCY"}          # P3b v1.7 §13.2
KINDS_C = {"SUGGESTION-RESEARCH", "SUGGESTION-METHOD", "HYPOTHESIS", "STRUCTURE-CANDIDATE"}  # P3b v1.7 §13.2
EPISTEMIC_B = {"RESEARCH-OBSERVATION", "DOMAIN-INTERPRETATION", "EXTERNAL-THEORY-COMPARISON"}  # P3b v1.7 §1B / §11.1
EPISTEMIC_C_PER_FILE = {"RESEARCH-SUGGESTION", "HYPOTHESIS"}                    # F-10: THEORY-CANDIDATE only at CPs
LENS_KEYS = ["STRUCTURAL", "MATHEMATICAL", "LOGICAL", "DDD", "STATISTICAL-ML"]  # the per-file lens checklist
LENS_ALL = set(LENS_KEYS) | {"EPISTEMIC", "CHRONOLOGICAL", "MIXED"}             # F-19: one vocabulary, L2 ⊂ L3
MIN_DIGEST_QUOTE = 40                                                            # RL-08
MIN_ITEM_QUOTE = 20                                                              # F-03
MIN_CONTENT = 10                                                                 # F-03
MAX_ITEM_UNITS = 6                                                               # F-03 (anti catch-all)
MAX_CONTRIB_ITEMS = 12                                                           # F-03 (anti catch-all)
PREREG_KEYS = ["population_rule", "temporal_scope", "selection_rule", "comparison_rule", "stopping_rule",
               "prediction", "registered_after_f", "holdout_basis", "confirmatory_checkpoint", "family",
               "multiplicity_rule"]                                              # C10 v1.2 (F-12, F-15)
OUTCOMES = ["SUPPORTED", "UNSUPPORTED", "UNDETERMINED"]                          # human rule 2026-09-25 (C11)
OUTCOME_KEYS = {"outcome", "result", "test_outcome", "test_result", "verdict", "finding_status"}  # F-10
ISO_UTC = re.compile(r"\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}Z")
# F-14: identifier-shaped S-lane references in analyst-authored text (a guarantee about identifiers only; C15)
S_LANE = re.compile(r"\bS\d{4}\b|\bP3[ab]\b|\bP3B\b|\bS5[a-c]?\b|\bG-LOG-\d{4}\b|\bOB\d{4}\b|\bH-19\b|chronological-read/")
S_ID = re.compile(r"\bS\d{4}\b")
# F-09: prescriptive / KnowledgeOS-design wording is Level 3; checked outside quotation marks
PRESCRIPTIVE = re.compile(r"\b(should|ought to|must be (modell?ed|implemented|adopted|treated)|we (propose|recommend)|"
                          r"recommend(ed|s)?|would be better|needs? to be (modell?ed|implemented)|"
                          r"knowledgeos (should|must|needs|would|could|ought)|"
                          r"(implement|model|adopt)(ed|s)? (it|this|x|them)? ?as (an? )?(aggregate|bounded context|"
                          r"value object|entity|domain event))\b", re.IGNORECASE)
QUOTED = re.compile(r"\"[^\"]*\"|“[^”]*”|'[^'\n]{3,}'|`[^`]*`")
FRAMING = re.compile(r"contradict|tension|conflict|inconsisten|paradox|incompatib|cannot both|fails? its own|violat|"
                     r"clash|oppos", re.IGNORECASE)                             # C07 source-framing markers


def norm(s):
    return re.sub(r"\s+", " ", s or "").strip()


def ledger_path(fid, name):
    return os.path.join(C.LEDGER, fid, name)


def jsonl_or_error(path, findings):
    if not os.path.exists(path):
        findings.append(f"MISSING {os.path.relpath(path, C.FDIR)}")
        return None
    out = []
    for i, l in enumerate(open(path, encoding="utf-8"), 1):
        if not l.strip():
            continue
        try:
            out.append(json.loads(l))
        except json.JSONDecodeError as e:
            findings.append(f"JSON {os.path.relpath(path, C.FDIR)}:{i} {e}")
    return out


def load_json(path):
    try:
        return json.load(open(path, encoding="utf-8")) if os.path.exists(path) else None
    except json.JSONDecodeError:
        return None


def quote_in(quote, text):
    return bool(norm(quote)) and norm(quote) in norm(text)


def s_lane_hits(*texts):
    return sorted({m.group(0) for t in texts for m in S_LANE.finditer(str(t or ""))})


def prescriptive(text):
    return bool(PRESCRIPTIVE.search(QUOTED.sub(" ", text or "")))


# ---- reading integrity (C01) --------------------------------------------------------------------------------------
def coverage(fid, run_id, digests_name="PAGE-DIGESTS.jsonl"):
    """Recompute page coverage for one run from READ-LOG + current bytes. Returns a dict for F-READ-INTEGRITY."""
    row = C.manifest()[fid]
    text = C.content_text(row)                                   # raises on IDENTITY-DRIFT
    pages = C.pages_of(text)
    expect = {k: C.sha256_bytes(text[a:b].encode("utf-8")) for k, (a, b) in enumerate(pages, 1)}
    logged, forged = {}, []
    for r in C.read_jsonl(ledger_path(fid, "READ-LOG.jsonl")):
        if r.get("run_id") != run_id or r.get("refused") or "page" not in r:
            continue
        p = r["page"]
        if expect.get(p["page"]) != p["page_sha256"] or p["content_sha256"] != row["content_sha256"]:
            forged.append(p["page"])
        else:
            logged[p["page"]] = p["page_sha256"]
    digests = {d.get("page"): d for d in C.read_jsonl(ledger_path(fid, digests_name)) if d.get("run_id") == run_id}
    digest_bad = []
    for k, (a, b) in enumerate(pages, 1):
        d = digests.get(k)
        q = (d or {}).get("verbatim_quote", "")
        if not d or len(q) < MIN_DIGEST_QUOTE or not quote_in(q, text[a:b]) or not (d.get("digest") or "").strip():
            digest_bad.append(k)
    missing = sorted(set(expect) - set(logged))
    return {"f_id": fid, "run_id": run_id, "content_sha256": row["content_sha256"], "page_chars": C.PAGE_CHARS,
            "expected_pages": len(pages), "pages_logged": len(logged), "missing_pages": missing,
            "forged_or_mismatched_pages": sorted(set(forged)), "digest_missing_or_invalid_pages": digest_bad,
            "hashes_verified": not forged, "coverage_pct": round(100.0 * len(logged) / len(pages), 2),
            "complete": not missing and not forged and not digest_bad}


# ---- units, math/code and definition detection (C02 §2, F-04, F-05) -----------------------------------------------
INVENTORY_CATEGORIES = [
    "TERM", "DEFINITION", "CONCEPT", "DISTINCTION", "RULE", "PRINCIPLE", "THEOREM", "FORMULA", "NOTATION",
    "ALGORITHM", "PROCEDURE", "ARCHITECTURE-COMPONENT", "RELATIONSHIP", "ASSUMPTION", "CONSTRAINT", "EXAMPLE",
    "COUNTEREXAMPLE", "HYPOTHESIS", "RESEARCH-QUESTION", "CONCLUSION", "UNRESOLVED-QUESTION", "CONTRADICTION-OR-TENSION",
    "CLASSIFICATION", "MECHANISM", "DEPENDENCY", "REFERENCE"]                    # the human's list, 2026-09-25
DISPOSITIONS = {"RESTATES-UNIT", "NO-SUBSTANTIVE-CONTENT"}                       # coverage substitutes (prose only)
RELEASES = {"NOT-A-DEFINITION", "NOT-MATHEMATICAL"}                              # detector false-positive releases
MATH_CATS = {"FORMULA", "NOTATION", "THEOREM"}
DEF_CATS = {"DEFINITION", "TERM"}
CODE_CATS = {"ALGORITHM", "PROCEDURE", "ARCHITECTURE-COMPONENT", "MECHANISM", "NOTATION", "FORMULA", "EXAMPLE"}
SOURCE_STATUS = {"ASSERTED", "PROOF-SKETCH", "PROVED", "CITED", "UNDEFINED-UNCLEAR"}   # RN-06 (source status)
DEFINITION_FORMS = {"FORMAL", "INFORMAL", "NEGATIVE", "BY-EXAMPLE", "BY-ANALOGY", "IMPLICIT"}      # C03 §1
REF_KINDS = {"FILE", "DOCUMENT", "THEORY", "MODEL", "AUTHOR", "WORK", "OTHER"}
MARKUP_ONLY = re.compile(r"\s*(-{3,}|\*{3,}|_{3,}|\|?\s*:?-{3,}:?\s*(\|\s*:?-{3,}:?\s*)*\|?)\s*")
MATH_ENVS = r"(equation|align|gather|multline|eqnarray|math|displaymath|array|matrix|[pbvBV]matrix|cases|split|aligned)"
LATEX_CMDS = (r"frac|sum|prod|int|oint|forall|exists|nexists|mathcal|mathfrak|mathbb|mathrm|mathbf|mathsf|to|"
              r"rightarrow|Rightarrow|leftarrow|Leftarrow|leftrightarrow|Leftrightarrow|mapsto|circ|times|otimes|oplus|"
              r"subseteq|subset|supseteq|supset|neg|lnot|land|lor|wedge|vee|leq|geq|neq|le|ge|ne|dashv|vdash|models|in|"
              r"notin|ni|cup|cap|bigcup|bigcap|setminus|emptyset|varnothing|infty|partial|nabla|alpha|beta|gamma|delta|"
              r"Delta|Gamma|epsilon|varepsilon|Phi|phi|varphi|psi|Psi|sigma|Sigma|lambda|Lambda|mu|pi|Pi|theta|Theta|"
              r"omega|Omega|rho|tau|chi|xi|Xi|kappa|eta|zeta|prec|succ|preceq|succeq|sqsubseteq|langle|rangle|sqrt|lim|"
              r"sup|inf|max|min|operatorname|cdot|cdots|ldots|dots|equiv|approx|sim|simeq|cong|iff|implies|top|bot|"
              r"coloneqq|quad|qquad|hat|bar|tilde|vec|overline|underline|boxed|text|mid|parallel|perp|triangleq")
MATH_MARKERS = [
    ("DISPLAY-DOLLAR", re.compile(r"\$\$.+?\$\$")),
    ("INLINE-DOLLAR", re.compile(r"(?<![\\$\w])\$(?![\s$])([^$\n]{1,300}?)(?<![\s\\])\$(?![\w$])")),
    ("PAREN-DELIM", re.compile(r"\\\(.+?\\\)")),
    ("BRACKET-DELIM", re.compile(r"\\\[|\\\]")),
    ("ENVIRONMENT", re.compile(r"\\(begin|end)\{" + MATH_ENVS + r"\*?\}")),
    ("LATEX-COMMAND", re.compile(r"\\(" + LATEX_CMDS + r")(?![A-Za-z])")),
    ("UNICODE-MATH", re.compile("[\u2200-\u22FF\u27C0-\u27EF\u2980-\u2AFF\u2115\u2124\u211A\u211D\u2102"
                                "\U0001D400-\U0001D7FF]")),
]
DEFINITION_CUES = [
    re.compile(r"\bis defined as\b|\bis called\b|\bwe (call|define|say that)\b|\bdenotes?\b|\brefers? to\b|"
               r"\bstands? for\b|\bby [^.\n]{1,60}? we mean\b|:=|≔|\\coloneqq|\\triangleq", re.IGNORECASE),
    re.compile(r"^\s*(let|suppose)\s+\S.{0,80}?\bbe\b", re.IGNORECASE),
    re.compile(r"^\s*#+\s*(\S+\s+)?(definition|theorem|lemma|proposition|corollary|axiom|conjecture|hypothesis)\b",
               re.IGNORECASE),
    re.compile(r"\*\*\s*(definition|theorem|lemma|proposition|corollary|axiom)\b", re.IGNORECASE),
    re.compile(r"\*\*[^*\n]{1,80}\*\*\s*(is|are|means|:)\s", re.IGNORECASE),
    re.compile(r"\b(is|are) not\b[^.;\n]{0,160}[.;]\s*(it|they|this) (is|are)\b", re.IGNORECASE),   # negative definition
    re.compile(r"^\s*(the\s+|a\s+|an\s+)?(\*\*)?[A-Z][\w'-]*(\s[A-Z][\w'-]*){0,3}(\*\*)?\s+(is|are)\s+(not\s+)?\*\*"),
    re.compile(r"^\s*(\*\*)?[A-Z][\w'-]*(\s[\w'-]+){0,3}(\*\*)?\s+—\s+(the|a|an)\s", re.IGNORECASE),
]


def math_markers(t):
    out = []
    for name, rx in MATH_MARKERS:
        for m in rx.finditer(t):
            if name == "INLINE-DOLLAR" and re.fullmatch(r"[\d.,\s]+", m.group(1) or ""):
                continue                                           # currency such as $5
            out.append(name)
            break
    return out


def definition_cue(t):
    return any(rx.search(t) for rx in DEFINITION_CUES)


def units_of(text):
    """Deterministic content units (C02 §2, v1.2): each non-blank line is a unit, except ```/~~~ fenced code, indented
    code (≥ 4 spaces or a tab after a blank line, not a list continuation), `$$`-display blocks, `\\[ … \\]` and
    `\\begin{env} … \\end{env}` math blocks, which are one unit each. Markup-only lines are MARKUP (no coverage)."""
    lines, pos, spans = text.split("\n"), 0, []
    for ln in lines:
        spans.append((pos, pos + len(ln)))
        pos += len(ln) + 1
    pages = C.pages_of(text)

    def page_of(ch):
        for k, (a, b) in enumerate(pages, 1):
            if a <= ch < b or (ch == b == len(text)):
                return k
        return len(pages)

    out, i, prev_blank, prev_kind = [], 0, True, None
    while i < len(lines):
        raw, s = lines[i], lines[i].strip()
        if not s:
            i, prev_blank = i + 1, True
            continue
        j, kind = i, None
        fence = re.match(r"(```+|~~~+)", s)
        if fence:
            kind, tok, j = "CODE", fence.group(1)[:3], i + 1
            while j < len(lines) and not lines[j].strip().startswith(tok):
                j += 1
        elif (raw.startswith("    ") or raw.startswith("\t")) and prev_blank and prev_kind != "LIST-ITEM" \
                and not re.match(r"([-*+]|\d+\.)\s", s):
            kind = "CODE"
            while j + 1 < len(lines) and (lines[j + 1].startswith("    ") or lines[j + 1].startswith("\t")):
                j += 1
        elif s == "$$" or (s.startswith("$$") and s.count("$$") == 1):
            kind, j = "MATH", i + 1
            while j < len(lines) and "$$" not in lines[j]:
                j += 1
        elif s == "\\[" or (s.startswith("\\[") and "\\]" not in s):
            kind, j = "MATH", i + 1
            while j < len(lines) and "\\]" not in lines[j]:
                j += 1
        elif re.match(r"\\begin\{" + MATH_ENVS + r"\*?\}", s) and not re.search(r"\\end\{", s):
            env = re.match(r"\\begin\{([^}]*)\}", s).group(1)
            kind, j = "MATH", i + 1
            while j < len(lines) and ("\\end{" + env + "}") not in lines[j]:
                j += 1
        elif MARKUP_ONLY.fullmatch(raw):
            kind = "MARKUP"
        elif s.startswith("#"):
            kind = "HEADING"
        elif s.startswith("|"):
            kind = "TABLE-ROW"
        elif re.match(r"([-*+]|\d+\.)\s", s):
            kind = "LIST-ITEM"
        elif s.startswith(">"):
            kind = "QUOTE"
        else:
            kind = "LINE"
        j = min(j, len(lines) - 1)
        a, b = spans[i][0], spans[j][1]
        t = text[a:b]
        mk = math_markers(t)
        out.append({"unit_id": f"U{len(out) + 1:04d}", "kind": kind, "char_start": a, "char_end": b,
                    "page_start": page_of(a), "page_end": page_of(max(a, b - 1)),
                    "text_sha256": C.sha256_bytes(t.encode("utf-8")),
                    "definition_cue": kind not in ("CODE", "MARKUP") and definition_cue(t),
                    "math_bearing": kind == "MATH" or (kind not in ("CODE", "MARKUP") and bool(mk)),
                    "math_markers": mk if kind != "CODE" else [], "preview": t[:120]})
        prev_blank, prev_kind, i = False, kind, j + 1
    return out


# ---- content inventory (C02, C03; F-03, F-04, F-05, F-14, F-19) ---------------------------------------------------
def _consecutive(ids, order):
    idx = sorted(order[u] for u in ids)
    return all(b - a == 1 for a, b in zip(idx, idx[1:]))


def _overlaps(q, unit_text):
    """A quote represents a unit when it lies inside the unit, or the whole unit lies inside the quote."""
    return bool(norm(q)) and (norm(q) in norm(unit_text) or norm(unit_text) in norm(q))


def check_inventory(fid):
    """CONTENT-EXTRACTED gate (v1.2)."""
    f = []
    row = C.manifest()[fid]
    text = C.content_text(row)
    ulist = units_of(text)
    units = {u["unit_id"]: u for u in ulist}
    need_ids = [u["unit_id"] for u in ulist if u["kind"] != "MARKUP"]
    order = {u: k for k, u in enumerate(need_ids)}
    stored = jsonl_or_error(ledger_path(fid, "UNITS.jsonl"), f)
    if stored is not None and stored != ulist:
        f.append("UNITS.jsonl differs from the deterministic segmentation of the current bytes (re-run f_units.py)")
    items = jsonl_or_error(ledger_path(fid, "CONTENT-INVENTORY.jsonl"), f) or []
    disp = jsonl_or_error(ledger_path(fid, "UNIT-DISPOSITIONS.jsonl"), f) or []
    cats = load_json(ledger_path(fid, "CATEGORY-CHECK.json"))
    if cats is None:
        f.append("MISSING or invalid CATEGORY-CHECK.json")
    utext = {k: text[u["char_start"]:u["char_end"]] for k, u in units.items()}
    by_unit = collections.defaultdict(list)
    ids = set()
    for n, it in enumerate(items, 1):
        tag = f"item {n} ({it.get('item_id')})"
        if not re.fullmatch(rf"FCI-{fid}-\d{{4}}", str(it.get("item_id", ""))) or it["item_id"] in ids:
            f.append(f"{tag}: item_id must be unique FCI-{fid}-NNNN")
        ids.add(it.get("item_id"))
        cat = it.get("category")
        if cat not in INVENTORY_CATEGORIES:
            f.append(f"{tag}: category {cat!r} not in the closed list")
        cu = it.get("covers_units") or []
        if not cu or any(u not in order for u in cu):
            f.append(f"{tag}: covers_units empty, unknown or markup")
            continue
        if len(cu) > MAX_ITEM_UNITS or not _consecutive(cu, order):
            f.append(f"{tag}: an item covers ≤ {MAX_ITEM_UNITS} consecutive units (F-03 anti catch-all)")
        span = text[min(units[u]["char_start"] for u in cu):max(units[u]["char_end"] for u in cu)]
        q = it.get("verbatim_quote", "")
        if not quote_in(q, span):
            f.append(f"{tag}: verbatim_quote is not a contiguous quote inside its covered units (F-03)")
        if len(norm(q)) < MIN_ITEM_QUOTE and not any(norm(q) == norm(utext[u]) for u in cu):
            f.append(f"{tag}: verbatim_quote shorter than {MIN_ITEM_QUOTE} chars and not a whole unit (F-03)")
        c_ = norm(it.get("content"))
        if len(c_) < MIN_CONTENT or c_ == norm(q):
            f.append(f"{tag}: content must be an extraction of ≥ {MIN_CONTENT} chars, not the quote itself (F-03)")
        if it.get("page") not in {p for u in cu for p in range(units[u]["page_start"], units[u]["page_end"] + 1)}:
            f.append(f"{tag}: page does not match its units")
        hits = s_lane_hits(it.get("content"), it.get("context"), it.get("gloss"), " ".join(map(str, it.get("qualifications") or [])))
        if hits:
            f.append(f"{tag}: S-lane identifiers in analyst-authored fields {hits} (F-14, C15)")
        if cat == "DEFINITION":
            for k in ("term", "source_definition", "context"):
                if not (it.get(k) or "").strip():
                    f.append(f"{tag}: DEFINITION needs {k}")
            if it.get("source_definition") and not quote_in(it["source_definition"], span):
                f.append(f"{tag}: source_definition is not verbatim inside the covered units")
            for k in ("related_terms", "examples", "qualifications"):
                if not isinstance(it.get(k), list):
                    f.append(f"{tag}: DEFINITION needs {k}[] (may be empty)")
            if it.get("definition_form") not in DEFINITION_FORMS:
                f.append(f"{tag}: DEFINITION needs definition_form in {sorted(DEFINITION_FORMS)} (C03)")
        if cat == "TERM" and (not (it.get("term") or "").strip() or not isinstance(it.get("notation"), list)
                              or "gloss" not in it):
            f.append(f"{tag}: TERM needs term, notation[] and gloss (null if the source gives none) (C03 §2)")
        if cat == "THEOREM":
            if it.get("source_status") not in SOURCE_STATUS:
                f.append(f"{tag}: THEOREM needs source_status in {sorted(SOURCE_STATUS)} (RN-06)")
            if not quote_in(it.get("statement", ""), span):
                f.append(f"{tag}: THEOREM statement must be verbatim inside the covered units")
        if cat in ("FORMULA", "NOTATION") and not quote_in(it.get("latex", ""), span):
            f.append(f"{tag}: {cat} needs latex, verbatim inside the covered units (C02 §4)")
        if cat in ("RELATIONSHIP", "DEPENDENCY") and not all((it.get(k) or "").strip() for k in ("from", "to", "relation")):
            f.append(f"{tag}: {cat} needs from, to, relation (C02 §4)")
        if cat == "REFERENCE" and (it.get("ref_kind") not in REF_KINDS or not (it.get("target") or "").strip()):
            f.append(f"{tag}: REFERENCE needs target and ref_kind")
        if cat == "CONTRADICTION-OR-TENSION":
            sf = it.get("source_framing_quote", "")
            if not quote_in(sf, span) or not FRAMING.search(sf or ""):
                f.append(f"{tag}: CONTRADICTION-OR-TENSION needs source_framing_quote (verbatim, with the source's own "
                         f"framing word); an analyst-found tension is Level 2 INTERNAL-TENSION (C07)")
        for u in cu:
            by_unit[u].append(it)
    released = collections.defaultdict(set)
    dispositioned = {}
    for d in disp:
        us, kind, reason = d.get("unit_ids") or [], d.get("disposition"), norm(d.get("reason"))
        if kind not in DISPOSITIONS | RELEASES or len(reason) < MIN_CONTENT or not us:
            f.append(f"disposition {d}: needs disposition in {sorted(DISPOSITIONS | RELEASES)}, unit_ids and a reason")
            continue
        for u in us:
            if u not in order:
                f.append(f"disposition names unknown or markup unit {u}")
                continue
            if kind in RELEASES:
                released[u].add(kind)
                continue
            x = units[u]
            if x["kind"] in ("CODE", "MATH") or x["math_bearing"] or x["definition_cue"]:
                f.append(f"{u} ({x['kind']}{', math' if x['math_bearing'] else ''}{', definition cue' if x['definition_cue'] else ''})"
                         f": must be covered by an item, never dispositioned (F-04, F-05)")
            if kind == "RESTATES-UNIT" and d.get("restates_unit") not in by_unit:
                f.append(f"{u}: RESTATES-UNIT must point to a unit covered by an item")
            dispositioned[u] = kind
    missing, unquoted, cue_bad, math_bad, code_bad = [], [], [], [], []
    for u in need_ids:
        its, x = by_unit.get(u, []), units[u]
        if not its:
            if u not in dispositioned:
                missing.append(u)
            continue
        if not any(_overlaps(it.get("verbatim_quote", ""), utext[u]) for it in its):
            unquoted.append(u)
        if x["definition_cue"] and "NOT-A-DEFINITION" not in released[u] and not any(
                it.get("category") in DEF_CATS and _overlaps(it.get("verbatim_quote", ""), utext[u]) for it in its):
            cue_bad.append(u)
        if x["math_bearing"] and "NOT-MATHEMATICAL" not in released[u] and not any(
                it.get("category") in MATH_CATS and _overlaps(it.get("latex") or it.get("statement") or "", utext[u])
                for it in its):
            math_bad.append(u)
        if x["kind"] == "CODE" and not any(it.get("category") in CODE_CATS for it in its):
            code_bad.append(u)
    for label, lst in (("neither covered nor dispositioned", missing),
                       ("covered but not quoted inside the unit by any covering item (F-03)", unquoted),
                       ("definition-cue unit(s) without a DEFINITION/TERM item quoting inside them, or a NOT-A-DEFINITION release (F-05)", cue_bad),
                       ("math-bearing unit(s) without a FORMULA/NOTATION/THEOREM item whose latex lies in them, or a NOT-MATHEMATICAL release (F-04)", math_bad),
                       ("code unit(s) without an item in " + "/".join(sorted(CODE_CATS)) + " (F-04)", code_bad)):
        if lst:
            f.append(f"{len(lst)} unit(s) {label}: {lst[:15]}")
    counts = collections.Counter(it.get("category") for it in items)
    if cats is not None:
        texts = []
        for c in INVENTORY_CATEGORIES:
            e = cats.get(c)
            if not isinstance(e, dict) or e.get("count") != counts.get(c, 0) or len(norm(e.get("checked"))) < MIN_ITEM_QUOTE:
                f.append(f"CATEGORY-CHECK {c}: needs {{count: {counts.get(c, 0)}, checked: '<≥ {MIN_ITEM_QUOTE} chars: how "
                         f"the whole file was checked>'}} (F-19)")
            else:
                texts.append(norm(e["checked"]))
        if len(texts) == len(INVENTORY_CATEGORIES) and len(set(texts)) == 1:
            f.append("CATEGORY-CHECK: the same 'checked' text for all 26 categories is not a check (F-19)")
    stats = {"units": len(ulist), "markup": len(ulist) - len(need_ids), "items": len(items),
             "covered": sum(1 for u in need_ids if by_unit.get(u)), "dispositioned": len(dispositioned),
             "no_substantive": sum(1 for v in dispositioned.values() if v == "NO-SUBSTANTIVE-CONTENT"),
             "math_bearing": sum(1 for u in need_ids if units[u]["math_bearing"]),
             "definition_cue": sum(1 for u in need_ids if units[u]["definition_cue"]),
             "code": sum(1 for u in need_ids if units[u]["kind"] == "CODE"),
             "releases": {k: sum(1 for s in released.values() if k in s) for k in sorted(RELEASES)},
             "items_by_category": dict(sorted(counts.items()))}
    return not f, f, stats


def check_isolation_attestation(fid, run, role="EXTRACTOR", name="ISOLATION-ATTESTATION.json"):
    """C15 (F-02): the extractor's self-attestation. It records what was received and read; it cannot prove it."""
    f = []
    a = load_json(ledger_path(fid, name))
    if not a:
        return False, [f"MISSING or invalid {name} (C15)"]
    if a.get("run_id") != run or a.get("role") != role or not (a.get("model_id") or "").strip():
        f.append(f"{name}: run_id/role/model_id must be {run}/{role}/<model>")
    common = [rf"^{re.escape(C.FDIR_REL)}/prompts/", rf"^{re.escape(C.FDIR_REL)}/scripts/", rf"^reader:{fid}$",
              rf"^scratch:{role.lower()}-{fid}$"]
    own = {"EXTRACTOR": [rf"^{re.escape(C.FDIR_REL)}/ledger/{fid}/(UNITS|CONTENT-INVENTORY|UNIT-DISPOSITIONS|"
                         rf"PAGE-DIGESTS|READ-LOG)\.jsonl$", rf"^{re.escape(C.FDIR_REL)}/ledger/{fid}/"
                         rf"(CATEGORY-CHECK|ISOLATION-ATTESTATION)\.json$"],
           # the auditor never reads the extractor's L1 artifacts; the comparison is computed by f_compare_inventory
           "AUDITOR": [rf"^{re.escape(C.FDIR_REL)}/ledger/{fid}/(AUDITOR-[A-Z-]+|READ-LOG|UNITS)\.jsonl$",
                       rf"^{re.escape(C.FDIR_REL)}/ledger/{fid}/AUDITOR-ATTESTATION\.json$"]}.get(role, [])
    allowed = [re.compile(p) for p in common + own]
    for key in ("received", "read_paths"):
        if not isinstance(a.get(key), list) or not a[key]:
            f.append(f"{name}: {key}[] must list every item")
            continue
        bad = [p for p in a[key] if not any(rx.search(str(p)) for rx in allowed)]
        if bad:
            f.append(f"{name}: {key} outside the C15 allow-list: {bad[:5]}")
    if a.get("denied_material_consulted") is not False:
        f.append(f"{name}: denied_material_consulted must be false (C15 §3); if anything denied was seen, "
                 f"record it and stop — the run is not isolated")
    return not f, f


# ---- Phase 1 records (C04 §2) -------------------------------------------------------------------------------------
def check_reconstruction(fid):
    f = []
    row = C.manifest()[fid]
    text = C.content_text(row)
    files = jsonl_or_error(ledger_path(fid, "files.jsonl"), f)
    contribs = jsonl_or_error(ledger_path(fid, "contributions.jsonl"), f)
    props = jsonl_or_error(ledger_path(fid, "index-proposals.jsonl"), f)
    inv = {it.get("item_id"): it for it in C.read_jsonl(ledger_path(fid, "CONTENT-INVENTORY.jsonl"))}
    if files is not None:
        if len(files) != 1:
            f.append(f"files.jsonl has {len(files)} lines (need exactly 1)")
        for r in files:
            if r.get("f_id") != fid or r.get("content_sha256") != row["content_sha256"]:
                f.append("files.jsonl f_id/content_sha256 do not match the manifest")
            for k in ("summary", "contribution_assessment"):
                if not (r.get(k) or "").strip():
                    f.append(f"files.jsonl empty {k}")
            if r.get("provenance") not in PROVENANCE:
                f.append(f"files.jsonl provenance {r.get('provenance')!r}")
            if r.get("order_evidence") not in ORDER_EVIDENCE:
                f.append(f"files.jsonl order_evidence {r.get('order_evidence')!r}")
            if r.get("content_identical_to_s") is not bool(row.get("content_equals_s_sources")):
                f.append("files.jsonl content_identical_to_s must equal bool(manifest content_equals_s_sources) (RL-04)")
            if r.get("status") != "CONTENT":
                f.append(f"files.jsonl status {r.get('status')!r} (a read file is CONTENT)")
            if not isinstance(r.get("objects_touched"), list) or not isinstance(r.get("explicit_dates"), list):
                f.append("files.jsonl objects_touched/explicit_dates must be lists")
            hits = s_lane_hits(r.get("summary"), r.get("contribution_assessment"), " ".join(r.get("objects_touched") or []))
            if hits:
                f.append(f"files.jsonl: S-lane identifiers {hits} (F-14)")
    if contribs is not None:
        if not contribs:
            f.append("contributions.jsonl is empty (a CONTENT file records at least one contribution; §C-4)")
        refd = set()
        for i, c in enumerate(contribs, 1):
            tag = f"contribution {i}"
            if c.get("f_id") != fid:
                f.append(f"{tag}: f_id {c.get('f_id')!r}")
            if not c.get("types") or not set(c["types"]) <= TYPES:
                f.append(f"{tag}: types {c.get('types')} outside the closed list")
            if c.get("scope") not in SCOPES:
                f.append(f"{tag}: scope {c.get('scope')!r}")
            if not quote_in(c.get("anchor", ""), text):
                f.append(f"{tag}: anchor not found verbatim in the file")
            if len(c.get("anchor", "")) > 300:
                f.append(f"{tag}: anchor longer than 300 chars")
            if c.get("completeness") not in COMPLETENESS:
                f.append(f"{tag}: completeness {c.get('completeness')!r}")
            if c.get("review_flag") not in REVIEW_FLAGS:
                f.append(f"{tag}: review_flag {c.get('review_flag')!r}")
            if c.get("version_ref") not in VERSION_REF:
                f.append(f"{tag}: version_ref {c.get('version_ref')!r}")
            for a in c.get("assumptions") or []:
                if a.get("stated") not in ("EXPLICIT", "USED-UNSTATED") or not a.get("anchor"):
                    f.append(f"{tag}: assumption without stated/anchor")
            for lc in c.get("lineage_claims") or []:
                if lc.get("kind") not in LINEAGE or not quote_in(lc.get("quote", ""), text):
                    f.append(f"{tag}: lineage claim kind/quote invalid")
            refs = c.get("inventory_refs") or []
            if not refs or not set(refs) <= set(inv):
                f.append(f"{tag}: inventory_refs empty or unknown")
            elif len(refs) > MAX_CONTRIB_ITEMS:
                f.append(f"{tag}: carries {len(refs)} items; ≤ {MAX_CONTRIB_ITEMS} (F-03 anti catch-all)")
            elif not any(quote_in(c.get("anchor", ""), inv[r_].get("verbatim_quote", "")) or
                         quote_in(inv[r_].get("verbatim_quote", ""), c.get("anchor", "")) for r_ in refs):
                f.append(f"{tag}: anchor must coincide with (contain or lie in) the quote of one of its inventory items")
            hits = s_lane_hits(c.get("statement"), " ".join(c.get("labels") or []))
            if hits:
                f.append(f"{tag}: S-lane identifiers in statement/labels {hits} (F-14)")
            refd |= set(refs)
        lost = sorted(set(inv) - refd)
        if lost:
            f.append(f"{len(lost)} inventory item(s) carried by no contribution (content lost in reconstruction): {lost[:15]}")
    if props is not None:
        for p in props:
            if not p.get("working_label") or p.get("first_seen_in_f") != fid:
                f.append("index-proposals: working_label / first_seen_in_f invalid")
    return not f, f


# ---- Level 2: analysis (C04–C09; F-09, F-14, F-19) ----------------------------------------------------------------
LENS_STATUS = {"APPLIED", "NOT-APPLICABLE", "DEFERRED-TO-CHECKPOINT"}
ANALYSIS_KINDS = {"OBSERVATION", "STRUCTURE", "GAP", "INTERNAL-TENSION", "CORRECTNESS-FINDING", "SCHEMA-LIMITATION",
                  "METHODOLOGICAL-DEFICIENCY"}
ANALYTICAL_STATUS = {"INTERNALLY-CONSISTENT": "INTERNAL", "INTERNALLY-INCONSISTENT": "INTERNAL",
                     "EXTERNALLY-VERIFIED": "EXTERNAL", "EXTERNALLY-CONTRADICTED": "EXTERNAL",
                     "UNRESOLVED": "UNRESOLVED"}                                  # RN-04/05/06 → basis
CROSS_DISPOSITIONS = {"RELATED", "NOT-RELATED-AFTER-INSPECTION", "UNDETERMINED"}
RELATION_HINTS = {"SAME", "REFINEMENT", "EXTENSION", "REDEFINITION", "REPLACEMENT", "SPECIALIZATION", "DERIVED-FROM",
                  "CONTINUATION", "HOMONYM", "CONTRADICTION", "ANALOGY", "UNWITNESSED"}   # v3.5 A11 set + 2; a hint only


def normalize_term(t):
    return re.sub(r"[^a-z0-9α-ωа-я]+", "-", (t or "").lower()).strip("-")


def term_keys(fid):
    """Mechanical term keys of one F-ID: inventory TERM/DEFINITION terms + contribution labels (normalized)."""
    keys = collections.defaultdict(set)
    for it in C.read_jsonl(ledger_path(fid, "CONTENT-INVENTORY.jsonl")):
        if it.get("term"):
            keys[normalize_term(it["term"])].add(it["item_id"])
    for c in C.read_jsonl(ledger_path(fid, "contributions.jsonl")):
        for l in c.get("labels") or []:
            keys[normalize_term(l)].update(c.get("inventory_refs") or [])
    keys.pop("", None)
    return keys


def cross_file_candidates(fid):
    """Deterministic candidates: shared normalized term keys with every EARLIER, AUDITED text F-ID (processing order).
    v1.2: hashed ids (F-19); CONTENT-IDENTICAL-TO-S flags of both sides carried (F-14, RN-08)."""
    man = C.manifest()
    mine = term_keys(fid)
    out = []
    for other, r in man.items():
        if r["list_order"] >= man[fid]["list_order"]:
            break
        if C.current_state(other) != "AUDITED" or not os.path.exists(ledger_path(other, "CONTENT-INVENTORY.jsonl")):
            continue
        theirs = term_keys(other)
        for k in sorted(set(mine) & set(theirs)):
            out.append({"candidate_id": f"FXC-{fid}-{other}-" + hashlib.sha1(k.encode("utf-8")).hexdigest()[:12],
                        "key": k, "this_items": sorted(mine[k]), "other_f": other, "other_items": sorted(theirs[k]),
                        "this_identical_to_s": bool(man[fid].get("content_equals_s_sources")),
                        "other_identical_to_s": bool(r.get("content_equals_s_sources"))})
    return out


def check_analysis(fid):
    f = []
    ck = load_json(ledger_path(fid, "ANALYSIS-CHECKLIST.json"))
    recs = jsonl_or_error(ledger_path(fid, "ANALYSIS.jsonl"), f) or []
    inv = {it["item_id"] for it in C.read_jsonl(ledger_path(fid, "CONTENT-INVENTORY.jsonl"))}
    lens_used, ids = collections.Counter(), set()
    for i, r in enumerate(recs, 1):
        tag = f"analysis {i} ({r.get('an_id')})"
        if not re.fullmatch(rf"FAN-{fid}-\d{{3}}", str(r.get("an_id", ""))) or r["an_id"] in ids:
            f.append(f"{tag}: an_id must be unique FAN-{fid}-NNN")
        ids.add(r.get("an_id"))
        k, ec = r.get("kind"), r.get("epistemic_class")
        if r.get("level") != 2 or k not in ANALYSIS_KINDS or ec not in EPISTEMIC_B:
            f.append(f"{tag}: level 2, kind in {sorted(ANALYSIS_KINDS)}, layer-B epistemic class required")
        if r.get("lens") not in LENS_KEYS:
            f.append(f"{tag}: lens {r.get('lens')!r} not in {LENS_KEYS}")
        lens_used[r.get("lens")] += 1
        refs = r.get("inventory_refs") or []
        if not refs or not set(refs) <= inv:
            f.append(f"{tag}: inventory_refs empty or not items of this file")
        for key in ("statement", "reasoning", "run_id", "model_id"):
            if not norm(r.get(key)):
                f.append(f"{tag}: missing {key} (F-09: every Level-2 record states its reasoning)")
        if not ISO_UTC.fullmatch(str(r.get("research_time", ""))):
            f.append(f"{tag}: research_time must be ISO-8601 UTC (F-19)")
        if prescriptive(r.get("statement")) or prescriptive(r.get("reasoning")):
            f.append(f"{tag}: prescriptive / KnowledgeOS-design wording outside quotation marks — Level 2 describes; "
                     f"a proposal is Level 3 (research.jsonl, STRUCTURE-CANDIDATE or SUGGESTION-*) (F-09, RN-03, RN-07)")
        if ec == "DOMAIN-INTERPRETATION" and not norm(r.get("interpretation_frame")):
            f.append(f"{tag}: DOMAIN-INTERPRETATION names its interpretation_frame (e.g. 'category theory', 'DDD')")
        if ec == "EXTERNAL-THEORY-COMPARISON" and not norm(r.get("external_source")):
            f.append(f"{tag}: EXTERNAL-THEORY-COMPARISON names its external_source (P3B §13.12)")
        if r.get("lens") == "DDD" and k == "STRUCTURE" and r.get("ddd_claim_type") != "SOURCE-DESCRIBED":
            f.append(f"{tag}: a Level-2 DDD STRUCTURE is ddd_claim_type SOURCE-DESCRIBED; a design for KnowledgeOS is "
                     f"Level 3 (C08)")
        if k == "CORRECTNESS-FINDING":
            st, basis = r.get("analytical_status"), r.get("basis")
            if st not in ANALYTICAL_STATUS or basis != ANALYTICAL_STATUS.get(st):
                f.append(f"{tag}: CORRECTNESS-FINDING needs analytical_status in {sorted(ANALYTICAL_STATUS)} with the "
                         f"matching basis INTERNAL/EXTERNAL/UNRESOLVED (C06 v1.2)")
            if basis == "EXTERNAL" and not norm(r.get("external_source")):
                f.append(f"{tag}: an EXTERNAL basis names external_source (C06 v1.2)")
        hits = s_lane_hits(r.get("statement"), r.get("reasoning"))
        if hits:
            f.append(f"{tag}: S-lane identifiers {hits} (F-14)")
    if ck is None:
        f.append("MISSING or invalid ANALYSIS-CHECKLIST.json")
    else:
        for L in LENS_KEYS:
            e = ck.get(L)
            if not isinstance(e, dict) or e.get("status") not in LENS_STATUS or len(norm(e.get("reason"))) < MIN_CONTENT:
                f.append(f"ANALYSIS-CHECKLIST {L}: needs status in {sorted(LENS_STATUS)} and a reason (≥ {MIN_CONTENT} chars)")
                continue
            if e["status"] == "APPLIED" and not lens_used[L]:
                f.append(f"ANALYSIS-CHECKLIST {L}: APPLIED but no analysis record uses this lens")
            if e["status"] != "APPLIED" and lens_used[L]:
                f.append(f"ANALYSIS-CHECKLIST {L}: records use this lens but status is {e['status']}")
            if e["status"] == "DEFERRED-TO-CHECKPOINT" and L != "STATISTICAL-ML":
                f.append(f"ANALYSIS-CHECKLIST {L}: only STATISTICAL-ML may be deferred to a checkpoint (C09)")
    cands = cross_file_candidates(fid)
    disp = {d.get("candidate_id"): d for d in (jsonl_or_error(ledger_path(fid, "CROSS-FILE.jsonl"), f) or [])}
    man = C.manifest()
    for cd in cands:
        d = disp.get(cd["candidate_id"])
        if not d:
            f.append(f"cross-file candidate {cd['candidate_id']} ({cd['key']}) not dispositioned")
            continue
        if d.get("disposition") not in CROSS_DISPOSITIONS or len(norm(d.get("basis"))) < MIN_CONTENT:
            f.append(f"{cd['candidate_id']}: disposition in {sorted(CROSS_DISPOSITIONS)} and a basis required")
        if prescriptive(d.get("basis")):
            f.append(f"{cd['candidate_id']}: prescriptive wording in a Level-2 basis (F-09)")
        if d.get("disposition") == "RELATED":
            if d.get("relation_hint") not in RELATION_HINTS:
                f.append(f"{cd['candidate_id']}: RELATED needs relation_hint in {sorted(RELATION_HINTS)}")
            try:
                if not quote_in(d.get("this_quote", ""), C.content_text(man[fid])) or \
                        not any(quote_in(d.get("other_quote", ""), q) for q in recorded_quotes(cd["other_f"])):
                    f.append(f"{cd['candidate_id']}: this_quote must be in this file and other_quote in the other "
                             f"file's audited ledger")
            except RuntimeError as ex:
                f.append(str(ex))
    extra = set(disp) - {cd["candidate_id"] for cd in cands}
    if extra:
        f.append(f"CROSS-FILE.jsonl has records for unknown candidates: {sorted(extra)[:5]}")
    stats = {"analysis_records": len(recs), "lenses": {L: (ck or {}).get(L, {}).get("status") for L in LENS_KEYS},
             "cross_file_candidates": len(cands),
             "related": sum(1 for d in disp.values() if d.get("disposition") == "RELATED")}
    return not f, f, stats


# ---- Level 3: per-file research (C10; F-10, F-11, F-12, F-15, F-19) -----------------------------------------------
def recorded_quotes(fid):
    """Quotes an F-ID's Level-1 ledger holds: contribution anchors and inventory quotes/definitions (§C-5.3). Research
    quotes do not count — a record can never become its own evidence (v1.2)."""
    qs = [c.get("anchor", "") for c in C.read_jsonl(ledger_path(fid, "contributions.jsonl"))]
    for it in C.read_jsonl(ledger_path(fid, "CONTENT-INVENTORY.jsonl")):
        qs += [it.get("verbatim_quote", ""), it.get("source_definition", "")]
    return [q for q in qs if q]


def _strings(obj):
    if isinstance(obj, dict):
        for v in obj.values():
            yield from _strings(v)
    elif isinstance(obj, list):
        for v in obj:
            yield from _strings(v)
    elif isinstance(obj, str):
        yield obj


def _keys(obj):
    if isinstance(obj, dict):
        for k, v in obj.items():
            yield k
            yield from _keys(v)
    elif isinstance(obj, list):
        for v in obj:
            yield from _keys(v)


def check_research(fid, allow_empty_reason=None):
    import f_integrity as I
    f = []
    man = C.manifest()
    order = {k: r["list_order"] for k, r in man.items()}
    recs = jsonl_or_error(ledger_path(fid, "research.jsonl"), f)
    if recs is None:
        return False, f
    if not recs and not allow_empty_reason:
        f.append("research.jsonl is empty and no reason was given (§C-5.4)")
    an_ids = {a.get("an_id") for a in C.read_jsonl(ledger_path(fid, "ANALYSIS.jsonl"))}
    inv_ids = {it.get("item_id") for it in C.read_jsonl(ledger_path(fid, "CONTENT-INVENTORY.jsonl"))}
    own_quotes = recorded_quotes(fid)
    runbook = os.path.join(C.REPO, C.CONTRACT_REL)
    runbook_sha = C.sha256_file(runbook) if os.path.exists(runbook) else None
    temporal, multiplicity = I.decision("temporal_semantics"), I.decision("multiplicity_rules")
    ids = set()
    for i, r in enumerate(recs, 1):
        tag = f"research {i} ({r.get('rs_id')})"
        if not re.fullmatch(rf"FRS-{fid}-\d{{3}}", str(r.get("rs_id", ""))) or r["rs_id"] in ids:
            f.append(f"{tag}: rs_id must be unique FRS-{fid}-NNN")
        ids.add(r.get("rs_id"))
        k, ec, layer = r.get("kind"), r.get("epistemic_class"), r.get("output_layer")
        if k in KINDS_B:
            f.append(f"{tag}: kind {k} is Level 2 — record it in ANALYSIS.jsonl, not research.jsonl")
        elif k in KINDS_C:
            if layer != "C" or ec not in EPISTEMIC_C_PER_FILE or r.get("level") != 3:
                f.append(f"{tag}: kind {k} requires level 3, layer C and epistemic class in "
                         f"{sorted(EPISTEMIC_C_PER_FILE)} (THEORY-CANDIDATE exists only at checkpoints, F-10)")
        else:
            f.append(f"{tag}: kind {k!r} outside the closed list")
        if r.get("scale") not in ("OBJECT", "CROSS-OBJECT"):
            f.append(f"{tag}: a per-file record has scale OBJECT or CROSS-OBJECT; CORPUS scale exists only at "
                     f"checkpoints (F-10)")
        bad_keys = sorted(set(_keys(r)) & OUTCOME_KEYS)
        pr = r.get("preregistration") if isinstance(r.get("preregistration"), dict) else {}
        vocab_ok = pr.get("outcome_vocabulary") == OUTCOMES
        stray = [s for s in _strings({kk: vv for kk, vv in r.items() if kk != "preregistration"}) if s in OUTCOMES]
        if bad_keys or stray:
            f.append(f"{tag}: no test outcome in a per-file record (keys {bad_keys}, values {stray}); tests run only "
                     f"at checkpoints (C11, F-10)")
        if temporal is None and any(re.search(r"out[- ]of[- ]sample|held[- ]out|hold[- ]?out|prospective", s, re.I)
                                    for s in _strings(r) if s not in ("PENDING-HDR-2",)):
            f.append(f"{tag}: temporal hold-out language used while HDR-2 (processing vs historical time) is "
                     f"undecided (F-12)")
        a_refs, i_refs = r.get("analysis_refs"), r.get("inventory_refs") or []
        if not isinstance(a_refs, list) or not set(a_refs) <= an_ids or not set(i_refs) <= inv_ids or \
                not (a_refs or i_refs):
            f.append(f"{tag}: needs ≥ 1 analysis_refs (this file's Level-2 ids) or inventory_refs (C10 §2, F-10)")
        if r.get("lens") not in LENS_ALL:
            f.append(f"{tag}: lens {r.get('lens')!r} not in {sorted(LENS_ALL)} (F-19)")
        if not norm(r.get("statement")):
            f.append(f"{tag}: empty statement")
        for key in ("run_id", "model_id"):
            if not r.get(key):
                f.append(f"{tag}: missing {key}")
        if not ISO_UTC.fullmatch(str(r.get("research_time", ""))):
            f.append(f"{tag}: research_time must be ISO-8601 UTC (F-19)")
        if r.get("contract_sha256") != runbook_sha:
            f.append(f"{tag}: contract_sha256 must be the sha256 of the runbook in force (F-19)")
        ev = r.get("supporting_evidence") or []
        if not ev:
            f.append(f"{tag}: no supporting_evidence")
        for e in ev:
            src = e.get("f_id")
            if S_ID.fullmatch(src or ""):
                f.append(f"{tag}: S-Series id {src} cited as evidence (S contamination, §F-3)")
                continue
            if src not in man:
                f.append(f"{tag}: evidence f_id {src!r} unknown")
                continue
            if src == fid:
                if not any(quote_in(e.get("quote", ""), q) for q in own_quotes):
                    f.append(f"{tag}: a same-file quote must be one this file's L1 ledger records (F-10)")
            elif order[src] >= order[fid]:
                f.append(f"{tag}: evidence {src} is not earlier in processing order (no look-ahead, §F-2)")
            elif C.current_state(src) != "AUDITED":
                f.append(f"{tag}: evidence {src} is not AUDITED")
            elif not any(quote_in(e.get("quote", ""), q) for q in recorded_quotes(src)):
                f.append(f"{tag}: quote from {src} is not recorded in its audited ledger (§C-5.3)")
        if k in ("HYPOTHESIS", "STRUCTURE-CANDIDATE"):
            if temporal is None or multiplicity is None:
                f.append(f"{tag}: HUMAN DECISION REQUIRED — hypothesis registration is blocked until HDR-2 (temporal "
                         f"semantics) and HDR-3 (multiplicity rules) are recorded in F-DECISIONS.json (F-12, F-15)")
            for key in ("falsification_condition", "validation_question"):
                if not norm(r.get(key)):
                    f.append(f"{tag}: {k} needs {key}")
            if not isinstance(r.get("competing_hypotheses"), list) or not r["competing_hypotheses"]:
                f.append(f"{tag}: {k} needs competing_hypotheses[]")
            ce = r.get("contradicting_evidence")
            if not isinstance(ce, list) or any(not isinstance(x, dict) or x.get("f_id") != fid or
                                               not any(quote_in(x.get("quote", ""), q) for q in own_quotes) for x in ce):
                f.append(f"{tag}: contradicting_evidence[] = [{{f_id, quote}}] from this file's L1 ledger (may be empty)")
            dp = r.get("disconfirmation_plan")
            if not isinstance(dp, dict) or not all(norm(str(dp.get(x) or "")) for x in
                                                   ("population", "method", "completeness", "termination_bound", "where")) \
                    or dp.get("method") not in ("LEXICAL", "STRUCTURAL-ENUMERATION", "WHOLE-FILE-READING") \
                    or set(dp) & (OUTCOME_KEYS | {"performed", "findings"}):
                f.append(f"{tag}: disconfirmation_plan = {{population, method, completeness, termination_bound, where}} — "
                         f"planned, never performed per file (C10 v1.2, F-11)")
            if not pr:
                f.append(f"{tag}: {k} needs a preregistration block (C10)")
            else:
                for key in PREREG_KEYS:
                    if not norm(str(pr.get(key) or "")):
                        f.append(f"{tag}: preregistration.{key} missing")
                if not vocab_ok:
                    f.append(f"{tag}: preregistration.outcome_vocabulary must be {OUTCOMES}")
                if pr.get("registered_after_f") != fid:
                    f.append(f"{tag}: preregistration.registered_after_f must be {fid}")
                if temporal is not None and pr.get("holdout_basis") not in temporal.get("allowed_bases", []):
                    f.append(f"{tag}: preregistration.holdout_basis must be one of the decided bases (HDR-2)")
                if multiplicity is not None and pr.get("multiplicity_rule") not in multiplicity.get("allowed", []):
                    f.append(f"{tag}: preregistration.multiplicity_rule must be one of the decided rules (HDR-3)")
        hits = s_lane_hits(*_strings({kk: vv for kk, vv in r.items() if kk != "supporting_evidence"}))
        if hits:
            f.append(f"{tag}: S-lane identifiers {hits}; S conclusions are not imported (F-14)")
    return not f, f


# ---- isolation (§F-11) --------------------------------------------------------------------------------------------
def writes_outside_lane():
    """Paths changed since the bootstrap baseline that are outside the F-Series folder (recorded, attribution UNKNOWN)."""
    if C.TEST_ROOT:
        return []
    base = set(open(os.path.join(C.FDIR, "F-BASELINE-GIT-STATUS.txt"), encoding="utf-8").read().splitlines())
    now = subprocess.check_output(["git", "status", "--porcelain", "--untracked-files=all"], text=True,
                                  cwd=C.REPO).splitlines()
    return sorted(l for l in now if l not in base and C.FDIR_REL + "/" not in l)
