{strip}
{form}
	{jstabs}
		{jstab title="Topica Account Settings"}
			{legend legend="Topica Account Settings"}
				<input type="hidden" name="page" value="{$page}" />
					<div class="row">
					{formlabel label="Your Topica User Id"}
					{forminput}
					  <input type="text" name="topica_user_id" size="80" value="{$gBitSystem->getConfig('topica_user_id')}" />
					  {formhelp note="Your topica user id, usually your email address"}
					{/forminput}
					
					{formlabel label="Your Email Address Registered with Topica"}
					{forminput}
					  <input type="text" name="topica_user_email" size="80" value="{$gBitSystem->getConfig('topica_user_email')}" />
					  {formhelp note="Your email address on record with Topica"}
					{/forminput}
					
					{formlabel label="Your Topica Password"}
					{forminput}
					  <input type="password" name="topica_password1" size="80" value="{$gBitSystem->getConfig('topica_acct_pass')}" />
					{/forminput}
					
					{formlabel label="Confirm Your Topica Password"}
					{forminput}
					  <input type="password" name="topica_password2" size="80" value="" />
					{/forminput}
					
					{formlabel label="Your Topica List"}
					{forminput}
					  <input type="text" name="topica list" size="80" value="{$gBitSystem->getConfig('topica_list')}" />
					  {formhelp note="Your list at Topica that you would like to push registration info into."}
					{/forminput}
					</div>
		      <div class="row submit">
			       <input type="submit" name="topicaAdminSubmit" value="{tr}Change Preferences{/tr}" />
		      </div>
			{/legend}
		{/jstab}
	{/jstabs}
{/form}
{/strip}
