<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

use App\Http\Controllers\ProductController;

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\LaporanController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/auth-login', [AuthController::class, 'login'])->name('auth.login');

Route::middleware(['check.token'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/users', [UserController::class, 'user'])->name('user.index');
    Route::post('/users/save', [UserController::class, 'saveUser'])->name('users.save');
    Route::post('/users/updateRoleAccess', [RoleController::class, 'updateRolesUser'])->name('users.updateRoleAccess');
    Route::delete('/users/{id}', [UserController::class, 'deleteUser'])->name('users.delete');

    Route::get('/settings/menus', [MenuController::class, 'index'])->name('menus.index');
    Route::post('/change-role', [MenuController::class, 'changeRole'])->name('change.role');

    Route::get('/settings/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/settings/menu-access', [MenuController::class, 'menuAccess'])->name('menus.access'); 
    Route::post('/settings/roles/save', [RoleController::class, 'saveRole'])->name('roles.save'); 
    Route::delete('/settings/roles/{role_id}', [RoleController::class, 'deleteRole'])->name('roles.delete');
    Route::get('/set-active-menu', [MenuController::class, 'setActiveMenu'])->name('setActiveMenu');

    Route::post('transactions/export', [TransactionController::class, 'exportToExcel'])->name('transactions.export');

Route::get('product', [ProductController::class, 'index'])->name('product.index');

Route::post('product/save', [ProductController::class, 'saveProduct'])->name('product.save');

Route::delete('product/delete/{id}', [ProductController::class, 'deleteProduct'])->name('product.delete');

Route::get('transaction', [TransactionController::class, 'index'])->name('transaction.index');

Route::post('transaction/save', [TransactionController::class, 'saveTransaction'])->name('transaction.save');

Route::delete('transaction/delete/{id}', [TransactionController::class, 'deleteTransaction'])->name('transaction.delete');

Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
});
