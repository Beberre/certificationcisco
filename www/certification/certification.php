<?php
$titre = "Cisco Certification";
require "../commun/config.php" ;
require "../commun/fctcertification.php" ;
require "../commun/strsite.php" ;

echo $htmlstart;
echo $head ;
echo $bodystart;
echo $header;
echo $nav;
echo $sectionstart;
fctSectionCertification();
echo $sectionend;
echo $footer;
echo $bodystart;
echo $htmlend;

?>
