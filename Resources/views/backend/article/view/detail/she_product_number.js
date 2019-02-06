//{block name="backend/article/view/detail/base"}
// {$smarty.block.parent}
Ext.define('Shopware.apps.Article.view.detail.SheProductNumber', {
    override: 'Shopware.apps.Article.view.detail.Base',

    createLeftElements: function () {
        var me = this,
            result = me.callParent(arguments);

        /*{$regex = {config name=regex namespace=SheProductNumber}}*/
        var regex = "{$regex|escape:javascript}";
        var match = regex.match(new RegExp('^/(.*?)/([gimy]*)$'));
        regex = new RegExp(match[1], match[2]);
        me.numberField.regex = regex;

        return result;
    }
});
//{/block}
