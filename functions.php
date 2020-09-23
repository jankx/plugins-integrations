<?php
function jankx_plugins_integration_asset_url($path = '') {
    $assetDirUrl = str_replace(
        ABSPATH,
        site_url('/'),
        dirname(JANKX_PLUGIN_INTEGRATION_BUNDLES_LOADER)
    );
    return sprintf('%s/assets/%s', $assetDirUrl, $path);
}
