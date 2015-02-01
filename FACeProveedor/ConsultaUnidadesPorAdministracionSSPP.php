<?php
/**
 * Clase para consultar unidades por administracion a FACe
 *
 * @author     Francisco Javier Perales <http://es.linkedin.com/in/franciscoperales>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 *
 * PHP Version minima 5.4
 */

namespace FACeProveedor;


use XMLDsig\WSSESoap;
use XMLDsig\WSASoap;
use XMLDsig\WSSESoapClient;

class ConsultaUnidadesPorAdministracionSSPP
{
	private $_rawResponse;
	private $_rawRequest;
	private $_rawRequestSigned;
	private $_wsdl;
	private $_cert;
	private $_password;
	
	
	function __construct($wsdl,$cert,$pass)
	{
		if(empty($wsdl) or empty($cert) or empty($pass)){
			throw new \Exception("Un parametro viene vacio, revise el codigo");
		}
		$this->_wsdl=$wsdl;
		$this->_cert=$cert;
		$this->_password=$pass;
	}
	public function ConsultarUnidadesPorAdministracion($codigo_dir)
	{
		$sc = new WSSESoapClient($this->_wsdl ,array ('trace' => TRUE,'cache_wsdl' => WSDL_CACHE_NONE));
		
		try {
			$sc->setCert($this->_cert);
			$sc->setPass($this->_password);
			$out = $sc->ConsultarUnidadesPorAdministracion($codigo_dir);
			
			file_put_contents('request.xml', $sc->getRawrequests());
			file_put_contents('requestSigner.xml', $sc->getRawrequestsigned());
			file_put_contents('response.xml', $sc->getRawresponse());
		} catch (SoapFault $fault) {
		 	print_r($fault->getMessage());
		}
	}
	
	
	
}

