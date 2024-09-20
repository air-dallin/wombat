@section('js')
    @parent
    <script src="{{ asset('js/e-imzo.js')}}"></script>
    <script src="{{ asset('js/e-imzo-client.js')}}"></script>

    <script language="javascript">

        $(document).ready(function(){
            $('#data').val($('#document-json').text())
            $('#res').val('');
            $('#reject').click(function(){
                var comment = $('#comment').val()
                if(comment.length==0){
                    alert('{{__('main.enter_value')}}');
                    $('#comment').focus();
                    return false;
                }
                if(document.reject.sign.value.length==0){
                    alert('{{__('main.sign_not_found')}}');
                    return false;
                }
                notify('{{__('main.sending')}}','primary',5000);
                $.ajax({
                    type: 'post',
                    url: '/ru/didox/reject',
                    data: {
                        '_token': _csrf_token,
                        'object': document.eimzo.object.value,
                        'document_id': document.eimzo.document_id.value,
                        'signature': document.reject.sign.value,
                        'comment':comment
                    },
                    success: function ($response) {
                        if ($response.status) {
                            window.location.reload()
                        }else{
                            notify( $response.error,'danger',10000);
                        }
                    }
                });
                closeModal('multi-step-modal2');
            });
            $('.sign-btn').click(function(){
               $('select[name=key] option:first').change();
            });
        })
        var EIMZO_MAJOR = 3;
        var EIMZO_MINOR = 37;
        var errorCAPIWS = 'Ошибка соединения с E-IMZO. Возможно у вас не установлен модуль E-IMZO или Браузер E-IMZO.';
        var errorBrowserWS = 'Браузер не поддерживает технологию WebSocket. Установите последнюю версию браузера.';
        var errorUpdateApp = 'ВНИМАНИЕ !!! Установите новую версию приложения E-IMZO или Браузера E-IMZO.<br /><a href="https://e-imzo.uz/main/downloads/" role="button">Скачать ПО E-IMZO</a>';
        var errorWrongPassword = 'Пароль неверный.';
        var process = false;
        var companyTokenExpire = $('#tokenExpire').val();

        var AppLoad = function () {
            EIMZOClient.API_KEYS = [
                'localhost', '96D0C1491615C82B9A54D9989779DF825B690748224C2B04F500F370D51827CE2644D8D4A82C18184D73AB8530BB8ED537269603F61DB0D03D2104ABF789970B',
                '127.0.0.1', 'A7BCFA5D490B351BE0754130DF03A068F855DB4333D43921125B9CF2670EF6A40370C646B90401955E1F7BC9CDBF59CE0B2C5467D820BE189C845D0B79CFC96F',
                'wombat.uz', 'B20B41525B55BEA4DEFAAA1914D3FA83FD7E6544C193982C481406F37FE95F0132401782FF561741A4340449FEF86C4CAFA7CD3A3F81267B5DA97A6C3ADA4D54'
            ];
            EIMZOClient.checkVersion(function(major, minor){
                var newVersion = EIMZO_MAJOR * 100 + EIMZO_MINOR;
                var installedVersion = parseInt(major) * 100 + parseInt(minor);
                if(installedVersion < newVersion) {
                    uiUpdateApp();
                } else {
                    EIMZOClient.installApiKeys(function(){
                        uiLoadKeys();
                    },function(e, r){
                        if(r){
                            uiShowMessage(r);
                        } else {
                            wsError(e);
                        }
                    });
                }
            }, function(e, r){
                if(r){
                    uiShowMessage(r);
                } else {
                    uiNotLoaded(e);
                }
            });
        }


        var uiShowMessage = function(message){
            $('#sign-document').text('{{__('main.next')}}')
            $('#sign-document').css('display','block');
            $('#complete').css('display','none');
            if(message==errorWrongPassword) notify(message,'danger',5000);
        }

        var uiNotLoaded = function(e){
            var l = document.getElementById('message');
            l.innerHTML = '';
            if (e) {
                wsError(e);
            } else {
                uiShowMessage(errorBrowserWS);
            }
        }

        var uiUpdateApp = function(){
            var l = document.getElementById('message');
            l.innerHTML = errorUpdateApp;
        }

        var uiLoadKeys = function(){
            uiClearCombo();
            EIMZOClient.listAllUserKeys(function(o, i){
                var itemId = "itm-" + o.serialNumber + "-" + i;
                return itemId;
            },function(itemId, v){
                return uiCreateItem(itemId, v);
            },function(items, firstId){
                uiFillCombo(items);
                uiLoaded();
                uiComboSelect(firstId);
            },function(e, r){
                if(e){
                    uiShowMessage(errorCAPIWS + " : " + e);
                } else {
                    uiShowMessage(r);
                }
            });
        }

        var uiComboSelect = function(itm){
            if(itm){
                var id = document.getElementById(itm);
                id.setAttribute('selected','true');
            }
        }

        var cbChanged = function(c){
            document.getElementById('keyId').innerHTML = '';
            json = JSON.parse($('select[name=key] option:selected').attr('vo'));
            $('#eimzo_date_from').text(json.validFrom.replace('T',' ').replace('.000Z',''));
            $('#eimzo_date_to').text(json.validTo.replace('T',' ').replace('.000Z',''));
            $('#eimzo_company').text(json.O);
            $('#eimzo_tin').text(json.TIN);
            $('#eimzo_pinfl').text(json.PINFL);
            $('#complete').css('display','none');
            $('#sign-document').css('display','block');
        }

        var uiClearCombo = function(){
            var combo = document.eimzo.key;
            combo.length = 0;
        }

        var uiFillCombo = function(items){
            var combo = document.eimzo.key;
            for (var itm in items) {
                combo.append(items[itm]);
            }
        }

        var uiLoaded = function(){
            var l = document.getElementById('message');
            l.innerHTML = '';
        }

        var uiCreateItem = function (itmkey, vo) {
            var now = new Date();
            vo.expired = dates.compare(now, vo.validTo) > 0;
            var itm = document.createElement("option");
            itm.value = itmkey;
            itm.text = vo.O.length ? vo.O : vo.CN;
            if (vo.expired) {
                itm.style.color = 'gray';
                itm.text = itm.text + ' (срок истек)';
            }
            itm.setAttribute('vo',JSON.stringify(vo));
            itm.setAttribute('id',itmkey);
            return itm;
        }

        var wsError = function (e) {
            if (e) {
                uiShowMessage(errorCAPIWS + " : " + e);
            } else {
                uiShowMessage(errorBrowserWS);
            }
        };

        var status = 0;
        sign = function () {
            var itm = document.eimzo.key.value;
            if (itm) {
                var id = document.getElementById(itm);
                var vo = JSON.parse(id.getAttribute('vo'));
                var data = document.eimzo.data.value;
                var keyId = document.getElementById('keyId').innerHTML;
                status = document.eimzo.status.value;
                if(keyId){
                    if(status==2) {
                        appendPkcs7(data,keyId);
                    }else{
                        pkcs7(data,keyId);
                    }
                } else {
                    EIMZOClient.loadKey(vo, function(id){
                        document.getElementById('keyId').innerHTML = id;
                        keyId = id;
                        if(status==2) {
                            appendPkcs7(data,id);
                        }else {
                            pkcs7(data,id);
                        }
                    }, function(e, r){
                        if(r){
                            if (r.indexOf("BadPaddingException") != -1) {
                                uiShowMessage(errorWrongPassword);
                            } else {
                                uiShowMessage(r);
                            }
                        } else {
                            uiShowMessage(errorBrowserWS);
                        }
                        if(e) wsError(e);
                    });
                }

                /*if(companyTokenExpire) {
                    data = document.eimzo.inn.value;
                    pkcs7(data,keyId);
                }*/

                $('#sign-document').css('display','none');
                $('#complete').css('display','block');
            }
        };
        addTimestamp = function (){
            if(process) {
                return false;
            }
            process = true;
            notify('{{__('main.wait')}}','primary',15000);
            $.ajax({
                type: 'post',
                url: '/ru/didox/get-timestamp',
                data: {'_token': _csrf_token,'company_id':document.eimzo.company_id.value,'signatureHex': document.eimzo.hex.value},
                success: function ($response) {
                    if ($response.status) {
                        document.eimzo.tst.value = $response.timestamp;
                        var pkcs7 = document.eimzo.pkcs7.value;
                        var sn    = document.eimzo.serial.value;
                        var tst   = document.eimzo.tst.value;
                        CAPIWS.callFunction({plugin:"pkcs7", name:"attach_timestamp_token_pkcs7", arguments:[pkcs7, sn, tst]},function(event, data){
                            if(data.success){
                                notify('{{__('main.success')}} 1-2','success',15000);
                                res = document.getElementById('res')
                                res.value = data.pkcs7_64;
                                $.ajax({
                                    type: 'post',
                                    url: '/ru/didox/sign',
                                    data: {
                                        '_token': _csrf_token,
                                        'object': document.eimzo.object.value,
                                        'document_id': document.eimzo.document_id.value,
                                        'signature': document.eimzo.res.value
                                    },
                                    success: function ($response) {
                                        if ($response.status) {
                                            notify('{{__('main.success')}} 2-2','success',3000);
                                            window.location.reload()
                                        }else{
                                            notify($response.error,'danger',15000);
                                        }
                                        process = false;
                                    }
                                });
                            } else {
                                alert(data.reason);
                                process = false;
                            }
                        }, function (e) {
                            alert(e);
                            process = false;
                        });

                    } else {
                        alert($response.error);
                        process = false;
                    }
                },
                error: function (e) {
                    alert(e);
                    process = false;
                }
            });
        }

        /** добавить подпись партнера */
        appendPkcs7 = function(pkcs7_64,keyId){
            CAPIWS.callFunction({plugin:"pkcs7", name:"append_pkcs7_attached ", arguments:[pkcs7_64, keyId]},function(event, dataAttach) {
                if (dataAttach.success) {
                    document.eimzo.pkcs7.value = dataAttach.pkcs7_64;
                    document.eimzo.hex.value = dataAttach.signature_hex;
                    document.eimzo.serial.value = dataAttach.signer_serial_number;
                }
            }, function (e) {
                alert(e);
            });
        }

        /** сгенерировать подпись или обновить токен компании */
        pkcs7 = function(data,id) {
            EIMZOClient.createPkcs7(id, data, null, function (dataPkcs7) {
                if(!companyTokenExpire){
                    document.eimzo.pkcs7.value = dataPkcs7.pkcs7_64;
                    document.eimzo.hex.value = dataPkcs7.signature_hex;
                    document.eimzo.serial.value = dataPkcs7.signer_serial_number;
                }else { // обновить токен компании
                    $.ajax({
                        type: 'post',
                        url: '/ru/didox/get-timestamp',
                        data: {
                            '_token': _csrf_token,
                            'company_id': document.eimzo.company_id.value,
                            'signatureHex': dataPkcs7.signature_hex
                        },
                        success: function ($response) {
                            if ($response.status) {
                                var pkcs7 = dataPkcs7.pkcs7_64;
                                var sn = dataPkcs7.signer_serial_number;
                                var tst = $response.timestamp;
                                CAPIWS.callFunction({
                                    plugin: "pkcs7",
                                    name: "attach_timestamp_token_pkcs7",
                                    arguments: [pkcs7, sn, tst]
                                }, function (event, data) {
                                    if (data.success) {
                                        notify('Attach timestamp {{__('main.success')}} 1-2', 'success', 3000);
                                        //alert(id)
                                        $.ajax({
                                            type: 'post',
                                            url: '/ru/didox/update-token',
                                            data: {
                                                '_token': _csrf_token,
                                                'company_id': document.eimzo.company_id.value,
                                                'signature': data.pkcs7_64,
                                                'key':id
                                            },
                                            success: function ($response) {
                                                if ($response.status) {
                                                    notify('Update token {{__('main.success')}} 2-2', 'success', 3000);
                                                    window.location.reload()
                                                } else {
                                                    notify($response.error, 'danger', 15000);
                                                }
                                            }
                                        });

                                    } else {
                                        alert(data.reason);
                                    }
                                }, function (e) {
                                    alert(e);
                                });

                            } else {
                                alert($response.error);
                            }
                        },
                        error: function (e) {
                            alert(e);
                        }
                    });
                }


            }, function (e, r) {
                if (r) {
                    if (r.indexOf("BadPaddingException") != -1) {
                        uiShowMessage(errorWrongPassword);
                    } else {
                        uiShowMessage(r);
                    }
                } else {
                    document.getElementById('keyId').innerHTML = '';
                    uiShowMessage(errorBrowserWS);
                }
                if (e) wsError(e);
            });
        }

        /* attachTimestamp = function(){

            document.eimzo.tst.value = $response.timestamp;
            var pkcs7 = document.eimzo.pkcs7.value;
            var sn    = document.eimzo.serial.value;
            var tst   = document.eimzo.tst.value;
            CAPIWS.callFunction({plugin:"pkcs7", name:"attach_timestamp_token_pkcs7", arguments:[pkcs7, sn, tst]},function(event, data){
                if(data.success){
                    notify('{ {__('main.success')} } 1-2','success',15000);
                    res = document.getElementById('res')
                    res.value = data.pkcs7_64;
                    $.ajax({
                        type: 'post',
                        url: '/ru/didox/sign',
                        data: {
                            '_token': _csrf_token,
                            'object': document.eimzo.object.value,
                            'document_id': document.eimzo.document_id.value,
                            'signature': document.eimzo.res.value
                        },
                        success: function ($response) {
                            if ($response.status) {
                                notify('{ {__('main.success')} } 2-2','success',3000);
                                window.location.reload()
                            }else{
                                notify($response.error,'danger',15000);
                            }
                            process = false;
                        }
                    });
                } else {
                    alert(data.reason);
                    process = false;
                }
            }, function (e) {
                alert(e);
                process = false;
            });

        } */

        /* getTimestamp = function(){
            $.ajax({
                type: 'post',
                url: '/ru/didox/get-timestamp',
                data: {'_token': _csrf_token,'company_id':document.eimzo.company_id.value,'signatureHex': document.eimzo.hex.value},
                success: function ($response) {
                    if ($response.status) {
                        document.eimzo.tst.value = $response.timestamp;
                        var pkcs7 = document.eimzo.pkcs7.value;
                        var sn    = document.eimzo.serial.value;
                        var tst   = document.eimzo.tst.value;
                        CAPIWS.callFunction({plugin:"pkcs7", name:"attach_timestamp_token_pkcs7", arguments:[pkcs7, sn, tst]},function(event, data){
                            if(data.success){
                                notify('Attach timestamp { {__('main.success')}} 1-2','success',3000);
                                / * res = document.getElementById('res')
                                res.value = data.pkcs7_64; * /
                                $.ajax({
                                    type: 'post',
                                    url: '/ru/didox/update-token',
                                    data: {
                                        '_token': _csrf_token,
                                        'company_id': document.eimzo.company_id.value,
                                        'signature': data.pkcs7_64 // document.eimzo.res.value
                                    },
                                    success: function ($response) {
                                        if ($response.status) {
                                            notify('Update token { {__('main.success')}} 2-2','success',3000);
                                            window.location.reload()
                                        }else{
                                            notify($response.error,'danger',15000);
                                        }
                                        process = false;
                                    }
                                });

                            } else {
                                alert(data.reason);
                                process = false;
                            }
                        }, function (e) {
                            alert(e);
                            process = false;
                        });

                    } else {
                        alert($response.error);
                        process = false;
                    }
                },
                error: function (e) {
                    alert(e);
                    process = false;
                }
            }); */


        window.onload = AppLoad;
        //companyTokenExpire = true;
        //alert(companyTokenExpire)
        //pkcs7(document.eimzo.inn.value,'07e15361064f259d93dde46d2c15748c');

        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }
    </script>
@endsection
