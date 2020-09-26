<?php
namespace Jankx\Plugin\Integration\Plugins;

use Jankx\Plugin\Integration\PluginIntegrationManager;

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
            add_action('jankx_template_after_header', array($this, 'renderUberMenuAfterHeader'), 11);

            $item_output = ubermenu_toggle('jankx-ubermenu-vertical-menu', 'main', false, array(
                'icon_class' => 'rocket',
                'toggle_id' => 'ubermenu-main-2064-vertical_menu-2'
            ));
        }
        return $item_output;
    }

    public function renderUberMenuAfterHeader()
    {
        echo '<div class="jankx-integrate-ubermenu--vertical">';

        jankx_open_container();

        ubermenu('main', array(
            'theme_location' => static::VERTICAL_MENU_LOCATION,
            'target' => 'jankx-ubermenu-vertical-menu',
        ));

        jankx_close_container();

        echo '</div>';
    }

    protected function hasVerticalMenuItem()
    {
        $locations = get_nav_menu_locations();

        $submenu = wp_get_nav_menu_object($locations['secondary']);

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
        css(
            'jankx-ubermenu-integrate',
            jankx_plugins_integration_asset_url('css/ubermenu.css'),
            array(),
            PluginIntegrationManager::VERSION
        );
        array_push($deps, 'jankx-ubermenu-integrate');

        return $deps;
    }
}
