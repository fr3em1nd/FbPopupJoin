<?php if (!defined('APPLICATION')) exit();

$PluginInfo['FbPopupJoin'] = array(
   'Description' => 'Makes a popup for every visitor for the first time in the site to join your facebook community!',
   'Version' => '1.0.1',
   //'RequiredApplications' => array('Vanilla' => '2.0.10'),
   'RequiredTheme' => FALSE, 
   'RequiredPlugins' => FALSE,
   'HasLocale' => FALSE,
   'SettingsUrl' => '/plugin/FbPopupJoin',
   'SettingsPermission' => 'Garden.AdminUser.Only',
   'Author' => "fr3em1nd",
   'AuthorEmail' => 'info@webyoungmasters.com',
   'AuthorUrl' => 'http://www.webyoungmasters.com'
);

class FbPopupJoinPlugin extends Gdn_Plugin {


   public function __construct() {
      
   }
   

public function Base_AfterBody_Handler($Sender) {
//GET THE CONFIGURATION VALUES

if(InSection("Dashboard")) // dont render if in dashboard
return; 
 
$CookieExpiration = C('Plugin.FbPopupJoin.CookieExpiration', 30);  //30 days default settings if not set on config
$FbPage = C('Plugin.FbPopupJoin.FbPage','296307127079436');//use my page if does not exist on config

if($CookieExpiration==0 || $CookieExpiration<=1 || !isset($CookieExpiration)){ // If not set reverify to set to 30 disallow user to popup the fbpages everyday, ( avoid being greedy)
	$CookieExpiration=30;
}

//ECHO THE POPUP
echo '<script type="text/javascript">
jQuery(document).ready(function(){
if (document.cookie.indexOf(\'visited=true\') == -1) {
var setDays = 1000*60*60*24*'.$CookieExpiration.';
var expires = new Date((new Date()).valueOf() + setDays);
document.cookie = "visited=true;expires=" + expires.toUTCString();
$.colorbox({width:"430px", height:"470px", inline:true, href:"#tP"});
}
});
</script>
<div style="display:none">
<div id="tP" style="background:#fff;position:scroll;z-index:99999999">
<div style="text-align:center;padding-top:15px">
<h2 class="tP">Join our FB community!</h2>
<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2F'.$FbPage.'&amp;width=342&amp;height=300&amp;show_faces=true&amp;colorscheme=light&amp;stream=false&amp;show_border=false&amp;header=false&amp;appId=" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:342px; height:300px;" allowtransparency="true"></iframe>
</div></div></div>';
}
public function Base_Render_Before($Sender) {
      $Sender->AddCssFile($this->GetResource('design/colorbox.css', FALSE, FALSE));
      $Sender->AddJsFile($this->GetResource('js/jquery.colorbox-min.js', FALSE, FALSE));
   }
   public function PluginController_FbPopupJoin_Create($Sender) {

      $Sender->Title('FbPopup Plugin');
      $Sender->AddSideMenu('plugin/FbPopupJoin');

      $Sender->Form = new Gdn_Form();
      
    
      $this->Dispatch($Sender, $Sender->RequestArgs);
   }
   public function Controller_Index($Sender) {
   
      $Sender->Permission('Vanilla.Settings.Manage');
      
      $Sender->SetData('PluginDescription',$this->GetPluginKey('Description'));
		
		$Validation = new Gdn_Validation();
      $ConfigurationModel = new Gdn_ConfigurationModel($Validation);
      $ConfigurationModel->SetField(array(
         'Plugin.FbPopupJoin.CookieExpiration'     => 30,
         'Plugin.FbPopupJoin.FbPage'     => '<your FB page ID # here >'
      ));

      $Sender->Form->SetModel($ConfigurationModel);

      if ($Sender->Form->AuthenticatedPostBack() === FALSE) {
         $Sender->Form->SetData($ConfigurationModel->Data);
		} else {
         $ConfigurationModel->Validation->ApplyRule('Plugin.FbPopupJoin.FbPage', 'Required');
		 $ConfigurationModel->Validation->ApplyRule('Plugin.FbPopupJoin.FbPage', 'Integer');
         $ConfigurationModel->Validation->ApplyRule('Plugin.FbPopupJoin.CookieExpiration', 'Required');
         $ConfigurationModel->Validation->ApplyRule('Plugin.FbPopupJoin.CookieExpiration', 'Integer');
         
         $Saved = $Sender->Form->Save();
         if ($Saved) {
            $Sender->StatusMessage = T("Your changes have been saved.");
         }
      }
      $Sender->Render($this->GetView('FbPopupJoin.php'));
   }

   public function Base_GetAppSettingsMenuItems_Handler($Sender) {
      $Menu = &$Sender->EventArguments['SideMenu'];
      $Menu->AddLink('Add-ons', 'FB Popup', 'plugin/FbPopupJoin', 'Garden.AdminUser.Only');
   }
   public function Setup() {
      SaveToConfig('Plugin.FbPopupJoin.CookieExpiration', 30); //default cookie for expiration is 30 days
      SaveToConfig('Plugin.FbPopupJoin.FbPage', "296307127079436"); // use my page if not configured
    
   }
   public function OnDisable() {
      RemoveFromConfig('Plugin.FbPopupJoin.CookieExpiration');
      RemoveFromConfig('Plugin.FbPopupJoin.FbPage');
   }
   
}
