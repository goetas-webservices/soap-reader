<?php
namespace Goetas\XML\SOAPReader\Soap;

use Goetas\XML\WSDLReader\Wsdl\Message\Part;

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