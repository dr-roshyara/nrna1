# PowerShell script to replace ElectionLayout and NrnaLayout with PublicDigitLayout
# Usage: Run this script from the project root directory

$files = @(
    "resources\js\Pages\Vote\CreateVotingPage.vue",
    "resources\js\Pages\Vote\CreateCode.vue",
    "resources\js\Pages\User\Index.vue",
    "resources\js\Pages\Election\Viewboard.vue",
    "resources\js\Pages\Election\Management.vue",
    "resources\js\Pages\Code\CreateCode.vue",
    "resources\js\Pages\Code\Agreement.vue",
    "resources\js\Pages\Result\Index.vue",
    "resources\js\Pages\Vote\VoteShowVerify.vue",
    "resources\js\Pages\Vote\VoteDenied.vue",
    "resources\js\Pages\Vote\VoteShow.vue",
    "resources\js\Pages\Vote\BallotAccessDenied.vue",
    "resources\js\Pages\Code\VoteDenied.vue",
    "resources\js\Pages\Auth\ForgotPassword.vue",
    "resources\js\Shared\CreateCode.vue",
    "resources\js\Pages\Vote\CreateNew.vue",
    "resources\js\Pages\Vote\Create.vue",
    "resources\js\Pages\Election\ElectionCommittee.vue",
    "resources\js\Pages\Dashboard\MainDashboard.vue",
    "resources\js\Pages\Committee\Calendar.vue",
    "resources\js\Pages\Committee\Activities.vue",
    "resources\js\Components\Committee\Calendar.vue",
    "resources\js\Pages\Student\Create.vue",
    "resources\js\Pages\Profile\Show.vue",
    "resources\js\Pages\Message\Index.vue",
    "resources\js\Pages\Post\IndexPost.vue",
    "resources\js\Pages\Vote\VoteVerify.vue",
    "resources\js\Pages\Dashboard.vue",
    "resources\js\Pages\Vote\VoteShow copy.vue",
    "resources\js\Pages\Candidacy\Index.vue",
    "resources\js\Pages\Auth\Register_backup.vue",
    "resources\js\Pages\DeligateVote\VerifyDeligateVote.vue",
    "resources\js\Pages\DeligateVote\ResultDeligateVote.vue",
    "resources\js\Pages\DeligateVote\ShowDeligateVote.vue",
    "resources\js\Pages\DeligateVote\CreateDeligateVote.vue",
    "resources\js\Pages\DeligateVote\CreateDeligateCode.vue",
    "resources\js\Pages\DeligateCandidacy\IndexDeligateCandidacy.vue",
    "resources\js\Pages\Vote\CreateVote.vue",
    "resources\js\Pages\Candidacy\IndexCandidacy.vue",
    "resources\js\Pages\Candidacy\CreateCandidacy.vue",
    "resources\js\Pages\Auth\ResetPassword.vue",
    "resources\js\Pages\Notice\IndexNotice.vue"
)

$count = 0
$errors = @()

foreach ($file in $files) {
    $fullPath = Join-Path $PSScriptRoot $file

    if (Test-Path $fullPath) {
        try {
            $content = Get-Content $fullPath -Raw
            $modified = $false

            # Replace import statements
            if ($content -match 'import\s+(\w+)\s+from\s+"@/Layouts/ElectionLayout"') {
                $varName = $matches[1]
                $content = $content -replace 'import\s+\w+\s+from\s+"@/Layouts/ElectionLayout";?', 'import PublicDigitLayout from "@/Layouts/PublicDigitLayout.vue";'
                $modified = $true

                # Replace in components section
                $content = $content -replace "\b$varName\b(?=\s*[,}])", 'PublicDigitLayout'
            }

            if ($content -match 'import\s+(\w+)\s+from\s+"@/Layouts/NrnaLayout"') {
                $varName = $matches[1]
                $content = $content -replace 'import\s+\w+\s+from\s+"@/Layouts/NrnaLayout";?', 'import PublicDigitLayout from "@/Layouts/PublicDigitLayout.vue";'
                $modified = $true

                # Replace in components section
                $content = $content -replace "\b$varName\b(?=\s*[,}])", 'PublicDigitLayout'
            }

            # Replace template tags
            $content = $content -replace '<election-layout>', '<public-digit-layout>'
            $content = $content -replace '</election-layout>', '</public-digit-layout>'
            $content = $content -replace '<nrna-layout>', '<public-digit-layout>'
            $content = $content -replace '</nrna-layout>', '</public-digit-layout>'

            if ($modified -or ($content -ne (Get-Content $fullPath -Raw))) {
                Set-Content -Path $fullPath -Value $content -NoNewline
                $count++
                Write-Host "✓ Updated: $file" -ForegroundColor Green
            } else {
                Write-Host "- Skipped: $file (no changes needed)" -ForegroundColor Yellow
            }
        }
        catch {
            $errors += "Error processing $file : $_"
            Write-Host "✗ Error: $file - $_" -ForegroundColor Red
        }
    }
    else {
        Write-Host "✗ Not found: $file" -ForegroundColor Red
        $errors += "File not found: $file"
    }
}

Write-Host "`n========================================" -ForegroundColor Cyan
Write-Host "Summary:" -ForegroundColor Cyan
Write-Host "  Files processed: $count" -ForegroundColor Green
Write-Host "  Errors: $($errors.Count)" -ForegroundColor $(if ($errors.Count -gt 0) { 'Red' } else { 'Green' })
Write-Host "========================================`n" -ForegroundColor Cyan

if ($errors.Count -gt 0) {
    Write-Host "Errors encountered:" -ForegroundColor Red
    $errors | ForEach-Object { Write-Host "  - $_" -ForegroundColor Red }
}
