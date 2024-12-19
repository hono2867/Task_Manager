<?php
require'header.php';
require'Task.php';


$task = Task::find($_GET['id']);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	Task::update($_GET['id'], $_POST['title'], $_POST['description']);
	header('Location: index.php');
	exit;
}
?>


<h2>Edit Task</h2>
<form method="post">
	<label>Title</label>
	<input type="text" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
	<label>Description</label>
	<textarea name="description" required><?php echo htmlspecialchars($task['description']); ?></textarea>
	<button type="submit">Update</button>
</form>


<?php require'footer.php'; ?>	