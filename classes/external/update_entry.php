<?php
namespace block_revision\external;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/externallib.php');

use external_api;
use external_function_parameters;
use external_value;
use external_single_structure;

class update_entry extends external_api {
    public static function execute_parameters() {
        return new external_function_parameters([
            'id' => new external_value(PARAM_INT),
            'field' => new external_value(PARAM_TEXT),
            'value' => new external_value(PARAM_RAW)
        ]);
    }

    public static function execute($id, $field, $value) {
        global $DB;

        $allowed = ['learninglevel', 'revisioncount', 'nextreview'];
        if (!in_array($field, $allowed)) {
            throw new \invalid_parameter_exception("Field not allowed.");
        }

        $DB->set_field('block_revision_entries', $field, $value, ['id' => $id]);

        return ['status' => 'success'];
    }

    public static function execute_returns() {
        return new external_single_structure([
            'status' => new external_value(PARAM_TEXT)
        ]);
    }
}
