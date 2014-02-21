<?php

namespace DoctrineORMModule\Proxy\__CG__\Usuario\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Pessoa extends \Usuario\Entity\Pessoa implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array();



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }

    /**
     * {@inheritDoc}
     * @param string $name
     */
    public function __get($name)
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__get', array($name));

        return parent::__get($name);
    }

    /**
     * {@inheritDoc}
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__set', array($name, $value));

        return parent::__set($name, $value);
    }



    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return array('__isInitialized__', 'inputFilter', 'id', 'nome', 'data_cad', 'url', 'tipo', 'data_rev', 'email', 'situacao', 'origem_gravacao', 'operacao', 'idsis_rev', 'idsis_cad', 'pessoa_cad', 'pessoa_rev', 'fisica', 'usuario', 'oldId');
        }

        return array('__isInitialized__', 'inputFilter', 'id', 'nome', 'data_cad', 'url', 'tipo', 'data_rev', 'email', 'situacao', 'origem_gravacao', 'operacao', 'idsis_rev', 'idsis_cad', 'pessoa_cad', 'pessoa_rev', 'fisica', 'usuario', 'oldId');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Pessoa $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', array());
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', array());
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getSubscribedEvents()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSubscribedEvents', array());

        return parent::getSubscribedEvents();
    }

    /**
     * {@inheritDoc}
     */
    public function timestamp()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'timestamp', array());

        return parent::timestamp();
    }

    /**
     * {@inheritDoc}
     */
    public function checkOperacao()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'checkOperacao', array());

        return parent::checkOperacao();
    }

    /**
     * {@inheritDoc}
     */
    public function checkOrigemGravacao()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'checkOrigemGravacao', array());

        return parent::checkOrigemGravacao();
    }

    /**
     * {@inheritDoc}
     */
    public function checkTipo()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'checkTipo', array());

        return parent::checkTipo();
    }

    /**
     * {@inheritDoc}
     */
    public function checkSituacao()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'checkSituacao', array());

        return parent::checkSituacao();
    }

    /**
     * {@inheritDoc}
     */
    public function onFlush(\Doctrine\ORM\Event\OnFlushEventArgs $args)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'onFlush', array($args));

        return parent::onFlush($args);
    }

    /**
     * {@inheritDoc}
     */
    public function postFlush(\Doctrine\ORM\Event\PostFlushEventArgs $args)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'postFlush', array($args));

        return parent::postFlush($args);
    }

    /**
     * {@inheritDoc}
     */
    public function populate($data = array (
))
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'populate', array($data));

        return parent::populate($data);
    }

    /**
     * {@inheritDoc}
     */
    public function getInputFilter()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getInputFilter', array());

        return parent::getInputFilter();
    }

    /**
     * {@inheritDoc}
     */
    public function removeInputFilter($input)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeInputFilter', array($input));

        return parent::removeInputFilter($input);
    }

    /**
     * {@inheritDoc}
     */
    public function getArrayCopy()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getArrayCopy', array());

        return parent::getArrayCopy();
    }

    /**
     * {@inheritDoc}
     */
    public function setInputFilter(\Zend\InputFilter\InputFilterInterface $inputFilter)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setInputFilter', array($inputFilter));

        return parent::setInputFilter($inputFilter);
    }

    /**
     * {@inheritDoc}
     */
    public function setData($data)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setData', array($data));

        return parent::setData($data);
    }

    /**
     * {@inheritDoc}
     */
    public function getData()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getData', array());

        return parent::getData();
    }

}