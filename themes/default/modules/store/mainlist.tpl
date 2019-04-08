<!-- BEGIN: main -->
	<div class="tms_store">
	<!-- BEGIN: cata -->
	<div class="tms_store_title"><h3><a href="{cata.link}" title="{cata.title}" alt="{cata.title}">{cata.title}</a></h3></div>
	<div class="tms_store_body">
	
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

	</div>
	<!-- END: cata -->
</div>
<!-- END: main -->