<?php
namespace PhpWebservices\XML\SOAPReader\Soap;

use PhpWebservices\XML\WSDLReader\Wsdl\Message\Part;

abstract class AbstractHeader extends AbstractMessage
{

    /**
     *
     * @var Part
     */
    protected $part;
	public function getPart() {
		return $this->part;
	}
	public function setPart(Part $part) {
		$this->part = $part;
		return $this;
	}


}