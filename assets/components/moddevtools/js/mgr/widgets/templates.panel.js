modDevTools.panel.Templates = function(config) {
    config = config || {};
    Ext.apply(config,{
        id: 'tools-panel-templates',
        params: {
            action: 'mgr/template/getlist',
            child: MODx.request.id,
            link_type: config.link_type
        },
        config: {
            element: 'template',
            mimeType: 'text/html',
            modxTags : true
        }
    });
    this.config = config;
    modDevTools.panel.Templates.superclass.constructor.call(this,config);
};

Ext.extend(modDevTools.panel.Templates,modDevTools.panel.Elements, {
    getUpdateParams: function(input) {
        return {
            action: 'element/' + this.config.config.element + '/update',
            id: input.record.id,
            templatename: input.record.name,
            content: input.getValue()
        };
    },

    getElementValue: function (r) {
        return r.snippet || '';
    },

    loadProperties: function() {
        return false;
    }
});

Ext.reg('moddevtools-panel-templates',modDevTools.panel.Templates);

