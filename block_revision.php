<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Block revision is defined here.
 *
 * @package     block_revision
 * @copyright   2025 Your Name <you@example.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_revision extends block_base {

    /**
     * Initializes class member variables.
     */
    public function init() {
        // Needed by Moodle to differentiate between blocks.
        $this->title = get_string('pluginname', 'block_revision');
    }

    /**
     * Returns the block contents.
     *
     * @return stdClass The block contents.
     */
    public function get_content() {

         global $DB, $USER, $COURSE, $PAGE, $CGF, $OUTPUT;

        //$pageurl = $PAGE->url->out();

        $pageurl = $PAGE->url->get_path(true);  // true = leading slash
        if ($PAGE->url->get_query_string()) {
            $pageurl .= '?' . $PAGE->url->get_query_string();
        }


        if ($this->content !== null) {
            return $this->content;
        }

        if (empty($this->instance)) {
            $this->content = '';
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->items = [];
        $this->content->icons = [];
        $this->content->footer = '';

        if (!empty($this->config->text)) {
            $this->content->text = $this->config->text;
        } else {
            $text = $OUTPUT->render_from_template('block_revision/learningtracker',[]);
            $this->content->text = $text;
        }

        $PAGE->requires->js_call_amd('block_revision/init', 'init');
        return $this->content;
    }

    /**
     * Defines configuration data.
     *
     * The function is called immediately after init().
     */
    public function specialization() {

        // Load user defined title and make sure it's never empty.
        if (empty($this->config->title)) {
            $this->title = get_string('pluginname', 'block_revision');
        } else {
            $this->title = $this->config->title;
        }
    }

    /**
     * Sets the applicable formats for the block.
     *
     * @return string[] Array of pages and permissions.
     */
    public function applicable_formats() {
        return [
            '' => true,
        ];
    }

//    public function extend_navigation_course($navigation, $course, $context) {
//         $url = new moodle_url('/blocks/revision/view.php', ['courseid' => $course->id]);
//         $navigation->add(
//             get_string('viewrevisiondata', 'block_revision'),
//             $url,
//             navigation_node::TYPE_CUSTOM,
//             null,
//             null,
//             new pix_icon('i/report', '')
//         );
//     }

public function extend_navigation_course($navigation, $course, $context) {
    debugging('extend_navigation_course() called', DEBUG_DEVELOPER);
    global $PAGE;

    if (!has_capability('moodle/block:view', $context)) {
        return;
    }

    // Only show if inside a course page.
    if ($PAGE->context->contextlevel !== CONTEXT_COURSE) {
        return;
    }

    // Find the "courseadmin" node – the main parent of the "More" menu.
    if ($coursenode = $navigation->find('courseadmin', navigation_node::TYPE_COURSE)) {

        $url = new moodle_url('/blocks/revision/view.php', ['courseid' => $course->id]);

        $coursenode->add(
            get_string('viewrevisiondata', 'block_revision'),
            $url,
            navigation_node::TYPE_CUSTOM,
            null,
            'blockrevisionlink',
            new pix_icon('i/report', '')
        );
    }
}



}
