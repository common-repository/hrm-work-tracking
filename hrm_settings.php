<?php
/**
* Class and Function List:
* Function list:
* - my_users_settings_menu()
* - hrm_settings_page()
* Classes list:
*/
add_action('admin_menu', 'my_users_settings_menu');

function my_users_settings_menu()
{
    global $current_user;
    $uid   = $current_user->ID;
    $hrmid = get_option('human_resources_department');
    if ($hrmid == $uid)
    {
        add_users_page(__('Settings', 'hrm-work-tracking') , __('Settings', 'hrm-work-tracking') , 'activate_plugins', 'hrm-settings', 'hrm_settings_page');
    }
}

function hrm_settings_page()
{
    global $current_user;
    $uid            = $current_user->ID;
    $hrmid          = get_option('human_resources_department');

    // --------------------------------------------------------------------
    if (isset($_POST['remote']))
    {
        $user_remote_id = $_POST['remote'];
        $user_remote    = get_userdata($user_remote_id);
        if (isset($_POST['logon']))
        {
            user_begins_to_work("", $user_remote);
        }
        else if (isset($_POST['logoff']))
        {
            user_stops_to_work($user_remote_id);
        }
    }

    if (isset($_POST['remote_logging_option']))
    {
        update_option("remote_logging_option", $_POST["remote_logging_option"]);
    }
    else
    {
    }

    if (isset($_POST['post_measure_logging_option']))
    {
        update_option("post_measure_logging_option", $_POST["post_measure_logging_option"]);
    }
    else
    {
    }

    if (isset($_POST['logoff_logging_option']))
    {
        update_option("logoff_logging_option", $_POST["logoff_logging_option"]);
    }
    else
    {
    }

    if (isset($_POST['auto_logoff_option']))
    {
        update_option("auto_logoff_option", $_POST["auto_logoff_option"]);
    }
    else
    {
    }

    if (isset($_POST['hrm_daily']))
    {
        update_option("hrm_daily", $_POST["hrm_daily"]);
    }
    else
    {
    }

    // --------------------------------------------------------------------
    if (isset($_POST['other_hrms']))
    {
        update_option('other_hrms', $_POST['other_hrms']);
    }
    else
    {
    }

    $datecurrent = date("Y") . "_" . date("F");
    if (isset($_POST['dateshow']))
    {
        $dateshow    = $_POST['dateshow'];
    }
    else
    {
        $dateshow    = date("Y") . "_" . date("F");
        $_POST['dateshow']             = date("Y") . "_" . date("F");
    }

?>
<form method="POST" name="hrm-settings">
<div class="wrap">
<div id="icon-users" class="icon32"></div><h2><?php
    _e('Human Resources Department', 'hrm-work-tracking'); ?> - <?php
    _e('Settings', 'hrm-work-tracking'); ?></h2>
<br />
<?php
    if (isset($_POST['yes_clear_all_times']))
    {
        $all_users   = get_users();
        foreach ($all_users as $user)
        {
            $the_user_id = $user
                ->data->ID;
            reset_times($the_user_id);
        }
    }
    else if (isset($_POST['clear_all_time']))
    {
        echo "<div class=\"error\"> <p>" . __("Clear time of all users?", "hrm-work-tracking") . " </p> ";
        echo "<p><input type=submit name=\"yes_clear_all_times\" class=\"ill\" value=\"" . __("Yes", "hrm-work-tracking") . "\"></p>";
        echo "</div>";
    }

    if (isset($_POST['change_settings']))
    {
        echo "<div class=\"updated\"> <p>" . __("Settings saved.", "hrm-work-tracking") . " </p> </div>";
    }
    else
    {
    }

?>

<div class="postbox" >
<div class="inside">
<?php
    hrd_dashboard();
    echo "</div></div>";
    if ($uid == $hrmid)
    {
        if (!isset($_POST['clear_all_time']))
        {
?>



<div class="postbox" >
<div class="inside">
<input type="submit" class="ill" name="clear_all_time" value="<?php
            _e("Clear all times", "hrm-work-tracking"); ?>" />
</div></div>
<?php
        }
        else
        {
        } ?>
<div class="postbox" >
<div class="inside">
<label for="other_hrms"><?php
        _e('Other HRM employees', 'hrm-work-tracking'); ?></label>
<input type="text" name="other_hrms" value="<?php
        echo get_option('other_hrms'); ?>" /><small><?php
        _e('multiple seperated by comma', 'hrm-work-tracking'); ?></small>
</div></div>

<div class="postbox" >
<div class="inside">
<?php
        $alo = get_option("auto_logoff_option"); ?>
<?php
        echo __("Stop time tracking after certain time", "hrm-work-tracking"); ?> 
<input type="radio" name="auto_logoff_option" value="on" 
<?php
        if ($alo == "on")
        {
            echo "checked=checked";
        }
        else
        {
        } ?>
/> <?php
        echo __("on", "hrm-work-tracking"); ?> <input type="radio" name="auto_logoff_option" value="off" <?php
        if ($alo == "off" || $alo == "")
        {
            echo "checked=checked";
        }
        else
        {
        } ?> /> <?php
        echo __("off", "hrm-work-tracking"); ?> <small>(<?php
        echo __("after a fith of weekly hours.", "hrm-work-tracking"); ?>)</small> </div></div>


<div class="postbox" >
<div class="inside">
<?php
        $llo = get_option("logoff_logging_option"); ?>
<?php
        echo __("Stop time tracking on log out", "hrm-work-tracking"); ?> 
<input type="radio" name="logoff_logging_option" value="on" 
<?php
        if ($llo == "on")
        {
            echo "checked=checked";
        }
        else
        {
        } ?>
/> <?php
        echo __("on", "hrm-work-tracking"); ?> <input type="radio" name="logoff_logging_option" value="off" <?php
        if ($llo == "off" || $llo == "")
        {
            echo "checked=checked";
        }
        else
        {
        } ?> /> <?php
        echo __("off", "hrm-work-tracking"); ?></div></div>


<div class="postbox" >
<div class="inside">
<?php
        $rlo = get_option("remote_logging_option");
?>
Remote Logging <?php
        echo __("is", "hrm-work-tracking"); ?> <input type="radio" name="remote_logging_option" value="on" 
<?php
        if ($rlo == "on")
        {
            echo "checked=checked";
        }
        else
        {
        } ?>
/> <?php
        echo __("on", "hrm-work-tracking"); ?> <input type="radio" name="remote_logging_option" value="off" <?php
        if ($rlo == "off" || $rlo == "")
        {
            echo "checked=checked";
        }
        else
        {
        } ?> /> <?php
        echo __("off", "hrm-work-tracking"); ?></div></div>

<div class="postbox" >
<div class="inside">
<?php
        $pmlo = get_option("post_measure_logging_option");
?>
<?php
        echo __("Time to write posts is", "hrm-work-tracking"); ?> <input type="radio" name="post_measure_logging_option" value="on" 
<?php
        if ($pmlo == "on")
        {
            echo "checked=checked";
        }
        else
        {
        } ?>
/> <?php
        echo __("on", "hrm-work-tracking"); ?> <input type="radio" name="post_measure_logging_option" value="off" <?php
        if ($pmlo == "off" || $pmlo == "")
        {
            echo "checked=checked";
        }
        else
        {
        } ?> /> <?php
        echo __("off", "hrm-work-tracking"); ?>
</div></div>

<div class="postbox" >
<div class="inside">
<?php
        // update_option("hrm_daily", true );
        $pmld = get_option("hrm_daily");
        echo __("Daily Time Stats is ", "hrm-work-tracking"); ?> <input type="radio" name="hrm_daily" value="true" 
<?php
        if ($pmld == "true")
        {
            echo "checked=checked";
        }
        else
        {
        } ?>
/> <?php
        echo __("on", "hrm-work-tracking"); ?> <input type="radio" name="hrm_daily" value="false" <?php
        if ($pmld == "false" || $pmld == "")
        {
            echo "checked=checked";
        }
        else
        {
        } ?> /> <?php
        echo __("off", "hrm-work-tracking"); ?>
 <?php
        echo "<font style='color:red;font-size:9px;'>" . __("Be careful this can produce a lot of records in your Database", "hrm-work-tracking") . "</font>"; ?>
</div></div>



</form>

<div class="postbox" >
<div class="inside">
<?php
        _e("Support-Box", "hrm-work-tracking"); ?>
<br />

<?php
        echo __("Do you like this plugin or need help?", "hrm-work-tracking"); ?><br /><?php
        paypal_spenden_button(); ?><?php
        request_support_button(); ?> 
</div>
<?php
    }
    else
    {
    } ?>

<div class="postbox" >
<div class="inside">
<input type="submit" name="change_settings" value="<?php
    _e("Send Data", "hrm-work-tracking"); ?>" class="pause" />
</div></div>

<?php
}

?>