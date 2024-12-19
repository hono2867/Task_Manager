<?php
require 'functions.php';

class Task
{
	public static function all()
	{
		return loadTasks();
	}

	public static function find($id)
	{
		$tasks = loadTasks();
		foreach($tasks as $task) {
			if ($task['id'] == $id) {
				return $task;
			}
		}
		return null;
	}

	public static function create($title, $description)
	{
		$tasks = loadTasks();
		$id = count($tasks) + 1;
		$tasks[] = ['id' => $id, 'title' => $title, 'description' =>
$description];
		saveTasks($tasks);
	}

	public static function update($id,$title,$description)
	{
		$tasks = loadTasks();
		foreach ($tasks as &$task) {
			if ($task['id'] == $id) {
				$task['title'] = $title;
				$task['description'] = $description;
				break;
			}
		}
		saveTasks($tasks);
	}

	public static function delete($id)
	{
		$tasks = loadTasks();
		$tasks = array_filter($tasks, function($task) use ($id) {
			return $task['id'] != $id;
		});
		saveTasks($tasks);
	}
}
?>