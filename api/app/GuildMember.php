<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuildMember extends Model
{
    protected $table = 'guild_members';

    public $timestamps = false;

    public $server;
}
