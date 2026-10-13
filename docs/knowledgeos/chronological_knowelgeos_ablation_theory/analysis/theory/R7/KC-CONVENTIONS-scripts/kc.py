# KC-CONVENTIONS-PROPOSAL r1.1 reproduction script (r1.1: K13 / VC6 added, G6). r1 (r1: review F12 fixes: M's CONF_OWN limited to R34; K4b adds R76, R77, R78; F2/F8 variants added). Analysis only; decides nothing.
# Reuses R7/AUDIT-Q6-TEST1-scripts/blind_enc.py (FZ, SA..SI, RAT, E3REL, rank machinery).
# Run: python3 kc.py <path to R7>
import sys, os, io, contextlib
R7=sys.argv[1]
BE=os.path.join(R7,'AUDIT-Q6-TEST1-scripts'); RA=os.path.join(R7,'RESEARCH-AUDIT-1-scripts')
sys.argv=[os.path.join(BE,'blind_enc.py'),RA]
sys.path.insert(0,BE)
import blind_enc as B
import numpy as np
FZ=B.FZ; TA,TB,TC,TD,ALL=B.TA,B.TB,B.TC,B.TD,B.ALL
var=B.var; E={8,9}
RULES=('S','S1','S2'); RN={'S':'S','S1':"S'",'S2':"S''"}

# ---------------- single-convention alternatives (one at a time, from FZ) ----------------
GUARD_C={'R11':({3,4,6},None),'R14':(set(),None),'R15':({20},None),'R16':(set(),None),'R17':(set(),None),'R21':({15},None)}
NEG_READ={'R26':(FZ['R26'][0]|{2},None),'R30':(FZ['R30'][0]|{2,10},None),'R79':(FZ['R79'][0]|{13,17},None),
          'R68':(FZ['R68'][0]|{23},FZ['R68'][1])}
R80_MENTION={'R80':({8,9,13,17},None)}
CONF_OWN={'R33':({4,19,20,21},None),'R34':({19,21},None)}   # K10 alternative (single-convention run)
CONF_OWN_M={'R34':({19,21},None)}   # r1 (F12): under M, R33 names B-conf ('formally identical to B-conf') and is expanded; only R34 is own placement
TA_UNION=set().union(*[FZ[r][0] for r in TA])
ROWREF={'R71':(FZ['R71'][0]|set().union(*[FZ[r][0] for r in ['R25','R26','R27','R28','R29','R30','R31','R32','R33','R34','R35','R36','R37','R38']]),'N'),
        'R73':(FZ['R73'][0]|FZ['R31'][0],None),'R75':(set(TA_UNION),'B'),
        # r1 (F12): remaining single-row / set references of T-D rows
        'R76':(FZ['R76'][0]|FZ['R63'][0],'U'),
        'R77':(FZ['R77'][0]|set().union(*[FZ[r][0] for r in ['R4','R5','R19','R25','R26','R27','R28','R29']]),'N'),
        'R78':(FZ['R78'][0]|FZ['R39'][0],None)}
ROLES_OFF={'R63':(FZ['R63'][0],None),'R76':(FZ['R76'][0],None)}
# generic act -> C8 only (keep C9 only where adoption is named)
KEEP9={'R4','R5','R19','R55','R65','R63'}
ACT8={r:((FZ[r][0]-{9}) if r not in KEEP9 else FZ[r][0],FZ[r][1]) for r in ALL}
# value mapping as BL: v -> C4 (C5 only where a norm is spoken of), prep -> C8
VROWS=['R1','R5','R11','R13','R23','R25','R26','R30','R31','R37','R38','R61','R71']
CLROWS=['R12','R24','R51','R66','R67','R68','R69','R82']   # class-rule rows: CL contains v via D-chg/B-chg
def vm(enc):
    d=dict(enc)
    for r in VROWS+CLROWS:
        cs,a=d[r]; cs=set(cs)
        if 5 in cs: cs=(cs-{5})|{4}
        d[r]=(cs,a)
    for r in ('R5','R23'):
        cs,a=d[r]; d[r]=((set(cs)-{10})|{8},a)
    return d
ALT={
 'K1 guard rows read nothing (SH)':B.SH,
 'K1c guard rows read object-level content only':var(FZ,**GUARD_C),
 'K2 class rule off (SD)':B.SD,
 'K3 Adopted read through (SA)':B.SA,
 'K3b independent read through (SC)':B.SC,
 'K4 named clauses not read through (SI)':B.SI,
 'K4b row references read through':var(FZ,**ROWREF),
 'K5a R80 negated clause read (SF)':B.SF,
 'K5b R80 "acts exist" read (VC8)':B.VC['VC8 R80 "acts exist" joint statement -> C8,C9'],
 'K5c contrast/negated clauses read (R26,R30,R79,R68)':var(FZ,**NEG_READ),
 'K6 Cap named alone names an actor (SB)':B.SB,
 'K6b roles/offices do not name an actor':var(FZ,**ROLES_OFF),
 'K7 relational clause off (SE)':B.SE,
 'K8 generic act -> C8 only':ACT8,
 'K9 value mapping v->C4, prep->C8':vm(FZ),
 'K10 conforms own placement (R33,R34)':var(FZ,**CONF_OWN),
 'K13 statement scope: R41/R83 without C23 (VC6)':B.VC['VC6 R41/R83 without C23'],   # r1.1 (G6)
}
# ---------------- convention sets ----------------
SETS={}
SETS['T3/FZ (status quo)']=FZ
# M: aboutness/mention. Guards read (FZ); denials, contrasts and absent clauses read what they name;
# T1-named concepts at own placement (Adopted, Cap, independent, conforms); clause/class names expanded;
# no read-through of typing (relational clause off); row references not read.
M=var(B.SE,**NEG_READ,**R80_MENTION,**CONF_OWN_M)
SETS['M (mention)']=M
# C: content/signature. Reads the primitive coordinates in the signature of the formula the row states,
# with all definitions expanded; denials of axioms, contrasts, absent clauses contribute nothing;
# typed variables bound by Ref/con/Cap are read (relational on, Cap -> actor); row references read through.
C=var(FZ,**GUARD_C)
for alt in (B.SA,B.SB,B.SC):
    for r in ALL:
        if alt[r]!=FZ[r]: C[r]=alt[r]
C.update(ROWREF)
SETS['C (content)']=C
# K: contribution. Reads the signature (after full definitional expansion) of the formula the row
# contributes to the model: the formula it asserts or would activate; for a prohibition (T-A guard row,
# T-C exclusion) the formula it prohibits; for a T-D question the formula(s) it asks about.
# Empty clause (R80) and delimiting contrasts ("no Cap condition") contribute nothing.
K=dict(FZ)
for alt in (B.SA,B.SB,B.SC):
    for r in ALL:
        if alt[r]!=FZ[r]: K[r]=alt[r]
K.update(ROWREF)
SETS['K (contribution)']=K
SETS['K-VC8 (contribution; R80 "acts exist")']=var(K,R80=(set(E),None))
SETS['C-SH (content; guards all empty)']=var(C,**{r:(set(),None) for r in GUARD_C})
SETS['C-VC8 (content; R80 "acts exist")']=var(C,R80=(set(E),None))
# r1 (F2): parse variants of C's guard rows
SETS['C-R15ref (R15 reads R3 via its row reference)']=var(C,R15=(C['R15'][0]|FZ['R3'][0],None))
SETS['C-R15ref-R14 (also R14 "may coincide")']=var(C,R15=(C['R15'][0]|FZ['R3'][0],None),R14=({11,12,14},None))
# M0: pure surface mention, no expansion (M + class rule off + named clauses not read)
M0=dict(M)
for alt in (B.SD,B.SI):
    for r in ALL:
        if alt[r]!=FZ[r]: M0[r]=alt[r]
SETS['M0 (surface, no expansion)']=M0

# ---------------- measures ----------------
def counts(enc):
    return {rule:[sum(B.crosses(enc,r,rule) for r in rows) for rows in (TA,TA+TB,ALL)] for rule in RULES}
def hosts(enc,rule): return B.hostlists(enc,rule)
def hdiff(enc,rule):
    d,ok,reld=B.hostdiff(enc,rule); h=hosts(enc,rule)
    rel={r for r in h['X'] if r in TA and r not in B.HOSTED}
    return d,len(rel),sorted(rel,key=lambda x:int(x[1:]))
def moved(enc,rows=ALL):
    out={}
    for rule in RULES:
        ch=[r for r in rows if B.side(enc,r,rule)!=B.side(FZ,r,rule)]
        out[rule]=[(r,B.side(FZ,r,rule),B.side(enc,r,rule)) for r in ch]
    return out
# exhaustive rank over all 817,190 9|14 cuts, per spanning rule
SEL=B.SEL; idx=B.idx; full=B.full; PCM=B.PCM
LOC={'N':{2},'R':{16},'B':{2,16},'U':set(),None:set()}
def rank(enc,rows,rule):
    cr=np.zeros(SEL.shape,dtype=np.int32); pc=0
    for r in rows:
        cs,a=enc[r]
        if rule=='S2': cs=set(cs)|LOC[a]
        m=sum(1<<idx[c] for c in cs if c in idx)
        if rule=='S' and a:
            cr+=1; pc+=1; continue
        if not m: continue
        cr+=(((SEL&m)!=0)&(((~SEL)&full&m)!=0)).astype(np.int32)
        pc+=1 if ((m&PCM) and (m&~PCM&full)) else 0
    return pc,int((cr<pc).sum()),int((cr==pc).sum())
# H-D / H-B clause table
CLA={'D-chg R25':'R25','D-st R27':'R27','D-rec R29':'R29','B-chg R30':'R30','B-conf R32':'R32','B-conf pol R33':'R33',
     'B-recW R35':'R35','B-rec R36':'R36','R-i R79':'R79',"R-i' R80":'R80','R-h R83':'R83','R-j R38':'R38','R-e R26':'R26','R-d R28':'R28'}
def asym(enc,rule):
    return {k:B.side(enc,r,rule) for k,r in CLA.items()}

if __name__=='__main__':
    print('== single-convention alternatives from FZ: rows moved (all 83), counts, E3-REL, rank ==')
    for k,e in ALT.items():
        c=counts(e); mv=moved(e)
        print(f'\n## {k}')
        print('  counts T-A/T-A+T-B/all:',{RN[r]:c[r] for r in RULES})
        for rule in RULES:
            print(f'  {RN[rule]:3s} moved:',[f'{r}:{a}->{b}' for r,a,b in mv[rule]] or 'none')
        for rule in ('S','S1'):
            d,n,rel=hdiff(e,rule); print(f'  host diffs {RN[rule]}:',d or 'IDENTICAL',f'| E3-REL {n}')
        for rule in ('S','S1','S2'):
            print(f'  rank {RN[rule]:3s} TA+TB',rank(e,TA+TB,rule),' TA',rank(e,TA,rule))
    print('\n\n== convention sets ==')
    for k,e in SETS.items():
        c=counts(e)
        print(f'\n## {k}')
        print('  counts T-A/T-A+T-B/all:',{RN[r]:c[r] for r in RULES})
        print("  S vs S' rows:",[r for r in ALL if B.crosses(e,r,'S')!=B.crosses(e,r,'S1')])
        print("  S' == S'' on all rows:",all(B.side(e,r,'S1')==B.side(e,r,'S2') for r in ALL))
        mv=moved(e)
        for rule in ('S','S1'):
            print(f'  {RN[rule]:3s} moved vs FZ:',[f'{r}:{a}->{b}' for r,a,b in mv[rule]] or 'none')
        for rule in ('S','S1'):
            d,n,rel=hdiff(e,rule); print(f'  host diffs {RN[rule]}:',d or 'IDENTICAL',f'| E3-REL {n} {rel}')
            h=hosts(e,rule); print(f'     lists {RN[rule]}:',{kk:sorted(v,key=lambda x:int(x[1:])) for kk,v in h.items()})
        for rule in ('S','S1','S2'):
            print(f'  rank {RN[rule]:3s} TA+TB (P-c, strictly fewer, equal incl P-c)',rank(e,TA+TB,rule),' TA',rank(e,TA,rule))
        for rule in ('S','S1'):
            print(f'  clauses {RN[rule]}:',asym(e,rule))
        print('  R24 crosses (S,S1):',B.crosses(e,'R24','S'),B.crosses(e,'R24','S1'))
