<?php
// Set JSON header first
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Capture all output
ob_start();

try {
    // Test 1: Basic PHP
    $test1 = "PHP Working";

    // Test 2: File exists
    $routesExists = file_exists(__DIR__ . "/../routes.php");

    // Test 3: Autoloader
    spl_autoload_register(function ($class) {
        $class = str_replace("\\", "/", $class);
        $path = __DIR__ . "/../" . $class . ".php";
        if (file_exists($path)) {
            require_once $path;
        }
    });

    // Test 4: Load core files
    require_once __DIR__ . "/../App/Core/Response.php";
    require_once __DIR__ . "/../App/Helpers/JWT.php";

    // Test 5: Check if Owner model exists
    $ownerModelPath = __DIR__ . "/../App/Models/Owner.php";
    $ownerExists = file_exists($ownerModelPath);

    // Test 6: Try to load Owner model
    if ($ownerExists) {
        require_once $ownerModelPath;
        $ownerLoaded = class_exists('App\Models\Owner');
    } else {
        $ownerLoaded = false;
    }

    // Test 7: Check OwnerController
    $controllerPath = __DIR__ . "/../App/Controllers/OwnerController.php";
    $controllerExists = file_exists($controllerPath);

    if ($controllerExists) {
        require_once $controllerPath;
        $controllerLoaded = class_exists('App\Controllers\OwnerController');

        // Check if methods exist
        if ($controllerLoaded) {
            $reflection = new ReflectionClass('App\Controllers\OwnerController');
            $methods = [];
            foreach ($reflection->getMethods() as $method) {
                if ($method->class === 'App\Controllers\OwnerController') {
                    $params = [];
                    foreach ($method->getParameters() as $param) {
                        $params[] = $param->getName();
                    }
                    $methods[$method->getName()] = $params;
                }
            }
        }
    } else {
        $controllerLoaded = false;
        $methods = [];
    }

    $output = ob_get_clean();

    echo json_encode([
        "success" => true,
        "test1_php_working" => $test1,
        "test2_routes_exists" => $routesExists,
        "test3_autoloader" => "Registered",
        "test4_core_loaded" => "Response and JWT loaded",
        "test5_owner_model_exists" => $ownerExists,
        "test6_owner_model_loaded" => $ownerLoaded,
        "test7_controller_exists" => $controllerExists,
        "test8_controller_loaded" => $controllerLoaded,
        "test9_controller_methods" => $methods,
        "captured_output" => $output,
        "php_version" => phpversion()
    ], JSON_PRETTY_PRINT);

} catch (Exception $e) {
    $output = ob_get_clean();
    echo json_encode([
        "error" => $e->getMessage(),
        "file" => $e->getFile(),
        "line" => $e->getLine(),
        "trace" => $e->getTraceAsString(),
        "captured_output" => $output
    ], JSON_PRETTY_PRINT);
}
