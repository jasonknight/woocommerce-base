woocommerce-base
================

A series of helpers, base classes, and generators for making speedy
WooCommerce plugins.

The basic plugin structure is like this:

* my-plugin/
	* classes/
	* config/
		* actions.php
		* filters.php
		* config.yml
	* templates/
		* admin/
		* shortcodes/
	* assets
		* javascripts
		* stylesheets
			* images
	* my-plugin.php

Simply call ./gen my-plugin-name and this structure will be created for you.