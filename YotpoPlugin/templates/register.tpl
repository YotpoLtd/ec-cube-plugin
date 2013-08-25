<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_header.tpl"}-->
<script type="text/javascript">
window.resizeTo(760,735);
</script>

<!--{$arrForm.yotpo_css}-->

<div class="y-wrapper">
	<div class="y-side-box">
		<div class="y-side-header">Yotpoはあなたの商品のすばらしいレビューを簡単に作成します。.</div>
		<hr />
		<div class="row-fluid y-features-list text-shadow">
			<ul>
				<li><i class="y-side-icon conversation-rate"></i>コンバージョン率を向上</li>
				<li><i class="y-side-icon multi-languages"></i>多言語</li>
				<li><i class="y-side-icon forever-free"></i>永久無料</li>
				<li><i class="y-side-icon social-engagement"></i>ソーシャル エンゲージメントの向上</li>
				<li><i class="y-side-icon plug-play"></i>プラグ  &amp; プレイのインストール</li>
				<li><i class="y-side-icon full-customization"></i>完全カスタマイズ</li>
				<li><i class="y-side-icon analytics"></i>高度な分析</li>
				<li><i class="y-side-icon seo"></i>SEO の機能</li>
			</ul>
		</div>
	</div>
	<div class="y-white-box">
		<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|h}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="yotpo_register">
			<div class="y-page-header"><i class="y-logo"></i>Yotpo アカウントの作成</div>
			<fieldset id="y-fieldset" class="y-narrow-form">
				<div class="y-header">より多くのレビュー作成し、エンゲージメントを高め、販売を促進する。.</div>

				<div class="y-label">メール アドレス</div>
				<span class="attention"><!--{$arrErr.email}--></span>
				<div class="y-input"><input type="text" name="email" value="<!--{$arrForm.email|h}-->" /></div>
				<div class="y-label">名前</div>
				<span class="attention"><!--{$arrErr.name}--></span>
				<div class="y-input"><input type="text" name="name" value="<!--{$arrForm.name|h}-->" /></div>
				<div class="y-label">パスワード</div>
				<span class="attention"><!--{$arrErr.password}--></span>
				<div class="y-input"><input type="password" name="password" /></div>
				<div class="y-label">パスワードの確認入力</div>
				<span class="attention"><!--{$arrErr.password_confirmation}--></span>
				<div class="y-input"><input type="password" name="password_confirmation" /></div>
				<a class="y-submit-btn" href="javascript:;" onclick="document.form1.submit();return false;"> <span>登録 </span> </a>
			</fieldset>
			<!--{if $arrForm.register_error}--><p class="y-error">* <!--{$arrForm.register_error_msg}--></p><!--{/if}-->

		</form>
		<form name="form2" id="form2" method="post" action="<!--{$smarty.server.REQUEST_URI|h}-->">
	<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
	<input type="hidden" name="mode" value="yotpo_login">
			<div class="y-footer">
				<span class="y-footer-text">既に Yotpo をお使いの方。</span>
				<a class="y-submit-btn y-goto-config-btn" href="javascript:;" onclick="document.form2.submit();return false;"> <span class="y-already-logged-in">設定に移動</span></a>
				<div class='yotpo-terms'>登録し、<a href='https://www.yotpo.com/terms-of-service' target='_blank'>利用規約に同意</a>.</div>
			</div>
		</form>
	</div>
</div>



<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_footer.tpl"}-->