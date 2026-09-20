<?php namespace Anomaly\RepeaterFieldType\Validation;

use Anomaly\Streams\Platform\Field\Form\FieldFormBuilder;
use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;
use Anomaly\Streams\Platform\Stream\Contract\StreamRepositoryInterface;
use Illuminate\Database\DatabaseManager;

/**
 * Class ValidateSlug
 *
 * @link   http://pyrocms.com/
 * @author PyroCMS, Inc. <support@pyrocms.com>
 * @author Ryan Thompson <ryan@pyrocms.com>
 */
class ValidateSlug
{

    /**
     * Handle the validation.
     *
     * A repeater's pivot table is named after the stream
     * table and the field slug, so a slug that resolves to
     * an existing table would replace it.
     *
     * @param  string $value
     * @param  FieldFormBuilder $builder
     * @param  StreamRepositoryInterface $streams
     * @param  DatabaseManager $database
     * @return bool
     */
    public function handle(
        $value,
        FieldFormBuilder $builder,
        StreamRepositoryInterface $streams,
        DatabaseManager $database
    ) {
        $schema = $database->connection()->getSchemaBuilder();

        foreach ($this->targets($builder, $streams) as $stream) {

            $table = $stream->getEntryTableName() . '_' . $value;

            if (!$schema->hasTable($table)) {
                continue;
            }

            if ($schema->hasColumns($table, ['entry_id', 'related_id', 'sort_order'])) {
                continue;
            }

            return false;
        }

        return true;
    }

    /**
     * Return the streams the field could be assigned to.
     *
     * @param  FieldFormBuilder $builder
     * @param  StreamRepositoryInterface $streams
     * @return StreamInterface[]
     */
    protected function targets(FieldFormBuilder $builder, StreamRepositoryInterface $streams)
    {
        if ($stream = $builder->getStream()) {
            return [$stream];
        }

        if ($namespace = $builder->getNamespace()) {
            return $streams->findAllByNamespace($namespace)->all();
        }

        return [];
    }
}
