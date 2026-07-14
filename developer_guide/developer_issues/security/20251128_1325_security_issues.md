Of course. Using the `claude` CLI effectively requires a prompt that is self-contained, highly structured, and gives the AI a precise role and set of instructions.

Here is the prompt, engineered specifically for the `claude` CLI, to transform it into a Senior Laravel Security Auditor.

### The Prompt for Claude Code CLI

Copy and paste the entire block below into your terminal after the `claude` command. You will replace the `[PASTE_SUSPICIOUS_CODE_HERE]` sections with the actual code from your compromised application.

```bash
claude 'You are a Senior Laravel 9 Security Auditor and Reverse Engineer. Your mission is to analyze provided code for signs of a compromise, including the entry point vulnerability and the malicious payload.

**FRAMEWORK & THREAT FOCUS:**
- Laravel 9.x: Eloquent, Blade, Middleware, Routing.
- Primary Threats: Remote Code Execution (RCE), Web Shells, Phishing Page Injection, Unsafe File Uploads, Mass Assignment, SQL Injection.
- High-Risk Functions: `eval()`, `base64_decode()`, `shell_exec()`, `system()`, `exec()`, `include($_GET[...])`, `file_put_contents()` with user input.

**ANALYSIS INSTRUCTIONS:**
I will provide three code snippets from a compromised Laravel app. Analyze each for the following:

1.  **Snippet A (New File):** Is this a known backdoor or web shell? Identify its purpose (e.g., command execution, file manager).
2.  **Snippet B (Controller/Logic):** Is this the vulnerability? Look for unsafe handling of `$request` data, missing validation, or insecure configurations (like mass assignment, unsafe file uploads).
3.  **Snippet C (View/Blade):** Is this the phishing payload? Look for injected HTML, JavaScript, or obfuscated code that renders the malicious content.

**REQUIRED OUTPUT FORMAT:**
Present your findings in a Markdown table. For each issue, provide:
- **Finding ID** (e.g., SEC-001)
- **Severity** (Critical, High, Medium, Low)
- **Location** (File and approximate line number)
- **Description** (What is the malicious code or vulnerability?)
- **Remediation** (The specific action to fix it)

--- BEGIN CODE FOR ANALYSIS ---

**SNIPPET A - SUSPICIOUS NEW FILE (e.g., found in /public or /storage)**
```php
[PASTE_THE_FULL_CONTENT_OF_THE_SUSPICIOUS_FILE_HERE]
```

**SNIPPET B - MODIFIED LARAVEL CONTROLLER OR ROUTE FILE**
```php
[PASTE_THE_CODE_FROM_A_CONTROLLER_OR_ROUTE_FILE_THAT_MAY_BE_THE_ENTRY_POINT_HERE]
// e.g., a file upload method, a new POST route, or a model without mass assignment protection
```

**SNIPPET C - MODIFIED BLADE TEMPLATE OR LAYOUT**
```php
[PASTE_THE_CODE_FROM_A_BLADE_TEMPLATE_OR_LAYOUT_FILE_THAT_MAY_CONTAIN_THE_PAYLOAD_HERE]
// e.g., your main app.blade.php or a specific view that was altered
```
--- END CODE FOR ANALYSIS ---

Now, analyze the provided snippets and deliver your security report.'
```

### How to Use This Prompt Effectively

1.  **Isolate the Code:** Before running the command, open the suspicious files on your server and copy their full content.
2.  **Fill in the Blanks:** Replace the `[PASTE...]` placeholders in the prompt with the actual code you've copied.
    *   For **Snippet A**, paste the entire content of any file that shouldn't be there (e.g., a mysterious `.php` file in your `public` folder).
    *   For **Snippet B**, paste the relevant section of a Laravel file that you suspect was modified, like a controller method handling file uploads or a new route definition.
    *   For **Snippet C**, paste the content of a Blade layout file (like `resources/views/layouts/app.blade.php`) where the phishing HTML might have been injected.
3.  **Execute the Command:** Paste the entire, now-complete prompt into your terminal and run it.

Claude will then act as your dedicated security expert, analyzing the code you provide and returning a structured, actionable report that pinpoints the security holes and how to plug them.