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
$string['evalBlock'] = 'Auflistung der Evaluationen'; // Block header name.


// Strings:access.
$string['evalBlock:addinstance'] = 'Eine Auflistung der Evaluationen als Block hinzufügen';
$string['evalBlock:myaddinstance'] = 'Eine Auflistung der Evaluationen als Block auf meiner Moodle Seite hinzufügen';

// Strings:settings.
$string['FrSt_settings_timeopen'] = 'Beginn:';
$string['Help_settings_timeopen'] = 'Hier den Beginn der Evaluation in dem Format YYYY-MM-DD angeben.';

$string['FrSt_settings_timeclose'] = 'Ende:';
$string['Help_settings_timeclose'] = 'Hier das Ende der Evaluation in dem Format YYYY-MM-DD angeben.';

$string['FrSt_settings_namelike'] = 'Beinhaltet:';
$string['Help_settings_namelike'] = 'Der Name der Evaluation muss dies beinhalten.';

$string['FrSt_infotext'] = 'Infotext:';
$string['Help_infotext'] = 'Hier den Text eintragen, der oberhalb der Tabelle angezeigt werden soll.';

// Strings:table header.
$string['tableheader_1'] = 'Modulname';
$string['tableheader_2'] = 'Alle Evaluationen';
$string['tableheader_3'] = 'Ende';
$string['tableheader_4'] = 'Erledigt?';