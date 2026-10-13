# AUDIT-Q6-TEST1 reproduction script. Analysis only; decides nothing.
# Part 1 (Phase A) is the blind encoding BL, written before any comparison source was opened
# Part 1 is the Phase A scratch file (sha256 0a8d1f40f6de96b8..., frozen before Phase B) minus its __main__ block.
# Part 2 (Phases B/C) compares BL with A0, A1 and RV, defines the frozen encoding FZ and recomputes.
# Run: python3 blind_enc.py [path to R7/RESEARCH-AUDIT-1-scripts]  (default: ../RESEARCH-AUDIT-1-scripts)
"""
AUDIT Q6 test 1 -- PHASE A BLIND ENCODING (encoding "BL").
Written after reading ONLY: T2 r1.4 (statement column of R1-R83), T1 r1.6 (C1-C28),
FORMAL-THEORY-R4 r3.1 (Defs 1.1-1.11, 3.1, 3.2, 4.9, 5.1). Nothing else was opened
(no T3, E2b, RESEARCH-AUDIT-1, enc.py, q2*.py, E3, crossing tables).

CONVENTIONS (BL-Q1..Q11)
Q1  Encode the STATEMENT column only (working label + formal text), not the "rests on"
    T1 list, the N-list or the glosses in other columns.
Q2  Records: a record rho / R named or quantified -> C13. Attributes: r -> C14, c/text -> C15,
    k -> C16 (+actor). Ref -> C17. con / "is the act" / "reports" / mode -> C18.
Q3  Acts: issue act -> C8, adoption act -> C9, a GENERIC act (a in E, "act", con(a)) -> C8 and C9.
    prep flag -> C8 (attribute of the issue act). d -> C11, f / "entry into force" -> C12.
Q4  Read-through: a named R4 defined predicate is replaced by the concepts of its definition.
    If the predicate has its own C-row, that C is ALSO read (direct part):
      Adopted -> C10 + {C8,C9,C2,C11} + actors {alpha,Cap}
      Chg / change point -> C7 + {C6,C3}
      conforms / violates -> C21 + {C19,C6,C20,C3,C4}
      independent (Def 4.9) -> C25 + {C18,C13,C16,C8,C9,C3,C19} + {k,alpha,alphaW}
      performatively ambiguous (Def 5.1) -> C27 + {C13,C17,C18,C8,C9} + O {C14,C15}
      not-evidenced-in-capture / completeness / non-occurrence from absence -> C23 + {C8,C9,C19,C13,C17}
      Clause names (no C-row of their own): D-chg, D-st, D-rec, B-chg, B-conf, B-recW, B-rec
      are fully replaced by their Def 3.1 text.
    The "direct" set keeps only the defined predicate's own C (variant BL-direct).
Q5  CLASS MEMBERSHIP (H-D, H-B, R_B, H-D', frames) is NOT expanded into its clauses unless the
    row names the clauses. Only explicitly named coordinates are read. (variant BL-Q5x: expand.)
Q6  Actors: alpha(a) -> flag 'a'; alpha_W -> 'aW'; keeper k -> 'k'; Cap / "capable" -> 'Cap';
    actor set / actor identity -> 'A'. Every actor flag also reads C1. Cap also reads C2.
Q7  NEGATIONS: if the row's statement IS a negation / guard / non-constraint, the concepts of the
    negated constraint are read. A contrast clause ("not X", "beyond X") inside a positive
    statement is NOT read.
Q8  Values: tau -> C3; v / V / P / "value not in P" -> C4; sigma / "norm state" / "in force" -> C6.
    C5 (norm as pair) is read only where the row speaks of the norm as an entity / its content.
Q9  An attribute read without quantifying over its bearer does not read the bearer's concept
    (e.g. "f is never equated with r" reads C12, C14, not C8, C13).
Q10 A reference to ONE other row (e.g. "R31") reads that row's encoding; references to a set of
    rows ("the T-A constraints", "R25-R38") and parenthetical cross-references are not read.
Q11 C22 is read where the row is about the distinctness/ordering of the governance times;
    C26 where the row is about an additional observable Q / norm-state source; C28 where it is
    about evidential-limitation information.
"""
ALLC = [f"C{i}" for i in range(1, 29)]
DEF = {
 'Adopted': ({'C10'}, {'C8','C9','C2','C11','C1'}, {'a','Cap'}),
 'Chg':     ({'C7'},  {'C6','C3'}, set()),
 'conf':    ({'C21'}, {'C19','C6','C20','C3','C4'}, set()),
 'indep':   ({'C25'}, {'C18','C13','C16','C8','C9','C3','C19','C1'}, {'k','a','aW'}),
 'ambig':   ({'C27'}, {'C13','C17','C18','C8','C9','C14','C15'}, set()),
 'absent':  ({'C23'}, {'C8','C9','C19','C13','C17'}, set()),
}
CLAUSE = {
 'D-chg': ({'C7','C6','C3','C4','C8','C12','C2','C1'}, {'a','Cap'}),
 'D-st':  ({'C8','C12','C10','C9','C2','C11','C1'}, {'a','Cap'}),
 'D-rec': ({'C13','C17','C8','C9'}, set()),
 'B-chg': ({'C7','C6','C3','C4','C8','C12','C19','C20'}, set()),
 'B-conf':({'C19','C21','C6','C20','C3','C4'}, set()),
 'B-recW':({'C13','C17','C19'}, set()),
}
CLAUSE['B-rec'] = (CLAUSE['D-rec'][0] | CLAUSE['B-recW'][0], set())

# row: (tier, direct concepts, defined predicates read through, clauses, actor flags, note)
S = lambda s: set(s.split()) if s else set()
ROWS = {
 'R1': ('T-A', S('C8 C12 C6 C3 C4'), [], [], S(''), 'F1'),
 'R2': ('T-A', S('C18 C13 C17 C8 C9'), [], [], S(''), 'F2 ref part; generic act'),
 'R3': ('T-A', S('C18 C13 C11 C14 C8 C9'), [], [], S(''), 'F2 time part'),
 'R4': ('T-A', S('C9 C8'), [], [], S(''), 'obj typing'),
 'R5': ('T-A', S('C8 C9 C1 C11 C3 C4 C12'), [], [], S('a'), 'act attributes incl alpha; prep->C8'),
 'R6': ('T-A', S('C19 C3 C1 C20'), [], [], S('aW'), 'work-event attributes'),
 'R7': ('T-A', S('C13 C14 C15'), [], [], S(''), 'record r,c'),
 'R8': ('T-A', S('C13 C16 C1'), [], [], S('k'), 'keeper'),
 'R9': ('T-A', S('C17 C13 C8 C9 C19 C18'), [], [], S(''), 'Ref, con, reports'),
 'R10':('T-A', S('C2 C1'), [], [], S('Cap'), 'Cap typing; kinds iss/adp are not acts'),
 'R11':('T-A', S('C6 C8 C9 C12 C3 C4'), [], [], S(''), 'sigma primitive, not from E; F1 mention'),
 'R12':('T-A', S('C8 C9'), [], [], S(''), 'E nonempty; H-D/H-B not expanded (Q5)'),
 'R13':('T-A', S(''), ['Chg','conf','Adopted'], ['D-chg','B-chg','B-rec','D-rec','B-conf','B-recW','D-st'], S('a Cap'), 'clause implications'),
 'R14':('T-A', S('C12 C11 C14 C22'), [], [], S(''), 'no f=d/r equation (Q7,Q9,Q11)'),
 'R15':('T-A', S('C14 C11 C12 C22 C20 C18'), [], [], S(''), 'no order; except under constitution'),
 'R16':('T-A', S(''), ['absent'], [], S(''), 'no completeness'),
 'R17':('T-A', S('C6 C4 C8 C9 C19'), [], [], S(''), 'no norm->work dependence'),
 'R18':('T-A', S('C19 C6 C20 C3 C4'), ['conf'], [], S(''), 'violation def'),
 'R19':('T-A', S(''), ['Adopted'], [], S('a Cap'), 'Adopted def'),
 'R20':('T-A', S(''), ['Chg'], [], S(''), 'Chg def'),
 'R21':('T-A', S('C15 C6 C12'), [], [], S(''), 'c not tied to sigma/f'),
 'R22':('T-A', S('C13 C1 C8 C9 C19'), [], [], S('A'), 'identity'),
 'R23':('T-A', S('C13 C14 C15 C6 C12 C17 C18 C11 C2 C16 C1 C8 C9 C19 C20 C3 C4'), [], [], S('a aW k Cap A'), 'Lem 1.12'),
 'R24':('T-A', S('C17 C13 C8 C9 C18'), ['ambig'], [], S(''), 'Lem 5.2; classes not expanded'),
 'R25':('T-B', S(''), ['Chg'], ['D-chg'], S('a Cap'), 'D-chg; D-st gloss not read'),
 'R26':('T-B', S('C7 C6 C3 C4 C8 C12'), ['Chg'], [], S(''), 'D-chg w/o Cap'),
 'R27':('T-B', S(''), ['Adopted'], ['D-st'], S('a Cap'), 'D-st'),
 'R28':('T-B', S(''), ['Adopted'], ['D-st'], S('a Cap'), 'D-st+'),
 'R29':('T-B', S(''), [], ['D-rec'], S(''), 'D-rec'),
 'R30':('T-B', S(''), ['Chg'], ['B-chg'], S(''), 'B-chg'),
 'R31':('T-B', S(''), ['Chg'], ['B-chg'], S(''), 'B-chg target-tied'),
 'R32':('T-B', S(''), ['conf'], ['B-conf'], S(''), 'B-conf'),
 'R33':('T-B', S(''), ['conf'], ['B-conf'], S(''), 'B-conf policy'),
 'R34':('T-B', S(''), ['conf'], ['B-conf'], S(''), 'weak B-conf'),
 'R35':('T-B', S(''), [], ['B-recW'], S(''), 'B-recW'),
 'R36':('T-B', S(''), [], ['B-rec'], S(''), 'B-rec'),
 'R37':('T-B', S('C19 C20 C8 C12 C4'), ['Chg'], [], S(''), 'R-g / R-g+'),
 'R38':('T-B', S(''), ['Chg'], ['D-chg'], S('a Cap'), 'R-j'),
 'R39':('T-B', S('C18 C13 C8 C9'), [], [], S(''), 'mode convention; e.g. not read'),
 'R40':('T-C', S('C14 C12 C13'), [], [], S(''), 'r lower bound on f'),
 'R41':('T-C', S(''), ['absent'], [], S(''), 'universal completeness invariant'),
 'R42':('T-C', S('C11 C14'), [], [], S(''), 'd from r'),
 'R43':('T-C', S('C12 C14 C11'), [], [], S(''), 'f = r/d'),
 'R44':('T-C', S('C13 C8 C9 C18'), [], [], S(''), 'record is the act'),
 'R45':('T-C', S('C18 C13 C8 C9'), [], [], S(''), 'convention as observation'),
 'R46':('T-C', S('C15 C6'), [], [], S(''), 'self-asserted force as norm state'),
 'R47':('T-C', S('C6 C8 C9'), [], [], S(''), 'sigma from acts'),
 'R48':('T-C', S('C6 C4 C19'), [], [], S(''), 'authorization implies execution'),
 'R49':('T-C', S(''), ['absent'], [], S(''), 'non-occurrence from absence'),
 'R50':('T-C', S('C19 C6 C7'), [], [], S(''), 'causal work->norm'),
 'R51':('T-C', S(''), [], [], S(''), 'classify H-D/H-B; Q5 -> empty'),
 'R52':('T-C', S('C15 C18 C27 C13'), [], [], S(''), 'text fixes mode'),
 'R53':('T-C', S(''), [], [], S(''), 'finite values as params: no concept'),
 'R54':('T-C', S('C5'), [], [], S(''), 'norm content'),
 'R55':('T-C', S('C8 C9'), [], [], S(''), 'hold etc as act kinds'),
 'R56':('T-C', S('C2 C1'), [], [], S('Cap'), 'time-varying Cap'),
 'R57':('T-C', S(''), [], [], S(''), 'drafting time: no C-row (AMBIGUOUS)'),
 'R58':('T-C', S('C6 C8 C9'), [], [], S(''), 'state machine for norms or acts (C5 vs C6 ambiguous)'),
 'R59':('T-C', S('C13 C28'), [], [], S(''), 'labels on records'),
 'R60':('T-C', S('C2 C1'), [], [], S('Cap'), 'access as Cap'),
 'R61':('T-C', S(''), ['Chg','Adopted'], ['B-chg'], S('a Cap'), 'R-k'),
 'R62':('T-C', S('C26 C6 C13'), ['indep'], [], S('k a aW'), 'independent source'),
 'R81':('T-C', S('C19 C6 C7'), [], [], S(''), 'work outcomes feedback'),
 'R63':('T-C', S('C1 C2 C8 C9'), [], [], S('a Cap'), 'role rules'),
 'R64':('T-D', S('C5'), [], [], S(''), 'norm content?'),
 'R65':('T-D', S('C8 C9'), [], [], S(''), 'hold etc?'),
 'R66':('T-D', S('C26 C6 C12 C13 C16 C17 C18 C2 C8 C9 C19 C1'), [], [], S('k Cap'), 'Cor 4.3'),
 'R67':('T-D', S('C26 C6 C19 C3 C20 C4'), [], [], S(''), 'Prop 4.5'),
 'R68':('T-D', S('C26 C6 C3 C8 C12 C2 C1'), ['Adopted'], [], S('a Cap'), 'Prop 4.6; capture contrast not read'),
 'R69':('T-D', S('C26 C6 C19 C20'), [], [], S(''), 'Lem 4.7'),
 'R70':('T-D', S('C17'), ['indep'], [], S('k a aW'), 'Def 4.9 / Prop 5.4c'),
 'R82':('T-D', S('C13 C14 C15 C17 C18 C7 C12 C6'), ['conf'], [], S(''), 'Prop 4.1/Lem 4.2; O_not-sigma-f read by gloss only'),
 'R71':('T-D', S(''), ['Chg'], ['D-chg','B-chg'], S('a Cap'), 'which regime'),
 'R72':('T-D', S('C6 C12 C11 C2 C16 C20 C18 C17 C1'), [], [], S('Cap k a aW'), 'populate unobserved coords'),
 'R73':('T-D', S(''), ['Chg'], ['B-chg'], S(''), 'R31 via Q10'),
 'R74':('T-D', S('C28'), [], [], S(''), 'evidential info'),
 'R75':('T-D', S(''), [], [], S(''), 'adequacy of T-A rows; set reference not read (Q10)'),
 'R76':('T-D', S('C1 C2'), [], [], S('Cap'), 'roles beyond Cap'),
 'R77':('T-D', S('C8 C9 C13'), [], [], S(''), 'acts identifiable'),
 'R78':('T-D', S('C13 C18 C8 C9 C15'), [], [], S(''), 'mode for real texts'),
 'R79':('T-B', S('C8 C9'), [], [], S(''), 'R-i; contrast not read (Q7)'),
 'R80':('T-B', S('C13 C17 C8 C9'), [], [], S(''), 'R-i-prime; negation read (Q7)'),
 'R83':('T-B', S('C8 C9 C19 C13 C17'), ['absent'], [], S(''), 'R-h policy; condition read'),
}
# Variants (BL-level ambiguities found while encoding)
VARIANTS = {
 'R51_expand': ('R51', 'classes H-D,H-B expanded (Q5x)'),
 'R12_expand': ('R12', 'classes expanded (Q5x)'),
 'R83_nocond': ('R83', 'condition (C23) not read'),
 'R41_noC23': ('R41', 'C23 not read; only the universal reference clause'),
 'R57_record': ('R57', 'drafting time as attribute of record text: C13 C15'),
 'R82_all':   ('R82', 'O_not-sigma-f read as every coordinate'),
}

def enc(row, mode='full'):
    tier, d, preds, cls, act, _n = ROWS[row]
    c = set(d)
    for p in preds:
        own, rest, a = DEF[p]
        c |= own
        if mode == 'full':
            c |= rest; act = act | a
    for k in cls:
        cs, a = CLAUSE[k]; c |= cs; act = act | a
    if act: c.add('C1')
    if 'Cap' in act: c.add('C2')
    return tier, c, act


# ======================= PHASES B AND C =======================
"""AUDIT Q6 test 1, Phases B and C: comparison, frozen encoding FZ, variants, recomputation.
Analysis only; decides nothing. Usage: python3 q6t1.py [path-to-RESEARCH-AUDIT-1-scripts]
Inputs: blind_enc.py (Phase A, encoding BL; same folder), enc.py and q2r1.py (A0, A1, RV)."""
import sys, os, random, itertools
import numpy as np
HERE=os.path.dirname(os.path.abspath(__file__))
RA=sys.argv[1] if len(sys.argv)>1 else os.path.join(HERE,'..','RESEARCH-AUDIT-1-scripts')
sys.path.insert(0,RA); sys.path.insert(0,HERE)
from enc import aud as A0, mine as RV, TA, TB, TC, TD, ALL, CB1, CB23, mk
ns={}; exec(open(os.path.join(RA,'q2r1.py')).read().split('ENCS=')[0].replace('sys.path.insert(0,','#'),ns); A1=ns['audc']
def _flag(act):
    n=bool(act&{'a','aW','Cap'}); r='k' in act
    return 'B' if n and r else 'N' if n else 'R' if r else 'U' if 'A' in act else None
BL={r:({int(x[1:]) for x in enc(r)[1]}-{1}, _flag(enc(r)[2])) for r in ALL}

# ---------------- FROZEN ENCODING FZ (conventions F1-F12 in the md) ----------------
D_CHG={2,3,5,6,7,8,12}; D_ST={8,10,12}; D_REC={8,9,13,17}
B_CHG={3,5,6,7,8,12,19,20}; B_CONF={3,4,6,19,20,21}; B_RECW={13,17,19}
CL=D_CHG|D_ST|D_REC|B_CHG|B_CONF|B_RECW
E={8,9}
FZ={
'R1':({3,5,6,8,12},None),'R2':({13,17,18}|E,None),'R3':({11,13,14,18}|E,None),'R4':(set(E),None),
'R5':({3,4,5,10,11,12}|E,'N'),'R6':({3,19,20},'N'),'R7':({13,14,15},None),'R8':({13,16},'R'),
'R9':({13,17,18,19}|E,None),'R10':({2},'N'),'R11':({3,4,5,6,12}|E,None),  # r1 (F9): R11 reads C4 (sigma : T -> V^T)
'R12':(CL|E,'N'),
'R13':(set(CL),'N'),'R14':({11,12,14,22},None),'R15':({11,12,14,18,20,22}|E,None),
'R16':({13,17,19,23}|E,None),'R17':({5,6,19}|E,None),'R18':({3,4,6,19,20,21},None),
'R19':({2,10,11}|E,'N'),'R20':({3,6,7},None),'R21':({6,12,15},None),'R22':({13,19}|E,'U'),
'R23':({2,3,5,6,10,11,12,13,16,17,18,19,20}|E,'B'),'R24':(CL|{13,17,18,27}|E,'N'),
'R25':(set(D_CHG),'N'),'R26':(D_CHG-{2},None),'R27':(set(D_ST),None),'R28':(set(D_ST),None),
'R29':(set(D_REC),None),'R30':(set(B_CHG),None),'R31':(set(B_CHG),None),'R32':(set(B_CONF),None),
'R33':(set(B_CONF),None),'R34':(set(B_CONF),None),'R35':(set(B_RECW),None),'R36':(D_REC|B_RECW,None),
'R37':({5,6,7,8,12,19,20},None),'R38':(set(D_CHG),'N'),'R39':({13,18}|E,None),
'R79':(set(E),None),'R80':(set(),None),'R83':({13,17,19,23}|E,None),
'R40':({12,13,14},None),'R41':({13,17,19,23}|E,None),'R42':({11,14},None),'R43':({11,12,14},None),
'R44':({13,18}|E,None),'R45':({13,18}|E,None),'R46':({6,15},None),'R47':({6}|E,None),
'R48':({4,6,19},None),'R49':({13,19,23}|E,None),'R50':({6,7,19},None),'R51':(set(CL),'N'),
'R52':({15,18,27}|E,None),'R53':(set(),None),'R54':({5},None),'R55':(set(E),None),'R56':({2},None),
'R57':(set(),None),'R58':({5}|E,None),'R59':({13,28},None),'R60':({2},None),
'R61':({2,3,5,6,7,8,10,12},None),'R62':({13,25,26},None),'R81':({6,7,19},None),'R63':(set(E),'N'),
'R64':({5},None),'R65':(set(E),None),'R66':(CL|{2,6,12,13,16,17,18,19,26}|E,'B'),
'R67':(CL|{3,4,19,20,26},'N'),'R68':(CL|{2,3,6,10,12,26}|E,'N'),'R69':(CL|{6,19,20,26},'N'),
'R70':({3,13,16,17,18,25}|E,'B'),'R82':(CL|{6,12,13,17,18,19,21},'N'),'R71':(D_CHG|B_CHG,'N'),
'R72':({2,6,11,12,16,17,18,19,20}|E,'B'),'R73':({3},None),'R74':({28},None),'R75':(set(),None),
'R76':({2},'U'),'R77':({13}|E,None),'R78':({13,15,18}|E,None),
}
assert set(FZ)==set(ALL)
def var(base,**ch):
    d=dict(base)
    for r,v in ch.items(): d[r]=v
    return d
# (c) genuine-ambiguity variants
VC={
 'VC1 R62 "for a target"->C3': var(FZ,R62=({3,13,25,26},None)),
 'VC2 R46 "force"->f (C12)': var(FZ,R46=({6,12,15},None)),
 'VC3 R61 whole R-k clause (+work disjunct)': var(FZ,R61=({2,3,5,6,7,8,10,12,19,20},None)),
 'VC4 R58 "norms"->sigma (+C6)': var(FZ,R58=({5,6,8,9},None)),
 'VC5 R63 role rule reads Cap (+C2)': var(FZ,R63=({2,8,9},'N')),
 'VC6 R41/R83 without C23': var(FZ,R41=({8,9,13,17,19},None),R83=({8,9,13,17,19},None)),
}
# (a) convention alternatives, reported as sensitivity (resolved in FZ, kept visible)
ADR=lambda s:(s|{2,9,11},'N')
SA=var(FZ,R27=ADR(D_ST),R28=ADR(D_ST),R68=(FZ['R68'][0]|{9,11},'N'),R61=(FZ['R61'][0]|{9,11},'N'))
SB=var(FZ,R56=({2},'N'),R60=({2},'N'),R61=(FZ['R61'][0],'N'),R76=({2},'N'))
SC=var(FZ,R62=({3,8,9,13,16,18,19,25,26},'B'),R70=(FZ['R70'][0]|{19},'B'))
OWN={'R12':(set(E),None),'R24':({13,17,18,27}|E,None),'R51':(set(),None),
     'R66':({2,6,12,13,16,17,18,19,26}|E,'B'),'R67':({3,4,19,20,26},None),'R68':({2,3,6,10,12,26}|{8},'N'),
     'R69':({6,19,20,26},None),'R82':({6,12,13,17,18,19,21},None)}
SD=var(FZ,**OWN)
SE=var(FZ,R52=({15,18,27},None),R45=({13,18},None),R15=({11,12,14,18,20,22},None))
SF=var(FZ,R80=({8,9,13,17},None))
SG=var(FZ,R24=OWN['R24'])   # R24 without the class rule (A0/BL reading)
# r1 additions (review F3, F4, F7)
SH=var(FZ,**{r:(set(),None) for r in ('R11','R14','R15','R16','R17','R21')})   # guard rows read nothing
VC['VC8 R80 "acts exist" joint statement -> C8,C9']=var(FZ,R80=(set(E),None))
SI=var(FZ,R38=({6,7},None),R71=({6,7},None))   # R38 still reads Chg; R71 'norm-change regime' -> Chg   # named clauses not read through (R38, R71 read only a clause name)
SENS={'SA Adopted read through':SA,'SB Cap names an actor':SB,'SC independent read through':SC,
      'SD class rule off':SD,'SE relational clause off':SE,'SF R80 negation read':SF,'SG R24 class rule off':SG,'SH guard rows read nothing':SH,'SI named clauses not read through':SI}
ENCS={'A0':A0,'A1':A1,'RV':RV,'BL':BL,'FZ':FZ}

PC={c:0 for c in CB1}; PC.update({c:1 for c in CB23})
def crosses(enc,r,rule):
    cs,a=enc[r]; s={PC[c] for c in cs if c in PC}
    if a:
        if rule=='S': s|={0,1}
        elif rule=='S2': s|={'N':{1},'R':{0},'B':{0,1},'U':set()}[a]
    return len(s)>=2
def side(enc,r,rule):
    if crosses(enc,r,rule): return 'X'
    cs,a=enc[r]; s={PC[c] for c in cs if c in PC}
    if rule=='S2' and a: s|={'N':{1},'R':{0},'B':{0,1},'U':set()}[a]
    return {0:'CB1',1:'CB23'}[s.pop()] if s else 'none'
def counts(enc):
    return {rule:[sum(crosses(enc,r,rule) for r in rows) for rows in (TA,TA+TB,ALL)] for rule in ('S','S1','S2')}
# ---------------- ratified lists (E2b r1.3 SA1.3/SA1.4, ratified per reading by F-LOG-0290) ----------------
HOSTED={'R12':'MW1','R13':'MW1','R23':'MW1','R24':'CV4'}
RAT={
 'S':{'CB1':{'R7'},'X':{'R2','R3','R5','R6','R8','R9','R10','R14','R15','R16','R19','R21','R22','R12','R13','R23','R24',
        'R25','R29','R35','R36','R38','R39','R83'},
      'CB23':{'R1','R4','R11','R17','R18','R20','R26','R27','R28','R30','R31','R32','R33','R34','R37','R79'},'none':{'R80'}},
 'S1':{'CB1':{'R7','R8'},'X':{'R2','R3','R9','R14','R15','R16','R21','R22','R12','R13','R23','R24','R29','R35','R36','R39','R83'},
      'CB23':{'R1','R4','R5','R6','R10','R11','R17','R18','R19','R20','R25','R26','R27','R28','R30','R31','R32','R33','R34','R37','R38','R79'},'none':{'R80'}},
}
RAT['S2']=RAT['S1']
E3REL={'S':{'R2','R3','R5','R6','R8','R9','R10','R14','R15','R16','R19','R21','R22'},'S1':{'R2','R3','R9','R14','R15','R16','R21','R22'}}
E3REL['S2']=E3REL['S1']
def hostlists(enc,rule):
    out={'CB1':set(),'X':set(),'CB23':set(),'none':set()}
    for r in TA+TB: out[side(enc,r,rule)].add(r)
    return out
def hostdiff(enc,rule):
    h=hostlists(enc,rule); d=[]
    for k in h:
        for r in sorted(h[k]^RAT[rule][k],key=lambda x:int(x[1:])):
            if r in h[k]: d.append(f"{r}: {k} (ratified: {[kk for kk in RAT[rule] if r in RAT[rule][kk]][0]})")
    rel={r for r in h['X'] if r in TA and r not in HOSTED}
    return d, rel==E3REL[rule], sorted(rel^E3REL[rule],key=lambda x:int(x[1:]))
# ---------------- rank and null on T-A + T-B (placed concepts only = S') ----------------
PLACED=sorted(CB1|CB23); idx={c:i for i,c in enumerate(PLACED)}; n=23; full=(1<<n)-1
A=np.arange(0,1<<n,dtype=np.int64); sz=np.zeros(A.shape,dtype=np.int8); t=A.copy()
while t.any(): sz+=(t&1).astype(np.int8); t>>=1
SEL=A[sz==9]; PCM=sum(1<<idx[c] for c in CB1)
def rank(enc,rows):
    ms=[sum(1<<idx[c] for c in enc[r][0] if c in idx) for r in rows]; ms=[m for m in ms if m]
    cr=np.zeros(SEL.shape,dtype=np.int32)
    for m in ms: cr+=(((SEL&m)!=0)&(((~SEL)&full&m)!=0))
    pc=sum(1 for m in ms if (m&PCM) and (m&~PCM&full))
    return pc,int((cr<pc).sum()),int((cr==pc).sum()),len(SEL),cr
def null(cr,pc,N=8000,seed=20261013):
    rng=random.Random(seed); xs=[]
    PS=PLACED[:]
    # same procedure as q2r1.randparts, evaluated through the exhaustive table
    for _ in range(N):
        sh=PS[:]; rng.shuffle(sh); m=sum(1<<idx[c] for c in sh[:9])
        xs.append(m)
    pos=np.searchsorted(SEL,np.array(xs,dtype=np.int64)); v=cr[pos]
    return v.mean(), (v<=pc).mean()
if __name__=='__main__':
    print('== P-c crossings T-A / T-A+T-B / all ==')
    for k,e in list(ENCS.items())+list(VC.items())+list(SENS.items()):
        c=counts(e); print(f'{k:42s} S {c["S"]}  S\' {c["S1"]}  S\'\' {c["S2"]}')
    print('\n== crossing-set disagreement among A0/A1/RV/FZ (all rows) ==')
    for rule in ('S','S1','S2'):
        sets={k:{r for r in ALL if crosses(e,r,rule)} for k,e in ENCS.items()}
        U=set().union(*sets.values()); I=set.intersection(*sets.values())
        print(rule,{r:[k for k in sets if r in sets[k]] for r in sorted(U-I,key=lambda x:int(x[1:]))})
    print('\n== host lists vs ratified (T-A, T-B) ==')
    for k,e in list(ENCS.items())+list(VC.items())+list(SENS.items()):
        for rule in ('S','S1','S2'):
            d,ok,reld=hostdiff(e,rule)
            print(f'{k:42s} {rule:3s} diffs={d if d else "IDENTICAL"}  E3-REL {"13/8 match" if ok else reld}')
    print('\n== S vs S\' row set (all rows) ==')
    for k,e in list(ENCS.items())+list(SENS.items()):
        ch=[r for r in ALL if crosses(e,r,'S')!=crosses(e,r,'S1')]; print(f'{k:42s} {len(ch)} {ch}')
    print('\n== BL vs ratified host categories (42 T-A+T-B rows), Cohen kappa ==')
    for rule in ('S','S1'):
        h=hostlists(BL,rule); lab=lambda d,r:[k for k in d if r in d[k]][0]
        a=[lab(h,r) for r in TA+TB]; b=[lab(RAT[rule],r) for r in TA+TB]
        po=sum(x==y for x,y in zip(a,b))/len(a); cats=set(a)|set(b)
        pe=sum((a.count(c)/len(a))*(b.count(c)/len(b)) for c in cats)
        print(rule,f'agree {sum(x==y for x,y in zip(a,b))}/{len(a)} kappa {(po-pe)/(1-pe):.2f}')
    print('\n== exhaustive 9|14 rank and size-matched null (rows T-A+T-B; T-A) ==')
    for k,e in list(ENCS.items())+list(VC.items())+list(SENS.items()):
        for rl,rows in (('TA+TB',TA+TB),('TA',TA)):
            pc,lt,eq,N,cr=rank(e,rows); mu,p=null(cr,pc)
            print(f'{k:42s} {rl:6s} P-c {pc:3d}  strictly fewer {lt:4d}  equal(incl P-c) {eq:3d}  of {N}  exact P(<=) {(lt+eq)}/{N}  null mean {mu:5.1f} P(null<=) {p:.4f}')
