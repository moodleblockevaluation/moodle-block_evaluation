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
        global $CFG;
        global $OUTPUT;

        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();

        $userid = $USER->id;

        // Determine user status for display  STUD => Students; MA => Faculty.
        $status = $DB->get_field_sql("SELECT institution FROM {user} where id = '" . $userid . "'");

        $username = $USER->username;

        $settingsinfotext = get_config('block_evaluation', 'infotext');

        // Role=student.
        if ($status == 'STUD') {
            // Reading Block Settings.
            $settingstimeopen = get_config('block_evaluation', 'settings_timeopen');
            $settingstimeclose = get_config('block_evaluation', 'settings_timeclose');
            $settingsnamelike = get_config('block_evaluation', 'settings_namelike');

            $sql = 'SELECT f.id fid, m.id mid, f.course feedbackcourse, from_unixtime(timeopen) begin, ' .
            'From_unixtime(timeclose) end, f.name feedbackname, c.fullname coursename, k.path coursecategories

        ,(SELECT count(*) from {feedback_completed} WHERE userid = ' . $userid . ' and feedback=f.id) AS ausgefuellt

        FROM {feedback} f, {course} c, {course_categories} k, {course_modules} m, {modules} m2
        WHERE (timeopen >=UNIX_TIMESTAMP("' . $settingstimeopen . '") OR timeopen = \'0\')
        AND (timeclose <=UNIX_TIMESTAMP("' . $settingstimeclose . '") OR timeclose = \'0\')
        AND f.course = c.id
        AND c.category = k.id
        AND m2.name = \'feedback\'
        AND m.module = m2.id
        AND m.visible != 0
        AND m.course = f.course
        AND m.instance = f.id
        AND f.name like \'%' . $settingsnamelike . '%\'

AND c.id in (SELECT ic.id
FROM {course}           ic
JOIN {context}          con ON con.instanceid = ic.id
JOIN {role_assignments} ra  ON ra.contextid   = con.id    AND con.contextlevel = 50
JOIN {role}             r   ON ra.roleid      = r.id
JOIN {user}             u   ON u.id           = ra.userid
WHERE u.id = ' . $userid . '
  AND ic.id = ic.id
  AND ra.roleid = 5)

        ORDER BY c.fullname, f.name';

            $datasql = $DB->get_records_sql($sql);

            // Table.
            $tablehtml = "<table class=\"table table-bordered table-striped table-hover\"><thead><tr><th>" .
            get_string('tableheader_1', 'block_evaluation') . "<th>" .
            get_string('tableheader_2', 'block_evaluation') . "<th>" .
            get_string('tableheader_3', 'block_evaluation') . "<th>" .
            get_string('tableheader_4', 'block_evaluation') . "</th></tr></thead><tbody>";
            foreach ($datasql as $e) {
                $dateformat = DateTime::createFromFormat('Y-m-d H:i:s', $e->end)->format('d.m.y H:i');
                $feedbackurl = new moodle_url("/mod/feedback/view.php?id=" . $e->mid . "");
                $dataarr = [
                    $e->coursename,
                    "<a href = $feedbackurl target = _blank>$e->feedbackname</a>",
                    $dateformat,
                    $e->ausgefuellt,
                ];
                if ($dataarr[3] == 0) {
                    $tablehtml .= "<tr><td>$dataarr[0]<td>$dataarr[1]<td>$dataarr[2]" .
                        "<td align=\"center\"><img src=\"" . $CFG->wwwroot .
                        "/blocks/evaluation/pix/kreuz.png\" width=15></td></tr>";
                } else {
                    $tablehtml .= "<tr><td>$dataarr[0]<td>$dataarr[1]<td>$dataarr[2]" .
                        "<td align=\"center\"><img src=\"" . $CFG->wwwroot .
                        "/blocks/evaluation/pix/haken.png\" width=15></td></tr>";
                }
            };
            $tablehtml .= "</tbody></table></html>";

            // Print content in block.
            $this->content->text = $settingsinfotext;
            $this->content->text .= $tablehtml;
            $this->content->text .= "<a href='https://support.hs-nb.de/otrs/public.pl?Action=PublicFAQZoom;ItemID=78'" .
                " target='_blank'>FAQ</a>";
        } else if ($status == 'MA') {
            // SQL query of how many feedbacks are given in assigned courses for teachers.

            // Reading Block Settings.
            $settingstimeopen = get_config('block_evaluation', 'settings_timeopen');
            $settingstimeclose = get_config('block_evaluation', 'settings_timeclose');
            $settingsnamelike = get_config('block_evaluation', 'settings_namelike');

            $sql = 'SELECT f.id fid, m.id mid, f.course feedbackcourse, from_unixtime(timeopen) begin, ' .
                'From_unixtime(timeclose) end, f.name feedbackname, c.fullname coursename, k.path coursecategories

        ,(SELECT count(distinct(ra.userid)) Users FROM {role_assignments} ra
JOIN {context} ctx ON ra.contextid = ctx.id
JOIN {user_enrolments} ue on ue.userid = ra.userid
JOIN {enrol} e ON e.id = ue.enrolid
JOIN {user} u ON u.id = ue.userid
WHERE ra.roleid = 5 AND ctx.instanceid = c.id AND e.courseid = c.id
and ue.status = 0
and (ue.timeend = 0 or ue.timeend >= unix_timestamp())
and u.suspended = 0
        ) AS studentssum,

        (SELECT COUNT(*)

        FROM {feedback_completed} mfc

        WHERE mfc.feedback = f.id

        GROUP BY feedback) AS feedbacksum

        FROM {feedback} f, {course} c, {course_categories} k, {course_modules} m, {modules} m2

        WHERE (timeopen >=UNIX_TIMESTAMP("' . $settingstimeopen . '") OR timeopen = \'0\')

        AND (timeclose <=UNIX_TIMESTAMP("' . $settingstimeclose . '") OR timeclose = \'0\')

        AND f.course = c.id

        AND c.category = k.id

        AND m2.name = \'feedback\'

        AND m.module = m2.id

        AND m.course = f.course

        AND m.instance = f.id

        AND f.name like \'%' . $settingsnamelike . '%\'

    AND f.name like \'%' . $username . '%\'

        AND c.id IN (SELECT e.courseid FROM {user_enrolments} ue join {enrol} e ON e.id = ue.enrolid join {course} c on' .
            ' c.id = e.courseid where userid = ' . $userid . ')

        ORDER BY c.fullname, f.name';

            $datasql = $DB->get_records_sql($sql);

            // Table.
            $tablehtml = "<table class=\"table table-bordered table-striped table-hover\"><thead><tr><th>" .
            get_string('tableheader_1', 'block_evaluation') . "<th>" .
            get_string('tableheader_2', 'block_evaluation') . "<th>" .
            get_string('tableheader_3', 'block_evaluation') . "<th>Studenten insgesamt</th><th>" .
            get_string('tableheader_4', 'block_evaluation') . "</th></tr></thead><tbody>";
            foreach ($datasql as $e) {
                $dateformat = DateTime::createFromFormat('Y-m-d H:i:s', $e->end)->format('d.m.y H:i');
                $feedbackurl  = new moodle_url("/mod/feedback/view.php?id=" . $e->mid . "");
                $dataarr = [
                    $e->coursename,
                    "<a href = $feedbackurl target = _blank>$e->feedbackname</a>",
                    $dateformat,
                    $e->studentssum,
                    $e->feedbacksum,
                ];
                $tablehtml .= "<tr><td>$dataarr[0]<td>$dataarr[1]<td>$dataarr[2]<td>$dataarr[3]<td>$dataarr[4]</td></tr>";
            };
            $tablehtml .= "</tbody></table></html>";

            // Print content in block.
            $this->content->text = $settingsinfotext;
            $this->content->text .= $tablehtml;
            $this->content->text .= "<a href='https://support.hs-nb.de/otrs/public.pl?Action=PublicFAQZoom;ItemID=78' " .
                "target='_blank'>FAQ</a>";
        } else {
            echo get_string('access_denied', 'block_evaluation');
        }

        return $this->content;
    }
}
