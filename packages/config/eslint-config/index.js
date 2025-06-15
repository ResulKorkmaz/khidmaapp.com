// Açıklama: Monorepo genelinde kullanılacak ESLint base konfigürasyonu
const { resolve } = require("node:path");

const project = resolve(process.cwd(), "tsconfig.json");

/** @type {import("eslint").Linter.Config} */
module.exports = {
  extends: [
    "@vercel/style-guide/eslint/node",
    "@vercel/style-guide/eslint/typescript",
    "@vercel/style-guide/eslint/browser",
    "@vercel/style-guide/eslint/react",
    "@vercel/style-guide/eslint/next",
    "eslint-config-turbo",
  ].map(require.resolve),
  parserOptions: {
    project,
  },
  globals: {
    React: true,
    JSX: true,
  },
  settings: {
    "import/resolver": {
      typescript: {
        project,
      },
    },
  },
  ignorePatterns: ["node_modules/", "dist/"],
  rules: {
    // Clean Code: Fonksiyon karmaşıklığını sınırla
    "complexity": ["error", 10],
    "max-lines-per-function": ["error", { max: 20, skipBlankLines: true }],
    
    // Security: Console log'ları production'da yasak
    "no-console": "error",
    
    // SOLID: Interface segregation
    "@typescript-eslint/no-empty-interface": "error",
    
    // DRY: Tekrarlanan kodları önle
    "no-duplicate-imports": "error",
    
    // Type safety
    "@typescript-eslint/strict-boolean-expressions": "error",
    "@typescript-eslint/prefer-nullish-coalescing": "error",
  },
  overrides: [
    {
      files: ["*.config.js"],
      env: {
        node: true,
      },
    },
  ],
}; 