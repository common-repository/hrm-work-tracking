<?php
/**
* Class and Function List:
* Function list:
* - register_my_hrm_ill_submenu_page()
* - hrm_ill_page_callback()
* - my_admin_hrm_footer()
* - my_admin_init()
* Classes list:
*/
add_action('admin_menu', 'register_my_hrm_ill_submenu_page');

function register_my_hrm_ill_submenu_page()
{
	global $current_user;
	add_submenu_page('users.php', __('Sick Certificate', 'hrm-work-tracking') , __('Sick Certificate', 'hrm-work-tracking') , 'read', 'hrm-ill-page', 'hrm_ill_page_callback');
}

function hrm_ill_page_callback()
{
	global $current_user;
	$uid   = $current_user->ID;
	$ill   = $current_user->is_ill;
	$pause = $current_user->in_pause;
	$hrmid = get_option('human_resources_department');
	if (isset($_POST["ill"]) || $ill == "yes")
	{
		if (isset($_POST["ill"]))
		{
			declare_ill($uid, $_POST['date_field']);
		}

		echo "<h1>" . __("Sick Certificate", 'hrm-work-tracking') . "</h1><br />";
		$is_ill_until = get_user_meta($uid, "is_ill_until", true);
		echo __("absent due to illness to ", 'hrm-work-tracking') . $is_ill_until;

		// }
		// else if(($uid==$hrmid || is_hrm()) && !isset($_POST['hrm_ill'])){
		// Ill page for HRMs
		// hrm_settings_page_callback();

	}
	else
	{
		echo "<h1>" . __("Sick Certificate", 'hrm-work-tracking') . "</h1>";

		// Ill page for employees
		if ($pause == "no")
		{
?>

<script>
function validate(){
	if(document.sick_certificate.date_field.value == "")
	{
		alert("<?php
			_e("Please enter Date!", 'hrm-work-tracking'); ?>");
		return false;
	}
}
</script>

<?php
			_e("Please provide the date from the doctor's note.", 'hrm-work-tracking'); ?>
<form action="<?php
			echo admin_url() . "/profile.php?page=hrm-ill-page"; ?>" METHOD="POST" name="sick_certificate" onsubmit="return validate();">

<input type="text" name="date_field" class="mydatepicker" value="" /><br />
<br />
<input type="submit" class="ill" id="hrm_ill" value="<?php
			_e('report ill', 'hrm-work-tracking'); ?>" name="ill">

</form>
<?php
			/* ?>
			<hr>
			BETA:<br />
			<a href="#" id="insert-media-button" class="button insert-media add_media" data-editor="content" title="Add Media"><span class="wp-media-buttons-icon"></span> Add Media</a>
			<?php */

			// echo $pause;

		}
		else
		{ // since version 1.2
			echo __("Please quit break first!", "hrm-work-tracking");
?>
<br />
<form action="<?php
			echo admin_url(); ?>" name="hrm_form" method="POST">
<input type="submit" name="hrm_pause_quit" class="pause_quit" value="<?php
			_e('quit break', 'hrm-work-tracking'); ?>" />
</form><?php
		}
	}
}

function my_admin_hrm_footer()
{
?>
	<script type="text/javascript">
	jQuery(document).ready(function(){
		jQuery('.mydatepicker').datepicker({
			dateFormat : 'yy-mm-dd'
		});
	});
	</script>
	<?php
}

add_action('admin_footer', 'my_admin_hrm_footer');

function my_admin_init()
{
	$pluginfolder = get_bloginfo('url') . '/' . PLUGINDIR . '/' . dirname(plugin_basename(__FILE__));
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-datepicker', $pluginfolder . '/jquery/jquery.ui.datepicker.min.js', array(
		'jquery',
		'jquery-ui-core'
	));
	wp_enqueue_style('jquery.ui.theme', $pluginfolder . '/jquery/flick/jquery-ui-1.10.1.custom.css');
}

add_action('admin_init', 'my_admin_init');
?>