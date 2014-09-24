modDevTools.panel.Chunks = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,layout: 'auto'
        ,width: '100%'
        ,editors: this.editors
        ,items: [{
            html: '<p>'+_('moddevtools_chunks_intro')+'</p>'
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
    modDevTools.panel.Chunks.superclass.constructor.call(this,config);

    Ext.getCmp('modx-panel-template').on('success', function(data){
     //   console.log(data);
        this.getItems();
    }, this);
};

Ext.extend(modDevTools.panel.Chunks,MODx.Panel, {
    getItems: function() {
        var store = new Ext.data.JsonStore({
            url: modDevTools.config.connector_url
            ,baseParams: {
                action: 'mgr/chunk/getlist'
                ,parent: Ext.getCmp('modx-panel-template').template
                ,link_type: 'temp-chunk'
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
                            ,id: 'tools-chunk-' + i
                            ,title: r.name + ' (' + r.id + ')'
                            ,autoHeight: true
                            ,items: [{
                                xtype: Ext.ComponentMgr.types['modx-texteditor'] ? 'modx-texteditor' : 'textarea'
                                ,mimeType: 'text/html'
                                ,modxTags : true
                                ,value: r.snippet || ''
                                ,width: '100%'
                                ,height: 300
                                ,id: 'chunk-editor-' + r.id
                                ,record: r
                                ,enableKeyEvents: true
                                ,listeners: {
                                    keyup: {fn:function(a,b,c){
                                        var button = Ext.getCmp('save-chunk-' + this.record.id);
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
                                xtype: 'button',
                                id: 'save-chunk-' + r.id,
                                text: _('save'),
                                cls: 'primary-button',
                                input: 'chunk-editor-' + r.id,
                                disabled: true,
                                listeners: {
                                    click: {fn:function(a,b,c) {
                                        var input = Ext.getCmp(a.input);
                                        this.setText(_('saving'));
                                        MODx.Ajax.request({
                                            url: modDevTools.modx23 ? MODx.config.connector_url : (MODx.config.connectors_url + 'element/chunk.php')
                                            ,params: {
                                                action: modDevTools.modx23 ? 'element/chunk/update' : 'update',
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
                     //   console.log(i, item);
                        this.items.itemAt(1).add(item);
                    }
                  //  console.log(this.items.itemAt(1));
                    this.doLayout();
                },scope:this}
            }
        });
    }
});

Ext.reg('moddevtools-panel-chunks',modDevTools.panel.Chunks);

