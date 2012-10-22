[[ extends  TEMPLATE_PATH ~ "base/main.tpl" ]]
[[block center]]
	<script>
		{place_script|raw}
	</script>
    [[ include TEMPLATE_PATH ~ "breadcrumbs/main.tpl"]]

	<div class="names">
		<span>{modul.caption}</span>
	</div>
	<div class="add_advert_button">
		<a href="{advert_obj.getLinkPage(modul.id)}?form=add_advert">
			<button type="submit" class="button">
				<span class="button-left">
					<span class="button-right">
						<span class="button-text">
							<span><b>Добавить объявление</b></span>
						</span>
					</span>
				</span>
			</button>
		</a>
	</div>
	<div class="main-categories">
		[[for cat in categories]]
			<div class="block"><div class="categories1"><a href="{cat.full_url}">{cat.caption}</a>[[if cat.count]] ({cat.count})[[endif]]</div>
				[[if cat.child]]
						[[for _cat in cat.child]]
							<div><div class="categories2"><a href="{_cat.full_url}">{_cat.caption}</a>[[if _cat.count]] ({_cat.count})[[endif]]</div></div>
						[[endfor]]
				[[endif]]
			</div>
		[[endfor]]
	</div>
[[endblock]]

[[block right]]
	[[ include TEMPLATE_PATH ~ "news/right_block.tpl"]]
[[endblock]]