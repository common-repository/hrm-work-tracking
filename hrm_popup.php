<?php
/**
* Class and Function List:
* Function list:
* - my_users_popout_menu()
* Classes list:
*/
add_action('admin_menu', 'my_users_popout_menu');

function my_users_popout_menu()
{
	add_users_page('Pop Out', 'Pop Out', 'read', 'hrm-pop-out', 'dashboard_hrm_wt_function');
}
?>