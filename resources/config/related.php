<?php

return [

    /*
     * Stream namespaces a repeater may not relate to.
     *
     * A repeater renders and saves entries of the related
     * stream through its parent form, which does not consult
     * the owning module's permissions. Repeaters are for
     * content, so the streams that carry accounts, settings
     * and platform internals are refused.
     */
    'protected' => [
        'users',
        'settings',
        'preferences',
        'configuration',
        'streams_utilities',
    ],
];
