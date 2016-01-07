<?php
/**
 * Created by PhpStorm.
 * User: eduardojunior
 * Date: 06/01/16
 * Time: 11:18
 */
namespace Escola\Filter;

use Zend\Filter\AbstractFilter;
use Zend\Filter\FilterInterface;

class FormulaMedia extends AbstractFilter implements FilterInterface
{
    /**
     * @var array
     */
    protected $list = array();

    /**
     * @param null|array|Traversable $options
     */
    public function __construct($options = null)
    {
        if (null !== $options) {
            $this->setOptions($options);
        }
    }

    /**
     * Get the list of items to token-list
     *
     * @return array
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * Set the list of items to token-list.
     *
     * @param array|Traversable $list
     */
    public function setList($list = array())
    {
        if (!is_array($list)) {
            $list = ArrayUtils::iteratorToArray($list);
        }

        $this->list = $list;
    }

    public function filter($value)
    {
        //tenho uma lista
        //se passar alguma palavra fora da lista retorna null
        $tokens = array_map(null, explode(' ', $value));

        foreach ($tokens as $token){
            $check = in_array($token, $this->getList());
            if (!$check)
                return null;
        }

        return $value;

    }
}