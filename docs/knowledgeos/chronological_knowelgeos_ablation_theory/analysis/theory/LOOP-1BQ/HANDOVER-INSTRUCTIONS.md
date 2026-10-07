# Blind slice cross-check: handover instructions for the human operator

**What this is.** An independent programmer, a non-Claude model or a human, enumerates a small finite universe from specifications and reports counts and least counterexamples. They never see our results. Their output is compared with a sealed Claude-side run.

## 1. Check the bundle
```
sha256sum ~/F-XCHECK-SLICE-01.tar
```
It must print `fd83979b2b5f02380c2fa25f913d9c65a88e9b45d2f6baae3b372e1be4019a1b`.

## 2. Choose the verifier
- **Allowed:** a non-Claude model that can **write and run code**, e.g. ChatGPT with code execution or Gemini with code execution; or a human programmer.
- **Not allowed:** anything built on Claude, or Codex.
- **Prefer a different session from the bundle-02 run.** Using the same product is fine.

## 3. Use a fresh session
- New chat.
- Memory, personalization and custom instructions **OFF**.
- Web search and connectors **OFF**.
- Code execution **ON**.

## 4. Hand over
1. Upload `F-XCHECK-SLICE-01.tar`. If the tool cannot take a .tar, unpack it and upload the 5 files.
2. Paste exactly:
   ```
   Unpack the attached bundle if needed. Follow README.md exactly. Use only the files in the bundle. Return SLICE-RESULTS.json and NOTES.md, plus the program you wrote.
   ```
3. Say nothing else. If asked a question, reply only: `Use only the files provided; where ambiguous, choose the most literal reading and record it in NOTES.md.`
4. **Heavy run.** The universe is about 3 million histories.
   - If the sandbox times out, you may say once: `Optimise and retry in this session.`
   - **Fallback, if it still cannot finish:** download the program **unmodified** and run it locally with `python3 <program>` in a fresh empty folder containing the 5 bundle files. Record this under `deviations`.
   - **Never edit the program.**

## 5. Save the return
Do not edit anything. Save into a new folder `~/F-XCHECK-SLICE-RETURN-01/`:
- `SLICE-RESULTS.json`
- `NOTES.md`
- the program file(s)
- `TRANSCRIPT.txt` (the whole chat)
- `PROVENANCE.txt`:
  ```
  verifier: <model/product/version or human>
  model_version_shown: <exact name/version shown, or unknown>
  date_utc:
  bundle_sha256_checked: fd83979b2b5f02380c2fa25f913d9c65a88e9b45d2f6baae3b372e1be4019a1b (yes/no)
  upload_form: tar / five unpacked files
  execution_environment: <sandbox, or local python3 version if the fallback was used>
  files_returned: <list; note anything missing or truncated>
  fresh_session: yes/no
  memory_off: yes/no
  web_off: yes/no
  code_execution: yes/no
  retries: <n and reasons>
  deviations:
  ```

Then tell Claude: "Slice return is in `~/F-XCHECK-SLICE-RETURN-01/`".
