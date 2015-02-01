<?php
/**
 * Este fichero contiene un ejemplo de llamada a la clase EnviarFacturaSSPP
 *
 * @author     Francisco Javier Perales <http://es.linkedin.com/in/franciscoperales> <http://es.linkedin.com/in/franciscoperales>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 *
 * PHP Version minima 5.4
 */

chdir(__DIR__);

require_once 'vendor/autoload.php';


use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use FACeProveedor\EnviarFacturaSSPP;

$yaml = new Parser();

try {
	$config = $yaml->parse(file_get_contents('config/app.yml'));
} catch (ParseException $e) {
	printf("Unable to parse the YAML string: %s", $e->getMessage());
}


$wsdl=$config['wsdl'];
$privateKey=$config['privateKey'];
$password=$config['password'];
$enviarFactura = new EnviarFacturaSSPP($wsdl,$privateKey,$password);

$factura=file_get_contents("factura.xml.xsig");
$ficheroFactura=array("factura"=>$factura,"nombre"=>"FX1.xsig","mime"=>"application/xml");
$ficheroAnexo=array();

$enviarFactura->enviarFactura("francisco.perales@gmail.com", $ficheroFactura, $ficheroAnexo);





