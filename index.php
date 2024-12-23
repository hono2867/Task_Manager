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
			<strong class="task-title" data-id="<?php echo $task['id']; ?>"><?php echo htmlspecialchars($task['title']??'', ENT_QUOTES, 'UTF-8'); ?>
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
    let taskDetailContainer = document.createElement('div'); // タスク詳細を格納するコンテナ

    taskItem.appendChild(taskTitle);
    taskItem.appendChild(taskDetailContainer);
    document.getElementById('taskListContainer').appendChild(taskItem);

    let addButton = document.createElement('button');
    addButton.textContent = '＋';
    taskItem.appendChild(addButton); // タイトルの下に配置

    addButton.addEventListener('click', function() {
        let taskDetail = document.createElement('div');
        taskDetail.classList.add('task-detail'); // 横並びにするクラスを追加

        let checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        let taskInput = document.createElement('input');
        taskInput.type = 'text';
        taskInput.placeholder = 'Enter task detail';
        let editButton = document.createElement('button');
        editButton.textContent = 'edit';
        let deleteButton = document.createElement('button');
        deleteButton.textContent = 'delete';

        taskDetail.appendChild(checkbox);
        taskDetail.appendChild(taskInput);
        taskDetail.appendChild(editButton);
        taskDetail.appendChild(deleteButton);
        taskDetailContainer.appendChild(taskDetail); // タスク詳細をコンテナに追加

        // 編集ボタンのクリックイベント
        editButton.addEventListener('click', function() {
            taskInput.disabled = !taskInput.disabled; // 入力フィールドの有効/無効を切り替え
            if (!taskInput.disabled) {
                taskInput.focus(); // 編集モードの場合、入力フィールドにフォーカス
            } else {
                task.details = taskInput.value;
                updateTask(task.title, task.details); // 編集内容を保存
            }
        });

        // 削除ボタンのクリックイベント
        deleteButton.addEventListener('click', function() {
            taskDetailContainer.removeChild(taskDetail); // タスク詳細を削除
            if (taskDetailContainer.children.length === 0) { // タスク詳細が全て削除された場合
                document.getElementById('taskListContainer').removeChild(taskItem); // タスク全体を削除
            }
            removeTask(task);
        });
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