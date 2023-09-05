<?php namespace Anomaly\RepeaterFieldType\Command;

use Anomaly\RepeaterFieldType\RepeaterFieldType;
use Anomaly\Streams\Platform\Entry\EntryCollection;
use Anomaly\Streams\Platform\Ui\Form\Multiple\MultipleFormBuilder;
use Illuminate\Support\Collection;

/**
 * Class GetMultiformFromValue
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class GetMultiformFromValue
{
    /**
     * The field type instance.
     *
     * @var RepeaterFieldType
     */
    protected $fieldType;

    /**
     * Create a new GetMultiformFromValue instance.
     *
     * @param RepeaterFieldType $fieldType
     */
    public function __construct(RepeaterFieldType $fieldType)
    {
        $this->fieldType = $fieldType;
    }

    /**
     * Get the multiple form builder from the value.
     *
     * @return MultipleFormBuilder|null
     */
    public function handle()
    {
        /* @var EntryCollection $value */
        if (!$value = $this->fieldType->getValue()) {
            return null;
        }

        if (is_array($value)) {
            return dispatch_sync(new GetMultiformFromData($this->fieldType));
        }

        if ($value instanceof Collection) {
            return dispatch_sync(new GetMultiformFromRelation($this->fieldType));
        }

        return null;
    }
}
