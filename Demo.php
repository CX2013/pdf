<?php

/**
 * diff php
 * created by maxin
 * date: 2015/4/4
 */

require_once('const.php');
require_once('Util/ParamHandle.php');
require_once('Differ/DiffFactory.php');
//获取命令行的参数
$parmas = ParamHandle::getParams($argv);
//判断参数是否解析正确
if (false === $parmas['status']) {
    printf($parmas['msg']);
    exit;
}
$parmas    = $parmas['data'];
$lefFile   = $parmas['-l'];
$rightFile = $parmas['-r'];
$extension = $parmas['-t'];
$differ    = DiffFactory::getDiffer($extension);
$differ->receiveFile($lefFile, $rightFile);
$differ->handleDiff();
$result = $differ->getResult();
printf($result);
