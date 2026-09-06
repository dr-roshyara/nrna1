#!/usr/bin/env python3
r"""
circularity.py — tests whether the corpus's OWN mandatory-requirement set can
discriminate anything under the corpus's OWN necessity criterion.

COMMISSION GN-79 / GN-80. NOTHING HERE IS RATIFIED.

The criterion is stated twice in the corpus over two DIFFERENT index sets:

  step 272A §272A.16   o in O_core  <=>  exists d in D_mandatory : Remove(o) => Loss(d)
  step 277  §277.30    o is primitive <=> exists r in R_mandatory : r not in Closure(T-o)

D_mandatory IS enumerated (272A §272A.17, the 19-row Mandatory Distinction
Register). R_mandatory is NEVER defined anywhere in step 277.

This script asks one question of D_mandatory: is the map
distinction -> required operation INJECTIVE and TOTAL? If it is, then every
operation is the unique realizer of its own row, and the criterion returns
"all necessary" BY CONSTRUCTION, discriminating nothing.
"""
# step 272A §272A.17, transcribed verbatim: (distinction, required operation)
D_MANDATORY = [
 ("assertion can enter state",                        "Assert"),
 ("assertion can be withdrawn",                       "Retract"),
 ("newer assertion can replace older",                "Supersede"),
 ("assertion can be derived",                         "Infer"),
 ("knowledge states can combine",                     "Merge"),
 ("evidence can support",                             "Support"),
 ("evidence can refute",                              "Refute"),
 ("observation can become evidence",                  "Qualify"),
 ("epistemic state can be assessed",                  "Assess"),
 ("incompatible assertions can be detected",          "DetectContradiction"),
 ("unresolved epistemic problem can be resolved",     "Resolve"),
 ("knowledge can be queried",                         "Query"),
 ("states can be compared",                           "Compare"),
 ("identity can be established",                      "Identity"),
 ("equality can be evaluated",                        "Equal"),
 ("historical state can be reconstructed",            "Replay"),
 ("ancestry can be traced",                           "Trace"),
 ("governance can permit/deny operation",             "Authorize"),
 ("state can be checked against policy",              "Validate"),
]

def main():
    P = "=" * 78
    print(P)
    print("IS THE CORPUS'S OWN NECESSITY CRITERION ABLE TO DISCRIMINATE?")
    print("commission GN-79 / GN-80 · NOTHING RATIFIED")
    print(P)
    ds = [d for d, _ in D_MANDATORY]
    os_ = [o for _, o in D_MANDATORY]
    print(f"rows in 272A's D_mandatory register        : {len(D_MANDATORY)}")
    print(f"distinct distinctions                     : {len(set(ds))}")
    print(f"distinct required operations              : {len(set(os_))}")
    print(f"map distinction -> operation is TOTAL     : {all(o for o in os_)}")
    print(f"map distinction -> operation is INJECTIVE : {len(set(os_)) == len(os_)}")
    print(f"map operation -> distinction is INJECTIVE  : {len(set(ds)) == len(ds)}")
    print()
    print("Consequence, computed:")
    dup = {o: [d for d, oo in D_MANDATORY if oo == o] for o in set(os_)}
    multi = {o: v for o, v in dup.items() if len(v) > 1}
    print(f"  operations serving more than one distinction : {len(multi)}")
    print(f"  distinctions with more than one realizer     : 0 "
          f"(the register lists exactly ONE operation per row)")
    print()
    print("  Therefore, for EVERY o in the register there is EXACTLY ONE d whose only")
    print("  listed realizer is o. Removing o loses d. The criterion returns:")
    print()
    print(f"      |O_core| = {len(set(os_))}  =  |O_sem| — ALL 19 'necessary'")
    print()
    print("  This is not a derivation. It is a RESTATEMENT: the register was written by")
    print("  reading an operation off each distinction, so the necessity test over it")
    print("  is a TAUTOLOGY. It cannot eliminate a single member, and it cannot")
    print("  discover a member the register's author did not already name.")
    print()
    print("  ** The criterion is only informative over a requirement set derived")
    print("     INDEPENDENTLY of the operation vocabulary. That is why this")
    print("     commission derives R_A from the RATIFIED invariants and flow")
    print("     instead of from any candidate list. **")
    print()
    print(P)
    print("SECOND FINDING — the criterion's index set is UNDEFINED in the source")
    print("that states it:  step 277 §277.30 quantifies over R_mandatory and step 277")
    print("never enumerates R_mandatory anywhere. 272A §272A.16 quantifies over")
    print("D_mandatory, which IS enumerated (above) but is tautological. The two")
    print("index sets are never linked to each other.")
    print(P)

if __name__ == "__main__":
    main()
