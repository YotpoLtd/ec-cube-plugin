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
			<!--{if $arrForm.app_key && $arrForm.secret}-->ウィジェットのルック アンド フィールをカスタマイズや、購入設定後、メールを編集し 
				<!--{if $arrForm.app_key && $arrForm.secret}-->
					<a class="y-href" href="https://api.yotpo.com/users/b2blogin?app_key=<!--{$arrForm.app_key}-->&secret=<!--{$arrForm.secret}-->" target="_blank">、Yotpoのダッシュ ボードに移動するだけ。</a></div> 
				<!--{else}-->
					<a class="y-href" href="https://www.yotpo.com/?login=true" target="_blank">Yotpoのダッシュ ボードに移動するだけ。</a></div> 
				<!--{/if}-->
			<!--{/if}-->
				<!--{if $arrForm.already_logged_in}--><div class="y-label">APIとシークレットトークンを取得するには、 
		<a class="y-href" href="https://www.yotpo.com/?login=true" target="_blank">ここからログインし</a>, アカウント設定に移動します。.</div><!--{/if}-->

            <div class="y-label">別の言語を選択する場合は、2 文字の言語コードをここに入力してください <a class="y-href" href="http://support.yotpo.com/entries/21861473-Languages-Customization-" target="_blank">ここ</a>からサポート言語コードを見つけることができます。</div>
    	    <div class="y-input"><input type="text" class="yotpo_language_code_text" name="language_code" maxlength="2" value="<!--{$arrForm.language_code|h}-->" /></div>			
    	    <div class="y-label">Bottom LIneを有効にします。
            	<input type="checkbox" name="product_page_bottomline_enabled" value="1" <!--{if $arrForm.product_page_bottomline_enabled == "1"}-->checked<!--{/if}--> />
        	</div> 	
        	<div class="y-label">レビューシステムのデフォルトを無効にします。
        		<input type="checkbox" name="disable_default_reviews_system" value="1" <!--{if $arrForm.disable_default_reviews_system == "1"}-->checked<!--{/if}-->/>
        	</div>
			<div class="y-label">アプリケーション キー</div>
			<div class="y-input"><input type="text" name="app_key" value="<!--{$arrForm.app_key|h}-->" /></div>
			<div class="y-label">シークレットトークン</div>
			<div class="y-input"><input type="text" name="secret" value="<!--{$arrForm.secret|h}-->"/></div>
               	
		</fieldset>
		<a href="javascript:;" onclick="document.form1.submit();return false;"> <span class="y-submit-btn">更新 </span></a>
		<!--{if $arrForm.settings_update_success}--><p class="y-success">設定が正常に更新されました</p><!--{/if}-->
		<!--{if $arrForm.export_success}--><p class="y-success">過去の注文は Yotpo によって正常に処理されました。</p><!--{/if}-->

<!--{if $arrForm.export_error}--><p class="y-error">* <!--{$arrForm.export_error_msg}--></p><!--{/if}-->
	</div>
	</form>
	<div class="y-footer">
		<form name="form2" id="form2" method="post" action="<!--{$smarty.server.REQUEST_URI|h}-->">
	<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
	<input type="hidden" name="mode" value="export_orders">
			<ul><li><a href="javascript:;" onclick="document.form2.submit();return false;"> <span class="y-normal-btn">過去の注文のレビュー作成。 </span> </a></li></ul>
			<p>*レビューを依頼した過去の顧客に電子メールを送信。</p>
	</form>
	</div>
</div>

<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_footer.tpl"}-->