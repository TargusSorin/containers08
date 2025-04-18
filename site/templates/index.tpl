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
            <h2>{{subtitle}}</h2>
            <div class="page-content">
                {{content}}
            </div>
            <div class="metadata">
                <p>Autor: {{author}}</p>
                <p>Data publicării: {{date}}</p>
            </div>
        </div>
    </main>
    
    <footer>
        <p>&copy; {{year}} - Toate drepturile rezervate</p>
    </footer>
</body>
</html>