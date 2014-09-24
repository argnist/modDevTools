modDevTools.panel.Snippets = function(config) {
    config = config || {};
    Ext.apply(config,{
        id: 'tools-panel-snippets',
        border: false
        ,baseCls: 'modx-formpanel'
        ,layout: 'auto'
        ,width: '100%'
        ,editors: this.editors
        ,items: [{
            html: '<p>'+_('moddevtools_snippets_intro')+'</p>'
            ,border: false
            ,cls: 'modx-page-header container'
        },{
            layout: 'accordion',
            border:false
            ,layoutConfig:{animate:true}
            ,defaults: {
                bodyStyle: 'padding:15px'
                ,renderHidden : true
                ,stateEvents: ["collapse","expand"]
                ,getState:function() {
                    return {collapsed:this.collapsed};
                }
                ,border:false
            }
            ,items:[]
        }]
    });

    this.getItems();
    modDevTools.panel.Snippets.superclass.constructor.call(this,config);

    Ext.getCmp('modx-panel-template').on('success', function(data){
        this.getItems();
    }, this);
};

Ext.extend(modDevTools.panel.Snippets,MODx.Panel, {
    getItems: function() {
        var store = new Ext.data.JsonStore({
            url: modDevTools.config.connector_url
            ,baseParams: {
                action: 'mgr/snippet/getlist'
                ,parent: Ext.getCmp('modx-panel-template').template
                ,link_type: 'temp-snip'
            }
            ,autoLoad: true
            ,fields: ['id', 'name', 'snippet']
            ,root: 'results'
            ,totalProperty: 'total'
            ,autoDestroy: true
            ,listeners: {
                'load': {fn:function(opt,records,c){
                    var items = [];
                    this.items.itemAt(1).removeAll();
                    for (var i = 0; i < records.length; i++) {
                        var r = records[i].data;

                        var item = {
                            stateId:'state' + r.id
                            ,id: 'tools-snippet-' + i
                            ,title: r.name + ' (' + r.id + ')'
                            ,autoHeight: true
                            ,items: [{
                                xtype: Ext.ComponentMgr.types['modx-texteditor'] ? 'modx-texteditor' : 'textarea'
                                ,mimeType: 'application/x-php'
                                ,modxTags : false
                                ,value: '<?php\r\n' + (r.snippet || '')
                                ,width: '100%'
                                ,height: 300
                                ,id: 'snippet-editor-' + r.id
                                ,record: r
                                ,enableKeyEvents: true
                                ,listeners: {
                                    keyup: {fn:function(a,b,c){
                                        var button = Ext.getCmp('save-snippet-' + this.record.id);
                                        if (this.value !== this.getValue()) {
                                            if (button.disabled) {
                                                button.setDisabled(false);
                                            }
                                        } else {
                                            if (!button.disabled) {
                                                button.setDisabled(true);
                                            }
                                        }

                                    }}
                                }
                            },{
                                xtype: 'panel'
                                ,listeners: {
                                    beforerender: {fn:function(form){
                                       // console.log(form);
                                        Ext.Ajax.request({
                                            url: modDevTools.modx23 ? MODx.config.connector_url : (MODx.config.connectors_url + 'element/index.php')
                                            ,params: {
                                                action: modDevTools.modx23 ? 'element/getinsertproperties' : 'getInsertProperties'
                                                ,classKey: 'modSnippet'
                                                ,pk: r.id
                                                ,propertySet: 0
                                            }
                                            ,success: function(response, opts) {
                                                var obj = Ext.decode(response.responseText);
                                                var html = '';
                                                for (var i=0; i< obj.length; i++) {
                                                    html += '<b>&' + obj[i].fieldLabel + ': </b>"' + obj[i].value + '"<br/>(' + obj[i].description + ')<br>';
                                                }
                                                form.add({
                                                    title: _('properties'),
                                                    headerCfg: {
                                                        //cls: 'x-panel-header',
                                                        style: {
                                                            border: '1px solid #ccc',
                                                            background: '#f0f0f0',
                                                            padding: '10px'
                                                        }
                                                    },
                                                    html: html,
                                                    collapsible: true,
                                                    collapsed: true
                                                });
                                                form.doLayout();
                                            }
                                        });

                                    },scope:this}
                                }

                            },{
                                xtype: 'button',
                                id: 'save-snippet-' + r.id,
                                text: _('save'),
                                cls: 'primary-button',
                                input: 'snippet-editor-' + r.id,
                                disabled: true,
                                listeners: {
                                    click: {fn:function(a,b,c) {
                                        var input = Ext.getCmp(a.input);
                                        this.setText(_('saving'));
                                        MODx.Ajax.request({
                                            url: modDevTools.modx23 ? MODx.config.connector_url : (MODx.config.connectors_url + 'element/snippet.php')
                                            ,params: {
                                                action: modDevTools.modx23 ? 'element/snippet/update' : 'update',
                                                id: input.record.id,
                                                name: input.record.name,
                                                snippet: input.getValue()
                                            }
                                            ,listeners: {
                                                'success': {fn:function(r) {
                                                    if (r.success) {
                                                        input.setValue(input.getValue());
                                                        this.setDisabled(true);
                                                        this.setText(_('save'));
                                                    }
                                                },scope:this}
                                            }
                                        });
                                    }}
                                }
                            }]
                            ,listeners: {
                                beforecollapse: function(a,b){
                                    return b !== true; // prevent collapse if not collapse directly on panel
                                }
                                ,scope: this
                            }
                            ,collapsed:false
                            ,collapsible: true
                        };
                        this.items.itemAt(1).add(item);
                    }
                    this.doLayout();
                },scope:this}
            }
        });
    }
});

Ext.reg('moddevtools-panel-snippets',modDevTools.panel.Snippets);

