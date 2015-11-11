jQuery(function ($) {
    'use strict';

    var $floatBreakpoint = $('<div class="grid-float-breakpoint" style="position:absolute;top:-9999px;width:1px;height:1px;"></div>');
    var $xsVisible = $('<div class="visible-xs" style="position:absolute;top:-9999px;width:1px;height:1px;"></div>');
    var $smVisible = $('<div class="visible-sm" style="position:absolute;top:-9999px;width:1px;height:1px;"></div>');

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
		    	var detailpage= base_url+'admin/events/event_details/'+ str_h[1]; 		    	  		 
   				getEventDetailPage(detailpage,'special'); 
		    }else if (str_h[0]=='event-weekly') {   		  
		    	var detailpage= base_url+'admin/events/event_details/'+ str_h[1];   		 
   				getEventDetailPage(detailpage,'weekly'); 
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
		auto_range.start= moment();
		auto_range.end =moment().add(6, 'months');
		preset_options =  [
						{text: 'All',dateStart: function() { return  moment() },dateEnd: function() { return all_endDate }},
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
	  var url_link = base_url+'admin/home/dateRange/'+Math.random();  
	 
      
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
        preparehashtags('sort_field',$('.panel-body').attr('sort_field'),false);
		preparehashtags('sort_val',$('.panel-body').attr('sort_val'),false);
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
		e.preventDefault();		 
		$('#conversation').remove();
		$('.modal-backdrop').remove();
		 var frm= $(this).data('from');
		path = base_url+'admin/chat/conversation/'+$(this).data('to')+'/'+$(this).data('from');
		$('#serviceModal').remove();
		$.ajax({
	        type		: "POST",
	        url		: path,	
	        dataType: "json",	      
	        success	: function(resp){	
	        	
	        	resp.from= frm; 
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
			         hasher.setHash('event-special='+$(pop_event).data('event_id'));
			    },
			    confirmButton: "Edit series",
			    cancelButton: "Only this date",
			    post: true
			});
	   }else{
	   		hasher.setHash('event-special='+$(this).data('event_id'));
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
    		//window.location.href = base_url+'admin/'+controller_JS;
    		window.history.back();
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
		    	showloader();
		    	if($(event_details).data('event_type')=='special'){
		    		del_url = base_url+'admin/events/delete';
		    	}else{
		    		del_url = base_url+'admin/events/weeklydelete';
		    	}		       
		        $.ajax({
		            type: "POST",
		            url:del_url ,
		            data: {
		                event_id : $(event_details).data('event_id'), 
		                mevent_id : $(event_details).data('mevent_id')
		                },
		            success: function(data) {
		            	data =  jQuery.parseJSON(data);
		            	console.log(data)
		                if(data.success==true){
		                	if($('#section_list').data('clickedfrom')=='list') {
		                		setTimeout(function(){hasher.setHash($('#section_list').data('clickedfromurl')); }, 3000);
					    	;
					    	}else{
					    		setTimeout(function(){ window.location.href = base_url+'admin/'+controller_JS }, 3000);
					    		
					    	}
		                }
		                //window.history.back();	                        
		            }
		        });
		    },
		    cancel: function(button) {
		          
		    },
		    confirmButton: "Delete Event",
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
			if(type=='special')
				event_id =  $("#selected_event").attr("_id");
			else
				event_id = $("#selected_event").attr("event_id");	 
		path = base_url+'admin/events/removeimage/'+event_id;		 
		$.ajax({
	        type		: "POST",
	        url		: path,	
	        data		: {'url':$(this).data('url'),'type':$(this).data('type')},
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
});

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
		   if(event.event_type=='weekly'){
		   		$.confirm({
				    text: "Do you want to edit the series or only this date ?",
				    title: "Confirmation required",
				    confirm: function(button) {			       
				      hasher.setHash('event-weekly='+event._id);
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

	
	if((new Date()).getTimezoneOffset() == 240)
	{
		timezone = "America/New_York"
	}

	if((new Date()).getTimezoneOffset() == 420)
	{
		//timezone = "America/Los_Angeles"
	}

	$.get( base_url+'admin/events/cal_view/'+ whole_start_date + "/" + whole_end_date, function( cal_data )
	{
		//console.log("response")
		data =  jQuery.parseJSON(cal_data);
		//data = events.events
		//console.log('mada' ,data);

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
	        exp_uri= 'admin/user/export';	       
	        break;
	   case 'venue':
	        url = base_url+'admin/venue/ajax_list';
	        ejs = 'venue_list.ejs';
	        exp_uri= 'admin/venue/export';
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
	   	        
	}
	setHostingfilter(dtext);
	
   	if(section=='events'&& dtext.view_type=='calendar'){
   		
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
   	if(section=='events'){
   		if(typeof(dtext.sort_status)=='undefined'){
   			sort_status_val= '';
   		}else{
   			sort_status_val= dtext.sort_status;
   		}
   		$("#sort_status").select2("val", sort_status_val);	
   		$('#data-list').removeClass().addClass('row'); 		 
   	} 
   	dtext.startDate_iso = convertToServerTimeZone(new TimeShift.OriginalDate(start_date+" 00:00:00"));
   	dtext.endDate_iso = convertToServerTimeZone(new TimeShift.OriginalDate(end_date+" 23:59:00"));
   	console.log(dtext,'dtext')
   	//startDate_iso =new TimeShift.OriginalDate($.datepicker.formatDate('mm/dd/yy ',seldate.start)+"00:00:00"), 
   // endDate_iso = new TimeShift.OriginalDate($.datepicker.formatDate('mm/dd/yy ',seldate.end)+"23:59:00"), 
    $.ajax({
        type		: "POST",
        url		: url,	
        dataType: "json",		   
        data		: dtext,
        success	: function(resp){ 
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
			$('#data-search').val(resp.data_search);
			
			$("#sort-list").select2("val", resp.paginate.per_page);			
			$('#paginate_link').bootpag({
			    total: resp.paginate.total_page,
			    page: resp.paginate.offset,
			    maxVisible: 5,
			    leaps: true,
			    firstLastUse: true,
			    first: '←',
			    last: '→',		    
			}).on("page", function(event, num){
			    
			     preparehashtags('offset',num,false)
	             //getDataResponse(base_url+'admin/chat/ajax_list','chat');
			}); 
			$('.export_options').attr('href',base_url+exp_uri+'/?'+hasher.getHash());  
			 
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
    		var html =  new EJS({url: base_url+'assets/js/ejs/error.ejs?v='+ $.now()}).render(resp);
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
			         url: base_url+'admin/user/'+'save'+'/'+$("#selected_user").attr("user_id"),
			         emptytext: 'Please enter text'
			    });
				 
				$('#fname,#lname').editable({
	         type: 'text',
	         pk: 1,
	         url: base_url+'admin/user/'+'save'+'/'+$("#selected_user").attr("user_id"),
	         emptytext: 'Name',
	         validate: function(value) {
			     if(!$.trim(value)) return 'Required field!';
			}
	    });
	    $('#fbid').editable({
	         type: 'text',
	         pk: 1,
	         url: base_url+'admin/user/'+'save'+'/'+$("#selected_user").attr("user_id"),
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
	         url: base_url+'admin/user/'+'save_usermail'+'/'+$("#selected_user").attr("user_id"),
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
	         url: base_url+'admin/user/'+'save'+'/'+$("#selected_user").attr("user_id"),
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
	     $('.datepicker,.datepicker_event').on('changeDate', function(ev){      
	            $(this).datepicker('hide');
	             $( "[data-rel=datepicker]" ).trigger( "blur" );
	    });
	    
	    $('#dob').editable({
	        url: base_url+'admin/user/'+'savebirthday'+'/'+$("#selected_user").attr("user_id"),
	        validate: function(value) {
	        	var startDate = moment();
				var endDate = moment(value);
				
				if ( endDate > startDate) {
				  return 'Please enter valid birth date'
				}
				
			      
			   
			}
	    });
	    
	    $("[name='is_verified_host'],[name='suspend_user'],[name='gender']").bootstrapSwitch();
	    $("input[name='gender']")
	    .on('switchChange.bootstrapSwitch',
	        function(event, state) {  
	        	showloader(); 
	        	          
	            if($("#selected_user").attr("user_id")!=null){
	                $.ajax({
	                    type: "POST",
	                    url: base_url+'admin/user/'+'save'+'/'+$("#selected_user").attr("user_id"),
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
	            var url_link = base_url+'admin/user/'+'verified_host'+'/'+$("#selected_user").attr("user_fb_id"); 
	            if($("#selected_user").attr("user_id")!=null){
	                $.ajax({
	                    type: "POST",
	                    url: url_link,
	                    data: {
	                        data : v,  
	                        user:$("#selected_user").attr("user_id")
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
	            var url_link = base_url+'admin/user/'+'suspend_user'+'/'+$("#selected_user").attr("user_fb_id");         
	            var v = (state== true)?0:1; 	
	             
	            if($("#selected_user").attr("user_id")!=null){
	                $.ajax({
	                    type: "POST",
	                    url: url_link,
	                    data	: {
	                        data : v,  
	                        user:$("#selected_user").attr("user_id")
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
	         $("[name='is_recurring'],[name='is_verified_table'],[name='active']").bootstrapSwitch();
		     $("input[name='is_recurring'],[name='is_verified_table'],[name='active']")
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
	         $("[name='is_recurring'],[name='is_verified_table'],[name='active']").bootstrapSwitch();
		     $("input[name='is_recurring'],[name='is_verified_table'],[name='active']")
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
					}
		            
		   });
		   $(".edit_venue_url").editable({
		            type: 'text',
		            pk: 1,
		            url: base_url+'admin/venue/'+'save'+'/'+$("#selected_venue").attr("venue_id"),
		            emptytext: 'Url',
			         validate: function(value) {         	
					    if(!(value+"").match(/^(http|https|ftp):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/) ) {
					        return 'Please enter valid url. eg http://google.com';
					    }
					    
					}
		   });
		   $('#neighborhood').editable({
			   	type: 'text',
		        pk: 1,
		        value: resp.responseJSON.data.neighborhood,
		        url: base_url+'admin/venue/'+'save'+'/'+$("#selected_venue").attr("venue_id"), 
		        source: [
		                {value: 'bryant park', text: 'Bryant Park'},
		                {value: 'chelsea', text: 'Chelsea'},
					    {value: 'east village', text: 'East Village'},
						{value: 'flatiron', text: 'Flatiron'},
						{value: "hell's kitchen", text: "Hell's Kitchen"},
						{value: 'hells kitchen', text: 'Hells Kitchen'}	,
						{value: 'lincoln center', text: 'Lincoln Center'},
						{value: 'little italy', text: 'Little Italy'},
						{value: 'lower east side', text: 'Lower East Side'},
						{value: 'meat packing', text: 'Meat Packing'},
						{value: 'meatpacking district', text: 'Meatpacking District'},
						{value: 'midtown', text: 'Midtown'} ,
						{value: 'midtown east', text: 'Midtown East'} ,
						{value: 'midtown west', text: 'Midtown West'} ,
						{value: 'nolita', text: 'Nolita '} ,
						{value: 'theater district', text: 'Theater District'} ,
						{value: 'times square', text: 'Times Square'} ,
						{value: 'tribeca', text: 'Tribeca'} ,
						{value: 'west village', text: 'West Village'} 
		           ]
		             
		    }); 
		   $(".edit_venuephone").editable({
		            type: 'text',
		            pk: 1,
		            url: base_url+'admin/venue/'+'save'+'/'+$("#selected_venue").attr("venue_id"),
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
		   /*$(".edit_venue").click(function(){
		        $('#'+$(this).attr('id')).editable({
		            type: 'text',
		            pk: 1,
		            url: base_url+'admin/venue/'+'save'+'/'+$("#selected_venue").attr("venue_id"),
		            success: function(response) {  
		                $('.save_error').html('');
		                if(response!="success"){
		                    $('.save_error').html(response);
		                }
		            } 
		        });
		
		    });*/
		    
		    $('#venue_description').editable({
		        url: base_url+'admin/venue/'+'save'+'/'+$("#selected_venue").attr("venue_id"),
		        showbuttons: 'bottom'
		    });  
			$("#filevenue").fileinput({
		       "uploadUrl": base_url+'admin/venue/addimage',
		       'allowedFileExtensions': ['png'],                
		       "maxFileCount": 5,
		        uploadExtraData: function() {
		        	var out = {}
		        	out.event_id = $("#selected_venue").attr("venue_id");
		        	
		          return out; 
		      	}              	
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
	    	resp['siteurl'] = base_url;  
	    	if(type=='weekly'){
	    		ejsfile ='event_seriesdetail.ejs';
	    	}else{
	    		ejsfile ='event_detail.ejs';
	    	}
	    	
	    	      	 
	    	var html =  new EJS({url: base_url+'assets/js/ejs/'+ejsfile+'?v='+ $.now()}).render(resp);
	    	 	  
	    	//var html =  new EJS({url: base_url+'assets/js/ejs/'+ejs}).render(resp);	
	    	      		      
	        $('#data-list').html(html);
	        $('#test-heading').hide();	
    		$('#toolbar').hide();	            
			$('.modal-backdrop').remove();
			$("#loading").css("display","none"); 
	    },
	    complete : function (resp){
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

function enablespecialeventedit(resp){
	 
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
    $('#event_tags').editable({
    	type: 'text',
         pk: 1,
         url: base_url+'admin/events/'+'editspecial'+'/'+$("#selected_event").attr("_id"),
       source: [
          {id: 'house', text: 'House / EDM'},
          {id: 'hiphop', text: 'Hip Hop'},
		  {id: 'top40s', text: 'Top 40s'},
		  {id: 'dance', text: 'Dance'},
		  {id: 'nightclub', text: 'Nightclub'},
          {id: 'lounge', text: 'Lounge'},
		  {id: 'hotellounge', text: 'Hotel Lounge'},
		  {id: 'rooftop', text: 'Rooftop'},
          {id: 'restaurent', text: 'Restaurant'},
		  {id: 'redcarpet', text: 'Red Carpet'},
		  {id: 'upscalechic', text: 'Upscale Chic'},
          {id: 'casual', text: 'Casual'}
       ],
        select2: {
           multiple: true,
           viewseparator :','
        }
    });
	$("#file-4").fileinput({
       "uploadUrl": base_url+'admin/events/addimage',
       'allowedFileExtensions': ['png'],                
       "maxFileCount": 5,
        uploadExtraData: function() {
        	var out = {}
        	out.event_id = $("#selected_event").attr("_id");
        	out.method = 'addeventsimage';
          return out; 
      	}              	
	});	
}

function enableweeklyeventedit(resp){
	 
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
         url: base_url+'admin/events/'+'edit'+'/'+$("#selected_event").attr("event_id"), 
        value: resp.responseJSON.rank,    
        source:evrank
             
    });
    $('#event_tags').editable({
    	type: 'text',
         pk: 1,
         url: base_url+'admin/events/'+'edit'+'/'+$("#selected_event").attr("event_id"), 
       source: [
          {id: 'house', text: 'House / EDM'},
          {id: 'hiphop', text: 'Hip Hop'},
		  {id: 'top40s', text: 'Top 40s'},
		  {id: 'dance', text: 'Dance'},
		  {id: 'nightclub', text: 'Nightclub'},
          {id: 'lounge', text: 'Lounge'},
		  {id: 'hotellounge', text: 'Hotel Lounge'},
		  {id: 'rooftop', text: 'Rooftop'},
          {id: 'restaurent', text: 'Restaurant'},
		  {id: 'redcarpet', text: 'Red Carpet'},
		  {id: 'upscalechic', text: 'Upscale Chic'},
          {id: 'casual', text: 'Casual'}
       ],
        select2: {
           multiple: true,
           viewseparator :','
        }
    });
    $("#file-4").fileinput({
       "uploadUrl": base_url+'admin/events/addimage',
       'allowedFileExtensions': ['png'],                
       "maxFileCount": 5,
        uploadExtraData: function() {
        	var out = {}
        	out.event_id = $("#selected_event").attr("event_id");
        	out.method = 'addeventimage';
          return out; 
      	}              	
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
    var offset = 4.0
    var clientDate = new Date(date);
    var utc = clientDate.getTime() + (clientDate.getTimezoneOffset() * 60000);
    var serverDate = new Date(utc - (3600000*offset));
    return serverDate.getTime();
}
function convertToServerTimeZone(date)
{
    var offset = 4.0
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
function showloader(){
	$('<div class="modal-backdrop fade in" ><img class="loading-gif" src="'+base_url+'assets/images/load.gif"/></div>').appendTo(document.body);
}
