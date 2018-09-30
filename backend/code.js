$(".visual-form-builder").submit(function () {

    var action = $(this).attr('action');
    var form = $(this);
    var successText = $('#xs_form_success').html();

    if (form.valid() == true) {
        var arr=form.serializeArray();
        var fd=new FormData();
        fd.append('send','1');
        fd.append('servicio', arr[1].value);
        fd.append('fecha', arr[2].value);
        fd.append('hora', arr[3].value);
        fd.append('nombre', arr[4].value+' '+arr[5].value);
        fd.append('direccion', arr[6].value);
        fd.append('ciudad', arr[7].value);
        fd.append('numero', arr[8].value);
        fd.append('correo', arr[9].value);
        fd.append('obs', arr[10].value);
        fd.append('news', arr[11].value);
        

        form.find('.vfb-submit').val('Espere...');
        $.ajax({
            type: "POST",
            url: '/wp-content/themes/wellnesscenter/assets/js/request.php',
            processData: false,
            contentType: false,
            cache: false,
            enctype: 'multipart/form-data',
            data: fd,
            success: function (data) {
                console.log(data);
                $('#appointmentModal').find('.modal-body').html(successText);
            }
        });

        return false;
    }
});