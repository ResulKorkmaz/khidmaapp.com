#!/bin/bash
# FIX ALL SERVICE PAGES - SINGLE GREEN COLOR, NO GRADIENT

GREEN_BG="#10b981"
SERVICES_DIR="/Users/resulkorkmaz/Downloads/web/khidmaapp.com/src/Views/services"

for file in paint.php plumbing.php electric.php cleaning.php ac.php renovation.php; do
    echo "Fixing $file..."
    
    # Replace ALL background gradients with single green color
    sed -i '' 's/background: linear-gradient([^)]*);/background: #10b981;/g' "$SERVICES_DIR/$file"
    
    # Replace ALL gradient color references with single green
    sed -i '' 's/#[0-9a-fA-F]\{6\}/#10b981/g' "$SERVICES_DIR/$file"
    
    # Fix white button with white text - make text black
    sed -i '' 's/bg-white text-[a-z]*-[0-9]*/bg-white text-gray-900/g' "$SERVICES_DIR/$file"
    
    echo "✅ Fixed $file"
done

echo "🎉 All pages fixed with single green color!"

