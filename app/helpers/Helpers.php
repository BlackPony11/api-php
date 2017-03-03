<?php namespace Undefined\Helpers;
/**
 * Helper class with simple functions
 * @package System\Core
 */

class Helpers
{
    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param bool $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source https://gravatar.com/site/implement/images/php/
     */
    static function get_gravatar($email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array())
    {
        $url = 'https://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val)
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

    /**
     * Generate correct url from string
     *
     * @param $slug
     * @return string
     */
    static function create_slug($slug)
    {

        $lettersNumbersSpacesHyphens = '/[^\-\s\pN\pL]+/u';
        $spacesDuplicateHypens = '/[\-\s]+/';

        $slug = preg_replace($lettersNumbersSpacesHyphens, '', $slug);
        $slug = preg_replace($spacesDuplicateHypens, '-', $slug);

        $slug = trim($slug, '-');

        return mb_strtolower($slug, 'UTF-8');
    }

    /**
     * Multidimensional search
     *
     * @param $parents
     * @param $searched
     * @param $object
     * @return bool|int|string
     */
    function md_search($parents, $searched, $object = true)
    {
        if (empty($searched) || empty($parents)) {
            return false;
        }

        $for_out = null;
        foreach ($parents as $key => $value) {
            $exists = true;

            foreach ($searched as $skey => $svalue) {
                if ($object)
                    $exists = ($exists && isset($value->$skey) && ($value->$skey == $svalue));
                else
                    $exists = ($exists && isset($value[$skey]) && ($value[$skey] == $svalue));
            }

            if ($exists) {
                $for_out[] = $key;
            }
        }
        if (!empty($for_out)) {
            return $for_out;
        } else {
            return false;
        }
    }

    /**
     * Cleanup the value
     *
     * @param $value
     * @param null $type
     * @return mixed|string
     */
    public static function cleaner($value, $type = NULL)
    {

        switch ($type) {
            case 'num':
                $value = htmlspecialchars(addslashes($value), ENT_QUOTES);
                $value = preg_replace("/[^0-9]/i", "", $value);
                break;
            case 'numex':
                $value = htmlspecialchars(addslashes($value), ENT_QUOTES);
                $value = preg_replace("/[^0-9\,]/i", "", $value);
                break;
            case 'text':
                $value = htmlspecialchars($value, ENT_QUOTES);
                $value = preg_replace(array("/\r\n\r\n/", "/\n\n/"), array("<br/>", "<br/>"), $value);
                $value = preg_replace("/[^а-яёa-z]/iu", "", $value);
                break;
            case 'api':
                $value = htmlspecialchars($value, ENT_QUOTES);
                $value = preg_replace(array("/\r\n\r\n/", "/\n\n/"), array("<br/>", "<br/>"), $value);
                $value = preg_replace("/[^а-яёa-z0-9\-\_\.]/iu", "", $value);
                break;
            case 'filename':
                $value = htmlspecialchars($value, ENT_QUOTES);
                $value = preg_replace(array("/\r\n\r\n/", "/\n\n/"), array("<br/>", "<br/>"), $value);
                $value = preg_replace("/[^а-яёa-z\.\,\_\-\+\=\?\(\)\!0-9]/iu", "", $value);
                break;
            case 'json':
                $value = htmlspecialchars($value, ENT_QUOTES);
                $value = preg_replace(array("/\r\n\r\n/", "/\n\n/"), array("<br/>", "<br/>"), $value);
                $value = preg_replace("/[^а-яёa-z0-9\—\~\`\.\,\@\%\{\}\[\]\/\:\<\>\\\;\?\&\(\)\_\#\!\$\*\^\-\+\=\ \n\r]/iu", "", $value);
                break;
            default:
                $value = htmlspecialchars($value, ENT_QUOTES);
                $value = preg_replace(array("/\r\n\r\n/", "/\n\n/"), array("<br/>", "<br/>"), $value);
                $value = preg_replace("/[^а-яёa-z0-9\—\~\`\.\,\@\%\[\]\/\:\<\>\\\;\?\&\(\)\_\#\!\$\*\^\-\+\=\ \n\r]/iu", "", $value);
                break;
        }

        return $value;
    }

}
