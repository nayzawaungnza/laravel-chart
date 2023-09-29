<?php

namespace App\Http\Controllers;

use App\Models\Wage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChartController extends Controller
{
    public function index(){

        $today_income = 0;
        $today_outcome = 0;
        $today_date = date('Y-m-d');
        $today_inOutData = Wage::whereDate('date',$today_date)->get();
        foreach ($today_inOutData as $inOutData) {
            if($inOutData->type == 'in'){
                $today_income += $inOutData->price;
            }
            if($inOutData->type == 'out'){
                $today_outcome += $inOutData->price;
            }
        }

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

        //return $today_inData;
        $data = Wage::orderBy('date','desc')->get();
        return view('welcome',compact('data', 'today_income','today_outcome','day_arr','income_amount','outcome_amount'));
    }
    public function store(Request $request){
        //dd($request->toArray());
        Validator::make($request->all(), [
            'title' => 'required',
            'date' => 'required',
            'price' => 'required',
            'type'=> 'required',
        ])->validate();

        Wage::create([
            'title' => $request->title,
            'date' => $request->date,
            'price' => $request->price,
            'type' => $request->type,
        ]);
        return redirect()->route('wage#store')->with(['success' => 'Successfully']);
    }
}
