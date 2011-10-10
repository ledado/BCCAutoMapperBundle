<?php

namespace BCC\AutoMapperBundle\Mapper;

use Symfony\Component\Form\Util\PropertyPath;
use BCC\AutoMapperBundle\Mapper\FieldAccessor\Simple;
use BCC\AutoMapperBundle\Mapper\FieldAccessor\FieldAccessorInterface;
use BCC\AutoMapperBundle\Mapper\FieldFilter\FieldFilterInterface;

/**
 * AbstractMap returns a value for a member given a property path
 *
 * @author Michel Salib <michelsalib@hotmail.com>
 */
abstract class AbstractMap implements MapInterface
{
    
    protected $fieldAccessors = array();
    protected $fieldFilters = array();

    /**
     * Associate a member to another member given their property pathes.
     * 
     * @param string $destinationMember
     * @param string $sourceMember
     * @return AbstractMap 
     */
    public function route($destinationMember, $sourceMember)
    {
        $this->fieldAccessors[$destinationMember] = new Simple($sourceMember);
        
        return $this;
    }
    
    /**
     * Applies a field accessor policy to a member.
     * 
     * @param string $destinationMember
     * @param FieldAccessorInterface $fieldMapper
     * @return AbstractMap 
     */
    public function forMember($destinationMember, FieldAccessorInterface $fieldMapper)
    {
        $this->fieldAccessors[$destinationMember] = $fieldMapper;
        
        return $this;
    }
    
    /**
     * Applies a filter to the field.
     * 
     * @param string $destinationMember
     * @param FieldFilterInterface $fieldFilter 
     */
    public function filter($destinationMember, FieldFilterInterface $fieldFilter)
    {
        $this->fieldFilters[$destinationMember] = $fieldFilter;
        
        return $this;
    }

    /**
     * Builds the default map using property names.
     * 
     * @return AbstractMap 
     */
    public function buildDefaultMap()
    {
        $reflectionClass = new \ReflectionClass($this->getDestinationType());
        
        foreach ($reflectionClass->getProperties() as $property) {
            $this->fieldAccessors[$property->name] = new Simple($property->name);
        }
        
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getFieldAccessors()
    {
        return $this->fieldAccessors;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getFieldFilters() {
        return $this->fieldFilters;
    }
}