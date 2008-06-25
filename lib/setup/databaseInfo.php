<?php
  // Change the following to make deepskylog work with your database
  $host = "localhost";
  $user = "wim";
  $pass = "";
  $dbname = "deepskylog";

  // Installation directory (has to end on a slash!)
  $instDir = "/software/www/htdocs/deepskylog/";

  $baseURL = "http://localhost/deepskylog/";

  // General settings
  // The title to appear above all pages
  $title = "<a href=\"http://www.deepskylog.org\">DeepskyLog</a>";

  // The first part of the title that has to appear on each browser window
  $browsertitle = "DeepskyLog";

  // DeepskyLog can show only the observations of a given club. 
  // This makes it possible to have one central database in a country.
  // All clubs can have a local installation of DeepskyLog where only the 
  // observations of their own club can be shown.
  $club = "";

  // Allow users to register from within the DeepskyLog application directly
  // If you want to use a already existing database with users and passwords
  // eg ppb you should set this variable to "no" 
  $register = "yes";

  // Charts of Deepskylive (http://users.telenet.be/deepskylive) can be enabled
  // or disabled. Set to 1 to enable the charts of deepskylive.
  $deepskylive = 1;

  // Configure the output format of observation dates  
  // Some examples of formatting december 28th 2004:
  // d-m-Y displays 28-12-2004
  // Y/m/d displays 2004/12/28
  // M-d-Y displays Dec-28-2004
  $dateformat = "d/m/Y";

  // The standard language is the language that is used by default in 
  // deepskylog. At this moment, the possibilities are "en" and 
  // "nl". It is possible to disable the choose of a language using 
  // the variable $languageMenu. 
  $defaultLanguage = "nl";

  // If you don't want the users to change the language of deepskylog, you 
  // can set $languageMenu to 0 (1 to enable the menu)
  $languageMenu = 1;

  // maximum file size allowed for uploading drawings (in bytes)
  $maxFileSize = 2000000;

  // Defines the different DeepskyLog modules. 
  // At this moment, the possible modules are deepsky and comets.
  // The first defined module will be used for the main page.
  $modules = array("deepsky", "comets");

  // Defines the standard language of the descriptionswhich are selected during the 
  // registration.
  $standardLanguagesForObservationsDuringRegistration = "nl";

  // Defines the different languages which are selected during the 
  // registration.
  $languagesDuringRegistration = array("en", "nl");
?>
