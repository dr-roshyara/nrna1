#!/usr/bin/env python3
"""EG-6 option (b) probes: a retired attempt directory `<B>-R7.A1/` as a governed Legacy(B) member, through the
PRODUCTION universe/verifier on the SYNTHETIC r7_full_fixture. Read-only imports; temp dirs only; prints no S-id and
no label name. Run: cd <CR>/scripts/tests && PYTHONPATH=.:.. python3 -B <this file>"""
import collections, os, re, shutil, tempfile
import r7_full_fixture as FX
import p3b_s5_r7_universe as U
import p3b_read_source as RS

OB, R7 = FX.OB, FX.R7
RETIRED = f"{OB}-R7.A1"
LABS = None


def mask(s):
    s = re.sub(r"S\d{4}", "S####", str(s))
    for l in LABS or []:
        s = s.replace(l, "<label>")
    return s


def show(name, b):
    kinds = collections.Counter(mask(f).split(" (")[0][:90] for f in b.get("failures") or [])
    print(f"{name:50s} {b['result']:18s} {dict(kinds)}")


def retired_tree(base, plan):
    """A synthetic stand-in for attempt 1's ledger: every planned run dir + the assembly dir, moved under RETIRED."""
    files = {}
    for run in sorted(U.run_owner(plan)):
        files[f"{RETIRED}/{run}/objects.jsonl"] = b'{"attempt":1}\n'
        files[f"{RETIRED}/{run}/READ-LOG.jsonl"] = b""
    files[f"{RETIRED}/{R7}/WITNESS-DIGESTS.json"] = b'{"attempt":1}'
    for rel, data in files.items():
        p = os.path.join(base, rel)
        os.makedirs(os.path.dirname(p), exist_ok=True)
        open(p, "wb").write(data)
    return files


def digest_of(plan, extra=None):
    d = tempfile.mkdtemp(prefix="eg6dig-")
    try:
        retired_tree(d, plan)
        if extra:
            extra(d)
        return U.legacy_digest(d, OB, set(U.run_owner(plan)), R7)
    finally:
        shutil.rmtree(d)


def main():
    global LABS
    st = FX.base()
    LABS = st["ctx"]["labels"]
    plan = st["plan"]
    empty = __import__("hashlib").sha256().hexdigest()
    dig = digest_of(plan)
    print("reader grammar accepts retired name as a run id:", re.fullmatch(RS.R7_RUN, RETIRED) is not None,
          "| nested retired run path:", re.fullmatch(RS.R7_RUN, f"{RETIRED}/{OB}-R7-L01") is not None)
    print("canonical reader accepts --run", RETIRED, ":",
          U.canonical_reader("/abs/reader.py").match(f"python3 -B /abs/reader.py --run {RETIRED} --batch {OB} --label x "
                                                     f"--step 1 --mode bytes --page 1 S0001") is not None)

    def plant(root, adir, info):
        retired_tree(os.path.join(root, U.LEDGER), plan)

    show("baseline (attempt 1 = only attempt)", FX.verify(st))
    s = dict(st, post=plant, entry_extra={"legacy_dirs": [RETIRED], "legacy_ledger_sha256": dig})
    show("(b) attempt 2 + retired A1 listed, digest re-frozen", FX.verify(s))
    s = dict(st, post=plant, entry_extra={"legacy_dirs": [], "legacy_ledger_sha256": empty})
    show("(b) retired A1 present but NOT listed (no act)", FX.verify(s))
    s = dict(st, post=plant, entry_extra={"legacy_dirs": [RETIRED], "legacy_ledger_sha256": empty})
    show("(b) listed, digest NOT re-frozen", FX.verify(s))

    def plant_tampered(root, adir, info):
        plant(root, adir, info)
        run = sorted(U.run_owner(plan))[0]
        open(os.path.join(root, U.LEDGER, RETIRED, run, "objects.jsonl"), "wb").write(b'{"attempt":"edited"}\n')
    s = dict(st, post=plant_tampered, entry_extra={"legacy_dirs": [RETIRED], "legacy_ledger_sha256": dig})
    show("(b) retired evidence edited after the act", FX.verify(s))

    def plant_deleted(root, adir, info):
        plant(root, adir, info)
        shutil.rmtree(os.path.join(root, U.LEDGER, RETIRED, f"{R7}"))
    s = dict(st, post=plant_deleted, entry_extra={"legacy_dirs": [RETIRED], "legacy_ledger_sha256": dig})
    show("(b) retired evidence partly deleted", FX.verify(s))

    def plant_attempt_dir(root, adir, info):
        p = os.path.join(root, U.LEDGER, f"{OB}-R7.2-L01")
        os.makedirs(p, exist_ok=True)
        open(os.path.join(p, "objects.jsonl"), "wb").write(b"{}\n")
    s = dict(st, post=plant_attempt_dir)
    show("(a) an attempt-suffixed run dir <B>-R7.2-L01 today", FX.verify(s))


if __name__ == "__main__":
    main()
