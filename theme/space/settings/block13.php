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

$page = new admin_settingpage('theme_space_block13', get_string('settingsblock13', 'theme_space'));

          $name = 'theme_space/displayblock13';
          $title = get_string('turnon', 'theme_space');
          $description = get_string('displayblock13_desc', 'theme_space');
          $default = 0;
          $setting = new admin_setting_configcheckbox($name, $title . '<span class="badge badge-sq badge-dark ml-2">Block #13</span>', $description, $default);
          $page->add($setting);

          $name = 'theme_space/displayhrblock13';
          $title = get_string('displayblockhr', 'theme_space');
          $description = get_string('displayblockhr_desc', 'theme_space');
          $default = 1;
          $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
          $page->add($setting);

          $name = 'theme_space/block13class';
          $title = get_string('additionalclass', 'theme_space');
          $description = get_string('additionalclass_desc', 'theme_space');
          $default = '';
          $setting = new admin_setting_configtext($name, $title, $description, $default);
          $page->add($setting);

          $name = 'theme_space/block13introtitle';
          $title = get_string('blockintrotitle', 'theme_space');
          $description = get_string('blockintrotitle_desc', 'theme_space');
          $default = '';
          $setting = new admin_setting_configtextarea($name, $title, $description, $default);
          $page->add($setting);

          $name = 'theme_space/block13introcontent';
          $title = get_string('blockintrocontent', 'theme_space');
          $description = get_string('blockintrocontent_desc', 'theme_space');
          $default = '';
          $setting = new admin_setting_configtextarea($name, $title, $description, $default);
          $page->add($setting);

          $name = 'theme_space/block13htmlcontent';
          $title = get_string('blockhtmlcontent', 'theme_space');
          $description = get_string('blockhtmlcontent_desc', 'theme_space');
          $default = '';
          $setting = new space_setting_confightmleditor($name, $title, $description, $default);
          $page->add($setting);

          $name = 'theme_space/block13footercontent';
          $title = get_string('blockfootercontent', 'theme_space');
          $description = get_string('blockfootercontent_desc', 'theme_space');
          $default = '';
          $setting = new admin_setting_configtextarea($name, $title, $description, $default);
          $page->add($setting);

          $fileid = 'block13bg';
          $name = 'theme_space/block13bg';
          $title = get_string('block13bg', 'theme_space');
          $description = get_string('block13bg_desc', 'theme_space');
          $opts = array('accepted_types' => array('.png', '.jpg', '.gif', '.webp', '.tiff', '.svg'), 'maxfiles' => 1);
          $setting = new admin_setting_configstoredfile($name, $title, $description, $fileid, 0, $opts);
          $setting->set_updatedcallback('theme_reset_all_caches');
          $page->add($setting);

          $name = 'theme_space/block13customcss';
          $title = get_string('block13customcss', 'theme_space');
          $description = get_string('block13customcss_desc', 'theme_space');
          $default = '';
          $setting = new admin_setting_configtextarea($name, $title, $description, $default);
          $page->add($setting);

        //   $name = 'theme_space/block13bgcolor';
        //   $title = get_string('block13bgcolor', 'theme_space');
        //   $description = get_string('block13bgcolor_desc', 'theme_space');
        //   $setting = new admin_setting_configtextarea($name, $title, $description, '');
        //   $setting->set_updatedcallback('theme_reset_all_caches');
        //   $page->add($setting);

$settings->add($page);