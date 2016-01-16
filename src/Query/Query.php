<?hh // strict

namespace hlql\Query;

use hlql\Query\QueryCore;

/**
 * Query
 */
class Query extends QueryCore {

    /**
     * @var const string _fields
     */
    const string _fields = "fields";

    /**
     * @var const string _edges
     */
    const string _edges = "edges";

    /**
     * @var const string _args
     */
    const string _args = "args";

    /**
     * Constructor
     *
     * @param KeyedContainer<string, mixed> $query
     * @return void
     */
    public function __construct(
        KeyedContainer<string, mixed> $query
    ): void {

        $nq = Query::__toMap($query);
        $query = null;
        $key = $nq->firstKey();
        $value = $nq->firstValue();

        invariant(
            ($value instanceof KeyedIterable),
            'Invalid query structure. Expected KeyedContainer'
        );
        invariant(is_string($key), 'Invalid query name type. Expected String');
        parent::__construct($key, $value);
    }

    /**
     * Query to KeyedIterable
     *
     * @param KeyedContainer<string, mixed> $query
     * @return Map<string, mixed>
     */
    public static function __toMap(KeyedContainer<string, mixed> $query):
        Map<string, mixed>
    {
        $ki = Map {};
        foreach($query as $qk => $qv) {
            if ($qv instanceof KeyedContainer) {
                $ki->set($qk, Query::__toMap($qv) );
            } else {
                $ki->set($qk, $qv);
            }
        }
        return $ki;
    }

}
