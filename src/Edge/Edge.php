<?hh // strict

namespace hlql\Edge;

use hlql\Query\Query;

class Edge {

    /**
     * @var string $name Edge's name
     */
    private string $name = "";

    /**
     * @var ? Set<string> $fields Edge's fields
     */
    private ? Set<string> $fields;

    /**
     * @var ? Map<string, Edge> $edges Edge's edges
     */
    private ? Map<string, Edge> $edges;

    /**
     * @var ? Vector<Pair<string, string>> $args Edge's arguments
     */
    private ? Vector<Pair<string, string>> $args;

    /**
     * Constructor
     *
     * @param string $name Edge's name
     * @return void
     */
    public function __construct(string $name) {
        $this->name = $name;
    }

    /**
     * Get Edge's name
     *
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Get Edge's edges
     *
     * @return ? Map<string, Edge>
     */
    public function getEdges(): ? Map<string, Edge> {
        return $this->edges;
    }

    /**
     * Get Edge's fields
     *
     * @return ? Set<string>
     */
    public function getFields(): ? Set<string> {
        return $this->fields;
    }

    /**
     * Get Edge's arguments
     *
     * @return ? Vector<Pair<string, string>>
     */
    public function getArguments(): ? Vector<Pair<string, string>> {
        return $this->args;
    }

    /**
     * Add field
     *
     * @param string $field
     * @return void
     */
    public function addField(string $field): void {
        if(is_null($this->fields)) {
            $this->fields = Set {};
        }
        $this->fields->add($field);
    }

    /**
     * Add edge
     *
     * @param Edge $edge
     * @return void
     */
    public function addEdge(Edge $edge): void {
        if(is_null($this->edges)) {
            $this->edges = Map {};
        }
        $this->edges->set($edge->getName(), $edge);
    }

    /**
     * Add argument
     *
     * @param Pair<string, string> $argument
     * @return void
     */
    public function addArgument(Pair<string, string> $argument): void {
        if(is_null($this->args)) {
            $this->args = Vector {};
        }
        $this->args->add($argument);
    }

    /**
     * Has fields
     *
     * @return bool
     */
    public function hasFields(): bool {
        return $this->fields !== null && !$this->fields->isEmpty() ? true : false;
    }

    /**
     * Has edges
     *
     * @return bool
     */
    public function hasEdges(): bool {
        return $this->edges !== null && !$this->edges->isEmpty() ? true : false;
    }

    /**
     * Has arguments
     *
     * @return bool
     */
    public function hasArguments(): bool {
        return $this->args !== null && !$this->args->isEmpty() ? true : false;
    }

    /**
     * Edge to map
     *
     * @return Map<string, mixed>
     */
    public function __toMap(): Map<string, mixed> {
        return Map {
            $this->getName() => (Map {
                _query::fields => $this->getFields(),
                _query::edges => $this->getEdges()?->map($mv ==> $mv->__toMap()->firstValue()),
                _query::args => $this->getArguments()
            })->filterWithKey( ($mk, $mv) ==> !is_null($mv) )};
    }

}
