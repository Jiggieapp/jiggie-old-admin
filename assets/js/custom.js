var date = new Date();
$('.datepicker_event').datepicker({
	changeMonth: true,
    changeYear: true,
    yearRange: date.getFullYear()+" : 2044",
    minDate: "+0d",
    onSelect: function (date) {
        $( ".datepicker_event" ).trigger( "blur" );
    }    
});
$('.datepicker').datepicker({
  changeMonth: true,
  changeYear: true,
  yearRange: "1960:"+(new Date).getFullYear(),
  maxDate: "+0d",
  onSelect: function (date) {
        $( ".datepicker" ).trigger( "blur" );
    }  
});

$('[data-rel=timepicker]').timepicker().on('hide.timepicker', function(e) {
    $( "[data-rel=timepicker]" ).trigger( "blur" );
     
});
$('[data-rel=eventtimepicker]').timepicker().on('hide.timepicker', function(e) {
    $( "[data-rel=timepicker]" ).trigger( "blur" );
     var localtime = new TimeShift.OriginalDate($('.event_range_start').val()+' '+$('#starttime').val() );
	 localendtime = localtime.setHours(localtime.getHours()+parseInt($( "#event_time" ).val()));			
     $('#endtime ').val(dateFormats(localendtime, "mmm dd,yyyy h:MM tt"));
	 $('#end_time ').val(dateFormats(localendtime, "mmm dd,yyyy h:MM tt")); 
});

var bestPictures = new Bloodhound({
  datumTokenizer: Bloodhound.tokenizers.obj.whitespace('email'),
  queryTokenizer: Bloodhound.tokenizers.whitespace,  
  remote: {
    url: base_url+'admin/hostings/searchuser/%QUERY',
    wildcard: '%QUERY'
  }
});

bestPictures.initialize();
 
/*$('input#uemail').typeahead(null, {
  name: 'email',
  displayKey: 'email',
  source: bestPictures.ttAdapter()
});
*/
$('input#uemail').typeahead(null, {
  name: 'email',
  display: 'email',
  source: bestPictures,
  templates: {
    empty: [
      '<div class="empty-message">',
        'No users match the current query',
      '</div>'
    ].join('\n'),
   
    suggestion: function(data) {
    	return '<span class="col-xs-12"><span class="col-xs-2">'+
    	'<img   alt="image"  src="'+base_url+'timthumb1.php?src='+encodeURIComponent(data.profile_image_url)+'&w=50&q=100&h=50" /></span>'+
    	'<span class="col-xs-10"><strong>' + data.first_name +' '+ data.last_name+'</strong><p>'+ data.email+'</p></span></span>';	   
	}
  }
});

$('input#uemail').on('typeahead:selected',function(evt,data){
	 
   $( "input#uemail" ).trigger( "blur" );    
   $( "input#user_fb_id" ).val(data.fb_id);
});

$(document).ready(function(){ 
	
	$(".file1").fileinput({'showUpload':false, 'allowedFileTypes':['image'],allowedFileExtensions:['jpg','png'],maxFileSize:2000});
	
    $.fn.editable.defaults.mode = 'inline';
    
    
    
   /* $(".edit_user").click(function(){
        $('#'+$(this).attr('id')).editable({
            type: 'text',
            pk: 1,
            url: base_url+'admin/user/'+'save'+'/'+$(".panel-body").attr("user_id"),
            title: 'Enter username'
        });
    });*/
    
   
    
    

    /*$( "#data-list" ).on( "click",'.get_modal', function() {    
        var url_link = base_url+'admin/chat/'+'conversation'+'/'+Math.random();
        var selected_id = this.id;
        var link = this;  
          
        $.ajax({
            type: "POST",
            url: url_link,
            data	: {
                u2:$(link).attr("user_2"),
                u1:$(link).attr("user_1"),
                u2name:$(link).attr("user_name_2"),
                u1name:$(link).attr("user_name_1"),
                },

            success: function(msg) {
                $(".modal-body").html(msg);
            }
        });
    });   
   */
    $( ".get_modal_block" ).on( "click", function() {    
        var url_link = base_url+'admin/blocklists/'+'conversation'+'/'+Math.random();
        var selected_id = this.id;
        var res = selected_id.split("_");  
        var id = res[1];
        
        $.ajax({
            type: "POST",
            url: url_link,
            data	: {
                u2:$(".conversation_"+id).attr("user_2"),
                u1:$(".conversation_"+id).attr("user_1"), 
                channel:$(".conversation_"+id).attr("channel_id")
                },

            success: function(msg) {
                $(".modal-body").html(msg);
            }
        });
    });
    
    /* Hostings HTML Elements */
    
    
       
    $("[name='gender'],[name='venue_status'],[name='verified_host'],[name='suspend_user'],[name='is_recurring'],[name='is_promoter'],[name='verified_table'],[name='hstatus']").bootstrapSwitch();
    
 
    
    
    
    $(".save_data_element").click(function(){
        var selected_id = this.id;
        var res = selected_id.split("_");  
        var id = res[1];
        
        var url_link = base_url+'admin/hostings/save/'+Math.random(); 
        
        if(id == "recurring" || id == "promoter" || id == "hstatus") {
            if($('#' + id).is(":checked")) {
                var data =  1; 
            } else {
                var data =  0; 
            }
        } else {
            var data =  $("#"+id).val(); 
        }
       
        
        if(id == "date")
            data =  $("#input_"+id).val(); 
        $.ajax({
            type: "POST",
            url: url_link,
            data	: {
                data : data, 
                hosting:$(".panel-body").attr("hosting_id"),
                label:id
            },

            success: function(msg) {
                    
                $("#view_"+id).show();
                $("#update_"+id).hide();
                   
                if(msg == "D") {
                    $("#data_error").text("Venue already booked");
                } else if(msg == "F") {
                    $("#data_error").text("Updated Failed");
                } else {
                    $("#p_"+id).html(msg);
                    $("#data_error").text("");
                }
            }
        });
    });
    /* Hostings detail edit HTML Element ends here */
    
    $(".icheck").click(function(){
        var url_link = base_url+'admin/user/'+'save_gender'+'/'+Math.random(); 
        var data =  $(this).val(); 
        if($(".panel-body").attr("user_id")!=null){
            $.ajax({
                type: "POST",
                url: url_link,
                data	: {
                    data : data, 
                    user:$(".panel-body").attr("user_id")
                    },

                success: function(msg) {
                //console.log(msg)
                }
            });
        }    
    });
    
    
    
    
    $("#email").blur(function(){    	
        if($("#email").val()!="") {
            var url_link = base_url+'admin/user/check_email/'+Math.random(); 
            var email = $(this).val(); 
            $.ajax({
                type: "POST",
                url: url_link,
                data	: {
                    email : email
                },
                 
                success: function(msg) {
                    var data = jQuery.parseJSON(msg);                   
                    if(data.status == 0) {
                        $("#email_div").append("<label for='email_error' class='error1'>"+'Email already exists</label>');
                        $("#email_div").find("span").text("");  
                        $("#email_verified").val(0);
                    }else if(data.status == 2) {
                        $("#email_div").append("<label for='email_error' class='error1'>"+'Invalid Email</label>');
                        $("#email_div").find("span").text("");  
                        $("#email_verified").val(0);
                    }	else {
                        $("#email_verified").val(1);
                        $('label[for=email_error]').remove("");
                    }
                }
            });
        }
        $('label[for=email_error]').remove("");
    });
    
    $("#name").blur(function(){
        
    });
    
    $("#admin_email").blur(function(){
        if($("#email").val()!="") {
            var url_link = base_url+'admin/adminuser/check_email/'+Math.random(); 
            var email = $(this).val(); 
            $.ajax({
                type: "POST",
                url: url_link,
                data	: {
                    email : email
                },
                 
                success: function(msg) {
                    var data = jQuery.parseJSON(msg);
                    if(data.status == 0) {
                        $("#email_div").append("<label for='email_error' class='error1'>"+'Email already exists</label>');
                        $("#email_div").find("span").text("");  
                        $("#email_verified").val(0);
                    }else if(data.status == 2) {
                        $("#email_div").append("<label for='email_error' class='error1'>"+'Invalid Email</label>');
                        $("#email_div").find("span").text("");  
                        $("#email_verified").val(0);
                    }	else {
                        $("#email_verified").val(1);
                        $('label[for=email_error]').remove("");
                    }
                }
            });
        }
        $('label[for=email_error]').remove("");
    });
    
    /*$("#venue_name_create").blur(function(){
        if($("#venue_name_create").val()!="") {
            var url_link = base_url+'admin/venue/check_venue/'+Math.random(); 
            var v_name = $(this).val(); 
            $.ajax({
                type: "POST",
                url: url_link,
                data	: {
                    name : v_name
                },
                 
                success: function(msg) {
                    var data = jQuery.parseJSON(msg);
                    if(data.status == 0) {
                        $("#venue_name_div").append("<label for='vname_error' class='error1'>"+'Venue already exists</label>');
                        $("#venue_name_div").find("span").text("");  
                        $("#email_verified").val(0);
                    }	else {
                        $("#email_verified").val(1);
                        $('label[for=vname_error]').remove("");
                    }
                }
            });
        }
        $('label[for=vname_error]').remove("");
    });
    */
    $(function() {
  
      $('input[type="checkbox"]').change(function(e) {
          var selected_module= ($(this).attr("id"));
          if($(this).is(':checked')) { 

              //  $(this).parent().parent().siblings('ul').find('input[type="checkbox"]').attr('checked', 'checked');
                $("#ul_"+selected_module).closest("ul").find("input[type=checkbox]").prop("checked",this.checked);
            } else {
                 $("#ul_"+selected_module).closest("ul").find("input[type=checkbox]").prop("checked",false);
            }
      });
    });
    
    //Admin User
    $("input[name='admin_suspend_user']")
    .on('switchChange.bootstrapSwitch',
        function(event, state) {
            var url_link = base_url+'admin/adminuser/'+'suspend_user'+'/'+Math.random();         

            var su = (state== true)?1:0; 
            if($(".panel-body").attr("adminuser_id")!=null){
                $.ajax({
                    type: "POST",
                    url: url_link,
                    data	: {
                        data : su,  
                        user:$(".panel-body").attr("adminuser_id")
                        },

                    success: function(msg) {

                    }
                });
            }     
    });
    
    $(".admin_edit_user").editable({
            type: 'text',
            pk: 1,
            url: base_url+'admin/adminuser/'+'save'+'/'+$(".panel-body").attr("adminuser_id"),
            title: 'Enter username'
    });
        
    $("[name='admin_suspend_user']").bootstrapSwitch();
});
$("#adminuser_det").validate({
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
    	password: {
            required: true,            
            minlength: 6
        },
        zip: {
            number: true
        },
        url: {
            url: true
        }
    },
    submitHandler: function(form) {
        form.submit();
    }
});


$("#loginForm").validate({
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
    	password: {
            required: true,            
            minlength: 6
        },
        zip: {
            number: true
        },
        url: {
            url: true
        }
    },
    submitHandler: function(form) {
        if($("#email_verified").val() == 0) {
            return false;
        } else {
            form.submit();
        }
    }
});
$("#VenueForm").validate({
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
    	password: {
            required: true,            
            minlength: 6
        },
        zip: {
        	required: true,
            number: true,
            maxlength:5
        },
        lat: {
            latlong: true
        },
        lng: {
            latlong: true
        }
    },
    submitHandler: function(form) {
       form.submit();
    }
});
$.validator.addMethod("latlong", function(value, element) {
return this.optional(element) || (value).match(/^(\-?\d+(\.\d+)?)$/);
}, "Please enter valid data");
$("#hosting-form").validate({
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
        zip: {
            number: true
        },
        url: {
            url: true
        },
         
        starttime: {
             
             totalCheck: ['starttime', 'start_date', 'event_time']
        },
        start_date: {
            
             totalCheck: ['starttime', 'start_date', 'event_time']
        },
        event_time: {
             
             totalCheck: ['starttime', 'start_date', 'event_time']
        },
        uemail: {
        	required: true,			
			remote: {				
				url: base_url+'admin/hostings/check_email_validate/'+Math.random(),
				type: "post",				
			}
		},
		
    },
    messages:
     {
         uemail:
         {           
            remote: jQuery.validator.format("Please enter a valid user email address")
         },
         
     },
    submitHandler: function(form) {
     	$("#email_verified").val(1);
        // not sure y this hidden field set here 
        if($("#email_verified").val() == 0) {
            return false;
        } else {
            form.submit();
        }
    }
});
//&& h_end<e_end
$.validator.addMethod("totalCheck", function(value, element,params) {
	 
	var h_strat = new TimeShift.OriginalDate($('.event_range_start').val()+' '+$('#starttime').val()); 
	var h_end = new TimeShift.OriginalDate($('#endtime').val());
	var e_start = new TimeShift.OriginalDate($('#eventstarttime').data('eventstarttime'));
	var e_end = new TimeShift.OriginalDate($('#eventendtime').data('eventendtime'));
	  console.log(e_start,h_strat)
	  console.log(e_start<= h_strat)
	if(e_start<= h_strat && h_end<=e_end )
		 return this.optional(element) || true;
	else
		 return this.optional(element) || false;

}, "Hosting time should be within the bounds of event start and end date time");

$("#event-form").validate({
   highlight: function (element) {
        jQuery(element).closest('.form-group').removeClass('has-success').addClass('has-error');
    },
    success: function (element) {
        jQuery(element).closest('.form-group').removeClass('has-error');
        //$("#validateForm").submit();
    },
    errorElement: 'span',
    errorClass: 'help-block jq-validate-error',
    rules: {
    	endtime: {
            required: true 
        } 
    },
    submitHandler: function(form) {
       form.submit();
        
    }
});
$("#ticket-form").validate({
    highlight: function(element) {
        $(element).closest('.form-group').addClass('has-error');
    },
    unhighlight: function(element) {
        $(element).closest('.form-group').removeClass('has-error');
    },
    errorElement: 'span',
    errorClass: 'help-block',
    errorPlacement: function(error, element) {
        if(element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        } else {
            error.insertAfter(element);
        }
    },
    rules: { 
    	quantity:{
        	number: true,
        },
    	guest:{
        	number: true,
        },
        price:{
        	number: true,
        },
        deposit:{
        	number: true,
        },
        add_guest:{
        	number: true,
        },
        admin_fee:{
        	number: true,
        	required:"#chk_adminfee:checked"
        },
        tax:{
        	number: true,
        	required:"#chk_tax:checked"
        },
        tip:{
        	number: true,
        	required:"#chk_tip:checked"
        },
        full_amt_box:{
        	required:"#chk_fullamt:checked"
        },
        matching_box:{
        	required:"#chk_matching:checked"
        },
        returns_box:{
        	required:"#chk_returns:checked"
        },        
        new_label:{
        	required:"#chk_cnf_label:checked"
        },
        confirmation:{
        	required:"#chk_cnf_label:checked"
        }
    },
    submitHandler: function(form) {
       form.submit();
    }
});
$.validator.methods.price = function (value, element) {
    return this.optional(element) || /^-?(?:\d+|\d{1,3}(?:[\s\.,]\d{3})+)(?:[\.,]\d+)?$/.test(value);
}
/*
$.validator.addMethod("vaildendtime", function(value, element) {

  return isValidGuid(value);

}, 'Please select a valid and non empty Guid value.');



var isValidGuid = function(value) {
 alert(value)

}
*/


function chatDesc() {
    /*$( ".get_modal" ).on( "click", function() {   
        var url_link = base_url+'admin/chat/'+'conversation'+'/'+Math.random();
        var selected_id = this.id;
        var res = selected_id.split("_");  
        var id = res[1];

        $.ajax({
            type: "POST",
            url: url_link,
            data	: {
                u2:$(".conversation_"+id).attr("user_2"),
                u1:$(".conversation_"+id).attr("user_1"), 
                channel:$(".conversation_"+id).attr("channel_id"), 
                flag: $(".conversation_"+id).attr("flag")
                },

            success: function(msg) {
                $(".modal-body").html(msg);
            }
        });
    });   
    $( ".get_modal_block" ).on( "click", function() {    
        var url_link = base_url+'admin/blocklists/'+'conversation'+'/'+Math.random();
        var selected_id = this.id;
        var res = selected_id.split("_");  
        var id = res[1];
        
        $.ajax({
            type: "POST",
            url: url_link,
            data	: {
                u2:$(".conversation_"+id).attr("user_2"),
                u1:$(".conversation_"+id).attr("user_1"), 
                channel:$(".conversation_"+id).attr("channel_id")
                },

            success: function(msg) {
                $(".modal-body").html(msg);
            }
        });
    });*/
}
