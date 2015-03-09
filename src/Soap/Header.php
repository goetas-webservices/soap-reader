<?php

namespace PhpWebservices\XML\SOAPReader\Soap;

use PhpWebservices\XML\WSDLReader\Wsdl\Message\Part;

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