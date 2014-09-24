<?php
/**
 * The home manager controller for modDeveloperTools.
 *
 */
class modDeveloperToolsHomeManagerController extends modDeveloperToolsMainController {
	/* @var modDeveloperTools $modDeveloperTools */
	public $modDeveloperTools;


	/**
	 * @param array $scriptProperties
	 */
	public function process(array $scriptProperties = array()) {
	}


	/**
	 * @return null|string
	 */
	public function getPageTitle() {
		return $this->modx->lexicon('moddevtools');
	}


	/**
	 * @return void
	 */
	public function loadCustomCssJs() {
		$this->addJavascript($this->modDeveloperTools->config['jsUrl'] . 'mgr/widgets/items.grid.js');
		$this->addJavascript($this->modDeveloperTools->config['jsUrl'] . 'mgr/widgets/home.panel.js');
		$this->addJavascript($this->modDeveloperTools->config['jsUrl'] . 'mgr/sections/home.js');
		$this->addHtml('<script type="text/javascript">
		Ext.onReady(function() {
			MODx.load({ xtype: "moddevtools-page-home"});
		});
		</script>');
	}


	/**
	 * @return string
	 */
	public function getTemplateFile() {
		return $this->modDeveloperTools->config['templatesPath'] . 'home.tpl';
	}
}