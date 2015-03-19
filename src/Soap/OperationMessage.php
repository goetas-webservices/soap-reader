<?php
namespace GoetasWebservices\XML\SOAPReader\Soap;

use GoetasWebservices\XML\WSDLReader\Wsdl\Binding\OperationMessage as WsdlOperationMessage;

class OperationMessage
{
    /**
     *
     * @var \GoetasWebservices\XML\WSDLReader\Wsdl\Binding\OperationMessage
     */
    protected $message;
    /**
     *
     * @var Body
     */
    protected $body;
    /**
     * @var Header[]
     */
    protected $headers = array();
    /**
     * @var HeaderFault[]
     */
    protected $headerFaults = array();

    public function __construct(WsdlOperationMessage $message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody(Body $body)
    {
        $this->body = $body;
    }

    public function getHeaders()
    {
        return $this->headers;
    }
    public function getHeaderFaults()
    {
        return $this->headerFaults;
    }

	public function addHeader(Header $header)
	{
		$this->headers[]=$header;
	}
	public function addHeaderFault(HeaderFault $header)
	{
		$this->headerFaults[]=$header;
	}
}
