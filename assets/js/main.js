jQuery(function ($) {
	'use strict';

	var $floatBreakpoint = $('<div class="grid-float-breakpoint" style="position:absolute;top:-9999px;width:1px;height:1px;"></div>');
	var $xsVisible = $('<div class="visible-xs" style="position:absolute;top:-9999px;width:1px;height:1px;"></div>');
	var $smVisible = $('<div class="visible-sm" style="position:absolute;top:-9999px;width:1px;height:1px;"></div>');
	

	var baseDomain = "https://jiggie-dev.herokuapp.com/admin/admin/"
	var adminToken = "?admin_token=dsabalsdbaiyzVYVKJD78t87tgBQGK9sfhkslhfdksCFCJjgvgKV98y98h90z3pd"

	function openActiveTab(url) {
        if (url.match('#')) {
            $('.nav-tabs a[href=#' + url.split('#')[1] + ']').tab('show');
        }
    }

    function equalHeight(objects) {
        var max = 0;
        objects.each(function () {
            var height = $(this).height();
            max = height > max ? height : max;
        });
        objects.height(max);
    }

    $('body').append($floatBreakpoint).append($xsVisible).append($smVisible);
    $('[data-sidebar-toggle]').on('click', function () {
        if ($floatBreakpoint.is(':visible')) {
            $('body').toggleClass('sidebar-condensed');
        } else {
            $('body').toggleClass('sidebar-opened');
        }
    });

    $('.sidebar .nav').navgoco({
        caretHtml: false,
        accordion: true,
        onClickBefore: function () {
            if ($('body').hasClass('sidebar-condensed')) {
                var $parent = $(this).parent();
                // is first level menu
                if ($parent.parent().hasClass('nav')) {
                    $parent.siblings().removeClass('nav-dropdown-open');

                    if ($parent.hasClass('nav-dropdown')) {
                        $parent.toggleClass('nav-dropdown-open');
                    }

                    return false;
                }
            }
            return true;
        }
    });


    $(".event-dupimage-remove").click(function(e)
    {
        $("#update_dupcnt_imageform" +$(this).attr('tag') ).remove();
        $("#hidden_photo_" +$(this).attr('tag') ).remove();

        
    })

    //openActiveTab(document.location.toString());

    $('[data-rel="collapse"]').on('click', function () {
        var $this = $(this);
        var $panel = $this.closest('.panel');
        if ($panel.hasClass('collapsed')) {
            $this.children('.fa').removeClass('fa-plus').addClass('fa-minus');
            $panel.children('[class!="panel-heading"]').slideDown(300, function () {
                $panel.removeClass('collapsed');
            });
        } else {
            $this.children('.fa').removeClass('fa-minus').addClass('fa-plus');
            $panel.children('[class!="panel-heading"]').slideUp(300, function () {
                $panel.addClass('collapsed');
            });
        }
        return false;
    });

    $('[data-rel="reload"]').on('click', function () {
        return false;
    });

    $('[data-rel="close"]').on('click', function () {
        //jQuery(this).closest('.panel').fadeOut(300);
        //return false;
    });

    $('[data-toggle="tooltip"]').tooltip().on('show.bs.tooltip', function () {
        var style = $(this).data('style');
        if (style && style !== '') {
            $(this).data('bs.tooltip').tip().addClass('tooltip-' + style);
        }
    });
    $('[data-toggle="popover"]').popover().on('show.bs.popover', function () {
        var i, styles, style = $(this).data('style') || '';
        styles = style.split(' ');
        if (styles.length > 0) {
            for (i in styles) {
                styles[i] = 'popover-' + styles[i];
            }
            $(this).data('bs.popover').tip().addClass(styles.join(' '));
        }
    });

    $('[data-sync-height]').each(function () {
        equalHeight($(this).children());
    });

    $('.mail-navigation .active').on('click', function(e){
        if ($xsVisible.is(':visible') || $smVisible.is(':visible')) {
            e.preventDefault();
            $(this).closest('.mail-navigation').toggleClass('open');

            return false;
        }
    });
    // Setup default options for Bootstrap3 WYSIHTML5
    if($.fn.wysihtml5){
        $.fn.wysihtml5.defaultOptions = $.extend({}, $.fn.wysihtml5.defaultOptions, {
            color: true
        });
    }
    
});
$(document).ready(function() {
	
	if($('#section_list').length){
		
		//getDataResponse(base_url+'admin/chat/ajax_list','chat');
		function handleChanges(newHash, oldHash){
			 
			str_h = newHash.split("=");
		    if(str_h[0]=='details'){		    	 
		    	var detailpage= base_url+'admin/user/user_details/'+ str_h[1];   		 		 
   				getUserDetailPage(detailpage,'detail');
		    }else if (str_h[0]=='profle_images'){
		    	var detailpage= base_url+'admin/user/user_details/'+ str_h[1];   		 
   				getUserDetailPage(detailpage,'profile');
		    }else if (str_h[0]=='hosting-details'){
		    	var detailpage= base_url+'admin/hostings/details/'+ str_h[1];   		 
   				getHostingDetailPage(detailpage);
		    }else if (str_h[0]=='recurring-hosting-details'){
		    	var detailpage= base_url+'admin/hostings/recurringdetails/'+ str_h[1];   		 
   				getHostingRecurringDetailPage(detailpage);
		    }else if (str_h[0]=='venue-details') {   		  
		    	var detailpage= base_url+'admin/venue/venue_details/'+ str_h[1];   		 
   				getVenueDetailPage(detailpage,'detail'); 
		    }else if (str_h[0]=='venue_images') {   		  
		    	var detailpage= base_url+'admin/venue/venue_details/'+ str_h[1];   		 
   				getVenueDetailPage(detailpage,'profile'); 
		    }else if (str_h[0]=='event-special') {		    			  
		    	var detailpage= base_url+'admin/events/event_details/special/'+ str_h[1]; 		    	  		 
   				getEventDetailPage(detailpage,'special'); 
		    }else if (str_h[0]=='event-weekly') {   		  
		    	var detailpage= base_url+'admin/events/event_details/weekly/'+ str_h[1];   		 
   				getEventDetailPage(detailpage,'weekly'); 
		    }else if (str_h[0]=='ticket-details') {		    	   		  
		    	var detailpage= base_url+'admin/tickets/ticket_details/'+ str_h[1];   		 
   				getTicketDetailPage(detailpage,'special'); 
		    }
		    else if (str_h[0]=='ticket-recurring') {		    	   		  
		    	var detailpage= base_url+'admin/tickets/ticket_recurring/'+ str_h[1];   		 
   				getTicketDetailPage(detailpage,'weekly'); 
		    }
		    else if (str_h[0]=='order-details') {   		  
		    	var detailpage= base_url+'admin/orders/order_details/'+ str_h[1];   		 
   				getOrderDetailPage(detailpage,str_h[1]); 
   				 
		    }
		    else{	
		    	 	    	 
		    	getDataResponse();
		    }
				
	 	}

	    hasher.changed.add(handleChanges); //add hash change listener
	    hasher.initialized.add(handleChanges); //add initialized listener (to grab initial value in case it is already set)
	    hasher.init(); //initialize hasher (start listening for history changes)
	}
	if(controller_JS=='events' || controller_JS=='hostings' ){
		var all_endDate = moment().add(6, 'months') ;
		
		//auto_range ={"start":moment(),"end":moment().add(6, 'months')};
		max_date ='+6m';
		auto_range = {};
		auto_range.start= moment("2015-04-01"+'T01:00:00-04:00');
		auto_range.end =moment().add(6, 'months');
		preset_options =  [
						{text: 'All',dateStart: function() { return   moment("2015-04-01") },dateEnd: function() { return all_endDate }},
						{text: 'Today', dateStart: function() { return moment() }, dateEnd: function() { return moment() } },
						{text: 'Tomorrow ', dateStart: function() { return moment().add('days', 1) }, dateEnd: function() { return moment().add('days', 1) } },
						{text: 'Next 7 Days', dateStart: function() { return moment().add('days', 1) }, dateEnd: function() { return moment().add('days', 7)} },						
						{text: 'Next 30 Days', dateStart: function() { return moment().add('days', 1) }, dateEnd: function() { return moment().add('days', 30) } },							
						{text: 'Custom',dateStart: function() { return moment() }, dateEnd: function() { return moment().add('days', 30) } },
			
			 ]
	}else{
		var all_endDate = moment() ;
		max_date =0;
		auto_range = {};
		auto_range.start= moment("2014-04-01"+'T01:00:00-04:00');
		auto_range.end =moment();
		preset_options =  [
						{text: 'All',dateStart: function() { return  moment("2014-04-01") },dateEnd: function() { return all_endDate }},
						{text: 'Today', dateStart: function() { return moment() }, dateEnd: function() { return moment() } },
						{text: 'Yesterday', dateStart: function() { return moment().subtract('days', 1) }, dateEnd: function() { return moment().subtract('days', 1) } },
						{text: 'Last 7 Days', dateStart: function() { return moment().subtract('days', 7) }, dateEnd: function() { return moment().subtract('days', 1)} },
						{text: 'Previous 7 Days',  dateStart: function() { return moment().subtract('days', 14) }, dateEnd: function() { return moment().subtract('days', 8)} },
						{text: 'Last 30 Days', dateStart: function() { return moment().subtract('days', 30) }, dateEnd: function() { return moment().subtract('days', 1) } },	
						{text: 'Previous 30 Days', dateStart: function() { return moment().subtract('days', 60) }, dateEnd: function() { return moment().subtract('days', 31) } },					
						{text: 'Custom',dateStart: function() { return moment().subtract('days', 30) }, dateEnd: function() { return moment() } },
			
			 ]
	}
 
    $("#e1").daterangepicker1(
		{
			presetRanges:preset_options,
			applyOnMenuSelect: false,			
			datepickerOptions: {			
				maxDate: max_date
			},
			initStart:moment(start_date_JS+'T01:00:00-04:00') ,
			initEnd:moment(end_date_JS+'T01:00:00-04:00'),
			initSel:int_sel,
			autorange: auto_range, 
	});
      
    $('#e1').on('change.daterangepicker', function(e) {
      
			
	  var seldate = $("#e1").daterangepicker1("getRange");	 
	  var url_link = base_url+'admin/home/dateRange/'+ (new Date()).getTime();  
	   console.log(">>>>> " + seldate.end)
       console.log("url " + url_link)
        $.ajax({
            type: "POST",
            url: url_link,       

            data	: {
                startDate :$.datepicker.formatDate('mm/dd/yy',seldate.start), 
                endDate : $.datepicker.formatDate('mm/dd/yy',seldate.end),               
                selected : $('#e1').attr('data-sel')
                },
                 
            success: function(msg) {            	
                location.reload();
            }
        });
    });
          
    $("#sort-list").select2({
        minimumResultsForSearch: -1
    });
    
    $("#rank_h").select2({
        minimumResultsForSearch: -1
    });
    $("#search-venue").select2({ 
        placeholder: "Select Venue",
        allowClear: true,
        formatSelection: formatselected,
        formatResult: format,
        formatNoMatches: function(e){$("#selected_search_venue").val('');return "No venue found";}
    }).on("select2-selecting", function(e) {           
            preparehashtags('search_venue', e.val,true);
    }) .on("select2-removed", function(e) {
    	 $("#selected_search_venue").val('');
    	 preparehashtags('search_venue','',true);
         //console.log("removed val=" + e.val + " choice=" + e.choice.text);
    })
    
    $("#search-recurring").select2({
        placeholder: "Is Recurring",
        allowClear: true,
        minimumResultsForSearch: -1,
        formatSelection: formatselected_recurring,
        formatResult: format
    }).on("select2-selecting", function(e) {           
            preparehashtags('search_recurring', e.val,true);
    }).on("select2-removed", function() {
    	 preparehashtags('search_recurring', '',true);
           $("#selected_search_recurring").val('');
    });
    $("#search-promoter").select2({
        placeholder: "Is Promoter",
        allowClear: true,
        minimumResultsForSearch: -1,
        formatSelection: formatselected_promoter,
        formatResult: format
    }).on("select2-selecting", function(e) {           
            preparehashtags('search_promoter', e.val,true);
    }).on("select2-removed", function() {
    	   preparehashtags('search_promoter', '',true);
           $("#selected_search_promoter").val('');
    });
    $("#search-verified").select2({
        placeholder: "Is Verified",
        allowClear: true,
        minimumResultsForSearch: -1,
        formatSelection: formatselected_verified,
        formatResult: format
    }).on("select2-selecting", function(e) {           
            preparehashtags('search_verified', e.val,true);
    }).on("select2-removed", function() {
    	   preparehashtags('search_verified', '',true);
           $("#selected_search_verified").val('');
    });  
    
    $("#sort_status").select2({ 
        minimumResultsForSearch: -1
    }).on("select2-selecting", function(e) {           
            preparehashtags('sort_status', e.val,true);
    }) .on("select2-removed", function(e) {
    	 $("#selected_sort_status").val('');
    	 preparehashtags('search_venue','',true);
         //console.log("removed val=" + e.val + " choice=" + e.choice.text);
    })
    $("#sort-list").on("change", function(e) { 
       preparehashtags('per_page',$('#toolbar').find('.select2-choice').find('.select2-chosen').html(),true)
    })
    $("#sort-formsearch").on("click", function(e) { 
    	//preparehashtags('offset',1) 
        preparehashtags('search_name',$('#data-search').val(),true)
    	
    })
    $('#data-search').on("keypress", function(e){
        if (e.keyCode == '13') {
        	//preparehashtags('offset',1)  
        	preparehashtags('search_name',$('#data-search').val(),true) 
        	          
        }
    })
    $("#formsearch").on("click", function(e) { 
    	//preparehashtags('offset',1) 
        preparehashtags('search_name',$('#data-search').val(),true)
    	
    })
    $("#hostingsformsearch").on("click", function(e) { 
    	 
        //preparehashtags('offset',1) ;
        preparehashtags('search_name',$('#hostingsdata-search').val(),true);         
    	
    })
    $('#hostingsdata-search').on("keypress", function(e){
        if (e.keyCode == '13') {
        	//preparehashtags('offset',1) ;  
	        preparehashtags('search_name',$('#hostingsdata-search').val(),true);	        
	    	       
        }
    })
    $("#displayselection").on("click",'a', function(e) { 
    	e.preventDefault();	 
    	preparehashtags('view_type',$(this).data('dispaly'),false)
 
    })
    
    $("#displayeventselection").on("click",'a', function(e) { 
    	e.preventDefault();	 
    	$(this).siblings().removeClass('fc-state-active')
    	$(this).addClass('fc-state-active')
    	 preparehashtags('view_type',$(this).data('dispaly'),false);
    	 
    	 
    })
    
    $("body").find(".panel-body" ).off( "click").on( "click", "th", function() {   	
      
        if(!$( this).hasClass('sorting')){
        	return;
        }
        
        $('.panel-body').attr('sort_field',$( this).attr('data-field'));
        if($( this).hasClass('sorting_asc')){
            $( this).removeClass('sorting_asc').addClass('sorting_desc')
            $('.panel-body').attr('sort_val','desc')
        }else{
            $( this).removeClass('sorting_desc').addClass('sorting_asc')
            $('.panel-body').attr('sort_val','asc')
        }
        $('.dataTable th').not(this).each(function(){
            $(this).removeClass('sorting_desc').removeClass('sorting_asc');         	 
        });		
       // preparehashtags('sort_field',$('.panel-body').attr('sort_field'),false);
		//preparehashtags('sort_val',$('.panel-body').attr('sort_val'),false);
		prepareSortinghashtags($('.panel-body').attr('sort_field'),$('.panel-body').attr('sort_val'))
    }); 
    
	
    
    
     
    //photo section 
    $(".img-edit-link" ).click(function(e) {	
        e.stopPropagation();		 
        $("#show"+$(this).attr('id')).toggle();		
    });
	   
    $('.photo-drop li a').mouseout(function(){
			
    });
        
    $('body').click(function(e) {
        
        if(! $(e.target).hasClass('img-edit') ) {
            $('.photo-drop').hide();
        }
    });
		
    $( ".upload-text" ).on( "click",  function(ev) {
        
        ev.stopPropagation();
			
        var image =$(this).attr('trigger'); 
	 	  
        $('#'+image).css({
            'width':'0px', 
            'height' : '0px'
        });
        $('#'+image).removeClass('hidden');
		  
    });
    
    $('.p-phptos').on( "click", ".del-image",  function(ev) {
	    
        ev.stopPropagation();	    	  
        $.ajax({
            type		: "POST",
            url		: $(this).attr('url'),			   
            data		: {
                'old_img':$(this).attr('old-image-id'),
                'id':$(this).attr('row-id'),
                field:$(this).attr('data-field')
                },
            success	: function(resp){	   
				   
            },
            complete : function (){
                location.reload();
            }
        });
    });
    
    $('.ucimageuploader').on('change', function(){
	        
        if($(this).parent().attr('old-image-id'))
            var old_img = encodeURIComponent($(this).parent().attr('old-image-id'));	
        else     old_img ='';        
        var id= $(this).attr('form-id');
        var url = $("#"+id).attr('action');	        
        $("#"+id).attr('action',url+$(this).attr('name')+'/'+$(this).attr('data-field')+'/'+$(this).attr('data-for')+'/?img='+old_img);
        
        $("#"+id).ajaxForm({
            success:    function() {
                location.reload();
            }
        }).submit();
    
    });
	    
    $(document.body).on( "click", ".change-upload-photo",  function(ev) {
        
        ev.stopPropagation();

        $("#oldphoto_image_id").val('');
        $('#oldphoto_image_id').val($(this).attr('old-image-id'));
        image = $(this).attr('trigger'); 
        $('#'+image).css({
            'width':'0px', 
            'height' : '0px'
        });
        $('#'+image).removeClass('hidden');

    });
    $('.sidebar').height($(document).height());
   

    
    		var $container = $('#img-tems');
			// initialize Masonry after all images have loaded  
			$container.imagesLoaded( function() {
			  $( ".panel-body .thumbnail" ).on({
					 mouseenter: function() {
						//$( this ).find('.user-over').css({'opacity':1});
						//$( this ).find('.user-img').css({'opacity':0})
					}, mouseleave: function() {
						//$( this ).find('.user-over').css({'opacity':0});
						//$( this ).find('.user-img').css({'opacity':1});
					}
				});
			});
	
	
	$( "#data-list" ).on( "click", "#chat_sec_api .chatrow", function(e) {
		//e.preventDefault();		 
		$('#conversation').remove();
		$('.modal-backdrop').remove();
	    var frm= $(this).data('from');
	    var frm_name= $(this).data('fromname');
	    var to= $(this).data('to');
	    var to_name= $(this).data('toname');
		path = base_url+'admin/chat/conversation/'+$(this).data('to')+'/'+$(this).data('from');
		$('#serviceModal').remove();
		$.ajax({
	        type		: "POST",
	        url		: path,	
	        dataType: "json",	      
	        success	: function(resp){	
	        	
	        	resp.from= frm;
	        	resp.fromname= frm_name; 
	        	resp.to= to; 
	        	resp.toname= to_name;  
	        	resp.siteurl =base_url 	 
	        	var html =  new EJS({url: base_url+'assets/js/ejs/chat_conversation.ejs?v='+ $.now()}).render(resp);		        		      
	            $modal = $('<div class="modal " id="conversation"></div>');
	            $modal.html(html)               
                $('body').append($modal);
                $modal.modal({backdrop: 'static', keyboard: true});
                $modal.show();
	        }
        }); 
        
		
                // $modal.load(path);
	})
	
	$("#data-set1").select2({ 
        placeholder: "Select a metric",
        allowClear: true,
        formatSelection: formatselected,
        formatResult: format,
        formatNoMatches: function(e){return "No metric found";}
    })
	$("#data-set2").select2({ 
        placeholder: "Select a metric",
        allowClear: true,
        formatSelection: formatselected,
        formatResult: format,
        formatNoMatches: function(e){return "No metric found";}
    })
    $("#data-set1").on("change", function(e) { 
        getOverviewGraph();
    })
    $("#data-set2").on("change", function(e) { 
        getOverviewGraph();
    })
    $("#goptions").on("change", 'a', function(e) { 
        getOverviewGraph($(this).attr('id'));
    })
    if($('#ph_overviewgrpah').length)
   		 getOverviewGraph();
   	//if($('#section_chat').length)
   		 //getDataResponse(base_url+'admin/chat/ajax_list/','chat');
 
   	$('#section_list').on("click",'.stud_detail', function(e) { 
   		var detailpage= base_url+'admin/user/user_details/'+ $(this).data('user_id')
   		 hasher.setHash('details='+$(this).data('user_id')) 
   		//getUserDetailPage(detailpage,'detail');
   		e.preventDefault();    
    })
    $('#section_list').on("click",'.profile_image', function(e) { 
   		var detailpage= base_url+'admin/user/user_details/'+ $(this).data('user_id')
   		 hasher.setHash('profle_images='+$(this).data('user_id')) 
   		//getUserDetailPage(detailpage,'profile');
   		e.preventDefault();    
    })
    $('#section_list').on("click",'.host_detail', function(e) { 
    	 e.preventDefault();
    	     
   		//var detailpage= base_url+'admin/hostings/details/'+ $(this).data('hosting_id')
   		//hasher.setHash('hosting-details='+$(this).data('hosting_id')) 
   		
   		pop_host =  this ;  
    	//data-clickedfrom="" data-clickedfromurl=""
    	$('#section_list').data('clickedfrom','list');
    	$('#section_list').data('clickedfromurl', hasher.getHash())	;	 
   		if($(this).data('isrecurring')==true){
	   		$.confirm({
			    text: "Do you want to edit the series or only this date ?",
			    title: "Confirmation required",
			    confirm: function(button) {			       
			      hasher.setHash('recurring-hosting-details='+$(pop_host).data('recurring_id'));
			    },
			    cancel: function(button) {
			         hasher.setHash('hosting-details='+$(pop_host).data('hosting_id'));
			    },
			    confirmButton: "Edit series",
			    cancelButton: "Only this date",
			    post: true
			});
	   }else{
	   		//hasher.setHash('event-special='+$(this).data('event_id'));
	   		 hasher.setHash('hosting-details='+$(pop_host).data('hosting_id'));
	   }      
    })
    $('#section_list').on("click",'.venue_detail', function(e) { 
    	e.preventDefault();     
   		var detailpage= base_url+'admin/venue/venue_details/'+ $(this).data('venue_id')
   		hasher.setHash('venue-details='+$(this).data('venue_id')) 
   		//getVenueDetailPage(detailpage,'details');     
    })
    $('#section_list').on("click",'.venue_image', function(e) { 
   		var detailpage= base_url+'admin/venue/image/'+ $(this).data('venue_id')
   	    hasher.setHash('venue_images='+$(this).data('venue_id')) 
   		//getVenueDetailPage(detailpage,'profile');
   		e.preventDefault();    
    })


    $.fn.serializeObject = function()
    {
        var o = {};
        var a = this.serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    };

    $("#section_list").on("click","#btn_ticket_update",function(e)
    {
        e.preventDefault(); 
        
        
        var dataToSend = $("#editticket-form").serializeObject();
        //console.log(dataToSend);
        showloader();
        path = base_url+'admin/tickets/updateticket';        
        $.ajax({
            type        : "POST",
            url     : path, 
            data        : dataToSend,
            dataType: "json",         
            success : function(resp){
                
                     console.log("yes!!")                
                 
            },
             complete : function (o){
                location.reload();
             }
        });
        

    });

    $('#section_list').on("click",'.event_detail', function(e) { 
    	e.preventDefault(); 
    	pop_event =  this ;  
    	//data-clickedfrom="" data-clickedfromurl=""
    	$('#section_list').data('clickedfrom','list');
    	$('#section_list').data('clickedfromurl', hasher.getHash())	;

   		if($(this).data('event_type')=='weekly'){
	   		$.confirm({
			    text: "Do you want to edit the series or only this date ?",
			    title: "Confirmation required",
			    confirm: function(button) {			       
			      hasher.setHash('event-weekly='+$(pop_event).data('event_id'));
			    },
			    cancel: function(button) {
			         hasher.setHash('event-special='+$(pop_event).data('_id'));
			    },
			    confirmButton: "Edit series",
			    cancelButton: "Only this date",
			    post: true
			});
	   }else{
	   		hasher.setHash('event-special='+$(this).data('_id'));
	   }    
    })
    $('#section_list').on("click",'.event_ticket_list', function(e) { 
    	 
    	e.preventDefault(); 
    	pop_event =  this ;  
    	//data-clickedfrom="" data-clickedfromurl=""
    	$('#section_list').data('clickedfrom','list');
    	$('#section_list').data('clickedfromurl', hasher.getHash())	;
		var _id = $(this).data('_id');
		var event_id = $(this).data('event_id');	 
   		if($(this).data('event_type')=='weekly'){
   			
   			console.log(_id)
	   		$.confirm({
			    text: "Do you want to view/add ticket to the series or only this date ?",
			    title: "Confirmation required",
			    confirm: function(button) {			       
			       hasher.setHash();
			       window.location.href = base_url+'admin/tickets/#event-id='+event_id+'/is_recurring=1';
			    },
			    cancel: function(button) {			        
			       window.location.href = base_url+'admin/tickets/#event-id='+_id+'/is_recurring=0';
			      
			    },
			    confirmButton: "Add to series",
			    cancelButton: "Only this date",
			    post: true
			});
	   }else{
	   		 window.location.href = base_url+'admin/tickets/#event-id='+_id+'/is_recurring=0';
	   }    
    })
    $('.navbar').on("click",'.pagebackbtn', function(e) { 
    	  
    	
    	  window.history.back();
    	
    })
    $('#section_list').on("click",'.detail_close', function(e) { 
    	e.preventDefault(); 
    	
    	if($('#section_list').data('clickedfrom')=='list') {
    		//alert($('#section_list').data('clickedfrom'))
    		hasher.setHash($('#section_list').data('clickedfromurl'));
    	}else{
    		window.location.href = base_url+'admin/'+controller_JS;
    		//window.history.back();
    	}
    	
    })
     $('#section_events').on("click",'.detail_close', function(e) { 
    	e.preventDefault();    	
    	window.history.back();
    })
    
    $('#section_list').on("click",'#delete_event_details', function(e) {  	 
    	 e.preventDefault(); 
    	 event_details = this;
    	 $.confirm({
		    text: "Do you want to delete this event ?",
		    title: "Confirmation required",
		    confirm: function(button) {	
		    	hasher.setHash();
		    	var event_id= $(event_details).data('event_id');
			    
		    	showloader();
		    	if($(event_details).data('event_type')=='special'){
		    		 window.location.href = base_url+'admin/events/delete/'+event_id;
		    	}else{
		    		 window.location.href = base_url+'admin/events/weeklydelete/'+event_id+'/'+$('#forceedit').is(":checked");
		    	}		       
		        
		    },
		    cancel: function(button) {
		          
		    },
		    confirmButton: "Delete Event",
		    cancelButton: "Cancel",
		    post: true
		});  	
    	 
    })


    $('#section_list').on("click",'#edit_startdatetime', function(e) {     
         e.preventDefault(); 
         event_details = this;

         console.log("done!!!");

         var html =  new EJS({url: base_url+'assets/js/ejs/event_edit_time.ejs?v='+ $.now()}).render({});

        $modal = $('<div class="modal " id="edit_event_time"></div>');
        $modal.html(html);
        $('body').append($modal);
        $modal.modal({backdrop: 'static', keyboard: true});
        $modal.show();

        var defaultStartTime = window.cEditEventData.start_datetime_str.split(",")[1];
        defaultStartTime = defaultStartTime.slice(6,defaultStartTime.length);

        var defaultEndTime = window.cEditEventData.end_datetime_str.split(",")[1];
        defaultEndTime = defaultEndTime.slice(6,defaultEndTime.length);

        $('#basicExample .time_start').timepicker({
            'showDuration': true,
            'timeFormat': 'g:ia'
        });

        $('#basicExample .time_end').timepicker({
            'showDuration': true,
            'timeFormat': 'g:ia'
        });

        $('#basicExample .time_start').attr("value",defaultStartTime);
        $('#basicExample .time_end').attr("value",defaultEndTime);


        $('#basicExample .date_start').datepicker({
            'format': 'MMM dd yyyy',
            'autoclose': true,
            'startDate':window.cEditEventData.start_datetime,
            "minDate":new Date()
        });

        $('#basicExample .date_end').datepicker({
            'format': 'm/d/yyyy',
            'autoclose': true
        });


    
        });

    $('#section_list').on("click",'#delete_ticket_details', function(e) {  	 
    	 e.preventDefault(); 
    	 ticket_details = this;
    	 $.confirm({
		    text: "Do you want to delete this ticket ?",
		    title: "Confirmation required",
		    confirm: function(button) {	
		    	hasher.setHash();
		    	var ticket_id= $(ticket_details).data('ticket_id');
		    	var event_id= $(ticket_details).data('event_id');
			    var type    = $(ticket_details).data('type');
			    
		    	showloader();
		    	window.location.href = base_url+'admin/tickets/delete/'+ticket_id+'/'+event_id+'/'+type;		       
		        
		    },
		    cancel: function(button) {
		          
		    },
		    confirmButton: "Delete Ticket",
		    cancelButton: "Cancel",
		    post: true
		});  	
    	 
    })




    $("#location").val($("#venue_sel").find('option:selected').data("location"))
    if($('.event_range_start').val()){
    	 var localtime = new TimeShift.OriginalDate($('.event_range_start').val()+' '+$('#starttime').val() );
		 localendtime = localtime.setHours(localtime.getHours()+parseInt($( "#event_time" ).val()));			
         $('#endtime ').val(dateFormats(localendtime, "mmm dd,yyyy h:MM tt"));
    	 $('#end_time ').val(dateFormats(localendtime, "mmm dd,yyyy h:MM tt")); 
    }
     
    if($('#event_type').val()=='special'){
    	$('#endrangeselection').hide()
    	$('#startdatelabel').html('Start date')
    }else{
    	$('#startdatelabel').html('Start series date')
    	$('#endrangeselection').show()
    }
    $("#venue_sel").change(function()	{    	 
		var element = $(this).find('option:selected'); 
        var myTag = element.data("location");         
		$("#location").val(myTag);
	});
	$("#event_type").change(function()	{    	 
		var element = $(this).find('option:selected'); 
        if($(this).val()=='special'){
        	$('#endrangeselection').hide();
	    	$('#startdatelabel').html('Start date');
        }else{
        	$('#startdatelabel').html('Start series date');
	    	$('#endrangeselection').show();
	    }
         
	});
	$('.event_range_start').datepicker({
		changeMonth: true,
	    changeYear: true,
	    yearRange: date.getFullYear()+" : 2044",
	    minDate: "+0d",
	    dateFormat: "M dd, yy",	    
	    onSelect: function (date) {
	    	console.log('date',date);
	    	console.log('starttime',$('#starttime').val());
	    	console.log('event_time',$( "#event_time" ).val());
	    	 
	        $( ".event_range" ).trigger( "blur" );        
	         
			var localtime = new TimeShift.OriginalDate(date+' '+$('#starttime').val() );
			localendtime = localtime.setHours(localtime.getHours()+parseInt($( "#event_time" ).val()));			
	        $('#endtime ').val(dateFormats(localendtime, "mmm dd,yyyy h:MM tt"));
	    	$('#end_time ').val(dateFormats(localendtime, "mmm dd,yyyy h:MM tt"));
	    }    
	});
	$("#event_time").change(function()	{ 
		 var localtime = new TimeShift.OriginalDate($('.event_range_start').val()+' '+$('#starttime').val() );
			 localendtime = localtime.setHours(localtime.getHours()+parseInt($( "#event_time" ).val()));			
	         $('#endtime ').val(dateFormats(localendtime, "mmm dd,yyyy h:MM tt"));
	    	 $('#end_time ').val(dateFormats(localendtime, "mmm dd,yyyy h:MM tt"));   	 
		  
	});
	$('.event_range').datepicker({
		changeMonth: true,
	    changeYear: true,
	    yearRange: date.getFullYear()+" : 2044",
	    minDate: "+0d",
	    dateFormat: "M dd, yy",	    
	    onSelect: function (date) {
	        $( ".event_range" ).trigger( "blur" );
	        //$('#end_date').val(moment(date).add(6, 'months').format("MMM d, YYYY"))
	    }    
	});
	$("#event_tags").select2({
       placeholder: "Select tags",
    }).on("select2-selecting", function(e) {           
            console.log(e.val);
    })
 
	 
	 $( "#section_list" ).on( "click", ".event-file-remove", function(e) {
		e.preventDefault();	
		var type = $(this).data('type');
		if(type=='weekly')
			event_id =  $("#selected_event").attr("event_id"); 
		else
			event_id =  $("#selected_event").attr("_id"); 
		path = base_url+'admin/events/removeimage/'+event_id;		 
		$.ajax({
	        type		: "POST",
	        url		: path,	
	        data		: {'url':$(this).data('url'),'type':$(this).data('type'),'forceedit': $('#forceedit').is(":checked")},
	        dataType: "json",	      
	        success	: function(resp){
	        	          	    	 
	        	 
	        },
	         complete : function (){
	          	location.reload();
	         }
        });           
	});
	$( "#section_list" ).on( "click", ".venue-file-remove", function(e) {
		e.preventDefault();	
		venue_id = $("#selected_venue").attr("venue_id");	
		path = base_url+'admin/venue/removeimage/'+venue_id;		 
		$.ajax({
	        type		: "POST",
	        url		: path,	
	        data		: {'url':$(this).data('url')},
	        dataType: "json",	      
	        success	: function(resp){
	        	          	    	 
	        	 
	        },
	         complete : function (){
	         	 location.reload();
	         }
        });           
	});
	$( "#section_list" ).on( "click", ".view-buttoncal", function(e) {
		e.preventDefault();	
		$(this).siblings().removeClass('fc-state-active')
    	$(this).addClass('fc-state-active')
		preparehashtags('view',$(this).data('view'),false)   
		    
	})
	$(".page-content" ).on( "click",'.rest_btn', function(e) {
		e.preventDefault();
		//$('#ticket_type').trigger('change');
		//$('#ticket_type')[0].selected = true;
 
		
		
		$('#ticket_type').val('reservation').trigger('change');
		javascript:$('#ticket-form')[0].reset();
		if($('#chk_adminfee').is(':checked')){			
			$("#admin_fee").attr("disabled",false);
		}else{
			$("#admin_fee").attr("disabled",true);
		}
		if($('#chk_tax').is(':checked')){			
			$("#tax").attr("disabled",false);
		}else{
			$("#tax").attr("disabled",true);
		}
		 if($('#chk_tip').is(':checked')){			
			$("#tip").attr("disabled",false);
		}else{
			$("#tip").attr("disabled",true);
		}
	})
	
	$("#btnLoadLongLat").click(function(e){
		e.preventDefault();	
	    //http://maps.google.com/maps/api/geocode/json?address=
	    var url = "https://maps.google.com/maps/api/geocode/json?address=";
	    url+= $("#venue_address1").val() + ",";
	    url+= $("#venue_address2").val() + ",";
	    url+= $("#venue_city").val() + ",";
	    //url+= $("#venue_state").val() + ",";
	    url+= $("#venu_zip").val();
	     console.log(url,'url')
	    $.get(url, {}).done(function( data )
	    {  
	    	console.log(data,'data')
	    	$("#invalidaddress").html('')
	        if(data.status=="OK"){
	        	 $("#venue_long").val(data.results[0].geometry.location.lng);
	        	 $("#venue_lat").val(data.results[0].geometry.location.lat);
	        }
	        else{
	        	 $("#invalidaddress").html('Invalid Address ')
	        }
	    });
      
   });
   $(".page-content" ).on( "click",'.newconfirmation', function(e) {
		e.preventDefault();
		var resp={};
		val =parseInt($('#cnfm_count').val())+1
		resp.count=val;		
		$('#cnfm_count').val(val)
		if($('#cnfm_count').val()>0)
			$('#cnfm_ids').val($('#cnfm_ids').val()+val+',')
		var html =  new EJS({url: base_url+'assets/js/ejs/newconfirmations.ejs?v='+ $.now()}).render(resp);	    	        		      
	    $('#newconfirmationsarea').append(html);		
	})
	$(".page-content").on( "click",'.removeconfirmation', function(e) {
		 var id = $(this).data('id');
		 var str = $('#cnfm_ids').val();
		 $('#cnfm_ids').val(str.replace(id+',', '')); 
		 
		 $('#cnfm_count').val(val)
		 $(this).parent().parent().remove();
	})
	if($("#ticket_type" ).val()=='reservation'){
		$('#termsandcond').hide();
		$('#deposit').attr('disabled',true)
	}else if($("#ticket_type" ).val()=='booking'){
		$('#termsandcond').show();
		$('#deposit').attr('disabled',false)
	}
	else{
		$('#deposit').attr('disabled',true)
		$('#termsandcond').show()
	}
	
	
	$(".page-content" ).on( "change",'#ticket_type', function(e) {
		  if($(this).val()=='purchase'){
		  	$('#termsandcond').show();
		  	$('#deposit').attr('disabled',true)
		  }else if($(this).val()=='booking'){
		  	$('#termsandcond').show();
			$('#deposit').attr('disabled',false)
		  }		  
		  else{
		  	$('#termsandcond').hide();
		  	$('#deposit').attr('disabled',true)
		  }
		  $( "#deposit" ).trigger( "blur" );
	})
	
	if($('#price').val()>0)
	 	calculateTotal()
	$('.page-content').on('blur','#price', function() {
	   calculateTotal()
	});
	$('.page-content').on('blur','#admin_fee', function() {
		if($("#chk_adminfee").is(':checked'))
	   	  calculateTotal()
	});
	$('.page-content').on('blur','#tax', function() {
		if($("#chk_tax").is(':checked'))
	   		calculateTotal()
	});
	$('.page-content').on('blur','#tip', function() {
		if($("#chk_tip").is(':checked'))
	   		calculateTotal()
	});
	$('.page-content').on('click','#chk_adminfee', function() {
		if($(this).is(':checked')){
			
			$("#admin_fee").attr("disabled",false);
		}else{
			$("#admin_fee").attr("disabled",true);
		}
	    calculateTotal()
	});
	$('.page-content').on('click','#chk_tax', function() {
	    if($(this).is(':checked')){
			
			$("#tax").attr("disabled",false);
		}else{
			$("#tax").attr("disabled",true);
		}
		calculateTotal()
	});
	$('.page-content').on('click','#chk_tip', function() {
	    if($(this).is(':checked')){
			
			$("#tip").attr("disabled",false);
		}else{
			$("#tip").attr("disabled",true);
		}
		calculateTotal()
	});
});

function prepareSortinghashtags(field,value){ 
   //var clearoffset = true;
   if(hasher.getHash()){
   		var stat = true;
   	 	var s= hasher.getHashAsArray();
   	    text ='';   	 
   	 	for	(var index = 0; index < s.length; index++) {
		    cr = s[index];
		    str = cr.split("=")
		    
		    if(str[0] == 'sort_field'){		    	 
		    	stat = false;
		    	s[index] ='sort_field='+field;
		    }
		    if(str[0] == 'sort_val'){		    	 
		    	stat = false;
		    	s[index] ='sort_val='+value;
		    }
		   
		     text += s[index]+'/';
		
   	    }
   	  if(stat){	
		text += 'sort_field='+field+'/sort_val='+value+'/' 
	  }
	  text = text.replace(/\/\s*$/, "");
   	  hasher.setHash(text) 
   }else{
   	//sort_field=created_at/sort_val=asc
   	 hasher.setHash('sort_field='+field+'/sort_val='+value) 
   }
  
}

function preparehashtags(key,val,clearoffset){ 
   //var clearoffset = true;
   if(hasher.getHash()){
   		var stat = true;
   	 	var s= hasher.getHashAsArray();
   	    text ='';   	 
   	 	for	(var index = 0; index < s.length; index++) {
		    cr = s[index];
		    str = cr.split("=")
		    console.log(str[0])
		    if(str[0] == 'offset' && clearoffset== true){
		    	console.log(str[0],000)
		    	//stat = false;
		    	s[index] ='offset='+1;
		    }
		   
		    if(str[0] == key){
		    	
		    	stat = false;
		    	s[index] =key+'='+val
		    }
		     text += s[index]+'/';
		
   	    }
   	  if(stat){	
		text += key+'='+val+'/' 
	  }
	  text = text.replace(/\/\s*$/, "");
   	  hasher.setHash(text) 
   }else{
   	 hasher.setHash(key+'='+val) 
   }
  
}

function getEventResponse(viewmode,gotodate){
	 
	$('#toolbar').show();	
	$('#test-heading').show();	   
	$('.modal-backdrop').remove();
	$("#event_detail" ).hide();
	$("#cal_viewchanger" ).show();
	$("#search_cntr" ).hide();
	$('#data-list').empty();
	$('#data-list').fullCalendar('destroy'); 
	$('#data-list').fullCalendar('render'); 
	 
	$('#data-list').fullCalendar({
		header: {
			left: 'prev,next today',
			center: 'title',
			right: ''
		},	
		defaultView:viewmode,	
		defaultDate: new Date(),
		editable: true,
		eventLimit: true, 
		eventClick:  function(event, jsEvent, view)
		{ 
		   //console.log(event);return;
		   if(event.event_type=='weekly'){
		   		$.confirm({
				    text: "Do you want to edit the series or only this date ?",
				    title: "Confirmation required",
				    confirm: function(button) {			       
				      hasher.setHash('event-weekly='+event.event_id);
				    },
				    cancel: function(button) {
				         hasher.setHash('event-special='+event._id);
				    },
				    confirmButton: "Edit series",
				    cancelButton: "Only this date",
				    post: true
				});
		   }else{
		   		hasher.setHash('event-special='+event._id);
		   }
		    
			   	    			 
	    },
		loading: function(bool) {
			 
			$('#loading').toggle(bool);	
			
		},
		viewRender:function(view, element)
		{
			 
			$("#loading").css("display","block");
			$("#data-list").fullCalendar('removeEvents');						
			updateCalData();
			 var add_url = '<a class="tip add-task" title="" href="#"\n\
		        data-original-title="Dodaj zadanie"><i class="icon-plus"></i>madan</a>'; 
		    
					 
		},
	 	dayClick: function(date, allDay, jsEvent, view) {
	 		 
             tday =    new Date(date);
             tday.setDate(tday.getDate() + 1);
             console.log(tday,'ssssssssssss')
			 $.confirm({
			    text: "Do you want to add events to this date ?",
			    title: "Add new event to "+convertToServerTimeZone1(tday),
			    confirm: function(button) {		
			      date1 =	new Date(date);
			     // date1 =  convertToServerDate(date,'mm-dd-yy');    
			      window.location.href = base_url+'admin/events/create_event/'+date1.format(date.toISOString());
			    },
			    cancel: function(button) {
			         
			    },
			    confirmButton: "Yes",
			    cancelButton: "No",
			    post: true
			});
	   	},
		 
	});  
	if(gotodate){
		 		$('#data-list').fullCalendar('gotoDate',new Date(gotodate));
		 	} 		
}

function updateCalData()
{
	view = $("#data-list").fullCalendar('getView');
 
	var dateObj = view.start._d;
	var month = dateObj.getUTCMonth() + 1; //months from 1-12
	var day = dateObj.getUTCDate();
	var year = dateObj.getUTCFullYear();
	day = (day < 10)?"0" + String(day):String(day);
	month = (month < 10)?"0" + String(month):String(month);
	var whole_start_date = String(month) + "-" + String(day) + "-" + String(year);
	//console.log("whole_start_date " + whole_start_date)



	var dateObj = view.end._d;
	var month = dateObj.getUTCMonth() + 1; //months from 1-12
	var day = dateObj.getUTCDate();
	var year = dateObj.getUTCFullYear();
	day = (day < 10)?"0" + String(day):String(day);
	month = (month < 10)?"0" + String(month):String(month);
	var whole_end_date = String(month) + "-" + String(day) + "-" + String(year);
	//console.log("whole_end_date " + whole_end_date)

	//eventsadminwithdate
    timezone = "Asia/Jakarta"
	
	if((new Date()).getTimezoneOffset() == 240)
	{

	}

	if((new Date()).getTimezoneOffset() == 420)
	{
		//timezone = "America/Los_Angeles"
	}

	var sort_status = (dtext.sort_status == undefined)?"published":dtext.sort_status;

	$.get( base_url+'admin/events/cal_view/'+ whole_start_date + "/" + whole_end_date + "?sort_status=" + sort_status, function( cal_data )
	{
 
		data =  jQuery.parseJSON(cal_data); 
		for (var i = 0; i < data.length; i++)
		{
			//data[i].start = new Date(new Date(data[i].start_datetime));
			//data[i].end = new Date(new Date(data[i].end_datetime));
			data[i].start = moment(data[i].start_datetime).tz(timezone).format()
			data[i].end = moment(data[i].end_datetime).tz(timezone).format()
			//console.log("data[i].start_datetime");
			//console.log(data[i].start_datetime)
			
		};
		//console.log("after");
		//console.log(data);

		$("#data-list").fullCalendar('removeEvents')
		$("#data-list").fullCalendar('addEventSource',data, true)
		$("#loading").css("display","none");
	});

}


function getDataResponse(){
	 
	//var pathArray = url1.split( '/' );
	//$('.panel-body').data('page',pathArray[pathArray.length-1]);	
	$('<div class="modal-backdrop fade in" ><img class="loading-gif" src="'+base_url+'assets/images/load.gif"/></div>').appendTo(document.body);	 
    var section = $('#section_list').data('section');
     
    
	 
    var darr = hasher.getHashAsArray();   
    dtext = {};  	 
 	for	(var index = 0; index < darr.length; index++) {
	    dkey = darr[index];
	    keyval = dkey.split("=");
	    dval   = keyval[1]?keyval[1]:'';
	    //dtext +="'"+keyval[0]+"':"+dval+",";	  	
	    dtext[keyval[0]]= dval;
    }
   
    switch (section) {
	    case 'chat':
	        url = base_url+'admin/chat/ajax_list';
	        ejs = 'chat_list.ejs';
	        exp_uri= 'admin/chat/export';
	        break;
	    case 'user':	    	
	        if(typeof(dtext.view_type)=='undefined'||dtext.view_type=='list'){
	        	 url = base_url+'admin/user/ajax_list';
	       		 ejs = 'user_list.ejs';
	        }else if(dtext.view_type=='grid'){
	        	url = base_url+'admin/user/ajax_list';
	       	 	ejs = 'user_grid.ejs';
	        }
	        exp_uri= 'admin/export/user';	       
	        break;
	   case 'venue':
	        url = base_url+'admin/venue/ajax_list';
	        ejs = 'venue_list.ejs';
	        exp_uri= 'admin/export/venues';
	        break;
	   case 'hostings':
	        url = base_url+'admin/hostings/ajax_list';
	        ejs = 'hosting_list.ejs';
	        exp_uri= 'admin/hostings/export';
	        break;
	   case 'adminuser':
	        url = base_url+'admin/adminuser/ajax_list';
	        ejs = 'adminuser_list.ejs';
	        exp_uri= 'admin/adminuser/export';
	        break;
	   case 'blocklists':
	        url = base_url+'admin/blocklists/ajax_list';
	        ejs = 'block_list.ejs';
	        exp_uri= '';
	        break;
	   case 'events':
	        url = base_url+'admin/events/ajax_list';
	        ejs = 'event_list.ejs';
	        exp_uri= '';
	        break;
	   case 'tickets':
	        url = base_url+'admin/tickets/ajax_list';
	        ejs = 'ticket_list.ejs';
	        exp_uri= '';
	        break;
	    case 'orders':
	        url = base_url+'admin/orders/ajax_list';
	        ejs = 'order_list.ejs';
	        exp_uri= 'admin/export/orders'
	        break;	        
	}
	setHostingfilter(dtext);
	
   	if(section=='events'&& (dtext.view_type=='calendar' || typeof(dtext.view_type)=='undefined')){
   		
   		var deafult_view ='basicWeek';
   		var goTodate= '';
   		if(dtext.view =='basicWeek' || dtext.view =='month' || dtext.view =='basicDay' ){
   			deafult_view=dtext.view;
   		}
   			
   		//start_date 
   		var timestamp=Date.parse(dtext.start_date)		
		if (isNaN(timestamp)==false)
		{
		   goTodate= dtext.start_date;
		
		} 
		
		$('#cal_viewchanger').find('.'+deafult_view).addClass('fc-state-active')	
		
   		getEventResponse(deafult_view,goTodate); 
   		console.log(goTodate,'goTodate',start_date);
   		  
	 	 
   		return;
   	}  
   	dtext.startDate_iso = convertToServerTimeZone(new TimeShift.OriginalDate(start_date+" 00:00:00"));
   	dtext.endDate_iso = convertToServerTimeZone(new TimeShift.OriginalDate(end_date+" 23:59:00"));
   	if(section=='events'){
   		if(typeof(dtext.sort_status)=='undefined'|| dtext.sort_status=='upcoming'){
   			sort_status_val = "published"
   			/*
   			sort_status_val= 'upcoming';
   			dtext.startDate_iso = convertToServerTimeZone(new TimeShift.OriginalDate(moment().format("MM/DD/YYYY")+" 00:00:00"));
   			dtext.endDate_iso = convertToServerTimeZone(new TimeShift.OriginalDate( moment().add(6, 'months').format("MM/DD/YYYY")+" 23:59:00"));

            console.log("endDate_iso :: " + dtext.endDate_iso)
            */
   		}
   		 
   		else{
   			sort_status_val= dtext.sort_status;
   		}
   		$("#sort_status").select2("val", sort_status_val);	
   		$('#data-list').removeClass().addClass('row'); 		 
   	} 
   	//{'event-id':resp._id,'event_type':type,'is_recurring':type=='weekly'?1:0}
    
   	//startDate_iso =new TimeShift.OriginalDate($.datepicker.formatDate('mm/dd/yy ',seldate.start)+"00:00:00"), 
   // endDate_iso = new TimeShift.OriginalDate($.datepicker.formatDate('mm/dd/yy ',seldate.end)+"23:59:00"),
   
   /*
   url = "http://partyhostappdev.herokuapp.com/admin/admin/chat/list?admin_token=dsabalsdbaiyzVYVKJD78t87tgBQGK9sfhkslhfdksCFCJjgvgKV98y98h90z3pd&per_page=25&offset=1&sort_field=last_updated&sort_val=DESC&start_date=2014-04-01T04:00:00.000Z&end_date=2015-08-25T03:59:00.000Z"
   */

    $('.export_options').click(function()
                {
                    //preparehashtags('export',"true",true);
                   // window.open(window.location);
                   //preparehashtags("anchor","true")
                   console.log("yes!!")
                })
    


   console.log("URL : " + url)
   console.log("data");
   console.log(dtext)
   




    $.ajax({
        type		: "POST",
        url		: url,	
        dataType: "json",		   
        data		: dtext,
        success	: function(resp){ 
        	
            console.log(resp);

            if(dtext.is_recurring == "1")
		   {
		   	resp.event_type = "weekly";
		   }
		   if(dtext.is_recurring == "0")
		   {
		   	resp.event_type = "special";
		   }
            //return;
        	resp['siteurl'] = base_url; 
        	var html =  new EJS({url: base_url+'assets/js/ejs/'+ejs+'?v='+ $.now()}).render(resp);
        	//var html =  new EJS({url: base_url+'assets/js/ejs/'+ejs}).render(resp);	
        	$('#test-heading').show();
    		$('#toolbar').show();
    		$("#cal_viewchanger" ).hide();
			$("#search_cntr" ).show();
            $('#data-list').html(html)
			$('.modal-backdrop').remove();
			$('#paginate_count').html('');
			if(resp.current_controller=='hostings'){
				$('#hostingsdata-search').val(resp.data_search);
			}
			if (typeof(resp.data_search) == "undefined"){
				if(dtext.hasOwnProperty('search_name'))
					$('#data-search').val(dtext.search_name);
			}else{
				$('#data-search').val(resp.data_search);
			}
			if (typeof(resp.paginate) != "undefined"){
				$("#sort-list").select2("val", resp.paginate.per_page);			
				$('#paginate_link').bootpag({
				    total: resp.paginate.total_page,
				    page: resp.paginate.offset,
				    maxVisible: 5,
				    leaps: true,
				    firstLastUse: true,
				    first: '←',
				    last: '→',		    
				}).on("page", function(event, num)
				{
				     preparehashtags('offset',num,false)
		             //getDataResponse(base_url+'admin/chat/ajax_list','chat');
				}); 
			}
			if(resp.current_controller=='tickets'){
				$('#toolbar').hide();	
				$('#addnewobj').attr('href',resp.add_url)
			}
			if(hasher.getHash()){
				//$('.export_options').attr('href',base_url+exp_uri+'/?'+hasher.getHash()+'/start_date='+dtext.startDate_iso+'/end_date='+dtext.endDate_iso); 
			}else{
				//$('.export_options').attr('href',base_url+exp_uri+'/?start_date='+dtext.startDate_iso+'/end_date='+dtext.endDate_iso);
			}
			//$('.export_options').attr('href',base_url+exp_uri+'/?'+hasher.getHash());  
			 
        },
        complete : function (){
        	 
        	if(parseInt($('.dataTable').find('tbody').find('tr').length) < parseInt($('#toolbar').find('.select2-choice').find('.select2-chosen').html())){
				$('#paginate_count').html($('.dataTable').find('tbody').find('tr').not('.noresult').length);			
				 
			}else{				 
				$('#paginate_count').html($('#toolbar').find('.select2-choice').find('.select2-chosen').html());
			}	
            $('#data-list').find(".dataTable th").each(function(){
                $(this).removeClass('sorting_desc').removeClass('sorting_asc');               
                if($(this).attr('data-field')==$('.panel-body').attr('sort_field')){
                    $(this).addClass('sorting_'+$('.panel-body').attr('sort_val'))
                }
         				
            });
            
            $(".confirm").confirm({
			    text: "Are you sure you want to delete this user?",
			    title: "Confirmation required",
			    confirm: function(button) {			       
			       window.location.href = button.attr('data-url');
			    },
			    cancel: function(button) {
			        
			    },
			    confirmButton: "Yes",
			    cancelButton: "No",
			    post: true
			});
			var $container = $('#img-tems');
			 
			
        }
    });

    dtext["export"] = "true"
    $.ajax({
        type        : "POST",
        url     : url,  
        dataType: "json",          
        data        : dtext,
        success : function(resp){ 
            console.log(">>>>>>")
            console.log(resp);
            //$('.export_options').attr('href',base_url+'admin/user/users#/'+hasher.getHash() +"/export=true");

            $('.export_options').attr('href',resp.url + "&export=true");
        },
        complete : function (){
            console.log("done!!!!")
        }
    });

}
function setHostingfilter(dtext){
	//console.log()
	if(dtext.hasOwnProperty('search_venue')) 
		$('#search-venue').select2('val',dtext.search_venue);
   	else   
   		$('#search-venue').select2('val','');
	
	if(dtext.hasOwnProperty('search_promoter'))    	   
   	   $('#search-promoter').select2('val',dtext.search_promoter);
   	else
   	   $('#search-promoter').select2('val','');
   	
   	if(dtext.hasOwnProperty('search_verified')) 
   	   $('#search-verified').select2('val',dtext.search_verified);
   	else
   	   $('#search-verified').select2('val','');
	
	if(dtext.hasOwnProperty('search_recurring'))    	
   	   $('#search-recurring').select2('val',dtext.search_recurring);
   	else
   	   $('#search-recurring').select2('val','');
}
function getUserDetailPage(url,type){
	$.ajax({
    type		: "POST",
    url		: url,	
    dataType: "json",		   
    data		: '',
    success	: function(resp){
    	resp['siteurl'] = base_url; 
        if(type== 'detail')	
         	ejsfile ='user_detail.ejs';
        else 
        	ejsfile ='user_image.ejs';
    	 
    	if(!resp.error){
    		if(resp.user.invited_by){
    			$.get( base_url+'admin/user/getUserProfile/'+ resp.user.invited_by, function( user_data )
				{
					resp['invited_by']= jQuery.parseJSON(user_data); 
					var html =  new EJS({url: base_url+'assets/js/ejs/'+ejsfile+'?v='+ $.now()}).render(resp);		
					$('#data-list').html(html)             
					$('.modal-backdrop').remove();	
					enableUserdetailEdit();	
				});
    		}else{
    			resp['invited_by']='';
    			var html =  new EJS({url: base_url+'assets/js/ejs/'+ejsfile+'?v='+ $.now()}).render(resp);
    			$('#data-list').html(html)             
				$('.modal-backdrop').remove();
    		}
    		
			
    	}else{
    		var html =  new EJS({url: base_url+'assets/js/ejs/error.ejs'}).render(resp);
    		$('#data-list').html(html) 
    	}
    	
    	//var html =  new EJS({url: base_url+'assets/js/ejs/'+ejs}).render(resp);	
    	$('#test-heading').hide();	
    	$('#toolbar').hide();	        		      
        
		 
    },
    complete : function (){
    	 enableUserdetailEdit();
	        
    }
    
  })
}	
function enableUserdetailEdit(){
	$.fn.editable.defaults.mode = 'inline';
         $('.edit_user').editable({
			         type: 'text',
			         pk: 1,
			         url: base_url+'admin/user/'+'save'+'/'+$("#selected_user").attr("user_fb_id"),
			         emptytext: 'Please enter text'
			    });
				 
				$('#fname,#lname').editable({
	         type: 'text',
	         pk: 1,
	         url: base_url+'admin/user/'+'save'+'/'+$("#selected_user").attr("user_fb_id"),
	         emptytext: 'Name',
	         validate: function(value) {
			     if(!$.trim(value)) return 'Required field!';
			}
	    });
        /*
	    $('#fbid').editable({
	         type: 'text',
	         pk: 1,
	         url: base_url+'admin/user/'+'save'+'/'+$("#selected_user").attr("user_fb_id"),
	         emptytext: 'Facebook ID',
	         validate: function(value) {
			    if(!(value+"").match(/^\d+$/) ) {
			        return 'Please enter valid Facebook ID';
			    }
			}
	    }); 
	    $('.user-detail').find('#email').editable({
	         type: 'text',
	         pk: 1,
	         url: base_url+'admin/user/'+'save'+'/'+$("#selected_user").attr("user_fb_id"),
	         emptytext: 'Email',        
			 success: function(msg, config) {		 	
			 	var data = jQuery.parseJSON(msg); 		 	 
			    if(data.status=='error'){ 		    	
			    	 return 'Please enter a valid email address';		     
			    }else if(data.status=='taken')  {
			    	return 'Email address is already used';
			    }  else{}           
			}
	    });
	    $('.user-detail').find('#phone').editable({
	         type: 'text',
	         pk: 1,
	         url: base_url+'admin/user/'+'save'+'/'+$("#selected_user").attr("user_fb_id"),
	         emptytext: 'Phone',
	         validate: function(value) {         	
			    if(!(value+"").match(/^\d+$/) ) {
			        return 'Please enter valid Phone number';
			    }
			    if(value.length!=10) {
			        return 'Please enter valid Phone number';
			    }
			}
	    });
        $('#dob').editable({
            url: base_url+'admin/user/'+'save'+'/'+$("#selected_user").attr("user_fb_id"),
            validate: function(value) {
                var startDate = moment();
                var endDate = moment(value);
                
                if ( endDate > startDate) {
                  return 'Please enter valid birth date'
                }
                
                  
               
            }
        });
        */
	     $('.datepicker,.datepicker_event').on('changeDate', function(ev){      
	            $(this).datepicker('hide');
	             $( "[data-rel=datepicker]" ).trigger( "blur" );
	    });
	    

	    
	    $("[name='is_verified_host'],[name='suspend_user'],[name='gender']").bootstrapSwitch();
	    $("input[name='gender']")
	    .on('switchChange.bootstrapSwitch',
	        function(event, state) {  
	        	showloader(); 
	        	          
	            if($("#selected_user").attr("user_id")!=null){
	                $.ajax({
	                    type: "POST",
	                    url: base_url+'admin/user/'+'save'+'/'+$("#selected_user").attr("user_fb_id"),
	                    data: {
	                        value : this.value, 
	                        name:this.name
	                        },
	
	                    success: function(msg) {
	                        $("#data_error").text("");	
	                        $('.modal-backdrop').remove();		                        
	                    }
	                });
	            }
	        });
	     $("input[name='is_verified_host']")
	    .on('switchChange.bootstrapSwitch',
	        function(event, state) {
	        	showloader();
	            var v = (state== true)?1:0; 
	            var name = $(this).attr('name');	            		 
	            var url_link = base_url+'admin/user/'+'save'+'/'+$("#selected_user").attr("user_fb_id"); 
	            if($("#selected_user").attr("user_fb_id")!=null){
	                $.ajax({
	                    type: "POST",
	                    url: url_link,
	                    data: {
	                        value : v,
	                        name:name  
	                        
	                        },
	
	                    success: function(msg) {
	                    	$('.modal-backdrop').remove();	
	                    }
	                });
	            }        
	        });
	    
	    $("input[name='suspend_user']")
	    .on('switchChange.bootstrapSwitch',
	        function(event, state) {
	        	showloader();
	             var url_link = base_url+'admin/user/'+'save'+'/'+$("#selected_user").attr("user_fb_id");         
	            var v = (state== true)?0:1; 	
	             var name = $(this).attr('name');
	            if($("#selected_user").attr("user_id")!=null){
	                $.ajax({
	                    type: "POST",
	                    url: url_link,
	                    data	: {
	                         value : v,
	                        name:name  
	                        },
	
	                    success: function(msg) {
								$('.modal-backdrop').remove();	
	                    }
	                });
	            }     
	        });
	        enableDeleteButton();
}
function getHostingDetailPage(url){
	$('<div class="modal-backdrop fade in" ><img class="loading-gif" src="'+base_url+'assets/images/load.gif"/></div>').appendTo(document.body);
	$.ajax({
	    type		: "POST",
	    url		: url,	
	    dataType: "json",		   
	    data		: '',
	    success	: function(resp){
	    	resp.data['siteurl'] = base_url;  
	    	if(!resp.error)	        	 
	    	var html =  new EJS({url: base_url+'assets/js/ejs/hosting_detail.ejs?v='+ $.now()}).render(resp.data);
	    	else
	    	var html =  new EJS({url: base_url+'assets/js/ejs/error.ejs?v='+ $.now()}).render(resp);
	    	//var html =  new EJS({url: base_url+'assets/js/ejs/'+ejs}).render(resp);	
	    	$('#test-heading').hide();	
	    	$('#toolbar').hide();	        		      
	        $('#data-list').html(html)             
			$('.modal-backdrop').remove();
			 
	    },
	    complete : function (){
	    	 $.fn.editable.defaults.mode = 'inline';
	         $("[name='is_recurring'],[name='is_verified_table'],[name='visible']").bootstrapSwitch();
		     $("input[name='is_recurring'],[name='is_verified_table'],[name='visible']")
		     .on('switchChange.bootstrapSwitch',
		        function(event, state) {
		        	showloader();
		            var state_value = 0;
		            var name = $(this).attr('name');
		            if(state){
		                state_value = 1;
		            }  
		            if($("#selected_hosting").attr("hosting_id")!=null){
		            	console.log(this)
		            	 
		                $.ajax({
		                    type: "POST",
		                    url: base_url+'admin/hostings/'+'save'+'/'+$("#selected_hosting").attr("hosting_id"),
		                    data: {
		                        value : state_value, 
		                        hosting:$("#selected_hosting").attr("hosting_id"),
		                        name:name
		                    },
		
		                    success: function(msg) {
		                    	$('.modal-backdrop').remove();
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
		      $(".edit_host, #user_image_url").editable({
		            type: 'text',
		            pk: 0,
		            url: base_url+'admin/hostings/'+'save'+'/'+$("#selected_hosting").attr("hosting_id"),
		            success: function(msg) {  
		                    
		                if(msg == "D") {
		                    //$("#data_error").text("Venue already booked");
		                } else if(msg == "F") {
		                    $("#data_error").text("Updated Failed");
		                } else {
		                    $("#p_"+$(this).attr('id')).html(msg);
		                    $("#data_error").text("");
		                }
		            } 
		    });
		    var obj_rank = [];
		
		
			for (i = 1; i <= 100; i += 1) {  
			    tmp = {'value': i,'text': i };
			    obj_rank.push(tmp);
			}
			
		    $('#view_rank').find('#rank').editable({
				source:obj_rank,
				 type: 'text',
		         pk: 0,
				url: base_url+'admin/hostings/'+'save'+'/'+$("#selected_hosting").attr("hosting_id"),
		        success: function(response) {  
		            $('.save_error').html('');
		            if(response!="success"){
		                $('.save_error').html(response);
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
		        url: base_url+'admin/hostings/'+'save'+'/'+$("#selected_hosting").attr("hosting_id"),
		        validate: function(value) {
		        	var startDate = moment();
					var endDate = moment(value);
					
					if ( endDate < startDate) {
					  return 'Please enter valid Hosting date'
					}
					
				       // return 'Please enter valid h';
				   
				}
		       
		    });
		    
		    $('#host_description').editable({
		        url: base_url+'admin/hostings/'+'save'+'/'+$("#selected_hosting").attr("hosting_id"),
		        showbuttons: 'bottom'
		    });

		    enableDeleteButton();
		        
	    }
    
   })
}

function getHostingRecurringDetailPage(url){
	$('<div class="modal-backdrop fade in" ><img class="loading-gif" src="'+base_url+'assets/images/load.gif"/></div>').appendTo(document.body);
	$.ajax({
	    type		: "POST",
	    url		: url,	
	    dataType: "json",		   
	    data		: '',
	    success	: function(resp){
	    	resp.data['siteurl'] = base_url;  
	    	if(!resp.error)	        	 
	    	var html =  new EJS({url: base_url+'assets/js/ejs/hosting_recurring_detail.ejs?v='+ $.now()}).render(resp.data);
	    	else
	    	var html =  new EJS({url: base_url+'assets/js/ejs/error.ejs?v='+ $.now()}).render(resp);
	    	//var html =  new EJS({url: base_url+'assets/js/ejs/'+ejs}).render(resp);	
	    	$('#test-heading').hide();	
	    	$('#toolbar').hide();	        		      
	        $('#data-list').html(html)             
			$('.modal-backdrop').remove();
			 
	    },
	    complete : function (){
	    	 $.fn.editable.defaults.mode = 'inline';
	         $("[name='is_recurring'],[name='is_verified_table'],[name='visible']").bootstrapSwitch();
		     $("input[name='is_recurring'],[name='is_verified_table'],[name='visible']")
		     .on('switchChange.bootstrapSwitch',
		        function(event, state) {
		        	showloader();
		            var state_value = 0;
		            var name = $(this).attr('name');
		            if(state){
		                state_value = 1;
		            }  
		            if($("#selected_hosting").attr("hosting_id")!=null){
		            	 
		            	 
		                $.ajax({
		                    type: "POST",
		                    url: base_url+'admin/hostings/'+'save_rec'+'/'+$("#selected_hosting").attr("hosting_id"),
		                    data: {
		                        value : state_value, 
		                        hosting:$("#selected_hosting").attr("hosting_id"),
		                        name:name
		                    },
		
		                    success: function(msg) {
		                    	$('.modal-backdrop').remove();
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
		     
		    
		    $('#host_description').editable({
		        url: base_url+'admin/hostings/'+'save_rec'+'/'+$("#selected_hosting").attr("hosting_id"),
		        showbuttons: 'bottom'
		    });

		    enableDeleteButton();
		        
	    }
    
   })
}
function getVenueDetailPage(url,type){
	$.ajax({
	    type		: "POST",
	    url		: url,	
	    dataType: "json",		   
	    data		: '',
	    success	: function(resp){
	    	resp['siteurl'] = base_url;  
	    	if(type== 'detail')	
         		ejsfile ='venue_detail.ejs';
        	else 
        		ejsfile ='venue_image.ejs'; 
	    	if(!resp.error)	        	 
	    	var html =  new EJS({url: base_url+'assets/js/ejs/'+ejsfile+'?v='+ $.now()}).render(resp);
	    	else
	    	var html =  new EJS({url: base_url+'assets/js/ejs/error.ejs?v='+ $.now()}).render(resp);
	    	//var html =  new EJS({url: base_url+'assets/js/ejs/'+ejs}).render(resp);	
	    	$('#test-heading').hide();	
	    	$('#toolbar').hide();	        		      
	        $('#data-list').html(html)             
			$('.modal-backdrop').remove();
			 
	    },
	    complete : function (resp){
	    	 $.fn.editable.defaults.mode = 'inline';
	         $("[name='venue_status']").bootstrapSwitch();
		    $("input[name='venue_status']")
		    .on('switchChange.bootstrapSwitch',		    
		        function(event, state) {
		        	showloader();
		            var state_value = 0;
		            var name = $(this).attr('name');
		            if(state){
		                state_value = 1;
		            }  
		            
		            if($("#selected_venue").attr("venue_id")!=null){
		                $.ajax({
		                    type: "POST",
		                    url: base_url+'admin/venue/'+'save'+'/'+$("#selected_venue").attr("venue_id"),
		                    data: {
		                        value : state_value,
		                        name:name
		                    },
		
		                    success: function(msg) {
								$('.modal-backdrop').remove();
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
				url: base_url+'admin/venue/'+'save'+'/'+$("#selected_venue").attr("venue_id"),
		        success: function(response) { 
		        	response =  jQuery.parseJSON(response);		            	 
	                $('.save_error').html('');
	                 if(response.success!=true){		                 	 
	                 	return response.reason;			                
		            }
		        } 
			});
		   $(".edit_venue").editable({
		            type: 'text',
		            pk: 1,
		            url: base_url+'admin/venue/'+'save'+'/'+$("#selected_venue").attr("venue_id"),
		            success: function(response) {  
		            	response =  jQuery.parseJSON(response);		            	 
		                $('.save_error').html('');
		                 if(response.success!=true){		                 	 
		                 	return response.reason;			                
			            }
		            } 
		   });
		   $(".edit_venue_zip").editable({
		            type: 'text',
		            pk: 1,
		            url: base_url+'admin/venue/'+'save'+'/'+$("#selected_venue").attr("venue_id"),
		            validate: function(value) {         	
					    if(!(value+"").match(/^\d+$/) ) {
					        return 'Please enter valid zip';
					    }
					    if(value.length!=5) {
					        return 'Please enter valid zip';
					    }
					},
				    success: function(response) {  
		            	response =  jQuery.parseJSON(response);		            	 
		                $('.save_error').html('');
		                 if(response.success!=true){		                 	 
		                 	return response.reason;			                
			            }
		            } 
		            
		   });
		   $(".edit_venue_url").editable({
		            type: 'text',
		            pk: 1,
		            url: base_url+'admin/venue/'+'save'+'/'+$("#selected_venue").attr("venue_id"),
		            emptytext: 'Url'/*,
			         validate: function(value) {         	
					    
                        if(!(value+"").match(/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/) && value !=''  ) {
					        return 'Please enter valid url. eg http://google.com';
					    }
					    
					}*/
		   });
		   $('#neighborhood').editable({
			   	type: 'text',
		        pk: 1,
		        value: resp.responseJSON.data.neighborhood,
		        url: base_url+'admin/venue/'+'save'+'/'+$("#selected_venue").attr("venue_id"), 
		        source: [
		                {value: 'Menteng', text: 'Menteng'},
		                {value: 'Kuningan', text: 'Kuningan'},
                        {value: 'Kebayoran Baru', text: 'Kebayoran Baru'},
                        {value: 'Senayan', text: 'Senayan'},
                        {value: 'Permata Hijau', text: 'Permata Hijau'},
                        {value: 'Pondok Indah', text: 'Pondok Indah'},
                        {value: 'Lebak Bulus', text: 'Lebak Bulus'},
                        {value: 'Kemang', text: 'Kemang'},
                        {value: 'Cipete', text: 'Cipete'},
                        {value: 'Cilandak', text: 'Cilandak'},
                        {value: 'Kelapa Gading', text: 'Kelapa Gading'},
                        {value: 'Senopati', text: 'Senopati'},
                        {value: 'Sarinah', text: 'Sarinah'},
                        {value: 'Cikini', text: 'Cikini'}
		           ]
		             
		    }); 
            /*
            <option value="Menteng">Menteng</option>
            <option value="Kuningan">Kuningan</option>
            <option value="Kebayoran Baru">Kebayoran Baru</option>
            <option value="Senayan">Senayan</option>
            <option value="Permata Hijau">Permata Hijau</option>
            <option value="Pondok Indah">Pondok Indah</option>
            <option value="Lebak Bulus">Lebak Bulus</option>
            <option value="Kemang">Kemang</option>
            <option value="Cipete">Cipete</option>
            <option value="Cilandak">Cilandak</option>
            <option value="Kelapa Gading">Kelapa Gading</option>
            <option value="Senopati">Senopati</option>
            <option value="Sarinah">Sarinah</option>
            <option value="Cikini">Cikini</option>
            */


		   $(".edit_venuephone").editable({
		            type: 'text',
		            pk: 1,
		            url: base_url+'admin/venue/'+'save'+'/'+$("#selected_venue").attr("venue_id"),
		            emptytext: 'Phone',
			        validate: function(value) {  
			         	if(value)     {
                            /*
			         		if(!(value+"").match(/^\d+$/) ) {
					        	return 'Please enter valid Phone number';
						    }
						    if(value.length!=10) {
						        return 'Please enter valid Phone number';
						    }
                            */
			         	}  	
					   
				    },
				    success: function(response) {  
		            	response =  jQuery.parseJSON(response);		            	 
		                $('.save_error').html('');
		                 if(response.success!=true){		                 	 
		                 	return response.reason;			                
			            }
		            } 
				   
		   });
		   $(".edit_venuelat").editable({
		            type: 'text',
		            pk: 1,
		            url: base_url+'admin/venue/'+'save'+'/'+$("#selected_venue").attr("venue_id"),           
					validate: function(value) {	         	       	
					    if(!(value).match(/^(\-?\d+(\.\d+)?)$/) && $.trim(value) ) {
					    return 'Please enter valid data';
					    }  
					}
		   });
		   
		    $('#section_list').on("click",'#btnUpdateLongLat', function(e) {      
		        e.preventDefault();	
			    //http://maps.google.com/maps/api/geocode/json?address=
			    var url = "https://maps.google.com/maps/api/geocode/json?address=";
			    //url+= $("#address").html() + ",";
			    url+= $("#address").html()=="Empty"?'':$("#address").html() + ",";
			    url+= $("#address2").html()=="Empty"?'':$("#address2").html() + ",";
			    url+= $("#city").html() + ",";
			    url+= $("#state").html() + ",";
			    url+= $("#zip").html();
			    console.log('url',url);
			    $.get(url, {}).done(function( data )
			    {
			        if(data.status=="OK"){
			        	    
					        
					        $.ajax({
					            type: "POST",
					            url: base_url+'admin/venue/'+'save'+'/'+$("#selected_venue").attr("venue_id"),
					            data	: {
					                name:'lat',
					                value:data.results[0].geometry.location.lat,		               
					            },
					            success	: function(response){
					            	resp =  jQuery.parseJSON(response);	
					            	if(resp.success==true)
					            		$("#v_e_lat").html(data.results[0].geometry.location.lat);
					            }			             
		        			});
		        			$.ajax({
					            type: "POST",
					            url: base_url+'admin/venue/'+'save'+'/'+$("#selected_venue").attr("venue_id"),
					            data	: {
					                name:'long',
					                value:data.results[0].geometry.location.lng,		               
					            },
					            success	: function(response){
					            	resp =  jQuery.parseJSON(response);	
					            	if(resp.success==true)
					            		$("#v_e_long").html(data.results[0].geometry.location.lng);
					            	else
					            		$("#v_e_long").html('<span style="color:#c23527">'+resp.reason+'</span>');
					            }		             
		        			});
			        }
			        else{
			        	 $("#invalidaddress").html('Invalid Address ')
			        }
			       
			    });
		
		    }); 
		    
		    $('#venue_description').editable({
		        url: base_url+'admin/venue/'+'save'+'/'+$("#selected_venue").attr("venue_id"),
		        showbuttons: 'bottom'
		    });  
			$("#filevenue").fileinput({
		       "uploadUrl": base_url+'admin/venue/addimage',
		       'allowedFileExtensions': ['png','jpg','jpeg'],
		       'maxFileSize': 1000240,              
		       "maxFileCount": 1,
		       "minFileCount": 1,		       
		        uploadExtraData: function() {
		        	var out = {}
		        	out.event_id = $("#selected_venue").attr("venue_id");
		        	
		          return out; 
		      	} 
		      	      	
			});
		   
		    $('#filevenue').on('filebatchuploadcomplete', function(event, files, extra) {
        			 location.reload();
    		});
		    $('#filevenue').on('fileuploaded', function(event, data, previewId, index) {
					 location.reload();
			});
				      enableDeleteButton();
		        
	    }
    
   })
}

function getEventDetailPage(url,type){
	$('#data-list').empty();
	$('<div class="modal-backdrop fade in" ><img class="loading-gif" src="'+base_url+'assets/images/load.gif"/></div>').appendTo(document.body); 
	
	$.ajax({
	    type		: "POST",
	    url		: url,	
	    dataType: "json",		   
	    data		: '',
	    success	: function(resp){
	    	$('.modal-backdrop').remove();
			$("#loading").css("display","none"); 
	    	resp['siteurl'] = base_url;  
	    	if(type=='weekly'){
	    		ejsfile ='event_seriesdetail.ejs';
	    	}else{
	    		ejsfile ='event_detail.ejs';
	    	}
	    	
            if(resp.fullfillment_type == undefined)
            {
                resp.fullfillment_type = "none";
            }

            if(resp.fullfillment_value == undefined)
            {
                resp.fullfillment_value = "";
            }
            
	    	      	 
	    	
	        $('#test-heading').hide();	
    		$('#toolbar').hide();	            
			$('.modal-backdrop').remove();
			$("#loading").css("display","none"); 
			
			if(resp.success!=false){
				var html =  new EJS({url: base_url+'assets/js/ejs/'+ejsfile+'?v='+ $.now()}).render(resp);    	      		      
	        	$('#data-list').html(html);
	        	if(resp.active != undefined)
	        	{
	        		var isActive = resp.active;
	                if(!isActive)
	                {
	                    var html =  new EJS({url: base_url+'assets/js/ejs/error.ejs'}).render({"success":false,"error_msg":"This event is deleted and doesn't show in admin or app"});
	                    $('#errorBar').html(html);
	                    $('#errorBar').css({"display":"block"});
	                }
	        	}

                var updatetype = (type == "weekly")?"edit":"editspecial";
                //capFL
                //resp.responseJSON.venue._id
                $('#edit_fufill').editable({
                type: 'text',
                pk: 1,
                value: resp.fullfillment_type,
                url: base_url+'admin/events/'+updatetype+'/'+$("#selected_event").attr("_id"), 
                source: [
                        {value: 'none', text: 'None'},
                        {value: 'phone_number', text: 'Phone Number'},
                        {value: 'link', text: 'Link'},
                        {value: 'reservation', text: 'Reservation'},
                        {value: 'purchase', text: 'Purchase'}, 
                   ]
                     
                });







                $('#edit_venue_item').editable({
                type: 'text',
                pk: 1,
                value: resp.fullfillment_type,
                url: base_url+'admin/events/'+updatetype+'/'+$("#selected_event").attr("_id"), 
                source: [
                        {value: 'none', text: 'None'},
                        {value: 'phone_number', text: 'Phone Number'},
                        {value: 'link', text: 'Link'},
                        {value: 'reservation', text: 'Reservation'},
                        {value: 'purchase', text: 'Purchase'}, 
                   ]
                     
                });


                

                $('#edit_fufill_value').editable({
                type: 'text',
                pk: 1,
                value: resp.fullfillment_value,
                url: base_url+'admin/events/'+updatetype+'/'+$("#selected_event").attr("_id")
                     
                });

                window.cEditEventData = resp;

                console.log("resp.start_time " + resp.start_time)
                console.log("resp.start_datetime " + resp.start_datetime)
                /*
                $('#edit_startdatetime').editable({  
                    type:'combodate',
                    value: new Date(resp.start_datetime),
                    minuteStep:10,
                    url: base_url+'admin/events/'+updatetype+'/'+$("#selected_event").attr("_id"),
                    pk: 1
                });
                */
 /*
                $('<div class="confirmation-modal modal in" tabindex="-1" role="dialog" aria-hidden="false" style="display: block;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title">Confirmation required</h4></div><div class="modal-body">Do you want to delete this event ?</div><div class="modal-footer"><button class="confirm btn btn-primary" type="button" data-dismiss="modal">Delete Event</button><button class="cancel btn btn-default" type="button" data-dismiss="modal">Cancel</button></div></div></div></div>').appendTo(document.body);

               
                
                */


                /*
                $('#edit_startdatetime').editable({
                type: 'time',
                pk: 1,
                value: resp.start_datetime,
                url: base_url+'admin/events/'+updatetype+'/'+$("#selected_event").attr("_id")
                     
                });
                */

                console.log("is_recurring :: " + type)

				$.ajax({
				    type		: "POST",
				    url		: base_url+'admin/tickets/ajax_list',	
				    dataType: "json",		   
				    data		: {'event-id':resp._id,'event_type':type,'is_recurring':type=='weekly'?1:0},
				    success	: function(resp){ 
				    	resp.event_type=type;
				    	resp.siteurl =base_url
				    	var html =  new EJS({url: base_url+'assets/js/ejs/ticket_list.ejs?v='+ $.now()}).render(resp);		
						$('#ticket_listing').html(html) ;     
				    }
    
   				})
 
    		}else{
    			var html =  new EJS({url: base_url+'assets/js/ejs/error_msg.ejs'}).render(resp);		
				$('#data-list').html(html) ;
				
    		}
	    },
	    complete : function (resp){
	    	if(resp.responseJSON.success==false)
	    		return;
	    	console.log(resp.responseJSON.venue._id);
	    	
	    	 $.fn.editable.defaults.mode = 'inline';
	    	 if(type=="weekly")	
	    	 	enableweeklyeventedit(resp);
	    	 else
	    	 	enablespecialeventedit(resp);	    	    
			  
			/*
            $('.special_event_date').editable({		        
		        url: base_url+'admin/events/'+'editspecialdate'+'/'+$("#selected_event").attr("_id"),
		        validate: function(value) {
		        	var startDate = moment();
					var endDate = moment(value);					
					if ( endDate < startDate) {
					  return 'Please enter valid Event date'
					}  
				}		       
		    });	
		    */	 
	    }
    
   })
}
function getTicketDetailPage(url,type){
	$('#data-list').empty();
	$('<div class="modal-backdrop fade in" ><img class="loading-gif" src="'+base_url+'assets/images/load.gif"/></div>').appendTo(document.body); 
	
	$.ajax({
	    type		: "POST",
	    url		: url,	
	    dataType: "json",		   
	    data		: '',
	    success	: function(resp){
	    	resp['siteurl'] = base_url; 
	    	console.log(resp.orders_count)
	    	ejsfile ='ticket_edit.ejs';
	    	//ejsfile ='ticket_detail.ejs';
            if(type=='weekly'){
                ejsfile ='ticket_edit_series.ejs';
            }else{
                ejsfile ='ticket_edit.ejs';
            }
	        
	        $('#test-heading').hide();	
    		$('#toolbar').hide();	            
			$('.modal-backdrop').remove();
			$("#loading").css("display","none"); 
			if(resp.success!=false){
				 var html =  new EJS({url: base_url+'assets/js/ejs/'+ejsfile+'?v='+ $.now()}).render(resp); 
				 $('#data-list').html(html);
    		}else{
    			resp.reason='Invalid Ticket ID  ';
    			var html =  new EJS({url: base_url+'assets/js/ejs/error_msg.ejs'}).render(resp);		
				$('#data-list').html(html) ;
				
    		}
	    },
	    complete : function (resp){	    	    
	    	    $("[name='forceedit']").bootstrapSwitch(); 
				if($("#ticket_type" ).val()=='reservation'){
					$('#termsandcond').hide();
					$('#deposit').attr('disabled',true)
				}else if($("#ticket_type" ).val()=='booking'){
					$('#termsandcond').show();
					$('#deposit').attr('disabled',false)
				}
				else{
					$('#deposit').attr('disabled',true)
					$('#termsandcond').show()
				}
				if($('#price').val()>0)
	 				calculateTotal()
	 			if($('#chk_adminfee').is(':checked')){			
					$("#admin_fee").attr("disabled",false);
				}else{
					$("#admin_fee").attr("disabled",true);
				}
				if($('#chk_tax').is(':checked')){			
					$("#tax").attr("disabled",false);
				}else{
					$("#tax").attr("disabled",true);
				}
				 if($('#chk_tip').is(':checked')){			
					$("#tip").attr("disabled",false);
				}else{
					$("#tip").attr("disabled",true);
				}
				$("#editticket-form").validate({
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
				        	deposit_check: true
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
				        
				    },
				    submitHandler: function(form) {
				    	calculateTotal();
				    	$.ajax({
					            type:"POST",
					            url:base_url+'admin/tickets/updateTicket',
					            data:$(form).serialize(),//only input
					            success: function(response){
					            	response =  jQuery.parseJSON(response);
					                if(response.success==true)					                 
					                	 location.reload();
					            }
					        });
					    
				        
				    }
});
$.validator.methods.price = function (value, element) {
    return this.optional(element) || /^-?(?:\d+|\d{1,3}(?:[\s\.,]\d{3})+)(?:[\.,]\d+)?$/.test(value);
}
$.validator.addMethod("deposit_check", function(value, element,params) {
	 
	  
      if($('#ticket_type').val()=='booking'){
      	 if( value>0)
	      	return true;
	     else 
	     	return false
      }else{
      	return true;
      }
      
	//if(e_start<= h_strat && h_end<=e_end )
	//	 return this.optional(element) || true;
	//else
	//	 return this.optional(element) || false;

}, "Deposit needs to be greater than 0");
	    	 }
	    	 
		     /*$("input[name='ticket_status']")
		    .on('switchChange.bootstrapSwitch',		    
		        function(event, state) {
		        	showloader();
		            var state_value = 0;
		            var name = $(this).data('name');
		            if(state){
		                state_value = 1;
		            }  
		            console.log(name,'name')
		            if($("#selected_ticket").attr("ticket_id")!=null){
		                $.ajax({
		                    type: "POST",
		                    
		                    url: base_url+'admin/tickets/'+'save'+'/'+$("#selected_ticket").attr("ticket_id")+'/'+$("#selected_ticket").attr("is_recurring"),
		                    data: {
		                        value : state_value,
		                        name:name
		                    },
		
		                    success: function(msg) {
								$('.modal-backdrop').remove();
		                    }
		                });
		            }     
		      });
		    */
	    
    
   })
}
function getOrderDetailPage(url,order_id){
	$('#data-list').empty();
	$('<div class="modal-backdrop fade in" ><img class="loading-gif" src="'+base_url+'assets/images/load.gif"/></div>').appendTo(document.body); 
	
	$.ajax({
	    type		: "POST",
	    url		: url,	
	    dataType: "json",		   
	    data		: '',
	    success	: function(resp){
	    	resp['siteurl'] = base_url;  
	    	ejsfile ='order_detail.ejs';
	    	
	    	      	 
	    	var html =  new EJS({url: base_url+'assets/js/ejs/'+ejsfile+'?v='+ $.now()}).render(resp);
	    	 	  
	    	//var html =  new EJS({url: base_url+'assets/js/ejs/'+ejs}).render(resp);	
	    	      		      

	        $('#test-heading').hide();	
    		$('#toolbar').hide();	            
			$('.modal-backdrop').remove();
			$("#loading").css("display","none"); 
			$('#data-list').html(html);
	    },
	    complete : function (resp){
	    	 type  = resp.responseJSON.data.tickettype.ticket_type;
	    	 if(type=='reservation'){
                   orderstatus =[
			              {value: 'New', text: 'New'},
			              {value: 'Quoted', text: 'Quoted'},
			              {value: 'User & House Confirmed', text: 'User & House Confirmed'},
			              {value: 'Completed', text: 'Completed'},
			              {value: 'Canceled', text: 'Canceled'},
			              {value: 'No Show', text: 'No Show'},			              
			              {value: 'Other', text: 'Other'}
			           ]
             }else{
            	   orderstatus =[
			              {value: 'Paid', text: 'Paid'},		 
			              {value: 'Completed', text: 'Completed'},
			              {value: 'Canceled', text: 'Canceled'},
			              {value: 'No Show', text: 'No Show'},			              
			              {value: 'Other', text: 'Other'}
			            ]
             }

	    	 //url: base_url+'admin/venue/'+'save'+'/'+$("#selected_venue").attr("venue_id"),
	    	 console.log(">>>> " + base_url+'admin/orders/update/' + order_id)
	    	 //$.fn.editable.defaults.mode = 'inline';
	    	     $('#order_status').editable(
	    	     {
			        value: 0,
			        pk:1,   
			        source: orderstatus,
                    url: base_url+'admin/orders/update/' + order_id
			     });
	    	 	
	    	
	    }
    
   })
}
function enablespecialeventedit(resp){
	 
	$('#venue_id').editable({
        value: resp.responseJSON.venue._id,
        pk: 1,
        url: base_url+'admin/events/'+'editspecial'+'/'+$("#selected_event").attr("_id"),
        success: function(response, newValue)
        {
            console.log("done!!!! " + newValue)
            // /admin/venue/#/venue-details=
            $("#edit_venue_details_link").attr("href","/admin/venue/#/venue-details=" + newValue);

            location.reload();

        },
        source  : function ()
        {
        	var result;
            $.ajax({
                url:  base_url+'admin/events/allvenues/',		                   
                type: 'GET',
                data : {},
                global: false,
                async: false,
                dataType: 'json',
                success: function(data) {
            	 	dat_obj = data;						  
					venue = '[';  
					for (prop in dat_obj) {
						venue += '{"value":"'+dat_obj[prop]._id+'", "text":"'+ dat_obj[prop].name+'"},';						    	  
				    }
				    venue +=']';						   
                    result =  venue;
                }
            });
         
            return result;		
		}
    });	
         
    $('.edit_event').editable({
         type: 'text',
         pk: 1,
         url: base_url+'admin/events/'+'editspecial'+'/'+$("#selected_event").attr("_id"),			         
         emptytext: 'Please enter text',
         validate: function(value) {
	     	if(!$.trim(value)) return 'Required field!';
		 }
    });
			
    $('#event_status').editable({
	   	 type: 'text',
         pk: 1,
        value: resp.responseJSON.status,
        url: base_url+'admin/events/'+'editspecial'+'/'+$("#selected_event").attr("_id"),    
        source: [
              {value: 'draft', text: 'Draft'},
              {value: 'published', text: 'Published'},
              {value: 'passed', text: 'Passed'},
              {value: 'inactive', text: 'Inactive'},
              {value: 'soldout', text: 'Sold Out'}
           ]
             
    }); 
    $('#event_featured').editable({
    	 type: 'text',
         pk: 1,
         url: base_url+'admin/events/'+'editspecial'+'/'+$("#selected_event").attr("_id"),
        value: resp.responseJSON.source,    
        source: [
              {value: 'admin', text: 'Admin'},
              {value: 'user', text: 'User'},
              {value: 'featured', text: 'Featured'},			              
           ]
             
    });
    var evrank = [];
    for (i = 0; i <= 10 ;i += 1) {  
	    tmp = {'value': i,'text': i };
	    evrank.push(tmp);
	}
    $('#event_rank').editable({
    	type: 'text',
         pk: 1,
         url: base_url+'admin/events/'+'editspecial'+'/'+$("#selected_event").attr("_id"),
        value: resp.responseJSON.rank,    
        source:evrank
             
    });
    /*
    "Art & Culture",
    "Fashion",
    "Food & Drink",
    "Family",
    "Music",
    "Nightlife"
    

    "House / EDM",
    "Hip Hop",
    "Top 40s",
    "Dance",
    "Nightclub",
    "Lounge",
    "Hotel Lounge",
    "Rooftop",
    "Restaurant",
    "Red Carpet",
    "Upscale Chic",
    "Casual"
    

    */


    $('#event_tags').editable({
    	type: 'select',
          value: resp.responseJSON.tags.toString(),   
         emptytext: 'Please enter tag',
         url: base_url+'admin/events/'+'editspecial'+'/'+$("#selected_event").attr("_id"),
       source: [

          {id: 'Art & Culture', text: 'Art & Culture'},
          {id: 'Fashion', text: 'Fashion'},
		  {id: 'Food & Drink', text: 'Food & Drink'},
		  {id: 'Family', text: 'Family'},
          {id: 'Music', text: 'Music'},
          {id: 'Nightlife', text: 'Nightlife'}
       ],
        select2: {
           multiple: true,
           viewseparator :',',
           allowClear: true,
           width: '230px',
        }
    });
	$("#file-4").fileinput({
       "uploadUrl": base_url+'admin/events/addimage',
       'allowedFileExtensions': ['png','jpg','jpeg'],                
       'maxFileSize': 1000240,              
       "maxFileCount": 1,
       "minFileCount": 1,	
        uploadExtraData: function() {
        	var out = {}
        	out.event_id = $("#selected_event").attr("_id");
        	out.method = 'special';
          return out; 
      	}              	
	});
	$('#file-4').on('filebatchuploadcomplete', function(event, files, extra) {
        			 //location.reload();
                     console.log("extra");
                     console.log(extra)
                     console.log(event)
    		});
    $('#file-4').on('fileuploaded', function(event, data, previewId, index) {
			 location.reload();
	});	
}

function enableweeklyeventedit(resp){
	$("[name='forceedit']").bootstrapSwitch(); 
	$("input[name='forceedit']")
	    .on('switchChange.bootstrapSwitch',
	        function(event, state) {  
	        	//  $("#selected_event").attr("forceedit",state);
	        });
	$('#eventvenue').editable({
        value: resp.responseJSON.venue._id,    
        source  : function ()
        {
        	var result;
            $.ajax({
                url:  base_url+'admin/events/allvenues/',		                   
                type: 'GET',
                data : {},
                global: false,
                async: false,
                dataType: 'json',
                success: function(data) {
            	 	dat_obj = data;						  
					venue = '[';  
					for (prop in dat_obj) {
						venue += '{"value":"'+dat_obj[prop]._id+'", "text":"'+ dat_obj[prop].name+'"},';						    	  
				    }
				    venue +=']';						   
                    result =  venue;
                }
            });
         
            return result;		
		}
    });	     
    $('.edit_event').editable({
         type: 'text',
         pk: 1,
         params: function(params) {params.forceedit = $('#forceedit').is(":checked"); return params;  },
         url: base_url+'admin/events/'+'edit'+'/'+$("#selected_event").attr("event_id"),
         emptytext: 'Please enter text',
         validate: function(value) {
	     	if(!$.trim(value)) return 'Required field!';
		 }
    });
			
    $('#event_status').editable({
	   	 type: 'text',
         pk: 1,
        value: resp.responseJSON.status,
        params: function(params) {params.forceedit = $('#forceedit').is(":checked"); return params;  },
        url: base_url+'admin/events/'+'edit'+'/'+$("#selected_event").attr("event_id"),    
        source: [
              {value: 'draft', text: 'Draft'},
              {value: 'published', text: 'Published'},
              {value: 'passed', text: 'Passed'},
              {value: 'inactive', text: 'Inactive'},
              {value: 'soldout', text: 'Sold Out'}
           ]
             
    }); 
    $('#event_featured').editable({
    	 type: 'text',
         pk: 1,
         params: function(params) {params.forceedit = $('#forceedit').is(":checked"); return params;  },
         url: base_url+'admin/events/'+'edit'+'/'+$("#selected_event").attr("event_id"), 
        value: resp.responseJSON.source,    
        source: [
              {value: 'admin', text: 'Admin'},
              {value: 'user', text: 'User'},
              {value: 'featured', text: 'Featured'},			              
           ]
             
    });
    var evrank = [];
    for (i = 0; i <= 10 ;i += 1) {  
	    tmp = {'value': i,'text': i };
	    evrank.push(tmp);
	}
    $('#event_rank').editable({
    	type: 'text',
         pk: 1,
         params: function(params) {params.forceedit = $('#forceedit').is(":checked"); return params;  },
         url: base_url+'admin/events/'+'edit'+'/'+$("#selected_event").attr("event_id"), 
        value: resp.responseJSON.rank,    
        source:evrank
             
    });
    $('#event_tags').editable({
    	type: 'text',
         pk: 1,
         params: function(params) {params.forceedit = $('#forceedit').is(":checked"); return params;  },
         url: base_url+'admin/events/'+'edit'+'/'+$("#selected_event").attr("event_id"), 
       source: [
          {id: 'Art & Culture', text: 'Art & Culture'},
          {id: 'Fashion', text: 'Fashion'},
          {id: 'Food & Drink', text: 'Food & Drink'},
          {id: 'Family', text: 'Family'},
          {id: 'Music', text: 'Music'},
          {id: 'Nightlife', text: 'Nightlife'}
       ],
        select2: {
           multiple: true,
           viewseparator :',',
           allowClear: true,
           width: '230px',
        }
    });
    $("#file-4").fileinput({
       "uploadUrl": base_url+'admin/events/addimage',
       'allowedFileExtensions': ['png','jpg','jpeg'],                
       'maxFileSize': 1000240,              
       "maxFileCount": 1,
       "minFileCount": 1,	
        uploadExtraData: function() {
        	var out = {}
        	out.event_id = $("#selected_event").attr("event_id");
        	out.method = 'weekly';
        	out.forceedit =  $('#forceedit').is(":checked");
   			return out; 
      	}              	
	});	
	$('#file-4').on('filebatchuploadcomplete', function(event, files, extra) {
        			 location.reload();
    		});
    $('#file-4').on('fileuploaded', function(event, data, previewId, index) {
			 location.reload();
	});	
}
function enableDeleteButton(){
	$(".confirm").confirm({
	    text: "Are you sure you want to delete this "+$(".confirm").attr('data-type')+"?",
	    title: "Confirmation required",
	    confirm: function(button) {
	       
	       window.location.href = button.attr('data-url');
	    },
	    cancel: function(button) {
	        
	    },
	    confirmButton: "Yes",
	    cancelButton: "No",
	    post: true
	});
}
function getOverviewGraph(range) {
 	$('<div class="modal-backdrop fade in" ><img class="loading-gif" src="'+base_url+'assets/images/load.gif"/></div>').appendTo(document.body);
 	$('#ph_overviewgrpah').html('');
 	if($("#data-set1").select2('data')){
 		var data_set1 =    $("#data-set1").select2('data').id;
 		var label1 = $("#data-set1").select2('data').text;
 	}else{
 		var data_set1 ='';var label1 ='Select a metric'
 	}
 	if($("#data-set2").select2('data')){
 		var data_set2 =    $("#data-set2").select2('data').id;
 		var label2 = $("#data-set2").select2('data').text;
 	}else{
 		var data_set2 ='';var label2 ='Select a metric'
 	}
 	 
 	
 	var c_range = $('#goptions').find('a.active').attr('id')
 	c_range = c_range ? c_range:range;
 	 
    $.ajax({
        type : "POST",
        data : {set1:data_set1,set2:data_set2,period:c_range},        
        dataType: "json",
        url		: base_url+'admin/home/getgraph',        
        success	: function(resp){        		
        		Morris.Line({
	            element: 'ph_overviewgrpah',
	            data: resp,
	            xkey: 'date',
	            ykeys: ['a', 'b'],
	            labels: [label1, label2],
	            lineColors: ['#EFB55B', '#E8524E'],
	            lineWidth: 2,
	            pointSize: 4,
	            parseTime: false,
	            gridLineColor: 'rgba(255,255,255,.5)',
	            gridTextColor: '#fff',
	            hideHover: true,
	            resize: true,
	            smooth:false 
	        });  
            $('.modal-backdrop').remove();     
        }
    });
        
}
function loadListing(url){
    realodAjaxList(url);
}

function realodAjaxList(url1){
    
}

function format(state) {
	
    var originalOption = state.element;   
    return  $(originalOption).data('name') ;
}

function formatselected(state) {
	//console.log(state.element)
    var originalOption = state.element;
    if($(originalOption).data('id'))
   	 $("#selected_search_venue").val($(originalOption).data('id'));
    return  $(originalOption).data('name') ;
}

function formatselected_recurring(state) {
    var originalOption = state.element;
    $("#selected_search_recurring").val($(originalOption).data('id'));
    return  $(originalOption).data('name') ;
}
 
function formatselected_promoter(state) {
    var originalOption = state.element;
    $("#selected_search_promoter").val($(originalOption).data('id'));
    return  $(originalOption).data('name') ;
}

function formatselected_verified(state) {
    var originalOption = state.element;
    $("#selected_search_verified").val($(originalOption).data('id'));
    return  $(originalOption).data('name') ;
}
function convertToServerTimeZone1(date){
    var offset = -7.0
    var clientDate = new TimeShift.OriginalDate(date);
    var utc = clientDate.getTime() + (clientDate.getTimezoneOffset() * 60000);
    var serverDate = new TimeShift.OriginalDate(utc - (3600000*offset));
    // return serverDate.getDate()+'/'+serverDate.getMonth()+'/'+serverDate.getFullYear();
    return dateFormats(serverDate, "mm/dd/yy")
}
function convertToServerDate(date,format){
    var offset = -7.0
    var clientDate = new TimeShift.OriginalDate(date);
    var utc = clientDate.getTime() + (clientDate.getTimezoneOffset() * 60000);
    var serverDate = new TimeShift.OriginalDate(utc - (3600000*offset));
    // return serverDate.getDate()+'/'+serverDate.getMonth()+'/'+serverDate.getFullYear();
    return dateFormats(serverDate, format)
}
function convertToServerTimeZone(date)
{
    var offset = -7.0
    date = String(date);
    var clientDate = new TimeShift.OriginalDate(date);
    if(!Object.prototype.toString.call(clientDate) === "[object Date]")
    {
     return new TimeShift.OriginalDate.toISOString();
    }
    var utc = clientDate.getTime() - (clientDate.getTimezoneOffset() * 60000);
    var serverDate = new TimeShift.OriginalDate(utc + (3600000 * offset));
    return serverDate.toISOString();
}
function getEndSeriesDate(date,format){
	var edate = new TimeShift.OriginalDate(date);
	var today = new TimeShift.OriginalDate();
	console.log(edate,today,'todaytoday')
	if(edate<today){
		return 'Forever';
	}else{		
		return convertToServerDate(date,format);
	}
	//convertToServerDate
}
function showloader(){
	$('<div class="modal-backdrop fade in" ><img class="loading-gif" src="'+base_url+'assets/images/load.gif"/></div>').appendTo(document.body);
}
function getAge(dateString) 
{
	
    var today = new Date;
    var birthDate = new Date(dateString);
    
   
    var age = today.getFullYear() - birthDate.getFullYear();
    console.log(dateString) 
    var m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) 
    {
        age--;
    }
    return age;
}

function capFL(string)
{
    return string.charAt(0).toUpperCase() + string.slice(1);
}

function capFLSplit(string)
{
    string = string.split("_").join(" ");
    tmpA = string.split(" ");
    for (var i = 0; i < tmpA.length; i++)
    {
        tmpA[i] = capFL(tmpA[i]);
    };
    string = tmpA.join(" ")
    return string
}


function calculateTotal(){
	//Estimated Total = Price + adminFee + (Price x taxRate) + (Price x tipRate)
	var price = parseFloat($('#price').val()?$('#price').val():0 );
	if($("#chk_adminfee").is(':checked'))
		var adminfee  =$('#admin_fee').val()?$('#admin_fee').val():0 ;
	else
		var adminfee  =0;
	if($("#chk_tax").is(':checked')){
		var tax  = $('#tax').val()?$('#tax').val():0 ;
		if(isNaN(tax)) return;
		$('#tax_amt').html(parseFloat(price*(tax/100)).toFixed(2));
	}else{
		var tax  =0;		
		$('#tax_amt').html(parseFloat(price*(tax/100)).toFixed(2));
	}
		
	if($("#chk_tip").is(':checked')){
		var tip  = $('#tip').val()?$('#tip').val():0 ;
		if(isNaN(tip)) return;
		$('#tip_amt').html(parseFloat(price*(tip/100)).toFixed(2));
	}
	else{
		var tip  =0;
		$('#tip_amt').html(parseFloat(price*(tip/100)).toFixed(2));
	}
		
	var total= price + parseFloat(adminfee) + parseFloat(price*(tax/100))+ parseFloat(price*(tip/100));
	$('#total').val(total.toFixed(2));
}
 