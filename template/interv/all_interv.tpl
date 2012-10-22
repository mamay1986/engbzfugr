[[ extends  TEMPLATE_PATH ~ "base/main.tpl" ]]
[[block center]]

	[[ include TEMPLATE_PATH ~ "breadcrumbs/main.tpl"]]

	<div class="names">
		<span>{modul.caption}</span>
	</div>
	[[for interv in intervs]]
		<div class="shortnews">
			[[if interv.picture]]<a href="{interv.full_url}"><img alt="" src="/{interv_obj.fileDirectory}{interv.id}/100_80_{interv.picture}" align="left"class="shortnewsimg"></a>[[endif]]
			<div class="date"><span>{df('date','d.m.Y',interv.date)}</span></div>
			<a href="{interv.full_url}">{interv.caption}</a>
			<p>	
				{interv.anons|raw}
			</p>
			[[set tags = interv.tags]]
			[[ include TEMPLATE_PATH ~ "blocks/tags.tpl"]]
		</div>
	[[endfor]]
	<div style="clear:both;"></div>
	<div class="mbg"></div>
	
	[[ include TEMPLATE_PATH ~ "pager/pager.tpl"]]
	
[[endblock]]

[[block right]]
	[[ include TEMPLATE_PATH ~ "news/right_block.tpl"]]
[[endblock]]