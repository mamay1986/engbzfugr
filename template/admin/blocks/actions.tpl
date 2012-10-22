[[ import TEMPLATE_PATH ~ "admin/macro/actions.tpl" as forms ]]


[[if 'inmenu_new'  in  actions ]]
	[[if (item['inmenu_new']==1)]]
		[[set img = 'published.gif' ]]
	[[else]]	
		[[set img = 'notpublished.gif' ]]
	[[endif]]	
	
	[[ set link ]]
	 	/admin/?modul={request.modul}&id={item['id']}&action=inmenu_new
	[[ endset ]]
	
	{forms.action(link, img,'новинка в меню',16,12)}
[[endif]]

[[if 'inmenu'  in  actions ]]
	[[if (item['inmenu']==1)]]
		[[set img = 'published.gif' ]]
	[[else]]	
		[[set img = 'notpublished.gif' ]]
	[[endif]]	
	
	[[ set link ]]
	 	/admin/?modul={request.modul}&id={item['id']}&action=inmenu
	[[ endset ]]
	
	{forms.action(link, img,'показать в меню',16,12)}
[[endif]]

[[if 'index'  in  actions ]]
		[[if (item['index']==1)]]
			[[set img = 'new.gif' ]]
		[[else]]	
			[[set img = 'notnew.gif' ]]
		[[endif]]	
		
		[[ set link ]]
		 	/admin/?modul={request.modul}&id={item['id']}&action=index
		[[ endset ]]
	
		
		{forms.action(link, img,'Главная',16,16)}
[[endif]]

[[if 'newsindex'  in  actions ]]
		[[if (item['newsindex']==1)]]
			[[set img = 'new.gif' ]]
		[[else]]	
			[[set img = 'notnew.gif' ]]
		[[endif]]	
		
		[[ set link ]]
		 	/admin/?modul={request.modul}&id={item['id']}&action=newsindex
		[[ endset ]]
	
		
		{forms.action(link, img,'Публиковать на главной',16,16)}
[[endif]]

[[if 'move'  in  actions ]]

	[[set img = 'icon_up.gif' ]]
	[[ set link ]]
	 	/admin/?modul={request.modul}&id={item['id']}&action=up
	[[ endset ]]
	{forms.action(link, img,'Вверх',12,16)}

	[[set img = 'icon_down.gif' ]]
	[[ set link ]]
	 	/admin/?modul={request.modul}&id={item['id']}&action=down
	[[ endset ]]
	{forms.action(link, img,'Вниз',12,16)}

[[endif]]

[[if 'active'  in  actions ]]
	[[if (item['active']==1)]]
		[[set img = 'on.gif' ]]
	[[else]]	
		[[set img = 'off.gif' ]]
	[[endif]]	
	
	[[ set link ]]
	 	/admin/?modul={request.modul}&id={item['id']}&action=active[[if request.id_content]]&id_content={request.id_content}[[endif]][[if request.mod]]&mod={request.mod}[[endif]]
	[[ endset ]]
	
	{forms.action(link, img,'Включить/Выключить',17,12)}
[[endif]]

[[if 'comments'  in  actions ]]
	
	[[set img = 'comment.gif' ]]
	
	[[ set link ]]
	 	/admin/?modul=adm_comments&id_content={item['id']}&mod={absitem.mod}
	[[ endset ]]
	
	{forms.action(link, img,'Коментарии',16,16)}
[[endif]]

[[if 'edit'  in  actions ]]
	
	[[set img = 'icon_edit.gif' ]]
	
	[[ set link ]]
	 	/admin/?modul={request.modul}&id={item['id']}&action=edit[[if request.id_content]]&id_content={request.id_content}[[endif]][[if request.mod]]&mod={request.mod}[[endif]]
	[[ endset ]]
	
	{forms.action(link, img,'Редактировать',15,15)}
[[endif]]

[[if 'edit_hide_polya'  in  actions ]]
	
	[[set img = 'icon_edit.gif' ]]
	
	[[ set link ]]
	 	/admin/?modul={request.modul}&id={item['id']}&dop_polya=hide&action=edit
	[[ endset ]]
	
	{forms.action(link, img,'Редактировать',15,15)}
[[endif]]

[[if 'delete'  in  actions and (item['delete_security']=='0' or ignor_delete_security)]]

	[[set img = 'del.gif' ]]

	[[ set link ]]
	 	/admin/?modul={request.modul}&id={item['id']}&action=delete[[if request.id_content]]&id_content={request.id_content}[[endif]][[if request.mod]]&mod={request.mod}[[endif]]
	[[ endset ]]
	
	{forms.action(link, img,'удалить',16,16,'Вы уверенны?')}
[[endif]]

[[if 'post_vk'  in  actions and configs.token_vk and configs.user_id_vk ]]
	
	[[set img = 'logovk.jpg' ]]

	[[ set link ]]
	 	/admin/?modul={request.modul}&id={item['id']}&action=post_vk
	[[ endset ]]
	
	{forms.action(link, img)}
[[endif]]





