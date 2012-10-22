<div class="filters">
	Фильтр
	<form method="get">
		<input type="hidden" name="modul" value="{request.modul}" />
		<ul class="filter-list" >
			<li>
				<select onchange="this.form.submit()" name="id_interview" style="width: 187px;">
					<option value="0">---БЕЗ ВОПРОСА---</option>
					[[for item in all_cat]]
						<option [[if request.id_interview==item.id]]selected[[endif]] value="{item.id}">{item.caption}</option>
					[[endfor]]
				</select>
			</li>
		</ul>
	</form>
</div>