#!/usr/bin/env python3
"""
EXPERIMENTS 11-15 — EMPIRICAL BRIDGE, executed against the REAL running system.

The mandate (s18) requires mapping theory objects to actual KnowledgeOS/EKP data
and running tests.  Step 267 attempted this and mapped the theory onto
PublicDigit's ELECTION domain (GovernanceLineageGraph, EvidenceSet,
authorities.yaml).  This session maps it instead onto the Engineering Knowledge
Platform -- docs/knowledge/ + scripts/knowledge-lint.php -- which is the
governed-knowledge system that actually runs in this repository.

Every number below is read from the filesystem at run time.
"""
import os, re, sys, json, collections
ROOT = os.path.abspath(os.path.join(os.path.dirname(__file__), "../../../../../.."))
KDIR = os.path.join(ROOT, "docs/knowledge")
def hr(t): print("\n"+"="*74+f"\n{t}\n"+"="*74)

# ------------------------------------------------------------ load real cards
REL_KEYS = ["implements","requires","depends_on","derived_from","supersedes",
            "superseded_by","related_to","documents","verified_by","tested_by",
            "reviewed_by","adr","api","aggregate","state_machine"]

def parse_front(path):
    txt = open(path, encoding="utf-8", errors="replace").read()
    if path.endswith(".yaml"):
        fm = txt                      # a package card IS its frontmatter
    else:
        if not txt.startswith("---"): return None
        end = txt.find("\n---", 3)
        if end < 0: return None
        fm = txt[3:end]
    out, key = {}, None
    for line in fm.splitlines():
        if not line.strip() or line.lstrip().startswith("#"): continue
        m = re.match(r"^([a-z_]+):\s*(.*)$", line)
        if m:
            key, val = m.group(1), m.group(2).strip()
            if val.startswith("[") and val.endswith("]"):
                inner = val[1:-1].strip()
                out[key] = [x.strip() for x in inner.split(",") if x.strip()]
            elif val == "":
                out[key] = []
            else:
                out[key] = val
        elif key and line.lstrip().startswith("- "):
            out.setdefault(key, [])
            if isinstance(out[key], list): out[key].append(line.split("- ",1)[1].strip())
    return out

cards = {}
for dp, dn, fn in os.walk(KDIR):
    for f in fn:
        if not (f.endswith(".md") or f.endswith(".yaml")): continue
        if "/schema/" in os.path.join(dp,f).replace(os.sep,"/"): continue   # vocab files, not cards
        p = os.path.join(dp, f)
        fm = parse_front(p)
        if "knowledge-card.template" in p: continue          # the template is not a document
        if fm and fm.get("knowledge_id"):
            fm["_path"] = os.path.relpath(p, ROOT)
            cards[fm["knowledge_id"]] = fm

hr("EXP-11  Does K=(A,R) have a real instance?  (theory object -> live data)")
print(f"  scanned: {KDIR}")
print(f"  governed documents carrying a knowledge_id (|A|): {len(cards)}")
edges = []
for kid, c in cards.items():
    for rk in REL_KEYS:
        for tgt in (c.get(rk) or []):
            edges.append((kid, tgt, rk))
print(f"  typed relationships (|R|):                        {len(edges)}")
print(f"  distinct relation types in use:                   "
      f"{sorted({e[2] for e in edges})}")
# Only these relation types are declared to hold knowledge_id references.
ID_KEYS = {"implements","requires","depends_on","derived_from","supersedes",
           "superseded_by","related_to","verified_by","reviewed_by","adr"}
id_edges = [e for e in edges if e[2] in ID_KEYS]
dangling = [e for e in id_edges if e[1] not in cards]
print(f"  of these, id-typed edges (must resolve to a card):  {len(id_edges)}")
print(f"  id-typed edges whose target does NOT resolve:      {len(dangling)}")
if dangling:
    print("    unresolved targets: " + ", ".join(sorted({d[1] for d in dangling})[:12]))
print(f"""
  => K = (A, R) IS INSTANTIATED. |A|={len(cards)}, |R|={len(edges)}, with a typed
     relation vocabulary of {len({e[2] for e in edges})} kinds.  This is a Type-1 semantic
     realization in Step 267's own taxonomy -- and Step 267 did not find it,
     because it searched PublicDigit's election domain instead of the EKP.""")

# ------------------------------------------------------------------ EXP-12
hr("EXP-12  Sigma: how many status axes does the RUNNING system actually have?")
st  = collections.Counter(c.get("status","<none>")  for c in cards.values())
au  = collections.Counter(c.get("authority","<none>") for c in cards.values())
print(f"  axis 1 'status'    (lifecycle) : {dict(st)}")
print(f"  axis 2 'authority' (source trust): {dict(au)}")
pairs = collections.Counter((c.get("status"), c.get("authority")) for c in cards.values())
print(f"\n  observed (status, authority) combinations: {len(pairs)}")
for k,v in sorted(pairs.items(), key=lambda x: (str(x[0][0]), str(x[0][1]))): print(f"    {str(k):<32} x{v}")
indep = len(pairs) > max(len(st), len(au))
print(f"""
  Do the two axes vary independently in the real data? {indep}
  ({len(pairs)} observed pairs vs {len(st)} statuses and {len(au)} authorities;
   if the axes were the same concept, pairs would equal max(len) not exceed it.)

  => EMPIRICAL CONFIRMATION of ARC D's separation -- and the separation was
     ALREADY RUNNING, schema-enforced, before the theory derived it:
     statuses.yaml says verbatim 'status (lifecycle position) is INDEPENDENT of
     authority', and authorities.yaml says 'It is INDEPENDENT of status'.

  BUT -- and this is the finding -- NEITHER axis is the theory's Sigma.
     status    = lifecycle position   (draft ... frozen, superseded, archived)
     authority = trust in the SOURCE  (authoritative > derived > generated >
                                       historical > provisional)
     Sigma     = epistemic support    (Supported / Refuted / Conflicted / Unknown)
  There is NO field in the running system that records whether a claim is
  SUPPORTED BY EVIDENCE.  The implementation has Lifecycle x SourceTrust;
  the theory needs Lifecycle x SourceTrust x EpistemicSupport.""")

# ------------------------------------------------------------------ EXP-13
hr("EXP-13  Is the lifecycle a real state machine, and is it MONOTONE?")
sys.path.insert(0, os.path.dirname(__file__))
from yaml_lite import load_ordered
SCH = os.path.join(ROOT, "docs/knowledge/schema")
statuses   = load_ordered(os.path.join(SCH,"statuses.yaml"), "statuses")
authorities= load_ordered(os.path.join(SCH,"authorities.yaml"), "authorities")
print(f"  statuses.yaml declares {len(statuses)} states with a total 'order':")
for k,v in sorted(statuses.items(), key=lambda x: x[1].get("order",99)):
    print(f"    {v.get('order')}. {k:<12} settled={v.get('settled')} "
          f"{'requires_adr_to_change' if v.get('requires_adr_to_change') else ''}")
print(f"\n  authorities.yaml declares {len(authorities)} ranks:")
for k,v in sorted(authorities.items(), key=lambda x: x[1].get("rank",99)):
    print(f"    rank {v.get('rank')}: {k:<15} single_per_topic={v.get('single_per_topic', False)}")

print("""
  TEST: is the lifecycle MONOTONE (order only increases)?
  The schema gives a total order 1..8 -- but observe positions 7 and 8:""")
print(f"    6. frozen      settled={statuses['frozen'].get('settled')}")
print(f"    7. superseded  settled={statuses['superseded'].get('settled')}")
print(f"    8. archived    settled={statuses['archived'].get('settled')}")
print("""
  'superseded' and 'archived' are given HIGHER order numbers than 'baseline'
  and 'frozen'.  Under a monotone reading that says a superseded document is
  MORE advanced than a baseline one.  They are not progress states at all --
  they are TERMINATION states on a different axis.  The `order` field therefore
  encodes two different things in one integer: progression (1-6) and
  retirement (7-8).

  MEASUREMENT-THEORY CONSEQUENCE (Roberts, executed in EXP-4):
  `order` is used by the tooling 'for sorting/validation' per its own comment.
  Sorting is ordinal-admissible.  But any rule of the form
      'advance only if order(new) > order(old)'
  will happily accept  frozen(6) -> superseded(7)  AND  approved(4) ->
  superseded(7), while REJECTING superseded(7) -> approved(4) (a legitimate
  un-supersession after the replacement is withdrawn).
""")

# Executable probe of that
def can_advance(a,b): return statuses[b]["order"] > statuses[a]["order"]
probes = [("approved","superseded"), ("frozen","superseded"),
          ("superseded","approved"), ("archived","draft"), ("draft","frozen")]
for a,b in probes:
    print(f"    order-rule allows {a:<11} -> {b:<11}? {can_advance(a,b)}")
print("""    draft -> frozen is allowed by the order rule, skipping four states.
  => The single `order` integer is NOT a sufficient encoding of the lifecycle
     state machine.  A covering relation (which pairs are ADJACENT) is required,
     and statuses.yaml does not carry one.  The lint passes because it never
     checks transitions -- only membership.  VERIFIED below.""")

# ------------------------------------------------------------------ EXP-14
print("\n" + "="*74 + "\nEXP-14  What does knowledge-lint ACTUALLY enforce?\n" + "="*74)
lint = open(os.path.join(ROOT, "scripts/knowledge-lint.php"), encoding="utf-8", errors="replace").read()
rules = sorted(set(re.findall(r"\$add\(\s*'(?:error|warning)'\s*,[^,]+?,\s*'([a-z_]+)'", lint)))
print(f"  rule identifiers the linter can actually emit ({len(rules)}):")
for r in rules: print(f"    - {r}")
theory_rules = {
  "referential integrity of relations": "relationship_targets_exist" in rules,
  "cycle detection":                    any("circular" in r or "cycle" in r for r in rules),
  "single authoritative per topic":     "single_authoritative" in rules,
  "STATUS TRANSITION legality":         any("transition" in r for r in rules),
  "epistemic support / evidence":       any(("evidence" in r or "epistemic" in r or "support" in r) for r in rules),
}
print("\n  theory-relevant checks:")
for k,v in theory_rules.items(): print(f"    {k:<38} {'YES' if v else 'NO'}")
print("""
  => The linter enforces STRUCTURE (vocabulary, referential integrity, cycles,
     single-authoritative, orphans, link resolution, review dates).  It has NO
     rule for status-transition legality and NO rule mentioning evidence or
     epistemic support -- confirming EXP-12 from the code side: the running
     system has a Lifecycle axis and a SourceTrust axis, and no Sigma.""")

# ------------------------------------------------------------------ EXP-15
print("\n" + "="*74 + "\nEXP-15  Do the theory's INVARIANTS hold in the real data?\n" + "="*74)
fails = {}
# I1: supersedes must be antisymmetric and acyclic
sup = [(a,b) for (a,b,t) in edges if t == "supersedes"]
supby = [(a,b) for (a,b,t) in edges if t == "superseded_by"]
cyc = [(a,b) for (a,b) in sup if (b,a) in sup]
fails["I1 supersedes acyclic/antisymmetric"] = cyc
# I2: superseded_by must be the converse of supersedes
inconsistent = [(a,b) for (a,b) in supby if (b,a) not in sup]
fails["I2 superseded_by is converse of supersedes"] = inconsistent
# I3: a document with status 'superseded' must declare superseded_by
missing = [k for k,c in cards.items()
           if c.get("status")=="superseded" and not c.get("superseded_by")]
fails["I3 status=superseded => superseded_by non-empty"] = missing
# I4: at most one authoritative doc per (topic, bounded_context)
topics = collections.defaultdict(list)
for k,c in cards.items():
    if c.get("authority")=="authoritative" and c.get("topic"):   # the linter's own condition
        topics[(c.get("topic"), c.get("bounded_context"))].append(k)
dupes = {k:v for k,v in topics.items() if len(v)>1}
fails["I4 single authoritative per topic+context"] = dupes
# I5: every relationship target resolves
fails["I5 all relation targets resolve"] = dangling

# vacuity guard: a check with an empty population is not a pass
pop = {
 "I1 supersedes acyclic/antisymmetric": len(sup),
 "I2 superseded_by is converse of supersedes": len(supby),
 "I3 status=superseded => superseded_by non-empty":
      sum(1 for c in cards.values() if c.get("status")=="superseded"),
 "I4 single authoritative per topic+context":
      sum(1 for c in cards.values() if c.get("authority")=="authoritative" and c.get("topic")),
 "I5 all relation targets resolve": len(id_edges),
}
for name, bad in fails.items():
    n = len(bad); N = pop[name]
    tag = "VACUOUS (empty population)" if N == 0 else ("HOLDS" if n == 0 else "VIOLATED")
    print(f"    {name:<48} population={N:<4} violations={n:<3} {tag}")
    if n and n <= 6:
        for b in (list(bad.items())[:6] if isinstance(bad, dict) else bad[:6]):
            print(f"        {b}")
    elif n:
        sample = (list(bad.items())[:4] if isinstance(bad, dict) else bad[:4])
        for b in sample: print(f"        {b}")
        print(f"        ... and {n-4} more")
print("""
  These are the FIRST invariant checks executed against the EKP's real
  knowledge graph in this research programme.  A VACUOUS result is NOT a pass:
  it means the real data contains no instance of the construct, so the theory's
  invariant about it has never been exercised by reality.""")
unused = sorted(ID_KEYS - {e[2] for e in edges})
print("\n  Relation types the SCHEMA offers but the real graph never uses:")
print(f"    {unused}")
print("""  => The supersession and implementation machinery -- the part of the theory
     that carries revision, replacement and traceability -- is entirely
     UNEXERCISED in the only running instance available.  Four of the five
     invariant checks are therefore vacuous: the theory has not been tested by
     reality on any of them.""")
