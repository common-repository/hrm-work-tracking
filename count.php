<?php
/**
* Class and Function List:
* Function list:
* - show_work()
* Classes list:
*/
if ($in_pause == "yes")
{
	$statusword = __('break', 'hrm-work-tracking');
	echo "<font color=\"#0000ff\">";
	show_work($anfang_pause, $statusword, $pausenkonto);
	echo "</font>";
}
else
{
	$statusword = __('Work', 'hrm-work-tracking');
	if ($ueberstunden)
	{
		echo "<font color=\"#ff0000\">";
		show_work($anfang, $statusword, $pausenkonto);
		echo "</font>";
	}
	else
	{
		show_work($anfang, $statusword, $pausenkonto);
	}
}

?>


<?php
function show_work($anfang, $statusword, $pausenkonto)
{
?>

		<div id="cpcontainer">&nbsp;</div>

<script type="text/javascript">

var workingtime=new dcountup("<?php
	// $datum= change_time_to_js($datum);
	// $anfang=date("m d, Y h:s:i",$datum);
	echo $anfang;
?>", "days")

workingtime.oncountup=function(result){

var mycountainer=document.getElementById("cpcontainer")
	mycountainer.innerHTML="<?php
	echo $statusword; ?> "+result['days']+" <?php
	_e('days', 'hrm-work-tracking'); ?> "+result['hours']+" <?php
	_e('hours', 'hrm-work-tracking'); ?> "+result['minutes']+" <?php
	_e('minutes', 'hrm-work-tracking'); ?> "+result['seconds']+" <?php
	_e('seconds', 'hrm-work-tracking'); ?>"
}

</script>	
<?php
	if ($pausenkonto > 0 && $statusword == __('Work', 'hrm-work-tracking'))
	{
		echo "<font color=\"#0000ff\">- " . round(($pausenkonto / 60) , 0) . " " . __('minutes', 'hrm-work-tracking') . " " . __('break', 'hrm-work-tracking') . "</font>";
	}
}

?>