<?php
require'header.php';
require'Task.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	Task::create($_POST['title'], $_POST['description']);
	header('Location: index.php');
	exit;
}


$tasks = Task::all();
?>



<h2>Task List</h2>


<h3>Create NewTask</h3>
<form method="post">
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
 	
