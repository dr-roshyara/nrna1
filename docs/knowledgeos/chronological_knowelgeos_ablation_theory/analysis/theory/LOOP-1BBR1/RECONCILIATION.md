# 1bb-r1: reconciliation. **FAIL: neither search is executed** (human rule)

| | |
|---|---|
| Frozen | `f6c7eb1e9` |
| Review verdict | **FAIL** |
| D1–D14 | FIXED: D3, D4, D6, D7, D10, D11, D12, D13, D14 · **PARTIAL: D1, D2, D5, D8, D9** |

## New defects (substantive; adopted)
- **N1 (formal reasoning / independence):** third-party application (NIF-B) is **behaviour both hypotheses explain**, yet it established N-FORCE and fired a P4-HD falsifier. That collapses distinction 4 ("acts according to") into distinction 2 ("in force"). **Fix:** extend G-D1 to all behaviour. A force premise resting only on NIF-B is DISJUNCTIVE; only NIF-A (an independent state record) can FIRE.
- **N2 (formal reasoning):** "no permitting act in a complete channel ⇒ not permitted" **presupposes H-D's act-exclusivity (P1)**. Under H-B reading 1 a permission can arise without an act. P2 / C1 / O5 would therefore falsify H-B on an H-D premise. **Fix:** a non-permission derived from the absence of an act is DISJUNCTIVE {H-D P1, H-B conformance} unless NIF-A shows the state.
- **N3:** the maker's own records were admitted as a fallback **against H-B only** (asymmetric).
- **N4:** the sole adopter's "already governs" was frozen as non-adoption, with its sensitivity unscored.
- **Smuggling:** H-D (the act-exclusivity baseline; the asymmetric fallback) **and** H-B (practice treated as force). The corrected spec overcorrected in one place and undercorrected in another.

## Protocols
- **Rank-0 protocol: FAIL, not executable.**
  - R0-1: the script dies in a bare mirror clone (`git rev-parse --show-toplevel`), so every run yields CAPTURE_ERROR;
  - R0-2: merge-request / pull refs are not fetched;
  - target matching is over-inclusive (mentions; governance files) and under-inclusive (ref names; Unicode hyphens; untokened work);
  - merges are not operational.
- **Scoping protocol: PASS_WITH_LIMITATIONS.** It is bounded and existence-only, **but:**
  - SC-1: NIF_A_CHANNEL_EXISTS is **unreachable by construction** (content is required, but the check is existence-only);
  - SC-2: "not determinable" is reported as a negative finding;
  - SC-3: only current-tree paths are checked;
  - SC-4: completeness outcomes are unreachable;
  - SC-5: it outputs identities.

## Decision
The human rule is "if the reviewer returns FAIL, do not execute either search": **neither search is executed.** The authorization "fix 1bb before acquisition" covers repair, and no protocol has yet been frozen. So **one targeted repair round (1bb-r2)** follows, restricted to the FAIL items. **If r2 is not PASS or PASS_WITH_LIMITATIONS without blocking issues, STOP for the human**; no further rounds.
