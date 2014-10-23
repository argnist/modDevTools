modDevTools.panel.Elements = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false,
        baseCls: 'modx-formpanel',
        layout: 'auto',
        width: '100%',
        editors: this.editors,
        parentPanel: config.ownerCt.ownerCt.ownerCt, //modx-panel-(chunk/template/...)
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

    var tabs = config.ownerCt.ownerCt;
    if (!tabs.isDevToolsEventSet) {
        tabs.addListener('tabchange', function(){
            var btn = Ext.getCmp('modx-abtn-save');
            btn.setDisabled(false);
        }, this);
        tabs.isDevToolsEventSet = true;
    }

    config.parentPanel.on('success', function(){
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
                            keys: [{
                                key: "s",
                                ctrl:true,
                                scope: this,
                                fn: function(){
                                    this.focusedEditor.ownerCt.find('xtype', 'button')[0].fireEvent('click');
                                }
                            }],
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
                                    keyup: {fn:function(){
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
                                    }},
                                    'focus': {fn:function(editor){
                                        this.focusedEditor = editor;
                                        var btn = Ext.getCmp('modx-abtn-save');
                                        btn.setDisabled(true);
                                    }, scope: this},
                                    afterrender: {fn:function(field) {
                                        if (field.xtype == 'modx-texteditor') {
                                            var editor = field.editor;
                                            var name = this.parentPanel.record.name;
                                            editor.findAll(name);

                                            var ranges = this.highlightElements(editor, name);

                                            if (ranges.length > 0) {
                                                editor.gotoLine(ranges[0].end.row+1,ranges[0].end.column);
                                            }

                                            var _self = this;
                                            editor.getSession().on('change', function(){
                                                _self.highlightElements(editor, name);
                                            });
                                        }
                                    }, scope: this}
                                }
                            },this.loadProperties(r),{
                                xtype: 'button',
                                id: 'save-' + params.element + '-' + r.id,
                                text: _('save'),
                                cls: 'primary-button',
                                input: params.element + '-editor-' + r.id,
                                disabled: true,
                                keys: [{
                                    key: MODx.config.keymap_save || 's'
                                    ,ctrl: true
                                }],
                                listeners: {
                                    click: {fn:function() {
                                        if (this.disabled) return false;
                                        var input = Ext.getCmp(this.input);
                                        this.setText(_('saving'));
                                        MODx.Ajax.request({
                                            url: modDevTools.modx23 ? MODx.config.connector_url : (MODx.config.connectors_url + 'element/' + params.element + '.php'),
                                            params: this.ownerCt.ownerCt.ownerCt.getUpdateParams(input),
                                            listeners: {
                                                'success': {fn:function(r) {
                                                    if (r.success) {
                                                        input.value = input.getValue();
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
                                'beforecollapse':{fn:function(a,b){
                                    return b !== true; // prevent collapse if not collapse directly on panel
                                },scope: this}
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
    },

    getUpdateParams: function(input) {
        return {
            action: modDevTools.modx23 ? 'element/' + this.config.config.element + '/update' : 'update',
            id: input.record.id,
            name: input.record.name,
            snippet: input.getValue()
        };
    },

    highlightElements: function(editor, name) {
        var markers = editor.getSession().getMarkers(false);
        for (var id in markers) {
            if (markers[id].clazz.indexOf('ace_selected-word') === 0) {
                editor.getSession().removeMarker(id);
            }
        }

        editor.$search.set({needle:name});
        var ranges = editor.$search.findAll(editor.session);

        for (var i=0; i<ranges.length; i++) {
             editor.getSession().addMarker(ranges[i],"ace_selected-word", "text");
        }

        return ranges;
    }
});
