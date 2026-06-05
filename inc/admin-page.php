<?php

function ls_print_admin_assets() {
    $css_file = LANG_SW_PLUGIN_DIR . '/assets/admin.css';
    $js_file  = LANG_SW_PLUGIN_DIR . '/assets/admin.js';
    $css_url  = ls_asset_url( 'assets/admin.css' );
    $js_url   = ls_asset_url( 'assets/admin.js' );
    $css_ver  = file_exists( $css_file ) ? filemtime( $css_file ) : LANG_SW_VERSION;
    $js_ver   = file_exists( $js_file )  ? filemtime( $js_file )  : LANG_SW_VERSION;

    if ( $css_url !== '' ) {
        echo '<link rel="stylesheet" href="' . yourls_esc_url( $css_url ) . '?v=' . $css_ver . '" />';
    }
    if ( $js_url !== '' ) {
        echo '<script src="' . yourls_esc_url( $js_url ) . '?v=' . $js_ver . '"></script>';
    }
    echo '<script>window.LS_Data = ' . json_encode( [
        'confirm_reset' => yourls__( 'Are you sure you want to reset the language to default?', 'yourls-language-switcher' ),
    ], JSON_HEX_TAG | JSON_HEX_AMP ) . ';</script>';
}

function ls_config_page() {
    $message = null;

    if ( isset( $_POST['ls_save'] ) ) {
        yourls_verify_nonce( 'ls_config' );
        $message = ls_handle_save();
    }

    if ( isset( $_POST['ls_reset'] ) ) {
        yourls_verify_nonce( 'ls_reset', isset( $_POST['nonce_reset'] ) ? $_POST['nonce_reset'] : '' );
        yourls_delete_option( LANG_SW_OPTION );
        $message = [ 'success' => true, 'text' => yourls__( 'Language reset to English (default).', 'yourls-language-switcher' ) ];
    }

    $available  = ls_available_locales();
    $current    = ls_selected_locale();
    $nonce      = yourls_create_nonce( 'ls_config' );
    $nonce_reset = yourls_create_nonce( 'ls_reset' );

    ls_print_admin_assets();
    ls_show_update_notice();

    echo '<div class="ls-header">';
    echo '<h2 class="ls-title">&#127760; <span class="ls-title-text">YOURLS Language Switcher</span></h2>';
    echo '<p class="plugin-version">' . yourls__( 'Version: ', 'yourls-language-switcher' ) . LANG_SW_VERSION . '</p>';
    echo '</div>';

    if ( $message ) {
        $type = $message['success'] ? 'success' : 'warning';
        echo '<div class="notice notice-' . $type . '"><p>' . $message['text'] . '</p></div>';
    }

    echo '<form method="post" class="ls-form">';
    echo '<input type="hidden" name="nonce" value="' . yourls_esc_attr( $nonce ) . '" />';

    echo '<div class="ls-panel">';
    echo '<h3>' . yourls__( 'Language Settings', 'yourls-language-switcher' ) . '</h3>';

    echo '<div class="ls-row">';
    echo '<label for="ls_locale">' . yourls__( 'Active Language', 'yourls-language-switcher' ) . '</label>';
    echo '<small>' . yourls__( 'Select the language for the YOURLS admin interface. Only languages with installed translation files appear in this list.', 'yourls-language-switcher' ) . '</small>';

    echo '<select id="ls_locale" name="ls_locale">';
    $selected_empty = ( $current === '' ) ? ' selected="selected"' : '';
    echo '<option value=""' . $selected_empty . '>' . yourls_esc_html( yourls__( 'English (default)', 'yourls-language-switcher' ) ) . '</option>';

    foreach ( $available as $locale ) {
        $sel   = ( $locale === $current ) ? ' selected="selected"' : '';
        $label = ls_locale_label( $locale );
        echo '<option value="' . yourls_esc_attr( $locale ) . '"' . $sel . '>' . yourls_esc_html( $label ) . '</option>';
    }

    echo '</select>';
    echo '</div>'; // .ls-row

    if ( empty( $available ) ) {
        echo '<div class="ls-no-langs">';
        echo yourls__( 'No translation files found. Install YOURLS language files in <code>user/languages/</code> to enable language switching.', 'yourls-language-switcher' );
        echo '</div>';
    }

    echo '</div>'; // .ls-panel

    echo '<div class="ls-info-box">';
    echo '<h4 class="ls-info-title"><span class="ls-info-icon">i</span>' . yourls__( 'Notes', 'yourls-language-switcher' ) . '</h4>';
    echo '<ul class="ls-info-list">';
    echo '<li>' . yourls__( 'Language switching <strong>requires the corresponding <code>.mo</code> translation file</strong> to be present in the <code>user/languages/</code> directory of your YOURLS installation.', 'yourls-language-switcher' ) . '</li>';
    echo '<li>' . yourls__( 'If a language is not listed, its translation file has not been installed — the plugin cannot switch to it.', 'yourls-language-switcher' ) . '</li>';
    echo '<li>' . yourls__( 'YOURLS translation files are available in the <a href="https://github.com/YOURLS/YOURLS/tree/master/user/languages" target="_blank" rel="noopener noreferrer">YOURLS GitHub repository</a>.', 'yourls-language-switcher' ) . '</li>';
    echo '<li>' . yourls__( 'Selecting <strong>English (default)</strong> removes any override and falls back to the language set in <code>config.php</code>.', 'yourls-language-switcher' ) . '</li>';
    echo '</ul>';
    echo '</div>';

    echo '<div class="ls-actions">';
    echo '<button type="submit" name="ls_save" class="button">&#128190; ' . yourls__( 'Save Language', 'yourls-language-switcher' ) . '</button>';
    echo '<button type="submit" name="ls_reset" class="button" ';
    echo 'onclick="return confirm(window.LS_Data.confirm_reset);" formnovalidate>';
    echo '&#128260; ' . yourls__( 'Reset to Default', 'yourls-language-switcher' ) . '</button>';
    echo '<input type="hidden" name="nonce_reset" value="' . yourls_esc_attr( $nonce_reset ) . '" />';
    echo '</div>';

    echo '</form>';

    echo '<div class="plugin-footer">';
    echo '<div class="plugin-footer-top">';
    echo '<span>';
    echo '<a href="https://yourls.gioxx.org/plugins/language-switcher" target="_blank" rel="noopener noreferrer">&#127760; YOURLS Language Switcher</a>';
    echo '&nbsp;&middot;&nbsp;';
    echo '<img src="https://github.githubassets.com/favicons/favicon.png" class="github-icon" alt="" />';
    echo '<a href="' . LANG_SW_GITHUB_URL . '" target="_blank" rel="noopener noreferrer">GitHub</a>';
    echo '</span>';
    echo '<a href="#" onclick="window.scrollTo({top:0,behavior:\'smooth\'});return false;">&#8593; ' . yourls__( 'Back to top', 'yourls-language-switcher' ) . '</a>';
    echo '</div>';
    echo '<span>&#10084;&#65039; Lovingly developed by the usually-on-vacation brain cell of ';
    echo '<a href="https://github.com/gioxx" target="_blank" rel="noopener noreferrer">Gioxx</a> &ndash; ';
    echo '<a href="https://gioxx.org" target="_blank" rel="noopener noreferrer">Gioxx\'s Wall</a></span>';
    echo '</div>';
}

function ls_handle_save() {
    $selected  = isset( $_POST['ls_locale'] ) ? trim( (string) $_POST['ls_locale'] ) : '';
    $available = ls_available_locales();

    if ( $selected === '' ) {
        yourls_delete_option( LANG_SW_OPTION );
        return [ 'success' => true, 'text' => yourls__( 'Language reset to English (default).', 'yourls-language-switcher' ) ];
    }

    if ( !in_array( $selected, $available, true ) ) {
        return [ 'success' => false, 'text' => yourls__( 'Selected language is not available. Make sure its translation file is installed.', 'yourls-language-switcher' ) ];
    }

    yourls_update_option( LANG_SW_OPTION, $selected );
    return [ 'success' => true, 'text' => yourls__( 'Language updated successfully.', 'yourls-language-switcher' ) ];
}
