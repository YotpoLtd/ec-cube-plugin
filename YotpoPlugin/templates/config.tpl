<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_header.tpl"}-->
<script type="text/javascript">
window.resizeTo(760,735);
</script>
<!--{$arrForm.yotpo_css}-->

<div class="y-settings-white-box">
	<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|h}-->">
		<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
		<input type="hidden" name="mode" value="yotpo_settings">

		<div class="y-page-header">
			<i class="y-logo"></i><span>Settings</span></div>
	
		<div class="y-form-fields">
		<fieldset id="y-fieldset">
			<!--{if $arrForm.app_key && $arrForm.secret}--><div class="y-label">To customize the look and feel of the widget, and to edit your Mail After Purchase settings, just head to the 
				<!--{if $arrForm.app_key && $arrForm.secret}-->
					<a class="y-href" href="https://api.yotpo.com/users/b2blogin?app_key=<!--{$arrForm.app_key}-->&secret=<!--{$arrForm.secret}-->" target="_blank">Yotpo Dashboard.</a></div> 
				<!--{else}-->
					<a class="y-href" href="https://www.yotpo.com/?login=true" target="_blank">Yotpo Dashboard.</a></div> 
				<!--{/if}-->
			<!--{/if}-->
				<!--{if $arrForm.already_logged_in}--><div class="y-label">To get your api key and secret token 
		<a class="y-href" href="https://www.yotpo.com/?login=true" target="_blank">log in here</a>, and go to your account settings.</div><!--{/if}-->

            <div class="y-label">If you would like to choose a different language, please type the 2-letter language code here. You can find the supported langauge codes <a class="y-href" href="http://support.yotpo.com/entries/21861473-Languages-Customization-" target="_blank">here.</a></div>
    	    <div class="y-input"><input type="text" class="yotpo_language_code_text" name="language_code" maxlength="2" value="<!--{$arrForm.language_code|h}-->" /></div>			
    	    <div class="y-label">Enable Bottom Line
            	<input type="checkbox" name="product_page_bottomline_enabled" value="1" <!--{if $arrForm.product_page_bottomline_enabled == "1"}-->checked<!--{/if}--> />
        	</div> 	
        	<div class="y-label">Disable default reviews system
        		<input type="checkbox" name="disable_default_reviews_system" value="1" <!--{if $arrForm.disable_default_reviews_system == "1"}-->checked<!--{/if}-->/>
        	</div>
			<div class="y-label">App key</div>
			<div class="y-input"><input type="text" name="app_key" value="<!--{$arrForm.app_key|h}-->" /></div>
			<div class="y-label">Secret token</div>
			<div class="y-input"><input type="text" name="secret" value="<!--{$arrForm.secret|h}-->"/></div>
               	
		</fieldset>
		<a href="javascript:;" onclick="document.form1.submit();return false;"> <span class="y-submit-btn">Update </span></a>
		<!--{if $arrForm.settings_update_success}--><p class="y-success">Configuration has been updated successfully</p><!--{/if}-->
		<!--{if $arrForm.export_success}--><p class="y-success">Past orders were processed by Yotpo successfully.</p><!--{/if}-->

<!--{if $arrForm.export_error}--><p class="y-error">* <!--{$arrForm.export_error_msg}--></p><!--{/if}-->
	</div>
	</form>
	<div class="y-footer">
		<form name="form2" id="form2" method="post" action="<!--{$smarty.server.REQUEST_URI|h}-->">
	<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
	<input type="hidden" name="mode" value="export_orders">
			<ul><li><a href="javascript:;" onclick="document.form2.submit();return false;"> <span class="y-normal-btn">Generate Reviews For Past Orders </span> </a></li></ul>
			<p>*Send an email to your past customers, requesting them to write a review.</p>
	</form>
	</div>
</div>

<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_footer.tpl"}-->