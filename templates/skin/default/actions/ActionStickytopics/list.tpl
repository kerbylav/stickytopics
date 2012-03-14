{include file='header.tpl'}
<script language="JavaScript" type="text/javascript">
	aRouter['stickytopics_ajax']='{cfg name='path.root.web'}/admin/stickytopics/ajax';
</script>

<script
	type="text/javascript" src="{$sTemplateWebPathPlugin}js/loader.js"></script>

{literal}
<script language="JavaScript" type="text/javascript">
						var lsStickyStream;
						window.addEvent('domready', function() { 
							lsStickyStream=new lsStickyLoaderClass();
							lsStickyStream.changeBlog();
						});

</script>
{/literal}
{if $bCanSet}
<h3>{$aLang.stickytopics_list_title}</h3>
<br/>
<div>
<label for="blog_id">{$aLang.stickytopics_admin_select_blog}:</label><select name="blog_id" id="blog_id"
	onChange="lsStickyStream.changeBlog();">
	{if $bIsAdmin}<option value="0">{$aLang.stickytopics_main_page}</option>{/if}
	{if $bHasPersonal}<option value="-1">{$aLang.stickytopics_personal_blog}</option>{/if}
	{foreach from=$aBlogsAllow item=oBlog}
	<option value="{$oBlog->getId()}" {if $_aRequest.blog_id==$oBlog->getId()}selected{/if}>{$oBlog->getTitle()}</option>
	{/foreach}
</select>
<div class="stickytopics_content"></div>
<br/>
<label for="topic_filter">{$aLang.stickytopics_admin_topic_filter}:</label>
<input type="text" id="topic_filter" name="topic_filter" style="width: 100%" />
<input type="button"
	value="{$aLang.stickytopics_admin_topic_filter_button}"
	onclick="lsStickyStream.toggle('topics'); return false;" />

<div class="topics_content"></div>
</div>
{else}
<h3>{$aLang.stickytopics_no_blogs}</h3>
{/if}
{include file='footer.tpl'}
