modDevTools.BreadcrumbsPanel = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        bdMarkup: '<tpl if="typeof(trail) != &quot;undefined&quot;">'
            +'<div class="crumb_wrapper"><ul class="crumbs">'
            +'<tpl for="trail">'
            +'<li{[values.className != undefined ? \' class="\'+values.className+\'"\' : \'\' ]}>'
            +'<tpl if="typeof url != \'undefined\'">'
            +'<button type="button" data-url="{url}" class="controlBtn {[values.root ? \' root\' : \'\' ]}">{text}</button>'
            +'</tpl>'
            +'<tpl if="typeof url == \'undefined\'"><span class="text{[values.root ? \' root\' : \'\' ]}">{text}</span></tpl>'
            +'</li></tpl></ul></div></tpl>',
        bodyStyle: {background: 'transparent'}
    });
    modDevTools.BreadcrumbsPanel.superclass.constructor.call(this,config);
}

Ext.extend(modDevTools.BreadcrumbsPanel,MODx.BreadcrumbsPanel,{
    onClick: function(e) {
        var target = e.getTarget();
        if (typeof target != "undefined") {
            var url = target.getAttribute('data-url');
            if (url) {
                MODx.loadPage(url);
            }
        }
    }
    ,_updatePanel: function(data){
        this.tpl.overwrite(this.body, data);
        var $this = this;
        setTimeout(function(){
            $this.ownerCt.doLayout();
        }, 200);
    }
    ,getPagetitle: function(){
        var pagetitleCmp = Ext.getCmp('modx-resource-pagetitle');
        var pagetitle;
        if (typeof pagetitleCmp != "undefined") {
            pagetitle = pagetitleCmp.getValue();
            if (pagetitle.length == 0) {
                pagetitle = _('new_document');
            }
        } else {
            pagetitle = '';
        }
        return pagetitle;
    }

});
Ext.reg('moddevtools-breadcrumbs-panel',modDevTools.BreadcrumbsPanel);