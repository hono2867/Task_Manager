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
	<button type="submit">Create</button>
</form>

<div id="taskListContainer"></div>

<ul>
	<?php if (!empty($tasks)): ?>
	<?php foreach ($tasks as $task): ?>
		<li>
			<strong class="task-title" data-id="<?php echo $task['id']; ?>"><?php echo htmlspecialchars($task['title']); ?>
			</strong>
			<div class="task-details" id="task-<?php echo $task['id']; ?>" style="display: none;">
				<ul>
				<li><strong>Description:</strong> <?php echo htmlspecialchars($task['description']); ?></li>
                        <li><a href="edit.php?id=<?php echo $task['id']; ?>">Edit</a></li>
                        <li><a href="delete.php?id=<?php echo $task['id']; ?>">Delete</a></li>	
				</ul>
			</div>
		</li>
	<?php endforeach; ?>
	<?php else: ?>
		<li>No tasks available.</li>
	<?php endif; ?>
</ul>


<?php require 'footer.php'; ?>
 	
<script>
	// ページが読み込まれたときにタスクを復元
document.addEventListener('DOMContentLoaded', function() {
    let tasks = JSON.parse(localStorage.getItem('tasks')) || [];
    tasks.forEach(function(task) {
        addTaskToDOM(task);
    });
});

document.getElementById('createTaskButton').addEventListener('click', function() {
    document.getElementById('createTaskForm').style.display = 'block';
});

document.getElementById('createTaskForm').addEventListener('submit', function(event) {
    event.preventDefault();
    let title = document.querySelector('input[name="title"]').value;
    let task = { title: title, details: '' };
    addTaskToDOM(task);
    saveTask(task);
    document.querySelector('input[name="title"]').value = ''; // フォームをリセット
});

function addTaskToDOM(task) {
    let taskItem = document.createElement('div');
    let taskTitle = document.createElement('span');
    taskTitle.textContent = task.title;
    let addButton = document.createElement('button');
    addButton.textContent = '＋';

    taskItem.appendChild(taskTitle);
    taskItem.appendChild(addButton);
    document.getElementById('taskListContainer').appendChild(taskItem);

    addButton.addEventListener('click', function() {
        let editButton = document.createElement('button');
        editButton.textContent = 'edit';
        let deleteButton = document.createElement('button');
        deleteButton.textContent = 'delete';

        taskItem.appendChild(editButton);
        taskItem.appendChild(deleteButton);

        editButton.addEventListener('click', function() {
            let taskInput = document.createElement('input');
            taskInput.type = 'text';
            taskInput.value = task.details;
            taskItem.insertBefore(taskInput, editButton);
            taskInput.focus();

            taskInput.addEventListener('blur', function() {
                task.details = taskInput.value;
                updateTask(task.title, task.details);
                taskItem.removeChild(taskInput);
            });
        });

        deleteButton.addEventListener('click', function() {
            document.getElementById('taskListContainer').removeChild(taskItem);
            removeTask(task);
        });

        addButton.style.display = 'none'; // ＋ボタンを非表示
    });
}

function saveTask(task) {
    let tasks = JSON.parse(localStorage.getItem('tasks')) || [];
    tasks.push(task);
    localStorage.setItem('tasks', JSON.stringify(tasks));
}

function updateTask(title, newDetails) {
    let tasks = JSON.parse(localStorage.getItem('tasks')) || [];
    tasks = tasks.map(task => {
        if (task.title === title) {
            task.details = newDetails;
        }
        return task;
    });
    localStorage.setItem('tasks', JSON.stringify(tasks));
}

function removeTask(taskToRemove) {
    let tasks = JSON.parse(localStorage.getItem('tasks')) || [];
    tasks = tasks.filter(task => task.title !== taskToRemove.title);
    localStorage.setItem('tasks', JSON.stringify(tasks));
}
</script>