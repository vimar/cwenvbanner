# cwenvbanner [![Build Status](https://api.travis-ci.org/carstenwindler/cwenvbanner.svg?branch=master)](https://travis-ci.org/carstenwindler/cwenvbanner)

The documentation is not yet fully written. Probably it never will. This text file gives you a short overview, actually this extension is very simple.

## I want facts. Quick. (aka Impatient Programmers Abstract)

* Adds customizable banner to both Backend and Frontend with environment identifier (e.g. DEV)
* Prepends environment identifier to the Frontend page title
* Compatible with Typo3 6.x and 7.x
* I'm happy to get feedback or bugfixes! [https://github.com/carstenwindler/cwenvbanner](https://github.com/carstenwindler/cwenvbanner)

## About this extension

This extension was build for those who often work on multiple Typo3 projects at the same time, or who are working with Staging/Production servers and accidently made changes on the wrong system and wondered why the changes did not work as expected.

## Whats new

Version 1.0.0 finally dropped Typo3 4.x support, added Typo3 7.x support and was refactored to support Typo3 Composer Mode.

### Older versions

*Note:* A Typo3 4.7 compatible version can still be found here: [https://github.com/carstenwindler/cwenvbanner/tree/typo3-4.x-compat](https://github.com/carstenwindler/cwenvbanner/tree/typo3-4.x-compat)

With version 0.1.0, cwenvbanner offers you predefined banner styles (well, basically 3 different colours: Red, Yellow, Green) to setup the extension on different environments even quicker.

## Super quick install guide

**Important:** You need to save the configuration once, otherwise cwenvbanner will do nothing (because it's a polite extension).

* Install the extension via Extension Manager or Composer
* Upon installation via Extension Manager, it will show you the configuration screen
* If not, or if you use Composer, you have to open the Extension Manager and edit settings according to your needs (or just use the defaults)
* hit "Update" button in any case
* Reload Frontend and Backend

**If you use 2 different browsers** (one for backend, one for frontend) make sure that you don't use any login-dependend features (e.g. showFEBannerIfBEUserIsLoggedIn), as it won't work properly otherwise.
