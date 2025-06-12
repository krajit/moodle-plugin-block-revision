<?php

namespace block_revision\output;

defined('MOODLE_INTERNAL') || die();

use plugin_renderer_base;

class renderer extends plugin_renderer_base {
    public function render_learning_tracking_form(): string {
        global $DB, $USER, $COURSE, $PAGE, $CGF;

        $data = [];
        return $this->render_from_template('block_revision/learningTracker', $data);
    }
}