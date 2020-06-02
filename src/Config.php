<?php

namespace haxibiao\config;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $guarded = [];

    const CONFIG_OFF = "off";

    const CONFIG_ON = "on";

}
