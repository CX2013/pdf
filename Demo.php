<?php
/**
 * created by guozhucheng
 * date: 2015/4/6
 */

ini_set('memory_limit', '-1');
require_once 'Diff.php';

$left = $_SERVER['argv'][1];
$right = $_SERVER['argv'][2];

$leftContent  = file_get_contents($left);
$rightContent = file_get_contents($right);

$diff = new Differ($leftContent, $rightContent);
$out  = $diff->fetch_diff();
var_dump($out);