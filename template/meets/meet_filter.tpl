<div class="filters">
	<form action="{site_obj.getLinkPage(4)}" method="get" id="search">
	    <input type="hidden" name="filter[action]" value="search" />
	    <input type="hidden" name="filter[check]" value="true" />
		<input type="text" name="filter[search_string]" value="{search_string}" title="Введите название" class="fieldfocus"/>
		<select name="filter[event_category]" onchange="">
			<option value="">Выберите категорию</option>
			[[for item in categories]]
			<option value="{item.id}" [[if item.id == event_category]]selected="selected"[[endif]]>{item.caption}</option>
			[[endfor]]
		</select>
		
		<div id="select_date" style="display:inline;">
			[[if date]]
            	<input type="text" name="filter[event_date]" value="{date}" id="datepicker" style="width:200px;"/>
            [[else]]
				<select name="filter[event_date]" onchange="">
					<option value="">Выберите дату</option>
					<option value="today" [[if event_date == "today"]]selected="selected"[[endif]]>Сегодня</option>
					<option value="yersterday" [[if event_date == "yersterday"]]selected="selected"[[endif]]>Вчера</option>
					<option value="week" [[if event_date == "week"]]selected="selected"[[endif]]>Неделя</option>
					<option value="month" [[if event_date == "month"]]selected="selected"[[endif]]>Месяц</option>
				</select>
			[[endif]]
		</div>
		/*<input type="submit" value="&nbsp;&nbsp;Найти&nbsp;&nbsp;" class="submit">*/
		<button class="button" type="submit">
			<span class="button-left">
				<span class="button-right">
					<span class="button-text">
						<span>Найти</span>
					</span>
				</span>
			</span>
		</button>
		<br/>
	    <a href="#" class="selectbydate" onclick="getDate(this);return false;">Выбрать по числу</a>
	</form>
</div>