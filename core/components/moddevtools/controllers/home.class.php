<?php
/**
 * Home controller class for modDevTools.
 *
 * @package moddevtools
 * @subpackage controller
 */

/**
 * Class modDevToolsHomeManagerController
 */
class modDevToolsHomeManagerController extends modExtraManagerController
{
    /** @var modDevTools $moddevtools */
    public $moddevtools;

    public function initialize()
    {
        $path = $this->modx->getOption('moddevtools.core_path', null, $this->modx->getOption('core_path') . 'components/moddevtools/');
        $this->moddevtools = $this->modx->getService('moddevtools', 'modDevTools', $path . 'model/moddevtools/', array(
            'core_path' => $path
        ));

        parent::initialize();
    }

    public function loadCustomCssJs()
    {
        $assetsUrl = $this->moddevtools->getOption('assetsUrl');
        $jsUrl = $this->moddevtools->getOption('jsUrl') . 'mgr/';
        $jsSourceUrl = $assetsUrl . '../../../source/js/mgr/';
        $cssUrl = $this->moddevtools->getOption('cssUrl') . 'mgr/';
        $cssSourceUrl = $assetsUrl . '../../../source/css/mgr/';

        if ($this->moddevtools->getOption('debug') && ($assetsUrl != MODX_ASSETS_URL . 'components/moddevtools/')) {
            $this->addCss($cssSourceUrl . 'moddevtools.css?v=v' . $this->moddevtools->version);
            $this->addJavascript($jsSourceUrl . 'moddevtools.js?v=v' . $this->moddevtools->version);
            $this->addJavascript($jsSourceUrl . 'helper/util.js?v=v' . $this->moddevtools->version);
            $this->addJavascript($jsSourceUrl . 'widgets/search.form.js?v=v' . $this->moddevtools->version);
            $this->addJavascript($jsSourceUrl . 'widgets/regenerate.form.js?v=v' . $this->moddevtools->version);
            $this->addJavascript($jsSourceUrl . 'widgets/home.panel.js?v=v' . $this->moddevtools->version);
            $this->addJavascript($jsSourceUrl . 'sections/home.js?v=v' . $this->moddevtools->version);
        } else {
            $this->addCss($cssUrl . 'moddevtools.min.css?v=v' . $this->moddevtools->version);
            $this->addLastJavascript($jsUrl . 'moddevtools.min.js?v=v' . $this->moddevtools->version);
        }
        $this->addHtml('<script type="text/javascript">
            Ext.onReady(function() {
                modDevTools.config = ' . json_encode($this->moddevtools->config, JSON_PRETTY_PRINT) . ';
                MODx.load({xtype: "moddevtools-page-home"});
            });
        </script>');
    }

    public function getLanguageTopics()
    {
        return array('moddevtools:default');
    }

    public function process(array $scriptProperties = array())
    {
    }

    public function getPageTitle()
    {
        return $this->modx->lexicon('moddevtools');
    }

    public function getTemplateFile()
    {
        return $this->moddevtools->getOption('templatesPath') . 'home.tpl';
    }

    public function checkPermissions()
    {
        return $this->modx->hasPermission('view_chunk') && $this->modx->hasPermission('view_template');
    }
}
