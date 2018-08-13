$(document).ready(
    function()
    {

        function loadProfile(uc)
        {
            var theUrl = window.location.protocol + '//' + window.location.hostname + (window.location.port !== '' ? ':' + window.location.port : '');
            $.ajax(
                {
                    url: theUrl + '/auth/loadprofile',
                    type: 'POST',
                    data: {
                        ucname: uc
                    },
                    dataType: 'text',
                    beforeSend: function()
                    {
                        $('#loginfrm p.spinner').show();
                        $('#loginfrm p.overlay').show();
                        $('#loginfrm a#sms').html('تحميل الملف الشخصي...');
                    },
                    success: function(respond)
                    {
                        if(respond == 1) {
                            loadPrivileges($('#ucname').val());
                        } else {
                            $('#loginfrm p.spinner').hide();
                            $('#loginfrm p.overlay').hide();
                            $('#loginfrm a#sms').html('تسجيل الدخول');
                            $('#loginfrm a#sms').attr('href', '/auth/login');
                            $('p#loginerror').show().html('هناك خطا فني في تحميل بيانات. من فضلك حاول بعد فترة قصيرة من الوقت.');
                        }
                    },
                    error: function()
                    {

                    }
                }
            );
        }

        function loadPrivileges(uc)
        {
            var theUrl = window.location.protocol + '//' + window.location.hostname + (window.location.port !== '' ? ':' + window.location.port : '');
            $.ajax(
                {
                    url: theUrl + '/auth/loadprivileges',
                    type: 'POST',
                    data: {
                        ucname: uc
                    },
                    dataType: 'text',
                    beforeSend: function()
                    {
                        $('#loginfrm p.spinner').show();
                        $('#loginfrm p.overlay').show();
                        $('#loginfrm a#sms').html('تحميل الصلاحيات...');
                    },
                    success: function(respond)
                    {
                        if(respond == 1) {
                            doLogin();
                        } else {
                            $('#loginfrm p.spinner').hide();
                            $('#loginfrm p.overlay').hide();
                            $('#loginfrm a#sms').html('تسجيل الدخول');
                            $('#loginfrm a#sms').attr('href', '/auth/login');
                            $('p#loginerror').show().html('هناك خطا فني في تحميل بيانات. من فضلك حاول بعد فترة قصيرة من الوقت.');
                        }
                    },
                    error: function()
                    {

                    }
                }
            );
        }

        function doLogin()
        {
            var theUrl = window.location.protocol + '//' + window.location.hostname + (window.location.port !== '' ? ':' + window.location.port : '');
            $.ajax(
                {
                    url: theUrl + '/auth/dologin',
                    type: 'POST',
                    dataType: 'text',
                    beforeSend: function()
                    {
                        $('#loginfrm p.spinner').show();
                        $('#loginfrm p.overlay').show();
                        $('#loginfrm a#sms').html('تسجيل الدخول للنظام...');
                    },
                    success: function(respond)
                    {
                        if(respond == 1) {
                            window.location.href = '/';
                        } else {
                            $('#loginfrm p.spinner').hide();
                            $('#loginfrm p.overlay').hide();
                            $('#loginfrm a#sms').html('تسجيل الدخول');
                            $('#loginfrm a#sms').attr('href', '/auth/login');
                            $('p#loginerror').show().html('هناك خطا فني في تحميل بيانات. من فضلك حاول بعد فترة قصيرة من الوقت.');
                        }
                    },
                    error: function()
                    {

                    }
                }
            );
        }

        function checkSMSValidity(uc)
        {
            var theUrl = window.location.protocol + '//' + window.location.hostname + (window.location.port !== '' ? ':' + window.location.port : '');
            $.ajax(
                {
                    url: theUrl + '/auth/checksms',
                    type: 'POST',
                    data: {
                        ucname: uc,
                        code: $('#smscode').val()
                    },
                    dataType: 'text',
                    beforeSend: function()
                    {
                        $('#loginfrm p.spinner').show();
                        $('#loginfrm p.overlay').show();
                        $('#loginfrm a#sms').html('التحقق من صحة الرمز...');
                    },
                    success: function(respond)
                    {
                        if(respond == 1) {
                            loadProfile($('#ucname').val());
                        } else {
                            $('#loginfrm p.spinner').hide();
                            $('#loginfrm p.overlay').hide();
                            $('#loginfrm a#sms').html('ارسل الرمز');
                            $('p#loginerror').show().html('الرمز المرسل غير صحيح من فضلك تاكد من الرمز و حاول مرة اخرى.');
                        }
                    },
                    error: function()
                    {

                    }
                }
            );
        }


        $('#loginfrm').submit(function(e)
        {
            e.preventDefault();
            var theUrl = window.location.protocol + '//' + window.location.hostname + (window.location.port !== '' ? ':' + window.location.port : '');
            $.ajax(
                {
                    url: theUrl + '/auth/authenticate',
                    type: 'POST',
                    data: {
                        ucname: $('#ucname').val(),
                        ucpwd: $('#ucpwd').val()
                    },
                    dataType: 'text',
                    beforeSend: function()
                    {
                        $('#loginfrm p.spinner').show();
                        $('#loginfrm p.overlay').show();
                        $('#loginfrm input[type=submit]').val('التاكد من هويتك...');
                    },
                    success: function(output)
                    {
                        if(output == 1) {
                            $('#ucname').hide();
                            $('#ucpwd').hide();
                            $('#ucpwd').parent().append('<input required type="text" id="smscode" name="smscode" maxlength="4" placeholder="من فضلك ادخل الرمز المرسل لجوالك">')
                            $('#loginfrm input[type=submit]').hide();
                            $('#loginfrm input[type=submit]').parent().append('<a href="javascript:;" id="sms">ارسل الرمز</a>');
                            $('a#sms').on('click', function()
                            {
                                checkSMSValidity($('#ucname').val());
                            });
                            $('input#smscode').focus();
                            $(document).ready(function() {
                                $('#loginfrm input#smscode').keydown(function(event){
                                    if(event.keyCode == 13) {
                                        event.preventDefault();
                                        checkSMSValidity($('#ucname').val());
                                        return false;
                                    }
                                });
                            });
                        } else if(output == 2) {
                            $('#loginfrm p.spinner').hide();
                            $('#loginfrm p.overlay').hide();
                            $('#loginfrm input[type=submit]').val('دخول');
                            $('p#loginerror').show().html('عفوا هذا المستخدم تم تعطيله من قبل الادارة');
                        } else if(output == 3) {
                            $('#loginfrm p.spinner').hide();
                            $('#loginfrm p.overlay').hide();
                            $('#loginfrm input[type=submit]').val('دخول');
                            $('p#loginerror').show().html('عفوا كلمة المرور او اسم المستخدم غير صحيح');
                        } else if(output == 4) {
                            $('#loginfrm p.spinner').hide();
                            $('#loginfrm p.overlay').hide();
                            $('#loginfrm input[type=submit]').val('دخول');
                            $('p#loginerror').show().html('عفوا لقد انتهى وقت الدوام الرسمي');
                        }
                    },
                    error: function()
                    {

                    }
                }
            );
        });
    }
);
