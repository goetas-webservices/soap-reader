<?php
namespace Goetas\XML\SOAPReader\Soap;

use Goetas\XML\WSDLReader\Wsdl\Message\Part;

class Body extends AbstractMessage
{

    /**
     * Indicates with part or parts defined in the input or output message
     * (specified through the "wsdl:portType" element) are used to construct this SOAP Body.
     * If not provided, all parts are used.
     *
     * @var Part[]
     */
    protected $parts = array();

	public function getParts() {
		return $this->parts;
	}
	public function setParts($parts) {
		$this->parts = $parts;
		return $this;
	}

}