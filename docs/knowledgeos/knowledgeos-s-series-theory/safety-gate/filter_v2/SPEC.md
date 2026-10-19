# Filter v2 r3: required behaviour (the specification the D1 reviewer checks the code against)

| Field | Value |
|---|---|
| Status | **PROPOSED.** r4. Internal pre-review: r1 FAIL (9 fail-open); r2 PASS WITH CONDITIONS; r3 FAIL from a *fresh* reviewer (7 fail-open: encodings, hyphenated ID prefixes, invisible fillers, regex edges, non-canonical seal paths, names in tracebacks, output overwrite); r4 addresses the realistic ones (see §6 for the rest). Not D1-reviewed. Never run against real data. |
| **Design caveat** | Content matching against a denylist is a **best-effort screen**. Every review round has found further encodings, so it cannot be shown complete. The Principal's preferred D2 option (the seal custodian produces the allowed list) puts the trust in separation of duties, not in this screen. |
| Production use | **Disabled in code** (`D2_CLEARED = False`, and no production adapter exists) until the rule owner answers D2 in writing **and** the D1 review passes. Importing the library and calling it with a real seal counts as production use and falls under the same rule. |
| Tested with | Fake fixtures only (`test_kos_filter_v2.py`) |
| Supersedes, after approval | `corpus-access/discovery_filter.py` and `corpus-access/corpus_filter.py`. Those stay unchanged. |

## 1. Inputs

- **Seal S** (injected):
  - `holdout_labels`, with `holdout_label_list_sha256`;
  - `holdout_files` (rows {source_id, content_sha256, path, current_path?, historical_path?}), with `holdout_file_list_sha256`.
- **Pins P** (injected): the governance-pinned expected values of both hashes.
- **ROOT:** one declared repository root.
- **Corpus items:** (path, source_id or none).
- **Family files:** found by a recursive scan.

## 2. Normalization

| Function | Definition |
|---|---|
| decoded forms | the text, plus every form reachable by repeated %xx, HTML-entity, JSON `\uXXXX` and quoted-printable decoding (≤ 6 rounds, ≤ 64 forms). **Every form is matched**, so decoding can only add matches |
| fold(s) | for each form: NFKD; drop format characters (Cf), combining marks (Mn) and invisible fillers (U+034F, U+115F, U+1160, U+17B4–5, U+180B–F, U+2800, U+3164, U+FFA0); NFKC; drop them again; map every Unicode decimal digit to ASCII; casefold |
| compact1(s) | seal-side: the folded single string, letters and digits only |
| compact(s) | text-side: all folded forms, letters and digits only |
| norm(path) | os.path.normpath, then remove only a literal leading "./" |
| Source IDs | parsed from compact(id) as *letters + digits*; leading zeros ignored |

## 3. Integrity: fail closed (exception, no output list)

| ID | Rule |
|---|---|
| I-1 | label_list_hash = sha256(json.dumps(sorted(labels))). It must equal **both** the seal's own field and the pin. |
| I-2 | file_list_hash = sha256(json.dumps(sorted([source_id, content_sha256, path, current_path, historical_path] per row, missing values, including a missing source_id or sha, as ""; such rows are rejected by I-6 anyway))). It must equal the seal's own field **and** the pin. **The real convention must be confirmed by the D1 reviewer against the resolver's documented interface.** |
| I-3 | Non-empty labels **and** non-empty rows. |
| I-4 | Every scanned file (corpus or family), resolved through symlinks, lies inside ROOT. |
| I-5 | Every label has compact length ≥ 3. A shorter label means fail closed. **The threshold is the D1 reviewer's decision.** |
| I-6 | Every row has a parseable source ID, a 64-hex sha (any case, compared in lower case) and at least one path. |
| I-7 | Any symlinked directory or unreadable directory in the family tree, and any scanned file (corpus or family) that is a symlink, is not a regular file, or exceeds 50 MB, means fail closed. Files are opened with O_NOFOLLOW and O_NONBLOCK and checked with fstat. |
| I-8 | Every seal path is relative, '/'-separated, in NFC and free of '..'. Otherwise fail closed. Candidate paths are compared in NFC. |
| I-9 | Every exception raised by a scan is FailClosed, with a message that carries **no name**, and the original exception chain is suppressed (`from None`). |
| I-10 | The output writer refuses relative paths, existing files and symlinks (O_CREAT, O_EXCL, O_NOFOLLOW, mode 0600), and any field containing a tab or a newline. |

## 4. Withholding rules (the over-withholding direction is accepted)

**A corpus file is withheld iff any of the following holds:**
- its sha matches a sealed sha;
- its norm(path) matches any sealed path field;
- its source ID matches a sealed ID;
- its **path** contains a label (compact substring) or a sealed ID;
- its **text** contains a label or a sealed ID;
- its bytes are not strict UTF-8, or contain NUL.

**A family file is withheld iff any of the following holds:**
- its sha matches a sealed sha;
- its path relative to ROOT matches a sealed path;
- its **relative path (directories + filename) or text** contains a label (compact substring) or a sealed ID;
- it is undecodable (as above).

**Sealed-ID match:** on fold(text):
- the ID's prefix letters, with up to 3 characters outside [0-9a-z] between consecutive letters (so `F-LOG-0308` matches `flog308`);
- then up to 8 characters outside [0-9a-z];
- then any leading zeros;
- then the ID's number, **not followed by a digit**.

No look-behind is applied, so `ref2S9001` matches (over-withholding). Non-ASCII letters count as separators.

## 5. Outputs

- Allowed items only, plus counts.
- The number of subdirectories scanned.
- Output paths must be **absolute**: fail closed otherwise.
- ROOT and its git HEAD, from the production adapter (not written).
- **No withheld name, ID, label, hash or text is ever printed or written.** Rule 4 guarantees that no allowed path names a label or a sealed ID.

## 6. Residual risks (not closed by r4; for the Principal and the D1 reviewer)

**New residual risks found in the r3 review:**

| ID | Risk | Status |
|---|---|---|
| R-6 | Letters between the prefix and the number (`S No. 9001`); separators longer than 8 characters; digits split by separators (`S 9 001`); base64 or other encodings | Open; tests document the 8/9-character boundary |
| R-7 | TOCTOU on **parent** directories between the realpath check and open (O_NOFOLLOW protects only the last component) | Low; the downstream reader must re-check the listed sha before reading |
| R-8 | 2-character CJK labels fail I-5 (fail closed) | The D1 reviewer decides a per-script threshold |

**Residual risks carried forward:**

| ID | Risk | Status |
|---|---|---|
| R-1 | **Edited-and-moved copies (F-10)** that quote neither a label nor an ID | Open. Mitigation (near-duplicate check against sealed content) needs a D2 answer. |
| R-2 | **Homoglyphs** (e.g. Cyrillic а for Latin a) | Open. NFKC does not map them; a confusables map would be needed. |
| R-3 | **Real ID format** (other prefixes, separators of more than 8 characters, other encodings such as base64) | To be confirmed. |
| R-4 | **Case-variant paths** on case-insensitive filesystems | Low risk on the Linux host. |
| R-5 | **Over-withholding** from short or common labels | Accepted direction; threshold per I-5. |

## 7. Count-only source-ID check (1752 report §4.2)

`source_id_check(...)` returns exactly three values:
- the number of allowed families quoting a sealed ID (name or text);
- yes/no for the opened families;
- yes/no for the opened documents.

In production it runs only if D2 Q4 = (a), or (c) with another operator.
