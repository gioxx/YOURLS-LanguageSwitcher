# &#127760; YOURLS Language Switcher

**Switch the YOURLS admin interface language from the Plugins page — no need to edit `config.php`.**  
Only languages with installed translation files are available for selection.

[![Latest Release](https://img.shields.io/github/v/release/gioxx/YOURLS-LanguageSwitcher)](https://github.com/gioxx/YOURLS-LanguageSwitcher/releases)
[![License](https://img.shields.io/github/license/gioxx/YOURLS-LanguageSwitcher)](LICENSE)

---

## 🚀 Features

- **Live language switching** for the YOURLS admin interface, persisted in the database
- **No `config.php` edits required** — the override is applied on every page load via the `get_locale` filter
- **Only shows installed languages** — the dropdown lists exactly the locales whose `.mo` files exist in `user/languages/`; no ghost options, no silent failures
- **Reset to default** — selecting *English (default)* removes the override and defers back to `config.php`
- **Update notifications**: checks GitHub for new releases and shows a badge in the admin panel
- **Modular structure**: split into `inc/` modules and static `assets/`, no composer, no npm, no build step

---

## ⚠️ Important: language files are required

This plugin **can only switch to languages whose translation files are already installed** in your YOURLS instance.

> **Note:** YOURLS does not ship with language files by default. If no languages appear in the dropdown, you need to download `.mo` files and place them in `user/languages/` first.

Translation files for YOURLS are available in the [YOURLS GitHub repository](https://github.com/YOURLS/YOURLS/tree/master/user/languages).  
Copy the `yourls-<locale>.mo` file (e.g. `yourls-it_IT.mo`) into `user/languages/` — the language will then appear automatically in the plugin's dropdown.

---

## 🛠️ Installation

1. Download the plugin from [the latest release](https://github.com/gioxx/YOURLS-LanguageSwitcher/releases).
2. Unzip the contents into the `user/plugins/language-switcher/` directory.
3. Activate the plugin in the YOURLS admin panel under **Plugins**.
4. Go to **Plugins → Language Switcher** and select the desired language.

> **Requires YOURLS 1.9+ and PHP 7.4+**

---

## ⚙️ Usage

1. Open the **Language Switcher** settings page from the Plugins menu.
2. Select the language from the dropdown. Only locales with an installed `.mo` file in `user/languages/` are shown.
3. Click **Save Language**. The change takes effect immediately on the next page load.
4. To revert, select **English (default)** and save.

### How it works

The plugin hooks into `get_locale` and returns the stored locale whenever a language is selected. If no override is stored, or if the stored locale no longer has a translation file, the filter passes through the original locale from `config.php`.

---

## 🔧 Stored Options

| Option key | Type | Notes |
|---|---|---|
| `yourls_language_switcher_locale` | string | Locale code (e.g. `it_IT`); empty string = no override |

---

## 🌐 Translation

This plugin is ready for internationalization via `.po`/`.mo` files inside the `languages/` folder.  
Available languages:
- 🇬🇧 English (default)
- 🇮🇹 Italian (`it_IT`)

Contributions for additional languages are welcome.

---

## 📄 License

This plugin is licensed under the [MIT License](LICENSE).

---

## 💬 About

Lovingly developed by the usually-on-vacation brain cell of [Gioxx](https://github.com/gioxx), with assistance from Claude AI.

---

## 🤝 Contributing

Pull requests and feature suggestions are welcome!  
If you find bugs or have feature requests, [open an issue](https://github.com/gioxx/YOURLS-LanguageSwitcher/issues).  
If you find it useful, leave a ⭐ on GitHub! ❤️
