<?php
putenv("ORACLE_HOME=/u01/app/oracle/product/11.2.0/xe");
putenv("LD_LIBRARY_PATH=/u01/app/oracle/product/11.2.0/xe/lib:/lib:/usr/lib");
require_once '../vendor/autoload.php';

// init Silex app
$app = new Silex\Application();
$app['debug'] = true;

//configure database connection
/*
3.1.4.6. pdo_oci / oci8

user (string): Username to use when connecting to the database.
password (string): Password to use when connecting to the database.
host (string): Hostname of the database to connect to.
port (integer): Port of the database to connect to.
dbname (string): Name of the database/schema to connect to.
servicename (string): Optional name by which clients can connect to the database instance. Will be used as Oracle’s SID connection parameter if given and defaults to Doctrine’s dbname connection parameter value.
service (boolean): Whether to use Oracle’s SERVICE_NAME connection parameter in favour of SID when connecting. The value for this will be read from Doctrine’s servicename if given, dbname otherwise.
pooled (boolean): Whether to enable database resident connection pooling.
charset (string): The charset used when connecting to the database.
instancename (string): Optional parameter, complete whether to add the INSTANCE_NAME parameter in the connection. It is generally used to connect to an Oracle RAC server to select the name of a particular instance.
*/

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'oci8',
        'host' => 'localhost',
        'port' => '1521',
        'user' => 'buku555',
        'password' => 'buku555',
        'servicename' => 'XE'

    ),
));

// route for "/countries" URI: load countries list and return it in JSON format
$app->get('/users', function () use ($app) {

    $sql = "SELECT * FROM b_trxs";
    $users = $app['db']->fetchAll($sql);

    return $app->json($users);
});

// route for "/countries/{id}" URI: load specific country info and return it in JSON format
/*
$app->get('/countries/{id}', function ($id) use ($app) {
    $sql = "SELECT id, cities FROM countries WHERE id = ?";
    $country = $app['db']->fetchAssoc($sql, array((int) $id));

    return $app->json($country);
})->assert('id', '\d+');
*/
// default route
$app->get('/', function () {
    return "List of avaiable methods:
  - /countries - returns list of existing countries;\n
  - /countries/{id} - returns list of country's cities by id;";
});

$app->run();