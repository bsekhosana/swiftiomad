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


$page = new admin_settingpage('theme_space_settingssidebar', get_string( 'settingssidebar', 'theme_space'));
    $name = 'theme_space/customsidebarlogo';
    $title = get_string('customsidebarlogo', 'theme_space');
    $description = get_string('customsidebarlogo_desc', 'theme_space');
    $opts = array('accepted_types' => array('.png', '.jpg', '.svg', 'gif'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'customsidebarlogo', 0, $opts);
    $page->add($setting);

    $name = 'theme_space/customsidebardmlogo';
    $title = get_string('customsidebardmlogo', 'theme_space');
    $description = get_string('customsidebardmlogo_desc', 'theme_space');
    $opts = array('accepted_types' => array('.png', '.jpg', '.svg', 'gif'));
    $setting = new admin_setting_configstoredfile($name, $title, $description, 'customsidebardmlogo', 0, $opts);
    $page->add($setting);

    $name = 'theme_space/customnavitems';
    $title = get_string('customnavitems', 'theme_space');
    $description = get_string('customnavitems_desc', 'theme_space');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_space/customstcontent';
    $title = get_string('customstcontent', 'theme_space');
    $description = get_string('customstcontent_desc', 'theme_space');
    $default = '';
    $setting = new space_setting_confightmleditor($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_space/customsmcontent';
    $title = get_string('customsmcontent', 'theme_space');
    $description = get_string('customsmcontent_desc', 'theme_space');
    $default = '';
    $setting = new space_setting_confightmleditor($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_space/customsfcontent';
    $title = get_string('customsfcontent', 'theme_space');
    $description = get_string('customsfcontent_desc', 'theme_space');
    $default = '';
    $setting = new space_setting_confightmleditor($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_space/hturnoffsidebar';
    $heading = get_string('hturnoffsidebar', 'theme_space');
    $setting = new admin_setting_heading($name, $heading, format_text(get_string('hturnoffsidebar_desc', 'theme_space'), FORMAT_MARKDOWN));
    $page->add($setting);

    $name = 'theme_space/turnoffsidebarfp';
    $title = get_string('turnoffsidebarfp', 'theme_space');
    $description = get_string('turnoffsidebarfp_desc', 'theme_space');
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_space/turnoffsidebardashboard';
    $title = get_string('turnoffsidebardashboard', 'theme_space');
    $description = get_string('turnoffsidebardashboard_desc', 'theme_space');
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_space/turnoffsidebarcourse';
    $title = get_string('turnoffsidebarcourse', 'theme_space');
    $description = get_string('turnoffsidebarcourse_desc', 'theme_space');
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_space/turnoffsidebarincourse';
    $title = get_string('turnoffsidebarincourse', 'theme_space');
    $description = get_string('turnoffsidebarincourse_desc', 'theme_space');
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_space/turnoffsidebarreport';
    $title = get_string('turnoffsidebarreport', 'theme_space');
    $description = get_string('turnoffsidebarreport_desc', 'theme_space');
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_space/turnoffsidebarstandard';
    $title = get_string('turnoffsidebarstandard', 'theme_space');
    $description = get_string('turnoffsidebarstandard_desc', 'theme_space');
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_space/turnoffsidebaradmin';
    $title = get_string('turnoffsidebaradmin', 'theme_space');
    $description = get_string('turnoffsidebaradmin_desc', 'theme_space');
    $default = 0;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_space/hmycoursesbtn';
    $heading = get_string('hmycoursesbtn', 'theme_space');
    $setting = new admin_setting_heading($name, $heading, format_text(get_string('hmycoursesbtn_desc', 'theme_space'), FORMAT_MARKDOWN));
    $page->add($setting);

    $name = 'theme_space/stringmycourses';
    $title = get_string('stringmycourses', 'theme_space');
    $description = get_string('stringmycourses_desc', 'theme_space');
    $default = 'My Courses';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_space/stringdetails';
    $title = get_string('stringdetails', 'theme_space');
    $description = get_string('stringdetails_desc', 'theme_space');
    $default = '';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_space/stringallcourses';
    $title = get_string('stringallcourses', 'theme_space');
    $description = get_string('stringallcourses_desc', 'theme_space');
    $default = '<span>List of all available courses</span><svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.75 6.75L19.25 12L13.75 17.25"></path><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 12H4.75"></path></svg>';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_space/stringnocourses';
    $title = get_string('stringnocourses', 'theme_space');
    $description = get_string('stringnocourses_desc', 'theme_space');
    $default = 'You are not enrolled in any courses.';
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_space/hsidebarcolors';
    $heading = get_string('hsidebarcolors', 'theme_space');
    $setting = new admin_setting_heading($name, $heading, format_text(get_string('hsidebarcolors_desc', 'theme_space'), FORMAT_MARKDOWN));
    $page->add($setting);

    $name = 'theme_space/colordrawerbg';
    $title = get_string('colordrawerbg', 'theme_space');
    $description = get_string('color_desc', 'theme_space');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_space/colordrawertext';
    $title = get_string('colordrawertext', 'theme_space');
    $description = get_string('color_desc', 'theme_space');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_space/colordrawernavcontainer';
    $title = get_string('colordrawernavcontainer', 'theme_space');
    $description = get_string('color_desc', 'theme_space');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_space/colordrawernavbtnicon';
    $title = get_string('colordrawernavbtnicon', 'theme_space');
    $description = get_string('color_desc', 'theme_space');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);  

    $name = 'theme_space/colordrawernavbtniconh';
    $title = get_string('colordrawernavbtniconh', 'theme_space');
    $description = get_string('color_desc', 'theme_space');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);

    $name = 'theme_space/colordrawernavbtntext';
    $title = get_string('colordrawernavbtntext', 'theme_space');
    $description = get_string('color_desc', 'theme_space');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_space/colordrawernavbtntexth';
    $title = get_string('colordrawernavbtntexth', 'theme_space');
    $description = get_string('color_desc', 'theme_space');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_space/colordrawernavbtnbgh';
    $title = get_string('colordrawernavbtnbgh', 'theme_space');
    $description = get_string('color_desc', 'theme_space');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
    
    $name = 'theme_space/colordrawernavbtntextlight';
    $title = get_string('colordrawernavbtntextlight', 'theme_space');
    $description = get_string('color_desc', 'theme_space');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);
      
    $name = 'theme_space/colordrawerscrollbar';
    $title = get_string('colordrawerscrollbar', 'theme_space');
    $description = get_string('color_desc', 'theme_space');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);  

    $name = 'theme_space/colordrawerlink';
    $title = get_string('colordrawerlink', 'theme_space');
    $description = get_string('color_desc', 'theme_space');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);  

    $name = 'theme_space/colordrawerlinkh';
    $title = get_string('colordrawerlinkh', 'theme_space');
    $description = get_string('color_desc', 'theme_space');
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '');
    $setting->set_updatedcallback('theme_reset_all_caches');
    $page->add($setting);  
    

    $name = 'theme_space/hmainnav';
	$heading = get_string('hmainnav', 'theme_space');
	$setting = new admin_setting_heading($name, $heading, format_text(get_string('hmainnav_desc', 'theme_space'), FORMAT_MARKDOWN));
	$page->add($setting);

    $name = 'theme_space/isitemonsitehome';
    $title = get_string('isitemonsitehome', 'theme_space');
    $description = get_string('isitemonsitehome_desc', 'theme_space');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_space/isitemondashboard';
    $title = get_string('isitemondashboard', 'theme_space');
    $description = get_string('isitemondashboard_desc', 'theme_space');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    // $name = 'theme_space/isitemonmycourses';
    // $title = get_string('isitemonmycourses', 'theme_space');
    // $description = get_string('isitemonmycourses_desc', 'theme_space');
    // $default = 0;
    // $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    // $page->add($setting);

    $name = 'theme_space/isitemoncalendar';
    $title = get_string('isitemoncalendar', 'theme_space');
    $description = get_string('isitemoncalendar_desc', 'theme_space');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_space/isitemonprivatefiles';
    $title = get_string('isitemonprivatefiles', 'theme_space');
    $description = get_string('isitemonprivatefiles_desc', 'theme_space');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

    $name = 'theme_space/isitemoncontentbank';
    $title = get_string('isitemoncontentbank', 'theme_space');
    $description = get_string('isitemoncontentbank_desc', 'theme_space');
    $default = 1;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
    $page->add($setting);

// Must add the page after definiting all the settings!
$settings->add($page);
