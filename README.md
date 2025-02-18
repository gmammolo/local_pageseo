# Page SEO Plugin for Moodle

The `local_pageseo` plugin for Moodle 4.1+ allows administrators to set custom SEO metadata for specific pages. This plugin helps improve the search engine optimization of Moodle pages by allowing custom titles and descriptions to be set.

## Features

- Extracts information from a given URL.
- Compares two URLs to determine if they match based on their base, path, and category ID.
- Sets custom titles and descriptions for specific pages based on URL matching.

## Installation

1. Download the plugin and place it in the `local` directory of your Moodle installation.
2. Rename the folder to `pageseo`.
3. Visit the Site Administration > Notifications page to complete the installation.

## Configuration

1. Navigate to Site Administration > Plugins > Local plugins > Page SEO.
2. Add the URLs and their corresponding titles and descriptions in the provided textarea. The format should be a JSON array of objects, each containing `url`, `title`, and `description` fields.

Example:
```json
[
    {
        "url": "https://example.com/page1",
        "title": "Example Page 1",
        "description": "This is the description for page 1."
    },
    {
        "url": "https://example.com/page2",
        "title": "Example Page 2",
        "description": "This is the description for page 2."
    }
]
```

## License

This plugin is licensed under the [GNU General Public License v3 or later](https://www.gnu.org/licenses/gpl-3.0.html).

## Author

- Giuseppe MAMMOLO <gmammolo@gmail.com>