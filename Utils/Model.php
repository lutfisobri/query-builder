<?php
namespace Riyu\Database\Utils;

use Riyu\Database\Utils\Query\Builder;

abstract class Model
{
    /**
     * Name of table in database
     * 
     * @var string
     */
    protected $table;

    /**
     * Fillable column in database
     * 
     * @var array
     */
    protected $fillable;

    /**
     * Prefix table
     * 
     * @var string
     */
    protected $prefix;

    /**
     * Timestamp column
     * 
     * @var bool
     */
    protected $timestamp;

    /**
     * Created at column
     * 
     * @var string
     */
    const CREATED_AT = 'created_at';

    /**
     * Updated at column
     * 
     * @var string
     */
    const UPDATED_AT = 'updated_at';

    /**
     * Primary key column default is id
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Attributes
     * 
     * @var array
     */
    protected $attributes = [];

    /**
     * @var Riyu\Database\Events\Builders
     */
    protected $builder;

    public function __construct(array $attributes = [])
    {
        $this->builder = new Builder;
        $this->setAttributes($attributes);
        $this->booting();
    }

    /**
     * Booting model
     * 
     * @return void
     */
    public function booting()
    {
        $config = [
            'table' => $this->table,
            'fillable' => $this->fillable,
            'prefix' => $this->prefix,
            'timestamp' => $this->timestamp,
            'primaryKey' => $this->primaryKey,
            'created_at' => self::CREATED_AT,
            'updated_at' => self::UPDATED_AT
        ];
        $this->builder->setConfig($config);
        $this->setTimestamp();
    }

    /**
     * Set attributes
     * 
     * @param array $attributes
     * @return void
     */
    public function setAttributes(array $attributes)
    {
        if (is_null($this->table)) {
            $this->table = strtolower((new \ReflectionClass($this))->getShortName());
        }

        if (!is_null($this->prefix)) {
            $this->table = $this->prefix . $this->table;
        }

        foreach ($attributes as $key => $value) {
            $this->setAttribute($key, $value);
        }
    }

    /**
     * Set attribute
     * 
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setAttribute($key, $value)
    {
        $this->attributes[$key] = $value;
        $this->{$key} = $value;
    }

    /**
     * Get attribute
     * 
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        return $this->{$key};
    }

    /**
     * Get all attributes
     * 
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set timestamp
     * 
     * @return void
     */
    private function setTimestamp()
    {
        if ($this->timestamp) {
            $this->builder->setTimestamp(array(
                'created_at' => self::CREATED_AT,
                'updated_at' => self::UPDATED_AT,
            ));
        }
    }

    /**
     * Build query with attributes
     * 
     * @return void
     */
    public function save()
    {
        // get all property
        $this->getProperty();

        // build query insert with attributes
        return $this->execInsert($this->attributes);
    }

    /**
     * Get all property
     * 
     * @return void
     */
    public function getProperty()
    {
        // get all property
        $property = get_object_vars($this);

        $primaryKey = $this->primaryKey;

        // default property
        $default = [
            'table',
            'fillable',
            'prefix',
            'timestamp',
            'primaryKey',
            'attributes',
            'builder',
        ];

        // remove default property
        $property = array_diff_key($property, array_flip($default));


        // remove object property
        $property = array_filter($property, function ($value) {
            return !is_object($value);
        });

        // set attributes
        $this->attributes = array_merge($this->attributes, $property);
    }

    public function __call($method, $arguments)
    {
        new static;
        if (method_exists($this, $method)) {
            return $this->$method(...$arguments);
        }

        if (method_exists($this->builder, $method)) {
            return $this->builder->$method(...$arguments);
        }
    }

    public static function __callStatic($method, $arguments)
    {
        return (new static)->$method(...$arguments);
    }

    public function __get($name)
    {
        return $this->$name;
    }

    public function __set($name, $value)
    {
        $this->setAtr($name, $value);
    }

    public function setAtr($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function __invoke()
    {
        return $this;
    }

    public function __toString()
    {
        return $this->toJson();
    }

    public function __debugInfo()
    {
        return $this->attributes;
    }

    public function toJson()
    {
        return json_encode($this->attributes);
    }
}
