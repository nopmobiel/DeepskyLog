<?php // add_csv_observations.php - adds observations from a csv file to the database
$_GET['indexAction']='default_action';
if($_FILES['csv']['tmp_name']!="")
  $csvfile=$_FILES['csv']['tmp_name'];
$data_array=file($csvfile); 
for($i=0;$i<count($data_array);$i++ ) 
  $parts_array[$i]=explode(";",$data_array[$i]); 
for($i=0;$i<count($parts_array);$i++)
{ $objects[$i] = htmlentities($parts_array[$i][0]);
  $dates[$i] = $parts_array[$i][2];
  $locations[$i] = htmlentities($parts_array[$i][4]);
  $instruments[$i] = htmlentities($parts_array[$i][5]);
  $filters[$i] = htmlentities($parts_array[$i][7]);
  $eyepieces[$i] = htmlentities($parts_array[$i][6]);
  $lenses[$i] = htmlentities($parts_array[$i][8]);
}
//$objects = array_unique($objects);
// JV 20060224 add check to see if $objects contains data or not
// -> show error page
if(!is_array($objects))
 throw new Exception(LangInvalidCSVfile);
else
{ $objects = array_values($objects);
  $locations = array_unique($locations);
  $locations = array_values($locations);
  $instruments = array_unique($instruments);
  $instruments = array_values($instruments);
  $filters = array_unique($filters);
  $filters = array_values($filters);
  $eyepieces = array_unique($eyepieces);
  $eyepieces = array_values($eyepieces);
  $lenses = array_unique($lenses);
  $lenses = array_values($lenses);
  $dates = array_unique($dates);
  $dates = array_values($dates);
  $noDates=array();
  $wrongDates=array();
  $objectsMissing = array();
	$locationsMissing = array();
	$instrumentsMissing = array();
	$filtersMissing = array();
  $eyepiecesMissing = array();
  $lensesMissing = array();
  $errorlist=array();
  // Test if the objects, locations and instruments are available in the database
  for($i=0,$j=0;$i<count($objects);$i++)
  { $objectsquery=$objObject->getExactDSObject($objects[$i]);
    if(!$objectsquery)
    { $objectsMissing[$j++]=$objects[$i];
      $errorlist[]=$i;
    }
    else
      $correctedObjects[]=$objectsquery;
  }
	// Check for existence of locations
  for($i= 0,$j=0,$temploc='';$i<count($locations);$i++)
    if((!$locations[$i])||($temploc!=$locations[$i])&&($objLocation->getLocationId($locations[$i],$loggedUser)==-1))
    { $locationsMissing[$j++]=$locations[$i];
      $errorlist[]=$i;
    }
	  else
		  $temploc=$locations[$i];
  // Check for existence of instruments
  for($i=0,$j=0,$tempinst='';$i<count($instruments);$i++)
    if((!$instruments[$i])||($objInstrument->getInstrumentId($instruments[$i],$loggedUser)==-1))
    { $instrumentsMissing[$j++]=$instruments[$i];
		  $errorlist[]=$i;
    }
    else
		  $tempinst=$instruments[$i];
  // Check for the existence of the eyepieces
  for($i=0,$j=0;$i<count($eyepieces);$i++)
    if($eyepieces[$i]&&(!($objEyepiece->getEyepieceObserverPropertyFromName($eyepieces[$i],$loggedUser,'id'))))
    { $eyepiecesMissing[$j++]=$eyepieces[$i];
      $errorlist[]=$i;
    }
      // Check for the existence of the filters
  for($i=0,$j=0;$i<count($filters);$i++)
    if($filters[$i]&&(!($objFilter->getFilterObserverPropertyFromName($filters[$i], $loggedUser,'id'))))
    { $filtersMissing[$j++]=$filters[$i];
      $errorlist[]=$i;
    }
      // Check for the existence of the lenses
  for($i=0,$j=0;$i<count($lenses);$i++)
    if($lenses[$i]&&(!($objLens->getLensObserverPropertyFromName($lenses[$i],$loggedUser,'id'))))
    { $lensesMissing[$j++] = $lenses[$i];
      $errorlist[]=$i;
    }
      // Check for the correctness of dates
  for($i=0,$j=0,$k=0;$i<count($dates);$i++)
  { $datepart=sscanf($dates[$i],"%2d%c%2d%c%4d");
    if((!is_numeric($datepart[0]))||(!is_numeric($datepart[2]))||(!is_numeric($datepart[4]))||(!checkdate($datepart[2],$datepart[0],$datepart[4])))
    { $noDates[$j++]=$dates[$i]; 
      $errorlist[]=$i;
    }
    elseif((sprintf("%04d",$datepart[4]).sprintf("%02d",$datepart[2]).sprintf("%02d",$datepart[0]))>date('Ymd')) 
    { $wrongDates[$k++]=$dates[$i];
      $errorlist[]=$i;
    }
  }
  // error catching
  if(count($errorlist)>0)
  { $errormessage=LangCSVError1 . "<br />\n";
    if(count($noDates)>0)
    { $errormessage = $errormessage . "<ul>";
      $errormessage = $errormessage .  "<li>".LangCSVError8." : ";
      $errormessage = $errormessage .  "<ul>";
      for ( $i = 0;$i < count($noDates);$i++ )
        $errormessage = $errormessage . "<li>".$noDates[$i]."</li>";
      $errormessage = $errormessage .  "</ul>";
      $errormessage = $errormessage .  "</li>\n";
      $errormessage = $errormessage .  "</ul>";
    }
    if(count($wrongDates)>0)
    { $errormessage = $errormessage . "<ul>";
      $errormessage = $errormessage .  "<li>".LangCSVError9." : ";
      $errormessage = $errormessage .  "<ul>";
      for ( $i = 0;$i < count($wrongDates);$i++ )
        $errormessage = $errormessage . "<li>".$wrongDates[$i]."</li>";
      $errormessage = $errormessage .  "</ul>";
      $errormessage = $errormessage .  "</li>\n";
      $errormessage = $errormessage .  "</ul>";
    }
    if(count($objectsMissing)>0)
    { $errormessage = $errormessage . "<ul>";
      $errormessage = $errormessage .  "<li>".LangCSVError2." : ";
      $errormessage = $errormessage .  "<ul>";
      for ( $i = 0;$i < count($objectsMissing);$i++ )
        $errormessage = $errormessage . "<li>".$objectsMissing[$i]."</li>";
      $errormessage = $errormessage .  "</ul>";
      $errormessage = $errormessage .  "</li>\n";
      $errormessage = $errormessage .  "</ul>";
    }
    if(count($locationsMissing)>0)
    { $errormessage = $errormessage . "<ul>";
      $errormessage = $errormessage .  "<li>".LangCSVError3." : ";
      $errormessage = $errormessage . "<ul>";
      for ( $i = 0;$i < count($locationsMissing);$i++ )
        $errormessage = $errormessage . "<li>".$locationsMissing[$i]."</li>";
      $errormessage = $errormessage . "</ul>";
      $errormessage = $errormessage .  "</li>\n";
      $errormessage = $errormessage .  "</ul>";
    }
    if(count($instrumentsMissing)>0)
    { $errormessage = $errormessage . "<ul>";
      $errormessage = $errormessage . "<li>".LangCSVError4." : ";
      $errormessage = $errormessage . "<ul>";
      for ( $i = 0;$i < count($instrumentsMissing);$i++ )
        $errormessage = $errormessage . "<li>".$instrumentsMissing[$i]."</li>";
      $errormessage = $errormessage . "</ul>";
      $errormessage = $errormessage . "</li>\n";
      $errormessage = $errormessage . "</ul>";
    }
    if(count($filtersMissing)>0)
    { $errormessage = $errormessage . "<ul>";
      $errormessage = $errormessage .  "<li>".LangCSVError5." : ";
      $errormessage = $errormessage . "<ul>";
      for ( $i = 0;$i < count($filtersMissing);$i++ )
        $errormessage = $errormessage . "<li>".$filtersMissing[$i]."</li>";
      $errormessage = $errormessage . "</ul>";
      $errormessage = $errormessage .  "</li>\n";
      $errormessage = $errormessage .  "</ul>";
    }
    if (count($eyepiecesMissing) > 0)
    { $errormessage = $errormessage . "<ul>";
      $errormessage = $errormessage .  "<li>".LangCSVError6." : ";
      $errormessage = $errormessage . "<ul>";
      for ( $i = 0;$i < count($eyepiecesMissing);$i++ )
        $errormessage = $errormessage . "<li>".$eyepiecesMissing[$i]."</li>";
      $errormessage = $errormessage . "</ul>";
      $errormessage = $errormessage .  "</li>\n";
      $errormessage = $errormessage .  "</ul>";
    }
    if (count($lensesMissing) > 0)
    { $errormessage = $errormessage . "<ul>";
      $errormessage = $errormessage .  "<li>".LangCSVError7." : ";
      $errormessage = $errormessage . "<ul>";
      for ( $i = 0;$i < count($lensesMissing);$i++ )
        $errormessage = $errormessage . "<li>".$lensesMissing[$i]."</li>";
      $errormessage = $errormessage . "</ul>";
      $errormessage = $errormessage .  "</li>\n";
      $errormessage = $errormessage .  "</ul>";
    }
    
    set SESSION variable with error observations
    
    $messageLines[] = "<h2>".LangCSVError0."</h2>"."<p />".LangCSVError0."<p />".$errormessage."<p />".LangCSVError10."href to error list of observations"."<p />".LangCSVMessage4;
    $_GET['indexAction']='message';
  }
  $username=$objObserver->getObserverProperty($loggedUser,'firstname'). " ".$objObserver->getObserverProperty($loggedUser,'name');
  for($i=0;$i<count($parts_array);$i++)
  { if(!(in_array($i,$errorlist)))
    { $observername = $objObserver->getObserverProperty(htmlentities($parts_array[$i][1]),'firstname'). " ".$objObserver->getObserverProperty(htmlentities($parts_array[$i][1]),'name');
      if($parts_array[$i][1]==$username)
      { $instrum = $objInstrument->getInstrumentId(htmlentities($parts_array[$i][5]), $_SESSION['deepskylog_id']);
        $locat = $objLocation->getLocationId(htmlentities($parts_array[$i][4]), $_SESSION['deepskylog_id']);
        $dates = sscanf($parts_array[$i][2], "%2d%c%2d%c%4d");
        $date = sprintf("%04d%02d%02d", $dates[4], $dates[2], $dates[0]);
        $times = sscanf($parts_array[$i][3], "%2d%c%2d");
        $time = sprintf("%02d%02d", $times[0], $times[2]);
        if ($parts_array[$i][11] == "")
          $parts_array[$i][11] = "0";
        $obsid=$objObservation->addDSObservation($correctedObjects[$i-1],$_SESSION['deepskylog_id'],$instrum,$locat,$date,$time,htmlentities($parts_array[$i][13]),htmlentities($parts_array[$i][9]),htmlentities($parts_array[$i][10]),htmlentities($parts_array[$i][11]),htmlentities($parts_array[$i][12]));
				if ($parts_array[$i][6] != "")
				  $objObservation->setDsObservationProperty($obsid,'eyepieceid', $objEyepiece->getEyepieceObserverPropertyFromName(htmlentities($parts_array[$i][6]), $_SESSION['deepskylog_id'],'id'));
				if ($parts_array[$i][7] != "")
					$objObservation->setDsObservationProperty($obsid,'filterid', $objFilter->getFilterObserverPropertyFromName(htmlentities($parts_array[$i][7]), $_SESSION['deepskylog_id'],'id'));
				if ($parts_array[$i][8] != "")
					$objObservation->setDsObservationProperty($obsid,'lensid', $objLens->getLensObserverPropertyFromName(htmlentities($parts_array[$i][8]), $_SESSION['deepskylog_id'],'id'));
      }
      unset($_SESSION['QobsParams']);
    }
  }
}
?>
