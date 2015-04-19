<?php

/**
 *  IFileDiffer
 *  定义接口，处理文件diff的接口描述
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

    /**
     * 发生错误时触发
     * @param string $errMsg 错误信息
     * @param object $info 错误信息中包含的对象
     * @return mixed
     */
    function  onError($errMsg, $info = null);

    /**
     * 获取diff后的结果
     * @return mixed
     */
    function  getResult();

    /**
     * 返回是否有错误发生
     * @return mixed
     */
    function  isError();
}