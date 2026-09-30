#!/usr/bin/env python3
"""R7 bounded context WITNESS — "what happened?" (frozen addendum v2.3 §5, §7; W1–W8; ADR-R7-01; G-LOG-0088).

The harness transcript, decoded by the neutral syntax decoder (p3b_transcript_syntax), is the authoritative execution
witness within the declared trust boundary (harness, orchestrator and OS account trusted; agent-authored artifacts not).
This module derives the execution witness deterministically and evaluates W1 (completed dispatch; distinct reason codes),
W2 (dispatch binding), W3 (reader event content, page hash recomputed from the resolver bytes passed in), W4 (explicit
failure), W5 (harness-time ordering; derived stages), W7/W7a (preservation, monotonicity) and W8 (execution-capability
closure incl. Read of I(run)). Input reads are execution events, never evidence. Depends on Universe (read-only).
Failure strings are tagged "R7-W". Execution truth ≠ semantic truth: nothing here judges what a text means.

Implementation grammar (not semantics; recorded in the developer guide):
  dispatch binding  first prompt line `S5-RUN-BINDING run=<run> batch=<batch> label=<label> canary=<hex16>`,
                    canary = sha256(activation_commit ‖ run)[:16]
  orchestrator      a Bash command starting `: S5-ORCH <UNITS-VALIDATED|ASSEMBLED|FINAL-VALIDATED> batch=<B>[ label=<L>];`
                    whose output prints `<sha256>  <path>` lines (UNITS-VALIDATED: the unit record files)
  R7 reader output  first line `=== S#### page k/n bytes a-b (content sha256 C; page sha256 P) run R batch B label L inv I ===`,
                    last line `=== END S#### page k/n ACK-<12 hex> ===`
"""
import datetime
import hashlib
import importlib.util
import json
import os
import re

_HERE = os.path.dirname(os.path.abspath(__file__))


def _load(name):
    s = importlib.util.spec_from_file_location(name, os.path.join(_HERE, name + ".py"))
    m = importlib.util.module_from_spec(s)
    s.loader.exec_module(m)
    return m


ts = _load("p3b_transcript_syntax")
U = _load("p3b_s5_r7_universe")
r5 = U.r5

BIND = re.compile(r"^S5-RUN-BINDING run=(\S+) batch=(\S+) label=(\S+) canary=([0-9a-f]{16})$")
ORCH = re.compile(r"^: S5-ORCH (UNITS-VALIDATED|ASSEMBLED|FINAL-VALIDATED) batch=(OB\d{4})(?: label=([A-Za-z0-9][A-Za-z0-9._-]*))?;")
PRINTED = re.compile(r"^([0-9a-f]{64})  (\S+)$", re.M)
HDR = re.compile(r"^=== (S\d{4}) page (\d+)/(\d+) bytes (\d+)-(\d+) \(content sha256 ([0-9a-f]{64}); page sha256 ([0-9a-f]{64})\) "
                 r"run (\S+) batch (\S+) label (\S+) inv ([0-9a-f]{16}) ===$")
TRL = re.compile(r"^=== END (S\d{4}) page (\d+)/(\d+) (ACK-[0-9a-f]{12}) ===$")
CATN = re.compile(r"^\s*(\d+)\t(.*)$")


def _hit(check, text):
    """A SEAL / corpus check: a callable (production: count-only common helpers, so this module never holds the
    hold-out lists — H-19 guard) or a set of tokens (synthetic tests)."""
    return bool(check(text)) if callable(check) else any(t in text for t in check or ())


def canary(commit, run):
    return hashlib.sha256((commit + run).encode()).hexdigest()[:16]


def _t(s):
    try:
        return datetime.datetime.fromisoformat(str(s).replace("Z", "+00:00"))
    except ValueError:
        return None


def _sha(b):
    return hashlib.sha256(b).hexdigest()


def _fsha(p):
    return U.fsha(p) if os.path.isfile(p) else None


def _rel(path, root):
    if not isinstance(path, str) or not os.path.isabs(path):
        return None
    r = os.path.relpath(os.path.normpath(path), os.path.normpath(root))
    return None if r.startswith("..") else r


def _content_match(displayed, file_bytes, offset=None):
    """The harness Read view (`<n>\\t<line>` per line) must equal the frozen file's lines over the displayed range."""
    if displayed is None or file_bytes is None:
        return False, None
    lines = file_bytes.decode("utf-8", errors="replace").split("\n")
    shown = [CATN.match(l) for l in displayed.split("\n") if l.strip()]
    if not shown or any(m is None for m in shown):
        return False, None
    nums = [int(m.group(1)) for m in shown]
    ok = all(1 <= n <= len(lines) and lines[n - 1] == m.group(2) for n, m in zip(nums, shown))
    return ok, [min(nums), max(nums)]


def witness(batch, plan, main_path, sub_dir, tr_dir, reader_abs, commit, manifests, root, contents,
            holdout_tokens=(), corpus_tokens=(), declared_stages=None):
    """-> {value: T|F, failures, records, reads, input_reads, stages, agents}. Deterministic in its inputs."""
    F, records, reads, input_reads = [], [], [], []
    owners = U.run_owner(plan)
    rx = U.canonical_reader(reader_abs)
    main = ts.decode(main_path)
    if main.parse_errors:
        F.append(f"R7-W W1-PARSE orchestrator transcript: unparseable lines {main.parse_errors[:5]}")
    if ts.chain(main)["parent_missing"] or ts.chain(main)["dup_uuid"]:
        F.append("R7-W W1-CHAIN orchestrator transcript record chain broken")
    mcalls = ts.calls(main)
    # ---------------------------------------------------------------- W2: dispatches
    disp, disp_order = {}, []
    for c in mcalls:
        if c.name != "Agent":
            continue
        prompt = str(c.input.get("prompt") or "")
        m = BIND.match(prompt.split("\n", 1)[0])
        if not m:
            if "S5-RUN-BINDING" in prompt and f"batch={batch}" in prompt:
                F.append(f"R7-W W2 dispatch {c.id}: binding line is not exactly the first prompt line")
            continue
        run, b, lab, can = m.groups()
        if b != batch:
            continue
        if prompt.count("S5-RUN-BINDING") != 1:
            F.append(f"R7-W W2 dispatch {c.id}: more than one binding line")
        if run not in owners:
            F.append(f"R7-W W2 dispatch {c.id}: run {run} is not a planned run")
        elif owners[run][0] != lab:
            F.append(f"R7-W W2 dispatch {c.id}: label {lab} ≠ the plan owner of {run}")
        if can != canary(commit, run):
            F.append(f"R7-W W2 dispatch {c.id}: canary does not match the activation commit")
        m_ = manifests.get(run) or {}
        disp[c.id] = {"kind": "dispatch", "tool_use_id": c.id, "run": run, "batch": b, "label": lab, "canary": can,
                      "input_manifest_sha256": m_.get("manifest_sha256"), "t": c.t_call}
        disp_order.append(c.id)
    records += [disp[i] for i in disp_order]
    notes = {}
    for n in ts.notifications(main):
        notes.setdefault(n["task_id"], []).append(n)
    # ---------------------------------------------------------------- orchestrator markers
    orch = []
    for c in mcalls:
        if c.name != "Bash":
            continue
        m = ORCH.match(str(c.input.get("command") or ""))
        if not m or m.group(2) != batch:
            continue
        orch.append({"kind": "orchestrator", "marker": m.group(1), "label": m.group(3),
                     "printed_record_hashes": dict((p, h) for h, p in PRINTED.findall(c.text or "")),
                     "t_call": c.t_call, "t_result": c.t_result})
    # ---------------------------------------------------------------- W1: agents
    metas, files_by_agent = {}, {}
    for f in sorted(os.listdir(sub_dir)) if os.path.isdir(sub_dir) else []:
        p = os.path.join(sub_dir, f)
        if f.endswith(".meta.json"):
            aid = f[len("agent-"):-len(".meta.json")]
            try:
                metas[aid] = ts.read_meta(p)
            except (ValueError, OSError):
                F.append(f"R7-W W1 agent {aid}: unreadable meta.json")
        elif f.endswith(".jsonl"):
            for a in ts.ids(ts.decode(p))[0]:
                files_by_agent.setdefault(a, []).append(p)
    by_disp = {}
    for aid, m in metas.items():
        if m.get("toolUseId") in disp:
            by_disp.setdefault(m["toolUseId"], []).append(aid)
    agent_info, effectful_by_run = {}, {}
    for did in disp_order:
        d = disp[did]
        run = d["run"]
        aids = by_disp.get(did) or []
        if not aids:
            F.append(f"R7-W W1-TRANSCRIPT-MISSING {run}: dispatch {did} has no subagent transcript")
            continue
        for aid in sorted(aids):
            tp = os.path.join(sub_dir, f"agent-{aid}.jsonl")
            fl = files_by_agent.get(aid, [])
            if not os.path.isfile(tp):
                F.append(f"R7-W W1-TRANSCRIPT-MISSING {run}: agent {aid} has meta but no transcript")
                continue
            if len(fl) > 1:
                F.append(f"R7-W W1-TRANSCRIPT-DUPLICATE {run}: agent {aid} appears in {len(fl)} transcript files")
            tr = ts.decode(tp)
            ch = ts.chain(tr)
            if tr.parse_errors:
                F.append(f"R7-W W1-PARSE {run}: unparseable lines {tr.parse_errors[:5]}")
            if ch["parent_missing"] or ch["dup_uuid"]:
                F.append(f"R7-W W1-CHAIN {run}: record chain broken (parent unseen {len(ch['parent_missing'])}, "
                         f"duplicate uuid {len(ch['dup_uuid'])})")
            if ts.ids(tr)[0] != [aid]:
                F.append(f"R7-W W1-TRANSCRIPT-DUPLICATE {run}: transcript carries agent ids {ts.ids(tr)[0]}")
            ns = notes.get(aid, [])
            n_uses = ts.tool_use_count(tr)
            harness = None
            if not ns:
                F.append(f"R7-W W1-NOTIFICATION-ABSENT {run}: no completion notification (incomplete dispatch)")
            elif len(ns) > 1:
                F.append(f"R7-W W1-NOTIFICATION-MULTIPLE {run}: {len(ns)} notifications (agent resumed; RUN-INVALID)")
            else:
                n = ns[0]
                harness = n["tool_uses"]
                if n["tool_use_id"] != did:
                    F.append(f"R7-W W2 {run}: notification names dispatch {n['tool_use_id']} ≠ {did}")
                if n["status"] != "completed":
                    F.append(f"R7-W W1-NOTIFICATION-ABSENT {run}: notification status {n['status']!r}")
                if harness is None:
                    F.append(f"R7-W W1-HARNESS-COUNT-MISSING {run}: notification without a harness tool-use count")
                elif harness != n_uses:
                    F.append(f"R7-W W1-COUNT-MISMATCH {run}: transcript tool_use count {n_uses} ≠ harness count {harness}")
            cs = ts.calls(tr, persisted_root=tr_dir)
            if not cs:
                F.append(f"R7-W W1-ZERO-TOOL {run}: completed dispatch without any tool call (run FAILED)")
            elif all(c.name == U.HANDBACK_TOOL for c in cs):
                F.append(f"R7-W W1-DECLINED {run}: only the handback (run FAILED)")
            times = [r.get("timestamp") for r in tr.records if r.get("timestamp")]
            arec = {"kind": "agent", "agent_id": aid, "run": run, "transcript_sha256": tr.sha256,
                    "tool_use_count": n_uses, "harness_tool_use_count": harness, "notifications": len(ns),
                    "parse_errors": tr.parse_errors, "chain_ok": not (ch["parent_missing"] or ch["dup_uuid"]),
                    "first_t": min(times) if times else None, "last_t": max(times) if times else None}
            d["agent_id"] = aid
            records.append(arec)
            ev, eff = _classify(cs, run, batch, owners, manifests.get(run) or {}, rx, root, contents, tr_dir,
                                holdout_tokens, corpus_tokens, aid, F)
            records += ev
            reads += [e for e in ev if e["kind"] == "read"]
            input_reads += [e for e in ev if e["kind"] == "read-input"]
            if eff:
                effectful_by_run.setdefault(run, []).append(aid)
            agent_info[run] = {"agent_id": aid, "events": ev}
    for run, aids in effectful_by_run.items():
        if len(aids) > 1:
            F.append(f"R7-W W2 {run}: {len(aids)} dispatches with tool effects for one run (run FAILED)")
    dispatched = {disp[i]["run"] for i in disp_order}
    for run in owners:
        if run not in dispatched:
            F.append(f"R7-W W1 {run}: planned run was never dispatched")
    records += orch
    stages = _stages(plan, disp, disp_order, agent_info, orch, root, F)
    for s in declared_stages or []:
        got = (stages.get(s.get("label")) or {}).get(s.get("stage"))
        want = got.get("t_result") if isinstance(got, dict) else got
        if s.get("t") != want:
            F.append(f"R7-W W5 {s.get('label')}: declared stage {s.get('stage')!r} ≠ the harness-derived value")
    return {"value": "F" if F else "T", "failures": F, "records": records, "reads": reads, "input_reads": input_reads,
            "stages": stages, "agents": agent_info}


def _persisted_identity(path):
    """The identity of a persisted-output path: the lexically normalized ABSOLUTE path (deterministic; no filesystem
    lookup at verify time, because the archive relocates the harness files). A relative or non-string path has none."""
    if not isinstance(path, str) or not os.path.isabs(path):
        return None
    return os.path.normpath(path)


def _classify(cs, run, batch, owners, manifest, rx, root, contents, tr_dir, holdout, corpus, aid, F):
    """W8: every tool call is exactly one of {reader, read-input, write, handback} or a violation."""
    lab, role, perm = owners.get(run, (None, None, set()))
    entries = {e.get("path"): e for e in manifest.get("entries") or []}
    permitted_w = U.permitted_writes(run, role)
    ev, effectful = [], False
    announced = set()          # EXACT normalized paths the harness announced as this agent's persisted outputs (F-01)
    last_write = {}
    for c in cs:
        tin = json.dumps(c.input, ensure_ascii=False)
        tag = f"R7-W {run} call {c.index} ({c.name})"
        if _hit(holdout, tin):
            F.append(f"{tag}: W8 SEAL-BREACH-ATTEMPT (hold-out token in a tool call; batch STOP, P3B-ESC)")
            ev.append({"kind": "unauthorized", "agent_id": aid, "run": run, "tool": c.name, "command_sha256": _sha(tin.encode()),
                       "seal": True, "t_call": c.t_call, "t_result": c.t_result})
            effectful = True
            continue
        if c.name == U.HANDBACK_TOOL:
            pass
        elif c.name == "Bash":
            effectful = True
            cmd = str(c.input.get("command") or "")
            m = rx.match(cmd)
            if m:
                ev.append(_reader_event(c, m, run, batch, lab, role, perm, contents, aid, tag, F))
            elif "p3b_read_source" in cmd:
                F.append(f"{tag}: W8 noncanonical reader invocation")
                ev.append({"kind": "noncanonical", "agent_id": aid, "run": run, "command_sha256": _sha(cmd.encode()),
                           "t_call": c.t_call, "t_result": c.t_result})
            elif _hit(corpus, cmd):
                F.append(f"{tag}: W8 SEAL access to a corpus path outside the reader (batch STOP)")
            else:
                F.append(f"{tag}: W8 unauthorized Bash command")
                ev.append({"kind": "unauthorized", "agent_id": aid, "run": run, "tool": "Bash",
                           "command_sha256": _sha(cmd.encode()), "t_call": c.t_call, "t_result": c.t_result})
        elif c.name == "Read":
            effectful = True
            path = c.input.get("file_path")
            rel = _rel(path, root)
            e = entries.get(rel) if rel else None
            if e is not None:
                fb = open(os.path.join(root, rel), "rb").read() if os.path.isfile(os.path.join(root, rel)) else None
                ok, rng = _content_match(c.text, fb)
                if not ok:
                    F.append(f"{tag}: W8 read-input {rel}: content_match false (displayed ≠ frozen bytes)")
                ev.append({"kind": "read-input", "agent_id": aid, "run": run, "path": rel, "manifest_entry": e.get("category"),
                           "sha256_frozen": e.get("sha256"), "sha256_observed": _sha(fb) if fb is not None else None,
                           "displayed_range": rng, "content_match": ok, "t_call": c.t_call, "t_result": c.t_result})
            elif manifest.get("persisted_outputs") == "ALLOWED" and _persisted_identity(path) in announced:
                # F-01 (independent audit, G-LOG-0091): identity is the EXACT announced path, never a basename; the
                # displayed content must equal the archived artifact over the displayed range.
                pp = os.path.join(tr_dir, os.path.basename(path))
                pb = open(pp, "rb").read() if os.path.isfile(pp) else None
                ok, rng = _content_match(c.text, pb)
                if not ok:
                    F.append(f"{tag}: W8 read-input persisted {os.path.basename(path)}: content_match false "
                             f"(displayed ≠ archived artifact)")
                ev.append({"kind": "read-input", "agent_id": aid, "run": run, "path": os.path.basename(path),
                           "manifest_entry": "PERSISTED", "sha256_frozen": None, "sha256_observed": _sha(pb) if pb is not None else None,
                           "displayed_range": rng, "content_match": ok, "t_call": c.t_call, "t_result": c.t_result})
            else:
                seal = _hit(corpus, str(path))
                F.append(f"{tag}: W8 read outside I(run)" + (" — SEAL (corpus path; batch STOP)" if seal else ""))
                ev.append({"kind": "unauthorized", "agent_id": aid, "run": run, "tool": "Read",
                           "command_sha256": _sha(tin.encode()), "t_call": c.t_call, "t_result": c.t_result})
        elif c.name == "Write":
            effectful = True
            rel = _rel(c.input.get("file_path"), root)
            if rel in permitted_w:
                content = c.input.get("content") or ""
                last_write[rel] = content
                ev.append({"kind": "write", "agent_id": aid, "run": run, "path": rel,
                           "content_sha256": _sha(content.encode("utf-8")), "t": c.t_call})
            else:
                F.append(f"{tag}: W8 Write outside the run's permitted paths")
                ev.append({"kind": "unauthorized", "agent_id": aid, "run": run, "tool": "Write",
                           "command_sha256": _sha(tin.encode()), "t_call": c.t_call, "t_result": c.t_result})
        else:
            effectful = True
            F.append(f"{tag}: W8 unauthorized tool {c.name}")
            ev.append({"kind": "unauthorized", "agent_id": aid, "run": run, "tool": c.name,
                       "command_sha256": _sha(tin.encode()), "t_call": c.t_call, "t_result": c.t_result})
        if c.persisted_path:
            announced.add(_persisted_identity(c.persisted_path))
    for rel, content in last_write.items():
        p = os.path.join(root, rel)
        if not os.path.isfile(p) or open(p, "rb").read() != content.encode("utf-8"):
            F.append(f"R7-W W5 {run}: {rel} differs from its last witnessed Write (changed after production)")
    return ev, effectful


def _reader_event(c, m, run, batch, lab, role, perm, contents, aid, tag, F):
    run_a, b, l, step, k, sid = m.groups()
    k = int(k)
    base = {"agent_id": aid, "run": run, "batch": b, "label": l, "source_id": sid, "step": int(step), "page": k,
            "t_call": c.t_call, "t_result": c.t_result, "uuid_call": c.uuid_call, "uuid_result": c.uuid_result}
    if role == "SYNTHESIS":
        F.append(f"{tag}: W8 synthesis run made a reader call (records-only synthesis)")
    if run_a != run or b != batch:
        F.append(f"{tag}: W2 reader call under run {run_a} / batch {b} (bound run {run}, batch {batch})")
    if l != lab:
        F.append(f"{tag}: W2 reader call with label {l} ≠ the plan owner {lab}")
    if sid not in perm:
        F.append(f"{tag}: W8 reader call for {sid}, not permitted for this run")
    if c.text is None:
        F.append(f"{tag}: W1 reader call without a tool_result")
        return dict(base, kind="malformed")
    if c.is_error:
        if c.exit_code is None:
            F.append(f"{tag}: W4 denied by the harness (no reader output; stop condition)")
            return dict(base, kind="denied", exit=None)
        body = c.text.split("\n", 1)[1] if "\n" in c.text else ""
        if "REFUSED:" in body:
            F.append(f"{tag}: W4 refused reader call (stop condition): {body.strip()[:80]}")
            return dict(base, kind="refused", exit=c.exit_code, message=body.strip()[:200])
        F.append(f"{tag}: W4 malformed reader call (exit {c.exit_code}; stop condition)")
        return dict(base, kind="malformed", exit=c.exit_code)
    lines = c.text.rstrip("\n").split("\n")
    h, t = HDR.match(lines[0]) if lines else None, TRL.match(lines[-1]) if lines else None
    if not h:
        F.append(f"{tag}: W3 successful reader call without a valid positional header")
        return dict(base, kind="malformed")
    if not t:
        F.append(f"{tag}: W3 successful reader call without a valid positional trailer")
        return dict(base, kind="malformed")
    hs, hk, hn, ha, hb, hc, hp, hr, hbat, hl, inv = h.groups()
    if (hs, int(hk), hr, hbat, hl) != (sid, k, run_a, b, l) or (t.group(1), int(t.group(2))) != (sid, k):
        F.append(f"{tag}: W3 header/trailer identity ≠ the command arguments")
    if t.group(4) != "ACK-" + hp[-12:]:
        F.append(f"{tag}: W3 trailer acknowledgement ≠ the header page sha256")
    raw = contents.get(sid)
    ok = False
    if raw is not None and k <= len(r5.byte_pages(raw)):
        _, rec = r5.byte_page_record(sid, raw, k, _sha(raw))
        ok = (rec["page_sha256"], rec["byte_start"], rec["byte_end"], len(r5.byte_pages(raw)), _sha(raw)) == \
             (hp, int(ha), int(hb), int(hn), hc)
    if not ok:
        F.append(f"{tag}: W3 page sha256 / byte range ≠ the recomputation from the resolver bytes")
    return dict(base, kind="read", n_pages=int(hn), byte_start=int(ha), byte_end=int(hb), page_sha256=hp,
                content_sha256=hc, ack=t.group(4), invocation_id=inv, verified=ok)


def _stages(plan, disp, disp_order, agent_info, orch, root, F):
    """W5 / §7: stages derived from harness times; the ordering rules; printed record hashes = current files."""
    out = {}
    ends = [o for o in orch if o["marker"] == "ASSEMBLED"]
    fins = [o for o in orch if o["marker"] == "FINAL-VALIDATED"]
    assembled = ends[-1] if ends else None
    final_v = fins[-1] if fins else None
    for lab, L in plan["labels"].items():
        runs = L["runs"]
        if not runs:
            continue
        reading = [r for r, d in runs.items() if d["role"] in ("UNIT", "SINGLE")]
        final = next((r for r, d in runs.items() if d["role"] in ("SYNTHESIS", "SINGLE")), None)
        last_read = max((_t(e["t_result"]) for r in reading for e in (agent_info.get(r) or {}).get("events", [])
                         if e["kind"] == "read" and e.get("t_result")), default=None)
        writes = [e for e in (agent_info.get(final) or {}).get("events", []) if e["kind"] == "write"]
        produced = max((e["t"] for e in writes), default=None)
        st = {"produced": produced}
        if L["path"] == "DECOMPOSED":
            uvs = [o for o in orch if o["marker"] == "UNITS-VALIDATED" and o["label"] == lab]
            sd = next((disp[i]["t"] for i in disp_order if disp[i]["run"] == final), None)
            st.update(units_validated=uvs[-1] if uvs else None, synthesis_dispatched=sd)
            if len(uvs) != 1 or sd is None:
                F.append(f"R7-W W5 {lab}: exactly one UNITS-VALIDATED marker and one synthesis dispatch are required")
            else:
                uv = uvs[0]
                if last_read is not None and last_read > _t(uv["t_call"]):
                    F.append(f"R7-W W5 {lab}: unit reads completed after units-validated (R19)")
                if not _t(uv["t_result"]) < _t(sd):
                    F.append(f"R7-W W5 {lab}: synthesis not dispatched strictly after unit validation (R19)")
                if produced is not None and _t(produced) < _t(sd):
                    F.append(f"R7-W W5 {lab}: synthesis output produced before synthesis dispatch")
                for u in [r for r, d in runs.items() if d["role"] == "UNIT"]:
                    rel = f"{U.LEDGER}/{u}/file-reading-records.jsonl"
                    if uv["printed_record_hashes"].get(rel) != _fsha(os.path.join(root, rel)):
                        F.append(f"R7-W W5 {lab}: {rel} ≠ the hash printed at units-validated")
        elif last_read is not None and produced is not None and last_read > _t(produced):
            F.append(f"R7-W W5 {lab}: reads completed after the object was produced")
        if produced is None:
            F.append(f"R7-W W5 {lab}: the final run has no witnessed output Write")
        elif assembled is not None and _t(produced) > _t(assembled["t_call"]):
            F.append(f"R7-W W5 {lab}: output produced after assembly")
        out[lab] = st
    if assembled is None or final_v is None:
        F.append("R7-W W5 batch: ASSEMBLED and FINAL-VALIDATED markers are required")
    elif _t(assembled["t_result"]) > _t(final_v["t_call"]):
        F.append("R7-W W5 batch: final validation before assembly completed")
    return out


# ---------------------------------------------------------------- witness bytes, digests, preservation (W7, W7a)
def witness_bytes(records):
    return "".join(json.dumps(r, sort_keys=True, separators=(",", ":"), ensure_ascii=False) + "\n" for r in records).encode("utf-8")


def digests(main_path, sub_dir, agent_ids, wbytes):
    tr = {"main": U.fsha(main_path)}
    for aid in sorted(agent_ids):
        for suf in (".jsonl", ".meta.json"):
            p = os.path.join(sub_dir, f"agent-{aid}{suf}")
            if os.path.isfile(p):
                tr[f"agent-{aid}{suf}"] = U.fsha(p)
    return {"decoder_sha256": U.fsha(os.path.join(_HERE, "p3b_transcript_syntax.py")),
            "extractor_sha256": U.fsha(os.path.abspath(__file__)), "transcripts": tr, "witness_sha256": _sha(wbytes)}


def monotonicity_violations(unit_bytes, final_bytes, unit_dig, final_dig):
    """W7a: W_unit ⊆ W_final byte-identically; unit agent transcripts unchanged; the same decoder/extractor."""
    F = []
    fl = set(final_bytes.decode("utf-8").splitlines())
    missing = [l for l in unit_bytes.decode("utf-8").splitlines() if l not in fl]
    if missing:
        F.append(f"R7-W W7a: {len(missing)} unit-freeze witness record(s) absent or altered in the final witness")
    for k in ("decoder_sha256", "extractor_sha256"):
        if unit_dig.get(k) != final_dig.get(k):
            F.append(f"R7-W W7a: {k} changed between the freezes")
    for name, h in (unit_dig.get("transcripts") or {}).items():
        if name != "main" and (final_dig.get("transcripts") or {}).get(name) != h:
            F.append(f"R7-W W7a: unit-freeze transcript {name} changed")
    return F
