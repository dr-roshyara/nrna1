# G-8 source route: how the three missing sources can be obtained (PBDIGIT-346, 2026-11-14)

**Status: nothing here is evidence; nothing has been imported.** The three request drafts are for the Human Arb Chief to send (an external act, not taken by the implementation agent). The research below was read through a web reader on 2026-11-14 (quotes kept short); the G-8 source package (`../2026-11-14-pbdigit346-g8/proposed-sources/`) is unchanged.

| Source | What was found | Route | Request draft |
|---|---|---|---|
| **UN M49** (regions and country assignment) | M49 page: contact "Statistical Services Branch, UN Statistics Division", statistics@un.org (subject "M49 Online query"); **no free-use statement found** in what was read. UN copyright page: material may not be reproduced without "permission in writing from the publisher"; permissions via "United Nations Publications: Rights and Permissions" (https://shop.un.org/rights-permissions). UNdata's terms are permissive ("may be copied freely, duplicated and further distributed provided that UNdata is cited as the reference"), **but UNdata does not publish the M49 region list** (the region page tried returned 404) | written permission from UNSD for commercial storage and derivation | `request-unsd-m49.md` |
| **ISO 3166-1** | every iso.org page returned HTTP 403 to automated access; terms unknown | read ISO's terms in a browser; ask ISO; or license its official data product | `request-iso-3166.md` |
| **Nepal** | lawcommission.gov.np, mofaga.gov.np, sthaniya.gov.np, rajpatra.dop.gov.np: all **refuse or reset the connection from this environment** (network level) | download from a browser that can reach them, record the URL and date, supply the files | `request-nepal-documents.md` |

**When the documents arrive:** each file goes into a dataset directory with its URL, authority, publication, retrieval date, SHA-256 and reuse terms. The importer (guide 33) verifies the checksums and prepares **draft** revisions on the isolated test database only. The extracted data is presented for acceptance; nothing is published.
