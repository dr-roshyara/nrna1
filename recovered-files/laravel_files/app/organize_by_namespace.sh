#!/bin/bash

echo "Organizing files by namespace..."

# Process each file
for file in php_* blob-*; do
    if [ ! -f "$file" ]; then continue; fi
    
    # Extract namespace
    namespace=$(grep -E "^namespace\s+" "$file" | head -1 | sed -E 's/^namespace\s+([^;]+);/\1/' | tr '\\' '/')
    
    if [ ! -z "$namespace" ]; then
        # Create directory structure based on namespace
        target_dir="../organized/$namespace"
        mkdir -p "$target_dir"
        
        # Try to determine filename from class name
        class_name=$(grep -E "^(class|interface|trait|enum)\s+\w+" "$file" | head -1 | awk '{print $2}')
        
        if [ ! -z "$class_name" ]; then
            # Name file by class
            cp "$file" "$target_dir/${class_name}.php"
            echo "Organized: $namespace/$class_name.php"
        else
            # Use hash if no class found
            cp "$file" "$target_dir/$(basename "$file").php"
            echo "Organized: $namespace/$(basename "$file").php"
        fi
    else
        # Files without namespace go to root
        cp "$file" "../organized/$(basename "$file").php"
        echo "Organized: (no namespace)/$(basename "$file").php"
    fi
done

# Also check for markdown files
cd ..
for file in md_*; do
    if [ -f "$file" ]; then
        cp "$file" "organized/markdown/$(basename "$file").md"
        echo "Organized markdown: $(basename "$file").md"
    fi
done
