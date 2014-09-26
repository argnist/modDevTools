<?php
/**
 * The base class for modDevTools.
 */

class modDevTools {
	/* @var modX $modx */
	public $modx;


	/**
	 * @param modX $modx
	 * @param array $config
	 */
	function __construct(modX &$modx, array $config = array()) {
		$this->modx =& $modx;

		$corePath = $this->modx->getOption('moddevtools_core_path', $config, $this->modx->getOption('core_path') . 'components/moddevtools/');
		$assetsUrl = $this->modx->getOption('moddevtools_assets_url', $config, $this->modx->getOption('assets_url') . 'components/moddevtools/');
		$connectorUrl = $assetsUrl . 'connector.php';

		$this->config = array_merge(array(
			'assetsUrl' => $assetsUrl,
			'cssUrl' => $assetsUrl . 'css/',
			'jsUrl' => $assetsUrl . 'js/',
			'imagesUrl' => $assetsUrl . 'images/',
			'connectorUrl' => $connectorUrl,
			'corePath' => $corePath,
			'modelPath' => $corePath . 'model/',
			'chunksPath' => $corePath . 'elements/chunks/',
			'templatesPath' => $corePath . 'elements/templates/',
			'chunkSuffix' => '.chunk.tpl',
			'snippetsPath' => $corePath . 'elements/snippets/',
			'processorsPath' => $corePath . 'processors/'
		), $config);

		$this->modx->addPackage('moddevtools', $this->config['modelPath']);
		$this->modx->lexicon->load('moddevtools:default');
	}

    /**
     * Outputs the JavaScript needed to add a tab to the panels.
     *
     * @param string $class
     * @return bool
     */
    public function outputTab($class) {
        $this->modx->controller->addLexiconTopic('moddevtools:default');
        $this->modx->controller->addCss($this->config['cssUrl'] . 'mgr/main.css');
        $this->modx->controller->addJavascript($this->config['jsUrl'] . 'mgr/moddevtools.js');
        $this->modx->controller->addJavascript($this->config['jsUrl'] . 'mgr/misc/utils.js');

        $modx23 = !empty($this->modx->version) && version_compare($this->modx->version['full_version'], '2.3.0', '>=');

        $this->modx->controller->addHtml('
            <script type="text/javascript">
            // <![CDATA[
            modDevTools.config = {
                assets_url: "' . $this->config['assetsUrl'] . '"
                ,connector_url: "' . $this->config['connectorUrl'] . '"
                };
            modDevTools.modx23 = ' . (int)$modx23 . ';
            // ]]>
            </script>');

        if (!$modx23) {
            $this->modx->controller->addCss($this->config['cssUrl'] . 'mgr/bootstrap.buttons.css');
        }

        $this->modx->controller->addJavascript($this->config['jsUrl'] . 'mgr/widgets/elements.panel.js');
        $this->modx->controller->addJavascript($this->config['jsUrl'] . 'mgr/widgets/chunks.panel.js');
        $this->modx->controller->addJavascript($this->config['jsUrl'] . 'mgr/widgets/snippets.panel.js');
        if ($class == 'Template') {
            $this->modx->controller->addJavascript($this->config['jsUrl'] . 'mgr/widgets/resources.grid.js');
            $this->modx->controller->addJavascript($this->config['jsUrl'] . 'mgr/widgets/templates.js');
        } else if ($class == 'Chunk') {
            $this->modx->controller->addJavascript($this->config['jsUrl'] . 'mgr/widgets/chunks.js');
        }


        return true;
    }

    public function clearLinks($link_type = false, $parent = false) {
        $c = $this->modx->newQuery('modDevToolsLink');
        if ($link_type) {
            $c->where(array(
                'link_type:LIKE' => $link_type . '-%'
            ));
        }

        if ($parent) {
            $c->where(array(
                'parent' => $parent
            ));
        }
        $links = $this->modx->getIterator('modDevToolsLink', $c);
        /**
         * @var modDevToolsLink $link
         */
        foreach ($links as $link) {
            $link->remove();
        }
    }

    /**
     * @param xPDOObject $object
     */
    public function parseContent(&$object) {

        if ($object instanceof modTemplate) {
            $objLink = 'temp';
        } else if ($object instanceof modChunk) {
            $objLink = 'chunk';
        } else {
            $objLink = false;
        }

        if ($objLink === false) {
            return;
        }

        $this->clearLinks($objLink, $object->get('id'));
        $parser = $this->modx->getParser();
        $tags = array();
        $parser->collectElementTags($object->get('content'), $tags);
        foreach ($tags as $tag) {
            $tagName = $tag[1];
            if (substr($tagName,0,1) == '!') {
                $tagName = substr($tagName, 1);
            }

            $token = substr($tagName, 0, 1);

            $tagParts= xPDO :: escSplit('?', $tagName, '`', 2);
            $tagName= trim($tagParts[0]);
            $tagPropString= null;
            if (isset ($tagParts[1])) {
                $tagPropString= trim($tagParts[1]);
            }

            switch ($token) {
                case '$':
                    $class = 'modChunk';
                    $type = 'chunk';
                    $tagName = substr($tagName,1);
                    $this->debug('Found chunk ' . $tagName . ' with propString ' . $tagPropString);
                    break;
                case '+':
                case '~':
                case '#':
                case '%':
                case '-':
                case '*':
                    $class = $type = false;
                    break;
                default:
                    $class = 'modSnippet';
                    $type = 'snip';
                    $this->debug('Found snippet ' . $tagName . ' with propString ' . $tagPropString);
                    break;
            }
            $tagName = trim($this->modx->stripTags($tagName));

            if ($class && !empty($tagName)) {
                $obj = $this->modx->getObject($class, array('name' => $tagName));
                if ($obj) {
                    $this->debug('Object exists of class ' . $class);
                    $c = array(
                        'parent' => $object->get('id'),
                        'child' => $obj->get('id'),
                        'link_type' => $objLink . '-' . $type
                    );
                    $link = $this->modx->getObject('modDevToolsLink', $c);

                    if (!$link) {
                        $this->debug('Try to create link with criteria ' . print_r($c,1));
                        $link = $this->modx->newObject('modDevToolsLink', $c);
                        $link->save();
                    } else {
                        $this->debug('Link is already exists with criteria ' . print_r($c,1));
                    }
                } else {
                    $this->debug('Object doesnt exist of class ' . $class);
                }
            }
        }
    }

    public function debug($message) {
        if ($this->config['debug']) {
            if ($message instanceof xPDOObject) {
                $message = $message->toArray();
            }

            if (is_array($message)) {
                $message = print_r($message,1);
            }

            $this->modx->log(MODx::LOG_LEVEL_ERROR, $message);
        }
    }

}