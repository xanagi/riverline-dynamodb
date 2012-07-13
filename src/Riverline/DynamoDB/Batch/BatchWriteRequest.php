<?php

namespace Riverline\DynamoDB\Batch;

/**
 * @class
 */
class BatchWriteRequest implements \ArrayAccess
{
    /**
     * @var array
     */
    protected $requestItems;

    /**
     * @var string $table
     * @var WriteRequestItem $requestItem
     */
    public function setRequestItem($table, WriteRequestItem $requestItem)
    {
        $this->requestItems[$table] = $requestItem;
    }
 
    /**
     * @var mixed $offset
     */
    public function offsetExists($offset)
    {
        return isset($this->requestItems[$offset]);
    }

    /**
     * @var mixed $offset
     */
    public function offsetGet($offset)
    {
        if (!isset($this->requestItems[$offset])) {
            $this->requestItems[$offset] = new WriteRequestItem();   
        }
        return $this->requestItems[$offset];
    }

    /**
     * @var mixed $offset
     * @var mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            throw new \Exception('Square bracket syntax isn\'t allowed');
        }
        $this->setRequestItem($offset, $value);
    }
    
    /**
     * @var mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->requestItems[$offset]);
    }

    /**
     * @return boolean
     */
    public function isEmpty()
    {
        if (!empty($this->requestItems)) {
            foreach ($this->requestItems as $requestItem) {
                if (!$requestItem->isEmpty()) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Return a write get request item formated for DynamoDB
     * @return array
     */
    public function getForDynamoDB()
    {
        $parameters = array();
        foreach ($this->requestItems as $table => $writeRequestItem) {
            $parameters[$table] = $writeRequestItem->getForDynamoDB();
        }
        return $parameters;
    }
}