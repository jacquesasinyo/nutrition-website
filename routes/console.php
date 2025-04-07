<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Stichoza\GoogleTranslate\GoogleTranslate;

Artisan::command('inspire', function () {
    $translator = new GoogleTranslate('tr');
    $translatedName = $translator->translate('hi');
    dump($translatedName);
})->purpose('Display an inspiring quote');
