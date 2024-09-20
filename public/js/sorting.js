$(document).ready(function(){
    $(document).on('click','.sorting',function(){
        url = window.location.href
        field = $(this).data('sort');
        sort = getParameterByName('sort');
        if(sort==null) {
            if (url.indexOf('?')==-1){
                url += '?sort=' + field;
            }else {
                url += '&sort=' + field;
            }
        }else {
            sort_new = sort.split('-');
            if (sort.indexOf(field) == -1) {
                url = url.replace(sort, field);
            } else {
                if (sort_new.length > 1) {
                    url = url.replace(sort, sort_new[1]);
                } else {
                    url = url.replace(sort, '-' + sort);
                }
            }
        }
        location.href = url;
    });
    $('#listSearch').on('input change keyup',function(e){
        q = $(this).val();
        if(q.length>0) {
            field = $(this).data('field')!=null ? '&field='+ $(this).data('field') :'';
            $.ajax({
                type: 'GET',
                url: searchUrl+'?q=' + q + field,
                data: {'_token': _csrf_token, 'ajax_search': true},
                success: function ($response) {
                    if ($response.status) {
                        $('.custom-pagination').remove();
                        $('.card-body').html($response.data);
                    } else {
                        alert($response.error);
                    }
                },
                error: function (e) {
                    alert(e)
                }
            });
        }else{
            location.href=searchUrl;
        }
    });
    $('#listSearch').focus();
});

function getParameterByName(name, url = window.location.href) {
    name = name.replace(/[\[\]]/g, '\\$&');
    var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return '';
    return decodeURIComponent(results[2].replace(/\+/g, ' '));
}



