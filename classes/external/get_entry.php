<?php
namespace block_revision\external;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/externallib.php');

use external_api;
use external_function_parameters;
use external_value;
use external_single_structure;

class get_entry extends external_api {
    public static function execute_parameters() {
        return new external_function_parameters([
            'pageurl' => new external_value(PARAM_RAW, 'Current page URL')
        ]);
    }

    public static function execute($pageurl) {
        global $USER, $DB;

        self::validate_parameters(self::execute_parameters(), ['pageurl' => $pageurl]);

        $record = $DB->get_record('block_revision_entries', ['userid' => $USER->id, 'pageurl' => $pageurl]);

        if (!$record) {
            return [
                'learninglevel' => '',
                'revisioncount' => 0,
                'nextreview' => ''
            ];
        }

        return [
            'learninglevel' => $record->learninglevel,
            'revisioncount' => (int)$record->revisioncount,
            'nextreview' => date('Y-m-d', $record->nextreview)
        ];
    }

    public static function execute_returns() {
        return new external_single_structure([
            'learninglevel' => new external_value(PARAM_TEXT, 'Learning level'),
            'revisioncount' => new external_value(PARAM_INT, 'Revision count'),
            'nextreview' => new external_value(PARAM_TEXT, 'Next review date')
        ]);
    }
}
