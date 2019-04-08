<!-- BEGIN: main -->
<div class="tms_store">

		<div class="row">
		<!-- BEGIN: loop -->
		<div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
			<div class="tms_store_col">
            <div class="tms_store_img"><a href="{ROW.link}" title="{ROW.title}"><img src="{ROW.image}" alt="{ROW.title}"title="{ROW.title}"></a></div>	
			<div class="tms_store_city text-center" >{ROW.quanhuyen} - {ROW.tinhthanh}</div>
            <div class="caption text-center">	<h3><a href="{ROW.link}" title="{ROW.title}">{ROW.title}</a></h3></div>	
			</div>
		</div>
		<!-- END: loop -->

	</div>
	
	
	<div class="clear"></div>
	<!-- BEGIN: generate_page -->
	<div class="text-center">{NV_GENERATE_PAGE}</div>
	<!-- END: generate_page -->
	
	
</div>
<!-- END: main -->