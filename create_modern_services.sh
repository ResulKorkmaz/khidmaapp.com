#!/bin/bash
# Script to create modern service pages

TEMPLATE="/Users/resulkorkmaz/Downloads/web/khidmaapp.com/src/Views/services/cleaning.php"

# Paint - Blue theme (#2563eb #1e40af #1e3a8a)
sed 's/cleaning/paint/g; s/تنظيف/دهان/g; s/Cleaning/Painting/g; s/emerald/blue/g; s/#059669/#2563eb/g; s/#10b981/#1e40af/g; s/#0d9488/#1e3a8a/g; s/#34d399/#60a5fa/g; s/#4ade80/#3b82f6/g; s/#2dd4bf/#2563eb/g; s/✨/🎨/g; s/تنظيف شامل للمنازل والمكاتب والشقق/خدمات دهان داخلية وخارجية بأعلى جودة/g; s/احترافية في السعودية | شركة تنظيف/احترافية في السعودية | معلم دهان/g' "$TEMPLATE" > /Users/resulkorkmaz/Downloads/web/khidmaapp.com/src/Views/services/paint.php

echo "✅ Paint page created"

# Plumbing - Cyan theme (#0891b2 #06b6d4 #0e7490)
sed 's/cleaning/plumbing/g; s/تنظيف/سباكة/g; s/Cleaning/Plumbing/g; s/emerald/cyan/g; s/#059669/#0891b2/g; s/#10b981/#06b6d4/g; s/#0d9488/#0e7490/g; s/#34d399/#22d3ee/g; s/#4ade80/#06b6d4/g; s/#2dd4bf/#0891b2/g; s/✨/💧/g; s/تنظيف شامل للمنازل والمكاتب والشقق/خدمات سباكة - إصلاح تسربات وصيانة أنابيب/g; s/احترافية في السعودية | شركة تنظيف/احترافية في السعودية | فني سباكة/g' "$TEMPLATE" > /Users/resulkorkmaz/Downloads/web/khidmaapp.com/src/Views/services/plumbing.php

echo "✅ Plumbing page created"

# Electric - Orange/Yellow theme (#ea580c #f97316 #c2410c)
sed 's/cleaning/electric/g; s/تنظيف/كهرباء/g; s/Cleaning/Electrical/g; s/emerald/orange/g; s/#059669/#ea580c/g; s/#10b981/#f97316/g; s/#0d9488/#c2410c/g; s/#34d399/#fb923c/g; s/#4ade80/#f97316/g; s/#2dd4bf/#ea580c/g; s/✨/⚡/g; s/تنظيف شامل للمنازل والمكاتب والشقق/خدمات كهرباء - إصلاح أعطال وتمديدات كهربائية/g; s/احترافية في السعودية | شركة تنظيف/احترافية في السعودية | فني كهرباء/g' "$TEMPLATE" > /Users/resulkorkmaz/Downloads/web/khidmaapp.com/src/Views/services/electric.php

echo "✅ Electric page created"

# AC - Sky/Blue theme (#0284c7 #0ea5e9 #075985)
sed 's/cleaning/ac/g; s/تنظيف/تكييف/g; s/Cleaning/Air Conditioning/g; s/emerald/sky/g; s/#059669/#0284c7/g; s/#10b981/#0ea5e9/g; s/#0d9488/#075985/g; s/#34d399/#38bdf8/g; s/#4ade80/#0ea5e9/g; s/#2dd4bf/#0284c7/g; s/✨/❄️/g; s/تنظيف شامل للمنازل والمكاتب والشقق/خدمات تكييف - صيانة وتركيب وتنظيف المكيفات/g; s/احترافية في السعودية | شركة تنظيف/احترافية في السعودية | فني تكييف/g' "$TEMPLATE" > /Users/resulkorkmaz/Downloads/web/khidmaapp.com/src/Views/services/ac.php

echo "✅ AC page created"

# Renovation - Amber theme (#d97706 #f59e0b #b45309)
sed 's/cleaning/renovation/g; s/تنظيف/ترميم/g; s/Cleaning/Renovation/g; s/emerald/amber/g; s/#059669/#d97706/g; s/#10b981/#f59e0b/g; s/#0d9488/#b45309/g; s/#34d399/#fbbf24/g; s/#4ade80/#f59e0b/g; s/#2dd4bf/#d97706/g; s/✨/🏗️/g; s/تنظيف شامل للمنازل والمكاتب والشقق/خدمات ترميم وتجديد للمنازل والمباني/g; s/احترافية في السعودية | شركة تنظيف/احترافية في السعودية | مقاول ترميم/g' "$TEMPLATE" > /Users/resulkorkmaz/Downloads/web/khidmaapp.com/src/Views/services/renovation.php

echo "✅ Renovation page created"

echo "🎉 All service pages created successfully!"

