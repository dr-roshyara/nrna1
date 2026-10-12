import random
exec(open('a5.py').read().split("for name,enc in")[0])
for name,enc in [('audit',aud),('mine',mine)]:
    rs=rowsets(enc,TA)
    c=cohesion(rs,PC_parts)
    random.seed(3); s9=[];s14=[]
    for _ in range(5000):
        A=set(random.sample(PLACED,9)); r=cohesion(rs,[A,set(PLACED)-A])
        s9.append(r[0][2]); s14.append(r[1][2])
    s9=[x for x in s9 if x is not None]
    print(name,'CB1 %.2f'%c[0][2],'random9 mean %.2f'%(sum(s9)/len(s9)),'frac random9 >= CB1 %.3f'%(sum(x>=c[0][2] for x in s9)/len(s9)),
          '| CB23 %.2f'%c[1][2],'random14 mean %.2f'%(sum(s14)/len(s14)),'frac >= %.3f'%(sum(x>=c[1][2] for x in s14)/len(s14)))
