[[ extends  TEMPLATE_PATH ~ "base/main.tpl" ]]
[[block center]]

    [[ include TEMPLATE_PATH ~ "breadcrumbs/main.tpl"]]

    <div class="names">
		<span>
			{modul.caption} 
			[[if search_date_to]]
				( c {df('date','d.m.Y',search_date_to)} по {df('date','d.m.Y',search_date_from)})
			[[endif]]
		</span>
	</div>
	<div class="name_filter">Поиск событий города</div>
    [[if categories]]
    <div class="afisha-topic">
    	По категориям: 
		[[set id_parent_page = item.parent]]
		[[set id_page = item.id]]
        [[for item in categories]]
        	<a class="[[if loop.index0>0]]separator [[endif]][[if id_parent_page == item.id or id_page == item.id ]]active[[endif]]" href="{item.full_url}">{item.caption}</a>
        [[endfor]]
    </div>
    [[endif]]
    
    [[ include TEMPLATE_PATH ~ "meets/meet_filter.tpl"]]
    
    [[if not_found]]
    <p>По вашему запросу ничего не найдено. Попробуйте уточнить поиск.</p>
    [[endif]]
    

    [[for item in meets]]
    	<div class="shortnews">
			[[if item.picture]]<a href="{item.full_url}"><img alt="" src="/{meets_obj.fileDirectory}{item.id}/100_80_{item.picture}" align="left"class="shortnewsimg"></a>[[endif]]
			<div class="date">
				<div class="name">
					<a href="{meets_obj.getLinkPage(item.parent)}"><span>{item.name_category}</span></a>
				</div>
				<span class="f12">{df('date','d.m.Y H:i',item.date)} [[if item.date_from]]- {df('date','d.m.Y H:i',item.date_from)}[[endif]]</span>
			</div>
			<a href="{item.full_url}">{item.caption}</a>
			<p>	
				{item.anons|raw}
			</p>
			[[set tags = item.tags]]
			[[ include TEMPLATE_PATH ~ "blocks/tags.tpl"]]
		</div>
    [[endfor]]

    [[ include TEMPLATE_PATH ~ "pager/pager.tpl"]]


[[endblock]]

[[block right]]
	[[ include TEMPLATE_PATH ~ "news/right_block.tpl"]]
[[endblock]]