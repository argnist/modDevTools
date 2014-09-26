modDevTools.grid.Resources = function (config) {
	config = config || {};
	if (!config.id) {
		config.id = 'moddevtools-grid-resources';
	}
    this.sm = new Ext.grid.CheckboxSelectionModel();
	Ext.applyIf(config, {
		url: modDevTools.config.connector_url,
		fields: this.getFields(config),
		columns: this.getColumns(config),
		tbar: false,
		baseParams: {
			action: 'mgr/resource/getlist',
            template: MODx.request.id,
            sort: 'createdon',
            dir: 'DESC'
		},
		viewConfig: {
			forceFit: true,
			enableRowBody: true,
			autoFill: true,
			showPreview: true,
			scrollOffset: 0,
			getRowClass: function (rec, ri, p) {
				return !rec.data.published
					? 'moddevtools-row-disabled'
					: '';
			}
		},
		paging: true,
		remoteSort: true,
		autoHeight: true
	});
	modDevTools.grid.Resources.superclass.constructor.call(this, config);
};
Ext.extend(modDevTools.grid.Resources, MODx.grid.Grid, {
	windows: {},

	getMenu: function (grid, rowIndex) {
		var ids = this._getSelectedIds();

		var row = grid.getStore().getAt(rowIndex);
		var menu = modDevTools.utils.getMenu(row.data['actions'], this, ids);

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

    _getResId: function() {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        return ids[0];
    },

    updateResource: function(action) {
        var id = this._getResId();
        if (!id) {
            return false;
        }
        MODx.Ajax.request({
            url: modDevTools.modx23 ? MODx.config.connector_url : MODx.config.connectors_url + 'resource/index.php',
            params: {
                action: modDevTools.modx23 ? 'resource/' + action : action,
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

	unpublishResource: function (act, btn, e) {
        this.updateResource('unpublish');
	},

    publishResource: function (act, btn, e) {
        this.updateResource('publish');
    },

    removeResource: function (act, btn, e) {
        this.updateResource('delete');
    },

    undeleteResource: function (act, btn, e) {
        this.updateResource('undelete');
    },


    editResource: function(act, btn, e) {
        var id = this._getResId();
        if (!id) {
            return false;
        }
        MODx.loadPage((modDevTools.modx23 ? 'resource/update' : '?a=' + MODx.action['resource/update']) + '&id=' + id);
    },

    changeTemplate: function(btn,e) {
        var cs = this.getSelectedAsList();
        if (cs === false) return false;
        var r = {id: cs};
        if (!this.changeTemplateWindow) {
            this.changeTemplateWindow = MODx.load({
                xtype: 'moddevtools-window-change-template'
                ,record: r
                ,listeners: {
                    'success': {fn:function(r) {
                        this.refresh();
                    },scope:this}
                }
            });
        }
        this.changeTemplateWindow.setValues(r);
        this.changeTemplateWindow.show(e.target);
        return true;
    },

	getFields: function (config) {
		return ['id', 'pagetitle', 'description', 'published', 'createdon','actions'];
	},

	getColumns: function (config) {
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
			renderer: modDevTools.utils.renderBoolean,
			sortable: true,
			width: 30
		}, {
            header: _('createdon'),
            dataIndex: 'createdon',
            sortable: true,
            width: 50
        }, {
			header: _('moddevtools_grid_actions'),
			dataIndex: 'actions',
			renderer: modDevTools.utils.renderActions,
			sortable: false,
			width: 150,
			id: 'actions'
		}];
	},

	onClick: function (e) {
		var elem = e.getTarget();
		if (elem.nodeName == 'BUTTON') {
			var row = this.getSelectionModel().getSelected();
			if (typeof(row) != 'undefined') {
				var action = elem.getAttribute('action');
				if (action == 'showMenu') {
					var ri = this.getStore().find('id', row.id);
					return this._showMenu(this, ri, e);
				}
				else if (typeof this[action] === 'function') {
					this.menu.record = row.data;
					return this[action](this, e);
				}
			}
		}
		return this.processEvent('click', e);
	}

});
Ext.reg('moddevtools-grid-resources', modDevTools.grid.Resources);

modDevTools.window.ChangeTemplate = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('moddevtools_template_change'),
        url: modDevTools.config.connector_url,
        baseParams: {
            action: 'mgr/resource/changetemplate'
        },
        width: 400,
        fields: [{
            xtype: 'hidden',
            name: 'id'
        },{
            xtype: 'modx-combo-template',
            fieldLabel: _('template'),
            name: 'template',
            hiddenName: 'template',
            anchor: '100%'
        }]
    });
    modDevTools.window.ChangeTemplate.superclass.constructor.call(this,config);
};
Ext.extend(modDevTools.window.ChangeTemplate, MODx.Window);
Ext.reg('moddevtools-window-change-template', modDevTools.window.ChangeTemplate);