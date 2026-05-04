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
 * Plugin settings for the block_evaluation plugin.
 *
 * @package   block_evaluation
 * @copyright Hochschule Neubrandenburg <support_moodle@hs-nb.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


$settings->add(new admin_setting_configtext('block_evaluation/settings_timeopen', \
 get_string('FrSt_settings_timeopen', 'block_evaluation'), \
 get_string('Help_settings_timeopen', 'block_evaluation'), \
 '2000-01-01', PARAM_TEXT));

$settings->add(new admin_setting_configtext('block_evaluation/settings_timeclose', \
 get_string('FrSt_settings_timeclose', 'block_evaluation'), \
 get_string('Help_settings_timeclose', 'block_evaluation'), \
 '2030-01-01', PARAM_TEXT));

$settings->add(new admin_setting_configtext('block_evaluation/settings_namelike', \
 get_string('FrSt_settings_namelike', 'block_evaluation'), \
 get_string('Help_settings_namelike', 'block_evaluation'), \
 'evaluation', PARAM_TEXT));

$settings->add(new admin_setting_configtext('block_evaluation/infotext', \
 get_string('FrSt_infotext', 'block_evaluation'), \
 get_string('Help_infotext', 'block_evaluation'), \
 '', PARAM_TEXT));
