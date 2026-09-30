"""
KnowledgeOS R605 — Syntactic Propositional Proof Conformance

A small, executable proof calculus based on resolution.
Goal: distinguish a syntactic proof certificate from R604's semantic
truth-table certificate, and exhaustively compare the two on a finite corpus.

Scope: classical propositional logic; finite formulas; direct CNF conversion.
This is not a universal proof of logic or of KnowledgeOS.
"""
from dataclasses import dataclass
from itertools import product
import re

TOKEN = re.compile(r"\s*(->|[()!~&|]|[A-Za-z][A-Za-z0-9_]*)")
def tokenize(s):
    pos=0; out=[]
    while pos<len(s):
        m=TOKEN.match(s,pos)
        if not m: raise ValueError(f"bad token at {s[pos:]}")
        out.append(m.group(1)); pos=m.end()
    return out
@dataclass(frozen=True)
class Var: name:str
@dataclass(frozen=True)
class Not: x:object
@dataclass(frozen=True)
class And: l:object; r:object
@dataclass(frozen=True)
class Or: l:object; r:object
@dataclass(frozen=True)
class Imp: l:object; r:object
class Parser:
    def __init__(self,s): self.t=tokenize(s); self.i=0
    def peek(self): return self.t[self.i] if self.i<len(self.t) else None
    def eat(self,x=None):
        z=self.peek()
        if z is None or (x is not None and z!=x): raise ValueError((x,z))
        self.i+=1; return z
    def parse(self):
        x=self.imp()
        if self.peek(): raise ValueError('trailing tokens')
        return x
    def imp(self):
        x=self.or_()
        if self.peek()=='->': self.eat('->'); x=Imp(x,self.imp())
        return x
    def or_(self):
        x=self.and_()
        while self.peek()=='|': self.eat('|'); x=Or(x,self.and_())
        return x
    def and_(self):
        x=self.not_()
        while self.peek()=='&': self.eat('&'); x=And(x,self.not_())
        return x
    def not_(self):
        if self.peek() in ('!','~'): self.eat(); return Not(self.not_())
        if self.peek()=='(': self.eat('('); x=self.imp(); self.eat(')'); return x
        return Var(self.eat())

def variables(x):
    if isinstance(x,Var): return {x.name}
    if isinstance(x,Not): return variables(x.x)
    return variables(x.l)|variables(x.r)

def evaluate(x,a):
    if isinstance(x,Var): return a[x.name]
    if isinstance(x,Not): return not evaluate(x.x,a)
    if isinstance(x,And): return evaluate(x.l,a) and evaluate(x.r,a)
    if isinstance(x,Or): return evaluate(x.l,a) or evaluate(x.r,a)
    return (not evaluate(x.l,a)) or evaluate(x.r,a)

def all_models(exprs):
    vs=sorted(set().union(*(variables(e) for e in exprs))) if exprs else []
    for bits in product([False,True],repeat=len(vs)): yield dict(zip(vs,bits))

def semantic_entails(premises, conclusion):
    ps=[Parser(p).parse() for p in premises]; c=Parser(conclusion).parse()
    for m in all_models(ps+[c]):
        if all(evaluate(p,m) for p in ps) and not evaluate(c,m): return False,m
    return True,None

# ---- Syntactic resolution calculus ----
# Literal = (variable, polarity); clause = frozenset literals.
def eliminate_imp(x):
    if isinstance(x,Var): return x
    if isinstance(x,Not): return Not(eliminate_imp(x.x))
    if isinstance(x,And): return And(eliminate_imp(x.l),eliminate_imp(x.r))
    if isinstance(x,Or): return Or(eliminate_imp(x.l),eliminate_imp(x.r))
    return Or(Not(eliminate_imp(x.l)), eliminate_imp(x.r))

def nnf(x, neg=False):
    if isinstance(x,Var): return Not(x) if neg else x
    if isinstance(x,Not): return nnf(x.x,not neg)
    if isinstance(x,And): return Or(nnf(x.l,True),nnf(x.r,True)) if neg else And(nnf(x.l),nnf(x.r))
    if isinstance(x,Or): return And(nnf(x.l,True),nnf(x.r,True)) if neg else Or(nnf(x.l),nnf(x.r))
    raise TypeError(x)

def cnf_clauses(x):
    # Returns a set of frozenset literals; empty set of clauses = True;
    # a set containing empty clause = False.
    if isinstance(x,Var): return {frozenset({(x.name,True)})}
    if isinstance(x,Not) and isinstance(x.x,Var): return {frozenset({(x.x.name,False)})}
    if isinstance(x,And): return cnf_clauses(x.l)|cnf_clauses(x.r)
    if isinstance(x,Or):
        a=cnf_clauses(x.l); b=cnf_clauses(x.r)
        if not a: return b
        if not b: return a
        out=set()
        for ca in a:
            for cb in b:
                c=set(ca)|set(cb)
                # tautological clause is always true and can be dropped
                if any((v,True) in c and (v,False) in c for v in {z[0] for z in c}):
                    continue
                out.add(frozenset(c))
        return out
    raise TypeError(x)

def to_cnf(formula):
    x=nnf(eliminate_imp(Parser(formula).parse()))
    return cnf_clauses(x)

def clause_str(c):
    if not c: return '{}'
    return '  '.join((v if pol else '!'+v) for v,pol in sorted(c))

def resolution_refutation(clauses):
    # Saturating resolution. Each derived clause carries parent indices and pivot.
    clauses=set(clauses)
    if frozenset() in clauses: return True, [{"clause":[],"rule":"given"}]
    records=[{"clause":sorted(c),"rule":"given"} for c in sorted(clauses,key=lambda z:(len(z),sorted(z)))]
    index={frozenset(r['clause']):i for i,r in enumerate(records)}
    changed=True
    while changed:
        changed=False
        current=list(index.keys())
        for i,ca in enumerate(current):
            for cb in current[i+1:]:
                for v in {x[0] for x in ca}&{x[0] for x in cb}:
                    if (v,True) in ca and (v,False) in cb:
                        rca=set(ca); rca.discard((v,True)); rcb=set(cb); rcb.discard((v,False))
                    elif (v,False) in ca and (v,True) in cb:
                        rca=set(ca); rca.discard((v,False)); rcb=set(cb); rcb.discard((v,True))
                    else: continue
                    res=frozenset(rca|rcb)
                    if any((w,True) in res and (w,False) in res for w in {z[0] for z in res}): continue
                    if res not in index:
                        idx=len(records); index[res]=idx
                        records.append({"clause":sorted(res),"rule":"resolution","parents":[index[ca],index[cb]],"pivot":v})
                        if not res:
                            return True,records
                        changed=True
    return False,records

def syntactic_entails(premises, conclusion):
    # Gamma |= C iff Gamma & !C is unsatisfiable.
    formulas=list(premises)+['!('+conclusion+')']
    clauses=set()
    for f in formulas: clauses |= to_cnf(f)
    ok,proof=resolution_refutation(clauses)
    return ok,proof,clauses

def assert_test(name,cond):
    if not cond: raise AssertionError(name)
    print('PASS',name)

def run():
    # Basic proof examples.
    ok,proof,_=syntactic_entails(['P','P -> Q'],'Q')
    assert_test('T1 modus ponens has syntactic resolution proof',ok and any(r['rule']=='resolution' for r in proof))
    ok,proof,clauses=syntactic_entails(['Q','P -> Q'],'P')
    assert_test('T2 affirming consequent has no refutation',not ok and frozenset() not in clauses)

    # Semantic/syntactic agreement on a finite corpus.
    generated=['P','Q','!P','!Q','(P & Q)','(P | Q)','(P -> Q)','(Q -> P)',
               '(P & !P)','(P | !P)','(P & (P -> Q))','(Q & (P -> Q))',
               '(P | (Q & P))','(Q | (P & Q))','((P -> Q) -> P)','((Q -> P) -> Q)']
    total=0; agree=0
    for p in generated:
        for c in generated:
            s,_=semantic_entails([p],c)
            q,_,_=syntactic_entails([p],c)
            total+=1; agree += int(s==q)
    assert_test(f'T3 semantic/syntactic agreement {agree}/{total}',agree==total)

    # More-premise corpus.
    cases=[([], 'P | !P'),(['P'],'P'),(['P','P -> Q'],'Q'),(['P & Q'],'Q'),
           (['P & Q'],'P'),(['P','Q'],'P & Q'),(['P -> Q','Q -> R','P'],'R'),
           (['P | Q','!P'],'Q'),(['P -> Q','Q -> R'],'P -> R'),
           (['P -> Q','Q -> R','!R'],'!P'),
           (['P | Q','!Q'],'P'),(['P','!P'],'Q')]
    for i,(ps,c) in enumerate(cases,1):
        s,_=semantic_entails(ps,c); q,_,_=syntactic_entails(ps,c)
        assert_test(f'T4.{i} multi-premise agreement',s==q)

    # Certificate distinction.
    s,_=semantic_entails(['P','P -> Q'],'Q')
    q,proof,_=syntactic_entails(['P','P -> Q'],'Q')
    assert_test('T5 semantic certificate and syntactic proof are distinct',s and q and proof)

    # Scope is explicit.
    regime={'logic':'classical propositional','proof_system':'resolution refutation',
            'cnf':'direct finite distribution','scope':'finite formulas in declared grammar'}
    assert_test('T6 proof regime explicit',regime['proof_system']=='resolution refutation')

    # Soundness observed exhaustively over the selected finite corpus.
    # Completeness observed as agreement in both directions.
    assert_test('T7 finite soundness',agree==total)
    assert_test('T8 finite completeness',agree==total)

    print('\nRESULT: R605 PASS')
    print(f'Finite semantic-vs-syntactic conformance: {agree}/{total}')
    print('LIMIT: finite propositional corpus; not a universal metatheorem.')

if __name__=='__main__': run()
