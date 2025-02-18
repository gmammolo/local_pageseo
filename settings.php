<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin version and other meta-data are defined here.
 *
 * @package     local_pageseo';
 * @copyright   2025 Giuseppe MAMMOLO <gmammolo@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage('local_pageseo', get_string('pluginname', 'local_pageseo'));



    // Aggiungi un campo di textarea per visualizzare le tuple
    $name = 'local_pageseo/tuples';
    $title = get_string('tuples', 'local_pageseo');
    $description = get_string('tuplesdesc', 'local_pageseo');
    $default = json_encode([
        [
            'url' => 'https://example.com/page1',
            'title' => 'Example Page 1',
            'description' => 'This is the description for page 1.'
        ],
        [
            'url' => 'https://example.com/page2',
            'title' => 'Example Page 2',
            'description' => 'This is the description for page 2.'
        ]
    ], JSON_PRETTY_PRINT);
    $setting = new admin_setting_configtextarea($name, $title, $description, $default);

    // Verifica che il testo sia un JSON corretto
    // $setting->set_updatedcallback(function($value) {
    //     error_log($value);
    //     json_decode($value);

    //     if (json_last_error() !== JSON_ERROR_NONE) {
    //         throw new moodle_exception('invalidjson', 'local_pageseo', '', null, json_last_error_msg());
    //     }
    // });


    $settings->add($setting);



    
    $ADMIN->add('localplugins', $settings);
}