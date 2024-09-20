$(document).ready(function(){
    $('.remove-image').click(function(){
        id = $(this).data('id');
        obj = $('#image_'+id)

        if(location.href.indexOf('admin')>0){
            url = '/ru/admin/image/destroy/';
        }else{
            url = '/ru/profile/image/destroy/';
        }

        $.ajax({
            type: 'post',
            url: url + id,
            data: { '_token': _csrf_token },
            success: function($response) {
               // console.log($response)
                if ($response.status) {
                    $(obj).fadeOut();
                    $('.load-image').fadeIn();
                } else {
                    alert('Can`t remove image!')
                }
            },
            error: function(e) {
                alert('Server error or Internet connection failed!')
            }
        });
    });
});
