{$commentCustomScript}
{$client_includes}

{$commentFormOpen}
  {foreach from=$commentFormFields key=key item=field}
    {$field}
  {/foreach}
{$commentFormClose}
{$commentFormValidations}
