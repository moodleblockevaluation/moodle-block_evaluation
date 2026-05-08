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

    Create a 'lang' and inside 'en' folder (lang/en), where 'en' is the English language file for your block. If you are not
    an English speaker, you can replace 'en' with your appropriate language code. All language files for blocks go under the
    /lang subfolder of the block's installation folder.

    Strings are defined via the associative array $string provided by the string file. The array key is the string identifier,
    the value is the string text in the given language. Moodle supports over 100 languages (en (english), fr(french) etc.,).
    en (English) is the default language.

    It is mandatory that any manual text must be written in language strings for Moodle to identify the language defined in
    lang folder.

*/
// phpcs:disable moodle.Files.LangFilesOrdering.UnexpectedComment
// phpcs:disable moodle.Files.LangFilesOrdering.IncorrectOrder

$string['eval_block'] = 'Overview of evaluations'; // Block header name.
$string['pluginname'] = 'Evaluation block'; // Name of your plugin.
$string['privacy_metadata'] = 'The Evaluation block only shows data stored in other locations.';

// Strings:access.
$string['eval_block_addinstance'] = 'Add a evaluation block';
$string['eval_block_my_addinstance'] = 'Add a evaluation block to my Moodle page';

// Strings:settings.
$string['help_settings_timeopen'] = 'Enter the start of the evaluation here.';
$string['frst_settings_timeopen'] = 'Start:';

$string['help_settings_timeclose'] = 'Enter the end of the evaluation here.';
$string['frst_settings_timeclose'] = 'End:';

$string['help_settings_namelike'] = 'The name of the evaluation must include this.';
$string['frst_settings_namelike'] = 'Contains:';

$string['help_infotext'] = 'Enter the text to be displayed above the table here.';
$string['frst_infotext'] = 'Infotext:';

$string['deanofstudies'] = 'Dean of studies';
$string['trainer'] = 'Trainer';
$string['participant'] = 'Participant';
$string['totalparticipants'] = 'Total participants';
$string['nofeedbacks'] = 'No feedbacks';

$string['access_denied'] = 'No permission to view.';

$string['faqurl'] = '<a href="{$a}" target=\'_blank\'>FAQ</a>';
$string['frst_faqurl'] = 'FAQ URL:';
$string['help_faqurl'] = 'URL to support/FAQ from evaluation block.';

$string['settings_deanrolename'] = '{$a}';
$string['frst_deanrolename'] = 'Dean of Studies Role';
$string['help_deanrolename'] = 'Name of the Role of Dean of studies';

$string['open'] = 'open';
$string['completed'] = 'completed';

// Strings:table header.
$string['tableheader_1'] = 'Module name';
$string['tableheader_2'] = 'All evaluations';
$string['tableheader_3'] = 'End';
$string['tableheader_4'] = 'Finished?';
