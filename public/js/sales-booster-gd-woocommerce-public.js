(function( $ ) {
	'use strict';

	$(document).ready(function(){
        $(document).on("click touchstart", ".close-sbgd", function(){
            $(this).parent().remove();
            return false;
        });
        
        function running_ajax(){
            $.ajax({
                type: 'POST',
                url: SBGDajax.ajaxurl,
                data: {
                    action: 'gd_run_popup_script'
                },
                success: function( data ) {
                    if(data!=0){
                    if (!$.trim(data)){   
                        console.log("What follows is blank: " + data);
                    }
                    else{   
                        $("body").append(data);
                        
                        setTimeout(function(){
                            $(".pop-div").removeClass("animate__backInLeft").addClass("animate__backOutLeft");
                        }, 7000);
                        setTimeout(function(){
                            $(".pop-div").remove();
                        }, 9000);
                        
                    }
                    }
                }
            });
        }

        
        window.setInterval(function(){
            if(curStatus=='Active')
            running_ajax();
        }, 10000);
        
        
        setTimeout(function(){
            
            $.ajax({
                type: 'POST',
                url: SBGDajax.ajaxurl,
                data: {
                    action: 'sales_booster_add_ip'
                },
                success: function( data ) {
                    //console.log("Success check DB! "+data);
                }
            });
            
        }, 500);
        setTimeout(function(){
            
            var fp = new Fingerprint({
              canvas: true,
              ie_activex: true,
              screen_resolution: true
            });
            
            var uid = fp.get();
            
            var d = $.fn.deviceDetector;
            
            $.ajax({
                type: 'POST',
                url: SBGDajax.ajaxurl,
                data: {
                    action: 'sales_booster_add_device',
                    deVices: d.getOsName(),
                    broWser: d.getBrowserName(),
                    broWserVer: fingerprint_browser(),                    
                    broWUid: uid,
                    onLine: 1
                },
                success: function( data ) {
                    //console.log("Success! device DB"+data);
                }
            });
            
            
        }, 3000);
    });

})( jQuery );
