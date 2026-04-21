<?php
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

$string['pluginname'] = 'Evaluation Block'; // Name of your plugin.
$string['evalBlock'] = 'Overview of evaluations'; // Block header name.


// Strings:access.
$string['evalBlock:addinstance'] = 'Add a evaluation block';
$string['evalBlock:myaddinstance'] = 'Add a evaluation block to my Moodle page';

// Strings:settings.
$string['FrSt_settings_timeopen'] = 'Start:';
$string['Help_settings_timeopen'] = 'Enter the start of the evaluation here in the format YYYY-MM-DD.';

$string['FrSt_settings_timeclose'] = 'End:';
$string['Help_settings_timeclose'] = 'Enter the end of the evaluation here in the format YYYY-MM-DD.';

$string['FrSt_settings_namelike'] = 'Contains:';
$string['Help_settings_namelike'] = 'The name of the evaluation must include this.';

$string['FrSt_infotext'] = 'Infotext:';
$string['Help_infotext'] = 'Enter the text to be displayed above the table here.';

// Strings:table header.
$string['tableheader_1'] = 'Module name';
$string['tableheader_2'] = 'All evaluations';
$string['tableheader_3'] = 'End';
$string['tableheader_4'] = 'Finished?';

