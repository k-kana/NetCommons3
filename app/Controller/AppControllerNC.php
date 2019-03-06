<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('NetCommonsAppController', 'NetCommons.Controller');
App::uses('DebugTimer', 'DebugKit.Lib');
App::uses('ConnectionManager', 'Model');

/**
 * Application Controller for NetCommons
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 * @SuppressWarnings(PHPMD.NumberOfChildren)
 */
class AppController extends NetCommonsAppController {

/**
 * Constructor.
 *
 * @param CakeRequest $request Request object for this controller. Can be null for testing,
 *  but expect that features that use the request parameters will not work.
 * @param CakeResponse $response Response object for this controller.
 */
	public function __construct($request = null, $response = null) {
		//TODO: 測定用に追加。最後、削除する
		if (!empty($request) && empty($request->params['requested'])) {
			$url = $request->params['plugin'] . '/' . $request->params['controller'] . '/' . $request->params['action'];
			if ($request->params['pass']) {
				$url .= '/' . implode('/', $request->params['pass']);
			}
			if ($request->query) {
				$url .= '?' . implode('&', $request->query);
			}
			DebugTimer::start('plugin_timer_here', $url);
		}
		parent::__construct($request, $response);
	}

/**
 * beforeFilter
 *
 * @return void
 */
	public function beforeFilter() {
		$this->Components->unload('DebugKit.Toolbar');

if (empty($this->request->params['requested'])) {
	CakeLog::debug("=========================================");
	CakeLog::write('sqldump', "=========================================");
	$indent = '';
} else {
	$indent = '  ';
}

$db = ConnectionManager::getDataSource('master');
CakeLog::write('sqldump', $indent . __METHOD__ . '(' . __LINE__ . ') ' .
		preg_replace("/" . preg_quote("\\'", '/') . "/", "'", var_export($db->getLog(), true)));

$this->__key = md5(json_encode($this->request->params) .
		json_encode($this->request->params) . json_encode($this->request->data));
CakeLog::write('sqldump', $indent . '##### ' . var_export($this->__key, true));
CakeLog::write('sqldump', $indent . __METHOD__ . '(' . __LINE__ . ') ' .
		var_export($this->request->params, true));
CakeLog::write('sqldump', $indent . __METHOD__ . '(' . __LINE__ . ') ' .
		var_export($this->request->query, true));
CakeLog::write('sqldump', $indent . __METHOD__ . '(' . __LINE__ . ') ' .
		var_export($this->request->data, true));

CakeLog::debug($indent . '##### ' . var_export($this->__key, true));
CakeLog::debug($indent . __METHOD__ . '(' . __LINE__ . ') ' .
		var_export($this->request->params, true));
CakeLog::debug($indent . __METHOD__ . '(' . __LINE__ . ') ' .
		var_export($this->request->query, true));
CakeLog::debug($indent . __METHOD__ . '(' . __LINE__ . ') ' .
		var_export($this->request->data, true));

$this->_startTime = $this->_renderTime = microtime(true);

		parent::beforeFilter();
	}

/**
 * Called after the controller action is run, but before the view is rendered. You can use this method
 * to perform logic or set view variables that are required on every request.
 *
 * @return void
 * @link https://book.cakephp.org/2.0/en/controllers.html#request-life-cycle-callbacks
 */
	public function beforeRender() {
$this->_renderTime = microtime(true);
if (empty($this->request->params['requested'])) {
	$indent = '';
} else {
	$indent = '  ';
}

CakeLog::debug($indent . '##### ' . var_export($this->__key, true));
CakeLog::debug($indent . __METHOD__ . '(' . __LINE__ . ')  beforeFilter - beforeRender = Action');
CakeLog::debug($indent . var_export(($this->_renderTime - $this->_startTime), true));

if (empty($this->request->params['requested'])) {
	CakeLog::debug($indent . "--------");
}

		parent::beforeRender();
	}

/**
 * Called after the controller action is run and rendered.
 *
 * @return void
 * @link http://book.cakephp.org/2.0/ja/controllers.html#request-life-cycle-callbacks
 */
	public function afterFilter() {
		parent::afterFilter();

		//TODO: 測定用に追加。最後、削除する
		if (!empty($this->request) && empty($this->request->params['requested'])) {
			DebugTimer::stop('plugin_timer_here');
		}

if (empty($this->request->params['requested'])) {
	$indent = '';
} else {
	$indent = '  ';
}

$endTime = microtime(true);
CakeLog::debug($indent . '##### ' . var_export($this->__key, true));
CakeLog::debug($indent . __METHOD__ . '(' . __LINE__ . ')  beforeRender - afterFilter = Render');
CakeLog::debug($indent . var_export(($endTime - $this->_renderTime), true));
CakeLog::debug($indent . '##### ' . var_export($this->__key, true));
CakeLog::debug($indent . __METHOD__ . '(' . __LINE__ . ')  beforeFilter - afterFilter = Total');
CakeLog::debug($indent . var_export(($endTime - $this->_startTime), true));
CakeLog::debug($indent . "--------");

$db = ConnectionManager::getDataSource('master');
CakeLog::write('sqldump', $indent . '##### ' . var_export($this->__key, true));
CakeLog::write('sqldump', $indent . __METHOD__ . '(' . __LINE__ . ') ' .
		preg_replace("/" . preg_quote("\\'", '/') . "/", "'", var_export($db->getLog(), true)));
CakeLog::write('sqldump', $indent . "--------");


if (empty($this->request->params['requested'])) {
	CakeLog::debug("");
	CakeLog::debug("");
	CakeLog::debug("");
	CakeLog::debug("");
}

	}

}
