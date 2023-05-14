<?php

namespace App\Http\Controllers;

use App\Models\HistoryWeather;
use Illuminate\Http\RedirectResponse;

class HistoryWeatherController extends Controller
{
    /**
     * This function retrieves the 10 most recent history weather records and returns them.
     * 
     * @return object a collection of the last 10 records from the "history_weathers" table, ordered by
     * their ID in descending order.
     */
    public static function index(): object
    {
        $history = HistoryWeather::take(10)
            ->orderBy('id', 'desc')
            ->get();
        return $history;
    }

    /**
     * This PHP function deletes a history record of weather data by ID and redirects to the home page.
     * 
     * @param int id The parameter "id" is an integer that represents the unique identifier of a record
     * in the "history_weathers" table. This function is used to delete a record with the given id from
     * the database.
     * 
     * @return RedirectResponse a `RedirectResponse` object.
     */
    public function delete(int $id): RedirectResponse
    {
        $history = HistoryWeather::find($id);

        if (!$history) {
            return redirect()->route('home')->message('History not found');
        }

        $history->delete();
        return redirect()->route('home');
    }

    /**
     * The function clears all records from the HistoryWeather table and redirects to the home page.
     * 
     * @return RedirectResponse A `RedirectResponse` is being returned.
     */
    public function clear(): RedirectResponse
    {
        $info = HistoryWeather::all();

        foreach ($info as $item) {
            $item->delete();
        }
        return redirect()->route('home');
    }

    /**
     * This function stores weather information for a given city in a database.
     * 
     * @param object data an object containing weather data for a specific city, obtained from an API
     * call
     * @param string city The name of the city for which the weather information is being stored.
     */
    public static function store(object $data, string $city): void
    {
        $info_weather = new HistoryWeather();
        $info_weather->name_city = $city;
        $info_weather->latitude = $data->coord->lat;
        $info_weather->longitude = $data->coord->lon;
        $info_weather->temp = $data->main->temp;
        $info_weather->feels_like = $data->main->feels_like;
        $info_weather->temp_min = $data->main->temp_min;
        $info_weather->temp_max = $data->main->temp_max;
        $info_weather->pressure = $data->main->pressure;
        $info_weather->humidity = $data->main->humidity;
        $info_weather->save();
    }
}
