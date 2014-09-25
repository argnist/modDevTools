modDevTools.panel.Chunks = function(config) {
    config = config || {};
    Ext.apply(config,{
        id: 'tools-panel-chunks',
        params: {
            action: 'mgr/chunk/getlist',
            parent: MODx.request.id,
            link_type: config.ownerCt.link_type
        },
        config: {
            element: 'chunk',
            mimeType: 'text/html',
            modxTags : true
        }
    });
    this.config = config;
    modDevTools.panel.Chunks.superclass.constructor.call(this,config);
};

Ext.extend(modDevTools.panel.Chunks,modDevTools.panel.Elements, {
    getIntro: function() {
        return '<p>' + _('moddevtools_chunks_intro') + '</p>';
    },

    getElementValue: function (r) {
        return r.snippet || '';
    },

    loadProperties: function(r) {
        return false;
    }
});

Ext.reg('moddevtools-panel-chunks',modDevTools.panel.Chunks);

