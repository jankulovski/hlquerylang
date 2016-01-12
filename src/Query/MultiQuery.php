<?hh // strict

namespace hlql\Query;

use hlql\Query\Query;

/**
 * Query
 */
class MultiQuery {

    /**
     * @var Vector<Query> $queries
     */
    private Vector<Query> $queries = Vector {};

    /**
     * Constructor
     *
     * @param
     * @return void
     */
    public function __construct(KeyedContainer<string, mixed> $query): void {
        foreach($query as $sqk => $sqv) {
            $this->queries->add(new Query([$sqk => $sqv]));
        }
    }

}
