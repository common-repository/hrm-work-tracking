<?php
/**
* Class and Function List:
* Function list:
* - hrm_dp()
* - custom_dashboard_hrm()
* - hrm_admin_notices_tweet()
* - hrm_activation()
* - hrm_admin_init()
* - hrm_admin_notices()
* - hrm_deactivation()
* Classes list:
*/
function hrm_dp()
{
	require_once (ABSPATH . 'wp-load.php');

	require_once (ABSPATH . 'wp-admin/admin.php');

	require_once (ABSPATH . 'wp-admin/admin-header.php');

?>
<div class="wrap about-wrap">

	<h1><?php
	_e('Welcome to HRM Work Tracking'); ?></h1>

	<div class="about-text">
		<?php
	_e('Hello Stranger. You are running the HRM Work Tracking Plugin in version 1.47. For those of you who update from an earlier version than 1.46 read about the changes. The others: Keep calm, no changes at all.'); ?>
	</div>

	<h2 class="nav-tab-wrapper">
		<a href="#" id="welcome" class="nav-tab nav-tab-active" onclick="document.getElementById('start-content').style.display='none';document.getElementById('changes-content').style.display='none';document.getElementById('welcome-content').style.display='block';document.getElementById('start').className='nav-tab';document.getElementById('changes').className='nav-tab';this.className='nav-tab nav-tab-active';">
			<?php
	_e('Welcome'); ?>
		</a><a href="#"  id="changes" class="nav-tab"  onclick="document.getElementById('start-content').style.display='none';document.getElementById('changes-content').style.display='block';document.getElementById('welcome-content').style.display='none';document.getElementById('start').className='nav-tab';document.getElementById('welcome').className='nav-tab';this.className='nav-tab nav-tab-active';">
			<?php
	_e('Changes'); ?>
		</a><a href="#" class="nav-tab" id="start"  onclick="document.getElementById('start-content').style.display='block';document.getElementById('changes-content').style.display='none';document.getElementById('welcome-content').style.display='none';document.getElementById('welcome').className='nav-tab';document.getElementById('changes').className='nav-tab';this.className='nav-tab nav-tab-active';">
			<?php
	_e('Getting started / Support'); ?>
		</a>
	</h2>


		<div id="changes-content" style="display:none;">
	<div class="changelog">


		<h3><?php
	_e('Changes'); ?></h3>

		<div class="feature-section images-stagger-right">


			<style>td, th {border:1px groove white;}</style>
			<table>
			<tr><th>1.47</th><td>Minor bugfixes and reformatting of code</td></tr>
			<tr><th>1.46</th><td>last version with future improvements and future years support.</td></tr>
			<tr><th>1.45</th><td>last version which included support for year 2016</td></tr>
			<tr><th>1.41</th><td>some minor changes</td></tr>
			<tr><th>1.4</th><td>time tracking on how long somebody needs on post, added a welcome page</td></tr>
			<tr><th>1.39</th><td>Standard language changed from german to english</td></tr>
			<tr><th>1.3</th><td>Auto logout from time tracking, Settings page, remote logging, stop logging on logout</td></tr>
			<tr><th>1.2</th><td>
				extra pop out window<br />
				stop working on logout<br />
				extra settings page</td></tr>
		<tr><th>1.02</th><td>spanish translation files</td></tr>
		<tr><th>1.01</th><td>fix for activation issue</td></tr>
		<tr><th>1.00</th><td>Initial release</td></tr>
		</table>


		</div>
		</div>
		</div>

		<div id="welcome-content">
		<h2>Welcome</h2>
		You are running version 1.46. This is a slight update from 1.45.<br /><br />
		I want to highlight a few <b>highlights</b>:<br />
		<ul>
		<li>&dash; It is completly translated into english as native language</li>
		<li>&dash; It can count how long you or the other ones worked on an article.</li>
		<li>&dash; It has a settings page under Users -&gt; Settings</li>
		<li>&dash; It includes remote logging</li>
		<li>&dash; It can auto logoff if user is inactive</li>
		<li>&dash; You can clear all times</li>
		<li>&dash; With .po files you can translate it into every language</li>

		</ul>
		</div>

		<div id="start-content" style="display:none;">
			<h2>Start the Time Tracking ;-)</h2>
			Log out and then log in again:<br />
			Now you can find the tracking widget on your dashboard. counting.<br />
			In every post you have a meta box indicating how long are you writing<br />
			In the profile of each user you can set hours per week.<br />
			<hr>
			<br />
			For support and any requests visit <a href="https://emojized.com/" >https://emojized.com/</a>.<br />
			<br />
			Join us and see how the world of time tracking is changing.<br />
			<a href="https://twitter.com/share" class="twitter-share-button" data-url="http://wordpress.org/extend/plugins/hrm-work-tracking/" data-text="HRM Work Tracking for WordPress" data-via="wpplugindevcom" data-hashtags="WordPress">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script> 
<a href="https://twitter.com/wpplugindevcom" class="twitter-follow-button" data-show-count="false">Follow @wpplugindevcom</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script> 
<a href="https://twitter.com/intent/tweet?button_hashtag=wphrmwt" class="twitter-hashtag-button" data-related="wpplugindevcom">Tweet #wphrmwt</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script> 
<a href="https://twitter.com/intent/tweet?screen_name=wpplugindevcom" class="twitter-mention-button" data-related="wpplugindevcom">Tweet to @wpplugindevcom</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script> 
		</div>


</div>
<?php //include( ABSPATH . 'wp-admin/admin-footer.php' );

}

function custom_dashboard_hrm()
{
	global $current_user;
	$uid   = $current_user->ID;
	$hrmid = get_option('human_resources_department');
	if ($hrmid == $uid)
	{
		add_dashboard_page("HRM Welcome", "HRM Welcome Page", "activate_plugins", "hrm-dashboard-page", "hrm_dp");
	}
}

add_action("admin_menu", "custom_dashboard_hrm");

function hrm_admin_notices_tweet()
{
	global $current_user;
	$uid   = $current_user->ID;
	$hrmid = get_option('human_resources_department');
	$rac   = rand(0, 100);
	if ($hrmid == $uid && $rac == 55)
	{
		echo "<div class='updated'>";
?>
<img src="http://wp-plugin-dev.com/buttons/bird_blue_32.png" style="float:left;padding:5px"  /><script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>

<p>Please support HRM Work Tracking Plugin <a href="https://twitter.com/intent/tweet?text=I%20support%20the%20HRM%20Work%20Tracking%20plugin%20for&via=wpplugindevcom&hashtags=WordPress,timetracking,plugin,wphrmwt">on Twitter</a>!</p>	

<?php
		echo "</div>";
	}
	else
	{
	}
}

add_action("admin_notices", "hrm_admin_notices_tweet");

// / http://stackoverflow.com/questions/9807064/wordpress-how-to-display-notice-in-admin-panel-on-plugin-activation
function hrm_activation()
{
	$notices = get_option('hrm_deferred_admin_notices', array());
	$notices[]         = "My Plugin: Custom Activation Message";
	update_option('hrm_deferred_admin_notices', $notices);
}

function hrm_admin_init()
{
	$current_version = "1.4";
	$version         = get_option('hrm_version');
	if ($version != $current_version)
	{

		// Do whatever upgrades needed here.
		update_option('hrm_version', $current_version);
		$notices = get_option('hrm_deferred_admin_notices', array());
		$notices[]         = "HRM Work Tracking Plugin: Upgraded version $version to $current_version.";
		update_option('hrm_deferred_admin_notices', $notices);
	}
}
?>