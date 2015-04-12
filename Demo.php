<?php
/**
 * created by guozhucheng
 * date: 2015/4/6
 */

ini_set('memory_limit', '-1');
require_once 'Differ/DiffFactory.php';

$left  = $_SERVER['argv'][1];
$right = $_SERVER['argv'][2];

$diff = DiffFactory::getDiffer($left, $right);
if (false !== $diff) {
    $diff->receiveFile($left, $right);
    $diff->handleDiff();
    if(!$diff->isError()){
        printf($diff->getResult());
    }
}

//
//$differ = new JsonDiffer();
//$differ->receiveFile($left, $right);
//$differ->handleDiff();
//printf($differ->getResult());
//var_dump($_SERVER['argv']);

//
//var_dump($argv);
//
//$leftContent  = file_get_contents($left);
//$rightContent = file_get_contents($right);
//
//$diff = new Differ($leftContent, $rightContent);
//$out  = $diff->fetch_diff();
//var_dump($out);