(function ($) {
    $.fn.imageMapPro = function (options = {}) {
        var selected_unit = null;
        var selected_color = null;
        function get_unit(unit){
            
            $.ajax({
                url: 'imagemappro/'+options.id+'/unit',
                method: 'GET',
                dataType: 'json',
                data: {unit: unit},
                success: function (response) {
                    if(selected_unit){
                        $('[data-title="' + selected_unit + '"]').attr('style', '');
                    }
                    selected_unit = unit;
                    if("svg" == $('[data-title="' + unit + '"]').prop("tagName")){
                        $('[data-title="' + unit + '"]').attr('style', 'fill: '+selected_color+' !important;');
                    }
                    else{
                        $('[data-title="' + unit + '"]').attr('style', 'background: '+selected_color+' !important;');
                    }
                    $(document).trigger('selected-unit', [{ unit: response }]);
                    $('#unit-info').show();
                    $('html, body').animate({
                      scrollTop: $("#unit-info").offset().top
                    }, 800);
                    setTimeout(function(){
                        const event = new UIEvent('resize', {
                          bubbles: true,
                          cancelable: false,
                          view: window,
                          detail: 0
                        });
                        window.dispatchEvent(event);
                    }, 166);
                }
            });
        } 

        ImageMapPro.subscribe((action) =>{
            if(action.type == "mapInit"){
                if(ImageMapPro.isMobile()){
                    document.getElementById("image-map-pro").addEventListener("click", function(event) {
                        get_unit(event.target.getAttribute("data-title"));
                    });
                }
                setTimeout(function(){
                    const event = new UIEvent('resize', {
                      bubbles: true,
                      cancelable: false,
                      view: window,
                      detail: 0
                    });
                    window.dispatchEvent(event);
                }, 1000);
            }
            if(action.type == "objectClick"){
                get_unit(action.payload.object);
            }
            if(ImageMapPro.isMobile() && action.type == "tooltipShow"){
                ImageMapPro.hideTooltip(action.payload.map, action.payload.object);
            }
            if(action.type == "artboardChange"){
                $('[data-title="' + selected_unit + '"]').attr('style', 'background: '+selected_color+' !important;');
            }
        });

        $.ajax({
            url: 'imagemappro/'+options.id+'/map',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                selected_color = response.selected_color;
                ImageMapPro.init('#image-map-pro',response.map);
            }
        });
    };
})(jQuery);