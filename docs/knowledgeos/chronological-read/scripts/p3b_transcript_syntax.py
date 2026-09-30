#!/usr/bin/env python3
"""Neutral transcript syntax decoder for Claude Code harness transcripts (JSONL).

Purely syntactic: it decodes records, pairs tool calls with their results, exposes the record chain, and reads the
harness's own completion notifications and subagent metadata. It knows nothing about any research protocol and applies
no policy; semantics live in the consumer. A line that is not valid JSON is recorded as a parse error, never skipped
silently. Deterministic: the same bytes give the same decoded structure.
"""
import collections
import hashlib
import json
import os
import re

Transcript = collections.namedtuple("Transcript", "path records parse_errors sha256")


class Call:
    __slots__ = ("id", "name", "input", "t_call", "uuid_call", "t_result", "uuid_result", "text", "is_error",
                 "exit_code", "tool_use_result", "persisted_path", "index")

    def __init__(self, **kw):
        for k in self.__slots__:
            setattr(self, k, kw.get(k))

    def as_dict(self):
        return {k: getattr(self, k) for k in self.__slots__}


PERSISTED = re.compile(r"<persisted-output>.*?Full output saved to: (\S+)", re.S)
EXIT = re.compile(r"\AExit code (\d+)\b")


def decode(path):
    with open(path, "rb") as f:
        raw = f.read()
    records, errors = [], []
    for n, line in enumerate(raw.decode("utf-8", errors="replace").split("\n"), 1):
        if not line.strip():
            continue
        try:
            r = json.loads(line)
        except ValueError:
            errors.append(n)
            continue
        if isinstance(r, dict):
            records.append(r)
        else:
            errors.append(n)
    return Transcript(path, records, errors, hashlib.sha256(raw).hexdigest())


def blocks(record, kind):
    m = record.get("message") if isinstance(record.get("message"), dict) else {}
    c = m.get("content")
    return [b for b in (c if isinstance(c, list) else []) if isinstance(b, dict) and b.get("type") == kind]


def _text(content):
    if isinstance(content, str):
        return content
    if isinstance(content, list):
        return "".join(x.get("text", "") for x in content if isinstance(x, dict))
    return None


def calls(tr, persisted_root=None):
    """All tool calls in record order, each paired with its tool_result (None fields if the result is absent).
    persisted_root: a directory holding the harness's persisted outputs (matched by file name)."""
    results = {}
    for r in tr.records:
        for b in blocks(r, "tool_result"):
            results.setdefault(b.get("tool_use_id"), (r, b))
    out = []
    for r in tr.records:
        for b in blocks(r, "tool_use"):
            rr, rb = results.get(b.get("id"), (None, None))
            text = _text(rb.get("content")) if rb else None
            persisted = None
            if text is not None:
                m = PERSISTED.search(text)
                if m:
                    persisted = m.group(1)
                    cand = os.path.join(persisted_root, os.path.basename(persisted)) if persisted_root else persisted
                    if os.path.isfile(cand):
                        with open(cand, encoding="utf-8", errors="replace") as f:
                            body = f.read()
                        ex = EXIT.match(text)
                        text = (f"Exit code {ex.group(1)}\n" if ex else "") + body
            is_err = bool(rb.get("is_error")) if rb else None
            ex = EXIT.match(text) if (text is not None and is_err) else None
            out.append(Call(id=b.get("id"), name=b.get("name"), input=dict(b.get("input") or {}), t_call=r.get("timestamp"),
                            uuid_call=r.get("uuid"), t_result=rr.get("timestamp") if rr else None,
                            uuid_result=rr.get("uuid") if rr else None, text=text, is_error=is_err,
                            exit_code=(int(ex.group(1)) if ex else (0 if is_err is False else None)),
                            tool_use_result=rr.get("toolUseResult") if rr else None, persisted_path=persisted,
                            index=len(out)))
    return out


def persisted_announced(cs):
    """[(call index, file name)] of persisted outputs announced by the harness in these calls' results."""
    return [(c.index, os.path.basename(c.persisted_path)) for c in cs if c.persisted_path]


def chain(tr):
    """Structural record chain: every parentUuid must name an earlier record's uuid; uuids must be unique."""
    seen, missing, dup = set(), [], []
    for i, r in enumerate(tr.records):
        u, p = r.get("uuid"), r.get("parentUuid")
        if u is None:
            continue
        if u in seen:
            dup.append(i)
        if p is not None and p not in seen:
            missing.append(i)
        seen.add(u)
    return {"parent_missing": missing, "dup_uuid": dup}


def tool_use_count(tr):
    return sum(len(blocks(r, "tool_use")) for r in tr.records)


def ids(tr):
    a = sorted({r["agentId"] for r in tr.records if r.get("agentId")})
    s = sorted({r["sessionId"] for r in tr.records if r.get("sessionId")})
    return a, s


NOTE = re.compile(r"\A<task-notification>\s*<task-id>([^<]+)</task-id>\s*<tool-use-id>([^<]*)</tool-use-id>.*?"
                  r"<status>([^<]+)</status>", re.S)
USES = re.compile(r"<tool_uses>(\d+)</tool_uses>")


def notifications(main_tr):
    """The harness's completion notifications (queue-operation enqueue records carrying a <task-notification>)."""
    out = []
    for r in main_tr.records:
        if r.get("type") != "queue-operation" or r.get("operation") != "enqueue" or not isinstance(r.get("content"), str):
            continue
        m = NOTE.match(r["content"])
        if m:
            u = USES.search(r["content"])
            out.append({"task_id": m.group(1), "tool_use_id": m.group(2), "status": m.group(3),
                        "tool_uses": int(u.group(1)) if u else None, "t": r.get("timestamp")})
    return out


def read_meta(path):
    with open(path, encoding="utf-8") as f:
        return json.load(f)
