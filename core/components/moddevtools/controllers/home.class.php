<?php
/**
 * The home manager controller for modDevTools.
 *
 */
class modDevToolsHomeManagerController extends modDevToolsMainController {
    /* @var modDevTools $modDevTools */
    public $modDevTools;
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
        $this->addCss($this->modDevTools->config['cssUrl'] . 'mgr/main.css');
        $this->addCss($this->modDevTools->config['cssUrl'] . 'mgr/bootstrap.buttons.css');
        $this->addJavascript($this->modDevTools->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->modDevTools->config['jsUrl'] . 'mgr/widgets/search.form.js');
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

    public function checkPermissions() {
        return $this->modx->hasPermission('view_chunk') && $this->modx->hasPermission('view_template');
    }
}