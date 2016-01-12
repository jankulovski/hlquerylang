<?hh // strict

namespace hlql\Query;

use hlql\Edge\Edge;

type QueryFields = Set<string>;
type QueryField = string;
type QueryEdges = Map<string,QueryEdge>;
type QueryEdge = Vector<mixed>;
type QueryEdgeName = string;

type EdgeName = string;
type EdgeFields = Set<string>;
type EdgeEdges = Map<string, Edge>;
type EdgeField = string;
type EdgeArguments = Vector<Pair<string, string>>;
type EdgeArgument = Pair<string, string>;

enum _query : string as string {
    fields = "fields";
    edges = "edges";
    args = "args";
}

abstract class QueryParser {

    /**
     * @var Edge $edge Edge
     */
    private Edge $edge;

    /**
     * Constructor
     *
     * @param EdgeName $name Edge's name
     * @param KeyedIterable<string, mixed> $query Query
     * @return void
     */
    public function __construct(
        EdgeName $name,
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
            if($sqk === _query::fields) {
                invariant(($sqv instanceof Container), "Invalid attribute $sqk");
                $this->parseFields($edge, $sqv);
            } else if($sqk === _query::edges) {
                invariant(($sqv instanceof KeyedIterable), "Invalid attribute $sqk");
                $this->parseEdges($edge, $sqv);
            }
            else if($sqk === _query::args) {
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
     * @return EdgeName
     */
    public function getName(): EdgeName {
        return $this->edge->getName();
    }

    /**
     * Get query fields
     *
     * @return QueryFields
     */
    public function getFields(): ? EdgeFields {
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
     * @return QueryEdges
     */
    public function getEdges(): ? EdgeEdges {
        return $this->edge->getEdges();
    }

    /**
     * Get query arguments
     *
     * @return QueryArguments
     */
    public function getArguments(): ? EdgeArguments {
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
