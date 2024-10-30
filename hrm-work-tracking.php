<?php
/*
Plugin Name: HRM Work Tracking
Plugin URI: http://wordpress.org/plugins/hrm-work-tracking/
Version: 1.5
Description: A plugin to track the time from users on your blog.
Author: emojized
Author URI: https://emojized.com
Text Domain:   hrm-work-tracking
Domain Path:   /lang/

This Plugin is licensed under GPL
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

/**
* Class and Function List:
* Function list:
* - user_stops_to_work_on_logout()
* Classes list:
*/

include 'hrm_dashboard.php';

include 'hrm_core.php';

include 'user-extras.php';

include 'hrm-users.php';

include 'hrm_ill.php';

include 'hrm_article.php';

include 'hrm_settings.php';

include 'custom_hrm_dashboard.php';

include 'hrm_popup.php';

// since 1.1
// include ('hrm_holiday.php'); // for 1.5
// include ('hrm_task_widget.php'); // for 1.5
add_action('admin_enqueue_scripts', 'hrm_add_my_stylesheet_and_scripts');
add_action('wp_login', 'user_begins_to_work', 10, 2);
register_activation_hook(__FILE__, 'hrm_activate');
add_action('admin_menu', 'register_hrm');
register_activation_hook(__FILE__, 'hrm_activation');
add_action('admin_notices', 'hrm_admin_notices');
register_deactivation_hook(__FILE__, 'hrm_deactivation');
add_action('admin_init', 'hrm_admin_init');

function user_stops_to_work_on_logout()
{
	global $current_user;
	user_stops_to_work($current_user->ID);
}

$llo = get_option("logoff_logging_option");

if ($llo == "on")
{
	add_action('wp_logout', 'user_stops_to_work_on_logout');
}
else
{
}

$alo = get_option("auto_logoff_option");

if ($alo == "on")
{
	add_action('init', 'checkout_if_any_user_is_over_time');
}
else
{
}

?>