<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
//use App\Models\

/* 
   Quotes 
   php artisan insipre 
 
*/ 


Artisan::command('inspire', function () { 
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


//Artisan::