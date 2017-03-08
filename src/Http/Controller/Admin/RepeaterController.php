<?php namespace Anomaly\RepeaterFieldType\Http\Controller\Admin;

use Anomaly\RepeaterFieldType\RepeaterFieldType;
use Anomaly\Streams\Platform\Field\Contract\FieldInterface;
use Anomaly\Streams\Platform\Field\Contract\FieldRepositoryInterface;
use Anomaly\Streams\Platform\Http\Controller\AdminController;

/**
 * Class RepeaterController
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class RepeaterController extends AdminController
{

    /**
     * Return a form row.
     *
     * @param FieldRepositoryInterface $fields
     * @param                          $field
     */
    public function form(FieldRepositoryInterface $fields, $field)
    {
        /* @var FieldInterface $field */
        $field = $fields->find($field);

        /* @var RepeaterFieldType $type */
        $type = $field->getType();

        $type->setPrefix($this->request->get('prefix'));

        return $type
            ->form($field, $this->request->get('instance'))
            ->addFormData('field_type', $type)
            ->render();
    }
}
