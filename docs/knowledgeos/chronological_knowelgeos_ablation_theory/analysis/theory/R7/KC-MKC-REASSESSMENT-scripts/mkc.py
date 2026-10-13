# KC-MKC-REASSESSMENT draft r1.2 reproduction script (r1.2, J1: K-T's encoding equals K's by construction, row for row; it is NOT an independent encoding; r1.1: requirement checks per principle; r1: review F3c/F3d R11 fix and classification table; F3a/F5 pairing test; F6 counterexample encodings). Analysis only; decides nothing.
# Reuses R7/KC-CONVENTIONS-scripts/kc.py (sets T3/FZ, K, C, M and variants) and, through it,
# R7/AUDIT-Q6-TEST1-scripts/blind_enc.py (FZ, RAT, E3REL, crossing machinery). Neither is modified.
# Run: python3 -B mkc.py <path to R7>
import sys, os, io, contextlib
import numpy as np
R7=sys.argv[1]
sys.path.insert(0,os.path.join(R7,'KC-CONVENTIONS-scripts'))
sys.argv=['kc.py',R7]
with contextlib.redirect_stdout(io.StringIO()):
    import kc
B=kc.B; FZ=B.FZ; var=B.var; E={8,9}
TA,TB,TC,TD,ALL=B.TA,B.TB,B.TC,B.TD,B.ALL
RN=kc.RN
S=kc.SETS
# ---------------- principle encodings ----------------
P={}
P['T3 (baseline)']=S['T3/FZ (status quo)']
P['M']=S['M (mention)']
P['K']=S['K (contribution)']
P['K-VC8']=S['K-VC8 (contribution; R80 "acts exist")']
P['C (K1c)']=S['C (content)']
P['C-SH']=S['C-SH (content; guards all empty)']
P['C-R15ref']=S['C-R15ref (R15 reads R3 via its row reference)']
P['C-R15ref-R14']=S['C-R15ref-R14 (also R14 "may coincide")']
# New candidate U [E]: uniform subject formula. K's level (definitions, Cap typing, row refs read through)
# plus a polarity-uniform reading: every denied / non-required formula contributes its signature, by logical
# form, with no role labels. On guard rows this coincides with K; on R26, R30, R68, R79, R80 with M.
P['U [E] (uniform subject formula)']=var(P['K'],**kc.NEG_READ,**kc.R80_MENTION)
# New candidate C-Sigma [E]: C with a logical-form criterion for polarity: a statement (or part) that is
# equivalent to a Sigma_1 statement over histories (a possibility claim: "the frame does not impose psi",
# "f may coincide with d in some history") contributes nothing; Pi statements over histories, history-level
# formulas, and the positive import of exceptives ("no order except F2" asserts F2) contribute their signature.
# The row-by-row classification is the table FORM below (md section 3.3). r1 (review F3d): R11's F1 restatement
# ("F1 does fix sigma(f(a))(tau(a)) = v(a)") is a history-level formula and is read: R11 = {3,4,5,6,8,12}.
CS=dict(P['C (K1c)'])
CS.update({'R11':({3,4,5,6,8,12},None),'R14':(set(),None),'R16':(set(),None),'R17':(set(),None),'R21':({15},None),
           'R15':(P['C (K1c)']['R15'][0]|FZ['R3'][0],None)})
P['C-Sigma [E]']=CS
DIFF_CR15=[r for r in ALL if CS[r]!=P['C-R15ref'][r]]   # r1 (F3d, F3e): C-Sigma differs from C-R15ref only at R11 (same P-c verdict)
DIFF_C=[r for r in ALL if CS[r]!=P['C (K1c)'][r]]
# Logical-form classification used by C-Sigma for every row [E] (r1, review F3c).
# H = history-level formula (incl. typing, definitions); PI = universal (or forall-exists) statement over histories;
# SG = Sigma_1 possibility / non-entailment part (contributes nothing); EXC = exceptive (asserts its exception);
# SLOT = T-B slot clause; PROH = T-C formula as stated; Q = T-D formula asked about.
FORM={}
for r in ['R2','R3','R4','R5','R6','R7','R8','R10','R18','R19','R20','R22']: FORM[r]='H'
FORM.update({'R1':'H + SG ("says nothing about sigma at other times")','R9':'H + SG ("con may be undefined everywhere")',
 'R11':'H (typing; F1 restatement) + SG ("no frame axiom derives sigma from E")','R12':'PI (implication over classes; class rule) + SG ("R4 asserts nowhere ...")',
 'R13':'PI (clause implications) + SG ("neither converse holds")','R14':'SG ("never equates"; "may coincide in some histories")',
 'R15':'SG ("no order") + EXC ("except d = r under constitution (R3)") + H (t(w) typing)','R16':'SG ("does not require"; "absence ... not non-occurrence")',
 'R17':'SG ("does not require")','R21':'SG ("no axiom ties"; "not declared disjoint") + H ("a self-assertion is a value of c")',
 'R23':'PI (forall h, h\' : non-dependence)','R24':'PI (forall-exists: ambiguity, Def 5.1)'})
for r in TB: FORM[r]='SLOT (clause text; contrasts and glosses are SG)'
FORM['R80']='SLOT (empty clause; "acts exist" joint: VC8 choice)'
for r in TC: FORM[r]='PROH (formula as stated)'
for r in TD: FORM[r]='Q (formula asked about)'
for r in ['R66','R67','R68','R69','R70','R82']: FORM[r]='PI (meta: necessary / sufficient condition, or image equality P16)'
assert set(FORM)==set(ALL)
# r1.1 (H2) / r1.2 (J1): K-T. Its code is the same construction as kc.py's K (dict(FZ), the SA/SB/SC loop, ROWREF);
# SUBJ overwrites the guard rows with sets identical to FZ's. So K-T's encoding equals K's row for row BY CONSTRUCTION;
# only the stated definition differs. Its verdicts are K's results relabelled, not a second computation.
# T-A: history-level parts read after definitional unfolding; non-entailment parts about the frame contribute their
#      subject formula (for the guard rows this is the denied tie, written out below); T-B: slot clause, read through
#      definitions; T-C: prohibited formula as stated; T-D: formula asked about; row references read through.
KT=dict(FZ)
SUBJ={'R11':({3,4,5,6,8,12}|E,None),          # sigma typing + F1 restatement; subject of "no axiom derives sigma from E": sigma, acts
      'R14':({11,12,14,22},None),             # subject: f = d / f = r (C22: the three times)
      'R15':({11,12,14,18,20,22}|E,None),     # subject: order among r, d, f; exception F2 (con, acts); t(w)
      'R16':({13,17,19,23}|E,None),           # subject: every act / work event referenced (Ref, records); C23 label
      'R17':({5,6,19}|E,None),                # subject: norm value implies / excludes acts or work events
      'R21':({6,12,15},None)}                 # subject: c tied to sigma or f
KT.update(SUBJ)
for alt in (B.SA,B.SB,B.SC):                  # definitional unfolding: Adopted, Cap typing, independent
    for r in ALL:
        if alt[r]!=FZ[r]: KT[r]=alt[r]
KT.update(kc.ROWREF)                          # row references read through
P['K-T [E]']=KT
KT_DIFF_K=[r for r in ALL if KT[r]!=P['K'][r]]
KT_SAMEVERDICT=all(B.side(KT,r,ru)==B.side(P['K'],r,ru) for r in ALL for ru in ('S','S1','S2'))
MAIN=['T3 (baseline)','M','K','K-T [E]','C (K1c)','U [E] (uniform subject formula)','C-Sigma [E]']

def rel(e,rule):
    h=B.hostlists(e,rule); return sorted([r for r in h['X'] if r in TA and r not in B.HOSTED],key=lambda x:int(x[1:]))
def hd(e,rule):
    d,ok,_=B.hostdiff(e,rule); return d
CLA=kc.CLA
if __name__=='__main__':
    print('== 1. counts, E3-REL, host diffs vs ratified lists, clause table ==')
    for k,e in P.items():
        c=kc.counts(e)
        print(f'\n## {k}')
        print('  crossings T-A/T-A+T-B/all:',{RN[r]:c[r] for r in kc.RULES})
        print("  S' == S'' on all rows:",all(B.side(e,r,'S1')==B.side(e,r,'S2') for r in ALL))
        for rule in ('S','S1'):
            R=rel(e,rule); print(f'  E3-REL {RN[rule]}: {len(R)} {R}')
            print(f'  host diffs {RN[rule]}:',hd(e,rule) or 'IDENTICAL')
            print(f'  clauses {RN[rule]}:',kc.asym(e,rule))
        for rule in ('S','S1','S2'):
            print(f'  rank {RN[rule]:3s} TA+TB (P-c, strictly fewer, equal incl P-c)',kc.rank(e,TA+TB,rule))
    print('\n== 2. row-by-row verdicts where any main principle differs from T3 (S / S\') ==')
    for rule in ('S','S1'):
        for r in ALL:
            v={k:B.side(P[k],r,rule) for k in MAIN}
            if len(set(v.values()))>1: print(' ',RN[rule],r,v)
    # ---------------- 3. counterexamples (pairs of statements) ----------------
    print('\n== 3. counterexample pairs ==')
    SEL=B.SEL; idx=B.idx; full=B.full
    def ncut_diff(a,b):
        ma=sum(1<<idx[c] for c in a if c in idx); mb=sum(1<<idx[c] for c in b if c in idx)
        xa=((SEL&ma)!=0)&(((~SEL)&full&ma)!=0); xb=((SEL&mb)!=0)&(((~SEL)&full&mb)!=0)
        return int((xa!=xb).sum()), len(SEL)
    def verdict(enc_row,rule):
        d={'Q':enc_row}; return B.side(d,'Q',rule)
    pairs={
     # (principle, label, statement A encoding, statement B encoding)
     'CE-def-1 R27 (Adopted named) vs R27-unfolded (Def 1.10), under T3 / M':(FZ['R27'],({2,8,9,10,11,12},'N')),
     'CE-def-2 R34 "some w conforms" (M: own) vs R34-unfolded "some w has sigma(t(w))(tau(w)) in P", under M':(P['M']['R34'],FZ['R32']),
     'CE-def-3 R33 conforms own (T3 rule-2 text) vs R32 (T2: "formally identical to B-conf")':(({4,19,20,21},None),FZ['R32']),
     'CE-meta-1 R14 under C (K1c) vs R14\' "some history of H has an act with f(a)!=d(a) and one with a constituted record, f(a)!=r(rho)"':(P['C (K1c)']['R14'],({8,9,11,12,13,14,18},None)),
     'CE-meta-2 R16 under C (K1c) vs R16\' "some history of H has an act or work event referenced by no record"':(P['C (K1c)']['R16'],({8,9,13,17,19},None)),
     'CE-meta-3 R17 under C (K1c) vs R17\' "some history of H has a work event whose occurrence is not fixed by sigma"':(P['C (K1c)']['R17'],({4,6,19,20},None)),
     'CE-slot-1 R79 under M/U vs R79\' "R-i: the third conjunct of H-B is E != empty"':(P['M']['R79'],(set(E),None)),
     'CE-slot-2 R80 under M/U vs R80\' "R-i\': the third conjunct of H-B is T; E != empty holds jointly"':(P['M']['R80'],(set(E),None)),
     'CE-slot-3 R26 under M/U (gloss "with no Cap condition" read) vs R26 formula only':(P['M']['R26'],(FZ['R26'][0],None)),
     'CE-form-1 same logical form N(Gamma,psi), psi a record-reference requirement: R16 (Gamma = H axioms) vs R80 (Gamma = H-B under R-i\' clauses), T3 / K':(FZ['R16'],FZ['R80']),
    }
    for k,(a,b) in pairs.items():
        va=[verdict(a,ru) for ru in ('S','S1')]; vb=[verdict(b,ru) for ru in ('S','S1')]
        n,N=ncut_diff(a[0],b[0])
        print(f'  {k}\n     A {sorted(a[0])} {a[1]} -> S {va[0]}, S\' {va[1]} | B {sorted(b[0])} {b[1]} -> S {vb[0]}, S\' {vb[1]} | 9|14 cuts with different S\' verdict: {n} of {N}')
    # ---------------- 3b. guard / T-C pairing coherence (r1, review F3a, F5) ----------------
    print('\n== 3b. guard row vs its paired T-C row (T2 "Its guard is"; R15/R42 via X5; R16/R41 via X25): S\' verdicts, cuts differing ==')
    PAIRS=[('R14','R43'),('R16','R49'),('R16','R41'),('R17','R48'),('R21','R46'),('R15','R42'),('R11','R47')]
    for k in MAIN:
        e=P[k]; agree=0; cells=[]
        for g,t in PAIRS:
            vg,vt=B.side(e,g,'S1'),B.side(e,t,'S1'); n,_=ncut_diff(e[g][0],e[t][0]); agree+=vg==vt
            cells.append(f'{g}/{t} {vg}/{vt} ({n})')
        a6=sum(B.side(e,g,'S1')==B.side(e,t,'S1') for g,t in PAIRS[:6]); T2P=[('R14','R43'),('R16','R49'),('R17','R48'),('R21','R46'),('R11','R47')]
        a5=sum(B.side(e,g,'S1')==B.side(e,t,'S1') for g,t in T2P)
        print(f'  {k}: P-c agree {agree}/7 (all), {a6}/6 (review list), {a5}/5 (T2-stated only) | '+'; '.join(cells))
    print('\n== 3a2. K-T: encoding rows differing from K:',KT_DIFF_K,'| identical verdicts on all 83 rows under S, S\', S\'\':',KT_SAMEVERDICT)
    for r in KT_DIFF_K: print('   ',r,'K',sorted(P['K'][r][0]),'K-T',sorted(KT[r][0]),'cuts differing',ncut_diff(P['K'][r][0],KT[r][0])[0])
    print('\n== 3e. requirement checks per principle (computed parts) ==')
    for k in MAIN:
        e=P[k]
        r27=(B.side(e,'R27','S'),B.side({'Q':({2,8,9,10,11,12},'N')},'Q','S'),ncut_diff(e['R27'][0],{2,8,9,10,11,12})[0])
        r34=(ncut_diff(e['R34'][0],FZ['R32'][0])[0])
        rq5=(B.side(e,'R79','S1'),B.side(e,'R80','S1'))
        rq7h=[B.side(e,r,'S1') for r in ('R14','R15','R16','R21')]
        rq7c=[B.side(e,r,'S1') for r in ('R14','R16','R17')]
        print(f'  {k}: Rq2a R27 S {r27[0]} vs unfolded {r27[1]}, cuts {r27[2]}; R34 vs R32 cuts {r34} | Rq5 R79,R80 S\' {rq5} (witnesses CB23) | Rq7h R14,R15,R16,R21 {rq7h} | Rq7c R14,R16,R17 {rq7c}')
    print('\n== 3c. C-Sigma: rows differing from C (K1c) and from C-R15ref ==')
    print('  vs C (K1c):',DIFF_C,' vs C-R15ref:',DIFF_CR15, [ (r,sorted(P['C-R15ref'][r][0]),sorted(CS[r][0]),ncut_diff(P['C-R15ref'][r][0],CS[r][0])) for r in DIFF_CR15])
    print('\n== 3d. C-Sigma logical-form classification, all 83 rows ==')
    for r in ALL: print(f'  {r:4s} {B.side(CS,r,"S"):5s} {B.side(CS,r,"S1"):5s} {FORM[r]}')
    # ---------------- 4. Theorem 1 check: invariance modulo H forces one verdict on all T-A rows ----------------
    print('\n== 4. Theorem 1 premise check: ratified T-A categories are not constant ==')
    for rule in ('S','S1'):
        cats={k:sorted(v,key=lambda x:int(x[1:])) for k,v in B.RAT[rule].items() if v & set(TA)}
        print(' ',RN[rule],{k:[r for r in v if r in TA] for k,v in cats.items()})
