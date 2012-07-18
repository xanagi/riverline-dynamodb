<?php

namespace Riverline\DynamoDB;

/**
 * @class
 */
class AttributeUpdate implements \ArrayAccess, \IteratorAggregate
{
    /**
     * The update actions
     * @var array
     */
    private $actions = array();
    
    /**
     * @param string $name
     * @param \Riverline\DynamoDB\UpdateAction $action
     */
    public function setAttribute($name, $action)
    {
        $this->actions[$name] = $action;
    }

    /**
     * @see \ArrayAccess
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->actions[$offset]);
    }

    /**
     * @see \ArrayAccess
     * @param $offset
     * @return null
     */
    public function offsetGet($offset)
    {
        return (isset($this->actions[$offset])?$this->actions[$offset]:null);
    }

    /**
     * @see \ArrayAccess
     * @param $offset
     * @param $value
     * @throws Exception\AttributesException
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            throw new Exception\AttributesException('Square bracket syntax isn\'t allowed');
        }

        $this->setAttribute($offset, $value);
    }

    /**
     * @see \ArrayAccess
     * @param $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->actions[$offset]);
    }

    /**
     * @see \IteratorAggregate
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->actions);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param string|null $type
     */
    public function setPutAction($name, $value, $type = null)
    {
        $action = new UpdateAction(\AmazonDynamoDB::ACTION_PUT, $value, $type);
        $this->actions[$name] = $action;
    }

    /**
     * @param string $name
     */
    public function setDeleteAction($name)
    {
        $action = new UpdateAction(\AmazonDynamoDB::ACTION_DELETE);
        $this->actions[$name] = $action;
    }

    /**
     * @param string $name
     * @param mixed $value
     * @param string|null $type
     */
    public function setAddAction($name, $value, $type = null)
    {
        $action = new UpdateAction(\AmazonDynamoDB::ACTION_ADD, $value, $type);
        $this->actions[$name] = $action;
    }

}