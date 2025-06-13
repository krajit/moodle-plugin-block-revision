<?php
require_once(__DIR__ . '/../../config.php');
require_login();

$action = required_param('action', PARAM_ALPHA);
$userid = required_param('userid', PARAM_INT);
$pageurl = required_param('pageurl', PARAM_TEXT);
require_sesskey();

$record = $DB->get_record('block_plugin_tracker', [
    'userid' => $userid,
    'pageurl' => $pageurl
]);

if (!$record) {
    $record = new stdClass();
    $record->userid = $userid;
    $record->pageurl = $pageurl;
    $record->learninglevel = null;
    $record->revisioncount = 0;
    $record->nextreview = null;
    $record->timemodified = time();
    $record->id = $DB->insert_record('block_plugin_tracker', $record);
}

switch ($action) {
    case 'update_learning_level':
        $level = required_param('level', PARAM_ALPHA);
        $record->learninglevel = $level;
        $record->timemodified = time();
        $DB->update_record('block_plugin_tracker', $record);
        break;

    case 'update_revision':
        $revision = required_param('revision', PARAM_INT);
        $record->revisioncount = $revision;
        $record->timemodified = time();
        $DB->update_record('block_plugin_tracker', $record);
        break;

    case 'update_review_date':
        $nextreview = required_param('nextreview', PARAM_TEXT);
        $record->nextreview = $nextreview;
        $record->timemodified = time();
        $DB->update_record('block_plugin_tracker', $record);
        break;
}

echo json_encode(['status' => 'ok']);
die;
