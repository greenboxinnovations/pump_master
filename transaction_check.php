<?php
$table_name = "customers";
echo exec('/opt/lampp/bin/mysql -u"root" --password="toor"  "pump_master" < /opt/lampp/htdocs/pump_master/mysql_dump/'.$table_name.".sql");


?>