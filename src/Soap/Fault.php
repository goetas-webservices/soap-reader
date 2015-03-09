<?php
namespace Goetas\XML\SOAPReader\Soap;

use Goetas\XML\WSDLReader\Wsdl\Message\Part;

class Fault extends Body
{
    /**
     *
     * @var string
     */
    protected $name;
	public function getName() {
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
		return $this;
	}


}