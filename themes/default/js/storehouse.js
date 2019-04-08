/**
 * @Project NUKEVIET 4.x
 * @Author Thuong Mai So <hoangnt@nguyenvan.vn>
 * @Copyright (C) 2018 Thuong Mai So. All rights reserved
 * @License: Not free read more http://nukeviet.systems
 * @Createdate Fri, 10 Aug 2018 07:54:45 GMT
 */

 
 function get_alias(mod, id) {
	var title = strip_tags(document.getElementById('name').value);
	if (title != '') {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax&nocache=' + new Date().getTime(), 'title=' + encodeURIComponent(title) + '&mod=' + mod + '&id=' + id, function(res) {
			if (res != "") {
				document.getElementById('alias').value = res;
			} else {
				document.getElementById('alias').value = '';
			}
		});
	}
	return false;
}

function formatRepo (repo) {
	console.log(repo);
	if (repo.loading) return repo.text;
	return repo.title;
}

function formatRepoSelection (repo) {
	return '';
}
function formatRepoSelection2 (repo) {
	return repo.title;
}
function warehouse_id_select2 (current) {
	if(current == null) current = 0;
	$("#warehouse_id").select2({
	        language: "vi",
	        ajax: {
		        url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax',
	            dataType: 'json',
	            delay: 0,
	            data: function (params) {
	            	
                	return {
					  mod : "warehouse_list",
					  store_id : current
              		};
              	},
	            processResults: function (data, params) {
	                return {
	                    results: data
	                };
	            },
		        cache: true
	        },
	        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
	        minimumInputLength: 0,
	        templateResult: formatRepo, // omitted for brevity, see the source of this page
	        templateSelection: formatRepoSelection2 // omitted for brevity, see the source of this page
	    });
}
function warehouse_id_select2_new (current) {
	if(current == null) current = 0;
	$("#warehouse_id_new").select2({
	        language: "vi",
	        ajax: {
		        url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax',
	            dataType: 'json',
	            delay: 0,
	            data: function (params) {
	            	
                	return {
					  mod : "warehouse_list",
					  store_id : current
              		};
              	},
	            processResults: function (data, params) {
	                return {
	                    results: data
	                };
	            },
		        cache: true
	        },
	        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
	        minimumInputLength: 0,
	        templateResult: formatRepo, // omitted for brevity, see the source of this page
	        templateSelection: formatRepoSelection2 // omitted for brevity, see the source of this page
	    });
}
function add_product(current){
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax&nocache=' + new Date().getTime(),  'mod=products_items&id=' + current, function(res) {
		console.log(res);$('.ui-sortable').append(
			'<tr>' +
				'<td>' + res.title + '(' + res.code +')<input type="hidden" name="product_id[]" value="' + res.id +'"><input type="hidden" name="product_code[]" value="' + res.code +'"><input type="hidden" name="product_name[]" value="' + res.title +'"></td>' +
				'<td>' + '<input type="text" name="product_expried[]" value="">' +'</td>' +
				'<td>' + res.cost +
				' <input type="hidden" name="product_net_cost[]" value="' + res.cost  +'">' + 
				' <input type="hidden" name="product_unit_cost[]" value="' + res.cost  +'">' + 
				' <input type="hidden" name="product_real_unit_cost[]" value="' + res.cost  +'">' + 
				'</td>' +
				'<td><input type="text" name="product_base_quantity[]" value="' + res.quantity +'">' +
				'<input type="hidden" name="product_unit[]" value="' + res.purchase_unit +'">' +
				'</td>' +
				'<td><input type="hidden" name="product_discount[]" value="' + res.discount +'">' + res.discount +'</td>' +
				'<td><input type="hidden" name="product_tax_rate[]" value="' + res.tax_id +'">' +
					'<input type="hidden" name="product_tax[]" value="' + res.tax +'">' +
					'<input type="hidden" name="product_cost_tax[]" value="' + res.cost_tax +'">' +
					'(' + res.tax +'%) <br> ' + res.cost_tax +'</td>' +
				'<td><input type="hidden" name="product_total[]" value="' + res.total +'">' + res.total +'</td>' +
				'<td>' + '' +'</td>' +
			'</tr>'
		);	
	}); 
}


$(document).ready(function() {
    $("#products_id").select2({
        language: "vi",
        ajax: {
        url: script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                  return {
                      q: params.term, // search term
                      page: params.page,
					  mod: "products_list"
                  };
              },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return {
                    results: data,
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
        cache: true
        },
        escapeMarkup: function (markup) { return markup; }, // let our custom formatter work
        minimumInputLength: 1,
        templateResult: formatRepo, // omitted for brevity, see the source of this page
        templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
    });
	$("#products_id").on('change', function () {
	   var current = $("#products_id").select2("val");
		add_product(current);
	 });
	
	$("#select2-products_id-results .select2-results__option--highlighted").click(function() {
		var current = $("#products_id").select2("val");
		add_product(current);
	});
	$("#store_id").select2();
	var store_id = $("#store_session").val();
	var store_id_new = $("#store_session_new").val();
	warehouse_id_select2 (store_id);
	warehouse_id_select2_new (store_id_new);
    $("#store_id").on('change', function () {
		 var current = $("#store_id").select2("val");
		 $("#warehouse_id").val(null).trigger("change");
		 //warehouse_id_select2 (current);
		$.ajax({
    		type: "GET",
    		url: script_name + '?' + nv_name_variable + '=storehouse&' + nv_fc_variable + '=ajax&mod=set_session_store_id&session_store_id=' + current,
        	dataType: 'json',
        	data: function (params) {
                 return {
					 mod: 'set_session_store_id',
					 session_store_id:current
                 };
             },
            success: function (data, params) {
            	$("#store_session").val(data.session_store_id);
            	//warehouse_id_select2 (data.session_store_id);
            	console.log(data);
            	window.location.href = window.location.href;
            }
    	});
    	
	});
	$("#store_id_new").on('change', function () {
		 var current = $("#store_id_new").select2("val");
		 $("#warehouse_id_new").val(null).trigger("change");
		 warehouse_id_select2_new (current);
		
	 });
});

