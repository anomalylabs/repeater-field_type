<?php namespace Anomaly\RepeaterFieldType;

use Anomaly\Streams\Platform\Addon\FieldType\FieldType;
use Anomaly\Streams\Platform\Addon\FieldType\FieldTypeSchema;
use Anomaly\Streams\Platform\Assignment\Contract\AssignmentInterface;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class RepeaterFieldTypeSchema
 *
 * @link          http://pyrocms.com/
 * @author        PyroCMS, Inc. <support@pyrocms.com>
 * @author        Ryan Thompson <ryan@pyrocms.com>
 * @package       Anomaly\RepeaterFieldType
 */
class RepeaterFieldTypeSchema extends FieldTypeSchema
{

    /**
     * The pivot table's own columns.
     *
     * @var array
     */
    protected $signature = [
        'entry_id',
        'related_id',
        'sort_order',
    ];

    /**
     * Add the field type's pivot table.
     *
     * @param Blueprint $table
     * @param AssignmentInterface $assignment
     */
    public function addColumn(Blueprint $table, AssignmentInterface $assignment)
    {
        $table = $table->getTable() . '_' . $this->fieldType->getField();

        $this->guard($table);

        $this->schema->dropIfExists($table);

        $this->schema->create(
            $table,
            function (Blueprint $table) use ($assignment) {

                $table->increments('id');
                $table->integer('entry_id');
                $table->integer('related_id');
                $table->integer('sort_order')->nullable();

                $table->unique(
                    ['entry_id', 'related_id'],
                    md5($assignment->getId())
                );
            }
        );
    }

    /**
     * Rename the pivot table.
     *
     * @param Blueprint $table
     * @param FieldType $from
     */
    public function renameColumn(Blueprint $table, FieldType $from)
    {
        $to = $table->getTable() . '_' . $this->fieldType->getField();

        $this->guard($to);

        $this->schema->rename($table->getTable() . '_' . $from->getField(), $to);
    }

    /**
     * Drop the pivot table.
     *
     * @param Blueprint $table
     */
    public function dropColumn(Blueprint $table)
    {
        $table = $table->getTable() . '_' . $this->fieldType->getField();

        $this->guard($table);

        $this->schema->dropIfExists($table);
    }

    /**
     * Refuse to touch a table that is not a repeater pivot.
     *
     * @param string $table
     * @throws \RuntimeException
     */
    protected function guard($table)
    {
        if (!$this->schema->hasTable($table)) {
            return;
        }

        if ($this->schema->hasColumns($table, $this->signature)) {
            return;
        }

        throw new \RuntimeException(
            "The table [{$table}] already exists and is not a repeater pivot table. "
            . "Rename the [{$this->fieldType->getField()}] field to avoid the collision."
        );
    }
}
