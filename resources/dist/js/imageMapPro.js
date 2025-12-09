(function ($) {

    $.fn.select_unit = function (unit) {
        var $this = $(this);
        choosen_unit($this,unit);
    };

    function choosen_unit($this,unit){
        var selected_unit = $this.data('selected_unit');
        var selected_color = $this.data('selected_color');
        if(selected_unit){
            $('[data-title="' + selected_unit + '"]').attr('style', '');
        }
        selected_unit = unit;
        $this.data('selected_unit', unit);
        if("svg" == $('[data-title="' + unit + '"]').prop("tagName")){
            $('[data-title="' + unit + '"]').attr('style', 'fill: '+selected_color+' !important;');
        }
        else{
            $('[data-title="' + unit + '"]').attr('style', 'background: '+selected_color+' !important;');
        }
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

    $.fn.imageMapPro = function (options = {}) {
        var $this = $(this);
        function get_unit(unit){
            $.ajax({
                url: 'imagemappro/'+options.id+'/unit',
                method: 'GET',
                dataType: 'json',
                data: {unit: unit},
                success: function (response) {
                    choosen_unit($this,unit);
                    $(document).trigger('selected-unit', [{ unit: response }]);
                }
            });
        } 

        ImageMapPro.subscribe((action) =>{
            var selected_unit = $(this).data('selected_unit');
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
        var $this = $(this);
        $.ajax({
            url: 'imagemappro/'+options.id+'/map',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                $this.data('selected_color', response.selected_color);
                ImageMapPro.init('#image-map-pro',response.map);
            }
        });
    };
})(jQuery);