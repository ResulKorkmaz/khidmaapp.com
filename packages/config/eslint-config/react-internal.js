// Açıklama: React bileşenleri için ESLint konfigürasyonu
const { resolve } = require("node:path");

const project = resolve(process.cwd(), "tsconfig.json");

/** @type {import("eslint").Linter.Config} */
module.exports = {
  extends: [
    "@vercel/style-guide/eslint/node",
    "@vercel/style-guide/eslint/typescript",
    "@vercel/style-guide/eslint/browser",
    "@vercel/style-guide/eslint/react",
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
    
    // React: Hooks kuralları
    "react-hooks/rules-of-hooks": "error",
    "react-hooks/exhaustive-deps": "warn",
    
    // Import sorting
    "import/order": [
      "error",
      {
        "groups": [
          "builtin",
          "external",
          "internal",
          "parent",
          "sibling",
          "index"
        ],
        "newlines-between": "always"
      }
    ],
  },
}; 