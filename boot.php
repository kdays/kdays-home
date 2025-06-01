<?php
define('APP_NAMESPACE', 'Giftia');
define('ASSETS_HOST', 'https://misaka.ikdays.com/home');
define('ASSETS_BUILD', '250602');

spl_autoload_register(function ($class) {
    $prefix = 'Giftia\\';
    $base_dir = __DIR__ . '/app/';
    if (str_starts_with($class, $prefix)) {
        $relative_class = substr($class, strlen($prefix));
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

class Dispatcher {
    public static function run() {
        try {
            $path = $_SERVER['PATH_INFO'] ?? null;
            if ($path === null) {
                $requestUri = $_SERVER['REQUEST_URI'] ?? '/';
                $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
                // 去掉 ?参数
                $requestUri = explode('?', $requestUri, 2)[0];
                // 去掉 /index.php 前缀
                if (strpos($requestUri, $scriptName) === 0) {
                    $path = substr($requestUri, strlen($scriptName));
                } else {
                    $path = $requestUri;
                }
            }
            $path = trim($path, '/');
            $segments = $path === '' ? [] : explode('/', $path);

            if (empty($segments)) {
                $classParts = [APP_NAMESPACE, 'action', 'IndexAction'];
                $methodName = 'indexAction';
            } else {
                $actionName = ucfirst(preg_replace('/[^a-zA-Z0-9_]/', '', $segments[0])) . 'Action';
                $classParts = [APP_NAMESPACE, 'action', $actionName];
                $methodName = isset($segments[1]) ? preg_replace('/[^a-zA-Z0-9_]/', '', $segments[1]) . 'Action' : 'indexAction';
            }
            $className = implode('\\', $classParts);

            if (class_exists($className)) {
                $actionObj = new $className();
                if (method_exists($actionObj, $methodName)) {
                    $ref = new \ReflectionMethod($actionObj, $methodName);
                    if ($ref->isPublic() && !$ref->isConstructor() && strpos($methodName, '__') !== 0) {
                        $actionObj->$methodName();
                        return;
                    }
                } elseif (method_exists($actionObj, 'handle')) {
                    $ref = new \ReflectionMethod($actionObj, 'handle');
                    if ($ref->isPublic() && !$ref->isConstructor()) {
                        $actionObj->handle();
                        return;
                    }
                }
            }
            self::onNotFound($className, $methodName);
        } catch (\Throwable $e) {
            self::onAppError($e);
        }
    }

    protected static function renderTpl(string $tplFile, array $data = []) {
        (function () use ($tplFile, $data) {
            extract($data, EXTR_SKIP);
            include $tplFile;
        })();
    }

    public static function onAppError($e) {
        $tplFile = __DIR__ . '/app/views/error/app_error.phtml';
        $errorMsg = $e->getMessage();

        $errorTrace = $e->getTraceAsString();
        $statusCode = (is_object($e) && method_exists($e, 'getStatusCode')) ? $e->getStatusCode() : 500;
        
        http_response_code($statusCode);
        if (file_exists($tplFile)) {
            self::renderTpl($tplFile, compact('errorMsg', 'errorTrace'));
        } else {
            echo "App Error: " . htmlspecialchars($errorMsg) . "<br>";
            if ($errorTrace) echo nl2br(htmlspecialchars($errorTrace));
        }
    }

    public static function onNotFound($className, $methodName) {
        http_response_code(404);
        $tplFile = __DIR__ . '/app/views/error/404.phtml';
        if (file_exists($tplFile)) {
            self::renderTpl($tplFile);
        } else {
            echo "404 Not Found: {$className}->{$methodName}";
        }
    }
}

Dispatcher::run();

