modDevTools.grid.Resources = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'moddevtools-grid-resources';
    }
    this.sm = new Ext.grid.CheckboxSelectionModel();
    this.config = config;
    Ext.applyIf(config, {
        url: modDevTools.config.connectorUrl,
        fields: this.getFields(config),
        columns: this.getColumns(config),
        tbar: false,
        autoExpandColumn: 'pagetitle',
        baseParams: {
            action: 'mgr/resource/getlist',
            sort: 'createdon',
            dir: 'DESC',
            id: MODx.request.id,
            link_type: config.link_type
        },
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: 0,
            getRowClass: function (rec) {
                return !rec.data.published
                    ? 'moddevtools-row-disabled'
                    : '';
            }
        },
        paging: true,
        remoteSort: true,
        autoHeight: true
    });
    this.config = config;
    modDevTools.grid.Resources.superclass.constructor.call(this, config);
};
Ext.extend(modDevTools.grid.Resources, MODx.grid.Grid, {
    windows: {},
    getMenu: function (grid, rowIndex) {
        var ids = this._getSelectedIds();
        var row = grid.getStore().getAt(rowIndex);
        var menu = modDevTools.util.getMenu(row.data['actions'], this, ids);
        this.addContextMenuItem(menu);
    },
    _getSelectedIds: function () {
        var ids = [];
        var selected = this.getSelectionModel().getSelections();
        for (var i in selected) {
            if (!selected.hasOwnProperty(i)) {
                continue;
            }
            ids.push(selected[i]['id']);
        }
        return ids;
    },
    _getResId: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        return ids[0];
    },
    updateResource: function (action) {
        var id = this._getResId();
        if (!id) {
            return false;
        }
        MODx.Ajax.request({
            url: MODx.config.connector_url,
            params: {
                action: 'resource/' + action,
                id: id
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        })
    },
    unpublishResource: function () {
        this.updateResource('unpublish');
    },
    publishResource: function () {
        this.updateResource('publish');
    },
    removeResource: function () {
        this.updateResource('delete');
    },
    undeleteResource: function () {
        this.updateResource('undelete');
    },
    editResource: function () {
        var id = this._getResId();
        if (!id) {
            return false;
        }
        MODx.loadPage('resource/update' + '&id=' + id);
    },
    previewResource: function () {
        window.open(this.menu.record.preview_url);
    },
    changeTemplate: function (btn, e) {
        var sels = this.getSelectionModel().getSelections();
        if (sels.length <= 0) return false;
        var r = sels[0].data;
        if (!this.changeTemplateWindow) {
            this.changeTemplateWindow = MODx.load({
                xtype: 'moddevtools-window-change-template',
                record: r,
                listeners: {
                    'success': {
                        fn: function () {
                            this.refresh();
                        }, scope: this
                    }
                }
            });
        }
        this.changeTemplateWindow.setValues(r);
        this.changeTemplateWindow.show(e.target);
        return true;
    },
    getFields: function () {
        return ['id', 'pagetitle', 'description', 'published', 'createdon', 'actions', 'preview_url', 'template'];
    },
    getColumns: function () {
        return [{
            header: _('id'),
            dataIndex: 'id',
            sortable: true,
            width: 20
        }, {
            header: _('pagetitle'),
            dataIndex: 'pagetitle',
            sortable: true,
            editable: true,
            width: 150
        }, {
            header: _('published'),
            dataIndex: 'published',
            renderer: modDevTools.util.renderBoolean,
            sortable: true,
            width: 40
        }, {
            header: _('createdon'),
            dataIndex: 'createdon',
            sortable: true,
            width: 50
        }, {
            header: _('moddevtools_grid_actions'),
            dataIndex: 'actions',
            renderer: modDevTools.util.renderActions,
            sortable: false,
            width: 60,
            id: 'actions'
        }, {
            header: _('moddevtools_template'),
            dataIndex: 'template',
            sortable: false,
            hidden: true
        }];
    },
    onClick: function (e) {
        var elem = e.getTarget('.action', 2, true);
        if (elem) {
            var row = this.getSelectionModel().getSelected();
            if (typeof (row) != 'undefined') {
                var action = elem.getAttribute('data-action');
                if (action === 'showMenu') {
                    var ri = this.getStore().find('id', row.id);
                    return this._showMenu(this, ri, e);
                } else if (typeof this[action] === 'function') {
                    this.menu.record = row.data;
                    return this[action](this, e);
                }
            }
        }
        return this.processEvent('click', e);
    }
});
Ext.reg('moddevtools-grid-resources', modDevTools.grid.Resources);
modDevTools.window.ChangeTemplate = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        title: _('moddevtools_template_change'),
        url: modDevTools.config.connectorUrl,
        baseParams: {
            action: 'mgr/resource/changetemplate'
        },
        width: 400,
        fields: [{
            xtype: 'hidden',
            name: 'id'
        }, {
            xtype: 'modx-combo-template',
            fieldLabel: _('template'),
            name: 'template',
            hiddenName: 'template',
            anchor: '100%'
        }]
    });
    modDevTools.window.ChangeTemplate.superclass.constructor.call(this, config);
};
Ext.extend(modDevTools.window.ChangeTemplate, MODx.Window);
Ext.reg('moddevtools-window-change-template', modDevTools.window.ChangeTemplate);