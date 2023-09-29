<?php

namespace App\Http;

use Illuminate\Support\Facades\File;

class Directory
{
    public static function files($path = null): array
    {
        $files = [];

        if(!File::exists($path)) return [];

        foreach(File::files($path) as $file)
        {
            $files[] = $file->getRelativePathname();
        }

        return $files;
    }

    public static function models($not_in = []): array
    {
        $models = [];

        foreach(self::files(base_path('App/Models')) as $file)
        {
            if(in_array($file, $not_in) || str($file)->contains('Translation')) continue;

            $models[] = str($file)->replaceLast('.php','')->value();
        }

        return $models;
    }

    public static function migrations($model = null, $type = 'array'): array|string
    {
        if(!in_array($type, ['array', 'string'])) throw new \Exception('Invalid type');

        $migration = ($type == 'array') ? [] : '';

        foreach (self::files(base_path('database/migrations')) as $file)
        {
            if($model && !str($file)->contains($model)) continue;

            if($type == 'array') $migration[] = base_path('database/migrations/' . $file);

            else $migration = $file;
        }

        return $migration;
    }

    public static function migration($model): array|string
    {
        return self::migrations($model,'string');
    }
}
