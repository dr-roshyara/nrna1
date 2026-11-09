from itertools import combinations
subs=[frozenset(i for i in range(4) if m>>i&1) for m in range(1,16)]
tot=lam_notforest=forest_notlam=0
for k in range(1,5):
  for fam in combinations(subs,k):
    tot+=1
    lam=all(a<=b or b<=a or not(a&b) for a,b in combinations(fam,2))
    forest=all(all(x<=y or y<=x for x,y in combinations([s for s in fam if a<s],2)) for a in fam)
    if lam and not forest: lam_notforest+=1
    if forest and not lam: forest_notlam+=1
print(tot,lam_notforest,forest_notlam)
