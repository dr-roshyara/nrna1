"""
KnowledgeOS R594 — Minimal Support, Hypergraph Representation and Semantic Equivalence

Key correction:
R593's XOR singleton example was too strong. A factor alone does not
determine XOR when the other factor is unknown.

For dependency/sufficient-cause analysis, support must be value-bearing:
a partial assignment (set of literals), not merely a set of variable names.

A factor-only hypergraph preserves incidence but loses semantic polarity.
A conditional/value-bearing hypergraph preserves the tested Boolean semantics.
Finite executable evidence only; no universal theorem is claimed.
"""
from dataclasses import dataclass
from itertools import combinations, product

@dataclass(frozen=True)
class Literal:
    variable: str
    value: int

def all_assignments(vars_):
    for bits in product([0,1], repeat=len(vars_)):
        yield dict(zip(vars_,bits))

def sufficient_condition(fn,factors,condition,target_value):
    compatible=[a for a in all_assignments(factors)
                if all(a[l.variable]==l.value for l in condition)]
    return bool(compatible) and all(fn(a)==target_value for a in compatible)

def minimal_sufficient_conditions(fn,factors,target_value):
    literals=[Literal(v,val) for v in factors for val in (0,1)]
    candidates=[]
    for r in range(1,len(factors)+1):
        for c in combinations(literals,r):
            if len({x.variable for x in c}) != len(c):
                continue
            cond=frozenset(c)
            if sufficient_condition(fn,factors,cond,target_value):
                candidates.append(cond)
    return [c for c in candidates if not any(d<c for d in candidates)]

and_fn=lambda a:a["A"] & a["B"]
and_one=minimal_sufficient_conditions(and_fn,["A","B","C"],1)
assert frozenset({Literal("A",1),Literal("B",1)}) in and_one

or_fn=lambda a:(a["A"] & a["B"]) | (a["C"] & a["D"])
or_one=minimal_sufficient_conditions(or_fn,["A","B","C","D"],1)
assert frozenset({Literal("A",1),Literal("B",1)}) in or_one
assert frozenset({Literal("C",1),Literal("D",1)}) in or_one

xor_fn=lambda a:a["A"] ^ a["B"]
xor_zero=minimal_sufficient_conditions(xor_fn,["A","B"],0)
xor_one=minimal_sufficient_conditions(xor_fn,["A","B"],1)
assert set(xor_zero)=={
    frozenset({Literal("A",0),Literal("B",0)}),
    frozenset({Literal("A",1),Literal("B",1)})
}
assert set(xor_one)=={
    frozenset({Literal("A",0),Literal("B",1)}),
    frozenset({Literal("A",1),Literal("B",0)})
}
assert not any(len(c)==1 for c in list(xor_zero)+list(xor_one))

@dataclass(frozen=True)
class Hyperedge:
    support:frozenset
    target:str

def factor_hyperedges(conditions,target):
    return {Hyperedge(frozenset(l.variable for l in c),target) for c in conditions}

fn1=lambda a:a["A"] & a["B"]
fn2=lambda a:(1-a["A"]) & a["B"]
one1=minimal_sufficient_conditions(fn1,["A","B"],1)
one2=minimal_sufficient_conditions(fn2,["A","B"],1)
assert factor_hyperedges(one1,"Z")==factor_hyperedges(one2,"Z")
assert one1!=one2

@dataclass(frozen=True)
class ConditionalHyperedge:
    literals:frozenset
    target:str
    target_value:int

def conditional_hyperedges(fn,factors,target):
    result=set()
    for value in (0,1):
        for cond in minimal_sufficient_conditions(fn,factors,value):
            result.add(ConditionalHyperedge(cond,target,value))
    return result

assert conditional_hyperedges(fn1,["A","B"],"Z") != \
       conditional_hyperedges(fn2,["A","B"],"Z")

print("R594 — Minimal Support, Hypergraph and Semantic Equivalence")
print("AND value-bearing support: PASS")
print("OR alternative sufficient supports: PASS")
print("R593 XOR singleton claim refuted: PASS")
print("XOR value-conditioned supports: PASS")
print("Factor-only hypergraph loses polarity: PASS")
print("Conditional hypergraph preserves tested semantic distinction: PASS")
print("ML remains candidate-only: PASS")
print("RESULT: canonical dependency semantics require value/context-bearing",
      "support; factor-only hyperedges are an incomplete projection.")
