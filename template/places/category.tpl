[[ extends  TEMPLATE_PATH ~ "base/main.tpl" ]]
[[block center]]

    [[ include TEMPLATE_PATH ~ "breadcrumbs/main.tpl"]]
    
    <div class="names">
		<span>{item.caption}</span>
	</div>
	
	<div class="add_advert_button">
		<a href="{site_obj.getLinkPage(modul.params.id)}?form=add_place">
			<button type="submit" class="button">
				<span class="button-left">
					<span class="button-right">
						<span class="button-text">
							<span><b>Добавить место</b></span>
						</span>
					</span>
				</span>
			</button>
		</a>
	</div>
	
	<div class="afisha-topic">
		По категориям: 
		[[set id_parent_page = item.parent]]
		[[set id_page = item.id]]
		[[for cat in categories]]
			<a class="[[if loop.index0>0]]separator [[endif]][[if id_parent_page == cat.id or id_page == cat.id ]]active[[endif]]" href="{cat.full_url}">{cat.caption}</a>
		[[endfor]]
	</div>
	
    <div style="padding-top: 30px;">
		[[for item in places]]
			<div class="shortnews">
				[[if item.picture]]<a href="{item.full_url}"><img alt="" src="/{places_obj.fileDirectory}{item.id}/100_80_{item.picture}" align="left"class="shortnewsimg"></a>[[endif]]
				<div class="date">
					<div class="name">
						<a href="{places_obj.getLinkPage(item.parent)}"><span>{item.name_category}</span></a>
					</div>
				</div>
				<a href="{item.full_url}">{item.caption}</a>
				<p>	
					{item.addres}<br/>
					{item.phone}
				</p>
				[[set tags = item.tags]]
				[[ include TEMPLATE_PATH ~ "blocks/tags.tpl"]]
			</div>
		[[endfor]]
	
		[[ include TEMPLATE_PATH ~ "pager/pager.tpl"]]
    </div>                            
    {item.text|raw}

[[endblock]]

[[block right]]
	[[ include TEMPLATE_PATH ~ "news/right_block.tpl"]]
[[endblock]]
