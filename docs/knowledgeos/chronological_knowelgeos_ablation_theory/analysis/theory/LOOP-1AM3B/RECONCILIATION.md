# 1am-3b: reconciliation. Compact task report

| | |
|---|---|
| Question | D9: at the register's typed-header grain, do R-81..R-85 and R-91 have the same kind? |
| Frozen | `PREREG.md` (`5706bd8ac`); single blind verifier |
| **Disclosed bundle defect** | SOURCES omitted the R-86 row. So the verifier coded R-86's fields UNK, and it compared the header **issuer** field (R-81..85 "ARB" vs R-91 "ARB CHIEF (PREPARED)") in place of the ADOPT **actor** (the DA in both; F-LOG-0153 sources). That comparison is a category confusion caused by the bundle, **not a finding**. Outputs are kept unamended |

## Verified source facts (unaffected by the defect)

- **Y fields:** R-81 Authorization · R-82 Adoption · R-83 Adoption · R-84 Adoption · R-85 Adjudication · **R-91 "Determination on submitted evidence"**. **Y_equal = false.**
- **Separation scope = UNK:** the requirement is attributed to **Event D** (a specific design object, named in R-91's title), not to the type Y, and not stated generally.
- **Alternative not ruled out:** Y names the *act the row performs*, not the kind of object adopted. On that reading, R-91's act label itself names both collapsed steps.

## Reconciled model impact

- **The 1am-3 conformance witness is STRICT only at the coarse kind grain ("ruling").** At the typed-header grain the pair differs in Y, so it is **confounded (M_KC)**, whether Y is read as object kind or as act type.
- **Conformance: WEAK, grain-conditional.** One witness, and it holds only if kind is taken at the "ruling" grain.
- **H-KC** (conformance as a guard conditioned on the type Y): **not supported.** The text ties separation to Event D, not to Y. It stays UNK.
- **Structural observation, labelled INFERENCE:** the kind-grain choice itself decides witness strength. This is the model-integrity question: is "kind" a single dimension, or is it overloaded with the act type? The same pattern appeared for evidence in F-LOG-0149.

## Next (dynamic queue)

- **Conformance needs a second, disjoint cluster:** a pair of the same kind at *both* grains (the same Y), differing only in stated role separation.
- **Locate:** HELD / REFUSED rows whose text states self-certification or collapse, with a performed row of the same Y.
- Discrimination: **HIGH** if one exists.
