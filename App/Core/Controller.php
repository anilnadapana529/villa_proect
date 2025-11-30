<?php

class Controller {

    protected $db;

    public function __construct() {
        $this->db = Database::instance();
    }

    protected function json($data, $status = 200) {
        Response::json($data, $status);
    }

    protected function requireFields($input, $fields) {
        foreach ($fields as $f) {
            if (!isset($input[$f]) || $input[$f] === "") {
                Response::error("Missing field: $f");
            }
        }
    }
}
