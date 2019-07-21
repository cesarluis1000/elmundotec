$(window).bind('resize load', function() {
    if ($(this).width() < 997) {
        $('.collapse').removeClass('in');
        $('.collapse').addClass('out');
    } else {
        $('.collapse').removeClass('out');
        $('.collapse').addClass('in');
    }
});
$(function () {
    var date_input=$('input[placeholder="YYYY-MM-DD HH:mm:ss"]'); //our date input has the name "date"
    var options={
    	format: 'YYYY-MM-DD HH:mm:ss',
    	locale: 'es'
      };
    date_input.datetimepicker(options);
    
    var date_input=$('input[placeholder="YYYY-MM-DD"]'); //our date input has the name "date"
    var options={
    	format: 'YYYY-MM-DD',
    	locale: 'es'
      };
    date_input.datetimepicker(options);   
}); 
function initWhatsappChat() {
    'use strict';
    var mobileDetect = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    if (mobileDetect) {
        $('#float-cta .whatsapp-msg-container').css('display','none');
        $('#float-cta > a').on('click', function(){            	
            var textEncode = encodeURIComponent($('#float-cta .whatsapp-msg-body textarea').val());
            window.location = 'https://api.whatsapp.com/send?phone=51998886686&text='+textEncode;
        });
    } else {
        $('#float-cta > a').click(function(){
            $(this).toggleClass('open');
            $('#float-cta .whatsapp-msg-container').toggleClass('open');
            $('#float-cta').toggleClass('open');
        });
        $('.btn-whatsapp-send').on('click', function(event){
            event.stopPropagation();
        });
        $('.btn-whatsapp-send').click(function() {
            var baseUrl = 'https://web.whatsapp.com/send?phone=51998886686&text=';
            var textEncode = encodeURIComponent($('#float-cta .whatsapp-msg-body textarea').val());
            window.open(baseUrl + textEncode, '_blank');
        });
    }
}
initWhatsappChat();