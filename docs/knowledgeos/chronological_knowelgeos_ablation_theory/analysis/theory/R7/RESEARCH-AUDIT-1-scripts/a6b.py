import random
exec(open('a5.py').read().split("for name,enc in")[0])
for name,enc in [('audit',aud),('mine',mine)]:
    for lab,rows in [('TA',TA),('TA+TB',TA+TB)]:
        rs=rowsets(enc,rows)
        q=modularity(rs,PC_parts)
        random.seed(11); mm=[]
        for _ in range(20000):
            A=set(random.sample(PLACED,9)); mm.append(modularity(rs,[A,set(PLACED)-A]))
        print(name,lab,'Pc Q %.3f'%q,'frac random >= Pc: %.4f'%(sum(x>=q for x in mm)/len(mm)),'max %.3f'%max(mm))
