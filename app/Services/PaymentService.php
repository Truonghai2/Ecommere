<?php

namespace App\Services;

class PaymentService
{
    private static $instance = null;

    private $data = [];

    private function __construct() {}
    private function __clone() {}

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function setData($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function getData($key)
    {
        return $this->data[$key] ?? null;
    }

    public function allData()
    {
        return $this->data;
    }
        
}
