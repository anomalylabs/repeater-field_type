<?php namespace Anomaly\RepeaterFieldType;

use Anomaly\Streams\Platform\Addon\AddonServiceProvider;

/**
 * Class RepeaterFieldTypeServiceProvider
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 * @package       Anomaly\RepeaterFieldType
 */
class RepeaterFieldTypeServiceProvider extends AddonServiceProvider
{

    /**
     * The addon routes.
     *
     * @var array
     */
    protected $routes = [
        'streams/field_type/repeater/form/{field}' => 'Anomaly\RepeaterFieldType\Http\Controller\RepeaterController@form'
    ];
}
