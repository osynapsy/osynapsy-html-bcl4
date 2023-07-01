BclPager = {
    init : function()
    {
        $('body').on('click','.BclPagination a', function(e) {
            e.preventDefault();
            let parent = $(this).closest('.BclPagination');
            let hidden = $('input[type=hidden]:first-child', parent);
            hidden.val($(this).data('value'));
            if ($(parent).data('parent')) {
                Osynapsy.refreshComponents([$(parent).data('parent')]);
            } else {
                $(this).closest('form').submit();
            }
        });
    }
}

if (window.Osynapsy) {
    Osynapsy.plugin.register('BclPager',function(){
        BclPager.init();
    });
}


