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
 * Library functions for overview.
 *
 * @package   block_evaluation
 * @copyright Neubrandenburg University of Applied Sciences <support_moodle@hs-nb.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
 * Checks if user is dean of studies
 *
 * @uses core_user::is_current_user
 * @param $rolle: the role of user, $path the path of the course category
 * @return bool boolean returning true or false
 */
function is_deanofstudies($rolle, $path): bool {
    global $DB;
    global $USER;
    $sql = "
    SELECT if(cc.id is null,false,true) state
    FROM {course_categories} cc
    INNER JOIN {context} cx ON cc.id = cx.instanceid AND cx.contextlevel = '40'
    INNER JOIN  {role_assignments} ra ON cx.id = ra.contextid
    INNER JOIN  {role} r ON ra.roleid = r.id
    WHERE ra.userid = :userid and r.shortname = :role and cc.path = :path
    ORDER BY cc.depth, cc.path";

    $records = $DB->get_records_sql(
        $sql,
        ['userid' => $USER->id,
                'role' => $rolle,
                'path' => $path]
    );
    foreach ($records as $rec) {
        return $rec->state;
    }
    return false;
}
