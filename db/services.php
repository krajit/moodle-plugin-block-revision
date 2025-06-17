<?php

$functions = [
    'block_revision_save_entry' => [
        'classname' => 'block_revision\external\save_entry',
        'methodname' => 'execute',
        'classpath' => '',
        'description' => 'Saves revision form data',
        'type' => 'write',
        'ajax' => true,
        'loginrequired' => true,
    ],
    'block_revision_get_entry' => [
        'classname' => 'block_revision\external\get_entry',
        'methodname' => 'execute',
        'classpath' => '', // Not needed in Moodle 3.9+
        'description' => 'Get existing revision data for a page',
        'type' => 'read',
        'ajax' => true,
        'capabilities' => ''
    ],
];
