modDeveloperTools.page.Home = function(config) {
	config = config || {};
	Ext.applyIf(config,{
		components: [{
			xtype: 'moddevtools-panel-home'
			,renderTo: 'moddevtools-panel-home-div'
		}]
	}); 
	modDeveloperTools.page.Home.superclass.constructor.call(this,config);
};
Ext.extend(modDeveloperTools.page.Home,MODx.Component);
Ext.reg('moddevtools-page-home',modDeveloperTools.page.Home);