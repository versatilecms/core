<?php

use Versatile\Core\Seeders\AbstractBreadSeeder;

class SettingsBread extends AbstractBreadSeeder
{
    public function permissions()
    {
        return [
            [
                'name' => 'browse_settings',
                'description' => null,
                'table_name' => 'settings',
                'roles' => ['admin']
            ],
            [
                'name' => 'edit_settings',
                'description' => null,
                'table_name' => 'settings',
                'roles' => ['admin']
            ],
            [
                'name' => 'add_settings',
                'description' => null,
                'table_name' => 'settings',
                'roles' => ['admin']
            ],
            [
                'name' => 'delete_settings',
                'description' => null,
                'table_name' => 'settings',
                'roles' => ['admin']
            ]
        ];
    }

    public function settings()
    {
        return [
            [
                'key' => 'site.title',
                'display_name' => __('versatile::seeders.settings.site.title'),
                'value' => __('versatile::seeders.settings.site.title'),
                'details' => '',
                'type' => 'text',
                'order' => 1,
                'group' => 'Site',
            ],
            [
                'key' => 'site.description',
                'display_name' => __('versatile::seeders.settings.site.description'),
                'value' => __('versatile::seeders.settings.site.description'),
                'details' => '',
                'type' => 'text',
                'order' => 2,
                'group' => 'Site',
            ],
            [
                'key' => 'site.logo',
                'display_name' => __('versatile::seeders.settings.site.logo'),
                'value' => '',
                'details' => '',
                'type' => 'image',
                'order' => 3,
                'group' => 'Site',
            ],
            [
                'key' => 'site.google_analytics_tracking_id',
                'display_name' => __('versatile::seeders.settings.site.google_analytics_tracking_id'),
                'value' => '',
                'details' => '',
                'type' => 'text',
                'order' => 4,
                'group' => 'Site',
            ],
            // Admin
            [
                'key' => 'admin.title',
                'display_name' => __('versatile::seeders.settings.admin.title'),
                'value' => 'Versatile',
                'details' => '',
                'type' => 'text',
                'order' => 1,
                'group' => 'Admin',
            ],
            [
                'key' => 'admin.description',
                'display_name' => __('versatile::seeders.settings.admin.description'),
                'value' => __('versatile::seeders.settings.admin.description_value'),
                'details' => '',
                'type' => 'text',
                'order' => 2,
                'group' => 'Admin',
            ],
            [
                'key' => 'admin.loader',
                'display_name' => __('versatile::seeders.settings.admin.loader'),
                'value' => '',
                'details' => '',
                'type' => 'image',
                'order' => 3,
                'group' => 'Admin',
            ],
            [
                'key' => 'admin.icon_image',
                'display_name' => __('versatile::seeders.settings.admin.icon_image'),
                'value' => '',
                'details' => '',
                'type' => 'image',
                'order' => 4,
                'group' => 'Admin',
            ],
            [
                'key' => 'admin.google_analytics_client_id',
                'display_name' => __('versatile::seeders.settings.admin.google_analytics_client_id'),
                'value' => '',
                'details' => '',
                'type' => 'text',
                'order' => 1,
                'group' => 'Admin',
            ],
            [
                'key' => 'admin.bg_image',
                'display_name' => __('versatile::seeders.settings.admin.background_image'),
                'value' => '',
                'details' => '',
                'type' => 'image',
                'order' => 5,
                'group' => 'Admin',
            ]
        ];
    }
}
