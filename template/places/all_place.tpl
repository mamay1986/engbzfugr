[[ extends  TEMPLATE_PATH ~ "base/main.tpl" ]]
[[block center]]
	<script>
		{place_script|raw}
	</script>
    [[ include TEMPLATE_PATH ~ "breadcrumbs/main.tpl"]]

    <h1>{modul.caption}</h1>
	<div class="">
		<div class="afisha-topic">
			По категориям: 
			[[for cat in categories]]
				<a [[if loop.index0>0]]class="separator"[[endif]] onclick="showPlaces({cat.id},array_place);return false;" href="{cat.full_url}">{cat.caption}</a>
			[[endfor]]
		</div>
		<div class="filters">
			<form action="" method="post" id="search">
				<input type="text" id="query_google_map" name="query_google_map" value="{search_string}" title="Введите название" class="fieldfocus"/>
				/*<input onclick="searchPlaces(array_place);return false;" type="submit" value="&nbsp;&nbsp;Найти&nbsp;&nbsp;" class="submit">*/
				<button onclick="searchPlaces(array_place);return false;" class="button" type="submit">
					<span class="button-left">
						<span class="button-right">
							<span class="button-text">
								<span>Найти</span>
							</span>
						</span>
					</span>
				</button>
			</form>
		</div>
		<div class="map-places">
			<div id="map-content">
				<div id="map_canvas" style="position:absolute; z-index:1;"></div>
				<script type="">showPlacesAll(array_place)</script>
				[[raw]]
				<script type="text/javascript">
					(function() {
					  	makeScrollable('map_canvas', function(delta) {
						  ;
					  	});
					})();
				</script>
				[[endraw]]
			</div>
			<div class="preloader" id="preloader_google_maps"><center><img src="/images/preloader.gif"></center></div>
		</div>
	</div>
[[endblock]]

[[block right]]
	[[ include TEMPLATE_PATH ~ "news/right_block.tpl"]]
[[endblock]]