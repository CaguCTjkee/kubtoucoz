jQuery(function() {
    jQuery(document.body).on('click', '#doaction', function() {
        jQuery('#form input[name="why"]').val('addpost');
        jQuery('#form').submit();
        return false;
    });
    $('a[href="#category"]').hover(function() {
            $('select[name="category"]').css('background', '#F3D9D9');
    }, function() {
            $('select[name="category"]').css('background', '#fff');
    });
    $('a[href="#items_per_page"]').hover(function() {
            $('select[name="count"]').css('background', '#F3D9D9');
    }, function() {
            $('select[name="count"]').css('background', '#fff');
    });


    $('#cb-select-all-1, #cb-select-all-2').click(function () {
        allcb = $('input[name="post[]"]');

        if (this.checked == true)
        {
            $('#cb-select-all-1, #cb-select-all-2').attr('checked', true);
        }
        else
        {
            $('#cb-select-all-1, #cb-select-all-2').attr('checked', false);
        }

        for (var i = 0; i < allcb.length; i++) {
            if( !$(allcb[i]).parent().parent().parent().hasClass('hide') )
                allcb[i].checked = this.checked;
        }
    });
});
