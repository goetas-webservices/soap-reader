<?php

namespace Goetas\XML\SOAPReader;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Goetas\XML\WSDLReader\Events\Service\PortEvent;
use Goetas\XML\SOAPReader\Soap\Service;
use Goetas\XML\WSDLReader\Events\BindingEvent;
use Goetas\XML\WSDLReader\Events\Binding\OperationEvent as BindingOperationEvent;
use Goetas\XML\WSDLReader\Events\Binding\MessageEvent as BindingOperationMessageEvent;
use Goetas\XML\WSDLReader\Wsdl\Binding;
use Goetas\XML\SOAPReader\Soap\Operation;
use Goetas\XML\SOAPReader\Soap\OperationMessage;
use Goetas\XML\SOAPReader\Soap\Body;
use Goetas\XML\WSDLReader\Wsdl\Message\Part;
use Goetas\XML\SOAPReader\Soap\AbstractMessage;
use Goetas\XML\WSDLReader\Wsdl\Message;
use Goetas\XML\SOAPReader\Soap\Header;
use Goetas\XML\SOAPReader\Soap\HeaderFault;
use Goetas\XML\SOAPReader\Soap\AbstractHeader;

class SoapReader implements EventSubscriberInterface {
	const SOAP_NS = 'http://schemas.xmlsoap.org/wsdl/soap/';
	public static function getSubscribedEvents() {
		return [
				'service.port' => 'onServicePort',
				'binding' => 'onBinding',
				'binding.operation' => 'onBindingOperation',
		    	'binding.operation.message'=>'onBindingOperationMessge',
		    	'binding.operation.fault'=>'onBindingOperationFault'
		];
	}

	/**
	 *
	 * @var Service[]
	 */
	protected $servicesByBinding = array ();
	public function onServicePort(PortEvent $event) {
		$service = new Service ( $event->getPort () );

		foreach ( $event->getNode ()->childNodes as $node ) {
			if ($node->namespaceURI == self::SOAP_NS) {
				$service->setAddress ( $node->getAttribute ( "address" ) );
			}
		}

		$this->servicesByBinding [spl_object_hash ( $event->getPort ()->getBinding () )] = $service;
	}
	public function onBinding(BindingEvent $event) {
		$service = $this->getSoapServiceByBinding ( $event->getBinding () );

		foreach ( $event->getNode ()->childNodes as $node ) {
			if ($node->namespaceURI == self::SOAP_NS && $node->localName == 'binding') {
				$service->setTransport ( $node->getAttribute ( "transport" ) );
				if ($node->getAttribute ( "style" )) {
					$service->setStyle ( $node->getAttribute ( "style" ) );
				}
			}
		}
	}

	/**
	 *
	 * @param Binding $binging
	 * @return Service
	 */
	private function getSoapServiceByBinding(Binding $binging) {
		return $this->servicesByBinding [spl_object_hash ( $binging )];
	}
	public function onBindingOperation(BindingOperationEvent $event) {
		$service = $this->getSoapServiceByBinding ( $event->getOperation ()->getBinding () );

		$operation = new Operation ( $event->getOperation () );

		if ($message = $event->getOperation()->getInput()){
		    $operation->setInput(new OperationMessage($message));
		}
		if ($message = $event->getOperation()->getOutput()){
			$operation->setOutput(new OperationMessage($message));
		}
		if ($messages = $event->getOperation()->getFaults()){
			$operation->s(new OperationMessage($message));
		}
		foreach ( $event->getNode ()->childNodes as $node ) {
			if ($node->namespaceURI == self::SOAP_NS && $node->localName == 'operation') {
				if ($node->getAttribute ( "action" )) {
					$operation->setAction ( $node->getAttribute ( "action" ) );
				}elseif ($node->getAttribute ( "style" )) {
					$operation->setStyle( $node->getAttribute ( "style" ) );
				}
			}
		}

		$service->addOperation ( $operation );
	}

	public function onBindingOperationMessge(BindingOperationMessageEvent $event)
	{
	    $where = 'Input';

	    $operation = $event->getOperationMessage()->getOperation();
	    $oMessage = new OperationMessage($event->getOperationMessage());
	    $soapOperation->setInput($oMessage);

	    $typeOperation = $operation->getBinding()->getType()->getOperation($operation->getName());
	    $message = $typeOperation->{"get".$where}()->getMessage();


	    foreach ( $event->getNode ()->childNodes as $node ) {
	    	if ($node->namespaceURI == self::SOAP_NS && $node->localName == 'body') {
			    $body = new Body();
	    	    $this->fillBody($body, $message, $node);
				$oMessage->setBody($body);
	    	}
	    	if ($node->namespaceURI == self::SOAP_NS && $node->localName == 'header') {
	    	    $header = new Header();
	    	    $this->fillHeader($header, $message, $node);
	    		$oMessage->addHeader($header);
	    	}
	    	if ($node->namespaceURI == self::SOAP_NS && $node->localName == 'headerfalt') {
	    	    $header = new HeaderFault();
	    	    $this->fillAbstractHeader($header, $message, $node);
	    		$oMessage->addHeaderFault($header);
	    	}
	    }
	}

	private function fillMessage(AbstractMessage $message, \DOMElement $node)
	{
		if($node->getAttribute("namespace")){
			$message->setNamespace($node->getAttribute("namespace"));
		}
		if($node->getAttribute("use")){
			$message->setUse($node->getAttribute("use"));
		}
		if ($node->getAttribute("encodingStyle")) {
			$message->setEncoding(explode(" ", $node->getAttribute("encodingStyle")));
		}
	}

	private function fillBody(Body $body, Message $message, \DOMElement $node)
	{
		if($node->getAttribute("parts")){
		    $requiredParts = explode(" ", $node->getAttribute("parts"));
			$body->setParts(array_filter($message, function(Part $part) use($requiredParts){
				return in_array($part->getName(), $requiredParts);
			}));
		}else{
		    $body->setParts($message->getParts());
		}

		$this->fillMessage($body, $node);
	}
	private function fillAbstractHeader(AbstractHeader $header, Message $message, \DOMElement $node)
	{
		$header->setPart($message->getPart($node->getAttribute("parts")));
		$this->fillMessage($header, $node);
	}
	private function fillHeader(Header $header, Message $message, \DOMElement $node)
	{
		$this->fillAbstractHeader($header, $message, $node);
		foreach ( $node->childNodes as $childNode ) {
		    if ($childNode->namespaceURI == self::SOAP_NS && $childNode->localName == 'headerfault') {
		    	$headerFault = new HeaderFault();
		    	$this->fillAbstractHeader($headerFault, $message, $childNode);
		    	$header->addFault($headerFault);
		    }
		}
	}
}