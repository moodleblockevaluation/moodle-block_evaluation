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

// Let codechecker ignore some sniffs for this file as it is perfectly well ordered, just not alphabetically.
// phpcs:disable moodle.Files.LangFilesOrdering.UnexpectedComment
// phpcs:disable moodle.Files.LangFilesOrdering.IncorrectOrder

$string['evalBlock'] = 'Auflistung der Evaluationen'; // Block header name.
$string['pluginname'] = 'Evaluation Block'; // Name of your plugin.
$string['privacy:metadata'] = 'The quiz archive plugin does not store any personal data about any user.';


// Strings:access.
$string['FrSt_infotext'] = 'Infotext:';
$string['FrSt_settings_namelike'] = 'Beinhaltet:';
$string['FrSt_settings_timeclose'] = 'Ende:';
$string['FrSt_settings_timeopen'] = 'Beginn:';
$string['Help_infotext'] = 'Hier den Text eintragen, der oberhalb der Tabelle angezeigt werden soll.';
$string['Help_settings_namelike'] = 'Der Name der Evaluation muss dies beinhalten.';

$string['Help_settings_timeclose'] = 'Hier das Ende der Evaluation in dem Format YYYY-MM-DD angeben.';

$string['Help_settings_timeopen'] = 'Hier den Beginn der Evaluation in dem Format YYYY-MM-DD angeben.';

$string['evalBlock:addinstance'] = 'Eine Auflistung der Evaluationen als Block hinzufuegen';

$string['evalBlock:myaddinstance'] = 'Eine Auflistung der Evaluationen als Block auf meiner Moodle Seite hinzufuegen';

$string['access_denied'] = 'Keine Berechtigung zur Ansicht';

// Strings:table header.
$string['tableheader_1'] = 'Modulname';
$string['tableheader_2'] = 'Alle Evaluationen';
$string['tableheader_3'] = 'Ende';
$string['tableheader_4'] = 'Erledigt?';
