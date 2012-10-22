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
				<a class="[[if loop.index0>0]]separator [[endif]][[if id_parent_page == cat.id or id_page == cat.id ]]active[[endif]]" href="{cat.full_url}">{cat.caption}</a>
			[[endfor]]
		</div>	
	[[endif]]
		
    <div style="padding-top: 30px;">
		[[for item in adverts]]
			<div class="shortnews">
				[[if item.picture]]<a href="{item.full_url}"><img alt="" src="/{advert_obj.fileDirectory}{item.id}/100_80_{item.picture}" align="left" class="shortnewsimg"></a>[[endif]]
				<div class="date"><span>{df('date','d.m.Y H:i',item.date)}</span></div>
				<a href="{item.full_url}">{item.caption}</a>
				<p>	
					{item.text|raw}
				</p>
			</div>
		[[endfor]]
	
		[[ include TEMPLATE_PATH ~ "pager/pager.tpl"]]
    </div>                            
    {item.text|raw}

[[endblock]]

[[block right]]
	[[ include TEMPLATE_PATH ~ "news/right_block.tpl"]]
[[endblock]]
