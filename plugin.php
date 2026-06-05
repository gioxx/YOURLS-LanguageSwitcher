<?php
/*
Plugin Name: YOURLS Language Switcher
Plugin URI: https://github.com/gioxx/YOURLS-LanguageSwitcher
Description: Switch the YOURLS admin interface language from the Plugins page, without editing config.php. Only languages with installed translation files are available.
Version: 1.0.0
Author: Gioxx
Author URI: https://gioxx.org
Text Domain: yourls-language-switcher
Domain Path: /languages
*/

if ( !defined( 'YOURLS_ABSPATH' ) ) die();

define( 'LANG_SW_VERSION',    '1.0.0' );
define( 'LANG_SW_GITHUB_API', 'https://api.github.com/repos/gioxx/YOURLS-LanguageSwitcher/releases/latest' );
define( 'LANG_SW_GITHUB_URL', 'https://github.com/gioxx/YOURLS-LanguageSwitcher' );
define( 'LANG_SW_PLUGIN_DIR', dirname( __FILE__ ) );
define( 'LANG_SW_OPTION',     'yourls_language_switcher_locale' );

$ls_inc = LANG_SW_PLUGIN_DIR . '/inc/';
require_once $ls_inc . 'helpers.php';
require_once $ls_inc . 'update-check.php';
require_once $ls_inc . 'admin-page.php';

yourls_add_action( 'plugins_loaded', 'ls_boot' );
function ls_boot() {
    ls_load_textdomain();
    yourls_register_plugin_page( 'language-switcher', yourls__( 'Language Switcher', 'yourls-language-switcher' ), 'ls_config_page' );
}

yourls_add_filter( 'get_locale',                          'ls_get_locale_filter' );
yourls_add_filter( 'plugin_page_title_language-switcher', 'ls_page_title_with_badge' );

function ls_get_locale_filter( $locale ) {
    $selected = ls_selected_locale();
    return ( $selected !== '' ) ? $selected : $locale;
}
