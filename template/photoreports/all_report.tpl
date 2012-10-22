[[ extends  TEMPLATE_PATH ~ "base/main.tpl" ]]
[[block center]]

	[[ include TEMPLATE_PATH ~ "breadcrumbs/main.tpl"]]

	<div class="names">
		<span>{item.caption}</span>
	</div>
	
	<div class="afisha-topic photoreport" style="padding-bottom: 15px;">
		По категориям: 
		[[set id_parent_page = item.parent]]
		[[set id_page = item.id]]
		[[for cat in categories]]
			<a class="[[if loop.index0>0]]separator [[endif]][[if id_parent_page == cat.id or id_page == cat.id ]]active[[endif]]" href="{cat.full_url}">{cat.caption}</a>
		[[endfor]]
	</div>
	
	[[for report in reports]]
		<div class="fotolist [[if loop.index%3==0]]mrg0[[endif]]">
			[[if report.picture]]<a href="{report.full_url}"><img src="/{reports_obj.fileDirectory}{report.id}/232_155_{report.picture}" alt=""></a>[[endif]]
			<div class="fotoinfo">
				<span class="date">{df('date','d.m.Y',report.date)}</span>
				<span class="view">{gallery_obj.getCountPhoto(report.id)}</span>
				<div style="clear:both;"></div>
			</div>
			<a href="{report.full_url}" class="fotolink">{report.caption}</a>
		</div>
	[[endfor]]
	<div style="clear:both;"></div>
	<div class="mbg"></div>
	
	[[ include TEMPLATE_PATH ~ "pager/pager.tpl"]]
	
[[endblock]]

[[block right]]
	[[ include TEMPLATE_PATH ~ "news/right_block.tpl"]]
[[endblock]]