(jQuery)(function ($) {
    // Validate User
    var d1 = $.Deferred();
    var validOption = Cookies.get("wpi_pro");
    var licence = Cookies.get("wpi_licence");
    var validated = Cookies.get("wpi_validated");
    var url = 'https://media-store.net/wp-json/wp/v2/wpmi/validateUser';
    var wpurl = window.location.protocol+'//'+window.location.host+window.location.pathname;
    wpurl = wpurl.replace('admin.php', '');
    wpurl = wpurl + 'admin-ajax.php?action=wpi_valid_status';
    //Sconsole.log(wpurl);

if(validated !== 'true') {
    $.ajax({
        "type": "GET",
        "url": url,
        "data": "licence=" + licence,
        "cache": false,
        complete: function (xhr) {
            //console.log(xhr);
            d1.resolve(xhr, validOption);

            return d1.promise;
        },
        success: function (data, status, xhr) {
            console.log(data);
        },
        error: function (xhr, status, errorThrown) {
            //console.dir(errorThrown);
            //console.log(status);
        }

    });
    d1.promise().then(function (jqXHR, validOption) {
        //console.log('Promise geladen...');
        //console.log(jqXHR.responseJSON);
        var response = jqXHR.responseJSON;
        var wpajax = wpurl;
        var valid = 'false';

        $.ajax({
            "action": "wpi_valid_status",
            "type": "POST",
            "url": wpajax,
            "data": response,
            "cache": false,
            complete: function (xhr) {
                //console.log(xhr.responseText);
                if(xhr.responseText == 'reload0') {
                    window.location.reload();
                }
            },
        });


        /*if (response.valid === true && validOption !== 'true') {
         console.log(response);
         Cookies.set('wpi_pro', 'true', {path: ''});
         Cookies.set('wpi_validated', 'true', {path: ''});
         */
        /*setTimeout(function () {*/
        /*window.location.reload(true);*/
        /*}, 2000);*/
        /*}
         else if (true !== response.valid && validOption == 'true') {
         console.log(response.text);
         //Cookies.set('wpi_pro', 'false', {path: ''});
         Cookies.remove('wpi_licence');
         Cookies.remove('wpi_validated');
         }
         */

    });
}

    /**
     * Single Page Settings
     */
        // Activen Radio Button ermitteln
    var activeRadio = '.' + $('.single-radio input:checked').parent().parent().attr('id');
    // Settings einblenden
    $(activeRadio).removeClass('hidden');
    // Klick-Action überwachen
    $('.single-radio input').on('click', function (cb) {
        var newID = cb.target;
        var newRadio = '.' + $(newID).parent().parent().attr('id');
        $(activeRadio).addClass('hidden');
        $(newRadio).removeClass('hidden').css('opacity', '0').animate({opacity: 1}, 2000);
    });
    // Input Feld als Selector hinzufügen
    $('#preise tr:first-child').after('<input type="checkbox" class="selector" />  <label>Alles Auswählen</label>');

    // Bei Klick auf Selector alle Inputs auswählen
    $('.selector').click(function () {
        var next = $(this).nextAll('input');
        console.log(next);
    });


});