<?php

/**
 * created by maxin
 * date: 2015/4/4
 */
class Differ {

    /**
     * table 初始化的值
     */
    const TABLE_INIT_VAL = 0;


    //记录两个模板的相同的行和不同的行
    var $table = array();
    //左模板内容
    var $left = array();
    //右模板内容
    var $right = array();
    //左模板行数
    var $left_len = 0;
    //右模板行数
    var $right_len = 0;

    function __construct($left, $right) {
        //将模板按回车符或者换行符分割放入数组
        $this->left  = preg_split('/(\r\n|\n|\r)/', $left);
        $this->right = preg_split('/(\r\n|\n|\r)/', $right);
        //模板行数
        $this->left_len  = count($this->left);
        $this->right_len = count($this->right);
    }

    /**
     * 模板比较类构造函数，传入两个模板的内容
     * @param $left
     * @param $right
     */
    function Diff($left, $right) {
        //将模板按回车符或者换行符分割放入数组
        $this->left  = preg_split('/(\r\n|\n|\r)/', $left);
        $this->right = preg_split('/(\r\n|\n|\r)/', $right);
        //模板行数
        $this->left_len  = count($this->left);
        $this->right_len = count($this->right);
    }

    /**
     * 将0|1|0|0|1|0|样式的字符传拆分生键和值的数组形式，起始下标是-1
     * @param string $row
     * @return array
     */
    function getrow($row) {
        $return = array();
        $i      = -1;
        foreach (explode('|', $row) as $value) {
            $return[$i] = $value;
            $i++;
        }

        return $return;
    }

    /**
     * 比较两个模板内容有什么不同（类似SVN的模板比较），返回类型：array
     * @return array
     * Array
     * (
     * [0] => Diff_Entry Object(
     * [left] =>
     * [right] =>
     * )
     * [1] => Diff_Entry Object(
     * [left] =>
     * [right] =>
     * )
     * ...
     * )

     */
    function fetch_diff() {
        $prev_row     = array();
        $prev_row     = array_pad(array(), $this->right_len, self::TABLE_INIT_VAL);
        $prev_row[-1] = self::TABLE_INIT_VAL;
//        for ($i = -1; $i < $this->right_len; $i++) {
//            $prev_row[$i] = 0;
//        }
        for ($i = 0; $i < $this->left_len; $i++) {
            $this_row        = array(
                '-1' => 0
            );
            $data_left_value = $this->left[$i];
            for ($j = 0; $j < $this->right_len; $j++) {
                if ($data_left_value == $this->right[$j]) {
                    $this_row[$j] = $prev_row[$j - 1] + 1;
                } elseif ($this_row[$j - 1] > $prev_row[$j]) {
                    $this_row[$j] = $this_row[$j - 1];
                } else {
                    $this_row[$j] = $prev_row[$j];
                }
            }
            $this->table[$i - 1] = implode('|', $prev_row);

            $prev_row = $this_row;
        }

        unset($prev_row);
        $this->table[$this->left_len - 1] = implode('|', $this_row);
        $table                            = &$this->table;
        $output                           = $match = $nonmatch1 = $nonmatch2 = array();
        $data_left_key                    = $this->left_len - 1;
        $data_right_key                   = $this->right_len - 1;
        $this_row                         = $this->getrow($table[$data_left_key]);
        $above_row                        = $this->getrow($table[$data_left_key - 1]);
        while ($data_left_key >= 0 and $data_right_key >= 0) {
            if ($this_row[$data_right_key] != $above_row[$data_right_key - 1] and $this->left[$data_left_key] == $this->right[$data_right_key]) {
                //将不同部分放入输出数组
                $this->nonmatches($output, $nonmatch1, $nonmatch2);
                //记录相同部分
                array_unshift($match, $this->left[$data_left_key]);
                //坐标向左上方移动
                $data_left_key--;
                $data_right_key--;
                $this_row  = $above_row;
                $above_row = $this->getrow($table[$data_left_key - 1]);
            } elseif ($above_row[$data_right_key] > $this_row[$data_right_key - 1]) {
                //将相同部分放入输出数组
                $this->matches($output, $match);
                //记录左模板不同部分
                array_unshift($nonmatch1, $this->left[$data_left_key]);
                //坐标向上移动
                $data_left_key--;
                $this_row  = $above_row;
                $above_row = $this->getrow($table[$data_left_key - 1]);
            } else {
                //将相同部分放入输出数组
                $this->matches($output, $match);
                //记录右模板不同部分
                array_unshift($nonmatch2, $this->right[$data_right_key]);
                //坐标向左移动
                $data_right_key--;
            }
        }
        //将两模板相同部分放入输出数组
        $this->matches($output, $match);
        if ($data_left_key > -1 or $data_right_key > -1) {
            for (; $data_left_key > -1; $data_left_key--) {
                array_unshift($nonmatch1, $this->left[$data_left_key]);
            }
            for (; $data_right_key > -1; $data_right_key--) {
                array_unshift($nonmatch2, $this->right[$data_right_key]);
            }
            $this->nonmatches($output, $nonmatch1, $nonmatch2);
        }

        return $output;
    }

    /**
     * 将两个文件相同的行拼接后转换成Diff_Entry对象，并插入到$output数组的开头
     * @param array $output （引用传递）
     * @param array $match （引用传递）
     */
    function matches(&$output, &$match) {
        if (count($match) > 0) {
            $data = implode("\n", $match);
            array_unshift($output, new Diff_Entry($data, $data));
        }
        $match = array();
    }

    /**
     * 将两个文件不同的行拼接后转换成Diff_Entry对象，并插入到$output数组的开头
     * Diff_Entry Object
     * (
     * [left] => 11
     * [right] => 1
     * )
     * @param array $output （引用传递）
     * @param array $text_left （引用传递）
     * @param array $text_right （引用传递）
     */
    function nonmatches(&$output, &$text_left, &$text_right) {
        $s1 = count($text_left);
        $s2 = count($text_right);
        if ($s1 > 0 and $s2 == 0) {
            array_unshift($output, new Diff_Entry(implode("\n", $text_left), ''));
        } elseif ($s2 > 0 and $s1 == 0) {
            array_unshift($output, new Diff_Entry('', implode("\n", $text_right)));
        } elseif ($s1 > 0 and $s2 > 0) {
            array_unshift($output, new Diff_Entry(implode("\n", $text_left), implode("\n", $text_right)));
        }
        //赋空值
        $text_left = $text_right = array();
    }
}

/**
 * Class Diff_Entry
 */
class Diff_Entry {
    private $a;
    private $b;

    function  Diff_Entry($a, $b) {
        $this->a = $a;
        $this->b = $b;
    }
}