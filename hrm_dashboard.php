<?php
/**
* Class and Function List:
* Function list:
* - add_dashboard_clock()
* - add_dashboard_hrd()
* - add_hrm_wt()
* - dashboard_hrm_wt_function()
* Classes list:
*/
function add_dashboard_clock()
{ //deprecated in 1.1
	wp_add_dashboard_widget('dashboard_clock', __('clock', 'hrm-work-tracking') , 'dashboard_clock_function');
}

function add_dashboard_hrd()
{
	wp_add_dashboard_widget('dashboard_hrd', __('Human Resources Department', 'hrm-work-tracking') , 'hrd_dashboard');
}

function add_hrm_wt()
{
	wp_add_dashboard_widget('dashboard_hrm_wt', __('time recording', 'hrm-work-tracking') , 'dashboard_hrm_wt_function');
	global $wp_meta_boxes;
	$normal_dashboard_hrm = $wp_meta_boxes['dashboard']['normal']['core'];
	$hrm_widget_backup    = array(
		'dashboard_hrm_wt' => $normal_dashboard_hrm['dashboard_hrm_wt']
	);
	unset($normal_dashboard_hrm['dashboard_hrm_wt']);
	$sorted_dashboard_hrm = array_merge($hrm_widget_backup, $normal_dashboard_hrm);
	$wp_meta_boxes['dashboard']['normal']['core']                      = $sorted_dashboard_hrm;
}

add_action('wp_dashboard_setup', 'add_dashboard_clock'); //deprecated in 1.1
add_action('wp_dashboard_setup', 'add_dashboard_hrd');
add_action('wp_dashboard_setup', 'add_hrm_wt');

// shows the main widget with work tracking functionality
function dashboard_hrm_wt_function()
{
	global $current_user;

	// check if button is clicked
	if (isset($_POST['hrm_finished']))
	{
		user_stops_to_work($current_user->ID);
	}
	else if (isset($_POST['hrm_pause']))
	{
		user_starts_pause($current_user->ID);
	}
	else if (isset($_POST['hrm_pause_quit']))
	{
		user_stops_pause($current_user->ID);
	}
	else if (isset($_POST['hrm_ill']))
	{

		// declare_ill($current_user->ID);

	} // deprecated in 1.1 re-used in 1.17
	else if (isset($_GET['hrm_reset']) && $_GET['hrm_reset'] == "true")
	{
		reset_times($current_user->ID);
	} // deprecated in 1.1
	else
	{
	}

	// get all the data
	$month                       = date("F");
	$year                        = date("Y");
	$in_office                   = get_user_meta($current_user->ID, "in_office", true);
	$in_pause                    = get_user_meta($current_user->ID, "in_pause", true);
	$is_ill                      = get_user_meta($current_user->ID, "is_ill", true);
	$arbeitszeit_gesamt_sekunden = get_user_meta($current_user->ID, "Arbeitszeit_gesamt_" . $year . "_" . $month, true);
	$arbeitszeit_gesamt          = get_user_meta($current_user->ID, "Arbeitszeit_gesamt_" . $year . "_" . $month, true);
	if ($arbeitszeit_gesamt == false) $arbeitszeit_gesamt          = 0;
	$arbeitszeit_gesamt          = $arbeitszeit_gesamt / 3600;
	$arbeitszeit_gesamt          = round($arbeitszeit_gesamt, 2);
	$user_began                  = get_user_meta($current_user->ID, "Arbeitszeitbeginn", true);
	$user_began_pause            = get_user_meta($current_user->ID, "Pausenbeginn", true);
	$user_began_pause_read       = get_user_meta($current_user->ID, "Pausenbeginn_readable", true);
	$user_began_read             = get_user_meta($current_user->ID, "Arbeitszeitbeginn_readable", true);
	$pausenkonto                 = get_user_meta($current_user->ID, "Pausenkonto", true);
	$is_ill_until                = get_user_meta($current_user->ID, "is_ill_until", true);

	// set status
	if ($in_office == "yes" && $in_pause == "no")
	{
		$status                      = "<span class='green-circle'></span>";
	}
	else if ($in_office == "yes" && $in_pause == "yes")
	{
		$status                      = "<span class='orange-circle'></span>";
	}
	else if ($is_ill == "yes")
	{
		$status                      = "<span class='red-circle'>&cross;</span> <small>" . __("ill until", 'hrm-work-tracking') . " " . $is_ill_until . "</small>";
	}
	else
	{
		$status                      = "<span class='red-circle'></span>";
	}

	$anfang                      = change_time_to_js($user_began_read);
	if ($user_began_pause != "")
	{
		$anfang_pause                = change_time_to_js($user_began_pause_read);
	}

	echo "<h1>" . $current_user->user_firstname . " " . $current_user->user_lastname . " " . $status . "</h1>"; // activated by ".get_option('human_resources_department')."<br />";
	$month = __(date("F"));
	/*
	echo "<table class=\"data_for_hrm\"><tr><th>field</th><th>value</th></tr>";
	echo "<tr><td>Arbeitszeitbeginn</td><td>".$user_began."</td></tr>";
	echo "<tr><td>Arbeitszeitbeginn lesbar</td><td><b>".$user_began_read."</b></td></tr>";
	echo "<tr><td>Pausenbeginn</td><td><b>".$user_began_pause."</b></td></tr>";
	echo "<tr><td>Pausenbeginn lesbar</td><td><b>".$user_began_pause_read."</b></td></tr>";
	echo "<tr><td>Pausenkonto in s</td><td><b>".$pausenkonto."</b></td></tr>";
	echo "<tr><td>Arbeitszeit gesamt in h</td><td><b>".$arbeitszeit_gesamt."</b></td></tr>";
	echo "<tr><td>Current_time Timestamp</td><td>".current_time('timestamp')."</td></tr>";
	echo "</table>";
	*/
?>
<style>.pa{border-left:1px gray solid;border-top:1px solid gray;text-align:right;}</style>
<table><tr><td><?php
	_e('month', 'hrm-work-tracking'); ?></td><td><?php
	_e('target hours', 'hrm-work-tracking'); ?></td><td><?php
	_e('actual hours', 'hrm-work-tracking'); ?></td></tr>
<tr><td class="pa"><?php
	echo $month; ?></td><td class="pa"><?php
	if ($current_user->whow == false) $current_user->whow = 0;
	echo ($current_user->whow * 4); ?></td><td class="pa"><?php
	$ms = $current_user->whow * 4;
	if ($ms < $arbeitszeit_gesamt)
	{
		echo "<font color=\"#ff0000\">";
		echo $arbeitszeit_gesamt;
		echo "</font>";
		$ueberstunden = true;
	}
	else
	{
		echo $arbeitszeit_gesamt;
		$ueberstunden = false;
		$mst          = $current_user->whow * 720;
		if ($user_began == false) $user_began   = 0;
		if ($pausenkonto == false) $pausenkonto  = 0;
		$ah           = current_time('timestamp') - $user_began - $pausenkonto;
		if ($ah > $mst)
		{
			$ueberstunden = true;
		}
		else
		{
			$ueberstunden = false;
		}
	}

?></td></tr>
</table>
<p style="border-bottom:1px solid #ECECEC;"></p>
<?php
	echo "<span class=\"counting\">";
	if ($in_office == "yes")
	{
		include ('count.php');

	}
	else
	{
		echo __("working time", 'hrm-work-tracking') . ": " . $arbeitszeit_gesamt . " " . __("hours", 'hrm-work-tracking') . " " . __("in", 'hrm-work-tracking') . " " . $month;
	}

?></span>
<form action="<?php
	echo admin_url(); ?>" name="hrm_form" method="POST">
<br /><?php
	if (($in_pause == "" || $in_pause == "no") && $in_office == "yes")
	{ ?>
<input type="submit" name="hrm_pause" class="pause" value="<?php
		_e('start break', 'hrm-work-tracking'); ?>" />
<?php
	}

	if ($in_pause == "yes")
	{ ?>
<input type="submit" name="hrm_pause_quit" class="pause_quit" value="<?php
		_e('quit break', 'hrm-work-tracking'); ?>" />
<?php
	}

	if ($in_office == "yes" && ($in_pause == "no" || $in_pause == ""))
	{ ?>
<input type="submit" name="hrm_finished" class="finished" value="<?php
		_e('quitting time', 'hrm-work-tracking'); ?>" /></form><form method="POST" name="hrm_form" action="<?php
		echo admin_url() . "profile.php?page=hrm-ill-page"; ?>"> <input type="submit" name="hrm_ill" class="ill" value="<?php
		_e('report ill', 'hrm-work-tracking'); ?>" />
<?php
	}

?></form>

<?php //since  version 1.1
	global $pagenow;
	if ($pagenow == "users.php" && $_GET['page'] == "hrm-pop-out")
	{
		echo "<style>#wpfooter, #update-nag, #adminmenuback, #adminmenuwrap, #adminmenushadow, #adminmenu {display:none;}</style>";
	}
	else
	{
		echo "<br /><a href=\"users.php?page=hrm-pop-out\" target=\"_blank\">" . __("Open in new window", "hrm-work-tracking") . "</a>";
	}
}

// since 1.4
// add_shortcode( 'hrm-front' , 'dashboard_hrm_wt_function');

?>