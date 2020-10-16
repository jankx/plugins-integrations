<?php
function jankx_plugins_integration_asset_url($path = '') {
    $abspath = constant('ABSPATH');
    $pluginPath = dirname(JANKX_PLUGIN_INTEGRATION_BUNDLES_LOADER);

    if (PHP_OS === 'WINNT') {
        $abspath = str_replace('\\', '/', $abspath);
        $pluginPath = str_replace('\\', '/', $pluginPath);
    }

    $assetDirUrl = str_replace(
        $abspath,
        site_url('/'),
        $pluginPath
    );
    return sprintf('%s/assets/%s', $assetDirUrl, $path);
}
