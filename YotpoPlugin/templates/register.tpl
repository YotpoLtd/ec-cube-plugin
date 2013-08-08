<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_header.tpl"}-->
<script type="text/javascript">
window.resizeTo(760,735);
</script>

<!--{$arrForm.yotpo_css}-->

<div class="y-wrapper">
	<div class="y-side-box">
		<div class="y-side-header">Yotpo makes it easy to generate beautiful reviews for your products. These in turn lead to higher sales and happier customers.</div>
		<hr />
		<div class="row-fluid y-features-list text-shadow">
			<ul>
				<li><i class="y-side-icon conversation-rate"></i>Increase conversion rate</li>
				<li><i class="y-side-icon multi-languages"></i>Multi languages</li>
				<li><i class="y-side-icon forever-free"></i>Forever free</li>
				<li><i class="y-side-icon social-engagement"></i>Increase social engagement</li>
				<li><i class="y-side-icon plug-play"></i>Plug &amp; play installation</li>
				<li><i class="y-side-icon full-customization"></i>Full customization</li>
				<li><i class="y-side-icon analytics"></i>Advanced analytics</li>
				<li><i class="y-side-icon seo"></i>SEO capabilities'</li>
			</ul>
		</div>
	</div>
	<div class="y-white-box">
		<form name="form1" id="form1" method="post" action="<!--{$smarty.server.REQUEST_URI|h}-->">
<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
<input type="hidden" name="mode" value="yotpo_register">
			<div class="y-page-header"><i class="y-logo"></i>Create your Yotpo account</div>
			<fieldset id="y-fieldset" class="y-narrow-form">
				<div class="y-header">Generate more reviews, more engagement, and more sales.</div>

				<div class="y-label">Email address:</div>
				<span class="attention"><!--{$arrErr.email}--></span>
				<div class="y-input"><input type="text" name="email" value="<!--{$arrForm.email|h}-->" /></div>
				<div class="y-label">Name:</div>
				<span class="attention"><!--{$arrErr.name}--></span>
				<div class="y-input"><input type="text" name="name" value="<!--{$arrForm.name|h}-->" /></div>
				<div class="y-label">Password:</div>
				<span class="attention"><!--{$arrErr.password}--></span>
				<div class="y-input"><input type="password" name="password" /></div>
				<div class="y-label">Confirm password</div>
				<span class="attention"><!--{$arrErr.password_confirmation}--></span>
				<div class="y-input"><input type="password" name="password_confirmation" /></div>
				<a class="y-submit-btn" href="javascript:;" onclick="document.form1.submit();return false;"> <span>Register </span> </a>
			</fieldset>
			<!--{if $arrForm.register_error}--><p class="y-error">* <!--{$arrForm.register_error_msg}--></p><!--{/if}-->

		</form>
		<form name="form2" id="form2" method="post" action="<!--{$smarty.server.REQUEST_URI|h}-->">
	<input type="hidden" name="<!--{$smarty.const.TRANSACTION_ID_NAME}-->" value="<!--{$transactionid}-->" />
	<input type="hidden" name="mode" value="yotpo_login">
			<div class="y-footer">
				<span class="y-footer-text">Already using Yotpo?</span>
				<a class="y-submit-btn y-goto-config-btn" href="javascript:;" onclick="document.form2.submit();return false;"> <span class="y-already-logged-in">Go to Config</span></a>
				<div class='yotpo-terms'>By registering I accept the <a href='https://www.yotpo.com/terms-of-service' target='_blank'>Terms of Use</a>.</div>
			</div>
		</form>
	</div>
</div>



<!--{include file="`$smarty.const.TEMPLATE_ADMIN_REALDIR`admin_popup_footer.tpl"}-->