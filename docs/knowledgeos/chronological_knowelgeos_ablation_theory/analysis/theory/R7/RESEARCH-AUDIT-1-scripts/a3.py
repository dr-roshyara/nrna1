from enc import *
PLACED=set(CB1|CB23)
OBS={13,14,15,24}
def cr(enc,x,A,U=PLACED):
    cs=enc[x][0]&U; return bool(cs&A) and bool(cs-A)
for name,enc in [('audit',aud),('mine',mine)]:
    a=[x for x in ALL if cr(enc,x,CB1)!=cr(enc,x,OBS)]
    print(name,[(x,'Pc' if cr(enc,x,CB1) else 'Pd') for x in a])
    U2=set(range(2,29))
    b=[x for x in ALL if cr(enc,x,OBS)!=cr(enc,x,OBS,U2)]
    print(name,'Pd sensitivity to unplaced',b)
