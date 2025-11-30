<?php

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\OwnerController;
use App\Controllers\AdminController;
use App\Controllers\VillaController;
use App\Controllers\BookingController;
use App\Controllers\SearchController;
use App\Controllers\HomeController;
use App\Controllers\PaymentController;

$router = new Router();

$router->post('/admin-login', [AuthController::class, 'admin_login']);
$router->post('/owner-login', [AuthController::class, 'owner_login']);
$router->post('/user-login', [AuthController::class, 'user_login']);
$router->post('/user-register', [AuthController::class, 'user_register']);

$router->get('/villas', [VillaController::class, 'index']);
$router->get('/villas/{id}', [VillaController::class, 'show']);
$router->post('/villas', [VillaController::class, 'store']);
$router->put('/villas/{id}', [VillaController::class, 'update']);
$router->delete('/villas/{id}', [VillaController::class, 'destroy']);

$router->get('/bookings', [BookingController::class, 'index']);
$router->get('/bookings/{id}', [BookingController::class, 'show']);
$router->post('/bookings', [BookingController::class, 'store']);
$router->put('/bookings/{id}', [BookingController::class, 'update']);

$router->get('/search', [SearchController::class, 'search']);

$router->get('/home/sliders', [HomeController::class, 'sliders']);
$router->get('/home/categories', [HomeController::class, 'categories']);

$router->get('/user/profile', [UserController::class, 'profile']);
$router->put('/user/profile', [UserController::class, 'updateProfile']);
$router->get('/user/bookings', [UserController::class, 'bookings']);

$router->get('/owner/dashboard', [OwnerController::class, 'dashboard']);
$router->get('/owner/villas', [OwnerController::class, 'villas']);
$router->get('/owner/bookings', [OwnerController::class, 'bookings']);

$router->get('/admin/dashboard', [AdminController::class, 'dashboard']);
$router->get('/admin/users', [AdminController::class, 'users']);
$router->get('/admin/owners', [AdminController::class, 'owners']);
$router->get('/admin/bookings', [AdminController::class, 'bookings']);
$router->get('/admin/villas', [AdminController::class, 'villas']);
$router->get('/admin/pending-villas', [AdminController::class, 'pendingVillas']);
$router->post('/admin/villa/approve', [AdminController::class, 'approveVilla']);
$router->post('/admin/villa/reject', [AdminController::class, 'rejectVilla']);

$router->post('/payments/create', [PaymentController::class, 'create']);
$router->post('/payments/verify', [PaymentController::class, 'verify']);

$router->dispatch();
