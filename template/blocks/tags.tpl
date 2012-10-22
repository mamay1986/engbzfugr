[[if tags]]
<div class="tags">
	<span>Метки: </span>
	[[for tag in tags]]
		[[if not loop.first]]
			,
		[[endif]]
		<a href="{site_obj.getLinkPage(220)}{tag.id_tag}/">{tag.name}</a>
	[[endfor]]
</div>
[[endif]]