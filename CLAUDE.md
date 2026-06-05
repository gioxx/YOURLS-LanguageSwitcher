# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project

YOURLS Language Switcher is a plugin for [YOURLS](https://yourls.org/) (Your Own URL Shortener). It lets an admin switch the YOURLS admin interface language from the Plugins page without editing `config.php`. Only languages with installed `.mo` translation files in `user/languages/` appear in the dropdown.

## Plugin Structure

```
plugin.php               # Entry point: constants, require_once, hook registration
inc/
  helpers.php            # ls_load_textdomain, ls_asset_url, ls_remote_get,
                         # ls_available_locales, ls_selected_locale, ls_locale_label
  update-check.php       # ls_get_update_info, ls_show_update_notice,
                         # ls_page_title_with_badge
  admin-page.php         # ls_print_admin_assets, ls_config_page, ls_handle_save
assets/
  admin.css              # All admin styles (prefix: .ls-)
  admin.js               # Sleeky theme detection
languages/
  yourls-language-switcher.pot
  yourls-language-switcher-it_IT.po
  yourls-language-switcher-it_IT.mo
.github/FUNDING.yml
.gitattributes
LICENSE
README.md
```

## How It Works

The plugin hooks into `get_locale` (via `yourls_add_filter`) and returns the admin-selected locale stored in `yourls_language_switcher_locale`. If the stored locale is empty or no longer has a translation file, the filter passes through unchanged.

`yourls_get_available_languages()` (YOURLS core) scans `user/languages/` for `.mo` files and returns their locale codes — this is the canonical source for what's installable and visible in the dropdown.

## YOURLS API Used

- `yourls_add_action` / `yourls_add_filter` — hook registration
- `yourls_register_plugin_page` — settings page under Plugins menu
- `yourls_get_option` / `yourls_update_option` / `yourls_delete_option` — persistent settings
- `yourls_verify_nonce` / `yourls_create_nonce` — CSRF protection
- `yourls_get_available_languages()` — returns installed locale codes
- `yourls_get_locale()` — current locale
- `yourls_load_textdomain()` — i18n

## Function/Constant Prefix

- PHP functions: `ls_`
- PHP constants: `LANG_SW_`
- CSS classes: `.ls-`

## Alignment Reference

This plugin follows the structural and visual conventions documented in `/Users/gioxx/Documents/GitHub/ALIGNMENT_PROMPT.md`. The primary visual reference is `YOURLS-URLFallback` (`/Users/gioxx/Documents/GitHub/YOURLS-URLFallback`).

## Release conventions

- Tags and GitHub releases use no `v` prefix: `1.0.0`, not `v1.0.0`
- Before tagging: compile `.mo` with `msgfmt languages/yourls-language-switcher-it_IT.po -o languages/yourls-language-switcher-it_IT.mo` and commit
