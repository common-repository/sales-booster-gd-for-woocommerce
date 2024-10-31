(function( $ ) {
	'use strict';

	$(document).ready(function(){
        //GD Tabs
        const tabs = document.querySelectorAll(".tabs");
        const tab = document.querySelectorAll(".tab");
        const panel = document.querySelectorAll(".panels");
        function onTabClick(event) {
          // deactivate existing active tabs and panel
          for (let i = 0; i < tab.length; i++) {
            tab[i].classList.remove("active");
          }
          for (let i = 0; i < panel.length; i++) {
            panel[i].classList.remove("active");
          }
          // activate new tabs and panel
          event.target.classList.add('active');
          let classString = event.target.getAttribute('data-target');
          //console.log(classString);
          document.getElementById('panels').getElementsByClassName(classString)[0].classList.add("active");
        }
        for (let i = 0; i < tab.length; i++) {
          tab[i].addEventListener('click', onTabClick, false);
        }
        /*
        $('.custom_date').datepicker({
            dateFormat : 'yy-mm-dd',
            maxDate: new Date()
        });
        */
        //more
        $(document).on("click", ".create-more", function(){
            $("#Panel2").trigger("click");
            return false;
        });    
        //Delete User Order
        $(document).on("click", ".change-user-order", function(){
            var id = $(this).data("id"),
                name = $(this).data("name"),
                country = $(this).data("country"),
                productname = $(this).data("productname"),
                orderdate = $(this).data("orderdate"),
                dataType = $(this).data("type"),
                editForm = $("#edit_exe_order");
            if(dataType == 'delete'){
                if (confirm('Are you sure you want to delete?')) {
                    $.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        data: {
                            action: 'user_order_table',
                            id: id,
                            dataType: dataType
                        },
                        success: function( data ) {
                            $(".id-"+id).remove();
                            
                        }
                    });
                }
            } else if(dataType == 'edit')  {
                $(editForm).find("[name='id']").val(id);
                $(editForm).find("[name='name']").val(name);
                $(editForm).find("[name='country']").val(country);
                $(editForm).find("[name='product_name']").val(productname);
                $(editForm).find("[name='order_date']").val(orderdate);
                $("#edit_exe_order").fadeIn();
            }
            
            
            return false; 
        });
        //edit new user order
        $("#edit_exe_user_order").on("submit", function(){
            var id = $(this).find("[name='id']").val(),
                name = $(this).find("[name='name']").val(),
                country = $(this).find("[name='country']").val(),
                product_name = $(this).find("[name='product_name']").val(),
                order_date = $(this).find("[name='order_date']").val(),
                dataType = $(this).find("[name='dataType']").val();
            //console.log(name);
            
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'user_order_table',
                    dataType: dataType,
                    id: id,
                    name: name,
                    country: country,
                    product_name: product_name,
                    order_date: order_date
                },
                success: function( data ) {
                    //console.log(data);
                    $("#sales_booster_tbody").html(data)
                    $("#edit_exe_order").fadeOut();
                }
            });
            
            return false; 
        });
        //add new user order
        $("#add_new_user_order").on("submit", function(){
            var name = $(this).find("[name='name']").val(),
                country = $(this).find("[name='country']").val(),
                product_name = $(this).find("[name='product_name']").val(),
                order_date = $(this).find("[name='order_date']").val(),
                img_att = $(this).find("[name='gd-imgf-add']").val();
            //console.log(order_date);
            
            $.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'insert_user_order_table',
                    name: name,
                    country: country,
                    product_name: product_name,
                    order_date: order_date,
                    gd_imgf_add: img_att
                },
                success: function( data ) {
                    $("#sales_booster_tbody").html(data)
                    $("#add_new_user_order")[0].reset();
                    $(".add-new-order-l").html('<a href="#" class="gd-uploadf-img">Upload image</a>');
                }
            });
            
            return false; 
        });
        //close
        $(document).on("click", ".close-e", function(){
            $("#edit_exe_order").fadeOut();
            return false;
        });
        //upload image
        // on upload button click
    	$(document).on( 'click', '.gd-upload-img', function(e){
     
    		e.preventDefault();
            var rowId = $(this).data('id');                                                            
    		var button = $(this),
    		custom_uploader = wp.media({
    			title: 'Insert image',
    			library : {
    				// uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
    				type : 'image'
    			},
    			button: {
    				text: 'Use this image' // button label text
    			},
    			multiple: false
    		}).on('select', function() { // it also has "open" and "close" events
    			var attachment = custom_uploader.state().get('selection').first().toJSON();
    			button.html('<img id="'+attachment.id+'" src="' + attachment.url + '">').next().val(attachment.id).next().show();
                //add image id
                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        action: 'insert_image_in_table',
                        rowId: rowId,
                        
                        attachmentid: attachment.id
                    },
                    success: function( data ) {
                        $("#sales_booster_tbody").html(data);
                    }
                });
    		}).open();
     
    	});
        // add form button click
    	$(document).on( 'click', '.gd-uploadf-img', function(e){
     
    		e.preventDefault();
    		var button = $(this),
    		custom_uploader = wp.media({
    			title: 'Insert image',
    			library : {
    				// uploadedTo : wp.media.view.settings.post.id, // attach to the current post?
    				type : 'image'
    			},
    			button: {
    				text: 'Use this image' // button label text
    			},
    			multiple: false
    		}).on('select', function() { // it also has "open" and "close" events
    			var attachment = custom_uploader.state().get('selection').first().toJSON();
    			button.html('<img id="'+attachment.id+'" src="' + attachment.url + '">').next().val(attachment.id).next().show();
                $("#gd-imgf-add").val(attachment.id);
    		}).open();
     
    	});
        // on remove button click
    	$(document).on('click', '.gd-upload-img-rmv', function(e){
     
    		e.preventDefault();
            var rowId = $(this).data('id');
    		$.ajax({
                type: 'POST',
                url: ajaxurl,
                data: {
                    action: 'remove_image_in_table',
                    rowId: rowId
                },
                success: function( data ) {
                    $("#sales_booster_tbody").html(data);
                }
            });
    	});
    });    
    
})( jQuery );
