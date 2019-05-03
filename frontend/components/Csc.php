<?php
namespace app\components;
use Yii;
use yii\base\Component;

class Csc extends Component {

    public static function wrapTextLinks($text)
    {
        return preg_replace('/\b(https?:\/\/[\S]+)/si', '<a target="_blank" href="$1">$1</a>', htmlspecialchars($text));
    }

    public static function fn_write_r($mode = 'w')
    {
        static $count = 0;
        $args = func_get_args();

        $fp = fopen('ajax_result.html', $mode . '+');

        if (!empty($args)) {
            fwrite($fp, '<ol style="font-family: Courier; font-size: 12px; border: 1px solid #dedede; background-color: #efefef; float: left; padding-right: 20px;">');

            foreach ($args as $k => $v) {
                $v = htmlspecialchars(print_r($v, true));
                if ($v == '') {
                    $v = '    ';
                }

                fwrite($fp, '<li><pre>' . $v . "\n" . '</pre></li>');
            }
            fwrite($fp, '</ol><div style="clear:left;"></div>');
        }


        $count++;
    }

    public static function fn_print_r()
    {
        static $count = 0;
        $args = func_get_args();

        if (!empty($args)) {
            echo '<ol style="font-family: Courier; font-size: 12px; border: 1px solid #dedede; background-color: #efefef; float: left; padding-right: 20px;">';
            foreach ($args as $k => $v) {
                $v = htmlspecialchars(print_r($v, true));
                if ($v == '') {
                    $v = '    ';
                }

                echo '<li><pre>' . $v . "\n" . '</pre></li>';
            }
            echo '</ol><div style="clear:left;"></div>';
        }
        $count++;
    }

    public static function fn_print_die()
    {
        $args = func_get_args();
        call_user_func_array('Csc::fn_print_r', $args);
        exit(1);
    }

}