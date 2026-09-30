# EG-9 REVIEW-B v3: adversarial review of EG9-SPEC-A-v3 (delta review; authority: generated)

**Verdict: SPEC-ACCEPTABLE.**

## Precedent check (GL:502-530) — orchestrator confirmed, my v2 reading was overstated

Reread G-LOG-0024 and G-LOG-0025 directly. G-LOG-0024 is the **audit's recommendation** ("PB05 ACCEPTABLE-WITH-NOTES conditional... correction record... AUDIT-UPHELD"), not a decision. G-LOG-0025 is the **human act**: PB05 **ACCEPTED-CONDITIONAL**, "step-verify-programme's timeline and births are **quarantined**..., with the audit's correction recorded as **pending and not applied**. The other four PB05 objects and all PB05 register records are accepted with notes... **R2-001 and R2-002 records unchanged**; the quarantine is a separate marker." The quarantine lifts only after a separately authorized supplementary read.

**Confirmed: my v2-review claim overstated the precedent.** I wrote that it "conflicts with A's flat 'never applied' framing" and treated it as evidence pointing toward something like R-II (corrected object). That is wrong: the affected content was **excepted from acceptance entirely** (stayed unaccepted/quarantined), not corrected-and-accepted; the correction genuinely was "not applied," exactly as v1/v2 said. What I correctly flagged — that the finding "bore on acceptance" — is true only in the sense that it caused an *exception* (K3 §16.5 item 4, "named exceptions... stay PROPOSED"), which is a third form, not incorporation. v3's R-III is the faithful reading of the precedent; my v2 critique should be read as narrowed to "v2's citation (K3:1295-1303) didn't establish the R-I reading" (still correct, and v3 itself withdraws that citation) rather than "the precedent supports R-II" (which it does not).

## R-I / R-II / R-III — fairly stated, no strawman

Checked each row against source:
- **θ_D blindness / R-II:** correctly reasons that if the estimation object is post-audit-corrected, a same-family auditor's judgment enters the measured object for exactly the ~10% sampled records (AS: `SHARE = 0.10`, confirmed), biasing θ_D toward agreement precisely for the labels most likely to have had a real problem. This is a real, well-argued cost, not a dismissal.
- **Witness binding / R-II:** correct — a §12.3 `new_record` written after freeze is not witness-verified bytes (W1-W8 apply to the dispatched run, not to a post-hoc correction), and R7 defines no verification path for it. This is a genuine, substantive objection, stated plainly rather than hand-waved.
- **RR-3 / S7:** correctly identifies R-II as requiring pre-registered-policy text changes (a v2.8 change before the canary) — not minimized.
- **R-I / R-III costs are also stated** (R-I: EP-01 and `32-RECONCILIATION-OBJECTS.jsonl` can diverge per label, disclosed via two recorded shas; R-III: worst-cased NOT-ASSESSABLE mass grows with each exception). Neither reading is presented as costless. **No strawman found on either side.**

One clarity gap, not material: the recommendation combines R-I with "the precedent's rule that a correction is applied only by a separate human act," but doesn't say in one place what that act concretely *does*. Read against §0's own "R-III... a separately authorized supplementary read" language, the coherent meaning is that lifting a quarantine means authorizing a **fresh, properly witnessed S5 read/run** for the excepted content (which then goes through VERIFIED→AUDITED→ACCEPTED normally, becoming a later RR-3-compliant "first passing attempt") — not a bypass splice of the stored correction text into the ledger. Recommend one sentence saying this explicitly, so H-EG9-6 doesn't get read as authorizing an unwitnessed write path.

## Check (b′) + capture-fields mitigation — sound, residual honest

- **(b′) heading uniqueness**, checked before (b): directly closes the v2 parsing-ambiguity finding. Correct fix, with T-14 covering both 0 and 2+ matches.
- **(d) attempt, forward dependency**: re-confirmed `new_attempt` does not exist and `new_run` refuses at revision 7 (`ST:209-210`, verified by direct grep — exact match). "Attempt is always 1" is correctly stated as presently vacuous-but-correct, not glossed over, with T-16 pre-registered for when EG-6 (d) lands. Good discipline.
- **Residual, rewritten**: now states plainly that `check_tranche` is a *point-in-time* check, that `P3B-GOVERNANCE-LOG.md` has *no code-level append-only enforcement* (verified: unlike `P3B-STATE.json`'s hash chain, `verify_chain`/`save` at ST:97-116, confirmed by grep, or AR's write-once report at AR:65-67), and that a coordinated later rewrite of both the tranche file and its G-LOG section is undetectable **by the tool**, "visible only in git history." This is the honest, widened disclosure I asked for in v2 — it does not overclaim protection it doesn't have.
- **Mitigation (capture at acceptance)**: storing `glog_section_sha256` (section text, not whole-file, correctly reasoned since the whole file changes on every later append), `glog_commit`, and `tranche_commit`, with a dirty-tree check on the governance log itself at accept time. This converts an *undetectable* tamper into a *detectable-on-re-verification* one (compare the section extracted at `glog_commit` and at HEAD against the stored sha) without requiring the stronger fix (hash-chaining the governance log). A correctly scopes that stronger fix out as a separate backlog/governance-integrity item, appropriately, rather than absorbing it into EG-9. One honest gap A discloses rather than hides: the re-verification is a capability, not an automatic check — "a re-verification command is optional" — so undetected tampering stays possible in practice unless someone runs it. That's a fair, disclosed scope boundary for a fix this size, not a hidden weakness.

## Anything MATERIAL left?

**No.** Both v2 material findings are now resolved with sound designs and honestly-stated residuals rather than overclaimed guarantees. The one item I'd still ask for is the clarity sentence above (what "applying a correction" concretely means under R-I), which is an improvement to the human decision text, not a defect in the engineering or a gap in the argument.

## Verdict

**SPEC-ACCEPTABLE.** Recommend one added sentence to H-EG9-6 (quarantine-lift = a fresh witnessed run, not a bypass write) before it goes to the human; nothing else blocks acceptance.
