<?php

namespace App\Http\Controllers;

use App\Models\HistoryWeather;
use Carbon\Carbon;
use Illuminate\Http\Request;

use GuzzleHttp\Client;

class weatherController extends Controller
{
    private $client;
    private $api_key;
    private $endpoint;


    public function __construct()
    {
        $this->client = new Client();
        $this->api_key = config('services.weather.key');
        $this->endpoint = config('services.weather.url');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $history = $this->getHistory();
        return view('home', ['history' => $history]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $history = $this->getHistory();
        $city = $request->city;
        if (!empty($city)) {
            $response = $this->client->get($this->endpoint . '?q=' . $city . ',&APPID=' . $this->api_key);

            $statusCode = $response->getStatusCode();
            $jsonData = $response->getBody()->getContents();
            $data = json_decode($jsonData);

            if ($statusCode == 200) {

                app()->call([HistoryWeatherController::class, 'store'], [
                    'data' => $data,
                    'city' => $city
                ]);

                $history = $this->getHistory();
                return view('home', ['data' => $data, 'status' => $statusCode, 'history' => $history]);
            }
        } else {
            return view('home', ['msg' => 'Debe seleccionar una ciudad', 'history' => $history]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $history = $this->getHistory();
        if (isset($id) && !empty($id)) {

            $history_detail = HistoryWeather::where('id', $id)->get()->first();

            if (isset($history_detail) && !empty($history_detail)) {
                $history_detail = $this->convertHistoryData($history_detail);
                $history_detail->main->format_created = $this->formatDate($history_detail->main->created_at);
            }

            return view('home', ['id' => $id, 'history' => $history, 'history_detail' => $history_detail]);
        } else {
            return view('home', ['history' => $history]);
        }
    }

    /**
     * The function converts historical weather data from an array to an object in PHP.
     * 
     * @param object data The parameter `` is an object that contains historical weather data for
     * a city.
     * 
     * @return object an object that contains the converted historical weather data.
     */
    public function convertHistoryData(object $data): object
    {
        $history_detail = [
            "name" => $data->name_city,
            "coord" => [
                "lon" => $data->longitude,
                "lat" => $data->latitude
            ],
            "main" => [
                "temp" => $data->temp,
                "feels_like" => $data->feels_like,
                "temp_min" => $data->temp_min,
                "temp_max" => $data->temp_max,
                "pressure" => $data->pressure,
                "humidity" => $data->humidity,
                "created_at" => $data->created_at,
            ],
            "longitude" => $data->longitude,
            "latitude" => $data->latitude,
        ];

        /* convert all array content in onject */
        $history_detail = json_decode(json_encode($history_detail));

        return $history_detail;
    }

    /**
     * This function formats a given date string into a translated format of day, month, and year.
     * 
     * @param string date The input parameter "date" is a string representing a date in any valid
     * format that can be parsed by the Carbon library.
     * 
     * @return string The function `formatDate` is returning a string that represents the given date in
     * the format of "day month year".
     */
    public function formatDate(string $date): string
    {
        return Carbon::parse($date)->translatedFormat('d F Y');
    }

    /**
     * This function returns the history of weather data by calling the index method of the
     * HistoryWeatherController class.
     * 
     * @return object The `getHistory()` function is returning an object that is obtained by calling
     * the `index()` function of the `HistoryWeatherController`.
     */
    public function getHistory(): object
    {
        $history = app()->call([HistoryWeatherController::class, 'index']);
        return $history;
    }
}
