[[ extends  TEMPLATE_PATH ~ "base/main.tpl" ]]
[[block center]]

    [[ include TEMPLATE_PATH ~ "breadcrumbs/main.tpl"]]
    
    <div class="names">
		<span>{item.caption}</span>
	</div>
    <div class="story">
		[[if item.picture]]<img alt="" width="406" src="/{advert_obj.fileDirectory}{item.id}/406__{item.picture}" align="left" style="float:left;">[[endif]]
		<p class="date">Дата добавления: {df('date','d.m.Y',item.dop_params.date)}</p>
		<div>
			<ul>
				<li><strong>Тип объявления:</strong> [[if item.dop_params.type_advert == 0]]Продаю[[elseif item.dop_params.type_advert == 1]]Покупаю[[elseif item.dop_params.type_advert == 2]]Аренда[[else]]Услуги[[endif]]</li>
				[[if item.dop_params.price]]<li><strong>Цена:</strong> {item.dop_params.price}</li>[[endif]]
				[[if item.dop_params.name_user]]<li><strong>Контактное лицо:</strong> {item.dop_params.name_user}</li>[[endif]]
				[[if item.dop_params.email]]<li><strong>Email:</strong> {item.dop_params.email}</li>[[endif]]
				[[if item.dop_params.phone]]<li><strong>Телефон:</strong> {item.dop_params.phone}</li>[[endif]]
			</ul>
		</div>
		<br/>
		<strong>Объявление:</strong></br>
		{item.text|raw}
	</div>
	<div style="margin-bottom:20px;">
		[[ include TEMPLATE_PATH ~ "blocks/block_social_like.tpl"]]
	</div>
[[endblock]]

[[block right]]
	[[ include TEMPLATE_PATH ~ "news/right_block.tpl"]]
[[endblock]]