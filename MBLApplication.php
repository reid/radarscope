<?php

class MBLApplication {

    static public $appid;

    static public function getAppId () {

        if (self::$appid) return self::$appid;

        $file = dirname(__FILE__) . '/MBLApplicationId.txt';

        if (is_readable($file)) {

            self::$appid = file_get_contents($file);

            if (!is_string(self::$appid)) {
                throw new Exception('No valid application ID found');
            }

            return self::$appid;

        }

        throw new Exception("Make sure $file is readable");

    }

    static public function ago ($ts, $detail = 1, $format = 'F j') {

        static $max = 432000, // 5 days
               $periods = array(
                //  'week' => 604800,
                    'day' => 86400,
                    'hour' => 3600,
                    'minute' => 60,
                    'second' => 1,
               ),
               $recent = 'just now';

        $diff = time() - $ts;

        if ($diff < 0) return $recent;

        if ($diff < $max) {
            $output = array();
            foreach ($periods as $unit => $seconds) {

                if ($diff >= $seconds) {

                    $time = floor($diff / $seconds);
                    $diff %= $seconds;
                    
                    if ($unit == 'second') {
                        $time = 'just a';
                        $unit = 'moment';
                    } else if (
                        ($unit == 'minute' && $time < 15) or
                        ($unit == 'hour' && $time < 4)
                        ) {
                        $time = 'a few';
                    }

                    if ($time > 1 || !is_numeric($time))
                        $unit .= 's';

                    if ($time == 1)
                        $time = 'a';

                    $output[] = $time . ' ' . $unit;
                    $detail--;
                }

                if ($detail == 0)
                    break;
            }

            if (!$output)
                return $recent;
            else
                return implode(', ', $output) . ' ago';
        }

        return date($format, $ts);

    }

}
