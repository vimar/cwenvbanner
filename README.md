cwenvbanner v0.1.0
===

The documentation is not yet written. This text file gives you a short overview until it is available.

I want facts. Quick. (aka Impatient Programmers Abstract)
---

* Adds customizable banner to both Backend and Frontend with environment identifier (e.g. DEV)
* Prepends environment identifier to the Frontend page title
* Works both for Typo3 4.x and 6.x
* I'm happy to get feedback or bugfixes! Use the forge: https://forge.typo3.org/projects/extension-cwenvbanner

Whats new
---
With version 0.1.0, cwenvbanner offers you predefined banner styles (well, basically 3 differents colours: Red, Yellow, Green) to setup the extension on different environments even quicker.

**PHP 5.2 support has been dropped with this version!**

About this extension
---
This extension was build for those who often work on multiple Typo3 projects at the same time, or who are working with Staging/Production servers and accidently made changes on the wrong system and wondered why the changes did not work as expected.

Super quick install guide
---
**Important:** You need to save the configuration once, otherwise envbanner will do nothing (because it's a polite extension).

* Install the extension via the Extension Manager
* Upon installation, the Extension Manager will show you the configuration screen
* If not, while still in the Extension Manager, switch to the Configuration tab, edit settings according to your needs (you don't have to for a quick trial, it works out of the box)
* hit "Update" button in any case
* Reload Frontend and Backend

**If you use 2 different browsers** (one for backend, one for frontend) make sure that you don't use any login-dependend features (e.g. showFEBannerIfBEUserIsLoggedIn), as it won't work properly otherwise.
