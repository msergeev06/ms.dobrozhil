MS.dobrozhil.objects = {};

MS.dobrozhil.objects.changePlusMinus = function (icon, type) {
    if (type == "plus") {
        icon.removeClass('fa-minus');
        icon.addClass('fa-plus');
    } else {
        icon.removeClass('fa-plus');
        icon.addClass('fa-minus');
    }
};

MS.dobrozhil.objects.toggleId = function (id, type) {
    var el = $('#'+type+'-'+id);
    var icon = $('#icon-'+type+'-toggle-'+id);
    var view = el.data('view');
    var userID = MS.core.user.getID();
    if (view == "show") {
        el.hide();
        el.data("view","hide");
        MS.dobrozhil.objects.changePlusMinus(icon,"plus");
        if (type == "class") {
            MS.dobrozhil.objects.setCookieShowHideClasses(userID,id,0);
        }
    } else {
        el.show();
        el.data("view","show");
        MS.dobrozhil.objects.changePlusMinus(icon,"minus");
        if (type == "class") {
            MS.dobrozhil.objects.setCookieShowHideClasses(userID,id,1);
        }
    }
};

MS.dobrozhil.objects.toggleClass = function (id) {
    MS.dobrozhil.objects.toggleId(id,"class");
};

MS.dobrozhil.objects.toggleProperty = function (id) {
    MS.dobrozhil.objects.toggleId(id, "property");
};

MS.dobrozhil.objects.toggleMethods = function (id) {
    MS.dobrozhil.objects.toggleId(id, "methods");
};

MS.dobrozhil.objects.setCookieShowHideClasses = function (userID, classID, value)
{
    $.ajax({
        type: "POST",
        url: '/ms/modules/ms.dobrozhil/tools/ajax/set_cookie.php',
        data: {
            cookieName: 'classes-view-'+classID,
            value: value,
            userID: userID,
            sessid: ms_sessid()
        },
        success: function(data){
            if (data.result=='OK')
            {
                return true;
            }
            else
            {
                return false;
            }
        },
        dataType: 'json'
    });
};

MS.dobrozhil.objects.showHiddenClasses = function () {
    $('#hidden-classes').show();
    $('#bnt-hidden-classes').hide();
};