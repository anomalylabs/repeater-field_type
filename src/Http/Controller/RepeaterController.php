<?php namespace Anomaly\RepeaterFieldType\Http\Controller;

use Anomaly\RepeaterFieldType\RepeaterFieldType;
use Anomaly\Streams\Platform\Field\Contract\FieldInterface;
use Anomaly\Streams\Platform\Field\Contract\FieldRepositoryInterface;
use Anomaly\Streams\Platform\Http\Controller\PublicController;
use Anomaly\Streams\Platform\Ui\Form\Command\LoadForm;
use Anomaly\Streams\Platform\Ui\Form\Command\MakeForm;
use Anomaly\Streams\Platform\Ui\Form\Command\PopulateFields;
use Anomaly\Streams\Platform\Ui\Form\Command\SetFormResponse;

/**
 * Class RepeaterController
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class RepeaterController extends PublicController
{

    /**
     * Return a form row.
     *
     * The endpoint is public so that repeater fields work on
     * front end forms, so it renders markup only and never
     * posts. See the route's "get" verb.
     *
     * @param FieldRepositoryInterface $fields
     * @param                          $field
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function form(FieldRepositoryInterface $fields, $field)
    {
        /* @var FieldInterface $field */
        if (!$field = $fields->find($field)) {
            abort(404);
        }

        /* @var RepeaterFieldType $type */
        $type = $field->getType();

        if (!$type instanceof RepeaterFieldType) {
            abort(404);
        }

        $type->setPrefix($this->request->get('prefix'));

        $builder = $type
            ->form($field, $this->request->get('instance'))
            ->addFormData('field_type', $type);

        /*
         * Build and render the markup only. Calling render()
         * would run make() -> post(), which saves the form.
         */
        $builder->build();

        dispatch_sync(new PopulateFields($builder));
        dispatch_sync(new LoadForm($builder));
        dispatch_sync(new MakeForm($builder));
        dispatch_sync(new SetFormResponse($builder));

        return $builder->getFormResponse();
    }
}
