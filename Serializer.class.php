<?php

/****
 * @PHPVER4.0
 *
 * @author emnu
 * @ver --
 * @date 12/08/08
 *
 * use this class to convert from mutidimensional array to xml.
 * see example.php file on howto use this class
 *
 */
class arr2xml
{
    var $array = array();
    var $xml = '';

    function arr2xml($array, $insertCabecalho = true)
    {
        $this->array = $array;

        if (is_array($this->array) && count($this->array) > 0) {
            if ($insertCabecalho == true) {
                $this->xml .= '<?xml version="1.0" encoding="UTF-8"?>';
            }
            $this->xml .= "<full>";
            $this->struct_xml($array);
            $this->xml .= "</full>";
        } else {
            $this->xml .= "no_data";
        }
    }

    function struct_xml($array)
    {
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $tag = preg_replace('/[0-9]{1,}/', 'data', $k); // replace numeric key in array to 'data'
                $this->xml .= "<$tag>";
                $this->struct_xml($v);
                $this->xml .= "</$tag>";
            } else {
                $tag = preg_replace('/[0-9]{1,}/', 'data', $k); // replace numeric key in array to 'data'
                $this->xml .= "<$tag>$v</$tag>";
            }
        }
    }

    function get_xml()
    {
        echo $this->xml;
    }
}

?>