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
