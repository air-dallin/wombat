$(document).ready(function () {
    // let url = window.location.href;
    // let a = document.querySelectorAll("[href='" + url + "']")[0];
    // let b = a != undefined ? a.parentElement.classList.add('active') : '';

    $('.regions').change(function () {
        let region_id = $(this).val();
        $.ajax({
            type: 'post',
            url: '/ru/city/get-cities',
            data: {'_token': _csrf_token, 'region_id': region_id},
            success: function ($response) {
                if ($response.status) {
                    $('#cities').html($response.data);
                } else {
                    alert('main.error');
                }
            },
            error: function (e) {
                alert(e)
            }
        });
    });
    $('.select2-regions').select2({
        minimumResultsForSearch: Infinity,
    })

   // $('.summernote').summernote();
    $('.phone-mask').inputmask({
        mask: "(99) 999-99-99",
    })

})
