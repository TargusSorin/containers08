1) Am creat directorul containers08, în care am creat directorul site.
2) În folderul site am creat fișierul modules/database.php, în care am creat clasa Database cu parametrul connection.
3) În clasă am creat un constructor:
```
public function __construct($path) {
        try {
            $this->connection = new PDO("sqlite:$path");
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Eroare la conectarea la baza de date: " . $e->getMessage());
        }
    }
```
4) În clasă am creat funcția Execute:
```
public function Execute($sql) {
        try {
            return $this->connection->exec($sql);
        } catch (PDOException $e) {
            die("Eroare la executarea interogării: " . $e->getMessage());
        }
    }
```
5) În clasă am creat funcția Fetch:
```
public function Fetch($sql) {
        try {
            $stmt = $this->connection->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Eroare la preluarea datelor: " . $e->getMessage());
        }
    }
```
6) În clasă am creat funcția Create:
```
public function Create($table, $data) {
        try {
            $fields = implode(", ", array_keys($data));
            $placeholders = ":" . implode(", :", array_keys($data));
            
            $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";
            $stmt = $this->connection->prepare($sql);
            
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            
            $stmt->execute();
            return $this->connection->lastInsertId();
        } catch (PDOException $e) {
            die("Eroare la crearea înregistrării: " . $e->getMessage());
        }
    }
```
7) În clasă am creat funcția Read:
```
public function Read($table, $id) {
        try {
            $sql = "SELECT * FROM $table WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(":id", $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Eroare la citirea înregistrării: " . $e->getMessage());
        }
    }
```
8) În clasă am creat funcția Update:
```
public function Update($table, $id, $data) {
        try {
            $fields = [];
            foreach (array_keys($data) as $key) {
                $fields[] = "$key = :$key";
            }
            
            $fieldString = implode(", ", $fields);
            $sql = "UPDATE $table SET $fieldString WHERE id = :id";
            
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(":id", $id);
            
            foreach ($data as $key => $value) {
                $stmt->bindValue(":$key", $value);
            }
            
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Eroare la actualizarea înregistrării: " . $e->getMessage());
        }
    }
```
9) În clasă am creat funcția Delete:
```
public function Delete($table, $id) {
        try {
            $sql = "DELETE FROM $table WHERE id = :id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindValue(":id", $id);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            die("Eroare la ștergerea înregistrării: " . $e->getMessage());
        }
    }
```
10) În clasă am creat funcția Count:
```
public function Count($table) {
        try {
            $sql = "SELECT COUNT(*) as count FROM $table";
            $stmt = $this->connection->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return (int)$result['count'];
        } catch (PDOException $e) {
            die("Eroare la numărarea înregistrărilor: " . $e->getMessage());
        }
    }
```
11) În folderul modules am creat fișierul page.php care deține următoarea clasă:
```
class Page {
    private $template;

    public function __construct($template) {
        if (!file_exists($template)) {
            die("Șablonul $template nu există!");
        }
        $this->template = $template;
    }

    public function Render($data) {
        $content = file_get_contents($this->template);
        
        if (!$content) {
            die("Nu s-a putut citi șablonul!");
        }
        
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $content = str_replace("{{" . $key . "}}", $value, $content);
            }
        }
        
        return $content;
    }
}
```
12) În directorul site am creat fișierul templates/index.tpl cu următorul șablon:
```
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{title}}</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <header>
        <h1>{{title}}</h1>
    </header>
    
    <main>
        <div class="content">
            <div class="page-content">
                {{content}}
            </div>
        </div>
    </main>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> - Toate drepturile rezervate</p>
    </footer>
</body>
</html>
```
13) În folderul site am creat fișierul styles/style.css cu următorul conținut:
```
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    color: #333;
    background-color: #f4f4f4;
}

header {
    background-color: #4a6fa5;
    color: white;
    text-align: center;
    padding: 1rem;
}

main {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
}

.content {
    background-color: white;
    padding: 25px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    font-size: 2em;
    margin-bottom: 10px;
}

h2 {
    color: #4a6fa5;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid #e6e6e6;
}

.page-content {
    margin-bottom: 30px;
    line-height: 1.8;
}

.metadata {
    font-size: 0.9em;
    color: #777;
    border-top: 1px solid #e6e6e6;
    padding-top: 15px;
}

footer {
    text-align: center;
    padding: 20px;
    background-color: #333;
    color: white;
    margin-top: 30px;
}

@media (max-width: 768px) {
    main {
        padding: 10px;
    }
    
    .content {
        padding: 15px;
    }
}
```
14) În folderul site am creat fișierul index.php cu următorul conținut:
```
<?php

require_once __DIR__ . '/modules/database.php';
require_once __DIR__ . '/modules/page.php';

require_once __DIR__ . '/config.php';

$db = new Database($config["db"]["path"]);

$page = new Page(__DIR__ . '/templates/index.tpl');

$pageId = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($pageId < 1) {
    $pageId = 1;
}

$data = $db->Read("page", $pageId);

if (!$data) {
    $data = [
        'title' => 'Pagină negăsită',
        'content' => '<p>Pagina solicitată nu există.</p>'
    ];
}

echo $page->Render($data);
```
15) Tot în folderul site am creat fișierul config.php cu următorul conținut pentru conexiunea la baza de date:
```
<?php

$config = [
    "db" => [
        "path" => __DIR__ . '/database.sqlite'
    ],
    "site" => [
        "title" => "Site-ul meu PHP",
        "description" => "O aplicație web simplă bazată pe PHP și SQLite"
    ]
];

if (!file_exists($config["db"]["path"])) {
    try {
        $db = new PDO("sqlite:" . $config["db"]["path"]);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $sqlSchema = file_get_contents(__DIR__ . '/../sql/schema.sql');
        $db->exec($sqlSchema);
            
    } catch (PDOException $e) {
        die("Eroare la crearea bazei de date: " . $e->getMessage());
    }
}
```
16) În directorul site am creat fișierul sql/schema.sql cu următorul cod SQL:
```
CREATE TABLE page (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT,
    content TEXT
);

INSERT INTO page (title, content) VALUES ('Page 1', 'Content 1');
INSERT INTO page (title, content) VALUES ('Page 2', 'Content 2');
INSERT INTO page (title, content) VALUES ('Page 3', 'Content 3');
```