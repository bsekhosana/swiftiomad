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
 *
 * @package   theme_space
 * @copyright 2022 Marcin Czaja (https://rosea.io)
 * @license   Commercial https://themeforest.net/licenses
 *
 */


defined('MOODLE_INTERNAL') || die();

$page = new admin_settingpage('theme_space_general', get_string('generalsettings', 'theme_space'));

    $name = 'theme_space/hintro';
    $heading = get_string('hintro', 'theme_space');
    $setting = new space_setting_specialsettingheading($name, $heading, format_text(get_string('hintro_desc', 'theme_space'), FORMAT_MARKDOWN));
    $page->add($setting);

    $name = 'theme_space/darkmodetheme';
    $title = get_string('darkmodetheme', 'theme_space');
    $description = get_string('darkmodetheme_desc', 'theme_space');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_space/themeauthor';
    $title = get_string('themeauthor', 'theme_space');
    $description = get_string('themeauthor_desc', 'theme_space');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);
    
    $name = 'theme_space/fontawesome';
    $title = get_string('fontawesome', 'theme_space');
    $description = get_string('fontawesome_desc', 'theme_space');
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);
    
    // Unaddable blocks.
    // Blocks to be excluded when this theme is enabled in the "Add a block" list: Administration, Navigation, Courses and
    // Section links.
    $default = 'navigation,settings,course_list,section_links';
    $setting = new admin_setting_configtext('theme_space/unaddableblocks',
        get_string('unaddableblocks', 'theme_space'), get_string('unaddableblocks_desc', 'theme_space'), $default, PARAM_TEXT);
    $page->add($setting);

    // Google analytics block.
    $name = 'theme_space/googleanalytics';
    $title = get_string('googleanalytics', 'theme_space');
    $description = get_string('googleanalyticsdesc', 'theme_space');
    $default = '';
    $setting = new admin_setting_configtext($name, $title, $description, $default);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    // Setting to display a hint to the hidden visibility of a course.
    $name = 'theme_space/showhintcoursehidden';
    $title = get_string('showhintcoursehiddensetting', 'theme_space');
    $description = get_string('showhintcoursehiddensetting_desc', 'theme_space');
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    // Setting to display a hint to the guest accessing of a course
    $name = 'theme_space/showhintcourseguestaccesssetting';
    $title = get_string('showhintcourseguestaccesssetting', 'theme_space');
    $description = get_string('showhintcourseguestaccesssetting_desc', 'theme_space');
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

$settings->add($page);