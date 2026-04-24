<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::index');
$routes->get('/login', 'Auth::index');
$routes->get('/logout', 'Auth::logout');
$routes->post('/login-process', 'Auth::loginProcess');

$routes->group('', ['filter' => 'authCheck'], function($routes) {    
    $routes->get('/dirs', 'Home::dirs');
    $routes->get('/dir/(:any)', 'Home::uploadPhotos/$1');

    $routes->post('upload/uploadFile', 'Photos::uploadFile');
    $routes->post('/delete-photo', 'Photos::deletePhoto');
});

$routes->get('/my-qr/(:any)', 'Photos::QRPhotos/$1');
$routes->post('/load-dirs', 'Dirs::index');

// API
$routes->resource('api/photos');

$routes->post('/create-dir', 'API\Photos::createDirForm');
$routes->get('api/delete-dir/(:any)', 'API\Photos::deleteDirectory/$1');
$routes->get('api/validate-dir', 'API\Photos::validateDirectoryExpiry');
$routes->post('api/create-dir', 'API\Photos::createDir');
$routes->post('api/upload-photo', 'API\Photos::uploadPhoto');
$routes->post('api/register-photos', 'API\Photos::registerPhotos');




