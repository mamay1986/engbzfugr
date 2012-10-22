[[ extends  TEMPLATE_PATH ~ "base/main.tpl" ]]
[[block center]]
	<script>
		{place_script|raw}
	</script>
    [[ include TEMPLATE_PATH ~ "breadcrumbs/main.tpl"]]

	<div class="names">
		<span>Добавление места</span>
	</div>
	<div class="form_add_advert">
	[[if request.placeid]]
		<p class="h1">Спасибо, Ваша заявка отправлена на модерацию.</p>
	[[else]]
		[[if error]]
			<p class="error_block"> 
				Имеются незаполненные обязательные поля.
			</p>
		[[endif]]
		<form action="{advert_obj.getLinkPage(modul.id)}?form=add_place" method="post" enctype="multipart/form-data">
			<input type="hidden" name="action" value="add_place" />
			<input id="filds" type="hidden" name="filds" value="parent,caption,addres,date_work,phone,email,web,wifi,bron_cherez_engels,text" />
			<table>
				<tr id="parent">
					<td>Тип заведения <span class="zvezdochka">*</span></td>
					<td>
						<select onchange="showInputs(this.value);" class="[[if error.parent]]error[[endif]]" name="parent">
							<option value="0">Выберите тип заведения</option>
							[[for item in categories]]
								<option value="{item.id}" [[if request.parent == item.id]]selected[[endif]]>{item.caption}</option>
							[[endfor]]
						<select>
					</td>
				</tr>
				<tr id="caption">
					<td>Название <span class="zvezdochka">*</span></td>
					<td>
						<input class="fieldfocus [[if error.caption]]error[[endif]]" title="Введите название объявления" type="text" name="caption" value="{request.caption}" />
					</td>
				</tr>
				<tr id="addres">
					<td>Адрес <span class="zvezdochka">*</span></td>
					<td>
						<input class="fieldfocus [[if error.addres]]error[[endif]]" title="Введите адрес" type="text" name="addres" value="{request.addres}" />
					</td>
				</tr>
				<tr id="date_work">
					<td>Время работы <span class="zvezdochka">*</span></td>
					<td>
						<input class="fieldfocus [[if error.date_work]]error[[endif]]" title="Введите время работы" type="text" name="date_work" value="{request.date_work}" />
					</td>
				</tr>
				<tr id="phone">
					<td>Телефон <span class="zvezdochka">*</span></td>
					<td>
						<input class="fieldfocus [[if error.phone]]error[[endif]]" title="Введите телефон" type="text" name="phone" value="{request.phone}" />
					</td>
				</tr>
				<tr id="email">
					<td>Email <span class="zvezdochka">*</span></td>
					<td>
						<input class="fieldfocus [[if error.email]]error[[endif]]" title="Введите email" type="text" name="email" value="{request.email}" />
					</td>
				</tr>
				<tr id="web">
					<td>Web <span class="zvezdochka">*</span></td>
					<td>
						<input class="fieldfocus [[if error.web]]error[[endif]]" title="Введите web сайт" type="text" name="web" value="{request.web}" />
					</td>
				</tr>
				<tr id="wifi">
					<td>WI-FI <span class="zvezdochka">*</span></td>
					<td>
						<input type="radio" [[if request.wifi==1]]checked[[endif]] name="wifi" value="1"> Да &nbsp;&nbsp;<input type="radio" [[if request.wifi==0]]checked[[endif]] name="wifi" value="0"> Нет
					</td>
				</tr>
				<tr id="bron_cherez_engels">
					<td>Бронь через Энгельс <span class="zvezdochka">*</span></td>
					<td>
						<input type="radio" [[if request.bron_cherez_engels==1]]checked[[endif]] name="bron_cherez_engels" value="1"> Да &nbsp;&nbsp;<input type="radio" [[if request.bron_cherez_engels==0]]checked[[endif]] name="bron_cherez_engels" value="0"> Нет
					</td>
				</tr>
				/*дополнительные параметры*/
				<tr id="kitchen">
					<td>Кухня <span class="zvezdochka">*</span></td>
					<td>
						<select class="[[if error.kitchen]]error[[endif]]" name="kitchen">
							<option>Русская</option>
							<option>Европейская</option>
							<option>Азиатская</option>
							<option>Кавказская</option>
							<option>Итальянская</option>
							<option>Японская</option>
						</select>
					</td>
				</tr>
				<tr id="average_chek">
					<td>Средний счет <span class="zvezdochka">*</span></td>
					<td>
						<select class="[[if error.average_chek]]error[[endif]]" name="average_chek">
							<option>500 - 1000 руб.</option>
							<option>1000 - 1500 руб.</option>
							<option>1500 - 2500 руб.</option>
							<option>свыше 2500 руб.</option>
						</select>
					</td>
				</tr>
				<tr id="business_lunch">
					<td>Бизнес ланч <span class="zvezdochka">*</span></td>
					<td>
						<input type="radio" [[if request.business_lunch==1]]checked[[endif]] name="business_lunch" value="1"> Да &nbsp;&nbsp;<input type="radio" [[if request.business_lunch==0]]checked[[endif]] name="business_lunch" value="0"> Нет
					</td>
				</tr>
				<tr id="banket">
					<td>Банкет <span class="zvezdochka">*</span></td>
					<td>
						<input type="radio" [[if request.banket==1]]checked[[endif]] name="banket" value="1"> Да &nbsp;&nbsp;<input type="radio" [[if request.banket==0]]checked[[endif]] name="banket" value="0"> Нет
					</td>
				</tr>
				<tr id="more_services">
					<td>Доп. услуги <span class="zvezdochka">*</span></td>
					<td>
						<input class="fieldfocus [[if error.more_services]]error[[endif]]" title="Введите доп. услуги" type="text" name="more_services" value="{request.more_services}" />
					</td>
				</tr>
				<tr id="capacity">
					<td>Вместимость (кол-во чел.) <span class="zvezdochka">*</span></td>
					<td>
						<input class="fieldfocus [[if error.capacity]]error[[endif]]" title="Введите вместимость" type="text" name="capacity" value="{request.capacity}" />
					</td>
				</tr>
				<tr id="steam">
					<td>Парная <span class="zvezdochka">*</span></td>
					<td>
						<select class="[[if error.steam]]error[[endif]]" name="steam">
							<option>Финская</option>
							<option>Русская</option>
						</select>
					</td>
				</tr>
				<tr id="pool">
					<td>Бассейн <span class="zvezdochka">*</span></td>
					<td>
						<select class="[[if error.pool]]error[[endif]]" name="pool">
							<option>Горячий</option>
							<option>Холодный</option>
							<option>Купель</option>
						</select>
					</td>
				</tr>
				<tr id="restroom">
					<td>Комната отдыха <span class="zvezdochka">*</span></td>
					<td>
						<input class="fieldfocus [[if error.restroom]]error[[endif]]" title="Комната отдыха (да (кол-во)/нет)" type="text" name="restroom" value="{request.restroom}" />
					</td>
				</tr>
				<tr id="music">
					<td>Музыка <span class="zvezdochka">*</span></td>
					<td>
						<input class="fieldfocus [[if error.music]]error[[endif]]" title="Музыка" type="text" name="music" value="{request.music}" />
					</td>
				</tr>
				<tr id="residents">
					<td>Резиденты <span class="zvezdochka">*</span></td>
					<td>
						<input class="fieldfocus [[if error.residents]]error[[endif]]" title="Введите резидентов" type="text" name="residents" value="{request.residents}" />
					</td>
				</tr>
				<tr id="num_dance_flors">
					<td>Кол-во танцполов <span class="zvezdochka">*</span></td>
					<td>
						<input class="fieldfocus [[if error.num_dance_flors]]error[[endif]]" title="Введите кол-во танцполов" type="text" name="num_dance_flors" value="{request.num_dance_flors}" />
					</td>
				</tr>
				<tr id="num_track">
					<td>Кол-во дорожек <span class="zvezdochka">*</span></td>
					<td>
						<input class="fieldfocus [[if error.num_track]]error[[endif]]" title="Введите кол-во дорожек" type="text" name="num_track" value="{request.num_track}" />
					</td>
				</tr>
				<tr id="type_billiards">
					<td>Вид бильярда <span class="zvezdochka">*</span></td>
					<td>
						<select class="[[if error.type_billiards]]error[[endif]]" name="type_billiards">
							<option>Русский</option>
							<option>Американский</option>
						</select>
					</td>
				</tr>
				<tr id="num_tables">
					<td>Кол-во столов <span class="zvezdochka">*</span></td>
					<td>
						<input class="fieldfocus [[if error.num_tables]]error[[endif]]" title="Введите кол-во столов" type="text" name="num_tables" value="{request.num_tables}" />
					</td>
				</tr>
				<tr>
					<td>Фото</td>
					<td>
						<input type="file" name="image" />
					</td>
				</tr>
				<tr>
					<td>Описание <span class="zvezdochka">*</span></td>
					<td>
						<textarea class="fieldfocus [[if error.text]]error[[endif]]" title="Введите описание места" name="text" >{request.text}</textarea>
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