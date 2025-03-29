<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServerCountries extends Model
{
    //
    protected $table = 'v2_server_countries';

    // protected $dateFormat = 'U';

    protected $casts = [
        'id' => 'string',
        'show' => 'boolean',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp'
    ];

    public function servers()
    {
        return $this->hasMany(Server::class, 'country_id');
    }
}
