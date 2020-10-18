<?php
namespace Jankx\Plugin\Integration;

use Jankx\Template\Template;

class IntegrateTemplate {
    protected static $templateLoader;

    public static function getLoader() {
        $defaultTemplateDir = sprintf('%s/templates', dirname(JANKX_PLUGIN_INTEGRATION_BUNDLES_LOADER));
        if (is_null(static::$templateLoader)) {
            static::$templateLoader = Template::getLoader(
                $defaultTemplateDir,
                apply_filters('jankx_plugin_integrations_template_directory', 'plugins'),
                'wordpress'
            );
        }
        return static::$templateLoader;
    }

    public static function render() {
        $args = func_get_args();
        return call_user_func_array(
            array(static::getLoader(), 'render'),
            $args
        );
    }
}
