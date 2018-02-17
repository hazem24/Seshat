<?php
        namespace Framework\Lib\DataBase\DataMapper\Collections;
        use Framework\ConstructorClass as ConstructorClass;
        use Framework\Exception\DbException as DbException;
        use App\Model\Model as Model;
        use App\DomainMapper\DomainObjectFactory\DomainObjectFactory as DomainObject;
        

abstract class AbstractGeneratorCollection extends ConstructorClass
{
    protected $domainObject = null;
    protected $total = 0;
    protected $raw = [];
    private $result;
    private $pointer = 0;
    private $objects = [];
    public function __construct(array $raw = [], DomainObject $dObject = null)
    {
        $this->raw = $raw;
        $this->total = count($raw);
        if (count($raw) && is_null($dObject)) {
            throw new DbException("need Factory to generate objects");
        }
        $this->domainObject = $dObject;
    }
    public function add(Model $object)
    {
        $class = $this->targetClass();
        if (! ($object instanceof $class )) {
            throw new DbException("This is a {$class} collection");
        }
        $this->notifyAccess();
        $this->objects[$this->total] = $object;
        $this->total++;
    }
    public function getGenerator()
    {
        for ($x = 0; $x < $this->total; $x++) {
            yield $this->getRow($x);
        }
    }
    abstract public function targetClass(): string;
    protected function notifyAccess()
    {
        // deliberately left blank!
        //This Function Will Be OverRide If I Use Lazy Load Pattern
    }
    private function getRow($num)
    {
        $this->notifyAccess();
        if ($num >= $this->total || $num < 0) {
            return null;
        }
        if (isset($this->objects[$num])) {
            return $this->objects[$num];
        }
        if (isset($this->raw[$num])) {
            $this->objects[$num] = $this->domainObject->createObject($this->raw[$num]);
            return $this->objects[$num];
        }
    }
}
