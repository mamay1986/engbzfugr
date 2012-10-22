[[ extends  TEMPLATE_PATH ~ "base/main.tpl" ]]
[[block center]]

    [[ include TEMPLATE_PATH ~ "breadcrumbs/main.tpl"]]

	<div class="names">
		<span>{modul.caption}</span>
	</div>
	{modul.text|raw}
	<br/>
	<div class="names">
		<span>Добавление новости</span>
	</div>
	<div class="form_add_advert">
	[[if request.addnews]]
		<p class="h1">Спасибо, Ваша заявка отправлена на модерацию.</p>
	[[else]]
		[[if error]]
			<p class="error_block"> 
				Имеются незаполненные обязательные поля.
			</p>
		[[endif]]
		<form action="{site_obj.getLinkPage(modul.id)}?form=add_news" method="post" enctype="multipart/form-data">
			<input type="hidden" name="action" value="add_news" />
			<table>
				<tr id="caption">
					<td>Название <span class="zvezdochka">*</span></td>
					<td>
						<input class="fieldfocus [[if error.caption]]error[[endif]]" title="Введите название новости" type="text" name="caption" value="{request.caption}" />
					</td>
				</tr>
				<tr>
					<td>Телефон или Email</td>
					<td>
						<input class="fieldfocus [[if error.contact_info]]error[[endif]]" title="Введите контактные данные" type="text" name="contact_info" value="{request.contact_info}" />
					</td>
				</tr>
				<tr>
					<td>Фото</td>
					<td>
						<input type="file" name="image" />
					</td>
				</tr>
				<tr>
					<td>Текст новости<span class="zvezdochka">*</span></td>
					<td>
						<textarea class="fieldfocus [[if error.text]]error[[endif]]" title="Введите текст новости" name="text" >{request.text}</textarea>
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