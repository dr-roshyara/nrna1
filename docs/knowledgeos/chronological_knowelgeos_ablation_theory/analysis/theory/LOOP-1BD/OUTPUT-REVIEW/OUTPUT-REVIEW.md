# Output review — Protocol B (r3) execution

**Verdict: PASS_WITH_LIMITATIONS.**
- Every answer in `70_answers.tsv` and the headline in `71_overall.tsv` match my hand recomputation from `60`, `58` and `56` under PROTOCOL-B §2, §3 and §5.
- The integrity records agree with each other. No identity strings appear in any output.
- The limitations are about what the outputs can show, not about conformance. Two matter most:
  - the identity sets are very small (§4.1);
  - the K-NORMREG vocabulary probably over-captures (§4.2).
- I did not recompute any hash, because I had no shell in this review.

Sources: PROTOCOL-B.md, scope_b.sh (r3), REVIEW-r3.md, RUN-RECORD.md, SHA256SUMS-RUN-APP, QUESTIONS.md, and the ten files in RUN-ROOT/. I did not execute anything.

This review describes the outputs only. It does not interpret them for any hypothesis (H-D, H-B or any other).

---

## 1. Integrity

| Check | Result |
|---|---|
| SHA256SUMS coverage | It lists 9 files: `00_env`, `00_errors`, `00_script.sha256`, `56`, `58`, `60`, `61`, `70`, `71`. Together with `SHA256SUMS` itself, which `finish()` leaves out by design, that is all 10 files §5 specifies, and all 10 are present in RUN-ROOT. Any extra file present at `finish()` would have been hashed, and none was. |
| Hash values | **I did not recompute them** (I had no shell). One value checks out by inspection: `00_errors.tsv` is empty, and its listed hash `e3b0c442…b855` is the SHA-256 of the empty string. |
| Script hash | `00_script.sha256` = `e9e0975b…37a8`, which is the hash RUN-RECORD gives. I did not confirm that the local `scope_b.sh` hashes to this value. |
| Two runs identical | `SHA256SUMS-RUN-APP` and `RUN-ROOT/SHA256SUMS` are line-for-line identical (compared by reading). The `diff -r` IDENTICAL result is RUN-RECORD's claim; I did not re-run it. Apart from SHA256SUMS, the app-run output files were not available to me. |
| Exit codes | RUN-RECORD gives 0 for both runs. This is consistent with the script: code 0 requires an empty `00_errors.tsv` and guard rc 1, and the outputs show both (empty file, `identity_guard PASS`). |
| Errors file | Empty, in agreement with its hash. |
| HEAD record | `9f1bcab5…`, "unchanged before and after", appears **only in RUN-RECORD**. No output file records HEAD, the tip count or the ref-set hash. That is consistent with the protocol (§1: the result does not depend on HEAD). However, Q8 says "rerunnable on same ref set", and no output pins that ref set (see A6). |
| Internal consistency | `61_path_ids.tsv` has 394 rows: 6 K-CI, 3 K-HOOK, 202 K-NORMREG, 153 K-STATUS and 30 K-DEPLOY. These equal the `paths` column of `60`. |

## 2. Conformance (hand recomputation)

Inputs from `60`: `channel, paths, commits, writers, oe, on, nnp, nn`. From `58`, `commits_signed`=23 and `tags_signed`=0. From `56`, hooks=0 and hooksPath=unset.

| Channel | Rule path | Expected Q7 | Observed Q7 | ok |
|---|---|---|---|---|
| K-CI | np=6>0; not NORMREG; nnp=202>0; cfg → rule 3 | `IND[mechanism_host_enforced_unobservable;overlap_exec=1;overlap_norm=1]` | same | ✔ |
| K-HOOK | np=3; cfg → rule 3 | `IND[mechanism_host_enforced_unobservable;overlap_exec=1;overlap_norm=1]` | same | ✔ |
| K-CODEOWNERS | np=0 | `NOT_APPLICABLE(Q1)` (Q1 `NOT_EVIDENCED_AT_TIPS`) | same | ✔ |
| K-NORMREG | rule 1 (definitional) | `SELF_ATTESTED[norm_register;overlap_exec=3]` | same | ✔ |
| K-STATUS | observable; W=3, N_c=3, E≥3 (implied by oe=3); on=3>0 → rule 5 | `NOT_INDEPENDENT[overlap_norm=3]` | same | ✔ |
| K-DEPLOY | observable; W=3, N_c=3, E≥3; on=3>0 → rule 5 | `NOT_INDEPENDENT[overlap_norm=3]` | same | ✔ |
| K-SIG | signed=23>0 | `IND(signatures not verified)` | same | ✔ |
| K-LOCALHOOK | 0 hooks, unset | `NOT_APPLICABLE(Q1)` (Q1 `NOT_EVIDENCED_LOCALLY`) | same | ✔ |
| 5 host rows | — | `NOT_CHECKABLE_LOCALLY` ×8 | same | ✔ |

- **Q1–Q6 and Q8** match the script's mapping on every row:
  - Q3 `n=1/1/3/3/3`;
  - Q5 = `ND` on every evidenced row;
  - Q6 = the single outcome;
  - Q4 for K-SIG = `NO_CLIENT_SET(signer clock)`.
- **Headline.** The local Q7 values are 0 × INDEPENDENT…, 3 × `IND` (K-CI, K-HOOK, K-SIG), 2 × `NOT_INDEPENDENT` and 1 × `SELF_ATTESTED`. By first match, the headline is `LOCAL_INDEPENDENCE_NOT_DETERMINABLE`. The observed headline is the same, and so are the counts 0 / 3 / 2 / 1, `not_evidenced`=2 and `host_channels`=5. **headline_ok = true.**

## 3. Identity

- **What the outputs hold:** counts, 16-hex path IDs, protocol labels, `git version 2.55.0`, and the script hash.
- **What they do not hold:** no `@`, no e-mail, no name, no ref name, no URL, no path string and no signature block. **identity_ok = true.**
- **Residual risk:** this is the declared N10. The path IDs are unsalted, so predictable paths can be reversed by dictionary. That exposes paths, not identities.

## 4. Anomalies requiring disclosure

**A1 — Very small identity sets mean the overlap test has almost no statistical power.**
- The counts are |W| ≤ 3 for every channel (1 for K-CI and K-HOOK) and |N_c| = 3. |E| is **not emitted**; it is ≥ 3, inferred from overlap_exec = 3.
- The commit volume is large (4028 commits) compared with these sets. The whole author-e-mail universe may be only about 3 identities, but the outputs do not show its size.
- When |W_c| + |N_c| exceeds the universe size, a zero overlap is impossible under any assignment of identities. Overlap counts of 3/3 then show that the few identities all touched both path sets. They do not discriminate between role arrangements.
- In the other direction, a zero overlap at these sizes would also be fragile.
- Aliases are unresolved, so 3 e-mails may stand for fewer people, or for non-human authors (tools or agents).
- I note this as a property of the test at these counts, not as a reading of the result.

**A2 — K-NORMREG over-capture (NB-12, NB-13 and N15).**
- **Size.** K-NORMREG has 202 tip paths (409 path-commit touches, 3 writers), and K-STATUS has 153.
- **Vocabulary.** The tokens include `decision(s)`, `norm`, `register`, `authoriz*` and `ruling(s)`, with the r3 infix boundary. They can catch:
  - developer data and i18n files;
  - test fixtures;
  - config, including code-root files that are then removed from E.
- **Self-contamination (N15).** Only top-level `docs/knowledgeos/` is excluded. `.claude/` and `claude/` are neither excluded nor checked, so programme-adjacent artefacts there may be counted as "norm register" paths. That would be the same self-contamination the knowledgeos rule exists to prevent.
- **Consequence (NB-13).** All 202 paths make up N_c for K-STATUS and K-DEPLOY. So `overlap_norm=3` may partly be developer authorship labelled with the norm role, and it may partly come from programme artefacts.
- **Not visible in the outputs.** The outputs contain no path strings, so the composition cannot be determined from them.

**A3 — K-SIG: 23 of 4028 commits carry a signature header, and 0 tags are signed.**
- **What was checked.** Only the presence of a header. Signatures were not verified and the keyring was not consulted.
- **Merges are counted.** `rev-list` here does not use `--no-merges`, so merge commits are included. W, E and N_c exclude them.
- **Possible signers** (named only; **I did not verify any of them**):
  - (a) a developer key set up on one machine or for one period;
  - (b) a hosting platform's own key on commits made through the web UI or API (merges, squash, edits);
  - (c) bots or automation (dependency updaters, release or CI bots) whose commits the platform signs;
  - (d) imported third-party history (skeleton, upstream or subtree) signed by outside parties;
  - (e) an agent or tool session with signing configured;
  - (f) any of these with an invalid, expired or untrusted key, because presence says nothing about validity;
  - (g) the `gpgsig` header also covers SSH and x509 commit signatures.
- **Lower bound.** Under N7 and NB-5, 23 is a lower bound. Unchecked `cat-file` errors and SIGPIPE count as unsigned, and x509 tag signatures are missed.

**A4 — A path shared between K-STATUS and K-DEPLOY.** The ID `8984782af5dd32c1` appears under both channels. The protocol allows this, since only K-NORMREG and K-STATUS are disjoint, but it means the two channels' W sets are not disjoint. I did not cross-check every ID.

**A5 — |E| is not emitted.** Rule 4 needs E ≠ ∅, and that can only be inferred from oe > 0. The executor-set size is not disclosed in any output.

**A6 — The reproducibility anchor is outside the outputs.**
- HEAD, the tip count and the ref-set identity are recorded only in RUN-RECORD. They are not in the hashed outputs.
- The determinism test changed only the cwd. It ran on the same machine and the same ref set.

**A7 — Declared items still in force.**
- Q4 has no content-timestamp qualifier (N13).
- K-CI and K-HOOK each have one writer, and that writer is in both E and N_c (NB-9 is possible).
- Repo-local config was readable, as declared.

## 5. Neutrality

- The outputs contain only protocol outcome labels and counts. They make no hypothesis, conformance or scoring claim.
- This review draws no conclusion about H-D, H-B or any other theory.

## 6. Per QUESTIONS.md — does any locally checkable channel qualify as an *independent* norm-in-force record?

- **K-CI:** `INDEPENDENCE_NOT_DETERMINABLE[mechanism_host_enforced_unobservable]`; Q5 `NOT_DETERMINABLE_BY_EXISTENCE_CHECK`.
- **K-HOOK:** `INDEPENDENCE_NOT_DETERMINABLE[mechanism_host_enforced_unobservable]`; Q5 `NOT_DETERMINABLE_BY_EXISTENCE_CHECK`.
- **K-CODEOWNERS:** `NOT_EVIDENCED_AT_TIPS` → `NOT_APPLICABLE(Q1)`.
- **K-NORMREG:** `SELF_ATTESTED[norm_register]` (definitional); Q5 `NOT_DETERMINABLE_BY_EXISTENCE_CHECK`.
- **K-STATUS:** `NOT_INDEPENDENT[overlap_norm=3]` (observed, low power, see A1); Q5 `NOT_DETERMINABLE_BY_EXISTENCE_CHECK`.
- **K-DEPLOY:** `NOT_INDEPENDENT[overlap_norm=3]` (observed, low power, see A1); Q5 `NOT_DETERMINABLE_BY_EXISTENCE_CHECK`.
- **K-SIG:** `INDEPENDENCE_NOT_DETERMINABLE(signatures not verified)`; Q5 `NOT_DETERMINABLE_BY_EXISTENCE_CHECK`.
- **K-LOCALHOOK:** `NOT_EVIDENCED_LOCALLY` → `NOT_APPLICABLE(Q1)`.
- **Host channels (5):** `NOT_CHECKABLE_LOCALLY`.
- **Overall:** `LOCAL_INDEPENDENCE_NOT_DETERMINABLE`; no channel reached `INDEPENDENT_AT_IDENTITY_COUNT_LEVEL`; `COMPLETE_VS_HOST=NOT_SHOWN`.

## 7. Scoping questions for the human

1. What is the total number of distinct author e-mails in the tip set, and the size of |E|? These would set the power of the overlap test in A1. Should they be emitted as counts?
2. Of the 202 K-NORMREG paths, and the 153 K-STATUS paths, how many are under `.claude/`, `claude/`, the code roots, `lang/`, `config/` or `tests/`? Should `.claude/` and `claude/` be treated as programme artefacts and excluded, as `docs/knowledgeos/` is (N15)?
3. Which kind of signer (A3 a–g) is plausible for the 23 signed commits? Are most of them merge or web-UI commits? Is verifying them in scope at all?
4. Should HEAD, the tip count and the ref-set hash be emitted into the hashed outputs, so that Q8 "same ref set" is pinned?
5. Should one person with several e-mails, or non-human authors, be handled (aliases) before any count-overlap result is used?

## 8. Limitations of this review

- I recomputed no hashes, and I did not check that the local `scope_b.sh` hashes to `e9e0975b…`.
- I did not see the app-run outputs, only their SHA256SUMS.
- I checked for duplicate path IDs across channels by eye only, not exhaustively.
- I could not list the directory, so I established the file set from the protocol and from SHA256SUMS.
- The claims about HEAD, exit codes and `diff -r` rest on RUN-RECORD.
