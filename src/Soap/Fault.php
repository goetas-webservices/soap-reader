<?php
namespace GoetasWebservices\XML\SOAPReader\Soap;

use GoetasWebservices\XML\WSDLReader\Wsdl\PortType\Fault as WsdlFault;

class Fault extends Body
{
    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var WsdlFault
     */
    protected $fault;


    public function __construct(WsdlFault $fault)
    {
		$this->fault = $fault;
    }

	public function getName() {
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return \GoetasWebservices\XML\WSDLReader\Wsdl\PortType\Fault
	 */
	public function getFault()
	{
		return $this->fault;
	}

}