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