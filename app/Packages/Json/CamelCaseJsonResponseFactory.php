<?php
namespace App\Packages\Json;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Routing\ResponseFactory as BaseResponseFactory;

/**
 * Convert response JSON key to camelCase
 */
class CamelCaseJsonResponseFactory extends BaseResponseFactory
{
    public function __construct($arg1, $arg2)
    {
        parent::__construct($arg1, $arg2);
    }

    public function json($data = array(), $status = 200, array $headers = array(), $options = 0)
    {
        $json = $this->encodeJson($data);
        return parent::json($json, $status, $headers, $options);
    }

    /**
     * Encode a value to camelCase JSON
     */
    public function encodeJson($value)
    {
        if ($value instanceof Arrayable) {
            return $this->encodeArrayable($value);
        } else if (is_array($value)) {
            return $this->encodeArray($value);
        } else if (is_object($value)) {
            return $value;
        } else if (is_null($value)) {
            return '';
        } else {
            return $value;
        }
    }

    /**
     * Encode a arrayable
     */
    public function encodeArrayable($arrayable)
    {
        $array = $arrayable->toArray();
        return $this->encodeJson($array);
    }

    /**
     * Encode an array
     */
    public function encodeArray($array)
    {
        $newArray = [];
        foreach ($array as $key => $val) {
            $newArray[\camel_case($key)] = $this->encodeJson($val);
        }
        return $newArray;
    }

}
