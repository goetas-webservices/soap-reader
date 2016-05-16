<?php
namespace GoetasWebservices\XML\SOAPReader\Tests;

use GoetasWebservices\XML\SOAPReader\SoapReader;
use GoetasWebservices\XML\WSDLReader\DefinitionsReader;
use Symfony\Component\EventDispatcher\EventDispatcher;

class SoapReaderTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var DefinitionsReader
     */
    protected $wsdl;
    /**
     *
     * @var SoapReader
     */
    protected $soap;

    public function setUp()
    {
        $dispatcher = new EventDispatcher();
        $this->wsdl = new DefinitionsReader(null, $dispatcher);

        $this->soap = new SoapReader();
        $dispatcher->addSubscriber($this->soap);
    }

    public function testSimple()
    {
        $definitions = $this->wsdl->readFile(__DIR__ . '/res/easy.wsdl');

        $service = $definitions->getService('easy');
        $port = $service->getPort('easySOAP');

        $soapService = $this->soap->getSoapServiceByPort($port);

        $this->assertEquals('http://www.example.org/location', $soapService->getAddress());
        $this->assertEquals('document', $soapService->getStyle());
        $this->assertEquals('http://schemas.xmlsoap.org/soap/http', $soapService->getTransport());

        $this->assertArrayHasKey('run', $soapService->getOperations());

        $soapOperation = $soapService->getOperations()['run'];
        $this->assertEquals('http://www.example.org/run', $soapOperation->getAction());
        $this->assertEquals('document', $soapOperation->getStyle());

        $this->assertInstanceOf('GoetasWebservices\XML\WSDLReader\Wsdl\Binding\Operation', $soapOperation->getOperation());

        $input = $soapOperation->getInput();
        $body = $input->getBody();

        $this->assertEquals([], $body->getEncoding());
        $this->assertEquals('literal', $body->getUse());
        $this->assertEquals('', $body->getNamespace());

        $bodyParts = $body->getParts();
        $this->assertCount(1, $bodyParts);
        $this->assertInstanceOf('GoetasWebservices\XML\WSDLReader\Wsdl\Message\Part', $bodyParts['requestParams']);
        $this->assertEquals('run', $bodyParts['requestParams']->getElement()->getName());

        $output = $soapOperation->getOutput();

        $body = $output->getBody();

        $this->assertEquals([], $body->getEncoding());
        $this->assertEquals('literal', $body->getUse());
        $this->assertEquals('', $body->getNamespace());

        $bodyParts = $body->getParts();
        $this->assertCount(1, $bodyParts);
        $this->assertInstanceOf('GoetasWebservices\XML\WSDLReader\Wsdl\Message\Part', $bodyParts['responseParams']);
        $this->assertEquals('runResponse', $bodyParts['responseParams']->getElement()->getName());

        $faults = $soapOperation->getFaults();

        $this->assertCount(2, $faults);
        $this->assertArrayHasKey('f1', $faults);
        $this->assertArrayHasKey('f2', $faults);

        $headers = $soapOperation->getOutput()->getHeaders();

        $this->assertCount(1, $headers);
        $this->assertInstanceOf('GoetasWebservices\XML\SOAPReader\Soap\Header', $headers[0]);

        $this->assertCount(1, $headers[0]->getFaults());

        $this->assertInstanceOf('GoetasWebservices\XML\SOAPReader\Soap\HeaderFault', $headers[0]->getFaults()[0]);
    }

}


