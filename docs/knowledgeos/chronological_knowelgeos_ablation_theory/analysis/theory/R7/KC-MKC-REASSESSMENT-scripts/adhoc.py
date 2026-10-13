# KC-MKC-REASSESSMENT draft r1.2 (r1.2: K-T's A-i parse choice points enumerated as for C-Sigma, J2; r1.1: K-T base is the independent K-T encoding, H2; r1: K-T choice points listed, F8; C-Sigma R11 fixed, F3d): ad hoc choice points per principle, and what each choice moves.
# Analysis only; decides nothing. The classification of choice points is the analyst's [E] (md section 4.1).
# Run: python3 -B adhoc.py <path to R7>
import sys, io, contextlib, os
R7=sys.argv[1]; HERE=os.path.dirname(os.path.abspath(__file__)); sys.path.insert(0,HERE)
sys.argv=['mkc.py',R7]
with contextlib.redirect_stdout(io.StringIO()):
    import mkc
B=mkc.B; P=mkc.P; FZ=B.FZ; var=B.var; kc=mkc.kc; E={8,9}
def moved(base,alt):
    out=[]
    for ru in ('S','S1'):
        m=[f"{r}:{B.side(base,r,ru)}->{B.side(alt,r,ru)}" for r in B.ALL if B.side(base,r,ru)!=B.side(alt,r,ru)]
        out.append(f"{mkc.RN[ru]} {m or 'none'}")
    return ' | '.join(out)
def rel(e): return f"E3-REL {len(mkc.rel(e,'S'))}/{len(mkc.rel(e,'S1'))}"
KT=P['K-T [E]']; T3=P['T3 (baseline)']; M=P['M']; K=P['K']; C=P['C (K1c)']; U=P['U [E] (uniform subject formula)']; CS=P['C-Sigma [E]']
CH=[
 # (principle, choice point, rows, alternative encoding)
 ('T3','A1 guard enumeration (rule 1) vs absent/contrast clauses not read (rules 2, 5): guards read nothing instead','R11 R14-R17 R21',B.SH),
 ('T3','A1, other direction: absent/contrast clauses read as guards are','R26 R30 R68 R79 R80',var(T3,**kc.NEG_READ,**kc.R80_MENTION)),
 ('T3','A3 Ref/con read their acts, Cap does not read its actor: Cap read through its typing','R56 R60 R61 R76',B.SB),
 ('T3','A3 other direction: relational clause off','R45 R52',B.SE),
 ('T3','R83 condition ("never applied") read while R79 contrast is not: R83 condition not read (VC6)','R83 (R41)',B.VC['VC6 R41/R83 without C23']),
 ('T3','rule 1 "including the clauses it names" vs R25 gloss "with D-st, R27" not read: read it','R25',var(T3,R25=(FZ['R25'][0]|{10},'N'))),
 ('T3','K10 text "conformance -> C21, C4" (own) vs FZ read-through','R33 R34',var(T3,**kc.CONF_OWN)),
 ('M','R33 "formally identical to B-conf" read as naming B-conf (else own placement)','R33',var(M,R33=({4,19,20,21},None))),
 ('M','"R-A3" (an R3 item, no R4 text) in R39 / R45: not expanded','R39 R45',M),
 ('M','polarity-blind reading of B-chg\'s own Def 3.1 text "(no capability or status condition)" in rows naming B-chg','R13 R31 R61 R71 R73',var(M,R31=(FZ['R31'][0]|{2,10},None),R73=(M['R73'][0]|{2,10},None))),
 ('K','guard role from T2 tier label "guard form": label removed (rows read as assertions, C parse)','R11 R14-R17 R21',var(K,**kc.GUARD_C)),
 ('K','R80 role: "acts exist" (VC8) instead of empty clause','R80',P['K-VC8']),
 ('K','R80 role: guard-form denial instead of empty clause','R80',var(K,**kc.R80_MENTION)),
 ('K','R26 R30 R68 R79 classed as delimiting contrasts, not prohibitions: read as prohibitions','R26 R30 R68 R79',var(K,**kc.NEG_READ)),
 ('K-T','tier (T2 label T-A vs T-B) decides frame statement vs slot: R80 treated as a frame non-entailment','R80',var(KT,**kc.R80_MENTION)),
 ('K-T','R80 role: "acts exist" (VC8)','R80',var(KT,R80=(set(E),None))),
 ('K-T','R79 contrast vs subject of a non-entailment','R79',var(KT,R79=(KT['R79'][0]|{13,17},None))),
 # r1.2 (J2): the A-i parse choice points that count against C-Sigma, applied to K-T's guard-row subject readings
 ('K-T','A-i: R15 exception "except d = r under constitution (R3)" not part of the subject (con, acts dropped)','R15',var(KT,R15=({11,12,14,20,22},None))),
 ('K-T','A-i: R21 "is a value of c" typing part not read (subject "c tied to sigma or f" only)','R21',var(KT,R21=({6,12,15},None))),
 ('K-T','A-i: R11 F1 restatement not read (typing + subject "sigma derived from E" only)','R11',var(KT,R11=({3,4,6}|E,None))),
 ('K-T','A-i: R16 NOT-EVIDENCED clause not read as C23','R16',var(KT,R16=({13,17,19}|E,None))),
 ('C','guard parse: SH instead of K1c','R11 R14-R17 R21',P['C-SH']),
 ('C','guard parse: R15 reads R3 by C\'s own row-reference rule','R15',P['C-R15ref']),
 ('C','guard parse: also R14 "may coincide" read','R14 R15',P['C-R15ref-R14']),
 ('C','R80 "acts exist" (VC8)','R80',var(C,R80=(set(E),None))),
 ('C','class-level statements (R23 R24 R66-R70 R82) "assert no formula about a history": read nothing','R23 R24 R66-R70 R82',var(C,**{r:(set(),None) for r in ['R23','R24','R66','R67','R68','R69','R70','R82']})),
 ('U','R80 "acts exist" joint statement not read (only the denied record clause)','R80',var(U,R80=({13,17},None))),
 ('C-Sigma','exceptive import of R15 "except d = r under constitution" not asserted','R15',var(CS,R15=(C['R15'][0],None))),
 ('C-Sigma','R21 "is a value of c" read as typing (C15) vs as a Sigma_1 contrast (nothing)','R21',var(CS,R21=(set(),None))),
 ('C-Sigma','R11 typing and F1 restatement read vs nothing (typing vacuous in a typed signature)','R11',var(CS,R11=(set(),None))),
 ('C-Sigma','R11 F1 restatement not read (typing only, as C K1c)','R11',var(CS,R11=({3,4,6},None))),
 ('C-Sigma','R16 "absence is NOT-EVIDENCED-IN-CAPTURE" read as a C23 classification','R16',var(CS,R16=({23},None))),
 ('C-Sigma','R80 "acts exist" (VC8)','R80',var(CS,R80=(set(E),None))),
]
base={'T3':T3,'M':M,'K':K,'K-T':KT,'C':C,'U':U,'C-Sigma':CS}
from collections import Counter
cnt=Counter(); eff=Counter(); hosteff=Counter()
for p,lab,rows,alt in CH:
    mv=moved(base[p],alt); cnt[p]+=1
    mr={r for r in B.ALL for ru in ('S','S1') if B.side(base[p],r,ru)!=B.side(alt,r,ru)}
    if mr: eff[p]+=1
    if mr & set(B.TA+B.TB): hosteff[p]+=1
    print(f'[{p}] {lab}\n   rows: {rows}\n   moves: {mv}\n   {rel(alt)} (base {rel(base[p])})')
print('\nchoice points per principle (listed / moving any row / moving a T-A or T-B row):')
for p in base: print(f'  {p}: {cnt[p]} / {eff[p]} / {hosteff[p]}')
