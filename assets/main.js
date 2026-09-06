jQuery(document).ready(function ($) {
    $.noConflict;


    let search_input = $('.wcpbsc-search-input');
    let result_box = $('.wcpbsc-search-result');
    let loader = $('.wcpbsc-search-loader');
    let magnifier = $('.wcpbsc-search-magnifier');
    let cross_icon = $('.wcpbsc-search-cross');

    search_input.keyup(function (e) {
        e.preventDefault();
        let query = search_input.val();
        cross_icon.show();

        $.ajax({
            url: '',
            type: 'post',
            data: {
                action: WCPBSC.ajaxUrl,
                query: query,
                nonce: WCPBSC.nonce
            },
            beforeSend: function () {
                magnifier.hide();
                loader.show();
            },
            success: function (response) {
                if (response.success) {
                    let results = response.data;
                    if(results.empty){
                        result_box.text('نتیجه ای یافت نشد!!');
                    }else {
                        result_box.append(results);
                    }
                }
                loader.hide();
                magnifier.show();

            }, error: function (response) {
                loader.hide();
                magnifier.show();
            }
        });

        cross_icon.on('click',function (e){
            search_input.val('');
        })

    })


});