<?php namespace Anomaly\RepeaterFieldType\Command;

use Anomaly\RepeaterFieldType\RepeaterFieldType;
use Anomaly\Streams\Platform\Field\Contract\FieldInterface;
use Anomaly\Streams\Platform\Field\Contract\FieldRepositoryInterface;
use Anomaly\Streams\Platform\Ui\Form\Multiple\MultipleFormBuilder;
use Illuminate\Http\Request;

/**
 * Class GetMultiformFromPost
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class GetMultiformFromPost
{

    /**
     * The field type instance.
     *
     * @var RepeaterFieldType
     */
    protected $fieldType;

    /**
     * Create a new GetMultiformFromPost instance.
     *
     * @param RepeaterFieldType $fieldType
     */
    public function __construct(RepeaterFieldType $fieldType)
    {
        $this->fieldType = $fieldType;
    }

    /**
     * Handle the command.
     *
     * @param FieldRepositoryInterface $fields
     * @param MultipleFormBuilder      $forms
     * @param Request                  $request
     * @return MultipleFormBuilder|null
     */
    public function handle(FieldRepositoryInterface $fields, MultipleFormBuilder $forms, Request $request)
    {
        if (!$request->has($this->fieldType->getFieldName())) {
            return null;
        }

        /* @var FieldInterface $field */
        if (!$field = $fields->find($this->fieldType->id())) {
            return null;
        }

        $attached = $this->attached();

        foreach ((array)$request->get($this->fieldType->getFieldName()) as $item) {

            $entry    = array_get((array)$item, 'entry');
            $instance = array_get((array)$item, 'instance');

            /*
             * Only rows already attached to this entry may
             * be posted back. Anything else is a row belonging
             * to another entry, or one that does not exist.
             */
            if ($entry && !in_array($entry, $attached)) {
                continue;
            }

            /* @var RepeaterFieldType $type */
            $type = $field->getType();

            $type->setPrefix($this->fieldType->getPrefix());

            $form = $type->form($field, $instance);

            if ($entry) {
                $form->setEntry($entry);
            }

            $form->build();

            $form->setReadOnly($this->fieldType->isReadOnly());

            $forms->addForm($this->fieldType->getFieldName() . '_' . $instance, $form);
        }

        $forms->setOption('success_message', false);

        return $forms;
    }

    /**
     * Return the related IDs already attached to the entry.
     *
     * @return array
     */
    protected function attached()
    {
        $entry = $this->fieldType->getEntry();

        if (!$entry || !$entry->getId()) {
            return [];
        }

        return $this->fieldType
            ->getRelation()
            ->pluck('related_id')
            ->all();
    }
}
