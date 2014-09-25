modDevTools.page.Home = function(config) {
	config = config || {};
	Ext.applyIf(config, {
		components: [{
			xtype: 'moddevtools-panel-home', renderTo: 'moddevtools-panel-home-div'
		}]
	}); 
	modDevTools.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(modDevTools.page.Home, MODx.Component);
Ext.reg('moddevtools-page-home', modDevTools.page.Home);