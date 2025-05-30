<?php
namespace Voilaah\LinkedInConnect;

use Backend;
use System\Classes\PluginBase;
use Voilaah\LinkedInConnect\Components\LinkedInConnect;

/**
 * Plugin Information File
 *
 * @link https://docs.octobercms.com/3.x/extend/system/plugins.html
 */
class Plugin extends PluginBase
{
    /**
     * pluginDetails about this plugin.
     */
    public function pluginDetails()
    {
        return [
            'name' => 'LinkedInConnect',
            'description' => 'No description provided yet...',
            'author' => 'Voilaah',
            'icon' => 'icon-leaf'
        ];
    }

    /**
     * register method, called when the plugin is first registered.
     */
    public function register()
    {
        //
    }

    /**
     * boot method, called right before the request route.
     */
    public function boot()
    {
        //
    }

    /**
     * registerComponents used by the frontend.
     */
    public function registerComponents()
    {

        return [
            LinkedInConnect::class => 'linkedInConnect',
        ]
        ;

    }

    /**
     * registerPermissions used by the backend.
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'voilaah.linkedinconnect.some_permission' => [
                'tab' => 'LinkedInConnect',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * registerNavigation used by the backend.
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'linkedinconnect' => [
                'label' => 'LinkedInConnect',
                'url' => Backend::url('voilaah/linkedinconnect/mycontroller'),
                'icon' => 'icon-leaf',
                'permissions' => ['voilaah.linkedinconnect.*'],
                'order' => 500,
            ],
        ];
    }
}
