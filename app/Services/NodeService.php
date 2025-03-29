<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Server;
use App\Models\ServerCountries;
use App\Models\ServerRoute;
use App\Models\User;
use App\Protocols\General;
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

        return ServerCountries::select('id', 'alpha3', 'native_name', 'region', 'emoji_flag', 'show')
        ->where('show', true)->get();
    }

    public static function getSupportedCountriesByUser(User $user) {
        return ServerCountries::select('v2_server_countries.id', 'alpha3', 'native_name', 'region', 'emoji_flag', 'v2_server_countries.show')
        ->join('v2_server', 'v2_server.country_id', '=', 'v2_server_countries.id')
        ->distinct()
        ->whereJsonContains('v2_server.group_ids', (string) $user->group_id)
        ->where('v2_server.show', true)
        ->get();
    }



    /**
     * 获取指定用户可用的服务器列表
     */
    public static function getAvailableServerWithCountryId(User $user, $countryId)
    {

        $serverQuery = Server::whereJsonContains('group_ids', (string) $user->group_id)
            ->where('show', true)
            ->orderBy('sort', 'ASC');

        if ($countryId) {
            $serverQuery->where('country_id', $countryId);
        }

        $server = $serverQuery->inRandomOrder()->first();

        return $server;

        if ($countryId) {
            $server = Server::where('country_id', $countryId)
            ->whereJsonContains('group_ids', (string) $user->group_id)
            ->where('show', operator: true)
            ->orderBy('sort', 'ASC')
            ->inRandomOrder()->first();
        } else {
            $server = Server::whereJsonContains('group_ids', (string) $user->group_id)
            ->where('show', operator: true)
            ->orderBy('sort', 'ASC')
            ->inRandomOrder()->first();
        }

        return $server;

        #
        // return Server::where('country_id', $countryId)
        // ->whereJsonContains('group_ids', (string) $user->group_id)
        // ->where('show', operator: true)
        // ->orderBy('sort', 'ASC')
        // ->inRandomOrder()->first();
    }

    public static function getRandomServerInfo($userId, $country_id) {

        $user = User::findOrFail($userId);
        
        return self::getAvailableServerWithCountryId($user, $country_id);

        // return null;

        // if ($isAvailable) {
        //     if ($country_id) {
        //         return Server::where('country_id', $country_id)
        //         ->where('show', true)
        //         ->inRandomOrder()->first();
        //     } else {
        //         return Server::where('show', true)
        //         ->inRandomOrder()->first();
        //     }
        // }

    }

    public static function isAvailableUser(User $user) {
        if (!$user->banned && $user->transfer_enable && ($user->expired_at > time() || $user->expired_at === NULL)) {
            return true;
        }
        return false;
    }

    public static function getContentFromServerInfo($userId, $server) {
        $user = User::findOrFail($userId);

        $isAvailable = self::isAvailableUser($user);
        if (!$user->group_id) {
            throw new ApiException(__('not user plan group'), 400402, strval(400402));
        }
        if (!$isAvailable) {
            if ($user->banned) {
                throw new ApiException( __('Unavailable user banned'), 400401, strval(400401));
            }
            if (($user->expired_at > time() || $user->expired_at === NULL)) {
                throw new ApiException(__('Expired user plan'), 400402, strval(400402));
            }
            if ($user->transfer_enable) {
                throw new ApiException( __('no transfer enable'), 400403, strval(400403));
            }
        }

        if ($server) {

            return (new General($user, [$server]))->handle();
        }

        throw new ApiException( __('no transfer enable'), 400404, strval(400404));


        // $userService = new UserService();
        // if ($userService->isAvailable($user)) {
        //     $servers = ServerService::getAvailableServers($user);
        // }
        // $eTag = sha1(json_encode(array_column($servers, 'cache_key')));
        // if (strpos($request->header('If-None-Match', ''), $eTag) !== false ) {
        //     return response(null,304);
        // }
        // $data = NodeResource::collection($servers);
        // return response([
        //     'data' => $data
        // ])->header('ETag', "\"{$eTag}\"");

    }
}
