<?php
class Controller {
    protected function response($data, $status = 200) {
        http_response_code($status);
        echo json_encode($data);
        exit;
    }

    protected function model($model) {
        require_once __DIR__ . '/../models/' . $model . '.php';
        return new $model();
    }
}
