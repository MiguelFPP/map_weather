<?php

namespace App\Http\Controllers;

use App\Models\HistoryWeather;
use App\Models\info_weathers;
use Illuminate\Http\Request;

class HistoryWeatherController extends Controller
{
    public function delete(int $id)
    {
        $history = HistoryWeather::find($id);

        if(!$history){
            return redirect()->route('home')->message('History not found');
        }

        $history->delete();
        return redirect()->route('home');
    }

    public function clear()
    {
        $info=HistoryWeather::all();

        foreach ($info as $item) {
            $item->delete();
        }
        return redirect()->route('home');
    }
}
