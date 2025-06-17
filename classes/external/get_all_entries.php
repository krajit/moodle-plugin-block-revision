<?php
namespace block_revision\external;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/externallib.php');

use external_api;
use external_function_parameters;
use external_value;
use external_single_structure;
use external_multiple_structure;

class get_all_entries extends external_api {
    public static function execute_parameters() {
        return new external_function_parameters([
            'courseid' => new external_value(PARAM_INT, 'Course ID')
        ]);
    }

    public static function execute($courseid) {
        global $DB;

        $records = $DB->get_records('block_revision_data', ['courseid' => $courseid]);
        $users = [];

        foreach ($records as $r) {
            $user = \core_user::get_user($r->userid);
            $r->fullname = fullname($user);
        }

        return ['entries' => array_values($records)];
    }

    public static function execute_returns() {
        return new external_single_structure([
            'entries' => new external_multiple_structure(
                new external_single_structure([
                    'id' => new external_value(PARAM_INT),
                    'userid' => new external_value(PARAM_INT),
                    'fullname' => new external_value(PARAM_TEXT),
                    'pageurl' => new external_value(PARAM_TEXT),
                    'learninglevel' => new external_value(PARAM_TEXT),
                    'revisioncount' => new external_value(PARAM_INT),
                    'nextreview' => new external_value(PARAM_TEXT),
                ])
            )
        ]);
    }
}
