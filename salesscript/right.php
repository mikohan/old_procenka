<?php if($_SESSION['type'] == 'admin'): ?>
<nav>
	<ul class="left-adm-bar-sales">

		<!-- <li class="nav-item">
        <a class="nav-link pl-0" href="index.php" data-toggle="tooltip" data-placement="bottom" title="Список контактов"><i class="fa fa-edit"></i></a>
    </li> -->
		<!-- <li class="nav-item">
        <a class="nav-link pl-0" href="people_edit.php" data-toggle="tooltip" data-placement="bottom" title="Создать контакт"><i class="fa fa-university"></i></a>
    </li> -->
		<li><a class="nav-link pl-0" href="index.php" data-toggle="tooltip"
			data-placement="bottom" title="Интерфейс"><span><i class="fa fa-home"></i><span
					id="result1" class="badge badge-notify"></span></span></a></li>
		<li><a class="nav-link pl-0" href="../index.php" data-toggle="tooltip"
			data-placement="bottom" title="Проценка"><i class="fa fa-keyboard-o"></i></a>
		</li>


		<li><a class="nav-link pl-0" href="synonymInsert.php"
			data-toggle="tooltip" data-placement="bottom"
			title="Редактирование словаря синонимов"><span><i
					class="fa fa-list-alt"></i><span id="result1"
					class="badge badge-notify"></span></span></a></li>
		<li><a class="nav-link pl-0" href="caseInsert.php"
			data-toggle="tooltip" data-placement="bottom"
			title="Редактирование словаря ситуаций"><span><i class="fa fa-paw"></i><span
					id="result1" class="badge badge-notify"></span></span></a></li>

	</ul>
</nav>
<?php else: ?>
<nav>
	<ul class="left-adm-bar-sales">
		<li><a class="nav-link pl-0" href="index.php" data-toggle="tooltip"
			data-placement="bottom" title="Интерфейс"><span><i class="fa fa-home"></i><span
					id="result1" class="badge badge-notify"></span></span></a></li>
		<li><a class="nav-link pl-0" href="../index.php" data-toggle="tooltip"
			data-placement="bottom" title="Проценка"><i class="fa fa-keyboard-o"></i></a>
		</li>
	</ul>
</nav>
<?php endif ?>