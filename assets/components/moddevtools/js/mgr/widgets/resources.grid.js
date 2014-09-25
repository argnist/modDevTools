modDevTools.grid.Resources = function (config) {
	config = config || {};
	if (!config.id) {
		config.id = 'moddevtools-grid-resources';
	}
    this.sm = new Ext.grid.CheckboxSelectionModel();
	Ext.applyIf(config, {
		url: modDevTools.config.connector_url,
        sm: this.sm,
		fields: this.getFields(config),
		columns: this.getColumns(config),
		tbar: this.getTopBar(config),
		baseParams: {
			action: 'mgr/resource/getlist',
            template: MODx.request.id
		},
		listeners: {
			rowDblClick: function (grid, rowIndex, e) {
				var row = grid.store.getAt(rowIndex);
				this.updateResource(grid, e, row);
			}
		},
		viewConfig: {
			forceFit: true,
			enableRowBody: true,
			autoFill: true,
			showPreview: true,
			scrollOffset: 0,
			getRowClass: function (rec, ri, p) {
				return !rec.data.active
					? 'moddevtools-row-disabled'
					: '';
			}
		},
		paging: true,
		remoteSort: true,
		autoHeight: true
	});
	modDevTools.grid.Resources.superclass.constructor.call(this, config);

	// Clear selection on grid refresh
    this.on('render', function(){
        this.store.on('load', function () {
            this.config.sm.clearSelections();
        }, this);
    }, this);

};
Ext.extend(modDevTools.grid.Resources, MODx.grid.Grid, {
	windows: {},

	getMenu: function (grid, rowIndex) {
		var ids = this._getSelectedIds();

		var row = grid.getStore().getAt(rowIndex);
		var menu = modDevTools.utils.getMenu(row.data['actions'], this, ids);

		this.addContextMenuItem(menu);
	},

	createResource: function (btn, e) {
		var w = MODx.load({
			xtype: 'moddevtools-resource-window-create',
			id: Ext.id(),
			listeners: {
				success: {
					fn: function () {
						this.refresh();
					}, scope: this
				}
			}
		});
		w.reset();
		w.setValues({active: true});
		w.show(e.target);
	},

	updateResource: function (btn, e, row) {
		if (typeof(row) != 'undefined') {
			this.menu.record = row.data;
		}
		else if (!this.menu.record) {
			return false;
		}
		var id = this.menu.record.id;

		MODx.Ajax.request({
			url: this.config.url,
			params: {
				action: 'mgr/resource/get',
				id: id
			},
			listeners: {
				success: {
					fn: function (r) {
						var w = MODx.load({
							xtype: 'moddevtools-resource-window-update',
							id: Ext.id(),
							record: r,
							listeners: {
								success: {
									fn: function () {
										this.refresh();
									}, scope: this
								}
							}
						});
						w.reset();
						w.setValues(r.object);
						w.show(e.target);
					}, scope: this
				}
			}
		});
	},

	removeResource: function (act, btn, e) {
		var ids = this._getSelectedIds();
		if (!ids.length) {
			return false;
		}
		MODx.msg.confirm({
			title: ids.length > 1
				? _('moddevtools_resources_remove')
				: _('moddevtools_resource_remove'),
			text: ids.length > 1
				? _('moddevtools_resources_remove_confirm')
				: _('moddevtools_resource_remove_confirm'),
			url: this.config.url,
			params: {
				action: 'mgr/resource/remove',
				ids: Ext.util.JSON.encode(ids)
			},
			listeners: {
				success: {
					fn: function (r) {
						this.refresh();
					}, scope: this
				}
			}
		});
		return true;
	},

	disableResource: function (act, btn, e) {
		var ids = this._getSelectedIds();
		if (!ids.length) {
			return false;
		}
		MODx.Ajax.request({
			url: this.config.url,
			params: {
				action: 'mgr/resource/disable',
				ids: Ext.util.JSON.encode(ids)
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

	enableResource: function (act, btn, e) {
		var ids = this._getSelectedIds();
		if (!ids.length) {
			return false;
		}
		MODx.Ajax.request({
			url: this.config.url,
			params: {
				action: 'mgr/resource/enable',
				ids: Ext.util.JSON.encode(ids)
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

	getFields: function (config) {
		return ['id', 'pagetitle', 'description', 'published', 'actions'];
	},

	getColumns: function (config) {
		return [this.sm,{
			header: _('id'),
			dataIndex: 'id',
			sortable: true,
			width: 70
		}, {
			header: _('pagetitle'),
			dataIndex: 'pagetitle',
			sortable: true,
			width: 200
		}, {
			header: _('published'),
			dataIndex: 'published',
			renderer: modDevTools.utils.renderBoolean,
			sortable: true,
			width: 100
		}, {
			header: _('moddevtools_grid_actions'),
			dataIndex: 'actions',
			renderer: modDevTools.utils.renderActions,
			sortable: false,
			width: 100,
			id: 'actions'
		}];
	},

	getTopBar: function (config) {
		return;/* [{
			text: '<i class="icon icon-plus">&nbsp;' + _('moddevtools_resource_create'),
			handler: this.createResource,
			scope: this
		}, '->', {
			xtype: 'textfield',
			name: 'query',
			width: 200,
			id: config.id + '-search-field',
			emptyText: _('moddevtools_grid_search'),
			listeners: {
				render: {
					fn: function (tf) {
						tf.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
							this._doSearch(tf);
						}, this);
					}, scope: this
				}
			}
		}, {
			xtype: 'button',
			id: config.id + '-search-clear',
			text: '<i class="icon icon-times"></i>',
			listeners: {
				click: {fn: this._clearSearch, scope: this}
			}
		}];*/
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

	_doSearch: function (tf, nv, ov) {
		this.getStore().baseParams.query = tf.getValue();
		this.getBottomToolbar().changePage(1);
		this.refresh();
	},

	_clearSearch: function (btn, e) {
		this.getStore().baseParams.query = '';
		Ext.getCmp(this.config.id + '-search-field').setValue('');
		this.getBottomToolbar().changePage(1);
		this.refresh();
	}
});
Ext.reg('moddevtools-grid-resources', modDevTools.grid.Resources);