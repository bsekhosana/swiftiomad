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

$page = new admin_settingpage('theme_space_block22', get_string('settingsblock22', 'theme_space'));

          $name = 'theme_space/displayblock22';
          $title = get_string('turnon', 'theme_space');
          $description = get_string('displayblock22_desc', 'theme_space');
          $default = 0;
          $setting = new admin_setting_configcheckbox($name, $title . '<span class="badge badge-sq badge-dark ml-2">Block #22</span>', $description, $default);
          $page->add($setting);

          $name = 'theme_space/displayhrblock22';
          $title = get_string('displayblockhr', 'theme_space');
          $description = get_string('displayblockhr_desc', 'theme_space');
          $default = 1;
          $setting = new admin_setting_configcheckbox($name, $title, $description, $default);
          $page->add($setting);

          $name = 'theme_space/block22class';
          $title = get_string('additionalclass', 'theme_space');
          $description = get_string('additionalclass_desc', 'theme_space');
          $default = '';
          $setting = new admin_setting_configtext($name, $title, $description, $default);
          $page->add($setting);

          $name = 'theme_space/block22introtitle';
          $title = get_string('blockintrotitle', 'theme_space');
          $description = get_string('blockintrotitle_desc', 'theme_space');
          $default = '';
          $setting = new admin_setting_configtextarea($name, $title, $description, $default);
          $page->add($setting);

          $name = 'theme_space/block22introcontent';
          $title = get_string('blockintrocontent', 'theme_space');
          $description = get_string('blockintrocontent_desc', 'theme_space');
          $default = '';
          $setting = new admin_setting_configtextarea($name, $title, $description, $default);
          $page->add($setting);

          $name = 'theme_space/block22htmlcontent';
          $title = get_string('blockhtmlcontent', 'theme_space');
          $description = get_string('blockhtmlcontent_desc', 'theme_space');
          $default = '<div class="rui-card-cat-grid mx-0">
          <!-- Start item -->
          <a href="https://demo.rosea.io/baz/1/course/" class="rui-block-category-item">
              <div class="d-block"><img src="https://assets.rosea.io/themes/icon1.png" alt="Icon #1" width="60" height="60" class="img-fluid atto_image_button_text-bottom"></div>
              <div class="ml-2">
                  <h3 class="rui-block-category-item-title">Art History</h3>
                  <span class="rui-block-category-item-subtitle">3 courses</span>
              </div>
          </a>
          <!-- End item -->
      
          <!-- Start item -->
          <a href="https://demo.rosea.io/baz/1/course/" class="rui-block-category-item">
              <div class="d-block"><img src="https://assets.rosea.io/themes/icon2.png" alt="Icon #2
      " width="60" height="60" class="img-fluid atto_image_button_text-bottom"></div>
              <div class="ml-2">
                  <h3 class="rui-block-category-item-title">UI Design</h3>
                  <span class="rui-block-category-item-subtitle">16 courses</span>
              </div>
          </a>
          <!-- End item -->
      
          <!-- Start item -->
          <a href="https://demo.rosea.io/baz/1/course/" class="rui-block-category-item">
              <div class="d-block"><img src="https://assets.rosea.io/themes/icon3.png" alt="Icon #3" width="60" height="60" class="img-fluid atto_image_button_text-bottom"></div>
              <div class="ml-2">
                  <h3 class="rui-block-category-item-title">3D Design</h3>
                  <span class="rui-block-category-item-subtitle">4 courses</span>
              </div>
          </a>
          <!-- End item -->
      
          <!-- Start item -->
          <a href="https://demo.rosea.io/baz/1/course/" class="rui-block-category-item">
              <div class="d-block"><img src="https://assets.rosea.io/themes/icon4.png" alt="Icon #4" width="60" height="60" class="img-fluid atto_image_button_text-bottom"></div>
              <div class="ml-2">
                  <h3 class="rui-block-category-item-title">Media &amp; PR</h3>
                  <span class="rui-block-category-item-subtitle">9 courses</span>
              </div>
          </a>
          <!-- End item -->
      
          <!-- Start item -->
          <a href="https://demo.rosea.io/baz/1/course/" class="rui-block-category-item">
              <div class="d-block"><img src="https://assets.rosea.io/themes/icon5.png" alt="Icon #5" width="60" height="60" class="img-fluid atto_image_button_text-bottom"></div>
              <div class="ml-2">
                  <h3 class="rui-block-category-item-title">Programming</h3>
                  <span class="rui-block-category-item-subtitle">2 courses</span>
              </div>
          </a>
          <!-- End item -->
      
          <!-- Start item -->
          <a href="https://demo.rosea.io/baz/1/course/" class="rui-block-category-item">
              <div class="d-block"><img src="https://assets.rosea.io/themes/icon6.png" alt="Icon #6" width="60" height="60" class="img-fluid atto_image_button_text-bottom"></div>
              <div class="ml-2">
                  <h3 class="rui-block-category-item-title">Music</h3>
                  <span class="rui-block-category-item-subtitle">6 courses</span>
              </div>
          </a>
          <!-- End item -->
      
          <!-- Start item -->
          <a href="https://demo.rosea.io/baz/1/course/" class="rui-block-category-item">
              <div class="d-block"><img src="https://assets.rosea.io/themes/icon8.png" alt="Icon #7" width="60" height="60" class="img-fluid atto_image_button_text-bottom"></div>
              <div class="ml-2">
                  <h3 class="rui-block-category-item-title">Language courses</h3>
                  <span class="rui-block-category-item-subtitle">3 courses</span>
              </div>
          </a>
          <!-- End item -->
      
      </div>';
          $setting = new space_setting_confightmleditor($name, $title, $description, $default);
          $page->add($setting);

          $name = 'theme_space/block22footercontent';
          $title = get_string('blockfootercontent', 'theme_space');
          $description = get_string('blockfootercontent_desc', 'theme_space');
          $default = '';
          $setting = new admin_setting_configtextarea($name, $title, $description, $default);
          $page->add($setting);

$settings->add($page);