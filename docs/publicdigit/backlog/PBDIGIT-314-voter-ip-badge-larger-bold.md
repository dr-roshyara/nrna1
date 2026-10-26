# PBDIGIT-314: Show the voter's IP larger and bold on the election page

**Type:** UI improvement · **Epic:** none · **Created:** 2026-10-26 · **Raised by:** the PO, on `https://publicdigit.com/elections/onf-east-asia-real-election-a3d254a0`.

| | |
|---|---|
| **Status** | `IMPLEMENTED` · requested by the PO 2026-10-26; awaiting PO acceptance and frontend release |
| **Request** | "Voting from 27.34.71.107": make the IP larger and bold |
| **Scope** | the IP badge in `resources/js/Pages/Election/Show.vue` (hero) |

## Change
- The badge text was one translated string, `ip_badge_text` ("Voting from {ip}"), so the IP could not be styled on its own.
- It is now rendered with vue-i18n's `<i18n-t>` and an `#ip` slot, so only the IP is wrapped in `<strong class="esp-ip-badge__ip">`. The locale files are unchanged, and word order still follows each language (in Nepali the IP comes first).
- New scoped style `.esp-ip-badge__ip`: `font-size: 1rem` (the text around it is 0.7rem), `font-weight: 700`, colour `var(--gold-lt)`.

## Evidence
- `npm run design-check`: 91 before and after (threshold 150).
- `npm run prod` builds; the built chunk uses the `ip_badge_text` keypath, and the built CSS contains the `.esp-ip-badge__ip` rule.
- Rendered with the built CSS at 390 px (headless Chrome), in English and Nepali word order: the IP is larger, bold and gold, and the badge fits on one line.
- Not rendered in the running app (needs a logged-in voter).

## Developer guide
Folded here (small UI change).

## Related
- **PBDIGIT-313**: the same page's header was restored earlier the same day.
