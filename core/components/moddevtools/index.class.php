<?php

/**
 * Class modDeveloperToolsMainController
 */
abstract class modDeveloperToolsMainController extends modDeveloperToolsManagerController {
	/** @var modDeveloperTools $modDeveloperTools */
	public $modDeveloperTools;


	/**
	 * @return void
	 */
	public function initialize() {
		$corePath = $this->modx->getOption('moddevtools_core_path', null, $this->modx->getOption('core_path') . 'components/moddevtools/');
		require_once $corePath . 'model/moddevtools/moddevtools.class.php';

		$this->modDeveloperTools = new modDeveloperTools($this->modx);

		$this->addCss($this->modDeveloperTools->config['cssUrl'] . 'mgr/main.css');
		$this->addJavascript($this->modDeveloperTools->config['jsUrl'] . 'mgr/moddevtools.js');
		$this->addHtml('<script type="text/javascript">
		Ext.onReady(function() {
			modDeveloperTools.config = ' . $this->modx->toJSON($this->modDeveloperTools->config) . ';
			modDeveloperTools.config.connector_url = "' . $this->modDeveloperTools->config['connectorUrl'] . '";
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
class IndexManagerController extends modDeveloperToolsMainController {

	/**
	 * @return string
	 */
	public static function getDefaultController() {
		return 'home';
	}
}