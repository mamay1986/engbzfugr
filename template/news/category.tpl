[[ extends  TEMPLATE_PATH ~ "base/main.tpl" ]]
[[block center]]
	
	[[ include TEMPLATE_PATH ~ "breadcrumbs/main.tpl"]]
	
	<div class="names">
		<span>{item.caption}</span>
	</div>
	
	[[if categories]]
		<div class="afisha-topic">
			По категориям: 
			[[set id_parent_page = item.parent]]
			[[set id_page = item.id]]
			[[for cat in categories]]
				<a class="[[if loop.index0>0]]separator [[endif]][[if id_parent_page == cat.id or id_page == cat.id ]]active[[endif]]" href="{site_obj.getLinkPage(cat.id)}">{cat.caption}</a>
			[[endfor]]
		</div>	<br/>
	[[endif]]
	
	[[for new in news]]
		<div class="shortnews">
			[[if new.picture]]<a href="{new.full_url}"><img alt="" src="/{news_obj.fileDirectory}{new.id}/100_80_{new.picture}" align="left"class="shortnewsimg"></a>[[endif]]
			<div class="date"><span>{df('date','d.m.Y',new.date)}</span></div>
			<a href="{new.full_url}">{new.caption}</a>
			<p>	
				{new.anons|raw}
			</p>
			[[set tags = new.tags]]
			[[ include TEMPLATE_PATH ~ "blocks/tags.tpl"]]
		</div>
	[[endfor]]
	
	[[ include TEMPLATE_PATH ~ "pager/pager.tpl"]]
	
[[endblock]]

[[block right]]
	[[ include TEMPLATE_PATH ~ "news/right_block.tpl"]]
[[endblock]]