<?php

/**
 * Class modDevToolsMainController
 */
abstract class modDevToolsMainController extends modExtraManagerController {
	/** @var modDevTools $modDevTools */
	public $modDevTools;


	/**
	 * @return void
	 */
	public function initialize() {
		$corePath = $this->modx->getOption('moddevtools_core_path', null, $this->modx->getOption('core_path') . 'components/moddevtools/');
		require_once $corePath . 'model/moddevtools/moddevtools.class.php';

		$this->modDevTools = new modDevTools($this->modx);

		$this->addCss($this->modDevTools->config['cssUrl'] . 'mgr/main.css');
		$this->addJavascript($this->modDevTools->config['jsUrl'] . 'mgr/moddevtools.js');
		$this->addHtml('<script type="text/javascript">
		Ext.onReady(function() {
			modDevTools.config = ' . $this->modx->toJSON($this->modDevTools->config) . ';
			modDevTools.config.connector_url = "' . $this->modDevTools->config['connectorUrl'] . '";
		});
		</script>');

		parent::initialize();
	}


	/**
	 * @return array
	 */
	public function getLanguageTopics() {
		return array('moddevtools:default');
	}


	/**
	 * @return bool
	 */
	public function checkPermissions() {
		return true;
	}
}


/**
 * Class IndexManagerController
 */
class IndexManagerController extends modDevToolsMainController {

	/**
	 * @return string
	 */
	public static function getDefaultController() {
		return 'home';
	}
}