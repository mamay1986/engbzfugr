[[for item in items_meets_main]]
	[[ include TEMPLATE_PATH ~ "meets/item_repeat_main.tpl"]]
[[else]]
	<center>В данный день нет мероприятий</center><br/>
[[endfor]]