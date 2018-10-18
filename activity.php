<?php
require 'exe/lock.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Daily Activity - Beta</title>
	<meta name=viewport content="width=device-width, initial-scale=1">
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
	<meta http-equiv="pragma" content="no-cache" />	

	
	<!-- favicons -->
	<link rel="apple-touch-icon" sizes="57x57" href="css/favi/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="css/favi/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="css/favi/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="css/favi/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="css/favi/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="css/favi/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="css/favi/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="css/favi/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="css/favi/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="css/favi/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="css/favi/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="css/favi/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="css/favi/favicon-16x16.png">
	<link rel="manifest" href="css/favi/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="css/favi/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">

	<style type="text/css">
		#wrapper{overflow: hidden;}
		#main_padding{;margin: 50px;}
		table{border-collapse: collapse;}
		.right_num{text-align: right;}

		#date_div{margin-bottom: 20px;}

		#flex_div{display: flex;}
		.inline{flex: 1;}
	}
	</style>

	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css?family=Roboto+Slab" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			$('#total_trans').load('display/activity/total_app_trans.php');
			$('#daily_trans').load('display/activity/day_app_trans.php');

			$('#app_date').on('change', function(){
				var date = $(this).val();
				if(date != ""){
					$('#daily_trans').load('display/activity/day_app_trans.php?date='+date);
				}
			});
		});
	</script>
</head>
<body>

<!-- app nav -->
<div id="app_bar">  
	<div id="menu">
		<img src="css/icons/ic_menu.png">
	</div>
	<div id="app_name"><a href="index.php">PumpMaster</a></div>
</div>

<?php
	$active_page = 'activity';
	require'nav.php';
?>

<!-- wrapper -->
<div id="wrapper">
	<div id="main_padding">
		<div id="date_div"><input id="app_date" type="date" value="<?php echo date('Y-m-d')?>"></div>
		<div id="flex_div">
			<div id="daily_trans" class="inline"></div>
			<div id="total_trans" class="inline"></div>			
		</div>
	</div>
</div>

<!-- snackbar -->
<div id="snackbar"></div>

</body>
</html>