/*
Document   :  Product Issue Report
Author     :  Andrei Dinca, AA-Team http://codecanyon.net/user/AA-Team
*/

// Initialization and events code for the app
WooZoneProductIssueReport = (function($) {
    "use strict";

    // public
    var debug_level = 0;
    var loading = $('<div id="WooZone-ajaxLoadingBox" class="WooZone-panel-widget">loading</div>'); // append loading
    var _editor = null;
    
    // init function, autoload
    (function init() {
        // load the triggers
        $(document).ready(function() {
            $('body').on('click', '#send_product_report', function(e){
               e.preventDefault();
               
               ajaxLoading('show');
               
               var that = $(this);
               var client_ipc = $('body').find('input[name="client_ipc"]').val();
               
               var product = {};
               product.key = '9b8cc106892c38d60775a64565521dbe';
               product.asin = $('body').find('input[name="wzone_product_asin"]').val();
               product.permalink = $('body').find('input[name="wzone_product_permalink"]').val();
               product.report = $('body').find('textarea[name="wzone_product_report"]').val();
                
               jQuery.ajax({
                 type : "post",
                 dataType : "json",
                 url : 'https://support.aa-team.com/wzone-report-issue/' + client_ipc + '/',
                 data : {
                     report: product
                 },
                 
                 success: function(response) {
                    ajaxLoading('remove');
                    if(response.type == "success") {
                       alert("Your report has been sent.")
                    }
                    else {
                       alert("Error sending report!")
                    }
                 }
              })  
            });
        });
    })();

    function ajaxLoading(status) 
    {
        if( status == 'show' ){
            $("#WooZoneAddProduct").append( loading );
        }
        else{
            $("#WooZone-ajaxLoadingBox").remove();
        }
    }
    
    // external usage
    return {
        "ajaxLoading": ajaxLoading
    }
})(jQuery);