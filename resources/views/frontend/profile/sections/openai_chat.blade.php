<div
    class="modal fixed inset-0 z-50 flex hidden items-center justify-center overflow-y-auto"
    id="chat_modal"
>
    <div
        class="modal-overlay fixed inset-0 bg-gray-500 opacity-75 dark:bg-bgray-900 dark:opacity-50"
    ></div>
    <div class="modal-content mx-auto max-w-[948px] px-4" style=" min-width: 900px; ">
        <div class="step-content step-1 relative">
            <div
                class="relative max-w-[948px] rounded-lg bg-white p-7 transition-all dark:bg-darkblack-600"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-poppins text-3xl font-medium text-bgray-900 dark:text-white">WombatAI</h3>
                    </div>
                    <div>
                        <button
                            onclick="closeModal('chat_modal')"
                            type="button"
                            id="step-1-cancel"
                            class="inline-flex h-8 w-8 items-center justify-center rounded-md bg-bgray-200 text-bgray-700 hover:bg-red-500 hover:text-white focus:outline-none dark:bg-darkblack-500"
                        >
                            <span class="sr-only">Close</span>
                            <svg
                                class="fill-bgray-900 dark:fill-darkblack-300"
                                width="12"
                                height="12"
                                viewBox="0 0 12 12"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    fill-rule="evenodd"
                                    clip-rule="evenodd"
                                    d="M9.68746 10.609C9.94199 10.8636 10.3547 10.8636 10.6092 10.609C10.8638 10.3545 10.8638 9.94174 10.6092 9.6872L6.92202 5.99993L10.6093 2.31268C10.8638 2.05813 10.8638 1.64542 10.6093 1.39087C10.3547 1.13631 9.94199 1.13631 9.68746 1.39087L6.00019 5.07809L2.31292 1.39087C2.05837 1.13632 1.64566 1.13632 1.39111 1.39087C1.13656 1.64543 1.13656 2.05814 1.39111 2.3127L5.07835 5.99993L1.39112 9.6872C1.13657 9.94174 1.13657 10.3544 1.39112 10.609C1.64567 10.8636 2.05838 10.8636 2.31293 10.609L6.00019 6.92177L9.68746 10.609Z"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex flex-col">

                    <div class="chat-body mb-5 mt-5 space-y-10 dark:bg-darkblack-500" style="overflow-y: auto; max-height: 600px; padding-right: 15px;">
                        <div class="flex items-end justify-start space-x-3 chat-item">
                            <div class="flex space-x-3">
                                <div>
                                    <img src="http://wombat.uz/profile/assets/images/logo/logo.png" class="shrink-0" alt="" width="36">
                                </div>
                                <div class="rounded-b-lg rounded-r-lg bg-bgray-200 p-3 text-sm font-medium text-bgray-900 dark:bg-darkblack-600 dark:text-bgray-50">
                                    Я – WombatAI. Я прочитаю документ и отвечу на ваши вопросы, основываясь на его содержании. Пожалуйста, напишите свой вопрос.
                                </div>
                            </div>
                            <div>
                                <span class="text-xs font-medium text-bgray-500">09:30 AM</span>
                            </div>
                        </div>
                        @if(!empty($chatItems))
                            @foreach($chatItems as $item)
                                @if($item->role=='user')
                                    <div class="flex items-end justify-end space-x-3">
                                        <div>
                                            <span class="text-xs font-medium text-bgray-500">{{date('h:i A',strtotime($item->created_at))}}</span>
                                        </div>
                                        <div class="flex space-x-3 chat-item">
                                            <div class="rounded-b-lg rounded-r-lg bg-success-300 p-3 text-sm font-medium text-white">
                                                {{$item->message}}
                                            </div>
                                            <div>
                                                <img src="http://wombat.uz/profile/assets/images/avatar/user_1.jpg" class="shrink-0" alt="" width="36">
                                            </div>
                                        </div>
                                    </div>
                                @elseif($item->role=='assistant')

                                    <div class="flex items-end justify-start space-x-3 chat-item">
                                        <div class="flex space-x-3">
                                            <div>
                                                <img src="http://wombat.uz/profile/assets/images/logo/logo.png" class="shrink-0" alt="" width="36">
                                            </div>
                                            <div class="rounded-b-lg rounded-r-lg bg-bgray-200 p-3 text-sm font-medium text-bgray-900 dark:bg-darkblack-600 dark:text-bgray-50">
                                                {{ $item->message }}
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-xs font-medium text-bgray-500">{{date('h:i A',strtotime($item->created_at))}}</span>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif

                    </div>

                    <div class="bottom-10 mb-5 w-full lg:mb-0">
                        <div class="custom-quill-2">

                            <div id="toolbar2" class="ql-toolbar ql-snow">
                                <input type="text" id="chat_message" class="h-14 rounded-lg border-0 bg-bgray-50 p-4 focus:border focus:border-success-300 focus:ring-0 dark:bg-darkblack-500 dark:text-white w-full">
                                <button id="send_message" class="flex items-center justify-center gap-1.5 rounded-lg bg-success-400 px-4 py-2.5 text-sm font-semibold text-white" style=" float: right; top: -48px; position: relative; right: 8px; ">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M15.0586 7.09154L7.92522 3.52487C3.13355 1.12487 1.16689 3.09153 3.56689 7.8832L4.29189 9.3332C4.50022 9.7582 4.50022 10.2499 4.29189 10.6749L3.56689 12.1165C1.16689 16.9082 3.12522 18.8749 7.92522 16.4749L15.0586 12.9082C18.2586 11.3082 18.2586 8.69153 15.0586 7.09154ZM12.3669 10.6249H7.86689C7.52522 10.6249 7.24189 10.3415 7.24189 9.99987C7.24189 9.6582 7.52522 9.37487 7.86689 9.37487H12.3669C12.7086 9.37487 12.9919 9.6582 12.9919 9.99987C12.9919 10.3415 12.7086 10.6249 12.3669 10.6249Z" fill="white"></path>
                                    </svg>
                                    <span>Отправить</span>
                                </button>
                            </div>
                            <div class="mt-4 flex justify-end">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div style="display: none" id="chat_template">

    <div class="flex items-end justify-start space-x-3 chat-item assistant">
        <div class="flex space-x-3">
            <div>
                <img src="http://wombat.uz/profile/assets/images/logo/logo.png" class="shrink-0" alt="" width="36">
            </div>
            <div class="rounded-b-lg rounded-r-lg bg-bgray-200 p-3 text-sm font-medium text-bgray-900 dark:bg-darkblack-600 dark:text-bgray-50 assistant-message">
                {message}
            </div>
        </div>
        <div>
            <span class="text-xs font-medium text-bgray-500 assistant-time">{time}</span>
        </div>
    </div>
    <div class="flex items-end justify-end space-x-3 user">
        <div>
            <span class="text-xs font-medium text-bgray-500 user-time"></span>
        </div>
        <div class="flex space-x-3">
            <div class="rounded-b-lg rounded-r-lg bg-success-300 p-3 text-sm font-medium text-white user-message">
            </div>
            <div>
                <img src="http://wombat.uz/profile/assets/images/avatar/user_1.jpg" class="shrink-0" alt="" width="36">
            </div>
        </div>
    </div>

</div>

@section('js')
    @parent
    <script>
        $(document).ready(function () {
            var chat_process = false;
            var document_id = $('input[name="document_id"]').val();
            var doctype = $('input[name="object"]').val();
            if(doctype=='Product') doctype = 'Factura';
            var company_id = $('input[name="company_id"]').val();
            $('#send_message').click(function(){
                var message = $('#chat_message').val();
                if(message.length==0) {
                    alert('{{__('main.enter_value')}}');
                    $('#chat_message').focus();
                    return false;
                }
                $('#chat_message').val('');
                if(chat_process) {
                    return false;
                }
                chat_process = true;
                var time = new Date();
                var user = $('#chat_template .user').clone();
                setData(user,'user',message,time.toLocaleString('en-US', { hour: '2-digit', minute: '2-digit', hour12: true }));
                addChatItem(user,200);
                var assistant = $('#chat_template .assistant').clone();
                setData(assistant,'assistant','{{__('main.writing...')}}','');
                addChatItem(assistant,800);
                $.ajax({
                    type: 'post',
                    url: '/ru/profile/wombatai/send-message',
                    data: {'_token': _csrf_token, 'company_id': company_id,'document_id': document_id,'doctype': doctype,'message':message},
                    success: function ($response) {
                        if ($response.status) {
                            setData(assistant,'assistant',$response.message,$response.time);
                            scrollBottom();
                            $('#chat_message').val('').focus();
                        } else {
                            alert($response.error);
                        }
                        chat_process = false;
                    },
                    error: function (e) {
                        alert(e)
                        chat_process = false;
                    }
                });
            });
            $('input#chat_message').on('keyup',function(e) {
                if (e.keyCode ==13) $('#send_message').click();
            });
            function scrollBottom() {
                $('.chat-body').animate({
                    scrollTop: $('.chat-body')[0].scrollHeight
                }, 300);
            }
            function setData(object,name,message,time){
                object.find('.'+name+'-message').text(message);
                object.find('.'+name+'-time').text(time);
            }
            function addChatItem(object,timeout){
                setTimeout(function(){
                    $('.chat-body').append(object);
                    scrollBottom();
                },timeout);
            }
        })
    </script>
@endsection
