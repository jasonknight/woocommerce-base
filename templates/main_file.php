namespace REDE;
/*
Plugin Name: <?php echo $plugin->red_titleize($plugin_name); ?> 
Plugin URI: <?php echo $url; ?> 
Description: <?php echo $description; ?> 
Version: 1.0
Author: <?php echo $author; ?> 
Author URI: <?php echo $author_url; ?>

Copyright © <?php echo $copyright_year; ?> <?php echo $copyright_holder; ?>

*/
require_once dirname( __FILE__ ) . "/classes/<?php echo $class_file ?>";
$<?php echo $class_name ?>_Instance = new <?php echo $class_name ?>();