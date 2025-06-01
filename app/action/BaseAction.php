<?php
namespace Giftia\action;

abstract class BaseAction {
    protected function _genJsonResult(array $data) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    protected function _genViewResult(string $tplName, array $data = []) {
        $tplFile = __DIR__ . '/../views/' . str_replace(['..', '\\', '/'], ['','/','/'], $tplName) . '.phtml';
        if (!file_exists($tplFile)) {
            throw new \Giftia\utils\HttpException("View not found: " . htmlspecialchars($tplFile), 500);
        }

        (function () use ($tplFile, $data) {
            extract($data, EXTR_SKIP);
            include $tplFile;
        })();
        exit;
    }
}
