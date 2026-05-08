<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Evaluation block capabilities.
 *
 * @package    block_evaluation
 * @copyright  Neubrandenburg University of Applied Sciences <support_moodle@hs-nb.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/*  DOCUMENTATION
    .............

    The actual display of your block is block_evaluation.php

    init() method is essential part to pass the class variables:
    $this->title: to display the title in the header of your block.
    $this->version (optional unless you need Moodle to perform automatic updates) and there is no return value to be expected
    from init().

    $CFG stands for Configuration. CFG is a global variable can be used in any moodle page, contains Moodle's
    root, data(moodledata) and database configuration settings and other config values.

    get_string converts an array of string names to localised strings for a specific plugin. It looks formal when you code
    with language strings instead of manual text. It's a good habit of writing manual text to strings.

    has_config() method states that the block has a settings.php file. This method specifies whether your block wants to
    present additional configuration settings.

    get_content method should define $this->content variable of your block.
    If $this->content_type is BLOCK_TYPE_TEXT, then $this->content is expected to have the following member variables:
    text - a string of arbitrary length and content displayed inside the main area of the block, and can contain HTML.
    footer - a string of arbitrary length and content displayed below the text, using a smaller font size.
    It can also contain HTML.

    instance_allow_multiple() method indicates whether you want to allow multiple block instances in the same page or not.
    If you do allow multiple instances, it is assumed that you will also be providing per-instance configuration for the
    block.

*/

use block_evaluation\helper;
use core\output\html_writer;

/**
 * Evaluation block main class.
 *
 * @package    block_evaluation
 * @copyright  Neubrandenburg University of Applied Sciences <support_moodle@hs-nb.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class block_evaluation extends block_base {
    /**
     * The initiation method.
     *
     * @return void
     * @throws coding_exception
     */
    public function init() {
        global $CFG;
        $this->title = get_string('eval_block', 'block_evaluation');
    }

    #[\Override]
    public function has_config() {
        return true;
    }

    #[\Override]
    public function get_content() {
        global $DB;
        global $USER;
        global $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $output = '';

        // Reading Block Settings.
        $settingsinfotext = get_config('block_evaluation', 'infotext');
        $settingstimeopen = get_config('block_evaluation', 'settings_timeopen');
        $settingstimeclose = get_config('block_evaluation', 'settings_timeclose');
        $settingsnamelike = "%" . get_config('block_evaluation', 'settings_namelike') . "%";
        $faqlink = '';
        $faqurl = get_config('block_evaluation', 'faqurl');
        if ($faqurl !== '') {
            $faqlink = get_string('faqurl', 'block_evaluation', $faqurl);
        }
        $deanrole = '';
        $settingsdeanrolename = get_config('block_evaluation', 'settings_deanrolename');
        if ($settingsdeanrolename !== '') {
            $deanrole = get_string('settings_deanrolename', 'block_evaluation', $settingsdeanrolename);
        }

        if (is_siteadmin()) {
            $output .= get_string('access_denied', 'block_evaluation');
            $this->content->text = $output;
            return $this->content;
        } else {
            if ($settingsinfotext !== '') {
                $output .= html_writer::tag('p', $settingsinfotext);
            }
        }

        // 1. LOAD ALL FEEDBACK (centrally)
        // Only during the evaluation period specified in the configuration and matching the name pattern.
        $sql = "
            SELECT f.id AS feedbackid,
                   f.name AS feedbackname,
                   c.id AS courseid,
                   c.fullname AS coursename,
                   cm.id AS cmid,
                   f.timeopen,
                   f.timeclose,
                   cc.path,

                   -- Status (for students)
                   fc.id AS completedid,

                   -- Number of answers
                   (
                       SELECT COUNT(fc2.id)
                       FROM {feedback_completed} fc2
                       WHERE fc2.feedback = f.id
                   ) AS responsecount

            FROM {feedback} f
            JOIN {course} c ON c.id = f.course
            JOIN {modules} m ON m.name = 'feedback'
            JOIN {course_categories} cc ON cc.id = c.category
            JOIN {course_modules} cm ON cm.instance = f.id AND cm.module = m.id
            LEFT JOIN {feedback_completed} fc ON fc.feedback = f.id AND fc.userid = :userid
            WHERE f.timeopen >= :fopen and f.timeclose <= :fclose and f.name like :fname
            ORDER BY c.fullname, f.name
        ";

        $records = $DB->get_records_sql(
            $sql,
            ['userid' => $USER->id,
            'fopen' => $settingstimeopen,
            'fclose' => $settingstimeclose,
            'fname' => $settingsnamelike]
        );

        $showstudentoutput = false;
        $showteacheroutput = false;
        $showdeanofstudiesoutput = false;

        // Arrays for separate display.
        $studentoutput = "<table class=\"table table-striped table-hover\"><thead><tr><th>" .
            get_string('tableheader_1', 'block_evaluation') . "</th><th>" .
            get_string('tableheader_2', 'block_evaluation') . "</th><th>" .
            get_string('tableheader_3', 'block_evaluation') . "</th><th>" .
            get_string('tableheader_4', 'block_evaluation') . "</th></tr></thead><tbody>";

        $teacheroutput = "<table class=\"table table-striped table-hover\"><thead><tr><th>" .
            get_string('tableheader_1', 'block_evaluation') . "</th><th>" .
            get_string('tableheader_2', 'block_evaluation') . "</th><th>" .
            get_string('tableheader_3', 'block_evaluation') . "</th><th>" .
            get_string('totalparticipants', 'block_evaluation') . "</th><th>" .
            get_string('tableheader_4', 'block_evaluation') . "</th></tr></thead><tbody>";

        $deanofstudiesoutput = "<table class=\"table table-striped table-hover\"><thead><tr><th>" .
            get_string('tableheader_1', 'block_evaluation') . "</th><th>" .
            get_string('tableheader_2', 'block_evaluation') . "</th><th>" .
            get_string('tableheader_3', 'block_evaluation') . "</th><th>" .
            get_string('totalparticipants', 'block_evaluation') . "</th><th>" .
            get_string('tableheader_4', 'block_evaluation') . "</th></tr></thead><tbody>";

        foreach ($records as $rec) {
            $context = context_course::instance($rec->courseid);
            $url = new moodle_url('/mod/feedback/view.php', ['id' => $rec->cmid]);
            $link = html_writer::link($url, format_string($rec->feedbackname));
            // TEACHERS.
            if (has_capability('mod/feedback:viewreports', $context)) {
                // Determine the number of students in the course.
                $participants = count_enrolled_users($context, 'mod/feedback:complete', 0, true);
                // Teacher: show only your own.
                if (str_contains($rec->feedbackname, $USER->username)) {
                    $showteacheroutput = true;
                    $teacheroutput .= "<tr><td>" . format_string($rec->coursename) . "</td><td>" .
                    $link . "</td><td>" . userdate($rec->timeclose) . "</td><td>" . $participants .
                    "</td><td>" . $rec->responsecount . "</td></tr>";
                }
            }
            // DEAN OF STUDIES.
            // Determine the number of students in the course.
            $participants = count_enrolled_users($context, 'mod/feedback:complete', 0, true);
            // Teacher: show only your own.
            if (helper::is_deanofstudies($deanrole, $rec->path)) {
                $showdeanofstudiesoutput = true;
                $deanofstudiesoutput .= "<tr><td>" . format_string($rec->coursename) . "</td><td>" .
                $link . "</td><td>" . userdate($rec->timeclose) . "</td><td>" . $participants .
                "</td><td>" . $rec->responsecount . "</td></tr>";
            }
            // STUDENTS.
            if (has_capability('mod/feedback:complete', $context)) {
                // User enrolled in course?
                if (is_enrolled($context, $USER, 'mod/feedback:complete', true)) {
                    $showstudentoutput = true;
                    if (!empty($rec->completedid)) {
                        $status = get_string('completed', 'block_evaluation');
                        $icon = 't/check';
                        $iconclass = 'text-success';
                    } else {
                        $status = get_string('open', 'block_evaluation');
                        $icon = 't/completion_fail';
                        $iconclass = 'text-danger';
                    }
                    $studentoutput .= "<tr><td>" . format_string($rec->coursename) . "</td><td>" .
                    $link . "</td><td>" . userdate($rec->timeclose) . "</td><td>" .
                    $OUTPUT->action_icon(
                        $url,
                        new \pix_icon($icon, $status, '', ['class' => 'iconsmall ' . $iconclass]),
                        null,
                        ['title' => $status, 'class' => '']
                    )
                    . "</td></tr>";
                }
            }
        }
        // End of display dean of studies.
        if ($showdeanofstudiesoutput) {
            $output .= html_writer::tag('h4', get_string('deanofstudies', 'block_evaluation'), ['class' => 'h5']);
            $output .= $deanofstudiesoutput;
            $output .= "</tbody></table>";
        }
        // End of display teachers.
        if ($showteacheroutput) {
            $output .= html_writer::tag('h4', get_string('trainer', 'block_evaluation'), ['class' => 'h5']);
            $output .= $teacheroutput;
            $output .= "</tbody></table>";
        }
        // End of display students.
        if ($showstudentoutput) {
            $output .= html_writer::tag('h4', get_string('participant', 'block_evaluation'), ['class' => 'h5']);
            $output .= $studentoutput;
            $output .= "</tbody></table>";
        }

        if (!$showteacheroutput && !$showstudentoutput && !$showdeanofstudiesoutput) {
            $output .= html_writer::tag('p', get_string('nofeedbacks', 'block_evaluation'));
        }

        if ($faqlink !== '') {
            $output .= html_writer::tag('p', $faqlink);
        }

        $this->content->text = $output;
        return $this->content;
    }

    #[\Override]
    public function applicable_formats() {
        return [
            'all' => true,
        ];
    }
}
