<?php namespace Anomaly\RepeatersModule;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;

/**
 * Class RepeatersModuleServiceProvider
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class RepeatersModuleServiceProvider extends AddonServiceProvider
{

    /**
     * The addon routes.
     *
     * @var array
     */
    protected $routes = [
        'admin/repeaters'                                => 'Anomaly\RepeatersModule\Http\Controller\Admin\StreamsController@index',
        'admin/repeaters/create'                         => 'Anomaly\RepeatersModule\Http\Controller\Admin\StreamsController@create',
        'admin/repeaters/edit/{id}'                      => 'Anomaly\RepeatersModule\Http\Controller\Admin\StreamsController@edit',
        'admin/repeaters/fields'                         => 'Anomaly\RepeatersModule\Http\Controller\Admin\FieldsController@index',
        'admin/repeaters/fields/choose'                  => 'Anomaly\RepeatersModule\Http\Controller\Admin\FieldsController@choose',
        'admin/repeaters/fields/create'                  => 'Anomaly\RepeatersModule\Http\Controller\Admin\FieldsController@create',
        'admin/repeaters/fields/edit/{id}'               => 'Anomaly\RepeatersModule\Http\Controller\Admin\FieldsController@edit',
        'admin/repeaters/assignments/{stream}'           => 'Anomaly\RepeatersModule\Http\Controller\Admin\AssignmentsController@index',
        'admin/repeaters/assignments/{stream}/choose'    => 'Anomaly\RepeatersModule\Http\Controller\Admin\AssignmentsController@choose',
        'admin/repeaters/assignments/{stream}/create'    => 'Anomaly\RepeatersModule\Http\Controller\Admin\AssignmentsController@create',
        'admin/repeaters/assignments/{stream}/edit/{id}' => 'Anomaly\RepeatersModule\Http\Controller\Admin\AssignmentsController@edit',
    ];
}
