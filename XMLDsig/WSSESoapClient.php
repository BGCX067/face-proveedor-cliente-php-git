<?php

namespace XMLDsig;

use XMLDsig\WSASoap;
use XMLDsig\WSSESoap;
use XMLDsig\XMLSecurityKey;

class WSSESoapClient extends \SoapClient {
	protected $_pass;
	protected $_cert;
	protected $_noWSA;
	protected $_noTimestamp;
	private $_rawRequests;
	private $_rawRequestSigned;
	private $_rawResponse;
	
	/**
	 *
	 * @param field_type $_noTimestamp        	
	 */
	public function setNoTimestamp($_noTimestamp) {
		$this->_noTimestamp = $_noTimestamp;
	}
	
	/**
	 *
	 * @param field_type $_WSA        	
	 */
	public function setNoWSA($_noWSA) {
		$this->_noWSA = $_noWSA;
	}
	
	/**
	 *
	 * @param field_type $_pass        	
	 */
	public function setPass($_pass) {
		$this->_pass = $_pass;
	}
	
	/**
	 *
	 * @param field_type $_cert        	
	 */
	public function setCert($_cert) {
		$this->_cert = $_cert;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getRawrequests() {
		return $this->_rawRequests;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getRawrequestsigned() {
		return $this->_rawRequestSigned;
	}
	
	/**
	 *
	 * @return the unknown_type
	 */
	public function getRawresponse() {
		return $this->_rawResponse;
	}
	function __doRequest($soapMessage, $location, $action, $version, $one_way = 0) {
		$this->_rawRequests = $soapMessage;
		$dom = new \DOMDocument ();
		$dom->loadXML ( $soapMessage );
		
		$objWSA = new WSASoap ( $dom );
		if (! $this->_noWSA) {
			$objWSA->addAction ( $action );
			$objWSA->addTo ( $location );
			$objWSA->addMessageID ();
			$objWSA->addReplyTo ();
		}
		$dom = $objWSA->getDoc ();
		
		$objWSSE = new WSSESoap ( $dom );
		/* Sign all headers to include signing the WS-Addressing headers */
		$objWSSE->signAllHeaders = TRUE;
		
		if (! $this->_noTimestamp) {
			$objWSSE->addTimestamp ();
		}
		
		/* create new XMLSec Key using RSA SHA-1 and type is private key */
		$objKey = new XMLSecurityKey ( XMLSecurityKey::RSA_SHA1, array (
				'type' => 'private' 
		) );
		
		/* load the private key from file - last arg is bool if key in file (TRUE) or is string (FALSE) */
		$objKey->passphrase = $this->_pass;
		$objKey->loadKey ( $this->_cert, TRUE );
		
		/* Sign the message - also signs appropraite WS-Security items */
		$objWSSE->signSoapDoc ( $objKey );
		/* Add certificate (BinarySecurityToken) to the message and attach pointer to Signature */
		$token = $objWSSE->addBinaryToken ( file_get_contents ( $this->_cert ) );
		$objWSSE->attachTokentoSig ( $token );
		$request = $objWSSE->saveXML ();
		
		$this->_rawRequestSigned = $request;
		
		$this->_rawResponse = parent::__doRequest ( $request, $location, $action, $version );
		
		return $this->_rawResponse;
	}
}
