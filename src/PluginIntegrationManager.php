<?php
namespace Jankx\PluginsIntegrations;

use Jankx\PluginsIntegrations\Plugins\UberMenu;

class PluginIntegrationManager
{
    const VERSION = '0.0.5';

    protected static $instance;

    protected static $supportPlugins = array();
    protected static $activePlugins = array();

    public static function getInstance()
    {
        if (is_null(static::$instance)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    private function __construct()
    {
        $this->loadHelpers();
        $this->detectPlugins();

        add_action('after_setup_theme', array($this, 'activePlugins'), 5);
    }

    public function loadHelpers()
    {
        require_once dirname(JANKX_PLUGIN_INTEGRATION_BUNDLES_LOADER) . '/functions.php';
    }

    public function detectPlugins()
    {
        static::$supportPlugins = array(
            'ubermenu/ubermenu.php' => UberMenu::class,
        );

        $activePlugins = get_option('active_plugins');
        foreach ($activePlugins as $activePlugin) {
            if (!isset(static::$supportPlugins[$activePlugin])) {
                continue;
            }
            static::$activePlugins[$activePlugin] = static::$supportPlugins[$activePlugin];
        }
    }

    public function activePlugins()
    {
        $activePlugins = apply_filters('jankx_premium_integration_plugins', static::$activePlugins);

        foreach ($activePlugins as $activePlugin => $clsIntegration) {
            new $clsIntegration();
        }
    }
}
