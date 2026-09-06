"""Deterministic corpus generation.  Seeds are recorded; nothing is hand-picked."""
import random
from zero_algebra import Rep, Item, STOPWORDS, REQUIRED_TOKENS

TOKENS  = ["a","b","c","key","must","the","of","and","ref1","ref2","ref3","x","y"]
SOURCES = ["S1","S2","S3",None]
SCOPES  = ["in","out",None]

def gen_case(rng, cls):
    """One representation.  Patterns deliberately include the pathological shapes the
    spec lists: empty, singleton, repeats, nested repeats, alternating, overlapping
    rules, metadata conflicts, same value/different source, contradictions."""
    shape = rng.choice(["empty","singleton","repeat","nested","alternating","random",
                        "overlap","meta_conflict","same_value_diff_source","contradiction",
                        "long"])
    def mk(tok, **kw):
        if cls == "R1": return Item(tok)
        if cls == "R2": return Item(tok, source=rng.choice(SOURCES),
                                    uncertainty=round(rng.random(),2))
        if cls == "R3": return Item(tok, node=tok, edge_to=rng.choice(TOKENS))
        return Item(tok, source=rng.choice(SOURCES),
                    uncertainty=round(rng.random(),2), scope=rng.choice(SCOPES),
                    polarity=kw.get("pol", rng.choice([True,False,None])))
    if shape == "empty":      items = ()
    elif shape == "singleton":items = (mk(rng.choice(TOKENS)),)
    elif shape == "repeat":   t = rng.choice(TOKENS); items = tuple(mk(t) for _ in range(rng.randint(2,4)))
    elif shape == "nested":   t,u = rng.sample(TOKENS,2); items = (mk(t),mk(u),mk(t),mk(u),mk(t))
    elif shape == "alternating":
        t,u = rng.sample(TOKENS,2); items = tuple(mk(t if i%2==0 else u) for i in range(rng.randint(2,6)))
    elif shape == "overlap":  items = (mk("the"),mk("the"),mk("key"),mk("key"))
    elif shape == "meta_conflict":
        t = rng.choice(TOKENS)
        items = (Item(t, source="S1", uncertainty=0.1, scope="in", polarity=True),
                 Item(t, source="S2", uncertainty=0.9, scope="out", polarity=False))
    elif shape == "same_value_diff_source":
        t = rng.choice(TOKENS)
        items = tuple(Item(t, source=s, uncertainty=0.5, polarity=True) for s in ("S1","S2","S3"))
    elif shape == "contradiction":
        t = rng.choice(TOKENS)
        items = (Item(t, source="S1", polarity=True), Item(t, source="S2", polarity=False))
    elif shape == "long":     items = tuple(mk(rng.choice(TOKENS)) for _ in range(rng.randint(8,14)))
    else:                     items = tuple(mk(rng.choice(TOKENS)) for _ in range(rng.randint(1,6)))
    return Rep(cls, items), shape

def corpus(n, seed):
    rng = random.Random(seed)
    out = []
    for k in range(n):
        cls = rng.choice(["R1","R2","R3","R4"])
        D, shape = gen_case(rng, cls)
        out.append({"id": k, "rep": D, "cls": cls, "shape": shape})
    return out
