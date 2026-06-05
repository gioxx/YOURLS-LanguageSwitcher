<?php

function ls_load_textdomain() {
    $locale = yourls_get_locale();
    $domain = 'yourls-language-switcher';
    $path   = LANG_SW_PLUGIN_DIR . '/languages/';
    if ( file_exists( $path . "{$domain}-{$locale}.mo" ) ) {
        yourls_load_textdomain( $domain, $path . "{$domain}-{$locale}.mo" );
    } elseif ( file_exists( $path . "{$domain}-{$locale}.po" ) ) {
        yourls_load_textdomain( $domain, $path . "{$domain}-{$locale}.po" );
    }
}

function ls_asset_url( $relative_path ) {
    $relative_path = ltrim( (string) $relative_path, '/' );
    $plugin_dir    = LANG_SW_PLUGIN_DIR;

    if ( function_exists( 'yourls_plugin_url' ) ) {
        return rtrim( (string) yourls_plugin_url( $plugin_dir ), '/' ) . '/' . $relative_path;
    }

    if ( defined( 'YOURLS_PLUGINDIRURL' ) ) {
        $slug = basename( $plugin_dir );
        return rtrim( (string) YOURLS_PLUGINDIRURL, '/' ) . '/' . $slug . '/' . $relative_path;
    }

    if ( defined( 'YOURLS_SITE' ) && defined( 'YOURLS_ABSPATH' ) ) {
        $rel = str_replace( '\\', '/', str_replace( (string) YOURLS_ABSPATH, '', $plugin_dir ) );
        $rel = trim( $rel, '/' );
        return rtrim( (string) YOURLS_SITE, '/' ) . '/' . $rel . '/' . $relative_path;
    }

    return '';
}

function ls_remote_get( $url ) {
    $ch = curl_init();
    curl_setopt_array( $ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT      => 'YOURLS-LanguageSwitcher/' . LANG_SW_VERSION,
        CURLOPT_TIMEOUT        => 5,
    ] );
    $response  = curl_exec( $ch );
    $http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
    curl_close( $ch );
    if ( $http_code !== 200 || $response === false ) return null;
    return json_decode( $response, true );
}

function ls_available_locales() {
    $locales = yourls_get_available_languages();
    sort( $locales );
    return array_values( array_unique( $locales ) );
}

function ls_selected_locale() {
    $selected  = yourls_get_option( LANG_SW_OPTION, '' );
    $available = ls_available_locales();

    if ( !is_string( $selected ) || $selected === '' ) {
        return '';
    }

    return in_array( $selected, $available, true ) ? $selected : '';
}

function ls_locale_label( $locale ) {
    if ( $locale === '' ) {
        return yourls__( 'English (default)', 'yourls-language-switcher' );
    }

    $labels = [
        'bg_BG' => 'Bulgarian',
        'ca_ES' => 'Catalan',
        'cs_CZ' => 'Czech',
        'da_DK' => 'Danish',
        'de_CH' => 'German (Switzerland)',
        'de_DE' => 'German',
        'en_AU' => 'English (Australian)',
        'es_ES' => 'Spanish',
        'fa_FA' => 'Farsi',
        'fi_FI' => 'Finnish',
        'fr_FR' => 'French',
        'hi-IN' => 'Hindi',
        'id_ID' => 'Indonesian',
        'it_IT' => 'Italian',
        'ja_JP' => 'Japanese',
        'ko_KR' => 'Korean',
        'nb_NO' => 'Norwegian (Bokmål)',
        'nl_NL' => 'Dutch',
        'pl_PL' => 'Polish',
        'pt_BR' => 'Portuguese (Brazil)',
        'pt_PT' => 'Portuguese',
        'ru_RU' => 'Russian',
        'sk_SK' => 'Slovak',
        'te_IN' => 'Telugu',
        'tr_TR' => 'Turkish',
        'uk'    => 'Ukrainian',
        'zh_CN' => 'Chinese (Simplified)',
        'zh_TW' => 'Chinese (Traditional)',
    ];

    $name = isset( $labels[ $locale ] ) ? $labels[ $locale ] : $locale;
    return $name . ' (' . $locale . ')';
}
