<!DOCTYPE html>
<html>
<head>
    <title>Swagger UI</title>
    <link href="https://unpkg.com/swagger-ui-dist/swagger-ui.css" rel="stylesheet">
</head>
<body>
<div id="swagger-ui"></div>
<script src="https://unpkg.com/swagger-ui-dist/swagger-ui-bundle.js"></script>
<script>
    SwaggerUIBundle({
        url: "{{ url('/swagger/swagger.json') }}",
        dom_id: '#swagger-ui',
    });
</script>
</body>
</html>
