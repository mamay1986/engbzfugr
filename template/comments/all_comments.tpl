[[ extends  TEMPLATE_PATH ~ "base/main.tpl" ]]
[[block center]]
	
	[[ include TEMPLATE_PATH ~ "breadcrumbs/main.tpl"]]
	
	<div class="names">
		<span>{modul.caption}</span>
	</div>
	<div class="all_comments">
		[[for item in comments]]
			<div class="item_comments">
				<div class="date">Дата комментария: {df('date','H:i d.m.Y',item.date)}</div>
				<div class="comment_caption">
					<a href="{site_obj.getLinkPage(item.page_id)}#comment{item.id}">{item.page_caption}</a> от <a href="mailto:{item.name}@engels.bz">{item.name}</a>
				</div>
				<div class="comment_text">
					<p>{item.text|raw}</p>
				</div>
			</div>
		[[endfor]]
	</div>
	[[ include TEMPLATE_PATH ~ "pager/pager.tpl"]]
	
[[endblock]]

[[block right]]
	[[ include TEMPLATE_PATH ~ "news/right_block.tpl"]]
[[endblock]]