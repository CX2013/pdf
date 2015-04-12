<?php
require_once('JsonDiffer.php');
require_once(__DIR__ . "/../Util/FileUtil.php");

/**
 * created by guozhucheng
 * date: 2015/4/12
 */
class DiffFactory {

    /**
     * 错误信息：左右两个文件的扩展名不同
     */
    const  ERROR_MSG_WRONG_EXT = "left file and  right file extension is different\n";
    /**
     * 错误信息：不支持的文件名扩展类型
     */
    const  ERROR_MSG_UNSUPPORT_EXTENSION = "unsupport file extension\n";

    /**
     * 扩展名支持：json
     */
    const  EXTENSON_SUPPORT_JSON = "json";

    /**
     * 根据文件名称，通过工厂创建合适的Differ实现类
     * @param string $leftFile 左文件名
     * @param string $rightFile 右文件名
     * @return bool|IFileDiffer 工厂创建失败返回false，否则返回实例类名
     */
    public static function getDiffer($leftFile, $rightFile) {
        $leftExt  = FileUtil::getExtenson($leftFile);
        $rightExt = FileUtil::getExtenson($rightFile);
        //扩展名不同
        if ($leftExt !== $rightExt) {
            printf(self::ERROR_MSG_WRONG_EXT);

            return false;
        }
        switch ($leftExt) {
            //json
            case self::EXTENSON_SUPPORT_JSON:
                return new JsonDiffer();
                break;
            default:
                //不支持的扩展名
                printf(self::ERROR_MSG_UNSUPPORT_EXTENSION);

                return false;
                break;
        }
    }
}
