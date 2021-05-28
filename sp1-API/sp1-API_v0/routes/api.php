<?php

use App\a;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//<----------------Routes pour toutes les applications---------------->

Route::prefix('users')->group(function ()
{
    Route::post('/','UsersController@createElement');
    Route::put('/','UsersController@updateAll');
    Route::get('/accountants/all','UsersController@getAll');

    Route::put('/role/{role_id}','UsersController@updateByRole');
    Route::get('/role/{role_id}','UsersController@getByRole');

    Route::prefix('{user}')->group(function ()
    {
        Route::get('/one','UsersController@getById');
        Route::post('/update','UsersController@update');
        Route::put('/','UsersController@updateById');

        Route::prefix('application/{appli_id}')->group(function () {

            Route::put('enable', 'UsersController@enable');
            Route::put('disable', 'UsersController@disable');

            Route::post('login', 'UsersController@login');
            Route::put('logout', 'UsersController@logout');

            Route::put('last/login', 'UsersController@updateLastLoginByUser');
            Route::get('last/login', 'UsersController@getLastLoginByUser');

            Route::get('time/average', 'UsersController@getAverageTimeByUser');
        });
    });
});

//<----------------Routes application web---------------->
Route::prefix('visits')->group(function () {

    Route::post('/', 'VisitsController@createElement');
    Route::put('/', 'VisitsController@updateAll');
    Route::get('/', 'VisitsController@getAll');
    Route::delete('/', 'VisitsController@deleteAll');

    Route::prefix('{id}')->group(function (){
        Route::put('/', 'VisitsController@updateById');
        Route::get('/', 'VisitsController@getById');
        Route::delete('/', 'VisitsController@deleteById');
        Route::put('cancel', 'VisitsController@cancelById');
        Route::put('postpone', 'VisitsController@postponeById');
        Route::put('report','VisitsController@updateReportById');
        Route::get('report','VisitsController@getReportById');
        Route::delete('report','VisitsController@deleteReportById');
        Route::post('report','VisitsController@createReportById');
    });
    Route::put('doctors/{doctor_id}', 'VisitsController@updateByDoctor');
    Route::get('doctors/{doctor_id}', 'VisitsController@getByDoctor');
    Route::delete('doctors/{doctor_id}', 'VisitsController@deleteByDoctor');

    Route::prefix("users/{user_id}")->group(function (){
        Route::put('date/{date}', 'VisitsController@updateByUserByDate');
        Route::get('/date/{date}', 'VisitsController@getByUserByDate');
        Route::delete('/date/{date}', 'VisitsController@deleteByUserByDate');
        Route::prefix('count')->group(function () {
            Route::prefix('date')->group(function () {
                Route::get('{year}', 'VisitsController@countByYearByUser');
                Route::get('{month}/{year}', 'VisitsController@countByMonthYearByUser');
                Route::get('{day}/{month}/{year}', 'VisitsController@countByDateByUser');
                Route::get('since/sector-date-param', 'VisitsController@countBySectorDateByUser');
            });
            Route::prefix('group')->group(function () {
                Route::get('year', 'VisitsController@countByUserGroupByYear');
                Route::get('month/year', 'VisitsController@countByUserGroupByMonthYear');
                Route::get('date/users', 'VisitsController@countByUserGroupByDate');
            });
        });
    });
});

Route::prefix('doctors')->group(function () {

    Route::post('/', 'DoctorsController@createElement');
    Route::put('/', 'DoctorsController@updateAll');
    Route::get('/', 'DoctorsController@getAll');
    Route::delete('/', 'DoctorsController@deleteAll');

    Route::put('{id}', 'DoctorsController@updateById');
    Route::get('{id}', 'DoctorsController@getById');
    Route::delete('{id}', 'DoctorsController@deleteById');

    Route::put('users/{users_id}', 'DoctorsController@updateByUser');
    Route::get('users/{users_id}', 'DoctorsController@getByUser');
    Route::delete('users/{users_id}', 'DoctorsController@deleteByUser');

});

Route::prefix('medicines')->group(function () {
    Route::post('/', 'MedicinesController@createElement');
    Route::put('/', 'MedicinesController@updateAll');
    Route::get('/', 'MedicinesController@getAll');
    Route::delete('/', 'MedicinesController@deleteAll');

    Route::put('{id}', 'MedicinesController@updateById');
    Route::get('{id}', 'MedicinesController@getById');
    Route::delete('{id}', 'MedicinesController@deleteById');

    Route::put('order/{old_position}/{new_position}', 'MedicinesController@order');

    Route::prefix('sector/{sector_id}')->group(function () {
        Route::put('aim-sector-param', 'MedicinesController@updateAimBySector');
        Route::get('aim-sector-param', 'MedicinesController@getAimBySector');

        Route::get('selled-quantity/{id}', 'MedicinesController@getSelledQuatityById');
    });
});

Route::prefix('complementary-activities')->group(function () {
    Route::post('/', 'ComplementaryActivitiesController@createElement');
    Route::put('/', 'ComplementaryActivitiesController@updateAll');
    Route::get('/', 'ComplementaryActivitiesController@getAll');
    Route::delete('/', 'ComplementaryActivitiesController@deleteAll');

    Route::put('{id}', 'ComplementaryActivitiesController@updateById');
    Route::get('{id}', 'ComplementaryActivitiesController@getById');
    Route::delete('{id}', 'ComplementaryActivitiesController@deleteById');

    Route::put('users/{users_id}', 'ComplementaryActivitiesController@updateByUser');
    Route::get('users/{users_id}', 'ComplementaryActivitiesController@getByUser');
    Route::delete('users/{users_id}', 'ComplementaryActivitiesController@deleteByUser');
});

//<----------------Routes application desktop---------------->

Route::prefix('expense-sheets')->group(function () {

    Route::get('/{date}/date', 'ExpenseSheetsController@getAllByDate');
    Route::get('/waiting', 'ExpenseSheetsController@getAllInWaiting');
    Route::patch('/{date}/update', 'ExpenseSheetsController@updateAllByDate');
    Route::get('/{expenseSheet}/one', 'ExpenseSheetsController@getOne');
    Route::put('{expenseSheet}/update', 'ExpenseSheetsController@updateOne');

    Route::prefix('untreated')->group(function () {
        Route::get('/all', 'ExpenseSheetsController@getAllUntreated');
        Route::get('/{date}/all', 'ExpenseSheetsController@getAllUntreatedByDate');
        Route::patch('/{date}/update', 'ExpenseSheetsController@updateAllUntreatedByDate');
        Route::patch('/update', 'ExpenseSheetsController@updateAllUntreated');
    });

    Route::get('validated/{date}/all', 'ExpenseSheetsController@getAllValidatedByDate');

    Route::get('/users/{user}/historical', 'ExpenseSheetsController@getAllByMedicalVisitor');
    Route::get('/users/{user}/{date}/historical', 'ExpenseSheetsController@getAllByMedicalVisitorByDate');
});

Route::prefix('expenses')->group(function () {

    Route::get('/{expense}/one', 'ExpenseController@getOne');
    Route::put('{expense}', 'ExpenseController@updateOne');
    Route::get('/{state}/state', 'ExpenseController@getAllByState');
    Route::get('/expense-sheet/expense-in/{expenseSheet}/all', 'ExpenseController@getExpenseInPackagesByExpenseSheet');
    Route::get('/expense-sheet/expense-out/{expenseSheet}/all', 'ExpenseController@getExpenseOutPackagesByExpenseSheet');
    Route::delete('{expense}', 'ExpenseController@deleteOne');

});

Route::prefix('expense-packages')->group(function () {

    //Fonctionne
    Route::post('/create', 'ExpensePackagesController@createElement');
    Route::get('/all', 'ExpensePackagesController@getAll');
    Route::put('{expensePackage}', 'ExpensePackagesController@updateOne');
    Route::get('/{expensePackage}/one', 'ExpensePackagesController@getOne');
    Route::delete('/delete/{expensePackage}', 'ExpensePackagesController@deleteOne');

});

Route::prefix('expenses-in-packages')->group(function () {

    Route::get('/all', 'ExpenseInPackagesController@getAll');

});

Route::prefix('expenses-out-packages')->group(function () {

    Route::get('/all', 'ExpenseOutPackagesController@getAll');

});


