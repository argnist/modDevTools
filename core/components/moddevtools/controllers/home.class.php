<?php
/**
 * The home manager controller for modDevTools.
 *
 */
require_once dirname(__FILE__) . '/index.class.php';

/**
 * Class modDevToolsHomeManagerController
 */
class modDevToolsHomeManagerController extends modDevToolsBaseManagerController {
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
        parent::loadCustomCssJs();

        $this->addJavascript($this->modDevTools->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->modDevTools->config['jsUrl'] . 'mgr/widgets/search.form.js');
        $this->addJavascript($this->modDevTools->config['jsUrl'] . 'mgr/widgets/regenerate.form.js');
        $this->addJavascript($this->modDevTools->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->modDevTools->config['jsUrl'] . 'mgr/sections/home.js');

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
        return $this->modDevTools->config['templatesPath'] . 'home.tpl';
    }

    /**
     * @return bool
     */
    public function checkPermissions() {
        return $this->modx->hasPermission('view_chunk') && $this->modx->hasPermission('view_template');
    }
}

return 'modDevToolsHomeManagerController';