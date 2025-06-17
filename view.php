<?php
require('../../config.php');
require_login();

$courseid = required_param('courseid', PARAM_INT);
$context = context_course::instance($courseid);

require_capability('moodle/course:view', $context);

$PAGE->set_url(new moodle_url('/blocks/revision/view.php', ['courseid' => $courseid]));
$PAGE->set_context($context);
$PAGE->set_pagelayout('report');
$PAGE->set_title(get_string('viewrevisiondata', 'block_revision'));
$PAGE->set_heading(get_string('viewrevisiondata', 'block_revision'));

echo $OUTPUT->header();

// Load JS module for interactivity
$PAGE->requires->js_call_amd('block_revision/edit_table', 'init', []);

echo '<div id="revision-table-wrapper"></div>';

echo $OUTPUT->footer();
