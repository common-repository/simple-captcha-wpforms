<?php


namespace simple_captcha_wpforms\core\debug;

/**
 * Output arrays and objects in a readable way.
 *
 * @param mixed ...$val values of any type
 */
if (!function_exists('pr')) {
    function pr(...$val)
    {
        $out = '';
        foreach ($val as $v) {
            if (is_array($v) || is_object($v)) {
                $out .= "<pre>" . print_r($v, true) . "</pre>";
            } elseif ($v === true) {
                $out .= '(bool)TRUE   ';
            } elseif ($v === false) {
                $out .= '(bool)FALSE   ';
            } else {
                $out .= $v . '   ';
            }
        }
        echo wp_kses($out,['pre'=>[]]) . '   <br>';
    }
}


/**
 * clever error_log() for multiple mixed values.
 *
 * @param mixed ...$val values of any type
 *
 * @example  el($myVar, $myArray, $myBoolValue);
 */
if (!function_exists('el')) {
    function el(...$val)
    {
        $out = '';
        foreach ($val as $v) {
            if (is_array($v) || is_object($v)) {
                $out .= print_r($v, true);
            } elseif ($v === true) {
                $out .= '(bool)TRUE   ';
            } elseif ($v === false) {
                $out .= '(bool)FALSE   ';
            } else {
                $out .= $v . '   ';
            }
        }
        error_log($out);
    }
}


/**
 * Output arrays and objects in the JS Console
 *
 * @param [type] $val
 */
if (!function_exists('cl')) {
    function cl(...$val)
    {
        $out = "console.log('";
        foreach ($val as $v) {
            if (is_array($v) || is_object($v)) {
                $out .= str_replace(array("\r\n", "\n", "\r"), ' ', print_r($v, true));
            } elseif ($v === true) {
                $out .= '(bool)TRUE   ';
            } elseif ($v === false) {
                $out .= '(bool)FALSE   ';
            } else {
                $out .= $v . '   ';
            }
        }
        $out .= "');";
        echo PHP_EOL.'<script>'.wp_kses($out, []).'</script>';
    }
}