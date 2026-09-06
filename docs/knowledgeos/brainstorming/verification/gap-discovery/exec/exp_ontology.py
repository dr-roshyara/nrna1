#!/usr/bin/env python3
"""
EXPERIMENTS 16-18 — Foundational ontology: the definitional dependency graph.

Mandate s8: build the dependency graph; test whether each candidate primitive
can be defined without another undefined concept, and whether the graph is
acyclic.  Step 241 ("Dependency Graph of the KnowledgeOS Theory") asserts a
graph; this executes one.

EDGE MEANING:  X -> Y  reads "the definition of X mentions Y".
Every edge below is sourced to a corpus definition, cited in the DEPS table.
"""
import itertools, collections
def hr(t): print("\n"+"="*74+f"\n{t}\n"+"="*74)

# (node, [deps], source)  -- deps taken from the DEFINITIONS the corpus gives
DEPS = {
 "Observation":  ([],                                            "253.6 irreducible candidate"),
 "Time":         ([],                                            "025w bitemporal"),
 "Source":       ([],                                            "265.1 provenance triple"),
 "Entity":       ([],                                            "263 entity/dimension/value"),
 "Dimension":    (["Entity"],                                    "263: a dimension OF an entity"),
 "Value":        (["Dimension"],                                 "264.1 value space V_D per dimension"),
 "Proposition":  (["Entity","Dimension","Value"],                "262: P=(entity,dimension,value)"),
 "Context":      ([],                                            "267.6 field c (undefined type)"),
 "Provenance":   (["Source","Time"],                             "265.1 (source,method,timestamp)"),
 "Rule":         (["Policy"],                                    "253.10 rule is a policy element"),
 "Evidence":     (["Observation","Proposition","Context","Rule"],"Closure-04: Evidence(O,P,C,R)"),
 "Assessment":   (["Evidence","Policy"],                         "266.23 assessment needs assessment semantics"),
 "EpistemicStatus":(["Assessment"],                              "266.9 status assignment = assessment rule"),
 "Assertion":    (["Proposition","Evidence","Context","Time","Provenance","EpistemicStatus"],
                                                                 "267.6 A=(id,P,e,c,t,Pi) + sigma"),
 "RelationType": ([],                                            "263 relation vocabulary"),
 "Relation":     (["Assertion","RelationType"],                  "262: R subset A x A x RelType"),
 "K":            (["Assertion","Relation"],                      "262-267: K=(A,R)"),
 "Operation":    (["K","Policy","Authority"],                    "266.16-266.18 preconditions/postconditions"),
 "T_algebra":    (["Operation"],                                 "259: the mandatory operation set"),
 "Equivalence":  (["T_algebra","K"],                             "260.9: == must be a congruence for T"),
 "KStar":        (["Equivalence"],                               "260.10: K* = H/=="),
 "Minimality":   (["KStar","T_algebra"],                         "266.19 proof over complete operation set"),
 "History":      (["K","Operation"],                             "247: H=(K_0,T_1..T_t)"),
 "Authority":    (["Assertion","Policy"],                        "187: authority is a RECORDED grant; 255.6 not self-authorizing"),
 "Policy":       (["Authority","Assertion"],                     "270: policy is itself governed and recorded"),
 "Governance":   (["Policy","Authority"],                        "104/155 governance runtime"),
}

hr("EXP-16  Is the definitional dependency graph ACYCLIC?")
g = {k:v[0] for k,v in DEPS.items()}
print(f"  nodes={len(g)}  edges={sum(len(v) for v in g.values())}")

# Tarjan SCC
index, low, onstk, stk, idx, sccs = {}, {}, set(), [], [0], []
def strong(v):
    index[v]=low[v]=idx[0]; idx[0]+=1; stk.append(v); onstk.add(v)
    for w in g.get(v,[]):
        if w not in index: strong(w); low[v]=min(low[v],low[w])
        elif w in onstk:   low[v]=min(low[v],index[w])
    if low[v]==index[v]:
        c=[]
        while True:
            w=stk.pop(); onstk.discard(w); c.append(w)
            if w==v: break
        sccs.append(c)
for v in list(g):
    if v not in index: strong(v)
cyclic=[c for c in sccs if len(c)>1 or (len(c)==1 and c[0] in g.get(c[0],[]))]
print(f"  strongly-connected components with >1 node: {len(cyclic)}")
for c in cyclic:
    print(f"    CYCLE: {sorted(c)}")
    # print one concrete cycle path
    start=sorted(c)[0]; path=[start]; cur=start
    seen=set()
    while True:
        nxt=next((w for w in g[cur] if w in c and (cur,w) not in seen), None)
        if nxt is None: break
        seen.add((cur,nxt)); path.append(nxt); cur=nxt
        if cur==start: break
    print(f"      witness path: {' -> '.join(path)}")

hr("EXP-17  Which nodes are TRANSITIVELY BLOCKED by a non-computable dependency?")
# Step 266's class-C set: no decision procedure
CLASS_C = {"Relevance","Truth","Adequacy","Authority","Assessment","EpistemicStatus",
           "Minimality","Policy"}
print(f"  Step 266 class-C (no decision procedure): {sorted(CLASS_C)}")
blocked, reason = {}, {}
def reaches_C(n, seen=None):
    seen = seen or set()
    if n in seen: return None
    seen.add(n)
    if n in CLASS_C: return [n]
    for d in g.get(n,[]):
        r = reaches_C(d, seen)
        if r: return [n]+r
    return None
for n in g:
    p = reaches_C(n)
    if p and len(p) > 1: blocked[n]=p
print(f"\n  nodes whose definition transitively reaches a class-C object: {len(blocked)}/{len(g)}")
for n in sorted(blocked): print(f"    {n:<15} via {' -> '.join(blocked[n])}")
clean = sorted(set(g) - set(blocked) - CLASS_C)
print(f"\n  nodes NOT blocked ({len(clean)}): {clean}")

hr("EXP-18  Removal test: what breaks if each candidate primitive is deleted?")
def reconstructible(without):
    """A node survives if all its deps survive."""
    alive = set(g) - {without}
    changed = True
    while changed:
        changed = False
        for n in list(alive):
            if any(d not in alive for d in g[n]):
                alive.discard(n); changed = True
    return alive
CANDIDATES = ["Observation","Proposition","Entity","Dimension","Value","Time",
              "Source","Context","RelationType","Assertion","Relation"]
print(f"  {'removed':<14} {'surviving':<10} lost")
for c in CANDIDATES:
    alive = reconstructible(c)
    lost = sorted(set(g) - alive)
    print(f"  {c:<14} {len(alive):<10} {lost}")
print("""
  READING: a candidate whose removal destroys K is NECESSARY for K as defined.
  A candidate whose removal leaves K standing is not load-bearing for K -- it may
  still be load-bearing for something else, which the table shows explicitly.""")
