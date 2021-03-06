<!-- BEGIN: main -->
<link href="{NV_BASE_SITEURL}assets/js/select2/select2.min.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" data-show="after" src="{NV_BASE_SITEURL}assets/js/select2/select2.min.js"></script>
<div class="website_map_cuahang row">


	<style>#map_canvas { width:100%; height: 400px; }</style>
		<script src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyA7_ZyiNQBuJxZKsoOWWNGshZx8kewMt7o"></script>
		
		<div id="map_canvas"></div>

		<script>
		var map;
		var markersArray = [];
		var image_cuahang = 'img/';
		var bounds = new google.maps.LatLngBounds();
		var loc;

		function init(){
			var mapOptions = { mapTypeId: google.maps.MapTypeId.ROADMAP };
			map =  new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
			
			<!-- BEGIN: loop -->
			loc = new google.maps.LatLng({lat},{lng});
			bounds.extend(loc);
			addMarker(loc, '{row.title}', "active", '{row.link}', '{row.image}', '{anh_chinhanh}');
			<!-- END: loop -->

			map.fitBounds(bounds);
			map.panToBounds(bounds);    
		}

		function addMarker(location, title, active, link, image, anh_loai) {   

			var markerIcon = {
				  url: anh_loai,
				  scaledSize: new google.maps.Size(40, 40),
				  origin: new google.maps.Point(0, 0),
				  anchor: new google.maps.Point(32,65),
				  labelOrigin: new google.maps.Point(40,33)
				};
				
			var marker = new google.maps.Marker({
				position: location,
				icon: markerIcon,
				map: map,
				title: title,
				status: active
			});
			
			var html ='<div class="content_map_main"><div class="tiede_map_main"><a href="'+ link +'">'+ title +'</a></div><div class="image_des_map"><a href="'+ link +'"><img style="width:100px;" src="'+ image +'"/></a></div></div>';
				var infowindow = new google.maps.InfoWindow({
				  content:html
				  }); 
							
				marker.addListener('click', function() {
					 infowindow.open(map, marker); 
				});
				
				
		}

		$(function(){ init(); });
		</script>

<div class="tms_store_title">Tìm kiếm cửa hàng</div>
	<div class="tms_store_body">
		<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
					<span id="catid_search">
						 
							<select class="form-control" name="catid_search">
								<option value ="">-- Chọn danh mục --</option>
								<!-- BEGIN: CATALOGY -->					
								<option {CATALOGY.selected} value="{CATALOGY.alias}">{CATALOGY.title}</option>
								<!-- END: CATALOGY -->
							</select>
							
							
						</span>
				</div>
	<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
					<span id="getcity">
						 
							<select class="form-control tinhthanh" name="city_id" id="city">
								<option value ="0">-- Toàn quốc --</option>
								<!-- BEGIN: city -->					
								<option {CITY.selected} value="{CITY.alias}">{CITY.name}</option>
								<!-- END: city -->
							</select>
							
							
						</span>
				</div>
			<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
					<span id="getdistrict">	 
							<select class="form-control quanhuyen" name="district_id" id="district">
								<option value="0">-- Quận/ huyện --</option>
								<!-- BEGIN: district -->					
								<option value="{DISTRICT.alias}" {DISTRICT.selected}>{DISTRICT.type} {DISTRICT.name}</option>
								<!-- END: district -->
							</select>
							<span id="loading_district" style="display:none"><img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif"></span>							
						</span>
				</div>
		<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
					<span id="getward"> 
								<select class="form-control xaphuong" name="ward_id" id="ward">
								<option value="0">-- Xã/Phường --</option>
								<!-- BEGIN: ward -->					
								<option value="{WARD.alias}" {WARD.selected}>{DISTRICT.type} {WARD.name}</option>
								<!-- END: ward -->
								</select>
								<span id="loading_ward"  style="display:none"><img src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/images/load_bar.gif"></span>
							</span>
				</div>
		
	</div>
	
</div></div>





<script type="text/javascript">
	$(document).ready(function() {
	  $("select").select2();
	});
	
	$('select[name=catid_search]').change(function(){
		
		var catid_search = $(this).val();
		var tinhthanh = $('.tinhthanh option:selected').val();
		var quanhuyen = $('.quanhuyen option:selected').val();
		var xaphuong = $('.xaphuong option:selected').val();
		
		
		var html = 'map';
		if(catid_search != '')
		{
			html = html + '/' + catid_search;
		}
		
		if(tinhthanh != '')
		{
			html = html + '/' + tinhthanh;
		}
		
		if(quanhuyen != '')
		{
			html = html + '/' + quanhuyen;
		}
		
		if(xaphuong != '')
		{
			html = html + '/' + xaphuong;
		}
	
		if(html != '')
		{	
			window.location.href = '{NV_BASE_SITEURL}{module_name}/'+ html +'.html';
		}
		
		
	});
	
	
	$('.tinhthanh').change(function(){
	
		var catid_search = $('select[name=catid_search]').val();		
		var tinhthanh = $(this).val();
		
		var html = 'map';
		if(catid_search != '')
		{
			html = html + '/' + catid_search;
		}
		
		if(tinhthanh != '')
		{
			html = html + '/' + tinhthanh;
		}
	
		if(html != '')
		{	
			window.location.href = '{NV_BASE_SITEURL}{module_name}/'+ html +'.html';
		}
	});
	
	$('.quanhuyen').change(function(){
	
		var catid_search = $('select[name=catid_search]').val();	
		var tinhthanh = $('.tinhthanh option:selected').val();
		var quanhuyen = $(this).val();
	
		var html = 'map';
		if(catid_search != '')
		{
			html = html + '/' + catid_search;
		}
		
		if(tinhthanh != '')
		{
			html = html + '/' + tinhthanh;
		}
		
		if(quanhuyen != '')
		{
			html = html + '/' + quanhuyen;
		}
	
		if(html != '')
		{	
			window.location.href = '{NV_BASE_SITEURL}{module_name}/'+ html +'.html';
		}
		
		
	});
	
	$('.xaphuong').change(function(){
	
		var catid_search = $('select[name=catid_search]').val();	
		var tinhthanh = $('.tinhthanh option:selected').val();
		var quanhuyen = $('.quanhuyen option:selected').val();
		var xaphuong = $(this).val();
		
		var html = 'map';
		if(catid_search != '')
		{
			html = html + '/' + catid_search;
		}
		
		if(tinhthanh != '')
		{
			html = html + '/' + tinhthanh;
		}
		
		if(quanhuyen != '')
		{
			html = html + '/' + quanhuyen;
		}
		
		if(xaphuong != '')
		{
			html = html + '/' + xaphuong;
		}
	
		if(html != '')
		{	
			window.location.href = '{NV_BASE_SITEURL}{module_name}/'+ html +'.html';
		}
	
	});
	
	
	
	

</script>
<!-- END: main -->