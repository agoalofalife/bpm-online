<?php


use agoalofalife\bpm\KernelBpm;
use agoalofalife\bpm\SourcesConfigurations\File;

require_once './vendor/autoload.php';

$test = new KernelBpm();
$file = new File();

// 1.Option
// set file with configuration | pre settings
//$file->setSource(__DIR__ . '/config/apiBpm.php');
//$test->loadConfiguration($file);


// 2.Option
// set configuration manually
$test->setConfigManually('apiBpm', [
        'UrlLogin' => '',
        'Login'    => '',
        'Password' => '',
        'UrlHome'  => ''
]);

// 3, Setting collection
$test->setCollection('CaseCollection');

// 4. get Cookie or just auth
//$test->authentication();

// 5. Example Reading from API
//$test = $test->action('read:json', function ($read){
//    $read->amount(1)->skip(100);
//
//})->get();
//
//dd($test->toArray(), '?');

// 6.Example Creating from API
//$test = $test->action('create:xml', function ($creator){
//    $creator->setData([
//        // array key => value
//    ]);
//})->get();
//dd($test, 'done');

// 7 Example Update from API
$test = $test->action('update:xml', function ($creator){
    $creator->guid('')->setData([
       'Number' => 'test'
    ]);
})->get();
dd($test);