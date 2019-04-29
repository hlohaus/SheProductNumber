//{block name="backend/article/view/variant/list"}
//{$smarty.block.parent}
Ext.define('Shopware.apps.Article.view.variant.SheList', {
    override:'Shopware.apps.Article.view.variant.List',
    createCellEditor: function() {
        var me = this;

        me.cellEditor = Ext.create('Ext.grid.plugin.CellEditing', {
            clicksToMoveEditor: 1,
            autoCancel: true
        });

        // Register listener on the edit event to save the record and convert the price value. Without
        // this listener the insert price "10,55" would be become "1055"
        me.cellEditor.on('edit', function(editor, e) {
            if (e.value && e.field === 'price') {
                var newPrice = Ext.Number.from(e.value);
                newPrice = Ext.Number.toFixed(newPrice, 2);

                var oldPrice = Ext.Number.from(e.originalValue);
                oldPrice = Ext.Number.toFixed(oldPrice, 2);

                if (newPrice != oldPrice) {
                    if (e.record.getPriceStore.getCount() > 1) {
                        Ext.Msg.confirm(me.snippets.graduatedPrices.title, me.snippets.graduatedPrices.confirm, function (answer) {
                            if (answer === 'yes') {
                                me.fireEvent('editVariantPrice', e.record, newPrice);
                            } else {
                                e.record.reject();
                            }
                        });
                    } else {
                        me.fireEvent('editVariantPrice', e.record, newPrice);
                    }
                }

            } else if (e.field === 'pseudoPrice') {
                var newPseudoPrice = Ext.Number.toFixed(0, 2);
                var oldPseudoPrice = null;

                if(e.value !== null) {
                    newPseudoPrice = Ext.Number.from(e.value);
                    newPseudoPrice = Ext.Number.toFixed(newPseudoPrice, 2);

                    oldPseudoPrice = Ext.Number.from(e.originalValue);
                    if(! Ext.isDefined(e.originalValue) || e.originalValue === null) {
                        oldPseudoPrice = 0;
                    }

                    oldPseudoPrice = Ext.Number.toFixed(oldPseudoPrice, 2);
                }

                if (newPseudoPrice !== oldPseudoPrice || newPseudoPrice === 0.00) {
                    if (e.record.getPriceStore.getCount() > 1) {
                        Ext.Msg.confirm(me.snippets.graduatedPrices.title, me.snippets.graduatedPrices.confirm, function (answer) {
                            if (answer === 'yes') {
                                me.fireEvent('editVariantPseudoPrice', e.record, newPseudoPrice);
                            } else {
                                e.record.reject();
                            }
                        });
                    } else {
                        me.fireEvent('editVariantPseudoPrice', e.record, newPseudoPrice);
                    }
                }

            } else {
                var oldValue = e.originalValue,
                    newValue = e.value;

                // The number field is a mapping field of the variant. so we have to map this field
                if (e.field === 'details.number') {
                    oldValue = e.record.get('number');
                    newValue = e.record.get('details.number') || e.record.get('number')
                }

                if (e.field === 'details.inStock') {
                    oldValue = e.record.get('inStock');
                }

                /*{$regex = {config name=regex namespace=SheProductNumber}}*/
                var regex = "{$regex|escape:javascript}";
                var match = regex.match(new RegExp('^/(.*?)/([gimy]*)$'));
                regex = new RegExp(match[1], match[2]);

                if(e.field === 'details.number' &&  (!newValue || !regex.test(newValue))) {
                    Shopware.Notification.createGrowlMessage(me.snippets.saved.errorTitle, me.snippets.saved.ordernumberNotMatch, me.snippets.growlMessage);
                    e.record.set('number', oldValue);
                    e.record.set('details.number', oldValue);
                    return;
                }

                if (oldValue === newValue) {
                    return;
                }

                if (e.field === 'details.number') {
                    e.record.set('number', newValue);
                }

                if (e.field === 'details.inStock') {
                    e.record.set('inStock', newValue);
                }

                me.fireEvent('saveVariant', e.record);
            }
        });

        /**
         * Event listener which filters the html tags from the number value.
         */
        me.cellEditor.on('beforeedit', function(editor, e) {
            if(e.field === 'details.number') {

                // We need to defer the function call to make sure that the editor is rendered
                // and the value is loaded.
                Ext.defer(function() {

                    editor.editors.each(function(ed) {
                        ed.validationRequestParam = e.record.get('id');
                        ed.setValue(e.record.get('number'));
                    });
                }, 50);
            }
        }, me);

        return me.cellEditor;
    },
});
//{/block}

