<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';
const PATH = __DIR__ . '/../../src/resource/logs';

$app = new Slim\App();
// --------------
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
        ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
});
// --------


$app->get('/', function (Request $request, Response $response) {
    require_once('index.html');
});
$app->get('/api/statistic-request', function (Request $request, Response $response) {
    $data['date']          = [];
    $data['durations'] = [];
    $dateList     = [];
    $durationList = [];

    array_map(function($file) use (&$dateList, &$data, &$durationList){
        preg_match_all( '/[0-9]{4}-[0-9]{2}-[0-9]{2}\s[0-9]{2}:[0-9]{2}:[0-9]{2}/', file_get_contents(PATH .'/'. $file), $matchesDate);
        preg_match_all( '/:([0-9]\.[0-9]+)/', file_get_contents(PATH .'/'. $file), $matchesDuration);
        $data['date']      = array_merge($data['date'], array_shift($matchesDate));
        $data['durations'] = array_merge($data['durations'], $matchesDuration[1]);

    }, getFiles(PATH));

    return $response->withJson(json_encode($data), 200);
});

$app->post('/api/filterDates', function (Request $request, Response $response) {

    $data['date']     = [];
    $data['durations'] = [];
    $dateList         = [];
    $durationList     = [];
    array_map(function($file) use (&$dateList, &$data, $request, &$durationList){
        array_map(function($line) use ($request, &$dateList, &$data, &$durationList){
            if (preg_match("/{$request->getParams()['date']}/", $line))
            {
                preg_match_all( '/[0-9]{4}-[0-9]{2}-[0-9]{2}\s[0-9]{2}:[0-9]{2}:[0-9]{2}/', $line, $matchesDate);
                preg_match_all( '/:([0-9]\.[0-9]+)/', $line, $matchesDuration);
                $data['date']      = array_merge($data['date'], array_shift($matchesDate));
                $data['durations'] = array_merge( $data['durations'], $matchesDuration[1]);
            }
        }, explode(PHP_EOL, file_get_contents(PATH .'/'. $file)));
    }, getFiles(PATH));

    return $response->withJson(json_encode($data), 200);
});

$app->get('/api/listDates', function (Request $request, Response $response) {
    $data             = [];
    $dateList         = [];
    array_map(function($file) use (&$dateList, &$data){
        preg_match_all( '/[0-9]{4}-[0-9]{2}-[0-9]{2}/', file_get_contents(PATH .'/'. $file), $matchesDate);
        $data['date']      = array_merge($dateList, array_shift($matchesDate));
    }, getFiles(PATH));
    $data['date'] =array_unique($data['date']);
    return $response->withJson(json_encode($data), 200);

});

$app->run();


// get only files in dir
function getFiles($path)
{
    return array_values(array_filter(scandir($path), function($file) {
        return !is_dir($file);
    }));
}