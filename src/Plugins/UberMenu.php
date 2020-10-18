<?php
namespace Jankx\Plugin\Integration\Plugins;

use Jankx\Plugin\Integration\PluginIntegrationManager;
use Jankx\Plugin\Integration\IntegrateTemplate;

class UberMenu
{
    const VERTICAL_MENU_LOCATION = 'vertical_menu';
    const VERTICAL_ITEM_TYPE = 'vertical_ubermenu';

    public function __construct()
    {
        if (apply_filters('jankx_plugins_integrations_ubermenu_support_vertial_menu', false)) {
            add_filter('jankx_site_layout_register_menus', array($this, 'registerNewMenuLocations'));

            add_filter('jankx_site_layout_menu_items', array($this, 'addToJankxMenuItems'));
            add_filter('jankx_site_layout_vertical_ubermenu_menu_item', array($this, 'createMenuItemLabel'));

            add_filter('walker_nav_menu_start_el', array($this, 'renderMenuItem'), 10, 4);

            add_filter('body_class', array($this, 'appendOpenVerticalMenuClass'));
        }

        add_action('jankx_template_css_dependences', array($this, 'registerAssets'));
    }

    public function registerNewMenuLocations($menus)
    {
        $menus = array_merge($menus, array(
            static::VERTICAL_MENU_LOCATION => __('Veritical Menu', 'jankx-plugins-integrations')
        ));

        return $menus;
    }

    public function addToJankxMenuItems($items)
    {
        $items = array_merge($items, array(
            static::VERTICAL_ITEM_TYPE => __('Vertical UberMenu', 'jankx-plugins-integrations'),
        ));

        return $items;
    }

    public function createMenuItemLabel($item)
    {
        $item['title'] = __('Vertical Menu', 'jankx-plugins-integrations');

        return $item;
    }

    public function renderMenuItem($item_output, $item, $depth, $args)
    {
        if ($item->type === static::VERTICAL_ITEM_TYPE) {
            $item_output .= $this->renderUberMenuVerticalOrientation();
        }
        return $item_output;
    }

    public function renderUberMenuVerticalOrientation()
    {
        ob_start();
        echo '<div class="jankx-integrate-ubermenu--vertical">';

        jankx_open_container();
        add_filter('ubermenu_op', array($this, 'overrideOrientation'), 10, 3);
        ubermenu('main', array(
            'theme_location' => static::VERTICAL_MENU_LOCATION,
            'target' => 'jankx-ubermenu-vertical-menu',
        ));
        remove_filter('ubermenu_op', array($this, 'overrideOrientation'), 10, 3);
        jankx_close_container();

        echo '</div>';

        $verticalMenuContent = ob_get_clean();

        // Make the javascript to hover to show
        execute_script(
            IntegrateTemplate::render('ubermenu/show-menu-script', array(), null, false)
        );

        return $verticalMenuContent;
    }

    protected function hasVerticalMenuItem()
    {
        $locations = get_nav_menu_locations();
        $submenu   = wp_get_nav_menu_object($locations[apply_filters(
            'jankx_plugin_integrate_ubermenu_location',
            'secondary'
        )]);
        $menu_items = wp_get_nav_menu_items($submenu->name);

        foreach ($menu_items as $menu_item) {
            if ($menu_item->type === static::VERTICAL_ITEM_TYPE) {
                return true;
            }
        }
        return false;
    }

    public function appendOpenVerticalMenuClass($classes)
    {
        if (!$this->hasVerticalMenuItem()) {
            return $classes;
        }
        if (apply_filters('jankx_plugins_integrations_uber_force_show', false)) {
            $classes[] = 'ubermenu-force-show';
        }

        return $classes;
    }

    public function registerAssets($deps)
    {
        $ubermenuStyle = sprintf('%s/assets/css/ubermenu.css', dirname(JANKX_PLUGIN_INTEGRATION_BUNDLES_LOADER));
        $uberMetadata  = get_file_data($ubermenuStyle, array(
            'version' => 'Version',
        ));
        css(
            'jankx-ubermenu-integrate',
            jankx_plugins_integration_asset_url('css/ubermenu.css'),
            array(),
            $uberMetadata['version'] ? $uberMetadata['version'] : PluginIntegrationManager::VERSION
        );
        array_push($deps, 'jankx-ubermenu-integrate');

        return $deps;
    }

    public function overrideOrientation($val, $option, $section)
    {
        if ($option === 'orientation') {
            return 'vertical';
        }
        return $val;
    }
}
