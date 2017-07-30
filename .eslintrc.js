module.exports = {
  "env": {
    "browser": true,
    "es6": true
  },
  "extends": "eslint:recommended",
  "parserOptions": {
    "sourceType": "module"
  },
  "rules": {
    "indent": [
      "warn", 2
    ],
    "linebreak-style": [
      "warn", "unix"
    ],
    "quotes": [
      "warn", "single"
    ],
    "semi": [
      "warn", "always"
    ],
    "no-unused-vars": [
      "warn", {
        "vars": "all",
        "args": "after-used",
        "ignoreRestSiblings": false
      }
    ]
  }
};
