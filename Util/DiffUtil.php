<?php

/**
 * created by maxin
 * date: 2015/4/19
 */
class DiffUtil {

    /**
     * 带递归的array diff
     * @param array  $leftArray
     * @param  array $rightArray
     * @return array|bool
     */
    public static function  arrayDiffMulti($leftArray, $rightArray, &$count = 0) {
        $result = array();

        foreach ($leftArray as $mKey => $mValue) {
            if (array_key_exists($mKey, $rightArray)) {
                if (is_array($mValue)) {
                    $aRecursiveDiff = self::arrayDiffMulti($mValue, $rightArray[$mKey], $count);
                    if (count($aRecursiveDiff)) {
                        $result[$mKey] = $aRecursiveDiff;
                    }
                } else {
                    if ($mValue != $rightArray[$mKey]) {
                        $count++;
                        $result[$mKey] = $mValue;
                    }
                }
            } else {
                $count++;
                $result[$mKey] = $mValue;
            }
        }

        return $result;
    }
}