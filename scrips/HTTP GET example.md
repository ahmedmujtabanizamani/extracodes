# Required Putty or telnet or any client which can directly send messages to specified http port like 80

## HTTP Raw Requests

```
GET / HTTP/1.1
Host: www.dmasti.pk
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:122.0) Gecko/20100101 Firefox/122.0
Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8
Accept-Language: en-US,fr-FR;q=0.7,en;q=0.3
Accept-Encoding: gzip, deflate
Connection: keep-alive
Upgrade-Insecure-Requests: 1
Sec-GPC: 1
```
## Minimal
```
GET /index.php HTTP/1.0
Host: www.dmasti.pk
Accept-Encoding: gzip, deflate
Upgrade-Insecure-Requests: 1
```
## Minimal with different encoding
```
GET /wp-content/themes/new/assets/images/logo.png HTTP/1.1
Host: www.dmasti.pk
Accept-Encoding: bzr deflate
```
## Minimal with no encoding plain text response
```
GET /wp-content/themes/new/assets/js/theme-script.js HTTP/1.1
Host: www.dmasti.pk
Connection: keep-alive
```

---


## some extra useful header options
### Request
```
Accept-Encoding:gzip
```
### Response
```
Content-Encoding:gzip
```
### Request
```
Range:bytes=0-100
```
### Response
```
Accept-Ranges:bytes
Content-Length:100
```