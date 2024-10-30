<?php
/**
* Class and Function List:
* Function list:
* - add_dashboard_task_hrm_widgets()
* - dashboard_task_hrm_widget_function()
* - dashboard_todo_hrm_widget_function()
* Classes list:
*/
// as in version 1.2
function add_dashboard_task_hrm_widgets()
{
    wp_add_dashboard_widget('dashboard_task_hrm_widget', // Widget slug.
    'Dashboard Task Widget', // Title.
    'dashboard_task_hrm_widget_function'

    // Display function.
    );
    wp_add_dashboard_widget('dashboard_todo_hrm_widget', // Widget slug.
    'To Do List', // Title.
    'dashboard_todo_hrm_widget_function'

    // Display function.
    );
}

add_action('wp_dashboard_setup', 'add_dashboard_task_hrm_widgets');

function dashboard_task_hrm_widget_function()
{
    echo "<textarea name='current_task' rows=\"4\" cols=\"50\" disabled=disabled>Eine Textarea an dieser Stelle ist sinnlos. maximal eine für notizen</textarea>";
    echo "<br /><input type=submit value=\"start task\" class=\"pause\" /><input type=\"submit\" value=\"end task\" class=\"finished\" />";
}

function dashboard_todo_hrm_widget_function()
{
    echo "<table>";
    echo "<tr><th>Datenbankanbindung hier f&uuml;r </th><td><input value='add to tasks' type=submit class=\"button\" /></td></tr>";
    echo "<tr><th>Counter f&uuml;r Task</th><td><input value='add to tasks' type=submit class=\"button\" /></td></tr>";
    echo "</table>";
?>
<br />
Es fehlt ein Backend wo die AUfgaben eingetragen werden.<br />
Diese werden hier gelistet und man kann Sie in den Task counter (oben) uebernehmen.<bR />
Der einfachheit halber, sollten die Aufgaben nur in der To Do list verwaltet werden.<br />
Und zwar sowohl von einem selbst(eigene) als auch vom admin(alle).<br /><b>Schwierig wirds bei paralelllaufenden tasks</b>
<br />Parallel laufende gibt es nicht! weil man real nur einen machen kann. das heisst man muss wenn man den task unterbricht ihn beenden und den anderen starten.<?php
}

?>