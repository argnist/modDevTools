modDevTools.panel.Snippets = function(config) {
    config = config || {};
    Ext.apply(config,{
        id: 'tools-panel-snippets',
        params: {
            action: 'mgr/snippet/getlist',
            parent: MODx.request.id,
            link_type: config.link_type
        },
        config: {
            element: 'snippet',
            mimeType: 'application/x-php',
            modxTags : false
        }
    });
    this.config = config;
    modDevTools.panel.Snippets.superclass.constructor.call(this,config);
};

Ext.extend(modDevTools.panel.Snippets, modDevTools.panel.Elements, {
    getElementValue: function (r) {
        return '<?php\r\n' + (r.snippet || '');
    },

    loadProperties: function(r) {
        return {
            xtype: 'panel'
            ,listeners: {
                beforerender: {fn:function(form){
                    // console.log(form);
                    Ext.Ajax.request({
                        url: MODx.config.connector_url
                        ,params: {
                            action: 'element/getinsertproperties'
                            ,classKey: 'modSnippet'
                            ,pk: r.id
                            ,propertySet: 0
                        }
                        ,success: function(response) {
                            var obj = Ext.decode(response.responseText);
                            var html = '';
                            for (var i=0; i< obj.length; i++) {
                                html += '<b>&' + obj[i].fieldLabel + ': </b>"' + obj[i].value + '"<br/>(' + obj[i].description + ')<br>';
                            }
                            form.add({
                                title: _('properties'),
                                headerCfg: {
                                    style: {
                                        background: '#f0f0f0',
                                        margin: '10px 0 0',
                                        padding: '10px',
                                        cursor: 'pointer'
                                    }
                                },
                                items: [{
                                    html: html,
                                    style: {
                                        padding: '15px',
                                        border: '1px solid #ececec'
                                    }
                                }],
                                collapsible: true,
                                collapsed: true,
                                listeners: {
                                    afterrender: function(panel) {
                                        panel.header.on('click', function() {
                                            if (panel.collapsed) {panel.expand();}
                                            else {panel.collapse();}
                                        });
                                    }
                                }
                            });
                            form.doLayout();
                        }
                    });

                },scope:this}
            }
        };
    }
});

Ext.reg('moddevtools-panel-snippets',modDevTools.panel.Snippets);

