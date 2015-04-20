<?php

/**
 * 文件处理辅助类
 * created by maxin
 * date: 2015/4/11
 */
class FileUtil {

    /**
     * 定义文件打开方式—只读
     */
    const READ_MODE = 'r';
    /**
     * @var string 文件名
     */
    private $fileName;

    /**
     * @var string 文件编码方式
     */
    private $encoding;

    /**
     * @var object 读取文件的文件指针
     */
    private $fileReader;

    /**
     * 构造函数
     * @param string $fileName 文件名
     * @param string $encoding 文件编码方式，默认utf8
     */
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