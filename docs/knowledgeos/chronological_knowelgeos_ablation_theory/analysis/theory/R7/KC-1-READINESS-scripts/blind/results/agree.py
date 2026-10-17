import json,sys,os
D=sys.argv[1]; L=lambda n: json.load(open(os.path.join(D,n+'.json')))
A1,A2,B1,B2=L('A1'),L('A2'),L('B1'),L('B2')
G=['R11','R14','R15','R16','R17','R21']; CTL=['R1','R6','R9','R79','R80']
C=['C%d'%i for i in range(1,29)]
def kappa(x,y,rows):
    a=[int(c in x[r]) for r in rows for c in C]; b=[int(c in y[r]) for r in rows for c in C]
    n=len(a); po=sum(i==j for i,j in zip(a,b))/n; pa=sum(a)/n; pb=sum(b)/n
    pe=pa*pb+(1-pa)*(1-pb); return po,(po-pe)/(1-pe) if pe<1 else float('nan')
def jac(x,y): s,t=set(x),set(y); return 1.0 if not s|t else len(s&t)/len(s|t)
for nm,x,y in [('A1~A2',A1,A2),('B1~B2',B1,B2),('A1~B1',A1,B1),('A2~B2',A2,B2)]:
    for lab,rows in [('guard',G),('control',CTL)]:
        po,k=kappa(x,y,rows); ex=sum(set(x[r])==set(y[r]) for r in rows)
        mj=sum(jac(x[r],y[r]) for r in rows)/len(rows)
        print(f"{nm:6} {lab:8} exact {ex}/{len(rows)}  meanJaccard {mj:.3f}  item-agree {po:.3f}  kappa {k:.3f}")
print("\nper guard row (A1 | A2 | B1 | B2):")
for r in G: print(r, A1[r],'|',A2[r],'|',B1[r],'|',B2[r])
print("\nempty-set agreement on guard rows (B reads nothing):", {r:(B1[r]==[],B2[r]==[]) for r in G})
