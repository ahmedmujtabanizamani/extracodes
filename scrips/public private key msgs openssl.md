# Openssl required

## Generate Public Private Keys

```ssh-keygen -p -f ~/.ssh/id_rsa -m pem```

## Generate keys for encript the message based on Private key

```ssh-keygen -f ~/.ssh/id_rsa -e -m PKCS8 > id_rsa.pem.pub```

## encript the message

```openssl pkeyutl -encrypt -pubin -inkey id_rsa.pem.pub -in myMessage.txt -out myEncryptedMessage.txt```

## decript message

```cat myEncryptedMessage.txt | openssl pkeyutl -decrypt -inkey .ssh/id_rsa```


### some extra hints
- BASE64 to display binary data on terminal
- Compression -> compress data
- Encryption -> process of encrypt and decrypt data
- Cyper -> Algorithm, way of encrypt/decrypt data
