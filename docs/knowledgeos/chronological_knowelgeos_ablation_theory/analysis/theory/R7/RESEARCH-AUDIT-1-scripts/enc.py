# Encodings: row -> (set of concepts excluding C1, actor flag in {None,'N','R','U','B'})
TA=[f"R{i}" for i in range(1,25)]
TB=[f"R{i}" for i in range(25,40)]+["R79","R80","R83"]
TC=[f"R{i}" for i in range(40,64)]+["R81"]
TD=[f"R{i}" for i in range(64,79)]+["R82"]
ALL=TA+TB+TC+TD
assert len(set(ALL))==83

def mk(DCHG,DST,DREC,BCHG,BCONF,BRECW):
    return DCHG|DST|DREC|BCHG|BCONF|BRECW

# ---- MINE ----
D_CHG={2,3,5,6,7,8,12}; D_ST={8,10,12}; D_REC={8,13,17}
B_CHG={3,5,6,7,8,12,19,20}; B_CONF={19,21}; B_RECW={13,17,19}
CL=mk(D_CHG,D_ST,D_REC,B_CHG,B_CONF,B_RECW)
mine={
'R1':({3,5,6,8,12},None),'R2':({8,13,17,18},None),'R3':({8,11,13,14,18},None),'R4':({8,9},None),
'R5':({3,4,5,8,9,10,11,12},'N'),'R6':({3,19,20},'N'),'R7':({13,14,15},None),'R8':({13,16},'R'),
'R9':({8,9,13,17,18,19},None),'R10':({2},'N'),'R11':({3,4,6,8,12},None),'R12':(CL|{8},'N'),
'R13':(set(CL),'N'),'R14':({11,12,14},None),'R15':({11,12,14,18,20},None),'R16':({8,9,13,17,19,23},None),
'R17':({6,8,9,19},None),'R18':({3,4,6,19,20,21},None),'R19':({2,8,9,10,11},'N'),'R20':({3,6,7},None),
'R21':({6,12,15},None),'R22':({8,9,13,19},'U'),'R23':({2,6,8,9,11,12,13,14,15,16,17,18,19},'U'),
'R24':(CL|{8,13,17,18,27},'N'),
'R25':(set(D_CHG),'N'),'R26':(D_CHG-{2},None),'R27':({8,10,12},None),'R28':({8,10,12},None),
'R29':(set(D_REC),None),'R30':(set(B_CHG),None),'R31':(set(B_CHG),None),'R32':({3,4,6,19,20,21},None),
'R33':({4,19,21},None),'R34':({19,21},None),'R35':(set(B_RECW),None),'R36':(D_REC|B_RECW,None),
'R37':({5,6,7,8,12,19,20},None),'R38':(set(D_CHG),'N'),'R39':({8,13,18},None),
'R79':({8},None),'R80':(set(),None),'R83':({8,9,13,17,19,23},None),
'R40':({12,13,14},None),'R41':({8,9,13,17,19},None),'R42':({8,11,14},None),'R43':({11,12,14},None),
'R44':({8,13,18},None),'R45':({8,13,18},None),'R46':({6,12,15},None),'R47':({6,8},None),
'R48':({4,19},None),'R49':({8,13,19,23},None),'R50':({6,7,19},None),'R51':(set(CL),'N'),
'R52':({13,15,18,27},None),'R53':(set(),None),'R54':({5},None),'R55':({8,9},None),'R56':({2},None),
'R57':(set(),None),'R58':({5,6,8},None),'R59':({13,28},None),'R60':({2},None),'R61':(B_CHG|{2,10},None),
'R62':({3,13,25,26},None),'R81':({6,7,19},None),'R63':({8,9},'N'),
'R64':({5},None),'R65':({8,9},None),'R66':(CL|{2,6,8,9,12,13,16,17,18,19,26},'B'),
'R67':(CL|{3,4,19,20,26},'N'),'R68':(CL|{2,3,6,8,10,12,26},'N'),'R69':(CL|{6,19,20,26},'N'),
'R70':({3,8,13,16,18,25},'B'),'R82':(CL|{6,12,13,17,18,19,21},'N'),'R71':(D_CHG|B_CHG,'N'),
'R72':({2,6,11,12,16,17,18,20},'N'),'R73':({3},None),'R74':({28},None),'R75':(set(),None),
'R76':({2},'U'),'R77':({8,9,13},None),'R78':({8,13,18},None),
}
# ---- AUDIT (Appendix A transcription) ----
aD_CHG={2,3,5,6,7,8,12}; aD_ST={8,10,12}; aD_REC={8,13,17}
aB_CHG={3,5,6,7,8,12,19,20}; aB_CONF={3,4,6,19,20,21}; aB_RECW={13,17,19}
aCL=mk(aD_CHG,aD_ST,aD_REC,aB_CHG,aB_CONF,aB_RECW)
aud={
'R1':({3,5,6,8,12},None),'R2':({8,17,18},None),'R3':({8,11,14,18},None),'R4':({8,9},None),
'R5':({3,4,5,8,9,10,11,12},'N'),'R6':({3,19,20},'N'),'R7':({13,14,15},None),'R8':({13,16},'R'),
'R9':({8,9,13,17,18,19},None),'R10':({2},'N'),'R11':({6,8},None),'R12':(aCL|{8},'N'),'R13':(set(aCL),'N'),
'R14':({11,12,14},None),'R15':({11,12,14,20},None),'R16':({8,13,17,19,23},None),'R17':({6,8,19},None),
'R18':({3,4,6,19,20,21},None),'R19':({2,8,9,10,11},'N'),'R20':({6,7},None),'R21':({6,12,15},None),
'R22':({8,13,19},'U'),'R23':({2,6,8,11,12,13,16,17,18,19},'U'),'R24':({8,13,17,18,27},None),
'R25':(set(aD_CHG),'N'),'R26':(aD_CHG-{2},None),'R27':(set(aD_ST),None),'R28':(set(aD_ST),None),
'R29':(set(aD_REC),None),'R30':(set(aB_CHG),None),'R31':(set(aB_CHG),None),'R32':(set(aB_CONF),None),
'R33':({19,21},None),'R34':({19,21},None),'R35':(set(aB_RECW),None),'R36':(aD_REC|aB_RECW,None),
'R37':({6,7,8,12,19,20},None),'R38':(set(aD_CHG),'N'),'R39':({8,13,18},None),'R79':({8},None),
'R80':(set(),None),'R83':({8,13,17,19},None),'R40':({12,14},None),'R41':({8,13,17,19},None),
'R42':({11,14},None),'R43':({11,12,14},None),'R44':({8,13,18},None),'R45':({8,13,18},None),
'R46':({6,15},None),'R47':({6,8},None),'R48':({6,19},None),'R49':({8,13,19,23},None),'R50':({7,19},None),
'R51':(set(aCL),'N'),'R52':({8,15,18},None),'R53':(set(),None),'R54':({5},None),'R55':({8,9},None),
'R56':({2},None),'R57':(set(),None),'R58':({5,8},None),'R59':({13,28},None),'R60':({2},None),
'R61':({2,7,8,10},None),'R62':({13,25,26},None),'R63':({9},'N'),'R81':({7,19},None),'R64':({5},None),
'R65':({8,9},None),'R66':(aCL|{2,6,8,12,13,16,17,18,19,26},'N'),'R67':(aCL|{3,4,19,20,26},'N'),
'R68':(aCL|{2,6,10,26},'N'),'R69':(aCL|{6,19,20,26},'N'),'R70':({8,13,16,18,25},'B'),
'R71':(aD_CHG|aB_CHG,'N'),'R72':({2,6,11,12,16,17,18,20},'N'),'R73':({3,7},None),'R74':({28},None),
'R75':(set(),None),'R76':({2},'U'),'R77':({8,13},None),'R78':({8,13,18},None),'R82':(aCL|{6,12,13,17,18,19},None),
}
assert set(mine)==set(ALL) and set(aud)==set(ALL)
CB1={13,14,15,16,17,18,23,24,27}; CB23={2,3,4,5,6,7,8,9,10,11,12,19,20,21}
PC={c:0 for c in CB1}; PC.update({c:1 for c in CB23})
def sides(cs,part): return {part[c] for c in cs if c in part}
def cross(enc,row,part,rule,loc=None):
    cs,a=enc[row]; s=sides(cs,part)
    if a:
        if rule=='S': s|=set(part.values())
        elif rule=='S2' and loc:
            if a=='N': s|={loc['N']}
            elif a=='R': s|={loc['R']}
            elif a=='B': s|={loc['N'],loc['R']}
    return len(s)>=2
LOCPC={'N':1,'R':0}
