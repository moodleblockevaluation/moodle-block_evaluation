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
 * Version information for the block_evaluation plugin.
 *
 * @package   block_evaluation
 * @copyright Hochschule Neubrandenburg <support_moodle@hs-nb.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/*  DOCUMENTATION
    .............

    version.php defines the version of your block which allows to make sure your block plugin is compatible with the given
    Moodle site, as well as spotting whether an upgrade is needed.

    defined('MOODLE_INTERNAL') || die();
    It's a basic check if a php file should not be loaded on it's own (class files etc). It is best to use this line in
    every moodle page.

    $plugin enable you to add additional features and functionality to the Moodle core.

    $plugin->version:
    The version number of the plugin. The format is partially date based with the format, YYYYMMDDXX 
    (Year Month Day 24-hr time) where 24-hr time can be from 1 to 99. A new plugin version must have this number increased
    in this file, which is detected by the Moodle core and the upgrade process is triggered.
    ex: plugin->version = 2022122700; // Plugin released on 27th December 2022.

    $plugin->requires:
    Specifies the minimum version number of the Moodle core that your plugin requires. It is only possible to install version
    4.1, unless you have 3.9 or later. Moodle core's version number is defined in the file version.php located in Moodle
    root directory, in the $version variable.
    ex: $plugin->requires = 2019111200; // Moodle 3.8 is required.

    $plugin->component:
    The frankenstyle component name in the form of plugintype_pluginname. It is used during the installation and upgrade
    process for diagnostics and validation purpose to make sure the component is a block or a module or a course or a local
    component.
*/

defined('MOODLE_INTERNAL') || die();

$plugin->version = 2024012501;  // YYYYMMDDHH (Year Month Day 24-hr time).
$plugin->requires = 2022041200; // YYYYMMDDHH (The release version of Moodle 4.1).
$plugin->component = 'block_evaluation'; // Name of your plugin (used for diagnostics).
