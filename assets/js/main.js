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

    openActiveTab(document.location.toString());

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

    var cb = function(start, end, label) {
       // console.log(start.toISOString(), end.toISOString(), label);
            
        $('#reportrange span').html(start_date + ' - ' + end_date);
    }
	
    var optionSet1 = {
        startDate: moment(),
        endDate: '01/01/2010',
        minDate: '01/01/2010',
        maxDate: '12/31/2014',
        dateLimit: {
            days: 360
        },
        showDropdowns: true,
        showWeekNumbers: true,
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: true,
        ranges: {
        	'ALL': ['01/01/2010',moment()],
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        opens: 'left',
        buttonClasses: ['btn btn-default'],
        applyClass: 'btn-small btn-primary',
        cancelClass: 'btn-small',
        format: 'MM/DD/YYYY',
        separator: ' to ',
        locale: {
            applyLabel: 'Submit',
            cancelLabel: 'Clear',
            fromLabel: 'From',
            toLabel: 'To',
            customRangeLabel: 'Custom',
            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            firstDay: 1
        }
    };

    $('#reportrange span').html(start_date + ' - ' + end_date);
    $('#reportrange').daterangepicker(optionSet1, cb);
	
    $('#reportrange').on('show.daterangepicker', function() {
        //.log("show event fired");
    });
    $('#reportrange').on('hide.daterangepicker', function() {
        //console.log("hide event fired");
    });
    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
        var url_link = base_url+'admin/home/dateRange/'+Math.random(); 
        //alert('startDate :'+ picker.startDate.format('YYYY-MM-DD'));
      //  window.console.log,          'endDate : '+picker.endDate.format('YYYY-MM-DD'))	 
        $.ajax({
            type: "POST",
            url: url_link,
            data	: {
                startDate : picker.startDate.format('YYYY-MM-DD'), 
                endDate : picker.endDate.format('YYYY-MM-DD')
                },
                 
            success: function(msg) {
                location.reload();
            }
        });
			
	 

    });
    
    $('#reportrange').on('cancel.daterangepicker', function(ev, picker) {
        //console.log("cancel event fired");
    });
	
    $('#destroy').click(function() {
        $('#reportrange').data('daterangepicker').remove();
    });
          
    $("#sort-list").select2({
        minimumResultsForSearch: -1
    });
    $("#search-venue").select2({ 
        placeholder: "Select Venue",
        allowClear: true,
        formatSelection: formatselected,
        formatResult: format
    });
    $("#search-recurring").select2({
        placeholder: "Is Recurring",
        allowClear: true,
        minimumResultsForSearch: -1,
        formatSelection: formatselected_recurring,
        formatResult: format
    });
    $("#search-promoter").select2({
        placeholder: "Is Promoter",
        allowClear: true,
        minimumResultsForSearch: -1,
        formatSelection: formatselected_promoter,
        formatResult: format
    });
    $("#search-verified").select2({
        placeholder: "Is Verified",
        allowClear: true,
        minimumResultsForSearch: -1,
        formatSelection: formatselected_verified,
        formatResult: format
    });  
    $("#sort-list").on("change", function(e) { 
        realodAjaxList($('.panel-body').attr('data-url'));
    })
    $("#sort-formsearch").on("click", function(e) { 
        realodAjaxList($('.panel-body').attr('data-url'));
    })
    $('#data-search').on("keypress", function(e){
        if (e.keyCode == '13') {
           realodAjaxList($('.panel-body').attr('data-url'));
        }
    })
    $("#formsearch").on("click", function(e) { 
        realodAjaxList($('.panel-body').attr('data-url'));
    })
    $("body").find(".panel-body" ).off( "click").on( "click", "th", function() {   	
      
      
        $( ".dataTable").find('th').addClass('sorting')
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
		
        realodAjaxList( $('.panel-body').attr('data-url'));
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
						$( this ).find('.user-over').css({'opacity':1});
						$( this ).find('.user-img').css({'opacity':0})
					}, mouseleave: function() {
						$( this ).find('.user-over').css({'opacity':0});
						$( this ).find('.user-img').css({'opacity':1});
					}
				});
			});
});
function loadListing(url){
    realodAjaxList(url);
}

function realodAjaxList(url1){
	 	 
    $.ajax({
        type		: "POST",
        url		: url1,			   
        data		: {
            'per_page':$('#toolbar').find('.select2-choice').find('.select2-chosen').html(),
            'search_venue':$("#selected_search_venue").val(), 
            'search_date':$("#search_date").val(),
            'search_recurring':$("#selected_search_recurring").val(), 
            'search_promoter':$("#selected_search_promoter").val(), 
            'search_verified':$("#selected_search_verified").val(), 
            'sort_field':$('.panel-body').attr('sort_field'),
            'sort_val':$('.panel-body').attr('sort_val'),
            'search_name':$('#data-search').val()
            },
        success	: function(resp){			      
            $('#data-list').html(resp);
            chatDesc();
				   
        },
        complete : function (){
        	
            $('#data-list').find(".dataTable th").each(function(){
                $(this).removeClass('sorting_desc').removeClass('sorting_asc');
               // console.log($('.panel-body').attr('sort_field'));
                if($(this).attr('data-field')==$('.panel-body').attr('sort_field')){
                    $(this).addClass('sorting_'+$('.panel-body').attr('sort_val'))
                }
         				
            });
           // var $container = $('#img-tems');
			// initialize
			//$container.masonry({
			  //columnWidth: '.item',
			 // itemSelector: '.item'
			//});
			var $container = $('#img-tems');
			// initialize Masonry after all images have loaded  
			$container.imagesLoaded( function() {
			  $( ".panel-body .thumbnail" ).on({
					 mouseenter: function() {
						$( this ).find('.user-over').css({'opacity':1});
						$( this ).find('.user-img').css({'opacity':0})
					}, mouseleave: function() {
						$( this ).find('.user-over').css({'opacity':0});
						$( this ).find('.user-img').css({'opacity':1});
					}
				});
			});
        }
    });
}
function format(state) {
    var originalOption = state.element;
    return  $(originalOption).data('name') ;
}

function formatselected(state) {
    var originalOption = state.element;
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