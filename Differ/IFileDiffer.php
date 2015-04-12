<?php

/**
 * created by guozhucheng
 * date: 2015/4/11
 */
interface IFileDiffer {

    /**
     * 接受文件
     * @param string  $leftFile 待diff 左文件
     * @param  string $rightFile 待diff 右文件
     * @return
     */
    function receiveFile($leftFile, $rightFile);

    /**
     * 进行diff操作
     * @return mixed
     */
    function  handleDiff();

    function  onError($errMsg);

    function  getResult();

    function  isError();
}