#!/bin/bash
# ============================================
# VUE FILE HUNTER - Targeted Recovery for .vue files
# ============================================

cd /c/Users/nabra/OneDrive/Desktop/roshyara/xamp/nrna/nrna-eu && \

# Step 1: Create recovery directory for Vue files
echo "🔍 VUE FILE HUNTER - Searching for Vue components..." && \
VUE_RECOVERY_DIR="vue_recovery_$(date +%Y%m%d_%H%M%S)" && \
mkdir -p "$VUE_RECOVERY_DIR"/{found,inspected,restored} && \
echo "✅ Recovery directory: $VUE_RECOVERY_DIR" && \

# Step 2: Search for ANY files that might be Vue components
echo "" && echo "📋 PHASE 1: SCANNING FOR VUE COMPONENTS" && \

# Method A: Files with .vue extension
find git_recovery_20260304_014120/ -name "*.vue" -type f > "$VUE_RECOVERY_DIR/vue_files_by_extension.txt" && \
VUE_COUNT=$(wc -l < "$VUE_RECOVERY_DIR/vue_files_by_extension.txt" 2>/dev/null || echo 0) && \
echo "   ✅ Found $VUE_COUNT files with .vue extension" && \

# Method B: Files with <template> tag (Vue SFCs misnamed as .js)
echo "" && echo "📋 PHASE 2: SCANNING FOR MISNAMED VUE COMPONENTS" && \
find git_recovery_20260304_014120/ -name "*.js" -type f | while read file; do
    if head -5 "$file" 2>/dev/null | grep -q "<template"; then
        echo "$file" >> "$VUE_RECOVERY_DIR/vue_files_by_content.txt"
        echo "   ✅ Misnamed Vue: $(basename "$file")"
    fi
done && \

# Method C: Search specifically for "Security" related files
echo "" && echo "📋 PHASE 3: SEARCHING FOR SECURITY.VUE" && \

# Search by filename pattern
find git_recovery_20260304_014120/ -name "*Security*" -o -name "*security*" -type f > "$VUE_RECOVERY_DIR/security_by_name.txt" && \

# Search by content for security-related keywords
find git_recovery_20260304_014120/ -type f -exec grep -l -i "security\|password\|2fa\|mfa\|auth\|login" {} \; 2>/dev/null > "$VUE_RECOVERY_DIR/security_by_content.txt" && \

# Combine and deduplicate
cat "$VUE_RECOVERY_DIR/security_by_name.txt" "$VUE_RECOVERY_DIR/security_by_content.txt" | sort -u > "$VUE_RECOVERY_DIR/security_candidates.txt" && \
SECURITY_COUNT=$(wc -l < "$VUE_RECOVERY_DIR/security_candidates.txt") && \
echo "   ✅ Found $SECURITY_COUNT security-related files" && \

# Step 3: Inspect each candidate to see if it's our Security.vue
echo "" && echo "🔎 PHASE 4: INSPECTING SECURITY CANDIDATES" && \
while read file; do
    if [ -f "$file" ]; then
        echo "" >> "$VUE_RECOVERY_DIR/inspected/candidates_report.txt"
        echo "=== Candidate: $file ===" >> "$VUE_RECOVERY_DIR/inspected/candidates_report.txt"
        echo "Size: $(wc -c < "$file") bytes" >> "$VUE_RECOVERY_DIR/inspected/candidates_report.txt"
        echo "First 10 lines:" >> "$VUE_RECOVERY_DIR/inspected/candidates_report.txt"
        head -10 "$file" >> "$VUE_RECOVERY_DIR/inspected/candidates_report.txt"
        echo "---" >> "$VUE_RECOVERY_DIR/inspected/candidates_report.txt"
        
        # Check if it's likely our Security.vue
        if head -20 "$file" 2>/dev/null | grep -q "<template"; then
            if grep -q -i "security\|password\|2fa" "$file"; then
                echo "🎯 POTENTIAL SECURITY.VUE MATCH: $file"
                echo "   Copying for inspection..."
                cp "$file" "$VUE_RECOVERY_DIR/found/$(basename "$file")"
            fi
        fi
    fi
done < "$VUE_RECOVERY_DIR/security_candidates.txt" && \

# Step 4: If found, restore to correct location
echo "" && echo "📦 PHASE 5: RESTORATION" && \

if [ -n "$(ls -A "$VUE_RECOVERY_DIR/found/" 2>/dev/null)" ]; then
    echo "✅ Found potential matches in recovery!"
    echo ""
    echo "Potential matches:"
    ls -la "$VUE_RECOVERY_DIR/found/"
    
    echo ""
    echo "To restore as Security.vue, use:"
    echo "cp $VUE_RECOVERY_DIR/found/FILENAME resources/js/Pages/Public/Security.vue"
else
    echo "❌ No Security.vue found in recovery"
    echo ""
    echo "This confirms the file was NEVER in git recovery because:"
    echo "1. It was never committed to git"
    echo "2. It was never added to staging area"
    echo "3. It was deleted by git clean -fd before any git operation"
fi && \

# Step 5: Generate summary report
echo "" && echo "📊 RECOVERY SUMMARY" > "$VUE_RECOVERY_DIR/summary.txt"
echo "=================" >> "$VUE_RECOVERY_DIR/summary.txt"
echo "Vue files by extension: $VUE_COUNT" >> "$VUE_RECOVERY_DIR/summary.txt"
echo "Misnamed Vue files: $(wc -l < "$VUE_RECOVERY_DIR/vue_files_by_content.txt" 2>/dev/null || echo 0)" >> "$VUE_RECOVERY_DIR/summary.txt"
echo "Security candidates examined: $SECURITY_COUNT" >> "$VUE_RECOVERY_DIR/summary.txt"
echo "" >> "$VUE_RECOVERY_DIR/summary.txt"
echo "Files in recovery:" >> "$VUE_RECOVERY_DIR/summary.txt"
ls -la "$VUE_RECOVERY_DIR/found/" 2>/dev/null >> "$VUE_RECOVERY_DIR/summary.txt" || echo "No files found" >> "$VUE_RECOVERY_DIR/summary.txt"

echo "" && echo "🎉 VUE FILE HUNTER COMPLETE!"
echo "📁 Check results in: $VUE_RECOVERY_DIR/"
echo "📋 Summary: $VUE_RECOVERY_DIR/summary.txt"
echo "🔍 Inspected candidates: $VUE_RECOVERY_DIR/inspected/candidates_report.txt"