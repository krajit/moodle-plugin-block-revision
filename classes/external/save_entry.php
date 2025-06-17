<?php
namespace block_revision\external;

defined('MOODLE_INTERNAL') || die();
require_once($CFG->libdir . '/externallib.php');

use external_function_parameters;
use external_value;
use external_single_structure;
use external_api;

class save_entry extends external_api {

    public static function execute_parameters() {
        return new external_function_parameters([
            'learninglevel' => new external_value(PARAM_TEXT, 'Learning level'),
            'revisioncount' => new external_value(PARAM_INT, 'Revision count'),
            'nextreview' => new external_value(PARAM_TEXT, 'Next review date (Y-m-d)'),
            'pageurl' => new external_value(PARAM_RAW, 'Page URL'),
        ]);
    }

    public static function execute($learninglevel, $revisioncount, $nextreview, $pageurl) {
        error_log("DEBUG: save_entry called with $learninglevel, $revisioncount, $nextreview, $pageurl");

        global $USER, $DB;

        require_login();

        self::validate_parameters(self::execute_parameters(), [
            'learninglevel' => $learninglevel,
            'revisioncount' => $revisioncount,
            'nextreview' => $nextreview,
            'pageurl' => $pageurl
        ]);

        $timestamp = strtotime($nextreview);

        $existing = $DB->get_record('block_revision_entries', ['userid' => $USER->id, 'pageurl' => $pageurl]);

        $record = new \stdClass();
        $record->userid = $USER->id;
        $record->learninglevel = $learninglevel;
        $record->revisioncount = $revisioncount;
        $record->nextreview = $timestamp;
        $record->pageurl = $pageurl;
        $record->timemodified = time();

        if ($existing) {
            $record->id = $existing->id;
            $DB->update_record('block_revision_entries', $record);
        } else {
            $DB->insert_record('block_revision_entries', $record);
        }

        return ['status' => 'saved'];
    }

    public static function execute_returns() {
        return new external_single_structure([
            'status' => new external_value(PARAM_TEXT, 'Status')
        ]);
    }
}
