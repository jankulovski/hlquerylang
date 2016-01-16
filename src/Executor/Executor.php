<?hh // strict

namespace hlql\Executor;

use hlql\Query\Query;

/**
 * Executor
 */
abstract class Executor {

    /**
     * Get
     *
     * @param mixed $query
     * @return void
     */
    public function get(mixed $query): void {
        if(is_array($query)) {
            $query_ = new Query($query);
        }
        invariant(($query instanceof Query), "Expected Query");
        if($query->hasFields()) {
            $fields = $query->getFields();
            invariant(($fields instanceof Set), "Expected Set");
            $this->_get($fields);
        }

        if($query->hasEdges()) {
            $edges = $query->getEdges();
            invariant(($edges instanceof Traversable), "Expected Traversable");
            foreach($edges as $edge) {
                $reflectionMethod = new \ReflectionMethod(
                    get_class($this), $edge->getName()
                );
                $eobj = $reflectionMethod->invoke($this);
                $eobj->get(new Query($edge->__toMap()));
            }
        }
    }

    /**
     * _Get
     *
     * @param Set<string> $fields
     * @return void
     */
    abstract protected function _get(Set<string> $fields) : void;
}
