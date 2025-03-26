<?php

namespace App\Services;

use App\Models\Server;
use App\Models\ServerCountries;
use App\Models\ServerRoute;
use App\Models\User;
use App\Utils\Helper;
use Illuminate\Support\Collection;

class NodeService
{

    public static function getSupportRegions() {
        $regions = 
         [
            [
                'id' => 'asia',
                'name' => 'Asia',
                'show' => true,
            ],
            [
                'id' => 'europe',
                'name' => 'Europe',
                'show' => true,
            ],
            [
                'id' => 'africa',
                'name' => 'Africa',
                'show' => true,
            ],
            [
                'id' => 'oceania',
                'name' => 'Oceania',
                'show' => true,
            ],
            [
                'id' => 'americas',
                'name' => 'Americas',
                'show' => true,
            ],
            [
                'id' => 'polar',
                'name' => 'Polar',
                'show' => false,
            ],
            [
                'id' => 'antarctic_ocean',
                'name' => 'Antarctic Ocean',
                'show' => true,
            ],
            [
                'id' => 'antarctic',
                'name' => 'Antarctic',
                'show' => true,
            ],
        ];

        // $filterRegions = $regions;

        // Filter regions where 'show' is true
        $filteredRegions = array_filter($regions, function ($region) {
            return $region['show'] === true;
        });

        return array_values($filteredRegions);

        // Return JSON response
        // return json_encode(array_values($filteredRegions), JSON_PRETTY_PRINT);
        
        // return $filteredRegions;
    }

    public static function getSupportedCountries() {
        return ServerCountries::select('id', 'alpha3', 'name', 'native_name', 'region', 'emoji_flag', 'show')->where('show', true)->get();
    }

    public static function getRandomServerInfo($country_id) {

        if ($country_id) {
            return Server::where('country_id', $country_id)
            ->where('show', true)
            ->inRandomOrder()->first();
        } else {
            return Server::where('show', true)
            ->inRandomOrder()->first();
        }
    }
}
