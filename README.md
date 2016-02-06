# Alliance Scrapers

This is a script that can be run via the command line to scrape [The Blue Alliance](https://thebluealliance.com) for
events.

All data can be exported to CSV.

## Running

To run, first build a copy of the code and build it or download the pre-built phar archive.

`php Alliance-Scraper.phar [year | all] [path for export]`

## Building

Run the script in `/build` by executing

`php build.php [team id]`

Note: The that the team ID is required because The Blue Alliance API requires it for API calls.

## Requirements

- `php 5.6 or higher` (not tested with 7).
- An internet connection (obviously).
