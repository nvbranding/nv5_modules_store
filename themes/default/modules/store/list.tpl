<!-- BEGIN: main -->
<div class="tms_store">

	
		<!-- BEGIN: loop -->
		<div class="panel panel-default">
		<div class="panel-body">
			<a href="{ROW.link}"  alt="{ROW.title}"title="{ROW.title}"><img src="{ROW.image}" alt="{ROW.title}"title="{ROW.title}" style="max-height:140px" class="img-thumbnail pull-left imghome" /></a>
			<h3><a href="{ROW.link}" title="{ROW.title}" {ROW.target_blank}>{ROW.title}</a></h3>
			<div class="text-muted">
			<ul>
			<li><strong>{LANG.phone}:</strong> <a href="tel:{ROW.sdt}" title="{ROW.sdt}">{ROW.sdt}</a></li>
			<li><strong>{LANG.email}:</strong> <a href="mailto:{ROW.email}" title="{ROW.email}">{ROW.email}</a></li>
			<li><strong>{LANG.website}:</strong> <a href="{ROW.website}" title="{ROW.website}">{ROW.website}</a></li>
			<li><strong>{LANG.facebook}:</strong> <a href="{ROW.facebook}" title="{ROW.facebook}">{ROW.facebook}</a></li>
			<li><strong>{LANG.diachi}:</strong> {ROW.dia_chi}</li>
			</ul>
			</div>
		</div>
	</div>
		<!-- END: loop -->


	
	
	<div class="clear"></div>
	<!-- BEGIN: generate_page -->
	<div class="text-center">{NV_GENERATE_PAGE}</div>
	<!-- END: generate_page -->
	
	
</div>
<!-- END: main -->