<?php
/**
* Class and Function List:
* Function list:
* - hrm_add_user_working()
* - hrm_show_user_working_content()
* - show_user_working_status()
* Classes list:
*/
add_filter('manage_users_columns', 'hrm_add_user_working');

function hrm_add_user_working($columns)
{

	// $array['working'] = 'Online';
	// $columns=array_merge($array, $columns);
	$columns['working'] = 'Online';
	return $columns;
}

add_action('manage_users_custom_column', 'hrm_show_user_working_content', 10, 3);

function hrm_show_user_working_content($value, $column_name, $user_id)
{
	$user         = get_userdata($user_id);
	$in_office    = get_user_meta($user->ID, "in_office", true);
	$in_pause     = get_user_meta($user->ID, "in_pause", true);
	$is_ill       = get_user_meta($user->ID, "is_ill", true);

	// set status
	if ($in_office == "yes" && $in_pause == "no")
	{
		$status       = "<span class='green-circle'></span> " . __("present", 'hrm-work-tracking');
	}
	else if ($in_office == "yes" && $in_pause == "yes")
	{
		$status       = "<span class='orange-circle'></span> " . __("break", 'hrm-work-tracking');
	}
	else if ($is_ill == "yes")
	{
		$is_ill_until = get_user_meta($user->ID, "is_ill_until", true);
		$status       = "<span class='red-circle'> &cross;</span> " . __("ill until", 'hrm-work-tracking') . " " . $is_ill_until;
	}
	else
	{
		$status       = "<span class='red-circle'></span> " . __("absent", 'hrm-work-tracking');
	}

	if ('working' == $column_name) return $status;
	return $value;
}

function show_user_working_status($user_id)
{
	$user         = get_userdata($user_id);
	$in_office    = get_user_meta($user->ID, "in_office", true);
	$in_pause     = get_user_meta($user->ID, "in_pause", true);
	$is_ill       = get_user_meta($user->ID, "is_ill", true);

	// set status
	if ($in_office == "yes" && $in_pause == "no")
	{
		$status       = "<span class='green-circle'></span> " . __("present", 'hrm-work-tracking');
	}
	else if ($in_office == "yes" && $in_pause == "yes")
	{
		$status       = "<span class='orange-circle'></span> " . __("break", 'hrm-work-tracking');
	}
	else if ($is_ill == "yes")
	{
		$is_ill_until = get_user_meta($user->ID, "is_ill_until", true);
		$status       = "<span class='red-circle'> &cross;</span> " . __("ill until", 'hrm-work-tracking') . " " . $is_ill_until;
	}
	else
	{
		$status       = "<span class='red-circle'></span> " . __("absent", 'hrm-work-tracking');
	}

	return $status;
}

?>