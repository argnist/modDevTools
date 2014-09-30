## modDevTools

modDevTools is a component to accelerate some aspects of web development in MODX Revolution.

The basic idea is that when you edit templates not required to constantly look at the tree and open (in a separate window or by quick update) chunks and snippets.

Implemented features:
     outputs the code chunks and snippets used in the template or chunk, with the ability to edit. If there is, editor Ace is connected.
     finds chunks even in the parameters, for example in [[pdoResources?tpl=`rowTpl`]] it finds chunk rowTpl, if it already exists
     lists of parameters for snippets
     displays a list of resources that use this template
     you can proceed to edit, view, change the template, publish, remove resources from this list
     supported AjaxManager
     compatible with MODX 2.2 and 2.3

## Copyright Information

modDevTools is distributed as GPL (as MODx Revolution is), but the copyright owner
(Kireev Vitaly) grants all users of modDevTools the ability to modify, distribute
and use modDevTools in MODx development as they see fit, as long as attribution
is given somewhere in the distributed source of all derivative works.