<?php

namespace App\Http\Controllers\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\NodeResource;
use App\Models\User;
use App\Services\NodeService;
use App\Services\ServerService;
use App\Services\UserService;
use Illuminate\Http\Request;

class NodeController extends Controller
{

    public function fetchCountries(Request $request) {
        $userId = $request->user()->id;

        $user = User::findOrFail($userId);

        $data = [
            'regions' => NodeService::getSupportRegions(),
            // 'contries' => NodeService::getSupportedCountries(),
            'contries' => NodeService::getSupportedCountriesByUser($user),
        ];

        return $this->success($data);
    }

    public function fetchServerInfo(Request $request) {
        $userId = $request->user()->id;
        $country_id = $request->input('countryId');
        $server = NodeService::getRandomServerInfo($userId, country_id: $country_id);
        $nodeContent = NodeService::getContentFromServerInfo($userId, $server);
        return $this->success($nodeContent);
    }
}
