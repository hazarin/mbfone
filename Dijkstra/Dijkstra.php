<?php

class Dijkstra
{
    /**
     * @var array $graph
     */
    protected $graph;

    /**
     * @var array $visited
     */
    protected $visited;

    /**
     * @var array $dp;
     */
    protected $dp;

    /**
     * @var SplStack $stack
     */
    protected $stack;

    public function __construct($graph) {
        $this->graph = [];
        foreach ($graph as $nodeAName => $item) {
            $edges = [];
            foreach ($item['EDGES'] as $nodeBName) {
                $edges[$nodeBName] = $this->calculateCost($item['COORDS'], $graph[$nodeBName]['COORDS']);
            }
            $item['EDGES'] = $edges;
            $this->graph[$nodeAName] = $item;
        }
    }

    private function calculateCost(array $sourceCoords, array $targetCoords): float
    {
        $result = sqrt(pow(($sourceCoords[0] - $targetCoords[0]), 2) + pow(($sourceCoords[1] - $targetCoords[1]), 2));

        return $result;
    }

    public function shortestPath($source, $target) {
        // массив кратчайших путей к каждому узлу
        $d = [];
        // массив "предшественников" для каждого узла
        $pi = [];
        // очередь всех неоптимизированных узлов
        $Q = new SplPriorityQueue();

        foreach ($this->graph as $v => $adj) {
            $d[$v] = INF; // устанавливаем изначальные расстояния как бесконечность
            $pi[$v] = null; // никаких узлов позади нет
            foreach ($adj['EDGES'] as $w => $cost) {
                // воспользуемся ценой связи как приоритетом
                $Q->insert($w, $cost);
            }
        }

        // начальная дистанция на стартовом узле - 0
        $d[$source] = 0;

        while (!$Q->isEmpty()) {
            // извлечем минимальную цену
            $u = $Q->extract();
            if (!empty($this->graph[$u])) {
                // пройдемся по всем соседним узлам
                foreach ($this->graph[$u]['EDGES'] as $v => $cost) {
                    // установим новую длину пути для соседнего узла
                    $alt = $d[$u] + $cost;
                    // если он оказался короче
                    if ($alt < $d[$v]) {
                        $d[$v] = $alt; // update minimum length to vertex установим как минимальное расстояние до этого узла
                        $pi[$v] = $u;  // добавим соседа как предшествующий этому узла
                    }
                }
            }
        }

        // теперь мы можем найти минимальный путь
        // используя обратный проход
        $S = new SplStack(); // кратчайший путь как стек
        $u = $target;
        $dist = 0;
        // проход от целевого узла до стартового
        while (isset($pi[$u]) && $pi[$u]) {
            $S->push($u);
            $dist += $this->graph[$u]['EDGES'][$pi[$u]]; // добавим дистанцию для предшествующих
            $u = $pi[$u];
        }

        // стек будет пустой, если нет пути назад
        if ($S->isEmpty()) {
            echo "Нет пути из $source в $targetn";
        }
        else {
            // добавим стартовый узел и покажем весь путь
            // в обратном (LIFO) порядке
            $S->push($source);
            echo "$dist:";
            $sep = '';
            foreach ($S as $v) {
                echo $sep, $v;
                $sep = '->';
            }
            echo "\n";
        }
    }

    public function dfs(string $name)
    {
        $this->visited[$name] =  true;

        foreach ($this->graph[$name]['EDGES'] as $key => $item ) {
            if($this->visited[$key] == false) {
                $this->dfs($key);
            }
            $this->dp[$name] = max($this->dp[$name], $this->dp[$key] + $item);
        }
    }

    public function findLongestPath()
    {
        $this->dp = array_fill_keys(array_keys($this->graph), 0);
        $this->visited = array_fill_keys(array_keys($this->graph), false);
        foreach ($this->graph as $name => $item) {
            if (!$this->visited[$name]) {
                $this->dfs($name);
            }
        }
        $max = 0;
        foreach ($this->dp as $name => $item) {
            $max = max($max, $item);
        }
        echo $max;
    }

    public function topologicalSort(string $name): void
    {
        $this->visited[$name] = true;
        foreach ($this->graph[$name]['EDGES'] as $key => $item)
        {
            if ($this->visited[$key] === false) {
                $this->topologicalSort($key);
            }
        }

        $this->stack->push($name);
    }

    public function longestPath(string $name)
    {
        $this->stack = new SplStack();
        $this->dp = array_fill_keys(array_keys($this->graph), -INF);
        $this->visited = array_fill_keys(array_keys($this->graph), false);

        foreach ($this->graph as $key => $item)
        {
            if($this->visited[$key] === false) {
                $this->topologicalSort($key);
            }
        }

        $this->dp[$name] = 0;

        while ($this->stack->isEmpty() === false) {
            $current = $this->stack->pop();
            if ($this->dp[$current] !== -INF) {
                foreach ($this->graph[$current]['EDGES'] as $key => $item) {
                    if ($this->dp[$key] < $this->dp[$current] + $item) {
                        $this->dp[$key] = $this->dp[$current] + $item;
                    }
                }
            }
        }

        echo 'Longest path: ';
        foreach ($this->dp as $key => $item)
        {
            echo $key .':'.$item.' ';
        }
        echo "\n";

    }
}

$graph = [
    'A' => [
        'COORDS' => [1, 1],
        'EDGES' => [
            'B',
            'C',
            'F',
        ]
    ],
    'B' => [
        'COORDS' => [1, 5],
        'EDGES' => [
            'A',
            'C',
            'D',
        ]
    ],
    'C' => [
        'COORDS' => [5, 5],
        'EDGES' => [
            'A',
            'B',
            'D',
            'E',
        ]
    ],
    'D' => [
        'COORDS' => [10, 1],
        'EDGES' => [
            'B',
            'C',
            'E',
        ]
    ],
    'E' => [
        'COORDS' => [10, 10],
        'EDGES' => [
            'D',
            'C'
        ]
    ],
    'F' => [
        'COORDS' => [15, 27],
        'EDGES' => [
            'A',
            'C',
            'E',
        ]
    ],
];

$dijkstra = new Dijkstra($graph);
// Dijkstra
$dijkstra->shortestPath('E', 'A');
// DFS
$dijkstra->findLongestPath();
// Topological
$dijkstra->longestPath('A');
