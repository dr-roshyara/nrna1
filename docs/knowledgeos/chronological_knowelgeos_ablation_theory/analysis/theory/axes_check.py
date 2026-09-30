"""Small transparent check of the Knowledge-Metamodel §6 lifecycle axes (released L104) for a Learning object.
Axes as stated: authority generated->...->authoritative->historical; status idea->...->frozen/sealed | superseded->archived;
maturity Candidate->Observed->Validated->Standard. Only declared illegal combination: frozen+generated.
The unstated parts ('...', 'frozen/sealed', where 'superseded' branches from) are VARIANTS; a result counts only if it holds
in every variant. Moves: one axis advances one step (the source states forward orders only). Deterministic; stdlib."""
import itertools

AUTH = {"generated": ["authoritative"], "authoritative": ["historical"], "historical": []}
MAT = {"Candidate": ["Observed"], "Observed": ["Validated"], "Validated": ["Standard"], "Standard": []}
STATUS_VARIANTS = {  # the source's "idea → … → frozen/sealed | superseded → archived", read three ways
    "V1 frozen,sealed alternatives; superseded from either": {"idea": ["frozen", "sealed"], "frozen": ["superseded"], "sealed": ["superseded"], "superseded": ["archived"], "archived": []},
    "V2 frozen->sealed chain; superseded from sealed": {"idea": ["frozen"], "frozen": ["sealed"], "sealed": ["superseded"], "superseded": ["archived"], "archived": []},
    "V3 superseded reachable from idea too": {"idea": ["frozen", "sealed", "superseded"], "frozen": ["superseded"], "sealed": ["superseded"], "superseded": ["archived"], "archived": []},
}
ILLEGAL = lambda a, s, m: a == "generated" and s == "frozen"


def explore(STAT):
    start = ("generated", "idea", "Candidate"); seen = {start}; frontier = [start]; edges = []
    while frontier:
        a, s, m = frontier.pop()
        succ = [(na, s, m) for na in AUTH[a]] + [(a, ns, m) for ns in STAT[s]] + [(a, s, nm) for nm in MAT[m]]
        for y in succ:
            if ILLEGAL(*y): continue
            edges.append(((a, s, m), y))
            if y not in seen: seen.add(y); frontier.append(y)
    return seen, edges


def main():
    report = {}
    for name, STAT in STATUS_VARIANTS.items():
        seen, edges = explore(STAT)
        allc = [c for c in itertools.product(AUTH, STAT, MAT) if not ILLEGAL(*c)]
        # derived ordering: can a frozen state be reached while authority is still 'generated'? (never, by ILLEGAL) — and
        # is every reachable frozen state entered only after authority left 'generated'?
        frozen_entries = [(x, y) for x, y in edges if y[1] == "frozen" and x[1] != "frozen"]
        auth_before_freeze = all(x[0] != "generated" for x, y in frozen_entries)
        sealed_with_generated = any(c[0] == "generated" and c[1] == "sealed" for c in seen)
        undetermined = [c for c in allc if c in seen]  # reachable combinations whose legitimacy no released rule decides
        report[name] = {"combinations_total_legal": len(allc), "reachable": len(seen), "frozen_entry_edges": len(frozen_entries),
                        "authority_leaves_generated_before_freeze": auth_before_freeze,
                        "generated+sealed_reachable": sealed_with_generated,
                        "authoritative+archived_reachable": any(c[0] == "authoritative" and c[1] == "archived" for c in seen),
                        "historical+idea_reachable": any(c[0] == "historical" and c[1] == "idea" for c in seen),
                        "Standard+idea_reachable": any(c[2] == "Standard" and c[1] == "idea" for c in seen),
                        "undetermined_reachable_combinations": len(undetermined)}
    for k, v in report.items(): print(k, v)
    keys = [k for k in next(iter(report.values())) if isinstance(next(iter(report.values()))[k], bool)]
    print("ROBUST (same in all variants):", {k: report[next(iter(report))][k] for k in keys if len({r[k] for r in report.values()}) == 1})


if __name__ == "__main__":
    main()
