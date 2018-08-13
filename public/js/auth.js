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
                        $('#loginfrm input[type=submit]').val('تحميل الملف الشخصي...');
                    },
                    success: function(respond)
                    {
                        if(respond == 1) {
                            loadPrivileges($('#ucname').val());
                        } else {
                            $('#loginfrm p.spinner').hide();
                            $('#loginfrm p.overlay').hide();
                            $('#loginfrm input[type=submit]').val('دخول');
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
                        $('#loginfrm input[type=submit]').val('تحميل الصلاحيات...');
                    },
                    success: function(respond)
                    {
                        if(respond == 1) {
                            doLogin();
                        } else {
                            $('#loginfrm p.spinner').hide();
                            $('#loginfrm p.overlay').hide();
                            $('#loginfrm input[type=submit]').val('دخول');
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
                        $('#loginfrm input[type=submit]').val('تسجيل الدخول للنظام...');
                    },
                    success: function(respond)
                    {
                        if(respond == 1) {
                            window.location.href = '/';
                        } else {
                            $('#loginfrm p.spinner').hide();
                            $('#loginfrm p.overlay').hide();
                            $('#loginfrm input[type=submit]').val('دخول');
                            $('p#loginerror').show().html('هناك خطا فني في تحميل بيانات. من فضلك حاول بعد فترة قصيرة من الوقت.');
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
                            loadProfile($('#ucname').val());
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
