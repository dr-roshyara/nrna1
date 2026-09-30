#!/usr/bin/env python3
"""R7 ORCHESTRATOR TOOL (EG-1, G-LOG-0104; runbook: audit-p3b/20260928_S5-EXECUTION-PACKAGE-DRAFT.md §1).

Produces the artifacts the production verifier requires, reusing the production functions only (U.input_manifest,
U.slice_view, W.witness, W.witness_bytes, W.digests, p3b_s5_verify). It DISPATCHES NOTHING: dispatch is the orchestrator
session's Agent call, with the prompt this tool renders. It reads no corpus content except the integrity bytes the
witness needs (the same quarantine-aware resolver read the verifier performs), and it prints counts only.

  prepare B --emit DIR --dispatch-cwd DIR [--state P3B-STATE.json]  views + I(run) of the reading runs + one dispatch
                                                 prompt per run; --dispatch-cwd (REQUIRED) = the session's primary
                                                 working directory, which the dispatched agents inherit (F-5)
  archive B --session SESSION.jsonl [--archive DIR]  harness transcripts → <archive>/<B>/ (append-only refresh)
  freeze-units B L [--archive DIR]               the label's SYNTHESIS I(run) + the unit-validation witness freeze
  assemble B --date YYYY-MM-DD --model-id ID [--check]  the assembly <B>-R7 + the EMPTY objects (v2.8 §2.7, EG-5)
  freeze-final B [--archive DIR]                 WITNESS.jsonl + WITNESS-DIGESTS.json
  coverage B [--archive DIR]                     RI-1b SLICE-VIEW coverage vs R(run) (v2.8 §5.9a; report-only, counts)
  verify B [--archive DIR] [--out FILE]          p3b_s5_verify (contract revision 7)
  retire B --cause-class C --reason R [--archive DIR] [--staging DIR] [--state P3B-STATE.json]
                                                 EG-6 (v2.8 §2.8): retire the failed attempt m into <B>-R7.A<m>/

Every step validates before it writes; an existing artifact is never silently replaced (Refused).
`assemble` runs inside the `: S5-ORCH ASSEMBLED batch=B;` marker command (`--check` inside FINAL-VALIDATED); its
`<sha256>  <path>` lines become the marker's printed hashes in WITNESS.jsonl, which `freeze-final` re-checks.
"""
import collections
import copy
import datetime
import errno
import importlib.util
import json
import os
import re
import shutil
import sys
import tempfile

_HERE = os.path.dirname(os.path.abspath(__file__))
sys.path.insert(0, _HERE)


def _load(name):
    s = importlib.util.spec_from_file_location(name, os.path.join(_HERE, name + ".py"))
    m = importlib.util.module_from_spec(s)
    s.loader.exec_module(m)
    return m


import p3b_s5_ops_lib as ops                         # noqa: E402  (the shared common instance)

c = ops.load_common()
U = _load("p3b_s5_r7_universe")
W = _load("p3b_s5_r7_witness")
V = _load("p3b_s5_verify")
C = _load("p3b_s5_r7_reconstruction")
CR = c.CR
LEDGER = U.LEDGER
DEFAULT_ARCHIVE = os.path.expanduser("~/knowledgeos-witness-archive")
FREEZE_FILES = ("INPUT-MANIFESTS.json", "WITNESS.jsonl", "WITNESS-DIGESTS.json", "WITNESS-UNIT.jsonl",
                "WITNESS-UNIT-DIGESTS.json")


class Refused(Exception):
    pass


def _under(path, base):
    path, base = os.path.abspath(path), os.path.abspath(base)
    return path == base or path.startswith(base + os.sep)


def _read(path):
    with open(path, "rb") as f:
        return f.read()


def _put(path, data):
    os.makedirs(os.path.dirname(path), exist_ok=True)
    with open(path, "wb") as f:
        f.write(data)


def _context(batch, root):
    """The frozen batch context: manifest header + entry, plan (hash-checked), slices (hash-checked)."""
    try:
        mhdr, entry, _ = V.load_manifest_entry(root, batch)
    except V.Refused as e:
        raise Refused(str(e))
    bad = U.revision_violations(mhdr)
    if bad:
        raise Refused(f"{batch}: {len(bad)} revision-binding violation(s): {bad[0]}")
    slice_root = mhdr.get("slice_root", V.SLICE_ROOT)
    ppath = os.path.join(root, slice_root, f"{batch}.R7-PLAN.json")
    if not os.path.isfile(ppath) or U.fsha(ppath) != entry.get("r7_plan_sha256"):
        raise Refused(f"{batch}: the frozen plan is missing or ≠ the manifest r7_plan_sha256")
    plan = json.loads(_read(ppath).decode("utf-8"))
    slices = {}
    for lab in entry.get("labels") or []:
        sp = os.path.join(root, slice_root, batch, f"{lab}.json")
        if not os.path.isfile(sp):
            raise Refused(f"{batch}: slice of a manifest label is missing")
        body = _read(sp).decode("utf-8").rstrip("\n")
        if U.sha(body.encode("utf-8")) != (entry.get("slice_sha256") or {}).get(lab):
            raise Refused(f"{batch}: a slice ≠ its manifest sha256")
        slices[lab] = body
    return {"mhdr": mhdr, "entry": entry, "slice_root": slice_root, "plan": plan, "slices": slices,
            "owners": U.run_owner(plan), "adir": os.path.join(root, LEDGER, f"{batch}-R7")}


def check_prompt(text):
    if any(c.quarantine_hits(text)):
        raise Refused("a rendered prompt failed the quarantine scan; nothing written")
    return text


def _decision(v):
    return v.get("decision") if isinstance(v, dict) else v


def record_ids_block(run, batch, index):
    """v2.8 §2.6 instantiated for one final run: record run_id = the assembly id; the label's n range 1000·L+1…+999.
    Only SINGLE / SYNTHESIS runs write object, register and P1-gap records, so only their prompts carry it."""
    asm, lo = f"{batch}-R7", 1000 * index
    return [f"RECORD IDS (R7 addendum §2.6): in every object, register and P1-gap record you write, run_id is {asm};",
            f"  rs_id is {asm}:{batch}:<n> and gap_id is {asm}:{batch}:G<n>, n = {lo + 1} … {lo + 999} "
            f"(n = {lo} + k for your k-th record of that kind, in file order).",
            f"  Your ledger directory, every reader call, the READ-LOG and every claim-evidence ref use YOUR run id {run}."]


# ------------------------------------------------------------------ EG-3: hub required view lines (v2.8 §5.9a)
HUB_SEARCH_OMIT = ("ledger_hits", "raw_hits")                 # rule (iii): EVERY leaf under these is outside R(run)
HUB_CITABLE_DROP = ("search_records", "stage2_files", "source_meta")
VIEW_HEADER_PATH = "<header>"


def _view_paths(view_text):
    """[(1-based view line, path)] of the leaf lines; path is parsed from the line itself (header = line 1)."""
    lines = view_text.split("\n")
    if len(lines) < 2 or lines[0] != U.SLICE_VIEW_HEADER or lines[-1] != "":
        raise ValueError("not a SLICE-VIEW v1 text")
    return [(i + 2, json.loads(l.split("\t", 1)[0])) for i, l in enumerate(lines[1:-1])]


def hub_citable_sids(view_text, obj=None):
    """T(L) (§5.9a (iv)): the S-ids occurring in the canonical slice with search_records, stage2_files and source_meta
    removed; the slice is U.slice_view_parse(view) (lossless, §5.9), so T(L) is a function of the view alone.
    `obj` = that parse, when the caller already has it (parsed once per run)."""
    obj = U.slice_view_parse(view_text) if obj is None else obj
    rest = {k: v for k, v in obj.items() if k not in HUB_CITABLE_DROP} if isinstance(obj, dict) else obj
    return sorted(set(U.SID.findall(U.canon(rest).decode("utf-8"))))


def hub_required_lines(view_text, obj=None, vpaths=None):
    """R(run) under the hub rule (§5.9a): the header line plus every view line whose path p satisfies
    (i) p[0] ∉ {search_records, source_meta}; (ii) p[0] = search_records, p[1] ≥ 1; (iii) p[0..1] = [search_records, 0]
    and p[2] ∉ {ledger_hits, raw_hits}; (iv) p[0] = source_meta, p[1] ∈ T(L). -> sorted 1-based line numbers.
    Edge paths: ["search_records"] / ["source_meta"] (an empty container) satisfy no clause → excluded;
    ["search_records", 0] (an empty record 0) has no p[2], so (iii) holds → included."""
    T = set(hub_citable_sids(view_text, obj))
    req = [1]
    for n, p in (_view_paths(view_text) if vpaths is None else vpaths):
        k = p[0] if p else None
        if k not in ("search_records", "source_meta"):
            req.append(n)                                                          # (i)
        elif k == "search_records" and len(p) >= 2 and isinstance(p[1], int):
            if p[1] >= 1:
                req.append(n)                                                      # (ii)
            elif p[1] == 0 and not (len(p) >= 3 and p[2] in HUB_SEARCH_OMIT):
                req.append(n)                                                      # (iii)
        elif k == "source_meta" and len(p) >= 2 and p[1] in T:
            req.append(n)                                                          # (iv)
    return req


def required_lines(view_text, obj=None, vpaths=None):
    """R(run) for any run: the hub rule when the view's slice has "hub": true, else the whole view (§5.9a, last line)."""
    obj = U.slice_view_parse(view_text) if obj is None else obj
    if isinstance(obj, dict) and obj.get("hub") is True:
        return hub_required_lines(view_text, obj, vpaths)
    return list(range(1, len(view_text.split("\n"))))


def line_ranges(lines):
    """Sorted, merged, inclusive (a, b) ranges of a set of line numbers."""
    out = []
    for n in sorted(set(lines)):
        if out and n == out[-1][1] + 1:
            out[-1] = (out[-1][0], n)
        else:
            out.append((n, n))
    return out


def _hub_view_lines(slice_text):
    """The prompt's paging lines for a hub run (§5.9a): the REQUIRED LINES block replaces the whole-file instruction
    for the SLICE-VIEW; the 25 / 300 lines-per-Read caps are unchanged. None for a non-hub (or absent) slice."""
    if slice_text is None or json.loads(slice_text).get("hub") is not True:
        return None
    rr = line_ranges(hub_required_lines(U.slice_view(slice_text)))
    return ["Page every input with offset/limit: at most 25 lines per Read for the SLICE-VIEW (read the view, not the",
            "raw SLICE), at most 300 lines per Read for every other input, which you page until the whole file has been displayed.",
            "SLICE-VIEW REQUIRED LINES (§5.9a): " + ", ".join(f"{a}–{b}" for a, b in rr)
            + "; other view lines are optional."]


STOP_PAGING = ("Stop paging at the stated line count; a Read past it displays no content; if a Read reports that the file "
               "is shorter than the offset, stop paging that file.")
LINE_COUNT_AT_DISPATCH = "(line count at dispatch)"


def harness_line_count(data):
    """The number of lines the harness Read tool displays for a full read of `data`, which is also the N of its past-EOF
    notice "The file has N lines": the count of "\\n"-separated segments. A trailing newline therefore adds a final
    empty line and an empty file has 1 line (verified on synthetic files against the harness, canary attempt 1 F-3)."""
    return data.count(b"\n") + 1


def _input_line(root, e):
    """One YOUR INPUTS line with the input's line count from its frozen bytes at render time (F-3). An input that does
    not exist yet (a SYNTHESIS run's UNIT-RECORDS at prepare), or whose I(run) sha256 is None (not frozen), is stated
    at dispatch; bytes ≠ the I(run) sha256: Refused."""
    ap = os.path.join(root, e["path"])
    if not os.path.isfile(ap) or e.get("sha256") is None:
        return f"  {e['category']}  {ap}  {LINE_COUNT_AT_DISPATCH}"
    data = _read(ap)
    if U.sha(data) != e["sha256"]:
        raise Refused(f"an input ({e['category']}) ≠ its I(run) sha256; no line count is stated from unfrozen bytes")
    return f"  {e['category']}  {ap}  ({harness_line_count(data)} lines)"


def render_prompt(run, batch, plan, manifest, reader_abs, commit, root, slice_text=None):
    """The dispatch prompt (package §2, v2.7): the binding line first, pointers to the inputs, the execution grammar.
    It restates no contract rule and contains no source content. `slice_text` (the run's frozen slice) is needed only
    for a hub run (v2.8 §5.9a); without it, or for a non-hub slice, the prompt is unchanged."""
    lab, role, files = U.run_owner(plan)[run]
    decided = {s: _decision(d) for s, d in sorted((plan["labels"][lab].get("binary_decisions") or {}).items())}
    L = [f"S5-RUN-BINDING run={run} batch={batch} label={lab} canary={W.canary(commit, run)}", "",
         f"You are one S5 {role} run under contract revision 7 (the R7 addendum listed below as ADDENDUM). You are a measurement instrument:",
         "record what the sources say; never repair, infer across files you did not read, or invent.", "",
         "YOUR INPUTS (read ONLY these, with the Read tool, by these absolute paths):"]
    L += [_input_line(root, e) for e in manifest.get("entries") or []]
    L += (_hub_view_lines(slice_text) or
          ["Page every input with offset/limit until the whole file has been displayed: at most 25 lines per Read for the",
           "SLICE-VIEW (read the view, not the raw SLICE), at most 300 lines per Read for every other input."]) + [STOP_PAGING, ""]
    if role == "SYNTHESIS":
        L += ["CORPUS FILES: none. You do not call the reader; your evidence inputs are the UNIT-RECORDS listed above.", ""]
    else:
        L += ["CORPUS FILES: the ONLY way to read a source file is this exact Bash command, one page per call, nothing else on the line:",
              f"  python3 -B {reader_abs} --run {run} --batch {batch} --label {lab} --step <1|7|10> --mode bytes --page <K> <S####>",
              "Your files are exactly: " + " ".join(s for s in sorted(files) if s not in decided),
              "Read every page of every file."]
        if decided:
            L += ["Do NOT call the reader on: " + "; ".join(f"{s}: {d}" for s, d in decided.items()) + ".",
                  "Record each as the R7 addendum §6.1 instructs."]
        L += [""]
    L += ["WRITE ONLY these files (Write tool), by these absolute paths:"]
    L += [f"  {os.path.join(root, w)}" for w in sorted(U.permitted_writes(run, role))]
    if role in ("SINGLE", "SYNTHESIS"):                  # v2.8 §2.6: the concrete record-identity values (EG-5b)
        L += record_ids_block(run, batch, plan["labels"][lab]["index"])
    L += ["NO other tool calls: no ls, find, cat, grep, mkdir, cd, pip, no other Bash, no web. Every other call FAILS the run (W8).",
          f"Follow the rev3 contract and the R7 addendum for the {role} role; this prompt restates no rule.", "",
          f"When finished, call {U.HANDBACK_TOOL} with a one-line summary (counts only). Do not paste source content.", ""]
    return check_prompt("\n".join(L))


def _manifest_file(ctx):
    p = os.path.join(ctx["adir"], "INPUT-MANIFESTS.json")
    return p, (json.loads(_read(p).decode("utf-8")) if os.path.isfile(p) else None)


def _manifest_checked(root, batch, ctx, run):
    m = U.input_manifest(root, batch, ctx["slice_root"], ctx["plan"], run)
    bad = U.input_manifest_violations(m, ctx["plan"], batch, ctx["slice_root"])
    bad += [f"{e['path']} absent" for e in m["entries"] if e["sha256"] is None]
    if bad:
        raise Refused(f"I(run) {run}: {len(bad)} violation(s)")
    return m


READER_PATH = os.path.join(_HERE, "p3b_read_source.py")
_READER_PROBE = ("import sys\n"                             # script-equivalent: sys.path[0] = the reader's directory
                 "sys.path[0] = sys.argv[1].rpartition('/')[0] or '/'\n"   # (as `python3 -B <reader>`), never the cwd
                 "sys.argv[0] = sys.argv[1]\n"
                 "import importlib.util, json\n"
                 "s = importlib.util.spec_from_file_location('p3b_read_source', sys.argv[1])\n"
                 "m = importlib.util.module_from_spec(s)\n"
                 "s.loader.exec_module(m)\n"
                 "print(json.dumps({'cr': m.dio.s3.CR, 'activated': m.r7_activation(sys.argv[2]) is not None}))\n")


def reader_resolution(batch, cwd=None, reader=READER_PATH):
    """(cr, activated, reason) exactly as the READER resolves them when an agent starts it from `cwd` (default
    os.getcwd()): the reader module itself is loaded in a subprocess with that working directory, so its own import
    chain derives dio.s3.CR (git top level of cwd + CR_REL) and its own r7_activation(batch) is evaluated. Nothing is
    replicated here, so the check cannot diverge from the reader. reason is None when resolution succeeded.
    The child env drops every GIT_* variable (GIT_DIR, GIT_WORK_TREE, GIT_INDEX_FILE, GIT_CEILING_DIRECTORIES, …) and
    PYTHONPATH, so the operator's environment cannot redirect the resolution; the reader path is made absolute."""
    import subprocess
    cwd = os.getcwd() if cwd is None else cwd
    env = {k: v for k, v in os.environ.items() if not k.startswith("GIT_") and k != "PYTHONPATH"}
    try:
        p = subprocess.run([sys.executable, "-B", "-c", _READER_PROBE, os.path.abspath(reader), batch or ""], cwd=cwd,
                           env=env, capture_output=True, text=True, timeout=300)
    except (OSError, subprocess.SubprocessError) as e:
        return None, False, f"the reader could not be started from the dispatch working directory ({type(e).__name__})"
    out = [l for l in p.stdout.split("\n") if l.strip()]
    try:
        r = json.loads(out[-1]) if p.returncode == 0 and out else None
    except ValueError:
        r = None
    if not isinstance(r, dict) or not isinstance(r.get("cr"), str):
        return None, False, (f"the reader resolves no repository root from the dispatch working directory {cwd} "
                             f"(exit {p.returncode}; not inside a git repository?)")
    return r["cr"], r.get("activated") is True, None


def dispatch_root_check(batch, root=CR, cwd=None):
    """Canary attempt 1, F-1/F-5: the dispatched agents inherit the dispatching session's working directory and the
    reader resolves its repository root from it. -> (ok, reason): ok iff the reader, started from `cwd`, resolves the
    prepare `root` (realpath-normalized) and finds the batch activated there (its own r7_activation). The reason names
    the mismatch by paths only (never a label or an S-id)."""
    cr, activated, why = reader_resolution(batch, cwd)
    cwd = os.getcwd() if cwd is None else cwd
    if why:
        return False, why
    if os.path.realpath(cr) != os.path.realpath(root):
        return False, (f"the dispatch working directory {cwd} makes the reader resolve {cr}, not the prepare root "
                       f"{root}; dispatch from inside the repository that holds the root")
    if not activated:
        return False, f"the reader, started from the dispatch working directory {cwd}, finds no activated R7 plan for {batch}"
    return True, None


def prepare(batch, root=CR, emit=None, state_path=None, cwd=None):
    root = os.path.abspath(root)
    if not emit or _under(emit, CR) or _under(emit, root):
        raise Refused("--emit must be a directory outside the repository")
    ok, why = dispatch_root_check(batch, root, cwd)            # F-1/F-5: before anything is read into or written
    if not ok:
        raise Refused(f"{batch}: dispatch working directory check failed: {why}")
    if state_path:
        with open(state_path, encoding="utf-8") as f:
            b = (json.load(f).get("batches") or {}).get(batch) or {}
        if b.get("state") != "PREPARED" or b.get("run_id") != f"{batch}-R7":
            raise Refused(f"{batch}: state is not PREPARED at {batch}-R7")
    ctx = _context(batch, root)
    views = {}
    for lab, body in sorted(ctx["slices"].items()):              # EG-2: validate every view before writing any
        vp = os.path.join(root, ctx["slice_root"], batch, f"{lab}.view.txt")
        want = U.slice_view(body).encode("utf-8")
        if os.path.isfile(vp):
            if _read(vp) != want:
                raise Refused(f"{batch}: an existing SLICE-VIEW ≠ view(slice); never overwritten")
        else:
            views[vp] = want
    for vp, data in views.items():
        _put(vp, data)
    reading = {r for r, (_, role, _) in ctx["owners"].items() if role != "SYNTHESIS"}
    ms = {r: _manifest_checked(root, batch, ctx, r) for r in sorted(reading)}
    mp, old = _manifest_file(ctx)
    if old is not None:
        if {r: m for r, m in old.items() if r in reading} != ms or not set(old) <= set(ctx["owners"]):
            raise Refused(f"{batch}: the frozen INPUT-MANIFESTS.json ≠ the Universe derivation; never overwritten")
    else:
        _put(mp, json.dumps(ms, sort_keys=True).encode("utf-8"))
    prompts, mh = {}, ctx["mhdr"]
    for run in sorted(ctx["owners"]):
        m = ms.get(run) or U.input_manifest(root, batch, ctx["slice_root"], ctx["plan"], run)
        prompts[run] = render_prompt(run, batch, ctx["plan"], m, mh.get("r7_reader_abs"), mh.get("r7_activation_commit"), root,
                                     slice_text=ctx["slices"].get(ctx["owners"][run][0]))
    for run, text in prompts.items():
        pp = os.path.join(emit, batch, f"{run}.prompt.txt")
        if os.path.isfile(pp) and _read(pp) != text.encode("utf-8"):
            raise Refused(f"{batch}: an emitted prompt differs from the re-rendered one")
        _put(pp, text.encode("utf-8"))
    return {"views_written": len(views), "manifests": len(ms), "prompts": prompts,
            "dispatch_root": os.path.realpath(root)}          # = the root the reader resolves from cwd (checked above)


def archive(batch, session_jsonl, archive_root=None):
    """Copy the orchestrator session's harness files into <archive>/<batch>/{main.jsonl, subagents/, tool-results/}.
    Transcripts only grow: an existing archived file must be a byte-prefix of its source, else Refused (nothing copied)."""
    archive_root = os.path.abspath(archive_root or DEFAULT_ARCHIVE)
    if _under(archive_root, CR):
        raise Refused("the archive must be outside the repository (FD-4′)")
    if not os.path.isfile(session_jsonl) or not session_jsonl.endswith(".jsonl"):
        raise Refused("the session transcript is missing")
    sdir = session_jsonl[:-len(".jsonl")]
    dst = os.path.join(archive_root, batch)
    pairs = [(session_jsonl, os.path.join(dst, "main.jsonl"))]
    for sub in ("subagents", "tool-results"):
        d = os.path.join(sdir, sub)
        for f in sorted(os.listdir(d)) if os.path.isdir(d) else []:
            if os.path.isfile(os.path.join(d, f)):
                pairs.append((os.path.join(d, f), os.path.join(dst, sub, f)))
        ad = os.path.join(dst, sub)
        lost = set(os.listdir(ad) if os.path.isdir(ad) else []) - set(os.listdir(d) if os.path.isdir(d) else [])
        if lost:
            raise Refused(f"{len(lost)} archived {sub} file(s) no longer exist in the session")
    for s, t in pairs:
        if os.path.isfile(t) and not _read(s).startswith(_read(t)):
            raise Refused(f"archived {os.path.basename(t)} is not a prefix of its source (a transcript was rewritten)")
    for s, t in pairs:
        os.makedirs(os.path.dirname(t), exist_ok=True)
        shutil.copyfile(s, t)
    return {"files": len(pairs)}


def _witness(batch, root, ctx, archive_root, resolver_factory):
    arch = os.path.join(os.path.abspath(archive_root or ctx["mhdr"].get("r7_archive") or DEFAULT_ARCHIVE), batch)
    main = os.path.join(arch, "main.jsonl")
    if not os.path.isfile(main):
        raise Refused(f"{batch}: no archived orchestrator transcript (run archive first)")
    _, manifests = _manifest_file(ctx)
    if manifests is None:
        raise Refused(f"{batch}: no INPUT-MANIFESTS.json (run prepare first)")
    slices = {l: json.loads(b) for l, b in ctx["slices"].items()}
    need = sorted({s for l in slices for s in U.r6.slice_required(slices[l])[0]})
    contents = (resolver_factory or c.dio.discovery_resolver)().read_many(need) if need else {}
    mh = ctx["mhdr"]
    wres = W.witness(batch, ctx["plan"], main, os.path.join(arch, "subagents"), os.path.join(arch, "tool-results"),
                     mh.get("r7_reader_abs"), mh.get("r7_activation_commit"), manifests, root, contents,
                     V.R7_SEAL_CHECK[0], V.R7_CORPUS_CHECK[0])
    wb = W.witness_bytes(wres["records"])
    dg = W.digests(main, os.path.join(arch, "subagents"), [a["agent_id"] for a in wres["agents"].values()], wb)
    return wres, wb, dg


def freeze_units(batch, label, root=CR, archive_root=None, resolver_factory=None):
    """Runbook step 4: after the marker `: S5-ORCH UNITS-VALIDATED batch=B label=L;` — freeze the label's SYNTHESIS
    I(run) (its unit records now exist) and the unit-validation witness (W7a: later freezes only extend it)."""
    root = os.path.abspath(root)
    ctx = _context(batch, root)
    L = ctx["plan"]["labels"].get(label)
    if not L or L["path"] != "DECOMPOSED":
        raise Refused(f"{batch}: the label is not a DECOMPOSED label of the plan")
    units = sorted(r for r, d in L["runs"].items() if d["role"] == "UNIT")
    recs = {f"{LEDGER}/{u}/file-reading-records.jsonl" for u in units}
    if any(not os.path.isfile(os.path.join(root, p)) for p in recs):
        raise Refused(f"{batch}: a unit record of the label is missing")
    synth = next(r for r, d in L["runs"].items() if d["role"] == "SYNTHESIS")
    m = _manifest_checked(root, batch, ctx, synth)
    mp, ms = _manifest_file(ctx)
    if ms is None:
        raise Refused(f"{batch}: no INPUT-MANIFESTS.json (run prepare first)")
    if synth in ms and ms[synth] != m:
        raise Refused(f"{batch}: the frozen SYNTHESIS I(run) ≠ the derivation (a unit record changed)")
    wres, wb, dg = _witness(batch, root, dict(ctx), archive_root, resolver_factory)
    marks = [r for r in wres["records"] if r.get("kind") == "orchestrator" and r.get("marker") == "UNITS-VALIDATED"
             and r.get("label") == label]
    if not marks:
        raise Refused(f"{batch}: no UNITS-VALIDATED marker for the label in the orchestrator transcript")
    printed = marks[-1]["printed_record_hashes"]
    if any(printed.get(p) != U.fsha(os.path.join(root, p)) for p in recs):
        raise Refused(f"{batch}: the unit records ≠ the hashes printed at the UNITS-VALIDATED marker")
    all_units = {r for r, (_, role, _) in ctx["owners"].items() if role == "UNIT"}
    aids = {wres["agents"][r]["agent_id"] for r in all_units if r in wres["agents"]}
    urecs = [r for r in wres["records"] if r.get("run") in all_units or r.get("agent_id") in aids
             or (r.get("kind") == "orchestrator" and r.get("marker") == "UNITS-VALIDATED")]
    ub = W.witness_bytes(urecs)
    udg = {"decoder_sha256": dg["decoder_sha256"], "extractor_sha256": dg["extractor_sha256"],
           "transcripts": {k: h for k, h in dg["transcripts"].items() if any(a in k for a in aids)},
           "witness_sha256": U.sha(ub)}
    up = os.path.join(ctx["adir"], "WITNESS-UNIT.jsonl")
    if os.path.isfile(up) and not set(_read(up).decode("utf-8").splitlines()) <= set(ub.decode("utf-8").splitlines()):
        raise Refused(f"{batch}: the new unit freeze does not extend the previous one (W7a)")
    if synth not in ms:
        ms[synth] = m
        _put(mp, json.dumps(ms, sort_keys=True).encode("utf-8"))
    _put(up, ub)
    _put(os.path.join(ctx["adir"], "WITNESS-UNIT-DIGESTS.json"), json.dumps(udg).encode("utf-8"))
    return {"unit_records": len(urecs), "units": len(units)}


# ---------------------------------------------------------------------------------------------- EG-5: assembly (v2.8)
# Annex E, `EMPTY-OBJECT v1` (EG5-SPEC-A §3 field table; v2 author_role / generation_parameters.rule; v3 checklist rows).
# Every value is a plan / manifest / slice identifier, a mechanical copy of a hash-checked slice field, or a constant.
EMPTY_OBJECT_V1 = "EMPTY-OBJECT v1"
EMPTY_PRODUCER = "p3b_s5_r7_orchestrate.assemble"
EMPTY_MODE = "DETERMINISTIC-NO-MODEL-CALL"
EMPTY_AUTHOR_ROLE = "AI-AGENT (S5 orchestrator; EMPTY-OBJECT v1 rule)"
EMPTY_DETAIL = ("EMPTY-REQUIRED-SET (R17): the frozen plan derives an empty required set; no file was assigned or read; "
                "births NOT-EVIDENCED-IN-CAPTURE, absences ESCALATED")
EMPTY_REASON = "EMPTY-REQUIRED-SET (R17): no required file; never FOUND or GENUINELY-UNDEFINED"
EMPTY_LOAD = "EMPTY-REQUIRED-SET hub label: stage 2 not performed"
EMPTY_ROW34_NOTE = "slice semantic_status_mechanical (rows 3/4)"
CHECKLIST_VACUOUS = "VACUOUS-OVER-EMPTY-REQUIRED-SET"
NOT_EVIDENCED = "NOT-EVIDENCED-IN-CAPTURE"
ASSEMBLY_FILES = ("objects.jsonl", "register.jsonl", "p1-gap-capture.jsonl")
DATE_RX = re.compile(r"\d{4}-\d{2}-\d{2}")


def tool_sha256():
    return U.fsha(os.path.abspath(__file__))


def in_checklist(entry, label, slice_obj):
    """-> examined? The rule input is `slice["in_checklist"] is True` (the hash-checked slice G-09 reads); the manifest
    entry's dict label→bool is REQUIRED and must agree by value — never by dict membership (v3 §4; hardening H-1: an
    absent / null dict or a missing label is refused, no tolerance). R13."""
    s = slice_obj.get("in_checklist")
    d = entry.get("in_checklist")
    if not isinstance(s, bool):
        raise Refused("R13 the slice in_checklist is not a boolean")
    if not isinstance(d, dict):
        raise Refused("R13 the manifest entry carries no in_checklist dict (label → bool)")
    if label not in d or d[label] is not s:
        raise Refused("R13 the manifest entry in_checklist[label] is missing or ≠ the slice in_checklist")
    return s is True


def _hit_bearing(slice_obj, label):
    """The verifier's hit-bearing dimension rule (p3b_s5_verify, HUB absences), applied to the frozen slice."""
    srec = slice_obj.get("search_records") or []
    dims = {r["dimension"]: r for r in srec if r.get("record") == "DIMENSION"}
    lhs = {r.get("label"): r for r in srec if r.get("record") == "LABEL-HITS"}

    def hb(dim):
        r = dims[dim]
        ref = lhs.get(r.get("hits_ref") or label)
        if ref is None:
            return r.get("negative_label") is None
        return any((ref.get("raw_hits") or {}).values()) or any((ref.get("ledger_hits") or {}).values())
    return dims, sorted(d for d in dims if hb(d))


def empty_object(batch, label, entry, slice_obj, analysis_date, model_id, tool_sha256):
    """Annex E, EMPTY-OBJECT v1 (pure). The object of an EMPTY-path label: no source, quote, claim, timeline point,
    edge or disposition; every birth and current_lifecycle NOT-EVIDENCED-IN-CAPTURE; every absence ESCALATED with the
    stage-1 negative_label; OTHER / EMPTY-REQUIRED-SET (+ LOAD per hit-bearing dimension of a hub label)."""
    examined = in_checklist(entry, label, slice_obj)
    tier = slice_obj.get("tier")
    mech = slice_obj.get("semantic_status_mechanical") or {}
    rule = mech.get("rule")
    if tier == "Z":
        if rule != "ROW-0" or mech.get("note") != V.TIER_Z_NOTE:
            raise Refused("R6 a Tier Z slice whose mechanical semantic row is not ROW-0 with the H-11a note")
        sem = (None, "ROW-0", mech.get("note"))
    elif rule == "ROW-0":
        sem = (None, "ROW-0", mech.get("note"))
    elif rule in ("ROW-3", "ROW-4"):
        sem = (mech.get("value"), rule, EMPTY_ROW34_NOTE)
    else:
        raise Refused("R6 the slice's mechanical semantic rule is outside ROW-0 / ROW-3 / ROW-4")
    dims, hitdims = _hit_bearing(slice_obj, label)
    hub = slice_obj.get("hub")
    escalations = [{"field": "absences", "reason": "OTHER", "detail": EMPTY_DETAIL}]
    if hub is True:
        escalations += [{"field": f"absences.{d}", "reason": "LOAD", "detail": EMPTY_LOAD,
                         "hub_record": copy.deepcopy(slice_obj.get("hub_record"))} for d in hitdims]
    gp = {"producer": EMPTY_PRODUCER, "producer_sha256": tool_sha256, "rule": EMPTY_OBJECT_V1, "mode": EMPTY_MODE}
    o = {"working_label": label, "batch_id": batch, "run_id": f"{batch}-R7",
         "contract_sha256": entry.get("contract_sha256"), "input_manifest_sha256": slice_obj.get("input_manifest_sha256"),
         "tier": tier, "tier_causing_pair_ids": [], "hub": hub, "timeline": [],
         "timeline_summary": dict({f"first_{k}": None for k in U.KINDS}, later_support=[], later_refinement=[],
                                  contradicted_by=[], rejected_by=[], current_lifecycle=NOT_EVIDENCED),
         "semantic_status": sem[0], "semantic_status_rule": sem[1], "semantic_status_note": sem[2],
         "semantic_evidence": {"d4": [], "d5": []},
         "pair_breakdown": dict(collections.Counter(f"{p.get('relationship')}|{p.get('basis')}"
                                                    for p in slice_obj.get("p3a_pairs") or [])),
         "type_status": "UNTYPED", "mathematical_status": "UNDECIDABLE-FROM-CORPUS", "primary_layer": "LAYER-UNRESOLVED",
         "secondary_roles": [], "escalations": escalations, "births": {k: NOT_EVIDENCED for k in U.KINDS},
         "absences": {d: {"resolution": "ESCALATED", "negative_label": copy.deepcopy(r.get("negative_label")),
                          "population_basis": c.POPULATION_BASIS, "supplied_by": None, "reason": EMPTY_REASON}
                      for d, r in sorted(dims.items())},
         "stage2_dispositions": [], "census_reading_disagreements": [], "dependency_edges": [], "status_basis": {},
         "track_composition": {}, "hindsight_dependency": [], "superseded_by_sources": [], "proposed_by": "AI-AGENT",
         "evidence_presentation": "V1-PLUS-ROWS", "record_status": "PROPOSED", "model_id": model_id,
         "generation_parameters": gp, "author_role": EMPTY_AUTHOR_ROLE, "analysis_date": analysis_date}
    if examined:                                          # v3 §3: vacuous examination over the empty required set
        o["checklist_examined"] = list(range(1, 24))
        gp["checklist"] = CHECKLIST_VACUOUS
    return o


def self_check_violations(obj, batch, reg):
    """The pre-write self-check (R7): Σ, typing and META of the Universe, the EMPTY rule and S3 of Reconstruction."""
    lab = obj.get("working_label")
    return (U.validation_violations(obj) + U.typing_violations(reg, obj) + U.meta_violations(reg, obj)
            + C.empty_violations(obj, U.claims(reg, obj))
            + C.s3_violations([obj], lab, f"{batch}-R7", batch, c.quarantine_hits, None, layer_a={0}))


def _no_dup(pairs):
    keys = [k for k, _ in pairs]
    if len(keys) != len(set(keys)):
        raise ValueError("duplicate key")
    return dict(pairs)


def _no_const(name):
    raise ValueError(f"non-finite number {name}")


def _parse_jsonl(path, rel):
    """UTF-8; every non-blank line one JSON object; duplicate keys, NaN and Infinity rejected (no last-wins). R4."""
    try:
        text = _read(path).decode("utf-8")
    except UnicodeDecodeError:
        raise Refused(f"R4 {rel}: not UTF-8")
    out = []
    for i, line in enumerate(text.split("\n"), 1):
        if not line.strip():
            continue
        try:
            x = json.loads(line, object_pairs_hook=_no_dup, parse_constant=_no_const)
        except ValueError:
            raise Refused(f"R4 {rel}: line {i} is not parsable JSON without duplicate keys or non-finite numbers")
        if not isinstance(x, dict):
            raise Refused(f"R4 {rel}: line {i} is not a JSON object")
        out.append(x)
    return out


def _rel(root, path):
    return os.path.relpath(path, root).replace(os.sep, "/")


def assemble(batch, root=CR, analysis_date=None, model_id=None, check=False, archive_root=None):
    """Runbook step 6 (v2.8 §2.7): write ledger-p3b-r2/<B>-R7/{objects,register,p1-gap-capture}.jsonl — one object per
    manifest label in manifest order (the final run's object, or the EMPTY object), the final runs' register /
    p1-gap records in manifest-label blocks with file order kept, canonical JSON lines. It never edits, filters,
    renumbers or repairs a record; it reads no corpus byte and no archive (`archive_root` is accepted for CLI symmetry
    only). Refusals R1–R13 write nothing. Write-once; an identical existing assembly is left untouched; `check`
    re-derives and compares without writing (R9). -> counts + `printed` [(sha256, relpath)] + `absent` [relpath]."""
    root = os.path.abspath(root)
    if not isinstance(batch, str) or not re.fullmatch(r"OB\d{4}", batch):
        raise Refused("R1 the batch id is not OB####")
    if not isinstance(analysis_date, str) or not DATE_RX.fullmatch(analysis_date):
        raise Refused("R12 --date is not YYYY-MM-DD")
    try:
        datetime.date.fromisoformat(analysis_date)
    except ValueError:
        raise Refused("R12 --date is not a calendar date")
    if model_id not in V.MODEL_IDS:
        raise Refused("R10 --model-id is not a served model id of the model rule")
    try:
        ctx = _context(batch, root)
    except Refused as e:
        raise Refused(f"R1 {e}")
    apath = os.path.join(root, U.ADDENDUM_PATH)
    if not os.path.isfile(apath) or U.fsha(apath) != U.ADDENDUM_SHA256:
        raise Refused("R1 the R7 addendum bytes ≠ the frozen sha256")
    reg_contract = U.load_frozen_contract(root)[0]
    entry, plan, slice_root = ctx["entry"], ctx["plan"], ctx["slice_root"]
    labels = list(entry.get("labels") or [])
    if sorted(plan.get("labels") or {}) != sorted(labels) or len(set(labels)) != len(labels):
        raise Refused("R1 the frozen plan labels ≠ the manifest labels")
    tsha = tool_sha256()
    ledger = os.path.join(root, LEDGER)
    present = sorted(os.listdir(ledger)) if os.path.isdir(ledger) else []
    inputs = {_rel(root, os.path.join(root, V.MANIFEST)): None,
              _rel(root, os.path.join(root, slice_root, f"{batch}.R7-PLAN.json")): None}
    objects, reg_out, gap_out, absent = [], [], [], []
    n = collections.Counter()
    for L, lab in enumerate(labels, 1):
        Lp = plan["labels"][lab]
        if Lp.get("index") != L:
            raise Refused(f"R1 L{L:02d}: the plan index ≠ the manifest position")
        sl = json.loads(ctx["slices"][lab])
        in_checklist(entry, lab, sl)                            # R13 for every label
        if Lp.get("path") == "EMPTY":
            if Lp.get("runs"):
                raise Refused(f"R1 L{L:02d}: an EMPTY plan label with planned runs")
            rx = re.compile(re.escape(f"{batch}-R7-L{L:02d}") + r"(?![0-9])")
            if any(rx.match(d) for d in present):
                raise Refused(f"R5 L{L:02d}: a run directory exists for an EMPTY label")
            obj = empty_object(batch, lab, entry, sl, analysis_date, model_id, tsha)
            bad = self_check_violations(obj, batch, reg_contract)
            if bad:
                raise Refused(f"R7 L{L:02d}: the EMPTY object fails its self-check ({len(bad)} violation(s))")
            objects.append(obj)
            n["empty_objects"] += 1
            n["checklist_vacuous"] += 1 if "checklist_examined" in obj else 0
            continue
        finals = [r for r, d in (Lp.get("runs") or {}).items() if d.get("role") in ("SINGLE", "SYNTHESIS")]
        if len(finals) != 1:
            raise Refused(f"R1 L{L:02d}: the plan has no unique final run")
        fdir = os.path.join(ledger, finals[0])
        op = os.path.join(fdir, "objects.jsonl")
        if not os.path.isfile(op):
            raise Refused(f"R2 {_rel(root, op)}: the final run's objects.jsonl is absent")
        objs = _parse_jsonl(op, _rel(root, op))
        inputs[_rel(root, op)] = None
        if len(objs) != 1 or objs[0].get("working_label") != lab:
            raise Refused(f"R3 {_rel(root, op)}: not exactly one object, or not the plan owner's object")
        if objs[0].get("model_id") != model_id:
            raise Refused(f"R10 L{L:02d}: the agent object's model_id ≠ --model-id")
        objects.append(objs[0])
        for name, out, fld, pre in (("register.jsonl", reg_out, "rs_id", ""), ("p1-gap.jsonl", gap_out, "gap_id", "G")):
            p = os.path.join(fdir, name)
            if not os.path.isfile(p):
                absent.append(_rel(root, p))
                continue
            recs = _parse_jsonl(p, _rel(root, p))
            inputs[_rel(root, p)] = None
            idrx = re.compile(re.escape(f"{batch}-R7:{batch}:{pre}") + r"(\d+)")
            for i, r in enumerate(recs, 1):
                if r.get("working_label") != lab:
                    raise Refused(f"R3′ {_rel(root, p)}: record {i} names a label other than its run's plan owner")
                m = idrx.fullmatch(r.get(fld)) if isinstance(r.get(fld), str) else None
                if not m or not 1000 * L + 1 <= int(m.group(1)) <= 1000 * L + 999:
                    raise Refused(f"R11 {_rel(root, p)}: record {i} {fld} is outside L{L:02d}'s range "
                                  f"{batch}-R7:{batch}:{pre}{1000 * L + 1}…{1000 * L + 999}")
            out += recs
        if os.path.isfile(os.path.join(fdir, "p1-gap-capture.jsonl")):
            n["ignored_capture_files"] += 1
    data = {"objects.jsonl": objects, "register.jsonl": reg_out, "p1-gap-capture.jsonl": gap_out}
    data = {k: b"".join(U.canon(x) + b"\n" for x in v) for k, v in data.items()}
    paths = {k: os.path.join(ctx["adir"], k) for k in ASSEMBLY_FILES}
    existing = {k: (_read(p) if os.path.isfile(p) else None) for k, p in paths.items()}
    if check:
        if any(existing[k] != data[k] for k in ASSEMBLY_FILES):
            raise Refused("R9 --check: the assembly on disk ≠ the re-derivation (or is absent)")
    elif any(existing[k] is not None and existing[k] != data[k] for k in ASSEMBLY_FILES):
        raise Refused("R8 an existing assembly file ≠ the re-derivation (write-once; never overwritten)")
    written = 0
    for k in ([] if check else ASSEMBLY_FILES):
        if existing[k] is None:
            tmp = paths[k] + ".tmp"
            _put(tmp, data[k])
            os.replace(tmp, paths[k])
            written += 1
    printed = [(U.fsha(os.path.join(root, r)), r) for r in sorted(inputs)]
    printed += [(U.sha(data[k]), _rel(root, paths[k])) for k in ASSEMBLY_FILES]
    return {"labels": len(labels), "objects": len(objects), "empty_objects": n["empty_objects"],
            "register": len(reg_out), "p1_gap": len(gap_out), "absent_inputs": len(absent),
            "ignored_capture_files": n["ignored_capture_files"], "checklist_vacuous": n["checklist_vacuous"],
            "written": written, "check": bool(check),
            "printed": printed, "absent": absent, "batch": batch}


def assemble_lines(r):
    """The stdout of `assemble`: `<sha256>  <relpath>` per input and output (what the marker freezes), ABSENT lines,
    then a counts-only summary. No label name, no S-id, no content. Identical for the ASSEMBLED run, an idempotent
    re-run and the FINAL-VALIDATED `--check` (so `written` / `check` are not printed)."""
    out = [f"{h}  {p}" for h, p in r["printed"]] + [f"ABSENT {p}" for p in r["absent"]]
    keep = {k: v for k, v in sorted(r.items()) if k not in ("printed", "absent", "batch", "written", "check")}
    return out + [f"S5-ORCH assemble {r['batch']}: " + " ".join(f"{k}={v}" for k, v in keep.items())]


def freeze_final(batch, root=CR, archive_root=None, resolver_factory=None):
    """Runbook step 7 (after archive): the final witness and its digests; written once. v2.8 §2.7: refused unless the
    three assembly files equal the hashes printed at the last ASSEMBLED marker AND (hardening H-2) the hashes printed
    by the tool's `--check` at the last FINAL-VALIDATED marker (both from their orchestrator witness records)."""
    root = os.path.abspath(root)
    ctx = _context(batch, root)
    wres, wb, dg = _witness(batch, root, ctx, archive_root, resolver_factory)
    if set(dg["transcripts"].values()) & _retired_transcript_digests(batch, root):   # EG-6 T134 anti-replay (v2.8 §2.8)
        raise Refused(f"{batch}: an archived transcript reuses a retired transcript digest (a stale session; class S)")
    printed = {}
    for marker in ("ASSEMBLED", "FINAL-VALIDATED"):
        marks = [r for r in wres["records"] if r.get("kind") == "orchestrator" and r.get("marker") == marker]
        if not marks:
            raise Refused(f"{batch}: no {marker} marker in the orchestrator transcript")
        printed[marker] = marks[-1].get("printed_record_hashes") or {}
    for marker in ("ASSEMBLED", "FINAL-VALIDATED"):
        for name in ASSEMBLY_FILES:
            p = os.path.join(ctx["adir"], name)
            rel = _rel(root, p)
            if not os.path.isfile(p) or printed[marker].get(rel) != U.fsha(p) \
                    or printed[marker].get(rel) != printed["ASSEMBLED"].get(rel):
                raise Refused(f"{batch}: {name} ≠ the hash printed at the {marker} marker (or not printed there)")
    out = {os.path.join(ctx["adir"], "WITNESS.jsonl"): wb,
           os.path.join(ctx["adir"], "WITNESS-DIGESTS.json"): json.dumps(dg).encode("utf-8")}
    for p, data in out.items():
        if os.path.isfile(p) and _read(p) != data:
            raise Refused(f"{batch}: {os.path.basename(p)} exists and differs (the final freeze is written once)")
    for p, data in out.items():
        _put(p, data)
    return {"records": len(wres["records"]), "witness_failures": len(wres["failures"])}


# ---------------------------------------------------------------------- EG-3: RI-1b coverage report (report-only)
def run_view_coverage(view_text, view_path, run, records, obj=None, vpaths=None):
    """RI-1b for one run (§5.9a): coverage = ⋃ displayed_range of the run's `read-input` witness records on its own
    SLICE-VIEW path with content_match = true; COVERED ⇔ R(run) ⊆ coverage. Report only: never raises on NOT-COVERED,
    changes no verdict. uncovered_paths = the distinct JSON paths of the uncovered lines (VIEW_HEADER_PATH for line 1).
    `obj` / `vpaths` = the view's parse and line paths, when the caller already has them (parsed once per view)."""
    obj = U.slice_view_parse(view_text) if obj is None else obj
    vpaths = _view_paths(view_text) if vpaths is None else vpaths
    R = required_lines(view_text, obj, vpaths)
    cov, counted = set(), 0
    for r in records:
        rng = r.get("displayed_range")
        if (r.get("kind") != "read-input" or r.get("run") != run or r.get("path") != view_path
                or r.get("content_match") is not True or not isinstance(rng, list) or len(rng) != 2
                or not all(isinstance(x, int) and not isinstance(x, bool) for x in rng)):
            continue
        cov.update(range(rng[0], rng[1] + 1))
        counted += 1
    missing = [n for n in R if n not in cov]
    pmap = dict(vpaths)
    upaths = []
    for n in missing:
        p = VIEW_HEADER_PATH if n == 1 else json.dumps(pmap[n], ensure_ascii=False, separators=(",", ":"))
        if p not in upaths:
            upaths.append(p)
    return {"hub": isinstance(obj, dict) and obj.get("hub") is True, "status": "NOT-COVERED" if missing else "COVERED",
            "required_lines": len(R), "covered_required_lines": len(R) - len(missing), "uncovered_lines": len(missing),
            "uncovered_paths": upaths, "reads_counted": counted}


def view_path_of(run, lab, batch, slice_root, manifests=None):
    """The run's SLICE-VIEW path as the witness records it: the `path` of the SLICE-VIEW entry of the run's frozen
    I(run) (INPUT-MANIFESTS.json) when present, else the Universe's constructed path (§5.8)."""
    for e in ((manifests or {}).get(run) or {}).get("entries") or []:
        if e.get("category") == "SLICE-VIEW" and isinstance(e.get("path"), str):
            return e["path"]
    return f"{slice_root}/{batch}/{lab}.view.txt"


def coverage_report(batch, plan, slices, slice_root, records, manifests=None):
    """{run: run_view_coverage} for every planned run (an EMPTY label has no run, hence no entry). Every run of a label
    shares the label's SLICE-VIEW, so R(run) is role-independent (SINGLE, UNIT, SYNTHESIS). Each view is rendered and
    parsed once."""
    out, views = {}, {}
    for run, (lab, _role, _files) in sorted(U.run_owner(plan).items()):
        if lab not in views:
            v = U.slice_view(slices[lab])
            views[lab] = (v, U.slice_view_parse(v), _view_paths(v))
        v, obj, vpaths = views[lab]
        out[run] = run_view_coverage(v, view_path_of(run, lab, batch, slice_root, manifests), run, records, obj, vpaths)
    return out


def view_coverage(batch, root=CR, archive_root=None, resolver_factory=None):
    """`coverage B`: the RI-1b report over the batch's witness records (the same witness freeze-final computes). It
    writes nothing and fails nothing. -> counts + `per_run` (details stay in the returned dict; never printed)."""
    root = os.path.abspath(root)
    ctx = _context(batch, root)
    wres, _, _ = _witness(batch, root, ctx, archive_root, resolver_factory)
    per = coverage_report(batch, ctx["plan"], ctx["slices"], ctx["slice_root"], wres["records"], _manifest_file(ctx)[1])
    return {"batch": batch, "runs": len(per), "hub_runs": sum(1 for r in per.values() if r["hub"]),
            "covered": sum(1 for r in per.values() if r["status"] == "COVERED"),
            "not_covered": sum(1 for r in per.values() if r["status"] != "COVERED"),
            "uncovered_lines": sum(r["uncovered_lines"] for r in per.values()), "per_run": per}


def coverage_lines(r):
    """The stdout of `coverage`: counts only per run and in total — no label, no path, no S-id, no content."""
    out = [f"COVERAGE {run} {x['status']} hub={x['hub']} required={x['required_lines']} "
           f"uncovered_lines={x['uncovered_lines']} uncovered_paths={len(x['uncovered_paths'])} reads={x['reads_counted']}"
           for run, x in sorted(r["per_run"].items())]
    keep = {k: v for k, v in sorted(r.items()) if k not in ("per_run", "batch")}
    return out + [f"S5-ORCH coverage {r['batch']} (report-only, RI-1b): " + " ".join(f"{k}={v}" for k, v in keep.items())]


# ------------------------------------------------------------------------ EG-6: retire attempt m (v2.8 §2.3, §2.8)
# G-LOG-0106; EG6-SPEC-A option (b); -v2 §1 (crash-safe, resumable, write-once records, steps S0–S6, the guard);
# -v3 §1 (F-01: per-rename st_dev rule, EXDEV refused as class X, never copy-and-delete). Reference model:
# audit-p3b/eg6/probes/retire_model.py. Every move is one os.rename inside its own root (ledger → ledger, archive →
# archive); nothing is ever deleted; every step is idempotent, so re-running after a crash at any point completes to
# the byte-identical result.
RETIRE_CLASSES = ("X", "A1", "A2")                    # re-runnable; H, D, S stop for a human act; U re-verifies
INTENT_NAME = "RETIREMENT-INTENT.json"
COMPLETE_NAME = "RETIREMENT-COMPLETE.json"
INTENT_SCHEMA = "RETIREMENT-INTENT v1"
COMPLETE_SCHEMA = "RETIREMENT-COMPLETE v1"
_STM = []


class RetireRefused(Refused):
    """A refused retirement step. `cls` is the class of the refusal: X (environment: a device mismatch or EXDEV; the
    retirement stays resumable once the cause is removed) or S (integrity: the tree or a record disagrees with the
    intent record, or both / neither of a source and its target exist; STOP, human)."""

    def __init__(self, msg, cls):
        super().__init__(f"{msg} (class {cls})")
        self.cls = cls


def _state_module():
    if not _STM:
        _STM.append(ops.load_script("p3b_s5_state"))
    return _STM[0]


def _checkpoint(step):
    """Test seam: called after each completed retire step (a crash is injected here). A no-op in production."""
    return None


def _dev(path):
    """st_dev of an existing path (the F-01 device rule; a test seam for a simulated cross-device target)."""
    return os.stat(path).st_dev


def _parent_dev(target):
    """st_dev of the target's parent, i.e. of its nearest existing ancestor (the parent may not exist yet at S0)."""
    p = os.path.dirname(os.path.abspath(target))
    while not os.path.exists(p):
        p = os.path.dirname(p)
    return _dev(p)


def _device_rule(pairs, step):
    """F-01: before the first rename of a step, every still-pending (source, target) pair must satisfy
    st_dev(source) == st_dev(parent of target); otherwise the step is refused as class X and nothing is moved in it."""
    for s, t in pairs:
        if os.path.lexists(s) and not os.path.lexists(t) and _dev(s) != _parent_dev(t):
            raise RetireRefused(f"{step}: {os.path.basename(s)} → a target on another device; nothing moved in this step",
                                "X")


def _rename(s, t, step):
    """os.rename only. EXDEV → refused as class X (the retirement stays PENDING and resumable); any other OSError
    propagates (a crash, resumable by the same rules)."""
    try:
        os.rename(s, t)
    except OSError as e:
        if e.errno == errno.EXDEV:
            raise RetireRefused(f"{step}: EXDEV renaming {os.path.basename(s)}; the retirement stays PENDING and is "
                                "resumable", "X")
        raise


def _tree(base):
    """{relpath ('/'-separated): sha256} of every file under `base` ({} if it is not a directory). A symbolic link in
    the footprint is refused (class S): its bytes would not be bound by the move."""
    out = {}
    if not os.path.isdir(base) or os.path.islink(base):
        return out
    for b, ds, fs in os.walk(base):
        for x in ds + fs:
            if os.path.islink(os.path.join(b, x)):
                raise RetireRefused(f"a symbolic link inside {os.path.basename(base)}", "S")
        for f in fs:
            p = os.path.join(b, f)
            out[os.path.relpath(p, base).replace(os.sep, "/")] = U.fsha(p)
    return out


def _record_bytes(obj):
    return (json.dumps(obj, sort_keys=True, separators=(",", ":"), ensure_ascii=False) + "\n").encode("utf-8")


def _fsync_dir(d):
    try:
        fd = os.open(d, os.O_RDONLY)
    except OSError:
        return
    try:
        os.fsync(fd)
    except OSError:
        pass
    finally:
        os.close(fd)


def _put_once(path, obj):
    """Write-once via tmp → fsync → rename. An existing record must be byte-identical (else class S). A stale tmp (a
    crash inside this write) is the tool's own scratch: it is overwritten, never trusted. -> True iff written."""
    data = _record_bytes(obj)
    if os.path.lexists(path):
        if not os.path.isfile(path) or _read(path) != data:
            raise RetireRefused(f"{os.path.basename(path)} exists and differs (write-once)", "S")
        return False
    tmp = path + ".tmp"
    with open(tmp, "wb") as f:
        f.write(data)
        f.flush()
        os.fsync(f.fileno())
    os.replace(tmp, path)
    _fsync_dir(os.path.dirname(path))
    return True


def _archive_root(archive_root, mhdr, root):
    a = os.path.abspath(archive_root or mhdr.get("r7_archive") or DEFAULT_ARCHIVE)
    if _under(a, CR) or _under(a, root):
        raise Refused("the archive must be outside the repository (FD-4′)")
    return a


def _retired_dirs(ledger, batch):
    """{m: path} of the batch's retired attempt directories <B>-R7.A<m> in the ledger."""
    rx = re.compile(re.escape(batch) + r"-R7\.A([1-9]\d*)")
    out = {}
    for d in os.listdir(ledger) if os.path.isdir(ledger) else []:
        hit = rx.fullmatch(d)
        if hit:
            out[int(hit.group(1))] = os.path.join(ledger, d)
    return out


def _pending(ledger, batch):
    """The batch's PENDING retirements (RETIREMENT-INTENT.json exists, RETIREMENT-COMPLETE.json does not)."""
    return sorted(os.path.basename(d) for d in _retired_dirs(ledger, batch).values()
                  if os.path.lexists(os.path.join(d, INTENT_NAME)) and not os.path.lexists(os.path.join(d, COMPLETE_NAME)))


def _retired_transcript_digests(batch, root):
    """T134 (v2.8 §2.8: "its transcripts must not reuse a retired digest"): every `transcript_sha256` recorded in any
    <B>-R7.A<m>/RETIREMENT-INTENT.json of the batch. An unreadable intent record fails closed (Refused)."""
    out = set()
    for d in _retired_dirs(os.path.join(root, LEDGER), batch).values():
        p = os.path.join(d, INTENT_NAME)
        if not os.path.lexists(p):
            continue
        try:
            rec = json.loads(_read(p).decode("utf-8"))
            hs = rec["transcript_sha256"]
            if not isinstance(hs, list) or not all(isinstance(h, str) for h in hs):
                raise TypeError
        except (OSError, UnicodeDecodeError, ValueError, KeyError, TypeError):
            raise Refused(f"{batch}: a retired intent record ({os.path.basename(d)}) is unreadable (anti-replay fails closed)")
        out |= set(hs)
    return out


def may_start_attempt(batch, root=CR, archive_root=None):
    """EG6-SPEC-A-v2 §1: the guard for attempt m+1 (before `prepare B`, `archive B`, any dispatch, and required by
    `state new-attempt`). -> (ok, reason). It fails closed while: (i) a retirement of the batch is PENDING; (ii)
    <archive>/<B>/ exists and is non-empty; (iii) any planned run directory or the assembly <B>-R7 exists in the
    ledger; (iv) Legacy(B) is not the manifest entry's (U.namespace_violations: legacy_ledger_sha256 ≠ U.legacy_digest
    of the ledger, or a <B>-* directory not in legacy_dirs); or the frozen batch context is unusable. Counts only."""
    root = os.path.abspath(root)
    try:
        ctx = _context(batch, root)
        arch = _archive_root(archive_root, ctx["mhdr"], root)
    except (Refused, c.S5Error) as e:
        return False, f"the frozen batch context is unusable: {e}"
    except (OSError, ValueError):
        return False, "the frozen batch context is unreadable"
    ledger = os.path.join(root, LEDGER)
    pend = _pending(ledger, batch)
    if pend:
        return False, f"retirement {pend[0]} is PENDING (RETIREMENT-INTENT.json without RETIREMENT-COMPLETE.json)"
    a = os.path.join(arch, batch)
    if os.path.lexists(a) and (not os.path.isdir(a) or os.listdir(a)):
        return False, "the archive path <archive>/<B>/ is non-empty"
    planned = set(ctx["owners"])
    live = [d for d in sorted(planned | {f"{batch}-R7"}) if os.path.lexists(os.path.join(ledger, d))]
    if live:
        return False, f"{len(live)} planned run / assembly directory(ies) still exist in the ledger"
    e = ctx["entry"]
    ns = U.namespace_violations(ledger, batch, planned, e.get("legacy_ledger_sha256"), e.get("legacy_dirs") or [])
    if ns:
        return False, f"Legacy(B) ≠ the manifest entry's legacy_dirs / legacy_ledger_sha256 ({len(ns)} namespace violation(s))"
    return True, "ok"


def _verify_report_ref(root, batch, m, path=None):
    """The attempt's verify report, if any: `path`, else the verifier's name for the attempt (S5-VERIFY-<B>-R7.json
    for m = 1, S5-VERIFY-<B>-R7.A<m>.json for m >= 2). -> {path (root-relative, else the file name), sha256} | None."""
    p = os.path.abspath(path or (V.out_path(root, batch, f"{batch}-R7") if m == 1 else
                                 os.path.join(root, "audit-p3b", f"S5-VERIFY-{batch}-R7.A{m}.json")))
    if not os.path.isfile(p):
        if path:
            raise Refused("S0: the named verify report does not exist")
        return None
    return {"path": _rel(root, p) if _under(p, root) else os.path.basename(p), "sha256": U.fsha(p)}


def _retire_intent(batch, m, name, ctx, st, pre, root, arch, cause_class, reason, verify_report):
    """S0 (the preconditions only the live tree decides) + S1 (the write-once intent record: every file's old path,
    new path and sha256; the only step that inspects the live footprint)."""
    ledger = os.path.join(root, LEDGER)
    tgt = os.path.join(ledger, name)
    pend = _pending(ledger, batch)
    if pend:
        raise Refused(f"S0: another retirement of the batch is PENDING ({pend[0]})")
    if any(k > m for k in _retired_dirs(ledger, batch)):
        raise RetireRefused("S0: a retirement of a later attempt already exists", "S")
    if os.path.lexists(tgt) and (not os.path.isdir(tgt) or os.path.islink(tgt)
                                 or set(os.listdir(tgt)) - {INTENT_NAME + ".tmp"}):
        raise RetireRefused(f"S0: {name} exists without an intent record", "S")
    if U.fsha(os.path.join(root, V.MANIFEST)) != st["manifest_sha256"]:
        raise Refused("S0: the state is not bound to the current manifest")
    e, planned, asm = ctx["entry"], set(ctx["owners"]), f"{batch}-R7"
    if U.namespace_violations(ledger, batch, planned | {name}, e.get("legacy_ledger_sha256"), e.get("legacy_dirs") or []):
        raise RetireRefused("S0: Legacy(B) differs from the manifest entry before the retirement", "S")
    a_src, a_dst = os.path.join(arch, batch), os.path.join(arch, f"{batch}.A{m}")
    if not os.path.isdir(a_src) or os.path.islink(a_src) or not os.listdir(a_src):
        raise Refused("S0: attempt m's archive <archive>/<B>/ is absent or empty (run `archive B` first)")
    if os.path.lexists(a_dst):
        raise RetireRefused("S0: <archive>/<B>.A<m> already exists", "S")
    names = sorted(planned | {asm})
    if any(os.path.lexists(os.path.join(ledger, d)) and (os.path.islink(os.path.join(ledger, d))
                                                         or not os.path.isdir(os.path.join(ledger, d))) for d in names):
        raise RetireRefused("S0: a planned run / assembly name in the ledger is not a directory", "S")
    srcs = [d for d in names if os.path.isdir(os.path.join(ledger, d))]
    _device_rule([(os.path.join(ledger, d), os.path.join(tgt, d)) for d in srcs] + [(a_src, a_dst)], "S0")
    ledger_files = [{"old": f"{LEDGER}/{d}/{rel}", "new": f"{LEDGER}/{name}/{d}/{rel}", "sha256": h}
                    for d in srcs for rel, h in sorted(_tree(os.path.join(ledger, d)).items())]
    atree = _tree(a_src)
    intent = {"schema": INTENT_SCHEMA, "batch": batch, "attempt": m, "state": "PENDING", "attempt_state": pre["state"],
              "cause_class": cause_class, "failure_signature": pre.get("failure_signature"), "reason": reason,
              "verify_report": _verify_report_ref(root, batch, m, verify_report), "target": f"{LEDGER}/{name}",
              "ledger_moves": [{"from": f"{LEDGER}/{d}", "to": f"{LEDGER}/{name}/{d}"} for d in srcs],
              "archive_move": {"from": batch, "to": f"{batch}.A{m}"},
              "ledger_files": ledger_files,
              "archive_files": [{"old": f"{batch}/{rel}", "new": f"{batch}.A{m}/{rel}", "sha256": h}
                                for rel, h in sorted(atree.items())],
              "transcript_sha256": sorted({h for rel, h in atree.items()
                                           if rel == "main.jsonl" or rel.startswith("subagents/")}),
              "legacy_before": {"legacy_dirs": list(e.get("legacy_dirs") or []),
                                "legacy_ledger_sha256": e.get("legacy_ledger_sha256")},
              "manifest_sha256_before": st["manifest_sha256"]}
    if any(c.quarantine_hits(_record_bytes(intent).decode("utf-8"))):          # the free-text reason lands here
        raise Refused("S1: the intent record failed the quarantine scan; nothing written")
    os.makedirs(tgt, exist_ok=True)
    _put_once(os.path.join(tgt, INTENT_NAME), intent)


def _load_intent(path, batch, m, name):
    raw = _read(path)
    try:
        intent = json.loads(raw.decode("utf-8"))
    except (UnicodeDecodeError, ValueError):
        raise RetireRefused("the intent record is not readable JSON", "S")
    if not isinstance(intent, dict) or _record_bytes(intent) != raw or intent.get("schema") != INTENT_SCHEMA \
            or intent.get("batch") != batch or intent.get("attempt") != m or intent.get("target") != f"{LEDGER}/{name}":
        raise RetireRefused("the intent record is not this retirement's canonical record", "S")
    return intent


def _expected(intent):
    """(ledger dirs, {dir: {relpath: sha256}}, archive from, archive to, {relpath: sha256}) from the intent record."""
    try:
        dirs = [mv["from"][len(LEDGER) + 1:] for mv in intent["ledger_moves"]]
        exp = {d: {} for d in dirs}
        for f in intent["ledger_files"]:
            d, rel = f["old"][len(LEDGER) + 1:].split("/", 1)
            exp[d][rel] = f["sha256"]
        a_from, a_to = intent["archive_move"]["from"], intent["archive_move"]["to"]
        a_exp = {f["old"][len(a_from) + 1:]: f["sha256"] for f in intent["archive_files"]}
    except (KeyError, TypeError, ValueError, AttributeError):
        raise RetireRefused("the intent record is malformed", "S")
    if any("/" in d or not d for d in dirs + [a_from, a_to]):
        raise RetireRefused("the intent record is malformed", "S")
    return dirs, exp, a_from, a_to, a_exp


def _classify(pairs, step):
    """-> the pending pairs (source only). Target only = already moved; both or neither → refused (class S)."""
    pending = []
    for s, t in pairs:
        se, te = os.path.lexists(s), os.path.lexists(t)
        if se and te:
            raise RetireRefused(f"{step}: {os.path.basename(s)}: both the source and the target exist (inconsistent)", "S")
        if not se and not te:
            raise RetireRefused(f"{step}: {os.path.basename(s)}: neither the source nor the target exists (evidence lost)",
                                "S")
        if se:
            pending.append((s, t))
    return pending


def _retire_moves(intent, root, arch, tgt):
    """S2 … S6. Each step validates every pending pair (both/neither, the bytes still equal to the intent record, the
    device rule) before its first rename, so a refusal moves nothing in that step."""
    ledger = os.path.join(root, LEDGER)
    dirs, exp, a_from, a_to, a_exp = _expected(intent)
    pairs = [(os.path.join(ledger, d), os.path.join(tgt, d)) for d in dirs]
    pending = _classify(pairs, "S2")
    for s, _ in pending:
        if _tree(s) != exp[os.path.basename(s)]:
            raise RetireRefused(f"S2: the bytes of {os.path.basename(s)} ≠ the intent record; nothing moved in this step",
                                "S")
    _device_rule(pending, "S2")
    for s, t in pairs:                                                                  # S2
        if (s, t) in pending:
            _rename(s, t, "S2")
        _checkpoint(f"S2:{os.path.basename(s)}")
    for d in dirs:                                                                      # S3
        if _tree(os.path.join(tgt, d)) != exp[d]:
            raise RetireRefused(f"S3: the moved bytes of {d} ≠ the intent record", "S")
    _checkpoint("S3")
    a_s, a_t = os.path.join(arch, a_from), os.path.join(arch, a_to)                    # S4
    pending = _classify([(a_s, a_t)], "S4")
    if pending and _tree(a_s) != a_exp:
        raise RetireRefused("S4: the archive bytes ≠ the intent record; nothing moved in this step", "S")
    _device_rule(pending, "S4")
    if pending:
        _rename(a_s, a_t, "S4")
    _checkpoint("S4")
    if _tree(a_t) != a_exp:                                                             # S5
        raise RetireRefused("S5: the moved archive bytes ≠ the intent record", "S")
    _checkpoint("S5")
    ip = os.path.join(tgt, INTENT_NAME)                                                 # S6
    _put_once(os.path.join(tgt, COMPLETE_NAME),
              {"schema": COMPLETE_SCHEMA, "batch": intent["batch"], "attempt": intent["attempt"], "state": "COMPLETE",
               "intent_sha256": U.fsha(ip), "verified_files": len(intent["ledger_files"]) + len(intent["archive_files"])})
    _checkpoint("S6")


def _check_complete(tgt, batch, m):
    p = os.path.join(tgt, COMPLETE_NAME)
    raw = _read(p)
    try:
        rec = json.loads(raw.decode("utf-8"))
    except (UnicodeDecodeError, ValueError):
        rec = None
    if not isinstance(rec, dict) or _record_bytes(rec) != raw or rec.get("state") != "COMPLETE" \
            or rec.get("batch") != batch or rec.get("attempt") != m \
            or rec.get("intent_sha256") != U.fsha(os.path.join(tgt, INTENT_NAME)):
        raise RetireRefused("the completion record does not bind its intent record", "S")


def _verify_retired(intent, root, arch, tgt):
    """Before any re-freeze: the retired directory holds exactly the two records and the moved directories, and every
    byte (ledger and archive) still equals the intent record (the digest must never freeze altered evidence)."""
    dirs, exp, _, a_to, a_exp = _expected(intent)
    if set(os.listdir(tgt)) != set(dirs) | {INTENT_NAME, COMPLETE_NAME}:
        raise RetireRefused(f"{os.path.basename(tgt)} holds entries other than the records and the moved directories", "S")
    if any(_tree(os.path.join(tgt, d)) != exp[d] for d in dirs) or _tree(os.path.join(arch, a_to)) != a_exp:
        raise RetireRefused("the retired bytes ≠ the intent record", "S")


def _staging_will_be_used(root, st, batch, m):
    """True iff the re-freeze of this retirement will stage a manifest: the state is bound to the manifest on disk and
    that manifest does not yet record this re-freeze. (A resume after the REFREEZE-STATE crash, or a re-run after
    completion, never uses the staging directory, so an existing --staging is not refused then.)"""
    try:
        raw = _read(os.path.join(root, V.MANIFEST))
        hdr = json.loads(raw.decode("utf-8").split("\n")[0])["header"]
        recs = hdr.get("r7_legacy_refreezes") or []
    except (OSError, UnicodeDecodeError, ValueError, KeyError, TypeError, AttributeError):
        return True
    done = any(isinstance(r, dict) and r.get("batch") == batch and r.get("attempt") == m for r in recs)
    return U.sha(raw) == st.get("manifest_sha256") and not done


def _staging_dir(staging, root):
    """A NEW directory outside the repository; validated only where it is used (up front in `retire` when the re-freeze
    will use it, so a refusal writes nothing, and again at the use); created only when used."""
    if staging is None:
        return None
    s = os.path.abspath(staging)
    if _under(s, CR) or _under(s, root) or os.path.lexists(s):
        raise Refused("--staging must be a new directory outside the repository")
    return s


def _refrozen_manifest(raw, batch, m, name, dirs, digest, before, reason):
    """The manifest with the batch's entry re-frozen (legacy_dirs, legacy_ledger_sha256), the header's body
    output_sha256 recomputed (when present) and a provenance record appended to r7_legacy_refreezes. Every other line
    is byte-identical. Idempotent: an already re-frozen manifest is returned unchanged."""
    try:
        lines = raw.decode("utf-8").split("\n")
        hdr = json.loads(lines[0])["header"]
        idx = [i for i, l in enumerate(lines[1:], 1) if l.strip() and json.loads(l).get("batch_id") == batch]
        e = json.loads(lines[idx[0]]) if len(idx) == 1 else None
    except (UnicodeDecodeError, ValueError, KeyError, TypeError, IndexError, AttributeError):
        raise Refused("the manifest is not a readable header + entries JSONL")
    if not isinstance(hdr, dict) or not isinstance(e, dict):
        raise Refused(f"the manifest has no unique entry for {batch}")
    recs = list(hdr.get("r7_legacy_refreezes") or [])
    if any(isinstance(r, dict) and r.get("batch") == batch and r.get("attempt") == m for r in recs):
        if e.get("legacy_dirs") == dirs and e.get("legacy_ledger_sha256") == digest:
            return raw
        raise RetireRefused("the manifest records this re-freeze but its entry differs", "S")
    if list(e.get("legacy_dirs") or []) != before["legacy_dirs"] \
            or e.get("legacy_ledger_sha256") != before["legacy_ledger_sha256"]:
        raise RetireRefused("the manifest entry's legacy fields ≠ the intent record's", "S")
    if "output_sha256" in hdr and hdr["output_sha256"] != U.sha("\n".join(lines[1:]).encode("utf-8")):
        raise RetireRefused("the manifest header output_sha256 ≠ its body", "S")
    lines[idx[0]] = U.canon(dict(e, legacy_dirs=dirs, legacy_ledger_sha256=digest)).decode("utf-8")
    body = "\n".join(lines[1:])
    new_hdr = dict(hdr, r7_legacy_refreezes=recs + [{
        "batch": batch, "attempt": m, "retired": name, "from_legacy_ledger_sha256": before["legacy_ledger_sha256"],
        "to_legacy_ledger_sha256": digest, "authority": reason}])
    if "output_sha256" in hdr:
        new_hdr["output_sha256"] = U.sha(body.encode("utf-8"))
    text = U.canon({"header": new_hdr}).decode("utf-8") + "\n" + body
    if any(c.quarantine_hits(text)):
        raise Refused("STOP: the re-frozen manifest failed the quarantine scan")
    return text.encode("utf-8")


def _install(path, data, want_sha):
    tmp = path + ".tmp"
    with open(tmp, "wb") as f:
        f.write(data)
        f.flush()
        os.fsync(f.fileno())
    os.replace(tmp, path)
    _fsync_dir(os.path.dirname(path))
    if U.fsha(path) != want_sha:
        raise Refused("STOP: the installed manifest ≠ the state-bound hash")


def _retire_refreeze(batch, m, name, intent, ctx, root, state_path, staging):
    """After S6: re-freeze Legacy(B) in the manifest entry through a staged manifest + p3b_s5_state.rebind_manifest
    (composition identical: the legacy fields are not composition keys) and save the state; then install the manifest.
    Resumable: a crash after the state save is completed by installing the (deterministic) re-frozen bytes.
    -> (digest, manifest_written, state_saved)."""
    stm = _state_module()
    ledger, planned, asm = os.path.join(root, LEDGER), set(ctx["owners"]), f"{batch}-R7"
    before = intent["legacy_before"]
    if U.legacy_digest(ledger, batch, planned | {name}, asm) != before["legacy_ledger_sha256"]:
        raise RetireRefused("Legacy(B) outside the retired directory changed since the intent record", "S")
    digest = U.legacy_digest(ledger, batch, planned, asm)
    dirs = sorted(set(before["legacy_dirs"]) | {name})
    if U.namespace_violations(ledger, batch, planned, digest, dirs):
        raise RetireRefused("the ledger namespace would not verify after the re-freeze", "S")
    man_path = os.path.join(root, V.MANIFEST)
    raw = _read(man_path)
    new = _refrozen_manifest(raw, batch, m, name, dirs, digest, before, intent["reason"])
    new_sha, old_sha = U.sha(new), U.sha(raw)
    st = stm.load(state_path)
    if new == raw:
        if st["manifest_sha256"] != new_sha:
            raise RetireRefused("the manifest is re-frozen but the state is not bound to it", "S")
        return digest, 0, 0
    if st["manifest_sha256"] == new_sha:                   # a crash after the state save, before the install
        revs = [x for x in st["history"] if x.get("run_kind") == "MANIFEST-REVISION"]
        if not revs or revs[-1].get("from") != old_sha or revs[-1].get("to") != new_sha:
            raise RetireRefused("the state is bound to the re-frozen manifest without its rebind record", "S")
        _install(man_path, new, new_sha)
        return digest, 1, 0
    if st["manifest_sha256"] != old_sha:
        raise RetireRefused("the state is bound to neither the current nor the re-frozen manifest", "S")
    stage = _staging_dir(staging, root) or tempfile.mkdtemp(prefix="p3b-retire-stage-")
    os.makedirs(stage, exist_ok=True)
    prev_copy, tmp = os.path.join(stage, "previous-manifest.jsonl"), os.path.join(stage, "refrozen-manifest.jsonl")
    for p, data in ((prev_copy, raw), (tmp, new)):
        with open(p, "wb") as f:
            f.write(data)
    st_new = json.loads(json.dumps(st))
    try:
        stm.rebind_manifest(st_new, tmp, prev_copy, intent["reason"])       # validates BEFORE anything is written
    except stm.StateError as e:
        raise Refused(f"the manifest re-freeze was refused by the state: {e}")
    if st_new["batches"] != st["batches"] or st_new["manifest_sha256"] != new_sha:
        raise Refused("STOP: the rebind would change a batch or bind another manifest")
    stm.save(st_new, state_path)
    _checkpoint("REFREEZE-STATE")
    _install(man_path, new, new_sha)
    return digest, 1, 1


def retire(batch, cause_class, reason, root=CR, archive_root=None, state_path=None, staging=None, verify_report=None):
    """EG-6 option (b) (v2.8 §2.8; EG6-SPEC-A-v2 §1; -v3 §1): retire the batch's FAILED / INCOMPLETE attempt m.
    S0 preconditions: the state says `retirable` (revision 7; the current attempt FAILED / INCOMPLETE with a recorded
    class; no attempt ever VERIFIED / AUDITED / ACCEPTED); cause_class ∈ {X, A1, A2} and equals the recorded class; the
    state is bound to the manifest; Legacy(B) intact; no other PENDING retirement; <archive>/<B>/ exists and is
    non-empty; the device rule for every rename pair. S1 RETIREMENT-INTENT.json (write-once; every ledger and archive
    file's old path, new path and sha256; m, class, reason, the verify report ref, the transcript digests). S2 rename
    every planned run directory and the assembly into ledger-p3b-r2/<B>-R7.A<m>/; S3 verify every moved byte; S4 rename
    <archive>/<B> → <archive>/<B>.A<m>; S5 verify; S6 RETIREMENT-COMPLETE.json (write-once). Then the manifest entry's
    legacy_dirs / legacy_ledger_sha256 are re-frozen (staged manifest + p3b_s5_state.rebind_manifest, reason = `reason`)
    and the state is saved. Every step is idempotent: re-running after a crash completes to the byte-identical result.
    Refusals: Refused (a precondition; nothing written) or RetireRefused with cls X / S. -> counts (+ the COMPLETE
    record's sha256 for `state new-attempt --retirement-sha256`, and the re-frozen legacy digest)."""
    root = os.path.abspath(root)
    if not isinstance(batch, str) or not re.fullmatch(r"OB\d{4}", batch):
        raise Refused("S0: the batch id is not OB####")
    if not isinstance(reason, str) or not reason.strip():
        raise Refused("S0: a retirement requires the recorded reason (the G-LOG act or text)")
    stm = _state_module()
    state_path = os.path.abspath(state_path or os.path.join(root, stm.STATE))
    ctx = _context(batch, root)
    arch = _archive_root(archive_root, ctx["mhdr"], root)
    try:
        st = stm.load(state_path)
        pre = stm.retirable(st, batch)
    except stm.StateError as e:
        raise Refused(f"S0: {e}")
    except (OSError, ValueError, KeyError):
        raise Refused("S0: the state file is unreadable")
    m = pre["attempt"]
    if cause_class not in RETIRE_CLASSES:
        raise Refused(f"S0: cause class {cause_class!r} is not retirable (X, A1, A2 only; H, D, S stop for a human act; "
                      "U restores the archive and re-verifies)")
    if cause_class != pre["failure_class"]:
        raise Refused(f"S0: cause class {cause_class} ≠ the class {pre['failure_class']} recorded on attempt {m}")
    name = f"{batch}-R7.A{m}"
    tgt = os.path.join(root, LEDGER, name)
    ip, dp = os.path.join(tgt, INTENT_NAME), os.path.join(tgt, COMPLETE_NAME)
    resumed, already = os.path.lexists(ip), os.path.lexists(dp)
    if already and not resumed:
        raise RetireRefused(f"{name}: a completion record without its intent record", "S")
    if _staging_will_be_used(root, st, batch, m):              # validated only when the re-freeze will use it
        _staging_dir(staging, root)
    if not resumed:
        # a batch that cannot be re-run is not retired: its evidence stays in place, FAILED (a human act decides)
        rr7 = stm.rr7_reason(st, batch)
        if rr7:
            raise Refused(f"S0: {rr7}; not retired")
        if stm.wsys_stopped(st) or stm.wsys_status(st)["stop"]:
            raise Refused("S0: W-SYS has stopped S5 (class D, systemic); not retired until a human act")
        if m + 1 > stm.M_MAX_ATTEMPTS:
            raise Refused(f"S0: attempt {m + 1} would exceed M = {stm.M_MAX_ATTEMPTS} (RR-5); not retired")
        _retire_intent(batch, m, name, ctx, st, pre, root, arch, cause_class, reason, verify_report)
        _checkpoint("S1")
    intent = _load_intent(ip, batch, m, name)
    if intent.get("cause_class") != cause_class or intent.get("reason") != reason:
        raise Refused("a resumed retirement must name the cause class and reason of its intent record")
    if already:
        _check_complete(tgt, batch, m)
    else:
        _retire_moves(intent, root, arch, tgt)
    _verify_retired(intent, root, arch, tgt)
    digest, written, saved = _retire_refreeze(batch, m, name, intent, ctx, root, state_path, staging)
    return {"batch": batch, "attempt": m, "ledger_dirs": len(intent["ledger_moves"]),
            "ledger_files": len(intent["ledger_files"]), "archive_files": len(intent["archive_files"]),
            "verified_files": len(intent["ledger_files"]) + len(intent["archive_files"]),
            "resumed": int(resumed), "already_complete": int(already), "manifest_written": written,
            "state_saved": saved, "complete_sha256": U.fsha(dp), "legacy_ledger_sha256": digest}


def main(argv=None):
    argv = list(sys.argv[1:] if argv is None else argv)
    pos, opts, i = [], {}, 0
    while i < len(argv):
        if argv[i] in ("--emit", "--state", "--session", "--archive", "--out", "--root", "--date", "--model-id",
                       "--cause-class", "--reason", "--staging", "--dispatch-cwd") and i + 1 < len(argv):
            opts[argv[i][2:]] = argv[i + 1]
            i += 2
        elif argv[i] == "--check":
            opts["check"] = True
            i += 1
        else:
            pos.append(argv[i])
            i += 1
    if not pos or pos[0] not in ("prepare", "archive", "freeze-units", "assemble", "freeze-final", "coverage", "verify",
                                 "retire") or len(pos) < 2:
        print(__doc__.split("\n\n")[2], file=sys.stderr)
        return 2
    cmd, batch, root = pos[0], pos[1], os.path.abspath(opts.get("root") or CR)
    if cmd == "prepare" and not opts.get("dispatch-cwd"):        # F-5: never default to this process's own cwd
        print("REFUSED: prepare requires --dispatch-cwd DIR (the session's primary working directory, which the "
              "dispatched agents inherit); nothing written", file=sys.stderr)
        return 2
    try:
        c.verify_frozen()
        c.assert_sealed()
        if cmd == "prepare":
            r = prepare(batch, root, opts.get("emit"), opts.get("state"), cwd=opts["dispatch-cwd"])
            r = {k: (len(v) if k == "prompts" else v) for k, v in r.items()}
        elif cmd == "archive":
            r = archive(batch, opts.get("session") or "", opts.get("archive"))
        elif cmd == "freeze-units":
            if len(pos) != 3:
                raise Refused("freeze-units needs B and L")
            r = freeze_units(batch, pos[2], root, opts.get("archive"))
        elif cmd == "assemble":
            r = assemble(batch, root, opts.get("date"), opts.get("model-id"), bool(opts.get("check")), opts.get("archive"))
            c.assert_sealed()
            print("\n".join(assemble_lines(r)))
            return 0
        elif cmd == "freeze-final":
            r = freeze_final(batch, root, opts.get("archive"))
        elif cmd == "retire":                                       # EG-6 (v2.8 §2.8): counts + two hashes only
            if len(pos) != 2:
                raise Refused("retire needs exactly B")
            r = retire(batch, opts.get("cause-class"), opts.get("reason"), root, opts.get("archive"), opts.get("state"),
                       opts.get("staging"))
        elif cmd == "coverage":
            r = view_coverage(batch, root, opts.get("archive"))
            c.assert_sealed()
            print("\n".join(coverage_lines(r)))
            return 0
        else:
            V.R7_ARCHIVE[0] = opts.get("archive")
            return V.main(["--batch", batch, "--run", f"{batch}-R7", "--root", root] +
                          (["--out", opts["out"]] if opts.get("out") else []))
        c.assert_sealed()
    except (Refused, c.S5Error, OSError) as e:
        print(f"REFUSED: {e}", file=sys.stderr)
        return 2
    print(f"S5-ORCH {cmd} {batch}: " + " ".join(f"{k}={v}" for k, v in sorted(r.items())))
    return 0


if __name__ == "__main__":
    sys.exit(main())
