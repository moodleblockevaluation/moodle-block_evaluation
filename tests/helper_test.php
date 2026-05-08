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

namespace block_evaluation;

/**
 * Tests for Evaluation block
 *
 * @package    block_evaluation
 * @category   test
 * @copyright  Neubrandenburg University of Applied Sciences <support_moodle@hs-nb.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class helper_test extends \advanced_testcase {
    /**
     * Tests that the user is a dean of studies in a certain course category.
     *
     * @covers \block_evaluation\helper::is_deanofstudies
     */
    public function test_is_deanofstudies(): void {
        global $DB;
        $this->resetAfterTest();
        $generator = $this->getDataGenerator();
        // Create a course category.
        $category = $generator->create_category();
        // Create a dean role based on the manager role.
        $roleattr = ['name' => 'Dean', 'shortname' => 'dean', 'archetype' => 'manager'];
        $roleid = $generator->create_role($roleattr);

        // Create a user and assign the dean role to that user in the course category.
        $user = $generator->create_user();
        $categorycontext = \context_coursecat::instance($category->id);
        role_assign($roleid, $user->id, $categorycontext->id);

        $roleshortname = $DB->get_field('role', 'shortname', ['id' => $roleid]);
        $categorypath = $DB->get_field('course_categories', 'path', ['id' => $category->id]);
        $this->setUser($user);
        $this->assertTrue(helper::is_deanofstudies($roleshortname, $categorypath));
    }
}
