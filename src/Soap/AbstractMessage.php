<?php
namespace GoetasWebservices\XML\SOAPReader\Soap;

use GoetasWebservices\XML\WSDLReader\Wsdl\Message\Part;

abstract class AbstractMessage
{

    /**
     * Indicates whether the message part(s) should be used as is (literal) or encoded.
     * This is a required attribute.
     * - If use="literal", the message part(s) are referring to concrete
     * schema definition(s) and no encoding is needed.
     * - If use="encoded", the message part(s) are referring to abstract schema definition(s) and
     * concrete message can be produced by applying the specified encoding style.
     *
     * @var unknown
     */
    protected $use = 'literal';

    /**
     * Indicates the namespace to be used in case of use="encoded".
     *
     * @var string
     */
    protected $namespace;

    /**
     * Indicates encoding style(s) to be used in case of use="encoded".
     *
     * @var string[]
     */
    protected $encoding = array();

    public function getUse() {
    	return $this->use;
    }
    public function setUse($use) {
    	$this->use = $use;
    	return $this;
    }
    public function getNamespace() {
    	return $this->namespace;
    }
    public function setNamespace($namespace) {
    	$this->namespace = $namespace;
    	return $this;
    }
    public function getEncoding() {
    	return $this->encoding;
    }
    public function setEncoding($encoding) {
    	$this->encoding = $encoding;
    	return $this;
    }


}