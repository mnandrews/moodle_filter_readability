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
 * @package    filter
 * @subpackage urltoreadibility
 * @copyright  2014 Mark Andrews <mnandrews@me.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($ADMIN->fulltree) {
            
   $settings->add(new admin_setting_configtext('filter_readability_token',
        get_string('token_title', 'filter_readability'),
        get_string('token_description', 'filter_readability'), 'token', PARAM_NOTAGS));
        
    $settings->add(new admin_setting_configcheckbox('filter_readability_toggle_intro',
            get_string('intro', 'filter_readability'),
            get_string('intro_desc', 'filter_readability'),
            1));
            
    $settings->add(new admin_setting_configcheckbox('filter_readability_toggle_image',
            get_string('image', 'filter_readability'),
            get_string('image_desc', 'filter_readability'),
            1));
            
     $settings->add(new admin_setting_configcheckbox('filter_readability_toggle_info',
            get_string('info', 'filter_readability'),
            get_string('info_desc', 'filter_readability'),
            1));
            
    $settings->add(new admin_setting_configcheckbox('filter_readability_toggle_fullcontent',
            get_string('fullcontent', 'filter_readability'),
            get_string('fullcontent_desc', 'filter_readability'),
            1));
    
    $settings->add(new admin_setting_configtext('filter_readability_excludedomain',
    		get_string('excludedomain', 'filter_readability'),
    		get_string('excludedomain_desc', 'filter_readability'), 'excludedomain', PARAM_NOTAGS));
    




}
