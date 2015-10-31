var ModDevTools = function(config) {
	config = config || {};
	ModDevTools.superclass.constructor.call(this,config);
};
Ext.extend(ModDevTools,Ext.Component,{
	page:{},window:{},grid:{},tree:{},panel:{},combo:{},config: {},view: {},utils: {}
});
Ext.reg('moddevtools',ModDevTools);

modDevTools = new ModDevTools();
