<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Lalbert\Silex\Provider\MongoDBServiceProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();

$app['debug'] = true;

$app->register(new MongoDBServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../frontend/views',
));

$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.twig');
});

$app->post('/new_user', function (Request $request) use ($app) {
    $doc = array(
        'firstname' => mb_strtolower($request->request->get('firstname')),
        'lastname' => mb_strtolower($request->request->get('lastname')),
        'nickname' => mb_strtolower($request->request->get('nickname')),
        'age' => $request->request->get('age'),
        'password' => hash('sha256', $request->request->get('password'))
    );

    $valid = new Validator();
    $nickname = array('nickname' => $doc['nickname']);

    $unique = $app['mongodb']
        ->Hell
        ->Users
        ->find($nickname)
        ->toArray();

    if ($valid->validateAll($doc['firstname'], $doc['lastname'], $doc['nickname'], $doc['age'], $doc['password'])) {
        if (empty($unique)) {
            $app['mongodb']
                ->Hell
                ->Users
//                ->insertOne($doc)
            ;
            return $app->json(json_encode($doc), 201);
        } else {
            return $app->json(false, 203);
        }
    } else {
        return $app->json(false, 203);
    }
});

$app->post('/old_user', function (Request $request) use ($app) {
    $doc = array(
        'nickname' => mb_strtolower($request->request->get('nickname')),
        'password' => hash('sha256', $request->request->get('password'))
    );

    $result = $app['mongodb']
        ->Hell
        ->Users
        ->find($doc)
        ->toArray();

    return $app->json(json_encode($result), 200);
});

$app->post('/search_user', function (Request $request) use ($app) {
    $doc = array(
        'search' => mb_strtolower($request->request->get('search'))
    );

    $userSearch = new \MongoDB\BSON\Regex($doc['search'], "i");

    $result = $app['mongodb']
        ->Hell
        ->Users
        ->find(array("nickname" => $userSearch))
        ->toArray();

    if (empty($result)) {
        $result = $app['mongodb']
            ->Hell
            ->Users
            ->find(array("firstname" => $userSearch))
            ->toArray();
    }
    if (empty($result)) {
        $result = $app['mongodb']
            ->Hell
            ->Users
            ->find(array("lastname" => $userSearch))
            ->toArray();
    }
    if (empty($result)) {
        $result = $app['mongodb']
            ->Hell
            ->Users
            ->find(array("age" => $userSearch))
            ->toArray();
    }

    return json_encode($result);
});


$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    switch ($code) {
        case 404:
            return $message = '404 Not Found';
        case 500:
            return $message = '500 Internal Server Error';
        default:
            $message = 'We are sorry, but something went terribly wrong.';
    }

    return new Response($message);
});

$app->run();

class Validator
{
    function validateAll($fName, $lName, $nName, $age, $pswd)
    {
        $valid = true;

        if (!$this->validateFirstName($fName)) {
            $valid = false;
        }
        if (!$this->validateLastName($lName)) {
            $valid = false;
        }
        if (!$this->validateNickname($nName)) {
            $valid = false;
        }
        if (!$this->validateAge($age)) {
            $valid = false;
        }
        if (!$this->validatePassword($pswd)) {
            $valid = false;
        }
        return $valid;
    }

    function validateFirstName($fName)
    {
        $valid = true;

        if (strlen($fName) == 0 || !preg_match('^[a-zA-Z\s]+$^', $fName)) {
            $valid = false;
        }
        return $valid;
    }

    function validateLastName($lName)
    {
        $valid = true;

        if (strlen($lName) == 0 || !preg_match('^[a-zA-Z\s]+$^', $lName)) {
            $valid = false;
        }
        return $valid;
    }

    function validateNickname($nName)
    {
        $valid = true;
        if (strlen($nName) == 0 || !preg_match('^[A-Za-z0-9]+$^', $nName)) {
            $valid = false;
        }
        return $valid;
    }

    function validateAge($age)
    {
        $valid = true;

        if (strlen($age) == 0 || strlen($age) > 3 || !preg_match('^[0-9]+$^', $age)) {
            $valid = false;
        }
        return $valid;
    }

    function validatePassword($pswd)
    {
        $valid = true;

        if (strlen($pswd) == 0 || !preg_match('^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$^', $pswd)) {
            $valid = false;
        }
        return $valid;
    }
}