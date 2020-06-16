<?php

namespace haxibiao\config;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Aso extends Model
{
    protected $fillable = ['group', 'name', 'value'];

    public function QQNumber($root, array $args, $context)
    {
        return optional(self::where('name', '动态修改群qq号')->first())->value;
    }

    public static function getValue($group, $name)
    {
        $item = Aso::whereGroup($group)->whereName($name)->first();
        if (isset($item)&&str_contains($item->name,'图')){
            $item->value=Storage::cloud()->url($item->value);
        }
        info($name);
        return $item ? $item->value : '';
    }

    public function saveDownloadImage($file, $name)
    {

        if ($file) {
            $aso      = Aso::where('name', $name)->first();
            $aso_path = $aso->value;

            if (\str_contains($aso_path, env('COS_DOMAIN'))) {
                $aso_path = \str_after($aso_path, env('COS_DOMAIN'));
            }

            $cosDisk = Storage::cloud();
            $cosDisk->put($aso_path, \file_get_contents($file->path()));

            return $cosDisk->url($aso_path);
        }
    }
}
