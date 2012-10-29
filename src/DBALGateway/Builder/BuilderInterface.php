<?php
namespace DBALGateway\Builder;

/**
  *  Interface for entity builders
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
interface BuilderInterface
{
    /**
      *  Convert data array into entity
      *
      *  @return mixed
      *  @param array $data
      *  @access public
      */
    public function build($data);
    
    /**
      *  Convert and entity into a data array
      *
      *  @return array
      *  @access public
      */
    public function demolish($entity);
    
}

/* End of File */