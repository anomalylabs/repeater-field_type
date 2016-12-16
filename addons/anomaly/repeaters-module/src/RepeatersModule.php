<?php namespace Anomaly\RepeatersModule;

use Anomaly\Streams\Platform\Addon\Module\Module;

/**
 * Class RepeatersModule
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class RepeatersModule extends Module
{

    /**
     * This module does not
     * display in navigation.
     *
     * @var bool
     */
    protected $navigation = false;

    /**
     * The addon sections.
     *
     * @var array
     */
    protected $sections = [
        'repeaters' => [
            'buttons'  => [
                'new_repeater',
            ],
            'sections' => [
                'assignments' => [
                    'href'    => 'admin/repeaters/assignments/{request.route.parameters.stream}',
                    'buttons' => [
                        'assign_fields' => [
                            'data-toggle' => 'modal',
                            'data-target' => '#modal',
                            'enabled'     => 'admin/repeaters/assignments/*',
                            'href'        => 'admin/repeaters/assignments/{request.route.parameters.stream}/choose',
                        ],
                    ],
                ],
            ],
        ],
        'fields'    => [
            'buttons' => [
                'new_field' => [
                    'data-toggle' => 'modal',
                    'data-target' => '#modal',
                    'href'        => 'admin/repeaters/fields/choose',
                ],
            ],
        ],
    ];
}
