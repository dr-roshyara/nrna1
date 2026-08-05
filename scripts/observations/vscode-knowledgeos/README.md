# KnowledgeOS VS Code Extension (minimal, v0.1)

The IDE instrumentation of the ObservationTrigger port: native save events →
`observe.php --json` → advisory popup + output channel. **Zero polling.**

## What it does

- On saving any `app/**/*.php` file (blade excluded), runs the observation
  runtime on **that file only** and shows a warning toast when advisories exist.
- "Details" opens the KnowledgeOS output channel with full recommendation text.
- **Ephemeral by design:** displays advice, records nothing — the commit
  trigger remains the evidence-stream writer.

## What it deliberately does NOT do (yet)

- No Accept/Ignore/Later persistence — those buttons arrive with
  **DecisionCaptureService** (register-staged; this extension is its second
  decision surface when that row activates).
- No JetBrains/other IDEs — adapters follow operational evidence (register).

## Run it (development mode — no build step, plain JS)

1. Open this folder in VS Code: `code scripts/observations/vscode-knowledgeos`
2. Press `F5` ("Run Extension") — a new VS Code window opens with the
   extension active.
3. In that window, open the repository and save a PHP file under `app/`.

Or package it: `npx @vscode/vsce package` → install the `.vsix` via
"Extensions: Install from VSIX".

## Architecture position

```
VS Code onDidSaveTextDocument   (native event — replaces polling for VS Code users)
        ▼
extension.js                    (adapter: detects + delegates, no logic)
        ▼
observe.php --json              (the JSON API, any adapter can call it)
        ▼
ObservationRuntime.run(ChangeSet)   (unchanged, as always)
```

`watch.php` (the 2s poller) remains the fallback adapter for non-VS-Code
contexts; both honor the ObservationTrigger port contract (guide
`developer_guide/knowledgeos/01`).
