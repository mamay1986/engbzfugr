[[ extends  TEMPLATE_PATH ~ "base/main.tpl" ]]
[[block center]]

    [[ include TEMPLATE_PATH ~ "breadcrumbs/main.tpl"]]
    
    <div class="names">
		<span>{item.caption}</span>
	</div>
	<div class="name_filter">Поиск событий города</div>
    [[if categories]]
    <div class="afisha-topic">
    	По категориям: 
        [[for item in categories]]
        	<a [[if loop.index0>0]]class="separator"[[endif]] href="{meets_obj.getLinkPage(item.id)}">{item.caption}</a>
        [[endfor]]
    </div>
    [[endif]]
    
    [[ include TEMPLATE_PATH ~ "meets/meet_filter.tpl"]]
    
	<div class="story">
		[[if item.picture]]
			<img alt="" width="406" src="/{site_obj.fileDirectory}{item.id}/406__{item.picture}" align="left" style="float:left;" />
		[[endif]] 
		<p class="date">Дата : {df('date','d.m.Y',item.dop_params.date)}[[if item.dop_params.date_from]] - {df('date','d.m.Y',item.dop_params.date_from)}[[endif]]</p>
		
		[[if item.dop_params.info_place]]<div class="info_place">Мероприятие пройдет в <a href="{site_obj.getLinkPage(item.dop_params.info_place.id)}">{item.dop_params.info_place.caption}</a></div>[[endif]]
		
		{item.text|raw}
	</div>

	[[set tags = item.tags]]
	[[ include TEMPLATE_PATH ~ "blocks/tags.tpl"]]
	
	[[ include TEMPLATE_PATH ~ "comments/main.tpl"]]
	
[[endblock]]

[[block right]]
	[[ include TEMPLATE_PATH ~ "news/right_block.tpl"]]
[[endblock]]