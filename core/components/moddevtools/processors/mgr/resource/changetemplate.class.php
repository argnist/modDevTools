<?php

class modDevToolsResourceChangeTemplateProcessor extends modObjectUpdateProcessor {
    public $objectType = 'resource';
    public $classKey = 'modResource';
    public $permission = 'save_document';
    public $languageTopics = array('resource');

    public function beforeSave() {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        $template = $this->getProperty('template');
        if (empty($template)) {
            return $this->modx->lexicon('moddevtools_template_err_ns');
        }
        $template = $this->modx->getObject('modTemplate', $template);

        if (empty($template)) {
            return $this->modx->lexicon('moddevtools_template_err_nf');
        }

        return true;
    }
}

return 'modDevToolsResourceChangeTemplateProcessor';