<?php
ob_start(); //出力バッファを開始
require'header.php';
require'Task.php';


$task = Task::find($_GET['id']);
$error = null;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	Task::update($_GET['id'], $_POST['title'], $_POST['description']);
	if (!$error) {
		header('Location: index.php');
		exit;
	}
}
ob_end_flush(); //出力バッファをフラッシュ
?>


<h2>Edit Task</h2>
<?php if ($error): ?>
    <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?> 

<form method="post">
	<label>Title</label>
	<input type="text" name="title" value="<?php echo htmlspecialchars($task['title']); ?>" required>
	<label>Description</label>
	<textarea name="description" required><?php echo htmlspecialchars($task['description']); ?></textarea>
	<button type="submit">Update</button>
</form>


<?php require'footer.php'; ?>	