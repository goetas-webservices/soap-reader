<?php

namespace GoetasWebservices\XML\SOAPReader\Soap;

use GoetasWebservices\XML\WSDLReader\Wsdl\Message\Part;

class Header extends AbstractHeader {

	/**
	 *
	 * @var HeaderFault[]
	 */
	protected $faults = array ();

	public function getFaults()
	{
		return $this->faults;
	}
	public function addFault(HeaderFault $header)
	{
		$this->faults[] = $header;
	}
}