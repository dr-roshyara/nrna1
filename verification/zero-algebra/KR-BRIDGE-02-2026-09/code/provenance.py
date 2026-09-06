r"""LEVEL-AWARE READ-DISJOINTNESS GATE  (KR-BRIDGE-02 defect fix).

DEFECT: the first gate compared Pi's and Q's declared reads on the ORIGINAL fields.
That is unsound. Pi reads T(D), not D, and a transformation can DERIVE a field from
sources Pi never declared. Concretely:

    T_D_relational ranks records WITHIN THEIR TAG GROUP, so   rank = f(v, tag).
    Pi_value_set falls back to `rank` when `v is None` -- which is exactly the T_D case.
    Therefore on T_D output, Pi_value_set EFFECTIVELY READS {v, tag}.
    Q_argmax_ntags reads {src, tag}.  They SHARE `tag`.

The pair (Pi_value_set, Q_argmax_ntags) passes the naive gate and FAILS the real one
at the T_D level. Since that cell carried the ONLY substantial surviving effect, the
naive gate would have produced a bridge that was partly an artifact of shared reads --
the precise circularity the gate exists to prevent.

The gate is therefore a property of the TRIPLE (Pi, Q, T), never of the pair (Pi, Q).
"""
# what each transformation's output fields are DERIVED FROM, in original-field terms
PROVENANCE = {
 # output field  <-  ORIGINAL fields it can depend on.  "arity" = the record COUNT.
 # CORRECTED after AUDIT A1 (empirical perturbation test) falsified the first map:
 #   T_C_dedup decides WHICH RECORDS SURVIVE using `v`, so the surviving src/tag/t
 #   columns AND the record count all depend on `v`.  The first map declared them
 #   independent of v -- an UNSOUND under-declaration, the direction that matters.
 "T_A_preserving":  {"v": {"v"}, "src": {"src"}, "tag": {"tag"}, "t": {"t"},
                     "rank": set(), "arity": set()},
 "T_B_lossy":       {"v": {"v"}, "src": set(),   "tag": {"tag"}, "t": {"t"},
                     "rank": set(), "arity": set()},
 "T_C_dedup":       {"v": {"v"}, "src": {"src","v"}, "tag": {"tag","v"}, "t": {"t","v"},
                     "rank": {"v"}, "arity": {"v"}},
 # rank is computed by ordering values WITHIN a tag group -> depends on v AND tag.
 # (The perturbation test observes only `tag` because it compares SORTED columns and a
 #  within-group value swap can permute ranks without changing the rank multiset. The
 #  empirical test is a LOWER BOUND on dependency; {v,tag} is the conservative truth.)
 "T_D_relational":  {"v": set(),  "src": {"src"}, "tag": {"tag"}, "t": set(),
                     "rank": {"v", "tag"}, "arity": set()},
 # T_E sets src to None for every record, so the src COLUMN carries no information --
 # but its LENGTH still depends on v (dedup). Declared {"v"}: conservative and sound.
 "T_E_dedup_lossy": {"v": {"v"}, "src": {"v"},  "tag": {"tag","v"}, "t": {"t","v"},
                     "rank": {"v"}, "arity": {"v"}},
 "T_F_destroying":  {"v": set(),  "src": set(),  "tag": set(),   "t": set(),
                     "rank": set(), "arity": set()},
 "T_G_recoding":    {"v": {"v"}, "src": {"src"}, "tag": {"tag"}, "t": {"t"},
                     "rank": set(), "arity": set()},
}
# the OUTPUT fields each Pi actually touches, including its fallback path
# The OUTPUT fields each Pi touches, INCLUDING its fallback path and INCLUDING "arity":
# every Pi's value is a function of WHICH RECORDS SURVIVE, so each reads the record count.
PI_OUTPUT_READS = {
 "Pi_value_multiset":  {"v", "rank", "arity"},   # falls back to rank when v is None
 "Pi_value_set":       {"v", "rank", "arity"},   # same fallback
 "Pi_tag_multiset":    {"tag", "arity"},
 "Pi_source_multiset": {"src", "arity"},
 "Pi_arity":           {"arity"},                # reads ONLY the record count
}
Q_READS = {
 "Q_argmax_ntags": {"src", "tag"}, "Q_source_set": {"src"},
 "Q_majtag_total": {"tag", "v"},   "Q_max_value": {"v"}, "Q_tag_set": {"tag"},
}

def effective_pi_reads(pi_name, tname):
    """Pi's reads on T(D), pulled back to ORIGINAL fields through the provenance map."""
    prov = PROVENANCE[tname]
    out = set()
    for f in PI_OUTPUT_READS[pi_name]:
        out |= prov.get(f, set())
    return out

def admissible_triple(pi_name, q_name, tname):
    pr = effective_pi_reads(pi_name, tname); qr = Q_READS[q_name]
    shared = sorted(pr & qr)
    return (len(shared) == 0), shared, sorted(pr), sorted(qr)
