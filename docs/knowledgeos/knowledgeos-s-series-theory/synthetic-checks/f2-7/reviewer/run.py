import itertools,random,sys
sys.path.insert(0,'.')
from chk import analyze
X=O=(0,1)
def allF(n):
    doms=[list(itertools.product(X,repeat=s)) for s in range(1,n+1)]
    return [ [dict(zip(doms[s],vals)) for s,vals in enumerate(c)] for c in itertools.product(*[list(itertools.product(O,repeat=len(dm))) for dm in doms])]
# n=2 exhaustive all F, W size<=4 ; also |W|<=16 sampled
n=2; worlds=[(x,o) for x in itertools.product(X,repeat=n) for o in itertools.product(O,repeat=n)]
Fs=allF(2); mism=0; cnt=0; jm=0; sjnm=0
for k in range(1,5):
  for W in itertools.combinations(worlds,k):
    for F in Fs:
      J,sj,sm,_=analyze(W,n,F); cnt+=1
      if J!=sj: mism+=1
      if sj and not sm: sjnm+=1
      if not J: jm+=1
print("n2 |W|<=4",cnt,"mismatch",mism,"J-false",jm,"Sjoint&!Smulti",sjnm)
random.seed(1); mism=0;cnt=0;jm=0;sjnm=0
for _ in range(20000):
    W=random.sample(worlds,random.randint(5,16)); F=random.choice(Fs)
    J,sj,sm,_=analyze(W,n,F); cnt+=1; mism+=J!=sj; jm+= not J; sjnm+= sj and not sm
print("n2 |W|>=5 sampled",cnt,"mismatch",mism,"J-false",jm,"Sjoint&!Smulti",sjnm)
n=3; worlds=[(x,o) for x in itertools.product(X,repeat=n) for o in itertools.product(O,repeat=n)]
mism=0;cnt=0;jm=0;sjnm=0
for _ in range(3000):
    W=random.sample(worlds,random.randint(1,6))
    F=[{x:random.choice(O) for x in itertools.product(X,repeat=s)} for s in (1,2,3)]
    J,sj,sm,_=analyze(W,n,F); cnt+=1; mism+=J!=sj; jm+= not J; sjnm+= sj and not sm
print("n3 sampled",cnt,"mismatch",mism,"J-false",jm,"Sjoint&!Smulti",sjnm)
# T3 instance
F1={(0,):1,(1,):0}; F2={(0,0):1,(0,1):0,(1,0):1,(1,1):1}
print("T3",analyze([((0,1),(0,1)),((1,1),(0,0))],2,[F1,F2]))
