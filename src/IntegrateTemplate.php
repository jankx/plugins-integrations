<?php
namespace Jankx\PluginsIntegrations;

use Jankx;
use Jankx\Template\Template;

class IntegrateTemplate
{
    const ENGINE_ID = 'plugins_integrations';

    protected static $engine;

    public static function getEngine()
    {
        if (is_null(static::$engine)) {
            $engine = Template::createEngine(
                static::ENGINE_ID,
                apply_filters('jankx_plugin_integrations_template_directory', 'templates/plugins'),
                sprintf('%s/templates', dirname(JANKX_PLUGIN_INTEGRATION_BUNDLES_LOADER)),
                Jankx::getActiveTemplateEngine()
            );

            static::$engine = &$engine;
        }
        return static::$engine;
    }

    public static function render()
    {
        $args = func_get_args();
        return call_user_func_array(
            array(static::getEngine(), 'render'),
            $args
        );
    }
}
