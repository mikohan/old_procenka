<?php


error_reporting(E_ALL); 
ini_set("display_errors", 1);

require __DIR__. '/insertClass/Inserter.php';
require __DIR__. '/insertClass/InserterTwo.php';
require __DIR__. '/insertClass/InserterOne.php';

//include 'config.php';
include 'functions.php';

$cl3 = new Inserter;
$cl2 = new InserterTwo;
$cl1 = new InserterOne;

//Задаем таблицу с которой работаем
$table = 'price';

$cl3->price_table = $table;
$cl2->price_table = $table;
$cl1->price_table = $table;

$cl3->query_three();
echo '>>>Скрипат отработал по трем словам! <br>';

$cl2->query_three();
echo '>>Скрипат отработал по двум словам! <br>';

$cl1->query_three();
echo '>Скрипат отработал по одному слову! <br>';

echo '<hr>';
$empty = $cl3->getEmpty();
echo '!! Не присвоена категория - ' . count($empty) . ' элементу! <br>';
echo '<hr>';
//$cl3->p($empty);
?>

<table>
    <?php foreach($empty as $e):?>
    <tr>
        <td><?=$e['id']?></td>
        <td><?=$e['name']?></td>
    </tr>
    <?php endforeach; ?>
</table>

