<?php
require_once('JsonDiffer.php');
require_once('XmlDiffer.php');
require_once(__DIR__ . "/../Util/FileUtil.php");

/**
 *  DiffFactory
 *  工厂类，获取diff实例化的对象
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
    const  EXTENSION_SUPPORT_JSON = "json";

    /**
     * 支持diff的类型 xml
     */
    const  EXTENSION_SUPPORT_XML = "xml";

    /**
     * 根据文件名称，通过工厂创建合适的Differ实现类
     * $parm string $ext 文件类型
     * @return bool|IFileDiffer 工厂创建失败返回false，否则返回实例类名
     */
    public static function getDiffer($ext) {
        switch (strtolower($ext)) {//json
            case self::EXTENSION_SUPPORT_JSON:
                return new JsonDiffer();
                break;
            case self::EXTENSION_SUPPORT_XML:
                return new XmlDiffer();
                break;
            default:
                //不支持的扩展名
                printf(self::ERROR_MSG_UNSUPPORT_EXTENSION);

                return false;
                break;
        }
    }
}
