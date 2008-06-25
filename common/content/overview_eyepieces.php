<?php

// overview_eyepieces.php
// generates an overview of all eyepieces (admin only)
// version 3.2: WDM 22/01/2008

include_once "../lib/eyepieces.php";
include_once "../lib/util.php";
include_once "../lib/observations.php";
include_once "../lib/observers.php";
include_once "../lib/cometobservations.php";

$eyepieces = new Eyepieces;
$util = new util;
$util->checkUserInput();
$observations = new observations;
$cometobservations = new CometObservations;

$observers = new observers;

// sort

if(isset($_GET['sort']))
{
  $sort = $_GET['sort']; // field to sort on
}
else
{
  $sort = "name"; // standard sort on location name
}

$eyeps = $eyepieces->getSortedEyepieces($sort);

// minimum

if(isset($_GET['min']))
{
  $min = $_GET['min'];
}
else
{
  $min = 0;
}

// the code below looks very strange but it works

if((isset($_GET['previous'])))
{
  $orig_previous = $_GET['previous'];
}
else
{
  $orig_previous = "";
}

if((isset($_GET['sort'])) && $_GET['previous'] == $_GET['sort']) // reverse sort when pushed twice
{
  if ($_GET['sort'] == "name")
  {
    $eyeps = array_reverse($eyeps, true);
  }
  else
  {
    krsort($eyeps);
    reset($eyeps);
  }
    $previous = ""; // reset previous field to sort on
}
else
{
  $previous = $sort;
}

$step = 25;

echo("<div id=\"main\">\n<h2>".LangViewEyepieceTitle."</h2>");

$link = "common/view_eyepieces.php?sort=" . $sort . "&amp;previous=" . $orig_previous;

list($min, $max) = $util->printListHeader($eyeps, $link, $min, $step, "");

echo "<table>
      <tr class=\"type3\">
      <td><a href=\"common/view_eyepieces.php?sort=name&amp;previous=$previous\">".LangViewEyepieceName."</a></td>
      <td><a href=\"common/view_eyepieces.php?sort=focalLength&amp;previous=$previous\">".LangViewEyepieceFocalLength."</a></td>
      <td><a href=\"common/view_eyepieces.php?sort=maxFocalLength&amp;previous=$previous\">".LangViewEyepieceMaxFocalLength."</a></td>
      <td><a href=\"common/view_eyepieces.php?sort=apparentFOV&amp;previous=$previous\">".LangViewEyepieceApparentFieldOfView."</a></td>";

			echo "<td><a href=\"common/view_eyepieces.php?sort=observer&amp;previous=$previous\">".LangViewObservationField2."</a></td>";
			echo "<td></td>";
			echo "</tr>";

$count = 0;

while(list ($key, $value) = each($eyeps))
{
 if($count >= $min && $count < $max) // selection
 {
   if ($count % 2)
   {
    $type = "class=\"type1\"";
   }
   else
   {
    $type = "class=\"type2\"";
   }

   $name = stripslashes($eyepieces->getName($value));
   $focalLength = stripslashes($eyepieces->getFocalLength($value));
   $apparentFOV = $eyepieces->getApparentFOV($value);
   $observer = $eyepieces->getObserver($value);
   $maxFocalLength = $eyepieces->getMaxFocalLength($value);
   if ($maxFocalLength == "-1")
   {
     $maxFocalLength = "-";
   }

   if ($value != "1")
   {
    print("<tr $type>
           <td><a href=\"common/adapt_eyepiece.php?eyepiece=$value\">$name</a></td>\n
           <td>$focalLength</td>\n
           <td>$maxFocalLength</td>\n
           <td>$apparentFOV</td>\n
            <td>");
           echo ($observer);
           echo("</td>\n<td>");

           // check if there are no observations made with this eyepiece

           $queries = array("eyepiece" => $value);
           $obs = $observations->getObservationFromQuery($queries, "", "1", "False");

//           $comobs = $cometobservations->getObservationFromQuery($queries, "", "1", "False");

//           if(!sizeof($obs) > 0 && !sizeof($comobs) > 0) // no observations with eyepiece yet
           if(!sizeof($obs) > 0) // no observations with eyepiece yet
           {
              echo("<a href=\"common/control/validate_delete_eyepiece.php?eyepieceid=" . $value . "\">" . LangRemove . "</a>");
           }

           echo("</td>\n</tr>");

   }
 }
   $count++;
}
  echo "</table>";

  list($min, $max) = $util->printListHeader($eyeps, $link, $min, $step, "");

  echo "</div></div></body></html>";
?>
