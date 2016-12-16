<?php namespace Anomaly\RepeatersModule\Http\Controller\Admin;

use Anomaly\Streams\Platform\Assignment\Form\AssignmentFormBuilder;
use Anomaly\Streams\Platform\Assignment\Table\AssignmentTableBuilder;
use Anomaly\Streams\Platform\Field\Contract\FieldInterface;
use Anomaly\Streams\Platform\Field\Contract\FieldRepositoryInterface;
use Anomaly\Streams\Platform\Http\Controller\AdminController;
use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;
use Anomaly\Streams\Platform\Stream\Contract\StreamRepositoryInterface;

/**
 * Class AssignmentsController
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class AssignmentsController extends AdminController
{

    /**
     * Return an index of existing streams.
     *
     * @param AssignmentTableBuilder    $builder
     * @param StreamRepositoryInterface $streams
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(AssignmentTableBuilder $builder, StreamRepositoryInterface $streams)
    {
        /* @var StreamInterface $stream */
        $stream = $streams->find($this->route->getParameter('stream'));

        return $builder
            ->setStream($stream)
            ->render();
    }

    /**
     * Return the modal for choosing a field to assign.
     *
     * @param  FieldRepositoryInterface  $fields
     * @param  StreamRepositoryInterface $streams
     * @param                            $stream
     * @return \Illuminate\Contracts\View\View|mixed
     */
    public function choose(FieldRepositoryInterface $fields, StreamRepositoryInterface $streams, $stream)
    {
        /* @var StreamInterface $stream */
        $stream = $streams->find($this->route->getParameter('stream'));

        $fields = $fields
            ->findAllByNamespace('repeater')
            ->notAssignedTo($stream)
            ->unlocked();

        return $this->view->make('module::admin/assignments/choose', compact('fields'));
    }

    /**
     * Create a new assignment.
     *
     * @param  AssignmentFormBuilder     $builder
     * @param  StreamRepositoryInterface $streams
     * @param  FieldRepositoryInterface  $fields
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(
        AssignmentFormBuilder $builder,
        StreamRepositoryInterface $streams,
        FieldRepositoryInterface $fields
    ) {
        /* @var FieldInterface $field */
        /* @var StreamInterface $stream */
        $field  = $fields->find($this->request->get('field'));
        $stream = $streams->find($this->route->getParameter('stream'));

        return $builder
            ->setField($field)
            ->setStream($stream)
            ->render();
    }

    /**
     * Edit an existing assignment.
     *
     * @param  AssignmentFormBuilder     $builder
     * @param  StreamRepositoryInterface $streams
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(AssignmentFormBuilder $builder, StreamRepositoryInterface $streams)
    {
        /* @var StreamInterface $stream */
        $stream = $streams->find($this->route->getParameter('stream'));

        return $builder
            ->setStream($stream)
            ->render($this->route->getParameter('id'));
    }
}
