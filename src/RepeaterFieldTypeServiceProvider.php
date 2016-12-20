<?php namespace Anomaly\RepeaterFieldType;

use Anomaly\Streams\Platform\Addon\AddonIntegrator;
use Anomaly\Streams\Platform\Addon\AddonServiceProvider;
use Anomaly\Streams\Platform\Ui\Breadcrumb\BreadcrumbCollection;
use Illuminate\Http\Request;

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
        'admin/field_type/repeater/form/{field}' => 'Anomaly\RepeaterFieldType\Http\Controller\RepeaterController@form',
    ];

    /**
     * Register the addon.
     *
     * @param AddonIntegrator      $integrator
     * @param Request              $request
     * @param BreadcrumbCollection $breadcrumb
     */
    public function register(AddonIntegrator $integrator, Request $request, BreadcrumbCollection $breadcrumb)
    {
        if ($request->segment(2) == 'repeaters') {

            $breadcrumb->add('anomaly.module.repeaters::addon.name', 'admin/repeaters');

            $integrator->register(
                __DIR__ . '/../addons/anomaly/repeaters-module/',
                'anomaly.module.repeaters',
                true,
                true
            );
        }
    }
}
