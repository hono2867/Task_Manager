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
		 // バリデーション
        	$error = self::validate($title, $description);
       		if ($error) {
            	return $error;
        	}
		
		$tasks = loadTasks();
		$id = count($tasks) + 1;
		$tasks[] = ['id' => $id, 'title' => $title, 'description' =>
$description];
		saveTasks($tasks);
		return null;
	}

	public static function update($id,$title,$description)
	{
		// バリデーション
        	$error = self::validate($title, $description);
        	if ($error) {
            	return $error;
        	}
		
		$tasks = loadTasks();
		foreach ($tasks as &$task) {
			if ($task['id'] == $id) {
				$task['title'] = $title;
				$task['description'] = $description;
				break;
			}
		}
		saveTasks($tasks);
		return null;
	}

	public static function delete($id)
	{
		$tasks = loadTasks();
		$tasks = array_filter($tasks, function($task) use ($id) {
			return $task['id'] != $id;
		});
		saveTasks($tasks);
	}

	// バリデーションメソッドの追加
    	private static function validate($title, $description)
    	{
        if (empty($title) || trim($title) === "") {
            return "タイトルを入力してください";
        }
    	}
 
}
?>
