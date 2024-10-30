<?php
/**
* Class and Function List:
* Function list:
* - hrm_plugin_init()
* - plugin_permissions()
* - hide_hrm()
* - hide_hrm_from_plugin_editor()
* - curPageName_hrm()
* - is_hrm()
* Classes list:
*/
function hrm_plugin_init()
{
	$plugin_dir = basename(dirname(__FILE__)) . "/lang/";
	load_plugin_textdomain('hrm-work-tracking', false, $plugin_dir);
}

add_action('plugins_loaded', 'hrm_plugin_init');

function plugin_permissions($plugins)
{
	global $current_user;
	$uid   = $current_user->ID;
	$hrmid = get_option('human_resources_department');

	if ($uid == $hrmid)
	{
	}
	else
	{
		unset($plugins['hrm-work-tracking/hrm-work-tracking.php']);
	}

	return $plugins;
}

add_filter('all_plugins', 'plugin_permissions');

function hide_hrm($buffer)
{
	return (str_replace('<option value="hrm-work-tracking/hrm-work-tracking.php" >HRM Work Tracking</option>', "", $buffer));
}

function hide_hrm_from_plugin_editor($html)
{
	global $current_user;
	$curr  = curPageName_hrm();
	if ($curr == "plugin-editor.php")
	{
		$uid   = $current_user->ID;
		$hrmid = get_option('human_resources_department');
		if ($uid == $hrmid)
		{
		}
		else
		{
			ob_start("hide_hrm");
			return $html;
			ob_end_flush();
		}
	}
	else
	{
	}
}

add_action('admin_init', 'hide_hrm_from_plugin_editor', 10, 1);

function curPageName_hrm()
{
	return substr($_SERVER["SCRIPT_NAME"], strrpos($_SERVER["SCRIPT_NAME"], "/") + 1);
}

function is_hrm()
{
	global $current_user;
	$uid    = $current_user->ID;
	$hrms   = get_option('other_hrms', true);
	$hrm    = explode(',', $hrms);

	// print_r($hrm);
	foreach ($hrm as $hrmu)
	{
		if ($hrmu == $uid)
		{
			$is_hrm = true;
			$user   = new WP_User($uid);
			$user->add_cap('edit_users');
		}
		else
		{
			$user = new WP_User($uid);
			$user->remove_cap('edit_users');
			$is_hrm = false;
		}
	}

	return $is_hrm;
}

?>