import itertools
V=frozenset('ab'); subs=[frozenset(s) for r in range(3) for s in itertools.combinations('ab',r)]
opts=[None]+subs
def sv(spec):  # spec: tuple of per-side sets or None (one dimension); overlap = common point
    vals=set()
    for comp in itertools.product(*[subs if x is None else [x] for x in spec]):
        vals.add(bool(frozenset.intersection(*comp)))
    return 'T' if vals=={True} else 'F' if vals=={False} else 'U'
def book(spec):  # book's stated per-dimension rule (n sides)
    if all(x is not None for x in spec): return 'T' if frozenset.intersection(*spec) else 'F'
    if any(x is not None and len(x)==0 for x in spec): return 'F'
    return 'U'
bad=[(s,book(s),sv(s)) for s in itertools.product(opts,repeat=3) if book(s)!=sv(s)]
print(len(bad),'mismatches of',5**3); print([ (tuple(None if x is None else ''.join(sorted(x)) or 'empty' for x in s),b,v) for s,b,v in bad[:6]])
