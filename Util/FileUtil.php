<?php

/**
 * 文件处理辅助类
 * created by guozhucheng
 * date: 2015/4/11
 */
class FileUtil {

    const READ_MODE = 'r';
    private $fileName;
    private $encoding;

    private $fileReader;

    function  __construct($fileName, $encoding = 'utf8') {
        $this->fileName = $fileName;
        $this->encoding = $encoding;
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

    /**
     * 获取文件的扩展名
     * @param string $fileName
     * @return string 扩展名
     */
    static function  getExtension($fileName) {
        $info = pathinfo($fileName);

        return $info['extension'];
    }

    /**
     * 获取文件内容
     * @param string $fileName 文件名
     * @return bool|string
     */
    static function  getFileContent($fileName) {
        if (!file_exists($fileName)) {
            return false;
        }

        return file_get_contents($fileName);
    }

}