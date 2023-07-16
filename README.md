# Information

Интеграция информационных сообщений в статью.

## Install

1. Загрузите папки и файлы в директорию `extensions/MW_EXT_Issue`.
2. В самый низ файла `LocalSettings.php` добавьте строку:

```php
wfLoadExtension( 'MW_EXT_Issue' );
```

## Syntax

```html
{{#issue: [TYPE-1]|[TYPE-2]|[TYPE-3]}}
```
