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
 * @copyright 2024, Michell Trebbin <lg21117@hs-nb.de>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Ensure the configurations for this site are set

defined('MOODLE_INTERNAL') || die();

#if ($hassiteconfig) {

    // Create the new settings page
    // - in a local plugin this is not defined as standard, so normal $settings->methods will throw an error as
    // $settings will be null
    #$ADMIN->add('localplugins', new admin_category('block_evaluation_settings', new lang_string('evalblock', 'block_evaluation')));
    #$settings = new admin_settingpage('block_evaluation', 'Evaluation Block');

    #if ($ADMIN->fulltree) {

      // Add a setting field to the settings for this page
      $settings->add(new admin_setting_configtext(
          // This is the reference you will use to your configuration
          'block_evaluation/settings_timeopen',

          // This is the friendly title for the config, which will be displayed
          get_string('FrSt_settings_timeopen', 'block_evaluation'),

          // This is helper text for this config field
          get_string('Help_settings_timeopen', 'block_evaluation'),

          // This is the default value
          '2000-01-01',

          // This is the type of Parameter this config is
          PARAM_TEXT
      ));
      
      $settings->add(new admin_setting_configtext(
          // This is the reference you will use to your configuration
          'block_evaluation/settings_timeclose',

          // This is the friendly title for the config, which will be displayed
          get_string('FrSt_settings_timeclose', 'block_evaluation'),

          // This is helper text for this config field
          get_string('Help_settings_timeclose', 'block_evaluation'),

          // This is the default value
          '2030-01-01',

          // This is the type of Parameter this config is
          PARAM_TEXT
      ));
      
      
      $settings->add(new admin_setting_configtext(
          // This is the reference you will use to your configuration
          'block_evaluation/settings_namelike',

          // This is the friendly title for the config, which will be displayed
          get_string('FrSt_settings_namelike', 'block_evaluation'),

          // This is helper text for this config field
          get_string('Help_settings_namelike', 'block_evaluation'),

          // This is the default value
          'evaluation',

          // This is the type of Parameter this config is
          PARAM_TEXT
      ));
      
      $settings->add(new admin_setting_configtext(
          // This is the reference you will use to your configuration
          'block_evaluation/infotext',

          // This is the friendly title for the config, which will be displayed
          get_string('FrSt_infotext', 'block_evaluation'),

          // This is helper text for this config field
          get_string('Help_infotext', 'block_evaluation'),

          // This is the default value
          '',

          // This is the type of Parameter this config is
          PARAM_TEXT
      ));
    #}
    
    #$setting->set_updatedcallback('theme_reset_all_caches');
    
    // Create
    #$ADMIN->add('localplugins', $settings);
#}