<?php namespace Anomaly\RepeatersModule\Http\Controller\Admin;

use Anomaly\Streams\Platform\Addon\FieldType\FieldTypeCollection;
use Anomaly\Streams\Platform\Field\Form\FieldFormBuilder;
use Anomaly\Streams\Platform\Field\Table\FieldTableBuilder;
use Anomaly\Streams\Platform\Http\Controller\AdminController;

/**
 * Class FieldsController
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class FieldsController extends AdminController
{

    /**
     * Return an index of existing fields.
     *
     * @param FieldTableBuilder $builder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(FieldTableBuilder $builder)
    {
        return $builder
            ->setNamespace('repeater')
            ->render();
    }

    /**
     * Return the modal for choosing a field type.
     *
     * @param  FieldTypeCollection $fieldTypes
     * @return \Illuminate\View\View
     */
    public function choose(FieldTypeCollection $fieldTypes)
    {
        return $this->view->make('module::admin/fields/choose', ['field_types' => $fieldTypes->all()]);
    }

    /**
     * Create a new field.
     *
     * @param FieldFormBuilder    $builder
     * @param FieldTypeCollection $fieldTypes
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(FieldFormBuilder $builder, FieldTypeCollection $fieldTypes)
    {
        return $builder
            ->setNamespace('repeater')
            ->setFieldType($fieldTypes->get($_GET['field_type']))
            ->render();
    }

    /**
     * Edit an existing field.
     *
     * @param FieldFormBuilder $builder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(FieldFormBuilder $builder)
    {
        return $builder
            ->setNamespace('repeater')
            ->render($this->route->getParameter('id'));
    }
}
