<?php
/**
 * 常量定义
 * created by guozhucheng
 * date: 2015/4/17
 */

//没输入参数时的提示
define('MSG_SHOW_NO_ARGC', "diff: missing file argument\nTry `php diff.php --help' for more information.\n");

//help 信息输出
define('MSG_SHOW_HELP_INFO', "php diff.php -t [json|xml] -l leftFile -r rightFile -o outFile -e utf8\n" . "-t	待DIFF的数据/文件类型，测试题要求实现json/xml两种类型的DIFF功能\n" . "-l	待DIFF的左文件 \n" . "-r	待DIFF的右文件\n" . "-o	存储对比结果的文件\n" . "-e	编码格式，支持gbk和utf8两种格式，默认为utf8\n");

define('MSG_SHOW_TYPE_MISSING', "%s param missing\n");
//错误信息：文件不存在
define('FILE_NOT_EXISTS_MSG',"FILE %s NOT EXIST\n");