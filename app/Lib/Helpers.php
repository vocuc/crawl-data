<?php

use Carbon\Carbon;

if (!function_exists('is_mobile')) {

    /**
     * @return bool
     */
    function is_mobile()
    {
        $useragent = '';

        if (isset($_SERVER['HTTP_USER_AGENT']))
            $useragent = strtolower($_SERVER['HTTP_USER_AGENT']);

        $isMobile = (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4)));

        return ($isMobile) ? true : false;
    }
}

if (!function_exists('pd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed
     * @return void
     */
    function pd()
    {
        echo '<pre>';
        array_map(function ($x) {
            print_r($x);
        }, func_get_args());
        echo '</pre>';
        die(1);
    }
}

if (!function_exists('lim_number_format')) {
    /**
     * @param $number
     * @return string
     * @throws Exception
     */
    function lim_number_format($number,$unit = 'đ')
    {
        $divisor = 1;

        if ($number > 1000 && $number < 1000000) {
            $divisor = 1000;
            $unit = 'k';
        }

        if ($number > 1000000) {
            $divisor = 1000000;
            $unit = 'M';
        }

        return number_format(($number / $divisor), 0, ',', '.') . $unit;
    }
}

if (!function_exists('count_date')) {
    /**
     * @param $start_at
     * @param $end_at
     * @return string
     * @throws Exception
     */
    function count_date($start_at, $end_at)
    {
        $datetime1 = new DateTime($start_at);

        $datetime2 = new DateTime($end_at);

        $interval = $datetime1->diff($datetime2);

        return $interval->format('%a');
    }
}

if (!function_exists('pr')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed
     * @return void
     */
    function pr()
    {
        echo '<pre>';
        array_map(function ($x) {
            print_r($x);
        }, func_get_args());
        echo '</pre>';
    }
}

if (!function_exists('limit_text')) {

    /**
     * @param $text
     * @param $limit
     * @return string
     */
    function limit_text($text, $limit)
    {
        if (str_word_count($text, 0) > $limit) {
            $words = str_word_count($text, 2);
            $pos = array_keys($words);
            $text = substr($text, 0, $pos[$limit]) . ' ...';
        }
        return $text;
    }
}

if (!function_exists('limit_string')) {

    /**
     * @param $text
     * @param $limit
     * @return string
     */
    function limit_string($text, $limit)
    {
        if (strlen($text) > $limit) {
            $text = mb_substr($text, 0, $limit, 'UTF-8') . ' ...';
        }

        return $text;
    }
}


if (!function_exists('convert_phone')) {
    function convert_phone($phone)
    {
        if (preg_match('/^0/', $phone)) {
            $phone = preg_replace('/^0/', '84', $phone);
        }

        if (preg_match('/^\+/', $phone)) {
            $phone = preg_replace('/^\+/', '', $phone);
        }

        return $phone;
    }
}

if (!function_exists('phone_validate')) {
    function phone_validate($phone)
    {
        $prefixHeaderNumbers = '086|096|097|';
        $prefixHeaderNumbers .= '098|032|033|';
        $prefixHeaderNumbers .= '034|035|036|';
        $prefixHeaderNumbers .= '037|038|039|';
        $prefixHeaderNumbers .= '089|090|093|';
        $prefixHeaderNumbers .= '070|079|077|';
        $prefixHeaderNumbers .= '076|078|091|';
        $prefixHeaderNumbers .= '094|088|083|';
        $prefixHeaderNumbers .= '084|085|081|082|092';

        $phone = preg_replace('/^\+84/', '0', $phone);

        if (!preg_match('/^0/', $phone)) {
            return [
                'status' => false,
                'messages' => "Số điện thoại không đúng định dạng"
            ];
        }

        if (!preg_match('/^[0-9]+$/', $phone)) {
            return [
                'status' => false,
                'messages' => "Số điện thoại phải là số"
            ];
        }

        $phoneLength = strlen($phone);
        if ($phoneLength < 10 || $phoneLength > 10) {
            return [
                'status' => false,
                'messages' => "Số điện thoại phải là 10 chữ số"
            ];
        }

        if (!preg_match('/^' . $prefixHeaderNumbers . '/', $phone)) {
            return [
                'status' => false,
                'messages' => "Đầu số điện thoại của bạn không được hỗ trợ"
            ];
        }

        return [
            'status' => true,
            'messages' => ""
        ];
    }
}

if (!function_exists('link_validate')) {
    function link_validate($url)
    {
        $pattern = "/(https:\/\/www\.|http:\/\/www\.|https:\/\/|http:\/\/)?[a-zA-Z]{2,}(\.[a-zA-Z]{2,})(\.[a-zA-Z]{2,})?\/[a-zA-Z0-9]{2,}|((https:\/\/www\.|http:\/\/www\.|https:\/\/|http:\/\/)?[a-zA-Z]{2,}(\.[a-zA-Z]{2,})(\.[a-zA-Z]{2,})?)|(https:\/\/www\.|http:\/\/www\.|https:\/\/|http:\/\/)?[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}\.[a-zA-Z0-9]{2,}(\.[a-zA-Z0-9]{2,})?/u";

        if (preg_match($pattern, $url)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('getCategoryGmvIdByCategoryTiktok')) {
    function getCategoryGmvByCategoryTiktok(int $categoryIdTiktok)
    {
        $categoryTiktok = Illuminate\Support\Facades\Cache::get('category_tiktok_' . $categoryIdTiktok);

        if ($categoryTiktok === null) {
            Illuminate\Support\Facades\Log::error('Không tìm thấy danh mục TikTok : ' . $categoryIdTiktok);

            return [
                'status' => false,
                'message' => __('Không tìm thấy danh mục TikTok!'),
                'category' => App\Services\Tiktok\Product\GetCategoryService::CATEGORY_GMV_ID_PROD,
            ];
        }

        $categoryTiktok = json_decode($categoryTiktok, true);

        $dataResponse = [
            'status' => true,
            'category' => @$categoryTiktok['gmv_id'],
        ];

        if (@$categoryTiktok['parent_id'] == 0) {
            return $dataResponse;
        }

        return getCategoryGmvByCategoryTiktok($categoryTiktok['parent_id']);
    }
}

if (!function_exists('getLinkAuthoiationTiktok')) {
    function getLinkAuthoiationTiktok()
    {
        $baseLink = 'https://services.tiktokshop.com/open/authorize';

        $state = bin2hex(random_bytes(128 / 8));

        $params = [
            'service_id' => config('tiktok.service_id'),
            'state' => $state

        ];

        return $baseLink . '?' . http_build_query($params);
    }
}

if (!function_exists('getAllEntityOfDate')) {
    function getAllEntityOfDate($dateString)
    {
        $date = Carbon::parse($dateString);

        $dayOfWeek = $date->dayOfWeek;

        return [
            'date-d-m' => $date->format('d/m'),
            'day-name' => ($dayOfWeek == 0) ? 'CN' : 'T' . ($dayOfWeek + 1),
            'time-h-i' => $date->format('H:i'),
        ];
    }
}
