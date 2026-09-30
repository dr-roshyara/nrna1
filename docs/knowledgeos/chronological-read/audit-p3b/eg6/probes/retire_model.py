#!/usr/bin/env python3
"""EG-6 v2 E-01: a REFERENCE MODEL of the crash-safe, resumable, write-once `retire` (design probe, scratchpad only;
not repository code). Synthetic temp trees only; uses the production U.legacy_digest / U.namespace_violations.
Run: cd <CR>/scripts/tests && PYTHONPATH=.:.. python3 -B <this file>"""
import errno, hashlib, json, os, shutil, tempfile
import p3b_s5_r7_universe as U

B = "OB9004"
RUNS = [f"{B}-R7-L01", f"{B}-R7-L02U01", f"{B}-R7-L02U02", f"{B}-R7-L02S", f"{B}-R7-L04"]
ASSEMBLY = f"{B}-R7"


class Refused(Exception):
    pass


class Crash(Exception):
    pass


def sha(p):
    return hashlib.sha256(open(p, "rb").read()).hexdigest()


def _dev(path):
    """the device of an existing directory (monkeypatched by the F-01 test to simulate a cross-device target)."""
    return os.stat(path).st_dev


def same_device_or_refuse(pairs, step):
    """F-01: every rename stays inside its own root; check st_dev of the source and of the target's parent for every
    pending pair BEFORE the first rename of the step, and refuse (class X, nothing moved in this step) otherwise."""
    for s, t in pairs:
        if os.path.exists(s) and not os.path.exists(t) and _dev(s) != _dev(os.path.dirname(t)):
            raise Refused(f"{step}: {os.path.basename(s)} → target on another device (class X; nothing moved)")


def safe_rename(s, t, step):
    try:
        os.rename(s, t)
    except OSError as e:
        if e.errno == errno.EXDEV:
            raise Refused(f"{step}: EXDEV renaming {os.path.basename(s)} (class X; resumable)")
        raise


def tree(base):
    return {os.path.relpath(os.path.join(b, f), base): sha(os.path.join(b, f))
            for b, _, fs in os.walk(base) for f in fs} if os.path.isdir(base) else {}


def put_once(path, obj):
    """write-once via tmp + fsync + rename; an existing file must be byte-identical."""
    data = (json.dumps(obj, sort_keys=True, separators=(",", ":")) + "\n").encode()
    if os.path.exists(path):
        if open(path, "rb").read() != data:
            raise Refused(f"{os.path.basename(path)} exists and differs")
        return
    tmp = path + ".tmp"
    with open(tmp, "wb") as f:
        f.write(data); f.flush(); os.fsync(f.fileno())
    os.replace(tmp, path)


def retire(ledger, archive, m, crash_at=None, _step=[0]):
    tgt = os.path.join(ledger, f"{B}-R7.A{m}")
    intent_p, done_p = os.path.join(tgt, "RETIREMENT-INTENT.json"), os.path.join(tgt, "RETIREMENT-COMPLETE.json")
    step = iter(range(100))

    def cp(name):                      # crash point
        if crash_at == name:
            raise Crash(name)

    if os.path.exists(done_p):
        return "ALREADY-COMPLETE"
    if not os.path.exists(intent_p):
        # ---- S0 preconditions + S1 durable intent (the only step that inspects the live footprint)
        if os.path.exists(tgt) and os.listdir(tgt):
            raise Refused("target exists without an intent record")
        srcs = [d for d in RUNS + [ASSEMBLY] if os.path.isdir(os.path.join(ledger, d))]
        arch_src = os.path.join(archive, B)
        if not os.path.isdir(arch_src):
            raise Refused("attempt archive absent: run `archive B` first")
        intent = {"batch": B, "attempt": m, "state": "PENDING",
                  "ledger": {d: tree(os.path.join(ledger, d)) for d in srcs},
                  "archive": {"from": f"{B}", "to": f"{B}.A{m}", "files": tree(arch_src)}}
        os.makedirs(tgt, exist_ok=True)
        put_once(intent_p, intent)
    intent = json.load(open(intent_p))
    cp("after-intent")
    # ---- S2 move ledger dirs (each os.rename is atomic); resumable per dir
    same_device_or_refuse([(os.path.join(ledger, d), os.path.join(tgt, d)) for d in sorted(intent["ledger"])], "S2")
    for d in sorted(intent["ledger"]):
        s, t = os.path.join(ledger, d), os.path.join(tgt, d)
        if os.path.isdir(s) and not os.path.exists(t):
            safe_rename(s, t, "S2")
        elif os.path.isdir(s) and os.path.exists(t):
            raise Refused(f"{d}: both source and target exist (inconsistent; class S)")
        elif not os.path.exists(t):
            raise Refused(f"{d}: neither source nor target exists (evidence lost; class S)")
        cp(f"after-move-{d}")
    # ---- S3 verify every moved byte against the intent's sha256s (no extra, no missing)
    for d, files in intent["ledger"].items():
        if tree(os.path.join(tgt, d)) != files:
            raise Refused(f"{d}: moved bytes ≠ the intent record (class S)")
    cp("after-ledger-verify")
    # ---- S4 move the archive (atomic dir rename; same filesystem required)
    a_s, a_t = os.path.join(archive, intent["archive"]["from"]), os.path.join(archive, intent["archive"]["to"])
    same_device_or_refuse([(a_s, a_t)], "S4")
    if os.path.isdir(a_s) and not os.path.exists(a_t):
        safe_rename(a_s, a_t, "S4")
    elif os.path.isdir(a_s) and os.path.exists(a_t):
        raise Refused("archive: both source and target exist (class S)")
    cp("after-archive-move")
    # ---- S5 verify the archive bytes
    if tree(a_t) != intent["archive"]["files"]:
        raise Refused("archive bytes ≠ the intent record (class S)")
    cp("after-archive-verify")
    # ---- S6 completion record (write-once); the manifest re-freeze + state new_attempt are separate governed acts
    put_once(done_p, {"batch": B, "attempt": m, "state": "COMPLETE", "intent_sha256": sha(intent_p),
                      "verified_files": sum(len(v) for v in intent["ledger"].values()) + len(intent["archive"]["files"])})
    return "COMPLETE"


def may_start_attempt(ledger, archive):
    """prepare / archive / dispatch precondition for attempt m+1."""
    for d in os.listdir(ledger):
        if d.startswith(f"{B}-R7.A") and os.path.exists(os.path.join(ledger, d, "RETIREMENT-INTENT.json")) \
                and not os.path.exists(os.path.join(ledger, d, "RETIREMENT-COMPLETE.json")):
            return False, f"retirement {d} PENDING"
    if os.path.isdir(os.path.join(archive, B)) and os.listdir(os.path.join(archive, B)):
        return False, "archive path <archive>/<B>/ non-empty"
    if any(os.path.exists(os.path.join(ledger, d)) for d in RUNS + [ASSEMBLY]):
        return False, "an active run or assembly directory still exists"
    return True, "ok"


def build():
    root = tempfile.mkdtemp(prefix="eg6ret-")
    ledger, archive = os.path.join(root, "ledger-p3b-r2"), os.path.join(root, "archive")
    for d in RUNS + [ASSEMBLY]:
        os.makedirs(os.path.join(ledger, d))
        open(os.path.join(ledger, d, "objects.jsonl"), "w").write(json.dumps({"d": d}) + "\n")
    open(os.path.join(ledger, ASSEMBLY, "WITNESS.jsonl"), "w").write("{}\n")
    os.makedirs(os.path.join(archive, B, "subagents"))
    open(os.path.join(archive, B, "main.jsonl"), "w").write("{}\n")
    open(os.path.join(archive, B, "subagents", "agent-x.jsonl"), "w").write("{}\n")
    return root, ledger, archive


def final_view(ledger, archive):
    return tree(ledger), tree(archive)


def main():
    root, ledger, archive = build()
    print("before retire: may start attempt 2 →", may_start_attempt(ledger, archive))
    print("clean run:", retire(ledger, archive, 1), "| may start →", may_start_attempt(ledger, archive))
    ref = final_view(ledger, archive)
    dig = U.legacy_digest(ledger, B, set(RUNS), ASSEMBLY)
    ns_listed = U.namespace_violations(ledger, B, set(RUNS), dig, [f"{B}-R7.A1"])
    ns_unlisted = U.namespace_violations(ledger, B, set(RUNS), hashlib.sha256().hexdigest(), [])
    print("namespace after COMPLETE, listed+re-frozen:", len(ns_listed), "| without the governed act:", len(ns_unlisted))
    shutil.rmtree(root)
    points = ["after-intent"] + [f"after-move-{d}" for d in sorted(RUNS + [ASSEMBLY])] + \
             ["after-ledger-verify", "after-archive-move", "after-archive-verify"]
    ok = 0
    for p in points:
        root, ledger, archive = build()
        try:
            retire(ledger, archive, 1, crash_at=p)
        except Crash:
            pass
        blocked, why = may_start_attempt(ledger, archive)
        res = retire(ledger, archive, 1)                         # resume
        same = final_view(ledger, archive) == ref
        again = retire(ledger, archive, 1)                       # idempotent
        ok += (not blocked) and res == "COMPLETE" and same and again == "ALREADY-COMPLETE"
        print(f"crash {p:34s} blocked-while-pending={not blocked!s:5s} ({why:40s}) resume={res} identical={same} rerun={again}")
        shutil.rmtree(root)
    # adversarial: bytes changed between intent and move; a source re-created after its move
    root, ledger, archive = build()
    try:
        retire(ledger, archive, 1, crash_at="after-intent")
    except Crash:
        pass
    open(os.path.join(ledger, RUNS[0], "objects.jsonl"), "a").write("tampered\n")
    try:
        retire(ledger, archive, 1); print("tamper: NOT detected")
    except Refused as e:
        print("tamper between intent and move → REFUSED:", e)
    shutil.rmtree(root)
    root, ledger, archive = build()
    try:
        retire(ledger, archive, 1, crash_at=f"after-move-{sorted(RUNS + [ASSEMBLY])[0]}")
    except Crash:
        pass
    os.makedirs(os.path.join(ledger, sorted(RUNS + [ASSEMBLY])[0]))       # e.g. a late reader call recreates it
    try:
        retire(ledger, archive, 1); print("re-created source: NOT detected")
    except Refused as e:
        print("source re-created after its move → REFUSED:", e)
    shutil.rmtree(root)
    print(f"crash points resumed to the identical final state: {ok}/{len(points)}")
    exdev_tests(ref)


def exdev_tests(ref):
    """F-01: a simulated EXDEV from os.rename (S2 and S4) and a simulated cross-device target (st_dev check): the
    step is REFUSED as class X, the retirement stays PENDING (attempt m+1 blocked), and after the cause is removed
    the retirement resumes to the byte-identical final state."""
    global _dev
    real_rename, real_dev = os.rename, _dev
    for name, victim in (("EXDEV at S2 (3rd ledger dir)", f"{sorted(RUNS + [ASSEMBLY])[2]}"), ("EXDEV at S4 (archive)", B)):
        root, ledger, archive = build()
        def boom(s, t, _v=victim):
            if os.path.basename(s) == _v:
                raise OSError(errno.EXDEV, "Invalid cross-device link")
            return real_rename(s, t)
        os.rename = boom
        try:
            retire(ledger, archive, 1); r1 = "NOT refused"
        except Refused as e:
            r1 = f"REFUSED ({e})"
        finally:
            os.rename = real_rename
        blocked = not may_start_attempt(ledger, archive)[0]
        res = retire(ledger, archive, 1)
        print(f"{name:32s} {r1} | blocked={blocked} | resume after cause removed={res} identical={final_view(ledger, archive) == ref}")
        shutil.rmtree(root)
    root, ledger, archive = build()
    arch_root = archive
    _dev = lambda p: 999 if os.path.abspath(p) == os.path.abspath(arch_root) else real_dev(p)
    try:
        retire(ledger, archive, 1); r1 = "NOT refused"
    except Refused as e:
        r1 = f"REFUSED ({e})"
    moved_archive = os.path.isdir(os.path.join(archive, B + ".A1"))
    _dev = real_dev
    blocked = not may_start_attempt(ledger, archive)[0]
    res = retire(ledger, archive, 1)
    print(f"{'st_dev mismatch at S4':32s} {r1} | archive moved before refusal={moved_archive} | blocked={blocked} | "
          f"resume={res} identical={final_view(ledger, archive) == ref}")
    shutil.rmtree(root)


if __name__ == "__main__":
    main()
