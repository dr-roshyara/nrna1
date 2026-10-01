# Gate 1 (R2) handover: instructions for the human operator (v2, reviewed)

**Purpose:** a non-Claude coder independently codes 36 source spans under the A′ schema. Its coding is later compared with Claude's coding.

**What this tests:** whether the ACT/NORM coding can be reproduced by a different model family. It tests coder independence only, not independent sources.

**Your role:** you are only the courier. The coder must receive nothing except what is listed below.

**Known limitation (cannot be fixed here):** if the repository has ever been public, a model may have seen the earlier codings in its training data. This test cannot rule that out. Record what you know in PROVENANCE.txt.

---

## Part 1: before you start (checklist)

**1. Check the files.** In a terminal:
```
sha256sum ~/F-APRIME-GATE1-BUNDLE-02.tar ~/F-APRIME-GATE1-COMMISSIONING-PROMPT-02.txt
```
The output must be exactly:
```
6bad9adc4b7acb682398490fe745af5725fa51c61a7e8bf03d7087d60e673e27  F-APRIME-GATE1-BUNDLE-02.tar
86c920da8f0a47b933d40d89ca07d06844a89aee81c0988f25d3ec1eee54473c  F-APRIME-GATE1-COMMISSIONING-PROMPT-02.txt
```
If either value differs, stop.

**2. Choose the coder.** Any of these is allowed:
- a human, or
- a non-Claude model, e.g. GPT-x in ChatGPT, Gemini, Llama or Mistral.

These are **not** allowed:
- anything built on Claude (Claude.ai, Claude Code, Cursor or Copilot in Claude mode, etc.);
- **Codex** (a standing rule).

**3. Use a FRESH, clean, offline session.** This matters most.
- Start a **brand-new conversation**.
- **Turn off memory, personalization and custom instructions.** In ChatGPT: Settings → Personalization → Memory off, and no custom instructions. Or use a temporary chat.
- **Turn off web browsing, search and every connector or plugin.** The earlier codings exist in the git repository, and the coder must not be able to look them up.
- **Do NOT use the conversation in which you have been reviewing this project.** That model has seen Claude's conclusions, so it would not be independent.
- Attach no other files and no project.

**4. Say nothing extra.** Do **not** mention:
- Claude, or that other codings exist;
- expected results, hypotheses (H-D, H-B), KnowledgeOS or "Gate 1";
- what the coding will be compared with.

Paste only the text below.

**5. One attempt only.**
- Do not rephrase, re-run or restart because an answer "looks wrong".
- If you must restart (for example, the tool crashed), record it under `deviations:` and **keep both outputs**.

*Note:* the source passages themselves contain words like `.claude` (file paths in the original texts). That is verbatim source material and is expected.

---

## Part 2: the handover (choose ONE variant)

### Variant A (preferred): the tool can run code and unpack files
For example, ChatGPT with file uploads or data analysis.

1. Upload `F-APRIME-GATE1-BUNDLE-02.tar`.
2. **Open `~/F-APRIME-GATE1-COMMISSIONING-PROMPT-02.txt`, copy ALL of it, and paste it.** Copy from the .txt file, not from this guide. For reference, it reads:

```
You will receive one file: F-APRIME-GATE1-BUNDLE-02.tar

1. Verify its SHA-256 is:
   6bad9adc4b7acb682398490fe745af5725fa51c61a7e8bf03d7087d60e673e27
   If it differs, stop and report the mismatch.
2. Unpack it. It contains one folder: F-APRIME-GATE1-BUNDLE-02.
3. Follow that folder's README.md exactly. Use only the files in that folder.
4. Return two files, unchanged in structure:
   F-APRIME-GATE1-BUNDLE-02/CODING.json, F-APRIME-GATE1-BUNDLE-02/NOTES.md
```

### Variant B: chat only, or the tool rejects `.tar` uploads (often the case with Gemini)
This variant is a **recorded deviation**: the coder does not verify the hash, and you unpack the bundle.

1. **You** have verified the hash in Part 1. Now unpack:
   ```
   mkdir -p ~/gate1-handover && tar -xf ~/F-APRIME-GATE1-BUNDLE-02.tar -C ~/gate1-handover
   ```
2. Upload the **five** files from `~/gate1-handover/F-APRIME-GATE1-BUNDLE-02/`: README.md, TASK.md, SCHEMA-r2.md, RECORDS.json, SOURCES.md.
3. Paste exactly:

```
You will receive five files: README.md, TASK.md, SCHEMA-r2.md, RECORDS.json, SOURCES.md.
Follow README.md exactly. Use only these five files.
Return two complete files: CODING.json and NOTES.md.
```

---

## Part 3: getting a complete return

- CODING.json has **36 records** (R01–R36). If the tool offers a downloadable file, prefer it over copied text.
- **If the output is truncated**, say only: `Continue exactly where you stopped.` To join the parts, delete **only** text outside the code blocks (chat wrapper text) and **record every deletion** in PROVENANCE.txt.
- **Always also save the full raw chat export** (or copy-paste the whole conversation) as `TRANSCRIPT.txt`.
- **Do not correct, reformat or "fix" anything** in the returned content, even obvious errors. Errors are data.
- **If the result is not valid JSON**, or has a different structure, **save it as-is**. Claude logs the defect and does **not** repair it.
- If the coder asks a question, answer only: `Use only the files provided; where undecided, mark UNKNOWN.`

---

## Part 4: saving the return

Use a **new** folder. Do **not** touch `~/F-APRIME-GATE1-RETURN-02/`; that is an earlier Claude-only check.

```
mkdir ~/F-APRIME-GATE1-RETURN-03
```
If this says the folder already exists, stop and tell Claude.

Save into it:
- `CODING.json`
- `NOTES.md`
- `TRANSCRIPT.txt`: the raw chat
- `PROVENANCE.txt`, written by you:
  ```
  coder: <human name or "model: <product> <model/version>">
  date_utc: <YYYY-MM-DD HH:MM>
  variant: A or B
  fresh_session: yes/no
  memory_personalization_off: yes/no
  web_access: off/on
  repo_public: yes/no/unknown   (has github.com/dr-roshyara/nrna1 ever been public?)
  extra_text_given_to_coder: none   (or quote exactly what was said)
  attempts: 1   (or the number, with reasons)
  truncation_joins: <number of "continue" joins, 0 if none>
  deletions_when_joining: <none, or describe>
  questions_from_coder: <none, or quote>
  deviations: <none> (Variant B: "coder did not verify hash; operator unpacked")
  ```

**Then tell Claude:** "Gate 1 return -03 is in `~/F-APRIME-GATE1-RETURN-03/`."

**Do not open or edit the files further.** Claude hash-seals them **before reading**, then:
1. runs the comparator that was frozen before any return existed;
2. has the comparison independently reviewed;
3. reconciles.

**Claude's own coding is NOT ground truth.** Disagreements are analysed, not "corrected".
