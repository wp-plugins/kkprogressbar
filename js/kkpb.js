(function(a){
    a.widget("ui.slider",a.extend({},a.ui.mouse,{
        _init:function(){
            var b=this,c=this.options;
            this._keySliding=false;
            this._handleIndex=null;
            this._detectOrientation();
            this._mouseInit();
            this.element.addClass("ui-slider ui-slider-"+this.orientation+" ui-widget ui-widget-content ui-corner-all");
            this.range=a([]);
            if(c.range){
                if(c.range===true){
                    this.range=a("<div></div>");
                    if(!c.values){
                        c.values=[this._valueMin(),this._valueMin()]
                    }
                    if(c.values.length&&c.values.length!=2){
                        c.values=[c.values[0],c.values[0]]
                    }
                }else{
                    this.range=a("<div></div>")
                }
                this.range.appendTo(this.element).addClass("ui-slider-range");
                if(c.range=="min"||c.range=="max"){
                    this.range.addClass("ui-slider-range-"+c.range)
                }
                this.range.addClass("ui-widget-header")
            }
            if(a(".ui-slider-handle",this.element).length==0){
                a('<a href="#"></a>').appendTo(this.element).addClass("ui-slider-handle")
            }
            if(c.values&&c.values.length){
                while(a(".ui-slider-handle",this.element).length<c.values.length){
                    a('<a href="#"></a>').appendTo(this.element).addClass("ui-slider-handle")
                }
            }
            this.handles=a(".ui-slider-handle",this.element).addClass("ui-state-default ui-corner-all");
            this.handle=this.handles.eq(0);
            this.handles.add(this.range).filter("a").click(function(d){
                d.preventDefault()
            }).hover(function(){
                if(!c.disabled){
                    a(this).addClass("ui-state-hover")
                }
            },function(){
                a(this).removeClass("ui-state-hover")
            }).focus(function(){
                if(!c.disabled){
                    a(".ui-slider .ui-state-focus").removeClass("ui-state-focus");
                    a(this).addClass("ui-state-focus")
                }else{
                    a(this).blur()
                }
            }).blur(function(){
                a(this).removeClass("ui-state-focus")
            });
            this.handles.each(function(d){
                a(this).data("index.ui-slider-handle",d)
            });
            this.handles.keydown(function(i){
                var f=true;
                var e=a(this).data("index.ui-slider-handle");
                if(b.options.disabled){
                    return
                }
                switch(i.keyCode){
                    case a.ui.keyCode.HOME:case a.ui.keyCode.END:case a.ui.keyCode.UP:case a.ui.keyCode.RIGHT:case a.ui.keyCode.DOWN:case a.ui.keyCode.LEFT:
                        f=false;
                        if(!b._keySliding){
                            b._keySliding=true;
                            a(this).addClass("ui-state-active");
                            b._start(i,e)
                        }
                        break
                }
                var g,d,h=b._step();
                if(b.options.values&&b.options.values.length){
                    g=d=b.values(e)
                }else{
                    g=d=b.value()
                }
                switch(i.keyCode){
                    case a.ui.keyCode.HOME:
                        d=b._valueMin();
                        break;
                    case a.ui.keyCode.END:
                        d=b._valueMax();
                        break;
                    case a.ui.keyCode.UP:case a.ui.keyCode.RIGHT:
                        if(g==b._valueMax()){
                            return
                        }
                        d=g+h;
                        break;
                    case a.ui.keyCode.DOWN:case a.ui.keyCode.LEFT:
                        if(g==b._valueMin()){
                            return
                        }
                        d=g-h;
                        break
                }
                b._slide(i,e,d);
                return f
            }).keyup(function(e){
                var d=a(this).data("index.ui-slider-handle");
                if(b._keySliding){
                    b._stop(e,d);
                    b._change(e,d);
                    b._keySliding=false;
                    a(this).removeClass("ui-state-active")
                }
            });
            this._refreshValue()
        },
        destroy:function(){
            this.handles.remove();
            this.range.remove();
            this.element.removeClass("ui-slider ui-slider-horizontal ui-slider-vertical ui-slider-disabled ui-widget ui-widget-content ui-corner-all").removeData("slider").unbind(".slider");
            this._mouseDestroy()
        },
        _mouseCapture:function(d){
            var e=this.options;
            if(e.disabled){
                return false
            }
            this.elementSize={
                width:this.element.outerWidth(),
                height:this.element.outerHeight()
            };

            this.elementOffset=this.element.offset();
            var h={
                x:d.pageX,
                y:d.pageY
            };

            var j=this._normValueFromMouse(h);
            var c=this._valueMax()-this._valueMin()+1,f;
            var k=this,i;
            this.handles.each(function(l){
                var m=Math.abs(j-k.values(l));
                if(c>m){
                    c=m;
                    f=a(this);
                    i=l
                }
            });
            if(e.range==true&&this.values(1)==e.min){
                f=a(this.handles[++i])
            }
            this._start(d,i);
            k._handleIndex=i;
            f.addClass("ui-state-active").focus();
            var g=f.offset();
            var b=!a(d.target).parents().andSelf().is(".ui-slider-handle");
            this._clickOffset=b?{
                left:0,
                top:0
            }:{
                left:d.pageX-g.left-(f.width()/2),
                top:d.pageY-g.top-(f.height()/2)-(parseInt(f.css("borderTopWidth"),10)||0)-(parseInt(f.css("borderBottomWidth"),10)||0)+(parseInt(f.css("marginTop"),10)||0)
            };

            j=this._normValueFromMouse(h);
            this._slide(d,i,j);
            return true
        },
        _mouseStart:function(b){
            return true
        },
        _mouseDrag:function(d){
            var b={
                x:d.pageX,
                y:d.pageY
            };

            var c=this._normValueFromMouse(b);
            this._slide(d,this._handleIndex,c);
            return false
        },
        _mouseStop:function(b){
            this.handles.removeClass("ui-state-active");
            this._stop(b,this._handleIndex);
            this._change(b,this._handleIndex);
            this._handleIndex=null;
            this._clickOffset=null;
            return false
        },
        _detectOrientation:function(){
            this.orientation=this.options.orientation=="vertical"?"vertical":"horizontal"
        },
        _normValueFromMouse:function(d){
            var c,h;
            if("horizontal"==this.orientation){
                c=this.elementSize.width;
                h=d.x-this.elementOffset.left-(this._clickOffset?this._clickOffset.left:0)
            }else{
                c=this.elementSize.height;
                h=d.y-this.elementOffset.top-(this._clickOffset?this._clickOffset.top:0)
            }
            var f=(h/c);
            if(f>1){
                f=1
            }
            if(f<0){
                f=0
            }
            if("vertical"==this.orientation){
                f=1-f
            }
            var e=this._valueMax()-this._valueMin(),i=f*e,b=i%this.options.step,g=this._valueMin()+i-b;
            if(b>(this.options.step/2)){
                g+=this.options.step
            }
            return parseFloat(g.toFixed(5))
        },
        _start:function(d,c){
            var b={
                handle:this.handles[c],
                value:this.value()
            };

            if(this.options.values&&this.options.values.length){
                b.value=this.values(c);
                b.values=this.values()
            }
            this._trigger("start",d,b)
        },
        _slide:function(f,e,d){
            var g=this.handles[e];
            if(this.options.values&&this.options.values.length){
                var b=this.values(e?0:1);
                if((this.options.values.length==2&&this.options.range===true)&&((e==0&&d>b)||(e==1&&d<b))){
                    d=b
                }
                if(d!=this.values(e)){
                    var c=this.values();
                    c[e]=d;
                    var h=this._trigger("slide",f,{
                        handle:this.handles[e],
                        value:d,
                        values:c
                    });
                    var b=this.values(e?0:1);
                    if(h!==false){
                        this.values(e,d,(f.type=="mousedown"&&this.options.animate),true)
                    }
                }
            }else{
                if(d!=this.value()){
                    var h=this._trigger("slide",f,{
                        handle:this.handles[e],
                        value:d
                    });
                    if(h!==false){
                        this._setData("value",d,(f.type=="mousedown"&&this.options.animate))
                    }
                }
            }
        },
        _stop:function(d,c){
            var b={
                handle:this.handles[c],
                value:this.value()
            };

            if(this.options.values&&this.options.values.length){
                b.value=this.values(c);
                b.values=this.values()
            }
            this._trigger("stop",d,b)
        },
        _change:function(d,c){
            var b={
                handle:this.handles[c],
                value:this.value()
            };

            if(this.options.values&&this.options.values.length){
                b.value=this.values(c);
                b.values=this.values()
            }
            this._trigger("change",d,b)
        },
        value:function(b){
            if(arguments.length){
                this._setData("value",b);
                this._change(null,0)
            }
            return this._value()
        },
        values:function(b,e,c,d){
            if(arguments.length>1){
                this.options.values[b]=e;
                this._refreshValue(c);
                if(!d){
                    this._change(null,b)
                }
            }
            if(arguments.length){
                if(this.options.values&&this.options.values.length){
                    return this._values(b)
                }else{
                    return this.value()
                }
            }else{
                return this._values()
            }
        },
        _setData:function(b,d,c){
            a.widget.prototype._setData.apply(this,arguments);
            switch(b){
                case"disabled":
                    if(d){
                        this.handles.filter(".ui-state-focus").blur();
                        this.handles.removeClass("ui-state-hover");
                        this.handles.attr("disabled","disabled")
                    }else{
                        this.handles.removeAttr("disabled")
                    }
                case"orientation":
                    this._detectOrientation();
                    this.element.removeClass("ui-slider-horizontal ui-slider-vertical").addClass("ui-slider-"+this.orientation);
                    this._refreshValue(c);
                    break;
                case"value":
                    this._refreshValue(c);
                    break
            }
        },
        _step:function(){
            var b=this.options.step;
            return b
        },
        _value:function(){
            var b=this.options.value;
            if(b<this._valueMin()){
                b=this._valueMin()
            }
            if(b>this._valueMax()){
                b=this._valueMax()
            }
            return b
        },
        _values:function(b){
            if(arguments.length){
                var c=this.options.values[b];
                if(c<this._valueMin()){
                    c=this._valueMin()
                }
                if(c>this._valueMax()){
                    c=this._valueMax()
                }
                return c
            }else{
                return this.options.values
            }
        },
        _valueMin:function(){
            var b=this.options.min;
            return b
        },
        _valueMax:function(){
            var b=this.options.max;
            return b
        },
        _refreshValue:function(c){
            var f=this.options.range,d=this.options,l=this;
            if(this.options.values&&this.options.values.length){
                var i,h;
                this.handles.each(function(p,n){
                    var o=(l.values(p)-l._valueMin())/(l._valueMax()-l._valueMin())*100;
                    var m={};

                    m[l.orientation=="horizontal"?"left":"bottom"]=o+"%";
                    a(this).stop(1,1)[c?"animate":"css"](m,d.animate);
                    if(l.options.range===true){
                        if(l.orientation=="horizontal"){
                            (p==0)&&l.range.stop(1,1)[c?"animate":"css"]({
                                left:o+"%"
                            },d.animate);
                            (p==1)&&l.range[c?"animate":"css"]({
                                width:(o-lastValPercent)+"%"
                            },{
                                queue:false,
                                duration:d.animate
                            })
                        }else{
                            (p==0)&&l.range.stop(1,1)[c?"animate":"css"]({
                                bottom:(o)+"%"
                            },d.animate);
                            (p==1)&&l.range[c?"animate":"css"]({
                                height:(o-lastValPercent)+"%"
                            },{
                                queue:false,
                                duration:d.animate
                            })
                        }
                    }
                    lastValPercent=o
                })
            }else{
                var j=this.value(),g=this._valueMin(),k=this._valueMax(),e=k!=g?(j-g)/(k-g)*100:0;
                var b={};

                b[l.orientation=="horizontal"?"left":"bottom"]=e+"%";
                this.handle.stop(1,1)[c?"animate":"css"](b,d.animate);
                (f=="min")&&(this.orientation=="horizontal")&&this.range.stop(1,1)[c?"animate":"css"]({
                    width:e+"%"
                },d.animate);
                (f=="max")&&(this.orientation=="horizontal")&&this.range[c?"animate":"css"]({
                    width:(100-e)+"%"
                },{
                    queue:false,
                    duration:d.animate
                });
                (f=="min")&&(this.orientation=="vertical")&&this.range.stop(1,1)[c?"animate":"css"]({
                    height:e+"%"
                },d.animate);
                (f=="max")&&(this.orientation=="vertical")&&this.range[c?"animate":"css"]({
                    height:(100-e)+"%"
                },{
                    queue:false,
                    duration:d.animate
                })
            }
        }
    }));
    a.extend(a.ui.slider,{
        getter:"value values",
        version:"1.7.3",
        eventPrefix:"slide",
        defaults:{
            animate:false,
            delay:0,
            distance:0,
            max:100,
            min:0,
            orientation:"horizontal",
            range:false,
            step:1,
            value:0,
            values:null
        }
    })
})(jQuery);
;

function kkpbSaveSettingsBar(){

    var kol_aktywny = jQuery('#kkpb-kolor-aktywny').val();
    var kol_nieaktywny = jQuery('#kkpb-kolor-nieaktywny').val();
    var textura = jQuery('#kkpb-cien').val();
    if(textura == 'on'){
        textura = '1';
    }else{
        textura = '0';
    }
    var wiadomosc = "kol_aktywny="+kol_aktywny+"&kol_nieaktywny="+kol_nieaktywny+"&textura="+textura;

    jQuery.ajax({
        url: "../wp-content/plugins/kkprogressbar/skryptyphp/SaveBarSettings.php",
        data: wiadomosc,
        type: "POST",
        beforeSend: function(){
            jQuery('#save-loading').show();
        },
        success: function(html){
            jQuery('#save-loading').hide();
            jQuery('#info').html(html);
        }
    });

}

function addProgressBarDiv(){

    jQuery('#kkpb-edit').fadeOut("fast");
    jQuery('#kkpb-add').fadeIn("fast",function(){
        jQuery("#slider").slider({
            value:0,
            min: 0,
            max: 100,
            step: 1,
            slide: function(event, ui) {
                jQuery("#kkpb-procent").val(ui.value);
                var procent = jQuery('#kkpb-procent').val();

                if(procent >= 0 && procent <= 100){
                    jQuery('#kkpb-rama').html('<div style="height:20px; width:'+procent+'%; background:#ccc; border-radius:6px;"></div>');
                }else{
                    procent = 0;
                }
            }

        });

    });
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

        jQuery("#slider-edit").slider({
            value:procent,
            min: 0,
            max: 100,
            step: 1,
            slide: function(event, ui) {
                jQuery("#kkpb-procent-edit").val(ui.value);
                var procent = jQuery('#kkpb-procent-edit').val();

                if(procent >= 0 && procent <= 100){
                    jQuery('#kkpb-rama-edit').html('<div style="height:20px; width:'+procent+'%; background:#ccc; border-radius:6px;"></div>');
                }else{
                    procent = 0;
                }
            }

        });

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
                jQuery('#kkpb-status-'+id).attr({
                    'src':'../wp-content/plugins/kkprogressbar/images/nieaktywny.png'
                });
            }else if(html == 1){
                jQuery('#kkpb-status-'+id).attr({
                    'src':'../wp-content/plugins/kkprogressbar/images/aktywny.png'
                });
            }else if(html == 2){
                jQuery('#kkpb-status-'+id).attr({
                    'src':'../wp-content/plugins/kkprogressbar/images/wstrzymany.png'
                });
            }else{
                jQuery('#info').html('<div style="background: #ffd9d9; margin:20px; padding: 10px; border-top: 1px #bb0000 solid; border-bottom: 1px #bb0000 solid;">BŁĄD: Status nie został zmieniony.</div>');
                    
            }
        }
    });

}

jQuery(document).ready(function(){
    
    jQuery('#kkpb-procent').change(function(){
        var procent = jQuery('#kkpb-procent').val();

        if(procent >= 0 && procent <= 100){
            jQuery('#kkpb-rama').html('<div style="height:20px; width:'+procent+'%; background:#ccc; border-radius:6px;"></div>');
        }else{
            procent = 0;
        }

    });

    jQuery('#kkpb-procent-edit').change(function(){
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