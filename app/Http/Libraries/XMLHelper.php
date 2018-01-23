<?php
namespace App\Http\Libraries;

class XMLHelper
{
    /**
     * @param Object|string $data
     * @return string
     */
    public static function SimpleXMLObjectToString($data)
    {
        if ('object' == gettype($data)) {
            $data = @json_decode(@json_encode($data), 1);
            $data = $data[0];
        }
        return $data;
    }
}
