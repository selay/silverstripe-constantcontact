<% if SiteConfig.SS_ConstantContactFrom %>
<% with SiteConfig.SS_ConstantContactFrom %>
<div class="constant-contact-signup-widget">

<div class="constant-contact-signup-title"> $Top.SiteConfig.CcTitle</div>
<div class="constant-contact-signup-text"> $Top.SiteConfig.CcInfoText</div>

<form $FormAttributes>

  $Fields.dataFieldByName(first_name)
  <div class="spacer-small"></div>
  $Fields.dataFieldByName(last_name)
  <div class="spacer-small"></div>
  $Fields.dataFieldByName(email)
  <div class="spacer-small"></div>
  $Fields.dataFieldByName(postcode)
  <div class="spacer-small"></div>
  $Fields.dataFieldByName(list)
  $Fields.dataFieldByName(SecurityID)

<div class="left constant-contact-error-message hide-it"></div>
<div class="left constant-contact-success-message hide-it"></div>
$Fields.FieldByName(SignUpButton)
 
</form>
<div class="spacer-small"></div>
</div>
<% end_with %>
<% end_if %>