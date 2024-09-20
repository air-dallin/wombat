<style>
    #notify-container {
        display: none;
        position: fixed;
        z-index: 10000;
        top: 50px;
        right: 50px;
        background: #fff;
        border-radius: 5px;
        padding: 20px 30px;
        /* box-shadow: 5px 5px 7px #ccc; */
        max-width: 350px;
    }

    #notify-container.success{
        background: #4fbd4a;
        color:#fff;
    }
    #notify-container.warning{
        background: #ffb61b;
        color:#000;
    }
    #notify-container.danger{
        background: #fc077a;
        color:#fff;
    }
    #notify-container.primary{
        background: #0c7cd5;
        color:#fff;
    }
</style>

<div id="notify-container">
    <div id="notify-body">
        <div id="notify-message"></div>
    </div>
</div>
<script>
    var timerId = 0;
    function notify(message, type, timeout) {
        clearTimeout(timerId);
        if (timeout == undefined) timeout = 7000;
        if (type.length == 0) type = 'success';
        $('#notify-container #notify-message').html(message);
        $('#notify-container').attr('class', type);
        $('#notify-container').fadeIn('slow')
        timerId = setTimeout(function () {
            $('#notify-container').fadeOut('slow');
        }, timeout)
    }
</script>
