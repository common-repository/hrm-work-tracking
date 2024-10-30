<?php
/**
* Class and Function List:
* Function list:
* - hrm_time_add_custom_box()
* - hrm_seconds_inner_custom_box()
* - hrm_work_tracking_plugin_save_postdata()
* - my_users_work_tracking_menu()
* - hrm_work_page()
* Classes list:
*/
$pmlo    = get_option("post_measure_logging_option");

function hrm_time_add_custom_box()
{
    $screens = get_post_types('', 'names');
    foreach ($screens as $screen)
    {
        add_meta_box('hrm_seconds', __('HRM Post Timer', 'hrm-work-tracking') , 'hrm_seconds_inner_custom_box', $screen);
    }
}

function hrm_seconds_inner_custom_box($post)
{
    $current_user = wp_get_current_user();
    $cu_id        = $current_user->ID;
    $worked       = get_post_meta(get_the_ID() , "_time_spend_" . $cu_id, true);
    if ($worked == false)
    {
        $worked       = 0;
    }

    // seconds script from chandu http://stackoverflow.com/questions/5517597/plain-count-up-timer-in-javascript
    wp_nonce_field('hrm_seconds_inner_custom_box', 'hrm_seconds_inner_custom_box_nonce');
?>
<label id="minutes">00</label>:<label id="seconds">00</label> <?php
    _e('minutes worked on this post', "hrm-work-tracking"); ?>

<script type="text/javascript">
        var minutesLabel = document.getElementById("minutes");
        var secondsLabel = document.getElementById("seconds");
        var totalSeconds = <?php
    echo $worked; ?>;
        setInterval(setTime, 1000);

        function setTime()
        {
            ++totalSeconds;
            secondsLabel.innerHTML = pad(totalSeconds%60);
            minutesLabel.innerHTML = pad(parseInt(totalSeconds/60));

        }

        function pad(val)
        {
            var valString = val + "";
            if(valString.length < 2)
            {
                return "0" + valString;
            }
            else
            {
                return valString;
            }
        }
    </script>
<script>setInterval(setfield, 1000);
function setfield (){
document.getElementById("inpa").value=totalSeconds;
}
</script><input type="hidden" id="inpa" name="total_seconds_hrm" />
<?php
}

function hrm_work_tracking_plugin_save_postdata($post_id)
{
    $current_user = wp_get_current_user();
    $cu_id        = $current_user->ID;

    // Check if our nonce is set.
    if (!isset($_POST['hrm_seconds_inner_custom_box_nonce'])) return $post_id;
    $nonce = $_POST['hrm_seconds_inner_custom_box_nonce'];

    // Verify that the nonce is valid.
    if (!wp_verify_nonce($nonce, 'hrm_seconds_inner_custom_box')) return $post_id;

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;

    // Check the user's permissions.
    if ('page' == $_POST['post_type'])
    {
        if (!current_user_can('edit_page', $post_id)) return $post_id;
    }
    else
    {
        if (!current_user_can('edit_post', $post_id)) return $post_id;
    }

    /* OK, its safe for us to save the data now. */

    // Sanitize user input.
    $spend = sanitize_text_field($_POST['total_seconds_hrm']);

    // Update the meta field in the database.
    update_post_meta($post_id, "_time_spend_" . $cu_id, $spend);
}

$pmlo = get_option("post_measure_logging_option");

if ($pmlo == "on")
{
    add_action('add_meta_boxes', 'hrm_time_add_custom_box');
    add_action('save_post', 'hrm_work_tracking_plugin_save_postdata');
    add_action('admin_menu', 'my_users_work_tracking_menu');
}
else
{
}

function my_users_work_tracking_menu()
{
    add_users_page(__('My Work', 'hrm-work-tracking') , __('My Work', 'hrm-work-tracking') , 'read', 'hrm-article-work-tracking', 'hrm_work_page');
}

function hrm_work_page()
{
    $current_user      = wp_get_current_user();
    $cu_id             = $current_user->ID;
    $month_to_show     = $_POST["month_to_show"];
    if ($month_to_show == false)
    {
        $month_to_show     = date("m");
    }

    $year_to_show      = $_POST["year_to_show"];
    if ($year_to_show == false)
    {
        $year_to_show      = date("Y");
    }

    $user_to_show      = $_POST["user_to_show"];
    if ($user_to_show == false)
    {
        $user_to_show      = $cu_id;
    }

    // $user_to_show=$cu_id;
    // $year=date("Y");
    $year              = $year_to_show;
    $user_to_show_name = $user_to_show . ": " . $current_user->user_firstname . " " . $current_user->user_lastname;
    $the_query         = new WP_Query('post_type=any&meta_key=_time_spend_' . $user_to_show . '&posts_per_page=-1&monthnum=' . $month_to_show . '&year=' . $year . '');
    $full_time         = 0;
?>
<h1>My Work</h1>

<form action="" method="post">
<?php
    $uid               = $current_user->ID;
    $hrmid             = get_option('human_resources_department');
    if ($uid == $hrmid || is_hrm())
    {
?>
<select name="user_to_show">
<?php
        $blogusers         = get_users("orderby=id");
        foreach ($blogusers as $user)
        {
            if ($user_to_show == $user->ID)
            {
                $selected          = "selected";
            }
            else
            {
                $selected          = "";
            }

            echo "<option " . $selected . " value=\"" . $user->ID . "\">" . $user->ID . ": " . $user->user_firstname . " " . $user->user_lastname . "</option>";
        }

?>
<!--<option value="<?php
        echo $user_to_show; ?>"><?php
        echo $user_to_show_name; ?></option>-->
</select>
<?php
    }
    else
    {
        echo $user_to_show_name . " ";
    } ?>

<select name="month_to_show">
<option value="01" <?php
    if ($month_to_show == "01") echo "selected"; ?>><?php
    _e("January"); ?></option>
<option value="02" <?php
    if ($month_to_show == "02") echo "selected"; ?>><?php
    _e("February"); ?></option>
<option value="03" <?php
    if ($month_to_show == "03") echo "selected"; ?>><?php
    _e("March"); ?></option>
<option value="04" <?php
    if ($month_to_show == "04") echo "selected"; ?>><?php
    _e("April"); ?></option>
<option value="05" <?php
    if ($month_to_show == "05") echo "selected"; ?>><?php
    _e("May"); ?></option>
<option value="06" <?php
    if ($month_to_show == "06") echo "selected"; ?>><?php
    _e("June"); ?></option>
<option value="07" <?php
    if ($month_to_show == "07") echo "selected"; ?>><?php
    _e("July"); ?></option>
<option value="08" <?php
    if ($month_to_show == "08") echo "selected"; ?>><?php
    _e("August"); ?></option>
<option value="09" <?php
    if ($month_to_show == "09") echo "selected"; ?>><?php
    _e("September"); ?></option>
<option value="10" <?php
    if ($month_to_show == "10") echo "selected"; ?>><?php
    _e("October"); ?></option>
<option value="11" <?php
    if ($month_to_show == "11") echo "selected"; ?>><?php
    _e("November"); ?></option>
<option value="12" <?php
    if ($month_to_show == "12") echo "selected"; ?>><?php
    _e("December"); ?></option>
</select>
<select name="year_to_show">
<?php
    $it_year   = 2013;
    $last_year = date('Y') + 1;
    while ($it_year < $last_year)
    {
        echo '<option value="' . $it_year . '" ';
        if ($year_to_show == $it_year) echo "selected";
        echo '>' . $it_year . '</option>';
        $it_year++;
    }

?>
</select>
<input type="submit" value="<?php
    _e("Change Date", "hrm-work-tracking"); ?>" />
</form>

<hr>
<style>td{border:1px #DDD solid;}</style>
<table>
<tr><td   style="width:400px;"><?php
    _e("Title", "hrm-work-tracking"); ?></td><td  style="width:400px;"><?php
    _e("Excerpt", "hrm-work-tracking"); ?></td><td><?php
    _e("Time", "hrm-work-tracking"); ?></td></tr>

<?php
    if ($the_query->have_posts()): ?>

  <!-- pagination here -->

  <!-- the loop -->
  <?php
        while ($the_query->have_posts()):
            $the_query->the_post(); ?>
   <tr> <td><b><?php
            the_title(); ?></b></td><td><?php
            the_excerpt(); ?></td><td>
   <?php
            $time      = get_post_meta(get_the_ID() , "_time_spend_" . $user_to_show, true);
            $full_time = $full_time + $time;
            $time      = round($time / 60, 2);
            echo $time . " " . __("minutes", "hrm-work-tracking");
?>
   </td></tr>
  <?php
        endwhile; ?>
  <!-- end of the loop -->

  <!-- pagination here -->

  <?php
        wp_reset_postdata(); ?>

<?php
    else: ?>
  <p><?php
        _e('Sorry, no posts matched your criteria.'); ?></p>
<?php
    endif; ?>
<tr><td></td><td></td><td><?php
    echo round($full_time / 3600, 2);
    echo " " . __("hours", "hrm-work-tracking"); ?></td></tr>
</table><?php
}

?>