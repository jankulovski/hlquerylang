<?hh // strict

namespace hlql\Query;

use hlql\Edge\Edge;

abstract class QueryCore {

    /**
     * @var Edge $edge Edge
     */
    private Edge $edge;

    /**
     * Constructor
     *
     * @param string $name Edge's name
     * @param KeyedIterable<string, mixed> $query Query
     * @return void
     */
    public function __construct(
        string $name,
        KeyedIterable<string, mixed> $query
    ): void {
        $this->edge = new Edge($name);
        $this->parse($this->edge, $query);
    }

    /**
     * Parse query
     *
     * @param Edge $edge
     * @param KeyedIterable<string, mixed> $query
     * @return Edge
     */
    private function parse(Edge $edge, KeyedIterable<string, mixed> $query): Edge {
        foreach($query as $sqk => $sqv) {
            if($sqk === Query::_fields) {
                invariant(($sqv instanceof Container), "Invalid attribute $sqk");
                $this->parseFields($edge, $sqv);
            } else if($sqk === Query::_edges) {
                invariant(($sqv instanceof KeyedIterable), "Invalid attribute $sqk");
                $this->parseEdges($edge, $sqv);
            }
            else if($sqk === Query::_args) {
                invariant(($sqv instanceof KeyedIterable), "Invalid attribute $sqk");
                $this->parseArgs($edge, $sqv);
            } else {
                throw new \Exception("Invalid attribute $sqk");
            }
        }
        return $edge;
    }

    /**
     * Parse fields
     *
     * @param Edge $edge
     * @param Container<string> $fields
     * @return void
     */
    private function parseFields(Edge $edge, Container<string> $fields): void {
        foreach($fields as $field) {
            $edge->addField((string)$field);
        }
    }

    /**
     * Parse Edges
     *
     * @param Edge $edge
     * @param KeyedIterable<string, KeyedIterable<string, mixed>> $edges
     * @return void
     */
    private function parseEdges(
        Edge $edge,
        KeyedIterable<string, KeyedIterable<string, mixed>> $edges
    ): void {
        foreach($edges as $fk => $fv) {
            $edge->addEdge( $this->parse(new Edge($fk), $fv) );
        }
    }

    /**
     * Parse Arguments
     *
     * @param Edge $edge
     * @param KeyedIterable<string, string> $fields
     * @return void
     */
    private function parseArgs(Edge $edge, KeyedIterable<string, string> $args): void {
        foreach($args as $fk => $fv) {
            $edge->addArgument(Pair {(string)$fk, (string)$fv});
        }
    }

    /**
     * Get query name
     *
     * @return string
     */
    public function getName(): string {
        return $this->edge->getName();
    }

    /**
     * Get query fields
     *
     * @return ? Set<string>
     */
    public function getFields(): ? Set<string> {
        return $this->edge->getFields();
    }

    /**
     * Get query's root edge
     *
     * @return Edge
     */
    public function getEdge(): Edge {
        return $this->edge;
    }

    /**
     * Get query edges
     *
     * @return ? Map<string, Edge>
     */
    public function getEdges(): ? Map<string, Edge> {
        return $this->edge->getEdges();
    }

    /**
     * Get query arguments
     *
     * @return ? Vector<Pair<string, string>>
     */
    public function getArguments(): ? Vector<Pair<string, string>> {
        return $this->edge->getArguments();
    }

    /**
     * Has fields
     *
     * @return bool
     */
    public function hasFields(): bool {
        return $this->edge->hasFields();
    }

    /**
     * Has edges
     *
     * @return bool
     */
    public function hasEdges(): bool {
        return $this->edge->hasEdges();
    }

    /**
     * Has arguments
     *
     * @return bool
     */
    public function hasArguments(): bool {
        return $this->edge->hasArguments();
    }

}
