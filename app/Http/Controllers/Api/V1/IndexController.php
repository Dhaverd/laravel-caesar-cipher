<?php


namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;


class IndexController extends Controller
{
    public function index()
    {
        $cd = intval($_GET["cd"]);
        $text = str($_GET["text"]);
        $shift = intval($_GET["shift"]);
        $language = str($_GET["language"]);
        $shift = $this->normalizeShift($shift, $language);
        $result_text = '';
        if ($language == 'Ru'){
            if ($cd == 0){
                $result_text = $this->ruEncipher($text, $shift);
            } elseif ($cd == 1){
                $result_text = $this->ruDecipher($text, $shift);
            }
        } elseif ($language == 'En') {
            if ($cd == 0){
                $result_text = $this->enEncipher($text, $shift);
            } elseif ($cd == 1){
                $result_text = $this->enDecipher($text, $shift);
            }
        }
        return $result_text;
    }

    public function normalizeShift($shift, $alphabet)
    {
        if ($alphabet == 'Ru'){
            if ($shift > 32){
                while ($shift > 32)
                {
                    $shift = $shift - 32;
                }
            }
            return $shift;
        } elseif ($alphabet == 'En') {
            if ($shift > 26){
                while ($shift > 26)
                {
                    $shift = $shift - 26;
                }
            }
            return $shift;
        }
    }

    public function enCipher($ch, $key)
    {
        if (!ctype_alpha($ch))
            return $ch;

        $offset = ord(ctype_upper($ch) ? 'A' : 'a');
        return chr(fmod(((ord($ch) + $key) - $offset), 26) + $offset);
    }

    public function enEncipher($input, $key)
    {
        $output = "";

        $inputArr = str_split($input);
        foreach ($inputArr as $ch)
            $output .= $this->enCipher($ch, $key);
        return $output;
    }

    public function enDecipher($input, $key)
    {
        return $this->enEncipher($input, 26 - $key);
    }

    public function ruCipher($ch, $key)
    {
        $regexp = "/[А-ЯЁа-яё]/";
        $regexp_upper = "/[А-ЯЁ]\{Lu}/";
        if (!preg_match($regexp, $ch))
        {
            return $ch;
        }

        $offset = mb_ord(preg_match($regexp_upper, $ch) ? 'А' : 'а', "UTF-8");
        return mb_chr(fmod(((mb_ord($ch, "UTF-8") + $key) - $offset), 32) + $offset, "UTF-8");
    }

    function ruCipherReverse($ch, $key)
    {
        $regexp = "/[А-ЯЁа-яё]/";
        $regexp_upper = "/[А-ЯЁ]\{Lu}/";
        if (!preg_match($regexp, $ch))
        {
            return $ch;
        }

        $offset = mb_ord(preg_match($regexp_upper, $ch) ? 'А' : 'а', "UTF-8");
        return mb_chr(fmod(((mb_ord($ch, "UTF-8") - $key) - $offset), 32) + $offset, "UTF-8");
    }

    public function ruEncipher($input, $key)
    {
        $output = "";

        $inputArr = mb_str_split($input, 1, "UTF-8");
        foreach ($inputArr as $ch)
            $output .= $this->ruCipher($ch, $key);

        return $output;
    }

    public function ruDecipher($input, $key)
    {
        $output = "";

        $inputArr = mb_str_split($input, 1, "UTF-8");
        foreach ($inputArr as $ch)
            $output .= $this->ruCipherReverse($ch, $key);

        return $output;
    }
}
