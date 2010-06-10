function addProgressBarDiv(){

    $('#kkpb-edit').fadeOut("fast");
    $('#kkpb-add').fadeIn("fast");

}

function kkpbCloseDiv(id){
    $('#'+id).fadeOut("fast");
}

function kkpbSaveProgress(){
    var error = 0;
    var typ_projektu = $('#kkpb-typ-projektu').val();

    if(typ_projektu == 0){
        error = 1;
    }else if(typ_projektu == 1){
        var projekt = $('#kkpb-nazwa-projektu-post').val();
    }else if(typ_projektu == 2){
        var projekt = $('#kkpb-nazwa-projektu').val();
    }

    var opis = $('#kkpb-opis').val();
    var procent = $('#kkpb-procent').val();

    if(error == 0){
        var wiadomosc = "typ_projektu="+typ_projektu+"&projekt="+projekt+"&opis="+opis+"&procent="+procent;

        $.ajax({
            url: "../wp-content/plugins/kkprogressbar/skryptyphp/SaveBar.php?u=0",
            data: wiadomosc,
            type: "POST",
            beforeSend: function(){
                $('#save-loading').show();
            },
            success: function(html){
                $('#save-loading').hide();
                $('#info').html(html);
                setTimeout('window.location.reload()',2000);
            }
        });
    }
}

function kkpbSaveEditProgress(){

    var error = 0;
    var typ_projektu = $('#kkpb-typ-projektu-edit').val();
    var idprogress = $('#kkpb-idprogress').val();

    if(typ_projektu == 0){
        error = 1;
    }else if(typ_projektu == 1){
        var projekt = $('#kkpb-nazwa-projektu-post-edit').val();
    }else if(typ_projektu == 2){
        var projekt = $('#kkpb-nazwa-projektu-edit').val();
    }

    var opis = $('#kkpb-opis-edit').val();
    var procent = $('#kkpb-procent-edit').val();

    if(error == 0){
        var wiadomosc = "typ_projektu="+typ_projektu+"&projekt="+projekt+"&opis="+opis+"&procent="+procent+"&idprogress="+idprogress;

        $.ajax({
            url: "../wp-content/plugins/kkprogressbar/skryptyphp/SaveBar.php?u=1",
            data: wiadomosc,
            type: "POST",
            beforeSend: function(){
                $('#save-loading-edit').show();
            },
            success: function(html){
                $('#save-loading-edit').hide();
                $('#info').html(html);
                setTimeout('window.location.reload()',2000);
            }
        });
    }

}

function editKKPB(id,nazwa,opis,procent,typ,status,idpost){

    for(var i=0;i<$('#kkpb-typ-projektu-edit>option').length;i++){

        if($('#kkpb-typ-projektu-edit>option').get(i).value == typ){
            $('#kkpb-typ-projektu-edit>option').get(i).selected = true;

            if(typ == 0){
                $('#kkpb-projekt-a').hide();
                $('#kkpb-projekt-b').hide();
            }else if(typ == 1){

                $.ajax({
                    url: "../wp-content/plugins/kkprogressbar/skryptyphp/AddBar.php?e=1&id="+idpost,
                    type: "POST",
                    beforeSend: function(){
                        $('#add-loading-edit').show();
                    },
                    success: function(html){

                        $('#add-loading-edit').hide();
                        $('#kkpb-projekt-a-edit').hide();
                        $('#kkpb-projekt-b-edit').show();
                        $('#kkpb-projekt-b-wew-edit').html(html);
                        setTimeout('window.location.reload()',2000);
                    }
                });

            }else if(typ == 2){
                $('#kkpb-projekt-b-edit').hide();
                $('#kkpb-projekt-a-edit').show();
                $('#kkpb-projekt-a-wew-edit').html('<input type="text" id="kkpb-nazwa-projektu-edit" value="'+nazwa+'" />');
            }

        }

    }
    $('#kkpb-edit').fadeIn("fast",function(){
        //$('#id_e').val(id);
        //$('#nazwa_e').val(nazwa);
        $('#kkpb-opis-edit').val(opis);
        $('#kkpb-procent-edit').val(procent);
        $('#kkpb-idprogress').val(id);
    });

}

function delKKPB(id){

    if(confirm("Are you sure?")){
        $.ajax({
            url: "../wp-content/plugins/kkprogressbar/skryptyphp/DelBar.php",
            data: "id="+id,
            type: "POST",
            beforeSend: function(){
            //$('#add-loading-edit').show();
            },
            success: function(html){

                $('#info').html(html);
                setTimeout('window.location.reload()',2000);
            }
        });
    }
}

function zmienStatusKKPB(url,id){

    var wiadomosc = "id=" + id;

        $.ajax({
            url: url,
            data: wiadomosc,
            type: "POST",
            beforeSend: function(){
                $('#loader-status-'+id).show();
            },
            success: function(html){
                $('#loader-status-'+id).hide();
                if(html == 0){
                    $('#kkpb-status-'+id).attr({'src':'../wp-content/plugins/kkprogressbar/images/nieaktywny.png'});
                }else if(html == 1){
                    $('#kkpb-status-'+id).attr({'src':'../wp-content/plugins/kkprogressbar/images/aktywny.png'});
                }else if(html == 2){
                    $('#kkpb-status-'+id).attr({'src':'../wp-content/plugins/kkprogressbar/images/wstrzymany.png'});
                }else{
                    $('#info').html('<div style="background: #ffd9d9; margin:20px; padding: 10px; border-top: 1px #bb0000 solid; border-bottom: 1px #bb0000 solid;">BŁĄD: Status nie został zmieniony.</div>');
                    
                }
            }
        });

}

$(document).ready(function(){

    $('#kkpb-procent').keyup(function(){
        var procent = $('#kkpb-procent').val();

        if(procent >= 0 && procent <= 100){
            $('#kkpb-rama').html('<div style="height:20px; width:'+procent+'%; background:#ccc; border-radius:6px;"></div>');
        }else{
            procent = 0;
        }

    });

    $('#kkpb-procent-edit').keyup(function(){
        var procent = $('#kkpb-procent-edit').val();

        if(procent >= 0 && procent <= 100){
            $('#kkpb-rama-edit').html('<div style="height:20px; width:'+procent+'%; background:#ccc; border-radius:6px;"></div>');
        }else{
            procent = 0;
        }

    });

    $('#kkpb-typ-projektu').change(function(){
        
        var typ = $(this).val();

        if(typ == 0){
            $('#kkpb-projekt-a').hide();
            $('#kkpb-projekt-b').hide();
        }else if(typ == 1){

            $.ajax({
                url: "../wp-content/plugins/kkprogressbar/skryptyphp/AddBar.php?e=0",
                type: "POST",
                beforeSend: function(){
                    $('#add-loading').show();
                },
                success: function(html){

                    $('#add-loading').hide();
                    $('#kkpb-projekt-a').hide();
                    $('#kkpb-projekt-b').show();
                    $('#kkpb-projekt-b-wew').html(html);
                    
                }
            });

        }else if(typ == 2){
            $('#kkpb-projekt-b').hide();
            $('#kkpb-projekt-a').show();
            $('#kkpb-projekt-a-wew').html('<input type="text" id="kkpb-nazwa-projektu" />');
        }

    });

    $('#kkpb-typ-projektu-edit').change(function(){

        var typ = $(this).val();

        if(typ == 0){
            $('#kkpb-projekt-a-edit').hide();
            $('#kkpb-projekt-b-edit').hide();
        }else if(typ == 1){

            $.ajax({
                url: "../wp-content/plugins/kkprogressbar/skryptyphp/AddBar.php?e=1",
                type: "POST",
                beforeSend: function(){
                    $('#add-loading-edit').show();
                },
                success: function(html){

                    $('#add-loading-edit').hide();
                    $('#kkpb-projekt-a-edit').hide();
                    $('#kkpb-projekt-b-edit').show();
                    $('#kkpb-projekt-b-wew-edit').html(html);

                }
            });

        }else if(typ == 2){
            $('#kkpb-projekt-b-edit').hide();
            $('#kkpb-projekt-a-edit').show();
            $('#kkpb-projekt-a-wew-edit').html('<input type="text" id="kkpb-nazwa-projektu-edit" />');
        }

    });

});