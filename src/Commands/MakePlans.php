<?php

namespace Ro749\ListingUtils\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\GeneratorCommand;

class MakePlans extends GeneratorCommand
{
    protected $name = 'make:plans'; // Así se llama tu comando Artisan
    protected $description = 'Crea una clase Plans';
    protected $type = 'ImageMapPro';

    protected function getStub()
    {
        return __DIR__ . '/../Stubs/plans.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Plans';
    }
}