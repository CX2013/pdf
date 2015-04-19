<?php

/**
 * created by guozhucheng
 * date: 2015/4/18
 */
class ParamHandle {

    /**
     * 参数配置
     * @var array
     */
    private static $configParams = array(
        array(
            'key'     => '-t',
            'require' => true,
        ),
        array(
            'key'     => '-l',
            'require' => true,
        ),
        array(
            'key'     => '-r',
            'require' => true,
        ),
        array(
            'key'     => '-o',
            'require' => false,
        ),
        array(
            'key'     => '-e',
            'require' => false,
            'default' => 'utf8',
        ),
    );

    /**
     * 获取命令行中参数
     * @param array $cmdArgv 命令行参数数组
     * @return array
     *  $retData = array(
     * 'status' => boolen,
     * 'msg'    => '',
     * 'data'   => array,
     * );

     */
    public static function  getParams($cmdArgv) {
        $retData = array(
            'status' => false,
            'msg'    => '',
            'data'   => null,
        );
        //没有参数输入
        if (count($cmdArgv) == 1) {
            $retData['msg'] = MSG_SHOW_NO_ARGC;

            return $retData;
        }
        //获取help信息
        if (in_array('--help', $cmdArgv) || in_array('-h', $cmdArgv)) {
            $retData['msg'] = MSG_SHOW_HELP_INFO;

            return $retData;
        }
        /**
         * 获取其他参数
         */
        $params = array();
        foreach (self::$configParams as $confParam) {
            $params[$confParam['key']] = $confParam['default'];
            //参数不存在
            if (!in_array($confParam['key'], $cmdArgv)) {
                //如果是必传参数,并且未设置默认值，则进行报错处理
                if (true === $confParam['require'] && empty($confParam['default'])) {
                    $retData['msg'] = sprintf(MSG_SHOW_TYPE_MISSING, $confParam['key']);

                    return $retData;
                }
                continue;
            }
            //获取参数及参数的值
            $keyIndex = array_search($confParam['key'], $cmdArgv);
            $paramVal = $cmdArgv[$keyIndex + 1];
            if (substr($paramVal, 0, 1) == '-') {
                $retData['msg'] = sprintf(MSG_SHOW_TYPE_MISSING, $confParam['key']);

                return $retData;
            }

            $params[$confParam['key']] = $paramVal;
        }
        $retData = array(
            'status' => true,
            'msg'    => '',
            'data'   => $params,
        );

        return $retData;
    }
}