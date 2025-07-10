## Post Request
```
POST %/Path/To/File% HTTP/1.1
Accept: */*
Accept-Encoding: gzip, deflate, br
Accept-Language: en-GB,en;q=0.9,en-US;q=0.8
Cache-Control: no-cache
Connection: keep-alive
Content-Length: 25
Content-Type: application/x-www-form-urlencoded; charset=UTF-8
Cookie: %Cookie%
Host: %Host%
Origin: %Origin%
Pragma: no-cache
Referer: %Referer%
Sec-Fetch-Dest: empty
Sec-Fetch-Mode: cors
Sec-Fetch-Site: same-origin
User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36 Edg/121.0.0.0
X-Requested-With: XMLHttpRequest
sec-ch-ua: "Not A(Brand";v="99", "Microsoft Edge";v="121", "Chromium";v="121"
sec-ch-ua-mobile: ?0
sec-ch-ua-platform: "Windows"

%URL-ENCODED-DATA like this -> CheckState=0&AdminId=1049%
```

## Response
```
HTTP/1.1 200 OK
Date: Tue, 13 Feb 2024 06:07:41 GMT
Server: Apache/2.4.33 (Win32) OpenSSL/1.1.0h PHP/7.2.7
X-Powered-By: PHP/7.2.7
Access-Control-Allow-Origin: *
Content-Length: 7
Keep-Alive: timeout=5, max=100
Connection: Keep-Alive
Content-Type: text/html; charset=UTF-8

success
```

## Minimal request
```
POST %/Path/To/File% HTTP/1.1
Host: %Host Domain%
Content-Type: application/x-www-form-urlencoded; charset=UTF-8
Content-Length: 25

%URL-ENCODED-DATA like this --> CheckState=0&AdminId=1049%
```
## same request using CURL, but curl also support HTTPS

```curl %URL Host + Path% -X "POST" -H "Content-Type:application/x-www-form-urlencoded" -d "CheckState=0&AdminId=1049"```