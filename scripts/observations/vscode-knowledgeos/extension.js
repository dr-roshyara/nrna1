// KnowledgeOS VS Code adapter — the IDE instrumentation of the
// ObservationTrigger port (commission 2026-08-04).
//
// Native save events (workspace.onDidSaveTextDocument) — zero polling.
// The extension detects and delegates: no metric logic, no thresholds,
// no persistence. It executes `observe.php --json` and renders the result.
// Decision buttons stay display-only until DecisionCaptureService exists.

const vscode = require('vscode');
const cp = require('child_process');
const path = require('path');

let channel;

function activate(context) {
    channel = vscode.window.createOutputChannel('KnowledgeOS');

    context.subscriptions.push(
        vscode.workspace.onDidSaveTextDocument((document) => {
            const root = vscode.workspace.workspaceFolders?.[0]?.uri.fsPath;
            if (!root || !document.fileName.endsWith('.php')) {
                return;
            }
            const rel = path.relative(root, document.fileName).replace(/\\/g, '/');
            if (!rel.startsWith('app/') || rel.endsWith('.blade.php')) {
                return; // production classes only — same scope as every other trigger
            }

            cp.execFile(
                'php',
                ['scripts/observations/observe.php', '--json', rel],
                { cwd: root, timeout: 15000 },
                (error, stdout) => {
                    if (error) {
                        channel.appendLine(`observe failed for ${rel}: ${error.message}`);
                        return;
                    }
                    let result;
                    try {
                        result = JSON.parse(stdout);
                    } catch {
                        channel.appendLine(`unparseable observe output for ${rel}`);
                        return;
                    }

                    channel.appendLine(`[${new Date().toLocaleTimeString()}] ${rel} — ${result.advisories} advisories (${result.latency_ms}ms)`);
                    for (const rec of result.recommendations) {
                        channel.appendLine(`  [${rec.rule}] ${rec.subject}`);
                        channel.appendLine(`      ${rec.text}`);
                    }

                    if (result.advisories > 0) {
                        vscode.window
                            .showWarningMessage(
                                `KnowledgeOS: ${result.advisories} advisor${result.advisories === 1 ? 'y' : 'ies'} for ${path.basename(rel)} (advisory — you decide)`,
                                'Details'
                            )
                            .then((choice) => {
                                if (choice === 'Details') {
                                    channel.show(true);
                                }
                            });
                    }
                }
            );
        })
    );
}

function deactivate() {}

module.exports = { activate, deactivate };
