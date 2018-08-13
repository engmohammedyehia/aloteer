if(typeof $('table.data').DataTable === 'function') {
    $('table.data').DataTable(
        {
            initComplete: function () {
                if($(this).hasClass('cheque')) {
                    this.api().columns([2,3]).every( function () {
                        var column = this;
                        var select = $('<select><option value="">' +
                            $(column.header()).attr('data-name') + '</option></select>')
                            .appendTo( $(column.header()).empty() )
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );

                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                    } );
                }
            },
            "aaSorting": [],
            "stateSave": true,
            "columnDefs": [
                {
                    "targets": [$(this).find('thead').find('tr').children('th').length - 1],
                    "orderable": false
                }
            ]
        }
    );
    $('table.data').on( 'draw.dt', function () {
        $('a.open_controls').off('click');
        $('a.open_controls').click(function(evt)
        {
            evt.preventDefault();
            evt.stopPropagation();
            var theLink = $(this);
            $('a.open_controls').not(theLink).html('<i class="fa fa-caret-square-o-left"></i>').css({color: '#4a647d'});
            if(theLink.html() == '<i class="fa fa-caret-square-o-left"></i>') {
                theLink.html('<i class="fa fa-caret-square-o-down"></i>').css({color: '#479dce'});;
            } else {
                theLink.html('<i class="fa fa-caret-square-o-left"></i>').css({color: '#4a647d'});
            }
            var theContainer = theLink.next('div.controls_container');
            $("div.controls_container").not(theContainer).hide();
            theContainer.toggle();

            if(theContainer.height() > ($(window).height() - (evt.clientY || evt.screenY))) {
                theContainer.css({top: (theContainer.height() - 23) * -1});
            }
        });
    });
}

$('a.open_controls').off('click');
$('a.open_controls').click(function(evt)
{
    evt.preventDefault();
    evt.stopPropagation();
    var theLink = $(this);
    $('a.open_controls').not(theLink).html('<i class="fa fa-caret-square-o-left"></i>').css({color: '#4a647d'});
    if(theLink.html() == '<i class="fa fa-caret-square-o-left"></i>') {
        theLink.html('<i class="fa fa-caret-square-o-down"></i>').css({color: '#479dce'});;
    } else {
        theLink.html('<i class="fa fa-caret-square-o-left"></i>').css({color: '#4a647d'});
    }
    var theContainer = theLink.next('div.controls_container');
    $("div.controls_container").not(theContainer).hide();
    theContainer.toggle();

    if(theContainer.height() > ($(window).height() - (evt.clientY || evt.screenY))) {
        theContainer.css({top: (theContainer.height() - 23) * -1});
    }
});

$('a.menu_switch').click(function(evt)
{
    evt.preventDefault();
    if($(this).attr('data-menu-status') == 'false') {
        $(this).removeClass('no_animation');
        $('nav.main_navigation').removeClass('no_animation');
        $('div.action_view').removeClass('no_animation');
        $(this).attr('data-menu-status', 'true');
        $(this).addClass('opened');
        $('nav.main_navigation').addClass('opened');
        $('div.action_view').addClass('collapsed');
        if(getCookie('menu_opened') == "") {
            setCookie('menu_opened', true, 180, '.madinahcheque.dev');
        }
    } else {
        $(this).attr('data-menu-status', 'false');
        $(this).removeClass('opened');
        $('nav.main_navigation').removeClass('opened');
        $('div.action_view').removeClass('collapsed');
        deleteCookie('menu_opened', '.madinahcheque.dev');
    }
});

$('form.appForm input:not(.no_float)').on('focus', function()
{
    $(this).parent().find('label').addClass('floated');
}).on('blur', function()
{
    if($(this).val() == '') {
        $(this).parent().find('label').removeClass('floated');
    } else {

    }
});

$('div.radio_button, div.checkbox_button, label.radio span, label.checkbox span, a.language_switch.user').click(function(evt)
{
     evt.stopPropagation();
});

(function()
{
    var closeMessageButtons = document.querySelectorAll('p.message a.closeBtn');
    for ( var i = 0, ii = closeMessageButtons.length; i < ii; i++ ) {
        closeMessageButtons[i].addEventListener('click', function (evt) {
            evt.preventDefault();
            this.parentNode.parentNode.removeChild(this.parentNode);
        }, false);
    }
})();

$(document).click(function()
{
    $('ul.user_menu').hide();
    $('a.open_controls').html('<i class="fa fa-caret-square-o-left"></i>').css({color: '#4a647d'});
    $('div.controls_container').hide();
    $('div.controls_container').css({top: 6});
});

$('div.controls_container').click(function (evt)
{
    evt.stopPropagation();
});

$('a.language_switch.user,a.language_switch.mail,a.language_switch.notifications').click(function(evt)
{
    evt.preventDefault();
    evt.stopPropagation();
    $('ul.user_menu').not($(this).next()).hide();
    $(this).next().toggle();
});

window.onload = function()
{
    if($('html').attr('lang') == 'en') {
        $('ul.user_menu.mail').css('right', ($('a.language_switch.user').width() + 23));
        $('ul.user_menu.notifications').css('right', ($('a.language_switch.user').width() + 69));
    } else {
        $('ul.user_menu.mail').css('left', ($('a.language_switch.user').width() + 22));
        $('ul.user_menu.notifications').css('left', ($('a.language_switch.user').width() + 65));
    }
    if($('div.action_view').height() > $('nav.main_navigation').height()) {
        $('nav.main_navigation').height($('nav.main_navigation').height() + ($('div.action_view').height() - $('nav.main_navigation').height()));
    }
    $('div.dataTables_length select').on('change', function()
    {
        if($('div.action_view').height() > $('nav.main_navigation').height()) {
            $('nav.main_navigation').height($('nav.main_navigation').height() + ($('div.action_view').height() - $('nav.main_navigation').height()));
        } else {
            $('nav.main_navigation').height($('div.action_view').height());
        }
    })
}

$('li.submenu > a').click(function()
{
    var that = $(this);
    $('li.submenu > ul').not($(this).next()).slideUp();
    $(this).next().slideToggle(function()
    {
        $('li.submenu').not(that.parent()).removeClass('selected');
        if(that.parent().hasClass('selected')) {
            that.parent().removeClass('selected')
        } else {
            that.parent().addClass('selected');
        }
    });

});

(function()
{
    var userNameField = document.querySelector('input[name=Username]');
    if(null !== userNameField) {
        userNameField.addEventListener('blur', function()
        {
            var req = new XMLHttpRequest();
            req.open('POST', 'https://www.madinahcheque.dev/users/checkuserexistsajax');
            req.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            req.onreadystatechange = function()
            {
                var iElem = document.createElement('i');
                if(req.readyState == req.DONE && req.status == 200) {
                    if(req.response == 1) {
                        iElem.className = 'fa fa-times error';
                    } else if(req.response == 2) {
                        iElem.className = 'fa fa-check success';
                    }
                    var iElems = userNameField.parentNode.childNodes;
                    for ( var i = 0, ii = iElems.length; i < ii; i++ )
                    {
                        if(iElems[i].nodeName.toLowerCase() == 'i') {
                            iElems[i].parentNode.removeChild(iElems[i]);
                        }
                    }
                    userNameField.parentNode.appendChild(iElem);
                }
            }

            req.send("Username=" + this.value);
        }, false);
    }
})();

$.ajax(
    {
        url: 'https://api.openweathermap.org/data/2.5/weather',
        type: 'GET',
        data: {
            lat: 24.47,
            lon: 39.61,
            appid: '2b3787e49727461572b557acb1a7c87e',
            units: 'metric'
        },
        dataType: 'json',
        beforeSend: function()
        {
            $('a.weatherinfo span.degree').hide();
            $('a.weatherinfo').prepend('<i class="fa fa-spinner fa-spin fa-fw"></i>')
        },
        success: function(respond)
        {
            $('a.weatherinfo > i').remove();
            $('a.weatherinfo span.degree').show();
            $('a.weatherinfo span.icon').html('<img src="/img/weather/' + respond.weather[0].icon + '.png">');
            $('a.weatherinfo span.degree').html((respond.main.temp).toFixed() + '&deg;');
        }
    }
);

$("input[data-language=en]").keypress(function(evt){
    $('div.action_view p.keyboard').remove();

    var ew = evt.which;
    if(ew == 32)
        return true;
    if(48 <= ew && ew <= 57)
        return true;
    if(65 <= ew && ew <= 90)
        return true;
    if(97 <= ew && ew <= 122)
        return true;

    if($('html').attr('lang') == 'en') {
        $('div.action_view').prepend('<p class="message t2 keyboard">Switch keyboard language to english</p>');
    } else {
        $('div.action_view').prepend('<p class="message t2 keyboard">غير لغة لوحة المفاتيح للغة الإنجليزية</p>');
    }

    return false;
});

$('select[data-selectivity]').selectivity();

$('#OnBank').on('keyup', function()
{
    $('div.cheque_on_bank').html($(this).val());
});