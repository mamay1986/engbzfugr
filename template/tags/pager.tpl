[[if pages>1]]
[[set url_page = site_obj.getLinkPage(item.id)]]
<div style="clear:both;"></div>
<div class="pagination">
	<span style="float:left;">Страницы:</span>
	[[if page==1]]
		
	[[else]]
			/*<a href="{url_page}{pager_tag}/page-{page-1}/[[if query_str]]?{query_str}[[endif]]" class="active"> << </a>*/
			<a href="{url_page}{pager_tag}/page-1/[[if query_str]]?{query_str}[[endif]]" class="last">Первая</a>
			<a href="{url_page}{pager_tag}/page-{page-1}/[[if query_str]]?{query_str}[[endif]]" class="next">Предыдущяя</a>
	[[endif]]
	[[if pages<=10]]
		[[set k1 = 1]]
		[[set k2 = pages]]
	[[else]]
		[[if page>0 and page<=7]]
			[[set k1 = 1]]
			[[set k2 = 9]]
			[[set k3 = 1]]
		[[elseif page<=pages and page>=pages-7]]
			[[set k1 = pages-9]]
			[[set k2 = pages]]
			[[set k3 = 2]]
		[[else]]
			[[set k1 = page-3]]
			[[set k2 = page+3]]
			[[set k3 = 3]]
		[[endif]]
	[[endif]]
	[[if k3==2 or k3==3]]
			<a href="{url_page}{pager_tag}/page-1/[[if query_str]]?{query_str}[[endif]]">1</a>
			...
	[[endif]]
	
	[[for i in k1 .. k2]]
		[[if page==i]]
			<a href="javascript: void(0);" class="active">{i}</a>
		[[else]]
			<a href="{url_page}{pager_tag}/page-{i}/[[if query_str]]?{query_str}[[endif]]">{i}</a>
		[[endif]]
	[[endfor]]

	[[if k3==1 or k3==3]]
			...
			<a href="{url_page}{pager_tag}/page-{pages}/[[if query_str]]?{query_str}[[endif]]">{pages}</a>
	[[endif]] 
	
	[[if page==pages]]
		
	[[else]]
			/*<a href="{url_page}{pager_tag}/page-{page+1}/[[if query_str]]?{query_str}[[endif]]" class="active">>></a>*/
			<a href="{url_page}{pager_tag}/page-{page+1}/[[if query_str]]?{query_str}[[endif]]" class="next">Следующая</a>
			<a href="{url_page}{pager_tag}/page-{pages}/[[if query_str]]?{query_str}[[endif]]" class="last">Последняя</a>
	[[endif]]
</div>
[[endif]]