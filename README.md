# [Tag Cloud](https://development.azurecurve.co.uk/classicpress-plugins/tag-cloud/)
![Plugin Banner](/assets/images/banner-1544x500.png)

Customisable tag cloud which allows you to choose which tags are included.

## Description

The plugin integrates with the standard ClassicPress tag cloud, but allows tags to be excluded (or included) and gives easy control of other settings such as min and max font sizes, the sort order, and the number of tags to include.

## Installation

* Download the latest release of the plugin from [GitHub](https://github.com/azurecurve/azrcrv-tag-cloud/releases/latest/).
* Upload the entire zip file using the Plugins upload function in your ClassicPress admin panel.
* Activate the plugin.
* Configure relevant settings via the configuration page in the admin control panel (Tag Cloud, or the azurecurve menu).
* Deploy the "Tag Cloud by azurecurve" widget.

## Frequently Asked Questions

### Can I translate this plugin?
Yes, the .pot file is in the plugin's assets/languages folder; if you do translate this plugin, please send the .po and .mo files to translations@azurecurve.co.uk for inclusion in the next version (full credit will be given).

### Is this plugin compatible with both WordPress and ClassicPress?
This plugin is developed for ClassicPress, but will likely work on WordPress.

### I used the Network Settings feature in a previous version - what happened to it?
Network-wide settings were removed in version 2.0.0 as part of a rebuild of the plugin's admin interface. Each site on a network now always uses its own locally-configured settings, the same as before if you were not using that feature.

## Changelog

### [Version 2.0.0](https://github.com/azurecurve/azrcrv-tag-cloud/releases/tag/v2.0.0)

* Rebuilt the plugin on azurecurve's current plugin architecture (namespaced code, one file per responsibility, tabbed admin UI), matching Feed to Post.
* Added a dedicated top-level "Tag Cloud" admin menu entry, alongside the existing entry in the shared azurecurve menu.
* Added an Instructions tab to the settings page.
* Changed the plugin's text domain from `tag-cloud` to `azrcrv-tc` for consistency with the rest of the plugin (existing translations will need to be regenerated against the new .pot file).
* Increased the minimum required PHP version to 8.2.
* Removed the Network Settings feature (network admin settings page and "use network settings" option); each site now always uses its own settings.
* Fixed: a custom widget title entered in Appearance > Widgets was silently discarded due to a mismatched settings key, and always displayed "Tag Cloud" instead.
* Fixed: an unused, non-functional `[shortcode]` shortcode registration (pointing at a function that didn't exist) has been removed.
* Fixed: the "place the widget in a widget area" help text on the settings page displayed a literal `%s` instead of the widget's name.
* Fixed: each tag checkbox in the include/exclude list now has a unique ID, and its label correctly toggles it (previously all checkboxes shared the same ID and the label didn't point at the right one).
* Fixed: the scrollable box around the tag include/exclude checklist now actually applies (a CSS class name mismatch meant it never worked, and the style was also only ever loaded on the front end rather than the settings page).
* Fixed: settings values are no longer run through an unnecessary `stripslashes()` on display, which could corrupt values containing a backslash.
* Fixed: submitted settings are now correctly un-slashed before sanitizing, avoiding potential corruption of values containing quotes or backslashes.
* Fixed: numeric settings are correctly displayed in number fields and sanitised as numbers on save.
* Fixed: a fresh install with no saved settings no longer triggers a PHP warning when the widget checks the tag include/exclude list.
* Fixed: settings are now saved with `wp_safe_redirect()` rather than `wp_redirect()`.
* Improved: the Update Manager image filters and the Plugins-list settings link are now scoped to this plugin specifically, rather than using generic, unscoped hook names.
* Improved: plugin admin CSS/JS now only loads on this plugin's own admin screens, rather than on every admin screen of the site.
* Update readme.md and remove readme.txt (not required for ClassicPress).
* Update azurecurve menu.

### [Version 1.2.7](https://github.com/azurecurve/azrcrv-tag-cloud/releases/tag/v1.2.7)

* Update plugin header for compatibility with ClasssicPress v2.

### [Version 1.2.6](https://github.com/azurecurve/azrcrv-tag-cloud/releases/tag/v1.2.6)

* Update plugin header and readme for compatibility with ClassicPress Directory v2.
* Update Update Manager to version 2.5.0.

### [Version 1.2.5](https://github.com/azurecurve/azrcrv-tag-cloud/releases/tag/v1.2.5)

* Update readme file for compatibility with ClassicPress Director.

### [Version 1.2.4](https://github.com/azurecurve/azrcrv-tag-cloud/releases/tag/v1.2.4)

* Fix bug with selected tags not displaying correctly in admin settings page.

### [Version 1.2.3](https://github.com/azurecurve/azrcrv-tag-cloud/releases/tag/v1.2.3)

* Update readme files.
* Update language template.
* Fix bug with azurecurve menu.

### [Version 1.2.2](https://github.com/azurecurve/azrcrv-tag-cloud/releases/tag/v1.2.2)

* Update azurecurve menu.
* Update readme files.

### [Version 1.2.1](https://github.com/azurecurve/azrcrv-tag-cloud/releases/tag/v1.2.1)

* Update azurecurve menu and logo.

### [Version 1.2.0](https://github.com/azurecurve/azrcrv-tag-cloud/releases/tag/v1.2.0)

* Fix plugin action link to use admin_url() function.
* Rewrite option handling so defaults not stored in database on plugin initialisation.
* Add plugin icon and banner.
* Update azurecurve plugin menu.

### [Version 1.1.4](https://github.com/azurecurve/azrcrv-tag-cloud/releases/tag/v1.1.4)

* Fix bug with setting of default options.
* Fix bug with plugin menu.
* Update plugin menu css.

### [Version 1.1.3](https://github.com/azurecurve/azrcrv-tag-cloud/releases/tag/v1.1.3)

* Rewrite default option creation function to resolve several bugs.
* Upgrade azurecurve plugin to store available plugins in options.

### [Version 1.1.2](https://github.com/azurecurve/azrcrv-tag-cloud/releases/tag/v1.1.2)

* Update Update Manager class to v2.0.0.
* Update action link.
* Update azurecurve menu icon with compressed image.

### [Version 1.1.1](https://github.com/azurecurve/azrcrv-tag-cloud/releases/tag/v1.1.1)

* Fix bug with incorrect language load text domain.

### [Version 1.1.0](https://github.com/azurecurve/azrcrv-tag-cloud/releases/tag/v1.1.0)

* Add integration with Update Manager for automatic updates.
* Fix issue with display of azurecurve menu.
* Change settings page heading.
* Add load_plugin_textdomain to handle translations.

### [Version 1.0.1](https://github.com/azurecurve/azrcrv-tag-cloud/releases/tag/v1.0.1)

* Update azurecurve menu for easier maintenance.
* Move require of azurecurve menu below security check.

### [Version 1.0.0](https://github.com/azurecurve/azrcrv-tag-cloud/releases/tag/v1.0.0)

* Initial release for ClassicPress forked from azurecurve Tag Cloud WordPress Plugin.

## Other Notes

### About azurecurve

**azurecurve** was one of the first plugin developers to start developing for ClassicPress; all plugins are available from [azurecurve Development](https://development.azurecurve.co.uk/) and are integrated with the [Update Manager plugin](https://directory.classicpress.net/plugins/update-manager) for fully integrated, no hassle, updates.

The plugins available from **azurecurve** are:

* Add Open Graph Tags - [details](https://development.azurecurve.co.uk/classicpress-plugins/add-open-graph-tags/) / [download](https://github.com/azurecurve/azrcrv-add-open-graph-tags/releases/latest/)
* Add Twitter Cards - [details](https://development.azurecurve.co.uk/classicpress-plugins/add-twitter-cards/) / [download](https://github.com/azurecurve/azrcrv-add-twitter-cards/releases/latest/)
* Avatars - [details](https://development.azurecurve.co.uk/classicpress-plugins/avatars/) / [download](https://github.com/azurecurve/azrcrv-avatars/releases/latest/)
* BBCode - [details](https://development.azurecurve.co.uk/classicpress-plugins/bbcode/) / [download](https://github.com/azurecurve/azrcrv-bbcode/releases/latest/)
* Breadcrumbs - [details](https://development.azurecurve.co.uk/classicpress-plugins/breadcrumbs/) / [download](https://github.com/azurecurve/azrcrv-breadcrumbs/releases/latest/)
* Broken Links - [details](https://development.azurecurve.co.uk/classicpress-plugins/broken-links/) / [download](https://github.com/azurecurve/azrcrv-broken-links/releases/latest/)
* Call-out Boxes - [details](https://development.azurecurve.co.uk/classicpress-plugins/call-out-boxes/) / [download](https://github.com/azurecurve/azrcrv-call-out-boxes/releases/latest/)
* Chroma - [details](https://development.azurecurve.co.uk/classicpress-plugins/chroma/) / [download](https://github.com/azurecurve/azrcrv-chroma/releases/latest/)
* Code - [details](https://development.azurecurve.co.uk/classicpress-plugins/code/) / [download](https://github.com/azurecurve/azrcrv-code/releases/latest/)
* Comment Validator - [details](https://development.azurecurve.co.uk/classicpress-plugins/comment-validator/) / [download](https://github.com/azurecurve/azrcrv-comment-validator/releases/latest/)
* Conditional Links - [details](https://development.azurecurve.co.uk/classicpress-plugins/conditional-links/) / [download](https://github.com/azurecurve/azrcrv-conditional-links/releases/latest/)
* Contact Forms - [details](https://development.azurecurve.co.uk/classicpress-plugins/contact-forms/) / [download](https://github.com/azurecurve/azrcrv-contact-forms/releases/latest/)
* Disable FLoC - [details](https://development.azurecurve.co.uk/classicpress-plugins/disable-floc/) / [download](https://github.com/azurecurve/azrcrv-disable-floc/releases/latest/)
* Display After Post Content - [details](https://development.azurecurve.co.uk/classicpress-plugins/display-after-post-content/) / [download](https://github.com/azurecurve/azrcrv-display-after-post-content/releases/latest/)
* Estimated Read Time - [details](https://development.azurecurve.co.uk/classicpress-plugins/estimated-read-time/) / [download](https://github.com/azurecurve/azrcrv-estimated-read-time/releases/latest/)
* Events - [details](https://development.azurecurve.co.uk/classicpress-plugins/events/) / [download](https://github.com/azurecurve/azrcrv-events/releases/latest/)
* Feed to Post - [details](https://development.azurecurve.co.uk/classicpress-plugins/feed-to-post/) / [download](https://github.com/azurecurve/azrcrv-feed-to-post/releases/latest/)
* Filtered Categories - [details](https://development.azurecurve.co.uk/classicpress-plugins/filtered-categories/) / [download](https://github.com/azurecurve/azrcrv-filtered-categories/releases/latest/)
* Flags - [details](https://development.azurecurve.co.uk/classicpress-plugins/flags/) / [download](https://github.com/azurecurve/azrcrv-flags/releases/latest/)
* Floating Featured Image - [details](https://development.azurecurve.co.uk/classicpress-plugins/floating-featured-image/) / [download](https://github.com/azurecurve/azrcrv-floating-featured-image/releases/latest/)
* Get GitHub File - [details](https://development.azurecurve.co.uk/classicpress-plugins/get-github-file/) / [download](https://github.com/azurecurve/azrcrv-get-github-file/releases/latest/)
* Icons - [details](https://development.azurecurve.co.uk/classicpress-plugins/icons/) / [download](https://github.com/azurecurve/azrcrv-icons/releases/latest/)
* Image Optimiser - [details](https://development.azurecurve.co.uk/classicpress-plugins/image-optimiser/) / [download](https://github.com/azurecurve/azrcrv-image-optimiser/releases/latest/)
* Images - [details](https://development.azurecurve.co.uk/classicpress-plugins/images/) / [download](https://github.com/azurecurve/azrcrv-images/releases/latest/)
* Insult Generator - [details](https://development.azurecurve.co.uk/classicpress-plugins/insult-generator/) / [download](https://github.com/azurecurve/azrcrv-insult-generator/releases/latest/)
* Load Admin CSS - [details](https://development.azurecurve.co.uk/classicpress-plugins/load-admin-css/) / [download](https://github.com/azurecurve/azrcrv-load-admin-css/releases/latest/)
* Loop Injection - [details](https://development.azurecurve.co.uk/classicpress-plugins/loop-injection/) / [download](https://github.com/azurecurve/azrcrv-loop-injection/releases/latest/)
* Lorem Ipsum Generator - [details](https://development.azurecurve.co.uk/classicpress-plugins/lorem-ipsum-generator/) / [download](https://github.com/azurecurve/azrcrv-lorem-ipsum-generator/releases/latest/)
* Maintenance Mode - [details](https://development.azurecurve.co.uk/classicpress-plugins/maintenance-mode/) / [download](https://github.com/azurecurve/azrcrv-maintenance-mode/releases/latest/)
* Markdown - [details](https://development.azurecurve.co.uk/classicpress-plugins/markdown/) / [download](https://github.com/azurecurve/azrcrv-markdown/releases/latest/)
* Nearby - [details](https://development.azurecurve.co.uk/classicpress-plugins/nearby/) / [download](https://github.com/azurecurve/azrcrv-nearby/releases/latest/)
* Page Index - [details](https://development.azurecurve.co.uk/classicpress-plugins/page-index/) / [download](https://github.com/azurecurve/azrcrv-page-index/releases/latest/)
* Post Archive - [details](https://development.azurecurve.co.uk/classicpress-plugins/post-archive/) / [download](https://github.com/azurecurve/azrcrv-post-archive/releases/latest/)
* Quiz Engine - [details](https://development.azurecurve.co.uk/classicpress-plugins/quiz-engine/) / [download](https://github.com/azurecurve/azrcrv-quiz-engine/releases/latest/)
* Read GitHub File - [details](https://development.azurecurve.co.uk/classicpress-plugins/read-github-file/) / [download](https://github.com/azurecurve/azrcrv-read-github-file/releases/latest/)
* Redirect - [details](https://development.azurecurve.co.uk/classicpress-plugins/redirect/) / [download](https://github.com/azurecurve/azrcrv-redirect/releases/latest/)
* Remove Revisions - [details](https://development.azurecurve.co.uk/classicpress-plugins/remove-revisions/) / [download](https://github.com/azurecurve/azrcrv-remove-revisions/releases/latest/)
* RSS Feed - [details](https://development.azurecurve.co.uk/classicpress-plugins/rss-feed/) / [download](https://github.com/azurecurve/azrcrv-rss-feed/releases/latest/)
* RSS Suffix - [details](https://development.azurecurve.co.uk/classicpress-plugins/rss-suffix/) / [download](https://github.com/azurecurve/azrcrv-rss-suffix/releases/latest/)
* Series Index - [details](https://development.azurecurve.co.uk/classicpress-plugins/series-index/) / [download](https://github.com/azurecurve/azrcrv-series-index/releases/latest/)
* Shortcodes in Comments - [details](https://development.azurecurve.co.uk/classicpress-plugins/shortcodes-in-comments/) / [download](https://github.com/azurecurve/azrcrv-shortcodes-in-comments/releases/latest/)
* Shortcodes in Widgets - [details](https://development.azurecurve.co.uk/classicpress-plugins/shortcodes-in-widgets/) / [download](https://github.com/azurecurve/azrcrv-shortcodes-in-widgets/releases/latest/)
* SMTP - [details](https://development.azurecurve.co.uk/classicpress-plugins/smtp/) / [download](https://github.com/azurecurve/azrcrv-smtp/releases/latest/)
* Snippets - [details](https://development.azurecurve.co.uk/classicpress-plugins/snippets/) / [download](https://github.com/azurecurve/azrcrv-snippets/releases/latest/)
* String Inspector - [details](https://development.azurecurve.co.uk/classicpress-plugins/string-inspector/) / [download](https://github.com/azurecurve/azrcrv-string-inspector/releases/latest/)
* Strong Password Generator - [details](https://development.azurecurve.co.uk/classicpress-plugins/strong-password-generator/) / [download](https://github.com/azurecurve/azrcrv-strong-password-generator/releases/latest/)
* Tag Cloud - [details](https://development.azurecurve.co.uk/classicpress-plugins/tag-cloud/) / [download](https://github.com/azurecurve/azrcrv-tag-cloud/releases/latest/)
* Taxonomy Index - [details](https://development.azurecurve.co.uk/classicpress-plugins/taxonomy-index/) / [download](https://github.com/azurecurve/azrcrv-taxonomy-index/releases/latest/)
* Taxonomy Order - [details](https://development.azurecurve.co.uk/classicpress-plugins/taxonomy-order/) / [download](https://github.com/azurecurve/azrcrv-taxonomy-order/releases/latest/)
* Theme Switcher - [details](https://development.azurecurve.co.uk/classicpress-plugins/theme-switcher/) / [download](https://github.com/azurecurve/azrcrv-theme-switcher/releases/latest/)
* Timelines - [details](https://development.azurecurve.co.uk/classicpress-plugins/timelines) / [download](https://github.com/azurecurve/azrcrv-timelines/releases/latest/)
* Toggle Show/Hide - [details](https://development.azurecurve.co.uk/classicpress-plugins/toggle-showhide/) / [download](https://github.com/azurecurve/azrcrv-toggle-showhide/releases/latest/)
* Update Admin Menu - [details](https://development.azurecurve.co.uk/classicpress-plugins/update-admin-menu/) / [download](https://github.com/azurecurve/azrcrv-update-admin-menu/releases/latest/)
* URL Shortener - [details](https://development.azurecurve.co.uk/classicpress-plugins/url-shortener/) / [download](https://github.com/azurecurve/azrcrv-url-shortener/releases/latest/)
* Username Protection - [details](https://development.azurecurve.co.uk/classicpress-plugins/username-protection/) / [download](https://github.com/azurecurve/azrcrv-username-protection/releases/latest/)
* View Counter - [details](https://development.azurecurve.co.uk/classicpress-plugins/view-counter/) / [download](https://github.com/azurecurve/azrcrv-view-counter/releases/latest/)
* Widget Announcements - [details](https://development.azurecurve.co.uk/classicpress-plugins/widget-announcements/) / [download](https://github.com/azurecurve/azrcrv-widget-announcements/releases/latest/)
