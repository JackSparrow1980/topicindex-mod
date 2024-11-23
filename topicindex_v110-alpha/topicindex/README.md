# Topic Index Extension

[![Build Status](https://travis-ci.org/dmzx/Topic-Index.svg?branch=master)](https://travis-ci.org/dmzx/Topic-Index)

## Description
The **Topic Index** extension for phpBB allows you to create an ordered list of topics in posts and on an external page. This extension provides better topic organization and improved filtering options.

## Installation
1. Download the latest release.
2. Unzip the downloaded release and rename the folder to `topicindex`.
3. In the `ext` directory of your phpBB board, create a new directory named `dmzx` (if it does not already exist).
4. Copy the `topicindex` folder to `/ext/dmzx/` (the main extension class will be at `(your forum root)/ext/dmzx/topicindex/composer.json`).
5. Navigate in the ACP to `Customise -> Manage extensions`.
6. Find `Topic Index` under the Disabled Extensions list and click `Enable`.

## Uninstallation
1. Navigate in the ACP to `Customise -> Extension Management -> Extensions`.
2. Find `Topic Index` under the Enabled Extensions list and click `Disable`.
3. To completely remove the extension, click `Delete Data` and then delete the `/ext/dmzx/topicindex` folder.

## License
This project is licensed under the [GNU General Public License v2](http://opensource.org/licenses/GPL-2.0).

## Changes in Version 1.1.0-alpha
- Improved table layout for topics.
- Fixed alignment issues for headers and content.
- Added clear formatting for filtering letters.