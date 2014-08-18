<?php
/**
 * @author Oliver Lorenz <oliver.lorenz@project-collins.com>
 * @since 2014-08-17
 */

require_once __DIR__.'/vendor/autoload.php';

include('config.php');

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

/**
 * @param $name
 * @return string
 */
function getSwitch($name) {
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

/**
 * @param $config
 * @param $code
 * @param $channel
 * @param $state
 * @return string
 */
function doCommand($config, $code, $channel, $state)
{
    $pattern = $config['command']['pattern'];

    $command = sprintf($pattern, $code, $channel, $state);
    system($command);
    return $command;
}

$app = new Silex\Application();

$app->get('/', function() use($app, $config, $header, $footer) {
    $html = $header;
    foreach ($config['switches'] as $name => $data) {
        $html .= getSwitch($name, $data['code'], $data['channel']);
    }
    $html .= $footer;
    return $html;
});

$app->get('/light/{name}/on', function($name) use($app, $config) {
    $code = $config['switches'][$name]['code'];
    $channel = $config['switches'][$name]['channel'];
    $state = 1;

    $return = '';
    // $return =
        doCommand($config, $code, $channel, $state);
    return $return;
});

$app->get('/light/{name}/off', function($name) use($app, $config) {
    $code = $config[$name]['code'];
    $channel = $config[$name]['channel'];
    $state = 0;

    doCommand($config, $code, $channel, $state);
    return '';
});

$app->run();