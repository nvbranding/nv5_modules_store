<!-- BEGIN: main -->

<script type="text/javascript" src="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/store/magicslideshow/magicslideshow.js"></script>
<link href="{NV_BASE_SITEURL}themes/{TEMPLATE}/images/store/magicslideshow/magicslideshow.css" type="text/css" rel="stylesheet" media="all" />



    <div class="panel panel-default">
        <div class="panel-body">
		
	 <div class="row">
    <div class="col-xs-24 col-sm-14 col-md-14">	
				 
	<div class="MagicSlideshow album-{ALBUM.id}" data-options="selectors: bottom; selectors-style: thumbnails; selectors-size: 40px;">
		<img src="{row.image}" alt="{CATA.title}">
		<!-- BEGIN: image_loop -->
		<img src="{IMAGE.image}" alt="{CATA.title}">
		<!-- END: image_loop -->

	</div>

		</div>		
		 <div class="col-xs-24 col-sm-10 col-md-10">	
		     <ul class="product_info">
            <li><strong>{row.title}</strong> </li>
            <li><strong>{LANG.group}:</strong> <a href="{CATA.link}" title="{CATA.title}" alt="{CATA.title}">{CATA.title}</a></li>
			<li><strong>{LANG.phone}:</strong> <a href="tel:{row.sdt}" title="{row.sdt}">{row.sdt}</a></li>
			<li><strong>{LANG.email}:</strong> <a href="mailto:{row.email}" title="{row.email}">{row.email}</a></li>
			<li><strong>{LANG.website}:</strong> <a href="{row.website}" title="{row.website}">{row.website}</a></li>
			<li><strong>{LANG.facebook}:</strong> <a href="{row.facebook}" title="{row.facebook}">{row.facebook}</a></li>
			<li><strong>{LANG.diachi}:</strong> {row.dia_chi}</li>
            </ul>

		</div>	

        </div>
        </div>
        </div>


<!-- BEGIN: bodytext -->
<div class="tms_store_deatail_titlle"><h3>{LANG.bodytext}</h3></div>
	{row.bodytext}
<div class="clearfix"></div>
<!-- END: bodytext -->
<div class="clearfix"></div>
<!-- BEGIN: thaoluan -->
<div class="tms_store_deatail_titlle"><h3>{LANG.thaoluan}</h3></div>
<div id="fb-root"></div>
<script type="text/javascript" data-show="after">
	( function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id))
				return;
			js = d.createElement(s);
			js.id = id;
			js.src = "//connect.facebook.net/vi_VN/all.js#xfbml=1&appId={row.facebookapi}";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));
</script>
	
<div class="fb-comments" data-href="{LINKFB}" data-num-posts="5" data-width="100%" data-colorscheme="light"></div>

<div class="clearfix"></div>
<!-- END: thaoluan -->			


<div class="tms_store_deatail_titlle"><h3>{LANG.bodytext_maps}</h3></div>
<div class="clearfix"></div>
<div class="tms_store_body">
<div class="clearfix"></div>
<div id="map"></div>

    <script>
	
	
	
	
	 function star()
	{
	 if (navigator.geolocation) {
			navigator.geolocation.getCurrentPosition(showLocation);
		} else { 
			$('#location').html('Geolocation is not supported by this browser.');
		}
	}
	
	
	function showLocation(position) {
	
    latitude = position.coords.latitude;
    longitude = position.coords.longitude;
		initMap(latitude,longitude);
	}
	
      function initMap(latitude,longitude) {
        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer;
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 20,
          center: {lat: 61.85, lng: -87.65}
        }); 
        directionsDisplay.setMap(map);
		
		
        calculateAndDisplayRoute(directionsService, directionsDisplay);
		
		
      }

      function calculateAndDisplayRoute(directionsService, directionsDisplay) {
	  console.log(latitude);
	  console.log(longitude);
		
        directionsService.route({
          origin: new google.maps.LatLng(latitude, longitude),
          destination: new google.maps.LatLng({GOOGLEMAPLAT1}, {GOOGLEMAPLNG1}),
          travelMode: 'DRIVING'
        }, function(response, status) {
          if (status === 'OK') {
            directionsDisplay.setDirections(response);
          } else {
            window.alert('Directions request failed due to ' + status);
          }
        });
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA7_ZyiNQBuJxZKsoOWWNGshZx8kewMt7o&callback=star">
    </script>
</div>
<div class="clearfix"></div>



	
<div class="tms_store_deatail_titlle"><h3>{LANG.lienquan_detail}</h3></div>

<div class="row">
			
		<!-- BEGIN: loop_liequan -->
		<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
			<div class="tms_store_col">
            <div class="tms_store_img"><a href="{LQ.link}" title="{LQ.title}"><img src="{LQ.image}" alt="{LQ.title}"title="{LQ.title}"></a></div>	
			<div class="tms_store_city text-center" >{LQ.quanhuyen} - {LQ.tinhthanh}</div>
            <div class="caption text-center"><h3><a href="{LQ.link}" title="{LQ.title}">{LQ.title}</a></h3></div>	
			</div>
		</div>
		<!-- END: loop_liequan -->
				
</div>

<div class="clearfix"></div>



<!-- END: main -->