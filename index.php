<?php        

	require_once 'utils\auto_load.php';

	use utils\Utility;

	$domain = $_SERVER['SERVER_NAME'];
	$url = $_SERVER['REQUEST_URI'];
	$uriPath = explode("/", strtok($url, "?"));
	$controller = ucfirst(isset($uriPath[1]) ? $uriPath[1] : "");
	$action = Utility::camelCase(isset($uriPath[2]) ? $uriPath[2] : "");
	if (empty($controller))
		$controller = "Main";
	if (empty($action))
		$action = "index";
	$controller = "\\controllers\\$controller";
	if (!class_exists($controller))
		return Utility::notFound();
	/** @var BaseController $controller */
	$controller = new $controller();
	if (!method_exists($controller, $action))
		return Utility::notFound();
	$controller->beforeAction();
	$controller->{$action}();
	$controller->afterAction();
	http_response_code($controller->getHttpCode());
	return $controller->getResponse();
?>
