<?php

use App\Models\Wage;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [ChartController::class, 'index']);
Route::post('/', [ChartController::class, 'store'])->name('wage#store');

Route::get('/test',function(){
    $day_arr = [date('D')];
    $date_arr = [
        [
            'year'=>date('Y'),
            'month'=>date('m'),
            'day'=>date('d'),
        ]
    ];

    for ($i=1; $i <= 6 ; $i++) { 
        $day_arr[] = date('D', strtotime("-$i day"));

        $new_date = [
            'year'=>date('Y', strtotime("-$i day")),
            'month'=>date('m', strtotime("-$i day")),
            'day'=>date('d', strtotime("-$i day")),
        ];

        $date_arr[] = $new_date;
    }
    $income_amount = [];
    $outcome_amount = [];

    foreach ($date_arr as $date) {
        $income_amount[] = Wage::whereYear('date',$date['year'])
                                ->whereMonth('date',$date['month'])
                                ->whereDay('date',$date['day'])
                                ->where('type','in')
                                ->sum('price');
        $outcome_amount[] = Wage::whereYear('date',$date['year'])
                                ->whereMonth('date',$date['month'])
                                ->whereDay('date',$date['day'])
                                ->where('type','out')
                                ->sum('price');
    }

    return $income_amount;
});
