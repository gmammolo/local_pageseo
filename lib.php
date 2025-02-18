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

function local_pageseo_before_http_headers() {
    global $DB, $PAGE;

    $currenturl = $PAGE->url->out(false);

    $urls_config = get_config('local_pageseo', 'tuples');

   $urls =  json_decode($urls_config);

    if (json_last_error() !== JSON_ERROR_NONE) {
        error_log('local_pageseo error: invalid JSON.  ' . json_last_error_msg());
        //throw new moodle_exception('invalidjson', 'local_pageseo', '', null, json_last_error_msg());
        return;
    }

    // creaqte un mock di $urls
    if ($urls) {
        foreach ($urls as $tuple) {
            if (trim($tuple->url) == $currenturl) {
                //$PAGE->set_title(trim($tuple->title));
                //$PAGE->set_heading(trim($tuple->title));
                //$PAGE->set_description(trim($tuple->description));
                //break;
            }
        }
    }
}

// Registra l'evento
$observers = [
    [
        'eventname' => '\core\event\before_http_headers',
        'callback' => 'local_pageseo_before_http_headers',
    ],
];