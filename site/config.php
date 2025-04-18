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
        
        $db->exec("CREATE TABLE IF NOT EXISTS page (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            subtitle TEXT,
            content TEXT,
            author TEXT,
            date TEXT,
            year TEXT
        )");
        
        $db->exec("INSERT INTO page (title, subtitle, content, author, date, year) 
            VALUES ('Bine ați venit', 'Pagina de start', 
            '<p>Aceasta este o pagină de exemplu pentru a demonstra funcționalitatea aplicației.</p>
            <p>Editați conținutul paginii pentru a personaliza site-ul dumneavoastră.</p>', 
            'Administrator', '17 Aprilie 2025', '2025')");
            
    } catch (PDOException $e) {
        die("Eroare la crearea bazei de date: " . $e->getMessage());
    }
}