<?php
require_once("IFileDiffer.php");
require_once(__DIR__ . "/../Util/FileUtil.php");

/**
 * 处理json文件的diff
 */
class JsonDiffer implements IFileDiffer {

    /**
     * 错误信息：文件不存在
     */
    const FILE_NOT_EXISTS_MSG = "FILE %s NOT EXIST\n";

    /**
     * 错误信息，json文件格式错误
     */
    const JSON_FORMAT_ERROR = "JSON FORMAT ERROR\n";

    /**
     * 结果信息输出：输出文件行行数
     */
    const RESULT_MSG_LINE_COUNT = "left file line count %s ,right file line count  %s \n";

    /**
     * 结果信息输出：diff 结果描述
     */
    const RESULT_MSG_SUMMARY = "there are %s diff[s] in %s line[s], next is the detail differences.\n";

    /**
     *结果信息，diff 右文件相对于左文件增量信息
     */
    const RESULT_MSG_DIFF_ADD = "+++++++++++++\n*** line%s:format\n";
    /**
     * 结果信息,diff右文件相对于左文件减少信息
     */
    const RESULT_MSG_DIFF_RMV = "-------------\n*** line%s:format\n";


    /**
     * @var left 文件名
     */
    private $leftFile;
    /**
     * @var right 文件名
     */
    private $rightFile;

    /**
     * @var 左文件解析后的json array
     */
    private $leftJson;
    /**
     * @var 右文件解析后的json array
     */
    private $rightJson;

    /**
     * @var diff后的结果
     * array(
     * 'diffadd'=>array(),
     * 'diffrmv'=>array(),
     * )
     */
    private $diffResult;
    /**
     * 是否有出错信息，默认为false
     * @var bool
     */
    private $isError = false;

    /**
     * 将文件信息转换为array
     * @param $fileName
     * @param $jsonArray
     */
    private function  fileToArray($fileName, &$jsonArray) {
        $jsonArray = array();
        if (!file_exists($fileName)) {
            $this->onError(sprintf(self::FILE_NOT_EXISTS_MSG, $fileName));
            $this->isError = true;

            return;
        }
        $lineNum  = 1;
        $fileUtil = new FileUtil($fileName);
        while ($line = $fileUtil->getLine()) {
            $jsonInfo = json_decode($line, true);
            if (!$jsonInfo) {
                $this->onError(self::JSON_FORMAT_ERROR, $line);

                return;
            }
            $jsonArray[$lineNum] = $jsonInfo;
            $lineNum++;
        }
    }


    /**
     * 接受左右文件
     * @param $leftFile
     * @param $rightFile
     */
    function receiveFile($leftFile, $rightFile) {
        $this->leftFile  = $leftFile;
        $this->rightFile = $rightFile;

        $this->fileToArray($leftFile, $this->leftJson);
        $this->fileToArray($rightFile, $this->rightJson);

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
        $result .= sprintf(self::RESULT_MSG_LINE_COUNT, count($this->leftJson), count($this->rightJson));

        foreach ($this->diffResult as $lineIndex => $diffInfo) {
            $result .= sprintf(self::RESULT_MSG_SUMMARY, count($diffInfo['diffadd']) + count($diffInfo['diffrmv']), $lineIndex);
            if (!empty($diffInfo['diffadd'])) {
                $result .= sprintf(self::RESULT_MSG_DIFF_ADD, $lineIndex);
                $result .= json_encode($diffInfo['diffadd']) . "\n";
            }
            if (!empty($diffInfo['diffrmv'])) {
                $result .= sprintf(self::RESULT_MSG_DIFF_RMV, $lineIndex);
                $result .= json_encode($diffInfo['diffrmv']) . "\n";
            }
        }

        return $result;
    }

    /**
     * 进行diff操作
     * @return mixed
     */
    function  handleDiff() {
        //计算你的最大行数
        $maxLineCount = max(count($this->leftJson), count($this->rightJson));
        for ($lineIndex = 1; $lineIndex <= $maxLineCount; $lineIndex++) {
            $left    = empty($this->leftJson[$lineIndex]) ? array() : $this->leftJson[$lineIndex];
            $right   = empty($this->rightJson[$lineIndex]) ? array() : $this->rightJson[$lineIndex];
            $diffRmv = array_diff($left, $right);
            $diffAdd = array_diff($right, $left);
            if (!(empty($diffAdd) && empty($diffRmv))) {
                $this->diffResult[$lineIndex]['diffadd'] = $diffAdd;
                $this->diffResult[$lineIndex]['diffrmv'] = $diffRmv;
            }
        }
    }


    function  isError() {
        return $this->isError;
    }
}