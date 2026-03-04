#!/bin/bash
# Safe restoration script with rollback capability

echo "🔥 PROJECT PHOENIX - SAFE RESTORATION"
echo "====================================="

# Create timestamped backup
BACKUP_DIR="phoenix_backup_$(date +%Y%m%d_%H%M%S)"
mkdir -p "$BACKUP_DIR"

# Backup current state
echo "📦 Backing up current files to $BACKUP_DIR/..."
find app resources public -type f -name "*.php" -o -name "*.vue" -o -name "*.js" -o -name "*.json" 2>/dev/null | while read file; do
    mkdir -p "$BACKUP_DIR/$(dirname "$file")"
    cp "$file" "$BACKUP_DIR/$file"
done

# Restore reconstructed files
echo "🔄 Restoring reconstructed files..."
cp -r reconstruction/final/* ./ 2>/dev/null

echo ""
echo "✅ RESTORATION COMPLETE"
echo "📋 Rollback available: cp -r $BACKUP_DIR/* ./"
