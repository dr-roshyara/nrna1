# Resolution Status — the 12 Required Decision-Tree Questions

**Q1. Was the Phase-0 corpus boundary explicit?** Yes — MD-010/MD-011, directory-location-based, dated
2026-09-01 (`01`).

**Q2. Was `docs/knowledgeos/research/` intentionally excluded?** No evidence supports intentional,
considered exclusion of this specific directory — it postdates (in git-tracking terms) the boundary
decision by 5 days, and MD-010/MD-011's own text never names it.

**Q3. If excluded, is there evidence that exclusion was merely classification mechanics rather than
substantive research-boundary policy?** **Yes** — the check-in commit's own author-authored distinction
("brainstorming corpus" vs. "research lanes," `03`) suggests a considered, if informal, boundary
judgment existed even at check-in time — not pure mechanical accident, but also not the *same* act as
MD-010/MD-011's own formal decision.

**Q4. Does M0030 establish actual provenance into `kernel-reduction/`, or only intended output
location?** **Only intended output location**, per direct re-reading (`04`) — the strongest available
evidence, but explicitly not stronger than `[RECONSTRUCTED PROVENANCE]`.

**Q5. Is `04-operator-contracts.md` chronologically compatible with being Model-B research output?**
Yes, per content-internal self-dating (`04`).

**Q6. Is its later internal supersession/qualification material significant?** Yes, but it affects
minimality conclusions, not the contract definitions themselves (`04`).

**Q7. Is `C-type-system.md` genuinely Model-B evidence or simulation-specific elaboration?** Cannot be
established as the former — no citation link found; recorded as unresolved, weaker confidence than the
operator-contract case (`05`).

**Q8. Does the repository provide an existing mechanism for admitting previously out-of-scope
evidence?** Not found within this study's own reading of `00_control/protocol.md` or MD-010/MD-011 —
both describe the boundary as established, not as containing an amendment procedure.

**Q9. Does that mechanism permit a controlled, evidence-preserving extension without reopening Phase
0?** Not answerable — no such mechanism was found to exist (Q8).

**Q10. Are C1 `ConflictRecord` or C2 `Θ` actually evidenced elsewhere in the newly searched tree?**
`ConflictRecord`: found, but self-disqualified by its own source (`06`). `Θ`: not found anywhere.

**Q11. What remains unresolved after this census?** Whether `docs/knowledgeos/`'s own further
undiscovered directories (outside this study's own 6+2 already searched) might contain more; whether
`kernel-reduction/`'s own content, if formally admitted, would survive the same raw-source verification
rigor this reconstruction has applied everywhere else; the exact identity of who authored the
check-in commit's own "research lanes" framing intent (the commit author is a human collaborator, not
this session, and this study does not speak for their intent beyond what the commit message states).

**Q12. What is the smallest next separately authorized action?** A **formal corpus-boundary decision**
(structurally analogous to MD-010/MD-011 themselves) determining whether `docs/knowledgeos/research/`
should be admitted as Model-B-adjacent evidence — a human governance act, not a further research task
this reconstruction can perform on its own authority. If and only if that decision admits it, the
smallest subsequent research action would be a narrow retest of MD-024's own Pair 1 or Pair 2 using the
now-admissible operator contracts.
