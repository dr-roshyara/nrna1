#!/usr/bin/env python3
"""Synthetic Claude Code harness transcripts for R7 tests (test helper; no corpus, no network).

Record shapes follow the structures observed in the Model-D readiness experiment:
  subagents/agent-<id>.jsonl   user/assistant records with uuid/parentUuid/agentId/sessionId/timestamp; tool_use blocks
                               in assistant messages; tool_result blocks (+ record-level toolUseResult: dict on success,
                               "Error: ..." string on error) in user messages
  subagents/agent-<id>.meta.json  {agentType, description, toolUseId, ...}
  main.jsonl                   orchestrator: Agent tool_use (dispatch prompt), Bash tool_use (orchestrator markers),
                               queue-operation enqueue/remove records whose content is a <task-notification>
"""
import datetime
import hashlib
import json
import os

SESSION = "sess-0000-synthetic"
T0 = datetime.datetime(2026, 10, 1, 9, 0, 0, tzinfo=datetime.timezone.utc)


def ts(sec):
    return (T0 + datetime.timedelta(seconds=sec)).strftime("%Y-%m-%dT%H:%M:%S.") + f"{int((sec % 1) * 1000):03d}Z"


def uid(*parts):
    return hashlib.sha256("|".join(map(str, parts)).encode()).hexdigest()[:32]


# ---------------------------------------------------------------- call specs (tool_use + tool_result)
def bash(command, out, exit_code=0, persist_as=None, dt=1.0):
    return {"name": "Bash", "input": {"command": command, "description": "run"}, "out": out, "exit": exit_code,
            "persist": persist_as, "dt": dt}


def denied_bash(command):
    return {"name": "Bash", "input": {"command": command, "description": "run"}, "denied": True, "dt": 1.0}


def read(path, out, offset=None, limit=None, persist_as=None, dt=1.0):
    inp = {"file_path": path}
    if offset is not None:
        inp["offset"] = offset
    if limit is not None:
        inp["limit"] = limit
    return {"name": "Read", "input": inp, "out": out, "exit": 0, "persist": persist_as, "dt": dt}


def write(path, content, dt=1.0):
    return {"name": "Write", "input": {"file_path": path, "content": content}, "out": f"File created successfully at: {path}",
            "exit": 0, "dt": dt}


def handback(text, dt=1.0):
    return {"name": "SubagentHandback", "input": {"report": text}, "out": "delivered", "exit": 0, "dt": dt}


def other(name, inp, dt=1.0):
    return {"name": name, "input": inp, "out": "ok", "exit": 0, "dt": dt}


def cat_n(text, offset=1):
    """The harness Read view: `<n>\\t<line>` per line (line numbers right-aligned to width 6)."""
    return "".join(f"{i:>6}\t{l}\n" for i, l in enumerate(text.split("\n"), offset) if not (l == "" and i == offset + len(text.split("\n")) - 1))


def _records_for(agent_id, calls, prompt, start):
    recs, parent, t = [], None, start

    def add(r):
        nonlocal parent
        r.update(uuid=uid(agent_id, len(recs), r["type"]), parentUuid=parent, sessionId=SESSION, timestamp=ts(r.pop("_t")))
        if agent_id:
            r["agentId"] = agent_id
        parent = r["uuid"]
        recs.append(r)
    add({"type": "user", "message": {"role": "user", "content": prompt}, "_t": t})
    for n, c in enumerate(calls):
        t += 0.5
        tid = f"toolu_{uid(agent_id, n)[:20]}"
        add({"type": "assistant", "message": {"role": "assistant", "model": "claude-sonnet-5",
                                              "content": [{"type": "tool_use", "id": tid, "name": c["name"], "input": c["input"]}]},
             "_t": t})
        t += c.get("dt", 1.0)
        if c.get("denied"):
            txt = "Permission for this action was denied by the Claude Code auto mode classifier. Reason: [x]."
            add({"type": "user", "message": {"role": "user", "content": [{"type": "tool_result", "tool_use_id": tid,
                                                                          "content": txt, "is_error": True}]},
                 "toolUseResult": "Error: " + txt, "_t": t})
            continue
        out, ex = c["out"], c.get("exit", 0)
        if c.get("persist"):
            shown = (f"<persisted-output>\nOutput too large ({len(out)} B). Full output saved to: "
                     f"/harness/session/tool-results/{c['persist']}\n\nPreview (first 2KB):\n{out[:40]}\n</persisted-output>")
        else:
            shown = out
        if ex:
            txt = f"Exit code {ex}\n{shown}"
            add({"type": "user", "message": {"role": "user", "content": [{"type": "tool_result", "tool_use_id": tid,
                                                                          "content": txt, "is_error": True}]},
                 "toolUseResult": "Error: " + txt, "_t": t})
        else:
            add({"type": "user", "message": {"role": "user", "content": [{"type": "tool_result", "tool_use_id": tid,
                                                                          "content": shown, "is_error": False}]},
                 "toolUseResult": {"stdout": shown, "stderr": "", "interrupted": False, "isImage": False}, "_t": t})
    t += 0.5
    add({"type": "assistant", "message": {"role": "assistant", "model": "claude-sonnet-5",
                                          "content": [{"type": "text", "text": "finished"}]}, "_t": t})
    return recs, t


def write_agent(root, agent_id, dispatch_tool_use_id, calls, prompt="task", start=10.0):
    """Writes <root>/subagents/agent-<id>.jsonl + .meta.json; returns the transcript path."""
    d = os.path.join(root, "subagents")
    os.makedirs(d, exist_ok=True)
    recs, _ = _records_for(agent_id, calls, prompt, start)
    p = os.path.join(d, f"agent-{agent_id}.jsonl")
    with open(p, "w", encoding="utf-8") as f:
        f.write("".join(json.dumps(r, ensure_ascii=False) + "\n" for r in recs))
    with open(os.path.join(d, f"agent-{agent_id}.meta.json"), "w", encoding="utf-8") as f:
        json.dump({"agentType": "general-purpose", "description": "s5", "toolUseId": dispatch_tool_use_id,
                   "spawnDepth": 1, "requestShape": "background"}, f)
    return p


def agent_last_t(calls, start=10.0):
    return _records_for("x", calls, "p", start)[1]


# ---------------------------------------------------------------- orchestrator (main) transcript items
def main_dispatch(tool_use_id, prompt, t):
    return {"kind": "dispatch", "id": tool_use_id, "prompt": prompt, "t": t}


def main_marker(command, out, t, dt=1.0):
    return {"kind": "marker", "command": command, "out": out, "t": t, "dt": dt}


def main_notification(task_id, tool_use_id, tool_uses, op="enqueue", status="completed", t=5000.0):
    return {"kind": "notification", "task_id": task_id, "tool_use_id": tool_use_id, "tool_uses": tool_uses, "op": op,
            "status": status, "t": t}


def main_agent_message(agent_id, t=5000.0):
    return {"kind": "agent-message", "agent_id": agent_id, "t": t}


def write_main(root, items):
    recs, parent = [], None

    def add(r, t):
        nonlocal parent
        r.update(uuid=uid("main", len(recs)), parentUuid=parent, sessionId=SESSION, timestamp=ts(t))
        parent = r["uuid"]
        recs.append(r)
    for n, it in enumerate(items):
        if it["kind"] == "dispatch":
            add({"type": "assistant", "message": {"role": "assistant", "content": [
                {"type": "tool_use", "id": it["id"], "name": "Agent",
                 "input": {"description": "s5 run", "prompt": it["prompt"], "subagent_type": "general-purpose"}}]}}, it["t"])
            add({"type": "user", "message": {"role": "user", "content": [
                {"type": "tool_result", "tool_use_id": it["id"], "content": "Async agent launched successfully.", "is_error": False}]}},
                it["t"] + 0.2)
        elif it["kind"] == "marker":
            tid = f"toolu_m{n:04d}"
            add({"type": "assistant", "message": {"role": "assistant", "content": [
                {"type": "tool_use", "id": tid, "name": "Bash", "input": {"command": it["command"], "description": "marker"}}]}}, it["t"])
            add({"type": "user", "message": {"role": "user", "content": [
                {"type": "tool_result", "tool_use_id": tid, "content": it["out"], "is_error": False}]},
                "toolUseResult": {"stdout": it["out"], "stderr": ""}}, it["t"] + it.get("dt", 1.0))
        elif it["kind"] == "notification":
            usage = (f"<usage><subagent_tokens>1</subagent_tokens><tool_uses>{it['tool_uses']}</tool_uses>"
                     f"<duration_ms>1</duration_ms></usage>\n" if it["tool_uses"] is not None
                     else "<usage><subagent_tokens>1</subagent_tokens></usage>\n")
            content = (f"<task-notification>\n<task-id>{it['task_id']}</task-id>\n<tool-use-id>{it['tool_use_id']}</tool-use-id>\n"
                       f"<output-file>/tmp/x/tasks/{it['task_id']}.output</output-file>\n<status>{it['status']}</status>\n"
                       f"<summary>Agent \"s5\" finished</summary>\n<result>report</result>\n" + usage + "</task-notification>")
            recs.append({"type": "queue-operation", "operation": it["op"], "timestamp": ts(it["t"]), "sessionId": SESSION,
                         "content": content})
        elif it["kind"] == "agent-message":
            recs.append({"type": "queue-operation", "operation": "enqueue", "timestamp": ts(it["t"]), "sessionId": SESSION,
                         "content": f"<agent-message from=\"{it['agent_id']}\">\n[Subagent hand-back] report\n"})
    p = os.path.join(root, "main.jsonl")
    with open(p, "w", encoding="utf-8") as f:
        f.write("".join(json.dumps(r, ensure_ascii=False) + "\n" for r in recs))
    return p
