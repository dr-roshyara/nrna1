# Non-Claude cross-check: handover instructions for the human operator

**What this is:** an independent programmer (a non-Claude model, or a human) reimplements two finite-state verifications from specifications. They do not see our results. Their output is then compared with Claude's.

## 1. Check the bundle
```
sha256sum ~/F-XCHECK-BUNDLE-02.tar
```
It must print `4dff13519dc12db6d17d3a24bf6afafbdf7b80efe506968b62123aa92763dd08`.

## 2. Choose the verifier
- **Allowed:** a non-Claude model that can **write and run code**, for example ChatGPT with code execution ("data analysis") or Gemini with code execution; or a human programmer.
- **Not allowed:** anything built on Claude, or Codex.
- **Run time:** the runs are moderately heavy (about 1–3 minutes in local Python). A chat sandbox may time out. If it does, ask the model to optimise and retry *in the same session*, and record it.

## 3. Use a fresh session
- Start a new chat.
- Turn **memory, personalization and custom instructions OFF**.
- Turn **web search and connectors OFF**.
- **Keep code execution ON.**
- **Do not use any conversation that has seen this project.**

## 4. Hand over
1. Upload `F-XCHECK-BUNDLE-02.tar`. If the tool can't take a .tar, unpack it and upload the 5 files.
2. Paste exactly:
   ```
   Unpack the attached bundle if needed. Follow README.md exactly. Use only the files in the bundle. Return R5-RESULTS.json, V1B-RESULTS.json and NOTES.md, plus the program you wrote.
   ```
3. Say nothing else. If asked a question, reply only: `Use only the files provided; where ambiguous, choose the most literal reading and record it in NOTES.md.`
4. **One attempt.** Retries are allowed only to fix crashes or timeouts within the same session. Record each one.

## 5. Save the return
Do not edit anything. Save into a new folder `~/F-XCHECK-RETURN-01/`:
- `R5-RESULTS.json`
- `V1B-RESULTS.json`
- `NOTES.md`
- the program file(s)
- `TRANSCRIPT.txt` (the whole chat)
- `PROVENANCE.txt`:
  ```
  verifier: <model/product/version or human>
  model_version_shown: <exact model name/version the tool displays, or unknown>
  date_utc:
  bundle_sha256_checked: 4dff13519dc12db6d17d3a24bf6afafbdf7b80efe506968b62123aa92763dd08 (yes/no)
  upload_form: tar / five unpacked files
  execution_environment: <e.g. ChatGPT code sandbox; local Python 3.x>
  files_returned: <list; note anything missing or truncated>
  fresh_session: yes/no
  memory_off: yes/no
  web_off: yes/no
  code_execution: yes/no
  retries: <n and reasons>
  deviations:
  ```

Then tell Claude: "Cross-check return is in `~/F-XCHECK-RETURN-01/`". Claude hash-seals the return before reading it, compares it with the sealed results under the pre-registered rule, and has the comparison reviewed.
