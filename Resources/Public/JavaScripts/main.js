(function ($) {
    $('.tx-pictures [data-fancybox]').fancybox({
        caption: function (instance, item) {
            return $(this).siblings('figcaption').html();
        }
    });
})(jQuery);
