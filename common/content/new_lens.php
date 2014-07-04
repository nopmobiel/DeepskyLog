<?php 
// new_lens.php
// allows the user to add a new lens

if((!isset($inIndex))||(!$inIndex)) include "../../redirect.php";
elseif(!$loggedUser) throw new Exception(LangException002);
else new_lens();

function new_lens()
{ global $baseURL,$loggedUserName,
         $objLens,$objPresentations,$objUtil;
	echo "<div id=\"main\">";
	echo "<h4>".LangOverviewLensTitle." ".$loggedUserName."</h4>";
	echo "<hr />"; 
	$objLens->showLensesObserver();
	echo "<h4>" . LangAddLensTitle . "</h4>";
	$lns=$objLens->getSortedLenses('name');
	echo "<form role=\"form\" action=\"".$baseURL."index.php\" method=\"post\"><div>";	
	echo "<input type=\"hidden\" name=\"indexAction\" value=\"validate_lens\" />";
	$content1b= "<select class=\"form-control\" onchange=\"location = this.options[this.selectedIndex].value;\" name=\"catalog\">";
	while(list($key, $value) = each($lns))
	  $content1b.= "<option value=\"".$baseURL."index.php?indexAction=add_lens&amp;lensid=".urlencode($value)."\" ".(($value==$objUtil->checkRequestKey('lensid'))?" selected=\"selected\" ":'').">".$objLens->getLensPropertyFromId($value,'name')."</option>";
	$content1b.= "</select>&nbsp;";
	
	echo "<hr />";
	echo "<input type=\"submit\" class=\"btn btn-primary pull-right\" name=\"add\" value=\"".LangAddLensButton."\" />&nbsp;";
	echo "<div class=\"form-group\">
	       <label for=\"catalog\">". LangAddLensExisting."</label>";
	echo $content1b;
	echo "</div>";

	echo LangAddSiteFieldOr." ".LangAddLensFieldManually;
	echo "<br /><br />";

	echo "<div class=\"form-group\">
	       <label for=\"lensname\">". LangAddLensField1."</label>";
	echo "<input type=\"text\" required class=\"form-control\" maxlength=\"64\" name=\"lensname\" size=\"30\" value=\"".stripslashes($objUtil->checkRequestKey('lensname','')).stripslashes($objLens->getLensPropertyFromId($objUtil->checkRequestKey('lensid'),'name'))."\" />";
	echo "<span class=\"help-block\">" . LangAddLensField1Expl . "</span>";
	echo "</div>";

	echo "<div class=\"form-group\">
	       <label for=\"factor\">". LangAddLensField2."</label>";
	echo "<input type=\"number\" min=\"0.01\" max=\"99.99\" required step=\"0.01\" class=\"form-control\" maxlength=\"5\" name=\"factor\" size=\"5\" value=\"".stripslashes($objUtil->checkRequestKey('factor','')).stripslashes($objLens->getLensPropertyFromId($objUtil->checkRequestKey('lensid'),'factor'))."\" />";
	echo "<span class=\"help-block\">" . LangAddLensField2Expl . "</span>";
	echo "</div>";
	
	echo "<hr />";
	echo "</div></form>";
	echo "</div>";
}
?>