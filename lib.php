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

/**
 * Extracts information from a given URL.
 *
 * This function parses the provided URL and extracts its components,
 * including the base URL, path segments, query string, and fragment.
 *
 * @param string $url The URL to be parsed.
 * 
 * @return array An associative array containing the following keys:
 * - 'base': The base URL (scheme and host).
 * - 'path': An array of path segments.
 * - 'query': An associative array of query parameters.
 * - 'fragment': The URL fragment, if present.
 *
 * @example
 * $url = 'https://moodle.org/mod/page/view.php?id=123&chapter=4#section2';
 * $result = local_pageseo_extract_url_info($url);
 * // $result will be:
 * // [
 * //     'base' => 'https://moodle.org',
 * //     'path' => ['mod', 'page', 'view.php'],
 * //     'query' => ['id' => '123', 'chapter' => '4'],
 * //     'fragment' => 'section2'
 * // ]
 */
function local_pageseo_extract_url_info($url) {
    $url = parse_url($url);

    $path = $url['path'];
    $path = explode('/', $path);
    $path = array_filter($path);

    $path = array_values($path);

    $path = array_map(function($part) {
        return urldecode($part);
    }, $path);

    // Parse the query string into an associative array
    parse_str($url['query'] ?? '', $queryArray);

    return [
        'base' => $url['scheme'] . '://' . $url['host'],
        'path' => $path,
        'query' => $queryArray,
        'fragment' => $url['fragment'] ?? ''
    ];
}


/**
 * Compares two URLs to determine if they match based on their base, path, and category ID.
 *
 * This function extracts information from the provided URLs and checks if their base and path are identical.
 * Additionally, if both URLs contain a 'categoryid' query parameter, it checks if these parameters are equal.
 *
 * @param string $currentUrl The current URL to be compared.
 * @param string $pageSeoUrl The SEO URL to be compared.
 * @return bool Returns true if the URLs match based on the criteria; otherwise, false.
 */
function local_pageseo_metch_urls($currentUrl, $pageSeoUrl) {
    $infoCurrentUrl = local_pageseo_extract_url_info($currentUrl);
    $infoPageSeoUrl = local_pageseo_extract_url_info($pageSeoUrl);

    $matching = $infoCurrentUrl['base'] == $infoPageSeoUrl['base'] && $infoCurrentUrl['path'] == $infoPageSeoUrl['path'];

    // categoryID è un parametro discriminante per i vari corsi.
    if ($matching && isset($infoCurrentUrl['query']['categoryid']) && isset($infoPageSeoUrl['query']['categoryid'])) {
        $matching = $infoCurrentUrl['query']['categoryid'] == ($infoPageSeoUrl['query']['categoryid']);
    }
    return $matching;
}










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

    foreach ($urls as $tuple) {

        if (local_pageseo_metch_urls($currenturl, $tuple->url)) {
            $PAGE->set_title(trim($tuple->title));
            //$PAGE->set_description(trim($tuple->description));
            $PAGE->add_meta_tag('description',trim($tuple->description));
            
            return;
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