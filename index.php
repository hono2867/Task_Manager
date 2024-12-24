<?php
ob_start(); //出力バッファ開始
require'header.php';
require'Task.php';

$error = null;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$error = Task::create($_POST['title'], $_POST['description']);
	if (!$error) {
	header('Location: index.php');
	exit;
	}
}


$tasks = Task::all();
ob_end_flush(); //出力バッファをフラッシュ
?>



<h2>Task List</h2>


<h3>Create NewTask</h3>

<?php if ($error): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?> 
<button id="createTaskButton">Create New Task</button>

<form id="createTaskForm" method="post" style="display: none;">
	<label>Title</label>
	<input type="text" name="title" required>
	<label>Description</label>
	<textarea name ="description" required></textarea>
	<button type="submit">Create</button>
</form>

<ul>
	<?php if (!empty($tasks)): ?>
	<?php foreach ($tasks as $task): ?>
		<li>
			<strong><?php echo htmlspecialchars($task['title']); ?>
			</strong>
			<p><?php echo htmlspecialchars($task['description']); ?></p>
			<a href="edit.php?id=<?php echo $task['id']; ?>">Edit</a>
			<a href="delete.php?id=<?php echo $task['id']; ?>">Delete</a>
		</li>
	<?php endforeach; ?>
	<?php else: ?>
		<li>No tasks available.</li>
	<?php endif; ?>
</ul>


<?php require 'footer.php'; ?>
 	
