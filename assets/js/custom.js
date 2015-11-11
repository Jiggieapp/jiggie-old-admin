var date = new Date();
$('.datepicker_event').datepicker({
    startDate: date
});
$('.datepicker').datepicker();

$('#time').timepicker({
    minuteStep: 1,
    template: 'modal',
    appendWidgetTo: 'body',
    showSeconds: true,
    showMeridian: false,
    defaultTime: false
});

$('input#name').typeahead({
    name: 'email',
    remote : base_url+'admin/hostings/search/%QUERY',
    limit: 10
});

$(document).ready(function(){ 
    $.fn.editable.defaults.mode = 'inline';
    
    $('.edit_user').editable({
         type: 'text',
         pk: 1,
         url: base_url+'admin/user/'+'save'+'/'+$(".panel-body").attr("user_id"),
         emptytext: 'Please enter text'
    });
     
   /* $(".edit_user").click(function(){
        $('#'+$(this).attr('id')).editable({
            type: 'text',
            pk: 1,
            url: base_url+'admin/user/'+'save'+'/'+$(".panel-body").attr("user_id"),
            title: 'Enter username'
        });
    });*/
    
    $('.datepicker,.datepicker_event').on('changeDate', function(ev){
      
            $(this).datepicker('hide');
    });
    
    $('#dob').editable({
        url: base_url+'admin/user/'+'savebirthday'+'/'+$(".panel-body").attr("user_id")
    });
    
    $("input[name='gender']")
    .on('switchChange.bootstrapSwitch',
        function(event, state) {                     
            if($(".panel-body").attr("user_id")!=null){
                $.ajax({
                    type: "POST",
                    url: base_url+'admin/user/'+'save'+'/'+$(".panel-body").attr("user_id"),
                    data: {
                        value : this.value, 
                        name:this.name
                        },

                    success: function(msg) {
                        $("#data_error").text("");
                        if(msg == "D") {
                            $("#data_error").text("Venue already booked");
                        } else if(msg == "F") {
                            $("#data_error").text("Updated Failed");
                        } 
                    }
                });
            }
        });
    
    

    $( ".get_modal" ).on( "click", function() {    
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
    });
    
    /* Hostings HTML Elements */
    
    
    $(".edit_host").editable({
            type: 'text',
            pk: 1,
            url: base_url+'admin/hostings/'+'save'+'/'+$(".panel-body").attr("hosting_id"),
            success: function(msg) {  
                    
                if(msg == "D") {
                    $("#data_error").text("Venue already booked");
                } else if(msg == "F") {
                    $("#data_error").text("Updated Failed");
                } else {
                    $("#p_"+$(this).attr('id')).html(msg);
                    $("#data_error").text("");
                }
            } 
    });
    
    /*$(".edit_host").click(function(){
        $('#'+$(this).attr('id')).editable({
            type: 'text',
            pk: 1,
            url: base_url+'admin/hostings/'+'save'+'/'+$(".panel-body").attr("hosting_id"),
            success: function(msg) {  
                    
                if(msg == "D") {
                    $("#data_error").text("Venue already booked");
                } else if(msg == "F") {
                    $("#data_error").text("Updated Failed");
                } else {
                    $("#p_"+$(this).attr('id')).html(msg);
                    $("#data_error").text("");
                }
            } 
        });
    });*/
    
    $('#host_date').editable({
        url: base_url+'admin/hostings/'+'save'+'/'+$(".panel-body").attr("hosting_id")
    });
    
    $('#host_description').editable({
        url: base_url+'admin/hostings/'+'save'+'/'+$(".panel-body").attr("hosting_id"),
        showbuttons: 'bottom'
    });
    
    $("[name='is_recurring'],[name='is_promoter'],[name='promoter'],[name='hstatus'],[name='gender'],[name='venue_status'],[name='verified_host'],[name='suspend_user']").bootstrapSwitch();
    
    $("input[name='is_recurring'],[name='is_promoter'],[name='promoter'],[name='hstatus']")
    .on('switchChange.bootstrapSwitch',
        function(event, state) {
            var state_value = 0;
            var name = $(this).attr('name');
            if(state){
                state_value = 1;
            }  
            if($(".panel-body").attr("hosting_id")!=null){
                $.ajax({
                    type: "POST",
                    url: base_url+'admin/hostings/'+'save'+'/'+$(".panel-body").attr("hosting_id"),
                    data: {
                        value : state_value, 
                        hosting:$(".panel-body").attr("hosting_id"),
                        name:name
                    },

                    success: function(msg) {
                        $("#data_error").text("");
                        if(msg == "D") {
                            $("#data_error").text("Venue already booked");
                        } else if(msg == "F") {
                            $("#data_error").text("Updated Failed");
                        } 
                    }
                });
            }     
        });
    
    $("input[name='venue_status']")
    .on('switchChange.bootstrapSwitch',
        function(event, state) {
            var state_value = 0;
            var name = $(this).attr('name');
            if(state){
                state_value = 1;
            }  
            if($(".panel-body").attr("venue_id")!=null){
                $.ajax({
                    type: "POST",
                    url: base_url+'admin/venue/'+'save'+'/'+$(".panel-body").attr("venue_id"),
                    data: {
                        value : state_value,
                        name:name
                    },

                    success: function(msg) {

                    }
                });
            }     
        });
    $("input[name='verified_host']")
    .on('switchChange.bootstrapSwitch',
        function(event, state) {
            var v = (state== true)?1:0; 					 
            var url_link = base_url+'admin/user/'+'verified_host'+'/'+Math.random(); 
            if($(".panel-body").attr("user_id")!=null){
                $.ajax({
                    type: "POST",
                    url: url_link,
                    data: {
                        data : v,  
                        user:$(".panel-body").attr("user_id")
                        },

                    success: function(msg) {
                    }
                });
            }        
        });
    
    $("input[name='suspend_user']")
    .on('switchChange.bootstrapSwitch',
        function(event, state) {
            var url_link = base_url+'admin/user/'+'suspend_user'+'/'+Math.random();         

            var su = (state== true)?5:1; 
            if($(".panel-body").attr("user_id")!=null){
                $.ajax({
                    type: "POST",
                    url: url_link,
                    data	: {
                        data : su,  
                        user:$(".panel-body").attr("user_id")
                        },

                    success: function(msg) {

                    }
                });
            }     
        });
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
    
    var obj_grade = [];


for (i = 1; i <= 100; i += 1) {  
    tmp = {'value': i,'text': i };
    obj_grade.push(tmp);
}
    $('#rank').editable({
		source:obj_grade,
		 type: 'text',
            pk: 1,
		url: base_url+'admin/venue/'+'save'+'/'+$(".panel-body").attr("venue_id"),
        success: function(response) {  
            $('.save_error').html('');
            if(response!="success"){
                $('.save_error').html(response);
            }
        } 
	});
   $(".edit_venue").editable({
            type: 'text',
            pk: 1,
            url: base_url+'admin/venue/'+'save'+'/'+$(".panel-body").attr("venue_id"),
            success: function(response) {  
                $('.save_error').html('');
                if(response!="success"){
                    $('.save_error').html(response);
                }
            } 
   });
   
   /*$(".edit_venue").click(function(){
        $('#'+$(this).attr('id')).editable({
            type: 'text',
            pk: 1,
            url: base_url+'admin/venue/'+'save'+'/'+$(".panel-body").attr("venue_id"),
            success: function(response) {  
                $('.save_error').html('');
                if(response!="success"){
                    $('.save_error').html(response);
                }
            } 
        });

    });*/
    
    $('#venue_description').editable({
        url: base_url+'admin/venue/'+'save'+'/'+$(".panel-body").attr("venue_id"),
        showbuttons: 'bottom'
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
        if($("#email").val()!="") {
            var url_link = base_url+'admin/hostings/check_email/'+Math.random(); 
            var email = $(this).val(); 
            $.ajax({
                type: "POST",
                url: url_link,
                data	: {
                    email : email
                },
                 
                success: function(msg) {
                    var data = jQuery.parseJSON(msg);
                    if(data.status == 1) {
                        $("#hosting_name_div_err").append("<label for='email_error' class='error1'>"+'Not a valid user</label>');
                        //$("#hosting_name_div").find("span").text("");  
                        $("#email_verified").val(0);
                    }else if(data.status == 2) {
                        $("#hosting_name_div_err").append("<label for='email_error' class='error1'>"+'Invalid Email</label>');
                        //$("#hosting_name_div").find("span").text("");  
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
    
    $("#venue_name_create").blur(function(){
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
        zip: {
            number: true
        },
        phone: {
            phoneUS: true
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

function chatDesc() {
    $( ".get_modal" ).on( "click", function() {   
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
    });
}
