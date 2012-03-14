<ul class="menu">
    <li {if $sMenuItemSelect=='stickytopics'}class="active"{/if}>
        <a href="{$plugin_root_url}/">{$aLang.stickytopics_menu_stickytopics}</a>
	{if $sMenuItemSelect=='stickytopics'}
        <ul class="sub-menu" >
            <li {if $sMenuSubItemSelect=='list' || $sMenuSubItemSelect==''}class="active"{/if}><div><a href="{$plugin_root_url}/list/">{$aLang.stickytopics_menu_list}</a></div></li>
        </ul>
	{/if}
    </li>
</ul>
