# Alliance Scrapers

This is a script that can be run via the command line to scrape [The Blue Alliance](https://thebluealliance.com) for
events.

All data can be exported to CSV.

## Running

To run, first build a copy of the code and build it or download the pre-built phar archive.

`php Alliance-Scraper.phar [command] [options...]`

## Building

Run the script in `/build` by executing

`php build.php [team id (FRC2228)] [team website (https://mycoolteam.com)]`

Note: The that the team ID is required because The Blue Alliance API requires it for API calls.

## Requirements

- `php 5.6 or higher` (not tested with 7).
- `php5-curl` (or `php7-curl`).
- An internet connection (obviously).
