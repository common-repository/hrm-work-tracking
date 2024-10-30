<?php
/**
* Class and Function List:
* Function list:
* - user_begins_to_work()
* - user_stops_to_work()
* - user_starts_pause()
* - user_stops_pause()
* - declare_ill()
* - hrm_activate()
* - reset_times()
* - register_hrm()
* - dashboard_clock_function()
* - hrd_dashboard()
* - hrm_add_my_stylesheet_and_scripts()
* - change_time_to_js()
* Classes list:
*/
function user_begins_to_work($user_login, $user)
{
	$this_user    = $user->ID;
	$is_in_office = get_user_meta($this_user, "in_office", true);
	$is_ill       = get_user_meta($this_user, "is_ill", true);
	$is_ill_until = get_user_meta($this_user, "is_ill_until", true);
	$ill_date     = strtotime($is_ill_until . " 23:59:59");
	$date_now     = current_time('timestamp');

	// echo "<br /><br /><br /><hr>";
	// echo $date_now."<br />";
	// echo $ill_date."<br />";
	// echo "<hr><br /><br /><br />";
	if ($date_now > $ill_date)
	{
		$is_ill_now   = "no";
	}
	else
	{
		$is_ill_now   = "yes";
	}

	//  || $is_ill=="yes"
	if ($is_in_office == "yes" || ($is_ill == "yes" && $is_ill_now == "yes"))
	{
	}
	else
	{
		update_user_meta($this_user, "Arbeitszeitbeginn", current_time('timestamp'));
		update_user_meta($this_user, "Arbeitszeitbeginn_readable", current_time('mysql'));
		update_user_meta($this_user, "in_office", "yes");
		update_user_meta($this_user, "is_ill", "no");
		update_user_meta($this_user, "in_pause", "no");
	}
}

function user_stops_to_work($user)
{
	$month             = date("F");
	$year              = date("Y");
	$day               = date("j"); // since 1.42
	$this_user         = $user;
	$beginn            = get_user_meta($this_user, "Arbeitszeitbeginn", true);
	$gesamt            = get_user_meta($this_user, "Arbeitszeit_gesamt_" . $year . "_" . $month, true);
	$pausenkonto       = get_user_meta($this_user, "Pausenkonto", true);
	$is_in_office      = get_user_meta($this_user, "in_office", true);
	$is_in_pause       = get_user_meta($this_user, "in_pause", true);
	if ($is_in_office == "yes" && $is_in_pause == "no")
	{
		$today             = current_time('timestamp') - $beginn - $pausenkonto;
		$arbeitszeit_heute = $gesamt + $today;
		update_user_meta($this_user, "Arbeitszeit_gesamt_" . $year . "_" . $month, $arbeitszeit_heute);
		if (get_option("hrm_daily") == "true")
		{
			$todays_work       = get_user_meta($this_user, "Arbeitszeit_" . $year . "_" . $month . "_" . $day, true);
			$todays_work_total = $todays_work + $today;
			update_user_meta($user, "Arbeitszeit_" . $year . "_" . $month . "_" . $day, $todays_work_total); // since 1.42

		}

		update_user_meta($this_user, "in_office", "no");
		update_user_meta($this_user, "Pausenkonto", "0");
	}
	else
	{

		// deprecated since 1.2

	}
}

function user_starts_pause($user)
{
	$this_user    = $user;
	$is_in_office = get_user_meta($this_user, "in_office", true);
	if ($is_in_office == "yes")
	{
		update_user_meta($this_user, "Pausenbeginn", current_time('timestamp'));
		update_user_meta($this_user, "Pausenbeginn_readable", current_time('mysql'));
		update_user_meta($this_user, "in_pause", "yes");
	}
	else
	{
	}
}

function user_stops_pause($user)
{
	$this_user   = $user;
	$pause       = get_user_meta($this_user, "Pausenbeginn", true);
	$in_pause    = get_user_meta($this_user, "in_pause", true);
	if ($in_pause == "yes")
	{
		$pausenkonto = get_user_meta($this_user, "Pausenkonto", true);
		$pause_time  = current_time('timestamp') - $pause;
		$neu         = $pausenkonto + $pause_time;
		update_user_meta($this_user, "Pausenkonto", $neu);
		update_user_meta($this_user, "in_pause", "no");
	}
	else
	{
		echo "<script>alert('Sie machen grad keine Pause');</script>";
	}
}

function declare_ill($user, $date)
{
	$this_user   = $user;
	$is_in_pause = get_user_meta($this_user, "in_pause", true);
	if ($is_in_pause == "no")
	{
		user_stops_to_work($this_user);
		update_user_meta($this_user, "in_pause", "no");
		update_user_meta($this_user, "in_office", "no");
		update_user_meta($user, "is_ill", "yes");
		update_user_meta($user, "is_ill_until", $date);
	}
	else
	{
?><script>alert('Bitte beenden Sie Ihre Pause zuerst.');</script><?php
	}
}

function hrm_activate()
{
	global $current_user;
	add_option('human_resources_department', $current_user->ID);
	update_option('human_resources_department', $current_user->ID);
}

function reset_times($user)
{
	$month = date("F");
	$year  = date("Y");
	$day   = date("j"); // since 1.42
	update_user_meta($user, "Pausenkonto", "0");
	update_user_meta($user, "in_pause", "no");
	update_user_meta($user, "in_office", "no");
	update_user_meta($user, "is_ill", "no");
	update_user_meta($user, "Arbeitszeit_gesamt_" . $year . "_" . $month, "0");
	if (get_option("hrm_daily") == "true")
	{
		update_user_meta($user, "Arbeitszeit_" . $year . "_" . $month . "_" . $day, "0"); // since 1.42

	}
}

function register_hrm()
{
	register_setting('hrm_wt_group', 'human_resources_department');
}

// shows only a dashboard clock
function dashboard_clock_function()
{ //deprecated in 1.1
	echo "MySQL Time: " . current_time('mysql');
	echo "<br /> Timestamp: " . current_time('timestamp');
	echo "<br /> GMT Offset: " . get_option('gmt_offset');
	echo "<br /> Server Time: " . date('Y-m-d H:i:s') . "<br />";
}

// shows only a dashboard clock
function hrd_dashboard()
{
	$head_of_hrd = get_userdata(get_option('human_resources_department'));
	echo __('Staff Manager', 'hrm-work-tracking') . "<br />";
	echo $head_of_hrd->user_firstname . " " . $head_of_hrd->user_lastname . "<br />";;
	echo $head_of_hrd->user_email;
	if (isset($head_of_hrd->tel))
	{
		echo "<br />" . __('phone', 'hrm-work-tracking') . ": " . $head_of_hrd->tel;
	}
}

// adds stylesheet for buttons and circles
function hrm_add_my_stylesheet_and_scripts()
{

	// Respects SSL, Style.css is relative to the current file
	wp_register_style('hrm-style', plugins_url('style.css', __FILE__));
	wp_enqueue_style('hrm-style');
	wp_register_script('hrm-script', plugins_url('hrm_scripts.js', __FILE__));
	wp_enqueue_script('hrm-script');
}

function change_time_to_js($datum)
{
	$fulldate = explode(" ", $datum);
	$day      = explode("-", $fulldate[0]);

	if ($datum != false) return "" . $day[1] . " " . $day[2] . ", " . $day[0] . " " . $fulldate[1];
}

// here are the security algorhythms to prevent other users to make changes
include ('hrm_security.php');

// the new function to get how long somebody wrote on an article.

?>