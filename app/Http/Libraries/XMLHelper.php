<?php
namespace App\Http\Libraries;


use Illuminate\Support\Facades\Log;

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
            Log::info(var_dump($data));
            $data = $data[0];
        }
        return $data;
    }
}
