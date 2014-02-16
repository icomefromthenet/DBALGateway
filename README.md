# DBALGateway - Table Gateway for Doctrine DBAL.
[![Build Status](https://travis-ci.org/icomefromthenet/DBALGateway.png?branch=master)](https://travis-ci.org/icomefromthenet/DBALGateway)

[Doctrine DBAL](http://www.doctrine-project.org/projects/dbal.html) is a fantastic extension to PDO but writing CRUD code for simple 1 to 1 mappings still represents a time sink. This component implements [Table Gateway](http://martinfowler.com/eaaCatalog/tableDataGateway.html) on top of Doctrine DBAL and is heavily inspired by zf2 Table Gateway.

## What are the benefits?
1. Using metadata the gateway will convert values, for example DateTime is converted to a stamps and the stamp is converted back to DateTime. 
2. Events system, e.g pre_select , post_delete , pre_insert... based around the symfony2 event dispatcher.
3. Query Logger using Monolog, you would normally write this yourself.
4. Builder can map records to entities, you could build an active record on top of this gateway.
5. Supply a collection class and it will load them into it.
6. Fluid interface for running selects, inserts, updates and deletes.
7. Faster than manual CRUD.

## Whats are the cons?
1. Loose auto-completion in your IDE, for subclasses, only get it for the bases classes.
2. Overhead a little more memory and extra method calls.  


## Install

This component can be installed via composer.

```json
{
    "require" : {
        "icomefromthenet/dbal-gateway" : "dev-master",
    }

}

```

## Usage.

There are 3 components to every table.

1. Metadata instanceof ``DBALGateway\Metadata\Table``.
2. Subclass of ``DBALGateway\Table\AbstractTable`` the gateway.
3. Subclass of ``DBALGateway\Query\AbstractQuery`` the query class.

There are 2 optional components to every table

4. Custom result-set implementation of ``Doctrine\Common\Collections\Collection``.
5. Entity builder implementation of ``DBALGateway\Builder\BuilderInterface``.

### 1. The Metadata

Assume have the following databse table.

```sql
delimiter $$

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `dte_created` datetime NOT NULL,
  `dte_updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci$$

```

You can declare the metadata as follows.

```php
<?php
namespace ExampleFile

use DBALGateway\Metadata\Table;

call_user_func(function(){

    # create the table object
    $table = new Table('users');
    
    # assign normal columns 
    $table->addColumn('id',"integer", array("unsigned" => true));
    $table->addColumn('username', "string", array("length" => 32));
    $table->addColumn('first_name', "string", array("length" => 45));
    $table->addColumn('last_name',"string", array("length" => 45));
    $table->addColumn('dte_created','datetime');
    $table->addColumn('dte_updated','datetime');
    $table->setPrimaryKey(array("id"));

    # assign virtual columns, these are perfect for calulated values.
    # these columns can not be inserted but will be converted if found in a result-set.
    $table->addVirtualColumn('uptime',"datetime");
    
    return $table;    
});


```

The datatypes (second argument in addColumn) are **not mysql types** but **doctrine types**, the mapping can be found under ``Doctrine\DBAL\Platforms\{MYPLATFORM}``.

### 2. The Table Gateway.

You will need to declare a subclass of ``DBALGateway\Metadata\Table`` and override the method ``newQueryObject()``.

```php
<?php
namespace DBALGateway\Tests\Base\Mock;

use DBALGateway\Table\AbstractTable;

class MockUserTableGateway extends AbstractTable
{
    /**
      *  Create a new instance of the querybuilder
      *
      *  @access public
      *  @return QueryBuilder
      */
    public function newQueryBuilder()
    {
        return new MockUserQuery($this->adapter,$this);
    }

}

```

You may also include other custom code on this class. But note all methods a marked as ``protected`` be careful with naming.

### 3. The QueryClass

You will need to subclass ``DBALGateway\Query\AbstractQuery`` which is itself a subclass of ``Doctrine\DBAL\Query\QueryBuilder``.

```php
<?php
namespace DBALGateway\Tests\Base\Mock;

use DBALGateway\Query\AbstractQuery;
use DateTime;

class MockUserQuery extends AbstractQuery
{
    
    public function filterByUser($id)
    {
        $this->where('id = :id')->setParameter('id', $id, $this->getGateway()->getMetaData()->getColumn('id')->getType());
        return $this;
    }
    
    public function filterByUsername($name)
    {
        $this->where('username = :username')->setParameter('username', $id, $this->getGateway()->getMetaData()->getColumn('username')->getType());
        
        return $this;
    }
    
    
    public function filterByDateCreated(DateTime $created)
    {
        $this->where('dte_created = :dte_created')->setParameter('dte_created', $id, $this->getGateway()->getMetaData()->getColumn('dte_created')->getType());
        
        return $this;
    }
    
    public function filterByDateUpdated(DateTime $updated)
    {
        $this->where('dte_updated = :dte_updated')->setParameter('dte_updated', $id, $this->getGateway()->getMetaData()->getColumn('dte_updated')->getType());
        
        return $this;
    }
    
}

```

**Each custom filter should do the following.**

1. Set a unique named parameter.
2. Set the parameter value and fetch the doctrine type from the meta-data in the table gateway.
3. Return $this.

### 4. The Collections class.

When using ``find()`` on the gateway results will be stored in a collection class offerd by doctrine `Doctrine\Common\Collections\ArrayCollection`. If the gateway's constructor is passed an instance an alternative that inherits the interface from `Doctrine\Common\Collections\Collection` it will clone a copy and use that.

```php

use Doctrine\Common\Collections\Collection;


class CustomCollection implements Collection
{
 .....

}

$collection = new CustomCollection();

$gateway = new MockUserGateway('user',$conn,$event,$meta,$collection,null);

```

This gateway will clone ``$collection`` on each call to ``find``. **Note:** ``findOne()`` does not return collection just entity/array.



### 5. The Entity Builder

When using ``find()`` or ``findOne()`` each result found in the set will be passed to the builder for conversion into an entity. A builder must implement the interface found at ``DBALGateway\Builder\BuilderInterface``.

```php
use DBALGateway\Builder\BuilderInterface;

class EntityBuilder implements BuilderInterface
{
     /**
      *  Convert data array into entity
      *
      *  @return mixed
      *  @param array $data
      *  @access public
      */
    public function build($data)
    {
        $user = new UserEntity();
        
        $user->id = $data['id'];
        $user->username = $data['username'];
        
        ... etc
        
        return $user;
        
    }
    
    /**
      *  Convert and entity into a data array
      *
      *  @return array
      *  @access public
      */
    public function demolish($entity)
    {
    
    }

}

$builder = new EntityBuilder();
$gateway = new MockUserGateway('users',$conn,$event,$meta,null,$builder);

```

**Note:** If a collection class is used this new entity will be given to the collection.

## Running Queries

### Run an INSERT Query.

```php
$gateway = new MockUserGateway('users',$conn,$event,$meta);

$success = $gateway->insertQuery()
             ->start()
                ->addColumn('username','ausername')
                ->addColumn('first_name','myfname')
                ->addColumn('last_name','mylname')
                ->addColumn('dte_created',new DateTime())
                ->addColumn('dte_updated',DateTime())
             ->end()
           ->insert(); 

if($success) {
 
 $id = $gateway->lastInsertId();
 
}

```


### Run an UPDATE Query.

```php
$gateway = new MockUserGateway('users',$conn,$event,$meta);

$success = $gateway->updateQuery()
             ->start()
                ->addColumn('username','ausername')
                ->addColumn('first_name','myfname')
                ->addColumn('last_name','mylname')
                ->addColumn('dte_created',new DateTime())
                ->addColumn('dte_updated',DateTime())
             ->where()
                ->filterByUser(101)
             ->end()
           ->update(); 

if($success) {
 
 echo 'table row was updated';
 
}

```

### Run a DELETE Query.

```php
$gateway = new MockUserGateway('users',$conn,$event,$meta);

$success = $gateway->deleteQuery()
             ->start()
                ->filterByUser(1)
             ->end()
           ->delete(); 

if($success) {
 
 echo 'table row was removed';
 
}

```

### Run a SELECT Query.

There are two methods ``findOne()`` and ``find()``.

```php
$gateway = new MockUserGateway('users',$conn,$event,$meta);

$result = $gateway->selectQuery()
             ->start()
                ->filterByUser(1)
             ->end()
           ->findOne(); 

if($result !== null) {
    echo $result['id'];
    echo $result['username'];
    echo $result['dte_created']->format('U');
}

```


```php
$gateway = new MockUserGateway('users',$conn,$event,$meta);

$result = $gateway->selectQuery()
             ->start()
                ->filterByUser(1)
             ->end()
           ->find(); 

if($result !== null) {
    echo $result[0]['id'];
    echo $result[0]['username'];
    echo $result[0]['dte_created']->format('U');
}

```

## Instance a Gateway?

A Gateway has the following dependecies.

1. The table name in the schema.
2. The `Doctrine\DBAL\Connection` $connection.
3. An instance of `Symfony\Component\EventDispatcher\EventDispatcherInterface`.
4. The meta data for table instance of `DBALGateway\Metadata\Table`.
5. (optional) a result-set to clone an instance class that implements `Doctrine\Common\Collections\Collection`
6. (optional) a enity builder an instance class that implements `DBALGateway\Builder\BuilderInterface`

``` php
 new MockUserGateway('users',$conn,$event,$meta,$result_set,$builder);

```

## Features and Events.

The Gateway emits a number of events.

<table>
 <tr>
  <th>
    Event Name
  </th>
  <th>
    Event Description
  </th>
 </tr>
 <tr>
  <td>
    pre_initilize   
  </td>
  <td>
    Occurs during object construction.
  </td>
 </tr>
  <tr>
  <td>
    post_initilize   
  </td>
  <td>
    Occurs after object construction.
  </td>
 </tr>
 <tr>
  <td>
    pre_select
  </td>
  <td>
    Occurs before a select query is run.
  </td>
 </tr>
 <tr>
  <td>
    post_select
  </td>
  <td>
    Occurs after a select query is run.
  </td>
 </tr>
 <tr>
  <td>
    pre_delete
  </td>
  <td>
    Occurs before delete query is run.
  </td>
 </tr>
 <tr>
  <td>
    post_delete
  </td>
  <td>
    Occurs after delete query is run.
  </td>
 </tr>
 <tr>
  <td>
    pre_insert
  </td>
  <td>
    Occurs before an insert is run.
  </td>
 </tr>
  <tr>
  <td>
    post_insert
  </td>
  <td>
    Occurs after an insert is run.
  </td>
 </tr>
  <tr>
  <td>
    pre_update
  </td>
  <td>
    Occurs before an update is run.
  </td>
 </tr>
  <tr>
  <td>
    post_update
  </td>
  <td>
    Occurs after an update is run.
  </td>
 </tr>  
</table>

For an example see the [BufferedQueryLogger](http://github.com/icomefromthenet/DBALGateway/blob/master/src/DBALGateway/Feature/BufferedQueryLogger.php).

