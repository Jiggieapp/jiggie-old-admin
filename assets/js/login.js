jQuery(function ($) {
    'use strict';   
   
    $('#loginForm').find('#email').focus();
    $('#loginForm').validate({
    	 
        highlight: function (element) {
            jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
        },
        success: function (element) {
            jQuery(element).closest('.form-group').removeClass('has-error');
            $("#validateForm").submit();
        },
       
        errorElement: 'span',
        errorClass: 'help-block jq-validate-error',
        rules: {
            cpassword : {
                equalTo : "#password"
            }
        },
        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else if (element.prop('type') === 'checkbox') {
                error.appendTo(element.closest('.checkbox').parent());
            } else if (element.prop('type') === 'radio') {
                error.appendTo(element.closest('.radio').parent());
            }
            else {
                error.insertAfter(element);
            }
        }
    });
    $("#FPForm").validate({
	    highlight: function (element) {
	        jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
	    },
	    success: function (element) {
	        jQuery(element).closest('.form-group').removeClass('has-error');
	        $("#validateForm").submit();
	    },
	
	    errorElement: 'span',
	    errorClass: 'help-block jq-validate-error',
	    rules: {
	        
	        fp_mail: {
	        	required: true,			
				remote: {				
					url: base_url+'admin/home/check_email_validate/'+Math.random(),
					type: "post",				
				}
			}
	    },
	    messages:
	     {
	         fp_mail:
	         {           
	            remote: jQuery.validator.format("Please enter a valid email address")
	         },
	         
	     },
	    submitHandler: function(form) {
	     	$.ajax({
            type: "POST",
            url: base_url+'admin/home/forget_password/'+Math.random(),
            data	: {
                fp_mail:$("#fp_mail").val(),
                
            },

            success: function(msg) {
            	if(msg==true){
            		$('#fbofrm').slideUp( 4300 ).delay( 800 ).fadeOut( 400 );
            		 $("#fpmfg").html('A new password has been sent to your e-mail address');
            	}else{
            		$("#fpmfg").css( "color",'red' )
            		$("#fpmfg").html('Error in processing your request');
            	}
               
            }
        });
	    }
	});
	
	$( "#fplink" ).on( "click", function(e) { 
		e.preventDefault();
		 $("#fpmfg").html('');
		 $('#fbofrm').show();
	});
});
