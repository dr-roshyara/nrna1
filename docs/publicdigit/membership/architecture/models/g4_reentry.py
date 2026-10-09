"""G-4 extension: membership End@e followed by re-admission@g (new period, same Member,
ratified re-entry semantics). Does BQ-P3 (LAPSE vs SUSPEND) change observable results?"""
T = 6
cases = differ = 0; example = None
for x in range(T):
    for y in [None] + list(range(x + 1, T + 1)):
        held = lambda t, x=x, y=y: x <= t and (y is None or t < y)
        for e in range(1, T):
            for g in [None] + list(range(e, T)):        # g == e: same-day re-entry (Lemma 2)
                m = lambda t, e=e, g=g: t < e or (g is not None and t >= g)
                suspend = [held(t) and m(t) for t in range(T)]
                lapse = []; ended = False
                for t in range(T):
                    if held(t) and not m(t): ended = True
                    lapse.append(held(t) and not ended and m(t))
                cases += 1
                if lapse != suspend:
                    differ += 1
                    if example is None: example = dict(tenure=(x, y), end=e, readmit=g, suspend=suspend, lapse=lapse)
print(dict(cases=cases, lapse_vs_suspend_differ=differ)); print(example)
