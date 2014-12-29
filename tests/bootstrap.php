<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.1.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

// @codingStandardsIgnoreFile

use Cake\Core\ClassLoader;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\I18n;

$findRoot = function ($root) {
    do {
        $lastRoot = $root;
        $root = dirname($root);
        if (is_dir($root . '/vendor/cakephp/cakephp')) {
            return $root;
        }
    } while ($root !== $lastRoot);
    throw new Exception('Cannot find the root of the application, unable to run tests');
};
$root = $findRoot(__FILE__);
unset($findRoot);
chdir($root);

require_once 'vendor/autoload.php';

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', $root . DS . 'tests' . DS . 'test_app' . DS);
define('APP_DIR', 'App');

define('TMP', sys_get_temp_dir() . DS);

define('CAKE_CORE_INCLUDE_PATH', ROOT);
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('CAKE', CORE_PATH . 'src' . DS);

define('APP', ROOT . 'App' . DS);
define('TESTS', ROOT . DS . 'tests' . DS);

Configure::write('debug', true);
Configure::write('App', [
	'namespace' => 'App',
	'paths' => [
        'plugins' => [ROOT . 'Plugin' . DS],
        'templates' => [ROOT . 'App' . DS . 'Template' . DS]
	]
]);

if (!getenv('db_dsn')) {
	putenv('db_dsn=sqlite:///:memory:');
}
ConnectionManager::config('test', ['url' => getenv('db_dsn')]);

Plugin::load('Bake', [
    'path' => dirname(dirname(__FILE__)) . DS,
    'autoload' => true
]);

$loader = new Cake\Core\ClassLoader;
$loader->register();

$loader->addNamespace('Bake\Test\App', APP);
$loader->addNamespace('BakeTest', ROOT . 'Plugin' . DS . 'BakeTest' . DS . 'src');
