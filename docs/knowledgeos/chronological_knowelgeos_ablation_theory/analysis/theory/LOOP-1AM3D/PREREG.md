# 1am-3d: pre-registration (frozen before either subagent runs)

- **Purpose:** a **second, disjoint conformance cluster**. This moves conformance from WEAK toward SUPPORTED at a grain where the typed header is held constant. All rows carry "Delivery Governance · Acceptance".
- **Rows:** R-66, R-67 ("WITHDRAWN"), R-71 ("Engineering does not self-certify it"), R-93 ("ACCEPTED" + "NOT ACCEPTED").
- **Selection:** a locate on the markers only.

| Reconciled result | Consequence |
|---|---|
| ≥ 1 STRICT conformance pair, in a cluster disjoint from R-86/R-91 | conformance has 2 disjoint clusters. It becomes **SUPPORTED at the coarse grain**, and gets its **first witness at the typed-header grain** |
| only POSSIBLE | conformance stays WEAK, +1 possible; the UNK fields become locate targets |
| only item_kind witnesses | kind explains the acceptance outcomes; conformance is not demonstrated at this grain |
| none | no change |

**Sealed main-analyst expectation (bias check):** R-71 or R-93 yields a POSSIBLE (not STRICT) conformance witness, because the accepted counterpart's conformance is rarely *stated*. Moderate confidence.
