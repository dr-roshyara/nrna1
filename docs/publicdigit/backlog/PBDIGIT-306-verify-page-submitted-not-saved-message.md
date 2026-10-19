# PBDIGIT-306: The vote verify page says "successfully submitted" in green although the vote is not saved yet

**Type:** UX / voter clarity · **Epic:** none · **Created:** 2026-10-19 · **Raised by:** the PO, on `/v/{vslug}/vote/verify`. **Index row:** added 2026-10-19.

| | |
|---|---|
| **Status** | `IMPLEMENTED` · requested by the PO 2026-10-19; awaiting PO acceptance and a frontend release |
| **Classification** | **observed:** the status box shows a green check mark with "Vote Successfully Submitted / Your vote has been recorded…", while the same page warns "Your vote is NOT saved yet" |
| **Scope** | real and demo verify pages; en, de, np texts |

## Finding
- **The vote is only saved after the voter enters the 6-digit code and clicks "Verify & Save My Vote".** Until then, the green box with a check mark tells the voter the opposite.
- The page's own critical warning ("NOT saved yet") contradicts it.
- A voter who trusts the green box may leave without saving, and the vote is lost.

## Change
- **Texts** in `resources/js/locales/pages/VoteVerify/{en,de,np}.json`: `status_complete` and `status_message` now say *submitted, not saved yet*, and name the step and button that saves the vote. Each language quotes its own button label.
- **Box** in `resources/js/Pages/Vote/Verify.vue` and `resources/js/Pages/Vote/DemoVote/Verify.vue`:
  - green gradient → design-system `warning-*` tokens (pending state, per `.claude/UI_GUIDELINES.md`);
  - check-mark icon → clock icon;
  - `role="status"` added, and the icon marked `aria-hidden`.
- **Checks:** the JSON files are valid; `npm run design-check` total unchanged (7); the frontend build succeeds.

## Open
- **Nepali wording:** a native-speaker review is recommended.
- **Release:** production serves the committed `public/build` (last rebuilt 2026-09-16, with 11 frontend commits since). The change reaches voters only with a deliberate frontend release (`npm run build` + commit + deploy), which ships all pending frontend changes together.
