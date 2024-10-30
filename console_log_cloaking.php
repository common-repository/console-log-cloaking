<?php
/*
  Plugin Name:  Console Log Cloaking
  Description:  Console Log Cloaking handling functions
  Version:      1.0.0
  Author:       Codecide
  Author URI:   https://plugins.codecide.net/
  License:      GPL2
  License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 */
if(!defined('ABSPATH')) exit;
require_once('class/console_log_cloaking/console_log_cloaking.php');
require_once('class/console_log_cloaking/console_log_cloaking.admin.php');
new console_log_cloaking_admin();