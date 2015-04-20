<?php
require_once("IFileDiffer.php");
require_once(__DIR__ . "/../Util/FileUtil.php");
require_once(__DIR__ . "/../Util/DiffUtil.php");
require_once(__DIR__ . "/../Util/XML2Array.php");
require_once(__DIR__ . "/../const.php");

/**
 * created by maxin
 * date: 2015/4/19
 */
class XmlDiffer implements IFileDiffer {
    /**
     * 结果信息输出：diff 结果描述
     */
    const RESULT_MSG_SUMMARY = "there are %s diff[s], next is the detail differences.\n";
    /**
     *结果信息，diff 右文件相对于左文件增量信息
     */
    const RESULT_MSG_DIFF_ADD = "+++++++++++++\n";
    /**
     * 结果信息,diff右文件相对于左文件减少信息
     */
    const RESULT_MSG_DIFF_RMV = "-------------\n";
    /**
     * xml 解析失败
     */
    const XML_RESOLVE_FAILED = "xml resolve failed\n";

    /**
     * @var string left文件名
     */
    private $leftFile;
    /**
     * @var string right文件名
     */
    private $rightFile;
    private $leftArr;
    private $rightArr;

    /**
     * 是否有出错信息，默认为false
     * @var bool
     */
    private $isError = false;
    private $diffResult;

    /**
     * 接受文件
     * @param string  $leftFile 待diff 左文件
     * @param  string $rightFile 待diff 右文件
     * @return
     */
    function receiveFile($leftFile, $rightFile) {
        $this->leftFile  = $leftFile;
        $this->rightFile = $rightFile;

        $this->fileToArray($leftFile, $this->leftArr);
        $this->fileToArray($rightFile, $this->rightArr);
    }

    /**
     * 进行diff操作
     * @return mixed
     */
    function  handleDiff() {
        //对于xml文件，直接进行array比较
        $addCount = 0;
        $rmvCount = 0;
        $addDiff  = DiffUtil::arrayDiffMulti($this->leftArr, $this->rightArr, $addCount);
        $rmvDiff  = DiffUtil::arrayDiffMulti($this->rightArr, $this->leftArr, $rmvCount);
        if (!empty($addDiff) || !empty($rmvDiff)) {
            $this->diffResult['diffadd']      = $addDiff;
            $this->diffResult['diffaddcount'] = $addCount;
            $this->diffResult['diffrmv']      = $rmvDiff;
            $this->diffResult['diffrmvcount'] = $rmvCount;
        }
    }

    function  onError($errMsg, $info = null) {
        printf($errMsg);
        if ($info) {
            printf(json_encode($info));
        }
        $this->isError = true;
    }


    function  getResult() {
        $result = '';
        $result .= sprintf(self::RESULT_MSG_SUMMARY, count($this->leftArr) + count($this->rightArr));
        if ($this->diffResult['diffaddcount'] > 0) {
            $result .= self::RESULT_MSG_DIFF_ADD;
            $this->showArrayMulti($this->leftArr, $result);
        }
        if ($this->diffResult['diffrmvcount'] > 0) {
            $result .= self::RESULT_MSG_DIFF_RMV;
            $this->showArrayMulti($this->rightArr, $result);
        }

        return $result;
    }

    function  isError() {
        return $this->isError;
    }

    private function fileToArray($fileName, &$objArr) {
        $fileInfo = FileUtil::getFileContent($fileName);
        if (false === $fileInfo) {
            $this->onError(sprintf(FILE_NOT_EXISTS_MSG, $fileName));
            $this->isError = true;

            return;

        }
        try {
            $objArr = XML2Array::createArray($fileInfo);

        } catch (Exception $e) {
            $this->onError(self::XML_RESOLVE_FAILED, $e);

            return;
        }
    }

    /**
     * 递归显示xmldiff 后的数据
     * @param $arr
     */
    private function showArrayMulti($arr, &$retStr) {
        if (!is_array($arr)) {
            return;
        }
        foreach ($arr as $key => $val) {
            //如果不是最后的节点，则打印节点信息
            if (is_array($val)) {
                $retStr .= sprintf("%s->", $key);
                $this->showArrayMulti($val, $retStr);
            } //如果是叶子节点，则打打印叶子节点，并输出
            else {
                $retStr .= sprintf("%s\n", $key);
                $retStr .= sprintf("%s\n", $val);
            }
        }
    }
}