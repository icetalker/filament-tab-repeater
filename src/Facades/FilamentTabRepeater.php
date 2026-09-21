<?php

namespace Icetalker\FilamentTabRepeater\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Icetalker\FilamentTabRepeater\FilamentTabRepeater
 */
class FilamentTabRepeater extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Icetalker\FilamentTabRepeater\Forms\Components\TabRepeater::class;
    }
}
