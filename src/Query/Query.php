<?hh // strict

namespace hlql\Query;

use hlql\Query\QueryParser;

/**
 * Query
 */
class Query extends QueryParser {

    /**
     * @var const string _fields
     */
    const string _fields = "fields";
    const string _edges = "edges";
    const string _args = "args";

    /**
     * Constructor
     *
     * @param
     * @return void
     */
    public function __construct(
        KeyedContainer<string, mixed> $query
    ): void {

        $nq = Query::__toMap($query);
        $query = null;
        $key = $nq->firstKey();
        $value = $nq->firstValue();

        # Invariant shouldn't reach the fail point at any given time, since
        # Query::__toMap() returns an KeyedIterable, but need to convince the
        # typechecker we have the right data structure.
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
