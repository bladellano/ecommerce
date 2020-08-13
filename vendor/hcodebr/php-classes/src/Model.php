<?php

namespace Hcode;

class Model
{

    private $values = [];

    /**
     * Método mágico _call
     * @param $name, $args
     */
    public function __call($name, $args)
    {
        $method = substr($name, 0, 3);

        $fieldName = substr($name, 3, strlen($name));

        switch ($method) {
            case "get":
                return (isset($this->values[$fieldName])) ? $this->values[$fieldName] : NULL;
                break;

            case "set":
                $this->values[$fieldName] = $args[0];
                break;
        }
    }

    /**
     * Seta objeto dinamicamente
     * @return [void]
     */
    public function setData($data = array())
    {
        foreach ($data as $key => $value) {
            $this->{"set" . $key}($value);
        }
    }

    /**
     * Pega objeto dinamicamente
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }
}
