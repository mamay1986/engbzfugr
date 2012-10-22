[[ extends  TEMPLATE_PATH ~ "base/main.tpl" ]]
[[block center]]
	<script>
		{place_script|raw}
	</script>
    [[ include TEMPLATE_PATH ~ "breadcrumbs/main.tpl"]]

	<div class="names">
		<span>Добавление объявления</span>
	</div>
	<div class="form_add_advert">
	[[if request.advertid]]
		<p class="h1">Ваше объявление будет доступно <a href="{advert_obj.getLinkPage(request.advertid)}">по ссылке</a> .</p>
	[[else]]
		[[if error]]
			<p class="error_block"> 
				Имеются незаполненные обязательные поля.
			</p>
		[[endif]]
		<form action="{advert_obj.getLinkPage(modul.id)}?form=add_advert" method="post" enctype="multipart/form-data">
			<input type="hidden" name="action" value="add_advert" />
			<table>
				<tr>
					<td>Категория <span class="zvezdochka">*</span></td>
					<td>
						<select class="[[if error.parent]]error[[endif]]" name="parent">
							<option value="0">Выберите категорию</option>
							[[for item in categories]]
								<optgroup label="{item.caption}">
									[[for catitem in item.child]]
										<option value="{catitem.id}" [[if request.parent == catitem.id]]selected[[endif]]>{catitem.caption}</option>
									[[endfor]]
								</optgroup>
							[[endfor]]
						<select>
					</td>
				</tr>
				<tr>
					<td>Тип объявления <span class="zvezdochka">*</span></td>
					<td>
						<select class="[[if error.type_advert]]error[[endif]]" name="type_advert">
							<option value="0" [[if request.type_advert == 0]]selected[[endif]]>Продажа</option>
							<option value="1" [[if request.type_advert == 1]]selected[[endif]]>Покупка</option>
							<option value="2" [[if request.type_advert == 2]]selected[[endif]]>Аренда</option>
							<option value="3" [[if request.type_advert == 3]]selected[[endif]]>Услуги</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Название <span class="zvezdochka">*</span></td>
					<td>
						<input class="fieldfocus [[if error.caption]]error[[endif]]" title="Введите название объявления" type="text" name="caption" value="{request.caption}" />
					</td>
				</tr>
				<tr>
					<td>Цена</td>
					<td>
						<input class="fieldfocus [[if error.price]]error[[endif]]" title="Введите цену на товар" type="text" name="price" value="{request.price}" />
					</td>
				</tr>
				<tr>
					<td>Контактное лицо <span class="zvezdochka">*</span></td>
					<td>
						<input class="fieldfocus [[if error.name_user]]error[[endif]]" title="Введите как к Вам представляться" type="text" name="name_user" value="{request.name_user}" />
					</td>
				</tr>
				<tr>
					<td>Email <span class="zvezdochka">*</span></td>
					<td>
						<input class="fieldfocus [[if error.email]]error[[endif]]" title="Введите контактный email" type="text" name="email" value="{request.email}" />
					</td>
				</tr>
				<tr>
					<td>Телефон</td>
					<td>
						<input class="fieldfocus [[if error.phone]]error[[endif]]" title="Введите контактный телефон" type="text" name="phone" value="{request.phone}" />
					</td>
				</tr>
				<tr>
					<td>Фото</td>
					<td>
						<input type="file" name="image" />
					</td>
				</tr>
				<tr>
					<td>Текст <span class="zvezdochka">*</span></td>
					<td>
						<textarea class="fieldfocus [[if error.text]]error[[endif]]" title="Введите текст объявления" name="text" >{request.text}</textarea>
					</td>
				</tr>
				<tr>
					<td></td>
					<td align="right">
						<button onclick="$('#preloader_advert').show();" class="button" type="submit">
							<span class="button-left">
								<span class="button-right">
									<span class="button-text">
										<span>Добавить</span>
									</span>
								</span>
							</span>
						</button>
					</td>
				</tr>
			</table>
		</form>
		<div id="preloader_advert" class="preloader"><center><img src="/images/preloader.gif"></center></div>
	[[endif]]
	</div>
[[endblock]]

[[block right]]
	[[ include TEMPLATE_PATH ~ "news/right_block.tpl"]]
[[endblock]]