<?php
require_once(__DIR__ . '/../../config.php');
require_login();

$action = required_param('action', PARAM_ALPHA);
$userid = required_param('userid', PARAM_INT);
require_sesskey();

$record = $DB->get_record('your_table', ['userid' => $userid], '*', MUST_EXIST);

switch ($action) {
    case 'update_learning_level':
        $level = required_param('level', PARAM_ALPHA);
        $record->learninglevel = $level;
        $DB->update_record('your_table', $record);
        break;

    case 'update_revision':
        $revision = required_param('revision', PARAM_INT);
        $record->revisioncount = $revision;
        $DB->update_record('your_table', $record);
        break;

    case 'update_review_date':
        $nextreview = required_param('nextreview', PARAM_TEXT); // format: YYYY-MM-DD
        $record->nextreview = $nextreview;
        $DB->update_record('your_table', $record);
        break;
}

echo json_encode(['status' => 'ok']);
die;
