<?php
/**
 * Plugin Name: Jankx Plugin Integrations
 * Plugin URI: https://jankx.puleeno.com
 * Author: Puleeno Nguyen
 * Author URI: https://puleeno.com
 * Description:  Make compatible with other premium WordPress plugins
 * Version:1.0.0
 */

define('JANKX_PLUGIN_INTEGRATION_BUNDLES_LOADER', __FILE__);

use Jankx\Plugin\Integration\PluginIntegrationManager;

if (!class_exists(PluginIntegrationManager::class)) {
    $composerAutoloader = sprintf('%s/vendor/autoload.php');
    if (file_exists($composerAutoloader)) {
        require_once $composerAutoloader;
    }
}

if (!function_exists('jankx_plugins_inegrations')) {
    function jankx_plugins_inegrations() {
        return PluginIntegrationManager::getInstance();
    }
}

$GLOBALS['jankx_plugins_inegrations'] = &jankx_plugins_inegrations();
