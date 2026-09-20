<?php namespace Anomaly\RepeatersModule\Http\Controller\Admin;

use Anomaly\Streams\Platform\Http\Controller\AdminController;
use Anomaly\Streams\Platform\Stream\Form\StreamFormBuilder;
use Anomaly\Streams\Platform\Stream\Table\StreamTableBuilder;

/**
 * Class StreamsController
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class StreamsController extends AdminController
{

    /**
     * The fields to skip.
     *
     * @var array
     */
    protected $skips = [
        'title_column',
        'searchable',
        'trashable',
        'sortable',
        'config',
    ];

    /**
     * Return an index of repeater streams.
     *
     * The builders administer the streams stream rather than
     * one of this module's own, so the permission convention
     * derives nothing and each is set explicitly.
     *
     * @param StreamTableBuilder $builder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(StreamTableBuilder $builder)
    {
        return $builder
            ->setNamespace('repeater')
            ->setOption('permission', 'anomaly.module.repeaters::repeaters.read')
            ->setActions(
                [
                    'prompt' => [
                        'permission' => 'anomaly.module.repeaters::repeaters.delete',
                    ],
                ]
            )
            ->setButtons(
                [
                    'edit'        => [
                        'permission' => 'anomaly.module.repeaters::repeaters.write',
                    ],
                    'assignments' => [
                        'permission' => 'anomaly.module.repeaters::repeaters.fields',
                    ],
                ]
            )
            ->render();
    }

    /**
     * Create a new stream.
     *
     * @param StreamFormBuilder $builder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(StreamFormBuilder $builder)
    {
        return $builder
            ->setPrefix('repeater_')
            ->setNamespace('repeater')
            ->setOption('permission', 'anomaly.module.repeaters::repeaters.write')
            ->setSkips($this->skips)
            ->render();
    }

    /**
     * Edit an existing stream.
     *
     * @param StreamFormBuilder $builder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(StreamFormBuilder $builder)
    {
        return $builder
            ->setNamespace('repeater')
            ->setOption('permission', 'anomaly.module.repeaters::repeaters.write')
            ->setSkips($this->skips)
            ->render($this->route->parameter('id'));
    }
}
