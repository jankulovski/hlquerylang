<?hh // strict

namespace hlql\Edge;

use hlql\Query\Query;

class Edge {

    /**
     * @var EdgeName $name Edge's name
     */
    private EdgeName $name = "";

    /**
     * @var ? EdgeFields $fields Edge's fields
     */
    private ? EdgeFields $fields;

    /**
     * @var ? EdgeEdges $edges Edge's edges
     */
    private ? EdgeEdges $edges;

    /**
     * @var ? EdgeArguments $args Edge's arguments
     */
    private ? EdgeArguments $args;

    /**
     * Constructor
     *
     * @param EdgeName $name Edge's name
     * @return void
     */
    public function __construct(EdgeName $name) {
        $this->name = $name;
    }

    /**
     * Get Edge's name
     *
     * @return EdgeName
     */
    public function getName(): EdgeName {
        return $this->name;
    }

    /**
     * Get Edge's edges
     *
     * @return ? EdgeEdges
     */
    public function getEdges(): ? EdgeEdges {
        return $this->edges;
    }

    /**
     * Get Edge's fields
     *
     * @return EdgeFields
     */
    public function getFields(): ? EdgeFields {
        return $this->fields;
    }

    /**
     * Get Edge's arguments
     *
     * @return EdgeArguments
     */
    public function getArguments(): ? EdgeArguments {
        return $this->args;
    }

    /**
     * Add field
     *
     * @param EdgeField $field
     * @return void
     */
    public function addField(EdgeField $field): void {
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
     * @param EdgeArgument $argument
     * @return void
     */
    public function addArgument(EdgeArgument $argument): void {
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
