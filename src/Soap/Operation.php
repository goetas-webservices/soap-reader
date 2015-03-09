<?php
namespace PhpWebservices\XML\SOAPReader\Soap;

use PhpWebservices\XML\WSDLReader\Wsdl\Binding\Operation as WsdlOperation;

class Operation
{

    /**
     *
     * @var \PhpWebservices\XML\WSDLReader\Wsdl\Binding\Operation
     */
    protected $operation;

    /**
     * Provides the value for the SOAPAction header line.
     * Required for SOAP 1.1 over HTTP binding and not needed for other transportations.
     * @var string
     */
    protected $action;
    /**
     * "rpc|document" - Provides a message style for this operation.
     * This is an optional attribute.
     * The default is provided by the "soap:binding" element
     * @var unknown
     */
    protected $style = 'rpc';

    /**
     *
     * @var OperationMessage
     */
    protected $input;

    /**
     *
     * @var OperationMessage
     */
    protected $output;

    /**
     *
     * @var array
     */
    protected $faults = array();

    public function __construct(WsdlOperation $operation)
    {
        $this->operation = $operation;
    }

    public function getOperation()
    {
        return $this->operation;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    public function getStyle()
    {
        return $this->style;
    }

    public function setStyle($style)
    {
        $this->style = $style;
        return $this;
    }

    public function getInput()
    {
        return $this->input;
    }

    public function setInput(OperationMessage $input)
    {
        $this->input = $input;
        return $this;
    }

    public function getOutput()
    {
        return $this->output;
    }

    public function setOutput(OperationMessage $output)
    {
        $this->output = $output;
        return $this;
    }

    public function getFaults()
    {
        return $this->fault;
    }

    public function addFault(Fault $fault)
    {
    	$this->faults[] = $fault;
    }
}