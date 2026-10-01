# Gate 1 (R2) handover -03, Claude variant (human decision, 2026-10-01)

**What this is:** a fresh, isolated Claude session codes the 36 records. By human decision this replaces the non-Claude coder.

**What it can show:** within-family reproducibility with a **different Claude model** (Sonnet 5.5; RETURN-02 was Opus 5.5).

**What it cannot show:**
- cross-family reproducibility;
- the absence of errors shared by all Claude models.

**Gate 1's cross-family requirement is waived by the human. It is not satisfied.**

## Step 1: check the bundle
```
sha256sum ~/F-APRIME-GATE1-BUNDLE-02.tar
```
The output must be exactly:
```
6bad9adc4b7acb682398490fe745af5725fa51c61a7e8bf03d7087d60e673e27
```
Otherwise, stop.

## Step 2: create an isolated folder OUTSIDE the repository and unpack
```
mkdir ~/gate1-r03-claude && cd ~/gate1-r03-claude && tar -xf ~/F-APRIME-GATE1-BUNDLE-02.tar && ls F-APRIME-GATE1-BUNDLE-02
```
You should see exactly: `README.md  RECORDS.json  SCHEMA-r2.md  SOURCES.md  TASK.md`.

## Step 3: run the coder: one command, a fresh session, Sonnet, Read and Write only
```
cd ~/gate1-r03-claude/F-APRIME-GATE1-BUNDLE-02 && claude --model claude-sonnet-5-5 -p "Read README.md in the current directory and follow it exactly. Use only the files in the current directory. Write CODING.json and NOTES.md in the current directory." --allowedTools "Read,Write" --disallowedTools "Bash,WebFetch,WebSearch,Edit,Glob,Grep,Task,Agent,NotebookEdit" --permission-mode acceptEdits --output-format stream-json --verbose > ../transcript.jsonl 2> ../err.txt; echo exit=$?
```
- Type nothing else and do not re-run.
- **One attempt only.** If it crashes, record that under `deviations` and keep every output.

## Step 4: save the return (do not edit anything)
```
mkdir ~/F-APRIME-GATE1-RETURN-03-CLAUDE && cp ~/gate1-r03-claude/F-APRIME-GATE1-BUNDLE-02/CODING.json ~/gate1-r03-claude/F-APRIME-GATE1-BUNDLE-02/NOTES.md ~/gate1-r03-claude/transcript.jsonl ~/gate1-r03-claude/err.txt ~/F-APRIME-GATE1-RETURN-03-CLAUDE/
```
Then create `~/F-APRIME-GATE1-RETURN-03-CLAUDE/PROVENANCE.txt` with these lines:
```
coder: model: Claude Sonnet 5.5 (claude-sonnet-5-5), headless claude -p
independence: WITHIN-FAMILY ONLY (human decision 2026-10-01; cross-family requirement waived)
date_utc: <YYYY-MM-DD HH:MM>
cwd: ~/gate1-r03-claude/F-APRIME-GATE1-BUNDLE-02 (outside the repository)
tools: Read, Write only
inherited_context: user-level ~/.claude/CLAUDE.md may load (generic; no project content)
attempts: 1
deviations: <none, or describe>
```

## Step 5: tell Claude
"Gate 1 return -03 (Claude) is in `~/F-APRIME-GATE1-RETURN-03-CLAUDE/`."

**Then Claude:**
1. hash-seals the return before reading it;
2. audits the transcript to confirm only the 5 bundle files were read;
3. runs the frozen comparator against both 1ax and RETURN-02;
4. has the comparison reviewed;
5. reconciles.

A malformed return is logged as a defect and never repaired.
