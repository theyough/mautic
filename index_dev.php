<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic, NP
 * @author      Mautic
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

//fix for hosts that do not have date.timezone set
//it will be reset based on users settings
date_default_timezone_set ('UTC');

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

// Cannot function on PHP 5.6 properly due to a Doctrine bug which is included in Doctrine 2.5
// See http://www.doctrine-project.org/jira/browse/DDC-3120 for more
if (version_compare(PHP_VERSION, '5.6', 'ge')) {
    echo "Mautic will not function properly on PHP 5.6 due to an issue with a third party dependency.\n";
    echo "Please downgrade to PHP 5.4 or 5.5.";
    exit;
}

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
//umask(0000);

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
/*
if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1'))
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}
*/
$loader = require_once __DIR__.'/app/bootstrap.php.cache';
Debug::enable();

require_once __DIR__.'/app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
