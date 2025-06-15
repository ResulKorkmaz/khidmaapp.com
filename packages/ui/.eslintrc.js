// Açıklama: UI package ESLint konfigürasyonu
/** @type {import("eslint").Linter.Config} */
module.exports = {
  root: true,
  extends: ["@onlineusta/eslint-config/react-internal.js"],
  parser: "@typescript-eslint/parser",
  parserOptions: {
    project: "./tsconfig.lint.json",
  },
}; 