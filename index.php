<?php
/**
 * @author Oliver Lorenz <oliver.lorenz@project-collins.com>
 * @since 2014-08-17
 */

require_once __DIR__.'/vendor/autoload.php';

$config = array(
    'floor' => array(
        'code' => '10110',
        'channel' => '2',
    ),
    'livingroom' => array(
        'code' => '10110',
        'channel' => '1',
    ),
    'kitchen' => array(
        'code' => '10110',
        'channel' => '3',
    ),
    'bedroom' => array(
        'code' => '10110',
        'channel' => '4',
    ),
    'multimedia' => array(
        'code' => '10100',
        'channel' => '1',
    )
);

$header = '<html lang="en">
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
	</head>
	<body>
		<h1>Lights</h1>
		<div class="container-fluid">
		    <div class="row">
		    	<h2>All</h2>
		 		<div class="col-xs-6">
		    		<button type="button" class="btn btn-primary btn-lg btn-block" onclick="allOn()">On</button>
		    	</div>
		    	<div class="col-xs-6">
		    		<button type="button" class="btn btn-default btn-lg btn-block" onclick="allOff()">Off</button>
		    	</div>
		  	</div>
		';

$footer = '</div>
		<script type="text/javascript">
			function doSwitch (name, state) {
			    $.get(\'light/\' + name + \'/\' + state, function() {});
    		}

    		function allOn() {
    			doSwitch(\'10110\',1,1);
				doSwitch(\'10110\',2,1);
				doSwitch(\'10110\',3,1);
				doSwitch(\'10110\',4,1);
    		}

    		function allOff() {
    			doSwitch(\'10110\',1,0);
				doSwitch(\'10110\',2,0);
				doSwitch(\'10110\',3,0);
				doSwitch(\'10110\',4,0);
    		}
	    </script>
	</body>
</html>';

function getSwitch($name, $code, $channel) {
    $html = '<div class="row">
		    	<h2>' . ucfirst($name) . '</h2>
		 		<div class="col-xs-6">
		    		<button type="button" class="btn btn-primary btn-lg btn-block" onclick="doSwitch(\'' . $name . '\', \'on\')">On</button>
		    	</div>
		    	<div class="col-xs-6">
		    		<button type="button" class="btn btn-default btn-lg btn-block" onclick="doSwitch(\'' . $name . '\', \'off\')">Off</button>
		    	</div>
		  	</div>';
    return $html;
}

function doCommand($code, $channel, $state)
{
    $command = "cd /home/pi/rcswitch-pi/; sudo ./send " . $code . " " . $channel . " " . $state;
    system($command);
    return $command;
}

$app = new Silex\Application();

$app->get('/', function() use($app, $config, $header, $footer) {
    $html = $header;
    foreach ($config as $name => $data) {
        $html .= getSwitch($name, $data['code'], $data['channel']);
    }
    $html .= $footer;
    return $html;
});
$app->get('/light/{name}/on', function($name) use($app, $config) {
    $code = $config[$name]['code'];
    $channel = $config[$name]['channel'];
    $state = 1;

    doCommand($code, $channel, $state);
    return '';
});

$app->get('/light/{name}/off', function($name) use($app, $config) {
    $code = $config[$name]['code'];
    $channel = $config[$name]['channel'];
    $state = 0;

    doCommand($code, $channel, $state);
    return '';
});

$app->run();