# G-8 proposed sources: for the Human Arb Chief's review (PBDIGIT-346, 2026-11-14)

**Status of every source here: PROPOSED.** Nothing below is accepted evidence, and nothing has been imported into any database except the synthetic test sample. Instruction of 2026-11-14 (options A + C): present the sources, flag gaps, licensing and conflicts; do not resolve them silently.

## 1. Unicode CLDR 47: display names (D-87, D-231, D-232 (2))

| Field | Value |
|---|---|
| Authority | Unicode Consortium, CLDR |
| Release | **CLDR 47**, as shipped in **ICU 77.1** (`libicu-77.1-3.fc44`; ICU root bundle `Version` = 47) |
| How obtained | local ICU data on this machine, read per locale with **no ICU fallback** (`ResourceBundle::create(<locale>, 'ICUDATA-region', false)`); extractor `extract-cldr.php` |
| Retrieved | 2026-11-14 |
| Extract | `cldr47-territory-names.csv`, SHA-256 `dc4ed95febbe1fe083268a7c5c7fc229d26dd1d3161fdf036f3c2fe42b8bb713`: 254 codes (World 001, the five M49 regions 002/009/019/142/150, and the 248 ISO alpha-2 codes listed by UNSD) × de/en/ne; standard 254 each; short: en 9, de 5, ne 5; **no code missing in any of the three locales** |
| Reuse terms | **Unicode License V3** (`UNICODE-LICENSE-V3.txt`, copied from the ICU package): free use and redistribution, including commercial, with the copyright and permission notice kept |
| Observations | CLDR's English name for 001 is lowercase "world" (CLDR data as published) |
| Status | **proposed; licence permits storage** |

## 2. UN M49: the five regions and country-to-region assignment (D-85, D-86 (5), D-226 Q-G-1)

| Field | Value |
|---|---|
| Authority | United Nations Statistics Division |
| Source | https://unstats.un.org/unsd/methodology/m49/overview/ (the online version of "Standard Country or Area Codes for Statistical Use", Series M, No. 49; print last issued 1999) |
| Retrieved | 2026-11-14T07:41:25Z (HTTP 200; response headers in `un-m49-overview.response-headers.txt`) |
| File | the page (HTML), SHA-256 `b9048114f6e7f2abda83bf03d4263c9d7cd1bd7230e3d0461025ee7839a7a1fb`; its English table extracted (`extract-m49.php`) to CSV, SHA-256 `f35d5c345e0b9a66fd01b5150e25b24c6557192a5fd2f47996e5603b7c627dbf` |
| Content | 248 countries or areas, all with ISO alpha-2 and alpha-3 as UNSD lists them; Africa 60, Americas 57, Asia 50, Europe 51, Oceania 29; **Antarctica (AQ) has no region** (stays unassigned under D-86 (5)) |
| Reuse terms | **UN website terms of use** (https://www.un.org/en/about-us/terms-of-use): permission to download and copy "for the User's personal, non-commercial use, without any right to resell or redistribute them or to compile or create derivative works therefrom". The separate UN copyright page was **not read**. The M49 page states no specific licence |
| Status | **NOT stored in the repository; NOT used.** **Decision needed:** may PublicDigit (a platform for paying organisations) store M49 and derive its region data from it? |
| Note | The M49 table's ISO columns are UNSD's copy of ISO codes: not competent evidence for ISO 3166-1 itself |

## 3. ISO 3166-1: countries and areas, their names and codes (D-86 (3))

| Field | Value |
|---|---|
| Authority | ISO (3166 Maintenance Agency) |
| Attempted | https://www.iso.org/iso-3166-country-codes.html, https://www.iso.org/obp/ui/#search, and ISO's copyright and terms pages |
| Result | **HTTP 403 for every ISO page** (automated access refused); **ISO's reuse terms could not be read** |
| Status | **STOPPED** (your instruction: stop and present if ISO's terms are not established). **Decision needed:** the source of the ISO 3166-1 list (see the report) |

## 4. Nepal: provinces, districts, local levels (D-226 Q-G-1, D-232 (3))

| Field | Value |
|---|---|
| Competent authorities found | the Constitution of Nepal 2015, Schedule 4 (provinces and their districts), official text at the Nepal Law Commission (lawcommission.gov.np), official English translation by the Ministry of Law, Justice and Parliamentary Affairs; the **753 local levels** by the Ministry of Federal Affairs and General Administration (MoFAGA; formerly MoFALD) |
| Result | **unreachable from this machine:** mofaga.gov.np and sthaniya.gov.np time out; lawcommission.gov.np resets the connection |
| Not used | Wikipedia, Scribd, mirrors, Open Data Nepal: secondary, not competent evidence |
| Also to settle | province names were adopted later by each provincial assembly (separate instruments); the local-level count may be revised (reports of a review; the current count must be confirmed from the authority) |
| Status | **GAP. Decision needed:** documents supplied by you (option B), or another access path |
