#!/bin/bash
# Simplified Git Blob Recovery Script for Windows/MINGW64
# recover_all_files_simple.sh

# Color codes for output (disable for Windows if they cause issues)
RED=''
GREEN=''
YELLOW=''
BLUE=''
PURPLE=''
CYAN=''
NC=''

# Configuration
RECOVERY_DIR="git_recovery_$(date +%Y%m%d_%H%M%S)"
LOG_FILE="$RECOVERY_DIR/recovery.log"
MAX_FILE_SIZE=104857600  # 100MB max

# Create recovery directory
echo "Creating recovery directory: $RECOVERY_DIR"
mkdir -p "$RECOVERY_DIR"
mkdir -p "$RECOVERY_DIR"/{images,documents,code,archives,configs,logs,unknown,reports}

# Log function
log_message() {
    local message="$1"
    local timestamp=$(date '+%Y-%m-%d %H:%M:%S')
    echo "[$timestamp] $message" | tee -a "$LOG_FILE"
}

# Check if we're in a git repo
if ! git rev-parse --git-dir > /dev/null 2>&1; then
    log_message "ERROR: Not in a git repository!"
    exit 1
fi
log_message "SUCCESS: Git repository found: $(git rev-parse --show-toplevel)"

# Get unreachable blobs
log_message "INFO: Fetching unreachable blobs..."
git fsck --unreachable 2>/dev/null | grep "blob" | awk '{print $3}' > "$RECOVERY_DIR/all_blobs.txt"

# Check if we found any
if [ ! -s "$RECOVERY_DIR/all_blobs.txt" ]; then
    log_message "WARNING: No unreachable blobs found. Checking lost-found..."
    git fsck --lost-found > /dev/null 2>&1
    if [ -d ".git/lost-found/other" ]; then
        ls .git/lost-found/other/ > "$RECOVERY_DIR/all_blobs.txt"
    else
        log_message "ERROR: No blobs found to recover!"
        exit 1
    fi
fi

total=$(wc -l < "$RECOVERY_DIR/all_blobs.txt" | tr -d ' ')
log_message "SUCCESS: Found $total blobs to process"

# Process each blob
current=0
recovered=0
failed=0

while read -r hash; do
    # Skip empty lines
    if [ -z "$hash" ]; then
        continue
    fi
    
    current=$((current + 1))
    
    # Progress indicator
    if [ $((current % 10)) -eq 0 ] || [ $current -eq 1 ] || [ $current -eq $total ]; then
        log_message "INFO: Progress: $current/$total (Recovered: $recovered, Failed: $failed)"
    fi
    
    # Check if blob exists
    if ! git cat-file -e "$hash" 2>/dev/null; then
        log_message "WARNING: Blob $hash does not exist"
        failed=$((failed + 1))
        continue
    fi
    
    # Get size
    size=$(git cat-file -s "$hash" 2>/dev/null)
    if [ -z "$size" ] || [ "$size" -gt "$MAX_FILE_SIZE" ]; then
        log_message "WARNING: Skipping blob $hash (size: $size)"
        failed=$((failed + 1))
        continue
    fi
    
    # Create temp file
    temp_file="/tmp/$hash.tmp"
    if ! git cat-file -p "$hash" > "$temp_file" 2>/dev/null; then
        log_message "ERROR: Failed to extract blob $hash"
        failed=$((failed + 1))
        continue
    fi
    
    # Check if file has content
    if [ ! -s "$temp_file" ]; then
        log_message "WARNING: Blob $hash is empty"
        rm -f "$temp_file"
        failed=$((failed + 1))
        continue
    fi
    
    # Detect file type using file command
    file_info=$(file -b "$temp_file" 2>/dev/null)
    mime_type=$(file -b --mime-type "$temp_file" 2>/dev/null)
    
    # Determine category and extension
    category="unknown"
    extension="bin"
    
    case "$mime_type" in
        image/jpeg|image/jpg) category="images"; extension="jpg" ;;
        image/png) category="images"; extension="png" ;;
        image/gif) category="images"; extension="gif" ;;
        image/svg+xml) category="images"; extension="svg" ;;
        image/webp) category="images"; extension="webp" ;;
        image/bmp) category="images"; extension="bmp" ;;
        image/x-icon) category="images"; extension="ico" ;;
        
        text/plain) 
            # Check for specific text types
            if grep -q "<?php" "$temp_file" 2>/dev/null; then
                category="code"; extension="php"
            elif grep -q "function.*(" "$temp_file" 2>/dev/null; then
                category="code"; extension="js"
            elif grep -q "^#!" "$temp_file" 2>/dev/null; then
                category="code"; extension="sh"
            elif grep -q "<html" "$temp_file" 2>/dev/null; then
                category="code"; extension="html"
            elif grep -q "^\s*namespace" "$temp_file" 2>/dev/null; then
                category="code"; extension="php"
            elif head -1 "$temp_file" | grep -q "^#" ; then
                category="documents"; extension="md"
            else
                category="documents"; extension="txt"
            fi
            ;;
            
        text/html) category="code"; extension="html" ;;
        text/css) category="code"; extension="css" ;;
        text/x-php) category="code"; extension="php" ;;
        text/x-python) category="code"; extension="py" ;;
        text/x-javascript) category="code"; extension="js" ;;
        text/x-json) category="configs"; extension="json" ;;
        text/xml) category="configs"; extension="xml" ;;
        application/json) category="configs"; extension="json" ;;
        application/xml) category="configs"; extension="xml" ;;
        application/pdf) category="documents"; extension="pdf" ;;
        application/zip) category="archives"; extension="zip" ;;
        application/gzip) category="archives"; extension="gz" ;;
        application/x-sqlite3) category="databases"; extension="sqlite" ;;
        
        *)
            # Check if it's text
            if file "$temp_file" | grep -q "text"; then
                category="documents"; extension="txt"
            fi
            ;;
    esac
    
    # For PHP files, try to get class name
    if [ "$extension" = "php" ]; then
        class_name=$(grep -E "^class\s+\w+" "$temp_file" 2>/dev/null | head -1 | awk '{print $2}')
        if [ ! -z "$class_name" ]; then
            filename="${class_name}.php"
        else
            filename="${hash}.php"
        fi
    else
        filename="${hash}.${extension}"
    fi
    
    # Copy file to appropriate directory
    cp "$temp_file" "$RECOVERY_DIR/$category/$filename"
    
    # Log the recovery
    echo "$hash:$category:$extension:$size" >> "$RECOVERY_DIR/recovered_files.txt"
    
    recovered=$((recovered + 1))
    rm -f "$temp_file"
    
done < "$RECOVERY_DIR/all_blobs.txt"

# Generate summary
log_message "SUCCESS: Recovery complete!"
log_message "INFO: Total: $total, Recovered: $recovered, Failed: $failed"

# Create summary report
{
    echo "========================================="
    echo "GIT BLOB RECOVERY REPORT"
    echo "========================================="
    echo "Date: $(date)"
    echo "Repository: $(git rev-parse --show-toplevel)"
    echo ""
    echo "SUMMARY:"
    echo "  Total blobs found: $total"
    echo "  Successfully recovered: $recovered"
    echo "  Failed/Skipped: $failed"
    echo ""
    echo "FILES BY CATEGORY:"
    
    for category in images documents code archives configs logs unknown; do
        if [ -d "$RECOVERY_DIR/$category" ]; then
            count=$(find "$RECOVERY_DIR/$category" -type f | wc -l)
            if [ "$count" -gt 0 ]; then
                size=$(du -sh "$RECOVERY_DIR/$category" 2>/dev/null | cut -f1)
                echo "  $category: $count files ($size)"
            fi
        fi
    done
    
    echo ""
    echo "FILE TYPES DETECTED:"
    if [ -f "$RECOVERY_DIR/recovered_files.txt" ]; then
        cut -d: -f2,3 "$RECOVERY_DIR/recovered_files.txt" | sort | uniq -c | sort -rn
    fi
    
} > "$RECOVERY_DIR/summary.txt"

# Create simple restore script
cat > "$RECOVERY_DIR/restore.sh" << 'EOF'
#!/bin/bash
echo "Restore recovered files"
echo "======================="
echo ""
echo "Categories available:"
ls -d */ 2>/dev/null | grep -v "reports\|restore.sh"
echo ""
read -p "Enter category to restore (or 'all'): " category
if [ "$category" = "all" ]; then
    mkdir -p restored
    cp -r * restored/ 2>/dev/null
    echo "All files copied to ./restored/"
else
    if [ -d "$category" ]; then
        mkdir -p restored/$category
        cp -r $category/* restored/$category/ 2>/dev/null
        echo "Files copied to ./restored/$category/"
    else
        echo "Category not found"
    fi
fi
EOF

chmod +x "$RECOVERY_DIR/restore.sh"

echo ""
echo "========================================="
echo "RECOVERY COMPLETE!"
echo "========================================="
echo ""
echo "Recovery directory: $RECOVERY_DIR"
echo "Files recovered: $recovered"
echo ""
echo "Check the summary:"
echo "  cat $RECOVERY_DIR/summary.txt"
echo ""
echo "To restore files:"
echo "  cd $RECOVERY_DIR && ./restore.sh"
echo ""

# Show quick summary
ls -la "$RECOVERY_DIR" | grep -v total
