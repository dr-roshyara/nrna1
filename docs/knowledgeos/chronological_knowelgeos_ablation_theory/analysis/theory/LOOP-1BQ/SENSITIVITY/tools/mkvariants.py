import os, subprocess
base = open('base/slice_enum.py').read()
V = {
 'V1a_lastclause_modified_times_only': (
  "AMB-1 alt (a): the (iii)-L last clause constrains only change points of sigma' at a time where sigma' was modified (sigma'(t) != sigma(t)); i.e. 'the new value' = the value newly set by h'.",
  [("                if sig[0] != sig[1]:\n", "                if sig[0] != sig[1] and sig[1] != s1:\n")]),
 'V1b_lastclause_new_change_points_only': (
  "AMB-1 alt (b): the (iii)-L last clause constrains only change points of sigma' that are not already change points of sigma with the same new value.",
  [("                if sig[0] != sig[1]:\n", "                if sig[0] != sig[1] and not (s0 != s1 and sig[1] == s1):\n")]),
 'V2_added_act_carries_new_value': (
  "AMB-2 alt: an added a2 must carry the new value, v(a2) = sigma'(t(w1)).",
  [("                if not f1_ok(sig[0], sig[1], A1, A2):\n",
    "                if A2 is not None and A2[2] != nv:\n                    continue\n                if not f1_ok(sig[0], sig[1], A1, A2):\n")]),
 'V3_sigma_changes_at_violation_points': (
  "AMB-3 alt: Prop 4.1(iii) wording - sigma' may differ from sigma at every violation point of h (sigma(t) not in P), not only at t(w1); at t(w1) it must be in P (w1 conforms), at another violation point it ranges over V.",
  [("    for nv in V:\n        sig = [s0, s1]\n        sig[w1] = nv\n        if nv not in P:  # w1 conforms in h'\n            continue\n",
    "    other = 1 - w1\n    ovals = tuple(V) if (s0, s1)[other] not in P else ((s0, s1)[other],)\n    for nv, ov in [(a, b) for a in V for b in ovals]:\n        sig = [s0, s1]\n        sig[w1] = nv\n        sig[other] = ov\n        if nv not in P:  # w1 conforms in h'\n            continue\n")]),
 'V5_sigma_prime_change_points_only_at_tw1': (
  "AMB-4 alt: P0.2 '(sigma changes only at t(w1))' read additionally as Chg(sigma') subset of {t(w1)}: with t(w1) = 0, sigma' must be constant.",
  [("                if sig[0] != sig[1]:\n",
    "                if w1 == 0 and sig[0] != sig[1]:\n                    continue\n                if sig[0] != sig[1]:\n")]),
 'V6_control_Rb_vacuous_on_empty_W': (
  "AMB-5 (REJECTED reading, run as a control): B-conf^b 'some w in W conforms' made vacuously true when W is empty.",
  [("    bconf_b = w1 is not None and sig[w1] in P\n", "    bconf_b = w1 is not None and sig[w1] in P\n    bconf_b_rb = w1 is None or sig[w1] in P  # R-b only; R-c keeps its explicit P0.1 restatement\n"),
   ('        "R-b": (brec, HD0, bchg and bconf_b and brec),\n', '        "R-b": (brec, HD0, bchg and bconf_b_rb and brec),\n')]),
}
os.makedirs('variants', exist_ok=True)
for name, (desc, reps) in V.items():
    src = base
    for a, b in reps:
        assert src.count(a) == 1, (name, a)
        src = src.replace(a, b)
    d = os.path.join('variants', name); os.makedirs(d, exist_ok=True)
    open(os.path.join(d, 'tmp.py'), 'w').write(src)
    diff = subprocess.run(['diff', '-u', '--label', 'base/slice_enum.py (Opus, sealed)', '--label', name + '.py', 'base/slice_enum.py', os.path.join(d, 'tmp.py')], capture_output=True, text=True).stdout
    hdr = '# VARIANT ' + name + '\n# ' + desc + '\n# Exact diff from the base (LOOP-1BQ/CLAUDE-SIDE/slice_enum.py):\n' + ''.join('# ' + l + '\n' for l in diff.splitlines()) + '#\n'
    # keep shebang first
    lines = src.split('\n', 1)
    open(os.path.join(d, name + '.py'), 'w').write(lines[0] + '\n' + hdr + lines[1])
    os.remove(os.path.join(d, 'tmp.py'))
    print(name); print(diff)
