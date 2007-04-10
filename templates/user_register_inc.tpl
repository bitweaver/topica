{strip}
{if $gBitSystem->isPackageActive('topica')}	
	<br/>
	<h3>{tr}Optional Information{/tr}</h3>
	<p>{tr}Your information will be kept confidential. Please see our Privacy Policy.{/tr}</p>
	<div class="row">
		{formlabel label="Would You Like To Be On Our Mailing List?" for="topica"}
		{forminput}
			<input name="topica" type="checkbox" value="y" checked="checked">Yes - please put me on your emailing list.</input><br/>
		{/forminput}
	</div>		
	<div class="row">
		{formlabel label="First Name" for="topica"}
		{forminput}
			<input type="text" name="first_name" id=""first_name" value="{$reg.first_name}" />
		{/forminput}
	</div>
	<div class="row">
		{formlabel label="Last Name" for="topica"}
		{forminput}
			<input type="text" name="last_name" id=""last_name" value="{$reg.last_name}" />
		{/forminput}
	</div>
	<div class="row">
		{formlabel label="Home Phone" for="topica"}
		{forminput}
			<input type="text" name="phone_home" id=""phone_home" value="{$reg.phone_home}" />
		{/forminput}
	</div>
	<div class="row">
		{formlabel label="Cell Phone" for="topica"}
		{forminput}
			<input type="text" name="phone_cell" id=""phone_cell" value="{$reg.phone_cell}" />
		{/forminput}
	</div>
	<div class="row">
		{formlabel label="Address" for="topica"}
		{forminput}
			<input type="text" size="30" name="address" id="address" value="{$reg.address}" />
		{/forminput}
	</div>
	<div class="row">
		{formlabel label="City" for="topica"}
		{forminput}
			<input type="text" size="30" name="city" id="city" value="{$reg.city}" />
		{/forminput}
	</div>
	<div class="row">
		{formlabel label="State" for="topica"}
		{forminput}
			<select name="state" id="state" >
			{foreach from=$statesNProvs item=state}
				<option value="{$state}" {if $reg.state == $state}selected="selected"{/if}>{$state}</option>
			{/foreach}
			</select>			
		{/forminput}
	</div>
	<div class="row">
		{formlabel label="Zip" for="topica"}
		{forminput}
			<input type="text" name="zipcode" id="zipcode" value="{$reg.zipcode}" />
		{/forminput}
	</div>
{/if}
{/strip}