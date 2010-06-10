function addProgressBarDiv(){

    jQuery('#kkpb-edit').fadeOut("fast");
    jQuery('#kkpb-add').fadeIn("fast");

}

function kkpbCloseDiv(id){
    jQuery('#'+id).fadeOut("fast");
}

function kkpbSaveProgress(){
    var error = 0;
    var typ_projektu = jQuery('#kkpb-typ-projektu').val();

    if(typ_projektu == 0){
        error = 1;
    }else if(typ_projektu == 1){
        var projekt = jQuery('#kkpb-nazwa-projektu-post').val();
    }else if(typ_projektu == 2){
        var projekt = jQuery('#kkpb-nazwa-projektu').val();
    }

    var opis = jQuery('#kkpb-opis').val();
    var procent = jQuery('#kkpb-procent').val();

    if(error == 0){
        var wiadomosc = "typ_projektu="+typ_projektu+"&projekt="+projekt+"&opis="+opis+"&procent="+procent;

        jQuery.ajax({
            url: "../wp-content/plugins/kkprogressbar/skryptyphp/SaveBar.php?u=0",
            data: wiadomosc,
            type: "POST",
            beforeSend: function(){
                jQuery('#save-loading').show();
            },
            success: function(html){
                jQuery('#save-loading').hide();
                jQuery('#info').html(html);
                setTimeout('window.location.reload()',2000);
            }
        });
    }
}

function kkpbSaveEditProgress(){

    var error = 0;
    var typ_projektu = jQuery('#kkpb-typ-projektu-edit').val();
    var idprogress = jQuery('#kkpb-idprogress').val();

    if(typ_projektu == 0){
        error = 1;
    }else if(typ_projektu == 1){
        var projekt = jQuery('#kkpb-nazwa-projektu-post-edit').val();
    }else if(typ_projektu == 2){
        var projekt = jQuery('#kkpb-nazwa-projektu-edit').val();
    }

    var opis = jQuery('#kkpb-opis-edit').val();
    var procent = jQuery('#kkpb-procent-edit').val();

    if(error == 0){
        var wiadomosc = "typ_projektu="+typ_projektu+"&projekt="+projekt+"&opis="+opis+"&procent="+procent+"&idprogress="+idprogress;

        jQuery.ajax({
            url: "../wp-content/plugins/kkprogressbar/skryptyphp/SaveBar.php?u=1",
            data: wiadomosc,
            type: "POST",
            beforeSend: function(){
                jQuery('#save-loading-edit').show();
            },
            success: function(html){
                jQuery('#save-loading-edit').hide();
                jQuery('#info').html(html);
                setTimeout('window.location.reload()',2000);
            }
        });
    }

}

function editKKPB(id,nazwa,opis,procent,typ,status,idpost){

    for(var i=0;i<jQuery('#kkpb-typ-projektu-edit>option').length;i++){

        if(jQuery('#kkpb-typ-projektu-edit>option').get(i).value == typ){
            jQuery('#kkpb-typ-projektu-edit>option').get(i).selected = true;

            if(typ == 0){
                jQuery('#kkpb-projekt-a').hide();
                jQuery('#kkpb-projekt-b').hide();
            }else if(typ == 1){

                jQuery.ajax({
                    url: "../wp-content/plugins/kkprogressbar/skryptyphp/AddBar.php?e=1&id="+idpost,
                    type: "POST",
                    beforeSend: function(){
                        jQuery('#add-loading-edit').show();
                    },
                    success: function(html){

                        jQuery('#add-loading-edit').hide();
                        jQuery('#kkpb-projekt-a-edit').hide();
                        jQuery('#kkpb-projekt-b-edit').show();
                        jQuery('#kkpb-projekt-b-wew-edit').html(html);
                        setTimeout('window.location.reload()',2000);
                    }
                });

            }else if(typ == 2){
                jQuery('#kkpb-projekt-b-edit').hide();
                jQuery('#kkpb-projekt-a-edit').show();
                jQuery('#kkpb-projekt-a-wew-edit').html('<input type="text" id="kkpb-nazwa-projektu-edit" value="'+nazwa+'" />');
            }

        }

    }
    jQuery('#kkpb-add').fadeOut("fast");
    jQuery('#kkpb-edit').fadeIn("fast",function(){
        //jQuery('#id_e').val(id);
        //jQuery('#nazwa_e').val(nazwa);
        jQuery('#kkpb-opis-edit').val(opis);
        jQuery('#kkpb-procent-edit').val(procent);
        jQuery('#kkpb-idprogress').val(id);
    });

}

function delKKPB(id){

    if(confirm("Are you sure?")){
        jQuery.ajax({
            url: "../wp-content/plugins/kkprogressbar/skryptyphp/DelBar.php",
            data: "id="+id,
            type: "POST",
            beforeSend: function(){
            //jQuery('#add-loading-edit').show();
            },
            success: function(html){

                jQuery('#info').html(html);
                setTimeout('window.location.reload()',2000);
            }
        });
    }
}

function zmienStatusKKPB(url,id){

    var wiadomosc = "id=" + id;

        jQuery.ajax({
            url: url,
            data: wiadomosc,
            type: "POST",
            beforeSend: function(){
                jQuery('#loader-status-'+id).show();
            },
            success: function(html){
                jQuery('#loader-status-'+id).hide();
                if(html == 0){
                    jQuery('#kkpb-status-'+id).attr({'src':'../wp-content/plugins/kkprogressbar/images/nieaktywny.png'});
                }else if(html == 1){
                    jQuery('#kkpb-status-'+id).attr({'src':'../wp-content/plugins/kkprogressbar/images/aktywny.png'});
                }else if(html == 2){
                    jQuery('#kkpb-status-'+id).attr({'src':'../wp-content/plugins/kkprogressbar/images/wstrzymany.png'});
                }else{
                    jQuery('#info').html('<div style="background: #ffd9d9; margin:20px; padding: 10px; border-top: 1px #bb0000 solid; border-bottom: 1px #bb0000 solid;">BŁĄD: Status nie został zmieniony.</div>');
                    
                }
            }
        });

}

jQuery(document).ready(function(){

    jQuery('#kkpb-procent').keyup(function(){
        var procent = jQuery('#kkpb-procent').val();

        if(procent >= 0 && procent <= 100){
            jQuery('#kkpb-rama').html('<div style="height:20px; width:'+procent+'%; background:#ccc; border-radius:6px;"></div>');
        }else{
            procent = 0;
        }

    });

    jQuery('#kkpb-procent-edit').keyup(function(){
        var procent = jQuery('#kkpb-procent-edit').val();

        if(procent >= 0 && procent <= 100){
            jQuery('#kkpb-rama-edit').html('<div style="height:20px; width:'+procent+'%; background:#ccc; border-radius:6px;"></div>');
        }else{
            procent = 0;
        }

    });

    jQuery('#kkpb-typ-projektu').change(function(){
        
        var typ = jQuery(this).val();

        if(typ == 0){
            jQuery('#kkpb-projekt-a').hide();
            jQuery('#kkpb-projekt-b').hide();
        }else if(typ == 1){

            jQuery.ajax({
                url: "../wp-content/plugins/kkprogressbar/skryptyphp/AddBar.php?e=0",
                type: "POST",
                beforeSend: function(){
                    jQuery('#add-loading').show();
                },
                success: function(html){

                    jQuery('#add-loading').hide();
                    jQuery('#kkpb-projekt-a').hide();
                    jQuery('#kkpb-projekt-b').show();
                    jQuery('#kkpb-projekt-b-wew').html(html);
                    
                }
            });

        }else if(typ == 2){
            jQuery('#kkpb-projekt-b').hide();
            jQuery('#kkpb-projekt-a').show();
            jQuery('#kkpb-projekt-a-wew').html('<input type="text" id="kkpb-nazwa-projektu" />');
        }

    });

    jQuery('#kkpb-typ-projektu-edit').change(function(){

        var typ = jQuery(this).val();

        if(typ == 0){
            jQuery('#kkpb-projekt-a-edit').hide();
            jQuery('#kkpb-projekt-b-edit').hide();
        }else if(typ == 1){

            jQuery.ajax({
                url: "../wp-content/plugins/kkprogressbar/skryptyphp/AddBar.php?e=1",
                type: "POST",
                beforeSend: function(){
                    jQuery('#add-loading-edit').show();
                },
                success: function(html){

                    jQuery('#add-loading-edit').hide();
                    jQuery('#kkpb-projekt-a-edit').hide();
                    jQuery('#kkpb-projekt-b-edit').show();
                    jQuery('#kkpb-projekt-b-wew-edit').html(html);

                }
            });

        }else if(typ == 2){
            jQuery('#kkpb-projekt-b-edit').hide();
            jQuery('#kkpb-projekt-a-edit').show();
            jQuery('#kkpb-projekt-a-wew-edit').html('<input type="text" id="kkpb-nazwa-projektu-edit" />');
        }

    });

});