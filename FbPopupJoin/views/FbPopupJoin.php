<?php if (!defined('APPLICATION')) exit(); ?>
<h1><?php echo T($this->Data['Title']); ?></h1>
<div class="Info">
   <?php echo T($this->Data['PluginDescription']); ?>
</div>
<h3><?php echo T('Settings'); ?></h3>
<?php
   echo $this->Form->Open();
   echo $this->Form->Errors();
?>
<ul>
   <li><?php
      
      echo $this->Form->Label('Your FB Page ID from Facebook', 'Plugin.FbPopupJoin.FbPage');
	  echo $this->Form->Textbox('Plugin.FbPopupJoin.FbPage');
	  echo $this->Form->Label('Days The cookie will expire', 'Plugin.FbPopupJoin.CookieExpiration');
	  
      echo $this->Form->Textbox('Plugin.FbPopupJoin.CookieExpiration');
   ?></li>
</ul>
<?php
   echo $this->Form->Close('Save');
?>