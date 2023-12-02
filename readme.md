I have written these lines in '.htaccess' file and placed the file in the htdocs folder for the router to work:
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /libgen/
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php/$1 [L]
</IfModule>