#!/usr/bin/env python3
"""
STEP 290 / N-1 AUDIT.  Question: are the corpus's == (semantic) and ~ (observational)
one relation or two?
DISCIPLINE: only corpus-defined inputs. No invented example is used to make the
relations differ. Missing definitions FAIL LOUDLY rather than being filled in.
"""
import sys

print("="*76); print("STEP 290  N-1 ADJUDICATION AUDIT"); print("="*76)
print("""
ASSUMPTIONS PRINTED UP FRONT (mandate s12):
 A1 Only primary corpus text is admitted as a definition.
 A2 A self-labelled "candidate" is NOT an assertion of the relation's identity.
 A3 Different symbols alone do not prove distinctness; a shared formula alone does
    not prove identity.
 A4 No philosophical source is used. No governance preference is used.
 A5 Results measured here are marked MEASURED; corpus facts are marked CORPUS.
""")

# ---- the primary register, transcribed from primary text ----
DEFS = [
 # (locus, symbol, natural name, definition or None, self-label)
 ("246 A-D",   "=",        "structural equality",        "field/representation identity", "listed, not defined"),
 ("246 A-D",   "==",       "semantic equality",          None,                            "listed, not defined"),
 ("246 A-D",   "~",        "observational equivalence",  None,                            "listed, not defined"),
 ("246 A-D",   "~=_lambda","provenance-sensitive",       None,                            "listed, not defined"),
 ("261.1",     "=",        "representation/structural",  None,                            "register entry"),
 ("261.1",     "==",       "semantic equality",          None,                            "register entry"),
 ("261.1",     "~",        "observational equivalence",  None,                            "register entry"),
 ("261.1",     "~=_I",     "identity equivalence",       None,                            "register entry"),
 ("261.1",     "~=_P",     "provenance-sensitive",       None,                            "register entry"),
 ("261.1",     "~=_H",     "historical equivalence",     None,                            "register entry"),
 ("261.5",     "~",        "observational (histories)",  "forall O in O:   O(H1)=O(H2)",  "definition"),
 ("261.5",     "~",        "observational (states)",     "forall O in O_K: O(K1)=O(K2)",  "definition"),
 ("261.21",    "==_K",     "Knowledge-State equality",   "forall O in O_K: O(K1)=O(K2)",
                                          'CANDIDATE - "a candidate semantic equality"; '
                                          '"a candidate formal definition, not a completed theorem"'),
 ("258.8",     "==_K",     "Knowledge-State equivalence","forall O in O: O(K1)=O(K2)",
                                          'PROPOSAL - "a stronger definition is observational"'),
 ("261.20/25", "=_str",    "structural",                 None, "typed family, 2nd notation"),
 ("261.20/25", "==_sem",   "semantic",                   None, "typed family, 2nd notation"),
 ("261.20/25", "~_obs",    "observational",              None, "typed family, 2nd notation"),
 ("261.20/25", "SameId",   "identity",                   None, "typed family, 2nd notation"),
 ("261.20/25", "==_H",     "historical",                 None, "typed family, 2nd notation"),
 ("261.20/25", "==_P",     "provenance",                 None, "typed family, 2nd notation"),
]
print("--- primary register: %d entries ---" % len(DEFS))
defined  = [d for d in DEFS if d[3]]
undefined= [d for d in DEFS if not d[3]]
print("  entries carrying an actual definition : %d" % len(defined))
print("  entries that only NAME a relation     : %d" % len(undefined))
for l,s,n,d,lab in defined:
    print(f"    {l:11} {s:10} := {d:32} [{lab.split(' - ')[0][:40]}]")

# ---- Q1 does the corpus explicitly distinguish == and ~ ? ----
print("\n--- Q1: explicit distinction? ---")
distinct_loci=[("246","four relations listed as non-interchangeable"),
               ("261.1","six relations; 'should therefore NOT be collapsed'"),
               ("261.20",'"KnowledgeOS requires a typed FAMILY of relations"'),
               ("261.25",'K = (K, =_str, ==_sem, ~_obs, SameId, ==_H, ==_P) - 7-tuple'),
               ("261.20",'"they should not be conflated"')]
for l,t in distinct_loci: print(f"  CORPUS {l:8} {t}")
print("  => Q1 = YES, EXPLICITLY, at %d independent loci, in TWO notations." % len(distinct_loci))

# ---- Q5/Q6 is the SAME formula used for both? ----
print("\n--- Q5/Q6: is one formula used for both symbols? ---")
f_obs  = "forall O in O_K: O(K1)=O(K2)"
hits=[(l,s,lab) for l,s,n,d,lab in DEFS if d==f_obs]
for l,s,lab in hits: print(f"  {l:9} {s:7} := {f_obs}   [{lab[:52]}]")
print(f"  => the SAME formula carries {len({h[1] for h in hits})} distinct symbols: "
      f"{sorted({h[1] for h in hits})}")

# ---- the decisive test: is that an ASSERTION or a CANDIDATE? ----
print("\n--- DECISIVE: assertion of identity, or candidate proposal? ---")
for l,s,n,d,lab in DEFS:
    if d==f_obs or (d and d.startswith("forall O in O:")):
        kind = "CANDIDATE/PROPOSAL" if ("CANDIDATE" in lab or "PROPOSAL" in lab) else "ASSERTION"
        print(f"  {l:9} {s:7} -> {kind}")
        print(f"            self-label: {lab}")
print("""
  => BOTH loci that give == a definition SELF-LABEL it as a candidate/proposal:
     261.21 'a candidate formal definition, NOT a completed theorem'
     258.8  'a stronger definition IS observational' (a proposal), and 258.37
            'we still do not have the final =_X or ==_K'
  => Therefore the corpus does NOT assert ==_K = ~. It PROPOSES filling the
     ==_sem slot with the observational formula, and marks it unratified.""")

# ---- Q: can we construct a distinguishing pair from corpus material only? ----
print("\n--- distinguishability: exists K1,K2 with K1 == K2 but not K1 ~ K2 ? ---")
def require(name, present):
    if not present:
        print(f"  FAIL LOUDLY: required definition ABSENT -> {name}")
        return False
    return True
have_sem_proc = require("a decision procedure for ==  (semantic)", False)
have_OK       = require("a closed observation set O_K",            False)
if not (have_sem_proc and have_OK):
    print("""
  => UNDECIDABLE FROM CURRENT CORPUS.
     Constructing either witness needs (a) a procedure for == independent of the
     observational formula, or (b) a closed O_K. Neither exists (261.21 boxes
     'O_K is not yet completely closed'; 012 s35 makes == not fully decidable).
     NO HYPOTHETICAL IS CONSTRUCTED. (mandate s4)""")

# ---- required negative checks (mandate s11) ----
print("\n--- required negative checks ---")
for chk,verdict in [
 ("== = ~ assumed because both are called 'equivalence'","NOT ASSUMED - rejected; names are not evidence"),
 ("== != ~ assumed because symbols differ","NOT ASSUMED - Q1 rests on explicit prose, not symbols"),
 ("~_X assumed to be the canonical ~","NOT ASSUMED - ~_X is Sigma-level; corpus ~ is forall O in O_K"),
 ("~_X assumed to be behavioural equivalence","NOT ASSUMED - 258.11 separates label from behaviour"),
 ("~=_lambda assumed equivalent to either","NOT ASSUMED - 261.8 leaves it two-branched"),
 ("Sigma's existence treated as proof of observational equivalence","NOT ASSUMED"),
 ("delta-congruence dependency treated as proof of semantic equality","NOT ASSUMED - a dependency is not a semantic identity"),
 ("philosophical sources used","NONE USED"),
 ("governance preference used as evidence","NONE USED")]:
    print(f"  [ok] {chk:62} {verdict}")

print("\n--- degeneracy carry-forward (289) ---")
print("  ~_empty      = UNIVERSAL relation  (1 block)     -> degenerate, not a candidate")
print("  ~_{A,S,R,V,C}= DISCRETE relation   (2240 blocks) -> degenerate, not a candidate")
print("  => 30 non-degenerate ~_X candidates. NOT re-running the 2240 audit (mandate s5).")

print("\n"+"="*76)
print("VERDICT INPUTS ONLY. Classification is made in REFINED-STEP-290.md, not here.")
print("Nothing above selects a relation, repairs the corpus, or recommends a branch.")
print("="*76)
