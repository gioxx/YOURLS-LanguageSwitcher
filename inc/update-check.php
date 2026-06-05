<?php

function ls_get_update_info() {
    static $fetched = false;
    static $info    = null;

    if ( $fetched ) return $info;
    $fetched = true;

    $cached = yourls_get_option( 'ls_update_cache' );

    if (
        is_array( $cached )
        && !empty( $cached['checked_at'] )
        && !empty( $cached['latest_version'] )
        && ( time() - (int) $cached['checked_at'] ) < 86400
    ) {
        $info = [
            'latest_version'   => $cached['latest_version'],
            'update_available' => version_compare( $cached['latest_version'], LANG_SW_VERSION, '>' ),
            'release_url'      => $cached['release_url'] ?? '',
        ];
        return $info;
    }

    $response = ls_remote_get( LANG_SW_GITHUB_API );
    if ( $response && isset( $response['tag_name'] ) ) {
        $latest      = ltrim( $response['tag_name'], 'v' );
        $release_url = $response['html_url'] ?? '';
        yourls_update_option( 'ls_update_cache', [
            'checked_at'     => time(),
            'latest_version' => $latest,
            'release_url'    => $release_url,
        ] );
        $info = [
            'latest_version'   => $latest,
            'update_available' => version_compare( $latest, LANG_SW_VERSION, '>' ),
            'release_url'      => $release_url,
        ];
    }

    return $info;
}

function ls_show_update_notice() {
    $info = ls_get_update_info();
    if ( !$info || !$info['update_available'] ) return;

    echo '<div class="notice notice-info ls-update-notice">';
    echo '&#x1F195; <strong>YOURLS Language Switcher</strong>: ';
    echo sprintf( yourls__( 'New version available: <strong>%s</strong>!', 'yourls-language-switcher' ), yourls_esc_html( $info['latest_version'] ) );
    echo ' <a href="' . yourls_esc_url( $info['release_url'] ) . '" target="_blank" rel="noopener noreferrer">' . yourls__( 'View details on GitHub', 'yourls-language-switcher' ) . '</a>';
    echo '</div>';
}

function ls_page_title_with_badge( $title ) {
    $info = ls_get_update_info();
    if ( $info && $info['update_available'] ) {
        return $title . ' <span class="ls-update-badge">' . yourls__( 'Update Available', 'yourls-language-switcher' ) . '</span>';
    }
    return $title;
}
