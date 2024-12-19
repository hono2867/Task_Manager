<?php
function loadTasks(){
	$json = file_get_contents('tasks.json');
	return json_decode($json, true);
}

function saveTasks($tasks){
	$json = json_encode($tasks, JSON_PRETTY_PRINT);
	file_put_contents('tasks.json', $json);
}
?>