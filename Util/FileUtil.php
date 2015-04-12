<?php

/**
 * 文件处理辅助类
 * created by guozhucheng
 * date: 2015/4/11
 */
class FileUtil {

    const READ_MODE = 'r';
    private $fileName;

    private $fileReader;

    function  __construct($fileName) {
        $this->fileName = $fileName;
    }

    /**
     * 从文件里获取一行信息
     * @return bool|string
     */
    function  getLine() {
        if (null == $this->fileReader) {
            $this->fileReader = fopen($this->fileName, self::READ_MODE);
        }
        if (false === $this->fileReader) {
            return false;
        }

        return fgets($this->fileReader);
    }


    static function  getExtenson($fileName) {
        $info = pathinfo($fileName);

        return $info['extension'];
    }
}