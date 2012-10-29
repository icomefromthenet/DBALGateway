<?php
namespace DBALGateway\Table;

/**
  *  Events Definition for Table
  *
  *  @author Lewis Dyer <getintouch@icomefromthenet.com>
  *  @since 0.0.1
  */
final class TableEvents
{
    /**
     * The pre.initialize event is called before an new table is initialized.
     *
     * The event listener receives an \DBALGateway\TableEvent instance.
     *
     * @var string
     */
    const PRE_INITIALIZE = 'pre.initialize';
    
    /**
     * The post.initialize event is called after a new table is initialized.
     *
     * The event listener receives an \DBALGateway\TableEvent instance.
     *
     * @var string
     */
    const POST_INITIALIZE = 'post.initialize';
    
    /**
     * The pre.select event is called before a select query is executed.
     *
     * The event listener receives an \DBALGateway\TableEvent instance.
     *
     * @var string
     */
    const PRE_SELECT = 'pre.select';
    
    
    /**
     * The post.select event is called after a select query been executed.
     *
     * The event listener receives an \DBALGateway\TableEvent instance.
     *
     * @var string
     */
    const POST_SELECT = 'post.select';
    
    /**
     * The pre.update event is called before an update query is executed.
     *
     * The event listener receives an \DBALGateway\TableEvent instance.
     *
     * @var string
     */
    const PRE_UPDATE = 'pre.update';
    
    
    /**
     * The post.update event is called after a update query been executed.
     *
     * The event listener receives an \DBALGateway\TableEvent instance.
     *
     * @var string
     */
    const POST_UPDATE = 'post.update';
    
    /**
     * The pre.insert event is called before an insert query is executed.
     *
     * The event listener receives an \DBALGateway\TableEvent instance.
     *
     * @var string
     */
    const PRE_INSERT = 'pre.insert';
    
    
    /**
     * The post.insert event is called after an insert query been executed.
     *
     * The event listener receives an \DBALGateway\TableEvent instance.
     *
     * @var string
     */
    const POST_INSERT = 'post.insert';
    
    
    /**
     * The pre.delete event is called before a delete query is executed.
     *
     * The event listener receives an \DBALGateway\TableEvent instance.
     *
     * @var string
     */
    const PRE_DELETE = 'pre.delete';
    
    
    /**
     * The post.delete event is called after a delete query been executed.
     *
     * The event listener receives an \DBALGateway\TableEvent instance.
     *
     * @var string
     */
    const POST_DELETE = 'post.delete';
    
}

/* End of File */