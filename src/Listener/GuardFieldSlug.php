<?php namespace Anomaly\RepeaterFieldType\Listener;

use Anomaly\RepeaterFieldType\RepeaterFieldType;
use Anomaly\RepeaterFieldType\Validation\ValidateSlug;
use Anomaly\Streams\Platform\Field\Form\FieldFormBuilder;
use Anomaly\Streams\Platform\Ui\Form\Event\FormWasBuilt;

/**
 * Class GuardFieldSlug
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class GuardFieldSlug
{

    /**
     * Add the slug rule to the field form.
     *
     * @param FormWasBuilt $event
     */
    public function handle(FormWasBuilt $event)
    {
        $builder = $event->getBuilder();

        if (!$builder instanceof FieldFormBuilder) {
            return;
        }

        if (!$this->isRepeater($builder)) {
            return;
        }

        if (!$slug = $builder->getFormField('slug')) {
            return;
        }

        $slug->mergeRules(['valid_repeater_slug']);

        $slug->mergeValidators(
            [
                'valid_repeater_slug' => [
                    'handler' => ValidateSlug::class,
                    'message' => 'anomaly.field_type.repeater::validation.slug_collision',
                ],
            ]
        );
    }

    /**
     * Return whether the form is creating or editing a repeater field.
     *
     * @param  FieldFormBuilder $builder
     * @return bool
     */
    protected function isRepeater(FieldFormBuilder $builder)
    {
        if ($builder->getFieldType() instanceof RepeaterFieldType) {
            return true;
        }

        $entry = $builder->getFormEntry();

        return $entry && $entry->type === 'anomaly.field_type.repeater';
    }
}
