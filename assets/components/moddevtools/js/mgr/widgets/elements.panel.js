modDevTools.panel.Elements = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false,
        baseCls: 'modx-formpanel',
        layout: 'auto',
        width: '100%',
        editors: this.editors,
        items: [{
            html: this.getIntro(),
            border: false,
            cls: 'modx-page-header container'
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
    //modx-panel-(chunk/template/...)
    config.ownerCt.ownerCt.ownerCt.on('success', function(data){
        this.getItems();
    }, this);

    this.config = config;
    modDevTools.panel.Elements.superclass.constructor.call(this,config);
};

Ext.extend(modDevTools.panel.Elements, MODx.Panel, {
    getItems: function() {
        var params = this.config.config;
        var baseParams = this.config.params;
        var store = new Ext.data.JsonStore({
            url: modDevTools.config.connector_url,
            baseParams: baseParams,
            autoLoad: true,
            fields: ['id', 'name', 'snippet'],
            root: 'results',
            totalProperty: 'total',
            autoDestroy: true,
            listeners: {
                'load': {fn:function(opt,records,c){
                    var items = [];
                    this.items.itemAt(1).removeAll();
                    for (var i = 0; i < records.length; i++) {
                        var r = records[i].data;
                        var item = {
                            stateId: 'state' + r.id,
                            id: 'tools-' + params.element + '-' + i,
                            title: r.name + ' (' + r.id + ')',
                            headerCfg: {
                                cls: 'x-panel-header',
                                style: {
                                    background: '#ececec',
                                    padding: '10px',
                                    margin: '0 0 10px 0'
                                }
                            },
                            autoHeight: true,
                            items: [{
                                xtype: Ext.ComponentMgr.types['modx-texteditor'] ? 'modx-texteditor' : 'textarea',
                                mimeType: params.mimeType,
                                modxTags : params.modxTags,
                                value: this.getElementValue(r),
                                width: '100%',
                                height: 300,
                                id: params.element + '-editor-' + r.id,
                                record: r,
                                enableKeyEvents: true,
                                listeners: {
                                    keyup: {fn:function(a,b,c){
                                        var button = Ext.getCmp('save-' + params.element + '-' + this.record.id);
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
                            },this.loadProperties(r),{
                                xtype: 'button',
                                id: 'save-' + params.element + '-' + r.id,
                                text: _('save'),
                                cls: 'primary-button',
                                input: params.element + '-editor-' + r.id,
                                disabled: true,
                                listeners: {
                                    click: {fn:function(a,b,c) {
                                        var input = Ext.getCmp(a.input);
                                        this.setText(_('saving'));
                                        MODx.Ajax.request({
                                            url: modDevTools.modx23 ? MODx.config.connector_url : (MODx.config.connectors_url + 'element/' + params.element + '.php'),
                                            params: {
                                                action: modDevTools.modx23 ? 'element/' + params.element + '/update' : 'update',
                                                id: input.record.id,
                                                name: input.record.name,
                                                snippet: input.getValue()
                                            },
                                            listeners: {
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
                            }],
                            listeners: {
                                beforecollapse: function(a,b){
                                    return b !== true; // prevent collapse if not collapse directly on panel
                                }
                                ,scope: this
                            },
                            collapsed:false,
                            collapsible: true
                        };
                        this.items.itemAt(1).add(item);
                    }
                    this.doLayout();
                },scope:this}
            }
        });
    }
});
