<?php

use Anomaly\Streams\Platform\Stream\Contract\StreamInterface;
use Anomaly\Streams\Platform\Stream\Contract\StreamRepositoryInterface;

return [
    'related' => [
        'type'     => 'anomaly.field_type.select',
        'required' => true,
        'config'   => [
            'options' => function (StreamRepositoryInterface $streams) {

                $options = $streams->findAllByNamespace('repeater')->reduce(
                    /* @var StreamInterface as $stream */
                    function ($acc, StreamInterface $stream) {
                        $acc[$stream->getEntryModelName()] = $stream->getName();
                        return $acc;
                    },
                    []
                );

                ksort($options);

                return $options;
            },
        ],
    ],
    'add_row' => [
        'type' => 'anomaly.field_type.text',
    ],
    'min'     => [
        'type'   => 'anomaly.field_type.integer',
        'config' => [
            'min' => 1,
        ],
    ],
    'max'     => [
        'type'   => 'anomaly.field_type.integer',
        'config' => [
            'min' => 1,
        ],
    ],
];
