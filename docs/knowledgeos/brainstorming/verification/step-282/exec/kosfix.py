"""Canonical id: hash the full Evidence tuple, not just its ref. Fixes the C-defect found in F21."""
import sys,hashlib; sys.path.insert(0,'.')
import kosmodel
from kosmodel import *
def canonical_id(a):
    key=lambda x:(x.ref,x.polarity,x.state)
    return hashlib.sha256("|".join([a.P.E,a.P.D.name,a.P.V,
        ",".join(sorted(str(key(x)) for x in a.e)),a.c,a.t.vf,a.Pi]).encode()).hexdigest()[:10]
Assertion.id=property(canonical_id)
